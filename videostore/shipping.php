<?php
/*
  $Id: shipping.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHIPPING);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHIPPING));
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
            <td class="pageHeading">Shipping Information</td>
            <!--<td class="pageHeading" align="right"><?php //echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>-->
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" size=50%>
<table><tr><td  class="main">
<strong>SHIPPING INFORMATION</strong>    </td><td>
<img src="images//wwshipping.gif"></td></tr></table>

ON INTERNATIONAL DELIVERIES - CHECK ESTIMATES PROVIDED BY USPS AND UPS.<BR><BR>
Ordering an item from TravelVideoStore.com?  We ship to virtually any address in the world.    We offer UPS and USPS Shipping options from <u>Express Overnite</u> to <u>economical media mail</u>, allowing you the choice of how quickly you want to receive your package and how much you want to spend.  We also offer <b>Giftwrap Service</b>.  Items will be shipped same day for overnight orders received by 4pm EST.   All other orders will either ship same day or the next business day. If a tracking number is available for your shipping method, that number will be emailed to you upon shipping.   All orders are shipped with UPS or USPS tracking or delivery verification.  <br><br>
<b>Shipping is Calculated <u>based on Destination</u> and <u>Actual Shipping Weight</u>.  There are no per video charges, just the actual charges as calculated by weight with USPS and UPS on-line at your checkout.</b><br><br>
We offer:<br>
&#42 UPS Overnite (Next Business Day) and USPS Express Mail (1-2 Business Days) Starting at $19.95<br>
&#42 UPS 2 Day and USPS Priority Mail (2-3 Business Day) Starting at $5.85<br>
&#42 USPS First Class/Parcel Post (3-5 Business Days) Starting at $2.25<br>
&#42 USPS Media Mail (5-15 Days) Starting at $3.42<br>
&#42 UPS International and USPS GLobal Express and Priority Shipping (2-15 Business Days) Starting at $4.00<br><BR>

<table><tr><td  class="main"><b>Interested in Free Shipping?</b>     </td><TD> <img src="images//freeshipping.gif"> </td></tr></table>  <u>Purchase 3 or more products and receive free Domestic USPS Media Mail Shipping to to a single U.S. address</u>.  <i>(Including APO/FPO, P.O. Boxes, AK, HI, PR, USVI, Guam, and U.S. Protectorates)</i> <font size="-2">(TravelVideoStore.com may change or discontinue the FREE Shipping promotion at any time.)</font><BR><BR>
International packages may be subject to the customs fees and import duties of the country to which you have your order shipped.<BR><BR>

			<ul><b>USPS - United States Postal Service :</b>
			<li>Delivery confimation with each order and package tracking for Express only.</li>
			<li>Will deliver to P.O., APO, FPO addresses.</li>
			<li>More economical as the price is usually lower for similar service.</li>
			<li>Delivery time varies and can only be estimated.</li>

			</ul>

			<ul><b>UPS - United Parcel Service</b>
			<li>Full package tracking on all services.</li>
			<li>Will <u>NOT</u> deliver to P.O., APO, and FPO address.</li>
			<li>Delivery time is more precise and very rarely misses an estimated date.</li>

			</ul>

			<ul><b>Time Sensitive Orders</b>
                                                <li>International Orders may be subject to Customs and Brokerage Fees
			<li>Orders placed by 4PM EST will be shipped same day in most cases.  			  </li>
			</ul><BR><BR>


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
