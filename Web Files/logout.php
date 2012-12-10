<?php
	session_start();
	
	// Checks if the user is logged in
	if(isset($_SESSION['SID']))
	{
		include ("dbconnect.php");
		$logoutcon = $conn;
	
		$sessID = $_SESSION['SID'];
		
		$sql = "delete from SESSIONS where SESSIONID = :sessID";
		$logout = oci_parse($logoutcon, $sql);
		
		oci_bind_by_name($logout, ":sessID", $sessID);
		
		oci_execute($logout);
		oci_free_statement($logout);
		
		unset($_SESSION['SID']);
		setCookie("SID", "", time() - 3600);
		
		oci_close($logoutcon);
	}
	
	header("Location: login.html");
?>