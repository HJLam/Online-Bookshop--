<?php
	session_start();
	
	function generateInvoiceNo()
	/*
		Generates a random 5 digit invoice number.
		
		Return: a random 5 digit invoice number.
	*/
	{
		$alphabet = '0123456789';
		
		$invoiceNo = 'I';
		
		for($i = 0; $i < 4; $i++)
			$invoiceNo .= $alphabet[mt_rand(0, strlen($alphabet) - 1)];
		
		return $invoiceNo;
	}
	
	function itemInCart($product, $invoice, $type, $conn)
	/*
		Checks to see if a particular item is already in the cart.
		
		Param: the product in question, the invoice associated, the type of product, 
				and database connection.
		Return: 1 if the item is in the cart, 0 if not.
	*/
	{
		$dbcon = $conn;
		
		$sql = "select COUNT(*) AS INCART 
				from SHOPPING_CART 
				where INVOICENO = :invoice 
				and PRODUCTID = :product
				and CATID = :type";
		$cart = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($cart, ":invoice", $invoice);
		oci_bind_by_name($cart, ":product", $product);
		oci_bind_by_name($cart, ":type", $type);
		oci_define_by_name($cart, "INCART", $inCart);
		
		oci_execute($cart);
		oci_fetch($cart);
		oci_free_statement($cart);
		
		return $inCart;
	}
	
	function deleteInvoiceIfEmpty($invoice, $conn)
	/*
		If all of the items in an invoice have been deleted, then the invoice is also deleted.
		
		Param: the invoice in question, and database connection.
	*/
	{
		$dbcon = $conn;
		
		$sql = "select COUNT(*) AS ISEMPTY 
				from SHOPPING_CART 
				where INVOICENO = :invoice";
		$count = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($count, ":invoice", $invoice);
		oci_define_by_name($count, "ISEMPTY", $isEmpty);
		
		oci_execute($count);
		oci_fetch($count);
		oci_free_statement($count);
		
		if(!$isEmpty)
		{
			$sql = "delete from INVOICE 
					where INVOICENO = :invoice";
			$delete = oci_parse($dbcon, $sql);
			
			oci_bind_by_name($delete, ":invoice", $invoice);
			
			oci_execute($delete);
			oci_free_statement($delete);
		}
	}
	
	function updateStock($conn, $attString, $prodID, $type)
	/*
		Updates the stock number for a given product.
		
		Param: database connection, the new attribute string, containing the updated stock number,
				the product id, and product type (top-level category).
	*/
	{
		$dbcon = $conn;
		
		$sql = "update ITEM set ATTVAL = :attString 
				where PRODUCTID = :prodID
				and CATID = :type";
		$updateProd = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($updateProd, ":attString", $attString);
		oci_bind_by_name($updateProd, ":prodID", $prodID);
		oci_bind_by_name($updateProd, ":type", $type);
		
		oci_execute($updateProd);
		oci_free_statement($updateProd);
	}
	
	function addToCart($conn)
	/*
		Adds a product to the shopping cart, where all necessary inputs are retrieved from form data.
		
		Param: database connection.
	*/
	{
		$prodID = $_POST["addCart"];
		$type = $_POST["type"];
		$attString = $_POST["attString"];
		$price = $_POST["price"];
		$quantity = $_POST["quantity"];
		$session = $_SESSION['SID'];
		
		$dbcon = $conn;
		
		$email = getEmail($session, $dbcon);
		
		if(!hasOpenCart($email, $dbcon))
		{
			$invoiceNo = generateInvoiceNo();
			
			$sql = "insert into INVOICE values(:invoiceNo, 'C0000', :email, '0', '0')";
			$createInvoice = oci_parse($dbcon, $sql);
			
			oci_bind_by_name($createInvoice, ":invoiceNo", $invoiceNo);
			oci_bind_by_name($createInvoice, ":email", $email);
			
			oci_execute($createInvoice);
			oci_free_statement($createInvoice);
		}
		
		$price *= $quantity;
		$invoiceNo = getInvoice($email, $dbcon);
		
		if(itemInCart($prodID, $invoiceNo, $type, $dbcon))
		{
			$sql = "update SHOPPING_CART 
					set QUANTITY = QUANTITY + :quantity, PRICE = PRICE + :price
					where INVOICENO = :invoiceNo
					and PRODUCTID = :prodID
					and CATID = :type";
			$updateCart = oci_parse($dbcon, $sql);
			
			oci_bind_by_name($updateCart, ":quantity", $quantity);
			oci_bind_by_name($updateCart, ":price", $price);
			oci_bind_by_name($updateCart, ":invoiceNo", $invoiceNo);
			oci_bind_by_name($updateCart, ":prodID", $prodID);
			oci_bind_by_name($updateCart, ":type", $type);
			
			oci_execute($updateCart);
			oci_free_statement($updateCart);
		}
		else
		{
			$sql = "insert into SHOPPING_CART values (:invoiceNo, :prodID, :type, :quantity, :price)";
			$addCart = oci_parse($dbcon, $sql);
			
			oci_bind_by_name($addCart, ":invoiceNo", $invoiceNo);
			oci_bind_by_name($addCart, ":prodID", $prodID);
			oci_bind_by_name($addCart, ":type", $type);
			oci_bind_by_name($addCart, ":quantity", $quantity);
			oci_bind_by_name($addCart, ":price", $price);
			
			oci_execute($addCart);
			oci_free_statement($addCart);
		}
	
		$attVal = explode(':-', $attString);
		$attVal[count($attVal)-2] -= $quantity;
		$attributes = implode(':-', $attVal);
		
		updateStock($dbcon, $attributes, $prodID, $type);
		
		$sql = "update INVOICE 
				set TOTALPRICE = TOTALPRICE + :price 
				where INVOICENO = :invoiceNo";
		$updateInvoice = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($updateInvoice, ":price", $price);
		oci_bind_by_name($updateInvoice, ":invoiceNo", $invoiceNo);
		
		oci_execute($updateInvoice);
		oci_free_statement($updateInvoice);
		
		oci_close($dbcon);
		
		header("Location: viewItem.php?pid=$prodID&cid=$type&submitted=1");
	}
	
	function deleteFromCart($conn)
	/*
		Deletes a product from the shopping cart, where all necessary inputs are retrieved from form data.
		
		Param: database connection.
	*/
	{
		$prodID = $_POST['deleteCart'];
		$invoice = $_POST['invoice'];
		$type = $_POST['type'];
		$quantity = $_POST['quantity'];
		$price = $_POST['price'];
		
		$dbcon = $conn;
		
		$sql = "select ATTVAL from ITEM 
				where PRODUCTID = :prodID
				and CATID = :type";
		$getAtts = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($getAtts, ":prodID", $prodID);
		oci_bind_by_name($getAtts, ":type", $type);
		oci_define_by_name($getAtts, "ATTVAL", $attString);
		
		oci_execute($getAtts);
		oci_fetch($getAtts);
		oci_free_statement($getAtts);
		
		$attArray = explode(':-', $attString);
		$attArray[count($attArray)-2] += $quantity;
		$attributes = implode(':-', $attArray);
		
		updateStock($dbcon, $attributes, $prodID, $type);
		
		$sql = "update INVOICE 
				set TOTALPRICE = TOTALPRICE - :price 
				where INVOICENO = :invoice";
		$updateInvoice = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($updateInvoice, ":price", $price);
		oci_bind_by_name($updateInvoice, ":invoice", $invoice);
		
		oci_execute($updateInvoice);
		oci_free_statement($updateInvoice);
		
		$sql = "delete from SHOPPING_CART 
				where INVOICENO = :invoice 
				and PRODUCTID = :prodID
				and CATID = :type";
		$deleteCart = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($deleteCart, ":invoice", $invoice);
		oci_bind_by_name($deleteCart, ":prodID", $prodID);
		oci_bind_by_name($deleteCart, ":type", $type);
		
		oci_execute($deleteCart);
		oci_free_statement($deleteCart);
		
		deleteInvoiceIfEmpty($invoice, $dbcon);
		
		header("Location: cart.php");
	}
	
	//Checks if the user is logged in
	if(isset($_SESSION['SID']))
	{
		include("dbconnect.php");
		include("functions.php");
		
		//Checks if the user added something to the cart
		if(isset($_POST['addCart']))
			addToCart($conn);
		
		//Else checks if the user removed something from the cart
		else if(isset($_POST['deleteCart']))
			deleteFromCart($conn);
		
		//Else deals with the user if they somehow manage to get here
		else
			header("Location: browse.php");
	}
	else
		header("Location: login.html");
?>