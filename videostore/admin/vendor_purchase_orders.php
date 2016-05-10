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
$send_email = false;
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
	$vendor_id = (int) $_GET['vID'];
	$poID      = tep_db_prepare_input($_GET['poID']);
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

  switch ($_GET['action']) {
    case 'add_upc':
	$sql_query = "select products_id from products where products_upc='".$_POST[upc]."' limit 1";
	$product = tep_db_fetch_array(tep_db_query($sql_query));
	$sql_query = "select count(*) as cnt from vendor_purchase_order_details where po_id='".$_POST[poID]."' and product_id='".$product[products_id]."'";
	$exists = tep_db_fetch_array(tep_db_query($sql_query));
if ($exists[cnt]>0) echo "<script>window.location.href='vendor_purchase_orders.php?view=edit&page=1&vID=".$_POST[vID]."&poID=".$_POST[poID]."&msg=Such product already exists in this order!'</script>";

	if (intval($product[products_id]!=0)){
		$sql_query = "insert into vendor_purchase_order_details set po_id='".$_POST[poID]."', product_id='".$product[products_id]."', qty='', price='', qty_received='', receive_type='Consignment', receive_date='".date("Y-d-m")."', item_status='recevied'";
		tep_db_query($sql_query);
		$msg="Successfully Added";
	}
		else
		$msg="Product with such UPC not found in database";
      tep_redirect(tep_href_link(FILENAME_VENDOR_PO, 'view='.$_POST[view].'&page=1&vID=' . $_POST[vID].'&poID='.$_POST[poID].'&onfocus=1&msg='.$msg));		
	break;

    case 'delete':
	if ($_GET[sub]==1) {
	$add = tep_db_fetch_array(tep_db_query("select is_added from vendor_purchase_orders where po_id='".$_GET[poID]."' limit 1"));
	if (intval($add[is_added])!=0){
	$sql_query = tep_db_query("select * from vendor_purchase_order_details where po_id='".$_GET[poID]."'");
	while($row = tep_db_fetch_array($sql_query)){
		$sql = "update products set products_quantity = products_quantity - ".intval($row[qty_received])." where products_id=".$row[product_id];	
		//echo $sql."<br/>";
		tep_db_query($sql);
		}
	}
}
	tep_db_query("delete from vendor_purchase_order_details where po_id='".$_GET[poID]."'");
//echo "delete from vendor_purchase_order_details where po_id='".$_GET[poID]."'<br/>";
	tep_db_query("delete from vendor_purchase_orders where po_id='".$_GET[poID]."'");
//echo "delete from vendor_purchase_orders where po_id='".$_GET[poID]."'<br/>";
      tep_redirect(tep_href_link(FILENAME_VENDOR_PO, 'vID=' . $vendor_id));
    break;

    case 'insert':
    case 'save':
      $item_status  = tep_db_prepare_input($_POST['status']);
      $order_date   = tep_db_prepare_input($_POST['date']);

    	$poID = tep_db_prepare_input($_POST['poID']);
    	$poIDold = tep_db_prepare_input($_POST['poIDold']);

if ($_POST[act]=='1'){
if (!empty($_POST[delete])){
	foreach($_POST['delete'] as $value){
	if (@in_array($value, $_POST['back'])){
		$quantity = tep_db_fetch_array(tep_db_query("select qty_received from vendor_purchase_order_details where  po_id='".$_POST[poID]."' and product_id='".$value."'"));
		tep_db_query("update products set products_quantity=products_quantity+".$quantity[qty_received]." where products_id='".$value."'");
	}
	tep_db_query("delete from vendor_purchase_order_details where po_id='".$_POST[poID]."' and product_id='".$value."'");
	}
}
	echo "<script>window.location.href='vendor_purchase_orders.php?view=".$_POST[view]."&page=".$_POST[page]."&vID=".$_GET[vID]."&poID=".$_POST[poID]."'</script>";
}

    	if ( !$poID ) {
    		tep_redirect(tep_href_link(FILENAME_VENDOR_PO, 'vID=' . $vendor_id . '&poID=new' . '&error=You have to enter a valid purchase order ID!'));
    		break;
    	}

if ($_POST[add_ok]==1) $is_added = 1; else $is_added = 0;

if ($poIDold=='') $poIDold = $poID;

			$sql_data_array = array(
 	    	'po_id'       => $poIDold,
   	  	'vendor_id'   => $vendor_id,
   	  	'item_status' => $item_status,
   	  	'is_added' => $is_added
     	);

     	$sql_data_array['date'] = ( $order_date == '0000-00-00' ) ? 'now()' : $order_date;
		if ($poIDold!='')
			$query_check = "select `po_id` from `".TABLE_VENDOR_PO."` where `po_id` = '{$poIDold}'";
			else
			$query_check = "select `po_id` from `".TABLE_VENDOR_PO."` where `po_id` = '{$poID}'";
//echo $query_check;
			$res_check = tep_db_query($query_check);


      if ($_GET['action'] == 'insert') {
				if ( tep_db_num_rows($res_check) > 0 ) {
					tep_redirect(tep_href_link(FILENAME_VENDOR_PO, 'vID=' . $vendor_id . '&poID=new' . '&error=The purchase order ID ' . $poID . ' exists already!'));
    			break;
				}
        tep_db_perform(TABLE_VENDOR_PO, $sql_data_array);
      } elseif ($_GET['action'] == 'save') {
				if ( tep_db_num_rows($res_check) == 0 ) {
					tep_redirect(tep_href_link(FILENAME_VENDOR_PO, 'vID=' . $vendor_id . '&error=The purchase order ID ' . $poID . ' does not exist!'));
    			break;
				}
        tep_db_perform(TABLE_VENDOR_PO, $sql_data_array, 'update', "po_id = '" . $poIDold . "'");
      }

	if ($poIDold!='') tep_db_query("update ".TABLE_VENDOR_PO." set po_id='".$poID."' where po_id='".$poIDold."'");

			$sql_data_array = array(
  	   	'po_id'       => $poID,
    	 	'product_id'  => 0,
      	'qty'         => 0,
      	'price'       => 0,
      	'item_status' => 0
      );
			foreach ($_POST as $k => $v) {
				if ( substr($k, 0, 4) == 'qty_' )	{
      		$productID = (int) substr($k, 4);
        	$productQty = (int) $v;
        	if ( $productQty > 0 ) {
			$send_email = true;
     			$sql_data_array['receive_date'] = ( $order_date == '0000-00-00' ) ? 'now()' : $order_date;
        		$sql_data_array['product_id'] = $productID;
        		$sql_data_array['qty'] = $productQty;
        		$sql_data_array['price'] = (float) $_POST['price_'.$productID];
        		$sql_data_array['item_status'] = tep_db_prepare_input($_POST['status_'.$productID]);
        		if ($_GET['action'] == 'insert' || $_POST['new_'.$productID] ) {
        			tep_db_perform(TABLE_VENDOR_PO_DETAILS, $sql_data_array);
        		} else {
        			tep_db_perform(TABLE_VENDOR_PO_DETAILS, $sql_data_array, 'update', "po_id = '" . tep_db_input($poID) . "' and product_id = '" . tep_db_input($productID) . "'");
        		}
	if ($_POST[add_ok]!=''){
			//if ($_GET['action'] == 'insert')
       			$query_update = "UPDATE `".TABLE_PRODUCTS."` SET ".
        			"`products_quantity` = `products_quantity` + {$sql_data_array['qty']} ".
       				"WHERE ".
       				"`products_id` = '{$sql_data_array['product_id']}'";
			//else
       			//$query_update = "UPDATE `".TABLE_PRODUCTS."` SET ".
        		//	"`products_quantity` = '{$sql_data_array[qty]}' ".
       			//	"WHERE ".
       			//	"`products_id` = '{$sql_data_array['product_id']}'";			

       			tep_db_query($query_update);
	}
	if ($poIDold!='') tep_db_query("update ".TABLE_VENDOR_PO_DETAILS." set po_id='".$poID."' where po_id='".$poIDold."'");
	$sql_query = "select pv.vendors_item_number, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, p.products_ordered, p.products_model, p.products_quantity  from ((products p ) left join products_description pd on (p.products_id=pd.products_id)) left join products_to_vendors pv on (p.products_id=pv.products_id) where p.products_id='{$sql_data_array[product_id]}' limit 1";
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
							if (trim($row['po_email_address']) != '') {
								// Send email
		$mime_boundary = md5(time());
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; boundary=\"".$mime_boundary."\"; charset=iso-8859-1\r\n";
		$headers .= "From: TravelVideoStore.com Supplier Support <suppliersupport@travelvideostore.com>\r\n";
		$headers .= "Return-Path: Supplier Support<suppliersupport@travelvideostore.com>\r\n";
		$headers .= "Message-ID: <".time()." TheSystem@".$_SERVER['SERVER_NAME'].">\r\n";
		$headers .= "X-Mailer: PHP v".phpversion()."\r\n"; 
		$headers .= "To: ".$row[vendors_name]." <".$row[po_email_address].">\r\n";
		mail(trim($row[po_email_address]), 'TravelVideoStore.com Purchase Order '.$poID, $email_body."<br>Total items: ".$total_items.$email_end, $headers);
        	}
		$headers_cpy  = "MIME-Version: 1.0\r\n";
		$headers_cpy .= "Content-type: text/html; boundary=\"".$mime_boundary."\"; charset=iso-8859-1\r\n";
		$headers_cpy .= "Return-Path: Supplier Support<suppliersupport@travelvideostore.com>\r\n";
		$headers_cpy .= "Message-ID: <".time()." TheSystem@".$_SERVER['SERVER_NAME'].">\r\n";
		$headers_cpy .= "X-Mailer: PHP v".phpversion()."\r\n"; 
		$headers_cpy .= "From: TravelVideoStore.com Supplier Support <suppliersupport@travelvideostore.com>\r\n";
		$headers_cpy .= "To: travelvideostore.com <suppliersupport@travelvideostore.com>\r\n";
		mail('suppliersupport@travelvideostore.com', 'TravelVideoStore.com Purchase Order '.$poID, $email_body."<br>Total items: ".$total_items.$email_end, $headers_cpy);
	}
     }
}
      tep_redirect(tep_href_link(FILENAME_VENDOR_PO, 'vID=' . $vendor_id));
      break;
		case 'rec':
			$poID = tep_db_prepare_input($_POST['poID']);
			$poIDold = tep_db_prepare_input($_POST['poIDold']);

if ($_POST[act]=='1'){
if (!empty($_POST[delete])){
	foreach($_POST['delete'] as $value){
	if (@in_array($value, $_POST['back'])){
		$quantity = tep_db_fetch_array(tep_db_query("select qty_received from vendor_purchase_order_details where  po_id='".$_POST[poID]."' and product_id='".$value."'"));
		tep_db_query("update products set products_quantity=products_quantity+".$quantity[qty_received]." where products_id='".$value."'");
	}
	tep_db_query("delete from vendor_purchase_order_details where po_id='".$_POST[poID]."' and product_id='".$value."'");
	}
}
	echo "<script>window.location.href='vendor_purchase_orders.php?view=".$_POST[view]."&page=".$_POST[page]."&vID=".$_GET[vID]."&poID=".$_POST[poID]."'</script>";
}

	    $receive_type = tep_db_prepare_input($_POST['type']);
	    $receive_date = tep_db_prepare_input($_POST['date']);
	    $item_status  = tep_db_prepare_input($_POST['status']);

			if ( $poIDold ) {
				$query_check = "select `po_id` from `".TABLE_VENDOR_PO."` where `po_id` = '{$poIDold}'";
				$res_check = tep_db_query($query_check);
				if ( tep_db_num_rows($res_check) == 0 ) {
    			tep_redirect(tep_href_link(FILENAME_VENDOR_PO, 'vID=' . $vendor_id . '&poID=new' . '&error=The purchase order ID ' . $poID . ' does not exist!'));
    			break;
    		}

				$sql_data_array1 = array(
 	    		'po_id'        => $poIDold,
   	  		'receive_type' => $receive_type,
   	  		'item_status'  => $item_status   	  		
     		);
     		$sql_data_array1['receive_date'] = ( $receive_date == '0000-00-00' ) ? 'now()' : $receive_date;
				tep_db_perform(TABLE_VENDOR_PO, $sql_data_array1, 'update', "po_id = '" . tep_db_input($poID) . "'");
    	}
		if ($poIDold!='') tep_db_query("update ".TABLE_VENDOR_PO." set po_id='".$poID."' where po_id='".$poIDold."'");

			$sql_data_array = array(
  	   	'po_id'        => tep_db_input($poIDold),
      );
			foreach ($_POST as $k => $v) {
				if ( substr($k, 0, 4) == 'rec_' )	{
      		$productID = (int) substr($k, 4);
        	$productRec = (int) $v;
        	if ( $productRec > 0 ) {
			$send_email = true;
        		$sql_data_array['product_id'] = $productID;
        		$sql_data_array['qty_received'] = $productRec;
        		$sql_data_array['receive_type'] = tep_db_prepare_input($_POST['recType_'.$productID]);
        		$recDate = $_POST['date_'.$productID];
						$sql_data_array['receive_date'] = ( $recDate == '0000-00-00' ) ? 'now()' : "'".$recDate."'";
        		$sql_data_array['item_status'] = tep_db_prepare_input($_POST['status_'.$productID]);
        		if ( $poID ) {
        			$query_update = "UPDATE `".TABLE_VENDOR_PO_DETAILS."` SET ".
	        			"`qty_received` = `qty_received` + {$sql_data_array['qty_received']}, ".
  	      			"`receive_type` = '{$sql_data_array['receive_type']}', ".
    	    			"`receive_date` = {$sql_data_array['receive_date']}, ".
      	  			"`item_status` = '{$sql_data_array['item_status']}' ".
        				"WHERE ".
        				"`po_id` = '{$sql_data_array['po_id']}' AND".
        				"`product_id` = '{$sql_data_array['product_id']}'";
        			tep_db_query($query_update);
        		}
		if ($_POST[add_ok]!=''){
       			$query_update = "UPDATE `".TABLE_PRODUCTS."` SET ".
        			"`products_quantity` = `products_quantity` + {$sql_data_array['qty_received']} ".
       				"WHERE ".
       				"`products_id` = '{$sql_data_array['product_id']}'";
       			tep_db_query($query_update);
			}

$sql_query = "select pv.vendors_item_number, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, p.products_model, p.products_ordered, p.products_quantity from ((products p) left join products_description pd on (p.products_id=pd.products_id)) left join products_to_vendors pv on (p.products_id=pv.products_id) where p.products_id='{$sql_data_array[product_id]}' limit 1";
$prod = tep_db_fetch_array(tep_db_query($sql_query));
$email_body .="<table width='700' border='0'><tr><td width='100'>".$sql_data_array['qty_received']."</td><td align='left' width='150'>".$prod[products_model]."</td><td width='100'>".$prod[vendors_item_number]."</td><td align='left' width='350'><b>".trim($prod[products_name_prefix]." ".$prod[products_name]." ".$prod[products_name_suffix])."</b></td></table><br>";
$total_items = $total_items+$sql_data_array['qty_received'];
        	}
        }
      }
			if ( $_POST['apply_all'] == '1' ) {
				tep_db_perform(TABLE_VENDOR_PO_DETAILS, $sql_data_array1, 'update', "po_id = '" . tep_db_input($poIDold) . "'");
			}

	if ($poIDold!='') tep_db_query("update ".TABLE_VENDOR_PO_DETAILS." set po_id='".$poID."' where po_id='".$poIDold."'");


if ($_POST[send_ok]!=''){
	if ($send_email == true){
        		$query_check = "select `po_email_address`, `vendors_name` from `".TABLE_VENDORS."` where `vendors_id` = '{$vendor_id}'";
						$res_check = tep_db_query($query_check);
						if (tep_db_num_rows($res_check) > 0) {
							$row = tep_db_fetch_array($res_check);
							if (trim($row['po_email_address']) != '') {

								// Send email
		$mime_boundary=md5(time());
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; boundary=\"".$mime_boundary."\"; charset=iso-8859-1\r\n";
		$headers .= "Return-Path: Supplier Support <suppliersupport@travelvideostore.com>\r\n";
		$headers .= "From: TravelVideoStore.com Supplier Support <suppliersupport@travelvideostore.com>\r\n";
		$headers .= "To: ".$row[vendors_name]." <".$row[po_email_address].">\r\n";
		mail(trim($row[po_email_address]), 'TravelVideoStore.com Purchase Order '.$poID, $email_body."<br>Total items: ".$total_items.$email_end, $headers);
		}

		$headers_cpy  = "MIME-Version: 1.0\r\n";
		$headers_cpy .= "Content-type: text/html; boundary=\"".$mime_boundary."\"; charset=iso-8859-1\r\n";
		$headers_cpy .= "Return-Path: Supplier Support<suppliersupport@travelvideostore.com>\r\n";
		$headers_cpy .= "From: TravelVideoStore.com Supplier Support <suppliersupport@travelvideostore.com>\r\n";
		$headers_cpy .= "To: travelvideostore.com <suppliersupport@travelvideostore.com>\r\n";
		mail('suppliersupport@travelvideostore.com', 'TravelVideoStore.com Purchase Order '.$poID, $email_body."<br>Total items: ".$total_items.$email_end, $headers_cpy);

	}
    }
}
      tep_redirect(tep_href_link(FILENAME_VENDOR_PO, 'vID=' . $vendor_id . '&msg=The purchase order has been successfully received!'));
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
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?> for <?=$vend[vendors_name]?></td>
          </tr>
					<tr>
						<td class="smallText" align="right"><?php echo 'Select Vendor:'; ?><?php echo tep_draw_form('vendors_report', FILENAME_VENDOR_PO,'','get') . tep_draw_pull_down_menu('vID', tep_get_vendors(),'','onChange="this.form.submit()";');?></form></td>
					</tr>
					<tr>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" class="smalltext">
<?php
if ( $poID && !$vendor_id ) {
	$query_vendor_id = "select `vendor_id` from `".TABLE_VENDOR_PO."` where `id` = '{$poID}'";
	$res_vendor_id = tep_db_query($query_vendor_id);
	$vendor_id = tep_db_result($res_vendor_id, 0, 'vendor_id');
}

if ( $vendor_id ) {
	if ( $_GET['msg'] ) {
		echo("<tr><td colspan=3><font color=#ff0000>{$_GET['msg']}</font></td></tr>");
	}
	if ( !$_GET['view'] ) {
	echo '				<tr class="dataTableHeadingRow">';
?>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PO; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right" width="50">Back in stock?&nbsp;</td>
              </tr>
<?php
		$query_pos = "select `po_id`, `item_status` from `".TABLE_VENDOR_PO."` where `vendor_id` = '{$vendor_id}' order by `po_id` desc";
		$pos_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $query_pos, $query_pos_numrows);
		$res_pos = tep_db_query($query_pos);
		while ($po = tep_db_fetch_array($res_pos)) {
			echo '              <tr  onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" class="dataTableRow">' . "\n";
?>
                <td class="dataTableContent"><?php echo $po['po_id'].' - '.$po['item_status']; ?></td>
                <td class="dataTableContent" align="right">
                	<?php echo '<a href="' . tep_href_link(FILENAME_VENDOR_PO, 'view=edit&page=' . $_GET['page'] . '&vID=' . $vendor_id . '&poID=' . urlencode($po['po_id'])).'">Edit</a> <a href="'.tep_href_link(FILENAME_VENDOR_PO, 'view=rec&page=' . $_GET['page'] . '&vID=' . $vendor_id . '&poID=' . urlencode($po['po_id'])).'">Receive</a>&nbsp;<a onclick="checkOrder(\''.$_GET[page].'\', \''.$vendor_id.'\', \''.$po[po_id].'\')" href="#">Delete</a>'; ?>&nbsp;</td><td align="center"><input type="checkbox" id="back_<?=$po['po_id']?>" name="back_<?=$po['po_id']?>" value="T" /></td>
              </tr>
<?php
		}
?>
<script>
	function checkOrder(val1, val2, val3){
	if (confirm('Do you really want to delete this order?')){
	 obj = document.getElementById('back_'+val3)
		if (obj.checked==true) sub = 1; else sub = 0;
		window.location.href='vendor_purchase_orders.php?action=delete&page='+escape(val1)+'&vID='+escape(val2)+'&poID='+escape(val3)+'&sub='+sub;
	}
	else	
	return false;
	}
</script>

              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $pos_split->display_count($query_pos_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PO); ?></td>
                    <td class="smallText" align="right"><?php echo $pos_split->display_links($query_pos_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], '&vID='.$_GET['vID']); ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_VENDOR_PO, 'page=' . $_GET['page'] . '&vID=' . $vendor_id . '&view=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?><?php echo '<a href="' . tep_href_link(FILENAME_VENDOR_PO, 'page=' . $_GET['page'] . '&vID=' . $vendor_id . '&view=receive') . '">Receive</a>'; ?></td>
              </tr>
<?php
	} else {
		$formOptions = array(
			'receive_type' => array(),
			'item_status' => array()
		);
		$formOptions = getFormOptions(TABLE_VENDOR_PO, $formOptions);

		$query_products = "select pv.products_id from (".TABLE_PRODUCTS_TO_VENDORS." pv) LEFT JOIN products p on (p.products_id=pv.products_id) where pv.vendors_id = '{$vendor_id}' and p.products_out_of_print !=0 order by pv.products_id";
//echo $query_products."<br/><hr>";
		$qua_vendor = tep_db_num_rows(tep_db_query($query_products));
		$res_products = tep_db_query($query_products);

		$productList = array();

if ($qua_vendor>0){
		while ( $product = tep_db_fetch_array($res_products) ) {
			$productList[] = '\''.$product['products_id'].'\'';
		}
}

if (count($productList)>0){
		$query_product_info = "select `p`.`products_id`, `p`.`products_warehouse_location`, `p`.`products_image`, `p`.`products_out_of_print`, `p`.`products_model`, `p`.`products_price`, `p`.`products_quantity`, `p`.`products_ordered`, `pd`.`products_name_prefix`, `pd`.`products_name`, `pd`.`products_name_suffix`, `v`.`vendors_item_number`, `v`.`vendors_item_cost` from `".TABLE_PRODUCTS."` as `p`, `".TABLE_PRODUCTS_DESCRIPTION."` as `pd`, `".TABLE_PRODUCTS_TO_VENDORS."` as `v` where `p`.`products_id` = `pd`.`products_id` and `p`.`products_id` = `v`.`products_id` and `p`.`products_id` in (".implode(',', $productList).") group by products_id order by p.products_model, pd.products_name";
//echo $query_product_info;
		$res_product_info = tep_db_query($query_product_info);
}

		if ( $_GET['error'] ) {
			echo("<tr><td colspan=3><font color=#ff0000>{$_GET['error']}</font></td></tr>");
		}
		switch ($_GET['view']) {
			case 'new':
?>
              <tr class="dataTableHeadingRow">
                <th class="dataTableHeadingContent">Order Quantity</th>
                <th class="dataTableHeadingContent">Price per item</th>
                <th class="dataTableHeadingContent">Warehouse Location</th>
                <th class="dataTableHeadingContent">Picture</th>
                <th class="dataTableHeadingContent">OOP</th>
                <th class="dataTableHeadingContent">Model</th>
                <th class="dataTableHeadingContent">Vendor Item #</th>
                <th class="dataTableHeadingContent">Vendor Item Cost</th>
                <th class="dataTableHeadingContent">Retail Price</th>
                <th class="dataTableHeadingContent">Quantity</th>
                <th class="dataTableHeadingContent">Ordered</th>
                <th class="dataTableHeadingContent">Name</th>
                <th class="dataTableHeadingContent">Item Status</th>
              </tr>
              <form action="<?php echo FILENAME_VENDOR_PO . '?vID=' . $vendor_id . '&action=insert'; ?>" method="POST">
<?php
if (count($productList)>0){
				if ( tep_db_num_rows($res_products) > 0 ) {
					while ( $info = tep_db_fetch_array($res_product_info) ) {
						$pID = $info['products_id'];
						echo '              <tr class="dataTableRowSelected" align="center">' . "\n";
						echo "								<td><input type=\"text\" name=\"qty_{$pID}\" size=\"4\"></td>" . "\n";
						echo "								<td><input type=\"text\" name=\"price_{$pID}\" size=\"4\"></td>" . "\n";
						echo "								<td>{$info['products_warehouse_location']}</td>" . "\n";
						echo "								<td><img src=\"/images/{$info['products_image']}\"></td>" . "\n";
						echo "								<td>{$info['products_out_of_print']}</td>" . "\n";
						echo "								<td>{$info['products_model']}</td>" . "\n";
						echo "								<td>{$info['vendors_item_number']}</td>" . "\n";
						printf('<td>$%.2f</td>' . "\n", $info['vendors_item_cost']);
						printf('<td>$%.2f</td>' . "\n", $info['products_price']);
						echo "								<td>{$info['products_quantity']}</td>" . "\n";
						echo "								<td>{$info['products_ordered']}</td>" . "\n";
						echo "								<td>{$info['products_name_prefix']} {$info['products_name']} {$info['products_name_suffix']}</td>" . "\n";
						echo "								<td>".tep_draw_pull_down_menu('status_'.$pID, $formOptions['item_status'])."</td>";
						echo '							</tr>' . "\n";
					}
?>
						<tr>
							<td align="left" colspan="3" class="smallText">Purchase Order ID:<br /><input name="poID" id="poID" type="text" size="25"></td>
							<td align="left" colspan="5" class="smallText">Purchase Order Date (leave 0000-00-00 for current date):<br /><input name="date" type="text" size="12" value="0000-00-00"></td>
							<td align="left" colspan="1" class="smallText">Order Status:<br /><?php echo tep_draw_pull_down_menu('status', $formOptions['item_status']); ?></td>
							<td align="left" colspan="1" class="smallText">Add to Inventory?<br /><input type="checkbox" name="add_ok" value="1" /></td>
							<td align="left" colspan="1" class="smallText">Send Email?<br /><input type="checkbox" name="send_ok" value="1" /></td>
<?
$vend = tep_db_fetch_array(tep_db_query("select po_email_address from vendors where vendors_id=".$vendor_id));
?>
							<td align="left" colspan="2" class="smallText">Vendor Email:<br /><input type="text" value="<?=$vend[po_email_address]?>" readonly /></td></tr>
							<tr><td colspan="6" align="left" colspan="1" class="smallText">Email comments:<br /><textarea name="comments" style="width:500px; height:100px;"></textarea></td>
            	<td align="right" colspan="1" class="smallText"><?php echo tep_image_submit('button_save.gif', IMAGE_SAVE, 'onclick="return CheckField()"'); ?></td></tr>
            </tr>
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
          </form>
<?php
				}
		}
else echo "<tr><td colspan='13' align='center'><b>No products found</b></td></tr>";
			break;
			// Edit an order
			case 'edit':
?>
              <tr class="dataTableHeadingRow">
                <th class="dataTableHeadingContent">Order Quantity</th>
                <th class="dataTableHeadingContent">Price per item</th>
                <th class="dataTableHeadingContent">Warehouse Location</th>
                <th class="dataTableHeadingContent">Picture</th>
                <th class="dataTableHeadingContent">OOP</th>
                <th class="dataTableHeadingContent">Model</th>
                <th class="dataTableHeadingContent">Vendor Item #</th>
                <th class="dataTableHeadingContent">Vendor Item Cost</th>
                <th class="dataTableHeadingContent">Retail Price</th>
                <th class="dataTableHeadingContent">Quantity</th>
                <th class="dataTableHeadingContent">Ordered</th>
                <th width="40%" class="dataTableHeadingContent">Name</th>
                <th class="dataTableHeadingContent">Item Status</th>
                <th class="dataTableHeadingContent">Delete?</th>
                <th class="dataTableHeadingContent">Back in Stock?</th>
              </tr>
              <form name="list" action="<?php echo FILENAME_VENDOR_PO . '?vID=' . $vendor_id . '&action=save'; ?>" method="POST">
			<input type="hidden" name="act" value="" />
			<input type="hidden" name="view" value="<?=$_GET[view]?>" />
			<input type="hidden" name="page" value="<?=$_GET[page]?>" />
<?php
				$query_po = "select `date`, `item_status` from `".TABLE_VENDOR_PO."` where `po_id` = '{$poID}'";
				$res_po   = tep_db_query($query_po);
				if ( tep_db_num_rows($res_po) > 0 ) {
					$query_order_items = "select `product_id`, `qty`, `price`, `qty_received`, `receive_type`, `receive_date`, `item_status` from `".TABLE_VENDOR_PO_DETAILS."` where `po_id` = '{$poID}'";
					$res_order_items   = tep_db_query($query_order_items);
					$orderItems = array();
					while ( $orderItem = tep_db_fetch_array($res_order_items) ) {
						$orderItems[$orderItem['product_id']] = $orderItem;
					}
					$poInfo = tep_db_fetch_array($res_po);
					//$sql_query = "select * from vendor_purchase_order_details where po_id='{$poID}'";
					$sql_query = "select p.products_id, p.products_warehouse_location, p.products_image, p.products_out_of_print, p.products_model, p.products_price, p.products_quantity, p.products_ordered, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, v.* from `".TABLE_PRODUCTS."` p, `".TABLE_PRODUCTS_DESCRIPTION."` pd,  `vendor_purchase_order_details` v where p.products_id = pd.products_id and p.products_id = v.product_id and v.po_id='".$poID."' group by products_id order by p.products_model, pd.products_name";
					$res_product_info = tep_db_query($sql_query);
					while ( $info = tep_db_fetch_array($res_product_info) ) {
						$pID = $info['products_id'];
						if ( !$orderItems[$pID] ) {
							echo "								<input type=\"hidden\" name=\"new_{$pID}\" value=\"1\">\n";
						}
						echo '              <tr class="dataTableRowSelected" align="center">' . "\n";
						echo "								<td><input type=\"text\" name=\"qty_{$pID}\" size=\"5\" value=\"{$orderItems[$pID]['qty']}\"></td>" . "\n";
						echo "								<td><input type=\"text\" name=\"price_{$pID}\" size=\"6\" value=\"{$orderItems[$pID]['price']}\"></td>" . "\n";
						echo "								<td>{$info['products_warehouse_location']}</td>" . "\n";
						echo "								<td><img src=\"/images/{$info['products_image']}\"></td>" . "\n";
						echo "								<td>{$info['products_out_of_print']}</td>" . "\n";
						echo "								<td>{$info['products_model']}</td>" . "\n";
						echo "								<td>{$info['vendors_item_number']}</td>" . "\n";
						printf('								<td>$%.2f</td>' . "\n", $info['vendors_item_cost']);
						printf('								<td>$%.2f</td>' . "\n", $info['products_price']);
						echo "								<td>{$info['products_quantity']}</td>" . "\n";
						echo "								<td>{$info['products_ordered']}</td>" . "\n";
						echo "								<td>{$info['products_name_prefix']} {$info['products_name']} {$info['products_name_suffix']}</td>" . "\n";
						echo "								<td>".tep_draw_pull_down_menu('status_'.$pID, $formOptions['item_status'], $orderItems[$pID]['item_status'])."</td>" . "\n";
						echo "								<td><input type='checkbox' name='delete[]' value='".$pID."' /></td>";
						echo "								<td><input type='checkbox' name='back[]' value='".$pID."' /></td>";
						echo '							</tr>' . "\n";
					}
?>
<tr><td colspan="15" align="right"><input type="button" value="Delete" onClick="document.list.act.value='1'; document.list.submit();" /></td></tr>
						<tr>
							<td align="left" colspan="3" class="smallText">Purchase Order ID:<br /><input type="text" name="poID" id="poID" value="<?php echo urldecode($poID); ?>" /><input type="hidden" name="poIDold" value="<?php echo $poID; ?>"></td>
							<td align="left" colspan="5" class="smallText">Purchase Order Date (leave 0000-00-00 for current date):<br /><input name="date" type="text" size="12" value="<?php echo $poInfo['date'];?>"></td>
							<td align="left" colspan="1" class="smallText">Order Status:<br /><?php echo tep_draw_pull_down_menu('status', $formOptions['item_status'], $poInfo['item_status']); ?></td>
							<td align="left" colspan="1" class="smallText">Add to Inventory?<br /><input type="checkbox" name="add_ok" value="1" /></td>
							<td align="left" colspan="1" class="smallText">Send Email?<br /><input type="checkbox" name="send_ok" value="1" /></td>
<?
$vend = tep_db_fetch_array(tep_db_query("select po_email_address from vendors where vendors_id=".$vendor_id));
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
<form action="vendor_purchase_orders.php?action=add_upc" method="post" name="upcform">
	<input type="hidden" name="page" value="<?=$_GET[page]?>" />
	<input type="hidden" name="poID" value="<?=$_GET[poID]?>" />
	<input type="hidden" name="vID" value="<?=$vendor_id?>" />
	<input type="hidden" name="view" value="edit" />
<tr>
	<td class="smallText">Insert UPC number: </td>
	<td colspan="5" class="smallText"><input name="upc" type="text" value="" style="width:250px;"/>&nbsp;<input type="submit" value="&nbsp;Add product&nbsp;" /></td>
</tr>
</form>
<?php
				}
			break;
			// Receive an existing order
			case 'rec':
?>
              <tr class="dataTableHeadingRow">
                <th class="dataTableHeadingContent">Order Quantity</th>
                <th class="dataTableHeadingContent">Price<br/>per item</th>
                <th class="dataTableHeadingContent">Warehouse<br/>Location</th>
                <th class="dataTableHeadingContent">Picture</th>
                <th class="dataTableHeadingContent">OOP</th>
                <th class="dataTableHeadingContent">Model</th>
                <th class="dataTableHeadingContent">Vendor Item #</th>
                <th class="dataTableHeadingContent">Received so far</th>
                <th class="dataTableHeadingContent">Received new</th>
                <th class="dataTableHeadingContent">Name</th>
                <th class="dataTableHeadingContent">Rec. Type</th>
                <th class="dataTableHeadingContent">Rec. Date<br />(leave 0000-00-00 for current date)</th>
                <th class="dataTableHeadingContent">Item Status</th>
                <th class="dataTableHeadingContent">Delete?</th>
                <th class="dataTableHeadingContent">Back in Stock?</th>
              </tr>
              <form name="list" action="<?php echo FILENAME_VENDOR_PO . '?vID=' . $vendor_id . '&action=rec'; ?>" method="POST">
			<input type="hidden" name="act" value="" />
			<input type="hidden" name="view" value="<?=$_GET[view]?>" />
			<input type="hidden" name="page" value="<?=$_GET[page]?>" />
<?php

				$query_po = "select `date`, `receive_type`, `receive_date`, `item_status` from `".TABLE_VENDOR_PO."` where `po_id` = '{$poID}'";
				$res_po   = tep_db_query($query_po);
				if ( tep_db_num_rows($res_po) > 0 ) {
					$query_order_items = "select `product_id`, `qty`, `price`, `qty_received`, `receive_type`, `receive_date`, `item_status` from `".TABLE_VENDOR_PO_DETAILS."` where `po_id` = '{$poID}'";
					$res_order_items   = tep_db_query($query_order_items);
					$orderItems = array();
					while ( $orderItem = tep_db_fetch_array($res_order_items) ) {
						$orderItems[$orderItem['product_id']] = $orderItem;
					}

					$poInfo = tep_db_fetch_array($res_po);
					$sql_query = "select p.products_id, p.products_warehouse_location, p.products_image, p.products_out_of_print, p.products_model, p.products_price, p.products_quantity, p.products_ordered, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, v.* from `".TABLE_PRODUCTS."` p, `".TABLE_PRODUCTS_DESCRIPTION."` pd, `vendor_purchase_order_details` v where p.products_id = pd.products_id and p.products_id = v.product_id and v.po_id='".$poID."' group by products_id order by p.products_model, pd.products_name";
					$res_product_info = tep_db_query($sql_query);
					while ( $info = tep_db_fetch_array($res_product_info) ) {
						$pID = $info['products_id'];
						if ( !$orderItems[$pID] ) {
							continue;
						}
						echo '              <tr class="dataTableRowSelected" align="center">' . "\n";
						echo "								<td>{$orderItems[$pID]['qty']}</td>" . "\n";
						echo "								<td>{$orderItems[$pID]['price']}</td>" . "\n";
						echo "								<td>{$info['products_warehouse_location']}</td>" . "\n";
						echo "								<td><img src=\"/images/{$info['products_image']}\"></td>" . "\n";
						echo "								<td>{$info['products_out_of_print']}</td>" . "\n";
						echo "								<td>{$info['products_model']}</td>" . "\n";
						echo "								<td>{$info['vendors_item_number']}</td>" . "\n";
						echo "								<td align=center>{$orderItems[$pID]['qty_received']}</td>" . "\n";
						echo "								<td><input type=\"text\" name=\"rec_{$pID}\" size=\"5\"></td>" . "\n";
						echo "								<td>{$info['products_name_prefix']} {$info['products_name']} {$info['products_name_suffix']}</td>" . "\n";
						echo "								<td>".tep_draw_pull_down_menu('recType_'.$pID, $formOptions['receive_type'], $orderItems[$pID]['receive_type'])."</td>";
						echo "								<td><input type=\"text\" size=10 name=\"date_{$pID}\" value=\"{$orderItems[$pID]['receive_date']}\"></td>" . "\n";
						echo "								<td>".tep_draw_pull_down_menu('status_'.$pID, $formOptions['item_status'], $orderItems[$pID]['item_status'])."</td>";
						echo "								<td><input type='checkbox' name='delete[]' value='".$pID."' /></td>";
						echo "								<td><input type='checkbox' name='back[]' value='".$pID."' /></td>";
						echo '							</tr>' . "\n";
					}
?>
<tr><td colspan="15" align="right"><input type="button" value="Delete" onClick="document.list.act.value='1'; document.list.submit();" /></td></tr>
						<tr>
							<td align="left" colspan="3" class="smallText">Purchase Order ID:<br /><input type="text" id="poID" name="poID" value="<?php echo $poID; ?>" /><input type="hidden" name="poIDold" value="<?php echo $poID; ?>"></td>
							<td align="left" colspan="6" class="smallText">Receive Date (leave 0000-00-00 for current date):<br /><input name="date" type="text" size="12" value="<?php echo $poInfo['receive_date'];?>"></td>
							<td align="right" colspan="1" class="smallText">Receive Type:<br /><?php echo tep_draw_pull_down_menu('type', $formOptions['receive_type'], $poInfo['receive_type']); ?></td>
							<td align="right" colspan="1" class="smallText">Order Status:<br /><?php echo tep_draw_pull_down_menu('status', $formOptions['item_status'], $poInfo['item_status']); ?></td>
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
<?
$vend = tep_db_fetch_array(tep_db_query("select po_email_address from vendors where vendors_id=".$vendor_id));
?>
							<td align="left" colspan="1" class="smallText"><input type="checkbox" name="apply_all" value="1">Apply to all items<br><input type="checkbox" name="add_ok" value="1" /> Add to inventory?<br><input type="checkbox" name="send_ok" value="1" /> Send Email?<br>Vendor Email:<br /><input type="text" value="<?=$vend[vendors_email]?>" readonly /></td></tr>
            	<tr>
		<td colspan="6" align="left" colspan="1" class="smallText">Email comments:<br /><textarea name="comments" style="width:500px; height:100px;"></textarea></td>
		<td align="right" colspan="2" class="smallText"><?php echo tep_image_submit('button_save.gif', IMAGE_SAVE, 'onclick="return CheckField()"'); ?></td></tr>
            </tr>
          </form>
<tr><td><br/></td></tr>
<form action="vendor_purchase_orders.php?action=add_upc" method="post" name="upcform">
	<input type="hidden" name="page" value="<?=$_GET[page]?>" />
	<input type="hidden" name="poID" value="<?=$_GET[poID]?>" />
	<input type="hidden" name="vID" value="<?=$vendor_id?>" />
	<input type="hidden" name="view" value="rec" />
<tr>
	<td class="smallText">Insert UPC number: </td>
	<td colspan="5" class="smallText"><input name="upc" type="text" value="" style="width:250px;"/>&nbsp;<input type="submit" value="&nbsp;Add product&nbsp;" /></td>
</tr>
</form>

<?php
				}
			break;
			// Receive/Edit an order
			case 'receive':
?>
              <tr class="dataTableHeadingRow">
                <th class="dataTableHeadingContent">Warehouse Location</th>
                <th class="dataTableHeadingContent">Picture</th>
                <th class="dataTableHeadingContent">OOP</th>
                <th class="dataTableHeadingContent">Model</th>
                <th class="dataTableHeadingContent">Vendor Item #</th>
                <th class="dataTableHeadingContent">Quantity</th>
                <th class="dataTableHeadingContent">Received new</th>
                <th class="dataTableHeadingContent">Name</th>
                <th class="dataTableHeadingContent">Delete?</th>
                <th class="dataTableHeadingContent">Back in Stock?</th>
              </tr>
              <form name="list" action="<?php echo FILENAME_VENDOR_PO . '?vID=' . $vendor_id . '&action=rec'; ?>" method="POST">
			<input type="hidden" name="act" value="" />
			<input type="hidden" name="view" value="<?=$_GET[view]?>" />
			<input type="hidden" name="page" value="<?=$_GET[page]?>" />

<?php
					while ( $info = tep_db_fetch_array($res_product_info) ) {
						$pID = $info['products_id'];
						echo '              <tr class="dataTableRowSelected" align="center">' . "\n";
						echo "								<td>{$info['products_warehouse_location']}</td>" . "\n";
						echo "								<td><img src=\"/images/{$info['products_image']}\"></td>" . "\n";
						echo "								<td>{$info['products_out_of_print']}</td>" . "\n";
						echo "								<td>{$info['products_model']}</td>" . "\n";
						echo "								<td>{$info['vendors_item_number']}</td>" . "\n";
						echo "								<td align=center>{$info['products_quantity']}</td>" . "\n";
						echo "								<td><input type=\"text\" name=\"rec_{$pID}\" size=\"6\"></td>" . "\n";
						echo "								<td>{$info['products_name_prefix']} {$info['products_name']} {$info['products_name_suffix']}</td>" . "\n";
						echo "								<td><input type='checkbox' name='delete[]' value='".$pID."' /></td>";
						echo "								<td><input type='checkbox' name='back[]' value='".$pID."' /></td>";
						echo '							</tr>' . "\n";
					}
?>
						<tr>
            	<td align="right" colspan="8" class="smallText"><?php echo tep_image_submit('button_save.gif', IMAGE_SAVE); ?>&nbsp;<input type="button" value="Delete" onClick="document.list.act.value='1'; document.list.submit();" /></td>
            </tr>
          </form>
<?php
			break;
		}
	}

/*
if ( $payment_id && !$vendor_id ) {
	$query_vendor_id = "select `vendor_id` from `".TABLE_VENDOR_PAYMENTS."` where `id` = '{$payment_id}'";
	$res_vendor_id = tep_db_query($query_vendor_id);
	$vendor_id = tep_db_result($res_vendor_id, 0, 'vendor_id');
}
if ( $vendor_id ) {
?>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAYMENTS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
	$query_payments = "select `id`, `vendor_id`, `po_id`, `date`, `method`, `type`, `status` from `".TABLE_VENDOR_PAYMENTS."` where `vendor_id` = '{$vendor_id}' order by `id` desc";
	$payments_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $query_payments, $query_payments_numrows);
	$res_payments = tep_db_query($query_payments);
	while ($payment = tep_db_fetch_array($res_payments)) {
		if ( $payment['id'] == $payment_id ) {
			$payment_info = $payment;
			echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID=' . $vendor_id . '&pID=' . $payment['id'] . '&action=edit') . '\'">' . "\n";
		} else {
			echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID=' . $vendor_id . '&pID=' . $payment['id']) . '\'">' . "\n";
		}
?>
                <td class="dataTableContent"><?php echo $payment['id']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( $payment['id'] == $payment_id ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '111<a href="' . tep_href_link(FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID=' . $vendors['vendors_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
	}*/
?>
            </table></td>
<?php
/*	$formOptions = array(
		'method' => array(),
		'type' => array(),
		'status' => array()
	);
	foreach ($formOptions as $key => $value) {
		$query_options = "SHOW COLUMNS FROM `".TABLE_VENDOR_PAYMENTS."` LIKE '{$key}'";
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

  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_PAYMENT . '</b>');

      $contents = array('form' => tep_draw_form('vendors', FILENAME_VENDOR_PAYMENTS, 'action=insert' . '&vID=' . $vendor_id, 'post' ));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_ID . '<br>' . tep_draw_input_field('pID'));
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_PO_ID . '<br>' . tep_draw_input_field('poID'));
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_DATE . '<br>' . tep_draw_input_field('date'));
			$contents[] = array('text' => '<br>' . TEXT_PAYMENT_METHOD . '<br>' . tep_draw_pull_down_menu('method', $formOptions['method']));
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_TYPE . '<br>' . tep_draw_pull_down_menu('type', $formOptions['type']));
		  $contents[] = array('text' => '<br>' . TEXT_PAYMENT_STATUS . '<br>' . tep_draw_pull_down_menu('status', $formOptions['status']));

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_VENDORS, 'page=' . $_GET['page'] . '&vID=' . $vendor_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_PAYMENT . '</b>');

      $contents = array('form' => tep_draw_form('vendors', FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID=' . $vendor_id . '&action=save', 'post'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_ID . '<br>' . tep_draw_input_field('pID', $payment_id));
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_PO_ID . '<br>' . tep_draw_input_field('poID', $payment_info['po_id']));
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_DATE . '<br>' . tep_draw_input_field('date', $payment_info['date']));
			$contents[] = array('text' => '<br>' . TEXT_PAYMENT_METHOD . '<br>' . tep_draw_pull_down_menu('method', $formOptions['method'], $payment_info['method']));
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_TYPE . '<br>' . tep_draw_pull_down_menu('type', $formOptions['type'], $payment_info['type']));
		  $contents[] = array('text' => '<br>' . TEXT_PAYMENT_STATUS . '<br>' . tep_draw_pull_down_menu('status', $formOptions['status'], $payment_info['status']));

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_VENDORS, 'page=' . $_GET['page'] . '&vID=' . $mInfo->vendors_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }*/
}
?>
          </tr>
<tr><td align="center"><br/><br/><input type="button" value="Back to List" onClick="window.location.href='vendor_purchase_orders.php?vID=<?=$_GET[vID]?>'" /></td></tr>
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