<?php 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "http://google.com/"); 
$ret = curl_exec($ch); 
print $ret; 
curl_close($ch); 

?>
