<?php
$page_title = "Update";
$comment = "";
$error = "";
require_once("php/header.php");
require_once('php/connectvars.php');

//Start a connection to our database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$canDelete = false; //canDelete is used later to determine if the user is the admin account, the only one allowed to delete comments if necessary.

//If user submitted a comment
if(isset($_POST['submit'])){
		
	$captcha = $_POST['g-recaptcha-response'];
	$comment = mysqli_real_escape_string($dbc, trim($_POST['comment']));
	$updateid = mysqli_real_escape_string($dbc, trim($_POST['updateid']));
	
	//If user completed the reCaptcha
	if($captcha)
	{
		//Insert the users comment into the database
		$query3 = "INSERT INTO Comments (update_id, user_id, comment, date) VALUES ('$updateid', '".$_SESSION['id']."', '$comment', NOW())";
		mysqli_query($dbc, $query3);
		$comment = "";
		
	} 
	else 
	{
		$error = '<p class="error">Please complete the reCaptcha.</p>';
	}
}

//Get the post that the user has selected based on the ID of the variable in the URL
$query = "SELECT id, DATE_FORMAT(Date, '%b %e %Y') AS pdate, Title, content FROM Updates WHERE id = '".$_GET['id']."'";
$data = mysqli_query($dbc, $query);

//Check if a session is set and if the user ID is the sole admin account.
if(isset($_SESSION['id'])){
	if($_SESSION['id'] == 1){
		$canDelete = true; //User is allowed to delete comments
	}
}

//This will gather the data of the the post and adds it into the page.
while($row = mysqli_fetch_array($data)){
	echo '<div class="post">';
	echo '<div class="date">';
	echo $row['pdate']; //Post date
	echo '</div>';
	echo '<div class="title">';
	echo $row['Title']; //Title of the post
	echo '</div>';
	echo '<p>';
	echo nl2br($row['content']); //Content of the post
	echo '</p>';
	echo '</div>';
	
	//We start to select the comment data associated with the post that the user is viewing
	$query2 = "SELECT u.image AS img, u.username AS name, DATE_FORMAT(c.date, '%b %e, %Y @ %l:%i%p') AS datep, c.comment AS cmt, c.id AS cid FROM Comments c INNER JOIN user u ON (u.id = c.user_id) WHERE c.update_id=".$row['id']." ORDER BY datep";
	$data2 = mysqli_query($dbc, $query2);
		
	echo '<div class="commentNotifier">Comments:</div>';
	echo '<div class="commentSection">';
	
	//Create each comment
	while($row2 = mysqli_fetch_array($data2)){
		
		echo '<div class="user">';
		echo '<img src="'.$row2['img'].'" /> '.$row2['name'].' <div class="commentdate">'.$row2['datep'].'</div>';
		echo '</div>';
		echo '<div class="comment">';
		echo nl2br(htmlspecialchars($row2['cmt']));
		
		//Checks to see if the user is an admin capable of deleting a comment before showing the delete button.
		if($canDelete){
			echo '<br /><a class="delete" href="php/delete_comment.php?id='.$row2['cid'].'&uid='.$_GET['id'].'">Delete</a>';
		}
		echo '</div>';
	}
	
	//Checks to see if a user is logged in to allow them to comment
	if(isset($_SESSION['id'])){
		
		//Display an error if the user failed to complete the reCaptcha
		if($error != "")
		{
			echo $error;
		}
		
		//display the comment form to a user logged in
		require("php/comment-form.php");
	}
	else //if user is not logged in we give them a Sign in and Sign up button.
	{
		echo '<div class=profile style="float: none; margin: auto; text-align: center; padding: 30px 50px 30px 0px;">';
		echo '<a href="signin.php" class="profile2">Sign in</a><a href="signup.php" class="profile2">Sign up</a>';
		echo '</div>';
	}
	echo '</div>';
}

mysqli_close($dbc);
require_once("php/footer.php");
?>