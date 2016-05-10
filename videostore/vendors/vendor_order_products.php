<?php
ob_start();
/*
  $Id: vendor_order_products.php,v 1.80 2005/25/08 11:40:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . 'english/'.  FILENAME_VENDOR_ORDER_PRODUCTS);

  require(DIR_WS_INCLUDES .'database_tables.php');


  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  // require('includes/functions.php');

  session_start();
  if (isset($_SESSION["vendors_id"]) && $_SESSION["vendors_id"] <> "")
  {
  	$products_id = $HTTP_GET_VARS['prod_id'];
  	$products_sel = tep_db_query("select op.orders_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_sale_type, op.products_item_cost, o.date_purchased from ". TABLE_ORDERS_PRODUCTS ." op, orders o where o.orders_id = op.orders_id and op.products_id = ". $products_id." order by o.date_purchased desc");
	//$products_sel = tep_db_query("select orders_id, products_model, products_name, final_price, products_quantity, products_sale_type,products_item_cost  from ". TABLE_ORDERS_PRODUCTS ." where products_id = ". $products_id);
  	$prod_count = tep_db_num_rows($products_sel);
  	$products_fetch = tep_db_fetch_array($products_sel);

  	$vendor_det = tep_db_query("select * from ". TABLE_PRODUCTS_TO_VENDORS ." where products_id = ".$products_id." and vendors_id = ".$_SESSION["vendors_id"]);
	$vendor_det_rs = tep_db_fetch_array($vendor_det);
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
	<td width="100%" class="headerNavigation">&nbsp;<a href="../index.php" class="headerNavigation"><?php echo TXT_HOME?></span></a>&nbsp;-&nbsp;<a href="vendor_account_products.php" class="headerNavigation"><?php echo TXT_PRODUCT_INFO;?></a>&nbsp;-&nbsp;<a href="vendor_order_products.php?prod_id=<?php echo $products_id?>" class="headerNavigation"><?php echo TXT_ORDER_PRODUCT;?></td>
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
				if ($prod_count == 0)
				{
					echo "<tr><td class=headerError align=left valign=top>No records found!!!</td></tr>";
					echo "<tr><td>".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td></tr>";
					echo "<tr><td align=right valign=top><a href='".FILENAME_VENDOR_PRODUCTS."'>".tep_image(DIR_WS_INCLUDES_LOCAL.'images/button_back.gif')."</td></tr>";
				}
				else
				{
					echo "<tr><td colspan=6>";
					echo "<span class=main><b>".TXT_VENDORS_ITEM_NO .":</b> ".tep_draw_separator('pixel_trans.gif', 79, 1).$vendor_det_rs['vendors_item_number']."</span><Br>";
					echo "<span class=main><b>".TXT_VENDORS_PRODUCT_SALE_TYPE .":</b> ".tep_draw_separator('pixel_trans.gif', 55, 1).tep_get_vendors_product_sale_type($vendor_det_rs['vendors_product_sale_type'])."</span><Br>";
					echo "<span class=main><b>".TXT_VENDORS_PRODUCT_PAYMENT_TYPE .":</b> ".tep_draw_separator('pixel_trans.gif', 26, 1).tep_get_vendor_product_payment_type($vendor_det_rs['vendors_product_payment_type'])."</span><Br>";

					if (($vendor_det_rs['vendors_product_payment_type'] == 2) &&  ($vendor_det_rs['vendors_consignment'] == 3))
						echo "<span class=main><b>".TXT_VENDORS_CONSIGNMENT_PERCENTAGE .":</b> ".tep_draw_separator('pixel_trans.gif', 13, 1).$currencies->format($vendor_det_rs['vendors_consignment_percentage'],true,'USD','1.000000')."%</span><Br>";

					if (($vendor_det_rs['vendors_product_payment_type'] == 3) &&  ($vendor_det_rs['vendors_royalty'] == 3))
						echo "<span class=main><b>".TXT_VENDORS_ROYALTY_PERCENTAGE.":</b> ".tep_draw_separator('pixel_trans.gif', 44, 1).$currencies->format($vendor_det_rs['vendors_royalty_percentage'],true,'USD','1.000000')."%</span><Br>";


					echo "<br><span class=main><b>".TXT_MODEL_NO .":</b> ".tep_draw_separator('pixel_trans.gif', 136, 1).$products_fetch['products_model']."</span><Br>";
					echo "<span class=main><b>".TXT_DESC .":</b> ".tep_draw_separator('pixel_trans.gif', 109, 1).tep_output_string($products_fetch['products_name'])."</span>";
					echo "</td></tr>";


					tep_db_data_seek($products_sel, 0);
?>
					<tr>
						<td colspan=6><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td>
					</tr>

					<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent" align="left" width="7%"><?php echo TXT_ORDER_ID; ?>&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_QTY_SOLD; ?>&nbsp;</td>
<?

					  echo "<td class='dataTableHeadingContent' align='left' width='10%'>".TXT_VENDORS_ITEM_COST."&nbsp;</td>";?>
						<td class="dataTableHeadingContent" align="left" width="8%"><?php echo TXT_VENDOR_TOTAL; ?></td>						
						<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_SELLING_PRICE; ?></td>						
						<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_TOTAL; ?></td>						
						<td class="dataTableHeadingContent" align="left" width="17%"><?php echo TXT_ORDER_DATE; ?></td>						
						<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_SELLING_TYPE; ?></td>
					</tr>


<?php					$prod_total_price = 0;
						$total_vendor_cost_price = 0;
						while($products_fetch = tep_db_fetch_array($products_sel))
						{
							$prod_ordered_price = $products_fetch['products_quantity'] * $products_fetch['final_price'];
							$prod_total_sold += $products_fetch['products_quantity'];
							$prod_total_price += $prod_ordered_price;
						echo "<tr id=dataTableRow class=dataTableRow onmouseover=rowOverEffect(this) onmouseout=rowOutEffect(this)>";
						echo "<td class=dataTableContent align=left valign=top><a href=".FILENAME_VENDOR_CUSTOMER_PRODUCTS.	"?order_id=".$products_fetch['orders_id']."&prod_id=".$products_id.">".$products_fetch['orders_id']."</a></td>";
						echo "<td class=dataTableContent align=left valign=top>".$products_fetch['products_quantity']."</td>";
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format(($products_fetch['products_item_cost']),true,'USD','1.000000')."</td>";
						$total_vendor_cost_price = $total_vendor_cost_price+($products_fetch['products_item_cost']*$products_fetch['products_quantity']);
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format(($products_fetch['products_item_cost']*$products_fetch['products_quantity']),true,'USD','1.000000')."</td>";
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format($products_fetch['final_price'],true,'USD','1.000000')."</td>";
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format($prod_ordered_price,true,'USD','1.000000')."</td>";
						echo "<td class=dataTableContent align=left valign=top>".tep_datetime_short($products_fetch['date_purchased'])."</td>";
if ($products_fetch['products_sale_type']==1) $type='Direct Purchase';
elseif ($products_fetch['products_sale_type']==2) $type = 'Consignment';
elseif ($products_fetch['products_sale_type']==3) $type = 'Royalty';
elseif ($products_fetch['products_sale_type']==4) $type="Duplication";
elseif ($products_fetch['products_sale_type']==5) $type="Sample";
else $type = "Screener";

						echo "<td class=dataTableContent align=left valign=top>".$type."</td>";
					}

					echo "</tr>";
					echo "<tr><td colspan=6>".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td></tr>";
					echo "<tr><td colspan=8 align=left class=main><b>Total Orders: ".tep_draw_separator('pixel_trans.gif', 45, '10').$prod_total_sold."</td></tr>";
					echo "<tr><td colspan=8 align=left class=main><b>Total Vendor Sale: ".tep_draw_separator('pixel_trans.gif', 10, '10').$currencies->format($total_vendor_cost_price,true,'USD','1.000000')."</b></td></tr>";
					echo "<tr><td colspan=8 align=left class=main><b>Total Sale: ".tep_draw_separator('pixel_trans.gif', 60, '10').$currencies->format($prod_total_price,true,'USD','1.000000')."</b></td></tr>";
					echo "<tr><td colspan=8 align=right valign=top><a href='".FILENAME_VENDOR_PRODUCTS."'>".tep_image(DIR_WS_INCLUDES_LOCAL.'images/button_back.gif')."</td></tr>";
				}
?>
		</tr>
        </table><br></td>
      </tr>
    </table></td>
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


