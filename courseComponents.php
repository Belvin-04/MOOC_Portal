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
                $sql = "UPDATE enrollmentdetails SET completed = 2 ,dateCompleted = DATE_FORMAT(CURRENT_DATE(),'%d-%m-%Y') WHERE studentId = ".$_SESSION["studentid"]." AND courseId = ".$_GET["cid"];
                if($conn->query($sql)){
                    
                    header("Location: ./studentHome.php");
                }
                else{
                    echo $conn->error;
                }
            }
        ?>
    </body>
</html>