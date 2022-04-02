<!DOCTYPE html>
<html>
    <head><title>Manage Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
    <body>
        <a href="./facultyHome.php">HOME</a>
    <form action = "./facultyHelper.php" method="post">
            <div class="form-group">
            <input type="hidden" name="quesId" value=<?php if(isset($_GET['id'])){echo $_GET['id'];}else{echo "";} ?> >
            <div>
                <textarea class="form-control" name="question" placeholder="Question" rows="5" ><?php if(isset($_GET['ques'])){echo $_GET['ques'];}else{echo "";} ?></textarea><br/>
            </div>
            <div></div>
            
            <textarea class="form-control" name="a" placeholder="Option A" rows="5" ><?php if(isset($_GET['a'])){echo $_GET['a'];}else{echo "";} ?></textarea>
            <textarea class="form-control" name="b" placeholder="Option B" rows="5" ><?php if(isset($_GET['b'])){echo $_GET['b'];}else{echo "";} ?></textarea>
            <textarea class="form-control" name="c" placeholder="Option C" rows="5" ><?php if(isset($_GET['c'])){echo $_GET['c'];}else{echo "";} ?></textarea>
            <textarea class="form-control" name="d" placeholder="Option D" rows="5" ><?php if(isset($_GET['d'])){echo $_GET['d'];}else{echo "";} ?></textarea><br/>
            <input class="form-control" type="text" name="answer" placeholder="Answer" value=<?php if(isset($_GET['ans'])){echo $_GET['ans'];}else{echo "";} ?>>
            <input class="form-control" type="text" name="time" placeholder="Time" value=<?php if(isset($_GET['time'])){echo $_GET['time'];}else{echo "";} ?> >
            <input type="hidden" name="vidId" value=<?php if(isset($_GET['vidId'])){echo $_GET['vidId'];}else{echo "";} ?>>
            <input class = "btn btn-primary" type="submit" value=<?php if(isset($_GET['id'])){echo "Update";}else{echo "Insert";} ?> name = <?php if(isset($_GET['id'])){echo "updateQuiz";}else{echo "addQuiz";} ?> >
            </div>
        </form>

        <?php 
            require_once './settings/connection.php';
            $conn = $GLOBALS['conn'];
            $sql = "SELECT * FROM quizdetails WHERE status = 1 AND videoId = ".$_GET['vidId'];
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                echo "<table class='table'>";
                echo "<thead class='thead-dark'>";
                echo "<tr align='center'>";
                        echo "<td>questionId</td>";
                        echo "<td>question</td>";
                        echo "<td>A</td>";
                        echo "<td>B</td>";
                        echo "<td>C</td>";
                        echo "<td>D</td>";
                        echo "<td>Answer</td>";
                        echo "<td>Video Id</td>";
                        echo "<td>Time</td>"; 
                echo "</tr>";
                echo "</thead>";
                while($row = $result->fetch_assoc()){
                    $id = $row['questionId'];
                    $question = $row['question'];
                    $optionA = $row['optionA'];
                    $optionB = $row['optionB'];
                    $optionC = $row['optionC'];
                    $optionD = $row['optionD'];
                    $answer = $row['answer'];
                    $vidId = $row['videoId'];
                    $time = $row['time'];
                    
                    echo "<tr align='center'>";
                        echo "<td>$id</td>";
                        echo "<td>$question</td>";
                        echo "<td>$optionA</td>";
                        echo "<td>$optionB</td>";
                        echo "<td>$optionC</td>";
                        echo "<td>$optionD</td>";
                        echo "<td>$answer</td>";
                        echo "<td>$vidId</td>";
                        echo "<td>$time</td>";
                        echo "<td><a href='./manageQuiz.php?id=$id&ques=$question&a=$optionA&b=$optionB&c=$optionC&d=$optionD&ans=$answer&vidId=$vidId&time=$time'><button class='btn btn-info'>Update</button></a><a href='facultyHelper.php?id=$id&vidId=$vidId&delQuiz=1'><button class='btn btn-danger'>Delete</button></a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
        ?>
    </body>
</html>