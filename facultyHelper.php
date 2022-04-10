<?php 
session_start();
require_once './settings/connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once './include/' . 'Exception.php';
require_once './include/' . 'PHPMailer.php';
require_once './include/' . 'SMTP.php';
require_once './settings/mailCredentials.php';

function addCourse($cName,$minScore,$facultyId,$conn){
    $sql = "INSERT INTO courseDetails(courseName,minimumScore,facultyId,status) VALUES('$cName',$minScore,$facultyId,1)";
    if($conn->query($sql)){
        header('Location: ./manageCourse.php');
    }
    else{
        echo $conn->error;
    }
}

function updateCourse($cId,$cName,$minScore,$facultyId,$conn){
    $sql = "UPDATE courseDetails SET courseName = '$cName',minimumScore = $minScore,facultyId = $facultyId WHERE courseId = ".$cId;
    if($conn->query($sql)){
        header('Location: ./manageCourse.php');
    }
    else{
        echo $conn->error;
    }
}

function deleteCourse($cId,$conn){
    $sql = "UPDATE courseDetails SET status = 0 WHERE courseId = ".$cId;
    if($conn->query($sql)){
        header('Location: ./manageCourse.php');
    }
    else{
        echo $conn->error;
    }
}

function addVideo($name,$link,$cid,$conn){
    $sql = "SELECT * FROM videoDetails WHERE status = 1 AND courseId = ".$cid;
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $sql = "SELECT MAX(sequence) as sequence FROM videoDetails WHERE status = 1 AND courseId = ".$cid;
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $sequence = $row['sequence'];
        $sequence = $sequence+1;

        $sql = "INSERT INTO videoDetails(videoTitle,link,courseId,sequence,status) VALUES('$name','$link',$cid,$sequence,1)";
        if($conn->query($sql)){
            header("Location: ./manageVideos.php?cid=$cid");
        }
        else{
            echo $conn->error;
        }
    }
    else{
        $sql = "INSERT INTO videoDetails(videoTitle,link,courseId,sequence,status) VALUES('$name','$link',$cid,1,1)";
        if($conn->query($sql)){
            header("Location: ./manageVideos.php?cid=$cid");
        }
        else{
            echo $conn->error;
        }
    }
}

function updateVideo($id,$name,$cid,$link,$conn){
    $sql = "UPDATE videoDetails SET videoTitle = '$name',link = '$link' WHERE videoId = $id";
    if($conn->query($sql)){
        header("Location: ./manageVideos.php?cid=$cid");
    }
    else{
        echo $conn->error;
    }
}

function deleteVideo($id,$cid,$conn){
    $sql = "UPDATE videoDetails SET status = 0 WHERE videoId = $id";
    if($conn->query($sql)){
        header("Location: ./manageVideos.php?cid=$cid");
    }
    else{
        echo $conn->error;
    }
}

function addQuiz($ques,$a,$b,$c,$d,$ans,$time,$vId,$conn){
    $sql = "INSERT INTO quizdetails(question,optionA,optionB,optionC,optionD,answer,time,videoId,status) VALUES('$ques','$a','$b','$c','$d','$ans',$time,$vId,1)";
    if($conn->query($sql)){
        header("Location: ./manageQuiz.php?vidId=$vId");
    }
    else{
        $err = $conn->error;
        header("Location: ./manageQuiz.php?vidId=$vId&error=$err");
    }
}

function updateQuiz($quesId,$ques,$a,$b,$c,$d,$ans,$time,$vId,$conn){
    $sql = "UPDATE quizdetails SET question='$ques',optionA='$a',optionB='$b',optionC='$c',optionD='$d',answer='$ans',time=$time WHERE questionId = $quesId";
    if($conn->query($sql)){
        header("Location: ./manageQuiz.php?vidId=$vId");
    }
    else{
        echo $conn->error;
    }
}

function deleteQuiz($quesId,$vId,$conn){
    $sql = "UPDATE quizDetails SET status = 0 WHERE questionId = $quesId";
    if($conn->query($sql)){
        header("Location: ./manageQuiz.php?vidId=$vId");
    }
    else{
        echo $conn->error;
    }
}

function assignCourse($sId,$cId,$name,$mail,$conn){
    $cNameQuery = "SELECT courseName FROM courseDetails WHERE courseId = $cId";
    $result = $conn->query($cNameQuery);
    $row = $result->fetch_assoc();
    $cName = $row["courseName"];
    $subject="Course Assigned";
    $body="This mail is to inform you that you have been assigned the course $cName which you have to complete.";
    $sql = "INSERT INTO enrollmentdetails VALUES($cId,$sId,0,0)";
    if($conn->query($sql)){
        sendMail($mail,$name,$subject,$body);
        header("Location: ./assignCourse.php?cid=$cId&assigned=1");
    }
    else{
        $err = $conn->error;
        header("Location: ./assignCourse.php?cid=$cId&error=$err");
    }
    
}

function reassignCourse($sId,$cId,$stdName,$mail,$cName,$conn){
    $subject = "Course Re-Assigned";
    $body = "This mail is to inform you that you have not properly completed the course $cName. Hence the course has been reassigned to you.";
    $sql = "UPDATE enrollmentdetails SET completed = 0 WHERE courseId = $cId AND studentId = $sId";

    $getVideoList = "SELECT videoId FROM videoDetails WHERE courseId = $cId";

    if($conn->query($getVideoList)){
        
    }

    $deleteWatchedVideos = "DELETE FROM watchedVideos WHERE videoId ";
    $deleteAttemptedQuiz = "";
    if($conn->query($sql)){
        sendMail($mail,$stdName,$subject,$body);
        header("Location: ./assessment.php?cid=$cId");
    }
    else{
        echo $conn->error;
    }
}

function completeCourse($sId,$cId,$stdName,$mail,$cName,$conn){
    $subject = "Course Completed";
    $body = "This mail is to inform you that you have successfully completed the course $cName. You can see the certificate by going to your homepage and clicking on the certificate button next to the course.";
    $sql = "UPDATE enrollmentdetails SET completed = 1 WHERE courseId = $cId AND studentId = $sId";
    if($conn->query($sql)){
        sendMail($mail,$stdName,$subject,$body);
        header("Location: ./assessment.php?cid=$cId");
    }
    else{
        echo $conn->error;
    }
}

function sendMail($receiverMail,$receiverName,$subject,$body){
    // passing true in constructor enables exceptions in PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER; // for detailed debug output
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->Username = $GLOBALS['email']; // YOUR gmail email
        $mail->Password = $GLOBALS['pass']; // YOUR gmail password

        // Sender and recipient settings
        $mail->setFrom($GLOBALS['email'], 'MOOC Portal');
        $mail->addAddress($receiverMail, $receiverName);

        // Setting the email content
        $mail->IsHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = 'Plain text message body for non-HTML email client. Gmail SMTP email body.';


        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->send();
        echo "Email message sent.";
    } 
    catch (Exception $e) {
        echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
    }
}

if(isset($_POST['addCourse'])){
    $cname = $_POST['cName'];
    $minScore = $_POST['minScore'];
    addCourse($cname,$minScore,$_SESSION['facultyid'],$GLOBALS['conn']);
}
else if(isset($_POST['updateCourse'])){
    $cname = $_POST['cName'];
    $minScore = $_POST['minScore'];
    $cId = $_POST['cid'];
    updateCourse($cId,$cname,$minScore,$_SESSION['facultyid'],$GLOBALS['conn']);
}
else if(isset($_GET['op'])){
    deleteCourse($_GET['id'],$GLOBALS['conn']);
}
else if(isset($_POST['addVideo'])){
    $name = $_POST['videoName'];
    $link = $_POST['videoLink'];
    $cid = $_POST['courseId'];
    addVideo($name,$link,$cid,$GLOBALS['conn']);
}

else if(isset($_POST['updateVideo'])){
    $id = $_POST['videoId'];
    $name = $_POST['videoName'];
    $link = $_POST['videoLink'];
    $cid = $_POST['courseId'];
    updateVideo($id,$name,$cid,$link,$GLOBALS['conn']);
}
else if(isset($_GET['delVideo'])){
    deleteVideo($_GET['id'],$_GET['cid'],$GLOBALS['conn']);
}

else if(isset($_POST['addQuiz'])){
    $ques = $_POST['question'];
    $a = $_POST['a'];
    $b = $_POST['b'];
    $c = $_POST['c'];
    $d = $_POST['d'];
    $ans = $_POST['answer'];
    $time = $_POST['time'];
    $vId = $_POST['vidId'];
    addQuiz($ques,$a,$b,$c,$d,$ans,$time,$vId,$GLOBALS['conn']);
}

else if(isset($_POST['updateQuiz'])){
    $quesId = $_POST['quesId']; 
    $ques = $_POST['question'];
    $a = $_POST['a'];
    $b = $_POST['b'];
    $c = $_POST['c'];
    $d = $_POST['d'];
    $ans = $_POST['answer'];
    $time = $_POST['time'];
    $vId = $_POST['vidId'];
    updateQuiz($quesId,$ques,$a,$b,$c,$d,$ans,$time,$vId,$GLOBALS['conn']);
}

else if(isset($_GET['delQuiz'])){
    $quesId = $_GET['id'];
    $vidId = $_GET['vidId'];
    deleteQuiz($quesId,$vidId,$GLOBALS['conn']);
}

else if(isset($_GET['assign'])){
    $sId = $_GET['sId'];
    $cId = $_GET['cId'];
    $mail = $_GET['sEmail'];
    $name = $_GET['sName'];
    assignCourse($sId,$cId,$name,$mail,$GLOBALS['conn']);
}

else if(isset($_GET['reassign'])){
    $sId = $_GET['sid'];
    $cId = $_GET['cid'];
    $mail = $_GET['mail'];
    $sName = $_GET['sName'];
    $cName = $_GET['cName'];
    reassignCourse($sId,$cId,$sName,$mail,$cName,$GLOBALS['conn']);
}
else if(isset($_GET['complete'])){
    $sId = $_GET['sid'];
    $cId = $_GET['cid'];
    $mail = $_GET['mail'];
    $sName = $_GET['sName'];
    $cName = $_GET['cName'];
    completeCourse($sId,$cId,$sName,$mail,$cName,$GLOBALS['conn']);
}

else if(isset($_GET["unassign"])){
    $sId = $_GET['sId'];
    $cId = $_GET['cId'];
    assignCourse($sId,$cId,$GLOBALS['conn']);
}

?>