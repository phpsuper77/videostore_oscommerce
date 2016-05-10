<?php
/*
  $Id: tell_a_friend.php,v 1.6 2003/02/06 14:11:52 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Ask a Question');
define('HEADING_TITLE', 'Ask a Question about \'%s\'');
define('HEADING_TITLE_ERROR', 'Ask a Question');
define('ERROR_INVALID_PRODUCT', 'That product is no longer available. Please try again.');

define('FORM_TITLE_CUSTOMER_DETAILS', 'Your Info');

define('FORM_FIELD_CUSTOMER_NAME', 'Your Name:');
define('FORM_FIELD_CUSTOMER_EMAIL', 'Your Email Address:');
define('FORM_TITLE_FRIEND_MESSAGE', 'Your Question');
define('ERROR_FROM_NAME', 'Error: Your name must not be empty.');
define('ERROR_FROM_ADDRESS', 'Error: Your e-mail address must be a valid e-mail address.');


define('TEXT_EMAIL_SUCCESSFUL_SENT', 'Your question about <b>%s</b> has been successfully sent to <b>%s</b>.');

define('TEXT_EMAIL_SUBJECT', ' %s has a question -  %s');
define('TEXT_EMAIL_INTRO', 'Hi %s!' . "\n\n" . ' %s, has a question about %s - %s.');
define('TEXT_EMAIL_LINK', 'Link to the product:' . "\n\n" . '%s');
define('TEXT_EMAIL_SIGNATURE', 'Regards,' . "\n\n" . '%s');
/*** Begin Visual Verify Code ***/
define('VISUAL_VERIFY_CODE_TEXT_INTRO', 'Please type the security code you see below into the provided box.');
define('VISUAL_VERIFY_CODE_TEXT_INSTRUCTIONS', 'Enter security code here:');
define('VISUAL_VERIFY_CODE_BOX_IDENTIFIER', '<- Security Code<br>(refresh page to renew)');
  /*** end Visual Verify Code ***/

?>