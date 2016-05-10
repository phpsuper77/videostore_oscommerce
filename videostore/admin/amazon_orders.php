<?php
/*
  $Id: stats_customers.php,v 1.31 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

function validate_email($email)
{
   // Create the syntactical validation regular expression
   $regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
   // Presume that the email is invalid
   $valid = 0;
   // Validate the syntax
   if (preg_match('/'.$regexp.'/i', $email))
   {
      list($username,$domaintld) = preg_split("/@/",$email);
      // Validate the domain
      if (getmxrr($domaintld,$mxrecords))         $valid = 1;
   	  $valid = 1;	
   } else {
      $valid = 0;
   }
   return $valid;
}

function month_to_numbers($str){
	for ($i=1;$i<13;$i++){
		$curr_month = strtolower(date("M", mktime(0,0,0,$i,1,2006)));
		if (strlen($i)==1) $k = "0".$i; else $k=$i;
		if (strpos($str, $curr_month))  $str = trim(str_replace($curr_month, $k, $str));
	}	
	
	$part = explode(" ", $str);
	$wrong_date = explode("-", $part[0]);
	$date = $wrong_date[2]."-".$wrong_date[1]."-".$wrong_date[0];
	$time = $part[1];
	return $date." ".$time;
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">AMAZON ORDERS</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top">
<?if (($_POST[action]=='') || ($_POST[action]=='proceed')) {?>
	   <table border="0" width="100%" cellspacing="0" cellpadding="2">
		<form action="amazon_orders.php" method="post">
			<input type="hidden" name="action" value="proceed" />
	     <tr><td width="150"><b>Insert Emailed order:</b></td><td><textarea name="email_body" style="width:800px;height:400px;"><?=stripslashes($_POST[email_body])?></textarea></td></tr>
<?if ($_POST[action]=="") {?>
	     <tr><td colspan="2" align="center"><input type="submit" value="Proceed" /></td></tr>
<?} else {
?>
	     <tr><td colspan="2" align="center"><input type="submit" value="Re-proceed Email" /></td></tr>
<?}?>
		</form>
	     </tr>
            </table>
<? } ?>
<?
if ($_POST[action]=="proceed") {
$user = array();
$product_zone = false;
$sku_tmp = array(); $qty_tmp = array(); $price_tmp = array();
$email_body = strtolower(stripslashes($_POST['email_body']));

$lines = explode("\n", $email_body);
$get_comments = false;
foreach($lines as $line){
	if (strpos($line, "ship to:")!==false) { $name = explode(":", $line); $user['name'] = ucwords(trim($name[1])); }
	if (strpos($line, "address line 1:")!==false) { $address1 = explode(":", $line); $user['address1'] = ucwords(trim($address1[1])); }
	if (strpos($line, "address line 2:")!==false) { $address2 = explode(":", $line); $user['address2'] = ucwords(trim($address2[1])); }
	if (strpos($line, "city:")!==false) {$city = explode(":", $line); $user['city'] = ucwords(trim($city[1])); }
	if (strpos($line, "zip/postal code:")!==false) { $zip = explode(":", $line); $user['zip'] = ucwords(trim($zip[1])); }
	if (strpos($line, "country:")!==false) { $country = explode(":", $line); $user['country'] = ucwords(trim($country[1])); }
	if (strpos($line, "buyer name:")!==false) { $buyer_name = explode(":", $line); $user['buyer_name'] = ucwords(trim($buyer_name[1])); }
	if (strpos($line, "state/province/region:")!==false) { $state = explode(":", $line); $user['state'] = strtoupper(trim(str_replace(".","",$state[1]))); }

	if (strpos($line, "additional shipping credit:")!==false) {
		$shipping_credit = explode(":", $line); 
		if (strpos($shipping_credit[1], "$")!==false) $shipping_credit[1] = substr(trim($shipping_credit[1]),1);
		$user['shipping_credit'] = trim($shipping_credit[1]); 
		}

	if (strpos($line, "buyer e-mail:")!==false) { $buyer_email = explode(":", $line); $user['buyer_email'] = trim($buyer_email[1]); }
	if (strpos($line, "time of sale:")!==false) { $sale_time = explode("sale:", $line); $user['date'] = trim(month_to_numbers($sale_time[1])); }
	if (strpos($line, "shipping speed:")!==false) { $speedity = explode(":", $line); $user['speedity'] = ucwords(trim($speedity[1])); }

	if (strpos($line, "order #:")!==false) { $PO_order = explode(":", $line);	$user['PO_order'] = trim($PO_order[1]); $product_zone = true; }
	if ($product_zone==true){

		if (strpos($line, "sku:")!==false) $sku_tmp = explode(":", $line); 
		if (strpos($line, "quantity:")!==false) $qty_tmp = explode(":", $line); 
		if (strpos($line, "buyer's price:")!==false) {
			$price_tmp = explode(":", $line); 
			if (strpos($price_tmp[1], "$")!==false) $price_tmp[1] = substr(trim($price_tmp[1]),1);
			}
			if (!empty($sku_tmp) && !empty($qty_tmp) && !empty($price_tmp[1])){
				$products[] = array("sku"=>strtolower(trim($sku_tmp[1])), "qty"=>trim($qty_tmp[1]), "price"=>$price_tmp[1]);
				$sku_tmp = ""; $qty_tmp = ""; $price_tmp = "";
		}
		
	}	
	if ($get_comments!=true) { 
		if (strpos($line, "here are the details of your completed amazon marketplace")!==false) $get_comments = true;
				}
		if (strpos($line, "amazon commission:")!==false) $get_comments = false;

	if ($get_comments==true) $comments .=str_replace(">","",$line);
}
/*
print_r($user);
echo "<hr>";
print_r($products);
echo "<hr>";
*/
?>
<br/><br/>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<form action="amazon_orders.php" method="post">
	<input type="hidden" name="action" value="final_save" />
<?
	$count = tep_db_num_rows(tep_db_query("SELECT  * FROM  `customers` WHERE customers_email_address = '".$user[buyer_email]."' limit 1"));
	$part = explode(" ", $user[buyer_name]);
	if (count($part)>2){
		$fname = $part[0]." ".$part[1];
		for ($i=2;$i<count($part);$i++) $lname .= $part[$i]." ";
	}
	else{
		$fname = $part[0];
		$lname = $part[1];
	}

	$valid = validate_email($user[buyer_email]);
?>
<tr>
	<td align="right" valign="top"><b>Customer Info:</b></td>
	<td>
	<table>	
	<input type="hidden" name="exists" value="<?=$count?>" />
	<tr><td>Email:</td><td><input type="text" name="customer[customer_email]" readonly value="<?=$user[buyer_email]?>" />&nbsp; <? if ($valid==false) echo "<font style='color:red; font-weight:italic'>Invalid email!</font>";?></td></tr>
	<tr><td colspan="2"><i><?if ($count==0) echo "Not found in customer DB."; else echo "Already found in customer DB.";?></td></i></tr>
	<tr><td>First Name:</td><td><input  name="customer[customer_firstname]" type="text" value="<?=$fname?>" /></td></tr>
	<tr><td>Last Name:</td><td><input name="customer[customer_lastname]" type="text" value="<?=$lname?>" /></td></tr>
	</table>
	</td>
</tr>
<tr>
	<td align="right" width="150" valign="top"><b>Shipping/Billing Info:</b></td>
	<td width="50%">
	<table>
		<tr><td colspan="2">BILL TO:</td></tr>
		<tr><td>Name: </td><td><input type="text" name="billing_name" value="Amazon Marketplace Orders" /></td></tr>
		<tr><td>Company: </td><td><input name="billing_company" type="text" value="Travelvideostore.com" /></td></tr>
		<tr><td>Street 1: </td><td><input name="billing_address1" type="text" value="5420 Boran Dr" /></td></tr>
		<tr><td>Street 2: </td><td><input name="billing_address2" type="text" value="" /></td></tr>
		<tr><td>City: </td><td><input name="billing_city" type="text" value="Tampa" /></td></tr>
		<tr><td>State: </td><td><input name="billing_state" type="text" value="Florida" /></td></tr>
		<tr><td>ZIP: </td><td><input  name="billing_postcode" type="text" value="33610" /></td></tr>
		<tr><td>Country: </td><td><input name="billing_country" type="text" value="United States" /></td></tr>
	</table>
	</td>
	<td width="50%">
	<table>
		<tr><td colspan="2">SHIP TO:</td></tr>
		<input type="hidden" name="shipping_short_state" value="<?=$user[state]?>" />
		<tr><td>Name: </td><td><input type="text" name="shipping_name" value="<?=$user[name]?>" /></td></tr>
		<tr><td>Company: </td><td><input  name="shipping_company" type="text" value="<?=$user[company]?>" /></td></tr>
		<tr><td>Street 1: </td><td><input name="shipping_street1" type="text" value="<?=$user[address1]?>" /></td></tr>
		<tr><td>Street 2: </td><td><input name="shipping_street2" type="text" value="<?=$user[address2]?>" /></td></tr>
		<tr><td>City: </td><td><input  name="shipping_city" type="text" value="<?=$user[city]?>" /></td></tr>
<?
if ((strtoupper($user[country])=='USA') || (strtoupper($user[country])=='UNITED STATES') || (strtoupper($user[country])=='US')){
	if (strlen($user[state])==2)
	$state = tep_db_fetch_array(tep_db_query("select name from states where abbrev='".$user[state]."' limit 1"));
	else
	$state[name] = ucfirst($user[state]);

	$user[country] = 'United States';
	}
	else {
		$state[name] = ucfirst($user[state]);
		$user['speedity'] = 'International Standard Shipping';
	}
?>
		<tr><td>State: </td><td><input name="shipping_state" type="text" value="<?=$state[name]?>" /></td></tr>
		<tr><td>ZIP: </td><td><input name="shipping_postcode" type="text" value="<?=$user[zip]?>" /></td></tr>
		<tr><td>Country: </td><td><input  name="shipping_country" type="text" value="<?=$user[country]?>" /></td></tr>
	</table>
	</td>
</tr>
<tr>
	<td align="right" valign="top"><b>Products found:</b></td>
	<td colspan="2">
	<table>
	<?
if (is_array($products)){
	$i = 0;
	foreach ($products as $a){
		if (strpos("special-", $a['sku'])==true) { $a['sku'] = str_replace("special-", "", $a['sku']);	}
		$sql = tep_db_query("SELECT  * FROM  `products` WHERE products_model = '".$a[sku]."' limit 1");
		$prod = tep_db_fetch_array($sql);
		$count = tep_db_num_rows($sql);
	?>
	 <tr>
		<td><b>SKU:</b><br/><input id="products_sku_<?=$i?>" type="text" style="width:120px;" name="products_sku[]" value="<?=$a['sku']?>"/></td><td>
		<td><b>Q-ty:</b><br/><input id="products_qty_<?=$i?>" type="text" style="width:40px;" name="products_qty[]" value="<?=$a['qty']?>"/></td>
		<td><b>Price:</b><br/><input id="products_price_<?=$i?>" type="text" style="width:80px;" name="products_price[]" value="<?=$a['price']?>"/></td>
		<td><b>DB Price:</b><br/><input  id="products_db_price_<?=$i?>" type="text" style="width:80px;" value="<?=$prod['products_price']?>" disabled /></td>
		<td><b>Qty on stock:</b><br/><input  id="products_qty_stock_<?=$i?>" type="text" style="width:80px;" value="<?=$prod['products_quantity']?>" disabled /></td>
		<td>
		<? if (($count==0)) echo "<font style='font-weight:Italic;color:red'>Not found in product DB! Check SKU!</font>"; ?>
		<? if (($prod['products_quantity']<0) || ($prod['products_quantity']==0)) echo "<font style='font-weight:Italic;color:red'>Item quantity equal to zero or less!</font>"; ?>
		</td>
		<? if ($count==0 || $prod['products_quantity']<0 || $prod['products_quantity']==0){ ?>
			<td><input type='button' onClick='winOpen("<?=$i?>", "<?=$a[sku]?>")' value='Find Product'/></td>
		<? } ?>
			
	</tr>	
	<?	
	$i++;
	}
}
?>
<script>
	function winOpen(cnt, sku){
		window.open ("amazon_search_sku.php?cnt="+cnt+"&sku="+sku, "SEARCHING","location=0,status=0,scrollbars=0, width=500,height=500");	
	}
</script>
	</table>
	</td>
</tr>
	<tr><td align="right"><b>Order Details:</b></td><td>PO number: <input type="text" name="po_number" value="<?=$user[PO_order]?>" /></td></tr>
	<!--<tr><td></td><td>Order Date: <input type="text" name="date_purchased" value="<?=$user[date]?>" /></td></tr>-->
	<tr><td></td><td>Order Type: <input type="text" name="order_type" value="<?=$user[speedity]?>" /></td></tr>
	<tr><td></td><td>Shipping Credits: <input  name="shipping_credits" type="text" value="<?=$user[shipping_credit]?>" /></td></tr>

	<tr><td align="right"><b>Comments:</b></td><td><textarea name="comments" style="width:400px; height:200px;"><?=trim(ucwords($comments))?></textarea></td></tr>
<?
	$filename = 'includes/languages/english/vendors_email_body.dat';
	$fp = fopen($filename,'r');
	$text_block = @fread($fp, filesize ($filename));
	fclose($fp);			

?>
	<tr><td align="right"><b>Email Sending<br/>Text Block:</b></td><td><textarea name="email_text_block" style="width:400px; height:200px;"><?=$text_block?></textarea></td></tr>
<tr><td style="padding-top:10px;" colspan="3" align="center">
<?
$excistance = tep_db_num_rows(tep_db_query("select * from orders where purchase_order_number='".$user['PO_order']."'"));
if ($excistance>0) {$sts = 'disabled'; echo "<font color='red'><b>Either Purchase Order have been unrecognized or  this order already exsists in order database!</b></font><br/>";}
?>
<input type="submit" value="Save Order" <?=$sts?> />
</td></tr>
</form>
</table>
<? } 

if ($_POST[action]=='final_save'){
	if ($_POST[exists]==0){	
		$sql_query = "insert into customers set purchased_without_account=0, customers_firstname='".mysql_escape_string($customer[customer_firstname])."', customers_lastname='".mysql_escape_string($customer[customer_lastname])."', customers_email_address='".mysql_escape_string($customer[customer_email])."', customers_password='".tep_encrypt_password('travel')."', customers_type=0, customers_allow_purchase_order_entry='true'";
		tep_db_query($sql_query);
		$customer_id = tep_db_insert_id();
		$country_id = tep_db_fetch_array(tep_db_query("select countries_id from countries where countries_name='".mysql_escape_string($shipping_country)."' limit 1"));		
		if ($shipping_country=='United States')
		$_state = mysql_escape_string($shipping_short_state);
		else
		$_state = $shipping_state;
		$zone_id = tep_db_fetch_array(tep_db_query("select zone_id from zones where zone_code='".$_state."' limit 1"));
		$sql_query = "insert into address_book set customers_id='".$customer_id."', entry_company='".mysql_escape_string($shipping_company)."', entry_firstname='".mysql_escape_string($customer[customer_firstname])."', entry_lastname='".mysql_escape_string($customer[customer_lastname])."', entry_street_address='".mysql_escape_string($shipping_street1)."', entry_suburb='".mysql_escape_string($shipping_street2)."', entry_postcode='".$shipping_postcode."', entry_city='".mysql_escape_string($shipping_city)."', entry_state='".mysql_escape_string($shipping_state)."', entry_country_id='".$country_id[countries_id]."', entry_zone_id='".$zone_id[zone_id]."'";
		tep_db_query($sql_query);
		$address_book_id = tep_db_insert_id();
		$sql_query = "update customers set customers_default_address_id='".$address_book_id."' where customers_id=".$customer_id;
		tep_db_query($sql_query);

		$date_created = date("Y-m-d H:i:s");
		tep_db_query("insert into customers_info set customers_info_id='".$customer_id."', customers_info_date_account_created='".$date_created."', customers_info_source_id=0, global_product_notifications=0");

	}

	$sql_query = "SELECT a .  * , b .  * , c.countries_name FROM customers a LEFT  JOIN address_book b ON ( a.customers_default_address_id = b.address_book_id ) LEFT  JOIN countries c ON ( b.entry_country_id = c.countries_id ) WHERE a.customers_email_address =  '".$customer[customer_email]."' LIMIT 1 ";
	$customer = tep_db_fetch_array(tep_db_query($sql_query));

//if ($_POST[exists]!=0){
	$customer_ship[entry_company]  =$shipping_company;
	$customer_ship[fio]=$shipping_name;
	$customer_ship[entry_street_address]=$shipping_street1;
	$customer_ship[entry_suburb]=$shipping_street2;
	$customer_ship[entry_postcode]=$shipping_postcode;
	$customer_ship[entry_city]=$shipping_city;
	$customer_ship[entry_state]=$shipping_state;
	$customer_ship[countries_name]=$shipping_country;
//}
/*
else{
	$customer_ship[entry_company]  =$customer['entry_company'];
	$customer_ship[fio]=$customer['customers_firstname'] . ' ' . $customer['customers_lastname'];
	$customer_ship[entry_street_address]=$customer['entry_street_address'];
	$customer_ship[entry_suburb]=$customer['entry_suburb'];
	$customer_ship[entry_postcode]=$customer['entry_postcode'];
	$customer_ship[entry_city]=$customer['entry_city'];
	$customer_ship[entry_state]=$customer['entry_state'];
	$customer_ship[countries_name]=$customer['countries_name'];
}
*/

$date_purchased = date("Y-m-d H:i:s");

  $sql_data_array = array('customers_id' => $customer['customers_id'],
                          'customers_name' => $customer['customers_firstname'] . ' ' . $customer['customers_lastname'],
                          'customers_company' => $customer['entry_company'],
                          'customers_street_address' => $customer['entry_street_address'],
                          'customers_suburb' => $customer['entry_suburb'],
                          'customers_city' => $customer['entry_city'],
                          'customers_postcode' => $customer['entry_postcode'],
                          'customers_state' => $customer['entry_state'],
                          'customers_country' => $customer['countries_name'],
                          'customers_telephone' => $customer['customers_telephone'],
                          'customers_email_address' => $customer['customers_email_address'],
                          'customers_address_format_id' => '2',
                          'delivery_name' => $customer_ship['fio'],
                          'delivery_company' => $customer_ship['entry_company'],
                          'delivery_street_address' => $customer_ship['entry_street_address'],
                          'delivery_suburb' => $customer_ship['entry_suburb'],
                          'delivery_city' => $customer_ship['entry_city'],
                          'delivery_postcode' => $customer_ship['entry_postcode'],
                          'delivery_state' => $customer_ship['entry_state'],
                          'delivery_country' => $customer_ship['countries_name'],
                          'delivery_address_format_id' => '2',
			  'billing_name' => $billing_name, 
			  'billing_company' => $billing_company, 
	                  'billing_street_address' => $billing_address1, 
	                  'billing_suburb' => '', 
	                  'billing_city' => $billing_city, 
	                  'billing_postcode' => $billing_postcode, 
	                  'billing_state' => $billing_state, 
	                  'billing_country' => $billing_country,
	                  'billing_address_format_id' => '2',
                          'payment_method' => 'Amazon Marketplace',
                          'cc_type' => '',
                          'cc_owner' => '',
                          'cc_number' => '',
                          'cc_expires' => '',
                          'date_purchased' => $date_purchased,
                          'orders_status' => '1',
                          'purchase_order_number' => $po_number,
                          'currency' => 'USD',
                          'currency_value' => '1.00000000',
                          'customers_referer_url' => '',
                          'ipaddy' => '',
			  'ipisp' => '');
  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $org_insert_id = tep_db_insert_id();
  $insert_id = tep_db_insert_id()+2;
  $ord_upd = tep_db_query("update ".TABLE_ORDERS." set orders_id = ".$insert_id." where orders_id = ".$org_insert_id);

$length = count($products_sku);
$total_price = 0;
$products_body = '<table width="600" border="1"><tr><td width="150"><b>Q-ty</b></td><td width="450"><b>Title</b></td></tr>';
for ($i=0;$i<$length; $i++){
 $sel_prod = tep_db_fetch_array(tep_db_query("select a.products_price, b.products_id, b.products_name_prefix, b.products_name, b.products_name_suffix, a.products_model, c.series_name from products a left join products_description b on (a.products_id = b.products_id) LEFT JOIN series c ON (a.series_id = c.series_id) where a.products_model='".$products_sku[$i]."' limit 1"));



    $sql_query = "SELECT vendors_item_cost, vendors_product_payment_type, vendors_consignment, vendors_consignment_percentage, vendors_royalty , vendors_royalty_percentage FROM products_to_vendors where products_id=".$sel_prod[products_id]." limit 0,1";
    $prod_vendor = tep_db_fetch_array(tep_db_query($sql_query));

	switch($prod_vendor['vendors_product_payment_type']){
	case "1":
		$final_prod_vendor = $prod_vendor['vendors_item_cost'];		
	break;

	case "2":
		if (intval($prod_vendor['vendors_consignment'])==1) $final_prod_vendor = $prod_vendor['vendors_item_cost'];				
		if (intval($prod_vendor['vendors_consignment'])==2) $final_prod_vendor = ($sel_prod[products_price]/100)*$prod_vendor['vendors_consignment_percentage'];
		if (intval($prod_vendor['vendors_consignment'])==3) $final_prod_vendor = ($products_price[$i]/100)*$prod_vendor['vendors_consignment_percentage'];				
	break;

	case "3":
		if (intval($prod_vendor['vendors_royalty'])==1) $final_prod_vendor = $prod_vendor['vendors_item_cost'];				
		if (intval($prod_vendor['vendors_royalty'])==2) $final_prod_vendor = ($sel_prod[products_price]/100)*$prod_vendor['vendors_royalty_percentage'];
		if (intval($prod_vendor['vendors_royalty'])==3) $final_prod_vendor = ($products_price[$i]/100)*$prod_vendor['vendors_royalty_percentage'];
	break;
	default: 
		$prod_vendor['vendors_product_payment_type'] = '1';
	break;

}


//    $final_prod_vendor = $final_prod_vendor*$products_qty[$i];

    $total_price = $total_price+($products_price[$i]*$products_qty[$i]);

if (trim($sel_prod[series_name])!="") $series = "<b>".$sel_prod[series_name]."</b> - ";
    $products_body .= "<tr><td width='150'>".$products_qty[$i]."</td><td width='450'>".$series.$sel_prod[products_name_prefix]." ".$sel_prod[products_name]." ".$sel_prod[products_name_suffix]."(".$sel_prod[products_model].") = $".($products_price[$i]*$products_qty[$i])."</td></tr>";
    tep_db_query("insert into orders_products set products_item_cost='".$final_prod_vendor."', products_sale_type='".$prod_vendor[vendors_product_payment_type]."', orders_id='".$insert_id."', products_id='".$sel_prod[products_id]."', products_model='".mysql_escape_string(strtoupper($products_sku[$i]))."', products_name='".mysql_escape_string($series.$sel_prod[products_name_prefix]." ".$sel_prod[products_name]." ".$sel_prod[products_name_suffix])."', products_price='".$sel_prod[products_price]."' , final_price='".$products_price[$i]."', products_quantity='".$products_qty[$i]."', products_prepared=0, ordered=0");
    tep_db_query("update products set products_ordered = products_ordered + " . $products_qty[$i] . ", products_quantity = products_quantity - " . $products_qty[$i]." where products_id = '" . $sel_prod[products_id] . "'");
	}
    tep_db_query("insert into orders_status_history set orders_id='".$insert_id."', orders_status_id=1, date_added='".$date_purchased."', customer_notified=1, comments='".mysql_escape_string($comments)."'");

$products_body .="</table><br>";

$bottom_email_text = '';
    $tot = $total_price+$shipping_credits;
	for ($i=0;$i<3;$i++){
		$one = str_replace("$", "", $currencies->format($shipping_credits, true, 'USD','1.000000'));
		$two = str_replace("$", "", $currencies->format($total_price, true, 'USD','1.000000'));
		$three = str_replace("$", "", $currencies->format($tot, true, 'USD','1.000000'));
		if ($i==0) {
			$bottom_email_text .= "<b>Shipping speed: ".$order_type."</b> $".$one."<br>";
			tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>".$order_type."</b>', text='".$currencies->format($shipping_credits,true,'USD', '1.000000')."', value='".$one."', class='ot_shipping', sort_order=1");
		}
		if ($i==1) {
			$bottom_email_text .= "<b>Sub-Total:</b> $".$two."<br>";
			tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>Sub-Total:</b>', text='".$currencies->format($total_price, true,'USD','1.000000')."', value='".$two."', class='ot_subtotal', sort_order=2");
		}
		if ($i==2) {
			$bottom_email_text .= "<b>Total:</b> $".$three."<br>";
			tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>Total:</b>', text='<b>".$currencies->format($tot, true, 'USD','1.00000')."</b>', value='".$three."', class='ot_total', sort_order=3");
		}
	}
echo "<h2>ORDER #: ".$insert_id."</h2>";

$delivery_block  ="<br><b>Delivery Address:</b><br>";
if (trim($customer_ship['fio'])!='') $delivery_block .=$customer_ship['fio']."<br>";
if (trim($customer_ship['entry_company'])!='') $delivery_block .=$customer_ship['entry_company']."<br>";
if (trim($customer_ship['entry_street_address'])!='') $delivery_block .=$customer_ship['entry_street_address']."<br>";
if (trim($customer_ship['entry_street_address2'])!='') $delivery_block .=$customer_ship['entry_street_address2']."<br>";
$delivery_block .=$customer_ship['entry_city']." ".$customer_ship['entry_state']." ".$customer_ship['entry_postcode']."<br>";
if (trim($customer_ship['countries_name'])!='') $delivery_block .=$customer_ship['countries_name']."<br>";

/*
$billing_block ="<br><b>Billing Address:</b><br>";
$billing_lock .=$billing_name."<br>";
if ($billing_company!='')
$billing_block .=$billing_company."<br>";
$billing_block .=$billing_address1."<br>".$billing_city.", ".$billing_state." ".$billing_postcode."<br>";
$billing_block .=$billing_country."<br>";
*/

$email_body  = "Date: ".date('l, F d, Y H:i A')."<br><br>";
$email_body .= "Travelvideostore.com<br><hr>";
if (trim($email_text_block)!='')
$email_body .= stripslashes(nl2br($email_text_block))."<hr>";
$email_body .= "<br>";
$email_body .= "TRAVELVIDEOSTORE.COM ORDER NUMBER: ".$insert_id."<br>Customer Email Address: ".$customer[customers_email_address]."<br>";
if (trim($po_number)!='') $email_body .= "AMAZON MARKETPLACE ORDER NUMBER: ".$po_number."<br>";
$email_body .= "Detailed Invoice: <a href='https://www.travelvideostore.com/account_history_info.php?order_id=".$insert_id."'>https://www.travelvideostore.com/account_history_info.php?order_id=".$insert_id."</a><br>";
$email_body .= "Date Ordered: ".$date_purchased."<br>".$delivery_block."<br>Products<br><hr>";
$email_body .= $products_body."<br><hr>";
$email_body .= $bottom_email_text;



$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers .= "From: Customer Service <customerservice@travelvideostore.com>\r\n";
$headers .= "To: ".$customer['customers_firstname']." ".$customer['customers_lastname']." <".$customer[customers_email_address].">\r\n";
mail($customer[customers_email_address], 'TRAVELVIDEOSTORE.COM Amazon Marketplace Order Confirmation', $email_body, $headers);
mail('amazonsales@travelvideostore.com', 'TRAVELVIDEOSTORE.COM Amazon Marketplace Order Confirmation', $email_body, $headers);

?>
<li>Click <a target="_new" style="font-size:12px;font-weight:bold; color:blue" href='customers.php?selected_box=customers&language=english&cID=<?=$customer[customers_id]?>&action=edit'>here</a> to see user information<br/><li>Click <a target="_new" style="font-size:12px;font-weight:bold; color:blue" href='orders.php?language=english&oID=<?=$insert_id?>&action=edit'>here</a> to see order details<br/><br/><center><input type='button' value='Proceed New Email >>' onClick="window.location.href='amazon_orders.php'" /></center>
<?
}
?>
	    </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
