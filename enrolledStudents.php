<!DOCTYPE html>
<html>
    <head>
        <title>Student Enrolled</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>

    <body>
    <a href="./facultyHome.php">Home</a>
        <?php 
            session_start();
            if(isset($_SESSION['loggedin'])){
                if($_SESSION['loggedin'] == 1){
                    require_once './settings/connection.php';
                    $conn = $GLOBALS['conn'];   

                    $sql = "SELECT sd.studentId as sId,sd.studentGR as gr,sd.studentName as name FROM studentDetails sd,enrollmentDetails ed WHERE (sd.studentId = ed.studentId) AND ed.courseId = ".$_GET["cid"];
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        echo "<table class='table'>";
                            echo "<thead class='thead-dark'>";
                            echo "<tr align='center'>";
                                echo "<td colspan=3><b>Students Enrolled</b></td>";
                            echo "</tr>";
                            echo "<tr align='center'>";
                                echo "<th>Student GR</th>";
                                echo "<th>Student Name</th>";
                                echo "<th></th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                        while($row = $result->fetch_assoc()){
                            $sId = $row['sId'];
                            $gr = $row['gr'];
                            $name = $row['name'];
                            $cId = $_GET["cid"];
                            echo "<tr align='center'>";
                                echo "<td>$gr</td>";
                                echo "<td>$name</td>";
                                echo "<td><a href='./report.php?cid=$cId&sid=$sId'><button class='btn btn-primary'>Report</button></a></td>";
                            echo "</tr>";
                            
                        }
                        echo "</tbody>";
                        echo "</table><br/><br/><br/>";
                    }
                    else{
                        echo "No enrollmets in this course...";
                    }
                }
                else{
                    header('Location: ./index.html');
                }    
            }
            else{
                header('Location: ./index.html');
            }
        ?>
    </body>
</html>