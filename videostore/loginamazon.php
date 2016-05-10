<?php
/*
  $Id: login.php,v 1.80 2003/06/05 23:28:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
  if ($session_started == false) {
    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

  $error = false;
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
// +Login Page a la Amazon
    $new_customer = tep_db_prepare_input($HTTP_POST_VARS['new_customer']);
    $lpala_new = false; // New customer?
    $lpala_account_exists = false; // Give 'account exists' error?

    if ($new_customer == 'Y') {
      if (!tep_session_is_registered('login_email_address')) tep_session_register('login_email_address');
      $login_email_address = $email_address;
      $lpala_new = true;
      }

// Check if email exists
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
    if (!tep_db_num_rows($check_customer_query)) {
      // If the user said that they are a new customer and the account does not exist, 
      // redirect to Create Account
      if ($lpala_new) {tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT,'','SSL'));}
      $error = true;
    } else if ($lpala_new) {
      // We get here if the user said they are new but the account exists
      $lpala_error = true;
// -Login Page a la Amazon
    } else {
      $check_customer = tep_db_fetch_array($check_customer_query);
// Check that password is good
      if (!tep_validate_password($password, $check_customer['customers_password'])) {
        $error = true;
      } else {
        if (SESSION_RECREATE == 'True') {
          tep_session_recreate();
        }

        $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
        $check_country = tep_db_fetch_array($check_country_query);

        $customer_id = $check_customer['customers_id'];
        $customer_default_address_id = $check_customer['customers_default_address_id'];
        $customer_first_name = $check_customer['customers_firstname'];
        $customer_country_id = $check_country['entry_country_id'];
        $customer_zone_id = $check_country['entry_zone_id'];
        tep_session_register('customer_id');
        tep_session_register('customer_default_address_id');
        tep_session_register('customer_first_name');
        tep_session_register('customer_country_id');
        tep_session_register('customer_zone_id');

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");

// restore cart contents
        $cart->restore_contents();

        if (sizeof($navigation->snapshot) > 0) {
          $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
          $navigation->clear_snapshot();
          tep_redirect($origin_href);
        } else {
          tep_redirect(tep_href_link(FILENAME_DEFAULT));
        }
      }
    }
  }

  if ($error == true) {
    $messageStack->add('login', TEXT_LOGIN_ERROR);
  }
//+ Login Page a la Amazon
  if ($lpala_error) {
    $messageStack->add('login', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
  }
//- Login Page a la Amazon

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function session_win() {
  window.open("<?php echo tep_href_link(FILENAME_INFO_SHOPPING_CART); ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
        <!-- left_navigation //-->
        <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
        <!-- left_navigation_eof //-->
      </table></td>
    <!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                             </tr>
            </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <?php
  if ($messageStack->size('login') > 0) {
?>
        <tr>
          <td><?php echo $messageStack->output('login'); ?></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <?php
  }

  if ($cart->count_contents() > 0) {
?>
        <tr>
          <td class="smallText"><?php echo TEXT_VISITORS_CART; ?></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <?php
  }
?>
		<tr>
		  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
		  <tr>
<?php // +Login Page a la Amazon ?>
			<td width="50%" height="100%" valign="top"><table border="0" width="100%" height="100%" cellspacing="1" cellpadding="2">
				<tr>
				  <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2">
					  <tr>
						<td class="main"><b><?php echo TEXT_EMAIL_QUERY; ?></b></td>
					  </tr>
					  <tr>
						<td class="main"><?php echo TEXT_EMAIL_IS . '&nbsp;' . tep_draw_input_field('email_address'); ?><br><br><br></td>
					  </tr>
					  <tr>
						<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
					  </tr>
					  <tr>
						<td class="main"><b><?php echo TEXT_HAVE_PASSWORD; ?></b></td>
					  </tr>
					  <td class="main"><?php echo tep_draw_radio_field('new_customer','Y',false) . '&nbsp;' . TEXT_HAVE_PASSWORD_NO ?> </td>
					  </tr>
					  <tr>
						<td class="main"><?php echo tep_draw_radio_field('new_customer','N',true) . '&nbsp;' . TEXT_HAVE_PASSWORD_YES . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . 
						tep_draw_password_field('password','','maxlength="40"'); ?> </td>
					  </tr>
					  <tr>
						<td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
							<tr>
							  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
							  <td align="left"><?php echo tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN); ?></td>
							  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
							</tr>
						  </table></td>
					  </tr>
					  <tr>
						<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
					  </tr>
					  <tr>
						<td class="smallText" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>'; ?></td>
					  </tr>
					  <tr>
						<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
					  </tr>
					</table></td>
				</tr>
			  </table></td>
			<td width="10%"></td>
			<td width="40%" height="100%" valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
				<tr class="infoBoxHeading">
				  <td class="infoBoxHeading"><?php echo TEXT_NEW_CUSTOMER; ?></td>
				</tr>
				<tr class="infoBoxContents">
				  <td valign="top"><table border="0" width="100%"  cellspacing="0" cellpadding="2">
					  <tr>
						<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
					  </tr>
					  <tr>
						<td class="main"><?php echo TEXT_NEW_CUSTOMER_INTRODUCTION; ?></td>
					  </tr>
					</table></td>
				</tr>
			  </table></td> 
<?php // -Login Page a la Amazon ?>
          </tr>
        </table></td>
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
