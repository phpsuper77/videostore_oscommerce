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
  require('includes/split_page_results.php');
  require('includes/functions.php');

  session_start();
  if (isset($_SESSION["vendors_id"]) && $_SESSION["vendors_id"] <> "")
  {
  	//$products_id_sql = tep_db_query("select products_id from ". TABLE_PRODUCTS_TO_VENDORS ." where vendors_id = ". $_SESSION["vendors_id"]);
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
	<td width="100%" class="headerNavigation">&nbsp;<a href="../index.php" class="headerNavigation"><?php echo TXT_HOME?></span></a>&nbsp;-&nbsp;<a href="vendor_account_products.php" class="headerNavigation"><?php echo TXT_PRODUCT_INFO;?></a>&nbsp;-&nbsp;<a href="vendor_order_products.php?prod_id=<?php echo $products_id?>" class="headerNavigation"><b><?php echo TXT_ORDER_PRODUCT;?></b></td>
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
					  <tr><td class="pageHeading1" colspan=6>ITEMS</td></tr>
					</table>
					</td>
      			</tr>
				<tr>
					<td colspan=6><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td>
				</tr>
<?
					$prod_total_price = 0;
					$total_qty_sold = 0;
					$i=1;
					$products_sel_sql = "select pv.vendors_item_number, p.products_model, pv.products_id, op.orders_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_sale_type,op.products_item_cost, o.date_purchased, pd.products_name, pd.products_name_prefix, pd.products_name_suffix from ". TABLE_ORDERS_PRODUCTS ." op, ". TABLE_PRODUCTS_TO_VENDORS ." pv, ". TABLE_ORDERS ." o, ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_DESCRIPTION." pd where pv.products_id = p.products_id and o.orders_id = op.orders_id and pd.products_id = pv.products_id and op.products_id = pv.products_id and pv.vendors_id = ". $_SESSION["vendors_id"] ." and op.products_sale_type='".$_GET['type']."' order by o.date_purchased desc";
					$products_sel_all = tep_db_query($products_sel_sql);


					//PAGE NUMBER
					$orders_query_count = tep_db_num_rows($products_sel_all);
				    $orders_split = new splitPageResults($HTTP_GET_VARS['page'], 100, $products_sel_sql, $orders_query_count);
					$total_vendor_cost_price = 0;

					$products_sel = tep_db_query($products_sel_sql);

?>
	              	<tr><td><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td></tr>
		             <tr>
		                <td colspan="11"><table border="0" width="100%" cellspacing="0" cellpadding="2">
		                  <tr>
		                    <td class="main" valign="top"><?php echo $orders_split->display_count($orders_query_count, 100, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
		                    <td class="main" align="right"><?php echo $orders_split->display_links($orders_query_count, 100, 10, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
		                  </tr>
		                </table></td>
	              	</tr>
					<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent" align="left" width="6%"><?php echo TXT_ORDER_ID; ?>&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="15%">Model No&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="24%"><?php echo TXT_PRODUCT_NAME; ?>&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="7%"><?php echo TXT_QTY_SOLD; ?>&nbsp;</td>
						<td class='dataTableHeadingContent' align='left' width='10%'>Vendor Item No&nbsp;</td>
						<td class='dataTableHeadingContent' align='left' width='10%'><?php echo TXT_VENDORS_ITEM_COST; ?>&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="5%"><?php echo TXT_VENDOR_TOTAL; ?></td>
						<td class="dataTableHeadingContent" align="left" width="5%"><?php echo TXT_SELLING_PRICE; ?></td>
						<td class="dataTableHeadingContent" align="left" width="5%"><?php echo TXT_TOTAL; ?></td>
						<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_ORDER_DATE; ?></td>
						<td class="dataTableHeadingContent" align="left" width="25%"><?php echo TXT_SELLING_TYPE; ?></td>
					</tr>
<?
					while($products_fetch = tep_db_fetch_array($products_sel))
					{
					//$total_qty_sold = $total_qty_sold + $products_fetch['products_quantity'];
						$tot_vc=0;
						$vendor_det = tep_db_query("select * from ". TABLE_PRODUCTS_TO_VENDORS ." where products_id = ".$products_fetch['products_id']." and vendors_id = ".$_SESSION["vendors_id"]);
						$vendor_det_rs = tep_db_fetch_array($vendor_det);

						$prod_ordered_price = $products_fetch['products_quantity'] * $products_fetch['final_price'];
						//$prod_total_price += $prod_ordered_price;


						echo "<tr id=dataTableRow class=dataTableRow onmouseover=rowOverEffect(this) onmouseout=rowOutEffect(this)>";
						echo "<td class=dataTableContent align=left valign=top><a href=".FILENAME_VENDOR_CUSTOMER_PRODUCTS.	"?page=".$_GET[page]."&order_id=".$products_fetch['orders_id'].">".$products_fetch['orders_id']."</a></td>";
						echo "<td class=dataTableContent align=left valign=top>".tep_db_prepare_input(tep_output_string($products_fetch['products_model']))."</td>";
						echo "<td class=dataTableContent align=left valign=top>".tep_db_prepare_input(tep_output_string($products_fetch['products_name_prefix']))."&nbsp;".trim(tep_db_prepare_input(tep_output_string($products_fetch['products_name'])))."&nbsp;".trim(tep_db_prepare_input(tep_output_string($products_fetch['products_name_suffix'])))."</td>";
						echo "<td class=dataTableContent align=left valign=top>".$products_fetch['products_quantity']."</td>";
						echo "<td class=dataTableContent align=left valign=top>".$products_fetch['vendors_item_number']."</td>";
//$total_vendor_cost_price = $total_vendor_cost_price+($products_fetch['products_item_cost']*$products_fetch['products_quantity']);
$tot = $products_fetch[products_item_cost];
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format($tot,true,'USD','1.000000')."</td>";
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format(($products_fetch[products_item_cost]*$products_fetch['products_quantity']),true,'USD','1.000000')."</td>";
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format($products_fetch['final_price'],true,'USD','1.000000')."</td>";
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format($prod_ordered_price,true,'USD','1.000000')."</td>";
						echo "<td class=dataTableContent align=left valign=top>".tep_datetime_short($products_fetch['date_purchased'])."</td>";
						echo "<td class=dataTableContent align=center valign=top>";
if ($products_fetch['products_sale_type']==1) echo "Direct Purchase";
if ($products_fetch['products_sale_type']==2) echo "Cosnignment";
if ($products_fetch['products_sale_type']==3) echo "Royalty";
if ($products_fetch['products_sale_type']==4) echo "Duplication";
if ($products_fetch['products_sale_type']==5) echo "Sample";
if ($products_fetch['products_sale_type']==6) echo "Screener";
echo "</td>";

						//$qty_sold = tep_db_query("select products_quantity from ".TABLE_ORDERS_PRODUCTS." where orders_id=". $products_fetch["orders_id"]);
						//$qty_sold_rs = tep_db_fetch_array($qty_sold);
					}

while($products_fetch = tep_db_fetch_array($products_sel_all)){
	$total_qty_sold = $total_qty_sold + $products_fetch['products_quantity'];
	$tot_vc=0;
	$prod_ordered_price = $products_fetch['products_quantity'] * $products_fetch['final_price'];
	$prod_total_price += $prod_ordered_price;
	$total_vendor_cost_price = $total_vendor_cost_price+($products_fetch['products_item_cost']*$products_fetch['products_quantity']);

}

					echo "</tr>";
					echo "<tr><td colspan=6>".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td></tr>";
					echo "<tr><td colspan=8 align=left class=main><b>Total Items: ".tep_draw_separator('pixel_trans.gif', 45, '10').$total_qty_sold."</td></tr>";
					echo "<tr><td colspan=8 align=left class=main><b>Total Vendor Sale: ".tep_draw_separator('pixel_trans.gif', 10, '10').$currencies->format($total_vendor_cost_price,true,'USD','1.000000')."</b></td></tr>";
					echo "<tr><td colspan=8 align=left class=main><b>Total Sale: ".tep_draw_separator('pixel_trans.gif', 60, '10').$currencies->format($prod_total_price,true,'USD','1.000000')."</b></td></tr>";
					echo "<tr><td></td></tr>";
if ($type==1) $stat = 'Direct Purchase';
if ($type==2) $stat = 'Consignment';
if ($type==3) $stat = 'Royalty';

$total_pay = tep_db_fetch_array(tep_db_query("SELECT sum(payment) AS sum FROM `vendor_payments` WHERE vendor_id=".$_SESSION['vendors_id']." and type='".$stat."' and status='active'"));
					echo "<tr><td colspan=8 class=main><b>Total Payments: ".tep_draw_separator('pixel_trans.gif', 30, '10').$currencies->format($total_pay[sum],true,'USD','1.000000')."</b></td></tr>";
					echo "<tr><td></td></tr>";
$total = tep_db_fetch_array(tep_db_query("SELECT sum(products_item_cost*products_quantity) as sum FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=".$_GET['type']." ORDER  BY o.date_purchased DESC"));
$final = $total[sum]-$total_pay[sum];
					echo "<tr><td colspan=8 class=main><b>Total Pending Due: ".tep_draw_separator('pixel_trans.gif', 10, '10').$currencies->format($final,true,'USD','1.000000')."</b></td></tr>";
?>
		</tr>
	              	<tr><td><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td></tr>
		             <tr>
		                <td colspan="11"><table border="0" width="100%" cellspacing="0" cellpadding="2">
		                  <tr>
		                    <td class="main" valign="top"><?php echo $orders_split->display_count($orders_query_count, 100, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
		                    <td class="main" align="right"><?php echo $orders_split->display_links($orders_query_count, 100, 10, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
		                  </tr>
		                </table></td>
	              	</tr>
	              	<tr><td><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td></tr>
<?php
	              echo "<tr><td colspan=11 align=right valign=top><a href='vendor_account_view.php'>".tep_image(DIR_WS_INCLUDES_LOCAL.'images/button_back.gif')."</td></tr>";
?>
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
