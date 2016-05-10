<?php
/*
 * Dynamo Checkout by Dynamo Effects
 * Copyright 2008 - Dynamo Effects.  All Rights Reserved
 * http://www.dynamoeffects.com
 */
 

  if (isset($_GET['blank']) && $_GET['blank'] == '1') die('<html></html>');
  header('Content-Type: text/html; charset=utf-8'); 
  define('CHARSET', 'utf-8');
  error_reporting(0);
  @ini_set('display_errors', '0');
  define('FILENAME_CHECKOUT', 'checkout.php');
  define('CHECKOUT_KLUDGE', true);
  
  require_once('includes/checkout/checkout.config.php');
  //require_once('includes/classes/payment.php');
  if (isset($_GET['payment'])) {
    $payment = preg_replace('/[^A-Za-z0-9_]/', '', $_GET['payment']);
  }
  
  
  
	define('FILENAME_CHECKOUT', 'checkout.php');
  define('FILENAME_CHECKOUT_PAYMENT', 'checkout.php');
  define('FILENAME_CHECKOUT_PAYMENT_ADDRESS', 'checkout.php');
  define('FILENAME_CHECKOUT_PROCESS', 'checkout_process.php');
  define('FILENAME_CHECKOUT_SHIPPING', 'checkout.php');
  define('FILENAME_CHECKOUT_SHIPPING_ADDRESS', 'checkout.php');

  if (CHECKOUT_CONTRIB_CCGV || CHECKOUT_CONTRIB_DISCOUNT_COUPON) {
    define('CHECKOUT_ENABLE_COUPON', true);
  } else {
    define('CHECKOUT_ENABLE_COUPON', false);
  }
  
  //Disables Express Checkout button in PayPal WPP contribution
  define('MODULE_PAYMENT_PAYPAL_DP_BUTTON_PAYMENT_PAGE', 'No');

  require_once('includes/application_top.php');
  
  $ec_query = tep_db_query(
    "SELECT configuration_id " . 
    "FROM " . TABLE_CONFIGURATION . " " .
    "WHERE configuration_key = 'MODULE_PAYMENT_PAYPAL_DP_BUTTON_PAYMENT_PAGE' " .
    "  AND configuration_value = 'Yes' " .
    "LIMIT 1"
  );
  
  if (tep_db_num_rows($ec_query) > 0) {
    define('MODULE_PAYMENT_PAYPAL_DP_BUTTON_PAYMENT_METHOD', 'Yes');
  } else {
    define('MODULE_PAYMENT_PAYPAL_DP_BUTTON_PAYMENT_METHOD', 'No');
  }

  $express_checkout_config = array(
    'enabled' => $ec_enabled,
    'processing' => ($ec_enabled && !isset($_GET['ec_cancel']) && (tep_session_is_registered('paypal_ec_payer_id') || tep_session_is_registered('paypal_ec_payer_info'))),
    'payment_method' => (MODULE_PAYMENT_PAYPAL_DP_BUTTON_PAYMENT_METHOD == 'Yes' ? true : false),
    'address_editable' => (MODULE_PAYMENT_PAYPAL_EC_ADDRESS_OVERRIDE == 'Store' ? true : false)
  );
  
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_REAL_SHOPPING_CART));
  }
  
  if (($_SERVER['HTTPS'] != '1' && $_SERVER['HTTPS'] != 'on' && $_SERVER['SERVER_PORT'] != 443) && ENABLE_SSL) {
    header('Location: ' . tep_href_link(FILENAME_CHECKOUT, '', 'SSL'));
  }
  
  if (CHECKOUT_DEBUG_MODE) {
    error_reporting(0);
    @ini_set('display_errors', '1');
  } else {
    error_reporting(0);
    @ini_set('display_errors', '0');
  }
  
  $shipping_enabled = true;
  $shipping_dynamic = CHECKOUT_DYNAMIC_SHIPPING;
  
  if (($cart->show_weight() > 0 || !CHECKOUT_DYNAMIC_SHIPPING) && strpos($cart->content_type, 'virtual') === false) {
    $shipping_enabled = true;
  }

  if (!function_exists('get_zone')) {
    function get_zone($id, $state) {
      return true;
    }
  }

  if (is_object($navigation)) {
    $navigation->set_snapshot();
  }

  require_once('includes/classes/http_client.php');
  require_once(CHECKOUT_WS_INCLUDES . 'languages/' . $language . '/checkout.php');
  
  include(DIR_WS_LANGUAGES . $language . '/checkout_shipping.php');
  include(DIR_WS_LANGUAGES . $language . '/checkout_payment.php');
  include(DIR_WS_LANGUAGES . $language . '/checkout_confirmation.php');
  include(DIR_WS_LANGUAGES . $language . '/checkout_process.php');
 
  require_once(DIR_WS_CLASSES . 'payment.php');
  include(CHECKOUT_WS_LIB . 'checkout.class.php');
  
  $checkout = new checkout;
   
  if (isset($_POST['bs'])) {
    if (!tep_session_is_registered('customer_zone_id')) tep_session_register('customer_zone_id');

    $_SESSION['customer_zone_id'] = (is_numeric($_POST['bs']) && (int)$_POST['bs'] > 0 ? (int)$_POST['bs'] : '');
    $customer_zone_id = $_SESSION['customer_zone_id'];
  }
  
  if (isset($_POST['bcn'])) {
    $customer_country_id = (isset($_POST['bcn']) && (int)$_POST['bcn'] > 0 ? (int)$_POST['bcn'] : '');
  }
  
  require_once(DIR_WS_CLASSES . 'order.php');
  $order = new order;
   
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link(FILENAME_REAL_SHOPPING_CART));
    }
  }

  require_once(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();
  
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CHECKOUT, '', 'SSL'));

  if (isset($_POST['x'])) {
  	switch ($_POST['x']) {
    
  		case 'order_total':
        header('Content-type: application/json; charset=UTF-8');
  			$checkout->order_total();
  			break;
  		case 'get_zones':
        header('Content-type: application/json; charset=UTF-8');  
  			$checkout->get_zones($_POST['c'], $_POST['n'], $_POST['d']);
  			break;
      case 'get_payment_shipping':
        header('Content-type: application/json; charset=UTF-8');  
        $checkout->get_payment_shipping();
        break;
  		case 'apply_coupon':
        header('Content-type: application/json; charset=UTF-8');
  			$checkout->apply_coupon($_POST['coupon']);
  			break;
      case 'remove_coupon':
        header('Content-type: application/json; charset=UTF-8');
        $checkout->remove_coupon();
        break;
  		case 'apply_gift_card':
        header('Content-type: application/json; charset=UTF-8');
  			$checkout->apply_gift_card($_POST['gift_card']);
  			break;
      case 'remove_gift_card':
        header('Content-type: application/json; charset=UTF-8');
        $checkout->remove_gift_card();
        break;
      case 'update_cart':
        header('Content-type: text/html; charset=UTF-8');
        $checkout->update_cart();
        break;
      case 'login_window':
        header('Content-type: text/html; charset=UTF-8');
        $checkout->login_window();
        break;
      case 'remove_product':
        header('Content-type: application/json; charset=UTF-8');
        $checkout->remove_product();
        break;
      case 'log_error':
        header('Content-type: application/json; charset=UTF-8');
        $checkout->log_error($_POST['error']);
        break;
      case 'apply_points':
        header('Content-type: application/json; charset=UTF-8');
        $checkout->apply_points($_POST['s']);
        break;
      case 'apply_giftwrap':
        header('Content-type: application/json; charset=UTF-8');
        $checkout->apply_giftwrap($_POST['gw']);
        break;
  	}
  	
  	die();
    ob_flush();
  }

 //if (!tep_session_is_registered('shipping')) tep_session_unregister('shipping');
 // if (!tep_session_is_registered('shipping')) tep_session_register('shipping');
   

  if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
  $cartID = $cart->cartID;

  $error = false;

  if (isset($_GET['action']) && ($_GET['action'] == 'login')) {
    $email_address = tep_db_prepare_input($_POST['login_email_address']);
    $password = tep_db_prepare_input($_POST['login_password']);
    
    $error = do_login($email_address, $password);

    if ($error == true) {
      $messageStack->add('checkout', TEXT_LOGIN_ERROR);
    }
  }

  if ($order->delivery['zone_id'] > 0) {
    $ship_zone_id = $order->delivery['zone_id'];
  } elseif ($order->delivery['state'] != '' && is_numeric($order->delivery['country']['id'])) {
    $ship_zone_id = get_zone($order->delivery['country']['id'], $order->delivery['state']);
  }

  
  if ($order->billing['zone_id'] > 0) {
    $bill_zone_id = $order->billing['zone_id'];
  } elseif ($order->billing['state'] != '' && is_numeric($order->billing['country']['id'])) {
    $bill_zone_id = get_zone($order->billing['country']['id'], $order->billing['state']);
  }
  
  
  
  $payment_modules = new payment;
  
  //Catch all payment module error responses variations
  $payment_module_error = $checkout->detect_payment_module_error();
  
  if ($payment_module_error) {
//    tep_mail("TVS", "8133805407@txt.att.net", "Payment Error", $payment_module_error, "TVS", "sales@travelvideostore.com");
    $messageStack->add('checkout_payment', $payment_module_error);  
  }

  if (!tep_session_is_registered('comments')) tep_session_register('comments');

  if (tep_session_is_registered('customer_id')) {
    $customer_query = tep_db_query("SELECT c.*, ab.* FROM " . TABLE_CUSTOMERS . " c LEFT JOIN " . TABLE_ADDRESS_BOOK . " ab ON (c.customers_default_address_id = ab.address_book_id) WHERE c.customers_id = '" . (int)$_SESSION['customer_id'] . "'");
    $customer = tep_db_fetch_array($customer_query);
  } else {
    $customer_default_country = CHECKOUT_COUNTRY_DEFAULT_ID;
    /*
     * TODO: This does not correctly recognize the user's language
    if (!tep_session_is_registered('customer_id')) {
      preg_match('/[a-z]{2}\-([A-Za-z]{2})/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
      if (count($matches) == 2) {
        $country_query = tep_db_query("SELECT countries_id FROM " . TABLE_COUNTRIES . " WHERE countries_iso_code_2 = '" . strtoupper($matches[1]) . "' LIMIT 1");
        if (tep_db_num_rows($country_query) > 0) {
          $country = tep_db_fetch_array($country_query);
          $customer_default_country = $country['countries_id'];
        }
      }
    }
    */
  }
  
  $payment_shipping = $checkout->get_payment_shipping(false);
  $selections = $payment_shipping['payment'];
  
  for ($x = 0; $x < count($selections); $x++) {
    include(DIR_WS_LANGUAGES . $language . '/modules/payment/' . $selections[$x]['id'] . '.php');
    
    /* If this is Protx Direct v5, pull the fields from the confirmation method */
    if ($selections[$x]['id'] == 'protx_direct' && isset($GLOBALS[$selections[$x]['id']]->protocol)) {
      $selections[$x] = $GLOBALS[$selections[$x]['id']]->confirmation();
    }
  }
  
  $radio_buttons = 0;

  $hidden_variables = array(); //array('name' => 'shipping', 'value' => ''));
  
  $step_count = 1;
  
  if (tep_session_is_registered('checkout_order_id') && (int)$_SESSION['checkout_order_id'] > 0) {
    $checkout->delete_order($_SESSION['checkout_order_id']);
    tep_session_unregister('checkout_order_id');
  }
  
  if (CHECKOUT_CONTRIB_STS) {
    require(DIR_WS_INCLUDES . 'column_left.php');
    require(DIR_WS_INCLUDES . 'column_right.php');
  }
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo HTML_PARAMS; ?>>
<!-- 
 * Dynamo Checkout by Dynamo Effects
 * Copyright 2008 - Dynamo Effects.  All Rights Reserved
 *
 * For information on adding this checkout to your store, visit:
 * http://www.dynamoeffects.com
//-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset="<?php echo CHARSET; ?>">
<?php

  if (file_exists(DIR_WS_INCLUDES . 'header_tags.php') && function_exists('getcanonicalurl')) {
    require(DIR_WS_INCLUDES . 'header_tags.php');
  } else {
    echo '<title>' . TITLE . '</title>';
  }
  
  
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php 
  if (CHECKOUT_CONTRIB_STS_4) {
    $sts->restart_capture(); 
  }
  
  $checkout_stylesheet = '<link rel="stylesheet" type="text/css" href="' . CHECKOUT_WS_CSS . 'checkout.css" />';

  if (CHECKOUT_CONTRIB_STS_4) { 
    echo $checkout_stylesheet;
    $sts->restart_capture('checkout_stylesheet'); 
  } elseif (CHECKOUT_CONTRIB_STS_2) {
    $sts_block_name = 'checkout_stylesheet';
    $template[$sts_block_name] = $checkout_stylesheet;

  } else {
    echo $checkout_stylesheet;
  }  
  
?>
  <script type="text/javascript" src="<?php echo CHECKOUT_WS_JS; ?>jquery.js"></script>
  <script type="text/javascript" src="<?php echo CHECKOUT_WS_JS; ?>lightbox.js"></script>
  <script type="text/javascript"><?php include(CHECKOUT_WS_JS . 'checkout.js.php'); ?></script>
</head>
<?php
  if (CHECKOUT_CONTRIB_STS_4) {
    $sts->restart_capture ('applicationtop2header');
  } elseif (CHECKOUT_CONTRIB_STS_2) {
    $sts_block_name = 'applicationtop2header';
    require(STS_RESTART_CAPTURE);
  }

?>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<div style="display: none;"><?php echo $_SESSION['paypal_error_message']; ?></div>

<?php
  if (CHECKOUT_CONTRIB_STS_4) {
    $sts->restart_capture();
  }
?>

<noscript>
  <div class="checkout-overlay" style="height:3000px;visibility:visible"></div>
  <div class="checkout-dialog-box checkout-js-required"><?php echo CHECKOUT_JAVASCRIPT_REQUIRED; ?></div>
</noscript>
<?php 
  if (!CHECKOUT_CONTRIB_STS) {
    require(DIR_WS_INCLUDES . 'header.php'); 
  }
?>
<!-- body_text //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">

  <tr>
    <td class="pageHeading" valign="middle" colspan="2"><?php echo PAGE_HEADING; ?></td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top" class="checkout-column-left">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
<?php
  if ($messageStack->size('checkout') > 0) {
?>
              <tr>
                <td><?php echo $messageStack->output('checkout'); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
<?php
  }
  
  if (CHECKOUT_DISPLAY_CART) {
?>
              <tr>
                <td>
                  <a name="checkout-step-1"></a>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="productListing">
                    <tr>
                      <td class="checkout-productListing-heading"><img src="<?php echo CHECKOUT_WS_INCLUDES; ?>images/icon_review_cart.gif">&nbsp;<b><?php echo sprintf(CHECKOUT_STEP_1, $step_count); $step_count++; ?></b></td>
                    </tr>
                    <tr class="productListing">
                      <td class="checkout-productListing-data">
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td colspan="2">
                              <?php echo tep_draw_form('update_cart', tep_href_link(FILENAME_CHECKOUT, '', 'SSL'), 'post', 'target="checkout-gateway" autocomplete="off"'); ?>
                              <input type="hidden" name="x" value="update_cart" />
                              <div id="checkout-shopping-cart">
                                <?php $checkout->get_cart_table(); ?>
                              </div>
                              </form>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2" class="checkout-spacing-1"></td>
                          </tr>
                          <tr>
                            <td valign="top" width="70%">
                              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td>
                                    <table border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td valign="top" class="main" width="235"><?php echo CHECKOUT_MADE_CHANGES; ?></td>
                                        <td class="main"><a href="javascript:void(0);" onclick="document.forms['update_cart'].submit();"><?php echo tep_image_button('button_update_cart.gif'); ?></a></td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                                <?php if (CHECKOUT_ENABLE_COUPON) { ?>
                                <tr>
                                  <td class="checkout-spacing-1"></td>
                                </tr>
                                <tr>
                                  <td>
                                    <table border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td valign="middle" class="main" width="195" colspan="2"><?php echo CHECKOUT_PROMO_CODE; ?></td>
                                      </tr>
                                      <tr>
                                        <td class="main" valign="middle"><?php echo tep_draw_input_field('coupon', $_SESSION['coupon'], 'id="checkout-coupon" class="checkout-input-text"'); ?></td>
                                        <td class="main"><a href="javascript:void(0);" onclick="Checkout.ApplyCoupon();"><?php echo tep_image_button('button_update.gif'); ?></a></td>
                                      </tr>
                                    </table>
                                    <div id="checkout-coupon-status"></div>
                                  </td>
                                </tr>
                                <?php } ?>
                                <?php if (CHECKOUT_CONTRIB_GIFT_CARD === true) { ?>
                                <tr>
                                  <td class="checkout-spacing-1"></td>
                                </tr>
                                <tr>
                                  <td>
                                    <table border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td valign="middle" class="main" width="195" colspan="2"><?php echo CHECKOUT_GIFT_CARD; ?></td>
                                      </tr>
                                      <tr>
                                        <td class="main" valign="middle"><?php echo tep_draw_input_field('gift_card', $_SESSION['gift_card'], 'id="checkout-gift-card-code" class="checkout-input-text"'); ?></td>
                                        <td class="main"><a href="javascript:void(0);" onclick="Checkout.ApplyGiftCard();"><?php echo tep_image_button('button_update.gif'); ?></a></td>
                                      </tr>
                                    </table>
                                    <div id="checkout-gift-card-status"></div>
                                  </td>
                                </tr>
                                <?php } ?>
                              </table>
                            </td>
                            <td width="30%" align="right" valign="top" class="checkout-order-total-wrapper">
                              <div id="ot-top" class="checkout-order-total">
                                Calculating totals...
                              </div>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td class="checkout-spacing-1"></td>
              </tr>
<?php } ?>
              <tr>
                <td colspan="2">
         <!--          

                  <?php echo tep_draw_form('checkout', tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_process.php', '', 'SSL'), 'post', 'onSubmit="return Checkout.ProcessOrder();" target="checkout-gateway" autocomplete="off"') . tep_draw_hidden_field('action', 'checkout'); ?>
       -->          
  
                  <?php echo tep_draw_form('checkout', tep_href_link('checkout_process.php', '', 'SSL'), 'post', 'onSubmit="return Checkout.ProcessOrder();" target="checkout-gateway" autocomplete="off"') . tep_draw_hidden_field('action', 'checkout'); ?>
   
   <!--   
<?php echo tep_draw_form('checkout', tep_href_link('checkout_process.php', '', 'SSL'), 'post', '') . tep_draw_hidden_field('action', 'checkout'); ?>-->  


                  <table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="main">
                        <a name="checkout-step-1"></a>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2" class="productListing">
                          <tr>
                            <td class="checkout-productListing-heading"><img src="<?php echo CHECKOUT_WS_INCLUDES; ?>images/icon_pencil.gif">&nbsp;<b><?php echo sprintf(CHECKOUT_STEP_2, $step_count); $step_count++; ?></b></td>
                          </tr>
                          <tr class="productListing">
                            <td class="checkout-productListing-data">
                              <table border="0" width="100%" cellspacing="0" cellpadding="3">
                                <tr>
                                  <td valign="top">
                                    <table border="0" cellspacing="0" cellpadding="2" width="100%">
                                      <tr>
                                        <td class="main"><h2><?php echo CHECKOUT_HEADING_BILLING_ADDRESS; ?></h2></td>
                                      </tr>
<?php if (!tep_session_is_registered('customer_id')) { ?>
                                      <tr>
                                        <td class="main" valign="middle"><?php echo CHECKOUT_RETURNING_CUSTOMER; ?></td>
                                      </tr>
<?php 
      }
?>
                                      <tr>
                                        <td class="main" valign="top">
                                          <label for="bill_firstname"><?php echo CHECKOUT_FIRST_NAME; ?><br />
                                          <?php echo tep_draw_input_field('bill_firstname', $customer['customers_firstname'], 'class="checkout-input-text" maxlength="255" id="bill_firstname"'); ?>
                                          </label>
                                          <label for="bill_lastname"><?php echo CHECKOUT_LAST_NAME; ?><br />
                                          <?php echo tep_draw_input_field('bill_lastname', $customer['customers_lastname'], 'class="checkout-input-text" maxlength="255" id="bill_lastname"'); ?>
                                          </label>
                                          <div class="checkout-tip"><?php echo CHECKOUT_TIP_NAME; ?></div>
                                          <div class="checkout-form-error" id="ERROR_bill_name"></div>
                                        </td>
                                      </tr>
<?php if (!tep_session_is_registered('customer_id') && CHECKOUT_REQUIRE_EMAIL != 'H') { ?>
                                      <tr>
                                        <td class="main" style="padding-bottom: 8px">
                                          <label for="email_address"<?php echo (CHECKOUT_REQUIRE_EMAIL == 'O' ? ' class="optional"' : ''); ?>><?php echo CHECKOUT_EMAIL_ADDRESS; ?><br />
                                          <?php echo tep_draw_input_field('email_address', $customer['customers_email_address'], 'class="checkout-input-text" id="email_address" maxlength="255"'); ?>
                                          </label>
                                          <div class="checkout-tip"><?php echo CHECKOUT_TIP_EMAIL; ?></div>
                                          <div class="checkout-form-error" id="ERROR_email_address"></div>
                                        </td>
                                      </tr>
<?php } ?>
<?php if ((CHECKOUT_REQUIRE_DOB != 'H' || CHECKOUT_REQUIRE_GENDER != 'H') && !tep_session_is_registered('customer_id')) { ?>
                                      <tr>
                                        <td class="main" valign="top">
<?php   if (CHECKOUT_REQUIRE_DOB != 'H') { ?>
                                          <label for="dob"<?php echo (CHECKOUT_REQUIRE_DOB == 'O' ? ' class="optional"' : ''); ?>><?php echo CHECKOUT_DOB; ?><br />
                                          <?php echo tep_draw_input_field('dob', '', 'class="checkout-input-text" maxlength="32" id="dob"'); ?>
                                          </label>
<?php   } ?>
<?php   if (CHECKOUT_REQUIRE_GENDER != 'H') { ?>
                                          <label for="gender"<?php echo (CHECKOUT_REQUIRE_GENDER == 'O' ? ' class="optional"' : ''); ?>><?php echo CHECKOUT_GENDER; ?><br />
                                          <?php echo tep_draw_radio_field('gender', 'm') . '&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f') . '&nbsp;' . FEMALE; ?>
                                          </label>
<?php   } ?>        
                                          <div class="checkout-tip"><?php echo CHECKOUT_TIP_DOB; ?></div>
                                          <div class="checkout-tip"><?php echo CHECKOUT_TIP_GENDER; ?></div>
                                          <div class="checkout-form-error" id="ERROR_dob"></div>
                                          <div class="checkout-form-error" id="ERROR_gender"></div>
                                        </td>
                                      </tr>


<?php } ?>
<?php if (CHECKOUT_REQUIRE_COMPANY != 'H') { ?>

                                      <tr>
                                        <td class="main" valign="top">
                                          <label for="bill_company"<?php echo (CHECKOUT_REQUIRE_COMPANY == 'O' ? ' class="optional"' : ''); ?>><?php echo CHECKOUT_COMPANY; ?><br />
                                          <?php echo tep_draw_input_field('bill_company', $customer['entry_company'], 'class="checkout-input-text" id="bill_company" maxlength="255"'); ?>
                                          </label>
                                          <div class="checkout-tip"><?php echo CHECKOUT_TIP_COMPANY; ?></div>
                                          <div class="checkout-form-error" id="ERROR_company"></div>
                                        </td>
                                      </tr>
<?php } ?>

                                      <?php
                                        $country_count_query = tep_db_query("SELECT countries_id, countries_name FROM " . TABLE_COUNTRIES);
                                        $country_count = tep_db_num_rows($country_count_query);
                                      ?>
                                      <tr>
                                        <td class="main" valign="top">
                                          <label for="bill_country"><?php echo CHECKOUT_COUNTRY; ?>
                                      <?php 
                                        if ($country_count > 1) { 
                                          echo '<br />' . tep_get_country_list('bill_country', ($customer['entry_country_id'] > 0 ? $customer['entry_country_id'] : $customer_default_country), 'class="checkout-select" id="bill_country" onchange="Checkout.UpdateZones(\'bill\')"'); 
                                        } else {
                                          $country = tep_db_fetch_array($country_count_query);
                                          echo '&nbsp;&nbsp;<b>' . $country['countries_name'] . '</b>';
                                          echo tep_draw_hidden_field('bill_country', $country['countries_id'], 'id="bill_country"');
                                          
                                          if ($shipping_enabled) {
                                            echo tep_draw_hidden_field('ship_country', $country['countries_id'], 'id="ship_country"');
                                          }
                                        }
                                      ?>
                                          </label>
                                          <div class="checkout-tip"><?php echo CHECKOUT_TIP_COUNTRY; ?></div>
                                          <div class="checkout-form-error" id="ERROR_bill_country"></div>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="main" valign="top">
                                          <label for="bill_street_address"><?php echo CHECKOUT_STREET_ADDRESS; ?><br />
                                          <?php echo tep_draw_input_field('bill_street_address', $customer['entry_street_address'], 'class="checkout-input-text" id="bill_street_address" maxlength="255"'); ?>
                                          </label>
                                          <div class="checkout-tip"><?php echo CHECKOUT_TIP_STREET_ADDRESS; ?></div>
                                          <div class="checkout-form-error" id="ERROR_bill_street_address"></div>
                                        </td>
                                      </tr>
<?php if (CHECKOUT_REQUIRE_SUBURB != 'H') { ?>
                                      <tr>
                                        <td class="main" valign="top">
                                          <label for="bill_suburb"<?php echo (CHECKOUT_REQUIRE_SUBURB == 'O' ? ' class="optional"' : ''); ?>><?php echo CHECKOUT_SUBURB; ?><br />
                                          <?php echo tep_draw_input_field('bill_suburb', $customer['entry_suburb'], 'class="checkout-input-text" id="bill_suburb" maxlength="255"'); ?>
                                          </label>
                                          <div class="checkout-tip"><?php echo CHECKOUT_TIP_SUBURB; ?></div>
                                        </td>
                                      </tr>
<?php } ?>
                                      <tr>
                                        <td class="main" valign="top">
                                          <label for="bill_city"><?php echo CHECKOUT_CITY; ?><br />
                                          <?php echo tep_draw_input_field('bill_city', $customer['entry_city'], 'class="checkout-input-text" id="bill_city" maxlength="255"'); ?>
                                          </label>
<?php   if (CHECKOUT_REQUIRE_STATE != 'H') { ?>
                                          <div class="state_container">
                                            <label for="bill_state"<?php echo (CHECKOUT_REQUIRE_STATE == 'O' ? ' class="optional"' : ''); ?>><?php echo CHECKOUT_ZONE; ?>
                                              <div id="bill_state_blk">
                                                <?php $checkout->display_zones(($customer['entry_country_id'] > 0 ? $customer['entry_country_id'] : $customer_default_country), 'bill_state', ($customer['entry_zone_id'] < 1 ? $customer['entry_state'] : $customer['entry_zone_id']), false); ?>
                                              </div>
                                            </label>
                                          </div>
<?php   } ?>
                                          <label for="bill_postcode"<?php echo (CHECKOUT_REQUIRE_POSTCODE == 'O' ? ' class="optional"' : ''); ?>><?php echo CHECKOUT_ZIP; ?><br />
                                            <?php echo tep_draw_input_field('bill_postcode', $customer['entry_postcode'], 'class="checkout-input-text" id="bill_postcode" maxlength="10"'); ?>
                                          </label>
                                          <div class="checkout-form-error" id="ERROR_bill_city"></div>
                                          <div class="checkout-form-error" id="ERROR_bill_state"></div>
                                          <div class="checkout-form-error" id="ERROR_bill_postcode"></div>
                                        </td>
                                      </tr>
<?php if (CHECKOUT_REQUIRE_TELEPHONE != 'H') { ?>
                                      <tr>
                                        <td class="main" valign="top">
                                          <label for="telephone"<?php echo (CHECKOUT_REQUIRE_TELEPHONE == 'O' ? ' class="optional"' : ''); ?>><?php echo CHECKOUT_TELEPHONE; ?><br />
                                          <?php echo tep_draw_input_field('telephone', $customer['customers_telephone'], 'class="checkout-input-text" maxlength=32'); ?>
                                          </label>
                                          <div class="checkout-tip"><?php echo CHECKOUT_TIP_TELEPHONE; ?></div>
                                          <div class="checkout-form-error" id="ERROR_telephone"></div>
                                        </td>
                                      </tr>
<?php 
      } 

?>

                                    </table>
                                  </td>
                                
<?php if ($shipping_enabled && !CHECKOUT_FORCE_BILLING_ADDRESS) { ?>
                                  <td valign="top" width="50%">
                                    <table border="0" cellspacing="0" cellpadding="2" width="100%">
                                      <tr>
                                        <td class="main"><h2><?php echo CHECKOUT_HEADING_SHIPPING_ADDRESS; ?></h2></td>
                                      </tr>
                                      <tr>
                                        <td class="main" style="height:24px">
                                          <?php echo CHECKOUT_SHIPPING_SAME; ?>&nbsp;<input type="checkbox" name="checkout-ship-same" id="checkout-ship-same" value="1" onclick="Checkout.ToggleShipping();"<?php echo ($express_checkout_config['processing'] ? ' CHECKED' : ''); ?>>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td align="center">
                                          <div id="checkout-shipping" class="checkout-hidden">
                                          <table border="0" cellspacing="0" cellpadding="2" width="100%">
                                            <tr>
                                              <td class="main" valign="top">
                                                <label for="ship_firstname"><?php echo CHECKOUT_FIRST_NAME; ?><br />
                                                <?php echo tep_draw_input_field('ship_firstname', '', 'class="checkout-input-text" maxlength="255" id="ship_firstname"'); ?>
                                                </label>
                                                <label for="ship_lastname"><?php echo CHECKOUT_LAST_NAME; ?><br />
                                                <?php echo tep_draw_input_field('ship_lastname', '', 'class="checkout-input-text" maxlength="255" id="ship_lastname"'); ?>
                                                </label>
                                                <div class="checkout-tip"><?php echo CHECKOUT_TIP_NAME; ?></div>
                                                <div class="checkout-form-error" id="ERROR_ship_name"></div>
                                              </td>
                                            </tr>
<?php if (CHECKOUT_REQUIRE_COMPANY != 'H') { ?>

                                            <tr>
                                              <td class="main" valign="top">
                                                <label for="ship_company"<?php echo (CHECKOUT_REQUIRE_COMPANY == 'O' ? ' class="optional"' : ''); ?>><?php echo CHECKOUT_COMPANY; ?><br />
                                                <?php echo tep_draw_input_field('ship_company', '', 'class="checkout-input-text" id="ship_company" maxlength="255"'); ?>
                                                </label>
                                                <div class="checkout-tip"><?php echo CHECKOUT_TIP_COMPANY; ?></div>
                                                <div class="checkout-form-error" id="ERROR_company"></div>
                                              </td>
                                            </tr>
<?php } ?>

                                            <?php
                                              $country_count_query = tep_db_query("SELECT countries_id, countries_name FROM " . TABLE_COUNTRIES);
                                              $country_count = tep_db_num_rows($country_count_query);
                                            ?>
                                            <tr>
                                              <td class="main" valign="top">
                                                <label for="ship_country"><?php echo CHECKOUT_COUNTRY; ?>
                                            <?php 
                                              if ($country_count > 1) { 
                                                echo '<br />' . tep_get_country_list('ship_country', ($customer['entry_country_id'] > 0 ? $customer['entry_country_id'] : $customer_default_country), 'class="checkout-select" id="ship_country" onchange="Checkout.UpdateZones(\'ship\')"'); 
                                              } else {
                                                $country = tep_db_fetch_array($country_count_query);
                                                echo '&nbsp;&nbsp;<b>' . $country['countries_name'] . '</b>';
                                              }
                                            ?>
                                                </label>
                                                <div class="checkout-tip"><?php echo CHECKOUT_TIP_COUNTRY; ?></div>
                                                <div class="checkout-form-error" id="ERROR_ship_country"></div>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td class="main" valign="top">
                                                <label for="ship_street_address"><?php echo CHECKOUT_STREET_ADDRESS; ?><br />
                                                <?php echo tep_draw_input_field('ship_street_address', '', 'class="checkout-input-text" id="ship_street_address" maxlength="255"'); ?>
                                                </label>
                                                <div class="checkout-tip"><?php echo CHECKOUT_TIP_STREET_ADDRESS; ?></div>
                                                <div class="checkout-form-error" id="ERROR_ship_street_address"></div>
                                              </td>
                                            </tr>
<?php if (CHECKOUT_REQUIRE_SUBURB != 'H') { ?>
                                            <tr>
                                              <td class="main" valign="top">
                                                <label for="ship_suburb"<?php echo (CHECKOUT_REQUIRE_SUBURB == 'O' ? ' class="optional"' : ''); ?>><?php echo CHECKOUT_SUBURB; ?><br />
                                                <?php echo tep_draw_input_field('ship_suburb', '', 'class="checkout-input-text" id="ship_suburb" maxlength="255"'); ?>
                                                </label>
                                                <div class="checkout-tip"><?php echo CHECKOUT_TIP_SUBURB; ?></div>
                                              </td>
                                            </tr>
<?php } ?>
                                            <tr>
                                              <td class="main" valign="top">
                                                <label for="ship_city"><?php echo CHECKOUT_CITY; ?><br />
                                                <?php echo tep_draw_input_field('ship_city', '', 'class="checkout-input-text" id="ship_city" maxlength="255"'); ?>
                                                </label>
<?php if (CHECKOUT_REQUIRE_STATE != 'H') { ?>
                                                <div class="state_container">
                                                  <label for="ship_state"<?php echo (CHECKOUT_REQUIRE_STATE == 'O' ? ' class="optional"' : ''); ?>><?php echo CHECKOUT_ZONE; ?>
                                                    <div id="ship_state_blk">
                                                      <?php $checkout->display_zones(($customer['entry_country_id'] > 0 ? $customer['entry_country_id'] : $customer_default_country), 'ship_state', ($customer['entry_zone_id'] < 1 ? $customer['entry_state'] : $customer['entry_zone_id']), false); ?>
                                                    </div>
                                                  </label>
                                                </div>
<?php } ?>
                                                <label for="ship_postcode"<?php echo (CHECKOUT_REQUIRE_POSTCODE == 'O' ? ' class="optional"' : ''); ?>><?php echo CHECKOUT_ZIP; ?><br />
                                                  <?php echo tep_draw_input_field('ship_postcode', '', 'class="checkout-input-text" id="ship_postcode" maxlength="10"'); ?>
                                                </label>
                                                <div class="checkout-form-error" id="ERROR_ship_city"></div>
                                                <div class="checkout-form-error" id="ERROR_ship_state"></div>
                                                <div class="checkout-form-error" id="ERROR_ship_postcode"></div>
                                              </td>
                                            </tr>

                                          </table>
                                        </div>
                                      </td>
                                    </tr>
                                  </table>
                                </tr>
                              
<?php 
  }
  if (!tep_session_is_registered('customer_id') && (CHECKOUT_CREATE_ACCOUNT && !CHECKOUT_GENERATE_PASSWORD)) { 
?>
                                <tr>
                                  <td class="checkout-spacing-2">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td class="main" valign="top" colspan="2">
                                    <label for="password"><?php echo CHECKOUT_PASSWORD; ?><br />
                                    <?php echo tep_draw_password_field('password', '', 'class="checkout-input-text" maxlength="255"'); ?>
                                    </label>
                                    <div class="checkout-tip"><?php echo CHECKOUT_ENTER_PASSWORD; ?></div>
                                    <div class="checkout-form-error" id="ERROR_password"></div>
                                  </td>
                                </tr>
<?php 
      }

      if (CHECKOUT_CONTRIB_POINTS_AND_REWARDS && tep_not_null(USE_REFERRAL_SYSTEM) && USE_POINTS_SYSTEM == 'true' && USE_REDEEM_SYSTEM == 'true') {
?>
                                <tr>
                                  <td class="checkout-spacing-1" colspan="2"></td>
                                </tr>
                                <tr>
                                  <td class="main" valign="top" colspan="2">
                                    <table border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td class="main" valign="top">
                                          <label for="customer_referred"><?php echo CHECKOUT_REFERRAL_REFERRED; ?></label>
                                        </td>
                                        <td class="main" valign="middle">
                                          <?php echo tep_draw_input_field('points_and_rewards_referred', '', 'class="checkout-input-text" id="points-and-rewards-referred" maxlength="255"'); ?>
                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
<?php
      }

      if (CHECKOUT_CONTRIB_HOW_DID_YOU_HEAR && (tep_not_null(tep_get_sources()) || DISPLAY_REFERRAL_OTHER == 'true') && (!tep_session_is_registered('referral_id') || (tep_session_is_registered('referral_id') && DISPLAY_REFERRAL_SOURCE == 'true')) ) {
?>
                                <tr>
                                  <td class="checkout-spacing-1" colspan="2"></td>
                                </tr>
                                <tr>
                                  <td class="main" valign="top" colspan="2">
                                    <table border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td class="main" valign="top">
                                          <label for="source"><?php echo ENTRY_SOURCE; ?></label>
                                        </td>
                                        <td class="main"><?php echo tep_get_source_list('source', (DISPLAY_REFERRAL_OTHER == 'true' || (tep_session_is_registered('referral_id') && tep_not_null($referral_id)) ? true : false), (tep_session_is_registered('referral_id') && tep_not_null($referral_id)) ? '9999' : '') . '&nbsp;' . (tep_not_null(ENTRY_SOURCE_TEXT) ? '<span class="inputRequirement">' . ENTRY_SOURCE_TEXT . '</span>': ''); ?></td>
                                      </tr>
<?php
        if (DISPLAY_REFERRAL_OTHER == 'true' || (tep_session_is_registered('referral_id') && tep_not_null($referral_id))) {
?>
                                      <tr>
                                        <td class="main"><label for="source_other"><?php echo ENTRY_SOURCE_OTHER; ?></label></td>
                                        <td class="main"><?php echo tep_draw_input_field('source_other', (tep_not_null($referral_id) ? $referral_id : '')) . '&nbsp;' . (tep_not_null(ENTRY_SOURCE_OTHER_TEXT) ? '<span class="inputRequirement">' . ENTRY_SOURCE_OTHER_TEXT . '</span>': ''); ?></td>
                                      </tr>
<?php
        }
?>
                                    </table>

<?php
      } else if (CHECKOUT_CONTRIB_HOW_DID_YOU_HEAR && DISPLAY_REFERRAL_SOURCE == 'false') {
        echo '</td></tr><tr><td>' . tep_draw_hidden_field('source', ((tep_session_is_registered('referral_id') && tep_not_null($referral_id)) ? '9999' : '')) . tep_draw_hidden_field('source_other', (tep_not_null($referral_id) ? $referral_id : ''));
      }
?>
                                    <div class="checkout-form-error" id="ERROR_how_did_you_hear"></div>
                                  </td>
                                </tr>
<?php
  if (!tep_session_is_registered('customer_id') && CHECKOUT_CREATE_ACCOUNT && CHECKOUT_REQUIRE_NEWSLETTER != 'H') {
?>
                                <tr>
                                  <td class="checkout-spacing-2">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td class="main" valign="top" colspan="2">
                                    <h2><?php echo CHECKOUT_HEADING_NEWSLETTER; ?></h2>
                                    <input type="checkbox" name="newsletter" value="1" checked="checked" />&nbsp;<?php echo CHECKOUT_NEWSLETTER; ?>
                                  </td>
                                </tr>
<?php
  }
?>
                              </table>
                              
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>

                    <tr>
                      <td class="checkout-spacing-1"></td>
                    </tr>
                    <tr>
                      <td colspan="2" valign="top">
                        <a name="checkout-step-3"></a>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2" class="productListing">
                          <tr>
                            <td class="checkout-productListing-heading"><img src="<?php echo CHECKOUT_WS_INCLUDES; ?>images/icon_cc.gif" />&nbsp;<b><?php echo sprintf(CHECKOUT_STEP_3, $step_count); $step_count++; ?></b></td>
                          </tr>
                          <tr>
                            <?php /* <td><?php echo isset($_SESSION['paypal_error_message']) ? $_SESSION['paypal_error_message'] : ''; ?></td> */ ?>
                            
<?php if (isset($_SESSION['paypal_error_message']) && $_SESSION['paypal_error_message'] != '') { ?>
<div id="fancybox-paypal-inner" style="display: none; top: 10px; left: 10px; width: 521px; height: 252px; overflow: visible;">
    <div class="fancybox-content">
        <img src="images/header/logo_th.png" />
        <div class="checkout-process-content">
            <h2>Oops!  We had a problem processing your order.</h2>
            Error: <?php echo $_SESSION['paypal_error_message']; ?>
            <div class="fancybox-close-container">
                <a id="closelink" href="#">Close window</a>
            </div>
        </div>
    </div>
</div>
<?php /* <div id="paypalError"><?php echo $_SESSION['paypal_error_message']; ?></div> */ ?>
<script>
$(document).ready(function(){
    $('#fancybox-paypal-inner').fancybox();
    $('#fancybox-paypal-inner').show();
    $("#fancybox-paypal-inner").trigger('click');
    $("#closelink").click(function() { 
        $.fancybox.close();
        $('#fancybox-paypal-inner').hide(); 
    });
    
})
</script>
<?php   unset($_SESSION['paypal_error_message']); ?>
<?php } ?>
                          </tr>
                          <tr class="productListing">
                            <td class="checkout-productListing-data">
                              <table border="0" width="100%" cellspacing="0" cellpadding="3">
<?php 

  if (!$ec_enabled || isset($_GET['ec_cancel']) || (!tep_session_is_registered('paypal_ec_payer_id') && !tep_session_is_registered('paypal_ec_payer_info'))) {
?>
                                <tr>
                                  <td>
                                    <div class="checkout-form-error" id="ERROR_payment"></div>
                                    <div id="checkout-payment-methods">
                                      <ul class="checkout-payment-methods">
                                    
<?php
        $radio_buttons = 0;
        $total = 0;

        
        foreach ($selections as $selection) {
          if (!in_array($selection['id'], $checkout_payment_disable)) {
            if ($payment == '') {
              $payment = $selection['id'];
            }
          
            if ((count($selections) == 1 && $selection['id'] != 'paypal_wpp') || (count($selections) == 1 && $selection['id'] == 'paypal_wpp' && MODULE_PAYMENT_PAYPAL_DP_BUTTON_PAYMENT_METHOD == 'No')) {
              $payment_input = tep_draw_hidden_field('payment', $selection['id'], $selection['id']);
            } else {
              $payment_input = tep_draw_radio_field('payment', $selection['id'], ($selection['id'] == $payment), 'id="radio-' . $selection['id'] . '"');
            }

?>
                                        <li><a class="payment-title" id="a-<?php echo $selection['id']; ?>"><?php echo $payment_input . '&nbsp;&nbsp;' . $selection['module']; ?></a>
                                          <div>
<?php
            if (isset($selection['error'])) {
?>
                                          <p class="main"><?php echo $selection['error']; ?></p>
<?php
            } elseif (isset($selection['fields']) && is_array($selection['fields'])) {
?>
                                          <table border="0" cellspacing="0" cellpadding="2">

<?php
              for ($j=0, $n2=sizeof($selection['fields']); $j<$n2; $j++) {
?>
                                            <tr>
                                              <td class="main"><?php echo $selection['fields'][$j]['title']; ?></td>
                                              <td class="main"><?php echo $selection['fields'][$j]['field']; ?></td>
                                            </tr>
<?php
              }
?>
                                          </table>

<?php
            }
?>
                                          </div>
                                        </li>
<?php
            $radio_buttons++;
          }
          
          if ($selection['id'] == 'paypal_wpp' && MODULE_PAYMENT_PAYPAL_DP_BUTTON_PAYMENT_METHOD == 'Yes') {
            $payment_input = tep_draw_radio_field('payment', 'paypal_wpp_ec', false, 'id="radio-paypal_wpp_ec"');
?>
                                        <li><a class="payment-title" id="a-paypal_wpp_ec"><?php echo $payment_input . '&nbsp;&nbsp;' . MODULE_PAYMENT_PAYPAL_EC_TEXT_TITLE; ?></a>
                                          <div><span class="main"><?php echo TEXT_PAYPALWPP_EC_HEADER; ?></span></div>
                                        </li>
<?php
            
          }
        }
?>
                                    </ul>
                                    </div>
                                  </td>
                                </tr>
<?php

  } else {
    tep_paypal_wpp_switch_checkout_method(FILENAME_CHECKOUT);
    echo '<input type="hidden" name="payment" value="paypal_wpp" />';
  }
  if (CHECKOUT_ENABLE_COUPON && CHECKOUT_DISPLAY_CART === false) { 
?>
                                <tr>
                                  <td class="checkout-spacing-1"></td>
                                </tr>
                                <tr>
                                  <td>
                                    <table border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td valign="middle" class="main" width="195" colspan="2"><?php echo CHECKOUT_PROMO_CODE; ?></td>
                                      </tr>
                                      <tr>
                                        <td class="main" valign="middle"><?php echo tep_draw_input_field('coupon', $_SESSION['coupon'], 'id="checkout-coupon" class="checkout-input-text"'); ?></td>
                                        <td class="main"><a href="javascript:void(0);" onclick="Checkout.ApplyCoupon();"><?php echo tep_image_button('button_update.gif'); ?></a></td>
                                      </tr>
                                    </table>
                                    <div id="checkout-coupon-status"></div>
                                  </td>
                                </tr>
<?php 
  } 

  if (CHECKOUT_CONTRIB_POINTS_AND_REWARDS && USE_POINTS_SYSTEM == 'true' && USE_REDEEM_SYSTEM == 'true') {
    $customer_shopping_points = tep_get_shopping_points();

    if ($customer_shopping_points > 0 && get_redemption_rules($order) == true && get_points_rules_discounted($order) == true && 
        $customer_shopping_points >= POINTS_LIMIT_VALUE && (POINTS_MIN_AMOUNT == '' || $cart->show_total() >= POINTS_MIN_AMOUNT) ) {
        
          if (tep_session_is_registered('customer_shopping_points_spending')) tep_session_unregister('customer_shopping_points_spending');
          
          $max_points = $order->info['total']/REDEEM_POINT_VALUE > POINTS_MAX_VALUE ? POINTS_MAX_VALUE : $order->info['total']/REDEEM_POINT_VALUE;
          $max_points = $customer_shopping_points > $max_points ? $max_points : $customer_shopping_points;
          
          if ($order->info['total'] > tep_calc_shopping_pvalue($max_points)) {
            $note = '<br /><small>' . TEXT_REDEEM_SYSTEM_NOTE .'</small>';
          }
          
          $customer_shopping_points_spending = $max_points;
      ?>
                                <tr>
                                  <td class="checkout-spacing-1"></td>
                                </tr>
                                <tr>
                                  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td class="main"><b><?php echo CHECKOUT_HEADING_REDEEM_SYSTEM; ?></b></td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                                  <tr class="infoBoxContents">
                                    <td style="padding: 0 10px"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td class="main"><?php printf(CHECKOUT_REDEEM_SYSTEM_START, $currencies->format(tep_calc_shopping_pvalue($customer_shopping_points))); ?></td>
                                      </tr>
                                      <tr class="moduleRow">
                                        <td class="main">
                                          <?php 
                                            echo tep_draw_checkbox_field('customer_shopping_points_spending', '1', '', 'onclick="Checkout.ApplyPoints(this.checked)"'); 
                                            printf(CHECKOUT_REDEEM_SYSTEM_SPENDING, number_format($max_points,POINTS_DECIMAL_PLACES)); 
                                          ?>
                                        </td>
                                     </tr>
                                    </table></td>
                                  </tr>
                                </table></td>
                              </tr>
      <?php 
    }
  }
?>  

                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td class="checkout-spacing-1"></td>
                    </tr>
<?php  
  if ($shipping_enabled) { 
?>
                    <tr>
                      <td colspan="2" valign="top">
                        <a name="checkout-step-4"></a>
                          <table border="0" width="100%" cellspacing="0" cellpadding="2" class="productListing">
                            <tr>
                              <td class="checkout-productListing-heading"><img src="<?php echo CHECKOUT_WS_INCLUDES; ?>images/icon_shipping.gif" />&nbsp;<b><?php echo sprintf(CHECKOUT_STEP_4, $step_count); $step_count++; ?></b></td>
                            </tr>
                            <tr class="productListing">
                              <td class="checkout-productListing-data">
                                <div class="checkout-form-error" id="ERROR_shipping"></div>
                                <div id="checkout-shipping-quotes">
<?php
    if (!CHECKOUT_DYNAMIC_SHIPPING) {
      $shipping_options = $checkout->get_payment_shipping(false);
      $shipping_options = $shipping_options['shipping'];
      
      echo '<table border="0" width="100%" cellspacing="0" cellpadding="2">';
      
      $row = 0;
      foreach ($shipping_options as $quote) {
        if ($row > 0) {
          echo '<tr><td colspan="3" class="checkout-spacing-1">&nbsp;</td></tr>';
        }
        
        echo '<tr><td class="main" colspan="2">';
        if (isset($quote['icon']) && $quote['icon'] != '') {
          echo $quote['icon'] . '&nbsp;';
        }
        
        echo '<b>' . $quote['module'] . '</b>';
        
        echo '</td></tr>';
        echo '<tr>';
        
        if (isset($quote['error']) && $quote['error'] != '') {
          echo '<tr><td class="main" colspan="2">' . $quote['error'] . '</td></tr>';
        } else {
          foreach ($quote['methods'] as $method) {
            $checked = '';
            
            if ($method['cheapest'] == '1') {
              $checked = ' CHECKED';
            }
            echo '<tr>';
            echo '<td class="main"><input type="radio" name="shipping" id="' . $quote['id'] . '_' . $method['id'] . '" value="' . $quote['id'] . '_' . $method['id'] . '"' . $checked . ' />&nbsp;' . $method['title'] . '</td>';
            echo '<td class="main" width="80" align="right">' . $method['cost'] . '</td>';
            echo '</tr>';
          }
        }
        
        
        
        $row++;
      }
      
      echo '</table>';
    }
?>
                                </div>
                              </td>
                            </tr>

                          </table>
  
                      </td>
                    </tr>
                    <tr>
                      <td class="checkout-spacing-1"></td>
                    </tr>
<?php } ?>
                    <tr>
                      <td colspan="2" valign="top">
                        <a name="section_7"></a>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2" class="productListing">
                          <tr>
                            <td class="checkout-productListing-heading"><img src="<?php echo CHECKOUT_WS_INCLUDES; ?>images/icon_confirm_order.gif">&nbsp;<b><?php echo sprintf(CHECKOUT_STEP_5, $step_count); ?></b></td>
                          </tr>
                          <tr class="productListing">
                            <td class="checkout-productListing-data">
                              
                              <table border="0" width="100%" cellspacing="0" cellpadding="4">
                                <tr>
                                  <td>
                                    <div id="checkout-lower-shopping-cart">
                                      <table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  $products = $cart->get_products();
  $i = 0;
  if (count($products) > 0) {
    foreach ($products as $key => $p) {
     
      echo '<tr>';
      echo '  <td class="main" align="right" valign="top" width="30">' . $p['quantity'] . '&nbsp;x</td>';
      echo '  <td class="main" valign="top"><span class="orderEdit2">' . $p['name'] . '</span><br>';
      if (count($p['attributes'])) {
        foreach ($p['attributes'] as $option => $value) {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$p['id'] . "'
                                       and pa.options_id = '" . (int)$option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$languages_id . "'
                                       and poval.language_id = '" . (int)$languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);
          
          if (CHECKOUT_CONTRIB_OPTIONS_TYPE) {
            if ($value == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {                       

              $attr_value = $p['attributes_values'][$option];
              $attr_name_sql_raw = 'SELECT po.products_options_name FROM ' .
                TABLE_PRODUCTS_OPTIONS . ' po, ' .
                TABLE_PRODUCTS_ATTRIBUTES . ' pa WHERE ' .
                ' pa.products_id="' . tep_get_prid($p['id']) . '" AND ' .
                ' pa.options_id="' . $option . '" AND ' .
                ' pa.options_id=po.products_options_id AND ' .
                ' po.language_id="' . $languages_id . '" ';
              $attr_name_sql = tep_db_query($attr_name_sql_raw);
              if ($arr = tep_db_fetch_array($attr_name_sql)) {
                $attr_name  = $arr['products_options_name'];
              }
              
            } else {
              $attr_value = $attributes_values['products_options_values_name'];
              $attr_name  = $attributes_values['products_options_name'];
            }
          } else {
            $attr_name = $attributes_values['products_options_name'];
            $attr_value =  $attributes_values['products_options_values_name'];
          }

          echo '    <nobr><small>&nbsp;<i> - ' . $attr_name . ': ' . $attr_value . '</i></small></nobr></span><br>';
        }
      }
      echo '  </td>';
      echo '  <td class="main" align="right" valign="top">' . $currencies->display_price($p['final_price'], tep_get_tax_rate($p['tax_class_id'])) . '</td>';
      echo '</tr>';
      $i++;
    }
  }
  //echo "SHOW TABLES FROM  terrae2_osCommerce";
//$a=mysql_query("select * from  configuration");
//while($row = mysql_fetch_array($a)){
//echo "<pre>";
print_r($row);
//}
?>
                                      </table>
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td class="checkout-dashed-line" colspan="3">
                                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td>
                                          <table border="0" cellspacing="0" cellpadding="2">
<?php 
    if (CHECKOUT_CONTRIB_GIFTWRAP) { 
      require(DIR_WS_CLASSES . 'gift.php');
      $giftwrap_modules = new gift;
      if (tep_session_is_registered('giftwrap_info')) tep_session_unregister('giftwrap_info');
      
      $quote1 = $giftwrap_modules->quote1();

      if (tep_count_giftwrap_modules() > 0) {
        $yes_price = 0;
        $no_price = 0;

        foreach ($quote1[0]['methods'] as $q) {
          if ($q['id'] == 'giftwrap') {
            $yes_price = $q['cost'];
          } elseif ($q['id'] == 'nogiftwrap') {
            $no_price = $q['cost'];
          }
        }
        
        if (DISPLAY_PRICE_WITH_TAX == true && MODULE_ORDER_TOTAL_GIFTWRAP_TAX_CLASS > 0) {
          $giftwrap_tax = tep_get_tax_rate(MODULE_ORDER_TOTAL_GIFTWRAP_TAX_CLASS);
          $yes_price = $yes_price + tep_calculate_tax($yes_price, $giftwrap_tax);
          $no_price = $no_price + tep_calculate_tax($no_price, $giftwrap_tax);
        }
?>

                                            <tr>
                                              <td class="main" valign="top"><b><?php echo CHECKOUT_GIFTWRAP_METHOD; ?></b></td>
                                              <td class="main" valign="top"><input type="radio" name="giftwrap" value="giftwrap_giftwrap" onclick="Checkout.ApplyGiftWrap()" />Yes (+<?php echo $currencies->format($yes_price); ?>)<br /><input type="radio" name="giftwrap" value="nogiftwrap_nogiftwrap" onclick="Checkout.ApplyGiftWrap()" CHECKED />No (+<?php echo $currencies->format($no_price); ?>)</td>
                                            </tr>
<?php 
      } else {
        tep_session_unregister('giftwrap_info');
      }
    }
?>
                                            <tr>
                                              <td class="main" valign="top" colspan="2">
                                                <?php 
                                                  echo '<b>' . CHECKOUT_COMMENTS . '</b><br />';
                                                  echo CHECKOUT_TIP_COMMENTS . '<br />';
                                                  echo tep_draw_textarea_field('comments', 'soft', '5', '5', '', 'style="width:300px;height:40px"'); 
                                                ?>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td class="main" valign="top" colspan="2">
                                                <?php 
                                                  echo '<b>' . CHECKOUT_COMMENTS_SLIP . '</b><br />';
                                                  echo CHECKOUT_TIP_COMMENTS_SLIP . '<br />';
                                                  echo tep_draw_textarea_field('comments_slip', 'soft', '5', '5', '', 'style="width:300px;height:40px"'); 
                                                ?>
                                              </td>
                                            </tr>
                                          </table>
                                        </td>
                                        <td align="right" valign="top" width="150" style="padding-right:0">
                                          <div class="checkout-order-total">
                                            Calculating totals...
                                          </div>
                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>

                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td class="checkout-spacing-1"></td>
                    </tr>
                    <tr>
                      <td class="main" align="right">

                        <div id="dynamo-effects-logo"><a href="http://www.dynamoeffects.com" target="_BLANK"><img src="<?php echo CHECKOUT_WS_INCLUDES; ?>images/dynamo_effects.gif" alt="Dynamo Effects" border="0" /></a></div>
                     <!--<input type="submit" value="test" />   -->
			<div id="confirm_button">
                        <?php if (CHECKOUT_CONTRIB_TERMS_CONDITIONS) { ?>
                          <div class="checkout-form-error" id="ERROR_accept_terms"></div>
                          <input type="checkbox" name="accept_terms" value="1" /><?php echo sprintf(CHECKOUT_ACCEPT_TERMS, '<a href="#checkout-terms" class="checkout-lightbox-link">'); ?><br /><br />
                        <?php } ?>
                          <a href="#checkout-process" id="checkout-process-link">
                            <img src="<?php echo DIR_WS_LANGUAGES . $language . '/images/buttons/button_confirm_order.gif'; ?>" border="0">
                          </a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
          </table>
        </td>
<?php if (CHECKOUT_RIGHT_COLUMN) { ?>
        <td class="checkout-column-right" valign="top">
          <table border="0" width="100%" cellspacing="0" cellpadding="2" class="productListing">
            <tr>
             <td class="checkout-productListing-heading"><img src="<?php echo CHECKOUT_WS_INCLUDES; ?>images/icon_padlock.gif">&nbsp;<b><?php echo CHECKOUT_BOX_HEADING_1; ?></b></td>
            </tr>
            <tr class="productListing">
              <td class="checkout-productListing-data">
                <table border="0" width="100%" cellspacing="0" cellpadding="3">
                  <tr>
                    <td class="productListing-td main" height="100%" valign="top" style="padding: 8px">
                      <?php echo CHECKOUT_BOX_CONTENT_1; ?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <br />
          <table border="0" width="100%" cellspacing="0" cellpadding="2" class="productListing">
            <tr>
              <td class="checkout-productListing-heading"><img src="<?php echo CHECKOUT_WS_INCLUDES; ?>images/icon_info.gif">&nbsp;<b><?php echo CHECKOUT_BOX_HEADING_2; ?></b></td>
            </tr>
            <tr class="productListing">
              <td class="checkout-productListing-data">
                <table border="0" width="100%" cellspacing="0" cellpadding="3">
                  <tr>
                    <td class="productListing-td main" height="100%" valign="top" style="padding: 8px" >
                      <?php echo CHECKOUT_BOX_CONTENT_2; ?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
<?php } ?>
      </tr>
    </table>
    </form>
<div id="checkout-login-window" class="checkout-lightbox-inline">
  <div id="checkout-login">
<?php include(CHECKOUT_WS_TPL . 'checkout_login.php'); ?>
  </div>
</div>
<div id="checkout-process-window" class="checkout-lightbox-inline">
  <div id="checkout-process">
<?php include(CHECKOUT_WS_TPL . 'checkout_process.php'); ?>
  </div>
</div>
<div id="checkout-terms-window" class="checkout-lightbox-inline">
  <div id="checkout-terms">
<?php include(CHECKOUT_WS_TPL . 'checkout_terms.php'); ?>
  </div>
</div>
<?php if (CHECKOUT_CONTRIB_STS) { ?>
<script type="text/javascript">try { Checkout.loadingTimer = setTimeout("Checkout.Init();", 4000); } catch(e) {}</script>
<?php } ?>
    </td>
  </tr>
</table>
<!-- body_eof //-->
<?php
  if (CHECKOUT_CONTRIB_STS_2) {
    $sts_block_name = 'columnleft2columnright';
    include(STS_RESTART_CAPTURE);
  } elseif (CHECKOUT_CONTRIB_STS_4) {
    $sts->restart_capture('content');
  }
?>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<script type="text/javascript">try { Checkout.loadingTimer = setTimeout("Checkout.Init();", 4000); } catch(e) {}</script>



<div style="display: none;">

<?php

//Log order into holding tables before checkout is passed to payment processor

$holding_order_totals = $order_total_modules->process();

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
	                          'cc_type' => $order->info['cc_type'], 
	                          'cc_owner' => $order->info['cc_owner'], 
	                          'cc_number' => $order->info['cc_number'], 
	                          'cc_expires' => $order->info['cc_expires'], 
	                          'date_purchased' => 'now()', 
	                          //'orders_status' => DEFAULT_ORDERS_STATUS_ID, 
	                          'orders_status' => '0', 
	                          'comments' => $order->info['comments'], 
	                          'currency' => $order->info['currency'], 
	                          'currency_value' => $order->info['currency_value']);
//echo '<pre>'; print_r($sql_data_array); echo '</pre>'; //exit();
			 //tep_db_perform(TABLE_HOLDING_ORDERS, $sql_data_array);
			 //$insert_id = tep_db_insert_id();
			//$_SESSION['insert_order_id'] = $insert_id;

		   for ($i=0, $n=sizeof($holding_order_totals); $i<$n; $i++) {
		   	      $sql_data_array = array('orders_id' => $insert_id,
		   	                              'title' => $holding_order_totals[$i]['title'],
		   	                              'text' => $holding_order_totals[$i]['text'],
		   	                              'value' => $holding_order_totals[$i]['value'], 
		   	                              'class' => $holding_order_totals[$i]['code'], 
		   	                              'sort_order' => $holding_order_totals[$i]['sort_order']);
//echo '<pre>'; print_r($sql_data_array); echo '</pre>'; //exit();
		   //tep_db_perform(TABLE_HOLDING_ORDERS_TOTAL, $sql_data_array);
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
//echo '<pre>'; print_r($sql_data_array); echo '</pre>'; //exit();
       //tep_db_perform(TABLE_HOLDING_ORDERS_PRODUCTS, $sql_data_array);
       //$order_products_id = tep_db_insert_id();
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
       	//tep_db_perform(TABLE_HOLDING_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
      	}
      }
    }
          
//End log order before payment

?>

</div>



</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>