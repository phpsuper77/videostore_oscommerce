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

  if (isset($HTTP_POST_VARS['link']))
  	$prev_url = $HTTP_POST_VARS['link'];
  else
    $prev_url = $_SERVER['HTTP_REFERER'];

  if ($HTTP_GET_VARS['products_id']) $products_id = $HTTP_GET_VARS['products_id'];
  if ($HTTP_POST_VARS['products_id']) $products_id = $HTTP_POST_VARS['products_id'];

  $upd_flag=0;
  switch ($HTTP_POST_VARS['act']) {
  case 'update_for_all':
 	  $vendors_item_cost =  tep_db_prepare_input($HTTP_POST_VARS['vendors_item_cost']);
 	  $vendors_product_payment_type =  tep_db_prepare_input($HTTP_POST_VARS['vendors_product_payment_type']);
 	  $vendors_product_sale_type = tep_db_prepare_input($HTTP_POST_VARS['vendors_product_sale_type']);
 	  $vendors_consignment = tep_db_prepare_input($HTTP_POST_VARS['vendors_consignment']);
 	  $vendors_consignment_percentage = tep_db_prepare_input($HTTP_POST_VARS['vendors_consignment_percentage']);
 	  $vendors_royalty =  tep_db_prepare_input($HTTP_POST_VARS['vendors_royalty']);
 	  $vendors_royalty_percentage = tep_db_prepare_input($HTTP_POST_VARS['vendors_royalty_percentage']);

	$products = tep_db_query("select * from products_to_vendors where vendors_id=".$HTTP_POST_VARS[vendors_id]);
	while($row = tep_db_fetch_array($products)){
	  $upd_sql = "update ".TABLE_PRODUCTS_TO_VENDORS." set vendors_item_cost='$vendors_item_cost', vendors_product_payment_type='$vendors_product_payment_type', vendors_product_sale_type='$vendors_product_sale_type', vendors_consignment='$vendors_consignment', vendors_consignment_percentage='$vendors_consignment_percentage', vendors_royalty='$vendors_royalty', vendors_royalty_percentage='$vendors_royalty_percentage' where products_id = ".$row['products_id']." and vendors_id = ".$HTTP_POST_VARS['vendors_id'];
	 //echo $upd_sql."<br/>";
	tep_db_query($upd_sql);
	}
  tep_redirect($prev_url);	
  break;
  case 'update':
 	  $vendors_item_number = tep_db_prepare_input($HTTP_POST_VARS['vendors_item_number']);
 	  $vendors_item_cost =  tep_db_prepare_input($HTTP_POST_VARS['vendors_item_cost']);
 	  $vendors_product_payment_type =  tep_db_prepare_input($HTTP_POST_VARS['vendors_product_payment_type']);
 	  $vendors_product_sale_type = tep_db_prepare_input($HTTP_POST_VARS['vendors_product_sale_type']);
 	  $vendors_consignment = tep_db_prepare_input($HTTP_POST_VARS['vendors_consignment']);
 	  $vendors_consignment_percentage = tep_db_prepare_input($HTTP_POST_VARS['vendors_consignment_percentage']);
 	  $vendors_royalty =  tep_db_prepare_input($HTTP_POST_VARS['vendors_royalty']);
 	  $vendors_royalty_percentage = tep_db_prepare_input($HTTP_POST_VARS['vendors_royalty_percentage']);

     $sql_data_array = array( 'vendors_item_number' => $vendors_item_number,
							  'vendors_item_cost' => $vendors_item_cost,
							  'vendors_product_payment_type' => $vendors_product_payment_type,
							  'vendors_product_sale_type' => $vendors_product_sale_type,
							  'vendors_consignment' => $vendors_consignment,
							  'vendors_consignment_percentage' => $vendors_consignment_percentage,
							  'vendors_royalty' =>  $vendors_royalty,
							  'vendors_royalty_percentage' => $vendors_royalty_percentage
      );

	  $upd_sql = "update ".TABLE_PRODUCTS_TO_VENDORS." set vendors_item_number='$vendors_item_number', vendors_item_cost='$vendors_item_cost', vendors_product_payment_type='$vendors_product_payment_type', vendors_product_sale_type='$vendors_product_sale_type', vendors_consignment='$vendors_consignment', vendors_consignment_percentage='$vendors_consignment_percentage', vendors_royalty='$vendors_royalty', vendors_royalty_percentage='$vendors_royalty_percentage' where products_id = ".tep_db_input($HTTP_POST_VARS['products_id'])." and vendors_id = ".tep_db_input($HTTP_POST_VARS['vendors_id']);
	  tep_db_query($upd_sql);
	  $upd_flag=1;
      tep_redirect($prev_url);
      break;
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="javascript">
function update()
{
	document.vendors_entry.act.value="update";
	document.vendors_entry.method="post";
	document.vendors_entry.action = "vendors_entry.php?selected_box=vendors&vendors_id="+<?php echo $HTTP_GET_VARS[vendors_id]?>;
	document.vendors_entry.submit();
}
function back()
{
	document.vendors_entry.method="POST";
	document.vendors_entry.action = document.vendors_entry.link.value;
	document.vendors_entry.submit();
}
function update_for_all(){
	if (confirm("Do you really want to update this information (except of vendor item number) for all products of this vendor?")){
	document.vendors_entry.act.value="update_for_all";
	document.vendors_entry.method="post";
	document.vendors_entry.action = "vendors_entry.php?selected_box=vendors&vendors_id="+<?php echo $HTTP_GET_VARS[vendors_id]?>;
	document.vendors_entry.submit();
	}
	else return false;
}

 </script>
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php   echo tep_draw_form('vendors_entry', FILENAME_VENDORS_ENTRY,'products_id='.$products_id,'post');
					echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="50%" cellspacing="0" cellpadding="2">
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
	$vendors_query_raw = "select products_id, vendors_item_number, vendors_item_cost, vendors_product_payment_type, vendors_product_sale_type, vendors_consignment, vendors_consignment_percentage, vendors_royalty, vendors_royalty_percentage from " . TABLE_PRODUCTS_TO_VENDORS . " where products_id = ".$products_id." and vendors_id = ".$HTTP_GET_VARS['vendors_id'];
	$vendors_query = tep_db_query($vendors_query_raw);
	$vendors = tep_db_fetch_array($vendors_query);
?>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo TEXT_VENDORS_ITEM_NUMBER?></td>
		<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '4', '20') . '&nbsp;' .tep_draw_input_field('vendors_item_number', $vendors['vendors_item_number'],'','','',false);?></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo TEXT_VENDORS_ITEM_COST?></td>
		<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '4', '15') . '&nbsp;' . tep_draw_input_field('vendors_item_cost', $vendors['vendors_item_cost'],'','','',false); ?></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo TEXT_VENDORS_PRODUCT_PAYMENT_TYPE?></td>
		<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '4', '15') . '&nbsp;' . tep_draw_pull_down_menu('vendors_product_payment_type',tep_get_product_payment_type(), $vendors['vendors_product_payment_type']); ?></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo TEXT_VENDORS_PRODUCT_SALE_TYPE?></td>
		<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '4', '15') . '&nbsp;' . tep_draw_pull_down_menu('vendors_product_sale_type',tep_get_product_sale_type(), $vendors['vendors_product_sale_type']);?></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo TEXT_VENDORS_CONSIGNMENT?></td>
		<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '4', '15') . '&nbsp;' . tep_draw_pull_down_menu('vendors_consignment', tep_get_consignment(), $vendors['vendors_consignment']);?></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo TEXT_VENDORS_CONSIGNMENT_PERCENTAGE?></td>
		<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '4', '15') . '&nbsp;' . tep_draw_input_field('vendors_consignment_percentage', $vendors['vendors_consignment_percentage'],'','','',false);?></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo TEXT_VENDORS_ROYALTY?></td>
		<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '4', '15') . '&nbsp;' . tep_draw_pull_down_menu('vendors_royalty', tep_get_royalty(), $vendors['vendors_royalty']);?></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo TEXT_VENDORS_ROYALTY_PERCENTAGE?></td>
		<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '4', '15') . '&nbsp;' . tep_draw_input_field('vendors_royalty_percentage', $vendors['vendors_royalty_percentage'],'','','',false);?></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
		<td class="main" colspan=2>
			<?php
				echo tep_draw_hidden_field('act');
				echo '<a onClick="javascript : update();" style="cursor:hand">'.tep_image_button('button_update.gif', IMAGE_UPDATE).'</a>';
				echo '&nbsp;<a onClick="javascript : back();" style="cursor:hand">' .tep_image_button('button_back.gif', IMAGE_BACK).'</a>';
				echo '&nbsp;<a onClick="javascript : update_for_all();" style="cursor:hand"><input type="button" value="Update for all Products!" /></a>';
			?>
		</td>
	</tr>
<?
	echo tep_draw_hidden_field('products_id', $products_id);
	echo tep_draw_hidden_field('vendors_id', $HTTP_GET_VARS['vendors_id']);
	echo tep_draw_hidden_field('link', $prev_url."&selected_box=vendors");
?>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '90');?></td>
	</tr>
	</form>
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