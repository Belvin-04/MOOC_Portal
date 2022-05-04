<?php 
    require_once "./settings/connection.php";
    session_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    require_once './include/' . 'Exception.php';
    require_once './include/' . 'PHPMailer.php';
    require_once './include/' . 'SMTP.php';
    require_once './settings/mailCredentials.php';
    require_once './include/TCPDF/tcpdf.php';
    require "vendor/autoload.php";
    use Dompdf\Dompdf;

    

    function increaseScore($cid,$sid,$conn){
        $sql = "SELECT score FROM enrollmentdetails WHERE courseId = $cid AND studentId = $sid";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $score = $row["score"];
        $score = $score+1;

        $sql = "UPDATE enrollmentdetails SET score = $score WHERE courseId = $cid AND studentId = $sid";
        if($conn->query($sql)){
            echo "Score Updated";
        }
        else{
            echo $conn->error;
        }
    }

    function updateAttemptedQuiz($cid,$sid,$qid,$conn){
        $sql = "SELECT * FROM attemptedquizs WHERE courseId = $cid AND studentId = $sid AND questionId = $qid";
        
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $sql = "INSERT INTO attemptedquizs VALUES($cid,$sid,$qid)";
            if($conn->query($sql)){
                echo "Updated";
            }
            else{
                echo $conn->error;
            }
        }
    }

    function checkIfAttempted($cid,$sid,$qid,$conn){
        $sql = "SELECT * FROM attemptedquizs WHERE courseId = $cid AND studentId = $sid AND questionId = $qid";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return 1;
        }
        else{
            return 0;
        }
    }

    function updateWatchedVideos($vId,$sId,$conn){
        $sql = "INSERT INTO watchedvideos VALUES($vId,$sId)";
        if($conn->query($sql)){
            echo "Updated";
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

    function downloadPdf($contents){
        $dompdf = new DOMPDF();
        $dompdf->loadHtml($contents);
        $dompdf->render();
        $dompdf->stream();
        
    }

    if(isset($_POST["checkAnswer"])){
        $selectedAns = $_POST["selectedAnswer"];
        $realAns = $_POST["realAnswer"];

        if(strtoupper($selectedAns) == strtoupper($realAns)){
            $cid = $_POST["cId"];
            $sid = $_SESSION["studentid"];
            $quesId = $_POST["quesId"];
            
            if(checkIfAttempted($cid,$sid,$quesId,$GLOBALS["conn"]) == 0){
                increaseScore($cid,$sid,$GLOBALS["conn"]);
            }
        }
    }

    if(isset($_POST["updateAttempt"])){
        $cid = $_POST["cId"];
        $sid = $_SESSION["studentid"];
        $quesId = $_POST["quesId"];
        updateAttemptedQuiz($cid,$sid,$quesId,$GLOBALS["conn"]);
    }

    if(isset($_POST["updateWatchedVideo"])){
        $vId = $_POST["vId"];
        $sId = $_SESSION["studentid"];
        updateWatchedVideos($vId,$sId,$GLOBALS["conn"]);
    }
    if(isset($_GET["download"])){
        $s = $_GET["s"];
        $c = $_GET["c"];
        $f = $_GET["f"];
        $d = $_GET["d"];
        $contents = "<html style='margin: 0;
        padding: 0;'>
        <head>
        </head>
        <body style='color: black;
        display: table;
        font-family: Georgia, serif;
        font-size: 24px;
        text-align: center;
        margin: 0;
        padding: 0;'>
            <div style='border: 20px solid tan;
        width: 750px;
        height: 563px;
        vertical-align: middle;'>
                <div style='
                margin-top:10%;
                color: tan;'>
                    Marwadi University
                </div>
    
                <div style='color: tan;
        font-size: 48px;
        margin: 20px;'>
                    Certificate of Completion
                </div>
    
                <div style='margin: 20px;'>
                    This certificate is presented to
                </div>
    
                <div style='border-bottom: 2px solid black;
        font-size: 32px;
        font-style: italic;
        margin: 20px auto;
        width: 400px;'>
                    <span id='studentName'>$s</span>
                </div>
    
                <div style='margin: 20px;'>
                For successfully completing the course <b><span id='courseName'>$c</span></b><br/><br/>
                Under the Guidance of <b><span id='facultyName'>$f</span></b><br/><br/>
            On <b><span id='dateCompleted'>$d</span></b>
                </div>
            </div>
        </body>
    </html>";
        downloadPdf($contents);
    }
?>