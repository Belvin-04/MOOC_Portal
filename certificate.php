<html style="margin: 0;
    padding: 0;">
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script>
            function getHtml(){
                var sName = document.getElementById("studentName");
                var cName = document.getElementById("courseName");
                var fName = document.getElementById("facultyName");
                var dateComplete = document.getElementById("dateCompleted");
                $.ajax({
                    method:"POST",
                    url:"./studentHelper.php?download=1",
                    data:{s:sName,c:cName,f:facultyName,d:dateComplete},
                    processData:false
                }).done(function(response){
                    console.log(response);
                });
            }
        </script>
    </head>
    <body style="color: black;
    display: table;
    font-family: Georgia, serif;
    font-size: 24px;
    text-align: center;
    
    margin: 0;
    padding: 0;">
        <?php 
            require_once "./settings/connection.php";
            $conn = $GLOBALS["conn"];
            session_start();

            $sql = "SELECT sd.studentName as sName,cd.courseName as cName,ed.dateCompleted as dateCompleted FROM studentDetails sd,courseDetails cd,enrollmentDetails ed WHERE cd.courseId = ".$_GET["cid"]." AND ((ed.courseId = cd.courseId) AND (ed.studentId = sd.studentId)) AND sd.studentId = ".$_SESSION["studentid"];

            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $sql = "SELECT facultyName FROM courseDetails cd,facultyDetails fd WHERE (fd.facultyId = cd.facultyId) AND (cd.courseId = ".$_GET["cid"].")";
            $result1 = $conn->query($sql);
            $row1 = $result1->fetch_assoc();
        ?>
        <div style="border: 20px solid tan;
    width: 750px;
    height: 563px;
    vertical-align: middle;">
            <div style="
            margin-top:5%;
            color: tan;">
                Marwadi University
            </div>

            <div style="color: tan;
    font-size: 48px;
    margin: 20px;">
                Certificate of Completion
            </div>

            <div style="margin: 20px;">
                This certificate is presented to
            </div>

            <div style="border-bottom: 2px solid black;
    font-size: 32px;
    font-style: italic;
    margin: 20px auto;
    width: 400px;">
                <?php echo "<span id='studentName'>".$row["sName"]."</span>";?>
            </div>

            <div style="margin: 20px;">
            <?php echo "For successfully completing the course <b><span id='courseName'>".$row["cName"]."</span></b>";?><br/><br/>
            <?php echo "Under the Guidance of <b><span id='facultyName'>".$row1["facultyName"]."</span></b>";?><br/><br/>
            <?php echo "On <b><span id='dateCompleted'>".$row["dateCompleted"]."</span></b>";?>
            </div>
        </div>
        <a href="./studentHome.php">Home</a>
        <a href="./studentHelper.php?download=1&<?php echo "s=".$row["sName"]."&c=".$row["cName"]."&f=".$row1["facultyName"]."&d=".$row["dateCompleted"]; ?>" target="_blank"><button class="btn btn-primary">Download</button></a>
    </body>
</html>