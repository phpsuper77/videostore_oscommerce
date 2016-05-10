<?
require('includes/application_top.php');
include(DIR_WS_CLASSES . 'order.php');

$productList = (trim($_GET['list']) != '') ? $_GET['list'] : $_POST['list'];
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
		<option value="USPS">USPS</option>
		<option value="FedEx">FedEx</option>
		<option value="UPS">UPS</option>
		<option value="DHL">Freight</option>
	</select-->
	<select name="carry">
		<option value=""></option>
		<option value="US Mail">US Mail</option>
		<option value="UPS">UPS</option>
		<option value="FedEx">FedEx</option>
	</select>
	</br>
	Shipping Method: &nbsp;
	<!--select name="carry_type">
		<option value=""></option>
		<option value="USPS Media Mail">USPS Media Mail</option>
		<option value="USPS Parcel Mail">USPS Parcel Mail</option>
		<option value="USPS First Class Mail ">USPS First Class Mail </option>
		<option value="USPS Priority Mail">USPS Priority Mail</option>
		<option value="USPS Express Mail">USPS Express Mail</option>
		<option value="USPS Global Express Mail">USPS Global Express Mail</option>
		<option value="USPS Global Priority Mail">USPS Global Priority Mail</option>
		<option value="USPS Shipping Explanations">USPS Shipping Explanations</option>
		<option value="FedEx Ground">FedEx Ground</option>
		<option value="FedEx Home Delivery">FedEx Home Delivery</option>
		<option value="FedEx First Overnight">FedEx First Overnight</option>
		<option value="FedEx Priority Overnight">FedEx Priority Overnight</option>
		<option value="FedEx Standard Overnight">FedEx Standard Overnight</option>
		<option value="FedEx 2Day">FedEx 2Day</option>
		<option value="FedEx Express Saver">FedEx Express Saver</option>
		<option value="FedEx International Economy">FedEx International Economy</option>
		<option value="FedEx International Premium">FedEx International Premium</option>
		<option value="FedEx International Express FreightSM">FedEx International Express FreightSM</option>
		<option value="FedEx International First">FedEx International First</option>
		<option value="FedEx International Priority">FedEx International Priority</option>
		<option value="FedEx Freight">FedEx Freight</option>
		<option value="UPS Next Day Air Early A.M.">UPS Next Day Air Early A.M.</option>
		<option value="UPS Next Day Air">UPS Next Day Air</option>
		<option value="UPS Next Day Air Saver">UPS Next Day Air Saver</option>
		<option value="UPS 2nd Day Air">UPS 2nd Day Air</option>
		<option value="UPS 3 Day Select">UPS 3 Day Select</option>
		<option value="UPS Ground">UPS Ground</option>
		<option value="UPS Worldwide Express">UPS Worldwide Express</option>
		<option value="UPS Worldwide Saver">UPS Worldwide Saver</option>
		<option value="UPS Worldwide Expedited">UPS Worldwide Expedited</option>
		<option value="UPS Standard to Canada">UPS Standard to Canada</option>
		<option value="Worldwide Priority Express">Worldwide Priority Express</option>
		<option value="DHL Global Mail Priority">DHL Global Mail Priority</option>
		<option value="DHL Global Mail Standard">DHL Global Mail Standard</option>
		<option value="DHL Global Mail Economy">DHL Global Mail Economy</option>
		<option value="Ground">Ground</option>
		<option value="3 Day">3 Day</option>
		<option value="Best Available">Best Available</option>
	</select-->
	<select name="carry_type">
		<option value=""></option>
		<option value="First Class">US Mail First Class</option>
		<option value="Priority Mail">US Mail Priority Mail</option>
		<option value="Express Mail">US Mail Express Mail</option>
		<option value="International Air">US Mail International Air</option>
		<option value="Media Mail">US Mail Media Mail</option>
		<option value="Global Priority Mail">US Mail Global Priority Mail</option>
		<option value="Ground">UPS Ground</option>
		<option value="3 Day Select">UPS 3 Day Select</option>
		<option value="2nd Day Air">UPS 2nd Day Air</option>
		<option value="Next Day Air">UPS Next Day Air</option>
		<option value="Next Day Air Early AM">UPS Next Day Air Early AM</option>
		<option value="Standard">UPS Standard</option>
		<option value="Worldwide Expedited">UPS Worldwide Expedited</option>
		<option value="Worldwide Express">UPS Worldwide Express</option>
		<option value="Priority Overnight">FedEx Priority Overnight</option>
		<option value="Standard Overnight">FedEx Standard Overnight</option>
		<option value="Second Day">FedEx Second Day</option>
		<option value="International">FedEx International</option>
		<option value="Priority Overnight Saturday Delivery">FedEx Priority Overnight Saturday Delivery</option>
		<option value="Ground">FedEx Ground</option>
		<option value="Home">FedEx Home</option>
		<option value="International Ground">FedEx International Ground</option>
		<option value="International Economy">FedEx International Economy</option>
		<option value="International Priority">FedEx International Priority</option>
		<option value="International Mail">FedEx International Mail</option>
	</select>
<br/><br/><input type="submit" value="Apply" />&nbsp;&nbsp;<input type="button" onclick="window.close(); return false;" value="Close" />
</form>
<?}?>
<?
if ($_POST['action'] == 'apply') {

$oID = $_GET['order_id'];
$order = new order($oID);


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

		$rs = mysql_query("select * from products where products_id='".$order_products[products_id]."'");
		$product = mysql_fetch_array($rs);

		$rs = mysql_query("select * from products_to_allied where product_model='".$product[products_model]."'");
		$allied = mysql_fetch_array($rs);

	$qty = 0;		
	foreach($order->products as $k=>$v) {
		if ($v['ordered_products_id'] == $value) $qty = $v[qty];
	}

	$totalNum = $totalNum+$qty;

	if ($allied['product_set_total_disks'] == 1) {
		$productInfo .= '
			<Set copies="'.$qty.'">
			<MediaDefinitionProduct type="'.$allied[product_media_definition].'">
        			        <MediaFormat>'.$allied[product_disk_one_media_format].'</MediaFormat>
		                	<MediaContentName>'.$allied[product_model].'</MediaContentName>
	        		        <ProductId>'.$allied[product_model].'</ProductId>
		        	        <CDLabel>'.$allied[product_disk_one_cdlabel].'</CDLabel>
                			<LabelField number="1">'.$allied[product_disk_one_disclabel].'</LabelField>
                			<LabelField number="2">'.$allied[product_disk_one_wrap ].'</LabelField>
        			        <SilkScreen>'.$allied[product_disk_one_silkscreen].'</SilkScreen>
				   	<CDPackage>'.$allied[product_disk_one_cdpackage].'</CDPackage>
        			        <CDPackage>default</CDPackage>
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
	        		        <ProductId>'.$allied[product_model].'-DISK1</ProductId>
		        	        <CDLabel>'.$allied[product_disk_one_cdlabel].'</CDLabel>
                			<LabelField number="1">'.$allied[product_disk_one_disclabel].'</LabelField>
                			<LabelField number="2">'.$allied[product_disk_one_wrap ].'</LabelField>
        			        <SilkScreen>'.$allied[product_disk_one_silkscreen].'</SilkScreen>
				   	<CDPackage>'.$allied[product_disk_one_cdpackage].'</CDPackage>
        			        <CDPackage>2discdvdcase</CDPackage>
			            </MediaDefinitionProduct>

			<MediaDefinitionProduct type="'.$allied[product_media_definition].'">
        			        <MediaFormat>'.$allied[product_disk_two_media_format].'</MediaFormat>
		                	<MediaContentName>'.$allied[product_model].'</MediaContentName>
	        		        <ProductId>'.$allied[product_model].'-DISK2</ProductId>
		        	        <CDLabel>'.$allied[product_disk_two_cdlabel].'</CDLabel>
                			<LabelField number="1">'.$allied[product_disk_two_disclabel].'</LabelField>
                			<LabelField number="2">'.$allied[product_disk_otw_wrap ].'</LabelField>
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
<!DOCTYPE ServiceOrder SYSTEM "http://www.tangibleonline.com/dtd/orderlink/v3_1/ServiceOrder.dtd"><ServiceOrder version="3.1" campaign="298">';

$rs = mysql_query("select abbrev from states where name='".$order->delivery[state]."' limit 1");
$row = mysql_fetch_array($rs);
$state = $row['abbrev'];

$rs = mysql_query("select countries_iso_code_2 from countries where countries_name='".$order->delivery[country]."' limit 1");
$row = mysql_fetch_array($rs);
$country = $row['countries_iso_code_2'];

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

if (trim($order->delivery[street_address]) == '' && trim($order->delivery[suburb]) !='') {
	$address1 = $order->delivery[suburb];
	$address2 = '';
}

$customerInfo = '
<Recipient>
        <CompanyName>'.$order->customer[company].'</CompanyName>
        <AttentionName>'.$order->delivery['name'].'</AttentionName>
        <Address1>'.$address1.'</Address1>
        <Address2>'.$address2.'</Address2>
        <City>'.$order->delivery[city].'</City>
        <StateProvince>'.((trim($state) != "") ? $state : "").'</StateProvince>
        <PostalCode>'.$order->delivery[postcode].'</PostalCode>
        <Country>'.((trim($country) != "") ? $country : "").'</Country>
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

$customerSpecInfo = '
<CustomerSpecificData>
        <CustomData>
            <Reporting/>
            <Name>PurchaseOrder</Name>
            <Value>'.$OID.'</Value>
        </CustomData>
        <CustomData>
            <Reporting/>
            <Name>AffiliateID</Name>
            <Value>'.$affId.'</Value>
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
</CustomerSpecificData>';

$xmlFooter = '</ServiceOrder>';

$finalXml = $xmlHead . $finalProductInfo . $orderOptions . $sellerInfo . $customerInfo . $billingInfo . $customerSpecInfo . $xmlFooter;}

?>
<form action="allied.php" method="post">
	<input type="hidden" name="list" value="<?=$productList?>" />
	<input type="hidden" name="action" value="send" />
	<input type="hidden" name="order_id" value="<?=$oID?>" />
	<textarea name="xmlArr" style="width: 480px; height: 400px;"><?=htmlentities($finalXml)?></textarea><br/><input type="submit" value="&nbsp;Send&nbsp;" />
</form>
<?
}


$url = "http://medialinx.alliedvaughn.com:80";

if ($_POST['action'] == 'send') {
?>
<form action="allied.php" method="post">
	<input type="hidden" name="list" value="<?=$productList?>" />
	<input type="hidden" name="action" value="send" />
	<input type="hidden" name="order_id" value="<?=$oID?>" />
<?
	$xml = stripslashes($_POST['xmlArr']);
	$data = submitXML($url, $xml, 'travelvideo', 'tr4vlv1m');
	$pos1 = strpos(strtolower($data), "acknowledgereceipt");
	$pos2 = strpos(strtolower($data), "confirmshipment");
	$pos3 = strpos(strtolower($data), "confirmburn");

	if ($pos1 !== false || $pos2 !== false || $pos3 !== false) {

$pos = explode(",", $productList);
foreach($pos as $key=>$value) {
	mysql_query("update orders_products set is_allied=1, date_shipped_checkbox=1 where orders_products_id='".$value."'");
}	
$comments = "Sent to allied on " . date("m-d-Y H:i:s");


list($commentStatus, $orderStatus) = orderStatusAllied($oID);


mysql_query("insert into orders_status_history set orders_id='".$oID."', orders_status_id=" . $commentStatus . ", date_added='".date("Y-m-d H:i:s")."', customer_notified=0, comments='".mysql_escape_string($comments)."'");

if ($orderStatus!='none') {
	mysql_query("insert into orders set orders_status='".$orderStatus."' where orders_id=" . $oID);
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
        $request = "POST /xml/TWeb/ HTTP/1.0\nCustomerKey: $custkey\nPassword:$pass\nUser_Agent:PHP 5.0.4\nHost: ".$host."\nContent-Type: text/xml\nContent-Length: ".strlen($xml)."\n\n".$xml."\n";

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

      if ($total['cnt'] = $isAllied['cnt']) return array(3, 3);

      if ($shippedDate['cnt'] == $total['cnt']) return array(3, 3);

      if ($isAllied['cnt'] != $shippedDate['cnt']) return array(3, 'none');
}
?>