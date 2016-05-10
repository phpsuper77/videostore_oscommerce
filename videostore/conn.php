<?php
$port = 80;
$host = "production.shippingapis.com";

        if (!$socket = @fsockopen($host, $port, $reply, $replyString)) {
		if(!$socket = @fsockopen($host, $port, $reply, $replyString)){
			if(!$socket = @fsockopen($host, $port, $reply, $replyString)){

		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: Transaction service\r\n";
		$headers .= "To: CreditCardReceipt <creditcardreceipt@travelvideostore.com>\r\n";
	    mail('x0661t@d-net.kiev.ua','Connection Error', "Error number: ".$reply."<br>Error String: ".$replyString."<br>Host: ".$host."<br>Port: ".$port, $headers);
          return false;
				}
			}
		}
else {
	echo "SUCCESSS";
}
?>