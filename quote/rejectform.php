
<?php
$timeParams=$_POST['WEMPID'];





$reason=$_POST['Reason'];
$timeParams=$_POST['WEMPID'];

$connection = curl_init(); // initiate curl
//print_r($postData);

$data = array("WEMPID" => $timeParams, "Status" => "Rejected","Reason"=>$reason);                                                                    
$postData = json_encode($data);   
print_r($postData);
$transactionURL = "http://localhost/timesheet/admin/adminapi/updatesheet";
curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($connection, CURLOPT_URL, $transactionURL);
curl_setopt($connection, CURLOPT_POST, true);
curl_setopt($connection, CURLOPT_POSTFIELDS, $postData);
curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$responseReader = curl_exec($connection);
$responseData = json_decode($responseReader, true);
//print_r($responseReader);

echo "<pre>"; print_r($responseData); echo "</pre>";

?>

