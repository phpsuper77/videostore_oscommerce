<?php
/*
  $Id: shipping.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $breadcrumb->add('Product Submission', tep_href_link(FILENAME_SPEAKERS));
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
            <td class="pageHeading">Travel Video Product Submission</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><font size="1">
If you have a Travel Video product that you would like to submit for consideration to be added as our distribution offering, please send a copy of each product in each video format, along with<UL>
<li>Desired Distribution Method</li>
<li>The Year of production of your program/series</li>
<li>The Length of your program, title and synopsis</li>
<li>If your product is retail-ready, any special notes, such as Close-Captioned, Languages, Surround Sound, etc.</li>
<li>Any reviews that have been written about your product</li>
<li>Any additional description that better describes your product</li>
<li>If you have created video trailers in either .wmv or quicktime format</li>
<li>Graphic scans of your covers (Front and Back), if available </li></ul>

to us at:<BR><BR><center>

TravelVideoStore.com<BR>
Attn: Editorial Review Department<BR>
5420 Boran Drive<BR>
Tampa, FL  33610  <BR>
USA<BR><BR></center>

Upon receiving your product, we will begin an internal review of your product, to include a editorial review of your product, internal market analysis to determine marketability, potential price points, and competitive comparison to other offerings that we have in the respective categories.  Once completed, the next step in the process is to schedule a meeting with you to discuss the various acquisition possibilities that we would be interested in pursuing.  Possible acquisition options include, but are not limited to:<UL>

<li><b>Manufacturing/Publishing license - Rights Acquisition</b> - This is where we would acquire the rights to your product, either in an exclusive or non-exclusive basis, for a specified period of time.  Under this arrangement, we would assume replication, inventory, marketing, and distribution responsibilities and expense in lieu of a single up-front acquisition payment</li>
<li><b>Manufacturing/Publishing license - Royalty Basis</b> - Under this option, an agreed upon royalty payment would be made to you for each product sold, with TravelVideoStore.com assuming replication, inventory, marketing, and distribution responsibilities with quarterly royalty payments made to you.</li>
<li><b>Manufacturing/publishing license - Co-op Basis</b> - Under this option, an agreed upon royalty payment would be made to you for each product sold, with TravelVideoStore.com assuming replication, inventory,  and distribution responsibilities, and your sharing of marketing campaign expense with quarterly royalty payments made to you.</li>
<li><b>Direct Product Purchase</b> - Under this option, we would acquire your products in volumes that we determine directly from you and as needed to maintain our desired inventory levels.</li>
<li><b>Consignment Revenue-Share Basis</b> - Under this option, we would maintain mutually agreed upon inventory levels, with you providing your product on consignment for storage in our warehouse, with quarterly or monthly payments based on a revenue split agreement.</li>
<li><b>Other options as we may deem necessary</b></ul>

TravelVideoStore.com has quickly grown into the premier Travel Video distributor, combining the power of its web-based, easy-navigation proprietary delivering system along with extensive web-based marketing and print media advertising defining itself as the single largest provider of travel video titles to destinations around the world.  If your product is not being offered on our site, you are missing out on over 320,000 monthly travel video shoppers and growing.<BR><BR>

At TravelVideoStore.com our goal is to provide our customers with the largest selection of travel related videos in VHS and DVD format from a single supplier, stocked in-house in our warehouse for immediate shipping, extensive detailed information on our products, and online preview capability of video trailers.  We offer online reviews of all products offered by TravelVideoStore.com to be entered by our customers and interested parties.<BR><BR>

Please direct any questions you have regarding product submissions to us at <a href="mailto:suppliersupport@travelvideostore.com">SupplierSupport@travelvideostore.com</a>.  </font><BR><BR>

* Products Submitted will not be returned.

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









