<!DOCTYPE html>
<html>
    <head>
        <title>Assessment</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>

    <body>
        <a href="./facultyHome.php">Home</a><br/>
        <?php 
            session_start();
            require_once './settings/connection.php';
            $conn = $GLOBALS['conn'];   

            $sql = "SELECT cd.courseName as cName,sd.studentName as sName,sd.studentEmail as sMail , sd.studentId as sId,cd.courseId as cId ,sd.studentGR as GR_NO,sd.studentName as Name,cd.minimumScore as minScore,ed.score as score FROM studentdetails sd,coursedetails cd,enrollmentdetails ed WHERE ed.completed = 1 AND (cd.courseId = ed.courseId) AND (sd.studentId = ed.studentId) AND cd.courseId = ".$_GET["cid"];
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                echo "<table class='table'>";
                    echo "<thead class='thead-dark'>";
                    echo "<tr align='center'>";
                        echo "<td colspan=4><b>Assessment</b></td>";
                    echo "</tr>";
                    echo "<tr align='center'>";
                        echo "<th>GR Number</th>";
                        echo "<th>Name</th>";
                        echo "<th>Score</th>";
                        echo "<th>Operation</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                while($row = $result->fetch_assoc()){
                    $cid = $row['cId'];
                    $sid = $row['sId'];
                    $gr = $row['GR_NO'];
                    $name = $row['Name'];
                    $score = $row['score']." / ".$row['minScore'];
                    $mail = $row['sMail'];
                    $cName = $row['cName'];
                    echo "<tr align='center'>";
                        echo "<td>$gr</td>";
                        echo "<td>$name</td>";
                        echo "<td>$score</td>";
                        echo "<td><a><button class='btn btn-info'>Report</button></a></td>";
                    echo "</tr>";
                    
                }
                echo "</tbody>";
                echo "</table><br/><br/><br/>";
            }
            else{
                echo "No assessment work...";
            }
        ?>
    </body>
</html>