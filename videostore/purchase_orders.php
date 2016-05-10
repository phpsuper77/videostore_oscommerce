<?php
/*
  $Id: shipping.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $breadcrumb->add('Purchase Orders', tep_href_link(FILENAME_PURCHASE_ORDERS));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Purchase Orders : <?php echo TITLE; ?></title>
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
            <td class="pageHeading">Purchase Orders</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">
<table><tr><td><img src="images/purchaseorder_button.gif"></td><td  class="main">
Purchase orders (net 30 days, subject to credit approval) are accepted from government and accredited educational institutions, provided that they are submitted on purchase order forms with a purchase order number.</td></tr></table><BR>

<b>Our Federal Employer Identification Number is 20-0132336</b><BR><BR>

<b>Step 1 : Make your shopping list</b><BR><BR>
 
We recommend using our Online TravelVideoStore.com Cart to make your shopping list. TravelVideoStore.com Cart allows easy adding and removing of products. Our Cart also helps by providing real-time product availability and shipping cost. Also, by using our online ordering system, you can have easy access to your ordering history and tracking of your orders.  To add products into the Cart, simply click on the "Add to Cart" link. To see the content of your Cart, click on the CART contents on the top right corner of every page.  Please do not hesitate to call 800-288-5123  or email <a href="mailto:purchaseorders@travelvideostore.com">PurchaseOrders@travelvideostore.com.</a><br><BR>
 
  
<b>Step 2 : Calculate Total Cost with Shipping</b><BR><BR>
 
Once you have selected all the items that you are interested in purchasing, select the checkout button at the top right side of your screen, and go through the checkout process, when entering in payment method, select "purchase order" and if you know your purchase order number enter it as well, if you do not yet have a Purchase Order number, enter "TBD".  By entering your order online, you will insure that the item you ordered is "in-stock" and being held to ship to you once we receive your faxed purchase order.  Online orders are held for 5 days to allow time for your purchase order to be faxed or mailed to us, after 5 days, we will return the items to inventory.<BR><BR>
 
  
<b>Step 3 : Print your Order Receipt</b><BR><BR>
 
Upon completion of your order, print off your order reciept.<BR><BR>
 
  
<b>Step 4 : Transfer TravelVideoStore.com Item# to your Purchase Order (PO)</b><BR><BR>
 
Now that you have your shopping list on paper, you can simply transfer this information to your purchase order form or program. Please include our Item# or SKU number whenever possible. SKU numbers will increase order accuracy to ensure you order will be delivered accurately and on-time.<BR><BR>
 
  
<b>Step 5 : Fax your Purchase Order (PO)</b><BR><BR>
 
For your security and ours, TravelVideoStore.com only accept government purchase orders in their OFFICIAL PRINTED format. Fax your completed and signed purchase order to 813-627-0334 or 1-800-288-5123. Make sure you include your COMPLETE contact information so we can reach you if we have question.  If you prefer, you may mail your purchase order to us at TravelVideoStore.com, Attn: Purchase Order, 5420 Boran Drive, Tampa, FL 33610<BR><BR>
 
  
<b>Step 6 : Wait for confirmation</b><BR><BR>
 
If you have included your email address, we will inform you by email about the status of your order. If you have not included any email address, we will only call you if there is an issue with your order or for questions only.  We ship daily and carry all of our products in-stock for quick, timely, and efficient delivery of your order.  No need to wait because you are buying with a purchase order.<BR><BR>
 
 
Still have questions? Please call 800-288-5123 or Check our Frequently Asked Questions (FAQs) 







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