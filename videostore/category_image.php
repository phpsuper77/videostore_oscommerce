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

  $category_query = tep_db_query("select c.brochure_image, cd.categories_name from categories c LEFT JOIN categories_description cd on cd.categories_id=c.categories_id where c.categories_id = '" . (int)$HTTP_GET_VARS['cID'] . "'");
  $category = tep_db_fetch_array($category_query);
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
     echo tep_image(DIR_WS_IMAGES . $category['brochure_image'], $category['categories_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
     ?>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>
