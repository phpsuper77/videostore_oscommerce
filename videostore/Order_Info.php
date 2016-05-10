<?php
/*
  $Id: Order_Info.php,v 0.52 2002/09/21 hpdl Exp $
        by Cheng

        OSCommerce v2.2 CVS (09/17/02)

   Modified versions of create_account.php and related
  files.  Allowing 'purchase without account'.

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

  $breadcrumb->add('Order Info', tep_href_link('Order_Info.php', '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title><?php echo TITLE ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>


<?php require('includes/form_check.js.php'); ?>
				<?php
				// osCoders.biz - Analystics - start
				/*
				Conditional code added thank to  rrodkey and bfcase
				IMPORTANT -- IMPORTANT - IMPORTANT
				You'll need to update the "xxxx-x" in the samples (twice) above with your own Google Analytics account and profile number. 
				To find this number you can access your personalized tracking code in its entirety by clicking Check Status in the Analytics Settings page of your Analytics account.
				*/
				if ($request_type == 'SSL') {
				?>
				 <script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript">
				 </script>
				 <script type="text/javascript">
				   _uacct="UA-195024-1";
				   urchinTracker();
				 </script>
				<?php
				} else {
				?>
				 <script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
				 </script>
				   <script type="text/javascript">
				    _uacct="UA-195024-1";
				    urchinTracker();
				 </script>
				<?
				}
				// osCoders.biz - Analistics - end
				?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0"><br>
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="account_edit" method="post" <?php echo 'action="' .
tep_href_link('Order_Info_Process.php', '', 'SSL')
. '"'; ?> onSubmit="return check_form(this);"><input type="hidden" name="action" value="process"><table border="0" width="75%" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td><table border="0" width="75%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Billing Information</td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (sizeof($navigation->snapshot) > 0) {
?>
      <tr>
        <td class="smallText"><br><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
  $email_address = tep_db_prepare_input($HTTP_GET_VARS['email_address']);
  $account['entry_country_id'] = STORE_COUNTRY;

 require(DIR_WS_MODULES . 'Order_Info_Check.php');
 ?>
        </td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>