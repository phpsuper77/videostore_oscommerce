<?php
/*
  $Id: contact_us.php,v 1.42 2003/06/12 12:17:07 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	require('includes/application_top.php');
?>
<html>
	<head>
	</head>
	<body>
<?php
	error_reporting(E_ALL);
	ini_set('error_reporting',E_ALL);
	ini_set('display_errors','on');
	// echo 'test2<br />';
	
	if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'send')) {
		// echo 'test3<br />';
		$name = tep_db_prepare_input($HTTP_GET_VARS['name']);
		$email_address = 'nate@oscwebdev.com, Don Wyatt <donwyatt@travelvideostore.com>, Customer Service <customerservice@travelvideostore.com>';
		$enquiry = tep_db_prepare_input($HTTP_GET_VARS['enquiry']);

		// echo tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'test', $enquiry, $name, $email_address);
		echo tep_mail($name, $email_address, 'test 3-7-12', $enquiry, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		// echo tep_mail($name, $email_address, 'test', $enquiry, STORE_OWNER, 'donwyatt@travelvideostore.com');
		// echo 'test4<br />';
		if (isset($_REQUEST['RESULTS'])) echo $_REQUEST['RESULTS'];
		// echo 'test5<br />';
	}
	
	
	// echo "<pre>".print_r($_GET,true)."</pre>";



?>
	</body>
</html>
<?php

	require(DIR_WS_INCLUDES . 'application_bottom.php'); 
	
?>