<?php
/*
  $Id: customers.php,v 1.82 2003/06/30 13:54:14 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
`
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo "Product Feed Files"; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
		   <tr>
			   <td valign=middle><br><?php echo '<a href="'. tep_href_link(FILENAME_DEFINE_FROOGLE) . '">'?><?php echo  tep_image(DIR_WS_IMAGES . 'categories/froogle.gif', 'froogle', '65', '25')."&nbsp;Click here for froogle feed file";?></a></td>
		   </tr>
		   <tr>
<td valign=middle><br><a href="froogletxtfileAUD.php"><?php echo  tep_image(DIR_WS_IMAGES . 'categories/froogle.gif', 'froogle', '65', '25')."&nbsp;Click here for froogle feed file";?></a></td>
		   </tr>
		   <tr>

<td valign=middle><br><a href="froogletxtfileGBP.php"><?php echo  tep_image(DIR_WS_IMAGES . 'categories/froogle.gif', 'froogle', '65', '25')."&nbsp;Click here for froogle feed file";?></a></td>
		   </tr>
		   <tr>
			   <td valign=middle><br><?php echo '<a href="'. tep_href_link(FILENAME_DEFINE_YAHOO) . '">'?><?php echo  tep_image(DIR_WS_IMAGES . 'categories/yahoo.gif', 'yahoo', '65', '25')."&nbsp;Click here for yahoo product submit file";?></a></td>
		   </tr>
		   <tr>
			   <td valign=middle><br><?php echo '<a href="'. tep_href_link(FILENAME_DEFINE_AMAZON) . '">'?><?php echo  tep_image(DIR_WS_IMAGES . 'categories/amazon.gif', 'Amazon', '65', '25')."&nbsp;Click here for Amazon product submit file";?></a></td>
		   </tr>

 <tr>
			   <td valign=middle><br><a href="amazoncaproducttxtfile.php"><?php echo  tep_image(DIR_WS_IMAGES . 'categories/amazon.gif', 'Amazon Canada', '65', '25')."&nbsp;Click here for Amazon Canada product submit file";?></a></td>
		   </tr>
		   <tr>
			   <td valign=middle><br><a href="pricegrabberfeedfile.php"><?php echo  tep_image(DIR_WS_IMAGES . 'categories/pricegrabber.gif', 'PriceGrabber', '65', '25')."&nbsp;Click here for Price Grabber product submit file";?></a></td>
		   </tr>
		   <tr>
			   <td valign=middle><br><?php echo '<a href="'. tep_href_link(FILENAME_DEFINE_BIZRATE) . '">'?><?php echo  tep_image(DIR_WS_IMAGES . 'categories/bizrate.gif', 'BizRate', '100', '30')."&nbsp;Click here for BizRate product submit file";?></a></td>
		   </tr>
		   <tr>
			   <td valign=middle><br><?php echo '<a href="'. tep_href_link(FILENAME_DEFINE_MYSIMON) . '?selected_box=feed">'?><?php echo  tep_image(DIR_WS_IMAGES . 'categories/mysimon.gif', 'MySimon', '100', '30')."&nbsp;Click here for MySimon product submit file";?></a></td>
		   </tr>
		   <tr>
			   <td valign=middle><br><?php echo '<a href="'. tep_href_link(FILENAME_DEFINE_SHOPPING) . '?selected_box=feed">'?><?php echo  tep_image(DIR_WS_IMAGES . 'categories/shopping.gif', 'Shopping.com', '100', '30')."&nbsp;Click here for Shopping.com feed file";?></a></td>
		   </tr>
                   <tr>        
	                   <td valign=middle><br><?php echo '<a href="'. tep_href_link(FILENAME_DEFINE_NEXTAG) . '?selected_box=feed">'?><?php echo  tep_image(DIR_WS_IMAGES . 'categories/nextag.gif', 'Nextag', '100', '30')."&nbsp;Click here for Nextag feed file";?></a></td>
                   </tr>
 		   <tr>
			   <td valign=middle><br><a href="amazonukproducttxtfile.php"><?php echo  tep_image(DIR_WS_IMAGES . 'categories/amazon.gif', 'Amazon UK', '65', '25')."&nbsp;Click here for Amazon UK product submit file";?></a></td>
		   </tr>

      
  </table></td>
      </tr>
    </table></td>



<!-- body_text_eof //-->
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
