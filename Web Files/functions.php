<?php
	define("UNITPRICE", 0);
	define("STOCKNO", -1);
	
	function display($value)
	/*
		Writes a value on the page.
		
		Param: the value that needs to be written.
	*/
	{
		echo "<td>" . $value . "</td>";
	}
	
	function displayPicture($img)
	/*
		Draws the product picture on the page.
		
		Param: location of the picture.
	*/
	{
		echo "<td><img src = $img height = '42' width = '42' /></td>";
	}
	
	function generateCardId()
	/*
		Generates a random 5 digit id.
		
		Return: a random 5 digit id.
	*/
	{
		$alphabet = '0123456789';
		
		$cardID = 'C';
		
		for($i = 0; $i < 4; $i++)
			$cardID .= $alphabet[mt_rand(0, strlen($alphabet) - 1)];
		
		return $cardID;
	}
	
	function getEmail($session, $conn)
	/*
		Gets the user's email from their session id.
		
		Param: session id of the logged in user, and database connection.
		Return: the email of the logged in user.
	*/
	{
		$dbcon = $conn;
		
		$sql = "select EMAIL 
				from SESSIONS 
				where SESSIONID = :sessID";
		$getEmail = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($getEmail, ":sessID", $session);
		oci_define_by_name($getEmail, "EMAIL", $email);
		
		oci_execute($getEmail);
		oci_fetch($getEmail);
		oci_free_statement($getEmail);
		
		return $email;
	}
	
	function getInvoice($email, $conn)
	/*
		Gets an invoice number of an unpaid shopping cart for the user assuming that one already exists.
		
		Param: email of the user in question, and database connection.
		Return: invoice number to the shopping cart.
	*/
	{
		$dbcon = $conn;
		
		$sql = "select INVOICENO 
				from INVOICE 
				where EMAIL = :email and PAID = 0";
		$invoice = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($invoice, ":email", $email);
		oci_define_by_name($invoice, "INVOICENO", $invoiceNo);
		
		oci_execute($invoice);
		oci_fetch($invoice);
		oci_free_statement($invoice);
		
		return $invoiceNo;
	}
	
	function getNumber($attString, $location)
	/*
		Given the string containing the product attributes, gets either the stock number or the unit price of a product.
		
		Param: the attributes string, and the index of the attribute.
		Result: the stock number if location is -1, else unit price. 
	*/
	{
		$attArray;
		
		if($location == STOCKNO)
			$attArray = explode(':-', $attString, $location);
		else
			$attArray = explode(':-', $attString);
		
		return floatval(end($attArray));
	}
	
	function getTotal($invoice, $conn)
	/*
		Gets the total price of an invoice.
		
		Param: invoice in question, and database connection.
		Return: the total price.
	*/
	{
		$dbcon = $conn;
		
		$sql = "select TOTALPRICE 
				from INVOICE 
				where INVOICENO = :invoice";
		$getTotal = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($getTotal, ":invoice", $invoice);
		oci_define_by_name($getTotal, "TOTALPRICE", $total);
		
		oci_execute($getTotal);
		oci_fetch($getTotal);
		oci_free_statement($getTotal);
		
		return $total;
	}
	
	function hasOpenCart($email, $conn)
	/*
		Checks if the user has a shopping cart and it has not yet been paid.
		
		Param: email of the user in question and database connection.
		Return: true if a cart exist, false if not.
	*/
	{
		$dbcon = $conn;
		
		$sql = "select COUNT(*) AS HASCART 
				from INVOICE 
				where EMAIL = :email and PAID = 0";
		$invoice = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($invoice, ":email", $email);
		oci_define_by_name($invoice, "HASCART", $hasCart);
		
		oci_execute($invoice);
		oci_fetch($invoice);
		oci_free_statement($invoice);
		
		return $hasCart;
	}
	
	function itemLinker($prodName, $prodID, $catID)
	/*
		Creates a link of the product to its respective page.
		
		Param: the name of the link, the product id and the category id.
	*/
	{
		$anchor = "viewItem.php?pid=$prodID&cid=$catID";
		
		echo "<td><a href ='" . $anchor . "'>" . $prodName . "</a></td>";
	}
	
	function linkList()
	/*
		Draws the navigation bar on the page.
	*/
	{?>
		<div id = 'links'>
			<ul>
		
		<?php if(!isset($_SESSION['SID']))
			echo "<li><a href = 'login.html'>Login</a></li>";?>
		
				<li>Add another Credit Card</li>
				<li><a href = 'browse.php'>Browse</a></li>
				<li><a href = 'cart.php'>Shopping Cart</a></li>
			</ul>
		</div>
	<?php
	}
	
	function logoutButton()
	/*
		Draws the logout button on the page.
	*/
	{?>
		<div id = 'logout'>
			<form method = 'link' action = 'logout.php'>
				<input type = 'submit' value = 'Logout'>
			</form>
		</div>
	<?php
	}
	
	function notLoggedIn()
	/* 
		The default page given when a user is not logged in. 
	*/
	{?>
		<div id = 'main'>
			<p>Sorry, you need to be a registered user to view this page.</p>
			<a href = 'register.html'>Register here</a>
		</div>
	<?php
	}
?>