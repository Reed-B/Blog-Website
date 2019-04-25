<?php
$page_title = "Change Password";		//Set the title of the page
require_once('php/connectvars.php');	//Get the connection variables to the SQL database
require_once("php/header.php");			//Get the header of the website

//Connect to the database.
//The variables from $GET should normally come from links sent to the user through forgotton password emails.
//We must see if the $GET variables match the user who was requesting the change and the verification key sent through email.
//We also make sure that the verification code is not blank.
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$query = "SELECT * FROM user WHERE username='".$_GET['user']."' AND code='".$_GET['code']."' AND code<>''";
$data = mysqli_query($dbc, $query);

//If the query ran earlier successfully found the user.
if(mysqli_num_rows($data) == 1){
	
	//If the user submitted their password change request
	if(isset($_POST['submit'])){
		
		//Get the password set and escape any SQL injection attacks.
		$password = mysqli_real_escape_string($dbc, trim($_POST['password1']));
		
			//Update the users password and sets their verification code back to an empty string
			$query = "UPDATE user SET password=SHA('$password'), code='' WHERE username ='".$_GET['user']."'";
			mysqli_query($dbc, $query);
			echo '<p class="success">Password successfully changed.</p>';
	} else {
		//Display to the user the change password form if they did not submit their password change yet.
		require_once("php/changePasswordForm.php");
	}
}
else
{
	//Display an error to the user that something is wrong with their link. Chances are they are trying to manipulate the variables in the URL
	echo '<p class="error">Error: username or reset key do not match!</div>';
}
require_once("php/footer.php");
?>