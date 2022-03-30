<?php
session_start();
require_once('./redirect.php');

$accesstoken=$_SESSION['access_token'];

//Reset OAuth access token
$google_client->revokeToken($accesstoken);

//Destroy entire session data.
session_destroy();

//redirect page to index.php
header('location: ./index.html');

?>