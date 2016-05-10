<?

$string = file_get_contents( 'php://input' );

require('includes/application_top.php');
include('includes/xmlparser.class.php');


if (($_SERVER['REMOTE_ADDR'] == '64.244.237.35') || ($_SERVER['REMOTE_ADDR'] == '65.61.174.44') || ($_SERVER['REMOTE_ADDR'] == '209.61.174.18') || ($_SERVER['REMOTE_ADDR'] == '64.244.237.39') || ($_SERVER['REMOTE_ADDR'] == '64.244.237.36') || ($_SERVER['REMOTE_ADDR'] == '64.244.237.37') || ($_SERVER['REMOTE_ADDR'] == '64.244.237.34') || ($_SERVER['REMOTE_ADDR'] == '207.67.87.86') || ($_SERVER['REMOTE_ADDR'] == '64.244.237.38')   || ($_SERVER['REMOTE_ADDR'] == '72.21.86.239')) {

 
/*
$string = file_get_contents( 'php://input' );


|| ($_SERVER['REMOTE_ADDR'] == '96.254.126.27')

$fp = fopen("response.log", "a+");
fputs($fp, $string.chr(10));
fclose($fp);
*/
if ($_SERVER['REMOTE_ADDR'] == '96.254.126.27') $fulfillvendor = "TVS";
if ($_SERVER['REMOTE_ADDR'] == '64.244.237.34') $fulfillvendor = "Allied Vaughn";
if ($_SERVER['REMOTE_ADDR'] == '64.244.237.35') $fulfillvendor = "Allied Vaughn";
if ($_SERVER['REMOTE_ADDR'] == '64.244.237.36') $fulfillvendor = "Allied Vaughn";
if ($_SERVER['REMOTE_ADDR'] == '64.244.237.37') $fulfillvendor = "Allied Vaughn";
if ($_SERVER['REMOTE_ADDR'] == '64.244.237.38') $fulfillvendor = "Allied Vaughn";
if ($_SERVER['REMOTE_ADDR'] == '64.244.237.39') $fulfillvendor = "Allied Vaughn";
if ($_SERVER['REMOTE_ADDR'] == '207.67.87.86') $fulfillvendor = "Allied Vaughn";
if ($_SERVER['REMOTE_ADDR'] == '64.49.221.5') $fulfillvendor = "CustomCD";
if ($_SERVER['REMOTE_ADDR'] == '209.61.174.18') $fulfillvendor = "CustomCD";
if ($_SERVER['REMOTE_ADDR'] == '72.21.86.239') $fulfillvendor = "CustomCD";
/*
if ($_SERVER['REMOTE_ADDR'] == '96.254.126.27') $fulfillvendor = "TVS";
*/


$xml = new XMLParser($string);
$xml->Parse();


$orderId = trim($xml->document->tagChildren[1]->tagData);
$pos1 = strpos(strtolower($string), "acknowledgereceipt");
$pos2 = strpos(strtolower($string), "confirmburn");
$pos3 = strpos(strtolower($string), "confirmshipment");

if (intval($pos1)!=0) $acknowledge = "Acknowledge Receipt";
if (intval($pos2)!=0) $acknowledge = "Confirm Burn";
if (intval($pos3)!=0) $acknowledge = "Confirm Shipment";

if (intval($pos3)!=0) {


if (intval($orderId)!=0) {
	list($commentStatus, $orderStatus, $payment_method, $shippedDate, $total) = orderStatusAllied($orderId);

	$shipCarrier = trim($xml->document->tagChildren[2]->tagChildren[8]->tagData);
	$shipOption = trim($xml->document->tagChildren[2]->tagChildren[9]->tagData);
	$trackNo = trim($xml->document->tagChildren[2]->tagChildren[10]->tagData);
	if ($trackNo != '') {
		$comments = $fulfillvendor. " - " .$shipCarrier . "(".$shipOption.") with tracking number: " . $trackNo;
	}
	else {
		$comments = $fulfillvendor. " - " .$shipCarrier . "(".$shipOption.")";
	}
	$received = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));

        $prevTimeSql = tep_db_query("select * from orders_products where orders_id = '" . (int)$orderId . "' and date_sent_to_allied is not null limit 1");
        $prevOrder = tep_db_fetch_array($prevTimeSql);
	$prevTime = $prevOrder['date_sent_to_allied '];

	$nrSeconds = $received-$prevTime;
	$nrSeconds = abs($nrSeconds);
	$nrDaysPassed = floor($nrSeconds/86400);

	mysql_query("insert into orders_status_history set orders_id='".$orderId."', orders_status_id=".$commentStatus.", date_added='".date("Y-m-d H:i:s")."', customer_notified=1, comments='".mysql_escape_string($comments)."'");	
	mysql_query("update orders_products set days_between_allied='" . $nrDaysPassed ."', orders_products_ship_carrier='" . $shipCarrier ."', orders_products_tracking_number='" . $trackNo ."', date_ship_by_allied='" . $received . "', date_shipped='" . date("Y-m-d") . "' where is_allied=1 and orders_id=" . $orderId);
	list($commentStatus, $orderStatus, $payment_method, $shippedDate, $total) = orderStatusAllied($orderId);
	mysql_query("update orders set orders_status='" . $orderStatus ."', orders_ship_carrier='" . $shipCarrier ."', orders_tracking_number='" . $trackNo ."' where orders_id=" . $orderId);

echo "Successfully executed";
$errortxt = "SUCCESSFULLY EXECUTED" . " - " . $shippedDate . " - " . $total . " - " . $payment_method . " - " . $orderStatus . " - " . $acknowledge . " - " . $_SERVER['REMOTE_ADDR'] . " - " . $fulfillvendor . " - " .date("Y-m-d H:i:s"). " - " . $string;

$fp = fopen("response.log", "a+");
fputs($fp, $errortxt.chr(10));
fclose($fp);

}
else {
echo "Errors!";
$errortxt = "ERRORS!" . " - " . $acknowledge . " - " . $_SERVER['REMOTE_ADDR'] . " - " . $fulfillvendor . " - " .date("Y-m-d H:i:s"). " - " . $string;

$ce_to_name = "Don Wyatt";
$ce_to_email = "donwyatt@travelvideostore.com";
$ce_subject = "XML Errors Recieved";
$ce_from_name = "Fullfillment Processing";
$ce_from_email = "customerservice@travelvideostore.com";

tep_mail($ce_to_name, $ce_to_email, $ce_subject, $errortxt, $ce_from_name, $ce_from_email);


$fp = fopen("response.log", "a+");
fputs($fp, $errortxt.chr(10));
fclose($fp);
}
} else {
echo "Successfully executed";

if (intval($pos2)!=0) {
               list($commentStatus, $orderStatus, $payment_method, $shippedDate, $total) = orderStatusAllied($orderId);

	$received = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
	$comments = $fulfillvendor. " - "  . "Confirm Manufacture";

mysql_query("insert into orders_status_history set orders_id='".$orderId."', orders_status_id=".$commentStatus.", date_added='".date("Y-m-d H:i:s")."', customer_notified=1, comments='".mysql_escape_string($comments)."'");	

mysql_query("update orders_products set  date_confirm_by_allied='" . $received . "' where is_allied=1 and orders_id=" . $orderId); }

$errortxt = $shippedDate . " - " . $total . " - " . $payment_method . " - " . $orderStatus . " - " . $acknowledge . " - " . $orderId . " - " . $_SERVER['REMOTE_ADDR'] . " - " . $fulfillvendor . " - " .date("Y-m-d H:i:s"). " - " . $string;

$fp = fopen("response.log", "a+");
fputs($fp, $errortxt.chr(10));
fclose($fp);

}
}else {
echo "Access restricted";
/*
$string = file_get_contents( 'php://input' );
*/
$errortxt = "ACCESS RESTRICTED!" .  " - " . $_SERVER['REMOTE_ADDR'] . " - " .date("Y-m-d H:i:s"). " - " . $string;

$ce_to_name = "Don Wyatt";
$ce_to_email = "donwyatt@travelvideostore.com";
$ce_subject = "XML Access Restricted Recieved";
$ce_from_name = "Fullfillment Processing";
$ce_from_email = "customerservice@travelvideostore.com";

tep_mail($ce_to_name, $ce_to_email, $ce_subject, $errortxt, $ce_from_name, $ce_from_email);

$fp = fopen("response.log", "a+");
fputs($fp, $errortxt.chr(10));
fclose($fp);

}


function orderStatusAllied($orderId) {
      $order_query = tep_db_query("select count(*) as cnt from orders_products where orders_id = '" . (int)$orderId . "'");
      $total = tep_db_fetch_array($order_query);

      $order_query = tep_db_query("select count(*) as cnt from orders_products where is_allied=1 and orders_id = '" . (int)$orderId . "'");
      $isAllied = tep_db_fetch_array($order_query);

      $order_query = tep_db_query("select count(*) as cnt from orders_products where date_shipped_checkbox =1 and orders_id = '" . (int)$orderId . "'");
      $shippedDate = tep_db_fetch_array($order_query);

$paymethod = "Purchase Order";


      if ($shippedDate['cnt'] == $total['cnt']) {
                $order_query = tep_db_query("select payment_method from orders where orders_id = '" . (int)$orderId . "'");
      		$order1 = tep_db_fetch_array($order_query);		

                if ($order1['payment_method'] == $paymethod) {
		return array(7, 7, $order1['payment_method'], $shippedDate['cnt'], $total['cnt']);
                } else {
                return array(20, 20, $order1['payment_method'], $shippedDate['cnt'], $total['cnt']);
                }
       } else {
      $order_query = tep_db_query("select orders_status from orders where orders_id = '" . (int)$orderId . "'");
      		$order = tep_db_fetch_array($order_query);	
		return array(3, $order['orders_status'], $order1['payment_method'], $shippedDate['cnt'], $total['cnt']);
       }

}
?>