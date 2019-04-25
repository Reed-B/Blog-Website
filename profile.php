<?php

/*
This page used to have a way to change an email address, but it trusted the user too much that the email would
be legitimage and that it wasn't someone elses email. It did not send an email verification and therefore was
deemed insecure and removed from this profile editor. Now only the user's image can currently be changed.
*/

$page_title = "Profile";			//Set the title of the page
require_once("php/header.php");		//Get the header of the website
require_once('php/connectvars.php');//Get the database connection information
require_once('php/appvars.php');	//App variables is used to set profile image requirements

//Check if a session is not set then redirect the user back to the sign in page if it is not set
if(!isset($_SESSION['id'])){
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . 'signin.php';
	header('Location: ' . $home_url);
}


// Connect to the database and then get the users information based on their session id
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$query = "SELECT * FROM user WHERE id ='".$_SESSION['id']."'";
$data = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($data);

//If the user has already submitted their update request
if (isset($_POST['submit'])) {
	// Grab the users password and image they wish to use.
	$password = mysqli_real_escape_string($dbc, trim($_POST['password1']));
	$screenshot = mysqli_real_escape_string($dbc, trim($_FILES['screenshot']['name']));
	$screenshot_type = $_FILES['screenshot']['type'];
	$screenshot_size = $_FILES['screenshot']['size'];
	
	//If the user's password matches what they have entered
	$query = "SELECT * FROM user WHERE password =SHA('$password') AND id='".$_SESSION['id']."'";
	$data = mysqli_query($dbc, $query);
	if (mysqli_num_rows($data) == 1) {

		//If the screenshot input was not empty
		if (!empty($screenshot)) {
			//If the screenshot is a valid file type between a gif, gpeg, pjpeg, or png
			if ((($screenshot_type == 'image/gif') || ($screenshot_type == 'image/jpeg') || ($screenshot_type == 'image/pjpeg') || ($screenshot_type == 'image/png'))){
				//If the screenshot is within our allowed file size limits
				if(($screenshot_size > 0) && ($screenshot_size <= GW_MAXFILESIZE)) {
					//If there are no errors thrown by the file
					if ($_FILES['screenshot']['error'] == 0) {
						//These variables will determine where the image will be stored
						$folderPath = GW_UPLOADPATH . $_SESSION['id'];
						$target =  $folderPath . '/' . $screenshot;
						
						//If the user does not have a folder already for their profile image
						if(!is_dir($folderPath)) {
							mkdir("$folderPath");		//Make the users image directory
							chmod("$folderPath", 0755);	//Set the permissions of the directory
						}
						
						//If the file was able to be moved successfully
						if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $target)) {
							//If the users image is not the default image already, delete the image from our server to save on storage
							if($img != "img/unknown.jpg"){
								unlink($img);
							}
							
							//Update the database with the users new image
							$query = "UPDATE user SET image='$target' WHERE id='".$_SESSION['id']."'";
							$data = mysqli_query($dbc, $query);
							echo '<p class="success">Image successfully changed.</p>';
						} else {
							echo '<p class="error">There was an error uploading the image.</p>';
						}
					} else {
						echo '<p class="error">There was an error uploading the image.</p>';
					}
				} else {
					echo '<p class="error">Image file must be a jpg, png, or gif under 500kb.</p>';
				}
			} else {
				echo '<p class="error">Image file must be a jpg, png, or gif under 500kb.</p>';
			}
		} else {
			echo '<p class="error">Image not selected, the last image will be used.</p>';
		}

	}  else {
		echo '<p class="error">The password entered was not correct.</p>';
	}
}

mysqli_close($dbc);
?>
		<script src="js/signup.js">
		</script>
		<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return checkAll2()">
			<fieldset>
			<legend>Edit Profile</legend>
					<input type="hidden" name="MAX_FILE_SIZE" value="512000" />
				<label for="screenshot">Profile Image:</label>
				<input type="file" id="screenshot" name="screenshot" /><br />
				<label for="password1">Confirm Password:</label>
				<input type="password" id="password1" name="password1"/><div class="inputError" id="PW"></div><br />
			</fieldset>
			<input class="button submitbtn" type="submit" id="submit" value="Submit" name="submit"/>
		</form>
<?php
require_once("php/footer.php");
?>