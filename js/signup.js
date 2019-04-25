//This function checks if the users username is within the character limit we allow for usernames.
function checkUN(){
	var element=document.getElementById("username");
	var message=document.getElementById("UN");
	if(validateLength(3,30,element,message)){
		message.innerHTML="";
		return true;
	} else {
		return false;
	}
}

//This function will first check to see if the confirm password field matches the first password field
function checkPW(){
	var element=document.getElementById("password1");
	var message=document.getElementById("PW");
	checkPW2();
	
	//We then check if the user's password is within the character limit we allow
	if(validateLength(5,30,element,message)){
		message.innerHTML="";
		return true;
	} else {
		return false;
	}
}

//This function checks if both password fields match.
function checkPW2(){
	var element1=document.getElementById("password1");
	var element2=document.getElementById("password2");
	var message=document.getElementById("PW2");
	
	//Tell the user their passwords do not match
	if(element1.value != element2.value){
		message.innerHTML="Your passwords do not match each other. Please enter both again.";
		return false;
	} else {
		message.innerHTML="";
		return true;
	}
}

//This function will take the email field, see if it is within the character we allow, then check if it is actually an email with a regular expression
function checkEM(){
	var element=document.getElementById("email");
	var message=document.getElementById("EM");
	if(validateLength(0,60,element,message)){
		
		//Check if the email is a valid email address with a regular expression.
		if(validateRegEx(/^[\w\.-_\+]+@[\w-]+(\.\w{2,3})+$/,element.value, message, "Please enter a valid email address.")){
			message.innerHTML="";
			return true
		} else {
			return false;
		}
	} else {
		return false;
	}
}

//Check if all fields are set correctly
function checkAll(){
	if(checkUN() && checkPW() && checkPW2() && checkEM()){
		return true;
	} else {
		//I'm not sure why we are checking these again because all error messages would have already displayed by now if there was an error
		checkPW();
		checkPW2();
		checkEM();
		return false;
	}
}

//I'm also not sure why this is here since this is just redundant. It must have had a purpose besides just checking email again but that other purpose is gone now.
function checkAll2(){
	if(checkEM()){
		return true;
	} else {
		return false;
	}
}

//This is used I believe when the user needs to change their password but again it seems redundant to have a separate function rather than just calling the other 2 functions
function checkAll3(){
	if(checkPW() && checkPW2()){
		return true;
	} else {
		return false;
	}
}

function validateLength(minLength, maxLength, inputField, helpText) {
	// See if the input value contains at least minLength but no more than maxLength characters
	return validateRegEx(new RegExp("^.{" + minLength + "," + maxLength + "}$"),
	inputField.value, helpText,
	"Please enter a value " + minLength + " to " + maxLength + " characters in length.");
}

function validateRegEx(regex, input, helpText, helpMessage) {
	// See if the email inputed validates OK
	if (!regex.test(input)) {
		// The data is invalid, so send an error to the user
		if (helpText != null){
			helpText.innerHTML = helpMessage;
			return false;
		}
	} else {
		// The data is OK, so clear the help message and return true
		if (helpText != null){
			helpText.innerHTML = "";
			return true;
		}
	}
}