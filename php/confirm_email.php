<?php
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//The URL is suppose to contain the variables with the users username and the verification code which can only be obtained via email.
$query = "SELECT * FROM user WHERE username='".$_GET['user']."' AND code='".$_GET['code']."' AND code!=''";
$data = mysqli_query($dbc, $query);

//If variables needed for varification are present
if(mysqli_num_rows($data) == 1){
	//Set the user as they have confirmed their email address.
	$query = "UPDATE user SET confirmed_email=1, code='' WHERE username='".$_GET['user']."'";
	mysqli_query($dbc, $query);
}

//Send the user back to the home page
$home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php';
mysqli_close($dbc);
header('Location: ' . $home_url);
?>