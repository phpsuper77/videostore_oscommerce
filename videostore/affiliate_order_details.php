<?php
ob_start();
/*
  $Id: affiliate_order_details.php,v 1.80 2005/30/09 11:20:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $currencies = new currencies();

	  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_ORDER_DETAILS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL'));



  $prev_page = $_SERVER['HTTP_REFERER'];

  if (isset($HTTP_GET_VARS["order_id"]) && $HTTP_GET_VARS["order_id"] <> "")
  {
  	$orders_id = $HTTP_GET_VARS['order_id'];

  	$orders_sel = tep_db_query("select op.orders_id, op.products_id, op.products_model, op.products_name, op.final_price, op.products_quantity, o.date_purchased from ". TABLE_ORDERS_PRODUCTS ." op, ". TABLE_ORDERS ." o where o.orders_id = op.orders_id and op.orders_id = ". $orders_id);

  	$order_count = tep_db_num_rows($orders_sel);
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=450,height=120,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'affiliate_sales.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
				$orders_fetch = tep_db_fetch_array($orders_sel);
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="4">
		<tr><td class="main" colspan="5"><?php echo TXT_ORDER_ID  . ': <b>' . $orders_id ?></b></td></tr>
		<tr><td class="main" colspan="5"><?php echo TXT_ORDER_DATE  . ': <b>' . $orders_fetch[date_purchased] ?></b></td></tr>
		<tr><td class="main" colspan="5"><?php echo TXT_TOTAL_SALE . ' <b>' . $order_count ?></b></td></tr>
		<tr><td colspan="5"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td></tr>
		<tr><td colspan=6><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td></tr>
		<tr class="dataTableHeadingRow">
			<td class="infoBoxHeading" align="left" width="25%"><?php echo TXT_MODEL_NO; ?></td>
			<td class="infoBoxHeading" align="left" width="55%"><?php echo TXT_DESC; ?>&nbsp;</td>
			<td class="infoBoxHeading" align="left" width="10%"><?php echo TXT_QTY_SOLD; ?>&nbsp;</td>
			<td class="infoBoxHeading" align="left" width="10%"><?php echo TXT_SELLING_PRICE; ?></td>
		</tr>
<?php
					tep_db_data_seek($orders_sel,0);
					$number_of_sales = 0;
					$total_price = 0;
					while($orders_fetch = tep_db_fetch_array($orders_sel))
					{
						$number_of_sales++;
						$str = "<b></b>&nbsp;";
						$orders_fetch['products_name'] = str_replace($str, "", $orders_fetch['products_name']);

						$str = "&nbsp;<b>";
						$orders_fetch['products_name'] = str_replace($str, "<b>", $orders_fetch['products_name']);

						  if (($number_of_sales / 2) == floor($number_of_sales / 2)) {
							echo '          <tr class="productListing-even">';
						  } else {
							echo '          <tr class="productListing-odd">';
						  }
						echo "<td class=main align=left valign=top>".$orders_fetch['products_model']."</td>";
						echo "<td class=main align=left valign=top>".tep_output_string($orders_fetch['products_name'])."</td>";
						echo "<td class=main align=left valign=top>".$orders_fetch['products_quantity']."</td>";
						echo "<td class=main align=left valign=top>".$currencies->format($orders_fetch['final_price'],true,'USD', '1.000000')."</td>";
						$total_price += $orders_fetch['final_price'];					
					}
					echo "</tr>";
					echo "<tr><td colspan=6>".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td></tr>";
					echo "<tr><td colspan=6 align=right valign=top class=main><b>Total:  $$total_price </b></td></tr>";
					echo "<tr><td colspan=6 align=right valign=top class=main><a href='".$prev_page."'><b><u>BACK</u></b></a></td></tr>";

		}
?>
		</tr>
        </table><br></td>
      </tr>
    </table></form></td>
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
