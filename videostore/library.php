<?php
/*
  $Id: shipping.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $breadcrumb->add('Library', tep_href_link(FILENAME_LIBRARY));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Library : <?php echo TITLE; ?></title>
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
            <td class="pageHeading">Libraries</td>
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
<a href="http://www.ala.org"><img src="images/logos/ala_logo.jpg" border="0" alt="TravelVideoStore.com is a Proud Member of the American Library Association" title="TravelVideoStore.com is a Proud Member of the American Library Association" TARGET="_blank"></a>

<a href="http://www.ala.org"><img src="images/logos/AN05Colorsm.jpg" border="0" alt="TravelVideoStore.com attended the American Library Association Chicago05 National Convention" title="TravelVideoStore.com is a Proud Member of the American Library Association, supporting the efforts of the national convention" TARGET="_blank"></a><BR><BR>

TravelVideoStore.com is your single source for keeping up-to-date with your travel video collection.  We accept purchase orders from public libraries and school libraries, offering you quick shipping on your orders as we maintain the largest collection of in-stock travel videos in the country.<BR><BR>

<b>We offer the best prices on Travel Videos. (period)</b><BR><BR>

<b>Are you upgrading your video collections to DVD?</b>  We offer a large selection of travel videos in DVD format allowing you to purchase all of your Travel DVD's from one location.<BR><BR>

<B>COMMONLY ASKED QUESTIONS BY LIBRARIANS</b><BR><BR>


<b>Do you accept purchase orders?</b><BR><BR>

Yes we do!  See our instructions on the left information panel.<BR><BR>

<b>Why are there more titles available in VHS than DVD?</b><BR><BR>

The travel video industry has been slow in moving to DVD.  The main reason is the primary purchasers of travel videos have been schools, libraries, travel agents, and senior centers, each of which has been behind the technology curve.  However, we are seeing that 2004 is the year that the industry is moving solidly to DVD, with many new releases only being offered on DVD.  In addition, many libraries are receiving special funding to upgrade their video collections to DVD and with the cost of DVD players hitting lows of $20, DVD players are becoming more widespread.<BR><BR>

<B>Do you have your videos idenitifed by ISBN and UPC numbers?</b><BR><BR>

Yes we do.  On each video that carries an ISBN or UPC number, it is listed on the product page.  You may also search via ISBN or UPC (without the dashes) by using our search field located in the upper right hand corner of our website.<BR><BR>

<b>How do you stay on top of the Travel Videos being released and offered?</b><BR><BR>

At TravelVideoStore, we are actively involved in the Travel Film Business, supporting the efforts of the Travel Adventure Cinema Society, Video Librarian, Booklist, Library Journal, and Video Business to name just a few.  We work closely with Travel Film producers from around the world to bring not only videos produced and released in America, but also productions from around the world that you cannot find anywhere else but in the home country.  Our commitment is to provide the largest, most thorough collection of travel videos in an easy to navigate website.<BR><BR>

At TravelVideoStore.com, all we do is Travel Videos, we have knowledgable customer service staff that can help you find the best video to meet your needs.  Should you have any questions, please call us at 1-800-288-5123.  Send Library  related questions to us at <a href="mailto:librarian@travelvideostore.com">Librarian@TravelVideoStore.com</a>








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
