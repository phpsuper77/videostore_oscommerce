<?php
/*
  $Id: login.php,v 1.14 2003/06/09 22:46:46 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Login');
define('HEADING_TITLE', 'Welcome, Please Sign In');

// +('HEADING_NEW_CUSTOMER', 'New Customer');
define('TEXT_NEW_CUSTOMER', 'New Customer Benefits'); // Login Page a la Amazon
define('TEXT_NEW_CUSTOMER', 'I am a new customer.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'By creating an account at ' . STORE_NAME . ' you will be able to shop faster, be up to date on an orders status, provide product reviews keep track of the orders you have previously made and many other features.');

define('HEADING_NEW_CUSTOMER', 'New Customer');

define('HEADING_RETURNING_CUSTOMER', 'Returning Customer');
define('TEXT_RETURNING_CUSTOMER', 'I am a returning customer.');

define('TEXT_PASSWORD_FORGOTTEN', 'Password forgotten? Click here.');

define('TEXT_LOGIN_ERROR', 'Error: No match for E-Mail Address and/or Password.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>Note:</b></font> Your &quot;Visitors Cart&quot; contents will be merged with your &quot;Members Cart&quot; contents once you have logged on. <a href="javascript:session_win();">[More Info]</a>');
// Begin Checkout Without Account v0.70 changes

define('PWA_FAIL_ACCOUNT_EXISTS', 'An account already exists for the email address <i>{EMAIL_ADDRESS}</i>.  You must login here with the password for that account before proceeding to checkout, if you do not recall your password, select the Password Forgotten, Click Here, and a new password will be sent to you.');

// Begin Checkout Without Account v0.60 changes

define('HEADING_CHECKOUT', 'Proceed Directly to Checkout Without Setting Up an Account');
define('TEXT_CHECKOUT_INTRODUCTION', 'Proceed to Checkout without creating an account. By choosing this option none of your user information will be kept in our records, and you will not be able to review your order status, nor keep track of your previous orders.');
define('PROCEED_TO_CHECKOUT', 'Proceed to Checkout without Registering');

// End Checkout Without Account changes
// +Login Page a la Amazon
define('TEXT_EMAIL_QUERY', 'What is your e-mail address?');
define('TEXT_EMAIL_IS', 'My e-mail address is:');
define('TEXT_HAVE_PASSWORD', 'Do you have a '. STORE_NAME . ' password?');
define('TEXT_HAVE_PASSWORD_NO', 'No, I am a new customer.');
define('TEXT_HAVE_PASSWORD_YES', 'Yes, my password is:');
// -Login Page a la Amazon
?>
