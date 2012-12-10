<?php
	session_start();
	include("functions.php");
	
	function getGMTtimeStamp()
	{
		$stamp = date("YmdGis")."000+1000";
		return $stamp;
	}
		
	function getUserName($email, $conn)
	/*
		Given a user's email returns a string containing their first and last name.
		
		Param: email of the user in question, and database connection.
		Return: a string with the user's name.
	*/
	{
		$dbcon = $conn;
		
		$sql = "select FNAME, LNAME 
				from USERS 
				where EMAIL = :email";
		$names = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($names, ":email", $email);
		oci_define_by_name($names, "FNAME", $first);
		oci_define_by_name($names, "LNAME", $last);
		
		oci_execute($names);
		oci_fetch($names);
		oci_free_statement($names);
		
		return $first . " " . $last;
	}
	
	/**************************/
	/* Secure Socket Function */
	/**************************/
	function openSocket($host,$query)
	{
		// Break the URL into usable parts
		$path = explode('/',$host);
		$host = $path[0];
		unset($path[0]);
		$path = '/'.(implode('/',$path));



		// Prepare the post query
		$post  = "POST $path HTTP/1.1\r\n";
		$post .= "Host: $host\r\n";
		$post .= "Content-type: application/x-www-form-urlencoded\r\n";
		$post .= "Content-type: text/xml\r\n";
		$post .= "Content-length: ".strlen($query)."\r\n";
		$post .= "Connection: close\r\n\r\n$query";


		/***********************************************/
		/* Open the secure socket and post the message */
		/***********************************************/
	   $h = fsockopen("ssl://".$host, 443, $errno, $errstr);

		if ($errstr)
				print "$errstr ($errno)<br/>\n";
		fwrite($h,$post);

		/*******************************************/
		/* Retrieve the HTML headers (and discard) */
		/*******************************************/
		
		$headers = "";
		while ($str = trim(fgets($h, 4096))) {

				$headers .= "$str\n";
		}

		$headers2 = "";
		while ($str = trim(fgets($h, 4096))) {

				$headers2 .= "$str\n";
		}

		/**********************************************************/
		/* Retrieve the response */
		/**********************************************************/

		$body = "";
		while (!feof($h)) {
				$body .= fgets($h, 4096);
		}

		// Close the socket
		fclose($h);

		// Return the body of the response

		return $body;
	}

	function makeXMLTree ($data) {
	   $output = array();

	   $parser = xml_parser_create();

	   xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	   xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	   xml_parse_into_struct($parser, $data, $values, $tags);
	   xml_parser_free($parser);

	   $hash_stack = array();

	   foreach ($values as $key => $val)
	   {
		   switch ($val['type'])
		   {
			   case 'open':
				   array_push($hash_stack, $val['tag']);
				   break;
		
			   case 'close':
				   array_pop($hash_stack);
				   break;
		
			   case 'complete':
				   array_push($hash_stack, $val['tag']);
				   eval("\$output['" . implode($hash_stack, "']['") . "'] = \"{$val['value']}\";");
				   array_pop($hash_stack);
				   break;
		   }
	   }

	   return $output;
	}
	
	function paymentMessage($invoice, $total, $creditNo, $month, $year, $cvv)
	{
		$timestamp = getGMTtimestamp();
		$total *= 100;

		$vars = 
		"<?xml version=\"1.0\" encoding=\"UTF-8\"?>" .
		"<SecurePayMessage>" .
			"<MessageInfo>" .
				"<messageID>8af793f9af34bea0cf40f5fb5c630c</messageID>" .
				"<messageTimestamp>" .urlencode($timestamp). "</messageTimestamp>" .
				"<timeoutValue>60</timeoutValue>" .
				"<apiVersion>xml-4.2</apiVersion>" .
			"</MessageInfo>" .
			"<MerchantInfo>" .
				"<merchantID>CAX0001</merchantID>" .
				"<password>oguxue9i</password>" .
			"</MerchantInfo>" .
			"<RequestType>Payment</RequestType>" .
			"<Payment>" .
				"<TxnList count=\"1\">" .
					"<Txn ID=\"1\">" .
						"<txnType>0</txnType>" .
						"<txnSource>23</txnSource>" .
						"<amount>" .urlencode($total). "</amount>" .
						"<purchaseOrderNo>" .urlencode($invoice). "</purchaseOrderNo>" .
						"<currency>AUD</currency>" .
						"<CreditCardInfo>" .
							"<cardNumber>" .urlencode($creditNo). "</cardNumber>" .
							"<cvv>" .urlencode($cvv). "</cvv>" .
							"<expiryDate>" .urlencode($month). "/" .urlencode($year). "</expiryDate>" .
						"</CreditCardInfo>" .
					"</Txn>" .
				"</TxnList>" .
			"</Payment>" .
		"</SecurePayMessage>";
		
		return $vars;
	}
	
	function updateTable($invoice, $conn)
	/*
		Updates a given invoice and make it paid.
		
		Param: invoice in question to become paid, and database connection.
	*/
	{
		$dbcon = $conn;
		
		$sql = "update INVOICE 
				set PAID = '1' 
				where INVOICENO = :invoice";
		$update = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($update, ":invoice", $invoice);
		
		oci_execute($update);
		oci_free_statement($update);
	}
	
	function validateExpiry($month, $year)
	/*
		Server side expiry date validation.
		
		Param: month and year.
		Return: true if the expiry date is still valid, else false.
	*/
	{
		$currentYear = date("y");
		$currentMonth = date("n");
		
		$valid = true;
		
		if($year == $currentYear && $month < $currentMonth)
			$isValid = false;
		
		return $valid;
	}
	
	function writeMessage($email, $invoice, $cardNo, $conn)
	/*
		Writes a receipt to be sent via email, to the customer about the transaction details.
		
		Param: email of the user, the invoice to be written, the cardNo, and database connection.
		Return: the message to be sent.
	*/
	{
		$dbcon = $conn;
		$msg = "Hello " . getUserName($email, $dbcon) . ",\n\n";
		$msg .= "The following order was billed to account number " . $cardNo . ".\n\n";
		
		$sql = "select P.PRODNAME, QUANTITY, PRICE
				from PRODUCT P, SHOPPING_CART S, ITEM I
				where I.PRODUCTID = P.PRODUCTID 
				and I.PRODUCTID = S.PRODUCTID
				and I.CATID = S.CATID
				and S.INVOICENO = :invoice";
		$listCart = oci_parse($dbcon, $sql);
		
		oci_bind_by_name($listCart, ":invoice", $invoice);
		
		oci_execute($listCart);
		
		while(($row = oci_fetch_array($listCart)))
		{
			$msg .= $row["QUANTITY"] . "x " . $row["PRODNAME"];
			$msg .= "\t$" . $row["PRICE"] . "\n"; 
		}
		
		$msg .= "\nThe total price of the order is $" . getTotal($invoice, $dbcon) . ".\n\n";
		$msg .= "Horhou Lam\n42491428\n";
		return $msg;
	}
	
	// Checks if the user is logged in
	if(isset($_SESSION['SID']))
	{
		// Checks if there is a shopping cart present
		if(isset($_POST["invoice"]))
		{
			include("dbconnect.php");
			
			$dbcon = $conn;
			
			$invoice = $_POST["invoice"];
			$total = $_POST["total"];
			
			$creditNo = htmlentities($_POST["creditNo"]);
			$expMonth = $_POST["month"];
			$expYear = $_POST["year"];
			$cvv = htmlentities($_POST["cvv"]);
			
			// Checks the card expiry date is valid
			if(validateExpiry($expMonth, $expYear))
			{
				$host = "www.securepay.com.au/test/payment";
				$vars = paymentMessage($invoice, $total, $creditNo, $expMonth, $expYear, $cvv);
				
				$response = openSocket($host, $vars);
				
				$xmlres = array();
				$xmlres = makeXMLTree($response);

				$approved = trim($xmlres['SecurePayMessage']['Payment']['TxnList']['Txn']['approved']);
				$responseCode = trim($xmlres['SecurePayMessage']['Payment']['TxnList']['Txn']['responseCode']);
				$responseText = trim($xmlres['SecurePayMessage']['Payment']['TxnList']['Txn']['responseText']);
			}
			// else card is expired
			else
			{
				$approved = "No";
				$responseCode = 102;
				$responseText = "Invalid Expiry Date";
			}
			// checks if card is invalid 
			if($responseCode == 102 || $responseCode == 109 || $responseCode == 101)
			{
				oci_close($dbcon);
				header("Location: cart.php?fail=$responseCode");
			}
			else
			{
				// else card is valid
				if($approved == "Yes")
				{
					$creditNo = trim($xmlres['SecurePayMessage']['Payment']['TxnList']['Txn']['CreditCardInfo']['pan']);
					$cardDesc = trim($xmlres['SecurePayMessage']['Payment']['TxnList']['Txn']['CreditCardInfo']['cardDescription']);
					$expiry = trim($xmlres['SecurePayMessage']['Payment']['TxnList']['Txn']['CreditCardInfo']['expiryDate']);
					
					
					updateTable($invoice, $dbcon);
				
					$email = getEmail($_SESSION['SID'], $dbcon);
					$subject = "Order Confirmation";
					$from = "From: OnlineBookshopPayments";
					$msg = writeMessage($email, $invoice, $creditNo, $dbcon);
				}
				oci_close($dbcon);
				
				include("response.php");
			}
		}
		else
			header("Location: browse.php");
	}
	else
		header("Location: login.html");

?>