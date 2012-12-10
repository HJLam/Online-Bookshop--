<?php
	session_start();
	include("functions.php");
	
	function makeHtmlHead($title)
	/*
		Prints out the appropriate html head section, given the correct product title.
		
		Param: the title of the product in question.
	*/
	{?>
		<html>
		<head>
			<title><?php echo $title; ?> - Online Bookshop +</title>
			<link rel = 'stylesheet' type = 'text/css' href = 'obsstyle.css'>
			<script type = 'text/javascript' src = 'functions.js'></script>
		</head>
		<body>
			<div id = 'header'>
			<p id = 'admin'>COMP344 Assignment 2 2012<br />Horhou Lam<br />42491428</p>
			<h1>Online Bookshop +</h1>
			<h2><?php echo $title; ?></h2>
			</div>
		
		<?php
		linkList();
	}
	
	function displayProductInfo($product, $attName, $numbAtts)
	/*
		Prints out the properties of a product, given the product itself, its attributes, and
		the number of attributes it has.
		
		Param: the product to be displayed, the names of its attributes, and the number of attributes
				it has.
	*/
	{
		$attString = $product["ATTVAL"];
		$attVal = explode(':-', $attString);
		
		$stock = getNumber($product["ATTVAL"], STOCKNO);
		$price = getNumber($product["ATTVAL"], UNITPRICE);
		$item = $product["PRODUCTID"];
		$type = $product["CATID"];
		?>
		
		<div id = 'main'>
		<table>
			<tr>
			<td><img src = '<?php echo $product["PICTURE"]; ?>' height = '250' width = '250'/></td>
			<td><table>
		
		<?php
		for($i = 0; $i < $numbAtts - 2; $i++)
			echo "<tr><th>",$attName["ATTNAME"][$i],":</th><td>",$attVal[$i],"</td></tr>";
		
		if($stock == 0)
			echo "<tr><th>Available Stock:</th><td>SOLD OUT</td></tr>";
		else
			echo "<tr><th>Available Stock:</th><td>",$stock,"</td></tr>";
		?>
		
				<tr><th>Price:</th><td>$<?php echo $price; ?></td></tr>
		
				<form method = 'POST' name = 'addToCart' action = 'updateCart.php' onsubmit = 'return validAdd($stock)'>
				<input type = 'hidden' value = '<?php echo $item; ?>' name = 'addCart'>
				<input type = 'hidden' value = '<?php echo $type; ?>' name = 'type'>
				<input type = 'hidden' value = '<?php echo $attString; ?>' name = 'attString'>
				<input type = 'hidden' value = '<?php echo $price; ?>' name = 'price'>
				
				<tr><th>Quantity:</th>
				<td><input type = 'text' value = 1 name = 'quantity' maxlength = 3 size = 3 onchange = 'checkQuantity(this, $stock)'>
				<input type = 'submit' value = 'Add to Cart'></td>
				<td><div id = 'quantmsg' style = 'color:Red'></div></td></tr>
				</form>
		
				</table>
			</td>
			</tr>
		</table>
		</div>
		
		<?php
	}
	
	function validItem($pid, $cid, $conn)
	/*
		Checks to ensure that a product given from the query string actually exists.
		
		Param: the product, and its category.
		Return: true or false.
	*/
	{
		$dbcon = $conn;
		
		$sql = "select COUNT(*) as VALID
				from ITEM 
				where PRODUCTID = :itemNo
				and CATID = :itemType";
		$product = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($product, ":itemNo", $pid);
		oci_bind_by_name($product, ":itemType", $cid);
		oci_define_by_name($product, "VALID", $valid);
		
		oci_execute($product);
		oci_fetch($product);
		oci_free_statement($product);
		
		return $valid;
	}
	
	// Checks if user is logged in
	if(isset($_SESSION['SID']))
	{
		include("dbconnect.php");
			
		$dbcon = $conn;
		
		// Checks if an item is selected AND it exists
		if(isset($_GET["pid"]) && isset($_GET["cid"]) && validItem($_GET["pid"], $_GET["cid"], $dbcon))
		{
			$itemNo = $_GET["pid"];
			$itemType = $_GET["cid"];
			
			$sql = "select ATTNAME 
					from ATTRIBUTENAME 
					where CATID = :itemType";
			$attNames = oci_parse($dbcon, $sql);
			
			oci_bind_by_name($attNames, ":itemType", $itemType);
			
			oci_execute($attNames);
			
			$numbAtts = oci_fetch_all($attNames, $attributes);
			
			oci_free_statement($attNames);
			
			$sql = "select I.PRODUCTID, CATID, PRODNAME, ATTVAL, PICTURE 
					from PRODUCT P, ITEM I 
					where I.PRODUCTID = :itemNo
					and CATID = :itemType
					and I.PRODUCTID = P.PRODUCTID";
			$product = oci_parse($dbcon, $sql);
			
			oci_bind_by_name($product, ":itemNo", $itemNo);
			oci_bind_by_name($product, ":itemType", $itemType);
			
			oci_execute($product);
			
			$prodinfo = oci_fetch_array($product);
			
			makeHtmlHead($prodinfo["PRODNAME"]);
			
			logoutButton();
			
			displayProductInfo($prodinfo, $attributes, $numbAtts);
			
			if(isset($_GET["submitted"]) && $_GET["submitted"] == 1)
				echo "<script>itemAdded()</script>";
			
			oci_free_statement($product);
			oci_close($dbcon);
		}
		else
		{
			makeHtmlHead('View Item');
			logoutButton();
			?>
			
			<div id = 'main'>
			<p>You have not selected an item.</p>
			</div>
			
			<?php
		}
	}
	else
	{
		makeHtmlHead('View Item');
		notLoggedIn();
	}
?>
	
</body>
</html>