<?php
ob_start();
/*
  $Id: index.php,v 1.1 2003/06/11 17:37:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

/* CURRENT PROJECT FILE*/
  require('includes/application_top.php');


// the following cPath references come from application_top.php
  $category_depth = 'top';

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
 <?php
// RJW Begin Meta Tags Code
if (file_exists(DIR_WS_INCLUDES . 'meta_tags.php')) {
  require(DIR_WS_INCLUDES . 'meta_tags.php');
} else {
?>
<?php
// BOF: WebMakers.com Changed: Header Tag Controller v1.0
// Replaced by header_tags.php
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>

  <title><?php echo TITLE ?></title>
<?php
}
// RJW End Meta Tags Code
?>
<?php
}
// EOF: WebMakers.com Changed: Header Tag Controller v1.0
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>
<link rel="shortcut icon" href="/favicon.ico">
<link rel="icon" type="image/gif" href="animated_favicon1.gif" >
<link rel="apple-touch-icon" href="apple-touch-icon.png" >
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php');?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="170" valign="top"><table border="0" width="170" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php // echo tep_image(DIR_WS_IMAGES . 'table_background_default.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
            </td>
          </tr>
        </table></td>
      </tr>

          <tr>
            <td class="main"><?php include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFINE_MAINPAGE); ?></td>
          </tr>
</tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td></td>
          </tr>
		  <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
		  <tr>
            <td></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<?php

?><!-- body_text_eof //-->

    <td width="170" valign="top"><table border="0" width="170" cellspacing="0" cellpadding="2">
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