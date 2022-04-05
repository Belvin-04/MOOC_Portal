<!DOCTYPE html>
<html>
    <head>
        <title>Assign Course</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>
    <body>
        <a href="./facultyHome.php">HOME</a>
        <?php 
            session_start();
            require_once './settings/connection.php';
            $conn = $GLOBALS['conn'];

            $sql = "SELECT * FROM studentDetails ORDER BY studentGR";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        echo "<table class='table'>";
                        echo "<thead class='thead-dark'>";
                        echo "<tr align='center'>";
                                echo "<th>StudentId</th>";
                                echo "<th>Student Name</th>";
                                echo "<th>Student GR</th>";
                                echo "<th>Student Email</th>";
                                echo "<th>Operations</th>";
                        echo "</tr>";
                        echo "</thead>";
                        while($row = $result->fetch_assoc()){
                            $sId = $row['studentId'];
                            $gr = $row['studentGR'];
                            $sName = $row['studentName'];
                            $sEmail = $row['studentEmail'];
                            $cId = $_GET['cid'];
                            echo "<tr align='center'>";
                                echo "<td>$sId</td>";
                                echo "<td>$sName</td>";
                                echo "<td>$gr</td>";
                                echo "<td>$sEmail</td>";
                                echo "<td><a href='./facultyHelper.php?sId=$sId&cId=$cId&assign=1'><button class='btn btn-primary'>Assign</button></a> <a href='./facultyHelper.php'><button class='btn btn-warning'>Un-Assign</button></a></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                    if(isset($_GET["assigned"])){
                        echo "<script>alert('Course Assigned Successfully...');</script>";
                    }
                    if(isset($_GET["error"])){
                        echo "<script>alert('Student has completed the course...');</script>";
                    }
        ?>
    </body>
</html>