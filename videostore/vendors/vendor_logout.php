<?php
/*
  $Id: vendor_logout.php ,v 1.0 2005/22/08 23:28:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
require(DIR_WS_LANGUAGES . 'english/'.  FILENAME_VENDOR_LOGIN_TEXT);

session_start();
if (session_is_registered("vendors_id"))
{
	session_unregister("vendors_id");
	unset($_SESSION['product']);
	unset($_SESSION['cart']);
	unset($_SESSION['vendor']);
	unset($_SESSION['cc_type']);
	unset($_SESSION['cc_number']);
  	unset($_SESSION['cc_expires_month']);
  	unset($_SESSION['cc_expires_year']);
	unset($_SESSION['shipping']);
	unset($_SESSION['payment']);
	unset($_SESSION['comments']);
}
tep_redirect(tep_href_link(FILENAME_VENDOR_LOGIN, '', 'NONSSL'));
?>
