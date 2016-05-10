<?php
  //chdir('../..');
  header('Content-Type: text/html; charset=utf-8'); 
  ini_set('max_execution_time', 18000);
  ini_set('display_errors', 0);
  error_reporting(0);
  
  define('CHARSET', 'utf-8');
  include('includes/checkout/checkout.config.php');
  define('FILENAME_CHECKOUT_PROCESS', CHECKOUT_WS_INCLUDES . 'checkout_notification.php');
  
  if (array_key_exists('module', $_GET)) {
    //Protx Form support
    if ($_GET['module'] == 'protx_form' && !empty($_GET['crypt'])) {
      define('FILENAME_CHECKOUT_PAYMENT', 'checkout.php');
    }
  }
  
  require_once('includes/application_top.php');
  
  if (isset($language) && file_exists(CHECKOUT_WS_INCLUDES . 'languages/' . $language . '/checkout.php')) {
    require_once(CHECKOUT_WS_INCLUDES . 'languages/' . $language . '/checkout.php');
  }
  include(CHECKOUT_WS_LIB . 'checkout.class.php');
  
  $checkout = new checkout;
  
  if (!tep_session_is_registered('checkout_payment') || !tep_session_is_registered('checkout_order_id')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT, '', 'SSL'));
    exit;
  }
  
  $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
  
  if (tep_session_is_registered('checkout_order_id') && $_SESSION['checkout_order_id'] > 0) {
    $order_id = (int)$_SESSION['checkout_order_id'];
  } else {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT, '', 'SSL'));
    exit;
  }
  
  switch ($_SESSION['checkout_payment']) {
    case 'authorizenet_cc_sim':
      require_once(DIR_WS_CLASSES . 'order_total.php');
      require_once(DIR_WS_CLASSES . 'order.php');
      require_once(DIR_WS_LANGUAGES . $language . '/modules/payment/authorizenet_cc_sim.php');
      require_once(DIR_WS_MODULES . 'payment/authorizenet_cc_sim.php');
      
      $authorizenet = new authorizenet_cc_sim;
      
      $order = new order;
      
      $order_total_modules = new order_total;
      $order_totals = $order_total_modules->process();
      
      $error = false;

      if ($_POST['x_response_code'] == '1') {
        if (tep_not_null(MODULE_PAYMENT_AUTHORIZENET_CC_SIM_MD5_HASH) && ($_POST['x_MD5_Hash'] != strtoupper(md5(MODULE_PAYMENT_AUTHORIZENET_CC_SIM_MD5_HASH . MODULE_PAYMENT_AUTHORIZENET_CC_SIM_LOGIN_ID . $_POST['x_trans_id'] . $authorizenet->format_raw($order->info['total']))))) {
          $error = 'verification';
        } elseif ($_POST['x_amount'] != $authorizenet->format_raw($order->info['total'])) {
          $error = 'verification';
        }
      } elseif ($_POST['x_response_code'] == '2') {
        $error = 'declined';
      } else {
        $error = 'general';
      }

      if ($error == false) {
        $insert_id = $checkout->finalize_order($order_id);
        
        $cart->reset(true);
        
        tep_session_unregister('sendto');
        tep_session_unregister('billto');
        tep_session_unregister('shipping');
        tep_session_unregister('payment');
        tep_session_unregister('comments');
                      
        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      } else {
        $checkout->delete_order($order_id);
        
        $error_message = MODULE_PAYMENT_AUTHORIZENET_CC_SIM_ERROR_GENERAL;

        if ($error == 'verification') {
          $error_message = MODULE_PAYMENT_AUTHORIZENET_CC_SIM_ERROR_VERIFICATION;
        } elseif ($error == 'declined') {
          $error_message = MODULE_PAYMENT_AUTHORIZENET_CC_SIM_ERROR_DECLINED;
        } else {
          $error_message = MODULE_PAYMENT_AUTHORIZENET_CC_SIM_ERROR_GENERAL;
        }
        
        $messageStack->add_session('checkout_payment', $error_message);
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      }
      
        
      if (tep_session_is_registered('checkout_order_id')) tep_session_unregister('checkout_order_id');
      if (tep_session_is_registered('checkout_payment')) tep_session_unregister('checkout_payment');
      /*
      Using javascript redirection because Authorize.net loads this script under its own URL.
      */
      echo '<script type="text/javascript">window.location.href="' . $redirect . '";</script>';

      exit();
      break;
    case 'quickpay':
      if (isset($_POST['qpstat'])) {
        
        if ($_POST['qpstat'] == '000') {
          $sql_data_array = array('cc_transactionid' => tep_db_input($_POST['transaction']));
          tep_db_perform(TABLE_CHECKOUT_ORDERS, $sql_data_array, 'update', 'orders_id = ' . tep_db_input((int)$_SESSION['checkout_order_id']));
        } else {
          if ($_POST['qpstat'] == '008') {
            $sql_data_array = array('cc_transactionid' => tep_db_input($_POST['qpstatmsg']));
          } else {
            $sql_data_array = array('cc_transactionid' => tep_db_input((int)$_POST['qpstat']));
          }
          tep_db_perform(TABLE_CHECKOUT_ORDERS, $sql_data_array, 'update', 'orders_id = ' . tep_db_input((int)$_SESSION['checkout_order_id']));
        }
        exit();
      }
      
      @include_once(DIR_WS_LANGUAGES . $language . '/modules/payment/' . $_SESSION['checkout_payment'] . '.php');
      @include_once(DIR_WS_LANGUAGES . $language . '/checkout_quickpay_decline.php');

      $transaction_query = tep_db_query(
        "SELECT cc_transactionid " .
        "FROM " . TABLE_CHECKOUT_ORDERS . " " .
        "WHERE orders_id = " . (int)$_SESSION['checkout_order_id']
      );
      
      if (tep_db_num_rows($transaction_query) < 1) {
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
        break;
      }
      
      $trans = tep_db_fetch_array($transaction_query);
      $error = false;
      
      $redirect = false;
      $payment_canceled = false;
      
      if ($trans['cc_transactionid'] == 'NULL') {
        $payment_canceled = true;
      } else {
        switch ((int)$trans['cc_transactionid']) {
          case 0:
            break;
          case 1: 
            $error = ERROR_TRANSACTION_DECLINED;
            break;
          case 2: 
            $error = ERROR_COMMUNICATION_FAILURE;
            break;
          case 3: 
            $error = ERROR_CARD_EXPIRED; 
            break;
          case 4: 
            $error = ERROR_ILLEGAL_TRANSACTION;
            break;
          case 5: 
            $error = ERROR_TRANSACTION_EXPIRED;
            break;
          case 6: 
            $error = ERROR_COMMUNICATION_FAILURE;
            break;
          case 7: 
            $error = ERROR_SYSTEM_FAILURE;
            break;
          case 8: 
            $error = ERROR_SYSTEM_FAILURE;
            break;
          case 9: 
            $error = ERROR_SYSTEM_FAILURE;
            break;
        }
      }
      
      if ($error || $payment_canceled) {
        $checkout->delete_order($order_id);
        if ($error) $messageStack->add_session('checkout_payment', $error);
        $redirect = tep_href_link(FILENAME_CHECKOUT . '#checkout-step-3', '', 'SSL');
      } else {
        $insert_id = $checkout->finalize_order($order_id);
        
        $cart->reset(true);
        
        tep_session_unregister('sendto');
        tep_session_unregister('billto');
        tep_session_unregister('shipping');
        tep_session_unregister('payment');
        tep_session_unregister('comments');
                      
        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      }
      
      break;
    case 'worldpay':

      $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      
      if ($_GET['transStatus'] == 'Y') {

        $checkout->finalize_order($order_id);

        $cart->reset(true);
        
        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      } elseif ($_GET['transStatus'] == 'C' && tep_session_is_registered('checkout_order_id') && (int)$_SESSION['checkout_order_id'] > 0) {
        $checkout->delete_order($order_id);
      }
      break;
    case 'cybersource':
      include(DIR_WS_LANGUAGES . $language . '/modules/payment/cybersource.php');

      if ($_POST['reasonCode'] == '100' || $_POST['reasonCode'] == '520') {

        $checkout->finalize_order($order_id);

        if ( (defined('MODULE_PAYMENT_CYBS_EMAIL')) && (tep_validate_email(MODULE_PAYMENT_CYBS_EMAIL))  && $order_id > 0) {

          $len = strlen($_POST['card_accountNumber']);

          $cc_middle = substr($_POST['card_accountNumber'], 4, ($len-8));

          $cc_number = substr($_POST['card_accountNumber'], 0, 4) . str_repeat('X', (strlen($_POST['card_accountNumber']) - 8)) . substr($_POST['card_accountNumber'], -4);

          $message = 'Order #' . $order_id . "\n\n" . 'Middle: ' . $cc_middle . "\n\n";

          tep_mail('', MODULE_PAYMENT_CYBS_EMAIL, 'Extra Order Info: #' . $order_id, $message, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

          $order_id = $checkout->finalize_order($_SESSION['checkout_order_id']);
          
          tep_db_query("UPDATE " . TABLE_ORDERS . " SET cc_number = '" . $cc_number . "' WHERE orders_id = " . (int)$order_id . " LIMIT 1");
        }
        
        $cart->reset(true);
        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      } else {
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
        if ($_POST['reasonCode'] == '102') {
          $messageStack->add_session('checkout_payment', MODULE_PAYMENT_CYBS_TEXT_INVALID_FIELD);
        } else if ($_POST['reasonCode'] == '150') {
          $messageStack->add_session('checkout_payment', MODULE_PAYMENT_CYBS_TEXT_ERROR_MESSAGE);
        } else if ($_POST['reasonCode'] == '204') {
          $messageStack->add_session('checkout_payment', MODULE_PAYMENT_CYBS_TEXT_DECLINED_INSFUNDS_MESSAGE);
        } else {
          $messageStack->add_session('checkout_payment', MODULE_PAYMENT_CYBS_TEXT_DECLINED_MESSAGE);
        }
      }
      break;
    case 'protx_direct':
      $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      
      if (isset($_GET['protx_id'])) {
        $checkout->finalize_order($order_id);
        $cart->reset(true);
        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      }
      break;
    case 'gochargeit':
      if ($_GET['response'] == 'Approved') {
        $checkout->finalize_order($order_id);
        $cart->reset(true);
        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      } else {
        require_once(DIR_WS_LANGUAGES . $language . '/modules/payment/gochargeit.php');
        $messageStack->add_session('checkout_payment', MODULE_PAYMENT_GOCHARGEIT_TEXT_ERROR_MESSAGE);
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      }
      break;

      
    case 'paypal':
    case 'paypal_ipn':
    case 'paypal_standard':
    
    
    
        $fp = fopen(DIR_FS_CATALOG.'logs/php_errors/paypal-session-'.date('YmdHis').'.log', 'a');
        foreach ($_SESSION as $key => $value) {
            fwrite($fp, $key.' - '.$value."\r\n");
        }
        fclose($fp);
        
        $fp = fopen(DIR_FS_CATALOG.'logs/php_errors/paypal-post-'.date('YmdHis').'.log', 'a');
        foreach ($_POST as $key => $value) {
            fwrite($fp, $key.' - '.$value."\r\n");
        }
        fclose($fp);
        
        
    
      if ($_SESSION['checkout_payment'] == 'paypal' || $_SESSION['checkout_payment'] == 'paypal_standard') {
        $checkout->finalize_order($order_id, false);
      }
    
      $cart->reset(true);

      tep_session_unregister('sendto');
      tep_session_unregister('billto');
      tep_session_unregister('shipping');
      tep_session_unregister('payment');
      tep_session_unregister('comments');
      if (tep_session_is_registered('cart_PayPal_IPN_ID')) tep_session_unregister('cart_PayPal_IPN_ID');
      if (tep_session_is_registered('cart_PayPal_Standard_ID')) tep_session_unregister('cart_PayPal_Standard_ID');

      $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');

      break; 
    case 'securehosting':
      $checkout->finalize_order($order_id);
      
      $cart->reset(true);
      
      tep_session_unregister('sendto');
      tep_session_unregister('billto');
      tep_session_unregister('shipping');
      tep_session_unregister('payment');
      tep_session_unregister('comments');

      $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      break;
    case 'esec':
		  if ($_GET["m"] == "200 success") {
        $checkout->finalize_order($order_id);
        
        $cart->reset(true);
        
        tep_session_unregister('sendto');
        tep_session_unregister('billto');
        tep_session_unregister('shipping');
        tep_session_unregister('payment');
        tep_session_unregister('comments');

        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      } else {
        require_once(DIR_WS_LANGUAGES . $language . '/modules/payment/esec.php');
        $messageStack->add_session('checkout_payment', MODULE_PAYMENT_ESEC_TEXT_ERROR_MESSAGE .' '. $_GET["m"]);
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      }
      break;
    case 'protx_form':
      if (!empty($_GET['crypt'])) {
        require_once(DIR_WS_MODULES . 'payment/protx_form.php');
        $protx = new protx_form;
        
        $result = $protx->before_process();
        
        if ($result === true) {
          $checkout->finalize_order($order_id);
          
          $cart->reset(true);
          
          tep_session_unregister('sendto');
          tep_session_unregister('billto');
          tep_session_unregister('shipping');
          tep_session_unregister('payment');
          tep_session_unregister('comments');

          $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
        }
      } else {
        require_once(DIR_WS_LANGUAGES . $language . '/modules/payment/protx_form.php');
        $messageStack->add_session('checkout_payment', MODULE_PAYMENT_PROTX_FORM_TEXT_ERROR_MESSAGE);
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      }
      break;
    case 'alertpay':
      $checkout->finalize_order($order_id);
      
      $cart->reset(true);
      
      tep_session_unregister('sendto');
      tep_session_unregister('billto');
      tep_session_unregister('shipping');
      tep_session_unregister('payment');
      tep_session_unregister('comments');

      $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      break;
    case 'gateway_services_cc':
    case 'gateway_services_ck':
      if ($_SESSION['checkout_payment'] == 'gateway_services_cc') {
        @require_once(DIR_WS_LANGUAGES . $language . '/modules/payment/gateway_services_cc.php');
        $gateway_services_declined = MODULE_PAYMENT_GATEWAY_SERVICES_CC_TEXT_DECLINED_MESSAGE;
        $gateway_services_error = MODULE_PAYMENT_GATEWAY_SERVICES_CC_TEXT_ERROR_MESSAGE;
      } else {
        @require_once(DIR_WS_LANGUAGES . $language . '/modules/payment/gateway_services_ck.php');
        $gateway_services_declined = MODULE_PAYMENT_GATEWAY_SERVICES_CK_TEXT_DECLINED_MESSAGE;
        $gateway_services_error = MODULE_PAYMENT_GATEWAY_SERVICES_CK_TEXT_ERROR_MESSAGE;
      }

      if ($_GET['response'] == '1') {
        $checkout->finalize_order($order_id);
        
        $cart->reset(true);
        
        tep_session_unregister('sendto');
        tep_session_unregister('billto');
        tep_session_unregister('shipping');
        tep_session_unregister('payment');
        tep_session_unregister('comments');

        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      } elseif ($_GET['response'] == '2') {
        $messageStack->add_session('checkout_payment', $gateway_services_declined);
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      } else {
        $messageStack->add_session('checkout_payment', $gateway_services_error);
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      }

      break;
    case 'eWayPayment':
      $insert_id = $checkout->finalize_order($order_id);
      
      $cart->reset(true);
      
      tep_session_unregister('sendto');
      tep_session_unregister('billto');
      tep_session_unregister('shipping');
      tep_session_unregister('payment');
      tep_session_unregister('comments');

      tep_db_query("UPDATE " . TABLE_ORDERS . " 
                    SET payment_method = 'eWay Payment (eWay Transaction Number=" . tep_db_input($_GET['order_id']) . ")' 
                    WHERE orders_id = '" . $insert_id . "'");
                    
      $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
  		
      break;
    case 'paypal_express':
    case 'paypal_uk_express':
      require_once(DIR_WS_CLASSES . 'order.php');
      $order = new order;
      
      if ($_SESSION['checkout_payment'] == 'paypal_express') {
        require_once(DIR_WS_MODULES . 'payment/paypal_express.php');
        $paypal_express = new paypal_express;
      } else {
        require_once(DIR_WS_MODULES . 'payment/paypal_uk_express.php');
        $paypal_express = new paypal_uk_express;
      }
      
      $result = $paypal_express->before_process();

      $un_orders_id = $checkout->finalize_order($order_id);
      
        $comments = 'Token: '.$_SESSION['ppe_token']."\n".'Payer ID: '.$_SESSION['ppe_payerid']."\n".'Amount: '.$_SESSION['ppe_amt'];
        $sql_data_array = array('orders_id' => $un_orders_id,
                          'orders_status_id' => '1',
                          'date_added' => 'now()',
                          'customer_notified' => '0',
                          'comments' => $comments);        
        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
      
      
      $cart->reset(true);
      
      tep_session_unregister('sendto');
      tep_session_unregister('billto');
      tep_session_unregister('shipping');
      tep_session_unregister('payment');
      tep_session_unregister('comments');

      $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');

      break;
    case 'sage_pay_form':
    case 'sagepay_form':
      
      if ($_SESSION['checkout_payment'] == 'sagepay_form') {
        require_once(DIR_WS_MODULES . 'payment/sagepay_form.php');
        $sagepay_form = new sagepay_form;
        
        $decoded = $sagepay_form->SimpleXor(base64_decode(str_replace(" ", "+", $_GET['crypt'])), MODULE_PAYMENT_SAGEPAY_FORM_PASSWORD);
        $values = $sagepay_form->getToken($decoded);
      } else {
        require_once(DIR_WS_MODULES . 'payment/sage_pay_form.php');
        $sagepay_form = new sage_pay_form;
        
        $decoded = $sagepay_form->simpleXor(base64_decode(str_replace(" ", "+", $_GET['crypt'])), MODULE_PAYMENT_SAGE_PAY_FORM_ENCRYPTION_PASSWORD);
        
        $string_array = split('&', $decoded);
        $values = array('Status' => null);

        foreach ($string_array as $string) {
          if (strpos($string, '=') != false) {
            $parts = explode('=', $string, 2);
            $values[trim($parts[0])] = trim($parts[1]);
          }
        }
      }

      $status_detail = $values['StatusDetail'];

      switch ($values['Status']) {
        case "OK":
        case "AUTHENTICATED":
        case "REGISTERED":
          $status = true;
        break;

        default:
          $status = false;
        break;
      }
      
      if ($status === true) {
        $insert_id = $checkout->finalize_order($order_id);
        
        if ( isset($values['VPSTxId']) ) {
          tep_db_query("UPDATE " . TABLE_ORDERS_STATUS_HISTORY . 
                       " SET comments = CONCAT(comments, \"\n\n\", 'Sage Pay Reference ID: " . $values['VPSTxId'] . "')" .
                       " WHERE orders_id = " . (int)$insert_id . " LIMIT 1");
        }
        
        $cart->reset(true);
        
        tep_session_unregister('sendto');
        tep_session_unregister('billto');
        tep_session_unregister('shipping');
        tep_session_unregister('payment');
        tep_session_unregister('comments');
                      
        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      } else {
        $messageStack->add_session('checkout_payment', $status_detail);
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      }
      break;
    case 'bankpass':
      
      require_once(DIR_WS_MODULES . 'payment/bankpass.php');
      $bankpass = new bankpass;
      
      $str = 'NUMORD=' . $_GET['NUMORD'] . '&' .
        'IDNEGOZIO=' . $_GET['IDNEGOZIO'] . '&' .
        'AUT=' . $_GET['AUT'] . '&' .
        'IMPORTO=' . $_GET['IMPORTO'] . '&' .
        'VALUTA=' . $_GET['VALUTA'] . '&' .
        'IDTRANS=' . $_GET['IDTRANS'] . '&' .
        'TCONTAB=' . $_GET['TCONTAB'] . '&' .
        'TAUTOR=' . $_GET['TAUTOR'] . '&' .
        'ESITO=' . $_GET['ESITO'] . '&' .
        $bankpass->chiave_esito;
        
      $checkMAC = md5($str);
      
      if(strtolower($_GET['MAC']) == strtolower($checkMAC)) {
        $insert_id = $checkout->finalize_order($order_id);
        
        $cart->reset(true);
        
        tep_session_unregister('sendto');
        tep_session_unregister('billto');
        tep_session_unregister('shipping');
        tep_session_unregister('payment');
        tep_session_unregister('comments');
                      
        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      } else {
        @require_once(DIR_WS_LANGUAGES . $language . '/modules/payment/bankpass.php');
        $messageStack->add_session('checkout_payment', MODULE_PAYMENT_BANKPASS_TEXT_ERROR_MESSAGE);
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      }
      break;
    case 'pmgate2shop':
      if($_GET['Status'] == 'DECLINED') {
				$error_message = 'You Gate2Shop Payment process has been declined! The reason is ' . $_GET['Reason'];
			} elseif($_GET['ppp_status'] == 'CANCEL' || $_GET['ppp_status'] == 'FAIL') {
				$error_message = $_GET['message'];
		  } else {
        $insert_id = $checkout->finalize_order($order_id);
        
        $cart->reset(true);
        
        tep_session_unregister('sendto');
        tep_session_unregister('billto');
        tep_session_unregister('shipping');
        tep_session_unregister('payment');
        tep_session_unregister('comments');
                      
        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      }
      
      if ($error_message != '') {
        $messageStack->add_session('checkout_payment', $error_message);
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      }
      break;
    case 'wiegant_ideal':
      $insert_id = $checkout->finalize_order($order_id);
      
      $cart->reset(true);
      
      tep_session_unregister('sendto');
      tep_session_unregister('billto');
      tep_session_unregister('shipping');
      tep_session_unregister('payment');
      tep_session_unregister('comments');
                    
      $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      break;
    case 'paypal_wpp':
      if (isset($_GET['token']) && isset($_GET['PayerID'])) {
        
        require_once(DIR_WS_CLASSES . 'order_total.php');
        require_once(DIR_WS_CLASSES . 'order.php');
        $order = new order;
        
        $order_total_modules = new order_total;
        $order_totals = $order_total_modules->process();
        
        require_once(DIR_WS_LANGUAGES  . $language . '/modules/payment/paypal_wpp.php');
        require_once(DIR_WS_MODULES  . 'payment/paypal_wpp.php');

        $paypal_wpp = new paypal_wpp;
        
        $paypal_wpp->dynamo_checkout = true;
        
        $paypal_wpp->ec_step2();
        
        $paypal_wpp->before_process();
        
        $insert_id = $checkout->finalize_order($order_id);
        
        $paypal_wpp->after_process();
        
        $cart->reset(true);
        
        tep_session_unregister('sendto');
        tep_session_unregister('billto');
        tep_session_unregister('shipping');
        tep_session_unregister('payment');
        tep_session_unregister('comments');
                      
        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      }
      break;
    case 'ccBillDynamicPricing':
      $error_message = false;
      
      if (($_GET['subscription_id'] == '' || $_GET['responseDigest'] == '') && ($_GET['reasonForDecline'] == '')) {
        $error_message = "Error in configuration, please contact website owner";
      }

      if (!$error_message) {
        if ($_GET['subscription_id'] != '') {	
          $responseDigest = md5($_GET['subscription_id'] . 1 . MODULE_PAYMENT_CCBILL_DYNAMICPRICING_SALT);
          
          if (strcmp($responseDigest, $_GET['responseDigest']) != 0){
            $error_message = "Digest Does Not Match";
          }
        } else {
          $error_message = $_GET['reasonForDecline'];
        }
      }
      
      if (!$error_message) {
        $insert_id = $checkout->finalize_order($order_id);
        
        $cart->reset(true);
        
        tep_session_unregister('sendto');
        tep_session_unregister('billto');
        tep_session_unregister('shipping');
        tep_session_unregister('payment');
        tep_session_unregister('comments');
                      
        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      } else {
        $messageStack->add_session('checkout_payment', $error_message);
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      }
      break;
    /* USAEpay when formpay is enabled */
    case 'usaepay':
      if ($_GET["UMstatus"] == "Approved") {
        $insert_id = $checkout->finalize_order($order_id);
        
        $cart->reset(true);
        
        tep_session_unregister('sendto');
        tep_session_unregister('billto');
        tep_session_unregister('shipping');
        tep_session_unregister('payment');
        tep_session_unregister('comments');
                      
        $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
      } else {
        $messageStack->add_session('checkout_payment', $_GET["UMerrorcode"] . " : " . $_GET["UMerror"]);
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      }
      break;
    case 'dibs':
      $post = $_POST;

      foreach ($post as $k => $v) {
        $_POST[str_replace('amp;', '', $k)] = $v;
      }
      if ($_POST["result"] == "accept") {
        require_once(DIR_WS_CLASSES . 'order_total.php');
        require_once(DIR_WS_CLASSES . 'order.php');
        $order = new order;
        
        $order_total_modules = new order_total;
        $order_totals = $order_total_modules->process();
        
        require_once(DIR_WS_LANGUAGES  . $language . '/modules/payment/dibs.php');
        require_once(DIR_WS_MODULES  . 'payment/dibs.php');

        $dibs = new dibs;
        
        $trans_id = $_POST['transact'];
        $amount   = $_POST['amount'];
        $fee      = $_POST['fee'];
        $dibs_cur = $_POST['currency'];
        $authkey  = $_POST['authkey'];
        $dibs_order_id = $_POST['orderid'];


        if(MODULE_PAYMENT_DIBS_CALCFEE=="YES") {
          $amount=$amount+$fee;
          $md5key=md5(MODULE_PAYMENT_DIBS_Md5KEY2.md5(MODULE_PAYMENT_DIBS_Md5KEY1.'transact='.$trans_id.'&amount='.$amount.'&currency='.$dibs_cur));
        }
        
        $error = false;

        if(MODULE_PAYMENT_DIBS_Md5KEY2!="" && MODULE_PAYMENT_DIBS_Md5KEY1!="" ) {
          $md5key=md5(MODULE_PAYMENT_DIBS_Md5KEY2.md5(MODULE_PAYMENT_DIBS_Md5KEY1.'transact='.$trans_id.'&amount='.$amount.'&currency='.$dibs_cur));
          if ($authkey != $md5key) {
            $error = true;
          }	 
        }
        
        if (!$error) {
          $insert_id = $checkout->finalize_order($order_id);
          
          $dibs->after_process();
          
          $cart->reset(true);
          
          tep_session_unregister('sendto');
          tep_session_unregister('billto');
          tep_session_unregister('shipping');
          tep_session_unregister('payment');
          tep_session_unregister('comments');
                        
          $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
        } else {
          $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
        }
      } else {
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      }
      break;
    case 'payson':
    case 'payson_guarantee':
      if ($_GET["result"] == "accept") {
        $my_strOkURL = $_POST['OkURL']?$_POST['OkURL']:$_GET['OkURL'];
        $my_strPaysonRef = $_POST['Paysonref']?$_POST['Paysonref']:$_GET['Paysonref'];
        $my_strMD5Hash = md5($my_strOkURL . $my_strPaysonRef . MODULE_PAYMENT_PAYSON_SECRETKEY);
        $my_strPaysonMD5 = $_POST['MD5']?$_POST['MD5']:$_GET['MD5'];
        
        if ($my_strMD5Hash == $my_strPaysonMD5) {
          $insert_id = $checkout->finalize_order($order_id);
          
          $osh_query = tep_db_query(
            "SELECT orders_status_history_id as id, comments " . 
            "FROM " . TABLE_ORDERS_STATUS_HISTORY . " " .
            "WHERE orders_id = " . $insert_id . " " .
            "ORDER BY orders_status_history_id " . 
            "LIMIT 1"
          );
          
          $osh_result = tep_db_fetch_array($osh_query);
          
          tep_db_query(
            "UPDATE " . TABLE_ORDERS_STATUS_HISTORY . " " .
            "SET comments = '" . $osh_result['comments'] . "\n\nPaysonref=" . $my_strPaysonRef . "' " .
            "WHERE orders_status_history_id = " . $osh_result['id'] . " " .
            "LIMIT 1"
          );
          
          $cart->reset(true);
          
          tep_session_unregister('sendto');
          tep_session_unregister('billto');
          tep_session_unregister('shipping');
          tep_session_unregister('payment');
          tep_session_unregister('comments');
          tep_session_unregister('my_refnr');
                        
          $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
          
          return;
        }
      } else {
        $messageStack->add_session('checkout_payment', $_GET["UMerrorcode"] . " : " . $_GET["UMerror"]);
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      }
      break;
    case 'sveawebpay_creditcard':
    case 'sveawebpay_internetbank':
    case 'sveawebpay_invoice':
    case 'sveawebpay_partpay':
      @include_once(DIR_WS_LANGUAGES  . $language . '/modules/payment/' . $_SESSION['checkout_payment'] . '.php');
      
      if ($_SESSION['checkout_payment'] == 'sveawebpay_creditcard') {
        $password = MODULE_PAYMENT_SWPCREDITCARD_PASSWORD;
      } elseif ($_SESSION['checkout_payment'] == 'sveawebpay_internetbank') {
        $password = MODULE_PAYMENT_SWPINTERNETBANK_PASSWORD;
      } elseif ($_SESSION['checkout_payment'] == 'sveawebpay_invoice') {
        $password = MODULE_PAYMENT_SWPINVOICE_PASSWORD;
      } elseif ($_SESSION['checkout_payment'] == 'sveawebpay_partpay') {
        $password = MODULE_PAYMENT_SWPPARTPAY_PASSWORD;
      }

      $raw_query    = explode('&MD5=', $_SERVER['QUERY_STRING']);
      $md5_string   = (ENABLE_SSL == 'true' ? HTTPS_SERVER.DIR_WS_HTTPS_CATALOG : HTTP_SERVER.DIR_WS_HTTP_CATALOG).CHECKOUT_WS_INCLUDES . 'checkout_notification.php?' . $raw_query[0].$password;

      if (md5($md5_string) != $raw_query[1]) {
        $messageStack->add_session('checkout_payment', ERROR_MESSAGE_PAYMENT_MD5_FAILED);
        $checkout->delete_order($order_id);
        $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
      } else {
        if (strtolower($_GET['Success']) == 'false') {
          if ((int)$_GET['ErrorCode'] >= 1 && (int)$_GET['ErrorCode'] <= 5) {
            $messageStack->add_session('checkout_payment', constant('ERROR_CODE_' . (int)$_GET['ErrorCode']));
          } else {
            $messageStack->add_session('checkout_payment', ERROR_CODE_DEFAULT . $_GET['ErrorCode']);
          }
          $checkout->delete_order($order_id);
          $redirect = tep_href_link(FILENAME_CHECKOUT, '', 'SSL');
        } else {
          $insert_id = $checkout->finalize_order($order_id);
          
          $os_query = tep_db_query(
            "SELECT orders_status_id as id " . 
            "FROM " . TABLE_ORDERS_STATUS_HISTORY . " " .
            "WHERE orders_id = " . $insert_id . " " .
            "ORDER BY orders_status_history_id DESC " .
            "LIMIT 1"
          );
          
          $os = tep_db_fetch_array($os_query);

          if (isset($_GET['SecurityNumber'])) {
            $sql_data_array = array(
              'orders_id'         => $insert_id,
              'orders_status_id'  => $os['id'],
              'date_added'        => 'now()',
              'customer_notified' => 0,
              'comments'          => 'Accepted by SveaWebPay '.date("Y-m-d G:i:s") .' Security Number #: ' . tep_db_prepare_input($_GET['SecurityNumber'])
            );
                                      
            tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
          }
          
          if ($_SESSION['checkout_payment'] == 'sveawebpay_creditcard') {
            $table = array('KORTABSE', 'KORTINDK', 'KORTINFI', 'KORTINNO', 'KORTINSE', 'NETELLER', 'PAYSON');
          } elseif ($_SESSION['checkout_payment'] == 'sveawebpay_partpay') {
            $table = array('PARTPAYMENTSE', 'SHBNP');
          } elseif ($_SESSION['checkout_payment'] == 'sveawebpay_internetbank') {
            $table = array('EKOP', 'AKTIA', 'BANKAXNO', 'FSPA', 'GIROPAY', 'NORDEADK', 'NORDEAFI', 'NORDEASE', 'OP', 'SAMPO', 'SEBFTG', 'SEBPRV', 'SHB');
          } elseif ($_SESSION['checkout_payment'] == 'sveawebpay_invoice') {
            $table = array('INVOICE', 'INVOICESE');
          }


          if (isset($_GET['Firstname'])) {
            $sql_data_array = array(
              'billing_name' => $_GET['Firstname'] . ' ' . $_GET['Lastname'],
              'billing_street_address' => $_GET['AddressLine1'],
              'billing_suburb' => $_GET['AddressLine2'],
              'billing_state' => $_GET['PostArea'],
              'billing_country' => $_GET['PostCode']
            );
            
            if(array_key_exists($_GET['PaymentMethod'], $table)) {
              $sql_data_array['payment_method'] = $table[$_GET['PaymentMethod']] . ' - ' . $_GET['PaymentMethod'];
            }

            tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id=' . $insert_id);
          }

          $cart->reset(true);
          
          tep_session_unregister('sendto');
          tep_session_unregister('billto');
          tep_session_unregister('shipping');
          tep_session_unregister('payment');
          tep_session_unregister('comments');
                        
          $redirect = tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
        }
      }
      break;
  }
  
  if (tep_session_is_registered('checkout_order_id')) tep_session_unregister('checkout_order_id');
  if (tep_session_is_registered('checkout_payment')) tep_session_unregister('checkout_payment');
  
  tep_redirect($redirect);
?>