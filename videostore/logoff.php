<?php
/*
  $Id: logoff.php,v 1.13 2003/06/05 23:28:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGOFF);

  $breadcrumb->add(NAVBAR_TITLE);

  tep_session_unregister('customer_id');
  tep_session_unregister('customer_default_address_id');
  tep_session_unregister('customer_first_name');
  tep_session_unregister('customer_country_id');
  tep_session_unregister('customer_zone_id');
  tep_session_unregister('comments');
  tep_session_unregister('iswholesale');

unset($_SESSION[cc_number]);
unset($_SESSION['real_cc_number']);
unset($_SESSION['cc_expires_month']);
unset($_SESSION['cc_expires_year']);

//ICW - logout -> unregister GIFT VOUCHER sessions - Thanks Fredrik
  tep_session_unregister('gv_id');

/*--- Code Inserted By K.v.Karthik --- */

	/* Code For Delete The Coupon Code id */

		$r_id=$_SESSION['affiliate_id_banner'];
		if($r_id <> NULL)
		{
			$affiliate_coupon_sql1 = tep_db_query("select affiliate_coupon from affiliate_affiliate where affiliate_id = $r_id");
			$affiliate_coupon_rs1 = tep_db_fetch_array($affiliate_coupon_sql1);
			$affiliate_coupon_code1 = $affiliate_coupon_rs1['affiliate_coupon'];

			if ($affiliate_coupon_code1 == NULL)
			{
					unset($_SESSION['cc_id']);
			}
		}
	/*  End of Code For Delete The Coupon Code id */

  if ($_SESSION['affiliate_id_banner'] == '')
  {
  	tep_session_unregister('cc_id');
  }
  tep_session_unregister('cot_gv');
  tep_session_unregister('gv_redeem_code');
  tep_session_unregister('od_amount');

  /*----------- Code End ----------------*/
// BEGIN LOGOFF BACK BUTTON SECURITY FIX
// Do not let the customer use back button or refresh to go back after logoff
if (tep_session_is_registered('customer_id')) {
//$navigation->set_snapshot();
tep_session_destroy(); // disabled above line and changed to destroy so cannot hit back button and see potentially private info
tep_redirect(tep_href_link(FILENAME_LOGOFF, '', 'SSL')); // changed to FILENAME_LOGOFF instead of FILENAME_DEFAULT ... lock in loop
}
// END LOGOFF BACK BUTTON SECURITY FIX



//ICW - logout -> unregister GIFT VOUCHER sessions  - Thanks Fredrik
//tep_session_unregister('couponfield'); //DISCOUNTS
  $cart->reset();
  $wishList->reset();
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>


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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td></td>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="pageHeading" align="center">Logged Off</td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_MAIN; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
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
