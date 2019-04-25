<?php
$page_title = "Post Update";		//Title of the current page
require_once("php/header.php");		//Get the header of the website
require_once('php/connectvars.php');//Get the database connection information

//If there is not an active session currently or their session ID is not that of the owner, redirect them to the home page
if(!isset($_SESSION['id']) || $_SESSION['id'] != 1){
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . 'index.php';
	header('Location: ' . $home_url);
}

/***
Connect to the database and select every category option. 
This will allow us to make radio buttons later for the admin to select what category this post is in
***/
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$query = "SELECT * FROM Category";
$data = mysqli_query($dbc, $query);

//If the user has already submitted the new post to make
if (isset($_POST['submit'])) {
	
	//Get the content that the user is trying to post and escape any sql strings
	//We do not escape html characters because they are needed for formating.
	//The only user who should be allowed currently to post new content is the owner.
	$category_id = mysqli_real_escape_string($dbc, trim($_POST['category']));
	$title = mysqli_real_escape_string($dbc, trim($_POST['title']));
	$content = mysqli_real_escape_string($dbc, trim($_POST['content']));
	
	//This query will add the new post to the database
	$query2 = "INSERT INTO Updates (category_id, title, content, Date) VALUES ('$category_id', '$title', '$content', NOW())";
	$data2 = mysqli_query($dbc, $query2);
}
mysqli_close($dbc);
?>
		
		<form class="signin" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<fieldset>
			<legend>Post Update</legend>
				<label for="username">Insert into:</label><br />
				<?php
					//This makes the radio buttons needed to select what category the new post is in.
					while($row = mysqli_fetch_array($data)){
						echo '<input type="radio" name="category" value="'.$row['id'].'" />'.$row['name'].'<br>';
					}
				?>
				<label for="title">Title:</label>
					<input type="text" id="title" name="title" />
				<textarea class="question" id="content" name="content"></textarea>
			</fieldset>
			<input class="button submitbtn submitbtn2" type="submit" id="submit" value="Post Update" name="submit"/>
		</form>

<?php
//Add the footer of the webpage
require_once("php/footer.php");
?>