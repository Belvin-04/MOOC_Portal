<!DOCTYPE html>
<html>
    <head><title>Manage Video</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
    <body>
        <a href="./facultyHome.php">HOME</a>
        <form action="./facultyHelper.php" method="post">
            <div class="form-group">
            <input type="hidden" name="videoId" placeholder="Video Id" value=<?php if(isset($_GET['id'])){echo $_GET['id'];}else{echo "";}?> >
            <input type="text" class="form-control" name="videoName" placeholder="Video Name" value=<?php if(isset($_GET['name'])){echo $_GET['name'];}else{echo "";}?> >
            <input type="text" class="form-control" name="videoLink" placeholder="Video Link" value=<?php if(isset($_GET['link'])){echo $_GET['link'];}else{echo "";}?> >
            <input type="hidden" name="courseId" value=<?php if(isset($_GET['cid'])){echo $_GET['cid'];}else{echo "";}?> />
            <input type="hidden" name="sequence" placeholder="Sequence" value=<?php if(isset($_GET['sequence'])){echo $_GET['sequence'];}else{echo "";}?> >
            <input type = "submit" class='btn btn-primary' name = <?php if(isset($_GET['name'])){echo "updateVideo";}else{echo "addVideo";} ?> value = <?php if(isset($_GET['name'])){echo "Update_Video";}else{echo 'Add_Video';} ?> >
</div>
        </form>
        <?php 
            require_once './settings/connection.php';
            $conn = $GLOBALS['conn'];
            $sql = "SELECT * FROM videoDetails WHERE status = 1 AND courseId = ".$_GET['cid'];
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                echo "<table class='table'>";
                echo "<thead class='thead-dark'>";
                echo "<tr align='center'>";
                        echo "<td>videoId</td>";
                        echo "<td>Title</td>";
                        echo "<td>Link</td>";
                        echo "<td>Course Id</td>";
                        echo "<td>Sequence</td>";
                        echo "<td>Operations</td>";
                echo "</thead>";
                echo "</tr>";
                while($row = $result->fetch_assoc()){
                    $id = $row['videoId'];
                    $title = $row['videoTitle'];
                    $link = $row['link'];
                    $cid = $row['courseId'];
                    $sequence = $row['sequence'];
                    
                    echo "<tr align='center'>";
                        echo "<td>$id</td>";
                        echo "<td>$title</td>";
                        echo "<td>$link</td>";
                        echo "<td>$cid</td>";
                        echo "<td>$sequence</td>";
                        echo "<td><a href='./manageVideos.php?id=$id&name=$title&link=$link&cid=$cid&sequence=$sequence'><button class='btn btn-info'>Update</button></a> <a href='facultyHelper.php?id=$id&cid=$cid&delVideo=1'><button class='btn btn-danger'>Delete</button></a> <a href='./manageQuiz.php?vidId=$id'><button class='btn btn-warning'>Manage Quiz</button></a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
        ?>
    </body>
</html>