<?php
$page_title = "Sign Up";
require_once("php/header.php");
require_once('php/connectvars.php');

// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (isset($_POST['submit'])) {
	// Grab the profile data from POST
	$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
	$password = mysqli_real_escape_string($dbc, trim($_POST['password1']));
	$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
	$captcha = $_POST['g-recaptcha-response'];
	// Make sure someone isn't already registered using this username
	$query = "SELECT * FROM user WHERE username = '$username'";
	$data = mysqli_query($dbc, $query);
	// Check if the username is unique
	if (mysqli_num_rows($data) == 0) {
		
		//If user completed the reCaptcha
		if ($captcha) {
			
			//Create a random challenge phrase that the user will use to verify their email address
			$pass_phrase = "";
			for ($i = 0; $i < 10; $i++) {
				$pass_phrase .= chr(rand(97, 122));
			}
			
			//Add the user to the database
			$query = "INSERT INTO user (username, password, email, join_date, code) VALUES ('$username', SHA('$password'), '$email', NOW(), '$pass_phrase')";
			mysqli_query($dbc, $query);
			
			//Send an email to the user with a link to verify their email. The link contains the challenge phrase that will be compared later.
			$to = $email;
			$subject = 'Confirm E-mail Address';
			$msg = 'To confirm your email address for behnfeldt.info, click this link: behnfeldt.info/php/confirm_email.php?code='.$pass_phrase.'&user='.$username;
			mail($to, $subject, $msg, 'From:' . 'Confirm-Email@behnfeldt.info');

			// Confirm success with the user
			echo '<p class="success">Your account has been successfully created. Please confirm your email address using the link sent to it.</p>';
			$username = "";
			$email="";
		} else {
		echo '<p class="error">Please complete the reCaptcha.</p>';
		}
	} else {
		// An account already exists for this username, so display an error message
		echo '<p class="error">An account already exists for this username.</p>';
		$username = "";
	}
}
mysqli_close($dbc);
?>
		<script src="js/signup.js">
		</script>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return checkAll()">
			<fieldset>
			<legend>Registration Info </br>(THIS SITE IS INSECURE!)</legend>
				<label for="username">Username:</label>
					<input type="text" id="username" name="username" value="<?php if (!empty($username)){ echo $username; }?>" onBlur="checkUN()"/><div class="inputError" id="UN"></div><br />
				<label for="password1">Password:</label>
					<input type="password" id="password1" name="password1" onBlur="checkPW()"/><div class="inputError" id="PW"></div><br />
				<label for="password2">Password (retype):</label>
					<input type="password" id="password2" name="password2" onBlur="checkPW2()"/><div class="inputError" id="PW2"></div><br />
				<label for="password2">E-mail:</label>
					<input type="text" id="email" name="email" value="<?php if (!empty($email)) echo $email; ?>" onBlur="checkEM()"/><div class="inputError" id="EM"></div><br />
			</fieldset>
			<input class="button submitbtn" type="submit" id="submit" value="Sign Up" name="submit"/>
			<div class="g-recaptcha" data-sitekey="6LdL5ZUUAAAAAE3YWDCsm3SXkOmk88cZjQYM9OqL"></div>
		</form>
<?php
require_once("php/footer.php");
?>