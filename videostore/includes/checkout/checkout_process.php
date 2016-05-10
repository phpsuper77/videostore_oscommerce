<?php 
/*
 * Dynamo Checkout by Dynamo Effects
 * Copyright 2008-2010 - Dynamo Effects.  All Rights Reserved
 * http://www.dynamoeffects.com
 */
  chdir('../..');



  function showPrice($new_desc, $product_price){
    for ($i=0;$i<count($new_desc);$i++){
      $sum = round(($product_price*$new_desc[$i]['final']/100), 4);
      $product_price = $product_price - $sum;
    }
    return round($product_price, 4);
  }
    
  @ini_set('zlib.output_compression', 'Off');
  define('CHARSET', 'utf-8');
  define('CHECKOUT_ONE_PAGE', true);

  include('includes/checkout/checkout.config.php');
  
  /* These are payment modules that require the user to go to the processors' websites
   * to complete the payment, so they should be done at the very end
   */
  $suspended_payment = array(
    'paypal', 'paypal_ipn', 'paypal_standard', 'paypal_ec', 'worldpay',
    'cybersource', 'protx_direct', 'gochargeit', 'securehosting', 'esec',
    'protx_form', 'alertpay', 'gateway_services_cc', 'gateway_services_ck',
    'eWayPayment', 'paypal_express', 'paypal_uk_express', 'sagepay_form',
    'bankpass', 'epaywindow', 'pmgate2shop', 'wiegant_ideal', 'sage_pay_form',
    'ccBillDynamicPricing', 'authorizenet_cc_sim', 'quickpay', 'dibs', 
    'payson', 'payson_guarantee', 'sveawebpay_creditcard', 'sveawebpay_invoice',
    'sveawebpay_partpay', 'sveawebpay_internetbank'
  );
  
  /* These are the PayPal IPN modules where we want the order to be saved as normal, 
   * but we don't want the order receipt to be sent until IPN confirmation of payment
   * has been received.
   */
  $paypal_modules = array('paypal', 'paypal_ipn', 'paypal_standard', 'epaywindow');

  define('FILENAME_CHECKOUT', 'checkout.php');
  define('FILENAME_CHECKOUT_CONFIRMATION', CHECKOUT_WS_INCLUDES . 'checkout_process.php');
  define('FILENAME_CHECKOUT_PAYMENT', CHECKOUT_WS_INCLUDES . 'checkout_process.php');
  define('FILENAME_CHECKOUT_PAYMENT_ADDRESS', CHECKOUT_WS_INCLUDES . 'checkout_process.php');
  define('FILENAME_CHECKOUT_PROCESS', CHECKOUT_WS_INCLUDES . 'checkout_process.php');
  define('FILENAME_CHECKOUT_SHIPPING', CHECKOUT_WS_INCLUDES . 'checkout_process.php');
  define('FILENAME_CHECKOUT_SHIPPING_ADDRESS', CHECKOUT_WS_INCLUDES . 'checkout_process.php');
  define('NO_REDIRECT', true);
  
  if (!ini_get('safe_mode')) {
  	@set_time_limit(0);
  	@ini_set("max_execution_time", 300);
  	@ini_set("default_socket_timeout", 300);
    @ini_set("magic_quotes_gpc", "0");
    @ini_set("output_buffering", "0");
    //@set_magic_quotes_runtime(1);
  }

  require_once('includes/application_top.php');
  
  /*------------------Don't touch it uses in tep_get_tax_rate function in general.php ---------------*/
  if (intval($whole[iswholesale]) != 1) $checkout_flag = true;
  /*------------------Don't touch it uses in tep_get_tax_rate function in general.php ---------------*/
  
  if (defined('MODULE_PAYMENT_USAEPAY_INPUT_MODE') && MODULE_PAYMENT_USAEPAY_INPUT_MODE != 'Local') {
    $suspended_payment[] = 'usaepay';
  }

  //Protx Form Support
  if ($_SESSION['checkout_payment'] == 'protx_form' && !empty($_GET['crypt'])) {
    $redirect = tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', 'module=protx_form&crypt=' . $_GET['crypt'], 'SSL');
    
    //Avoid Ultimate SEO URL problems
    $redirect = preg_replace('/&amp;/', '&', $redirect);
    
    header("Location: " . $redirect);
    
    die();
  }
  
  require_once(CHECKOUT_WS_LIB . 'checkout.class.php');

  $shipping_enabled = false;
  
  if (($cart->show_weight() > 0 || !CHECKOUT_DYNAMIC_SHIPPING) && strpos($cart->content_type, 'virtual') === false) {
    $shipping_enabled = true;
  }
  
  @error_reporting(E_ALL ^ E_NOTICE);
  @ini_set('error_reporting', E_ALL ^ E_NOTICE);
  @ini_set('display_errors', '1');

  $checkout = new checkout;
  set_error_handler(array ($checkout, 'error_handler'));

  if (CHECKOUT_CONTRIB_STS === true) {
    ob_end_clean();
  }

  include(DIR_WS_LANGUAGES . $language . '/checkout_process.php');
  if (file_exists(CHECKOUT_WS_INCLUDES . 'languages/' . $language . '/checkout.php')) {
    require_once(CHECKOUT_WS_INCLUDES . 'languages/' . $language . '/checkout.php');
  } else {
    require_once(CHECKOUT_WS_INCLUDES . 'languages/english/checkout.php');
  }
  
  require_once(DIR_WS_CLASSES . 'order.php');
  require_once(DIR_WS_CLASSES . 'order_total.php');
  require_once(DIR_WS_CLASSES . 'shipping.php');
  require_once(DIR_WS_CLASSES . 'payment.php');
  

  
  /*
   * Watch for the various types of error messages getting passed
   */
  
  $payment_module_error = $checkout->detect_payment_module_error();
  
  if ($payment_module_error) {
    
    header("Content-Type: text/html; charset=" . CHARSET);
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Mon, 24 Mar 1982 14:32:00 GMT");
    
    echo '<html><head></head><body>';
    flush();
    @ob_flush();
    usleep(50000);
    $checkout->die_error($payment_module_error, true);
  }

  if (!isset($_POST['action']) || ($_POST['action'] != 'checkout')) die();

  echo '<html><body>';
  flush();
  usleep(50000);

  /*
   * If shopping cart is empty, go away
   */



  if ($cart->count_contents() < 1) {

    echo '<script type="text/javascript"> parent.location.href = "' . tep_href_link(FILENAME_REAL_SHOPPING_CART) . '";</script>';
//echo '<script type="text/javascript"> alert( "' . tep_href_link(FILENAME_REAL_SHOPPING_CART) . '");</script>';

  }

  /*
   * Setup $order and $order_total_modules objects
   */
  
  $error = false;
  
  if (tep_session_is_registered('customer_id')) {
    $customer_status = 'LOGGED_IN_CUSTOMER';
  } else {
    $customer_status = 'NEW_CUSTOMER';
  }
  
  $process = true;

  /*
   * STEP 1: Parse incoming data
   */
   
  /*
   * Send initial processing status -- 10%
   */
  $checkout->send_status('10%', CHECKOUT_PROCESS_10);

  $email_address = tep_db_prepare_input($_POST['email_address']);
  $dob = tep_db_prepare_input($_POST['dob']);
  $gender = tep_db_prepare_input($_POST['gender']);

  $bill_firstname = tep_db_prepare_input($_POST['bill_firstname']);
  $bill_lastname = tep_db_prepare_input($_POST['bill_lastname']);
  $bill_company = tep_db_prepare_input($_POST['bill_company']);

/*  Added to let us know when someone attempts to checkout so that we can catch errors quicker
*/
  $NEW_txt_order= "New Attempted Order - ".$bill_lastname;
  @tep_mail("TVS", "8133805407@txt.att.net", "Sale", $NEW_txt_order, "TVS", STORE_OWNER_EMAIL_ADDRESS);


  $bill_street_address = tep_db_prepare_input($_POST['bill_street_address']);
  $bill_suburb = tep_db_prepare_input($_POST['bill_suburb']);
  $bill_postcode = tep_db_prepare_input($_POST['bill_postcode']);
  $bill_city = tep_db_prepare_input($_POST['bill_city']);
  $bill_country = tep_db_prepare_input($_POST['bill_country']);
  $bill_zone_id = $checkout->clean_integer($_POST['bill_state']);
  $bill_state = ($bill_zone_id > 0 ? tep_get_zone_name($bill_country, $bill_zone_id, '') : $_POST['bill_state']);

  if ($_POST['checkout-ship-same'] == '1' && !CHECKOUT_FORCE_BILLING_ADDRESS && $shipping_enabled) {
    $ship_firstname = tep_db_prepare_input($_POST['ship_firstname']);
    $ship_lastname = tep_db_prepare_input($_POST['ship_lastname']);
    $ship_company = tep_db_prepare_input($_POST['ship_company']);
    $ship_street_address = tep_db_prepare_input($_POST['ship_street_address']);
    $ship_suburb = tep_db_prepare_input($_POST['ship_suburb']);
    $ship_postcode = tep_db_prepare_input($_POST['ship_postcode']);
    $ship_city = tep_db_prepare_input($_POST['ship_city']);
    $ship_country = tep_db_prepare_input($_POST['ship_country']);
    $ship_zone_id = $checkout->clean_integer($_POST['ship_state']);
    $ship_state = ($ship_zone_id > 0 ? tep_get_zone_name($ship_country, $ship_zone_id, '') : $_POST['ship_state']);
  } else {
    $ship_firstname = $bill_firstname;
    $ship_lastname = $bill_lastname;
    $ship_company = $bill_company;
    $ship_street_address = $bill_street_address;
    $ship_suburb = $bill_suburb;
    $ship_postcode = $bill_postcode;
    $ship_city = $bill_city;
    $ship_state = $bill_state;
    $ship_country = $bill_country;
    $ship_zone_id = $bill_zone_id;
  }

  $shipping = $_POST['shipping'];
  $_SESSION['shipping'] = $shipping;
  $payment = $_POST['payment'];

  $telephone = tep_db_prepare_input($_POST['telephone']);
  
  if (tep_not_null($_POST['comments'])) {
    $comments = tep_db_prepare_input($_POST['comments']);
  }
  
  $newsletter = false;
  
  if (CHECKOUT_REQUIRE_NEWSLETTER && $_POST['newsletter'] == '1') {
    $newsletter = true;
  }

  $password = tep_db_prepare_input($_POST['password']);
  
  if (CHECKOUT_CONTRIB_HOW_DID_YOU_HEAR === true) {
    $source = tep_db_prepare_input($_POST['source']);
    if (isset($_POST['source_other'])) $source_other = tep_db_prepare_input($_POST['source_other']);
  }
  
  $accept_terms = true;
  
  if (CHECKOUT_CONTRIB_TERMS_CONDITIONS === true) {
    if ($_POST['accept_terms'] != '1') {
      $accept_terms = false;
    }
  }



  /*
   * STEP 2: Verify incoming data
   */
   
  $checkout->send_status('20%', CHECKOUT_PROCESS_20);

  $error = false;

  if (strlen($bill_firstname) < CHECKOUT_FIRST_NAME_MIN_LENGTH) {
    $error = true;
    $checkout->send_error('bill_firstname', CHECKOUT_FIRST_NAME_ERROR);
  }

  if (strlen($bill_lastname) < CHECKOUT_LAST_NAME_MIN_LENGTH) {
    $error = true;
    $checkout->send_error('bill_lastname', CHECKOUT_LAST_NAME_ERROR);
  }
  
  if (CHECKOUT_REQUIRE_DOB == 'R' && checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4)) == false) {
    $error = true;
    $checkout->send_error('dob', CHECKOUT_DOB_ERROR);
  }
  
  if (CHECKOUT_REQUIRE_GENDER == 'R' && !tep_session_is_registered('customer_id') && $gender != 'm' && $gender != 'f') {
    $error = true;
    $checkout->send_error('gender', CHECKOUT_GENDER_ERROR);
  }

  if (!isset($_SESSION['customer_id'])) {
    if (CHECKOUT_REQUIRE_EMAIL != 'H') {
      if (strlen($email_address) < CHECKOUT_EMAIL_ADDRESS_MIN_LENGTH) {
        if (CHECKOUT_REQUIRE_EMAIL == 'R' && CHECKOUT_CREATE_ACCOUNT) {
          $error = true;
          $checkout->send_error('email_address', CHECKOUT_EMAIL_ADDRESS_ERROR);
        } else {
          $customer_status = 'NO_ACCOUNT';
        }
      } elseif (tep_validate_email($email_address) == false) {
        if (CHECKOUT_REQUIRE_EMAIL == 'R' && CHECKOUT_CREATE_ACCOUNT) {
          $error = true;
          $checkout->send_error('email_address', $email_address . ' ' . CHECKOUT_EMAIL_ADDRESS_INVALID);
        } else {
          $customer_status = 'NO_ACCOUNT';
        }
      } else {
        $check_email_query = tep_db_query(
          "SELECT c.customers_email_address, " . 
            "c.customers_password, " .  
            "c.customers_id " . 
          "FROM " . TABLE_CUSTOMERS . " c " . 
          "LEFT JOIN " . TABLE_CUSTOMERS_INFO . " ci " . 
            "ON (c.customers_id = ci.customers_info_id) " . 
          "WHERE c.customers_email_address = '" . tep_db_input($email_address) . "'"
        );
        
        if (tep_db_num_rows($check_email_query) > 0) {
          $check_email = tep_db_fetch_array($check_email_query);
          //They're a customer, so log them in if their password is correct
          if ($password_error || !tep_validate_password($password, $check_email['customers_password'])) {
            if (CHECKOUT_CREATE_ACCOUNT) {
              if (!CHECKOUT_GENERATE_PASSWORD) {

                $error = true;
                $checkout->send_error('password', CHECKOUT_EMAIL_ADDRESS_ERROR_EXISTS);
                $checkout->die_error(CHECKOUT_EMAIL_ADDRESS_ERROR_EXISTS, true);

              } else {
                $error = true;
                $checkout->send_error('email_address', CHECKOUT_EMAIL_ADDRESS_ERROR_EXISTS_NO_ACCOUNT);
                $checkout->die_error(CHECKOUT_EMAIL_ADDRESS_ERROR_EXISTS_NO_ACCOUNT, true);
              }
           } else {
              $customer_status = 'NO_ACCOUNT';
            }
          } else {
            $customer_status = 'RETURN_CUSTOMER';
            $customer_real_id = $check_email['customers_id'];
            $customer_id = $check_email['customers_id'];
            $email_address = $check_email['customers_email_address'];
          }
        } else {
          if (CHECKOUT_CREATE_ACCOUNT) {
            $customer_status = 'NEW_CUSTOMER';
          } else {
            $customer_status = 'NO_ACCOUNT';
          }
        }
      }
    } else {
      $customer_status = 'NO_ACCOUNT';
    }
  } else {
    $email_query = tep_db_query("SELECT customers_email_address FROM " . TABLE_CUSTOMERS . " WHERE customers_id = " . (int)$_SESSION['customer_id'] . " LIMIT 1");
    if (tep_db_num_rows($email_query) > 0) {
      $email = tep_db_fetch_array($email_query);
      $email_address = $email['customers_email_address'];
    }
  }
  
  if (CHECKOUT_REQUIRE_COMPANY == 'R' && strlen($bill_company) < CHECKOUT_COMPANY_MIN_LENGTH) {
    $error = true;
    $checkout->send_error('bill_company', CHECKOUT_COMPANY_ERROR);
  }
  
  if (strlen($bill_street_address) < CHECKOUT_STREET_ADDRESS_MIN_LENGTH) {
    $error = true;
    $checkout->send_error('bill_street_address', CHECKOUT_STREET_ADDRESS_ERROR);
  }

  if (CHECKOUT_REQUIRE_SUBURB == 'R' && strlen($bill_suburb) < CHECKOUT_SUBURB_MIN_LENGTH) {
    $error = true;
    $checkout->send_error('bill_suburb', CHECKOUT_SUBURB_ERROR);
  }

  if (strlen($bill_city) < CHECKOUT_CITY_MIN_LENGTH) {
    $error = true;
    $checkout->send_error('bill_city', CHECKOUT_CITY_ERROR);
  }
  
  if (CHECKOUT_REQUIRE_STATE == 'R' && (int)$bill_zone_id < 1 && $bill_state == '') {
    $error = true;
    $checkout->send_error('bill_state', ENTRY_STATE_ERROR);
  }
 
  if (CHECKOUT_REQUIRE_POSTCODE == 'R' && strlen($bill_postcode) < CHECKOUT_POSTCODE_MIN_LENGTH) {
    $error = true;
    $checkout->send_error('bill_postcode', CHECKOUT_POST_CODE_ERROR);
  }
  
  if ($_POST['checkout-ship-same'] == '1' && $shipping_enabled) {
    if (strlen($ship_firstname) < CHECKOUT_FIRST_NAME_MIN_LENGTH) {
      $error = true;
      $checkout->send_error('ship_firstname', CHECKOUT_FIRST_NAME_ERROR);
    }
  
    if (strlen($ship_lastname) < CHECKOUT_LAST_NAME_MIN_LENGTH) {
      $error = true;
      $checkout->send_error('ship_lastname', CHECKOUT_LAST_NAME_ERROR);
    }
    
    if (CHECKOUT_REQUIRE_COMPANY == 'R' && strlen($ship_company) < CHECKOUT_COMPANY_MIN_LENGTH) {
      $error = true;
      $checkout->send_error('ship_company', CHECKOUT_COMPANY_ERROR);
    }
  
    if (strlen($ship_street_address) < CHECKOUT_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;
      $checkout->send_error('ship_street_address', CHECKOUT_STREET_ADDRESS_ERROR);
    }
    
    if (CHECKOUT_REQUIRE_SUBURB == 'R' && strlen($ship_suburb) < CHECKOUT_SUBURB_MIN_LENGTH) {
      $error = true;
      $checkout->send_error('ship_suburb', CHECKOUT_SUBURB_ERROR);
    }

    if (CHECKOUT_REQUIRE_POSTCODE == 'R' && strlen($ship_postcode) < CHECKOUT_POSTCODE_MIN_LENGTH) {
      $error = true;
      $checkout->send_error('ship_postcode', CHECKOUT_POST_CODE_ERROR);
    }

    if (strlen($ship_city) < CHECKOUT_CITY_MIN_LENGTH) {
      $error = true;
      $checkout->send_error('ship_city', CHECKOUT_CITY_ERROR);
    }
    
    if (CHECKOUT_REQUIRE_STATE == 'R' && (int)$ship_zone_id < 1 && $ship_state == '') {
      $error = true;
      $checkout->send_error('ship_state', ENTRY_STATE_ERROR);
    }

  }

  if (CHECKOUT_REQUIRE_TELEPHONE == 'R' && strlen($telephone) < CHECKOUT_TELEPHONE_MIN_LENGTH) {
    $error = true;
    $checkout->send_error('telephone', CHECKOUT_TELEPHONE_NUMBER_ERROR);
  }
  
  if (!tep_session_is_registered('customer_id') && CHECKOUT_CREATE_ACCOUNT && !CHECKOUT_GENERATE_PASSWORD) {
    $password_error = false;
    if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;
      $password_error = true;
      $checkout->send_error('password', CHECKOUT_PASSWORD_ERROR);
    }
  }

  if (CHECKOUT_CONTRIB_HOW_DID_YOU_HEAR === true) {
    if (REFERRAL_REQUIRED == 'true' && !is_numeric($source)) {
      $error = true;
      $checkout->send_error('how_did_you_hear', CHECKOUT_SOURCE_ERROR);
    }

    if (REFERRAL_REQUIRED == 'true' && DISPLAY_REFERRAL_OTHER == 'true' && $source == '9999' && !tep_not_null($source_other)) {
      $error = true;
      $checkout->send_error('how_did_you_hear', CHECKOUT_SOURCE_OTHER_ERROR);
    }
  }

  if ($error) {
     $checkout->die_error('Please go back and fix the fields that haven\'t been filled out correctly.', true);
  }

  if ($shipping_enabled) {
    if (($shipping == '' || strpos($shipping, '_') === false)) {
      $error = true;
      $checkout->send_error('shipping', CHECKOUT_SHIPPING_ERROR);
    } else {
      $shipping_selection = explode('_', $shipping);

      if (count($_SESSION['shipping_quotes'][$shipping_selection[0]][$shipping_selection[1]]) < 1) {
        $error = true;
        $checkout->send_error('shipping', CHECKOUT_SHIPPING_ERROR);
      }
    }
  }
  if ($payment == '') {
    $error = true;
    $checkout->send_error('payment', CHECKOUT_PAYMENT_ERROR);
  }
  
  if (!$accept_terms) {
    $error = true;
    $checkout->send_error('accept_terms', CHECKOUT_ACCEPT_TERMS_ERROR);
  }
    
  if ($error) {
    $checkout->die_error('Please go back and fix the fields that haven\'t been filled out correctly.', true);
  }

  /*
   * STEP 3: BUILD ORDER OBJECT
   */

  $checkout->send_status('30%', CHECKOUT_PROCESS_30);

  
  require('includes/classes/http_client.php');
  
  $shipping_module = explode('_', $shipping);

  $shipping = $_SESSION['shipping_quotes'][$shipping_module[0]][$shipping_module[1]];
  $shipping['id'] = $shipping_module[0] . '_' . $shipping_module[1];
  $shipping['title'] = $shipping['module'] . " (" . $shipping['title'] . ")";
  
  $paypal_wpp_express_checkout = false;
  
  if ($payment == 'paypal_wpp_ec') {
    $payment = 'paypal_wpp';
    $paypal_wpp_express_checkout = true;
  }
  
  /* Payment module is initialized here so that the order
     object has the correct order status */
  $payment_modules = new payment($payment);
  
  if ($paypal_wpp_express_checkout && @is_object($GLOBALS['paypal_wpp'])) {
    $GLOBALS['paypal_wpp']->dynamo_checkout = true;
  }
  
  $order = new order;

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();
  
  $free_shipping = false;
  
  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
    $pass = false;

    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
        if ($order->delivery['country_id'] == STORE_COUNTRY) $pass = true;
        break;
      case 'international':
        if ($order->delivery['country_id'] != STORE_COUNTRY) $pass = true;
        break;
      case 'both':
        $pass = true;
        break;
    }

    if ($pass && $order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) {
      $free_shipping = true;
    }
  }

  if (($cart->show_weight() > 0 && strpos($cart->content_type, 'virtual') === false) || !CHECKOUT_DYNAMIC_SHIPPING) {
    if ($free_shipping && $shipping['id'] == 'free_free') {
      include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
    } else {
      if (file_exists(DIR_WS_MODULES . 'shipping/' . $shipping_module[0] . '.php')) {
        include_once(DIR_WS_MODULES . 'shipping/' . $shipping_module[0] . '.php');
        $sm = new $shipping_module[0];

        if ($sm->tax_class > 0) {
          $shipping_tax = tep_get_tax_rate($sm->tax_class, $customer_country_id, $customer_zone_id);
          $shipping_tax_description = tep_get_tax_description($sm->tax_class, $customer_country_id, $customer_zone_id);
         
          $order->info['tax'] += tep_calculate_tax($order->info['shipping_cost'], $shipping_tax);
          $order->info['tax_groups']["$shipping_tax_description"] += tep_calculate_tax($order->info['shipping_cost'], $shipping_tax);
          $order->info['total'] += tep_calculate_tax($order->info['shipping_cost'], $shipping_tax);

          if (DISPLAY_PRICE_WITH_TAX == 'true') $order->info['shipping_cost'] += tep_calculate_tax($order->info['shipping_cost'], $shipping_tax);
        }
      }
    }
  }
  
  if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
  $_SESSION['cartID'] = $cart->cartID;

  /*
   * Modify the $order->info array
   */
  $order->info['comments'] = $comments;
  
  
  /*
   * Modify the $order->customer array
   */      
  $order->customer = array('firstname' => $bill_firstname,
                           'lastname' => $bill_lastname,
                           'company' => $bill_company,
                           'street_address' => $bill_street_address,
                           'suburb' => $bill_suburb,
                           'city' => $bill_city,
                           'postcode' => $bill_postcode,
                           'state' => (empty($bill_state) ? tep_get_zone_name($bill_country, $bill_zone_id) : $bill_state),
                           'zone_id' => $bill_zone_id,
                           'country' => $checkout->get_country_info($bill_country),
                           'format_id' => '2',
                           'telephone' => $telephone,
                           'email_address' => $email_address);
  /*
   * Modify the $order->delivery array
   */   
  $order->delivery = array('firstname' => $ship_firstname,
                           'lastname' => $ship_lastname,
                           'company' => $ship_company,
                           'street_address' => $ship_street_address,
                           'suburb' => $ship_suburb,
                           'city' => $ship_city,
                           'postcode' => $ship_postcode,
                           'state' => (empty($ship_state) ? tep_get_zone_name($ship_country, $ship_zone_id) : $ship_state),
                           'zone_id' => $ship_zone_id,
                           'country' => $checkout->get_country_info($ship_country),
                           'country_id' => $ship_country,
                           'format_id' => '2');

  /*
   * Modify the $order->billing array
   */   
  $order->billing = array('firstname' => $bill_firstname,
                          'lastname' => $bill_lastname,
                          'company' => $bill_company,
                          'street_address' => $bill_street_address,
                          'suburb' => $bill_suburb,
                          'city' => $bill_city,
                          'postcode' => $bill_postcode,
                          'state' => (empty($bill_state) ? tep_get_zone_name($bill_country, $bill_zone_id) : $bill_state),
                          'zone_id' => $bill_zone_id,
                          'country' => $checkout->get_country_info($bill_country),
                          'country_id' => $bill_country,
                          'format_id' => '2');

  if ($payment == 'moneyorder') {
    $order->info['order_status'] = 9;
  }
                          
  if ($payment == 'po') {
    $order->info['order_status'] = 10;
  }
                          
  /*
   * STEP 4: LOAD ORDER TOTALS
   */
  $checkout->send_status('40%', CHECKOUT_PROCESS_40);
  
  if (CHECKOUT_CONTRIB_POINTS_AND_REWARDS === true) {
    $customer_shopping_points = 0;
    if ($_POST['customer_shopping_points_spending'] == '1') {
      $customer_shopping_points = tep_get_shopping_points();
      $_POST['customer_shopping_points_spending'] = $customer_shopping_points;
    }
  }

   
  $order_total_modules = new order_total;
  $order_totals = $order_total_modules->process();



//Log order into holding tables before checkout is passed to payment processor

$holding_order_totals = $order_totals;

//echo '<pre>'; print_r($holding_order_totals); echo '</pre>'; //exit();

	  $sql_data_array = array('customers_id' => $customer_id,
	                          'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
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
	                          'delivery_street_address' => $order->delivery['street_address'], 
	                          'delivery_suburb' => $order->delivery['suburb'], 
	                          'delivery_city' => $order->delivery['city'], 
	                          'delivery_postcode' => $order->delivery['postcode'], 
	                          'delivery_state' => $order->delivery['state'], 
	                          'delivery_country' => $order->delivery['country']['title'],
	                          'delivery_address_format_id' => $order->delivery['format_id'], 
				  'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'], 
	                          'billing_street_address' => $order->billing['street_address'], 
	                          'billing_suburb' => $order->billing['suburb'], 
	                          'billing_city' => $order->billing['city'], 
	                          'billing_postcode' => $order->billing['postcode'], 
	                          'billing_state' => $order->billing['state'], 
	                          'billing_country' => $order->billing['country']['title'],
	                          'billing_address_format_id' => $order->billing['format_id'],
	                          'payment_method' => $order->info['payment_method'], 
	                          /*'cc_type' => $order->info['cc_type'], 
	                          'cc_owner' => $order->info['cc_owner'], 
	                          'cc_number' => $order->info['cc_number'], 
	                          'cc_expires' => $order->info['cc_expires'],*/ 
	                          'date_purchased' => 'now()', 
	                          //'orders_status' => DEFAULT_ORDERS_STATUS_ID, 
	                          'orders_status' => '0', 
	                          'comments' => $order->info['comments'], 
	                          'currency' => $order->info['currency'], 
	                          'currency_value' => $order->info['currency_value']);
//echo '<pre>'; print_r($sql_data_array); echo '</pre>'; //exit();
			 tep_db_perform(TABLE_HOLDING_ORDERS, $sql_data_array);
			 $insert_id = tep_db_insert_id();
			$_SESSION['insert_order_id'] = $insert_id;
            
         
            
        $fp = fopen(DIR_FS_CATALOG.'logs/paypal_logs/ho-'.date('YmdHis').'.log', 'a');
        fwrite($fp, "TABLE_HOLDING_ORDERS:\r\n");
        foreach ($sql_data_array as $key => $value) {
            fwrite($fp, $key.' - '.$value."\r\n");
        }
        //fclose($fp);

		   for ($i=0, $n=sizeof($holding_order_totals); $i<$n; $i++) {
		   	      $sql_data_array = array('orders_id' => $insert_id,
		   	                              'title' => $holding_order_totals[$i]['title'],
		   	                              'text' => $holding_order_totals[$i]['text'],
		   	                              'value' => $holding_order_totals[$i]['value'], 
		   	                              'class' => $holding_order_totals[$i]['code'], 
		   	                              'sort_order' => $holding_order_totals[$i]['sort_order']);
                                          
                fwrite($fp, "TABLE_HOLDING_ORDERS_TOTAL-".$i.":\r\n");
                    foreach ($sql_data_array as $key => $value) {
                    fwrite($fp, $key.' - '.$value."\r\n");
                }
//echo '<pre>'; print_r($sql_data_array); echo '</pre>'; //exit();
		   tep_db_perform(TABLE_HOLDING_ORDERS_TOTAL, $sql_data_array);
			 }

       for ($i=0; $i<sizeof($order->products); $i++) {

       $sql_data_array = array('orders_id' => $insert_id, 
                             'products_id' => tep_get_prid($order->products[$i]['id']), 
                             'products_model' => $order->products[$i]['model'], 
                             'products_name' => $order->products[$i]['name'], 
                             'products_price' => $order->products[$i]['price'], 
                             'final_price' => $order->products[$i]['final_price'], 
                             'products_tax' => $order->products[$i]['tax'], 
                             'products_quantity' => $order->products[$i]['qty']);

       tep_db_perform(TABLE_HOLDING_ORDERS_PRODUCTS, $sql_data_array);
       $order_products_id = tep_db_insert_id();
       
       fwrite($fp, "TABLE_HOLDING_ORDERS_PRODUCTS-".$i.":\r\n");
       foreach ($sql_data_array as $key => $value) {
        fwrite($fp, $key.' - '.$value."\r\n");
       }
       
    	 if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
       	for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
       		$attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
       		$attributes_values = tep_db_fetch_array($attributes);
       		$sql_data_array = array('orders_id' => $insert_id, 
       	                        'orders_products_id' => $order->products[$i]['id'], 
       	                        'products_options' => $attributes_values['products_options_name'],
       	                        'products_options_values' => $attributes_values['products_options_values_name'], 
       	                        'options_values_price' => $attributes_values['options_values_price'], 
       	                        'price_prefix' => $attributes_values['price_prefix']);
//echo '<pre>'; print_r($sql_data_array); echo '</pre>'; //exit();       	
       	tep_db_perform(TABLE_HOLDING_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
        
          fwrite($fp, "TABLE_HOLDING_ORDERS_PRODUCTS_ATTRIBUTES-".$j.":\r\n");
          foreach ($sql_data_array as $key => $value) {
            fwrite($fp, $key.' - '.$value."\r\n");
          }
        
      	}
      }
    }
   

    
    fclose($fp);
          
//End log order before payment


  /*
   * STEP 5: PROCESS PAYMENT METHOD
   */
  
  $checkout->send_status('50%', CHECKOUT_PROCESS_50); 

  if ($payment != 'paypal_express' && $payment != 'paypal_uk_express' && !$paypal_wpp_express_checkout) {
    $payment_modules->pre_confirmation_check();
  }

  /*
   * If this is paypal, don't process the payment until the order has been stored
   */
  if ($order->info['total'] > 0) {
    if (!in_array($payment, $suspended_payment) && !$paypal_wpp_express_checkout) {
      if ($payment == 'usaepay' && defined('MODULE_PAYMENT_USAEPAY_INPUT_MODE') && MODULE_PAYMENT_USAEPAY_INPUT_MODE == 'Local') {
        $_POST['cc_owner'] = $bill_firstname . " " . $bill_lastname;
        $_POST['cc_expires'] = $_POST['usaepay_cc_expires_month'] . $_POST['usaepay_cc_expires_year'];
        $_POST['cc_type'] = $GLOBALS[$payment]->cc_card_type;
        $_POST['cc_number'] = trim($_POST['usaepay_cc_number']);
        $_POST['cc_cvv'] = trim($_POST['usaepay_cc_cvv']);
        $GLOBALS[$payment]->cc_card_number = trim($GLOBALS[$payment]->cc_card_number);
      }
      
      $payment_modules->before_process();
    } else {
      if ((!is_object($GLOBALS[$payment]) || !$GLOBALS[$payment]->enabled) && !$paypal_wpp_express_checkout) {
        $checkout->die_error('That payment option couldn\'t be initialized.  Please select another.', true);
      }
    }
  }
//$checkout->die_error('Hello', true);
  /*
   * STEP 6: CREATE CUSTOMER IF NECESSARY
   */
  if ($customer_status == 'NEW_CUSTOMER') {
    $checkout->send_status('60%', CHECKOUT_PROCESS_60);

    $sql_data_array = array('customers_firstname' => $bill_firstname,
                            'customers_lastname' => $bill_lastname,
                            'customers_email_address' => $email_address,
                            'customers_telephone' => $telephone,
                            'customers_newsletter' => ($newsletter ? '1' : '0'));

    if (CHECKOUT_REQUIRE_GENDER != 'H') $sql_data_array['customers_gender'] = $gender;
    if (CHECKOUT_REQUIRE_DOB != 'H') $sql_data_array['customers_dob'] = tep_date_raw($dob);
    
    if (CHECKOUT_GENERATE_PASSWORD) {
      $password = tep_create_random_value(CHECKOUT_PASSWORD_MIN_LENGTH);
    }
    
    $sql_data_array['customers_password'] = tep_encrypt_password($password);

    tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

    $customer_id = tep_db_insert_id();
    $customer_real_id = $customer_id;
    
    $sql_data_array = array('customers_id' => $customer_id,
                            'entry_firstname' => $bill_firstname,
                            'entry_lastname' => $bill_lastname,
                            'entry_company' => $bill_company,
                            'entry_street_address' => $bill_street_address,
                            'entry_postcode' => $bill_postcode,
                            'entry_city' => $bill_city,
                            'entry_country_id' => $bill_country,
                            'entry_suburb' => $bill_suburb);
                            
    if (CHECKOUT_REQUIRE_STATE != 'H') {
      if ($bill_zone_id > 0) {
        $sql_data_array['entry_zone_id'] = $bill_zone_id;
        $sql_data_array['entry_state'] = '';
      } else {
        $sql_data_array['entry_zone_id'] = '0';
        $sql_data_array['entry_state'] = $bill_state;
      }
    }

    tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

    $address_id = tep_db_insert_id();
    
    $billto = $address_id;
    $sendto = $address_id;
    if (!tep_session_is_registered('billto')) tep_session_register('billto');
    if (!tep_session_is_registered('sendto')) tep_session_register('sendto');

    tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

    tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

    if ($_POST['checkout-ship-same'] != '1') {
      $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $ship_firstname,
                              'entry_lastname' => $ship_lastname,
                              'entry_company' => $ship_company,
                              'entry_street_address' => $ship_street_address,
                              'entry_postcode' => $ship_postcode,
                              'entry_city' => $ship_city,
                              'entry_country_id' => $ship_country);

      $sql_data_array['entry_suburb'] = $ship_suburb;

      if ($ship_zone_id > 0) {
        $sql_data_array['entry_zone_id'] = $ship_zone_id;
        $sql_data_array['entry_state'] = '';
      } else {
        $sql_data_array['entry_zone_id'] = '0';
        $sql_data_array['entry_state'] = $ship_state;
      }
      
      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
      $sendto = tep_db_insert_id();
    }

    $name = $firstname . ' ' . $lastname;
    
    include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

    $email_data = array();
    
    $email_data['POINTS_AND_REWARDS'] = '';
    
    if (CHECKOUT_CONTRIB_POINTS_AND_REWARDS === true) {
      if (NEW_SIGNUP_POINT_AMOUNT > 0) {
        tep_add_welcome_points($customer_id);
        
        $points_account .= '<a href="' . tep_href_link(FILENAME_MY_POINTS, '', 'SSL') . '"><b><u>' . EMAIL_POINTS_ACCOUNT . '</u></b></a>.';
        $points_faq .= '<a href="' . tep_href_link(FILENAME_MY_POINTS_HELP, '', 'NONSSL') . '"><b><u>' . EMAIL_POINTS_FAQ . '</u></b></a>.';
        $email_data['POINTS_AND_REWARDS'] = sprintf(EMAIL_WELCOME_POINTS , $points_account, number_format(NEW_SIGNUP_POINT_AMOUNT,POINTS_DECIMAL_PLACES), $currencies->format(tep_calc_shopping_pvalue(NEW_SIGNUP_POINT_AMOUNT)),$points_faq) ."\n\n";
      }
    }
    
    $email_data['CCGV'] = '';

    if (CHECKOUT_REQUIRE_GENDER != 'H' && !empty($gender)) {
       if ($gender == 'm') {
         $email_data['SALUTATION'] = sprintf(EMAIL_GREET_MR, $bill_lastname);
       } else {
         $email_data['SALUTATION'] = sprintf(EMAIL_GREET_MS, $bill_lastname);
       }
    } else {
      $email_data['SALUTATION'] = sprintf(EMAIL_GREET_NONE, $bill_firstname);
    }
    
    $email_data['TELEPHONE'] = $telephone;
    $email_data['LOGIN_INFO'] = sprintf(CHECKOUT_EMAIL_LOGIN_INFORMATION, $email_address, $password); 
    $email_data['STORE_NAME'] = STORE_NAME;
    $email_data['STORE_OWNER_EMAIL_ADDRESS'] = STORE_OWNER_EMAIL_ADDRESS;
    $email_data['SUBJECT'] = EMAIL_SUBJECT;
    $email_data['NAME'] = $bill_firstname . ' ' . $bill_lastname;
    $email_data['EMAIL_ADDRESS'] = $email_address;
    $email_data['EMAIL_RECIPIENT'] = $email_address;
    $email_data['SENDER_NAME'] = STORE_OWNER;
    $email_data['SENDER_EMAIL'] = STORE_OWNER_EMAIL_ADDRESS;
    
    if (!empty($email_address)) {
      $checkout->send_email('welcome', $email_data);
    }
  }
  
  if (CHECKOUT_CONTRIB_HOW_DID_YOU_HEAR === true && $customer_status != 'NO_ACCOUNT' && $customer_id > 0) {
    tep_db_query("UPDATE " . TABLE_CUSTOMERS_INFO . " SET customers_info_source_id = '" . (int)$source . "' WHERE customers_info_id = " . (int)$customer_id . " LIMIT 1");

    if ($source == '9999') {
      tep_db_perform(TABLE_SOURCES_OTHER, array('customers_id' => (int)$customer_id, 'sources_other_name' => tep_db_input($source_other)));
    }
  }
  
  if ($payment == 'nochex_apc') {
    if (CHECKOUT_AUTO_LOGIN && $customer_status != 'NO_ACCOUNT' && !tep_session_is_registered('customer_id')) {
      tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET . " WHERE customers_id = " . (int)$customer_id);
      tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " WHERE customers_id = " . (int)$customer_id);

      $checkout->do_login($email_address, $password);
    }
    
    if (!tep_session_is_registered('billto') || (int)$billto < 1) {
      tep_session_register('billto');
      
      $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $bill_firstname,
                              'entry_lastname' => $bill_lastname,
                              'entry_company' => $bill_company,
                              'entry_street_address' => $bill_street_address,
                              'entry_postcode' => $bill_postcode,
                              'entry_city' => $bill_city,
                              'entry_country_id' => $bill_country,
                              'entry_suburb' => $bill_suburb);
                              
      if (CHECKOUT_REQUIRE_STATE != 'H') {
        if ($bill_zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $bill_zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $bill_state;
        }
      }

      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
      
      $billto = tep_db_insert_id();
    }
    if (!tep_session_is_registered('sendto') || (int)$sendto < 1) {
      tep_session_register('sendto');
      if ($_POST['checkout-ship-same'] != '1') {
        $sql_data_array = array('customers_id' => $customer_id,
                                'entry_firstname' => $ship_firstname,
                                'entry_lastname' => $ship_lastname,
                                'entry_company' => $ship_company,
                                'entry_street_address' => $ship_street_address,
                                'entry_postcode' => $ship_postcode,
                                'entry_city' => $ship_city,
                                'entry_country_id' => $ship_country);

        $sql_data_array['entry_suburb'] = $ship_suburb;

        if ($bill_zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $ship_zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $ship_state;
        }
        
        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
        $sendto = tep_db_insert_id();
      } else {
        $sendto = $billto;
      }
    }
    
    if (!tep_session_is_registered('payment')) tep_session_register('payment');
    
    echo '<script type="text/javascript">parent.location.href="' . tep_href_link(FILENAME_CHECKOUT_NOCHEX_APC,'','SSL'). '";</script>';
    exit;
  }

  /*
   * STEP 7: SAVE ORDER
   */

  $checkout->send_status('70%', CHECKOUT_PROCESS_70);
  
  if (!in_array($payment, $suspended_payment) && tep_session_is_registered('customer_id')) {
    if ($order->info['payment_method'] == 'Purchase Order Account') {
  		$check_credit = tep_db_query("select customers_credit_left from " . TABLE_CUSTOMERS . " where customers_id ='" . (int)$customer_id . "'");
  		$credit = tep_db_fetch_array($check_credit);
  		$subamt = $credit['customers_credit_left'] - $order->info['total'];
  		$credit_tot = tep_db_query("select customers_credit_amount from " . TABLE_CUSTOMERS . " where customers_id ='" . (int)$customer_id . "'");
  		$credit_tot = tep_db_fetch_array($credit_tot);
  		$credit_amount = $credit_tot['customers_credit_amount'];
  		tep_db_query("update " . TABLE_CUSTOMERS . " set customers_credit_left ='" . $subamt . "' where customers_id = '" . (int)$customer_id . "'");
  	}
  }
  
  if ($payment == 'cc' && array_key_exists('cc_expires_month', $_POST) && array_key_exists('cc_expires_year', $_POST)) {
    $order->info['cc_expires'] = $_POST['cc_expires_month'] . $_POST['cc_expires_year'];
  }
  
  if ($payment == 'cc' && trim($order->info['cc_type']) == '') {
    $order->info['cc_type'] = $GLOBALS[$payment]->cc_card_type;
  }
  
  if ((!in_array($payment, $suspended_payment) || in_array($payment, $paypal_modules)) && !$paypal_wpp_express_checkout) {
    $database = array('ORDERS' => TABLE_ORDERS,
                      'ORDERS_PRODUCTS' => TABLE_ORDERS_PRODUCTS,
                      'ORDERS_PRODUCTS_ATTRIBUTES' => TABLE_ORDERS_PRODUCTS_ATTRIBUTES,
                      'ORDERS_PRODUCTS_DOWNLOAD' => TABLE_ORDERS_PRODUCTS_DOWNLOAD,
                      'ORDERS_STATUS_HISTORY' => TABLE_ORDERS_STATUS_HISTORY,
                      'ORDERS_TOTAL' => TABLE_ORDERS_TOTAL);
                      
  } else {
    $database = array('ORDERS' => TABLE_CHECKOUT_ORDERS,
                      'ORDERS_PRODUCTS' => TABLE_CHECKOUT_ORDERS_PRODUCTS,
                      'ORDERS_PRODUCTS_ATTRIBUTES' => TABLE_CHECKOUT_ORDERS_PRODUCTS_ATTRIBUTES,
                      'ORDERS_PRODUCTS_DOWNLOAD' => TABLE_CHECKOUT_ORDERS_PRODUCTS_DOWNLOAD,
                      'ORDERS_STATUS_HISTORY' => TABLE_CHECKOUT_ORDERS_STATUS_HISTORY,
                      'ORDERS_TOTAL' => TABLE_CHECKOUT_ORDERS_TOTAL);
  }

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
                          'payment_method' => $GLOBALS[$payment]->title,  //$order->info['payment_method'], 
                          'cc_type' => $order->info['cc_type'], 
                          'cc_owner' => $order->info['cc_owner'], 
                          'cc_number' => $order->info['cc_number'], 
                          'cc_expires' => $order->info['cc_expires'], 
                          'date_purchased' => 'now()', 
                          'last_modified' => 'null',
                          'orders_status' => $order->info['order_status'], 
                          'currency' => $order->info['currency'], 
                          'customers_referer_url' => $referer_url_real,
                          'comments_slip' => stripslashes($order->info['comments_slip']),
                          'iswholesale' => intval($whole['iswholesale']),
                          'currency_value' => $order->info['currency_value']);
/* Log order into holding tables before checkout is passed to payment processor */
/*   tep_db_perform(TABLE_HOLDING_ORDERS, $sql_data_array); */
/* End log order before payment */                     
  if ($payment == 'po' && isset($order->info['purchase_order_number'])) {
    $sql_data_array['purchase_order_number'] = $order->info['purchase_order_number'];
  }
                          
  if (in_array($payment, array('cc', 'cc_cvv2')) && !empty($order->info['cc_cvv2'])) {
    $sql_data_array['cc_cvv2'] = $order->info['cc_cvv2'];
  }

  if (CHECKOUT_CONTRIB_IPISP === true) {
    $ip = $_SERVER["REMOTE_ADDR"];
    $client = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
    $str = preg_split("/\./", $client);
    $i = count($str);
    $x = $i - 1;
    $n = $i - 2;
    $isp = $str[$n] . "." . $str[$x];
    
    $sql_data_array['ipaddy'] = $ip;
		$sql_data_array['ipisp'] = $isp;
  }
  
  if (CHECKOUT_CONTRIB_UNIQUE_ORDER_NUMBER === true) {
    $unique_order_number = date("mdYHis");
    $sql_data_array['orders_id'] = $unique_order_number;
  }
                   
  tep_db_perform($database['ORDERS'], $sql_data_array);
  
  $insert_id = tep_db_insert_id();
  
  tep_db_query(
    "UPDATE " . $database['ORDERS'] . " SET last_modified = NULL WHERE orders_id = " . $insert_id . " LIMIT 1"
  );

  if (CHECKOUT_CONTRIB_UNIQUE_ORDER_NUMBER === true) {
    $insert_id = $unique_order_number;
  }
  
  if ($payment == 'paypal_ipn') {
    global $cart_PayPal_IPN_ID;
    $_SESSION['cart_PayPal_IPN_ID'] = $cartID . '-' . $insert_id;
    $cart_PayPal_IPN_ID = $_SESSION['cart_PayPal_IPN_ID'];
  }
  
  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => $order_totals[$i]['title'],
                            'text' => $order_totals[$i]['text'],
                            'value' => $order_totals[$i]['value'], 
                            'class' => $order_totals[$i]['code'], 
                            'sort_order' => $order_totals[$i]['sort_order']);
                            
    tep_db_perform($database['ORDERS_TOTAL'], $sql_data_array);
  }
  
  if (!in_array($payment, $suspended_payment) && tep_session_is_registered('customer_id')) {
    $checkout->redeem_points();
  }

  $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
  $sql_data_array = array('orders_id' => $insert_id, 
                          'orders_status_id' => $order->info['order_status'], 
                          'date_added' => 'now()', 
                          'customer_notified' => $customer_notification,
                          'comments' => $order->info['comments']);
                          
  tep_db_perform($database['ORDERS_STATUS_HISTORY'], $sql_data_array);
 
  if(CHECKOUT_CONTRIB_DISCOUNT_COUPON === true && tep_session_is_registered('coupon') && is_object($order->coupon) && !in_array($payment, $suspended_payment)) {
	  $sql_data_array = array( 'coupons_id' => $order->coupon->coupon['coupons_id'],
                             'orders_id' => $insert_id );
                             
	  tep_db_perform( TABLE_DISCOUNT_COUPONS_TO_ORDERS, $sql_data_array );
  }

// initialized for the email confirmation
  $products_ordered = '';
  $subtotal = 0;
  $total_tax = 0;

  for ($i=0; $i<sizeof($order->products); $i++) {
    if (!in_array($payment, $suspended_payment)) {
      $checkout->reduce_stock($order->products[$i]);
      $sql_query = "SELECT vendors_item_cost, vendors_product_payment_type, vendors_consignment, vendors_consignment_percentage, vendors_royalty , vendors_royalty_percentage FROM products_to_vendors where products_id=".tep_get_prid($order->products[$i]['id']." limit 0,1");

      $prod_vendor = tep_db_fetch_array(tep_db_query($sql_query));
          
      switch($prod_vendor['vendors_product_payment_type']){
      case "1":
        $final_prod_vendor = $prod_vendor['vendors_item_cost'];		
      break;

      case "2":
        if (intval($prod_vendor['vendors_consignment'])==1) $final_prod_vendor = $prod_vendor['vendors_item_cost'];				
        if (intval($prod_vendor['vendors_consignment'])==2) $final_prod_vendor = ($order->products[$i]['price']/100)*$prod_vendor['vendors_consignment_percentage'];
        if (intval($prod_vendor['vendors_consignment'])==3) {
          $pp_price = showPrice($new_disc, $order->products[$i]['final_price']);
          $final_prod_vendor = round(($pp_price*$prod_vendor['vendors_consignment_percentage']/100), 4);
          //$final_prod_vendor = ($order->products[$i]['final_price']/100)*$prod_vendor['vendors_consignment_percentage'];
          //mail('customerservice@travelvideostore.com','CONSIGNMENT PERCENTAGE DISCOUNT', 'Order number: '.$insert_id.'<br>Discount('.$prod_vendor['vendors_consignment_percentage'].'%) for product "'.$order->products[$i]['name'].'" is '.$final_prod_vendor);
        }
      break;

      case "3":
        if (intval($prod_vendor['vendors_royalty'])==1) $final_prod_vendor = $prod_vendor['vendors_item_cost'];				
        if (intval($prod_vendor['vendors_royalty'])==2) $final_prod_vendor = ($order->products[$i]['price']/100)*$prod_vendor['vendors_royalty_percentage'];
        if (intval($prod_vendor['vendors_royalty'])==3) {
          $pp_price = showPrice($new_disc, $order->products[$i]['final_price']);
          $final_prod_vendor = round(($pp_price*$prod_vendor['vendors_royalty_percentage']/100), 4);
          //$final_prod_vendor = ($order->products[$i]['final_price']/100)*$prod_vendor['vendors_royalty_percentage'];
          //mail('customerservice@travelvideostore.com','ROYALTY PERCENTAGE DISCOUNT', 'Order number: '.$insert_id.'<br>Discount('.$prod_vendor['vendors_royalty_percentage'].'%) for product "'.$order->products[$i]['name'].'" is '.$final_prod_vendor);
          //mail('x0661t@d-net.kiev.ua','ROYALTY PERCENTAGE DISCOUNT', 'Order number: '.$insert_id.'<br>Discount('.$prod_vendor['vendors_royalty_percentage'].'%) for product "'.$order->products[$i]['name'].'" is '.$final_prod_vendor);
        }
      break;
      default: 
        $prod_vendor['vendors_product_payment_type'] = '1';
      break;
    }

      
      
    }
    
    $sql_data_array = array('orders_id' => $insert_id, 
                            'products_id' => tep_get_prid($order->products[$i]['id']), 
                            'products_model' => $order->products[$i]['model'], 
                            'products_name' => $order->products[$i]['name'], 
                            'products_price' => $order->products[$i]['price'], 
                            'final_price' => $order->products[$i]['final_price'], 
                            'products_sale_type' => $prod_vendor['vendors_product_payment_type'],
                            'products_item_cost' => $final_prod_vendor,
                            'products_tax' => $order->products[$i]['tax'], 
                            'products_quantity' => $order->products[$i]['qty']);
                            
    tep_db_perform($database['ORDERS_PRODUCTS'], $sql_data_array);
    
    $order_products_id = tep_db_insert_id();

    if (CHECKOUT_CONTRIB_CCGV === true && !in_array($payment, $suspended_payment)) {
      $order_total_modules->update_credit_account($i);
    }

    $attributes_exist = '0';
    $products_ordered_attributes = '';
    if ($order->products[$i]['attributes']) {
      $attributes_exist = '1';
      for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
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
        
        $attr_name = $attributes_values['products_options_name'];

        if (CHECKOUT_CONTRIB_OPTIONS_TYPE === true) {
          if ($attributes_values['products_options_id'] == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {
            $attr_name_sql_raw = 'SELECT po.products_options_name FROM ' .
              TABLE_PRODUCTS_OPTIONS . ' po, ' .
              TABLE_PRODUCTS_ATTRIBUTES . ' pa WHERE ' .
              ' pa.products_id="' . tep_get_prid($order->products[$i]['id']) . '" AND ' .
              ' pa.options_id="' . $order->products[$i]['attributes'][$j]['option_id'] . '" AND ' .
              ' pa.options_id=po.products_options_id AND ' .
              ' po.language_id="' . $languages_id . '" ';
            $attr_name_sql = tep_db_query($attr_name_sql_raw);
            if ($arr = tep_db_fetch_array($attr_name_sql)) {
              $attr_name  = $arr['products_options_name'];
            }
          }
        }

        $sql_data_array = array('orders_id' => $insert_id, 
                                'orders_products_id' => $order_products_id, 
                                'products_options' => $attr_name,
                                'products_options_values' => $order->products[$i]['attributes'][$j]['value'], 
                                'options_values_price' => $attributes_values['options_values_price'], 
                                'price_prefix' => $attributes_values['price_prefix']);
        tep_db_perform($database['ORDERS_PRODUCTS_ATTRIBUTES'], $sql_data_array);

        if (DOWNLOAD_ENABLED == 'true') {
          $sql_data_array = array('orders_id' => $insert_id, 
                                  'orders_products_id' => $order_products_id, 
                                  'orders_products_filename' => $attributes_values['products_attributes_filename'], 
                                  'download_maxdays' => $attributes_values['products_attributes_maxdays'], 
                                  'download_count' => $attributes_values['products_attributes_maxcount']);
                                  
          tep_db_perform($database['ORDERS_PRODUCTS_DOWNLOAD'], $sql_data_array);
        }
        $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $order->products[$i]['attributes'][$j]['value'];
      }
    }
    
    //------INSERT VALUES INTO THE DISCOUNT_CUSTOMERS----//
    if ((tep_session_is_registered('couponfield')) && (tep_not_null($_SESSION["couponfield"]))) {
    global $disc_amt;
    $cus_id = $customer_id;
    $disc_coupon = $_SESSION["couponfield"];
    $sql_disc = "select * from discounts where discount_code = '$disc_coupon'";
    $rs_disc = tep_db_query($sql_disc);
    $count_rows = tep_db_num_rows($rs_disc);
    if ($count_rows > 0)
    {
      $row_disc = tep_db_fetch_array($rs_disc);
      $disc_id = $row_disc['discount_id'];
    }

    $sub_total = $order->info['subtotal'];
    $disc_total = substr($order_totals[1][text],2);

    $sql_query = "insert into discount_customers (customer_id, discount_id, sub_total, discount_total) values ($cus_id,$disc_id,$sub_total,$disc_total)";
    $rs_sql = tep_db_query($sql_query);
    }


    //------insert customer choosen option eof ----
    
//------insert customer choosen option eof ----
    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
    $total_cost += $total_products_price;

    $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
  }

  if (CHECKOUT_CONTRIB_CCGV === true && tep_session_is_registered('customer_id')) {
    $order_total_modules->apply_credit();
  }

  /*
   * STEP 9: LOG CUSTOMER IN
   */
  if (CHECKOUT_AUTO_LOGIN && $customer_status != 'NO_ACCOUNT' && !tep_session_is_registered('customer_id')) {
    $checkout->send_status('90%', CHECKOUT_PROCESS_90);
    /*
     * If they're a returning customer, delete any old baskets to avoid problems
     */
    tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET . " WHERE customers_id = " . (int)$customer_id);
    tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " WHERE customers_id = " . (int)$customer_id);

    $checkout->do_login($email_address, $password);
		
	  if (!in_array($payment, $suspended_payment) && tep_session_is_registered('customer_id')) {
	    $checkout->redeem_points();
	  }
  }

  /*
   * STEP 10: FORWARD TO CHECKOUT_SUCCESS.PHP
   */
  $checkout->send_status('100%', CHECKOUT_PROCESS_100, true);

  
  
  //Send invoice emails only if payment has already been made
  if (!in_array($payment, $suspended_payment) && !$paypal_wpp_express_checkout) {
    if (CHECKOUT_CONTRIB_GIFT_CARD === true) {
      if (tep_session_is_registered('gift_card')) {
        $checkout->update_gift_card_balance($_SESSION['gift_card']);
      }
    }
    $email_data = array();
    
    $email_data['STORE_NAME'] = STORE_NAME;
    $email_data['STORE_OWNER_EMAIL_ADDRESS'] = STORE_OWNER_EMAIL_ADDRESS;
    $email_data['SUBJECT'] = sprintf(CHECKOUT_INVOICE_SUBJECT_CUSTOMER, $insert_id);
    $email_data['NAME'] = $order->customer['firstname'] . ' ' . $order->customer['lastname'];
    $email_data['EMAIL_ADDRESS'] = $order->customer['email_address'];
    $email_data['EMAIL_RECIPIENT'] = $order->customer['email_address'];
    $email_data['ORDER_ID'] = $insert_id;
    $email_data['INVOICE_URL'] = tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false);
    $email_data['DATE_LONG'] = strftime(DATE_FORMAT_LONG);
    $email_data['TELEPHONE'] = $telephone;
    $email_data['COMMENTS'] = tep_db_output($order->info['comments']);
    $email_data['COMMENTS_SLIP'] = tep_db_output($order->info['comments']);
    $email_data['PRODUCTS'] = $products_ordered;
    $email_data['SENDER_NAME'] = STORE_OWNER;
    $email_data['SENDER_EMAIL'] = STORE_OWNER_EMAIL_ADDRESS;
    
    for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
      $email_data['ORDER_TOTALS'] .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
    }
    
    $email_data['DELIVERY_ADDRESS'] = '';

    if ($order->content_type != 'virtual') {
      $email_data['DELIVERY_ADDRESS'] = $order->delivery['firstname'] . ' ' . $order->delivery['lastname'] . "\n" . 
                                        $order->delivery['street_address'] . "\n";
                      
      if ($order->delivery['suburb'] != '') {
        $email_data['DELIVERY_ADDRESS'] .= $order->delivery['suburb'] . "\n";
      }
      
      $email_data['DELIVERY_ADDRESS'] .= $order->delivery['city'] . ', ' . $order->delivery['state'] . '  ' . $order->delivery['postcode'] . "\n";
      $email_data['DELIVERY_ADDRESS'] .= $order->delivery['country']['title'];
    }

    $email_data['BILLING_ADDRESS'] = '';
    
    $email_data['BILLING_ADDRESS'] = $order->billing['firstname'] . ' ' . $order->billing['lastname'] . "\n" . 
                                     $order->billing['street_address'] . "\n";
                    
    if ($order->billing['suburb'] != '') {
      $email_data['BILLING_ADDRESS'] .= $order->billing['suburb'] . "\n";
    }
    
    $email_data['BILLING_ADDRESS'] .= $order->billing['city'] . ', ' . $order->billing['state'] . '  ' . $order->billing['postcode'] . "\n";
    $email_data['BILLING_ADDRESS'] .= $order->billing['country']['title'];
              

    $email_data['PAYMENT_METHOD'] = '';
    
    if (is_object($$payment)) {
      $payment_class = $$payment;
      $email_data['PAYMENT_METHOD'] = $order->info['payment_method'] . "\n\n";
      if($order->info['purchase_order_number']) {
        $email_data['PAYMENT_METHOD'] .= EMAIL_TEXT_PURCHASE_ORDER_NUMBER . $order->info['purchase_order_number'] . "\n";
      }
      if ($payment_class->email_footer) {
        $email_data['PAYMENT_METHOD'] .= $payment_class->email_footer;
      }
    }
    
    $email_temp = $email_data;
    
    foreach ($email_temp as $key => $val) {
      $email_data[$key] = $checkout->c($val);
    }
    if (!empty($order->customer['email_address'])) {
      $checkout->send_email('invoice', $email_data);
    }
    
    if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
      $email_data['NAME'] = STORE_NAME;
      $email_data['EMAIL_RECIPIENT'] = SEND_EXTRA_ORDER_EMAILS_TO;
      $email_data['SUBJECT'] = sprintf(CHECKOUT_INVOICE_SUBJECT_MERCHANT, $insert_id);
      $email_data['SENDER_NAME'] = $checkout->c($order->customer['firstname'] . ' ' . $order->customer['lastname']);
      $email_data['SENDER_EMAIL'] = $order->customer['email_address'];
      $checkout->send_email('invoice', $email_data);
    }
/*  Send Order Confirmation to Phone Text Message */
      $email_data['NAME'] = "TVS";
      $email_data['EMAIL_RECIPIENT'] = "8133805407@txt.att.net";
      $email_data['SUBJECT'] = "Sale";
      $email_data['SENDER_NAME'] = $checkout->c($order->customer['firstname'] . ' ' . $order->customer['lastname']);
      $email_data['SENDER_EMAIL'] = $order->customer['email_address'];
      $checkout->send_email('invoice_text', $email_data);
    
    if ($whole[iswholesale] != 1){
    /*================Affiliate Email Notification==============*/
    if (intval($ref)!=0){
      $affiliate_send = tep_db_query("select * from affiliate_affiliate where affiliate_id='".$ref."' and affiliate_order_notification='Y' and affiliate_notification_email!='' and affiliate_email_code!=''");
      $orders = tep_db_fetch_array($affiliate_send);
    }
    }
    /*================End of Affiliate Email Notification==============*/
  } else {
    $checkout->send_status('100%', CHECKOUT_PROCESS_100_OFFSITE, true); 
          $email_data['NAME'] = $orders['affiliate_firstname'] . ' ' . $orders['affiliate_lastname'];
      $email_data['EMAIL_RECIPIENT'] = $orders['affiliate_notification_email'];
      $email_data['SENDER_NAME'] = "TravelVideoStore.com Affiliate Sales";
      $email_data['SENDER_EMAIL'] = "affiliates@travelvideostore.com";

      if ($orders['affiliate_email_code']=='D'){
      	$email_data['SUBJECT'] = "TravelVideoStore.com Affiliate Sale Detail Notification";
      	$checkout->send_email('invoice', $email_data);
                                               }
      if ($orders['affiliate_email_code']=='S'){
      	$email_data['SUBJECT'] = "TravelVideoStore.com Affiliate Sale Notification";
      	$checkout->send_email('invoice_summary', $email_data);
      }

    $_SESSION['checkout_payment'] = $payment;

    if (isset($GLOBALS[$payment]->form_paypal_url)) {
      $url = $GLOBALS[$payment]->form_paypal_url;
    } else {
      $url = $GLOBALS[$payment]->form_action_url;
      
      if ($payment == 'eWayPayment') {
        if (strpos($url, 'http') === false) {
          $url = tep_href_link($url, '', 'SSL');
        }
        
        if (substr($url, -1) == '?') {
          $url .= '&';
        } else {
          $url .= '?';
        }
        
        //URL formed this way due to stores with SEF URLs
        $url .= 'payment_method=eWayPayment';
      }
    }
    
    if ($payment == 'paypal' || $payment == 'paypal_standard') {
      $form_name = 'paypal_payment_info';
    } else {
      $form_name = 'offsite_payment_form';
    }
    
    /* Remember the correct order to update */

    if (!tep_session_is_registered('checkout_order_id')) tep_session_register('checkout_order_id');
    $_SESSION['checkout_order_id'] = $insert_id;
    
    /* PayPal WPP Express Checkout */
    if ($paypal_wpp_express_checkout) {
      if (tep_session_is_registered('paypal_error')) tep_session_unregister('paypal_error');

      if (isset($_GET['clearSess'])) {
        tep_session_unregister('paypal_ec_temp');
    		tep_session_unregister('paypal_ec_token');
    		tep_session_unregister('paypal_ec_payer_id');
    		tep_session_unregister('paypal_ec_payer_info');
      }

      if (!method_exists($payment_modules, 'ec_step1') || !method_exists($payment_modules, 'ec_step2')) {
        $checkout->die_error(TEXT_PAYPALWPP_ERROR_PAYMENT_CLASS, true);
      }
      
      if(tep_session_is_registered('paypal_ec_token')) tep_session_unregister('paypal_ec_token');
      
      $payment_modules->ec_step1(FILENAME_CHECKOUT);
      exit();
    }
    
    if (strncmp($payment, 'sveawebpay', 10) == 0) {
      if (is_array($shipping_selection)) {
        $_SESSION['shipping'] = $shipping;
        
        $GLOBALS[$shipping_selection[0]] = new stdClass;
        $GLOBALS[$shipping_selection[0]]->title = $shipping['title'];
        if (isset($shipping['description'])) {
          $GLOBALS[$shipping_selection[0]]->description = $shipping['description'];
        }
      }
      
      $payment_modules->before_process();
      exit();
    }
    
    /* ePay Payment */
    if (strncmp($payment, 'epay', 4) == 0) { 
      $_SESSION["order_id"] = $insert_id;
      
      if (!tep_session_is_registered('sendto')) tep_session_register('sendto');
      $_SESSION['sendto'] = $sendto;
      
      if (!tep_session_is_registered('payment')) tep_session_register('payment');
      $_SESSION['payment'] = 'epaywindow';
      
      if ($GLOBALS[$payment]->IsPopup()) {
        $url = tep_href_link('checkout_epay.php', '', 'SSL');
      } else {
        $url = 'https://relay.ditonlinebetalingssystem.dk/relay/v2/relay.cgi/' . tep_href_link('checkout_epay.php', '', 'SSL');
        if (getenv("HTTP_COOKIE") != "") {
          $url .= '?HTTP_COOKIE=' . getenv("HTTP_COOKIE");
        }
      }
      
      echo '<script type="text/javascript">';
      echo 'parent.Checkout.Location("' . $url . '");';
      echo '</script>';
      @ob_flush();
      flush();
      die();
    }
    
    /* PayPal Express */
    if ($payment == 'paypal_express' || $payment == 'paypal_uk_express') {
      echo '<script type="text/javascript">';
      echo 'parent.Checkout.Location("';
      if ($payment == 'paypal_express') {
        echo tep_href_link('ext/modules/payment/paypal/express.php', '', 'SSL');
      } else {
        echo tep_href_link('ext/modules/payment/paypal/express_uk.php', '', 'SSL');
      }
      echo '");</script>';
      @ob_flush();
      flush();
      die();
    }
    
    if (in_array($payment, array('quickpay'))) {
      $url = 'https://secure.quickpay.dk/quickpay.php';
    }

    echo tep_draw_form($form_name, $url, 'post', 'target="_parent"');
        
    if ($payment == 'cybersource') {
      $order->info['currency'] = strtolower($order->info['currency']);
    } 
    
    /* Support for a strange version of PayPal IPN out there */
    if ($payment == 'paypal' && @method_exists($GLOBALS[$payment], 'formFields')) {
      echo $GLOBALS[$payment]->formFields();
    } elseif ($payment == 'quickpay') {
      /* For Quickpay v4.0.5 */
      if (method_exists($GLOBALS[$payment], 'get_payment_options_name')) {
        $quickpay_language = "da";
        switch ($language) {
          case "english": $quickpay_language = "en"; break;
          case "swedish": $quickpay_language = "se"; break;
          case "norwegian": $quickpay_language = "no"; break;
          case "german": $quickpay_language = "de"; break;
          case "french": $quickpay_language = "fr"; break;
        }
        
        $creditcards_lock_array = $GLOBALS[$payment]->creditcardgroup[$_POST['qp_card']];
        $cardtypelock = (is_array($creditcards_lock_array)) ? implode(",", $creditcards_lock_array) : '';
        $qp_order_id = sprintf('%04d', $insert_id);  
        $order_amount = 100*$currencies->calculate($order->info['total'], true, $order->info['currency'], $order->info['currency_value'], '.', '');
        $currency_code = $order->info['currency'];
        $merchant_id = MODULE_PAYMENT_QUICKPAY_SHOPID;
        $ok_page = tep_href_link(FILENAME_CHECKOUT_NOTIFICATION, '', 'SSL');
        $error_page = tep_href_link(FILENAME_CHECKOUT_NOTIFICATION, '', 'SSL');
        $result_page = tep_href_link(FILENAME_CHECKOUT_NOTIFICATION, tep_session_name() . '=' . tep_session_id());
        $protocol = '3';
        $msgtype = 'authorize';
        $autocapture = '0';
        $testmode = (CHECKOUT_ONEPAGE_ENABLED ? '0' : '1');

        $md5word = MODULE_PAYMENT_QUICKPAY_MD5WORD;
        $md5check = md5($protocol.$msgtype.$merchant_id.$quickpay_language.$qp_order_id.$order_amount.$currency_code.$ok_page.$error_page.$result_page.$autocapture.$cardtypelock.$testmode.$md5word);

        echo tep_draw_hidden_field('protocol', $protocol) .
             tep_draw_hidden_field('msgtype', $msgtype) .
             tep_draw_hidden_field('merchant', $merchant_id) .
             tep_draw_hidden_field('language', $quickpay_language) .
             tep_draw_hidden_field('ordernumber', $qp_order_id) .
             tep_draw_hidden_field('amount', $order_amount) . 
             tep_draw_hidden_field('currency', $currency_code) .
             tep_draw_hidden_field('continueurl', $ok_page) .
             tep_draw_hidden_field('cancelurl', $error_page) .
             tep_draw_hidden_field('callbackurl', $result_page) .
             tep_draw_hidden_field('autocapture', $autocapture) .
             tep_draw_hidden_field('cardtypelock', $cardtypelock) . 
             tep_draw_hidden_field('testmode', $testmode) .
             tep_draw_hidden_field('md5check', $md5check);
      /* For Quickpay v3.2.1 */
      } else {
        $qp_order_id = sprintf('%04d', $insert_id);
        $quickpay_language = ($language == "danish") ? "da" : "en";
        $order_amount = 100*$currencies->calculate($order->info['total'], true, $order->info['currency'], $order->info['currency_value'], '.', '');
        $currency_code = $order->info['currency'];
        $merchant_id = MODULE_PAYMENT_QUICKPAY_SHOPID;
        $ok_page = tep_href_link(FILENAME_CHECKOUT_NOTIFICATION, '', 'SSL');
        $error_page = tep_href_link(FILENAME_CHECKOUT_NOTIFICATION, '', 'SSL');
        $result_page = tep_href_link(FILENAME_CHECKOUT_NOTIFICATION, tep_session_name() . '=' . tep_session_id());
        $cci_page = tep_href_link(FILENAME_CHECKOUT_QUICKPAY, 'language=' . $quickpay_language . '&currency=' . $currency_code . '&qpcard=' . $HTTP_POST_VARS['qp_card']);
        $md5word = MODULE_PAYMENT_QUICKPAY_MD5WORD;
        $md5check = md5($qp_order_id.$order_amount.$currency_code.$merchant_id.$ok_page.$error_page.$result_page.$cci_page.$md5word);
        
        echo tep_draw_hidden_field('language', $quickpay_language) .
             tep_draw_hidden_field('autocapture', '0') .
             tep_draw_hidden_field('ordernum', $qp_order_id) .
             tep_draw_hidden_field('merchant', $merchant_id) .
             tep_draw_hidden_field('amount', $order_amount) . 
             tep_draw_hidden_field('currency', $currency_code) .
             tep_draw_hidden_field('okpage', $ok_page) .
             tep_draw_hidden_field('errorpage', $error_page) .
             tep_draw_hidden_field('resultpage', $result_page) .
             tep_draw_hidden_field('ccipage', $cci_page) .
             tep_draw_hidden_field('md5check', $md5check);
      }
    } else {
      //Fixed for Protx Direct 5.5
      if ($payment != 'protx_direct' || ($payment == 'protx_direct' && !isset($GLOBALS[$payment]->protocol))) {
        echo $GLOBALS[$payment]->process_button();
      } else {
        if (!tep_session_is_registered('shipping')) tep_session_register('shipping');
        if (!tep_session_is_registered('payment')) tep_session_register('payment');
        
        if (!tep_session_is_registered('billto') || (int)$billto < 1) {
          if (!tep_session_is_registered('billto')) tep_session_register('billto');
          
          $sql_data_array = array(
            'customers_id' => $customer_id,
            'entry_firstname' => $bill_firstname,
            'entry_lastname' => $bill_lastname,
            'entry_company' => $bill_company,
            'entry_street_address' => $bill_street_address,
            'entry_postcode' => $bill_postcode,
            'entry_city' => $bill_city,
            'entry_country_id' => $bill_country,
            'entry_suburb' => $bill_suburb
          );
                                  
          if (CHECKOUT_REQUIRE_STATE != 'H') {
            if ($bill_zone_id > 0) {
              $sql_data_array['entry_zone_id'] = $bill_zone_id;
              $sql_data_array['entry_state'] = '';
            } else {
              $sql_data_array['entry_zone_id'] = '0';
              $sql_data_array['entry_state'] = $bill_state;
            }
          }

          tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
          
          $billto = tep_db_insert_id();
        }
        
        foreach ($_POST as $key => $val) {
          echo '<input type="hidden" name="' . $key . '" value="' . $val . '" />';
        }
      }
    }

    echo '</form>';
    
    @ob_flush();
    flush();

    echo '<script type="text/javascript">';
    echo 'var submit_retries = 0;';
    echo 'function reload_form() {';
    echo '  var is_error = false;';
    echo '  if (submit_retries < ' . CHECKOUT_PAYMENT_PROCESSOR_SAFEGUARD_RETRIES . ') {';
    if (CHECKOUT_PAYMENT_PROCESSOR_SAFEGUARD_PROMPT) {
      echo '    if (confirm("' . CHECKOUT_SUBMIT_FORM_AGAIN . '")) {';
      echo '      submit_retries++;';
      echo '      submit_form();';
      echo '    } else {';
      echo '      is_error = true;';
      echo '    }';
    } else {
      echo '      submit_retries++;';
      echo '      submit_form();';
    }
    echo '  } else {';
    echo '    is_error = true;';
    echo '  }';
    
    echo '  if (is_error) {';
    echo '    var dialog;';
    echo '    dialog = \'<img src="' . CHECKOUT_IMG_LOGO . '">\';';
    echo '    dialog += \'<br /><br />\';';
    echo '    dialog += \'<span><h2>\';';
    echo '    dialog += \'' . CHECKOUT_PROBLEM_FOUND . '</h2>\';';
    echo '    dialog += \'<div style="font-size:1px;height:10px"></div>\';';
    echo '    dialog += \'<span class="main" style="font-size:12px;color:#000">' . CHECKOUT_SUBMIT_FORM_ERROR . '</span>\';';
    echo '    dialog += \'<div style="font-size:1px;height:20px"></div>\';';
    echo '    dialog += \'<center><a href="javascript:void(0);" onclick="parent.Checkout.ClearOverlay();"><img src="' . DIR_WS_LANGUAGES . $language . '/images/buttons/button_back.gif" border="0"></a></center>\';';
    echo '    dialog += \'</span>\';';

    echo '    parent.document.getElementById(\'checkout-dialog-box\').innerHTML = dialog;';
    echo '  }';
    echo '}';
    
    echo 'function submit_form() {';
    
    switch ($payment) {
      case 'cybersource':
        echo 'document.' . $form_name . '.elements["orderPage_declineResponseURL"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', '', 'SSL') . '";';
        echo 'document.' . $form_name . '.elements["orderPage_receiptResponseURL"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', '', 'SSL') . '";';
        break;
      case 'paypal':
      case 'paypal_ipn':
      case 'paypal_standard':
        echo 'document.' . $form_name . '.elements["return"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', '', 'SSL') . '";';
        echo 'document.' . $form_name . '.elements["cancel_return"].value="' . tep_href_link(FILENAME_CHECKOUT, '', 'SSL') . '";';
        break;
      case 'gochargeit':
        echo 'document.' . $form_name . '.elements["remoteurl"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', '', 'SSL') . '";';
        break;
      case 'securehosting':
        echo 'document.' . $form_name . '.elements["success_url"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', '', 'SSL') . '";';
        break;
      case 'esec':
        echo 'document.' . $form_name . '.elements["EPS_RESULTURL"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', '', 'SSL') . '";';
        break;
      case 'alertpay':
        echo 'document.' . $form_name . '.elements["ap_returnurl"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', '', 'SSL') . '";';
        echo 'document.' . $form_name . '.elements["ap_cancelurl"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', '', 'SSL') . '";';
        break;
      case 'gateway_services_cc':
      case 'gateway_services_ck':
        echo 'document.' . $form_name . '.elements["redirect"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', '', 'SSL') . '";';
        break;
      case 'authorizenet_cc_sim':
        echo 'document.' . $form_name . '.elements["x_relay_url"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', 'module=authorizenet_cc_sim', 'SSL') . '";';
        break;
      case 'usaepay':
        echo 'document.' . $form_name . '.elements["UMredirApproved"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', '', 'SSL') . '";';
        echo 'document.' . $form_name . '.elements["UMredirDeclined"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', '', 'SSL') . '";';
        break;
      case 'wiegant_ideal':
        echo 'document.' . $form_name . '.elements["accepturl"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', '', 'SSL') . '";';
        echo 'document.' . $form_name . '.elements["declineurl"].value="' . tep_href_link(FILENAME_CHECKOUT, '', 'SSL') . '";';
        echo 'document.' . $form_name . '.elements["exceptionurl"].value="' . tep_href_link(FILENAME_CHECKOUT, '', 'SSL') . '";';
        echo 'document.' . $form_name . '.elements["cancelurl"].value="' . tep_href_link(FILENAME_CHECKOUT, '', 'SSL') . '";';
        break;
      case 'dibs':
        echo 'document.' . $form_name . '.elements["accepturl"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', 'module=' . $payment . '&result=accept', 'SSL') . '";';
        echo 'document.' . $form_name . '.elements["cancelurl"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', 'module=' . $payment . '&result=cancel', 'SSL') . '";';
        break;
      case 'payson':
        $my_currency = MODULE_PAYMENT_PAYSON_CURRENCY;
        $my_cost = number_format(($order->info['total'] - $order->info['shipping_cost']) * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency), ',', '');
        $my_extracost = number_format($order->info['shipping_cost'] * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency), ',', '');
        $my_md5 = md5(MODULE_PAYMENT_PAYSON_ID . ':' . $my_cost . ':' . $my_extracost . ':' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', 'module=' . $payment . '&result=accept', 'SSL') . ':' . MODULE_PAYMENT_PAYSON_GUARANTEE . MODULE_PAYMENT_PAYSON_SECRETKEY);
        
        echo 'document.' . $form_name . '.elements["OkUrl"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', 'module=' . $payment . '&result=accept', 'SSL') . '";';
        echo 'document.' . $form_name . '.elements["CancelUrl"].value="' . tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', 'module=' . $payment . '&result=cancel', 'SSL') . '";';
        echo 'document.' . $form_name . '.elements["MD5"].value="' . $my_md5 . '";';

        break;
    }

    if (CHECKOUT_PAYMENT_PROCESSOR_SAFEGUARD_TIMEOUT > 0) {
      echo 'setTimeout(\'reload_form();\', ' . abs(CHECKOUT_PAYMENT_PROCESSOR_SAFEGUARD_TIMEOUT) . '000);';
    }
    echo 'document.' . $form_name . '.submit();';
    echo '}';
    echo 'window.onload=submit_form;';

    echo '</script>';
    flush();
    die();
  }
  
  $vend_array = array();
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
  	$vendor_send = tep_db_query("SELECT * FROM products_to_vendors a LEFT JOIN vendors b ON ( a.vendors_id = b.vendors_id ) WHERE a.products_id = '".tep_get_prid($order->products[$i][id])."' AND b.sale_email = '1' AND email_detail != ''");
    while($row = tep_db_fetch_array($vendor_send)){
      if (!in_array($row[sale_email_address],$vend_array)){
        array_push($vend_array, $row[sale_email_address]);

        if ($row['email_detail']=='D'){
      $email_data['NAME'] = $row['vendors_name'];
      $email_data['EMAIL_RECIPIENT'] = $row['sale_email_address'];
      $email_data['SENDER_NAME'] = "TravelVideoStore.com Vendor Sales";
      $email_data['SENDER_EMAIL'] = "suppliersupport@travelvideostore.com";
      $email_data['SUBJECT'] = "TravelVideoStore.com Vendor Detail Sales Notification";
      $checkout->send_email('invoice', $email_data);        }
        if ($row['email_detail']=='S'){
      $email_data['NAME'] = $row['vendors_name'];
      $email_data['EMAIL_RECIPIENT'] = $row['sale_email_address'];
      $email_data['SENDER_NAME'] = "TravelVideoStore.com Vendor Sales";
      $email_data['SENDER_EMAIL'] = "suppliersupport@travelvideostore.com";
      $email_data['SUBJECT'] = "TravelVideoStore.com Vendor Sales Notification";
      $checkout->send_email('invoice_summary', $email_data);        }
			
		  }
		}
	}

  if ($whole[iswholesale] != 1){
  // Include OSC-AFFILIATE

    if (file_exists(DIR_WS_INCLUDES . 'affiliate_checkout_process.php')) {
      require(DIR_WS_INCLUDES . 'affiliate_checkout_process.php');
    }
  }
  
  // Begin Affiliate Program - Sales Tracking
  $orders_total = $currencies->format($cart->show_total() - $total_tax); 

  tep_session_register('orders_total'); 

  $orders_id = $insert_id; 

  tep_session_register('orders_id'); 
  // End Affiliate Program - Sales Tracking 

  $payment_modules->after_process();
   
  $cart->reset(true);
  
  
  tep_session_unregister('sendto');
  tep_session_unregister('billto');
  tep_session_unregister('shipping');
  tep_session_unregister('payment');
  tep_session_unregister('comments');
  tep_session_unregister('comments_slip');
  tep_session_unregister('good_cc_number');
  tep_session_unregister('good_cc_cove');
  
  if (CHECKOUT_CONTRIB_CCGV === true) {
    if(tep_session_is_registered('credit_covers')) tep_session_unregister('credit_covers');
    $order_total_modules->clear_posts();
    if (tep_session_is_registered('cc_id')) tep_session_unregister('cc_id');
  }
  
  if (CHECKOUT_CONTRIB_DISCOUNT_COUPON === true) {
    if (tep_session_is_registered('coupon')) tep_session_unregister('coupon');
  }
  
  if (CHECKOUT_CONTRIB_POINTS_AND_REWARDS === true) {
    if (tep_session_is_registered('customer_shopping_points')) tep_session_unregister('customer_shopping_points');
    if (tep_session_is_registered('customer_shopping_points_spending')) tep_session_unregister('customer_shopping_points_spending');
    if (tep_session_is_registered('customer_referral')) tep_session_unregister('customer_referral');
  }
  
//  set checkout_success "yes" for holding order
    tep_db_query("update " . TABLE_HOLDING_ORDERS . " set orders_status='1' where orders_id='".$_SESSION['insert_order_id']."'");
  
  
  $checkout->send_status('100%', CHECKOUT_PROCESS_100, true);
  echo '<script type="text/javascript">';
  echo ' function goto_checkout_success() {';
  echo '   parent.location.href="' . tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL') . '";';
  echo '   setTimeout("goto_checkout_success();", 5000);';
  echo ' }';
  echo ' goto_checkout_success();';
  echo '</script>';
  //ob_flush();
  flush();
?>
  </body>
</html>
<?php die(); ?>