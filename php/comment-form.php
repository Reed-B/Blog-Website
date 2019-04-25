<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$_GET['id']; ?>" >
	<fieldset>
		<input type="hidden" id="updateid" name="updateid" value="<?php echo $row['id']; ?>" />
			<textarea class="question" id="comment" name="comment"><?php echo $comment; ?></textarea>
	</fieldset>
	<input class="button submitbtn" type="submit" id="submit" value="Submit" name="submit"/>
	<div class="g-recaptcha" data-sitekey="6LdL5ZUUAAAAAE3YWDCsm3SXkOmk88cZjQYM9OqL"></div>
	
</form>