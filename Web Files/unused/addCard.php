<?php
	session_start();
	include("functions.php");
?>

<html>
	<head>
	<title>Add Credit Card - Online Bookshop +</title>
	<link rel = "stylesheet" type = "text/css" href = "obsstyle.css">
	<script type = 'text/javascript' src = 'functions.js'></script>
	</head>
	
	<body>
	<div id = "header">
		<p id = "admin">COMP344 Assignment 2 2012<br />Horhou Lam<br />42491428</p>
	
		<h1>Online Bookshop +</h1>
		<h2>Add Credit Card</h2>
	</div>
	
	<?php
		linkList();
		
		if(isset($_SESSION['SID']))
		{
			logoutButton();
			
			echo "<div id = 'main'><form method = 'POST' name = 'addCard' action = 'updateCard.php' onsubmit = 'return validateCard()'>";
			echo "<p>NOTE: Currently, credit card details entered here will not be used in payment processing. 
					Also, cards entered here consist of a 10 digit number rather than the usual 16, as such is invalid anyway.</p>";
			echo "<table>";
			echo "<tr><td>Credit Card No:</td>
				<td><input type = 'text' name = 'creditNo' maxlength = 10 onchange = 'checkCreditNo(this)'></td>
				<td><div id = 'creditNoMsg' style = 'color: Red'></div></td></tr>";
			echo "<tr><td>Expiry Date:</td>
				<td><select name = 'month'>
				<option value = 01>01</option>
				<option value = 02>02</option>
				<option value = 03>03</option>
				<option value = 04>04</option>
				<option value = 05>05</option>
				<option value = 06>06</option>
				<option value = 07>07</option>
				<option value = 08>08</option>
				<option value = 09>09</option>
				<option value = 10>10</option>
				<option value = 11>11</option>
				<option value = 12>12</option>
				</select>/
				<select name = 'year'>
				<option value = 2012>2012</option>
				<option value = 2013>2013</option>
				<option value = 2014>2014</option>
				<option value = 2015>2015</option>
				<option value = 2016>2016</option>
				<option value = 2017>2017</option>
				<option value = 2012>2018</option>
				</select></td>
				<td><div id = 'expiryMsg' style = 'color: Red'></div></td></tr>";
			echo "<tr><td>CVV:</td>
				<td><input type = 'text' name = 'cvv' maxlength = 3 size = 3 onchange = 'checkCVV(this)'></td>
				<td><div id = 'cvvMsg' style = 'color: Red'></div></td></tr>";
		
			echo "<tr><td>Give a name to your card:</td>
				<td><input type = 'text' name = 'cardName'></td></tr>";
		
			echo "<tr><td><input type = 'submit' value = 'Add Card' /> </td></tr>";
			echo "</table></form></div>";
			
		}
		else
			notLoggedIn();
	
	?>
	
	</body>
</html>