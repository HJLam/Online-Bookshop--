<?php
	// checks if the user is logged in
	if(isset($_SESSION['SID']))
	{
?>
<html>
	<head>
	<title>Transaction Summary - Online Bookshop +</title>
	<link rel = "stylesheet" type = "text/css" href = "obsstyle.css">
	<script type = 'text/javascript' src = 'functions.js'></script>
	</head>
	
	<body>
	
	<p id = "admin">COMP344 Assignment 2 2012<br />Horhou Lam<br />42491428</p>

	<h1>Online Bookshop +</h1>
	<h2>Transaction Summary</h2>
	
	<?php linkList(); logoutButton();?>
	
	<div id = "main">
		<p>Transaction outcome: <?php echo $responseText;?></p>
		
		<?php
		
		if($approved == "Yes")
		{
			?>
			
			<table>
				<tr><td><h3>Credit Info</h3></td></tr>
				<tr><td><?php echo $creditNo; ?></td></tr>
				<tr><td><?php echo $cardDesc; ?></td></tr>
				<tr><td><?php echo $expiry; ?></td></tr>
				
				<tr><td><h3>Billing Info</h3></td></tr>
				<tr><td>Total: $<?php echo $total; ?>.00</td></tr>
			</table>
			<p>
			
			<?php
		}
		else{
			if($responseCode == 51)
				echo "The are insufficient funds on this card, go <a href='cart.php'>back</a> to try with a different card or remove some items from the cart.";
			else if($responseCode == 43)
				echo "This card has been invalidated as it is suspected to have been stolen. Please use a different card.";
			else if($responseCode == 34)
				echo "This card has been suspected to been used in fraudulent activity, and is rejected by the system. Please try a different card.";
		}
		?>
		
		</p>
		<p>Return to <a href="browse.php">home</a>.</p>
	</div>
	
	</body>
	
</html>
<?php
	}
	else
		header("Location: login.html");
?>