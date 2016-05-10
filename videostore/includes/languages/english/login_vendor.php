<?php
/*
  $Id: login_vendor.php,v 1.14 2003/06/09 22:46:46 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Login');
define('HEADING_TITLE', 'Welcome, Please Sign In');

define('HEADING_NEW_VENDOR', 'New VENDOR');
define('TEXT_NEW_VENDOR', 'I am a new VENDOR.');
define('TEXT_NEW_VENDOR_INTRODUCTION', 'By creating an account at ' . STORE_NAME . ' you will be able to shop faster, be up to date on an orders status, provide product reviews keep track of the orders you have previously made and many other features.');

define('HEADING_RETURNING_VENDOR', 'Returning Vendor');
define('TEXT_RETURNING_VENDOR', 'I am a returning vendor');

define('TEXT_PASSWORD_FORGOTTEN', 'Password forgotten? Click here.');

define('TEXT_LOGIN_ERROR', 'Error: No match for Username and/or Password.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>Note:</b></font> Your &quot;Visitors Cart&quot; contents will be merged with your &quot;Members Cart&quot; contents once you have logged on. <a href="javascript:session_win();">[More Info]</a>');
// Begin Checkout Without Account v0.70 changes

define('PWA_FAIL_ACCOUNT_EXISTS', 'An account already exists for the email address <i>{EMAIL_ADDRESS}</i>.  You must login here with the password for that account before proceeding to checkout, if you do not recall your password, select the Password Forgotten, Click Here, and a new password will be sent to you.');

define('ENTRY_USERNAME', 'Username:');
// Begin Checkout Without Account v0.60 changes

define('TXT_LOGIN','Login');
define('TXT_HOME','Home');
?>
