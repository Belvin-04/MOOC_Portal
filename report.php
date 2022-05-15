<!DOCTYPE html>
<html>
    <head>
        <title>Report</title>
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
                    $sId = $_GET["sid"];
                    $cId = $_GET["cid"];
                    $sql = "SELECT * FROM reportDetails WHERE studentId = $sId AND courseId = $cId";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        echo "<table class='table'>";
                            echo "<thead class='thead-dark'>";
                            echo "<tr align='center'>";
                                echo "<td colspan=3><b>Report</b></td>";
                            echo "</tr>";
                            echo "<tr align='center'>";
                                echo "<th>Status</th>";
                                echo "<th>Timestamp</th>";
                                echo "<th></th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                        while($row = $result->fetch_assoc()){
                            $id = $row['id'];
                            $status = $row['status'];
                            $timestamp = $row['timestamp'];
                            echo "<tr align='center'>";
                                echo "<td>$status</td>";
                                echo "<td>$timestamp</td>";
                                echo "<td><a><button class='btn btn-primary'>Details</button></a></td>";
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