<?php
$page_title = "Recover Account";		//Title of the page
require_once("php/header.php");			//Get the standard header format of the website
require_once('php/connectvars.php');	//Get the database connection variables

//Make the connection to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Generate a verification code that the user will use to change their password.
$pass_phrase = "";
//Generate the code
for ($i = 0; $i < 10; $i++) {
	$pass_phrase .= chr(rand(97, 122));
}

//If the user has a session started, we allow them to just change their password.
//I believe I was going to use this area for users already signed in to the website to change their password.
//The only issue is I don't believe there is a link anywhere when the user is signed in so this is unused now.
if(isset($_SESSION['id'])){
	
	//Query the database for the users information
	$query = "SELECT username, email FROM user WHERE id=".$_SESSION['id'];
	mysqli_query($dbc, $query);
	$data = mysqli_query($dbc, $query);
	$row = mysqli_fetch_array($data);
	
	//Retrive the users username and email to send them a code to reset their password
	$email = $row['email'];
	$username = $row['username'];
	
	//Update the verification code into the users profile
	$query = "UPDATE user SET code='$pass_phrase' WHERE id=".$_SESSION['id'];
	mysqli_query($dbc, $query);
	
	//Send the email needed for users to change their password.
	$to = $email;
	$subject = 'Change Password';
	$msg = 'To change your password for wah-su.com, click this link: wah-su.com/changePassword.php?code='.$pass_phrase.'&user='.$username;
	mail($to, $subject, $msg, 'From:' . ' Change Password@behnfeldt.info');
	echo '<p class="success">E-mail sent.</p>';
} else {
	if(isset($_POST['submit'])){
		//Get the username the user says is theirs
		$username = mysqli_real_escape_string($dbc, trim($_POST['username']));

		//Query the database for the users information
		$query = "SELECT email FROM user WHERE username = '$username'";
		mysqli_query($dbc, $query);
		$data = mysqli_query($dbc, $query);
		$row = mysqli_fetch_array($data);

		//Retrive the users username and email to send them a code to reset their password
		$email = $row['email'];
		
		//Update the verification code into the users profile
		$query = "UPDATE user SET code='$pass_phrase' WHERE username='$username'";
		mysqli_query($dbc, $query);

		//Send the email needed for users to change their password.
		$to = $email;
		$subject = 'Change Password';
		$msg = 'To change your password for behnfeldt.info, click this link: behnfeldt.info/changePassword.php?code='.$pass_phrase.'&user='.$username;
		mail($to, $subject, $msg, 'From:' . ' Change Password@behnfeldt.info');
		echo '<p class="success">Email sent.</p>';
	} else {
		//If the user is not logged in or has yet to submit the form, give them the form needed
		require('php/recoveryForm.php');
	}
}

mysqli_close($dbc);
require_once("php/footer.php");
?>