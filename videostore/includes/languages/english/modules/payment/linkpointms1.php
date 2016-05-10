<?php
/*
  $Id: linkpointms1.php,v 1.4 2002/11/01 05:35:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_LINKPOINTMS1_TEXT_TITLE', 'Credit Card');
  define('MODULE_PAYMENT_LINKPOINTMS1_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any');
  define('LINKPOINTMS1_ERROR_HEADING', 'There has been an error processing your credit card');
  define('LINKPOINTMS1_ERROR_MESSAGE', 'Please check your credit card details, Check that you are not using any special Characters in your billing or shipping address fields as they can cause communication errors!');
  define('MODULE_PAYMENT_LINKPOINTMS1_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_LINKPOINTMS1_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_LINKPOINTMS1_TEXT_CREDIT_CARD_EXPIRES', 'Expiration Date:');
  define('MODULE_PAYMENT_LINKPOINTMS1_TEXT_CREDIT_CARD_CHECKNUMBER', 'Card Verification Value:');
  define('MODULE_PAYMENT_LINKPOINTMS1_TEXT_ERROR_MESSAGE', 'There has been an error processing your credit card, please try again or call us at 1-800-288-5123 for assistance');
  define('MODULE_PAYMENT_LINKPOINTMS1_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_LINKPOINTMS1_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_LINKPOINTMS1_CVS_NOT_PRESENT', '  Not present');
  define('TEXT_SEARCH_CVS_HELP', 'What is my CVV Number[?]');
  define('MODULE_PAYMENT_LINKPOINTMS1_CARD_DETECTION', 'Card type detection is automatic.');
  
?>