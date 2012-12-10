<?php
	session_start();
	include("functions.php");
	
	function categoryList($conn)
	/*
		Prints out all of the categories available for all products in the database.
		
		Param: database category.
	*/
	{
		$dbcon = $conn;
		
		$sql = "select * from CATEGORY order by CATNAME asc";
		$categories = oci_parse($dbcon, $sql);
		oci_execute($categories);
		
		?>
		
		<div id = 'categories'>
		<ul>
			<fieldset>
				<legend>Categories</legend>
				<li><a href = 'browse.php'>All</a></li>
		
		<?php	
		while(($row = oci_fetch_array($categories)))
		{
			$catid = $row["CATID"];
			echo "<li><a href = 'browse.php?cat=$catid'>", $row["CATNAME"],"</a></li>";	
			
			echo "<ul>";
			subCats($dbcon, $catid);
			echo "</ul>";
		}
		?>
		
			</fieldset>
		</ul>
		</div>
		
		<?php
	}
	
	function subCats($conn, $catid)
	/*
		Prints out all of the subcategories for a given top-level category.
		
		Param: database connection, and the top-level category.
	*/
	{
		$dbcon = $conn;
		
		$sql = "select SCATID, SCATNAME 
				from SUBCAT 
				where CATID = :catid
				order by SCATNAME asc";
		$subcat = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($subcat, ":catid", $catid);
		
		oci_execute($subcat);
		
		while(($row = oci_fetch_array($subcat)))
		{
			$scatid = $row["SCATID"];
			echo "<li><a href = 'browse.php?scat=$scatid'>", $row["SCATNAME"],"</a></li>";
		}
	}
	
	function validateCats($catid, $conn)
	/*
		Checks to ensure that a category given from the query string actually exists.
		
		Param: Category, and database connection.
		Return: true or false.
	*/
	{
		$dbcon = $conn;
		
		$sql = "select COUNT(*) AS VALID
				from CATEGORY
				where CATID = :catid";
		$cat = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($cat, ":catid", $catid);
		oci_define_by_name($cat, "VALID", $valid);
		
		oci_execute($cat);
		oci_fetch($cat);
		oci_free_statement($cat);
		
		return $valid;
	}
	
	function validateSubcats($scatid, $conn)
	/*
		Checks to ensure that a sub-category given from the query string actually exists.
		
		Param: Category, and database connection.
		Return: true or false.
	*/
	{
		$dbcon = $conn;
		
		$sql = "select COUNT(*) AS VALID
				from SUBCAT
				where SCATID = :scatid";
		$scat = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($scat, ":scatid", $scatid);
		oci_define_by_name($scat, "VALID", $valid);
		
		oci_execute($scat);
		oci_fetch($scat);
		oci_free_statement($scat);
		
		return $valid;
	}
?>

<html>
	<head>
	<title>Browse Catalog - Online Bookshop +</title>
	<link rel = "stylesheet" type = "text/css" href = "obsstyle.css">
	<script type = 'text/javascript' src = 'functions.js'></script>
	</head>
	
	<body>
	<p id = "admin">COMP344 Assignment 2 2012<br />Horhou Lam<br />42491428</p>

	<h1>Online Bookshop +</h1>
	<h2>Browse Catalog</h2>
	
	<?php
		linkList();
		
		//Checks if the user is logged in
		if(isset($_SESSION['SID']))
		{
			include("dbconnect.php");
			
			$dbcon = $conn;
			
			categoryList($dbcon);
			logoutButton();
			
			$sql;
			$listProducts;
			
			//Checks if a sub-category is selected and exists
			if(isset($_GET['scat']) && validateSubcats($_GET['scat'], $dbcon))
			{
				$scatid = $_GET['scat'];
				
				$sql = "select I.PRODUCTID, CATID, PRODNAME, ATTVAL, PICTURE
						from PRODUCT P, ITEM I
						where P.PRODUCTID = I.PRODUCTID
						and P.PRODUCTID in 
						(select PRODUCTID from PRODUCTSUBCAT
						 where SCATID = :scatid)
						and CATID in
						(select CATID from SUBCAT
						 where SCATID = :scatid)
						order by PRODNAME asc";
				$listProducts = oci_parse($dbcon, $sql);
				
				oci_bind_by_name($listProducts, ":scatid", $scatid);
			}
			//else check if a main category is selected
			else if(isset($_GET['cat']) && validateCats($_GET['cat'], $dbcon))
			{
				$catid = $_GET['cat'];
				
				$sql = "select I.PRODUCTID, CATID, PRODNAME, ATTVAL, PICTURE
						from PRODUCT P, ITEM I
						where P.PRODUCTID = I.PRODUCTID
						and CATID = :catid
						order by PRODNAME asc";
				$listProducts = oci_parse($dbcon, $sql);
				
				oci_bind_by_name($listProducts, ":catid", $catid);
			}
			//else list all products from all main and sub-categories
			else
			{
				$sql = "select I.PRODUCTID, CATID, PRODNAME, ATTVAL, PICTURE
						from PRODUCT P, ITEM I
						where P.PRODUCTID = I.PRODUCTID
						order by PRODNAME asc";
			
				$listProducts = oci_parse($dbcon, $sql);
			}
			oci_execute($listProducts);
			?>
			
			<div id = 'main'>
			<table border = '1'>
				<tr>
					<td></td>
					<th>PRODUCT</th>
					<th>AVAILABLE</th>
					<th>PRICE</th>
				</tr>		
			
			<?php
			while(($row = oci_fetch_array($listProducts)))
			{
				echo "<tr>";
				displayPicture($row["PICTURE"]);
				itemLinker($row["PRODNAME"], $row["PRODUCTID"], $row["CATID"]);
				
				$stock = getNumber($row["ATTVAL"], STOCKNO);
				
				if($stock == 0)
					display("SOLD OUT");
				else
					display($stock);
				
				display(getNumber($row["ATTVAL"], UNITPRICE));
				echo "</tr>";
			}
			?>
			
			</table>
			</div>
			
			<?php
			oci_free_statement($listProducts);
			oci_close($dbcon);
		}
		else
			notLoggedIn();
	?>
	
	</body>
</html>