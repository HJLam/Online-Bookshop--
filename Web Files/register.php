<?php
	include("functions.php");
	
	function checkEmail($inputmail, $conn)
	/*
		Checks the email address to ensure it conforms the standard.
		ie. first.last@mq.edu.au or first.last@students.mq.edu.au
		
		Param: the email address that was given by the user, and database connection.
		Return: true if the email is valid, else false.
	*/
	{
		$isValid = true;
		$validEmail = "/([a-z])+\.([a-z])+([0-9])*@(students.)?(mq.edu.au)$/";
		
		if(preg_match($validEmail, $inputmail))
		{
			$regemails = oci_parse($conn, 'select EMAIL from USERS');
	
			oci_execute($regemails);
			
			while(($row = oci_fetch_array($regemails)) && $isValid)
				if($inputmail == $row['EMAIL'])
					$isValid = false;
		}
		else
			$isValid = false;
		
		oci_free_statement($regemails);
		
		return $isValid;
	}
	
	function checkPasswords($first, $second)
	/*
		checks that both passwords match and are greater than 6 in length.
		
		Param: both passwords entered by the user.
		Return: true if they match, else false.
	*/
	{
		if(strlen($first) >= 6 && ($first == $second))
			return true;
			
		return false;
	}

	include("dbconnect.php");
	
	$regcon = $conn;
	
	$email = $_POST["email"];
	$password = $_POST["password"];
	$confpass = $_POST["confPass"];
	
	$fname = htmlentities($_POST["fname"]);
	$lname = htmlentities($_POST["lname"]);
	
	$unitNo = htmlentities($_POST["unitNo"]);
	$street = htmlentities($_POST["street"]);
	$city = htmlentities($_POST["city"]);
	$state = htmlentities($_POST["state"]);
	$postcode = htmlentities($_POST["postcode"]);
	
	// Checks both email and password is valid
	if(checkEmail($email, $regcon) && checkPasswords($password, $confpass))
	{
	
		$msg = "Congratulations " . $fname . " " . $lname . "!\nYou are now a registered user of Online Bookshop. Your login information is:\n";
		$msg .= "Login: " . $email . "\n";
		
		$msg .= "Horhou Lam\n42491428\n";
			
		$subject = "Registration Confirmation";
		$from = "From: OnlineBookshopRegistration";
		
		//mail($email, $subject, $msg, $from);
		
		$hashedPass = crypt($password);
		
		$sql = "INSERT INTO USERS VALUES(:email, :fname, :lname, :password)";
		$register = oci_parse($regcon, $sql);
		
		oci_bind_by_name($register, ":email", $email);
		oci_bind_by_name($register, ":fname", $fname);
		oci_bind_by_name($register, ":lname", $lname);
		oci_bind_by_name($register, ":password", $hashedPass);
		
		oci_execute($register);
		oci_free_statement($register);
		
		$sql = "INSERT INTO CUSTOMER VALUES(:email, :unitNo, :street, :city, :state, :postcode)";
		$register = oci_parse($regcon, $sql);
		
		oci_bind_by_name($register, ":email", $email);
		oci_bind_by_name($register, ":unitNo", $unitNo);
		oci_bind_by_name($register, ":street", $street);
		oci_bind_by_name($register, ":city", $city);
		oci_bind_by_name($register, ":state", $state);
		oci_bind_by_name($register, ":postcode", $postcode);
		
		oci_execute($register);
		oci_free_statement($register);
		
		oci_close($regcon);
		
		include("login.php");
	}
	
	else
	{
		oci_close($regcon);
		
		echo "<script>alert('Sorry, there is already an account under the email $email please register again with a different email address.')</script>";
		echo "<script>window.history.back()</script>";
	}

	
?>