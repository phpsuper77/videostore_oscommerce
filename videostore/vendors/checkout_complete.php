<?php
/*
  $Id: checkout_process.php,v 1.128 2003/05/28 18:00:29 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include('includes/application_top.php');
  
  // echo SEND_EXTRA_ORDER_EMAILS_TO; exit;

define('MODULE_SHIPPING_INSTALLED','usps.php;fedex1.php;dhlairborne.php;table.php;ups.php;zones.php');

  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  DEFINE('MODULE_ORDER_TOTAL_INSTALLED','ot_subtotal.php;ot_tax.php;ot_shipping.php;ot_total.php');

if (intval($_SESSION["vendors_id"])==0)
  {
	tep_redirect("../index.php");
	exit;
  }


/*------------------Don't touch it uses in tep_get_tax_rate function in general.php */
$checkout_flag = true;
/*------------------Don't touch it uses in tep_get_tax_rate function in general.php */

  //ORDER IP RECORDER
  $ip1 = $HTTP_SERVER_VARS["REMOTE_ADDR"];
  $client = gethostbyaddr($HTTP_SERVER_VARS["REMOTE_ADDR"]);
  $str = preg_split("/\./", $client);
  $i = count($str);
  $x = $i - 1;
  $n = $i - 2;
  $isp = $str[$n] . "." . $str[$x];
  //ORDER IP RECORDER
  DEFINE('MODULE_PAYMENT_INSTALLED','linkpointms1.php') ;
  if (MODULE_PAYMENT_INSTALLED=='') {
	header('Location: https://secure.travelvideostore.com/vendors/checkout_2.php');
	exit;
 }


// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
//      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
	header('Location: https://secure.travelvideostore.com/vendors/distribution.php');
	exit;
    }
  }

  // echo SEND_EXTRA_ORDER_EMAILS_TO."<br />";
  // echo EMAIL_TEXT_SUBJECT."<br />";
  // echo $email_order."<br />";
  // echo STORE_OWNER."<br />";
  // echo STORE_OWNER_EMAIL_ADDRESS;
  // exit;
  // echo __LINE__.' - '.SEND_EXTRA_ORDER_EMAILS_TO; exit;
  // echo DIR_WS_LANGUAGES . 'english/' . FILENAME_CHECKOUT_PROCESS; exit;
  include(DIR_WS_LANGUAGES . 'english/' . FILENAME_CHECKOUT_PROCESS);

  // echo SEND_EXTRA_ORDER_EMAILS_TO."<br />";
  // echo EMAIL_TEXT_SUBJECT."<br />";
  // echo $email_order."<br />";
  // echo STORE_OWNER."<br />";
  // echo STORE_OWNER_EMAIL_ADDRESS;
  // exit;
// load selected payment module
  require('includes/classes/payment.php');
  if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
  $payment = 'linkpointms1';

  $payment_modules = new payment($payment);
//var_dump($payment_modules);
// load the selected shipping module
  require('includes/classes/shipping.php');
  $shipping_modules = new shipping($shipping);

  require('includes/classes/order.php');
  $order = new order;

// load the before_process function from the payment modules
  $payment_modules->before_process();
// var_dump($payment_modules);
  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;

  $order_totals = $order_total_modules->process();

if (strtolower(trim($order->info['payment_method']))=='check/money order') $order->info['order_status'] = 9;
if (strtolower(trim($order->info['payment_method']))=='purchase order') $order->info['order_status'] = 10;


  $sql_data_array = array('customers_id' => $customer_id,
                          'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
                          'customers_company' => $order->customer['company'],
                          'customers_street_address' => $order->customer['street_address'],
                          'customers_suburb' => $order->customer['suburb'],
                          'customers_city' => $order->customer['city'],
                          'customers_postcode' => $order->customer['postcode'],
                          'customers_state' => $order->customer['state'],
                          'customers_country' => $order->customer['country']['title'],
                          'customers_telephone' => $order->customer['telephone'],
                          'customers_email_address' => $order->customer['email_address'],
                          'customers_address_format_id' => $order->customer['format_id'],
                          'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],
                          'delivery_company' => $order->delivery['company'],
                          'delivery_street_address' => $order->delivery['street_address'],
                          'delivery_suburb' => $order->delivery['suburb'],
                          'delivery_city' => $order->delivery['city'],
                          'delivery_postcode' => $order->delivery['postcode'],
                          'delivery_state' => $order->delivery['state'],
                          'delivery_country' => $order->delivery['country']['title'],
                          'delivery_address_format_id' => $order->delivery['format_id'],
			  'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'], 
	                  'billing_company' => $order->billing['company'], 
	                  'billing_street_address' => $order->billing['street_address'], 
	                  'billing_suburb' => $order->billing['suburb'], 
	                  'billing_city' => $order->billing['city'], 
	                  'billing_postcode' => $order->billing['postcode'], 
	                  'billing_state' => $order->billing['state'], 
	                  'billing_country' => $order->billing['country']['title'],
	                  'billing_address_format_id' => $order->billing['format_id'],
                          'payment_method' => $order->info['payment_method'],
                          'cc_type' => $order->info['cc_type'],
                          'cc_owner' => $order->info['cc_owner'],
                          'cc_number' => $order->info['cc_number'],
                          'cc_expires' => $order->info['cc_expires'],
                          'date_purchased' => 'now()',
                          'orders_status' => $order->info['order_status'],
// purchaseorders_1_4 start
                          'purchase_order_number' => $order->info['purchase_order_number'],
// purchaseorders_1_4 end
                          'currency' => $order->info['currency'],
                          'currency_value' => $order->info['currency_value'],
                          'customers_referer_url' => $referer_url,
                          'ipaddy' => $ip1,
			  'ipisp' => $isp);
  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  #$insert_id = tep_db_insert_id();


 
 //tep_db_query("update " . TABLE_HOLDING_ORDERS . " set  where orders_id='".$_SESSION[insert_order_id]."'");
//tep_db_query("update " . TABLE_HOLDING_ORDERS . " set billing_name='".$order->billing['firstname'] . ' ' . $order->billing['lastname']."', billing_company='".$order->billing['company']."', billing_street_address='".$order->billing['street_address']."', billing_suburb='".$order->billing['suburb']."', billing_city='".$order->billing['city']."', billing_postcode='".$order->billing['postcode']."', billing_state='".$order->billing['state']."', billing_country='".$order->billing['country']['title']."', billing_address_format_id='".$order->billing['format_id']."' where orders_id='".$_SESSION[insert_order_id]."'");


  #######
  #################UPDATED FOR AUTOINCREMENT OF ORDER ID
  #######
  $org_insert_id = tep_db_insert_id();
  $insert_id = tep_db_insert_id()+2;
  $_SESSION[order_id_complete] = $insert_id;
  $ord_upd = tep_db_query("update ".TABLE_ORDERS." set orders_id = ".$insert_id." where orders_id = ".$org_insert_id);
  #######

		   $sql_data_array1 = array('billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'], 
	                  	  'billing_company' => $order->billing['company'], 
	                          'billing_street_address' => $order->billing['street_address'], 
	                          'billing_suburb' => $order->billing['suburb'], 
	                          'billing_city' => $order->billing['city'], 
	                          'billing_postcode' => $order->billing['postcode'], 
	                          'billing_state' => $order->billing['state'], 
	                          'billing_country' => $order->billing['country']['title'],
	                          'billing_address_format_id' => $order->billing['format_id']);
		   tep_db_perform(TABLE_HOLDING_ORDERS, $sql_data_array1,'update','orders_id="'.$_SESSION[insert_order_id].'"');


  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => $order_totals[$i]['title'],
                            'text' => $order_totals[$i]['text'],
                            'value' => $order_totals[$i]['value'],
                            'class' => $order_totals[$i]['code'],
                            'sort_order' => $order_totals[$i]['sort_order']);
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  }

//  $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
$customer_notification = 1;

  $sql_data_array = array('orders_id' => $insert_id,
                          'orders_status_id' => $order->info['order_status'],
                          'date_added' => 'now()',
                          'customer_notified' => $customer_notification,
                          'comments' => $order->info['comments']);
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

// initialized for the email confirmation
  $products_ordered = '';
  $subtotal = 0;
  $total_tax = 0;

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
// Stock Update - Joao Correia
    if (STOCK_LIMITED == 'true') {
      if (DOWNLOAD_ENABLED == 'true') {
        $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename
                            FROM " . TABLE_PRODUCTS . " p
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                             ON p.products_id=pa.products_id
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                             ON pa.products_attributes_id=pad.products_attributes_id
                            WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
// Will work with only one option for downloadable products
// otherwise, we have to build the query dynamically with a loop
        $products_attributes = $order->products[$i]['attributes'];
        if (is_array($products_attributes)) {
          $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
        }
        $stock_query = tep_db_query($stock_query_raw);
      } else {
        $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
      }
      if (tep_db_num_rows($stock_query) > 0) {
        $stock_values = tep_db_fetch_array($stock_query);
// do not decrement quantities if products_attributes_filename exists
        if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
          $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
        } else {
          $stock_left = $stock_values['products_quantity'];
        }
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
        if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
        }
      }
    }

// Update products_ordered (for bestsellers list)
    tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");


    $sql_data_array = array('orders_id' => $insert_id,
                            'products_id' => tep_get_prid($order->products[$i]['id']),
                            'products_model' => $order->products[$i]['model'],
                            'products_name' => $order->products[$i]['name'],
                            'products_price' => $order->products[$i]['price'],
                            'final_price' => $order->products[$i]['final_price'],
                            'products_tax' => $order->products[$i]['tax'],
                            'products_sale_type' => '4',
                            'products_item_cost' => '',
                            'products_quantity' => $order->products[$i]['qty']);

    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
    $order_products_id = tep_db_insert_id();
    $order_total_modules->update_credit_account($i);//ICW ADDED FOR CREDIT CLASS SYSTEM
//------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';
    if (isset($order->products[$i]['attributes'])) {
      $attributes_exist = '1';
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        if (DOWNLOAD_ENABLED == 'true') {
          $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
                               from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                               left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                on pa.products_attributes_id=pad.products_attributes_id
                               where pa.products_id = '" . $order->products[$i]['id'] . "'
                                and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                and pa.options_id = popt.products_options_id
                                and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                and pa.options_values_id = poval.products_options_values_id
                                and popt.language_id = '" . $languages_id . "'
                                and poval.language_id = '" . $languages_id . "'";
          $attributes = tep_db_query($attributes_query);
        } else {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
        }
        $attributes_values = tep_db_fetch_array($attributes);

        $sql_data_array = array('orders_id' => $insert_id,
                                'orders_products_id' => $order_products_id,
                                'products_options' => $attributes_values['products_options_name'],
                                'products_options_values' => $attributes_values['products_options_values_name'],
                                'options_values_price' => $attributes_values['options_values_price'],
                                'price_prefix' => $attributes_values['price_prefix']);
        tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

        if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
          $sql_data_array = array('orders_id' => $insert_id,
                                  'orders_products_id' => $order_products_id,
                                  'orders_products_filename' => $attributes_values['products_attributes_filename'],
                                  'download_maxdays' => $attributes_values['products_attributes_maxdays'],
                                  'download_count' => $attributes_values['products_attributes_maxcount']);
          tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
        }
        $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
      }
    }

    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
    $total_cost += $total_products_price;

$add_text = '';
$date_avail = tep_db_fetch_array(tep_db_query("select products_date_available,products_out_of_print from products where products_id='".$order->products[$i]['id']."' limit 1"));

$part = explode("-",substr($date_avail[products_date_available],0,10));
$date_available = mktime(0, 0, 1, $part[1], $part[2], $part[0]);
$curr_date = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));

if (($date_avail['products_date_available']!='') and ($date_available>$curr_date) and ($date_avail['products_out_of_print']=='1')){
	$add_text = "(**Pre-Ordered Item will ship on ".$part[1]."-".$part[2]."-".$part[0]."**)";
}

    $products_ordered .= $order->products[$i]['qty'] . '   ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'])*$order->products[$i]['qty'], 'true','USD', '1.000000') . $products_ordered_attributes . " ".$add_text."\n";
  }

  $order_total_modules->apply_credit();//ICW ADDED FOR CREDIT CLASS SYSTEM
// lets start with the email confirmation
$email_order="";
if (!tep_session_is_registered('noaccount')) {
  $email_order_old = STORE_NAME . "\n" .
                 EMAIL_SEPARATOR . "\n" .
                 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" .
                 //EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . "\n" .
                 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";

  $email_order_new = STORE_NAME . "\n" .
                 EMAIL_SEPARATOR . "\n" .
                 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" .
                 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";

  $email_order_new_one = STORE_NAME . "\n" .
                 "Customer Email Address: " .$order->customer['email_address']. "\n" .
                 EMAIL_SEPARATOR . "\n" .
                 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" .
                 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";

  if ($order->info['comments']) {
    $email_order .= tep_db_output($order->info['comments']) . "\n\n";
  }
  $email_order .= EMAIL_TEXT_PRODUCTS . "\n" .
                  EMAIL_SEPARATOR . "\n" .
                  $products_ordered .
                  EMAIL_SEPARATOR . "\n";
  } else {
  $email_order = STORE_NAME . "\n" .
                 EMAIL_SEPARATOR . "\n" .
                 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" .
                 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
  if ($order->info['comments']) {
    $email_order .= tep_db_output($order->info['comments']) . "\n\n";
  }
  $email_order .= EMAIL_TEXT_PRODUCTS . "\n" .
                  EMAIL_SEPARATOR . "\n" .
                  $products_ordered .
                  EMAIL_SEPARATOR . "\n";
  }

  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
  }

$email_summary = $email_order;

  if ($order->content_type != 'virtual') {
    $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" .
                    EMAIL_SEPARATOR . "\n" .
                    tep_address_format(2, $order->delivery, false, '', "\n") . "\n";
  }

  $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
                  EMAIL_SEPARATOR . "\n" .
                  tep_address_format(2, $order->billing, false, '', "\n") . "\n\n";
  if (is_object($$payment)) {
    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" .
                    EMAIL_SEPARATOR . "\n";
    $payment_class = $$payment;
    $email_order .= $payment_class->title . "\n\n";
    if ($payment_class->email_footer) {
      $email_order .= $payment_class->email_footer . "\n\n";

// purchaseorders_1_4 start
      if($order->info['purchase_order_number'])
                        {
        $email_order .= EMAIL_TEXT_PURCHASE_ORDER_NUMBER . $order->info['purchase_order_number'] . "\n";
      }
// purchaseorders_1_4 end
    }
  }

  if (!tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order_old.$email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS)){
	echo 'error while sending email';
  }


// send emails to other people
  if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    if (!tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS)){
		echo 'error while sending email';
	}
  }



  $payment_modules->after_process();

  $cart->reset(true);


// unregister session variables used during checkout

	unset($_SESSION['product']);
	//unset($_SESSION['cart']);
	unset($_SESSION['vendor']);
	unset($_SESSION['cc_type']);
	unset($_SESSION['cc_number']);
  	unset($_SESSION['cc_expires_month']);
  	unset($_SESSION['cc_expires_year']);
	unset($_SESSION['shipping']);
	unset($_SESSION['payment']);
	unset($_SESSION['comments']);



	if(tep_session_is_registered('credit_covers')) tep_session_unregister('credit_covers'); //ICW ADDED FOR CREDIT CLASS SYSTEM
  $order_total_modules->clear_posts();//ICW ADDED FOR CREDIT CLASS SYSTEM

 // tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
    header('Location: https://secure.travelvideostore.com/vendors/checkout_success.php');

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>


