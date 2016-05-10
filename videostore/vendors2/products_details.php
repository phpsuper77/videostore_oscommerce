<?php
ob_start();
/*
  $Id: vendor_account_products.php,v 1.80 2005/22/08 23:28:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . 'english/'.  FILENAME_VENDOR_PRODUCTS);

  require(DIR_WS_INCLUDES .'database_tables.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  session_start();

if ($_POST[action]=='save_prod'){

	if ($_GET['product_type']=='listed'){
		$sql_query = "SELECT  * FROM products_to_vendors a LEFT  JOIN products b ON ( a.products_id = b.products_id ) WHERE a.vendors_id =".$vendors_id." AND b.products_status =1 AND vendors_product_payment_type=".$_GET['type']." order by b.products_model";
  		$products_sel = tep_db_query($sql_query);
		}
		else{
		$sql_query = "SELECT pv.* FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$vendors_id." and op.products_sale_type=".$_GET[type]." ORDER  BY o.date_purchased DESC";
  		$products_sel = tep_db_query($sql_query);
		}

while($products_fetch = tep_db_fetch_array($products_sel)){
	$sql_query = "update products_to_vendors set vendors_item_number='".$item_no[$products_fetch[products_id]]."', replenish='".$replenish[$products_fetch[products_id]]."' where products_id=".$products_fetch[products_id];
	tep_db_query($sql_query);
	}
echo '<script>window.location.href="products_details.php?type='.$_GET[type].'&product_type='.$_GET[product_type].'";</script>';
}

  if (isset($_SESSION["vendors_id"]) && $_SESSION["vendors_id"] <> "")
  {
	if ($_GET['product_type']=='listed'){
		$sql_query = "SELECT  * FROM products_to_vendors a LEFT  JOIN products b ON ( a.products_id = b.products_id ) WHERE a.vendors_id =".$vendors_id." AND b.products_status =1 AND vendors_product_payment_type='".$_GET['type']."' order by b.products_model";
  		$products_sel = tep_db_query($sql_query);
		}
		else{
		$sql_query = "SELECT pv.* FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$vendors_id." and op.products_sale_type=".$_GET[type]." ORDER  BY o.date_purchased DESC";
  		$products_sel = tep_db_query($sql_query);
		}
  }
  else
  {
	tep_redirect("index.php");
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
	<td width="100%" class="headerNavigation">&nbsp;<a href="../index.php" class="headerNavigation"><?php echo TXT_HOME?></span></a>&nbsp;-&nbsp;<a href="vendor_account_products.php" class="headerNavigation"><?php echo TXT_PRODUCT_INFO;?></a></td>
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
					<td colspan=7 width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
					  <tr><td class="pageHeading1" colspan=6><?php echo HEADING_TITLE; ?></td></tr>
					</table>
					</td>
      			</tr>
				<tr>
					<td colspan=7><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td>
				</tr>
<form action='products_details.php?type=<?=$_GET[type]?>&product_type=<?=$_GET[product_type]?>' method='post'>
		<input type="hidden" name="action" value="save_prod" />
				<tr>
					<td align="left" class="main" width=95%><b>P.V</b> - Products Viewed&nbsp;&nbsp;<b>Q.S</b> - Quantity Sold&nbsp;&nbsp;<b>Q.H</b> - Quantity on Hand&nbsp;&nbsp;<b>I.C</b> - Vendor Item Cost&nbsp;&nbsp;<b>L.P</b> - List Price&nbsp;&nbsp;<b>S.P</b> - Selling Price</td>
					<td align="right" valign="bottom"><input type="submit" value='Save Changes' />&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo FILENAME_VENDOR_ORDER_PRODUCTS_ALL?>"><?php echo tep_image(DIR_WS_INCLUDES_LOCAL.'images/display_all.gif');?></a></td>
				</tr>
				<tr>
					<td colspan=7><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td>
				</tr>
	          <tr>
	            <td valign="top" colspan=7><table border="0" width="100%" cellspacing="1" cellpadding="2">
	              <tr class="dataTableHeadingRow">
	                <td class="dataTableHeadingContent" align="left" width="15%"><?php echo TXT_MODEL_NO; ?>&nbsp;</td>
	                <td class="dataTableHeadingContent" align="left" width="15%"><?php echo TXT_ITEM_NO; ?>&nbsp;</td>
	                <td class="dataTableHeadingContent" align="left" width="35%"><?php echo TXT_DESC; ?></td>
	                <td class="dataTableHeadingContent" align="left" width="5%">Replenish Q-ty</td>
	                <td class="dataTableHeadingContent" align="left" width="5%"><?php echo TXT_PRODUCT_VIEWED; ?></td>
	                <td class="dataTableHeadingContent" align="left" width="5%"><?php echo TXT_QTY_SOLD; ?>&nbsp;</td>
             		<td class="dataTableHeadingContent" align="left" width="5%"><?php echo TXT_QTY_ONHAND; ?>&nbsp;</td>
             		<td class="dataTableHeadingContent" align="left" width="5%"><?php echo TXT_ITEM_COST; ?>&nbsp;</td>
					<td class="dataTableHeadingContent" align="left" width="5%"><?php echo TXT_LIST_PRICE; ?></td>
					<td class="dataTableHeadingContent" align="left" width="5%"><?php echo TXT_SELLING_PRICE; ?></td>
					<td class="dataTableHeadingContent" align="left" width="5%">Action</td>
	              </tr>
<?
				$prod_viewed_count = 0;
				$qty_on_hand = 0;
				$total_qty_sold = 0;
				while($products_fetch = tep_db_fetch_array($products_sel))
				{
					$item_det_sql = tep_db_query("select products_id, products_model, products_quantity, products_price, products_ordered from ". TABLE_PRODUCTS ." where products_id = ". $products_fetch["products_id"]);
					$item_det_rs = tep_db_fetch_array($item_det_sql);

					$item_desc_sql = tep_db_query("select products_name_prefix, products_name, products_name_suffix, products_clip_url from ". TABLE_PRODUCTS_DESCRIPTION ." where products_id = ". $products_fetch["products_id"]);
					$item_desc_rs = tep_db_fetch_array($item_desc_sql);

					$item_spl_price = tep_db_query("select specials_new_products_price from ".TABLE_SPECIALS." where products_id = ". $products_fetch["products_id"]);
					$item_spl_rs = tep_db_fetch_array($item_spl_price);

					$item_view_sql = tep_db_query("select products_viewed from ".TABLE_PRODUCTS_DATA." where products_id = ". $products_fetch["products_id"]);
					$item_view_rs = tep_db_fetch_array($item_view_sql);

					$vendor_det = tep_db_query("select vendors_item_number, vendors_item_cost from ".TABLE_PRODUCTS_TO_VENDORS." where products_id = ".$item_det_rs['products_id']." and vendors_id = ". $vendors_id);
					$vendor_det_rs = tep_db_fetch_array($vendor_det);

					$qty_sold = tep_db_query("select sum(products_quantity) as quantity_sold from ".TABLE_ORDERS_PRODUCTS." where products_id=". $products_fetch["products_id"]);
					$qty_sold_rs = tep_db_fetch_array($qty_sold);

					echo "<tr id=dataTableRow class=dataTableRow onmouseover=rowOverEffect(this) onmouseout=rowOutEffect(this)>";
					echo "<td class=dataTableContent align=left valign=top>".$item_det_rs['products_model']."</td>";
					echo "<td class=dataTableContent align=left valign=top><input type='text' size='7' name='item_no[".$item_det_rs['products_id']."]' value='".$products_fetch['vendors_item_number']."'/></td>";
					echo "<td class=dataTableContent align=left valign=top><a href=".FILENAME_VENDOR_PRODUCTS_DISP."?prod_id=".$item_det_rs['products_id'].">".tep_db_prepare_input(tep_output_string($item_desc_rs['products_name_prefix']))."&nbsp;".trim(tep_db_prepare_input(tep_output_string($item_desc_rs['products_name'])))."&nbsp;".trim(tep_db_prepare_input(tep_output_string($item_desc_rs['products_name_suffix'])))."</a> </td>";
					echo "<td class=dataTableContent align=left valign=top><input type='text' size='3' name='replenish[".$item_det_rs['products_id']."]' value='".$products_fetch[replenish]."'/></td>";
					echo "<td class=dataTableContent align=left valign=top>".$item_view_rs['products_viewed']."</td>";
					echo "<td class=dataTableContent align=left valign=top>".$qty_sold_rs['quantity_sold']."</td>";
					echo "<td class=dataTableContent align=left valign=top>".$item_det_rs['products_quantity']."</td>";
					echo "<td class=dataTableContent align=left valign=top>".$currencies->format($vendor_det_rs['vendors_item_cost'],true,'USD', '1.000000')."</td>";
					echo "<td class=dataTableContent align=left valign=top>".$currencies->format($item_det_rs['products_price'],true,'USD', '1.000000')."</td>";
					if (isset($item_spl_rs['specials_new_products_price']) && ($item_spl_rs['specials_new_products_price'] <> ''))
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format($item_spl_rs['specials_new_products_price'],true,'USD', '1.000000')."</td>";
					else
						echo "<td class=dataTableContent align=left valign=top>-</td>";
					echo "<td class=dataTableContent align=left valign=top><a href=".FILENAME_VENDOR_ORDER_PRODUCTS."?prod_id=".$item_det_rs['products_id'].">".tep_image(DIR_WS_INCLUDES_LOCAL.'images/button_orders.gif')."</a></td>";

					$prod_viewed_count += $item_view_rs['products_viewed'];
					$qty_on_hand += $item_det_rs['products_quantity'];
					$total_qty_sold += $qty_sold_rs['quantity_sold'];
				}
?>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class=dataTableContent align=right valign=top><b>Total: &nbsp;</b></td>
			<td class=dataTableContent align=left valign=top><b><?php echo $prod_viewed_count?></b></td>
			<td class=dataTableContent align=left valign=top><b><?php echo $total_qty_sold?></b></td>
			<td class=dataTableContent align=left valign=top><b><?php echo $qty_on_hand?></b></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
</form>
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