<!DOCTYPE html>
<html>
    <head><title>Create Course</title></head>

    <body>
        <?php 
        require_once './settings/connection.php';
            session_start();
            $conn = $GLOBALS['conn'];
            if(isset($_SESSION['loggedin'])){
                if($_SESSION['loggedin'] == 1){
                    ?>
                    <a href="./facultyHome.php">Home</a>
                    <form action="facultyHelper.php" method="post">
                        <input name="cName" type="text" placeholder = "Course Name"/>
                        <input name="minScore" type="text" placeholder = "Minimum Score"/>
                        <input type="submit" name="addCourse" value="Add Course"/>
                    </form>
                    <?php          
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