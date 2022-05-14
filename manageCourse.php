<!DOCTYPE html>
<html>
    <head><title>Manage Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
    <body>
        <a href="./facultyHome.php">Home</a></br>
        <form method="post" action="facultyHelper.php">
            <div class="form-group">
                <input type="hidden" name="cid" value=<?php if(isset($_GET["id"])){echo $_GET['id'];}else{echo "";} ?> >
                <input class="form-control" placeholder = "Course Name" type="text" name="cName" value=<?php if(isset($_GET["name"])){echo $_GET['name'];}else{echo "";} ?> >
                <input class="form-control" placeholder = "Score" type="text" name="score" value=<?php if(isset($_GET["score"])){echo $_GET['score'];}else{echo "";} ?> >
                <input type="submit" class="btn btn-primary" name=<?php if(isset($_GET["id"])){echo "updateCourse";}else{echo "addCourse";} ?> value=<?php if(isset($_GET["id"])){echo "Update_Course";}else{echo "Add_Course";} ?> />
            </div>
        </form>

        <?php 
        require_once './settings/connection.php';
            session_start();
            $conn = $GLOBALS['conn'];
            if(isset($_SESSION['loggedin'])){
                if($_SESSION['loggedin'] == 1){
                    $sql = "SELECT * FROM courseDetails WHERE status = 1 AND facultyId = ".$_SESSION['facultyid'];
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        echo "<table class='table'>";
                        echo "<thead class='thead-dark'>";
                        echo "<tr align='center'>";
                                echo "<th>CourseId</td>";
                                echo "<th>CourseName</td>";
                                echo "<th>Score</td>";
                                echo "<th>Min Score</td>";
                                echo "<th>Operations</td>";
                        echo "</thead>";
                        echo "</tr>";
                        while($row = $result->fetch_assoc()){
                            $cId = $row['courseId'];
                            $cName = $row['courseName'];
                            $score = $row['score'];
                            $minScore = $row['minimumScore'];
                            
                            echo "<tr align='center'>";
                                echo "<td>$cId</td>";
                                echo "<td>$cName</td>";
                                echo "<td>$score</td>";
                                echo "<td>$minScore</td>";
                                echo "<td><a href='./manageVideos.php?cid=$cId'><button class='btn btn-warning'>Manage Video</button></a> <a href='manageCourse.php?id=$cId&name=$cName&score=$score'><button class='btn btn-info'>Update</button></a> <a href='./facultyHelper.php?id=$cId&op=del'><button class='btn btn-danger'>Delete</button></a></td>";
                            echo "</tr>";
                        }
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