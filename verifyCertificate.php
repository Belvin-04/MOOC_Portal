<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Verify Certificate</title>
</head>
<body
style="color: black;
    display: table;
    font-family: Georgia, serif;
    font-size: 24px;
    text-align: center;
    
    margin: 0;
    padding: 0;">
    <a href="./facultyHome.php">Home</a>
    <form action='./facultyHelper.php?findCertificate=1' method="post">
        <input type="text" name="certificateKey" placeholder="Certificate Key" required/>
        <input class="btn btn-primary" type="submit"/>
    </form>

    <?php 

        if(isset($_GET["s"])){
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
                <?php echo "<span id='studentName'>".$_GET["s"]."</span>";?>
            </div>

            <div style="margin: 20px;">
            <?php echo "For successfully completing the course <b><span id='courseName'>".$_GET["c"]."</span></b>";?><br/><br/>
            <?php echo "Under the Guidance of <b><span id='facultyName'>".$_GET["f"]."</span></b>";?><br/><br/>
            <?php echo "On <b><span id='dateCompleted'>".$_GET["d"]."</span></b>";?>
            </div>
        </div>
<?php
        }
        else if(isset($_GET["no"])){
            echo "Invalid Certificate Key";
        }

    ?>
</body>
</html>