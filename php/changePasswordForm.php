		<script src="js/signup.js">
		</script>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?code='.$_GET['code'].'&user='.$_GET['user']; ?>" onsubmit="return checkAll3()">
			<fieldset>
			<legend>Change Password</br>(THIS SITE IS INSECURE!)</legend>
			<label for="password1">Password:</label>
					<input type="password" id="password1" name="password1" onBlur="checkPW()"/><div class="inputError" id="PW"></div><br />
				<label for="password2">Password (retype):</label>
					<input type="password" id="password2" name="password2" onBlur="checkPW2()"/><div class="inputError" id="PW2"></div><br />
			</fieldset>
			<input class="button submitbtn" type="submit" id="submit" value="Submit" name="submit"/>
		</form>