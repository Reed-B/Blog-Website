<?php
$error = "";
require_once('php/connectvars.php');

// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//If user submitted the login attempt
if (isset($_POST['submit'])) {
	
	//Check if the reCaptcha has been completed
	$captcha = $_POST['g-recaptcha-response'];
	if($captcha){
		
		//Retrieve what the user has submitted as their username and password
		$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
		$password = mysqli_real_escape_string($dbc, trim($_POST['password']));
	
		// Make sure someone isn't already registered using this username
		$query = "SELECT id FROM user WHERE username='$username' AND password=SHA('$password')";
		$data = mysqli_query($dbc, $query);
	
		// Check if the username is unique
		if (mysqli_num_rows($data) == 1) {
			
			//Populate the users session and cookies with their account data.
			$row = mysqli_fetch_array($data);
			$_SESSION['id'] = $row['id'];
			$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
			setcookie('id', $row['id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
			setcookie('ip', $_SERVER['REMOTE_ADDR'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
			
			//Get user IP for making sure they are on the same computer as last time.
			$ip = $_SERVER['REMOTE_ADDR'];
			$query = "UPDATE user SET last_ip = '$ip' WHERE username = '$username'";
			$data = mysqli_query($dbc, $query);
			
			//Send the user back to the home page after successfully logging in
			$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . 'index.php';
			header('Location: ' . $home_url);
		} else {
			$error = '<p class="error">Username or password was incorrect.</p>';
		}
	} else {
		$error = '<p class="error">Please complete the reCaptcha.</p>';
	}
}

$page_title = "Login";
require_once("php/header.php");

if($error != ""){
	echo $error;
}
?>
		
		<form class="signin" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<fieldset>
			<legend>Sign in</legend>
				<label for="username">Username:</label>
					<input type="text" id="username" name="username" value="<?php if (!empty($username)){ echo $username; }?>"/><br />
				<label for="password1">Password:</label>
					<input type="password" id="password" name="password"/><br />
				<div class="recover"><a href="recovery.php">Recover Account</a></div>
			</fieldset>
			<input class="button submitbtn submitbtn2" type="submit" id="submit" value="Sign in" name="submit"/>
			<div class="g-recaptcha" data-sitekey="6LdL5ZUUAAAAAE3YWDCsm3SXkOmk88cZjQYM9OqL"></div>
		</form>

<?php
require_once("php/footer.php");
?>