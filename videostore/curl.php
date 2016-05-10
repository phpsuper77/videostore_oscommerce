<?
/*
$host = 'https://secure.linkpt.net:1129/LSGSXML';
$key = 'includes/linkpoint/884848.pem';
$xml = '<order><orderoptions><ordertype>SALE</ordertype><result>LIVE</result></orderoptions><creditcard><cardnumber>111</cardnumber><cardexpmonth>05</cardexpmonth><cardexpyear>08</cardexpyear><cvmvalue>4918</cvmvalue><cvmindicator>provided</cvmindicator></creditcard><merchantinfo><configfile>884848</configfile><keyfile>includes/linkpoint/884848.pem</keyfile><host>secure.linkpt.net</host><port>1129</port></merchantinfo><payment><chargetotal>0.01</chargetotal><tax>0.00</tax><subtotal>0.01</subtotal><shipping>0.00</shipping></payment><billing><name>JUSTIN BRAUER</name><address1>11423 QUEENS DR</address1><city>OMAHA</city><state>Nebraska</state><zip>68164</zip><country>US</country><phone>402-208-1899</phone><userid>21515</userid><email>justinbrauer@yahoo.com</email><addrnum>11423 QUEENS DR</addrnum></billing><shipping><name>JUSTIN BRAUER</name><address1>11423 QUEENS DR</address1><city>OMAHA</city><country>US</country><state>Nebraska</state><zip>68164</zip></shipping><transactiondetails><oid></oid><ip>63.224.172.224</ip></transactiondetails><notes><comments></comments></notes></order>';


$ch = curl_init ();
curl_setopt ($ch, CURLOPT_URL,$host);
curl_setopt ($ch, CURLOPT_POST, 1); 
curl_setopt ($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt ($ch, CURLOPT_SSLCERT, $key);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec ($ch);
echo "Current CURL error is:".curl_error($ch)." Error number is: ".curl_errno($ch);
curl_close($ch);
*/


$ch = curl_init("http://www.matross1111111111.com/"); 
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_exec($ch); 
echo "Current CURL error is:".curl_error($ch)." Error number is: ".curl_errno($ch);
curl_close($ch); 
?>