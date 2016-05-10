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
            <td class="pageHeading">CUSTOMFLIX ORDERS</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top">
<? 
 if($_GET[action]!='save'){
?>
<form action="customflix.php?action=parse" method="post">
<b>Input product list:</b><br/>
<textarea name="ng_list" style="width:800px;height: 400px;"><?=stripslashes($_POST['ng_list'])?></textarea><br/>
<input type="submit" value="Parse" />
</form>
<?
}

$i=0;
if ($_GET['action']=="parse"){
	$lines = explode("\n", trim($_POST['ng_list']));
	$p=0;
	foreach($lines as $line){
		if (trim($line)!=""){
		if ($i==0) {
			$part = explode("(title", $line);
			$customerflix_id = intval($part[1]);
			$model = tep_db_fetch_array(tep_db_query("select products_model from products where customerflix_id='".$customerflix_id."' limit 1"));
			$cur_model = $model[products_model];
			$products[$p] = array();
			$products[$p]['products_model'] = $cur_model;
			$products[$p]['customerflix_id'] = $customerflix_id;
			}
			
		if ($i==1) {
			$part = explode("units", $line);
			$products[$p]['qty'] = intval($part[0]);
			
			$parts = explode("$", $part[1]);
			$products[$p]['price'] = floatval($parts[1]);
			$t = $t + floatval($parts[1]);

//		var_dump($products[$p]);
//		echo "<hr>";
$p++;
			}			
		$i++;
		if ($i==2) $i=0;
		}
	}	

//print_r($products);
//echo "---".$t."---";
$customer = tep_db_fetch_array(tep_db_query("select customers_email_address, customers_firstname, customers_lastname, customers_default_address_id  from customers where customers_id=37578"));
$address = tep_db_fetch_array(tep_db_query("select a.*, b.countries_name as entry_country from address_book a LEFT JOIN countries b on (a.entry_country_id=b.countries_id) where a.address_book_id=".$customer['customers_default_address_id']));
?>
<br/>
<form action="customflix.php?action=save" method="post">
<table width="100%">
<tr>
	<td align="right" valign="top"><b>Customer Info:</b></td>
	<td>
	<table>	
	<tr><td>Email:</td><td><input type="text" name="customer[customers_email_address]" readonly value="<?=$customer['customers_email_address']?>" /></td></tr>
	<tr><td>First Name:</td><td><input  name="customer[customer_firstname]" type="text" value="<?=$customer['customers_firstname']?>" /></td></tr>
	<tr><td>Last Name:</td><td><input name="customer[customer_lastname]" type="text" value="<?=$customer['customers_lastname']?>" /></td></tr>
	</table>
	</td>
</tr>
<tr>
	<td align="right" width="150" valign="top"><b>Billing Info:</b></td>
	<td width="50%">
	<table>
		<tr><td colspan="2">BILL TO:</td></tr>
		<tr><td>Name: </td><td><input type="text" name="billing_name" value="<?=$customer[customers_firstname]." ".$customer[customers_lastname]?>" /></td></tr>
		<tr><td>Company: </td><td><input name="billing_company" type="text" value="<?=$address[entry_company]?>" /></td></tr>
		<tr><td>Street: </td><td><input name="billing_address1" type="text" value="<?=$address[entry_street_address]?>" /></td></tr>
		<tr><td>City: </td><td><input name="billing_city" type="text" value="<?=$address[entry_city]?>" /></td></tr>
		<tr><td>State: </td><td><input name="billing_state" type="text" value="<?=$address[entry_state]?>" /></td></tr>
		<tr><td>ZIP: </td><td><input  name="billing_postcode" type="text" value="<?=$address[entry_postcode]?>" /></td></tr>
		<tr><td>Country: </td><td><input name="billing_country" type="text" value="<?=$address[entry_country]?>" /></td></tr>
	</table>
	</td><td></td>
		<input type="hidden" name="shipping_name" value="" />
		<input  name="shipping_company" type="hidden" value="" />
		<input name="shipping_street1" type="hidden" value="" />
		<input  name="shipping_city" type="hidden" value="" />
		<input name="shipping_state" type="hidden" value="" />
		<input name="shipping_postcode" type="hidden" value="" />
		<input  name="shipping_country" type="hidden" value="" />
</tr>
<tr>
	<td align="right" valign="top"><b>Products found:</b></td>
	<td colspan="2">
	<table>
	<?
function cmp ($a, $b) {
    return strcmp($a["products_model"], $b["products_model"]);
}
$inc = 0;
usort($products, "cmp");
if (count($products)>0){
for ($i=0;$i<count($products);$i++){
	 //if (trim($products[$i][products_model] == ""))  $inc = $inc+1;
		$sql = tep_db_query("SELECT  * FROM  `products` WHERE products_model = '".$products[$i][products_model]."' limit 1");
		$prod = tep_db_fetch_array($sql);
		$count = tep_db_num_rows($sql);
$total_price = $total_price + floatval($products[$i]['price']);
	?>
	 <tr>
		<td><b>SKU:</b><br/><input id="products_sku_<?=$i?>" type="text" style="width:120px;" name="products_sku[]" value="<?=$prod['products_model']?>"/></td><td>
		<td><b>Customflix ID:</b><br/><input type="text" style="width:100px;" name="customflix_ids[]" value="<?=$products[$i]['customerflix_id']?>"/></td><td>
		<td><b>Q-ty:</b><br/><input id="products_qty_<?=$i?>" type="text" style="width:40px;" name="products_qty[]" value="<?=$products[$i]['qty']?>"/></td>
		<td><b>Total Price:</b><br/><input id="products_price_<?=$i?>" type="text" style="width:80px;" name="products_price[]" value="<?=floatval($products[$i]['price'])?>"/></td>
		<td><b>DB Price:</b><br/><input  id="products_db_price_<?=$i?>" type="text" style="width:80px;" value="<?=$prod['products_price']?>" disabled /></td>
		<td><b>Qty on stock:</b><br/><input  id="products_qty_stock_<?=$i?>" type="text" style="width:80px;" value="<?=$prod['products_quantity']?>" disabled /></td>
		<td>
		<? if (($count==0)) echo "<font style='font-weight:Italic;color:red'>Not found in product DB! Check customerflix_id!</font>"; ?>
		<? if (($prod['products_quantity']<0) || ($prod['products_quantity']==0)) echo "<font style='font-weight:Italic;color:red'>Item quantity equal to zero or less!</font>"; ?>
		</td>
		<? if ($count==0 || $prod['products_quantity']<0 || $prod['products_quantity']==0){ ?>
			<td><input type='button' onClick='winOpen("<?=$i?>", "<?=$prod[products_model]?>")' value='Find Product'/></td>
		<? } ?>
			
	</tr>	
	<?	
		//}
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
<? if ($i==0) $sts = 'disabled';?>
<tr><td colspan="3" align="center"><input type="submit" value="Save Order" <?=$sts?> /></td></tr>
</form>
</table>	
<?
}

if ($_GET[action]==save){

	$sql_query = "SELECT a .  * , b .  * , c.countries_name FROM customers a LEFT  JOIN address_book b ON ( a.customers_default_address_id = b.address_book_id ) LEFT  JOIN countries c ON ( b.entry_country_id = c.countries_id ) WHERE a.customers_id =  '37578' LIMIT 1 ";
	$customer = tep_db_fetch_array(tep_db_query($sql_query));


	$customer_ship[entry_company]  ='';
	$customer_ship[fio]='';
	$customer_ship[entry_street_address]='';
	$customer_ship[entry_postcode]='';
	$customer_ship[entry_city]='';
	$customer_ship[entry_state]='';
	$customer_ship[countries_name]='';


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
                          'payment_method' => 'Customflix',
                          'cc_type' => '',
                          'cc_owner' => '',
                          'cc_number' => '',
                          'cc_expires' => '',
                          'date_purchased' => $date_purchased,
                          'orders_status' => '20',
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

for ($i=0;$i<$length; $i++){

  $sel_prod = tep_db_fetch_array(tep_db_query("select a.products_price, b.products_id, b.products_name_prefix, b.products_name, b.products_name_suffix, a.products_model, c.series_name from products a left join products_description b on (a.products_id = b.products_id) LEFT JOIN series c ON (a.series_id = c.series_id) where a.products_model='".$products_sku[$i]."' limit 1"));

    $sql_query = "SELECT vendors_item_cost, vendors_product_payment_type, vendors_consignment, vendors_consignment_percentage, vendors_royalty , vendors_royalty_percentage FROM products_to_vendors where products_id=".$sel_prod[products_id]." limit 0,1";
    $prod_vendor = tep_db_fetch_array(tep_db_query($sql_query));

	switch($prod_vendor['vendors_product_payment_type']){
	case "1":
		$final_prod_vendor = (round(($products_price[$i]/$products_qty[$i]),2)/100)*25;
	break;

	case "2":
		$final_prod_vendor = (round(($products_price[$i]/$products_qty[$i]),2)/100)*25;
	break;

	case "3":
	if (intval($prod_vendor['vendors_royalty_percentage'])==0) $prod_vendor['vendors_royalty_percentage'] = 25;

		$final_prod_vendor = (round(($products_price[$i]/$products_qty[$i]),2)/100)*$prod_vendor['vendors_royalty_percentage'];

	break;
	default: 
		$final_prod_vendor = (round(($products_price[$i]/$products_qty[$i]),2)/100)*25;
	break;
}

    $total_price = $total_price + $products_price[$i];
    $currdate = date("Y-m-d");
    tep_db_query("insert into orders_products set products_item_cost='".$final_prod_vendor."', products_sale_type='3', orders_id='".$insert_id."', products_id='".$sel_prod[products_id]."', products_model='".mysql_escape_string(strtoupper($products_sku[$i]))."', products_name='".mysql_escape_string($series.$sel_prod[products_name_prefix]." ".$sel_prod[products_name]." ".$sel_prod[products_name_suffix])."', products_price='".round(($sel_prod[products_price]/$products_qty[$i]), 2)."' , final_price='".round(($products_price[$i]/$products_qty[$i]), 2)."', products_quantity='".$products_qty[$i]."', products_prepared=0, ordered=0, date_shipped='".$currdate."', date_shipped_checkbox=1");
    tep_db_query("update products set products_ordered = products_ordered + " . $products_qty[$i] . "  where products_id = '" . $sel_prod[products_id] . "'");
	}


    tep_db_query("insert into orders_status_history set orders_id='".$insert_id."', orders_status_id=1, date_added='".$date_purchased."', customer_notified=1, comments='".mysql_escape_string($comments)."'");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>DOD</b>', text='$0.00', value='0.00', class='ot_shipping', sort_order=1");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='Sub-Total:', text='<b>".$currencies->format($total_price, true, 'USD','1.00000')."</b>', value='".$total_price."', class='ot_subtotal', sort_order=2");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>Total:</b>', text='<b>".$currencies->format($total_price, true, 'USD','1.00000')."</b>', value='".$total_price."', class='ot_total', sort_order=3");


   // echo "<script>window.location.href='customflix.php?msg=All orders have been inserted!'</script>";
echo "<h2>ORDER #: ".$insert_id."</h2>";
?>
<li>Click <a target="_new" style="font-size:12px;font-weight:bold; color:blue" href='customers.php?selected_box=customers&language=english&cID=<?=$customer[customers_id]?>&action=edit'>here</a> to see user information<br/><li>Click <a target="_new" style="font-size:12px;font-weight:bold; color:blue" href='orders.php?language=english&oID=<?=$insert_id?>&action=edit'>here</a> to see order details<br/><br/><center><input type='button' value='Proceed New List >>' onclick="window.location.href='customflix.php'" /></center>
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