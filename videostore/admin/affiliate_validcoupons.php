<?php
/*
  $Id: affiliate_validcoupons.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<head>
<body>
<table width="580" class="infoBoxContents">
<tr>
<td colspan="3" class="infoBoxHeading" align="center">Valid Coupons</td>
</tr>
<?
    echo "<tr><td><b>Coupon ID</b></td><td><b>Coupon Code</b></td><td><b>Coupon Name</b></td></tr><tr>";
    $result = mysql_query("SELECT c.coupon_id, c.coupon_code, cd.coupon_name FROM coupons c, coupons_description cd where cd.coupon_id = c.coupon_id and c.coupon_type <> 'G'");
    if ($row = mysql_fetch_array($result)) {
        do {
            echo "<td class='infoBoxContents'>&nbsp".$row["coupon_id"]."</td>\n";
            echo "<td class='infoBoxContents'>".$row["coupon_code"]."</td>\n";
	    echo "<td class='infoBoxContents'>".$row["coupon_name"]."</td>\n";
            echo "</tr>\n";
        }
        while($row = mysql_fetch_array($result));
    }
    echo "</table>\n";
?>
<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">Close Window</a>'; ?>&nbsp;&nbsp;&nbsp;</p>
<br>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>
