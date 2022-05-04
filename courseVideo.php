<!DOCTYPE html>
<html>
  <head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  </head>
  <body>
  <?php 

require_once "./settings/connection.php";
session_start();
$conn = $GLOBALS["conn"];
$questions = array();
$quesTimes = array();
$sql = "SELECT * FROM quizDetails WHERE status = 1 AND videoId = ".$_GET["vId"]." ORDER BY time";
$result = $conn->query($sql);
if($result->num_rows > 0){
  while($row = $result->fetch_assoc()){
    $time = $row["time"];
    $quesId = $row["questionId"];
    $ques = $row['question'];
    $a = $row['optionA'];
    $b = $row['optionB'];
    $c = $row['optionC'];
    $d = $row['optionD'];
    $ans = $row['answer'];
    $questions = $questions + array("$time"=>array("quesId"=>$quesId,"ques"=>array("ques"=>$ques,"options"=>array("A"=>$a,"B"=>$b,"C"=>$c,"D"=>$d),"Ans"=>$ans)));
    array_unshift($quesTimes,$time);
  }
  $quesJson = json_encode($questions);
  $quesTimes = array_reverse($quesTimes);
  
}
?>
    <input type="hidden" value=<?php echo $_GET['cId']; ?> id="cid">
    <input type="hidden" value=<?php echo $_GET['videoLinkId']; ?> id="videoLink" />
    <input type="hidden" value=<?php echo $_GET['vId']; ?> id="vid" />
    <!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
    <center>
    <button class="btn btn-primary" onclick="setPlayerSize('640','360')">Minimum Size</button>
      <button class="btn btn-primary" onclick="setPlayerSize('854','480')">Medium Size</button>
      <button class="btn btn-primary" onclick="setPlayerSize('1024','720')">Maximum Size</button>
      <div id="player"></div>
      
    </center>

    <script>
      
      $(document).ready(function(){
        $("#quizTable").hide();
      });
      // 2. This code loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');
      let questions;
      let quesTimes;
      let quesObj;
      let quesIndex = 0;
      var timer;
      let quizDisplayed = false;
      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
      var link = document.getElementById("videoLink").value;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
          height: '360',
          width: '640',
          videoId: link,
          playerVars: {
            'playsinline': 1,
            controls : 0,
            disablekb:0
          },
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
      }
      function setPlayerSize(w,h){
       player.setSize(parseInt(w),parseInt(h)); 
      }
      // 4. The API will call this function when the video player is ready.
      function onPlayerReady(event) {
        event.target.playVideo();
        questions = '<?php if(count($questions) == 1){echo $quesJson;}else{echo $quesJson;}; ?>';
        quesTimes = [<?php if(count($questions) == 1){echo implode($quesTimes);}else{echo implode(",",$quesTimes);}; ?>]
        quesObj = JSON.parse(questions);
        
      }
      function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING) {
          timer = setInterval(() => {
            checkForQuizTime(quesTimes[quesIndex]);
          }, 1000);
          if(quizDisplayed){
            let cid = $("#cid").val();
            let id = $("#quesId").val();
            $.ajax({
            method: "POST",
            url: "./studentHelper.php",
            data: { updateAttempt:1,cId:cid,quesId:id }
            })
              .done(function( response ) {
              $("#quizTable").hide();
              player.playVideo();
              console.log(response);
            });
            quizDisplayed = false;
          }
        }
        if (event.data == YT.PlayerState.PAUSED) {
          clearInterval(timer);
        }

        if(event.data == YT.PlayerState.ENDED){
          let cid = $("#cid").val();
          let vid = $("#vid").val();
          $.ajax({
            method:"POST",
            url:"./studentHelper.php",
            data:{cId:cid,vId:vid,updateWatchedVideo:1}
          })
          .done(function(response){
            console.log(response);
            window.location.href = "./courseComponents.php?cid="+cid;
          });
        }
      } 

      

      function stopVideo() {
        player.stopVideo();
      }

      function checkForQuizTime(quizTime){
        if(quizTime == parseInt(player.getCurrentTime())){
          player.pauseVideo();
          let quesId = (quesObj[quizTime]);
          let id = quesId["quesId"];
          let ques = quesId["ques"];
          let question = ques["ques"];
          let options = ques["options"];
          let answer = ques["Ans"];
          
          quizDisplayed = true;
          $("#quesId").val(id);
          $("#realAns").val(answer);
          $("#ques").html(question);
          $("#opAQ").html(options["A"]);
          $("#opBQ").html(options["B"]);
          $("#opCQ").html(options["C"]);
          $("#opDQ").html(options["D"]);
          $("#quizTable").show();
          quesIndex++;
        }
      }

      function check(){

        let ans = $('input[name=ans]:checked').val();
        let quesId = $("#quesId").val();
        let Ans = $("#realAns").val();
        let cid = $("#cid").val();
        $.ajax({
          method: "POST",
          url: "./studentHelper.php",
          data: { selectedAnswer: ans,checkAnswer:1,realAnswer:Ans,cId:cid,quesId:quesId }
        })
          .done(function( response ) {
            $("#quizTable").hide();
            player.playVideo();
            console.log(response);
          });
      }
    </script>
    <input type="hidden" id="quesId" name="quesId"/>
    <input type="hidden" id="realAns" name="realAns"/>

    <table class="table table-border" id="quizTable">
      <tr>
        <td id="ques"></td>
      </tr>
      <tr>
        <td id="opA"><input type="radio" name="ans" value="a"><span id="opAQ"></span></td>
      </tr>
      <tr>
        <td id="opB"><input type="radio" name="ans" value="b"><span id="opBQ"></span></td>
      </tr>
      <tr>
        <td id="opC"><input type="radio" name="ans" value="c"><span id="opCQ"></span></td>
      </tr>
      <tr>
        <td id="opD"><input type="radio" name="ans" value="d"><span id="opDQ"></span></td>
      </tr>
      <tr>
        <td id="sub"><button onclick = check() class="btn btn-primary">Submit</button></td>
      </tr>
    </table>
  </body>

</html>