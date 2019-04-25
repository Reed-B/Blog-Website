<?php
//Start an http session
session_start();
//Database connection variables imported
require_once('php/connectvars.php');

// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Check if a cookie from our site is already set on the users browser
if(isset($_COOKIE['id'])){
	//We get user information from the database to compare later
	$query = "SELECT last_ip FROM user WHERE id='".$_COOKIE['id']."'";
	$data = mysqli_query($dbc, $query);
	$row = mysqli_fetch_array($data);
	
	//Check to see if the cookie is from the same IP address as the last time they visited. If it is, we set the cookie values to the current session values.
	//Originally this was to prevent users from being signed into their account from a different computer.
	//This turns out to be a good measure to be taken from session hijacking, but may not be the most appropriate way to handle this.
	if($_COOKIE['ip'] == $row['last_ip']){
		$_SESSION['id']=$_COOKIE['id'];
	} else {
		//Reset user cookie and session data in case they have logged in from a different computer.
		setcookie('id', '', time() - 3600);
 		setcookie('ip', '', time() - 3600);
 		$_SESSION = array();
 			session_destroy();
	}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<Title><?php echo $page_title; //$page_title is set on each individual php page. ?></title>
	<meta charset="utf-8">
	<meta name="description" content="A web page">
	<link href="css/reset.css" rel="stylesheet"/>
	<link href="css/style.css" rel="stylesheet"/>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
	<div class="header">
		<div class="header2">
			<div class="banner"><a href="index.php">Reed Behnfeldt</a></div>
			<div class="profile">
			<?php
				//Get the users profile data from their current session information. If it is not set then we will give them the Sign in and Sign up buttons.
				if(!isset($_SESSION['id'])){
					echo '<a href="signin.php" class="profile2">Sign in</a><a href="signup.php" class="profile2">Sign up</a>';
				} else {
					$query = "SELECT * FROM user WHERE id='".$_SESSION['id']."'";
					$data = mysqli_query($dbc, $query);
					$row = mysqli_fetch_array($data);
					$img = $row['image'];
					$user = $row['username'];
					echo '<a href="profile.php"><img src="'.$img.'" width="40" height="40"/></a> Welcome, &nbsp;'.$user.'<a href="logout.php" class="profile2">Logout</a>';
				}
			?>
			</div>
		</div>
	</div>
	
	<div class="wrapper" id="wrapper">
		<div class="lbuttons">
		
		</div>
		
		<?php
			//Button data is set based on the categories that are set in the database. Each category has its own ID for the set of posts that it will display based on that ID.
			$query2 = "SELECT * FROM Category";
			$data2 = mysqli_query($dbc, $query2);
			
			//Creates the buttons
			while($row2 = mysqli_fetch_array($data2)){
				echo '<a href="category.php?id='.$row2['id'].'"><div class="button">'.$row2['name'].'</div></a>';
			}
			mysqli_close($dbc);
		?>

		<div class="rbuttons">
		
		</div>
		
		<div class="right">
			<a class="twitter-timeline" href="https://twitter.com/Wahsu_" width="260px" height="800px" data-widget-id="441281848572801024">Tweets by @Wahsu_</a>
    		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
    		if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
    		</script>
			
			<br>
			
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({
			google_ad_client: "ca-pub-9667351036008447",
			enable_page_level_ads: true
			});
			</script>
		</div>
		<div class="wrapper2">