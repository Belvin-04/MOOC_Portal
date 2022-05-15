<!DOCTYPE html>
<html>
    <head><title>Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
    <body>
        <a href="./studentHome.php">HOME</a>
        <?php 
            session_start();
            require_once "./settings/connection.php";
            
            $sql = "SELECT * FROM videoDetails WHERE status = 1 AND courseId = ".$_GET['cid']." AND videoId NOT IN (SELECT videoId FROM watchedVideos WHERE studentId = ".$_SESSION['studentid'].") ORDER BY sequence";
            $conn = $GLOBALS['conn'];
            $cid = $_GET["cid"];
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                echo "<table class='table'>";
                    echo "<thead class='thead-dark'>";
                        echo "<tr align='center'>";
                            echo "<th>Video</th>";
                            echo "<th>Operation</th>";
                        echo "</tr>";
                    echo "<thead>";
                    while($row = $result->fetch_assoc()){
                        $vId = $row['videoId'];
                        $title = $row['videoTitle'];
                        $link = $row['link'];
                        $link = explode("watch?v=",$link)[1];
                        echo "<tr align='center'>";
                            echo "<td>$title</td>";
                            echo "<td><a href='./courseVideo.php?videoLinkId=$link&vId=$vId&cId=$cid'><button class='btn btn-primary'>Watch Video</button></a></td>";
                        echo "</tr>";
                    }
                echo "</table>";
            }
            else{
                $sql = "SELECT * FROM `enrollmentdetails` ed,`coursedetails` cd WHERE (ed.courseId = cd.courseId) AND (ed.score >= cd.minimumScore) AND (ed.studentId=".$_SESSION['studentid']." AND ed.courseId = ".$_GET['cid'].")";
                $result = $conn->query($sql);

                $studentDetailsQuery = "SELECT cd.courseName as cName,sd.studentName as sName,sd.studentEmail as sMail , sd.studentId as sId,cd.courseId as cId ,sd.studentGR as GR_NO,sd.studentName as Name,cd.minimumScore as minScore,ed.score as score FROM studentdetails sd,coursedetails cd,enrollmentdetails ed WHERE (cd.courseId = ed.courseId) AND (sd.studentId = ed.studentId) AND cd.courseId = ".$_GET['cid'];
                $result1 = $conn->query($studentDetailsQuery);
                $row = $result1->fetch_assoc();
                $cid = $row['cId'];
                $sid = $row['sId'];
                $name = $row['Name'];
                $mail = $row['sMail'];
                $cName = $row['cName'];

                if($result->num_rows > 0){
                    //complete
                    header("Location: ./facultyHelper.php?cid=$cid&sid=$sid&sName=$name&mail=$mail&cName=$cName&complete=1");
                }
                else{
                    //reassign
                    header("Location: ./facultyHelper.php?cid=$cid&sid=$sid&sName=$name&mail=$mail&cName=$cName&reassign=1");
                }
            }
        ?>
    </body>
</html>