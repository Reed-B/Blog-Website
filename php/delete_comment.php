<?php
//Used to send users back that are not supposed to be here later
$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . 'index.php';

//Check if the users sesssion is set and that they are also an admin of the site
if(!isset($_SESSION['id']) || $_SESSION['id'] != 1){
	header('Location: ' . $home_url);
}

//Get the database connection information and then connect to the database
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Delete the comment selected using this query
$query = "DELETE FROM Comments WHERE id = ".$_GET['id']." LIMIT 1";
$data = mysqli_query($dbc, $query);

//Redirect back to the post's page that had a comment deleted
$home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/posting.php?id='.$_GET['uid'];
header('Location: ' . $home_url);
mysqli_close($dbc);
?>