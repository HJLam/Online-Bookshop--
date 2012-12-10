<?php
	session_start();
	
	function generateSessionId()
	/*
		Generates a random 5 character session id.
		
		Return: a random 5 character id.
	*/
	{
		$alphabet = 'abcdefghijklmnopqrstuvwxyz0123456789';
		
		$sessID = '';
		
		for($i = 0; $i < 5; $i++)
			$sessID .= $alphabet[mt_rand(0, strlen($alphabet) - 1)];
		
		return $sessID;
	}
	
	function validate($conn, $email, $password)
	/*
		Given the email and password, it checks if they match the corresponding values in the database.
		
		Param: the email and password in question, and the database connection.
		Return: true if sucessful, else false.
	*/
	{
		$isValid = false;
		
		$sql = "select PASSWORD 
				from USERS 
				where EMAIL = :email";
		$loginDetails = oci_parse($conn, $sql);
		
		oci_bind_by_name($loginDetails, ":email", $email);
		oci_define_by_name($loginDetails, "PASSWORD", $hashedPass);
		
		oci_execute($loginDetails);
		oci_fetch($loginDetails);
		oci_free_statement($loginDetails);
		
		if(crypt($password, $hashedPass) == $hashedPass)
			$isValid = true;
		
		return $isValid;
	}
	
	// if the user is logged in, then another session won't be created
	if(isset($_SESSION['SID']))
		header("Location: browse.php");
	
	include("dbconnect.php");
	
	$logincon = $conn;
	
	$email = $_POST["email"];
	$password = $_POST["password"];
	
	// Checks that the login information is valid
	if(validate($logincon, $email, $password))
	{	
		$sessionID = generateSessionId();
		
		$sql = "insert into SESSIONS values (:sessionID, :email)";
		$createSess = oci_parse($logincon, $sql);
		
		oci_bind_by_name($createSess, ":sessionID", $sessionID);
		oci_bind_by_name($createSess, ":email", $email);
		
		oci_execute($createSess);
		oci_free_statement($createSess);
		
		$_SESSION['SID'] = $sessionID;
		
		setCookie('SID', $sessionID);
		
		oci_close($logincon);
		
		header("Location: browse.php");
		
	}
	else
	{
		oci_close($logincon);
		
		include("login.html");
		echo "<script>alert('Invalid login, please try again.')</script>";
	}
	
	
?>