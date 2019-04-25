		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return checkAll()">
			<fieldset>
			<legend>Recover Password</legend>
				<label for="username">Username:</label>
				<input type="text" id="username" name="username" />
				</fieldset>
			<input class="button submitbtn" type="submit" id="submit" value="Sign Up" name="submit"/>
		</form>