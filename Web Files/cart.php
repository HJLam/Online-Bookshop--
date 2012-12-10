<?php
	session_start();
	include("functions.php");
	
	function deleteButton($invoice, $product, $type, $quantity, $price)
	/*
		Prints a delete button to an associated product in the cart.
		
		Param: the invoice the product appears on, the product itself, the product type 
				(or top-level category it is), the number that was to be purchase, and its
				price with respect to quantity.
	*/
	{
		?>
		
		<td>
			<form method = 'POST' action = 'updateCart.php'>
			<input type = 'hidden' value = '<?php echo $product; ?>' name = 'deleteCart'>
			<input type = 'hidden' value = '<?php echo $invoice; ?>' name = 'invoice'>
			<input type = 'hidden' value = '<?php echo $type; ?>' name = 'type'>
			<input type = 'hidden' value = '<?php echo $quantity; ?>' name = 'quantity'>
			<input type = 'hidden' value = '<?php echo $price; ?>' name = 'price'>
			<input type = 'submit' value = 'Remove'>
			</form>
		</td>
		
		<?php
	}
	
?>
<html>
	<head>
	<title>Shopping Cart - Online Bookshop +</title>
	<link rel = "stylesheet" type = "text/css" href = "obsstyle.css">
	<script type = "text/javascript" src = "functions.js"></script>
	</head>
	
	<body>
	<div id = "header">
		<p id = "admin">COMP344 Assignment 2 2012<br />Horhou Lam<br />42491428</p>
	
		<h1>Online Bookshop +</h1>
		<h2>Shopping Cart</h2>
	</div>

	<?php
		linkList();
		
		// checks if user is logged in
		if(isset($_SESSION['SID']))
		{
			include("dbconnect.php");
			
			logoutButton();
			
			$dbcon = $conn;
			$session = $_SESSION['SID'];
			$email = getEmail($session, $dbcon);
			
			// Checks if a cart exists
			if(hasOpenCart($email, $dbcon))
			{
				$invoice = getInvoice($email, $dbcon);
				
				$sql = "select PICTURE, I.PRODUCTID, I.CATID, P.PRODNAME, QUANTITY, PRICE
						from PRODUCT P, ITEM I, SHOPPING_CART S
						where I.PRODUCTID = P.PRODUCTID
						and I.PRODUCTID = S.PRODUCTID
						and I.CATID = S.CATID
						and S.INVOICENO = :invoice
						order by P.PRODNAME asc";
				$listCart = oci_parse($dbcon, $sql);
				
				oci_bind_by_name($listCart, ":invoice", $invoice);
				
				oci_execute($listCart);
				?>
				
				<div id = 'main'>
				<table border = '1'>
					<tr>
						<td></td>
						<th>PRODUCT</th>
						<th>AMOUNT</th>
						<th>PRICE</th>
					</tr>
				
				<?php
				while(($row = oci_fetch_array($listCart)))
				{
					$prodID = $row["PRODUCTID"];
					$type = $row["CATID"];
					$quantity = $row["QUANTITY"];
					$price = $row["PRICE"];
					
					echo "<tr>";
					displayPicture($row["PICTURE"]);
					itemLinker($row["PRODNAME"], $prodID, $type);
					display($quantity);
					display($price);
					deleteButton($invoice, $prodID, $type, $quantity, $price);
					
					echo "</tr>";
				}
				oci_free_statement($listCart);
				
				$total = getTotal($invoice, $dbcon);
				?>
				
					<tr>
						<td colspan = 2></td>
						<th>Total Price:</th>
						<td>$<?php echo $total; ?></td>
					</tr>
				</table>
				</div>
					
				<div id = 'payment'>
				<form method = 'POST' name = 'cardForm' action = 'confirm.php' onsubmit = 'return validateCard()'>
				<table>
					<tr><td>Credit Card No:</td>
						<td><input type = 'text' name = 'creditNo' maxlength = 16></td>
						<td><div id = 'creditNoMsg' style = 'color: Red'></div></td></tr>
					<tr><td>Expiry Date:</td>
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
						<option value = 12>2012</option>
						<option value = 13>2013</option>
						<option value = 14>2014</option>
						<option value = 15>2015</option>
						<option value = 16>2016</option>
						<option value = 17>2017</option>
						<option value = 18>2018</option>
						</select></td>
						<td><div id = 'expiryMsg' style = 'color: Red'></div></td></tr>
					<tr><td>CVV:</td>
						<td><input type = 'text' name = 'cvv' maxlength = 3 size = 3></td>
						<td><div id = 'cvvMsg' style = 'color: Red'></div></td></tr>
				</table>
				<input type = 'hidden' value = '<?php echo $invoice; ?>' name = 'invoice'>
				<input type = 'hidden' value = '<?php echo $total; ?>' name = 'total'>
				<input type = 'submit' value = 'Confirm'>
				</form>
				</div>
				
				<?php
				// If the card entered contained invalid values
				if(isset($_GET["fail"]))
				{
					if($_GET["fail"] == 101)
						echo "<script>document.getElementById(\"creditNoMsg\").innerHTML=\"Invalid Credit Card No\"</script>";
					else if($_GET["fail"] == 102)
						echo "<script>document.getElementById(\"expiryMsg\").innerHTML=\"Invalid Expiry Date\"</script>";
					else if($_GET["fail"] == 109)
						echo "<script>document.getElementById(\"cvvMsg\").innerHTML</script>";
				}
			}
			else
				echo "<div id = 'main'><p>You have no items in your cart.</p></div>";
				
			oci_close($dbcon);
		}
		else
			notLoggedIn();
	?>

	</body>
</html>