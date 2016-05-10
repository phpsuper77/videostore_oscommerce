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
            <td class="pageHeading"><?php echo TEXT_DISCOUNT_ENTRY; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
		   <tr>
			   <td valign=middle><br>
					<table border="0" width="75%" cellspacing="0" cellpadding="2">
						<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent"><?php echo "S.no"; ?></td>
						<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DISCOUNT_CODE; ?></td>
						<td class="dataTableHeadingContent"><?php echo TEXT_DISCOUNT_TOTAL_REDEEM; ?></td>
						<td class="dataTableHeadingContent"><?php echo TEXT_DISCOUNT_TOTAL_AMOUNT; ?></td>
						</tr>


						<?php
						  $discount_query_raw = "select *  from " . TABLE_DISCOUNTS ." order by discount_id";
						  $discount_query = tep_db_query($discount_query_raw);
						  $sno = 1;
						  while ($row_discount_query = tep_db_fetch_array($discount_query))
						  {
						  	$disc_cust_raw = "select count(*) count_disc, sum(discount_total) sum_disc from ". TABLE_DISCOUNT_CUSTOMER . " where discount_id =". $row_discount_query["discount_id"];
						  	$disc_cust_query = tep_db_query($disc_cust_raw);
						  	$row_disc_query = tep_db_fetch_array($disc_cust_query);

						  	if ($row_disc_query[sum_disc] == 0)
						  		$row_disc_query[sum_disc] =0;
							echo "<tr class='dataTableRow' onmouseover='rowOverEffect(this)' onmouseout='rowOutEffect(this)'>";
							echo "<td class='dataTableContent'>$sno.</td>";
							echo "<td class='dataTableContent'>$row_discount_query[discount_code]</td>";
							echo "<td class='dataTableContent'>$row_disc_query[count_disc]</td>";
							echo "<td class='dataTableContent'>$$row_disc_query[sum_disc]</td>";
							echo "</tr>";
							$sno++;

						  }
						 ?>

					</table>

			   </td>
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
