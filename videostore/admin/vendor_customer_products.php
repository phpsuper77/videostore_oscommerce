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

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();


if ($_POST[action]=='save'){
	$te = "select op.orders_products_id, op.products_item_cost, op.products_sale_type, op.orders_id, op.products_id, op.products_model, op.products_name, op.final_price, op.products_quantity, o.date_purchased from ". TABLE_ORDERS_PRODUCTS ." op, ". TABLE_ORDERS ." o where o.orders_id = op.orders_id and op.orders_id = ". $_POST[orders_id];
	$pro = tep_db_query($te);
	while($row = tep_db_fetch_array($pro))	{
	$prod_id = $row[products_id];
	$ord_id = $row[orders_id];
	$orders_products_id = $row[orders_products_id];
	$sql_query = "update orders_products set final_price='".$vendor_final_price[$orders_products_id]."', products_sale_type='".$vendor_selling_type[$orders_products_id]."', products_item_cost='".$vendor_item_cost[$orders_products_id]."' where orders_products_id='".$orders_products_id."'";
	//echo $sql_query."<br>";
	tep_db_query($sql_query);	
	}
	echo "<script>window.location.href='vendor_customer_products.php?selected_box=vendors&vendors_id=".$_POST[vendors_id]."&order_id=".$_POST[orders_id]."';</script>";
exit;
}

  $prev_page = "vendor_order_products_all.php?selected_box=vendors&vendors_id=".$_GET[vendors_id];

  if (isset($HTTP_GET_VARS["vendors_id"]) && $HTTP_GET_VARS["vendors_id"] <> "")
  {
  	$id = $HTTP_GET_VARS["vendors_id"];
  	$orders_id = $HTTP_GET_VARS['order_id'];
  	$products_id = $HTTP_GET_VARS['prod_id'];

  	$orders_sel = tep_db_query("select op.orders_products_id, op.products_item_cost, op.products_sale_type, op.orders_id, op.products_id, op.products_model, op.products_name, op.final_price, op.products_quantity, o.date_purchased from ". TABLE_ORDERS_PRODUCTS ." op, ". TABLE_ORDERS ." o where o.orders_id = op.orders_id and op.orders_id = ". $orders_id." order by o.date_purchased");
  	$orders_count = tep_db_num_rows($orders_sel);
  }
  else
  {
	tep_redirect("../index.php");
	exit;
  }
//echo "select op.orders_products_id, op.products_item_cost, op.products_sale_type, op.orders_id, op.products_id, op.products_model, op.products_name, op.final_price, op.products_quantity, o.date_purchased from ". TABLE_ORDERS_PRODUCTS ." op, ". TABLE_ORDERS ." o where o.orders_id = op.orders_id and op.orders_id = ". $orders_id;
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
<form action="vendor_customer_products.php" method="post" >
	<input type="hidden" name="action" value="save" />
	<input type="hidden" name="orders_id" value="<?=$orders_id?>" />
	<input type="hidden" name="vendors_id" value="<?=$id?>" />
				<tr>
					<td><input type="button" value="Edit orders" onclick="showFields();" /></td><td style="display:none;" id="td_hide">Item Cost: <input type="text" id="item_all" name="item_all" value="" />&nbsp;<input type="button" value="&nbsp;Set&nbsp;" onclick="getAllItem();" /><br/>Price: <input type="text" id="price_all" name="price_all" value="" />&nbsp;<input type="button" value="&nbsp;Set&nbsp;" onclick="getAllPrice();" /><br/>Selling Type: <select id="type_all" name="type_all"><option value="1">Direct Purchase</option><option value="2">Consignment</option><option value="3">Royalty</option><option value="4">Duplication</option><option value="5">Sample</option><option value="6">Screener</option></select>&nbsp;<input type="button" value="&nbsp;Set&nbsp;" onclick="getAllType();" /></td><td align="right" colspan="5" class=smallText><input type="submit" id="but" style="display:none;" value="&nbsp;&nbsp;&nbsp;Save Changes&nbsp;&nbsp;&nbsp;" /></td><td align="right" colspan=3><a href='<?=$prev_page?>'><?=tep_image_button('button_back.gif',IMAGE_BACK)?></td>
				</tr>

					<tr>
						<td colspan=6><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td>
					</tr>
					<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent" align="left" width="20%"><?php echo TXT_MODEL_NO; ?></td>
						<td class="dataTableHeadingContent" align="left" width="30%"><?php echo TXT_DESC; ?>&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_QTY_SOLD; ?>&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="10%">Vendor Item Cost&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="10%">Vendor Total Cost&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_SELLING_PRICE; ?></td>
						<td class="dataTableHeadingContent" align="left" width="10%">Sale Price</td>
						<td class="dataTableHeadingContent" align="left" width="10%">Date Purchase</td>
						<td class="dataTableHeadingContent" align="left" width="10%">Selling Type&nbsp;</td>
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
						echo "<td class=dataTableContent align=left valign=top><div id='item_cost_".$orders_fetch[orders_products_id]."'>".$currencies->format($orders_fetch[products_item_cost],true,'USD','1.000000')."</div><div id='item_cost_".$orders_fetch[orders_products_id]."_a' style='display:none;'><input style='width:90px;' type='text' id='vendor_item_cost[$orders_fetch[orders_products_id]]' name='vendor_item_cost[$orders_fetch[orders_products_id]]' value='".$orders_fetch[products_item_cost]."' /></div></td>";
						echo "<td class=dataTableContent align=left valign=top>".($orders_fetch[products_item_cost]*$orders_fetch['products_quantity'])."</td>";
						echo "<td class=dataTableContent align=left valign=top><div id='final_price_".$orders_fetch[orders_products_id]."'>".$currencies->format($orders_fetch['final_price'],true,'USD','1.000000')."</div><div id='final_price_".$orders_fetch[orders_products_id]."_a' style='display:none;'><input style='width:90px;' type='text' id='vendor_final_price[$orders_fetch[orders_products_id]]' name='vendor_final_price[$orders_fetch[orders_products_id]]' value='".$orders_fetch[final_price]."' /></div></td>";
						echo "<td class=dataTableContent align=left valign=top>".($orders_fetch[final_price]*$orders_fetch[products_quantity])."</td>";
						echo "<td class=dataTableContent align=left valign=top>".tep_datetime_short($orders_fetch['date_purchased'])."</td>";
if ($orders_fetch['products_sale_type']==1) $typ="Direct Purchase";
elseif ($orders_fetch['products_sale_type']==2) $typ="Consignment";
elseif ($orders_fetch['products_sale_type']==3) $typ="Royalty";
elseif ($orders_fetch['products_sale_type']==4) $typ="Duplication";
elseif ($orders_fetch['products_sale_type']==5) $typ="Sample";
else $typ = "Screener";

						echo "<td class=dataTableContent align=left valign=top><div id='sale_type_".$orders_fetch[orders_products_id]."'>".$typ."</div><div style='display:none;' id='sale_type_".$orders_fetch[orders_products_id]."_a'><select id='vendor_selling_type[".$orders_fetch[orders_products_id]."]' name='vendor_selling_type[".$orders_fetch[orders_products_id]."]'><option value='1'>Direct Purchase</option><option value='2'>Consignment</option><option value='3'>Royalty</option><option value='4'>Duplication</option><option value='5'>Sample</option><option value='6'>Screener</option></select></div></td>";
					}
					echo "</tr>";
echo "<tr><td><br/></td></tr>";
$or = tep_db_query("SELECT title, value FROM orders_total where orders_id=".$orders_id." order by sort_order");
				while($row = tep_db_fetch_array($or))
					echo "<tr><td colspan=9 align='right'><span class=main><b>".$row[title]."</b> ".$currencies->format($row[value],true,'USD', '1.000000')."<span></td></tr>";
					echo "<tr><td colspan=9>".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td></tr>";
?>
					<tr><td colspan="2"><input type="button" value="Edit orders" onclick="showFields();" /></td><td align="right" colspan="5" class=smallText><input type="submit" id="but1" style="display:none;" value="&nbsp;&nbsp;&nbsp;Save Changes&nbsp;&nbsp;&nbsp;" /></td><td align="right" colspan=2><a href='<?=$prev_page?>'><?=tep_image_button('button_back.gif',IMAGE_BACK)?></td></tr>
<?

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
	$products = "select op.orders_products_id, op.products_item_cost, op.products_sale_type, op.orders_id, op.products_id, op.products_model, op.products_name, op.final_price, op.products_quantity, o.date_purchased from ". TABLE_ORDERS_PRODUCTS ." op, ". TABLE_ORDERS ." o where o.orders_id = op.orders_id and op.orders_id = ". $orders_id;
//echo $products;
	$products_selling = tep_db_query($products);
	$products_selling_new = tep_db_query($products);
	$products_selling_newes = tep_db_query($products);
	$products_selling_newes_item = tep_db_query($products);

		echo "<script>function showFields(){";
	while($products_f = tep_db_fetch_array($products_selling)){
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
		echo "document.getElementById('but').focus();";
		echo "document.getElementById('td_hide').style.display = 'block';";
		echo "}</script>";

		echo "<script>function getAllPrice(){";
	while($products_f1 = tep_db_fetch_array($products_selling_new)){
		echo "document.getElementById('vendor_final_price[".$products_f1[orders_products_id]."]').value=document.getElementById('price_all').value;";		
	}
		echo "}</script>";


		echo "<script>function getAllType(){";
	while($products_f1 = tep_db_fetch_array($products_selling_newes)){
		echo "val = document.getElementById('type_all').value;t_set(document.getElementById('vendor_selling_type[".$products_f1[orders_products_id]."]'), val);";
	}
		echo "}</script>";

		echo "<script>function getAllItem(){";
	while($products_f1 = tep_db_fetch_array($products_selling_newes_item)){
		echo "document.getElementById('vendor_item_cost[".$products_f1[orders_products_id]."]').value=document.getElementById('item_all').value;";		
	}
		echo "}</script>";

?>