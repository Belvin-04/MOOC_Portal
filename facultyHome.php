<!DOCTYPE html>
<html>
    <head><title>Faculty Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>

    <body>
        <a href="https://accounts.google.com/logout">Logout</a>
        <form method="post" action="manageCourse.php">
            <input type="submit" name="create" value="Manage Course"/>
        </form>
        <?php 
            session_start();
            require_once './settings/connection.php';
            $conn = $GLOBALS['conn'];
            if(isset($_SESSION['loggedin'])){
                if($_SESSION['loggedin'] == 1){
                    $sql = "SELECT * FROM courseDetails WHERE status = 1 AND facultyId = ".$_SESSION['facultyid'];
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        echo "<table class='table'>";
                        echo "<thead class='thead-dark'>";
                        echo "<tr align='center'>";
                                echo "<td>CourseId</td>";
                                echo "<td>CourseName</td>";
                                echo "<td>Minimum Score</td>";
                                echo "<td>Operations</td>";
                        echo "</tr>";
                        echo "</thead>";
                        while($row = $result->fetch_assoc()){
                            $cId = $row['courseId'];
                            $cName = $row['courseName'];
                            $cMinScore = $row['minimumScore'];
                            
                            echo "<tr align='center'>";
                                echo "<td>$cId</td>";
                                echo "<td>$cName</td>";
                                echo "<td>$cMinScore</td>";
                                echo "<td><a href='./assignCourse.php?cid=$cId'><button class='btn btn-primary'>Enroll</button></a> <a href='assessment.php?cid=$cId'><button class='btn btn-info'>Assess</button><a/></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                    else{
                        echo "No Course Created...";
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