<?php
$page_title = "Home";					//Set the title for the browser
require_once("php/header.php");			//Populate the page with the standard website header
require_once('php/connectvars.php');	//Get database connection information
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Query for all posts made ever. Pages can be implemented in the future easilily but not nececcary now.
$query = "SELECT id, DATE_FORMAT(Date, '%b %e %Y') AS pdate, title, content FROM updates ORDER BY date DESC";
$data = mysqli_query($dbc, $query);

//Display all the posts made
while($row = mysqli_fetch_array($data)){
	echo '<div class="post"><a href="posting.php?id='.$row['id'].'">';
	echo '<div class="date">';
	echo $row['pdate'];
	echo '</div>';
	echo '<div class="title">';
	echo $row['title'];
	echo '</div>';
	echo '<p>';
	echo nl2br($row['content']);
	echo '</p>';
	echo '</div>'.'</a>';
}

//Close the database and afterwards display the footer of the website.
mysqli_close($dbc);
require_once("php/footer.php");
?>