<?
  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
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
            <td class="pageHeading">AMAZON CA FILE PARSER:</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
		<td>
<?
if ($_POST[action]=='final_save'){
	if ($_POST[exists]==0){	
		$sql_query = "insert into customers set purchased_without_account=0, customers_firstname='".mysql_escape_string($customer[customer_firstname])."', customers_lastname='".mysql_escape_string($customer[customer_lastname])."', customers_email_address='".mysql_escape_string($customer[customer_email])."', customers_password='".tep_encrypt_password('travel')."', customers_type=0, customers_allow_purchase_order_entry='true'";
		tep_db_query($sql_query);
		$customer_id = tep_db_insert_id();
		$country_id = tep_db_fetch_array(tep_db_query("select countries_id from countries where countries_name='".mysql_escape_string($shipping_country)."' or countries_iso_code_2='".mysql_escape_string($shipping_country)."' or countries_iso_code_3='".mysql_escape_string($shipping_country)."' limit 1"));
		if ($shipping_country=='United States')
		$_state = mysql_escape_string($shipping_short_state);
		else
		$_state = $shipping_state;
		$zone_id = tep_db_fetch_array(tep_db_query("select zone_id from zones where zone_code='".$_state."' limit 1"));
		$sql_query = "insert into address_book set customers_id='".$customer_id."', entry_company='".mysql_escape_string($shipping_company)."', entry_firstname='".mysql_escape_string($customer[customer_firstname])."', entry_lastname='".mysql_escape_string($customer[customer_lastname])."', entry_street_address='".mysql_escape_string($shipping_street1)."', entry_suburb='".mysql_escape_string($shipping_street2)."',entry_postcode='".$shipping_postcode."', entry_city='".mysql_escape_string($shipping_city)."', entry_state='".mysql_escape_string($shipping_state)."', entry_country_id='".$country_id[countries_id]."', entry_zone_id='".$zone_id[zone_id]."'";
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
//else{
/*
	$customer_ship[entry_company]  =$customer['entry_company'];
	$customer_ship[fio]=$customer['customers_firstname'] . ' ' . $customer['customers_lastname'];
	$customer_ship[entry_street_address]=$customer['entry_street_address'];
	$customer_ship[entry_suburb]=$customer['entry_suburb'];
	$customer_ship[entry_postcode]=$customer['entry_postcode'];
	$customer_ship[entry_city]=$customer['entry_city'];
	$customer_ship[entry_state]=$customer['entry_state'];
	$customer_ship[countries_name]=$customer['countries_name'];
*/
//}

$date_purchased = date("Y-m-d H:i:s");
$rt = tep_db_fetch_array(tep_db_query("select value from currencies where code='CAD' limit 1"));

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
                          'payment_method' => 'Amazon CA Marketplace',
                          'cc_type' => '',
                          'cc_owner' => '',
                          'cc_number' => '',
                          'cc_expires' => '',
                          'date_purchased' => $date_purchased,
                          'orders_status' => '1',
                          'purchase_order_number' => $po_number,
                          'currency' => 'CAD',
                          'currency_value' => $rt[value],
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
		if (intval($prod_vendor['vendors_consignment'])==2) $final_prod_vendor = (($sel_prod[products_price]/$rt[value])/100)*$prod_vendor['vendors_consignment_percentage'];
		if (intval($prod_vendor['vendors_consignment'])==3) $final_prod_vendor = (($products_price[$i]/$rt[value])/100)*$prod_vendor['vendors_consignment_percentage'];				
	break;

	case "3":
		if (intval($prod_vendor['vendors_royalty'])==1) $final_prod_vendor = $prod_vendor['vendors_item_cost'];				
		if (intval($prod_vendor['vendors_royalty'])==2) $final_prod_vendor = (($sel_prod[products_price]/$rt[value])/100)*$prod_vendor['vendors_royalty_percentage'];
		if (intval($prod_vendor['vendors_royalty'])==3) $final_prod_vendor = (($products_price[$i]/$rt[value])/100)*$prod_vendor['vendors_royalty_percentage'];
	break;
	default: 
		$prod_vendor['vendors_product_payment_type'] = '1';
	break;
}


//    $final_prod_vendor = $final_prod_vendor*$products_qty[$i];

    $total_price = $total_price+($products_price[$i]*$products_qty[$i]);
if (trim($sel_prod[series_name])!="") $series = "<b>".$sel_prod[series_name]."</b> - ";
    $products_body .= "<tr><td width='150'>".$products_qty[$i]."</td><td width='450'>".$series.$sel_prod[products_name_prefix]." ".$sel_prod[products_name]." ".$sel_prod[products_name_suffix]."(".$sel_prod[products_model].") = &pound;".($products_price[$i]*$products_qty[$i])."(CAD)</td></tr>";
    tep_db_query("insert into orders_products set products_item_cost='".$final_prod_vendor."', products_sale_type='".$prod_vendor[vendors_product_payment_type]."', orders_id='".$insert_id."', products_id='".$sel_prod[products_id]."', products_model='".mysql_escape_string(strtoupper($products_sku[$i]))."', products_name='".mysql_escape_string($series.$sel_prod[products_name_prefix]." ".$sel_prod[products_name]." ".$sel_prod[products_name_suffix])."', products_price='".($sel_prod[products_price]/$rt[value])."' , final_price='".($products_price[$i]/$rt[value])."', products_quantity='".$products_qty[$i]."', products_prepared=0, ordered=0");
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
			$bottom_email_text .= "<b>Shipping speed: ".$order_type."</b> &pound;".$one."(CAD)<br>";
			tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>".$order_type."</b>', text='".$currencies->format($shipping_credits,true,'CAD', '1.000000')."', value='".($one/$rt[value])."', class='ot_shipping', sort_order=1");
		}
		if ($i==1) {
			$bottom_email_text .= "<b>Sub-Total:</b> $".$two."(CAD)<br>";
			tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>Sub-Total:</b>', text='".$currencies->format($total_price, true,'CAD','1.000000')."', value='".($two/$rt[value])."', class='ot_subtotal', sort_order=2");
		}
		if ($i==2) {
			$bottom_email_text .= "<b>Total:</b> $".$three."(CAD)<br>";
			tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>Total:</b>', text='<b>".$currencies->format($tot, true, 'CAD','1.00000')."</b>', value='".($three/$rt[value])."', class='ot_total', sort_order=3");
		}
	}

echo "<h2>ORDER #: ".$insert_id."</h2>";

tep_db_query("delete from parse_tmp where order_id='".$po_number."'");

$delivery_block  ="<br><b>Delivery Address:</b><br>";
if (trim($customer_ship['fio'])!='') $delivery_block .=$customer_ship['fio']."<br>";
if (trim($customer_ship['entry_company'])!='') $delivery_block .=$customer_ship['entry_company']."<br>";
if (trim($customer_ship['entry_street_address'])!='') $delivery_block .=$customer_ship['entry_street_address']."<br>";
if (trim($customer_ship[entry_suburb])!='') $delivery_block .=$customer_ship[entry_suburb]."<br>";
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
if (trim($po_number)!='') $email_body .= "AMAZON CA MARKETPLACE ORDER NUMBER: ".$po_number."<br>";
$email_body .= "Detailed Invoice: <a href='https://www.travelvideostore.com/account_history_info.php?order_id=".$insert_id."'>https://www.travelvideostore.com/account_history_info.php?order_id=".$insert_id."</a><br>";
$email_body .= "Date Ordered: ".$date_purchased."<br>".$delivery_block."<br>Products<br><hr>";
$email_body .= $products_body."<br><hr>";
$email_body .= $bottom_email_text;



$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers .= "From: Customer Service <customerservice@travelvideostore.com>\r\n";
$headers .= "To: ".$customer['customers_firstname']." ".$customer['customers_lastname']." <".$customer[customers_email_address].">\r\n";
//mail($customer[customers_email_address], 'TRAVELVIDEOSTORE.COM Amazon CA Marketplace Order Confirmation', $email_body, $headers);
mail('amazoncasales@travelvideostore.com', 'TRAVELVIDEOSTORE.COM Amazon CA Marketplace Order Confirmation', $email_body, $headers);
//mail('x0661t@d-net.kiev.ua', 'TRAVELVIDEOSTORE.COM Amazon CA Marketplace Order Confirmation', $email_body, $headers);

?>
<li>Click <a target="_new" style="font-size:12px;font-weight:bold; color:blue" href='customers.php?selected_box=customers&language=english&cID=<?=$customer[customers_id]?>&action=edit'>here</a> to see user information<br/><li>Click <a target="_new" style="font-size:12px;font-weight:bold; color:blue" href='orders.php?language=english&oID=<?=$insert_id?>&action=edit'>here</a> to see order details<br/><br/>
<?
$next_po = tep_db_fetch_array(tep_db_query("select order_id from parse_tmp where currency='CA' limit 1"));
if ($next_po[order_id]!='') {
?>
<input type="button" value="Proceed Next Order >>" onclick="window.location.href='amazonCA_parse.php?action=next&po=<?=$next_po[order_id]?>'" />
	<?
} 
else  echo "<b>All orders have been parsed...</b>&nbsp;&nbsp;<input type='button' value='Start New Parsing >>' onclick='window.location.href=\"amazonCA_parse.php\"'/>";
}

function showInterface($po){

	$sql_query = "select * from parse_tmp where order_id='".$po."' limit 1";
	$new_customer = tep_db_fetch_array(tep_db_query($sql_query));	
?>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
<form action="amazonCA_parse.php" method="post">
	<input type="hidden" name="action" value="final_save" />
<?
	$count_users = tep_db_query("SELECT  * FROM  `customers` WHERE customers_email_address = '".$new_customer[buyer_email]."' limit 1");
	$count = tep_db_num_rows($count_users);
	$cu = tep_db_fetch_array($count_users);

	$part = explode(" ", $new_customer[buyer_name]);
	if (count($part)>2){
		$fname = $part[0]." ".$part[1];
		for ($i=2;$i<count($part);$i++) $lname .= $part[$i]." ";
	}
	else{
		$fname = $part[0];
		$lname = $part[1];
	}
?>
<tr>
	<td align="right" valign="top"><b>Customer Info:</b></td>
	<td>
	<table>	
	<input type="hidden" name="exists" value="<?=$count?>" />
	<tr><td>Email:</td><td><input type="text" name="customer[customer_email]" readonly value="<?=$new_customer[buyer_email]?>" /></td></tr>
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
		<tr><td>Name: </td><td><input type="text" name="billing_name" value="Amazon CA Marketplace Orders" /></td></tr>
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
		<input type="hidden" name="shipping_short_state" value="<?=$new_customer[shipping_state]?>" />
		<tr><td>Name: </td><td><input type="text" name="shipping_name" value="<?=$new_customer[recipient_name]?>" /></td></tr>
		<tr><td>Company: </td><td><input  name="shipping_company" type="text" value="<?=$new_customer[company]?>" /></td></tr>
		<tr><td>Street 1: </td><td><input name="shipping_street1" type="text" value="<?=$new_customer[shipping_address_1]?>" /></td></tr>
		<tr><td>Street 2: </td><td><input name="shipping_street2" type="text" value="<?=$new_customer[shipping_address_2]?>" /></td></tr>
		<tr><td>City: </td><td><input  name="shipping_city" type="text" value="<?=$new_customer[shipping_city]?>" /></td></tr>
<?
if ((strtoupper($new_customer[shipping_country])=='USA') || (strtoupper($new_customer[shipping_country])=='United States') || (strtoupper($new_customer[shipping_country])=='US')){
	if (strlen(trim($new_customer[shipping_state]))==2)
	$state = tep_db_fetch_array(tep_db_query("select name from states where abbrev='".str_replace('.','',$new_customer[shipping_state])."' limit 1"));
	else
	$state[name] = ucfirst($new_customer[shipping_state]);

	$user[country] = 'United States';
	}
	else {
$s_country = tep_db_fetch_array(tep_db_query("select countries_name from countries where countries_name='".$new_customer[shipping_country]."' or countries_iso_code_2='".$new_customer[shipping_country]."' or countries_iso_code_3='".$new_customer[shipping_country]."' limit 1"));
	$user[country] = $s_country[countries_name];
	$state[name] = ucfirst($new_customer[shipping_state]);
//	$new_customer[shipping_method] = 'International Standard Shipping';
	}
?>
		<tr><td>State: </td><td><input name="shipping_state" type="text" value="<?=$state[name]?>" /></td></tr>
		<tr><td>ZIP: </td><td><input name="shipping_postcode" type="text" value="<?=$new_customer[shipping_zip]?>" /></td></tr>
		<tr><td>Country: </td><td><input  name="shipping_country" type="text" value="<?=$user[country]?>" /></td></tr>
	</table>
	</td>
</tr>
<tr>
	<td align="right" valign="top"><b>Products found:</b></td>
	<td colspan="2">
	<table>
	<?
	$sql_query = "select * from parse_tmp where order_id='".$po."'";
	$products = tep_db_query($sql_query);

	$i = 0;
	$total_count = 0;
	$shipping_fee = 0;

$rt = tep_db_fetch_array(tep_db_query("select value from currencies where code='CAD' limit 1"));
while($row = tep_db_fetch_array($products)){
$shipping_fee = $shipping_fee+($row[shipping_fee]*$row[quantity]);

		$row['sku'] = ('^SPECIAL[0-9]{0,}-', '', $row['sku']);
		$sql = tep_db_query("SELECT  * FROM  `products` WHERE products_model = '".$row[sku]."' limit 1");
		$prod = tep_db_fetch_array($sql);
		$count = tep_db_num_rows($sql);
		$total_count = $total_count + $new_customer['quantity'];
	?>
	 <tr>
		<td><b>SKU:</b><br/><input id="products_sku_<?=$i?>" type="text" style="width:120px;" name="products_sku[]" value="<?=$row['sku']?>"/></td><td>
		<td><b>Q-ty:</b><br/><input id="products_qty_<?=$i?>" type="text" style="width:40px;" name="products_qty[]" value="<?=$row['quantity']?>"/></td>
		<td><b>Price:</b><br/><input id="products_price_<?=$i?>" type="text" style="width:80px;" name="products_price[]" value="<?=$row['price']?>"/></td>
		<td><b>DB Price:</b><br/><input  id="products_db_price_<?=$i?>" type="text" style="width:80px;" value="<?=round($prod['products_price']*$rt[value], 2)?>" disabled /></td>
		<td><b>Qty on stock:</b><br/><input  id="products_qty_stock_<?=$i?>" type="text" style="width:80px;" value="<?=$prod['products_quantity']?>" disabled /></td>
		<td><b>Shipping Fee (per item):</b><br/><input type="text" style="width:80px;" value="<?=$row['shipping_fee']?>" disabled /></td>
		<td>
		<? if (($count==0)) echo "<font style='font-weight:Italic;color:red'>Not found in product DB! Check SKU!</font>"; ?>
		<? if (($prod['products_quantity']<0) || ($prod['products_quantity']==0)) echo "<font style='font-weight:Italic;color:red'>Item quantity equal to zero or less!</font>"; ?>
		</td>
		<? if ($count==0 || $prod['products_quantity']<0 || $prod['products_quantity']==0){ ?>
			<td><input type='button' onClick='winOpen("<?=$i?>", "<?=$row[sku]?>")' value='Find Product'/></td>
		<? } ?>
			
	</tr>	
	<?	
	$i++;
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
	<tr><td align="right"><b>Order Details:</b></td><td>PO number: <input type="text" name="po_number" value="<?=$po?>" /></td></tr>
	<tr><td></td><td>Order Type: <input type="text" name="order_type" value="<?=$new_customer[shipping_method]?>" /></td></tr>
	<tr><td></td><td>Shipping Credits: <input  name="shipping_credits" type="text" value="<?=$shipping_fee?>" /></td></tr>

	<tr><td align="right"><b>Comments:</b></td><td><textarea name="comments" style="width:400px; height:200px;"><?if (trim($new_customer[comments])!="") echo $new_customer[comments];
else {
$comments_string ="Here Are The Details Of Your Completed Amazon Marketplace Sale:\n\n";
$comments_string.="Order #: ".$po."\n";
$comments_string.="Listings: ".$i."\n";
$comments_string.="Total items Count: ".$total_count."\n\n";
$tot_products = tep_db_query("select * from parse_tmp where order_id='".$po."'");
$t = 0;

while($rr = tep_db_fetch_array($tot_products)){
$t++;
$comments_string .="Listing ".$t.": ".$rr[item_name]."\n";
$comments_string .="Listing Id: ".strtolower($rr[listing_id])."\n";
$comments_string .="Sku: ".$rr[sku]."\n";
$comments_string .="Quantity: ".$rr[quantity]."\n";
$comments_string .="Buyer's price: &pound;".$rr[total_price]."(CAD)\n\n";
}

$comments_string .="\n\n\n";
$comments_string .="Buyer's Email: ".$new_customer[buyer_email]."\n";
$comments_string .="Time of Sale: ".$new_customer[purchase_date]."\n";
$comments_string .="CA Shipping Speed: ".$new_customer[shipping_method]."\n";
$comments_string .="\n\n";
$comments_string .="(please Note Media Mail Should Only Be Used For Standard Shipping On Books, Music, Videos, Dvds, Computer Games, Video Games, And Computer Software.)\n\n";
$comments_string .="https://www.amazon.com/gp/seller-account/orders/orderDetails.html/105-0905880-7579640?ie=UTF8&ordersLinkEncoded=&orderID=".$po."";
echo $comments_string;
}
?>
</textarea></td></tr>
<?
	$filename = 'includes/languages/english/vendorsCA_email_body.dat';
	$fp = fopen($filename,'r');
	$text_block = @fread($fp, filesize ($filename));
	fclose($fp);			

?>
	<tr><td align="right"><b>Email Sending<br/>Text Block:</b></td><td><textarea name="email_text_block" style="width:400px; height:200px;"><?=$text_block?></textarea></td></tr>
<tr><td style="padding-top:10px;" colspan="3" align="center"><input type="submit" value="Save Order" /></td></tr>
</form>
</table>

<?
	
}


if ($_GET[action]=="next"){
	showInterface($_GET[po]);		
}

if ($_POST[action]=="parse"){
		if (is_uploaded_file($_FILES['amazon_parser']['tmp_name']))
		{
			$name = $_FILES['amazon_parser']['name'];
			move_uploaded_file($_FILES['amazon_parser']['tmp_name'], 'tmp/'.$name);
		}

	$filename = 'tmp/'.$name;
	$fp = fopen($filename,"r");
	$contents = fread ($fp, filesize ($filename));
	fclose($fp);
	unlink($filename);	

	$lines = explode("\n", $contents);
$i=0;
$t = 0;
foreach($lines as $line){
	if ($i!=0){
		$part = explode("\t", $line);
		$count = tep_db_num_rows(tep_db_query("select orders_id from orders where purchase_order_number='".$part[1]."'"));
		if ($count==0){
		$t++;
		if ($t==1) $first_po = $part[1];
		$sql_query = "insert into parse_tmp set currency='CA', order_id='".$part[1]."', payment_date='".$part[3]."', item_name='".mysql_escape_string($part[5])."', sku='".$part[7]."', price='".$part[8]."', shipping_fee='".$part[9]."', quantity='".$part[10]."', total_price='".$part[11]."', purchase_date='".$part[12]."', buyer_email='".mysql_escape_string($part[14])."', buyer_name='".mysql_escape_string($part[15])."', recipient_name='".mysql_escape_string($part[16])."', shipping_address_1='".mysql_escape_string($part[17])."', shipping_address_2='".mysql_escape_string($part[18])."', shipping_city='".mysql_escape_string($part[19])."', shipping_state='".mysql_escape_string($part[20])."', shipping_zip='".$part[21]."', shipping_country='".mysql_escape_string($part[22])."', comments='".mysql_escape_string($part[23])."', listing_id='".mysql_escape_string($part[6])."', shipping_method='".$part[25]."'";
		//echo $sql_query."<hr>";
		tep_db_query($sql_query);
			}
			else{
			tep_db_query("delete from parse_tmp where order_id='".$part[1]."'");
			}
		}
	$i++;
	}

$cc = tep_db_num_rows(tep_db_query("select order_id from parse_tmp where currency='CA'"));
if ($cc!=0)
	showInterface($first_po);
else
	echo "<script>window.location.href='amazonCA_parse.php?msg=All orders already in database!'</script>";
}

if ($_POST[action]=='' and $_GET[action]==''){
?>
<table>
<tr><td><?if (trim($msg)!='') echo "<font color='red'><b>".$msg."</b></font>";?></td></tr>
<form action="amazonCA_parse.php" method="post" enctype="multipart/form-data" name="parse">
	<input type="hidden" name="action" value="parse" />
	<tr><td align="left"><br/><b>Load File:</b> <input type="file" name="amazon_parser" onchange="if (this.value.lastIndexOf('.txt')==-1) { alert('Load tab-delimited files with TXT extention only!'); document.getElementById('parse_btn').disabled = true;} else document.getElementById('parse_btn').disabled = false;" /> <input type="submit" id='parse_btn' value="&nbsp;Parse&nbsp;" /></td></tr>
</form>
<?
	$tmp = tep_db_query("select * from parse_tmp where currency='CA'");
	$count = tep_db_num_rows($tmp);
if ($count!=0){
	while ($row = tep_db_fetch_array($tmp)){
		$cnt = tep_db_num_rows(tep_db_query("select orders_id from orders where purchase_order_number='".$row[order_id]."'"));
		if ($cnt!=0) tep_db_query("delete from parse_tmp where order_id='".$row[order_id]."'");
	}

	$new_tmp = tep_db_query("select order_id from parse_tmp where currency='CA'");
	$cnt = tep_db_num_rows($new_tmp);

	if ($cnt!=0){
	$row = tep_db_fetch_array($new_tmp);
	echo "<tr><td>Not all orders have been parsed, press <input type='button' value='Proceed parsing >>' onclick='window.location.href=\"amazonCA_parse.php?action=next&po=".$row[order_id]."\"'> to continue...</td></tr>";
	}
}
?>
</table>
<?
}
?>
</td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>