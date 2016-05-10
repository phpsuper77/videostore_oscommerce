<?php
ob_start();
/*
  $Id: vendors.php,v 1.00 2003/06/26 23:21:48 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2003 Fernando Borcel
  Copyright (c) 2004 Joseph Passavanti

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

    require(DIR_WS_CLASSES . 'currencies.php');
    $currencies = new currencies();

if ($_POST[action]=="del_record"){
if (count($_POST[del])>0){
	  foreach($_POST[del] as $k=>$v){
		$sql_query = "delete from products_to_vendors where products_id=".$v." and vendors_id=".$_POST[vendors_id]."";
		tep_db_query($sql_query);
	}
}
        $prod = tep_db_query("select products_id from products_to_vendors where vendors_id='".$_POST[vendors_id]."'");

	while($row = tep_db_fetch_array($prod)){
		$ids = $row[products_id];
		if ($_POST[print_out][$ids]==1) $out = 1; else $out = 0;
		if ($_POST[status][$ids]==1) $stat = 1; else $stat = 0;
		$sql_query = "update products set products_out_of_print='".$out."', products_status='".$stat."' where products_id='".$ids."'";
		tep_db_query($sql_query);
}

	echo "<script>window.location.href='vendors_info.php?vendors_id=".$_POST[vendors_id]."'</script>";
}
///jim+ MSHM  20100220 keep vendor in view
    $vendor_id=0;
    if (isset($_GET[vendors_id]))
        $vendor_id = $_GET[vendors_id];
    $vend = tep_db_fetch_array(tep_db_query("select * from vendors where vendors_id=".$vendor_id));
///jim-
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
          <td class="pageHeading"><?php

           if (trim($vend[vendors_name])>"")
           $tailpiece= " for ". $vend[vendors_name];

           echo HEADING_TITLE . $tailpiece; ?></td>
              <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
			   <table border="0" width="100%" cellspacing="0" cellpadding="2">
				<tr>
					<td class="smallText" align="right"><?php echo TABLE_HEADING_VENDOR_CHOOSE; ?><?php echo tep_draw_form('vendors_info', FILENAME_VENDOR_INFO,'','get') . tep_draw_pull_down_menu('vendors_id', tep_get_vendors(),'','onChange="this.form.submit()";');?></form></td>

				  </tr>
				</table>
			<tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td>
			</tr>
			<tr>
			  <td colspan=7>
<?php
  				if (isset($HTTP_POST_VARS['vendors_id']) || isset($HTTP_GET_VARS['vendors_id'])) {
				if (isset($HTTP_POST_VARS['vendors_id'])) $id = $HTTP_POST_VARS['vendors_id'];
				else if (isset($HTTP_GET_VARS['vendors_id'])) $id = $HTTP_GET_VARS['vendors_id'];
				  $products_sel = tep_db_query("select a.*, b.products_status, b.products_out_of_print from ". TABLE_PRODUCTS_TO_VENDORS ." a LEFT JOIN products b on (a.products_id=b.products_id) where vendors_id = ". $id." order by b.products_model");
?>
<form action="vendors_info.php" method="POST">
	<input type="hidden" name="action" value="del_record" />
	<input type="hidden" name="vendors_id" value="<?=$id?>" />
				<tr>
					<td align="right"><input type="submit" onclick="return confirm('Do you really want to make those changes?');" value="Save Changes" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo FILENAME_VENDOR_ORDER_PRODUCTS_ALL?>?selected_box=vendors&vendors_id=<?php echo $id;?>"><?php echo tep_image_button('button_display_all.gif');?></a></td>
				</tr>

				<tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td>
				</tr>

                    <tr>
			            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
			              <tr class="dataTableHeadingRow">
			                <td class="dataTableHeadingContent" align="left" width="15%"><?php echo TXT_MODEL_NO; ?>&nbsp;</td>
			                <td class="dataTableHeadingContent" align="left" width="44%"><?php echo TXT_DESC; ?></td>
			                <td class="dataTableHeadingContent" align="left" width="5%"><?php echo TXT_QTY_SOLD; ?>&nbsp;</td>
		             		<td class="dataTableHeadingContent" align="left" width="8%"><?php echo TXT_QTY_ONHAND; ?>&nbsp;</td>
							<td class="dataTableHeadingContent" align="left" width="8%"><?php echo TXT_LIST_PRICE; ?></td>
							<td class="dataTableHeadingContent" align="left" width="10%"><?php echo TXT_SELLING_PRICE; ?></td>
							<td class="dataTableHeadingContent" align="left" width="10%">Action</td>
							<td class="dataTableHeadingContent" align="left" width="10%">Delete Record?</td>
							<td class="dataTableHeadingContent" align="left" width="10%">In Print?</td>
							<td class="dataTableHeadingContent" align="left" width="10%">Status</td>
			              </tr>
<?
				while($products_fetch = tep_db_fetch_array($products_sel))
				{
					$item_det_sql = tep_db_query("select products_id, products_model, products_quantity, products_price, products_ordered from ". TABLE_PRODUCTS ." where products_id = ". $products_fetch["products_id"]);
					$item_det_rs = tep_db_fetch_array($item_det_sql);

					$item_desc_sql = tep_db_query("select products_name_prefix, products_name, products_name_suffix from ". TABLE_PRODUCTS_DESCRIPTION ." where products_id = ". $products_fetch["products_id"]);
					$item_desc_rs = tep_db_fetch_array($item_desc_sql);

					$item_spl_price = tep_db_query("select specials_new_products_price from ".TABLE_SPECIALS." where products_id = ". $products_fetch["products_id"]);
					$item_spl_rs = tep_db_fetch_array($item_spl_price);

					$qty_sold = tep_db_query("select sum(products_quantity) as quantity_sold from ".TABLE_ORDERS_PRODUCTS." where products_id=". $products_fetch["products_id"]);
					$qty_sold_rs = tep_db_fetch_array($qty_sold);


					echo "<tr id=dataTableRow class=dataTableRow onmouseover=rowOverEffect(this) onmouseout=rowOutEffect(this)>";
					echo "<td class=dataTableContent align=left valign=top>".$item_det_rs['products_model']."</td>";
					echo "<td class=dataTableContent align=left valign=top><a href=".FILENAME_VENDORS_ENTRY."?selected_box=vendors&products_id=".$products_fetch["products_id"]."&vendors_id=".$id.">".tep_db_prepare_input(tep_output_string($item_desc_rs['products_name_prefix']))."&nbsp;".trim(tep_db_prepare_input(tep_output_string($item_desc_rs['products_name'])))."&nbsp;".trim(tep_db_prepare_input(tep_output_string($item_desc_rs['products_name_suffix'])))."</a></td>";

					echo "<td class=dataTableContent align=left valign=top>".$qty_sold_rs['quantity_sold']."</td>";
					echo "<td class=dataTableContent align=left valign=top>".$item_det_rs['products_quantity']."</td>";
					echo "<td class=dataTableContent align=left valign=top>".$currencies->format($item_det_rs['products_price'],true,'USD', '1.000000')."</td>";
					if (isset($item_spl_rs['specials_new_products_price']) && ($item_spl_rs['specials_new_products_price'] <> ''))
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format($item_spl_rs['specials_new_products_price'],true,'USD', '1.000000')."</td>";
					else
						echo "<td class=dataTableContent align=left valign=top>-</td>";
					echo "<td class=dataTableContent align=left valign=top><a href=".FILENAME_VENDOR_ORDER_PRODUCTS."?selected_box=vendors&vendors_id=".$id."&prod_id=".$item_det_rs['products_id'].">".tep_image_button('button_orders.gif',IMAGE_ORDERS)."</a></td>";
					echo "<td class=dataTableContent align=left valign=top><input type='checkbox' name='del[]' value='".$products_fetch[products_id]."' /></td>";
if ($products_fetch['products_out_of_print']==1)
	$print = "checked";
	else
	$print = "";
					echo "<td class=dataTableContent align=left valign=top><input type='checkbox' name='print_out[$products_fetch[products_id]]' value='1' ".$print." /></td>";
if ($products_fetch['products_status']==1)
	$stat = "checked";
	else
	$stat = "";

					echo "<td class=dataTableContent align=left valign=top><input type='checkbox' name='status[$products_fetch[products_id]]' value='1' ".$stat."/></td>";
				}
?>


            </table></form><?php
  }
?></td>
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