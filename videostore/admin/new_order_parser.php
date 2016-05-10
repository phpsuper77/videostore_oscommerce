<?
  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  require(DIR_WS_MODULES . 'excel/reader.php');
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
<?php
if (strtolower($_GET['action']) == 'parse') {

	if (!isset($_POST['type'])) die("Error Order Type Identification!\n");
	$target_path = "tmp/";
	$basename = basename( $_FILES['uploadedfile']['name']);
	$target_path = $target_path . $basename; 

	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path) && trim($_FILES['uploadedfile']['tmp_name']) != '') {
	    echo "The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded";
	} else{
	    die("There was an error uploading the file, please try again!");
	}

	switch($_POST['type']) {
		case '1':
		//csv
			parseCustomflix($target_path);		
		break;

		case '2':
		//csv
			parseOverdrive($target_path);
		break;

		case '3':
		//xls
			parseGrapeflix($target_path);
		break;

		case '4':
		//xls
			parseAmazonDe($target_path);
		break;

		case '5':
		//xls
			parseAmazonPoUk($target_path);
		break;

		case '6':
		//csv
			parseYouTube($target_path);		

		case '7':
		//xls
			parseAmazonUK($target_path);
		break;		break;

	}
	echo "<script>location.href='new_order_parser.php?msg=Successfully Parsed!'</script>";
}

function parseCustomflix($path) {
	global $currencies;
	$handle = fopen ($path,"r");
	$products = array();
	while ($data = fgetcsv ($handle, 1000, ",")) {
	    $num = count ($data);
	    //print "<p> $num полей в строке $row: <br>\n";
	    if (++$row == 1) continue;
	    for ($c=0; $c < $num; $c++) {
		switch($c){
	        	case '1': 
				$title_id = intval(trim($data[$c])); 
				if (!array_key_exists($title_id, $products)) {
					$products[$title_id] = array(); 
					$products[$title_id]['qty'] = 0;
					$products[$title_id]['price'] = 0;
					$products[$title_id]['royalty'] = 0;
				}
			break;
	        	case '3': 
				$qty = intval(trim($data[$c])); 
				$products[$title_id]['qty'] = $products[$title_id]['qty']+$qty;
			break;
	        	case '6': 
				$royalty = trim(str_replace('$', '', $data[$c])); 
				$products[$title_id]['price'] = $royalty;
				$products[$title_id]['royalty'] = $products[$title_id]['royalty'] + ($qty*$royalty);
			break;			
		}
	    }
	}
	$error = false;	
	foreach($products as $key=>$value) {
		$model = tep_db_fetch_array(tep_db_query("select products_model from products where customerflix_id LIKE '%".$key."%' limit 1"));
		if ($model) $products[$key]['products_model'] = $model['products_model'];
		else {		
			$error = true;
			echo "Products with Title ID: " . $key . " does not exists in DB<br />";
		}
	}
	if ($error) die();

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
			  'billing_name' => $customer[customers_firstname]." ".$customer[customers_lastname], 
			  'billing_company' => $customer[entry_company], 
	                  'billing_street_address' => $customer['entry_street_address'], 
	                  'billing_suburb' => '', 
	                  'billing_city' => $customer[entry_city], 
	                  'billing_postcode' => $customer[entry_postcode], 
	                  'billing_state' => $customer[entry_state], 
	                  'billing_country' => $customer[countries_name],
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



$total_price = 0;
foreach($products as $key=>$value){

  $sel_prod = tep_db_fetch_array(tep_db_query("select a.products_price, b.products_id, b.products_name_prefix, b.products_name, b.products_name_suffix, a.products_model, c.series_name from products a left join products_description b on (a.products_id = b.products_id) LEFT JOIN series c ON (a.series_id = c.series_id) where a.products_model='".$value[products_model]."' limit 1"));

    $sql_query = "SELECT vendors_item_cost, vendors_product_payment_type, vendors_consignment, vendors_consignment_percentage, vendors_royalty , vendors_royalty_percentage FROM products_to_vendors where products_id=".$sel_prod[products_id]." limit 0,1";
    $prod_vendor = tep_db_fetch_array(tep_db_query($sql_query));

	switch($prod_vendor['vendors_product_payment_type']){
	case "1":
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;

	case "2":
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;

	case "3":
	if (intval($prod_vendor['vendors_royalty_percentage'])==0) $prod_vendor['vendors_royalty_percentage'] = 25;

		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*$prod_vendor['vendors_royalty_percentage'];

	break;
	default: 
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;
    }

    $total_price = $total_price + $value['royalty'];
    $currdate = date("Y-m-d");

    tep_db_query("insert into orders_products set products_item_cost='".$final_prod_vendor."', products_sale_type='3', orders_id='".$insert_id."', products_id='".$sel_prod[products_id]."', products_model='".mysql_escape_string(strtoupper($value['products_model']))."', products_name='".mysql_escape_string($series.$sel_prod[products_name_prefix]." ".$sel_prod[products_name]." ".$sel_prod[products_name_suffix])."', products_price='".round(($value['royalty']/$value['qty']), 2)."' , final_price='".round(($value['royalty']/$value['qty']), 2)."', products_quantity='".$value['qty']."', products_prepared=0, ordered=0, date_shipped='".$currdate."', date_shipped_checkbox=1");

    tep_db_query("update products set products_ordered = products_ordered + " . $value['qty'] . "  where products_id = '" . $sel_prod[products_id] . "'");
	}

    tep_db_query("insert into orders_status_history set orders_id='".$insert_id."', orders_status_id=1, date_added='".$date_purchased."', customer_notified=1, comments='".mysql_escape_string($comments)."'");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>DOD</b>', text='$0.00', value='0.00', class='ot_shipping', sort_order=1");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='Sub-Total:', text='<b>".$currencies->format($total_price, true, 'USD','1.00000')."</b>', value='".$total_price."', class='ot_subtotal', sort_order=2");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>Total:</b>', text='<b>".$currencies->format($total_price, true, 'USD','1.00000')."</b>', value='".$total_price."', class='ot_total', sort_order=3");

    fclose ($handle);	
    @unlink(@$path);

    echo "<script>location.href='new_order_parser.php?msg=Successfully Parsed Order: ".$insert_id." created!'</script>";
    exit;
}

//ADDED 1-3-11 for YouTube Parsing
function parseYouTube($path) {
	global $currencies;
	$handle = fopen ($path,"r");
	$products = array();
	while ($data = fgetcsv ($handle, 1000, ",")) {
	    $num = count ($data);
	    //print "<p> $num полей в строке $row: <br>\n";
	    if (++$row == 1) continue;
	    for ($c=0; $c < $num; $c++) {
		switch($c){
	        	case '1': 
				$title_id = (strval($data[$c])); 
				if (!array_key_exists($title_id, $products)) {
					$products[$title_id] = array(); 
					$products[$title_id]['qty'] = 0;
					$products[$title_id]['price'] = 0;
					$products[$title_id]['royalty'] = 0;
				}
			break;
	        	case '3': 
				$qty = intval(trim($data[$c])); 
				$products[$title_id]['qty'] = $products[$title_id]['qty']+$qty;
			break;
	        	case '6': 
				$royalty = trim(str_replace('$', '', $data[$c])); 
				$products[$title_id]['price'] = $royalty;
				$products[$title_id]['royalty'] = $products[$title_id]['royalty'] + ($qty*$royalty);
			break;			
		}
	    }
	}
	$error = false;	
	foreach($products as $key=>$value) {
		$model = tep_db_fetch_array(tep_db_query("select products_model from products where products_youtube_id='".$key."' limit 1"));
		if ($model) $products[$key]['products_model'] = $model['products_model'];
		else {		
			$error = true;
			echo "Products with YouTube ID: " . $key . " does not exists in DB<br />";
		}
	}
	if ($error) die();

	$sql_query = "SELECT a .  * , b .  * , c.countries_name FROM customers a LEFT  JOIN address_book b ON ( a.customers_default_address_id = b.address_book_id ) LEFT  JOIN countries c ON ( b.entry_country_id = c.countries_id ) WHERE a.customers_id =  '64776' LIMIT 1 ";
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
			  'billing_name' => $customer[customers_firstname]." ".$customer[customers_lastname], 
			  'billing_company' => $customer[entry_company], 
	                  'billing_street_address' => $customer['entry_street_address'], 
	                  'billing_suburb' => '', 
	                  'billing_city' => $customer[entry_city], 
	                  'billing_postcode' => $customer[entry_postcode], 
	                  'billing_state' => $customer[entry_state], 
	                  'billing_country' => $customer[countries_name],
	                  'billing_address_format_id' => '2',
                          'payment_method' => 'YouTube',
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



$total_price = 0;
foreach($products as $key=>$value){

  $sel_prod = tep_db_fetch_array(tep_db_query("select a.products_price, b.products_id, b.products_name_prefix, b.products_name, b.products_name_suffix, a.products_model, c.series_name from products a left join products_description b on (a.products_id = b.products_id) LEFT JOIN series c ON (a.series_id = c.series_id) where a.products_model='".$value[products_model]."' limit 1"));

    $sql_query = "SELECT vendors_item_cost, vendors_product_payment_type, vendors_consignment, vendors_consignment_percentage, vendors_royalty , vendors_royalty_percentage FROM products_to_vendors where products_id=".$sel_prod[products_id]." limit 0,1";
    $prod_vendor = tep_db_fetch_array(tep_db_query($sql_query));

	switch($prod_vendor['vendors_product_payment_type']){
	case "1":
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;

	case "2":
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;

	case "3":
	if (intval($prod_vendor['vendors_royalty_percentage'])==0) $prod_vendor['vendors_royalty_percentage'] = 25;

		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*$prod_vendor['vendors_royalty_percentage'];

	break;
	default: 
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;
    }

    $total_price = $total_price + $value['royalty'];
    $currdate = date("Y-m-d");

    tep_db_query("insert into orders_products set products_item_cost='".$final_prod_vendor."', products_sale_type='3', orders_id='".$insert_id."', products_id='".$sel_prod[products_id]."', products_model='".mysql_escape_string(strtoupper($value['products_model']))."', products_name='".mysql_escape_string($series.$sel_prod[products_name_prefix]." ".$sel_prod[products_name]." ".$sel_prod[products_name_suffix])."', products_price='".round(($value['royalty']/$value['qty']), 2)."' , final_price='".round(($value['royalty']/$value['qty']), 2)."', products_quantity='".$value['qty']."', products_prepared=0, ordered=0, date_shipped='".$currdate."', date_shipped_checkbox=1");

    tep_db_query("update products set products_ordered = products_ordered + " . $value['qty'] . "  where products_id = '" . $sel_prod[products_id] . "'");
	}

    tep_db_query("insert into orders_status_history set orders_id='".$insert_id."', orders_status_id=1, date_added='".$date_purchased."', customer_notified=1, comments='".mysql_escape_string($comments)."'");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>Rentals</b>', text='$0.00', value='0.00', class='ot_shipping', sort_order=1");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='Sub-Total:', text='<b>".$currencies->format($total_price, true, 'USD','1.00000')."</b>', value='".$total_price."', class='ot_subtotal', sort_order=2");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>Total:</b>', text='<b>".$currencies->format($total_price, true, 'USD','1.00000')."</b>', value='".$total_price."', class='ot_total', sort_order=3");

    fclose ($handle);	
    @unlink(@$path);

    echo "<script>location.href='new_order_parser.php?msg=Successfully Parsed Order: ".$insert_id." created!'</script>";
    exit;
}

//End Added 1-3-11 for YouTube parsing


function parseAmazonPoUk($path) {
	global $currencies;

	$handle = fopen ($path,"r");
	$products = array();
	while ($data = fgetcsv ($handle, 8000, ",")) {
	//var_dump($data);exit;
	    $num = count ($data);
	    //print "<p> $num полей в строке $row: <br>\n";
	    if (++$row == 1) continue;
	    for ($c=0; $c < $num; $c++) {
		switch($c){
			case '0':
				$pon = trim($data[$c]);
			break;
	        	case '1': 
				$title_id = trim($data[$c]); 
				if (!array_key_exists($title_id, $products)) {
					$products[$title_id] = array(); 
					$products[$title_id]['qty'] = 0;
					$products[$title_id]['price'] = 0;
					$products[$title_id]['royalty'] = 0;
				}
			break;
	        	case '9': 
				$qty = intval(trim($data[$c])); 
				$products[$title_id]['qty'] = $products[$title_id]['qty']+$qty;
			break;
		}
	    }
	}

	$error = false;	
	foreach($products as $key=>$value) {
		$model = tep_db_fetch_array(tep_db_query("select products_model from products where products_upc='".$key."' limit 1"));
		if ($model) $products[$key]['products_model'] = $model['products_model'];
		else {		
			$error = true;
			echo "Products with UPC: " . $key . " doesnot exists in DB<br />";
		}
	}
	if ($error) die();

	$sql_query = "SELECT a .  * , b .  * , c.countries_name FROM customers a LEFT  JOIN address_book b ON ( a.customers_default_address_id = b.address_book_id ) LEFT  JOIN countries c ON ( b.entry_country_id = c.countries_id ) WHERE a.customers_id =  '54769' LIMIT 1 ";
	$customer = tep_db_fetch_array(tep_db_query($sql_query));

	$customer_ship[entry_company] = $customer['entry_company'];
	$customer_ship[fio] = $customer['customers_firstname'] . ' ' . $customer['customers_lastname'];
	$customer_ship[entry_street_address] = $customer['entry_street_address'];
	$customer_ship[entry_postcode] = $customer['entry_postcode'];
	$customer_ship[entry_city] = $customer['entry_city'];
	$customer_ship[entry_state] = $customer['entry_state'];
	$customer_ship[countries_name] = $customer['countries_name'];


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
			  'billing_name' => $customer[customers_firstname]." ".$customer[customers_lastname], 
			  'billing_company' => $customer[entry_company], 
	                  'billing_street_address' => $customer['entry_street_address'], 
	                  'billing_suburb' => '', 
	                  'billing_city' => $customer[entry_city], 
	                  'billing_postcode' => $customer[entry_postcode], 
	                  'billing_state' => $customer[entry_state], 
	                  'billing_country' => $customer[countries_name],
	                  'billing_address_format_id' => '2',
                          'payment_method' => 'Wholesale Customer Purchase',
                          'cc_type' => '',
                          'cc_owner' => '',
                          'cc_number' => '',
                          'cc_expires' => '',
                          'date_purchased' => $date_purchased,
                          'orders_status' => '20',
                          'iswholesale' => '1',
                          'purchase_order_number' => $pon,
                          'currency' => 'USD',
                          'currency_value' => '1.00000000',
                          'customers_referer_url' => '',
                          'ipaddy' => '',
			  'ipisp' => '');

  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $org_insert_id = tep_db_insert_id();
  $insert_id = tep_db_insert_id()+2;
  $ord_upd = tep_db_query("update ".TABLE_ORDERS." set orders_id = ".$insert_id." where orders_id = ".$org_insert_id);



$total_price = 0;
foreach($products as $key=>$value){

  $sel_prod = tep_db_fetch_array(tep_db_query("select a.products_price, b.products_id, b.products_name_prefix, b.products_name, b.products_name_suffix, a.products_model, c.series_name from products a left join products_description b on (a.products_id = b.products_id) LEFT JOIN series c ON (a.series_id = c.series_id) where a.products_model='".$value[products_model]."' limit 1"));

    $sql_query = "SELECT vendors_item_cost, vendors_product_payment_type, vendors_consignment, vendors_consignment_percentage, vendors_royalty , vendors_royalty_percentage FROM products_to_vendors where products_id=".$sel_prod[products_id]." limit 0,1";
    $prod_vendor = tep_db_fetch_array(tep_db_query($sql_query));

	switch($prod_vendor['vendors_product_payment_type']){
	case "1":
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;

	case "2":
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;

	case "3":
	if (intval($prod_vendor['vendors_royalty_percentage'])==0) $prod_vendor['vendors_royalty_percentage'] = 25;

		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*$prod_vendor['vendors_royalty_percentage'];

	break;
	default: 
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;
    }

    $total_price = $total_price + $value['royalty'];
    $currdate = date("Y-m-d");

    tep_db_query("insert into orders_products set products_item_cost='".$final_prod_vendor."', products_sale_type='3', orders_id='".$insert_id."', products_id='".$sel_prod[products_id]."', products_model='".mysql_escape_string(strtoupper($value['products_model']))."', products_name='".mysql_escape_string($series.$sel_prod[products_name_prefix]." ".$sel_prod[products_name]." ".$sel_prod[products_name_suffix])."', products_price='".round(($value['royalty']/$value['qty']), 2)."' , final_price='".round(($value['royalty']/$value['qty']), 2)."', products_quantity='".$value['qty']."', products_prepared=0, ordered=0, date_shipped='', date_shipped_checkbox=0");

    tep_db_query("update products set products_ordered = products_ordered + " . $value['qty'] . "  where products_id = '" . $sel_prod[products_id] . "'");
	tep_db_query("update products set products_quantity = products_quantity - " . $value['qty'] . "  where products_id = '" . $sel_prod[products_id] . "'");
	}

    tep_db_query("insert into orders_status_history set orders_id='".$insert_id."', orders_status_id=1, date_added='".$date_purchased."', customer_notified=1, comments='".mysql_escape_string($comments)."'");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>DHL Packet Within Europe</b>', text='$0.00', value='0.00', class='ot_shipping', sort_order=1");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='Sub-Total:', text='<b>".$currencies->format($total_price, true, 'USD','1.00000')."</b>', value='".$total_price."', class='ot_subtotal', sort_order=2");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>Total:</b>', text='<b>".$currencies->format($total_price, true, 'USD','1.00000')."</b>', value='".$total_price."', class='ot_total', sort_order=3");

    fclose ($handle);	
    @unlink(@$path);

    echo "<script>location.href='new_order_parser.php?msg=Successfully Parsed Order: ".$insert_id." created!'</script>";
    exit;
}

function parseOverdrive($path) {
	global $currencies;
	$handle = fopen ($path,"r");
	$products = array();
	while ($data = fgetcsv ($handle, 1000, ",")) {
	    $num = count ($data);
	    if (++$row == 1) continue;
	    for ($c=0; $c < $num; $c++) {

		switch($c){
	        	case '3': 
				$catalog_id = trim($data[$c]); 
				if (!array_key_exists($catalog_id, $products)) {
					$products[$catalog_id] = array(); 
					$products[$catalog_id]['qty'] = 0;
					$products[$catalog_id]['price'] = 0;
					$products[$catalog_id]['royalty'] = 0;
				}
			break;
	        	case '5': 
				$qty = 1; 
				//$qty = intval($data[$c]); 
				$products[$catalog_id]['qty'] = $products[$catalog_id]['qty']+$qty;
			break;
	        	case '10': 
				$amount_owed = $data[$c]; 
				$products[$catalog_id]['price'] = $amount_owed/$qty;
				$products[$catalog_id]['royalty'] = $products[$catalog_id]['royalty'] + $amount_owed;
			break;
		}
	    }
	}

	$error = false;	
	foreach($products as $key=>$value) {
		$model = tep_db_fetch_array(tep_db_query("select products_model from products where products_id='".$key."' limit 1"));
		if ($model) $products[$key]['products_model'] = $model['products_model'];
		else {		
			$error = true;
			echo "Products with Produst ID: " . $key . " doesnot exists in DB<br />";
		}
	}
	if ($error) die();

$sql_query = "SELECT a .  * , b .  * , c.countries_name FROM customers a LEFT  JOIN address_book b ON ( a.customers_default_address_id = b.address_book_id ) LEFT  JOIN countries c ON ( b.entry_country_id = c.countries_id ) WHERE a.customers_id =  '51772' LIMIT 1 ";
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
			  'billing_name' => $customer[customers_firstname]." ".$customer[customers_lastname], 
			  'billing_company' => $customer[entry_company], 
	                  'billing_street_address' => $customer['entry_street_address'], 
	                  'billing_suburb' => '', 
	                  'billing_city' => $customer[entry_city], 
	                  'billing_postcode' => $customer[entry_postcode], 
	                  'billing_state' => $customer[entry_state], 
	                  'billing_country' => $customer[countries_name],
	                  'billing_address_format_id' => '2',
                          'payment_method' => 'Overdrive',
                          'cc_type' => '',
                          'cc_owner' => '',
                          'cc_number' => '',
                          'cc_expires' => '',
                          'date_purchased' => $date_purchased,
                          'orders_status' => '20',
                          'iswholesale' => '1',
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



$total_price = 0;
foreach($products as $key=>$value){

  $sel_prod = tep_db_fetch_array(tep_db_query("select a.products_price, b.products_id, b.products_name_prefix, b.products_name, b.products_name_suffix, a.products_model, c.series_name from products a left join products_description b on (a.products_id = b.products_id) LEFT JOIN series c ON (a.series_id = c.series_id) where a.products_model='".$value[products_model]."' limit 1"));

    $sql_query = "SELECT vendors_item_cost, vendors_product_payment_type, vendors_consignment, vendors_consignment_percentage, vendors_royalty , vendors_royalty_percentage FROM products_to_vendors where products_id=".$sel_prod[products_id]." limit 0,1";
    $prod_vendor = tep_db_fetch_array(tep_db_query($sql_query));

	switch($prod_vendor['vendors_product_payment_type']){
	case "1":
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;

	case "2":
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;

	case "3":
	if (intval($prod_vendor['vendors_royalty_percentage'])==0) $prod_vendor['vendors_royalty_percentage'] = 25;

		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*$prod_vendor['vendors_royalty_percentage'];

	break;
	default: 
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;
    }

    $total_price = $total_price + $value['royalty'];
    $currdate = date("Y-m-d");

    tep_db_query("insert into orders_products set products_item_cost='".$final_prod_vendor."', products_sale_type='3', orders_id='".$insert_id."', products_id='".$sel_prod[products_id]."', products_model='".mysql_escape_string(strtoupper($value['products_model']))."', products_name='".mysql_escape_string($series.$sel_prod[products_name_prefix]." ".$sel_prod[products_name]." ".$sel_prod[products_name_suffix])."', products_price='".round(($value['royalty']/$value['qty']), 2)."' , final_price='".round(($value['royalty']/$value['qty']), 2)."', products_quantity='".$value['qty']."', products_prepared=0, ordered=0, date_shipped='".$currdate."', date_shipped_checkbox=1");

    tep_db_query("update products set products_ordered = products_ordered + " . $value['qty'] . "  where products_id = '" . $sel_prod[products_id] . "'");
	}

    tep_db_query("insert into orders_status_history set orders_id='".$insert_id."', orders_status_id=1, date_added='".$date_purchased."', customer_notified=1, comments='".mysql_escape_string($comments)."'");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>DOD</b>', text='$0.00', value='0.00', class='ot_shipping', sort_order=1");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='Sub-Total:', text='<b>".$currencies->format($total_price, true, 'USD','1.00000')."</b>', value='".$total_price."', class='ot_subtotal', sort_order=2");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>Total:</b>', text='<b>".$currencies->format($total_price, true, 'USD','1.00000')."</b>', value='".$total_price."', class='ot_total', sort_order=3");
    fclose ($handle);	
    @unlink(@$path);

    echo "<script>location.href='new_order_parser.php?msg=Successfully Parsed Order: ".$insert_id." created!'</script>";
    exit;
}

function parseGrapeflix($path){
	global $currencies;

	$handle = fopen ($path,"r");
	$products = array();
	$addon = null;
	$prevId = null;
	while ($data = fgetcsv ($handle, 1000, ",")) {
	    $num = count ($data);
	    if (++$row == 1) continue;
	    if (trim($data[0])!= '') {
		$prevId = trim($data[0]);
		$data[6] = str_replace('$', '', $data[6]);
	    	$products[trim($data[0])] = $data;
		$addon = null;
	    }
	    else {
		$products[$prevId][9] = $products[$prevId][9] . " " . $data[9];
	    }

	}

	$error = false;	
	foreach($products as $key=>$value) {
		$model = tep_db_fetch_array(tep_db_query("select products_model from products where products_id='".$value[2]."' limit 1"));
		if ($model) $products[$key]['products_model'] = $model['products_model'];
		else {		
			$error = true;
			echo "Products with Products ID: " . $key . " does not exists in DB<br />";
		}
	}
	if ($error) die();

//var_dump($products[$prevId]);
//echo "<hr/>";
//exit;

	foreach($products as $key=>$value) {
		$pon = $key;
		$payment = 'Grapeflix';

		$customer = tep_db_fetch_array(tep_db_query("select * from customers where customers_email_address='".trim($value[14])."' limit 1"));
		if ($customer) { $customer_id = $customer['customers_id']; }
		else {		
			//creating new customer for this orders
			$sql_query = "insert into customers set purchased_without_account=0, customers_firstname='".mysql_escape_string(trim($value[7]))."', customers_lastname='".mysql_escape_string(trim($value[8]))."', customers_email_address='".mysql_escape_string(trim($value[14]))."', customers_password='".tep_encrypt_password('travel')."', customers_type=0, customers_allow_purchase_order_entry='true'";
			tep_db_query($sql_query);
			$customer_id = tep_db_insert_id();
			$country_id = tep_db_fetch_array(tep_db_query("select countries_id from countries where countries_name='".mysql_escape_string(trim($value[13]))."' or countries_iso_code_2='".mysql_escape_string(trim($value[13]))."' or countries_iso_code_3='".mysql_escape_string(trim($value[13]))."' limit 1"));

			$_state = '';
			if (trim($value[12])!='') {
				if (strtolower(trim($value[13]))=='united states' ||  strtolower(trim($value[13]))=='us' || strtolower(trim($value[13]))=='usa') {
					$state = tep_db_fetch_array(tep_db_query("select name from states where abbrev='".trim($value[12])."' or name='".trim($value[12])."' limit 1"));
					$_state = $state['abbrev'];
				}
				else {
					$_state = trim($value[12]);
				}
			}

			$zone_id = tep_db_fetch_array(tep_db_query("select zone_id from zones where zone_code='".$_state."' limit 1"));
			$sql_query = "insert into address_book set customers_id='".$customer_id."', entry_company='', entry_firstname='".mysql_escape_string(trim($value[7]))."', entry_lastname='".mysql_escape_string(trim($value[8]))."', entry_street_address='".mysql_escape_string(trim($value[9]))."', entry_suburb='', entry_postcode='".trim($value[11])."', entry_city='".mysql_escape_string(trim($value[10]))."', entry_state='".mysql_escape_string($_state)."', entry_country_id='".$country_id[countries_id]."', entry_zone_id='".$zone_id[zone_id]."'";
			tep_db_query($sql_query);
			$address_book_id = tep_db_insert_id();
			$sql_query = "update customers set customers_default_address_id='".$address_book_id."' where customers_id=".$customer_id;
			tep_db_query($sql_query);
			$date_created = date("Y-m-d H:i:s");
			tep_db_query("insert into customers_info set customers_info_id='".$customer_id."', customers_info_date_account_created='".$date_created."', customers_info_source_id=0, global_product_notifications=0");
		}






$sql_query = "SELECT a .  * , b .  * , c.countries_name FROM customers a LEFT  JOIN address_book b ON ( a.customers_default_address_id = b.address_book_id ) LEFT  JOIN countries c ON ( b.entry_country_id = c.countries_id ) WHERE a.customers_id =  '".$customer_id."' LIMIT 1 ";
	$customer = tep_db_fetch_array(tep_db_query($sql_query));

	$customer_ship[entry_company]  =$customer['entry_company'];
	$customer_ship[fio]=$customer['customers_firstname'] . ' ' . $customer['customers_lastname'];
	$customer_ship[entry_street_address]=$customer['entry_street_address'];
	$customer_ship[entry_postcode]=$customer['entry_postcode'];
	$customer_ship[entry_city]=$customer['entry_city'];
	$customer_ship[entry_state]=$customer['entry_state'];
	$customer_ship[countries_name]=$customer['countries_name'];


$firstDate = explode(" ", $value[1]);
$pos = explode("/", $firstDate[0]);
$date_purchased = $pos[2] . "-" . $pos[0] . "-" . $pos[1];
if (trim($date_purchased) == '') $date_purchased = date("Y-m-d");

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
			  'billing_name' => $customer[customers_firstname]." ".$customer[customers_lastname], 
			  'billing_company' => $customer[entry_company], 
	                  'billing_street_address' => $customer['entry_street_address'], 
	                  'billing_suburb' => '', 
	                  'billing_city' => $customer[entry_city], 
	                  'billing_postcode' => $customer[entry_postcode], 
	                  'billing_state' => $customer[entry_state], 
	                  'billing_country' => $customer[countries_name],
	                  'billing_address_format_id' => '2',
                          'payment_method' => $payment,
                          'cc_type' => '',
                          'cc_owner' => '',
                          'cc_number' => '',
                          'cc_expires' => '',
                          'date_purchased' => $date_purchased,
                          'orders_status' => '20',
                          'iswholesale' => '1',
                          'purchase_order_number' => $pon,
                          'currency' => 'USD',
                          'currency_value' => '1.00000000',
                          'customers_referer_url' => '',
                          'ipaddy' => '',
			  'ipisp' => '');

  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $org_insert_id = tep_db_insert_id();
  $insert_id = tep_db_insert_id()+2;
  $ord_upd = tep_db_query("update ".TABLE_ORDERS." set orders_id = ".$insert_id." where orders_id = ".$org_insert_id);



$total_price = 0;
  $sel_prod = tep_db_fetch_array(tep_db_query("select a.products_price, b.products_id, b.products_name_prefix, b.products_name, b.products_name_suffix, a.products_model, c.series_name from products a left join products_description b on (a.products_id = b.products_id) LEFT JOIN series c ON (a.series_id = c.series_id) where a.products_model='".$value[products_model]."' limit 1"));

    $sql_query = "SELECT vendors_item_cost, vendors_product_payment_type, vendors_consignment, vendors_consignment_percentage, vendors_royalty , vendors_royalty_percentage FROM products_to_vendors where products_id=".$sel_prod[products_id]." limit 0,1";
    $prod_vendor = tep_db_fetch_array(tep_db_query($sql_query));

	switch($prod_vendor['vendors_product_payment_type']){
	case "1":
		$final_prod_vendor = (round(($value[6]),2)/100)*25;
	break;

	case "2":
		$final_prod_vendor = (round(($value[6]),2)/100)*25;
	break;

	case "3":
	if (intval($prod_vendor['vendors_royalty_percentage'])==0) $prod_vendor['vendors_royalty_percentage'] = 25;

		$final_prod_vendor = (round(($value[6]),2)/100)*$prod_vendor['vendors_royalty_percentage'];

	break;
	default: 
		$final_prod_vendor = (round(($value[6]/$value['qty']),2)/100)*25;
	break;
    }

    $total_price = $total_price + $value[6];
    $firstDate = explode(" ", $value[1]);
    $pos = explode("/", $firstDate[0]);
    $currdate = $pos[2] . "-" . $pos[0] . "-" . $pos[1];
    if (trim($currdate) == '') $currdate = date("Y-m-d");

    tep_db_query("insert into orders_products set products_item_cost='".$final_prod_vendor."', products_sale_type='3', orders_id='".$insert_id."', products_id='".$sel_prod[products_id]."', products_model='".mysql_escape_string(strtoupper($value['products_model']))."', products_name='".mysql_escape_string($series.$sel_prod[products_name_prefix]." ".$sel_prod[products_name]." ".$sel_prod[products_name_suffix])."', products_price='".round(($value[6]), 2)."' , final_price='".round(($value[6]), 2)."', products_quantity='1', products_prepared=0, ordered=0, date_shipped='".$currdate."', date_shipped_checkbox=1");

    tep_db_query("update products set products_ordered = products_ordered + 1  where products_id = '" . $sel_prod[products_id] . "'");

    tep_db_query("insert into orders_status_history set orders_id='".$insert_id."', orders_status_id=1, date_added='".$currdate."', customer_notified=1, comments='".mysql_escape_string($comments)."'");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>DOD</b>', text='$0.00', value='0.00', class='ot_shipping', sort_order=1");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='Sub-Total:', text='<b>".$currencies->format($total_price, true, 'USD','1.00000')."</b>', value='".$total_price."', class='ot_subtotal', sort_order=2");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>Total:</b>', text='<b>".$currencies->format($total_price, true, 'USD','1.00000')."</b>', value='".$total_price."', class='ot_total', sort_order=3");
	}

    fclose ($handle);	
    @unlink(@$path);

    echo "<script>location.href='new_order_parser.php?msg=Successfully Parsed! See order list for detailes!'</script>";
    exit;
}

function parseAmazonDe($path){
	global $currencies;
	$handle = fopen ($path,"r");
	$products = array();
	while ($data = fgetcsv ($handle, 1000, ",")) {
	    $num = count ($data);
	    if (++$row == 1) continue;
	    for ($c=0; $c < $num; $c++) {
		switch($c){
	        	case '4': 
				$catalog_id = trim($data[$c]); 
				if (!array_key_exists($catalog_id, $products)) {
					$products[$catalog_id] = array(); 
					$products[$catalog_id]['qty'] = 0;
					$products[$catalog_id]['price'] = 0;
					$products[$catalog_id]['royalty'] = 0;
				}
			break;
	        	case '6': 
				$qty = intval($data[$c]); 
				$products[$catalog_id]['qty'] = $products[$catalog_id]['qty']+$qty;
			break;
	        	case '10': 
				$amount_owed = $data[$c]; 
				$products[$catalog_id]['price'] = $amount_owed;
				$products[$catalog_id]['royalty'] = $products[$catalog_id]['royalty'] + ($amount_owed*$qty);
			break;
		}
	    }
	}

	$error = false;	
	foreach($products as $key=>$value) {
		$model = tep_db_fetch_array(tep_db_query("select products_model from products where customerflix_id LIKE'%".$key."%' limit 1"));
		if ($model) $products[$key]['products_model'] = $model['products_model'];
		else {		
			$error = true;
			echo "Products with Createspace ID: " . $key . " does not exists in DB<br />";
		}
	}
	if ($error) die();

$sql_query = "SELECT a .  * , b .  * , c.countries_name FROM customers a LEFT  JOIN address_book b ON ( a.customers_default_address_id = b.address_book_id ) LEFT  JOIN countries c ON ( b.entry_country_id = c.countries_id ) WHERE a.customers_id =  '55241' LIMIT 1 ";
	$customer = tep_db_fetch_array(tep_db_query($sql_query));

	$customer_ship[entry_company]  ='';
	$customer_ship[fio]='';
	$customer_ship[entry_street_address]='';
	$customer_ship[entry_postcode]='';
	$customer_ship[entry_city]='';
	$customer_ship[entry_state]='';
	$customer_ship[countries_name]='';


$date_purchased = date("Y-m-d H:i:s");

$sql_query = "SELECT * from currencies where upper(code)='EUR' limit 1";
$curr = tep_db_fetch_array(tep_db_query($sql_query));

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
			  'billing_name' => $customer[customers_firstname]." ".$customer[customers_lastname], 
			  'billing_company' => $customer[entry_company], 
	                  'billing_street_address' => $customer['entry_street_address'], 
	                  'billing_suburb' => '', 
	                  'billing_city' => $customer[entry_city], 
	                  'billing_postcode' => $customer[entry_postcode], 
	                  'billing_state' => $customer[entry_state], 
	                  'billing_country' => $customer[countries_name],
	                  'billing_address_format_id' => '2',
                          'payment_method' => 'Customflix EU',
                          'cc_type' => '',
                          'cc_owner' => '',
                          'cc_number' => '',
                          'cc_expires' => '',
                          'date_purchased' => $date_purchased,
                          'orders_status' => '20',
                          'iswholesale' => '1',
                          'purchase_order_number' => $po_number,
                          'currency' => 'EUR',
                          'currency_value' => '1.00000000',
                          'customers_referer_url' => '',
                          'ipaddy' => '',
			  'ipisp' => '');

  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $org_insert_id = tep_db_insert_id();
  $insert_id = tep_db_insert_id()+2;
  $ord_upd = tep_db_query("update ".TABLE_ORDERS." set orders_id = ".$insert_id." where orders_id = ".$org_insert_id);



$total_price = 0;
foreach($products as $key=>$value){

  $sel_prod = tep_db_fetch_array(tep_db_query("select a.products_price, b.products_id, b.products_name_prefix, b.products_name, b.products_name_suffix, a.products_model, c.series_name from products a left join products_description b on (a.products_id = b.products_id) LEFT JOIN series c ON (a.series_id = c.series_id) where a.products_model='".$value[products_model]."' limit 1"));

    $sql_query = "SELECT vendors_item_cost, vendors_product_payment_type, vendors_consignment, vendors_consignment_percentage, vendors_royalty , vendors_royalty_percentage FROM products_to_vendors where products_id=".$sel_prod[products_id]." limit 0,1";
    $prod_vendor = tep_db_fetch_array(tep_db_query($sql_query));

	switch($prod_vendor['vendors_product_payment_type']){
	case "1":
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;

	case "2":
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;

	case "3":
	if (intval($prod_vendor['vendors_royalty_percentage'])==0) $prod_vendor['vendors_royalty_percentage'] = 25;

		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*$prod_vendor['vendors_royalty_percentage'];

	break;
	default: 
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;
    }

    $total_price = $total_price + $value['royalty'];
    $currdate = date("Y-m-d");

    tep_db_query("insert into orders_products set products_item_cost='".$final_prod_vendor."', products_sale_type='3', orders_id='".$insert_id."', products_id='".$sel_prod[products_id]."', products_model='".mysql_escape_string(strtoupper($value['products_model']))."', products_name='".mysql_escape_string($series.$sel_prod[products_name_prefix]." ".$sel_prod[products_name]." ".$sel_prod[products_name_suffix])."', products_price='".round(($value['royalty']/$value['qty']), 2)."' , final_price='".round(($value['royalty']/$value['qty']), 2)."', products_quantity='".$value['qty']."', products_prepared=0, ordered=0, date_shipped='".$currdate."', date_shipped_checkbox=1");

    tep_db_query("update products set products_ordered = products_ordered + " . $value['qty'] . "  where products_id = '" . $sel_prod[products_id] . "'");
	}

    tep_db_query("insert into orders_status_history set orders_id='".$insert_id."', orders_status_id=1, date_added='".$date_purchased."', customer_notified=1, comments='".mysql_escape_string($comments)."'");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>DOD</b>', text='&euro;0.00', value='0.00', class='ot_shipping', sort_order=1");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='Sub-Total:', text='<b>".$currencies->format($total_price, true, 'EUR','1.00000')."</b>', value='".$total_price."', class='ot_subtotal', sort_order=2");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>Total:</b>', text='<b>".$currencies->format($total_price, true, 'EUR','1.00000')."</b>', value='".$total_price."', class='ot_total', sort_order=3");
    fclose ($handle);	
    @unlink(@$path);

    echo "<script>location.href='new_order_parser.php?msg=Successfully Parsed Order: ".$insert_id." created!'</script>";
    exit;
}
function parseAmazonUK($path){
	global $currencies;
	$handle = fopen ($path,"r");
	$products = array();
	while ($data = fgetcsv ($handle, 1000, ",")) {
	    $num = count ($data);
	    if (++$row == 1) continue;
	    for ($c=0; $c < $num; $c++) {
		switch($c){
	        	case '4': 
				$catalog_id = trim($data[$c]); 
				if (!array_key_exists($catalog_id, $products)) {
					$products[$catalog_id] = array(); 
					$products[$catalog_id]['qty'] = 0;
					$products[$catalog_id]['price'] = 0;
					$products[$catalog_id]['royalty'] = 0;
				}
			break;
	        	case '6': 
				$qty = intval($data[$c]); 
				$products[$catalog_id]['qty'] = $products[$catalog_id]['qty']+$qty;
			break;
	        	case '10': 
				$amount_owed = $data[$c]; 
				$products[$catalog_id]['price'] = $amount_owed;
				$products[$catalog_id]['royalty'] = $products[$catalog_id]['royalty'] + ($amount_owed*$qty);
			break;
		}
	    }
	}

	$error = false;	
	foreach($products as $key=>$value) {
		$model = tep_db_fetch_array(tep_db_query("select products_model from products where customerflix_id LIKE'%".$key."%' limit 1"));
		if ($model) $products[$key]['products_model'] = $model['products_model'];
		else {		
			$error = true;
			echo "Products with Createspace ID: " . $key . " does not exists in DB<br />";
		}
	}
	if ($error) die();

$sql_query = "SELECT a .  * , b .  * , c.countries_name FROM customers a LEFT  JOIN address_book b ON ( a.customers_default_address_id = b.address_book_id ) LEFT  JOIN countries c ON ( b.entry_country_id = c.countries_id ) WHERE a.customers_id =  '75197' LIMIT 1 ";
	$customer = tep_db_fetch_array(tep_db_query($sql_query));

	$customer_ship[entry_company]  ='';
	$customer_ship[fio]='';
	$customer_ship[entry_street_address]='';
	$customer_ship[entry_postcode]='';
	$customer_ship[entry_city]='';
	$customer_ship[entry_state]='';
	$customer_ship[countries_name]='';


$date_purchased = date("Y-m-d H:i:s");

$sql_query = "SELECT * from currencies where upper(code)='GBP' limit 1";
$curr = tep_db_fetch_array(tep_db_query($sql_query));

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
			  'billing_name' => $customer[customers_firstname]." ".$customer[customers_lastname], 
			  'billing_company' => $customer[entry_company], 
	                  'billing_street_address' => $customer['entry_street_address'], 
	                  'billing_suburb' => '', 
	                  'billing_city' => $customer[entry_city], 
	                  'billing_postcode' => $customer[entry_postcode], 
	                  'billing_state' => $customer[entry_state], 
	                  'billing_country' => $customer[countries_name],
	                  'billing_address_format_id' => '2',
                          'payment_method' => 'Customflix EU',
                          'cc_type' => '',
                          'cc_owner' => '',
                          'cc_number' => '',
                          'cc_expires' => '',
                          'date_purchased' => $date_purchased,
                          'orders_status' => '20',
                          'iswholesale' => '1',
                          'purchase_order_number' => $po_number,
                          'currency' => 'GBP',
                          'currency_value' => '1.00000000',
                          'customers_referer_url' => '',
                          'ipaddy' => '',
			  'ipisp' => '');

  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $org_insert_id = tep_db_insert_id();
  $insert_id = tep_db_insert_id()+2;
  $ord_upd = tep_db_query("update ".TABLE_ORDERS." set orders_id = ".$insert_id." where orders_id = ".$org_insert_id);



$total_price = 0;
foreach($products as $key=>$value){

  $sel_prod = tep_db_fetch_array(tep_db_query("select a.products_price, b.products_id, b.products_name_prefix, b.products_name, b.products_name_suffix, a.products_model, c.series_name from products a left join products_description b on (a.products_id = b.products_id) LEFT JOIN series c ON (a.series_id = c.series_id) where a.products_model='".$value[products_model]."' limit 1"));

    $sql_query = "SELECT vendors_item_cost, vendors_product_payment_type, vendors_consignment, vendors_consignment_percentage, vendors_royalty , vendors_royalty_percentage FROM products_to_vendors where products_id=".$sel_prod[products_id]." limit 0,1";
    $prod_vendor = tep_db_fetch_array(tep_db_query($sql_query));

	switch($prod_vendor['vendors_product_payment_type']){
	case "1":
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;

	case "2":
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;

	case "3":
	if (intval($prod_vendor['vendors_royalty_percentage'])==0) $prod_vendor['vendors_royalty_percentage'] = 25;

		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*$prod_vendor['vendors_royalty_percentage'];

	break;
	default: 
		$final_prod_vendor = (round(($value['royalty']/$value['qty']),2)/100)*25;
	break;
    }

    $total_price = $total_price + $value['royalty'];
    $currdate = date("Y-m-d");

    tep_db_query("insert into orders_products set products_item_cost='".$final_prod_vendor."', products_sale_type='3', orders_id='".$insert_id."', products_id='".$sel_prod[products_id]."', products_model='".mysql_escape_string(strtoupper($value['products_model']))."', products_name='".mysql_escape_string($series.$sel_prod[products_name_prefix]." ".$sel_prod[products_name]." ".$sel_prod[products_name_suffix])."', products_price='".round(($value['royalty']/$value['qty']), 2)."' , final_price='".round(($value['royalty']/$value['qty']), 2)."', products_quantity='".$value['qty']."', products_prepared=0, ordered=0, date_shipped='".$currdate."', date_shipped_checkbox=1");

    tep_db_query("update products set products_ordered = products_ordered + " . $value['qty'] . "  where products_id = '" . $sel_prod[products_id] . "'");
	}

    tep_db_query("insert into orders_status_history set orders_id='".$insert_id."', orders_status_id=1, date_added='".$date_purchased."', customer_notified=1, comments='".mysql_escape_string($comments)."'");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>DOD</b>', text='&pound;0.00', value='0.00', class='ot_shipping', sort_order=1");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='Sub-Total:', text='<b>".$currencies->format($total_price, true, 'GBP','1.00000')."</b>', value='".$total_price."', class='ot_subtotal', sort_order=2");
    tep_db_query("insert into orders_total set orders_id='".$insert_id."', title='<b>Total:</b>', text='<b>".$currencies->format($total_price, true, 'GBP','1.00000')."</b>', value='".$total_price."', class='ot_total', sort_order=3");
    fclose ($handle);	
    @unlink(@$path);

    echo "<script>location.href='new_order_parser.php?msg=Successfully Parsed Order: ".$insert_id." created!'</script>";
    exit;
}
?>
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
            <td class="pageHeading">External Order PARSER:</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php if ($_GET['msg']!=''){?>
	<tr><td><b><?=$_GET['msg']?></b></td></tr>
<?php } ?>
      <tr>
	<form action="?action=parse" method="post" enctype="multipart/form-data" onsubmit="return confirm('Be sure that file extension compatible with chosen order type method!\n')">
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr><td>File: </td><td><input type="file" name="uploadedfile" /></td></tr>
	  <tr>
		<td>Type: </td>
		<td>
		Customflix-CSV&nbsp;<input type="radio" name="type" value="1" checked />&nbsp;&nbsp;&nbsp;
		Overdrive-CSV&nbsp;<input type="radio" name="type" value="2"/>&nbsp;&nbsp;&nbsp;
		GrapeFlix-XLS&nbsp;<input type="radio" name="type" value="3"/>&nbsp;&nbsp;&nbsp;
		Amazon DE-XLS&nbsp;<input type="radio" name="type" value="4"/>&nbsp;&nbsp;&nbsp;
		Amazon UK-XLS&nbsp;<input type="radio" name="type" value="7"/>&nbsp;&nbsp;&nbsp;
		Amazon PO UK-XLS&nbsp;<input type="radio" name="type" value="5"/>&nbsp;&nbsp;&nbsp;
		YouTube-CSV&nbsp;<input type="radio" name="type" value="6"/>&nbsp;&nbsp;&nbsp;

		</td>
	  </tr>
	  <tr><td colspan="2" align="left"><input type="submit" value="Parse" />&nbsp;<input type="button" value="Cancel" onclick="location.href='new_order_parser.php'"/></td></tr>
	</table>
	</form>
	</td></tr>
</table>
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