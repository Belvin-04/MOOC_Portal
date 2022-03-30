<html>
    <head>
        <?php echo "<link rel='stylesheet' href='./css/style.css' />" ?>
    </head>
    <body>
        <?php 
            require_once "./settings/connection.php";
            $conn = $GLOBALS["conn"];
            session_start();

            $sql = "SELECT sd.studentName as sName,cd.courseName as cName FROM studentDetails sd,courseDetails cd,enrollmentDetails ed WHERE cd.courseId = ".$_GET["cid"]." AND ((ed.courseId = cd.courseId) AND (ed.studentId = sd.studentId)) AND sd.studentId = ".$_SESSION["studentid"];

            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $sql = "SELECT facultyName FROM courseDetails cd,facultyDetails fd WHERE (fd.facultyId = cd.facultyId) AND (cd.courseId = ".$_GET["cid"].")";
            $result1 = $conn->query($sql);
            $row1 = $result1->fetch_assoc();
        ?>
        <div class="container">
            <div class="logo">
                Marwadi University
            </div>

            <div class="marquee">
                Certificate of Completion
            </div>

            <div class="assignment">
                This certificate is presented to
            </div>

            <div class="person">
                <?php echo $row["sName"];?>
            </div>

            <div class="reason">
            <?php echo "For successfully completing the course <b>".$row["cName"]."</b>";?><br/><br/>
            <?php echo "Under the Guidance of <b>".$row1["facultyName"]."</b>";?>
            </div>
        </div>
        <a href="./studentHome.php">Home</a>
    </body>
</html>