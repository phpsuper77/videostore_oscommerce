<?php
  /*
   * Dynamo Checkout by Dynamo Effects
   * Copyright 2008 - Dynamo Effects.  All Rights Reserved
   * http://www.dynamoeffects.com

   
   * Enable/Disable Dynamo Checkout Sitewide
   * By setting this option to "false," the original checkout system
   * will instantly take over the checkout process.  You can still access
   * and test the one page checkout by typing in the URL directly.
   */
  define('CHECKOUT_ONEPAGE_ENABLED', true);
 
  /*
   * This is the logo that will be displayed throughout
   * the checkout process
   */
  define('CHECKOUT_IMG_LOGO', 'images/header/logo_th.png');
  
  /*
   * This is the default country that will be selected in 
   * the checkout if user's location can't be determined.  
   * Enter the two-letter country code (US for United States, 
   * GB for Great Britain, CA for Canada, etc)
   */
  define('CHECKOUT_COUNTRY_DEFAULT', 'US');
   
  /*
   * If you would you like non-logged in members to be logged in 
   * during the checkout process, set this to "true."
   */
  define('CHECKOUT_AUTO_LOGIN', true);
  
  /*
   * Display or hide the shopping cart
   */
  define('CHECKOUT_DISPLAY_CART', true);
  
  /*
   * Display product image in the cart
   */
   define('CHECKOUT_CART_IMAGES', true);
  
  /* 
   * Display or hide right column
   */
  define('CHECKOUT_RIGHT_COLUMN', true);
  
  /*
   * Send Bug Reports to Dynamo Effects
   * To continuously improve this product, error reports from live shops
   * are imperative to locate and fix problems.  Set this to "true" to enable it 
   * and any unexpected errors are sent to Dynamo Effects for immediate action
   */
  define('CHECKOUT_SEND_BUG_REPORTS', true);
  
  /* 
   * Dynamic Payment
   * Set this to "true" if your payment modules are zone sensitive.  Typically this
   * should be set to false if everyone can use the same payment methods
   */
  define('CHECKOUT_DYNAMIC_PAYMENT', false);
   
  /* 
   * Dynamic Shipping
   * Set this to "false" if you only use flat or per-item shipping modules
   * where the price isn't location dependant
   */
  define('CHECKOUT_DYNAMIC_SHIPPING', true);

  /*
   * Debug Mode
   * This will output debug information to a text file
   * when a customer experiences an error
   */
  define('CHECKOUT_DEBUG_MODE', true);
  
  /*
   * Automatically create accounts during the checkout
   * process.  If set to true, email field will be visible
   * no matter what you set below.  If set to "false," the password field 
   * will be hidden no matter what you set below.
   */
  define('CHECKOUT_CREATE_ACCOUNT', true);
  
  /*
   * Automatically generate new account passwords
   * You can shorten the checkout process even further by hiding the password field
   * and automatically generating the password during the checkout process.  Set to
   * "true" to automatically generate passwords.  CHECKOUT_CREATE_ACCOUNT must be 
   * set to "true" for this to take effect
   */
   define('CHECKOUT_GENERATE_PASSWORD', true);
   
  /*
   * Auto-Generated Password Length
   * This is the length of the automatically generated password.  If CHECKOUT_GENERATE_PASSWORD
   * is set to "false," this has no effect
   */
  define('CHECKOUT_PASSWORD_MIN_LENGTH', 8);
   
   
   /*
    * Require billing address be used as shipping address
    * Setting this to "true" will completely remove the shipping address section of the
    * checkout forcing the customer to use their billing address as their shipping address.
    */
   define('CHECKOUT_FORCE_BILLING_ADDRESS', false);
   
   
   /*
    * Override the Shopping Cart
    * Setting this to true will replace the normal shopping cart with the one page checkout.
    */
   define('CHECKOUT_OVERRIDE_SHOPPING_CART', false);
   
   /*
    * Enable Payment Processor Safeguard (Part 1)
    * When using a payment module that sends the customer to a third-party payment website,
    * at times the third-party website may timeout or the customer's connection may reset.  Set this
    * value to the number of seconds that you would like to wait before taking the next action in step #2. 
    * Setting this value to "0" (zero) will disable this safeguard altogether.
    */
   define('CHECKOUT_PAYMENT_PROCESSOR_SAFEGUARD_TIMEOUT', 30);
   
   /*
    * Payment Processor Safeguard (Part 2)
    * If the third-party payment safeguard is enabled (not zero), how many times would you like 
    * to retry sending the information to the payment website before displaying an error?
    */
   define('CHECKOUT_PAYMENT_PROCESSOR_SAFEGUARD_RETRIES', 1);
   
   /*
    * Payment Processor Safeguard (Part 3)
    * If the third-party payment safeguard is enabled (not zero) and the number of retries is greater than zero,
    * would you like to ask the customer if they would like to retry or should be be automatic?  
    * Set this to "true" if you would like to prompt the customer to click the "retry" button or choose another payment method
    * Set this to "false" if you would like it to retry loading the payment processor's page automatically
    */
   define('CHECKOUT_PAYMENT_PROCESSOR_SAFEGUARD_PROMPT', false);
   
   /*
    * State/Province Smart Abbreviation
    * The state/province field is abbreviated for 6 countries where that is standard practice.  You can enable or disable
    * this functionality if you would like.
    *
    * You have three options:
    *   smart - abbreviate only the 6 countries' states/provinces where it is common practice
    *   full  - show full state/province names for all countries
    *   short - show abbreviations for all state/province names for all countries
    */
   define('CHECKOUT_STATE_ABBREVIATION', 'full');
   
   /* 
    * Disable Payment Modules 
    * If you would like to disable certain payment modules on the checkout page, list them here
    * For example:
    * $checkout_payment_disable = array('paypal_ec', 'googlecheckout');    
    */
   $checkout_payment_disable = array('google', 'googlecheckout', 'quickpayedankort', 'quickpaynetbank');

  /*
   * Required Fields
   * R = Required and displayed
   * O = Optional and displayed
   * H = Not required and hidden
   *
   * Note that disabling too many fields might make your checkout 
   * behave strangely.  For instance some shipping modules require
   * the city, state, zip code and country and some payment modules
   * won't process an order without a full address
   */
   
  define('CHECKOUT_REQUIRE_EMAIL', 'R');
  define('CHECKOUT_REQUIRE_COMPANY', 'O');
  define('CHECKOUT_REQUIRE_GENDER', 'H');
  define('CHECKOUT_REQUIRE_DOB', 'H');
  define('CHECKOUT_REQUIRE_SUBURB', 'O'); 
  define('CHECKOUT_REQUIRE_STATE', 'R');
  define('CHECKOUT_REQUIRE_POSTCODE', 'R'); //R or O only
  define('CHECKOUT_REQUIRE_TELEPHONE', 'O');
  define('CHECKOUT_REQUIRE_NEWSLETTER', 'O'); //O or H only
  
  
  /******************************************
   *************Contributions****************
   ******************************************/

  /* Enable/Disable CCGV support */
  define('CHECKOUT_CONTRIB_CCGV', true);
  
  /* Enable/disable kgt Discount Coupon support */
  define('CHECKOUT_CONTRIB_DISCOUNT_COUPON', false);
  
  /* Enable STS v4 support */
  define('CHECKOUT_CONTRIB_STS_4', false);
  
  /* Enable STS v2 support (You should upgrade to STS v4) */
  define('CHECKOUT_CONTRIB_STS_2', false);
  
  /* Enable IPISP support (Records ISP and IP) */
  define('CHECKOUT_CONTRIB_IPISP', true);
  
  /* 
   * Enable How Did you Hear About Us support 
   * http://addons.oscommerce.com/info/2159
   */
  define('CHECKOUT_CONTRIB_HOW_DID_YOU_HEAR', false);
  
  /* 
   * Enable Points & Rewards support 
   * http://addons.oscommerce.com/info/3220
   */
  define('CHECKOUT_CONTRIB_POINTS_AND_REWARDS', false);
  
  /* Enable Products Attributes Option Type support */
  define('CHECKOUT_CONTRIB_OPTIONS_TYPE', false);
  
  /* Enable Giftwrap support */
  define('CHECKOUT_CONTRIB_GIFTWRAP', true);
  
  /* Enable Terms & Conditions */
  define('CHECKOUT_CONTRIB_TERMS_CONDITIONS', false);
  
  /* Enable Unique Order Number support */
  define('CHECKOUT_CONTRIB_UNIQUE_ORDER_NUMBER', false);
  
  /* Enable Get 1 Free support */
  define('CHECKOUT_CONTRIB_GET_1_FREE', false);
  
  /* Enable Online Gift Card support */
  define('CHECKOUT_CONTRIB_GIFT_CARD', false);
  
  
  
  
  
  
  /******************************************
   * DO NOT MODIFY ANYTHING BELOW THIS LINE *
   ******************************************/
   
  /* The location of the checkout support files. */
  define('CHECKOUT_WS_INCLUDES', 'includes/checkout/');
  define('CHECKOUT_WS_LIB', CHECKOUT_WS_INCLUDES . 'lib/');
  define('CHECKOUT_WS_TPL', CHECKOUT_WS_INCLUDES . 'tpl/');
  define('CHECKOUT_WS_JS', CHECKOUT_WS_INCLUDES . 'js/');
  define('CHECKOUT_WS_CSS', CHECKOUT_WS_INCLUDES . 'css/');
  
  define('FILENAME_CHECKOUT_NOTIFICATION', CHECKOUT_WS_INCLUDES . 'checkout_notification.php');
  
  /*
   * Loading Bar Image
   * This image is displayed when assets are loading
   */
  define('CHECKOUT_IMG_LOADING', CHECKOUT_WS_INCLUDES . 'images/loading_bar.gif');
  
  define('TABLE_CHECKOUT_ORDERS', 'checkout_orders');
  define('TABLE_CHECKOUT_ORDERS_PRODUCTS', 'checkout_orders_products');
  define('TABLE_CHECKOUT_ORDERS_PRODUCTS_ATTRIBUTES', 'checkout_orders_products_attributes');
  define('TABLE_CHECKOUT_ORDERS_PRODUCTS_DOWNLOAD', 'checkout_orders_products_download');
  define('TABLE_CHECKOUT_ORDERS_STATUS_HISTORY', 'checkout_orders_status_history');
  define('TABLE_CHECKOUT_ORDERS_TOTAL', 'checkout_orders_total');
   

  
  if (CHECKOUT_CONTRIB_STS_4 || CHECKOUT_CONTRIB_STS_2) {
    define('CHECKOUT_CONTRIB_STS', true);
  } else {
    define('CHECKOUT_CONTRIB_STS', false);
  }
  
  @define('CHECKOUT_ONE_PAGE', false);
    
  define('FILENAME_CHECKOUT', 'checkout.php');
  
  if (CHECKOUT_ONEPAGE_ENABLED) {
    /* PayPal WPP Express Checkout Support */
    if ($_GET['action'] == 'express_checkout') {
    
      if (basename($_SERVER['PHP_SELF']) == 'checkout_process.php' && $_GET['action'] == 'express_checkout' && isset($_GET['token']) && isset($_GET['PayerID'])) {
        header('Location: ' . HTTPS_SERVER . DIR_WS_HTTPS_CATALOG . FILENAME_CHECKOUT_NOTIFICATION . '?module=paypal_wpp_ec&token=' . $_GET['token'] . '&PayerID=' . $_GET['PayerID']);
        die();
      } else {
        header('Location: ' . HTTPS_SERVER . DIR_WS_HTTPS_CATALOG . FILENAME_CHECKOUT . '?payment=paypal_wpp_ec');
        die();
      }
    }
    if (strpos(basename($_SERVER['PHP_SELF']), 'checkout_') === false && strpos(basename($_SERVER['PHP_SELF']), 'ec_shipping') === false) {
      define('FILENAME_CHECKOUT_SHIPPING', 'checkout.php');
    }

    /* Worldpay support */
    if (strpos($_SERVER['PHP_SELF'], 'wpcallback.php') > 0) {
      define('FILENAME_CHECKOUT_PROCESS', CHECKOUT_WS_INCLUDES . 'checkout_notification.php');
      define('FILENAME_CHECKOUT_PAYMENT', CHECKOUT_WS_INCLUDES . 'checkout_notification.php');
      
      $_POST['cartId'] .= '&transStatus=' . $_POST['transStatus'];
      $cartId = $_POST['cartId'];
    }
    
    /* Protx Direct Support */
    if (strpos($_SERVER['PHP_SELF'], 'protx_process.php') > 0) {
      define('FILENAME_CHECKOUT_PROCESS', CHECKOUT_WS_INCLUDES . 'checkout_notification.php');
      define('FILENAME_CHECKOUT_PAYMENT', FILENAME_CHECKOUT);
    }
    /* EWay Payment Support */
    if (strpos($_SERVER['PHP_SELF'], 'ewaypayment/pay.php') > 0 && $_GET['payment_method'] == 'eWayPayment') {
      define('FILENAME_CHECKOUT_PROCESS', CHECKOUT_WS_INCLUDES . 'checkout_notification.php');
      define('FILENAME_CHECKOUT_PAYMENT', FILENAME_CHECKOUT);
    }
    /* PayPal Express Support */
    if (strpos($_SERVER['PHP_SELF'], 'ext/modules/payment/paypal/express.php') !== false ||
        strpos($_SERVER['PHP_SELF'], 'ext/modules/payment/paypal/express_uk.php') !== false) {
        define('CHECKOUT_ONE_PAGE', true);
      define('FILENAME_CHECKOUT_CONFIRMATION', CHECKOUT_WS_INCLUDES . 'checkout_notification.php');
      define('FILENAME_SHOPPING_CART', CHECKOUT_WS_INCLUDES . 'checkout_process.php');
    }
    
    /* SagePay Form / Protx Form Support */
    if ($_POST['payment'] == 'sagepay_form' || $_SESSION['checkout_payment'] == 'sagepay_form' || $_POST['payment'] == 'sage_pay_form' || $_SESSION['checkout_payment'] == 'sage_pay_form') {
      define('FILENAME_CHECKOUT_PROCESS', CHECKOUT_WS_INCLUDES . 'checkout_notification.php');
      define('FILENAME_CHECKOUT_PAYMENT', FILENAME_CHECKOUT);
    }
    
    /* Bankpass */
    if ($_POST['payment'] == 'bankpass') {
      define('FILENAME_CHECKOUT_PROCESS', CHECKOUT_WS_INCLUDES . 'checkout_notification.php');
    }
    
    /* EPay */
    if (strpos($_SERVER['PHP_SELF'], 'checkout_epay_accept.php') !== false) {
      define('FILENAME_CHECKOUT_PAYMENT', FILENAME_CHECKOUT);
      define('FILENAME_CHECKOUT_SHIPPING', FILENAME_CHECKOUT);
    }
    /* Quickpay DK */
    define('MODULE_PAYMENT_QUICKPAY_LOCALFORM', 'True');
    /* Dibs */
    define('MODULE_PAYMENT_DIBS_CALLBACK', 'NO');
    define('FILENAME_CHECKOUT_CANCELLED_DIBS', CHECKOUT_WS_INCLUDES . 'checkout_notification.php');
    
    /* PayPal WPP 3DSecure Support */
    if (strpos($_SERVER['PHP_SELF'], FILENAME_CHECKOUT) !== false && $_GET['checkout_payment'] == 'paypal_wpp' && $_GET['action'] == 'cardinal_centinel_auth') {
?>
      <script type="text/javascript">
        parent.$('iframe#checkout-gateway').bind('load', function() { try { Checkout.LoadingComplete(); } catch(e) {} });
<?php
      foreach ($_POST as $key => $var) {
        echo 'parent.$("form[name=checkout]").append(\'<input type="hidden" name="' . $key . '" value="' . str_replace('"', '\"', $var) . '">\');';
      }
?>
        parent.$("#checkout-process-link").trigger("click");
      </script>
<?php
      die();
    }
  }
  
  /* Don't overwrite the shopping cart page if you're already there */
  if (CHECKOUT_ONEPAGE_ENABLED && CHECKOUT_OVERRIDE_SHOPPING_CART && strpos(basename($_SERVER['PHP_SELF']), 'shopping_cart.php') === false) {
    define('FILENAME_SHOPPING_CART', 'checkout.php');
    define('FILENAME_REAL_SHOPPING_CART', 'shopping_cart.php');
  } else {
    define('FILENAME_REAL_SHOPPING_CART', 'shopping_cart.php');
  }
  
  define('CHECKOUT_VERSION', '1.9.6');
  
  if (CHECKOUT_ONE_PAGE) define('GZIP_COMPRESSION', 'false');
?>