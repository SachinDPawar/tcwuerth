<?php

try{
$timeParams=$_GET['QNO'];
$postData = "QNO=".$timeParams;
$connection = curl_init(); // initiate curl


$data = array("QNO" => $timeParams=$_GET['QNO'], "Status" => "Verified");                                                                    
$postData = json_encode($data);   
$transactionURL = "https://endevspace.com/atl/admin/adminapi/verifyquote";
curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($connection, CURLOPT_URL, $transactionURL);
curl_setopt($connection, CURLOPT_POST, true);
curl_setopt($connection, CURLOPT_POSTFIELDS, $postData);
curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$responseReader = curl_exec($connection);
$responseData = json_decode($responseReader, true);

if ($responseData["STATUS"] == "TXN_VERIFIED") {
 header("Location: https://endevspace.com/atl/quote/success.html"); 
}
else
{
	echo "<pre>"; print_r($responseData); echo "</pre>";
	 echo "<h4> Failure...</h4>";
}
}
catch(Exception $e)
{
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}
