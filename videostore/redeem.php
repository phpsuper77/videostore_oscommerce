<?php
/*
  $Id: shipping.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  define('NAVBAR_TITLE','Gift Card Redemption');

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('redeem.php'));

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>

<script language="javascript">
function check_form()
{
	if (document.redeem.gv_no.value == '')
	{
		alert("Please enter the gift code");
		document.redeem.gv_no.focus();
		return false;
	}
	return true;
}
</script>
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
            <td class="pageHeading">Gift Card Redemption</td>
          </tr>
        </table></td>
        <?php echo tep_draw_form('redeem',tep_href_link('gv_redeem.php', '', 'NONSSL'),'get','onsubmit="return check_form();"'); ?>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<tr>
        <td  class="main">Congratulations on receiving your TravelVideoStore.com Gift Card.  The first step is to enter your gift code found on the back of your gift card in the box below.  <br><br>Next, you will be brought to a login screen, if you already have an account setup with us, then login to your existing account to have your giftcard amount applied to your account.  If you do not have an account with us yet, then select the create an account function to create an account in which your gift card amount will be applied.<BR><BR>Your gift card balance will be deposited into your account for use on your next purchase, you may also send the balance on your gift card to any other person by selecting the send gift voucher function.<br><br>When entering your gift code, include all dashes, ie. 2005-0923-0569-8513-0497-256  in the box below</td>
      </tr>



      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" size=50%>
				<table>
					<tr>
						<td class="main">
							<b>Gift Card Code Number:</b>
						</td>
						<TD>
							<?php echo tep_draw_separator('pixel_trans.gif', '32', '1').tep_draw_input_field('gv_no','','size="32"');?>
						</td>
					</tr>
				</table>

</td>
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
                <td align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    </form>
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
