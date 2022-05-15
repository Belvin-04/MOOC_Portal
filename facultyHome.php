<!DOCTYPE html>
<html>
    <head><title>Faculty Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        function setCutOff(){
            var cutOff = document.getElementById("cutOff").value;
            var percentageRegex = /^(([0-9]{1}|[1-9]{1}[0-9]{1})|([1]{1}[0]{2}))$/;
            if(percentageRegex.test(cutOff)){
                $.ajax({
                    method:"post",
                    url:"facultyHelper.php",
                    data:{marks:cutOff},
                }).done(function(response){
                    console.log(response);
                    location.reload();
                });
            }
            else{
                alert("Invalid Percentage");
            }
            
        }
    </script>
</head>

    <body>
        <a href="https://accounts.google.com/logout">Logout</a>
        <button style="float:right" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
            Cut Off Peercentage
        </button>
        <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Cut Off Percentage</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id = "cutOff"/>%
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="setCutOff()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

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
                                echo "<td><a href='./assignCourse.php?cid=$cId'><button class='btn btn-primary'>Enroll</button></a> <a href='enrolledStudents.php?cid=$cId'><button class='btn btn-info'>Enrolled Students</button><a/></td>";
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