<!DOCTYPE html>
<html>
    <head><title>Update Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
    <body>
        <a href="./facultyHome.php">Home</a></br>
        <form method="post" action="facultyHelper.php">
            <input type="hidden" name="cid" value=<?php if(isset($_GET["id"])){echo $_GET['id'];}else{echo "";} ?> >
            <input type="text" name="cName" value=<?php if(isset($_GET["name"])){echo $_GET['name'];}else{echo "";} ?> >
            <input type="text" name="minScore" value=<?php if(isset($_GET["minScore"])){echo $_GET['minScore'];}else{echo "";} ?> >
            <input type="submit" name=<?php if(isset($_GET["id"])){echo "updateCourse";}else{echo "addCourse";} ?> value=<?php if(isset($_GET["id"])){echo "Update";}else{echo "Add";} ?> />
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
                        echo "<table border=1>";
                        echo "<tr>";
                                echo "<td>CourseId</td>";
                                echo "<td>CourseName</td>";
                                echo "<td>Minimum Score</td>";
                                echo "<td>Operations</td>";
                        echo "</tr>";
                        while($row = $result->fetch_assoc()){
                            $cId = $row['courseId'];
                            $cName = $row['courseName'];
                            $cMinScore = $row['minimumScore'];
                            
                            echo "<tr>";
                                echo "<td>$cId</td>";
                                echo "<td>$cName</td>";
                                echo "<td>$cMinScore</td>";
                                echo "<td><a href='./manageVideos.php?cid=$cId'><button>Manage Video</button></a><a href='manageCourse.php?id=$cId&name=$cName&minScore=$cMinScore'><button>Update</button></a><a href='./facultyHelper.php?id=$cId&op=del'><button>Delete</button></a></td>";
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