<?php

$oraUser="42491428";
$oraPass="lamorpass";
$oraDB="(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=matrix.science.mq.edu.au)(PORT=1521)))(CONNECT_DATA=(SID=neo)))";



 
 $conn = oci_connect($oraUser,$oraPass,$oraDB);
		
if (!$conn) 
{
 echo "fail";
}
	

?> 


