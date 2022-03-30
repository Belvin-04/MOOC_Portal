<!DOCTYPE html>
<html>
    <head>
        <title>Student Home</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>

    <body>
    <a href="https://accounts.google.com/logout">Logout</a>
        <?php 
            session_start();
            if(isset($_SESSION['loggedin'])){
                if($_SESSION['loggedin'] == 1){
                    require_once './settings/connection.php';
                    $conn = $GLOBALS['conn'];   

                    $sql = "SELECT c.courseName cName,e.courseId cId FROM enrollmentdetails e,coursedetails c WHERE (e.courseId=c.courseId) AND (studentId = ".$_SESSION['studentid']." AND completed = 0)";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        echo "<table class='table'>";
                            echo "<thead class='thead-dark'>";
                            echo "<tr>";
                                echo "<td colspan=3 align='center'><b>Pending Courses</b></td>";
                            echo "</tr>";
                            echo "<tr>";
                                echo "<th>Course Name</th>";
                                echo "<th>Course Id</th>";
                                echo "<th>Operations</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                        while($row = $result->fetch_assoc()){
                            $courseName = $row['cName'];
                            $courseId = $row['cId'];
                            echo "<tr>";
                                echo "<td>$courseName</td>";
                                echo "<td>$courseId</td>";
                                echo "<td><a href='./courseComponents.php?cid=$courseId'><button class='btn btn-primary'>Go to Course</button></a></td>";
                            echo "</tr>";
                            
                        }
                        echo "</tbody>";
                        echo "</table><br/><br/><br/>";
                    }


                    $sql = "SELECT c.courseName cName,e.courseId cId FROM enrollmentdetails e,coursedetails c WHERE (e.courseId=c.courseId) AND (studentId = ".$_SESSION['studentid']." AND completed = 1)";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        echo "<table class='table'>";
                            echo "<thead class='thead-dark'>";
                            echo "<tr>";
                                echo "<td colspan=3 align='center'><b>Completed Courses</b></td>";
                            echo "</tr>";
                            echo "<tr>";
                                echo "<th>Course Name</th>";
                                echo "<th>Course Id</th>";
                                echo "<th>Operations</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                        while($row = $result->fetch_assoc()){
                            $courseName = $row['cName'];
                            $courseId = $row['cId'];
                            echo "<tr>";
                                echo "<td>$courseName</td>";
                                echo "<td>$courseId</td>";
                                echo "<td><a href='./certificate.php?cid=$courseId'><button class='btn btn-primary'>Certificate</button></a></td>";
                            echo "</tr>";
                            
                        }
                        echo "</tbody>";
                        echo "</table><br/><br/><br/>";
                    }

                    $sql = "SELECT c.courseName cName,e.courseId cId FROM enrollmentdetails e,coursedetails c WHERE (e.courseId=c.courseId) AND (studentId = ".$_SESSION['studentid']." AND completed = 2)";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        echo "<table class='table'>";
                            echo "<thead class='thead-dark'>";
                            echo "<tr>";
                                echo "<td colspan=2 align='center'><b>Courses under review</b></td>";
                            echo "</tr>";
                            echo "<tr>";
                                echo "<th>Course Name</th>";
                                echo "<th>Course Id</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                        while($row = $result->fetch_assoc()){
                            $courseName = $row['cName'];
                            $courseId = $row['cId'];
                            echo "<tr>";
                                echo "<td>$courseName</td>";
                                echo "<td>$courseId</td>";
                            echo "</tr>";
                            
                        }
                        echo "</tbody>";
                        echo "</table>";
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