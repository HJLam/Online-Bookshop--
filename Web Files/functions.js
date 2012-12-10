// ---Functions for login.html and register.html---
function getCookie(cookieName)
//This function is derived from w3schools implementation on reading cookies using JavaScript
{
	var cookies = document.cookie.split(";");
	var value = "";
	
	for (var i=0; i < cookies.length; i++)
	{
	  var cookieID = cookies[i].substr(0, cookies[i].indexOf("="));
	  var cookieVal = cookies[i].substr(cookies[i].indexOf("=") + 1);
	  cookieID = cookieID.replace(/^\s+|\s+$/g,"");
	  
	  if (cookieID == cookieName)
		value = unescape(cookieVal);
	}
	
	return value;
}

/*
	*** A8 - if the user tries to access a page with an existing session cookie, then by
	by default it'll return them to browse.php and not allow them to login under a different
	account or register another one.
*/
function checkCookie()
/*
	Checks if a session cookie exists, if it does then redirect to the browse page.
*/
{
	var sid = getCookie('SID');
	
	if(sid != "")
		window.location = "browse.php";
}

// ---Functions for register.html---
function checkEmail(inputMail)
/*
	Checks the email address to ensure it conforms the standard.
	ie. first.last@mq.edu.au or first.last@students.mq.edu.au
	
	Param: the email address that was given by the user.
	Return: true if the email is valid, else false.
*/
{
	var email = inputMail.value;
	var validEmailExp = /([a-z])+\.([a-z])+([0-9])*@(students.)?(mq.edu.au)$/g;
	
	if(!validEmailExp.test(email))
	{
		document.getElementById("emailMsg").innerHTML = "Invalid Email, please try again.";
		return false;
	}
	
	document.getElementById("emailMsg").innerHTML = "";
	return true;
}

function checkPassword(inputPass)
/*
	Given a password, it checks to ensure it conforms to the requirements.
	ie. must be greater than 6 characters, contain a number and letter.
	
	Param: the password that was given by the user.
	Return: true if the password is valid, else false.
*/
{
	var password = inputPass.value;
	var validPassExp = /^(?=.*\d)(?=.*[a-zA-Z]).{6,}$/g;
	
	if(!validPassExp.test(password))
	{
		document.getElementById("passwordMsg").innerHTML = "Invalid password, must be at least 6 characters and contain at least 1 letter and 1 number.";
		document.forms["registration"]["confPass"].value = "";
		
		return false;
	}
	else
	{
		document.getElementById("passwordMsg").innerHTML = "";
		document.forms["registration"]["confPass"].focus();
		
		return true;
	}
}

function confirmPassword(inputPass)
/*
	Checks to ensure that both passwords match.
	
	Param: the second password input.
	Return: true if the passwords match, else false.
*/
{
	var password = document.forms["registration"]["password"].value;
	var confPass = inputPass.value;
	
	if(password != confPass)
	{
		document.getElementById("confPassMsg").innerHTML = "Passwords do not match, please try again.";
		return false;
	}
	
	document.getElementById("confPassMsg").innerHTML = "";
	return true;
}

function checkHouseNum(inputNum)
/*
	Checks to ensure that the house number is indeed a number.
	
	Param: the given number.
	Return: true if the input is a valid number, else false.
*/
{
	var unitNo = inputNum.value;
	var validNo = /([0-9])+(\/([0-9])+)?$/;
	
	if(!validNo.test(unitNo))
	{
		document.getElementById("unitNoMsg").innerHTML = "Not a proper number, please try again.";
		return false;
	}
	
	document.getElementById("unitNoMsg").innerHTML = "";
	return true;
}

function checkLegalString(inputName, idName)
/*
	Checks that the input is a word only containing letters from the alphabet. An error message is 
	printed if the string is not a valid word.
	
	Param: the input in question, and the error msg block associated to the input.
	Return: true if the input is a word, else false.
*/
{
	var name = inputName.value;
	var validName = /^([a-z])+$/i
	
	if(!validName.test(name))
	{
		document.getElementById(idName).innerHTML = "Invalid, please try again.";
		return false;
	}
	
	document.getElementById(idName).innerHTML = "";
	return true;
}

function checkStreet(inputStreet)
/*
	Checks to ensure that the given street is valid. 
	
	Param: input street
	Return: true if input is a valid street, else false.
*/
{
	var street = inputStreet.value;
	var validStreet = /([a-z])+\s(Street|Avenue|Road|Drive|Parade|Place)$/i;
	
	if(!validStreet.test(street))
	{
		document.getElementById("streetMsg").innerHTML = "Not a proper street, please try again.";
		return false;
	}
	
	document.getElementById("streetMsg").innerHTML = "";
	return true;
}

function checkState(inputState)
/*
	Checks to ensure that a state has been selected.
	
	Param: input selected value.
	Return: true if something was selected, else false.
*/
{
	var state = inputState.value;
	
	if(state == "")
	{
		document.getElementById("stateMsg").innerHTML = "Please select a state.";
		return false;
	}
	
	document.getElementById("stateMsg").innerHTML = "";
	return true;
}

function checkPostCode(inputCode)
/*
	Checks to ensure that a given postcode 4 digits.
	
	Param: input postcode.
	Return: true if the input is valid, else false.
*/
{
	var postcode = inputCode.value;
	
	if(isNaN(postcode) || postcode < 1000)
	{
		document.getElementById("postcodeMsg").innerHTML = "Invalid postcode, please try again.";
		return false;
	}
	
	document.getElementById("postcodeMsg").innerHTML = "";
	return true;
}

function checkCVV(number)
/*
	Checks if the given CVV is 3 digits.
	
	Return: true if valid, else false.
*/
{
	var cvv = number.value;
	
	if(isNaN(cvv) || cvv < 100)
	{
		document.getElementById("cvvMsg").innerHTML = "Please enter a valid number.";
		return false;
	}
	
	document.getElementById("cvvMsg").innerHTML = "";
	return true;
}

function validate()
/*
	Validates the correctness of all input values in the registration form, whether or not the 
	system will complete registration will depend on the output of this function.
	
	Return: true if all the form values are valid, else false.
*/
{
	var regForm = document.forms["registration"];
	
	var valEmail = checkEmail(regForm["email"]);
	var valPass = checkPassword(regForm["password"]);
	var valConf = confirmPassword(regForm["confPass"]);
	var valFname = checkLegalString(regForm["fname"], "fnameMsg");
	var valLname = checkLegalString(regForm["lname"], "lnameMsg");
	var valUnitNo = checkHouseNum(regForm["unitNo"]);
	var valStreet = checkStreet(regForm["street"]);
	var valCity = checkLegalString(regForm["city"], "cityMsg");
	var valState = checkState(regForm["state"]);
	var valPostcode = checkPostCode(regForm["postcode"]);
	
	return valEmail && valPass && valConf && valFname && valLname && valUnitNo && valStreet && valCity && valState && valPostCode;
}

// ---Functions for viewItem.php---
function checkQuantity(input, limit)
/*
	Checks the quantity for the number of copies of a single item that can be selected for a purchase.
	
	Param: the user input and the maximum amount that can be selected.
	Return: true if the amount is valid, else false.
*/
{
	var quant = input.value;
	
	if(isNaN(quant) || quant < 1 || quant > limit)
	{
		document.getElementById("quantmsg").innerHTML = "Please enter a valid amount.";
		return false;
	}
	
	document.getElementById("quantmsg").innerHTML = "";
	return true;
}

function validAdd(limit)
/*
	See above.
*/
{
	var quantity = document.forms["addToCart"]["quantity"];
	return checkQuantity(quantity, limit);
}

function itemAdded()
/*
	Prints an alert informing an item has been added to the cart.
*/
{
	alert("Item added to the shopping cart.");
}

// ---Functions for cart.php---
function checkCreditNo2(number)
/*
	Checks to ensure that the credit card number is of length 16 and only digits.
	
	Param: the input credit card number.
	Return: true if the credit card is valid, else false.
*/
{
	var cardNo = number.value;
	
	if(isNaN(cardNo) || cardNo < 1000000000000000)
	{
		document.getElementById("creditNoMsg").innerHTML = "Please enter a valid number.";
		return false;
	}
	
	document.getElementById("creditNoMsg").innerHTML = "";
	return true;
}

function checkExpiry2()
{
	var month = document.forms["cardForm"]["month"].value;
	var year = document.forms["cardForm"]["year"].value;
	
	var date = new Date();
	var cMonth = date.getMonth() + 1;
	var cYear = date.getFullYear() - 2000;
	
	if(year == cYear && month < cMonth)
	{
		document.getElementById("expiryMsg").innerHTML = "Please enter a valid expiration date.";
		return false;
	}
	else
	{
		document.getElementById("expiryMsg").innerHTML = "";
		return true;
	}
}

function validateCard()
{
	var cardForm = document.forms["cardForm"];
	
	var valCardNo = checkCreditNo2(cardForm["creditNo"]);
	var valExpiry = checkExpiry2();
	var valCVV = checkCVV(cardForm["cvv"]);
	
	return valCardNo && valExpiry && valCVV;
}