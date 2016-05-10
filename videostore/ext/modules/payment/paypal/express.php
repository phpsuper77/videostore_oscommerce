<?php
/*
  $Id: express.php 1803 2008-01-11 18:16:37Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/



error_reporting(E_ALL | E_STRICT) ;
ini_set('display_errors', 'On');

  chdir('../../../../');
  //chdir($_SERVER['DOCUMENT_ROOT']);

function MyLog($msg)
{
/*
	$file = "log.txt";	
	$content = file_get_contents($file) . "\n\r" . $msg;
	file_put_contents($file, $content);
*/
}

MyLog("-------- ext/../express ----<hr/>");


  require('includes/application_top.php');
  //var_dump("aaa", $_SESSION);exit;
  include_once(DIR_WS_CLASSES.'http_client.php');

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  require('includes/modules/payment/paypal_express.php');

  $paypal_express = new paypal_express();

  if (!$paypal_express->check() || !$paypal_express->enabled) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  if (MODULE_PAYMENT_PAYPAL_EXPRESS_TRANSACTION_SERVER == 'Live') {
    //$api_url = $paypal_express->nvp_api_url;
    $api_url = 'https://api-3t.paypal.com/nvp';
    $paypal_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout';
  } else {
    $api_url = $paypal_express->nvp_api_url_sandbox;
    $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout';
  }

  if (!tep_session_is_registered('sendto')) {
    tep_session_register('sendto');
    $sendto = $customer_default_address_id;
  }

  if (!tep_session_is_registered('billto')) {
    tep_session_register('billto');
    $billto = $customer_default_address_id;
  }

// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
  if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
  $cartID = $cart->cartID;

  $params = array('USER' => MODULE_PAYMENT_PAYPAL_EXPRESS_API_USERNAME,
                  'PWD' => MODULE_PAYMENT_PAYPAL_EXPRESS_API_PASSWORD,
                  'VERSION' => $paypal_express->api_version,
                  'SIGNATURE' => MODULE_PAYMENT_PAYPAL_EXPRESS_API_SIGNATURE);

  switch ($HTTP_GET_VARS['osC_Action']) {
    case 'retrieve':
      $params['METHOD'] = 'GetExpressCheckoutDetails';
      $params['TOKEN'] = $HTTP_GET_VARS['token'];
      $params['VERSION'] = '95.0';

      $post_string = '';

      foreach ($params as $key => $value) {
        $post_string .= $key . '=' . urlencode(trim($value)) . '&';
      }

      $post_string = substr($post_string, 0, -1);
//echo '<pre>'; print_r($post_string); echo '</pre>'; exit();
      $response = $paypal_express->sendTransactionToGateway($api_url, $post_string);
      $response_array = array();
      parse_str($response, $response_array);

      if (($response_array['ACK'] == 'Success') || ($response_array['ACK'] == 'SuccessWithWarning')) {
        include(DIR_WS_CLASSES . 'order.php');
        
        
        $fp = fopen(DIR_FS_CATALOG.'logs/paypal_logs/ppe-resp-'.date('YmdHis').'.log', 'a');
        foreach ($response_array as $key => $value) {
            fwrite($fp, $key.' - '.$value."\r\n");
        }
        fclose($fp);
        
        
        //$GLOBALS['comments'] = 'Token: '.$response_array['TOKEN'].', CorellationID: '.$response_array['CORRELATIONID'];
        //tep_session_register('comments');
        //$comments = 'Token: '.$response_array['TOKEN'].', CorellationID: '.$response_array['CORRELATIONID'];
        //$_SESSION['paypal_comments'] = 'Token: '.$response_array['TOKEN'].', CorellationID: '.$response_array['CORRELATIONID'];
        /*$sql_data_array = array('orders_id' => $insert_id,
                          'orders_status_id' => $order->info['order_status'],
                          'date_added' => 'now()',
                          'customer_notified' => $customer_notification,
                          'comments' => $order->info['comments']);
        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);*/
        
        /**
         * country state zone_id's
         */
        // Get the customer's country ID.
        $country_query = tep_db_query("SELECT countries_id, countries_name, address_format_id
            FROM " . TABLE_COUNTRIES . "
            WHERE countries_iso_code_2 = '".$response_array['SHIPTOCOUNTRYCODE']."'
            LIMIT 1");

        // see if we found a record, if not default to American format
        if (tep_db_num_rows($country_query) > 0) {
            $country = tep_db_fetch_array($country_query);
            // grab the country id and address format
            $country_id = $country['countries_id'];
            $country_name = $country['countries_name'];
            $address_format_id = $country['address_format_id'];
        } else {
            // default
            $country_id = '223';
            $country_name = 'United States';
            $address_format_id = '2'; // 2 is the American format
        }
        // get the state id
        $states_query = tep_db_query("SELECT zone_id FROM ".TABLE_ZONES." WHERE zone_code = '".$response_array['SHIPTOSTATE']."' AND zone_country_id = '".$country_id."' LIMIT 1");
        // see if we found a record
        if (tep_db_num_rows($states_query) > 0) {
            $states = tep_db_fetch_array($states_query);
            // grab the state zone_id
            $state_id = $states['zone_id'];
        } else {
            // default
            $state_id = 0;
        }
        /**
         * See if the user is logged in
         * if not
         *  check email for exisiting account, log them in under that account, then check address book for entry, if not same add it
         *  if email not found, create user, add address book entry
         *  log in user
         */ 
        if (!tep_session_is_registered('customer_id')) {
            $check_customer_query = tep_db_query("SELECT customers_id,
                customers_firstname, customers_lastname, customers_paypal_payerid,
                customers_paypal_ec
                FROM " . TABLE_CUSTOMERS . "
                WHERE customers_email_address = '" . tep_db_input($response_array['EMAIL']) . "'");
            $check_customer = tep_db_fetch_array($check_customer_query);

            // see if we found an account that matches the email address given
            // back from paypal
            $acct_exists = false;
            if (tep_db_num_rows($check_customer_query) > 0) {
                $acct_exists = true;
                // see if this was only a temp account if so remove it
                if ($check_customer['customers_paypal_ec'] == '1') {
                    // Delete the existing temporary account
                    $paypal_express->ec_delete_user($check_customer['customers_id']);
                    $acct_exists = false;
                }
            }
            // Create an account, if the account does not exist
            if (!$acct_exists) {
                // Generate a random 8-char password
                $salt = '46z3haZzegmn676PA3rUw2vrkhcLEn2p1c6gf7vp2ny4u3qqfqBh5j6kDhuLmyv9xf';
                srand((double)microtime() * 1000000);
                $password = '';
                for ($x = 0; $x < 7; $x++) {
                    $num = rand() % 33;
                    $tmp = substr($salt, $num, 1);
                    $password = $password . $tmp;
                }

                // set the customer information in the array for the table insertion
                $sql_data_array = array('customers_firstname'       => $response_array['FIRSTNAME'],
                                        'customers_lastname'            => $response_array['LASTNAME'],
                                        'customers_email_address'       => $response_array['EMAIL'],
                                        'customers_telephone'           => '0',
                                        'customers_fax'                 => '',
                                        'customers_gender'              => '',
                                        'customers_newsletter'          => '0',
                                        'customers_password'            => tep_encrypt_password($password),
                                        'customers_paypal_payerid'      => $response_array['PAYERID']);
                // insert the data
                $result = tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
                // grab the customer_id (last insert id)
                $customer_id = tep_db_insert_id();
                // set the customer address information in the array for the table insertion
                $sql_data_array = array('customers_id'              => $customer_id,
                                        'entry_gender'              => '',
                                        'entry_firstname'           => $response_array['FIRSTNAME'],
                                        'entry_lastname'            => $response_array['LASTNAME'],
                                        'entry_street_address'      => $response_array['SHIPTOSTREET'],
                                        'entry_suburb'              => '',
                                        'entry_city'                => $response_array['SHIPTOCITY'],
                                        'entry_zone_id'             => $state_id,
                                        'entry_postcode'            => $response_array['SHIPTOZIP'],
                                        'entry_country_id'          => $country_id);

                // insert the data
                tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
                // grab the address_id (last insert id)
                $address_id = tep_db_insert_id();
                // set the address id lookup for the customer
                tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");
                // insert into the customers info table, the new customer_id
                tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");
                // notify or not
                if ($paypal_express->new_account_notify) {
                    // require the language file
                    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

                    // set the mail text
                    $email_text = sprintf(EMAIL_GREET_NONE, $response_array['FIRSTNAME']) . EMAIL_WELCOME . EMAIL_TEXT;
                    $email_text .= EMAIL_EC_ACCOUNT_INFORMATION . "Username: " . $response_array['EMAIL'] . "\nPassword: " . $password . "\n\n";
                    $email_text .= EMAIL_CONTACT;

                    // send the mail
                    tep_mail($response_array['FIRSTNAME'] . " " . $response_array['LASTNAME'], $response_array['EMAIL'], EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

                    // set session var
                    $_SESSION['paypal_ec_temp'] = false;
                }else{
                    // Make it a temporary account that'll be deleted once they've checked out
                    tep_db_query("UPDATE " . TABLE_CUSTOMERS . "
                        SET customers_paypal_ec = '1'
                        WHERE customers_id = '" . (int)$customer_id . "'");

                    // set the boolean ec temp value
                    $_SESSION['paypal_ec_temp'] = True;
                }

            }else{
                // we already have the account, if we user temp accounts - make sure account not deleted here
            }
            // log the user in with the email sent back from paypal response
            $paypal_express->user_login($response_array['EMAIL'], false);
        }

        if ($cart->get_content_type() != 'virtual') {
          $country_iso_code_2 = tep_db_prepare_input($response_array['SHIPTOCOUNTRYCODE']);
          $zone_code = tep_db_prepare_input($response_array['SHIPTOSTATE']);

          $country_query = tep_db_query("select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id from " . TABLE_COUNTRIES . " where countries_iso_code_2 = '" . tep_db_input($country_iso_code_2) . "'");
          $country = tep_db_fetch_array($country_query);

          $zone_name = $response_array['SHIPTOSTATE'];
          $zone_id = 0;

          $zone_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country['countries_id'] . "' and zone_code = '" . tep_db_input($zone_code) . "'");
          if (tep_db_num_rows($zone_query)) {
            $zone = tep_db_fetch_array($zone_query);

            $zone_name = $zone['zone_name'];
            $zone_id = $zone['zone_id'];
          }

          $sendto = array('firstname' => substr($response_array['SHIPTONAME'], 0, strpos($response_array['SHIPTONAME'], ' ')),
                          'lastname' => substr($response_array['SHIPTONAME'], strpos($response_array['SHIPTONAME'], ' ')+1),
                          'company' => '',
                          'street_address' => $response_array['SHIPTOSTREET'],
                          'suburb' => '',
                          'postcode' => $response_array['SHIPTOZIP'],
                          'city' => $response_array['SHIPTOCITY'],
                          'zone_id' => $zone_id,
                          'zone_name' => $zone_name,
                          'country_id' => $country['countries_id'],
                          'country_name' => $country['countries_name'],
                          'country_iso_code_2' => $country['countries_iso_code_2'],
                          'country_iso_code_3' => $country['countries_iso_code_3'],
                          'address_format_id' => ($country['address_format_id'] > 0 ? $country['address_format_id'] : '1'));

          $billto = $sendto;

          $order = new order;

          $total_weight = $cart->show_weight();
          $total_count = $cart->count_contents();

// load all enabled shipping modules
          include(DIR_WS_CLASSES . 'shipping.php');
          $shipping_modules = new shipping;

          $free_shipping = false;

          if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
            $pass = false;

            switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
              case 'national':
                if ($order->delivery['country_id'] == STORE_COUNTRY) {
                  $pass = true;
                }
                break;

              case 'international':
                if ($order->delivery['country_id'] != STORE_COUNTRY) {
                  $pass = true;
                }
                break;

              case 'both':
                $pass = true;
                break;
            }

            if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
              $free_shipping = true;

              include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
            }
          }
set_error_handler('user_log');
          if (!tep_session_is_registered('shipping')) tep_session_register('shipping');
          $shipping = false;

//$shipping = 'free_free';
          if ( (tep_count_shipping_modules() > 0) || ($free_shipping == true) ) {
            if ($free_shipping == true) {
              $shipping = 'free_free';
            } else {  //echo 'checkout'; exit();
// get all available shipping quotes
              $quotes = $shipping_modules->quote();
// select cheapest shipping method
              $shipping = $shipping_modules->cheapest();
              $shipping = $shipping['id'];
            }
          }

          if (strpos($shipping, '_')) {
            list($module, $method) = explode('_', $shipping);

            if ( is_object($$module) || ($shipping == 'free_free') ) {
              if ($shipping == 'free_free') {
                $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
                $quote[0]['methods'][0]['cost'] = '0';
              } else {
                $quote = $shipping_modules->quote($method, $module);
              }

              if (isset($quote['error'])) {
                tep_session_unregister('shipping');

                tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
              } else {
                if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
                  $shipping = array('id' => $shipping,
                                    'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
                                    'cost' => $quote[0]['methods'][0]['cost']);
                }
              }
            }
          }

          if (!tep_session_is_registered('payment')) tep_session_register('payment');
          $payment = $paypal_express->code;

          if (!tep_session_is_registered('ppe_token')) tep_session_register('ppe_token');
          $ppe_token = $response_array['TOKEN'];

          if (!tep_session_is_registered('ppe_payerid')) tep_session_register('ppe_payerid');
          $ppe_payerid = $response_array['PAYERID'];
          
          if (!tep_session_is_registered('ppe_amt')) tep_session_register('ppe_amt');
          $ppe_amt = $response_array['AMT'];

          tep_redirect(tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'));
        } else {
          if (!tep_session_is_registered('shipping')) tep_session_register('shipping');
          $shipping = false;

          $sendto = false;

          if (!tep_session_is_registered('payment')) tep_session_register('payment');
          $payment = $paypal_express->code;

          if (!tep_session_is_registered('ppe_token')) tep_session_register('ppe_token');
          $ppe_token = $response_array['TOKEN'];

          if (!tep_session_is_registered('ppe_payerid')) tep_session_register('ppe_payerid');
          $ppe_payerid = $response_array['PAYERID'];

          tep_redirect(tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'));
        }
      } else {
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, 'error_message=' . stripslashes($response_array['L_LONGMESSAGE0'].' - '.$response_array['L_SHORTMESSAGE0']), 'SSL'));
      }

      break;

    default: 
      include(DIR_WS_CLASSES . 'order.php');
      $order = new order;

$aa1 = serialize($_SESSION['shipping']);
$bb1 = tep_session_is_registered('shipping');
$mylog = serialize($order);
$mylog = "1=====>" . $mylog . "<hr>";
$aa = serialize($shipping);
$mylog .= "-----------------" . $aa . "<hr>" . "========== session ==========<hr>" . $aa1 . "======<hr>" . $bb1;

MyLog($mylog);

      $params['METHOD'] = 'SetExpressCheckout';
      
      $params['PAYMENTACTION'] = ((MODULE_PAYMENT_PAYPAL_EXPRESS_TRANSACTION_METHOD == 'Sale') ? 'Sale' : 'Authorization');
      $params['RETURNURL'] = tep_href_link('ext/modules/payment/paypal/express.php', 'osC_Action=retrieve', 'SSL', true, false);
      //$params['CANCELURL'] = tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL', true, false);
      $params['CANCELURL'] = tep_href_link('checkout.php', '', 'SSL', true, false);
      
      //$params['AMT'] = $paypal_express->format_raw($order->info['total']);
      $params['PAYMENTREQUEST_0_AMT'] = $paypal_express->format_raw($order->info['total']);
      
      //$params['CURRENCYCODE'] = $order->info['currency'];
      $params['PAYMENTREQUEST_0_CURRENCYCODE'] = $order->info['currency'];
      
      $params['VERSION'] = '95.0';
      
      $params['REQCONFIRMSHIPPING'] = '0';

      if ($order->content_type == 'virtual') {
        $params['NOSHIPPING'] = '1';
      }
      // check the cart
      $o = 0;
      foreach ($order->products as $key => $arr){
        if(count($arr['rp']) == 0){
            continue;
        }
        include_once(DIR_WS_CLASSES . '/paypal_rp_product_info.php');
        $rpPprice = $currencies->display_price($arr['rp']['amt'],$arr['tax_class_id']);
        $rpPinfo = new paypal_rp_product_info($arr, $arr['rp'], $rpPprice);
        $rpPInfoArr = $rpPinfo->getProductInfoFull();
        // build the description
        $params['L_BILLINGAGREEMENTDESCRIPTION'.$o] = $arr['name'] . ' ';
        if(strlen($arr['rp']['trialBillingPeriod'])>0){
            $params['L_BILLINGAGREEMENTDESCRIPTION'.$o] .= $rpPInfoArr['trial'] . ', and ';
        }
        $params['L_BILLINGAGREEMENTDESCRIPTION'.$o] .= $rpPInfoArr['normal'];
        $params['L_BILLINGAGREEMENTDESCRIPTION'.$o] = substr($params['L_BILLINGAGREEMENTDESCRIPTION'.$o], 0, 127);
        // add the params
        $params['L_BILLINGTYPE'.$o] = 'RecurringPayments';
        
        // inc the count
        $o++;
      }
      
      //$params['ITEMAMT'] = $paypal_express->format_raw($order->info['subtotal']);
      $params['PAYMENTREQUEST_0_ITEMAMT'] = $paypal_express->format_raw($order->info['subtotal']);
      //$params['ITEMAMT'] = $order->info['subtotal'];

/*
$mylog = serialize($order);
$mylog = "1=====>" . $mylog . "<hr>";
MyLog($mylog);
*/

      //$params['SHIPPINGAMT'] = $paypal_express->format_raw($order->info['shipping_cost']);
      $params['PAYMENTREQUEST_0_SHIPPINGAMT'] = $paypal_express->format_raw($order->info['shipping_cost']);
      //$params['SHIPPINGAMT'] = $order->info['shipping_cost'];
    
      $item_amount = 0;
  

    
      foreach ($order->products as $product){

        //$params['L_NUMBER'.$o] = $product['model'];
        $params['L_PAYMENTREQUEST_0_NUMBER'.$o] = $product['model'];
        
        //$params['L_NAME'.$o] = str_replace('&nbsp;', ' ', strip_tags($product['name']));
        $params['L_PAYMENTREQUEST_0_NAME'.$o] = str_replace('&nbsp;', ' ', strip_tags($product['name']));
        
        //$params['L_QTY'.$o] = $product['qty'];
        $params['L_PAYMENTREQUEST_0_QTY'.$o] = $product['qty'];
        
        //$params['L_AMT'.$o] = $paypal_express->format_raw($product['final_price']);
        $params['L_PAYMENTREQUEST_0_AMT'.$o] = $paypal_express->format_raw($product['final_price']);
        //$params['L_AMT'.$o] = round($product['final_price'], 2);
        
        $item_amount += $paypal_express->format_raw($product['final_price']) * $product['qty'];
        
        // inc the count
        $o++;
      }
      include_once(DIR_WS_CLASSES.'order_total.php');
    $order_total_modules = new order_total;
/*
$mylog = serialize($order_total_modules);
$mylog = "2=====>" . $mylog . "<hr>";
MyLog($mylog);
*/

    $order_totals = $order_total_modules->process();
    //$order_totals = $order_total_modules;
     //$order_totals = get_express_order_total();
    //$item_amount = $order->info['subtotal'];
/*
$mylog = serialize($order_totals);
$mylog = "3=====>" . $mylog . "<hr>";
MyLog($mylog);
*/
    if (is_array($order_totals)) {
        foreach ($order_totals as $module_info) {

            if ($module_info['code'] == 'ot_subtotal') {
                
                //$item_amount = $module_info['value'];
                
            } elseif ($module_info['code'] == 'ot_tax') {
                
                //$params['TAXAMT'] = $paypal_express->format_raw($module_info['value']);
                $params['PAYMENTREQUEST_0_TAXAMT'] = $paypal_express->format_raw($module_info['value']);
                //$params['TAXAMT'] = round($module_info['value'], 2);
                
                $tax_amount = $paypal_express->format_raw($module_info['value']);

               
            } elseif ($module_info['code'] == 'ot_qty_discount') {
                
                //$params['L_NAME'.$o] = strip_tags($module_info['title']);
                $params['L_PAYMENTREQUEST_0_NAME'.$o] = strip_tags($module_info['title']);
                
                //$params['L_QTY'.$o] = 1;
                $params['L_PAYMENTREQUEST_0_QTY'.$o] = 1;
                
                //$params['L_AMT'.$o] = $paypal_express->format_raw($module_info['value']);
                $params['L_PAYMENTREQUEST_0_AMT'.$o] = $paypal_express->format_raw($module_info['value']);
                //$params['L_AMT'.$o] = $module_info['value'];
                
                //$item_amount += $module_info['value'];
                $item_amount += $paypal_express->format_raw($module_info['value']);
                $o++;

           } elseif ($module_info['code'] == 'ot_coupon') {
                
                //$params['L_NAME'.$o] = strip_tags($module_info['title']);
                $params['L_PAYMENTREQUEST_0_NAME'.$o] = strip_tags($module_info['title']);
                
                //$params['L_QTY'.$o] = 1;
                $params['L_PAYMENTREQUEST_0_QTY'.$o] = 1;
                
                //$params['L_AMT'.$o] = $paypal_express->format_raw($module_info['value']);
                $params['L_PAYMENTREQUEST_0_AMT'.$o] = $paypal_express->format_raw($module_info['value']);
                //$params['L_AMT'.$o] = $module_info['value'];
                
                //$item_amount += $module_info['value'];
                $item_amount += $paypal_express->format_raw($module_info['value']);
                $o++;
                
            } elseif ($module_info['code'] == 'ot_giftwrap') {
                
                //$params['L_NAME'.$o] = strip_tags($module_info['title']);
                $params['L_PAYMENTREQUEST_0_NAME'.$o] = strip_tags($module_info['title']);
                
                //$params['L_QTY'.$o] = 1;
                $params['L_PAYMENTREQUEST_0_QTY'.$o] = 1;
                
                //$params['L_AMT'.$o] = $paypal_express->format_raw($module_info['value']);
                $params['L_PAYMENTREQUEST_0_AMT'.$o] = $paypal_express->format_raw($module_info['value']);
                //$params['L_AMT'.$o] = $module_info['value'];
                
                $item_amount += $paypal_express->format_raw($module_info['value']);
                $o++;
                
            } elseif ($module_info['code'] == 'ot_shipping') {
                
                //$params['SHIPPINGAMT'] = $paypal_express->format_raw($module_info['value']);
                $params['PAYMENTREQUEST_0_SHIPPINGAMT'] = $paypal_express->format_raw($module_info['value']);
                //$params['SHIPPINGAMT'] = $module_info['value'];
                
            } elseif ($module_info['code'] == 'ot_total') {
                //$params['AMT'] = $paypal_express->format_raw($module_info['value']);
            }
        }
    }
/*    
    if (isset($_SESSION['shipping']['cost'])) {
        //$params['SHIPPINGAMT'] = $paypal_express->format_raw($_SESSION['shipping']['cost']);
        $params['PAYMENTREQUEST_0_SHIPPINGAMT'] = $paypal_express->format_raw($_SESSION['shipping']['cost']);
        //$params['SHIPPINGAMT'] = $_SESSION['shipping']['cost'];
        //$item_amount += $_SESSION['shipping']['cost'];
    }
*/   
    //$params['ITEMAMT'] = $item_amount;
    $params['PAYMENTREQUEST_0_ITEMAMT'] = $item_amount;
    //$params['ITEMAMT'] = $item_amount;
    
    //echo round($params['ITEMAMT'], 2).' + '.round($params['SHIPPINGAMT'], 2).' + '.round($tax_amount, 2); exit();
    //$params['AMT'] = round($params['ITEMAMT'], 2) + round($params['SHIPPINGAMT'], 2) + round($params['TAXAMT'], 2);
    $params['PAYMENTREQUEST_0_AMT'] = round($params['PAYMENTREQUEST_0_ITEMAMT'], 2) + round($params['PAYMENTREQUEST_0_SHIPPINGAMT'], 2) + round($params['PAYMENTREQUEST_0_TAXAMT'], 2);
    
    //$params['CURRENCYCODE'] = 'USD';
    


$fp = fopen(DIR_FS_CATALOG.'logs/paypal_logs/ppe-request-'.date('YmdHis').'.log', 'a');
        foreach ($params as $key => $value) {
            fwrite($fp, $key.' - '.$value."\r\n");
        }
	$ss = serialize($order);
        fwrite($fp, $ss);
        fclose($fp);

      $post_string = '';

      foreach ($params as $key => $value) {
        $post_string .= $key . '=' . urlencode(trim($value)) . '&';
      }
      $post_string = substr($post_string, 0, -1);
//var_dump($post_string);exit;
      $response = $paypal_express->sendTransactionToGateway($api_url, $post_string);
      $response_array = array();
//var_dump($response);exit;
      parse_str($response, $response_array);

      if (($response_array['ACK'] == 'Success') || ($response_array['ACK'] == 'SuccessWithWarning')) {
        tep_redirect($paypal_url . '&token=' . $response_array['TOKEN']);
      } else {
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, 'error_message=' . stripslashes($response_array['L_LONGMESSAGE0'].' - '.$response_array['L_SHORTMESSAGE0']), 'SSL'));
        //$checkout->die_error($response_array['L_LONGMESSAGE0'].' - '.$response_array['L_SHORTMESSAGE0'], true);
      }
      break;
  }

  tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));

  require(DIR_WS_INCLUDES . 'application_bottom.php');
  
  
  
  
function get_express_order_total() {
	
	    global $cart, $currencies, $order, $order_total_modules, $payment_modules, $shipping, $cc_id;
		
			
		
	    if (!tep_session_is_registered('customer_zone_id')) tep_session_register('customer_zone_id');

      if (is_numeric($order->delivery['zone_id']) && (int)$order->delivery['zone_id'] > 0) {
        $_SESSION['customer_zone_id'] = $customer_zone_id = (int)$order->delivery['zone_id'];
      } else {
        $_SESSION['customer_zone_id'] = $customer_zone_id = -1;
      }
      
      if (!tep_session_is_registered('customer_country_id')) tep_session_register('customer_country_id');
      
      $_SESSION['customer_country_id'] = $customer_country_id = (isset($order->delivery['country']['id']) && (int)$order->delivery['country']['id'] > 0 ? (int)$order->delivery['country']['id'] : -1);
	    if (isset($_SESSION['shipping'])) {
	      $ship = urldecode($_SESSION['shipping']);
	      
	      if (strpos($ship, '_') !== false) {
	        $ship = explode('_', $ship);
	      } else {
	        $ship = array($ship, '');
	      }


		$shipping = $_SESSION['shipping_quotes'][$ship[0]][$ship[1]];
		
		$shipping['id'] = $ship[0] . '_' . $ship[1];
      }

	    //$order = new order;
//echo '!!!<pre>'; print_r($order); echo '</pre>'; exit();
      //$order->delivery['firstname'] = $_POST['fn'];
      //$order->delivery['lastname'] = $_POST['ln'];
      //$order->delivery['street_address'] = $_POST['a1'];
      //$order->delivery['suburb'] = $_POST['a2'];
      //$order->delivery['city'] = $_POST['c'];
      //$order->delivery['postcode'] = $_POST['pc'];
      
      if (isset($order->delivery['country']['id']) && (int)$order->delivery['country']['id'] > 0) {
        $order->delivery['country'] = array('id' => (int)$order->delivery['country']['id']);
      } else {
        $order->delivery['country'] = array('id' => CHECKOUT_COUNTRY_DEFAULT_ID);
      }

      $country = tep_get_countries($order->delivery['country']['id'], true);
      
      $order->delivery['country'] = array('id' => $order->delivery['country']['id'], 
                                          'title' => $country['countries_name'], 
                                          'iso_code_2' => $country['countries_iso_code_2'], 
                                          'iso_code_3' => $country['countries_iso_code_3']);
                                          
      $order->delivery['country_id'] = $order->delivery['country']['id'];
      
      if ((is_numeric($order->delivery['zone_id']) && (int)$order->delivery['zone_id'] > 0) && trim($order->delivery['zone_id']) != '') {
        $order->delivery['state'] = tep_get_zone_name($order->delivery['country_id'], $order->delivery['zone_id'], $order->delivery['zone_id']);
        //$order->delivery['zone_id'] = (int)$_POST['s'];
      } else {
        $order->delivery['state'] = (!empty($order->delivery['zone_id']) ? trim($order->delivery['zone_id']) : '');
        $order->delivery['zone_id'] = '0';
      }
      $additional_totals = 0;
      
      if (CHECKOUT_CONTRIB_GIFTWRAP) {
        $additional_totals += $order->info['giftwrap_cost'];
      }
      
      if (isset($_POST['payment'])) {
        $payment = $_POST['payment'];
        $GLOBALS['payment'] = $payment;
      }
      
      if ($payment == 'quickpay') {
        if (!is_object($payment_modules)) {
          require_once(DIR_WS_CLASSES . 'payment.php');
          $payment_modules = new payment;
        }

        if (@is_object($GLOBALS[$payment])) {
          if (method_exists($GLOBALS[$payment], 'get_order_fee')) {
            global $quickpay_fee;
            $order->info['payment_method'] = MODULE_PAYMENT_QUICKPAY_TEXT_TITLE;
            $GLOBALS[$payment]->get_order_fee();
          }
        }
      }
      
	    if (isset($_POST['shipping'])) {
        if (isset($_SESSION['shipping_quotes'][$ship[0]][$ship[1]]['module']) && $_SESSION['shipping_quotes'][$ship[0]][$ship[1]]['module'] != '') {
  	      $order->info['shipping_method'] = $this->c($_SESSION['shipping_quotes'][$ship[0]][$ship[1]]['module'] . " (" . 
                                            $_SESSION['shipping_quotes'][$ship[0]][$ship[1]]['title'] . ")");
        
          $cost = $this->clean_float($_SESSION['shipping_quotes'][$ship[0]][$ship[1]]['cost']);
  	      $order->info['shipping_cost'] = tep_add_tax($cost, $_SESSION['shipping_quotes'][$ship[0]][$ship[1]]['tax']);
        }

        if ($order->info['shipping_cost'] > 0) {
          //Add the shipping cost only if the total is missing it
          if (DISPLAY_PRICE_WITH_TAX == 'true') {
            if (round($order->info['subtotal'], 2) + round($additional_totals, 2) + round($order->info['shipping_cost'], 2) == round($order->info['total'], 2) + round($order->info['shipping_cost'], 2)) {
              $order->info['total'] += $order->info['shipping_cost'];
            }
          } else {
            if (round($order->info['tax'], 2) + round($additional_totals, 2) + round($order->info['subtotal'], 2) + round($order->info['shipping_cost'], 2) == round($order->info['total'], 2) + round($order->info['shipping_cost'], 2)) {
              $order->info['total'] += $order->info['shipping_cost'];
            }
          }

          if (file_exists(DIR_WS_MODULES . 'shipping/' . $ship[0] . '.php')) {
            include_once(DIR_WS_MODULES . 'shipping/' . $ship[0] . '.php');
            $sm = new $ship[0];

            if ($sm->tax_class > 0) {
              $shipping_tax = tep_get_tax_rate($sm->tax_class, $customer_country_id, $customer_zone_id);
              $shipping_tax_description = tep_get_tax_description($sm->tax_class, $customer_country_id, $customer_zone_id);

              $order->info['tax'] += tep_calculate_tax($cost, $shipping_tax);
              $order->info['tax_groups']["$shipping_tax_description"] += tep_calculate_tax($cost, $shipping_tax);
              $order->info['total'] += tep_calculate_tax($cost, $shipping_tax);
            }

          }
        }
	    }

        
        $giftwrap_info =& $_SESSION['giftwrap_info'];
        $cc_id = $_SESSION['coupon'];


	    if (CHECKOUT_CONTRIB_CCGV) { 
        if (@method_exists($order_total_modules, 'pre_confirmation_check')) {
          $order_total_modules->pre_confirmation_check();
        } else { 
          //$this->log_error('CCGV support is enabled, but does not appear to be installed.', false);
        }
	    }
      
      if (CHECKOUT_CONTRIB_POINTS_AND_REWARDS) {
        if (tep_session_is_registered('customer_shopping_points_spending')) {
          $_POST['customer_shopping_points_spending'] = $_SESSION['customer_shopping_points_spending'];
        }
      }

	    $order_totals = $order_total_modules->process();
   
      return $order_totals;

  	}
  
?>