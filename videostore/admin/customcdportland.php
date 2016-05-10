<?
require('includes/application_top.php');
include(DIR_WS_CLASSES . 'order.php');

if (strlen(curPageURL())>2048) echo "<font color='red'>Please don't use internet explorer for this order. Use either safari or firefox or opera to process it!</font>";

$productList = (trim($_GET['list']) != '') ? $_GET['list'] : $_POST['list'];
//echo $productList;
$oID = (trim($_GET['order_id']) !='') ? $_GET['order_id'] : $_POST['order_id'];

if (trim($productList) == '') die("No items to send to allied!");

?>
<? if ($_POST['action'] == '') { ?>
<form action="" method="POST">
	<input type="hidden" name="list" value="<?=$productList?>" />
	<input type="hidden" name="action" value="apply" />
	<input type="hidden" name="order_id" value="<?=$oID?>" />
	Shipping Carry:&nbsp;
	<!--select name="carry">
		<option value=""></option>
		<option value="DEFAULT">USPS</option>
		<option value="FedEx">FedEx</option>
	</select-->
	<select name="carry">
		<option value=""></option>
		<option value="DEFAULT">USPS</option>
		<option value="FedEx">FedEx</option>

	</select>
	</br>
	Shipping Method: &nbsp;
	<!--select name="carry_type">
		<option value=""></option>
                               <option value="USPS-FCM-DC">USPS First Class Mail</option>
		<option value="USPS-PM">USPS Priority Mail</option>
                               <option value="USPS-EM">USPS Express Mail</option>
                               <option value="USPS-FCM-DC">USPS First Class International Mail</option>
                               <option value="USPS-PMI">USPS International Priority Mail</option>
		<option value="FEDEX-PRT">FedEx Priority Overnight</option>
		<option value="FEDEX-STD">FedEx Standard Overnight</option>
		<option value="FEDEX-2DAY">FedEx Second Day</option>
		<option value="FEDEX-EXSAVER">FedEx Saver</option>
		<option value="FEDEX-GRND">FedEx Ground</option>
		<option value="FEDEX-HOME">FedEx Home</option>
		<option value="FEDEX-INTLECON">FedEx International Economy</option>
		<option value="FEDEX-INTLPRT">FedEx International Priority</option>
(faster delivery)</option>
	</select-->
	<select name="carry_type">
		<option value=""></option>
                                <option value="USPS-FCM-DC">USPS First Class Mail</option>
		<option value="USPS-PM">USPS Priority Mail</option>
                                <option value="USPS-EM">USPS Express Mail</option>
                                <option value="USPS-FCM-DC">USPS First Class International Mail</option>
                               <option value="USPS-PMI">USPS International Priority Mail</option>
		<option value="FEDEX-PRT">FedEx Priority Overnight</option>
		<option value="FEDEX-STD">FedEx Standard Overnight</option>
		<option value="FEDEX-2DAY">FedEx Second Day</option>
		<option value="FEDEX-EXSAVER">FedEx Saver</option>
		<option value="FEDEX-GRND">FedEx Ground</option>
		<option value="FEDEX-HOME">FedEx Home</option>
		<option value="FEDEX-INTLECON">FedEx International Economy</option>
		<option value="FEDEX-INTLPRT">FedEx International Priority</option>
	</select>
<br/><br/><input type="submit" value="Apply" />&nbsp;&nbsp;<input type="button" onclick="window.close(); return false;" value="Close" />
</form>
<?}?>
<?
if ($_POST['action'] == 'apply') {

$oID = $_GET['order_id'];
$order = new order($oID);
$carrier = $_POST['carry'];
$carry_type = $_POST['carry_type'];


$aff_sql = "select aa.affiliate_id,aa.affiliate_firstname, aa.affiliate_lastname, aa.affiliate_company, asa.affiliate_salesman from affiliate_affiliate aa, affiliate_sales asa where aa.affiliate_id = asa.affiliate_id and asa.affiliate_orders_id = '". $oID ."'";
$aff_rs = mysql_query($aff_sql);
$aff = mysql_fetch_array($aff_rs);

if ($aff) {
	$affId = $aff['affiliate_id'];

	$sel_aff_name = mysql_query("select affiliate_firstname, affiliate_lastname from affiliate_affiliate where affiliate_id = '".$aff['affiliate_salesman']."'");
	$rs_aff_name = mysql_fetch_array($sel_aff_name);
	$sales_firstname = $rs_aff_name['affiliate_firstname'];
	$sales_lastname = $rs_aff_name['affiliate_lastname'];

	if (isset($sales_lastname) || isset($sales_firstname)) $affRoot = $sales_firstname." ".$sales_lastname; else $affRoot = "-";

}


$pos = explode(",", $productList);
$productInfo = '';
$totalNum = 0;

foreach($pos as $key=>$value) {
	if (trim($value) != ''){
		$rs = mysql_query("select * from orders_products where orders_products_id='".$value."'");

		$order_products = mysql_fetch_array($rs);
//if ($order_products) {
//	echo $order_products['products_model'] . "<br/>";
//	$ttt++;
//}
		$rs = mysql_query("select * from products where products_id='".$order_products[products_id]."'");
		$product = mysql_fetch_array($rs);

		$rs = mysql_query("select * from products_to_allied where product_model='".$product[products_model]."'");
		$allied = mysql_fetch_array($rs);

	$qty = 0;		
	foreach($order->products as $k=>$v) {
		if ($v['ordered_products_id'] == $value) $qty = $v[qty];
	}

	$totalNum = $totalNum+$qty;

$title= str_replace('&nbsp;&nbsp;', ' ', $order_products[products_name]);
$title= str_replace('&nbsp;', ' ', $title);
$title= str_replace('&amp;', 'and', $title);
//$title= html_entity_decode($title);
$title= htmlentities($title);
$title= str_replace('&amp;', 'and', $title);
$title= mb_convert_encoding($title, "UTF-8", "HTML-ENTITIES");
               $title= str_replace('Â', '', $title);


	if ($allied['product_set_total_disks'] == 1) {
		$productInfo .= '
			<Set copies="'.$qty.'">
			<MediaDefinitionProduct type="'.$allied[product_media_definition].'">
        			        <MediaFormat>'.$allied[product_disk_one_media_format].'</MediaFormat>
		                	<MediaContentName>'.$allied[product_model].'</MediaContentName>
	        		        <ProductId>'.$allied[product_disk_one_iso].'</ProductId>
		        	        <CDLabel>'.$allied[product_disk_one_cdlabel].'</CDLabel>
                			<LabelField number="1">'.$allied[product_disk_one_disclabel].'</LabelField>
                			<LabelField number="2">'.$allied[product_disk_one_wrap ].'</LabelField>
                			<LabelField number="3">'.$product[products_upc].'</LabelField>
                			<LabelField number="4">'.$title.'</LabelField>
        			        <SilkScreen>'.$allied[product_disk_one_silkscreen].'</SilkScreen>
				   	<CDPackage>'.$allied[product_disk_one_barcode_placement ].'</CDPackage>
				   	<CDPackage>'.$allied[product_disk_one_cdpackage].'</CDPackage>
        			        </MediaDefinitionProduct>
			</Set>
		';
	}
	elseif ($allied['product_set_total_disks'] == 2) {
		$productInfo .= '
			<Set copies="'.$qty.'">
			<MediaDefinitionProduct type="'.$allied[product_media_definition].'">
        			        <MediaFormat>'.$allied[product_disk_one_media_format].'</MediaFormat>
		                	<MediaContentName>'.$allied[product_model] . '</MediaContentName>
	        		        <ProductId>'.$allied[product_disk_one_iso].'</ProductId>
		        	        <CDLabel>'.$allied[product_disk_one_cdlabel].'</CDLabel>
                			<LabelField number="1">'.$allied[product_disk_one_disclabel].'</LabelField>
                			<LabelField number="2">'.$allied[product_disk_one_wrap ].'</LabelField>
                			<LabelField number="3">'.$product[products_upc].'</LabelField>
                			<LabelField number="4">'.$title.'</LabelField>
        			        <SilkScreen>'.$allied[product_disk_one_silkscreen].'</SilkScreen>
                                 <CDPackage>travelvideowrap</CDPackage>
				   	<CDPackage>'.$allied[product_disk_one_cdpackage].'</CDPackage>
        			        </MediaDefinitionProduct>

			<MediaDefinitionProduct type="'.$allied[product_media_definition].'">
        			        <MediaFormat>'.$allied[product_disk_two_media_format].'</MediaFormat>
		                	<MediaContentName>'.$allied[product_model].'</MediaContentName>
	        		        <ProductId>'.$allied[product_disk_two_iso].'</ProductId>
		        	        <CDLabel>'.$allied[product_disk_two_cdlabel].'</CDLabel>
                			<LabelField number="1">'.$allied[product_disk_two_disclabel].'</LabelField>
                			<LabelField number="2">'.$allied[product_disk_otw_wrap ].'</LabelField>
                			<LabelField number="3">'.$product[products_upc].'</LabelField>
        			        <SilkScreen>'.$allied[product_disk_two_silkscreen].'</SilkScreen>
			            </MediaDefinitionProduct>
			</Set>';

	}
	else {
		//Custom
	}

	}
}
if (trim($productInfo) == "") die("No products to send to allied!");
$finalProductInfo = '<MediaOptions>' . $productInfo . '<MailLabel>default</MailLabel><OrderPackage/></MediaOptions>';

$xmlHead = '<?xml version="1.0" encoding="UTF-8"?>
<ServiceOrder version="3.1" campaign="298">';

$rs = mysql_query("select abbrev from states where name='".$order->delivery[state]."' limit 1");
$row = mysql_fetch_array($rs);
$state = $row['abbrev'];



if (trim($state) == '') $state = $order->delivery[state];

if (trim(strtolower($state)) == 'alberta') $state = 'AB';
if (trim(strtolower($state)) == 'ontario') $state = 'ON';
if (trim(strtolower($state)) == 'british columbia') $state = 'BC';
if (trim(strtolower($state)) == 'manitoba') $state = 'MB';
if (trim(strtolower($state)) == 'new brunswick') $state = 'NB';
if (trim(strtolower($state)) == 'newfoundland') $state = 'NL';
if (trim(strtolower($state)) == 'northwest territories') $state = 'NT';
if (trim(strtolower($state)) == 'nova scotia') $state = 'NS';
if (trim(strtolower($state)) == 'nunavut') $state = 'NU';
if (trim(strtolower($state)) == 'prince edward island') $state = 'PE';
if (trim(strtolower($state)) == 'quebec') $state = 'QC';
if (trim(strtolower($state)) == 'saskatchewan') $state = 'SK';
if (trim(strtolower($state)) == 'yukon territory') $state = 'YT';

$rs = mysql_query("select countries_iso_code_2 from countries where lower(countries_name)='".strtolower($order->delivery[country])."' limit 1");
$row = mysql_fetch_array($rs);
$countryD = $row['countries_iso_code_2'];

foreach($order->totals as $key=>$value) {

	if ($value['class'] == 'ot_shipping') {
		$otShipping = trim(str_replace("$", "", strip_tags($value['text'])));
	}

	if ($value['class'] == 'ot_subtotal') {
		$otSubtotal = trim(str_replace("$", "", strip_tags($value['text'])));
	}

	if ($value['class'] == 'ot_total') {
		$otTotal = trim(str_replace("$", "", strip_tags($value['text'])));
	}

	if ($value['class'] == 'ot_tax') {
		$otTax = trim(str_replace("$", "", strip_tags($value['text'])));
	}

$address1 = $order->delivery[street_address];
$address2 = $order->delivery[suburb];

$ShipCarrier = $_POST[carry];
$ShipOption = $_POST[carry_type];

if (trim($order->delivery[street_address]) == '' && trim($order->delivery[suburb]) !='') {
	$address1 = $order->delivery[suburb];
	$address2 = '';
}

$customerInfo = '
<Recipient>
        <CompanyName>'.htmlentities($order->delivery[company]).'</CompanyName>
        <AttentionName>'.htmlentities($order->delivery['name']).'</AttentionName>
        <Address1>'.htmlentities($address1).'</Address1>
        <Address2>'.htmlentities($address2).'</Address2>
        <City>'.htmlentities($order->delivery[city]).'</City>
        <StateProvince>'.((trim($state) != "") ? $state : "").'</StateProvince>
        <PostalCode>'.$order->delivery[postcode].'</PostalCode>
        <Country>'.((trim($countryD) != "") ? $countryD : "").'</Country>
        <EmailAddress>'.$order->customer[email_address].'</EmailAddress>
        <Phone>'.$order->customer[telephone].'</Phone>
        <ShipCarrier>'.$_POST[carry].'</ShipCarrier>
        <ShipOption>'.$_POST[carry_type].'</ShipOption>
</Recipient>';

$sellerInfo = '
<Sender>
	<CompanyName>TravelVideoStore.com</CompanyName>
	<ContactName></ContactName>
</Sender>';


$billingInfo = '
<BillingOptions>
        <BillingCode/>
        <BillThirdParty>travelvideo</BillThirdParty>
        <CollectedAmount>'.$otTotal.'</CollectedAmount>
        <ChargedProductSales>'.$otSubtotal.'</ChargedProductSales>
        <ChargedFreight>'.$otShipping.'</ChargedFreight>
        <ChargedTax>'.$otTax.'</ChargedTax>
    </BillingOptions> 
';

$orderOptions = '
<OrderOptions>
        <SenderOrderId>'.$oID.'</SenderOrderId>
        <FeedBackOptions>
	    <ConfirmShipment/>
            <ConfirmMethod>http</ConfirmMethod>
            <ConfirmURI>http://www.travelvideostore.com/response.php</ConfirmURI>
        </FeedBackOptions>
</OrderOptions> 
';



$rs = mysql_query("select abbrev from states where name='".$order->billing[state]."' limit 1");
$row = mysql_fetch_array($rs);
$stateB = $row['abbrev'];



if (trim($stateB) == '') $stateB = $order->billing[state];

if (trim(strtolower($stateB)) == 'alberta') $stateB = 'AB';
if (trim(strtolower($stateB)) == 'ontario') $stateB = 'ON';
if (trim(strtolower($stateB)) == 'british columbia') $stateB = 'BC';
if (trim(strtolower($stateB)) == 'manitoba') $stateB = 'MB';
if (trim(strtolower($stateB)) == 'new brunswick') $stateB = 'NB';
if (trim(strtolower($stateB)) == 'newfoundland') $stateB = 'NL';
if (trim(strtolower($stateB)) == 'northwest territories') $stateB = 'NT';
if (trim(strtolower($stateB)) == 'nova scotia') $stateB = 'NS';
if (trim(strtolower($stateB)) == 'nunavut') $stateB = 'NU';
if (trim(strtolower($stateB)) == 'prince edward island') $stateB = 'PE';
if (trim(strtolower($stateB)) == 'quebec') $stateB = 'QC';
if (trim(strtolower($stateB)) == 'saskatchewan') $stateB = 'SK';
if (trim(strtolower($stateB)) == 'yukon territory') $stateB = 'YT';

$rs = mysql_query("select countries_iso_code_2 from countries where lower(countries_name)='".strtolower($order->billing[country])."' limit 1");
$row = mysql_fetch_array($rs);
$countryB = $row['countries_iso_code_2'];

$countryB = (trim($countryB) != "") ? $countryB : "";

$stateB = (trim($stateB) != "") ? $stateB : "";

$customerSpecInfo = '
<CustomerSpecificData>
        <CustomData>
            <Reporting/>
            <Name>PurchaseOrder</Name>
            <Value>'.$order->info[purchase_order_number].'</Value>
        </CustomData>
        <CustomData>
            <Reporting/>
            <Name>CommentsSlip</Name>
            <Value>'.htmlentities($order->info[comments_slip]).'</Value>
        </CustomData>
        <CustomData>
            <Reporting/>
            <Name>AffiliateID</Name>
            <Value>'.$affId.'</Value>
        </CustomData>
        <CustomData>
            <Memo/>
            <Name>BillTo</Name>
            <Value>'.htmlentities($order->billing[company]).' | '.htmlentities($order->billing['name']).' | '.htmlentities($order->billing[street_address]).' | '.htmlentities($order->billing[suburb]).' | '.htmlentities($order->billing[city]).' | '.$stateB.' | '.$order->billing[postcode].' | '.$countryB.'</Value>
        </CustomData>
        <CustomData>
            <Reporting/>
            <Name>RootID</Name>
            <Value>'.$affRoot.'</Value>
        </CustomData>
        <CustomData>
            <Reporting/>
            <Name>TotalItems</Name>
            <Value>'.$totalNum.'</Value>
        </CustomData>
<CustomData>
     <Reporting/>
     <Name>Department</Name>
     <Value>USA</Value>
</CustomData>
<CustomData>
            <Reporting/>
            <Name>iswholesale</Name>
            <Value>'.htmlentities($order->info[iswholesale]).'</Value>
        </CustomData>
</CustomerSpecificData>';

$xmlFooter = '</ServiceOrder>';

$finalXml = $xmlHead . $finalProductInfo . $orderOptions . $sellerInfo . $customerInfo . $billingInfo . $customerSpecInfo . $xmlFooter;}

?>
<form action="customcdportland.php" method="post">
	<input type="hidden" name="list" value="<?=$productList?>" />
	<input type="hidden" name="action" value="send" />
	<input type="hidden" name="order_id" value="<?=$oID?>" />
	<input type="hidden" name="carrier" value="<?=$carrier?>" />
	<input type="hidden" name="carry_type" value="<?=$carry_type?>" />
	<textarea name="xmlArr" style="width: 480px; height: 400px;"><?=htmlentities($finalXml)?></textarea><br/><input type="submit" value="&nbsp;Send&nbsp;" />
</form>
<?
}


$url = "https://www.customcd.us/tvs/SubmitOrders.aspx";

if ($_POST['action'] == 'send') {
?>
<form action="customcdportland.php" method="post">
	<input type="hidden" name="list" value="<?=$productList?>" />
	<input type="hidden" name="action" value="send" />
	<input type="hidden" name="order_id" value="<?=$oID?>" />
	<input type="hidden" name="carrier" value="<?=$carrier?>" />
	<input type="hidden" name="carry_type" value="<?=$carry_type?>" />
<?
	$xml = stripslashes($_POST['xmlArr']);
	$data = submitXML($url, $xml, 'travelvideo', 'tr4vlv1m');
	$pos1 = strpos(strtolower($data), "acknowledgereceipt");
	$pos2 = strpos(strtolower($data), "confirmshipment");
	$pos3 = strpos(strtolower($data), "confirmburn");

	if ($pos1 !== false || $pos2 !== false || $pos3 !== false) {

$pos = explode(",", $productList);
foreach($pos as $key=>$value) {
	$sent = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
	mysql_query("update orders_products set date_sent_to_allied='".$sent."', is_allied=1, date_shipped_checkbox=1, fulfilled_by='POR' where orders_products_id='".$value."'");

$rs1 = mysql_query("select * from orders_products where orders_products_id='".$value."'");
		$order_products = mysql_fetch_array($rs1);

$rs2 = mysql_query("select * from products where products_id='".$order_products[products_id]."'");
		$product = mysql_fetch_array($rs2);

$total_ordered = $product[products_quantity] + $order_products[products_quantity] ;

	mysql_query("update products set products_quantity='".$total_ordered."' where products_id='".$order_products[products_id]."'");
}
	
//$comments = "Sent to Portland Fulfillment Center on " . date("m-d-Y H:i:s") ." - SentOrderID=" .$oID ;
//$comments = "Sent to Portland Fullfilment Center " . date("m-d-Y H:i:s") ." - SentOrderID=" .$oID." - ".$ShipCarrier."  ".$ShipOption ;

$carrier = $_POST['carrier'];
$carry_type = $_POST['carry_type'];


list($commentStatus, $orderStatus) = orderStatusAllied($oID);

$comments = "Sent to Portland Fulfillment Center " . date("m-d-Y H:i:s") ." - Order ID=" .$oID." - ".$carrier." ".$carry_type;


mysql_query("insert into orders_status_history set orders_id='".$oID."', orders_status_id=" . $commentStatus . ", date_added='".date("Y-m-d H:i:s")."', customer_notified=1, comments='".mysql_escape_string($comments)."'");

if ($orderStatus == "22") {
	mysql_query("update orders set orders_status='".$orderStatus."' where orders_id='" . $oID."'");
}
?>
	Answer: <textarea style="width: 480px; height: 200px;"><?=$data?></textarea>&nbsp;<input type="button" onclick="window.opener.location.reload(); window.close()" value="&nbsp;Done&nbsp;" />
<? } else {
	?>
	To Send: <textarea name="xmlArr" style="width: 480px; height: 200px;"><?=$xml?></textarea>&nbsp;<input type="submit" value="&nbsp;Send One More Time&nbsp;" /><br/>
	Answer: <textarea style="width: 480px; height: 200px;"><?=$data?></textarea>&nbsp;<input type="button" onclick="window.close()" value="&nbsp;Done&nbsp;" />
	<?	
}
exit;
}


function submitXml($url, $xml, $custkey, $pass) {
        $request = "POST /tvs/SubmitOrders.aspx HTTP/1.0\nCustomerKey: $custkey\nPassword:$pass\nUser_Agent:PHP 5.0.4\nHost: ".$host."\nContent-Type: text/xml\nContent-Length: ".strlen($xml)."\n\n".$xml."\n";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$request);
        $data = curl_exec($ch);

        if (curl_errno($ch)){
                print curl_error($ch); die();
        }
        else{
                curl_close($ch);
		return $data;
        }
}

function orderStatusAllied($orderId) {
      $order_query = tep_db_query("select count(*) as cnt from orders_products where orders_id = '" . (int)$orderId . "'");
      $total = tep_db_fetch_array($order_query);

      $order_query = tep_db_query("select count(*) as cnt from orders_products where is_allied=1 and orders_id = '" . (int)$orderId . "'");
      $isAllied = tep_db_fetch_array($order_query);

      $order_query = tep_db_query("select count(*) as cnt from orders_products where date_shipped_checkbox =1 and orders_id = '" . (int)$orderId . "'");
      $shippedDate = tep_db_fetch_array($order_query);

      if ($total['cnt'] == $isAllied['cnt']) return array(22, 22);

      if ($shippedDate['cnt'] == $total['cnt']) return array(22, 22);

      if ($total['cnt'] == $shippedDate['cnt']) {return array(22, 22);} else {return array(22, 'none');} 
}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
?>