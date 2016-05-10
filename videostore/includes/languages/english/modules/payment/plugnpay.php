<?php
/*
  $Id: authorizenet.php,v 1.9 2002/01/04 10:45:18 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_PLUGNPAY_TEXT_TITLE', 'Mastercard, Visa &nbsp;&nbsp;&nbsp;<img src="images/cc.gif" width="100" height="35">');;
  define('MODULE_PAYMENT_PLUGNPAY_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC Owner: pnptest<br> CC#: 4111111111111111<br>Expiry: Any');
  define('MODULE_PAYMENT_PLUGNPAY_TEXT_TYPE', 'Type:');
  define('MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_OWNER', 'Name On Card:');
  define('MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:4111111111111111');
  define('MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_CVV', 'CVV/CVV2:');
  define('MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiry Date:');
  define('MODULE_PAYMENT_PLUGNPAY_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_PLUGNPAY_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_PLUGNPAY_TEXT_ERROR_MESSAGE', 'There has been an error processing your credit card, please try again.');
  define('MODULE_PAYMENT_PLUGNPAY_TEXT_ERROR', 'Credit Card Error!');
?>