<?php
session_start();
require_once './Google_PHP_SDK/vendor/autoload.php';
require_once './settings/connection.php';
  
//marwadieducation.edu.in = faculty
//marwadiuniversity.ac.in = student
// init configuration
$clientID = '828204760738-rig9899tml31j280g3drkra07a6btfnu.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/MOOC%20Portal/redirect.php';
   
// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

$conn = $GLOBALS['conn'];

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);
   
  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;
  $facultyExpr = "/marwadieducation.edu.in/i";
  $studentExpr = "/marwadiuniversity.ac.in/i";
  $stmt = "";
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
  else{
    if($email === "pravin.nadar111646@marwadiuniversity.ac.in"){
      $stmt = $conn->prepare("SELECT * FROM facultydetails WHERE facultyEmail = ?");
      $stmt->bind_param("s",$email);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows <= 0){
        $stmt = $conn->prepare("INSERT INTO facultydetails (facultyName,facultyEmail) VALUES (?,?)");
        $stmt->bind_param("ss",$name,$email);
        $stmt->execute();
      }
      setFacultySession($email,$conn);
      header("Location: ./facultyHome.php");
    }
    else if(preg_match($facultyExpr,$email)){
      $stmt = $conn->prepare("SELECT * FROM facultydetails WHERE facultyEmail = ?");
      $stmt->bind_param("s",$email);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows <= 0){
        $stmt = $conn->prepare("INSERT INTO facultydetails (facultyName,facultyEmail) VALUES (?,?)");
        $stmt->bind_param("ss",$name,$email);
        $stmt->execute();
      }
      setFacultySession($email,$conn);
      header("Location: ./facultyHome.php");
    }
    else if(preg_match($studentExpr,$email)){
      preg_match('/[0-9]{6}[0-9]*/',$email,$match);
      $grNo = $match[0];
      echo $grNo;
      $stmt = $conn->prepare("SELECT * FROM studentdetails WHERE studentEmail = ?");
      $stmt->bind_param("s",$email);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows <= 0){
        $stmt = $conn->prepare("INSERT INTO studentdetails (studentName,studentEmail,studentgr) VALUES (?,?,?)");
        $stmt->bind_param("sss",$name,$email,$grNo);
        $stmt->execute();
      }
      setStudentSession($email,$conn);
      header("Location: ./studentHome.php");
    }
  }

  // now you can use this profile info to create account in your website and make user logged in.
} else {
  header("Location: ".$client->createAuthUrl());
}

function setStudentSession($email,$conn){
  $sql = "SELECT studentid FROM studentdetails WHERE studentemail = '$email'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $id = $row['studentid'];
  $_SESSION['loggedin'] = 1;
  $_SESSION['studentid'] = $id;

}

function setFacultySession($email,$conn){
  $sql = "SELECT facultyid FROM facultydetails WHERE facultyemail = '$email'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $id = $row['facultyid'];
  $_SESSION['loggedin'] = 1;
  $_SESSION['facultyid'] = $id;
}
?>