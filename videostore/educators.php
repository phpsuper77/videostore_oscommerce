<?php
/*
  $Id: shipping.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
;
  $breadcrumb->add('Educators', tep_href_link(FILENAME_EDUCATORS));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Educators : <?php echo TITLE; ?></title>
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
            <td class="pageHeading">Educators</td>
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

<table><tr><td  class="main">   
Travel Videos are an excellent way to entertain and educate your students about different cultures and parts of the world.</td>
<td><img src="images/Classroom.jpg"></td></tr></table>

<B>Most Asked Questions by Educators</b><BR><BR>

<b>Can I play your videos in my classroom?</b><BR><BR>

Under current copyright law, all of the videos sold by TravelVideoStore.com may be exhibited in classrooms under the following conditions:<OL>
<LI>An instructor is present during the exhibition of the videos</li>
<LI>The videos are used in the context of face-to-face instruction</li>
<LI>No admission fee is charged</LI>
<LI>The videos are not duplicated</li>
<li>In addition, videos may be used on closed-circuit systems within one school.</li></OL>

<b>Disclaimer:</b>  The above is a summary of generally accepted interpretation of current federal copyright laws, but is in no way intended as legal advice.  The purchaser of videos from Travelvideostore.com takes all responsibility for the proper use of the videos as defined by current copyright laws.  If you have any questions, please feel free to contact us at 1-800-288-5123.<BR><BR>

<B>Do you take school purchase orders?</b><BR><BR>

Yes, we do accept purchase orders from public schools and universities.  Please see our <a href="/purchase_orders.php">PURCHASE ORDER </a>page under the information heading to the left.  Also, please note that we stock all of our videos in-house, allowing us to ship your in-stock order within 24 hours of receipt of a signed purchase order.  If you are a private school, please call us at 800-288-5123 for more information about the submission of purchase orders.<BR><BR>

<b>Do you provide discounts for teachers?</b><BR><BR>

At the present time we do not offer any sort of special discounts for teachers.  Our pricing is much less than many companies that offer educational videos.   However, we do offer periodic coupons and specials for our customers.  If you currently have a website, you may consider joining our affliate program.  That program provides for you to earn commissions from the videos sold through your site, including your own purchases made through your link.  See our affiliate information on the lower left side of your screen.<BR><BR>

<b>I see that you have some videos that are educational versions, what is the difference?</b><BR><BR>

Some video titles are offered in an educational format, offering educational classroom plans, handouts, and other teaching materials in addtion to the video itself.<BR><BR>

<B>Do your videos have ratings on them?</b><BR><BR>

Almost all of our videos have a "G" rating, the few that are not, are clearly marked.  It has been brought to our attention that some nature videos might depict some violence in graphic detail unsuitable for small children, when we are made aware of such concerns, we add a comment under the reviews section of that product.  Also, all of our products allow for our customers to write their own reviews and experiences to share with future purchasers.  Feel free to submit your own reveiws of videos you like or dislike.














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
