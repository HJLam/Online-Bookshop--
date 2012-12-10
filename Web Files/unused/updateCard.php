<?php
	session_start();
	include("functions.php");
	
	function cardExists($cardNo, $conn)
	{
		$dbcon = $conn;
		$sql = "select COUNT(*) AS HASCARD from CREDIT_CARD where CARDNO = '$cardNo'";
		$cards = oci_parse($dbcon, $sql);
		oci_define_by_name($cards, "HASCARD", $hasCard);
		oci_execute($cards);
		oci_fetch($cards);
		oci_free_statement($cards);
		
		return $hasCard;
	}
	
	if(isset($_SESSION['SID']))
	{
		if(isset($_POST["creditNo"]))
		{
			include("dbconnect.php");
			
			$dbcon = $conn;
			
			$cardNo = $_POST["creditNo"];
			
			if(!cardExists($cardNo, $dbcon))
			{
				$email = getEmail($_SESSION['SID'], $dbcon);
				$cardID = generateCardId();
				$expiry = $_POST["month"] . '/'. $_POST["year"];
				$cvv = $_POST["cvv"];
				$cardName = $_POST["cardName"];
				
				$sql = "INSERT INTO CREDIT_CARD VALUES('$cardID', '$email', '$cardNo', '$cardName', '$expiry', '$cvv')";
		
				$register = oci_parse($dbcon, $sql);
				oci_execute($register);
				oci_free_statement($register);
				
				oci_close($dbcon);
				
				header("Location: browse.php?card=1");
			}
			else
			{
				oci_close($dbcon);
				
				echo "<script>alert('This card has already been registered. Please register another card.')</script>";
				echo "<script>window.history.back()</script>";
			}
		}
		else
			header("Location: browse.php");
	}
	else
		header("Location: login.html");
?>