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

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();


if ($_POST[action]=='save'){
	$te = "select  op.orders_products_id, op.products_id, op.orders_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_sale_type, o.date_purchased from ". TABLE_ORDERS_PRODUCTS ." op,". TABLE_ORDERS ." o where o.orders_id = op.orders_id and op.products_id = ". $_POST[products_id];
	$pro = tep_db_query($te);
	while($row = tep_db_fetch_array($pro))	{
	$prod_id = $row[products_id];
	$ord_id = $row[orders_id];
	$orders_products_id = $row[orders_products_id];

	$sql_query = "update orders_products set final_price='".$vendor_final_price[$orders_products_id]."', products_sale_type='".$vendor_selling_type[$orders_products_id]."', products_item_cost='".$vendor_item_cost[$orders_products_id]."' where orders_products_id='".$orders_products_id."'";
	//echo $sql_query."<br>";
	tep_db_query($sql_query);	
	}
	echo "<script>window.location.href='vendor_order_products.php?selected_box=vendors&vendors_id=".$_POST[vendors_id]."&prod_id=".$_POST[products_id]."'</script>";
exit;
}



  if (isset($HTTP_GET_VARS["vendors_id"]) && $HTTP_GET_VARS["vendors_id"] <> "")
  {
  	$id = $HTTP_GET_VARS["vendors_id"];
  	$products_id = $HTTP_GET_VARS['prod_id'];
  	$products_sel = tep_db_query("select op.orders_products_id, op.products_item_cost, op.products_id, op.orders_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_sale_type, o.date_purchased from ". TABLE_ORDERS_PRODUCTS ." op,". TABLE_ORDERS ." o where o.orders_id = op.orders_id and op.products_id = ". $products_id." order by o.date_purchased desc");
  	$prod_count = tep_db_num_rows($products_sel);
  	$products_fetch = tep_db_fetch_array($products_sel);

	$vendor_det = tep_db_query("select * from ". TABLE_PRODUCTS_TO_VENDORS ." where products_id = ".$products_id." and vendors_id = ".$id);
	$vendor_det_rs = tep_db_fetch_array($vendor_det);

  }
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
function rowOverEffect(object) {
  if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
}


//--></script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
        <td width="100%" colspan=6><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
				<tr>
					<td colspan=6><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td>
				</tr>

<?php
				if ($prod_count == 0)
				{
					echo "<tr><td class=errorText align=left valign=top><span class=main><b>No records found!!!</b></span></td></tr>";
					echo "<tr><td>".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td></tr>";
					echo "<tr><td colspan=7 align=right valign=top><a href='".FILENAME_VENDOR_INFO."?selected_box=vendors&vendors_id=".$id."'>".tep_image_button('button_back.gif',IMAGE_BACK)."</td></tr>";
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
	
					echo "<br><span class=main><b>".TXT_MODEL_NO .":</b> ".tep_draw_separator('pixel_trans.gif', 40, 1).$products_fetch['products_model']."</span><Br>";
					echo "<span class=main><b>".TXT_DESC .":</b> ".tep_draw_separator('pixel_trans.gif', 18, 1).tep_output_string($products_fetch['products_name'])."</span>";
					echo "</td></tr>";


					tep_db_data_seek($products_sel, 0);
?>
<form action="vendor_order_products.php" method="post">
	<input type="hidden" name="action" value="save" />
	<input type="hidden" name="vendors_id" value="<?=$id?>" />
	<input type="hidden" name="products_id" value="<?=$products_id?>" />
				<tr>
					<td><input type="button" value="Edit orders" onclick="showFields();" /></td><td style="display:none;" id="td_hide">Item Cost: <input type="text" id="item_all" name="item_all" value="" />&nbsp;<input type="button" value="&nbsp;Set&nbsp;" onclick="getAllItem();" /><br/>Price: <input type="text" id="price_all" name="price_all" value="" />&nbsp;<input type="button" value="&nbsp;Set&nbsp;" onclick="getAllPrice();" /><br/>Selling Type: <select id="type_all" name="type_all"><option value="1">Direct Purchase</option><option value="2">Consignment</option><option value="3">Royalty</option><option value="4">Duplication</option><option value="5">Sample</option><option value="6">Screener</option></select>&nbsp;<input type="button" value="&nbsp;Set&nbsp;" onclick="getAllType();" /></td><td align="right" colspan="7" class=smallText><input type="submit" id="but" style="display:none;" value="&nbsp;&nbsp;&nbsp;Save Changes&nbsp;&nbsp;&nbsp;" />&nbsp;<a href='<?=FILENAME_VENDOR_INFO?>?selected_box=vendors&vendors_id=<?=$id?>'><?=tep_image_button('button_back.gif',IMAGE_BACK)?></td></td>
				</tr>

					<tr>
						<td colspan=6><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td>
					</tr>

					<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent" align="left" width="7%"><?php echo TXT_ORDER_ID; ?>&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_QTY_SOLD; ?>&nbsp;</td>
						<td class='dataTableHeadingContent' align='left' width='10%'>Vendor Item Cost</td>
						<td class="dataTableHeadingContent" align="left" width="8%">Vendor Total Cost</td>
						<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_SELLING_PRICE; ?></td>
						<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_TOTAL; ?></td>
						<td class="dataTableHeadingContent" align="left" width="15%"><?php echo TXT_ORDER_DATE; ?></td>
						<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_SELLING_TYPE; ?></td>
					</tr>
<?php
					$prod_total_price = 0;					
					$total_vendor_cost_price = 0;

					while($products_fetch = tep_db_fetch_array($products_sel))
					{
					if ($products_fetch['orders_id']!=$prev) $order=$order+1;
						$prev = $products_fetch['orders_id'];

						$prod_ordered_price = $products_fetch['products_quantity'] * $products_fetch['final_price'];
						$prod_total_sold += $products_fetch['products_quantity'];
						$prod_total_price += $prod_ordered_price;
						echo "<tr id=dataTableRow class=dataTableRow onmouseover=rowOverEffect(this) onmouseout=rowOutEffect(this)>";
						echo "<td class=dataTableContent align=left valign=top><a href=".FILENAME_VENDOR_CUSTOMER_PRODUCTS.	"?selected_box=vendors&vendors_id=".$id."&order_id=".$products_fetch['orders_id']."&prod_id=".$products_id.">".$products_fetch['orders_id']."</a></td>";
						echo "<td class=dataTableContent align=left valign=top>".$products_fetch['products_quantity']."</td>";
$total_vendor_cost_price = $total_vendor_cost_price+($products_fetch[products_item_cost]*$products_fetch['products_quantity']);
						echo "<td class=dataTableContent align=left valign=top><div id='item_cost_".$products_fetch[orders_products_id]."'>".$currencies->format($products_fetch[products_item_cost],true,'USD','1.000000')."</div><div id='item_cost_".$products_fetch[orders_products_id]."_a' style='display:none;'><input style='width:90px;' type='text' id='vendor_item_cost[$products_fetch[orders_products_id]]' name='vendor_item_cost[$products_fetch[orders_products_id]]' value='".$products_fetch[products_item_cost]."' /></div></td>";
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format(($products_fetch[products_item_cost]*$products_fetch['products_quantity']),true,'USD','1.000000')."</td>";
						echo "<td class=dataTableContent align=left valign=top><div id='final_price_".$products_fetch[orders_products_id]."'>".$currencies->format($products_fetch['final_price'],true,'USD','1.000000')."</div><div id='final_price_".$products_fetch[orders_products_id]."_a' style='display:none;'><input style='width:90px;' type='text' id='vendor_final_price[$products_fetch[orders_products_id]]' name='vendor_final_price[$products_fetch[orders_products_id]]' value='".$products_fetch[final_price]."' /></div></td>";
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format($prod_ordered_price,true,'USD','1.000000')."</td>";
						echo "<td class=dataTableContent align=left valign=top>".tep_datetime_short($products_fetch['date_purchased'])."</td>";
if ($products_fetch['products_sale_type']==1) $typ="Direct Purchase";
elseif ($products_fetch['products_sale_type']==2) $typ="Consignment";
elseif ($products_fetch['products_sale_type']==3) $typ="Royalty";
elseif ($products_fetch['products_sale_type']==4) $typ="Duplication";
elseif ($products_fetch['products_sale_type']==5) $typ="Sample";
else $typ = "Screener";
						echo "<td class=dataTableContent align=left valign=top><div id='sale_type_".$products_fetch[orders_products_id]."'>".$typ."</div><div style='display:none;' id='sale_type_".$products_fetch[orders_products_id]."_a'><select id='vendor_selling_type[".$products_fetch[orders_products_id]."]' name='vendor_selling_type[".$products_fetch[orders_products_id]."]'><option value='1'>Direct Purchase</option><option value='2'>Consignment</option><option value='3'>Royalty</option><option value='4'>Duplication</option><option value='5'>Sample</option><option value='6'>Screener</option></select></div></td>";
					}
					echo "</tr>";
					echo "<tr><td colspan=6>".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td></tr>";
					echo "<tr><td colspan=8 align=left class=main><b>Total Orders: ".tep_draw_separator('pixel_trans.gif', 45, '10').$order."</td></tr>";
					echo "<tr><td colspan=8 align=left class=main><b>Total items: ".tep_draw_separator('pixel_trans.gif', 55, '10').$prod_total_sold."</td></tr>";
					echo "<tr><td colspan=8 align=left class=main><b>Total Vendor Sale: ".tep_draw_separator('pixel_trans.gif', 10, '10').$currencies->format($total_vendor_cost_price,true,'USD','1.000000')."</b></td></tr>";
					echo "<tr><td colspan=8 align=left class=main><b>Total Sale: ".tep_draw_separator('pixel_trans.gif', 60, '10').$currencies->format($prod_total_price,true,'USD','1.000000')."</b></td></tr>";
					echo "<tr><td><input type='button' value='Edit orders' onclick='showFields();' /></td><td colspan=7 align='right'><input type='submit' id='but1' style='display:none;' value='&nbsp;&nbsp;&nbsp;Save Changes&nbsp;&nbsp;&nbsp;' /><a href='".FILENAME_VENDOR_INFO."?selected_box=vendors&vendors_id=".$id."'>".tep_image_button('button_back.gif',IMAGE_BACK)."</td></tr>";


				}
?>
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
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

<script>

	function t_set(obj,index){
		for(i=0;i<obj.length;i++){
			if (obj[i].value==index){
				obj.selectedIndex=i;
				break;
			}
		}				
	}	

</script>
<?

  	$products = tep_db_query("select  op.orders_products_id, op.products_id, op.orders_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_sale_type, o.date_purchased from ". TABLE_ORDERS_PRODUCTS ." op,". TABLE_ORDERS ." o where o.orders_id = op.orders_id and op.products_id = ". $products_id);
	$products_new = tep_db_query("select  op.orders_products_id, op.products_id, op.orders_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_sale_type, o.date_purchased from ". TABLE_ORDERS_PRODUCTS ." op,". TABLE_ORDERS ." o where o.orders_id = op.orders_id and op.products_id = ". $products_id);
	$products_newest = tep_db_query("select  op.orders_products_id, op.products_id, op.orders_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_sale_type, o.date_purchased from ". TABLE_ORDERS_PRODUCTS ." op,". TABLE_ORDERS ." o where o.orders_id = op.orders_id and op.products_id = ". $products_id);
	$products_newest_item = tep_db_query("select  op.orders_products_id, op.products_id, op.orders_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_sale_type, o.date_purchased from ". TABLE_ORDERS_PRODUCTS ." op,". TABLE_ORDERS ." o where o.orders_id = op.orders_id and op.products_id = ". $products_id);

		echo "<script>function showFields(){";
	while($products_f = tep_db_fetch_array($products)){
		echo "document.getElementById('item_cost_".$products_f[orders_products_id]."').style.display='none';";
		echo "document.getElementById('item_cost_".$products_f[orders_products_id]."_a').style.display='block';";
		echo "document.getElementById('final_price_".$products_f[orders_products_id]."').style.display='none';";
		echo "document.getElementById('final_price_".$products_f[orders_products_id]."_a').style.display='block';";
		echo "document.getElementById('sale_type_".$products_f[orders_products_id]."').style.display='none';";
		echo "document.getElementById('sale_type_".$products_f[orders_products_id]."_a').style.display='block';";
		echo "t_set(document.getElementById('vendor_selling_type[".$products_f[orders_products_id]."]'), '".$products_f[products_sale_type]."');";

	}
		echo "document.getElementById('but').style.display = 'block';";
		echo "document.getElementById('but1').style.display = 'block';";
		echo "document.getElementById('td_hide').style.display = 'block';";
		echo "document.getElementById('but').focus();";
		echo "}</script>";

		echo "<script>function getAllPrice(){";
	while($products_f1 = tep_db_fetch_array($products_new)){
		echo "document.getElementById('vendor_final_price[".$products_f1[orders_products_id]."]').value=document.getElementById('price_all').value;";		
	}
		echo "}</script>";


		echo "<script>function getAllType(){";
	while($products_f1 = tep_db_fetch_array($products_newest)){
		echo "val = document.getElementById('type_all').value;t_set(document.getElementById('vendor_selling_type[".$products_f1[orders_products_id]."]'), val);";
	}
		echo "}</script>";


		echo "<script>function getAllItem(){";
	while($products_f1 = tep_db_fetch_array($products_newest_item)){
		echo "document.getElementById('vendor_item_cost[".$products_f1[orders_products_id]."]').value=document.getElementById('item_all').value;";		
	}
		echo "}</script>";

?>
