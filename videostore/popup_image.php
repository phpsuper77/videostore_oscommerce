<?php
/*
  $Id: popup_image.php,v 1.18 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $navigation->remove_current_page();

  $products_query = tep_db_query("select pd.products_name, p.products_image, p.products_image_lrg, p.cover_image, p.products_image_xl_1, p.products_image_xl_2, p.products_image_xl_3, p.products_image_xl_4, p.products_image_xl_5, p.products_image_xl_6 from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and pd.language_id = '" . (int)$languages_id . "'");
  $products = tep_db_fetch_array($products_query);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $products['products_name']; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<script language="javascript" type="text/javascript"><!--
var i=0;
var s=0;
function resize() {

  if (navigator.appName == 'Netscape') i=40;
  if (window.navigator.userAgent.indexOf("Mozilla") != -1) s=40; //This browser is Internet Explorer in SP2.
  if (window.navigator.userAgent.indexOf("SV1") != -1) s=20; //This browser is Internet Explorer in SP2.
  if (window.navigator.userAgent.indexOf("MSIE") != -1) s=35; //This browser is Internet Explorer in SP2.

  if (document.images[0]) window.resizeTo(document.images[0].width +30, document.images[0].height+60-i+s);
  self.focus();
}
//--></script>

</head>
<body onload="resize();">
<?php
           if (($HTTP_GET_VARS['image'] ==0) && ($products['products_image_lrg'] != '')) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_lrg'], $products['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
     } elseif ($HTTP_GET_VARS['image'] ==1) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_1'], $products['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
     } elseif ($HTTP_GET_VARS['image'] ==2) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_2'], $products['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
     } elseif ($HTTP_GET_VARS['image'] ==3) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_3'], $products['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
     } elseif ($HTTP_GET_VARS['image'] ==4) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_4'], $products['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
     } elseif ($HTTP_GET_VARS['image'] ==5) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_5'], $products['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
     } elseif ($HTTP_GET_VARS['image'] ==6) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_6'], $products['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
     } elseif ($HTTP_GET_VARS['image'] ==7) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
     } else
     echo tep_image(DIR_WS_IMAGES . $products['cover_image'], $products['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
     ?>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>