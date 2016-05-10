<?php
ob_start();
/*
  $Id: vendor_customer_products.php,v 1.80 2005/25/08 11:40:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . 'english/'.  FILENAME_VENDOR_CUSTOMER_PRODUCTS);

  require(DIR_WS_INCLUDES .'database_tables.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  // require('includes/functions.php');
  $currencies = new currencies();


  $prev_page = $_SERVER['HTTP_REFERER'];

  session_start();
  if (isset($_SESSION["vendors_id"]) && $_SESSION["vendors_id"] <> "")
  {
  	$orders_id = $HTTP_GET_VARS['order_id'];
  	$products_id = $HTTP_GET_VARS['prod_id'];

  	//$orders_sel = tep_db_query("select orders_id, products_id, products_model, products_name, final_price, products_quantity from ". TABLE_ORDERS_PRODUCTS ." where orders_id = ". $orders_id);
  	$orders_sel = tep_db_query("select op.orders_id, op.products_id, op.products_model, op.products_name, op.final_price, op.products_quantity, o.date_purchased from ". TABLE_ORDERS_PRODUCTS ." op, ". TABLE_ORDERS ." o where o.orders_id = op.orders_id and op.orders_id = ". $orders_id);
	$orders_count = tep_db_num_rows($orders_sel);
  }
  else
  {
	tep_redirect("../index.php");
	exit;
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="./includes/main.css">
<link rel="stylesheet" type="text/css" href="../stylesheet.css">

<script language="javascript"><!--
function rowOverEffect(object) {
  if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
}


//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<table border="0" width="100%" cellspacing="1" cellpadding="1">
<tr>
	<td width="100%" class="headerNavigation">&nbsp;<a href="../index.php" class="headerNavigation"><?php echo TXT_HOME?></span></a>&nbsp;-&nbsp;<a href="vendor_account_products.php" class="headerNavigation"><?php echo TXT_PRODUCT_INFO;?></a>&nbsp;-&nbsp;<a href="vendor_order_products.php?prod_id=<?php echo $products_id?>" class="headerNavigation"><b><?php echo TXT_ORDER_PRODUCT;?></b>&nbsp;-&nbsp;<a href="vendor_customer_products.php?order_id=<?php echo $orders_id?>&prod_id=<?php echo $products_id?>" class="headerNavigation"><b><?php echo TXT_CUSTOMER_ORDER;?></b></td>
</tr>
</table>

<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php
	if (tep_session_is_registered('vendors_id'))
		require('./includes/column_left.php');
?>
<!-- left_navigation_eof //-->
    </table></td>
        <td width="100%" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
	          <tr>
	            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	            <tr>
					<td colspan=6 width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
					  <tr><td class="pageHeading1" colspan=6><?php echo HEADING_TITLE; ?></td></tr>
					</table>
					</td>
      			</tr>
				<tr>
					<td colspan=6><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td>
				</tr>
<?php

				if ($orders_count == 0)
				{
					echo "<tr><td class=headerError align=left valign=top>No records found!!!</td></tr>";
					echo "<tr><td>".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td></tr>";
					echo "<tr><td align=right valign=top><a href='".$prev_page."'>".tep_image(DIR_WS_INCLUDES_LOCAL.'images/button_back.gif')."</td></tr>";
				}
				else
				{
					$orders_fetch = tep_db_fetch_array($orders_sel);
					echo "<tr><td colspan=6><span class=main><b>".TXT_ORDER_ID .": $orders_id</span></td></tr>";
					echo "<tr><td colspan=6><span class=main><b>".TXT_ORDER_DATE.":</b> ".tep_datetime_short($orders_fetch[date_purchased])."<span></td></tr>";

?>
					<tr>
						<td colspan=6><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td>
					</tr>
					<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent" align="left" width="25%"><?php echo TXT_MODEL_NO; ?></td>
						<td class="dataTableHeadingContent" align="left" width="55%"><?php echo TXT_DESC; ?>&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_QTY_SOLD; ?>&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_SELLING_PRICE; ?></td>
					</tr>
<?php
					tep_db_data_seek($orders_sel,0);
					while($orders_fetch = tep_db_fetch_array($orders_sel))
					{
						$str = "<b></b>&nbsp;";
						$orders_fetch['products_name'] = str_replace($str, "", $orders_fetch['products_name']);

						$str = "&nbsp;<b>";
						$orders_fetch['products_name'] = str_replace($str, "<b>", $orders_fetch['products_name']);

						if ($products_id == $orders_fetch['products_id'])
							echo "<tr id=dataTableRow class=dataTableRowSelected onmouseover=rowOverEffect(this) onmouseout=rowOutEffect(this)>";
						else
							echo "<tr id=dataTableRow class=dataTableRow onmouseover=rowOverEffect(this) onmouseout=rowOutEffect(this)>";
						echo "<td class=dataTableContent align=left valign=top>".$orders_fetch['products_model']."</td>";
						echo "<td class=dataTableContent align=left valign=top>".tep_output_string($orders_fetch['products_name'])."</td>";
						echo "<td class=dataTableContent align=left valign=top>".$orders_fetch['products_quantity']."</td>";
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format($orders_fetch['final_price'],true,'USD', '1.000000')."</td>";
					}
					echo "</tr>";
echo "<tr><td><br/></td></tr>";
$or = tep_db_query("SELECT title, value FROM orders_total where orders_id=".$orders_id." order by sort_order");
				while($row = tep_db_fetch_array($or))
					echo "<tr><td colspan=6 align='right'><span class=main><b>".$row[title]."</b> ".$currencies->format($row[value],true,'USD', '1.000000')."<span></td></tr>";
					echo "<tr><td colspan=6>".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td></tr>";
					echo "<tr><td colspan=6 align=right valign=top><a href='".$prev_page."'>".tep_image(DIR_WS_INCLUDES_LOCAL.'images/button_back.gif')."</td></tr>";

				}
?>
		</tr>
        </table><br></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require('footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
