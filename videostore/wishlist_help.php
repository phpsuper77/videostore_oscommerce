<?php
/*
  $Id: conditions.php,v 1.22 2003/06/05 23:26:22 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_WISHLIST_HELP);
  include ('includes/ssl_provider.js.php');

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_WISHLIST_HELP));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
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
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
<script>
	function show(id){
	for (i=1;i<8;i++){
	if (i!=id)
		document.getElementById('faq'+i).style.display="none";
		else
		document.getElementById('faq'+i).style.display="";
	}
}
</script>
        <td class="main"><?php echo TEXT_INFORMATION; ?></td>
      </tr>
      <tr>
        <td class="main"><br/>
<div id="faq1" style="display:none;">
<b>What is my Wish List? </b><br/>
My Wish List is a convenient way for you to save a reminder of an item you would like to purchase later or an item that is not currently in stock.
</DIV>
<div id="faq2" style="display:none;">
<b>How do I add items to my Wish list?</b><br/>
To add an item to My Wish List, you must first have an account with travelvideostore.com and be logged in. Once you're logged in view any product you wish to add. If the item can be added a "Wish List" box will appear in the right column. Click on the Wish List image or click on the "Add" text to automatically place the item in your Wish List.<br/><br/>
The Wish List box also provides a link to view all the items in your Wish List. A link to your Wish List can also be found by clicking on "My Account".
</DIV>
<div id="faq3" style="display:none;">
<b>Can I add "out-of-stock" items or "coming-soon" items?</b><br/>
Yes. You can add any item you choose to My Wish List
</DIV>
<div id="faq4" style="display:none;">
<b>How do I view my Wish List?</b><br/>
Go to My Account and follow the link to My Wish List. You can also view your Wish List by following the link on the Wish List box that appears whenever you view the full description of one of our products.
</DIV>
<div id="faq5" style="display:none;">
<b>How do I move my Wish List items to my Shopping Cart?</b><br/>
To move My Wish List items to the Shopping Cart, go to My Wish List page and click the checkbox for each item you wish to move to your cart. Make sure you have the "Move to Cart" button checked, then click the "Continue" button. All checked items will be removed from your list and added to your Shopping Cart.
</DIV>
<div id="faq6" style="display:none;">
<b>How do I remove items from my Wish List?</b><br/>
To remove items from My Wish List, click the checkbox for each items you wish to delete. Make sure you have the "Delete" button checked, then click the "Continue" button. All checked items will be removed from your list.
</DIV>
<div id="faq7" style="display:none;">
<b>Can I make my Wish List available to others?</b><br/>
Yes. Your Wish List is accessible <b>only</b> by you when you are logged in to your account, and you are the only one that can see it on-line. However, you can email your wishlist to a friend by visiting the My Wish List main page, entering your friends email address into the 'send your wishlist to a friend' box and clicking the email envelope.<br/><br/>
Your friend will receive your complete Wish List along with a message if you wish to add one. They will be able to follow the links in the email to view each item in the Wish List.
</DIV>
	</td>
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
