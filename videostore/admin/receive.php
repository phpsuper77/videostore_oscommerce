<?php
/*
  $Id: vendor_payments.php,v 1.00 2003/06/26 23:21:48 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2003 Fernando Borcel
  Copyright (c) 2004 Joseph Passavanti

  Released under the GNU General Public License
*/
function getFormOptions($table, $formOptions)
{
	foreach ($formOptions as $key => $value) {
		$query_options = "SHOW COLUMNS FROM `".$table."` LIKE '{$key}'";
		$res_options = tep_db_query($query_options);
		$options = tep_db_result($res_options, 0, 'Type');
		$replacements = array('enum(', '\'', ')');
		$options = str_replace($replacements, '', $options);
		$options = explode(',', $options);
		$options2 = array();
		foreach ($options as $option) {
			$option = array('id' => $option, 'text' => $option);
			$options2[] = $option;
		}
		$formOptions[$key] = $options2;
	}
	return $formOptions;
}


  require('includes/application_top.php');
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style>
table.stdTable{
	border-collapse: collapse;
}

table.stdTable th {
	padding:2px;
	border: 1px solid #B3BAC5; 
	color: #B3BAC5;
	font-weight: bold;
	text-align:center;
	font-size: 11px;
}

table.stdTable th a {
	font-size: 11px;
	color: #B3BAC5;
	font-weight: bold;
}


table.stdTable td {
	padding:2px;
	border: 1px solid #B3BAC5; 	
}
</style>

<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" <?if ($_GET[onfocus]==1) {?>onload="document.upcform.upc.focus();"<?}?>>
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
<?
$vend = tep_db_fetch_array(tep_db_query("select b.vendors_name from vendor_purchase_orders a left join vendors b on (a.vendor_id=b.vendors_id) where a.po_id='".$_GET[id]."'"));
?>

          <tr>
            <td class="pageHeading">RECEIVING ITEMS <?if (trim($_GET[id])!='') {?>for <? echo $vend[vendors_name]; }?></td>
          </tr>
					<tr>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
<?
	if (($_GET[action]=='') and ($_POST[action]=='')){
	$sql_query = "select * from vendor_purchase_orders where item_status!='Received' order by po_id asc";
	$query_count = tep_db_num_rows(tep_db_query($sql_query));
	$bugs_split = new splitPageResults($HTTP_GET_VARS['page'], 100, $sql_query, $query_count);

?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td class="main" valign="top"><?php echo $bugs_split->display_count($query_count, 100, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="main" align="right"><?php echo $bugs_split->display_links($query_count, 100, 10, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
</table>
<br/>
<form action="receive.php" method="post">
	<input type="hidden" name="action" value="delete" />
	<table border="0" width="100%" cellspacing="0" class="stdTable">
<tr><td align='right' colspan="4"><a href='receive.php?action=details&id=' style="color:blue"><b>Add order</b></a></td></tr>
<tr>
	<th>Purchase Order</th>
	<th>Action</th>
	<th>Delete?</th>
	<th>Back to Stock?</th>
</tr>
	<tr><td>
<?
	$poses = tep_db_query($sql_query);
$i=0;
while ($pos = tep_db_fetch_array($poses)){
$class = '';
if ($i%2==0) $class = 'dataTableHeadingRow';
	echo "<tr class='".$class."'><td width='90%'>".$pos[po_id]."</td>";
	echo "<td align='center'><a href='receive.php?action=details&id=".$pos[po_id]."'>Details</a></td><td align='center'><input type='checkbox' name='delete[]' value='".$pos[po_id]."' /></td><td><input type='checkbox' name='back[]' value='".$pos[po_id]."' /></td></tr>";
$i++;
	}

?>
</td></tr>
<tr><td colspan="4" align="right"><input onclick="return confirm('Do you really want to delete this items?');" type="submit" value='Delete' /></td></tr>
        </table>
<br/>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td class="main" valign="top"><?php echo $bugs_split->display_count($query_count, 100, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="main" align="right"><?php echo $bugs_split->display_links($query_count, 100, 10, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
</table>
<? } 
if ($_POST[action]=='delete'){
	foreach($_POST['delete'] as $value){
	$add = tep_db_fetch_array(tep_db_query("select is_added from vendor_purchase_orders where po_id='".$value."' limit 1"));
	if (in_array($value, $_POST['back']) and intval($add[is_added])!=0){
		$sql_query = tep_db_query("select * from vendor_purchase_details where po_id='".$value."'");
		while($proc = tep_db_fetch_array($sql_query)){
		$quantity = tep_db_fetch_array(tep_db_query("select qty_received from vendor_purchase_order_details where  po_id='".$value."' and product_id='".$proc[product_id]."'"));
		tep_db_query("update products set products_quantity=products_quantity+".$quantity[qty_received]." where products_id='".$proc[product_id]."'");
		}
	}
		tep_db_query("delete from vendor_purchase_orders where po_id='".$value."'");
		tep_db_query("delete from vendor_purchase_order_details where po_id='".$value."'");
}
echo "<script>window.location.href='receive.php'</script>";
}


if ($_POST[action]=='save'){
	if (trim($_POST[old_po])=="")
	$sql_query = "insert into vendor_purchase_orders set po_id='".$_POST[po_id]."', vendor_id='".$_POST[vID]."', date='".date("Y-m-d")."', receive_type='".$_POST[receive_type]."', receive_date='".date("Y-m-d")."', item_status='".$_POST[item_status]."', is_added=0";
	else {
	$sql_query = "update vendor_purchase_orders set po_id='".$_POST[po_id]."', vendor_id='".$_POST[vID]."', date='".date("Y-m-d")."', receive_type='".$_POST[receive_type]."', receive_date='".date("Y-m-d")."', item_status='".$_POST[item_status]."', is_added=0 where po_id='".$_POST[old_po]."'";
	$sql_upd = "update vendor_purchase_order_details set po_id='".$_POST[po_id]."' where po_id='".$_POST[old_po]."'";
	tep_db_query($sql_upd);
}
	tep_db_query($sql_query);
	echo "<script>window.location.href='receive.php?action=details&id=".$_POST[po_id]."'</script>";
}

if ($_GET[action]=='details'){
	$sql_query = "select * from vendor_purchase_orders where po_id='".$_GET[id]."'";
	$pos = tep_db_fetch_array(tep_db_query($sql_query));

?>
	<table>
		<?if ($_GET[msg]!='') {?>
		<tr><td colspan="3" align="center"><font color="red"><b><?=$_GET[msg]?></b></font></td></tr>
		<?}?>
		<form action="receive.php" method="post">
			<input type="hidden" name="action" value="save" />
			<input type="hidden" name="old_po" value="<?=$pos[po_id]?>" />
		<tr>
			<td><b>PO ID:</b><br/><input type="text" name="po_id" value="<?=$pos[po_id]?>" style="width:150px;"/>
			</td>
		</tr>
		<tr>
			<td><b>Vendor:</b><br/><?=tep_draw_pull_down_menu('vID', tep_get_vendors(),$pos[vendor_id],'');?>
			</td>
		</tr>
<?
if ($pos[receive_type]=="Consignment") $selected_1 = "selected";
if ($pos[receive_type]=="Direct Purchase") $selected_2 = "selected";
if ($pos[receive_type]=="Royalty") $selected_3 = "selected";
?>
		<tr>
			<td><b>Received Type:</b><br/>
<select name="receive_type">
	<option value="Consignment" <?=$selected_1?>>Consignment</option>
	<option value="Direct Purchase" <?=$selected_2?>>Direct Purchase</option>
	<option value="Royalty" <?=$selected_3?>>Royalty</option>
</select>
			</td>
		</tr>
<?
if ($pos[item_status]=="ordered") $status_1 = "selected";
if ($pos[item_status]=="received") $status_2 = "selected";
if ($pos[item_status]=="backordered") $status_3 = "selected";
if ($pos[item_status]=="canceled") $status_4 = "selected";
if ($pos[item_status]=="short") $status_5 = "selected";
?>

		<tr>
			<td><b>Order Status:</b><br/>
<select name="item_status">
	<option value="Ordered" <?=$status_1?>>Ordered</option>
	<option value="Received" <?=$status_2?>>Received</option>
	<option value="Backordered" <?=$status_3?>>Backordered</option>
	<option value="Canceled" <?=$status_4?>>Canceled</option>
	<option value="Short" <?=$status_5?>>Short</option>
</select>
			</td>
		</tr>

		<tr>
			<td><br/><input type="submit" value="&nbsp;Save Order Info&nbsp;" />&nbsp;&nbsp;&nbsp;<input type="button" value="Cancel" onclick="location.href='receive.php'" /></td>
		</tr>
		</form>
	</table>
<br/>
<table>
              <tr class="dataTableHeadingRow">
                <th class="dataTableHeadingContent">Order Quantity</th>
                <th class="dataTableHeadingContent">Price per item</th>
                <th class="dataTableHeadingContent">Warehouse Location</th>
                <th class="dataTableHeadingContent">Picture</th>
                <th class="dataTableHeadingContent">OOP</th>
                <th class="dataTableHeadingContent">Model</th>
                <th class="dataTableHeadingContent">Retail Price</th>
                <th class="dataTableHeadingContent">On Hand</th>
                <th class="dataTableHeadingContent">Ordered</th>
                <th width="40%" class="dataTableHeadingContent">Name</th>
                <th class="dataTableHeadingContent">Item Status</th>
                <th class="dataTableHeadingContent">Received Date</th>
                <th class="dataTableHeadingContent">Received Type</th>
                <th class="dataTableHeadingContent">Delete?</th>
                <th class="dataTableHeadingContent">Back in Stock?</th>
              </tr>
              <form action="receive.php?vID=<?=$pos[vendor_id]?>&action=save_list" method="POST" name="list">
			<input type="hidden" name="poID" value="<?=$pos[po_id]?>" />
			<input type="hidden" name="act" value="" />
<?php
if ($pos[po_id]!=''){

		$formOptions = array(
			'receive_type' => array(),
			'item_status' => array()
		);
		$formOptions = getFormOptions(TABLE_VENDOR_PO, $formOptions);

					$query_order_items = "select `product_id`, `qty`, `price`, `qty_received`, `receive_type`, `receive_date`, `item_status` from `".TABLE_VENDOR_PO_DETAILS."` where `po_id` = '".$pos[po_id]."'";
					$res_order_items   = tep_db_query($query_order_items);
					$res_order_items_1   = tep_db_query($query_order_items);
					$orderItems = array();
					while ( $orderItem = tep_db_fetch_array($res_order_items) ) {
						$orderItems[$orderItem['product_id']] = $orderItem;
					}


					while ( $info = tep_db_fetch_array($res_order_items_1) ) {
						$pID = $info['product_id'];

						$query_product_info = "select `p`.`products_id`, `p`.`products_warehouse_location`, `p`.`products_image`, `p`.`products_out_of_print`, `p`.`products_model`, `p`.`products_price`, `p`.`products_quantity`, `p`.`products_ordered`, `pd`.`products_name_prefix`, `pd`.`products_name`, `pd`.`products_name_suffix` from `".TABLE_PRODUCTS."` as `p`, `".TABLE_PRODUCTS_DESCRIPTION."` as `pd` where `p`.`products_id` = `pd`.`products_id` and `p`.`products_id`=".$pID." group by products_id order by p.products_model, pd.products_name limit 1";
						$prod_info = tep_db_fetch_array(tep_db_query($query_product_info));
						echo '              <tr class="dataTableRowSelected" align="center">' . "\n";
						echo "								<td><input type=\"text\" name=\"qty_{$pID}\" size=\"5\" value=\"{$orderItems[$pID]['qty']}\"></td>" . "\n";
						echo "								<td><input type=\"text\" name=\"price_{$pID}\" size=\"6\" value=\"{$orderItems[$pID]['price']}\"></td>" . "\n";
						echo "								<td>{$prod_info['products_warehouse_location']}</td>" . "\n";
						echo "								<td><img src=\"/images/{$prod_info['products_image']}\"></td>" . "\n";
						echo "								<td>{$prod_info['products_out_of_print']}</td>" . "\n";
						echo "								<td>{$prod_info['products_model']}</td>" . "\n";
						printf('								<td>$%.2f</td>' . "\n", $prod_info['products_price']);
						echo "								<td>{$prod_info['products_quantity']}</td>" . "\n";
						echo "								<td>{$prod_info['products_ordered']}</td>" . "\n";
						echo "								<td>{$prod_info['products_name_prefix']} {$prod_info['products_name']} {$prod_info['products_name_suffix']}</td>" . "\n";
						echo "								<td>".tep_draw_pull_down_menu('status_'.$pID, $formOptions['item_status'], $orderItems[$pID]['item_status'])."</td>" . "\n";
						echo "								<td><input type='text' name='date_".$pID."' value='{$orderItems[$pID][receive_date]}' style='width:80px;'/></td>" . "\n";
						echo "								<td>".tep_draw_pull_down_menu('recType_'.$pID, $formOptions['receive_type'], $orderItems[$pID]['receive_type'])."</td>";
						echo "								<td><input type='checkbox' name='delete[]' value='".$pID."' /></td>";
						echo "								<td><input type='checkbox' name='back[]' value='".$pID."' /></td>";
						echo '							</tr>' . "\n";
					}
?>
<tr><td colspan="15" align="right"><input type="button" value="Delete" onclick="document.list.act.value='1'; document.list.submit();" /></td></tr>
						<tr>
							<td align="left" colspan="1" class="smallText">Add to Inventory?<br /><input type="checkbox" name="add_ok" value="1" /></td>
							<td align="left" colspan="1" class="smallText">Send Email?<br /><input type="checkbox" name="send_ok" value="1" /></td>

<?
$vend = tep_db_fetch_array(tep_db_query("select po_email_address from vendors where vendors_id=".$pos[vendor_id]));
?>
							<td align="left" colspan="1" class="smallText">Vendor Email:<br /><input type="text" value="<?=$vend[po_email_address]?>" readonly /></td></tr>
							<tr><td colspan="6" align="left" colspan="1" class="smallText">Email comments:<br /><textarea name="comments" style="width:500px; height:100px;"></textarea></td>

            	<td align="right" colspan="1" class="smallText"><?php echo tep_image_submit('button_save.gif', IMAGE_SAVE, 'onclick="return CheckField()"'); ?></td></tr>
            </tr>
          </form>
<script>
function CheckField(){
	val = document.getElementById('poID').value;
	if (val=='') {
		alert("Please, insert Purchase Order Number before saving");
		return false;
	}
	return true;
}
</script>
<tr><td><br/></td></tr>
<form action="receive.php?action=add_upc" method="post" name="upcform">
	<input type="hidden" name="poID" value="<?=$pos[po_id]?>" />
	<input type="hidden" name="vID" value="<?=$pos[vendor_id]?>" />
	<input type="hidden" name="item_status" value="<?=$pos[item_status]?>" />
	<input type="hidden" name="receive_type" value="<?=$pos[receive_type]?>" />
<tr>
	<td class="smallText">Insert UPC number: </td>
	<td colspan="5" class="smallText"><input name="upc" type="text" value="" style="width:250px;"/>&nbsp;<input type="submit" value="&nbsp;Add product&nbsp;" /></td>
</tr>
</form>
</table>
<?
	}
}

if ($_GET[action]=="add_upc"){
	$sql_query = "select products_id from products where products_upc='".$_POST[upc]."' limit 1";
	$product = tep_db_fetch_array(tep_db_query($sql_query));
	$sql_query = "select count(*) as cnt from vendor_purchase_order_details where po_id='".$_POST[poID]."' and product_id='".$product[products_id]."'";
	$exists = tep_db_fetch_array(tep_db_query($sql_query));
if ($exists[cnt]>0) echo "<script>window.location.href='receive.php?action=details&id=".$_POST[poID]."&msg=Such product already exists in this order!'</script>";
	if (intval($product[products_id]!=0)){
		$sql_query = "insert into vendor_purchase_order_details set po_id='".$_POST[poID]."', product_id='".$product[products_id]."', qty='', price='', qty_received='', receive_type='".$_POST[receive_type]."', receive_date='".date("Y-d-m")."', item_status='".$_POST[item_status]."'";
		tep_db_query($sql_query);
	}
	echo "<script>window.location.href='receive.php?action=details&id=".$_POST[poID]."&onfocus=1'</script>";
	
}

if ($_GET[action]=='save_list'){
  	$poID = tep_db_prepare_input($_POST['poID']);
  	$vendor_id = tep_db_prepare_input($_GET['vID']);

if ($_POST[act]=='')
{
$vend = tep_db_fetch_array(tep_db_query("select vendors_name from vendors where vendors_id=".$vendor_id));
$sup_address = '';
$vv = tep_db_fetch_array(tep_db_query("select * from vendors where vendors_id=".$vendor_id));
if (trim($vv[vendors_name])!='') $sup_address .=$vv[vendors_name]."<br>";
if (trim($vv['vendors_ship_addr1'])!='') $sup_address .=$vv[vendors_ship_addr1]."<br>";
if (trim($vv['vendors_ship_addr2'])!='') $sup_address .=$vv[vendors_ship_addr2]."<br>";
$sup_address .=$vv[vendors_ship_city]." ".$vv[vendors_ship_state]." ".$vv[vendors_ship_zip];

$supliers = "<table width='400'><tr><td width='150' align='left'>SUPPLIER:</td><td width='250' align='left'>".$sup_address."</td></tr></table>";
$email_body = stripslashes(nl2br($_POST[comments]))."<br><br>".$supliers."<br><br>TERMS: ".$term."<br><br>ACCOUNT NUMBER: ".$act."<br><br>We would like to order the following items:<br><br>";
$email_body .= "<table width='700' border='0'><tr><td align='left' width='100'>QTY</td><td align='left' width='150'>TVS MODEL#</td><td align='left' width='100'>VENDOR ITEM#</td><td  width='350' align='left'>TITLE</td></table><hr>";
$email_end = "<br><br>Ship items to:<br>TravelVIdeoStore.com<br>5420 Boran Dr<br>Tampa, FL 33610<br><br>We request that tracking numbers for our shipment be sent once they are available to us at suppliersupport@travelvideostore.com";

			foreach ($_POST as $k => $v) {
				if ( substr($k, 0, 4) == 'qty_' )	{
      		$productID = (int) substr($k, 4);
        	$productQty = (int) $v;
        	if ( $productQty > 0 ) {
			$send_email = true;
        		$recDate = $_POST['date_'.$productID];
			$sql_data_array['receive_date'] = ( $recDate == '0000-00-00' ) ? 'now()' : "'".$recDate."'";
        		$sql_data_array['receive_type'] = tep_db_prepare_input($_POST['recType_'.$productID]);
        		$sql_data_array['product_id'] = $productID;
        		$sql_data_array['qty'] = $productQty;
        		$sql_data_array['price'] = (float) $_POST['price_'.$productID];
        		$sql_data_array['item_status'] = tep_db_prepare_input($_POST['status_'.$productID]);
       			tep_db_perform(TABLE_VENDOR_PO_DETAILS, $sql_data_array, 'update', "po_id = '" . tep_db_input($poID) . "' and product_id = '" . tep_db_input($productID) . "'");

	if ($_POST[add_ok]!=''){
       			$query_update = "UPDATE `".TABLE_PRODUCTS."` SET ".
        			"`products_quantity` = `products_quantity` + {$sql_data_array['qty']} ".
       				"WHERE ".
       				"`products_id` = '{$sql_data_array['product_id']}'";
       			tep_db_query($query_update);
	}
	$sql_query = "select pv.vendors_item_number, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, p.products_ordered, p.products_model, p.products_quantity  from products p left join products_description pd on (p.products_id=pd.products_id) left join products_to_vendors pv on (p.products_id=pv.products_id) where p.products_id='{$sql_data_array[product_id]}' limit 1";
	$prod = tep_db_fetch_array(tep_db_query($sql_query));
$email_body .="<table width='700' border='0'><tr><td width='100'>".$sql_data_array['qty']."</td><td align='left' width='150'>".$prod[products_model]."</td><td width='100'>".$prod[vendors_item_number]."</td><td align='left' width='350'><b>".trim($prod[products_name_prefix]." ".$prod[products_name]." ".$prod[products_name_suffix])."</b></td></table><br>";
$total_items = $total_items+$sql_data_array['qty'];
						}
        }
    }
if ($_POST[send_ok]!=''){
	if ($send_email == true){
        		$query_check = "select `po_email_address`, `vendors_name` from `".TABLE_VENDORS."` where `vendors_id` = '{$vendor_id}'";
						$res_check = tep_db_query($query_check);
						if (tep_db_num_rows($res_check) > 0) {
							$row = tep_db_fetch_array($res_check);
							if ($row['po_email_address'] != '') {
								// Send email
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "Return-Path: Supplier Support<suppliersupport@travelvideostore.com>\r\n";
		$headers .= "From: TravelVideoStore.com Supplier Support <suppliersupport@travelvideostore.com>\r\n";
		$headers .= "To: ".$row[vendors_name]." <".$row[po_email_address].">\r\n";
		mail(trim($row[po_email_address]), 'TravelVideoStore.com Purchase Order '.$poID, $email_body."<br>Total items: ".$total_items.$email_end, $headers);
        	}
		$headers_cpy  = "MIME-Version: 1.0\r\n";
		$headers_cpy .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers_cpy .= "From: TravelVideoStore.com Supplier Support <suppliersupport@travelvideostore.com>\r\n";
		$headers_cpy .= "Return-Path: Supplier Support<suppliersupport@travelvideostore.com>\r\n";
		$headers_cpy .= "To: travelvideostore.com <suppliersupport@travelvideostore.com>\r\n";
		mail('suppliersupport@travelvideostore.com', 'TravelVideoStore.com Purchase Order '.$poID, $email_body."<br>Total items: ".$total_items.$email_end, $headers_cpy);
		//mail('x0661t@d-net.kiev.ua', 'TravelVideoStore.com Purchase Order '.$poID, $email_body."<br>Total items: ".$total_items.$email_end, $headers_cpy);
	}
     }
}
}
	else{
if (!empty($_POST[delete])){
	foreach($_POST['delete'] as $value){
	if (@in_array($value, $_POST['back'])){
		$added = tep_db_fetch_array(tep_db_query("select is_added from vendor_purchase_orders where po_id='".$_POST[poID]."' limit 0, 1"));
		if (intval($added[is_added])!=0){
		$quantity = tep_db_fetch_array(tep_db_query("select qty_received from vendor_purchase_order_details where  po_id='".$_POST[poID]."' and product_id='".$value."'"));
		tep_db_query("update products set products_quantity=products_quantity+".$quantity[qty_received]." where products_id='".$value."'");
		}
	}
	tep_db_query("delete from vendor_purchase_order_details where po_id='".$_POST[poID]."' and product_id='".$value."'");
	}
   }
}
	echo "<script>window.location.href='receive.php?action=details&id=".$poID."'</script>";
}
?>
</td>
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