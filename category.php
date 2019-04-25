<?php
/*
*This webpage will display when the user has selected one of the category buttons on the header of the webpage.
*Users can open specific posts from this location.
*/
$page_title = "Category";		//Name of the page
require_once("php/header.php");	//Header of the website

require_once('php/connectvars.php');							//Gets the data base credentials
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	//Connects to the database

//Get the posts that cater to the category the user has selected
$query = "SELECT Name FROM Category WHERE id ='".$_GET['id']."'";
$data = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($data);

//Display the posts available to pick from that are from the category the user has selected
$query = "SELECT id, DATE_FORMAT(Date, '%b %e %Y') AS pdate, Title, content FROM Updates WHERE category_id ='".$_GET['id']."' ORDER BY date DESC";
$data = mysqli_query($dbc, $query);

//Display all posts from this category the user can select from
while($row = mysqli_fetch_array($data)){
	echo '<div class="post"><a href="posting.php?id='.$row['id'].'">';
	echo '<div class="date">';
	echo $row['pdate'];
	echo '</div>';
	echo '<div class="title">';
	echo $row['Title'];
	echo '</div>';
	echo '<p>';
	echo nl2br($row['content']);
	echo '</p>';
	echo '</div>'.'</a>';
}

mysqli_close($dbc);
require_once("php/footer.php");
?>