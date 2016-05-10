<?php
/*
  $Id: affiliate_validproducts.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_BANNERS_BUILD);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>
<head>
<body>
<table width="580" class="infoBoxContents">
<tr>
<td colspan="2" class="infoBoxHeading" align="center"><?php echo TEXT_VALID_PRODUCTS_LIST; ?></td>
</tr>
<?
    echo "<tr><td><b>". TEXT_VALID_PRODUCTS_ID . "</b></td><td><b>" . TEXT_VALID_PRODUCTS_NAME . "</b></td></tr><tr>";
    $result = mysql_query("SELECT * FROM products, products_description left join series on products.series_id = series.series_id left join products_media_types on products.products_media_type_id = products_media_types.products_media_type_id WHERE products.products_id = products_description.products_id and products.products_status >0 and products_description.language_id = '" . $languages_id . "' ORDER BY series.series_name, products_description.products_name_prefix, products_description.products_name, products_description.products_name_suffix");
    if ($row = mysql_fetch_array($result)) {
        do {
            echo "<td class='infoBoxContents'>&nbsp".$row["products_id"]."</td>\n";
            echo "<td class='infoBoxContents'>".'<b>'.$row["series_name"].'</b><br>'.$row["products_name_prefix"].'&nbsp;<b>'.$row["products_name"].'</b>&nbsp;'.$row["products_name_suffix"].'-'.$row["products_media_type_name"]."</td>\n";
            echo "</tr>\n";
        }
        while($row = mysql_fetch_array($result));
    }
    echo "</table>\n";
?>
<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?>&nbsp;&nbsp;&nbsp;</p>
<br>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>