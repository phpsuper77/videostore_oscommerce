<?php
/*
  $Id: orders.php,v 1.112 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'update_order':
        $order_updated = false;
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
        $status = tep_db_prepare_input($HTTP_POST_VARS['status']);
        $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);
        $shipcompany = tep_db_prepare_input($HTTP_POST_VARS['shipcompany']);
        $tracking = tep_db_prepare_input($HTTP_POST_VARS['tracking']);


if (is_array($_POST['update_products'])){
	foreach($_POST['update_products'] as $orders_products_id => $products_details)	{

				$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS . " SET
					date_shipped = '".$products_details["shipped"]."',
					date_shipped_checkbox = '".$products_details["date_shipped_checkbox"]."',
					returned_reason_checkbox = '".$products_details["returned_reason_checkbox"]."',
					returned_reason = '".addslashes($products_details["reason"])."'
					WHERE orders_id = '" . (int)$oID . "'
					AND orders_products_id = '$orders_products_id';";
				tep_db_query($Query);


 if (tep_not_null($tracking)) {
          tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set orders_products_ship_carrier= '" . $HTTP_POST_VARS['shipcompany'] . "', orders_products_tracking_number=  '" . $HTTP_POST_VARS['tracking'] . "' where orders_id = '" . (int)$oID . "'");
                                       }

/* ========================== Back to Stock ====================== */
		if (intval($products_details["back"])>0){
			$currency_info = tep_db_fetch_array(tep_db_query("select currency, currency_value from orders where orders_id='".$oID."'"));

			$products_price = tep_db_fetch_array(tep_db_query("select final_price as price from orders_products where orders_products_id='".$products_details[back]."'"));
			$back_subtotal = tep_db_fetch_array(tep_db_query("select value as subtotal from orders_total where class='ot_subtotal' and orders_id='".$oID."'"));
			$back_total = tep_db_fetch_array(tep_db_query("select value as total from orders_total where class='ot_total' and orders_id='".$oID."'"));			

$back_subtotal_value = $back_subtotal[subtotal] - $products_price[price];
$back_total_value = $back_total[total] - $products_price[price];

$text = $currencies->format($back_subtotal_value,true,$currency_info[currency],$currency_info[currency_value]);
			tep_db_query("update orders_total set value='".$back_subtotal_value."', text='<b>".$text."</b>' where class='ot_subtotal' and orders_id='".$oID."'");			
//echo "update orders_total set value='".$back_subtotal_value."', text='<b>".$text."</b>' where class='ot_subtotal' and orders_id='".$oID."'<br/>";

$text = $currencies->format($back_total_value,true,$currency_info[currency], $currency_info[currency_value]);
			tep_db_query("update orders_total set value='".$back_total_value."', text='<b>".$text."</b>' where class='ot_total' and orders_id='".$oID."'");			
//echo "update orders_total set value='".$back_total_value."', text='<b>".$text."</b>' where class='ot_total' and orders_id='".$oID."'<br/>";


    			tep_db_query("update products set products_ordered = products_ordered - " . $products_details[qty] . ", products_quantity = products_quantity + " . $products_details[qty]." where products_id = '" . $products_details[back] . "'");
//echo "update products set products_ordered = products_ordered - " . $products_details[qty] . ", products_quantity = products_quantity + " . $products_details[qty]." where products_id = '" . $products_details[back] . "'<br/>";
			tep_db_query("update orders_products set products_price = '0.00', final_price = '0.00', products_tax='0.00', products_item_cost='0.00', back=1 where orders_products_id = '" . $products_details[back] . "'");
//echo "update orders_products set products_price = '0.00', final_price = '0.00', products_tax='0.00', products_item_cost='0.00', back=1 where orders_products_id = '" . $products_details[back] . "'<br/>";
		}
/* ========================== End of Back to Stock ====================== */

	}
$order_updated = true;
}


        $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased, ipaddy, ipisp from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        $check_status = tep_db_fetch_array($check_status_query);

 if (tep_not_null($tracking)) {
          tep_db_query("update " . TABLE_ORDERS. " set orders_ship_carrier= '" . $HTTP_POST_VARS['shipcompany'] . "', orders_tracking_number=  '" . $HTTP_POST_VARS['tracking'] . "', last_modified = now()  where orders_id = '" . (int)$oID . "'");
                                       }

        if ( ($check_status['orders_status'] != $status) || tep_not_null($comments)) {
          tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . (int)$oID . "'");


          $customer_notified = '0';
          if (isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on')) {
            $notify_comments = '';
            if (isset($HTTP_POST_VARS['notify_comments']) && ($HTTP_POST_VARS['notify_comments'] == 'on')) {
              $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
            }
/*
            $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);

            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

            $customer_notified = '1';
          } */
$pwa_check_query= tep_db_query("select purchased_without_account, payment_method, purchase_order_number from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
$pwa_check= tep_db_fetch_array($pwa_check_query);
 if ($pwa_check['purchased_without_account'] != '1'){

           $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
 } else {
   $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
 }
	
if (trim($pwa_check['payment_method']) == 'Amazon Marketplace' || trim($pwa_check['payment_method']) == 'Amazon UK Marketplace')
	   $subject = $pwa_check['payment_method'] . ' Order '.$pwa_check[purchase_order_number].' Update';
	else
	   $subject = EMAIL_TEXT_SUBJECT;

           tep_mail($check_status['customers_name'], $check_status['customers_email_address'], $subject, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

           $customer_notified = '1';
         }
/*
          tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");
*/

$ShipperTracker = " ";

if (tep_not_null($tracking)) 
         $ShipperTracker =  " - " . $shipcompany . " - " .  $tracking;
                                        

          tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . $ShipperTracker . " - " . $_SERVER['REMOTE_ADDR'] . "')");

          $order_updated = true;
        }

        if ($order_updated == true) {
         $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
        } else {
          $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
        }

        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
        break;
      case 'deleteconfirm':
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

        tep_remove_order($oID, $HTTP_POST_VARS['restock']);

        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action'))));
        break;
    }
  }

  if (($action == 'edit') && isset($HTTP_GET_VARS['oID'])) {
    $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }

  include(DIR_WS_CLASSES . 'order.php');
?>
<script>
	function printLabel(val){
	window.open('print_label.php?oID='+val);
	}

	function generatePDF(id){
		window.open('generate_invoice.php?oID='+id);
	}

</script>
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
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
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
<?php
  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);

    ######################################
    ##INCLUDED FOR AFFILIATE DISPLAY
    $aff_sql = "select aa.affiliate_id,aa.affiliate_firstname, aa.affiliate_lastname, aa.affiliate_company, asa.affiliate_salesman from affiliate_affiliate aa, affiliate_sales asa where aa.affiliate_id = asa.affiliate_id and asa.affiliate_orders_id = '". $oID ."'";
	$aff_rs = tep_db_query($aff_sql);
	$aff_count = tep_db_num_rows($aff_rs);
######################################
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right">
<?php 
/*==================Define shipping method=============================*/
        $rqShipping = tep_db_query("SELECT title FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = " . (int)$HTTP_GET_VARS['oID']. " AND class = 'ot_shipping'");
        $shipping = tep_db_fetch_array($rqShipping);
if (strpos($shipping[title], 'USPS')!==false) 
	$shipp = "USPS";        
elseif (strpos($shipping[title], 'UPS')!==false) 
	$shipp = "UPS";        
else
	$shipp = "FEDEX";
/*==================Define shipping method=============================*/
if ($shipp=="FEDEX"){
    // begin fedex label mod
    // determine whether this is on the test or production server
    $value_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FEDEX1_SERVER'");
    $value = tep_db_fetch_array($value_query);
    $fedex_gateway = $value['configuration_value'];

    $orders_query_raw = "select orders_id, fedex_tracking, orders_status from " . TABLE_ORDERS . " where orders_id = '" . (int)$HTTP_GET_VARS['oID'] . "'";
    $orders_query = tep_db_query($orders_query_raw);
    if (tep_db_num_rows($orders_query)) {
        $fedex_orders = tep_db_fetch_array($orders_query);
    }

    // check for a fedex tracking number in the order record
    // if yes tracking number, show "fedex label," "track" and "cancel" options
    $fedex_tracking = $fedex_orders['fedex_tracking'];

    if ($fedex_tracking) {
        // display the label
        echo '<a href="fedex_popup.php?num=' . $fedex_tracking . '&oID=' . $HTTP_GET_VARS['oID'] . '">' . tep_image_button('button_fedex_label.gif', IMAGE_ORDERS_FEDEX_LABEL) . '</a>&nbsp;';
        // cancel the request
        echo '<a href="' . tep_href_link(FILENAME_SHIP_FEDEX, 'oID=' . $HTTP_GET_VARS['oID'] . '&num=' . $fedex_tracking . '&action=cancel&fedex_gateway=' . $fedex_gateway) . '" onClick="return(window.confirm(\'Cancel shipment of order number ' . $order->orders_id . '?\'));">' . tep_image_button('button_cancel_shipment.gif', IMAGE_ORDERS_CANCEL_SHIPMENT) . '</a>&nbsp;';
    }
    elseif ($fedex_orders['orders_status'] != 3) {          // if the order has not been manually marked "delivered,"
	// display the "ship" button
	echo '<a href="' . tep_href_link(FILENAME_SHIP_FEDEX, 'oID=' . $HTTP_GET_VARS['oID'] . '&action=new&status=3') . '">' . tep_image_button('button_ship.gif', IMAGE_ORDERS_SHIP) . '</a>&nbsp;';
    }
    // end fedex label mod
}
else
	echo '<a href="' . tep_href_link(FILENAME_ORDERS_USPS_SHIP, 'oID=' . $HTTP_GET_VARS['oID']) . '" TARGET="_blank">' . tep_image_button('button_labelshipping.gif', IMAGE_ORDERS_USPS_SHIP) . '</a>&nbsp;';
//echo '<input type="button" value="Print Label" onclick="javascript:printLabel('.$HTTP_GET_VARS['oID'].')" />&nbsp;<a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <input type="button" value="PDF invoice" onclick="javascript:generatePDF('.$HTTP_GET_VARS['oID'].')" /> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> '.$usps; 


echo '<input type="button" value="Print Label" onclick="javascript:printLabel('.$HTTP_GET_VARS['oID'].')" />&nbsp;<a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> '.$usps; 

?>
</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
                <td class="main"><?php echo strtoupper(tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>')); ?></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
                <td class="main"><?php echo strtoupper(tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>')); ?></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
                <td class="main"><?php echo strtoupper(tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>')); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
	<tr><td colspan="9"><table border="0">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
                <td class="main"><?php echo $order->customer['telephone']; ?></td>
		    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <!--/tr>
              <tr-->
                <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
		    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <!--/tr>
<tr-->
		<td class="main"><b><?php echo Referral; ?></b></td>
		<td class="main"><a href="<?php echo $order->info['customers_referer_url'];  ?>" target="_blank"><?php echo $order->info['customers_referer_url']; ?></a></td>
		    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
               </tr>
		<tr>
		<td colspan="9">
<table border="0">
		<tr>
            	    <td class="main"><b>Order Number:</b></td>
 		    <td class="main"><?php echo $_GET['oID']; ?></td>
		    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<!--/tr>
		<tr-->
        	    <td class="main"><b>Date Purchased:</b></td>
	            <td class="main"><?php echo $order->info['date_purchased']; ?></td>
		    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<!--/tr>
		<tr-->
            	    <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
	            <td class="main"><?php echo $order->info['payment_method']; ?></td>
		    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
</table>	
		</td>
		</tr>

              <!--ORDER IP RECORDER-->
               <tr>
                <td class="main">&nbsp;</td>
               </tr>
		<tr><td colspan="9">
		<table width="100%">
               <tr>
                <td class="main"><b><?php echo ENTRY_IPADDRESS; ?></b></td>
                <td class="main"><?php echo $order->customer['ipaddy']; ?></td>
               <!--/tr>
               <tr-->
                <td class="main"><b>Location: </b></td>
                <td class="main">
<?php 
    $country_query  = "SELECT countrySHORT, countryLONG FROM ipcountry WHERE ipFROM<=inet_aton('".$order->customer[ipaddy]."') AND ipTO>=inet_aton('".$order->customer[ipaddy]."')";
    $country_exec = tep_db_query($country_query);
    $ccode_array = tep_db_fetch_array($country_exec);
echo $ccode_array['countryLONG'];
?></td>
               <!--/tr>

				<tr-->
				<td class="main"><b><?php echo ENTRY_IPISP; ?></b></td>
				<td class="main"><?php echo $order->customer['ipisp']; ?></td>
				</tr>
<tr>
	<td class="main"><b>Order Type: </b></td>
<script>
	function winOpen(){
		window.open ('wholesaleInfo.php?customer_id=<?=$order->info[customers_id]?>', "WholesaleInfo","location=0,status=0,scrollbars=0, width=500,height=130");	
	}
</script>
	<td class="main">
<?
	if (intval($order->info['iswholesale']) == 1) echo "<font color=red><b>WHOLESALE</b></font> (<a href='#' onclick='winOpen(); return false;'>info</a>)"; else echo "REGULAR";
?>
	</td>
<td class="main"># Prev Orders:</td><td><?php $count = tep_db_fetch_array(tep_db_query("select count(*) as cnt from orders where customers_id='".$order->info['customers_id']."'"));?><a href="orders.php?criteria=8&searchstring=<?=$order->info['customers_id']?>&action=edit"><?=$count[cnt].'</a>'?></td>
<td class="main">$ Prev Orders:</td><td><?php $summ = tep_db_fetch_array(tep_db_query("SELECT sum(ot.value) as cnt FROM orders o LEFT  JOIN orders_total ot ON ot.orders_id = o.orders_id WHERE o.customers_id =  '".$order->info['customers_id']."' AND ot.class =  'ot_total'"));?> <a href="orders.php?criteria=8&searchstring=<?=$order->info['customers_id']?>&action=edit"><?=$currencies->format($summ[cnt], true, $order->info['currency'], $order->info['currency_value']).'</a>'?></td>
</tr>
               <!--ORDER IP RECORDER-->
</table></td></tr>
</table></td></tr>
      		 <!-- Affiliate Display -->
	  	  <?php
	  	  	if ($aff_count > 0)
	  	  	{
	  		$aff_fetch = tep_db_fetch_array($aff_rs);
	
		  		if ($aff_fetch['affiliate_id'] <> $aff_fetch['affiliate_salesman'])
		  		{
		  			$sel_aff_name = tep_db_query("select affiliate_firstname, affiliate_lastname from affiliate_affiliate where affiliate_id = '".$aff_fetch['affiliate_salesman']."'");
	  	  			$rs_aff_name = tep_db_fetch_array($sel_aff_name);
	  	  			$sales_firstname = $rs_aff_name['affiliate_firstname'];
					$sales_lastname = $rs_aff_name['affiliate_lastname'];
	  	  		}
  	  	?>
	 	<tr>
	 	<td>
	 	<Table width=80% cellspacing=0 cellpadding=2>
	 	  <tr>
			<td class="main" valign=top><b><?php echo "Affiliate Root:"; ?></b> &nbsp;&nbsp;&nbsp;<?php if (isset($sales_lastname) || isset($sales_firstname)) echo $sales_firstname." ".$sales_lastname; else echo "-"; ?></td>
			<td class="main" valign=top><b><?php echo "Firstname:"; ?></b>&nbsp;&nbsp;&nbsp;<?php echo $aff_fetch['affiliate_firstname'];  ?></td>
			<td class="main" valign=top><b><?php echo "Lastname:"; ?></b>&nbsp;&nbsp;&nbsp;<?php echo $aff_fetch['affiliate_lastname'];  ?></td>
			  </tr>
		  </table>
            	</td>
            </tr>
	  <?
	  	}
	  ?>
	  <!----------------------->

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
<?php
//    if (tep_not_null($order->info['cc_type']) || tep_not_null($order->info['cc_owner']) || tep_not_null($order->info['cc_number'])) {
    if (tep_not_null($order->info['cc_owner']) || tep_not_null($order->info['cc_number'])) {

?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
            <td class="main"><?php echo $order->info['cc_type']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
            <td class="main"><?php echo $order->info['cc_owner']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
            <td class="main"><?php
// credit_card_show_last_4 start
								// Only want to show the last 4 digits of the credit card number.
								// Other digits will be represented with an 'X'.
								$len = (strlen($order->info['cc_number']) - 4);
echo substr($order->info['cc_number'], 0, 4) . str_repeat('X', 12) . substr($order->info['cc_number'], 12, 16);"===";
/*
                $masked_cc_number = '';
                for($i = 0 ; $i < $len ; $i++)
								{
                  $masked_cc_number .= 'X';
								} // for
								$masked_cc_number .= substr($order->info['cc_number'], $len - 1, 4);
								echo $masked_cc_number
*/
// credit_card_show_last_4 end
								?>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
            <td class="main"><?php echo $order->info['cc_expires']; ?></td>
          </tr>
<?php
// purchaseorders_1_4 start
    }
		else if($order->info['purchase_order_number'])
		{
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_PURCHASE_ORDER_NUMBER; ?></td>
            <td class="main"><?php echo $order->info['purchase_order_number']; ?></td>
          </tr>
<?php
// purchaseorders_1_4 end
    }
?>
<script>
	function openAllied() {
		toAllied = document.getElementById('to_allied').value;
		arr = toAllied.split(", ");
		list = '';
		for (var i=0; i<arr.length; i++) {
			val = arr[i];
			if (document.getElementById('shipped_' + val).checked == false) {
				list = list + val + ',';
			}
		}
		window.open ('allied.php?order_id=<?=$oID?>&list='+ list, "AlliedInfo","location=0,status=0,scrollbars=0, width=500,height=500");	
	}

function openCustomCDDE() {
		toAllied = document.getElementById('to_allied').value;
		arr = toAllied.split(", ");
		list = '';
		for (var i=0; i<arr.length; i++) {
			val = arr[i];
			if (document.getElementById('shipped_' + val).checked == false) {
				list = list + val + ',';
			}
		}
		window.open ('customcdgermany.php?order_id=<?=$oID?>&list='+ list, "AlliedInfo","location=0,status=0,scrollbars=0, width=500,height=500");	
	}
function openCustomCDUSA() {
		toAllied = document.getElementById('to_allied').value;
		arr = toAllied.split(", ");
		list = '';
		for (var i=0; i<arr.length; i++) {
			val = arr[i];
			if (document.getElementById('shipped_' + val).checked == false) {
				list = list + val + ',';
			}
		}
		window.open ('customcdportland.php?order_id=<?=$oID?>&list='+ list, "AlliedInfo","location=0,status=0,scrollbars=0, width=500,height=500");	
	}

function openTampaRimage() {
		toAllied = document.getElementById('to_allied').value;
		arr = toAllied.split(", ");
		list = '';
		for (var i=0; i<arr.length; i++) {
			val = arr[i];
			if (document.getElementById('shipped_' + val).checked == false) {
				list = list + val + ',';
			}
		}
		window.open ('TampaRimage.php?order_id=<?=$oID?>&list='+ list, "AlliedInfo","location=0,status=0,scrollbars=0, width=500,height=500");	
	}

	function getDate(){
		var currentTime = new Date()
		var month = currentTime.getMonth() + 1
		var day = currentTime.getDate()
		var year = currentTime.getFullYear()
		return year + "-" + month + "-" + day;
}

	function changeDate(id){
	obj = document.getElementById('shipped_'+id);
	if (obj.checked == true){
		document.getElementById('shipped_date_'+id).disabled = false;
		document.getElementById('shipped_date_'+id).value = getDate();
	} else {
		document.getElementById('shipped_date_'+id).value = "0000-00-00";	
		document.getElementById('shipped_date_'+id).disabled = true;	
		}
	}

	function changeComments(id){
	obj = document.getElementById('returned_'+id);
	if (obj.checked == true)
		document.getElementById('returned_reason_'+id).disabled = false;
	else {
		document.getElementById('returned_reason_'+id).value = "";	
		document.getElementById('returned_reason_'+id).disabled = true;	
		}
	}

	function t_set(obj,index){
		for(i=0;i<obj.length;i++){
			if (obj[i].value==index){
				obj.selectedIndex=i;
				break;
			}
		}				
	}	

	function selectAlls(obj){
	if (obj.checked == true) flag = true; else flag = false;
<?for ($i=0; $i<sizeof($order->products); $i++) {?>
	document.getElementById('shipped_<?=$order->products[$i]['ordered_products_id']?>').checked = flag;   	
	changeDate('<?=$order->products[$i]['ordered_products_id']?>');
<?}?>
	}


	function checkSelect(){
	err = '';
<?for ($i=0; $i<sizeof($order->products); $i++) {?>
	if (document.getElementById('shipped_<?=$order->products[$i]['ordered_products_id']?>').checked == false) err = err+'error';
<?}?>
	return err;	
	}



	function backAll(obj){
	if (obj.checked == true) flag = true; else flag = false;
<?for ($i=0; $i<sizeof($order->products); $i++) {
	if ($order->products[$i]['back']!=1){
	?>
	document.getElementById('back_<?=$order->products[$i]['ordered_products_id']?>').checked = flag;   	
	document.getElementById('returned_<?=$order->products[$i]['ordered_products_id']?>').checked = flag;   	
	changeComments('<?=$order->products[$i]['ordered_products_id']?>');
<?
		}
	}
?>
}

	function backSel(obj, val){
	if (obj.checked == true) flag = true; else flag = false;
	document.getElementById('back_'+val).checked = flag;   	
	document.getElementById('returned_'+val).checked = flag;   	
	changeComments(val);
}

	function selectAllc(obj){
	counter = document.getElementById('gen_returned_reason').selectedIndex;
	word = document.getElementById('gen_returned_reason').options[counter].value;
	if (obj.checked == true) {
		flag = true; 
	} else {
		document.getElementById('gen_returned_reason').selectedIndex = 0;
		word = 'none';
		flag = false;
	}
<?for ($i=0; $i<sizeof($order->products); $i++) {?>
	document.getElementById('returned_<?=$order->products[$i]['ordered_products_id']?>').checked = flag;   	
	changeComments('<?=$order->products[$i]['ordered_products_id']?>');
	 t_set(document.getElementById('returned_reason_<?=$order->products[$i]['ordered_products_id']?>'),word);
<?}?>
	}

</script>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><td>&nbsp;<input type="button" value="Send to Allied Chicago" onclick="openAllied(); return false;" />
      &nbsp;<input type="button" value="Send to Germany" onclick="openCustomCDDE(); return false;" />&nbsp;<input type="button" value="Send to Portland" onclick="openCustomCDUSA(); return false;" />&nbsp;<input type="button" value="Send to Tampa" onclick="openTampaRimage(); return false;" /></td></tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr class="dataTableHeadingRow">
	  <td colspan="5"></td>
	  <td class="dataTableHeadingContent" align="left" style="padding-left:20px;">Shipped</td>
	  <td class="dataTableHeadingContent" align="center">Returned&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Returned Reason&nbsp;&nbsp;Back?</td>
	  <td class="dataTableHeadingContent" align="right"></td>
	</tr>
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
	    <td class="dataTableHeadingContent" style="padding-left:41px;"><input type="checkbox" name="getAlls" onclick="selectAlls(this)" value="1"/>&nbsp;Date Shipped&nbsp;&nbsp;&nbsp;</td>
	    <td align="center"><input type="checkbox" name="getAllc" onclick="selectAllc(this)" value="1"/>&nbsp;<select id='gen_returned_reason' name='gen_selection' style='width:150px;'><option value='none'></option><option value='Fraud'>Fraud</option><option value='Defective'>Defective</option><option value='Ordered Incorrectly'>Ordered Incorrectly</option><option value='Does not meet needs'>Does not meet needs</option><option value='Did not like'>Did not like</option><option value='Wrong Region Code'>Wrong Region Code</option><option value='DVD-R not compatible'>DVD-R not compatible</option><option value='Duplicate Order'>Duplicate Order</option><option value='Did not arrive in time'>Did not arrive in time</option></select><input type="checkbox" onclick="backAll(this);" /></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          </tr>

<?php
$listAllied = '';
echo tep_draw_form('status', FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=update_order');
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      $orders_products_id = $order->products[$i]['ordered_products_id'];
      $color = '';
      $sql  = "select * from products_to_allied where status=1 and product_model='".$order->products[$i]['model']."'";

      $rs = mysql_query($sql);
      if (mysql_num_rows($rs)>0) {
	$color='#fed9cd';
	$listAllied .=$order->products[$i][ordered_products_id] . ", ";
      }
      echo '<input type="hidden" name="update_products['.$orders_products_id.'][tmp]" value="'.$orders_products_id.'"/>';
      echo '<input type="hidden" name="update_products['.$orders_products_id.'][qty]" value="'.$order->products[$i]['qty'].'"/>';
      echo '          <tr class="dataTableRow" style="background: ' . $color . '">' . "\n" .
           '            <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

      if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
        for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
          echo '</i></small></nobr>';
        }
      }

$str = '';
if ($order->products[$i]['back']==1) $str = 'checked disabled';

      echo '            </td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><input onclick="changeDate(\''.$orders_products_id.'\');" id="shipped_'.$orders_products_id.'" type="checkbox" name="update_products['.$orders_products_id.'][date_shipped_checkbox]" value="1" />&nbsp;<input disabled type="text" id="shipped_date_'.$orders_products_id.'" name="update_products['.$orders_products_id.'][shipped]" style="width:130px;" value="'.$order->products[$i][date_shipped].'" /></td>' . "\n" .
           '            <td class="dataTableContent" align="center" valign="top"><input onclick="changeComments(\''.$orders_products_id.'\');" id="returned_'.$orders_products_id.'" type="checkbox" name="update_products['.$orders_products_id.'][returned_reason_checkbox]" value="1" />&nbsp;<select disabled id="returned_reason_'.$orders_products_id.'" name="update_products['.$orders_products_id.'][reason]" style="width:150px;"><option value="none"></option><option value="Fraud">Fraud</option><option value="Defective">Defective</option><option value="Ordered Incorrectly">Ordered Incorrectly</option><option value="Does not meet needs">Does not meet needs</option><option value="Did not like">Did not like</option><option value="Wrong Region Code">Wrong Region Code</option><option value="DVD-R not compatible">DVD-R not compatible</option><option value="Duplicate Order">Duplicate Order</option><option value="Did not arrive in time">Did not arrive in time</option></select><input id="back_'.$orders_products_id.'" name="update_products['.$orders_products_id.'][back]" type="checkbox" value="'.$orders_products_id.'" '.$str.'  onclick="backSel(this, \''.$orders_products_id.'\')" /></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '          </tr>' . "\n";
if ($order->products[$i][returned_reason_checkbox]=='1') {
?>
<script>
	 document.getElementById('returned_<?=$orders_products_id?>').checked = true;
	 document.getElementById('returned_reason_<?=$orders_products_id?>').disabled = false;	 
	 t_set(document.getElementById('returned_reason_<?=$orders_products_id?>'),'<?=$order->products[$i][returned_reason]?>');
</script>
<?	
		}
if ($order->products[$i][date_shipped_checkbox]=='1') {?>
<script>
	 document.getElementById('shipped_<?=$orders_products_id?>').checked = true;
	 document.getElementById('shipped_date_<?=$orders_products_id?>').disabled = false;	 
</script>
<?	
		}
	}
$listAllied = substr(trim($listAllied), 0, -1);
echo "<input type='hidden' id='to_allied' name='to_allied' value='".$listAllied."' />";
?>
          <tr>
            <td align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">
<?php
    for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
      echo '              <tr>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr><td>&nbsp;<input type="button" value="Send to Allied Chicago" onclick="openAllied(); return false;" />
      &nbsp;<input type="button" value="Send to Germany" onclick="openCustomCDDE(); return false;" />&nbsp;<input type="button" value="Send to Portland" onclick="openCustomCDUSA(); return false;" />&nbsp;<input type="button" value="Send to Tampa" onclick="openTampaRimage(); return false;" /></td></tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
<?php
    $orders_history_query = tep_db_query("select orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added");
    if (tep_db_num_rows($orders_history_query)) {
      while ($orders_history = tep_db_fetch_array($orders_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($orders_history['customer_notified'] == '1') {
          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n" .
             '            <td class="smallText">' . stripslashes(nl2br(tep_db_output($orders_history['comments']))) . '&nbsp;</td>' . "\n" .
             '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr>
        <td class="main"><br><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
<td>Shipper:<select name="shipcompany">
	<option value="USPS">USPS</option>
	<option value="UPS">UPS</option>
	<option value="FEDEX">FEDEX</option>
	<option value="DHL">DHL</option>
	<option value="Deutsch Post">Deutsch Post</option>
</select>Tracking:<input type="text" size="40" maxlength="40" name="tracking"><br />
 </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status'], 'onChange="showConfirm(this)"'); ?></td>
              </tr>
<script>
	type = '<?=$order->info[payment_method]?>';
	function showConfirm(val){
		if (type == 'Purchase Order'){
			if (val.value == '20'){
				if (confirm("Press cancel to change status to Waiting for PO Payment")) t_set(val, '20'); else t_set(val, '7');
			}
		}
		else {
			if (val.value == '20'){
				notAll = checkSelect();
				if (notAll.indexOf('error') == -1) t_set(val, '20');  else {alert("Not all products checked as shipped!"); t_set(val, '5'); }
			}			
		}
	}

	function t_set(obj,index){
		for(i=0;i<obj.length;i++){
			if (obj[i].value==index){
				obj.selectedIndex=i;
			}
		}
	}

</script>
              <tr>
                <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo tep_draw_checkbox_field('notify', '', true); ?></td>
                <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo tep_draw_checkbox_field('notify_comments', '', true); ?></td>
              </tr>
            </table></td>
            <td valign="top"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
          </tr>
        </table></td>
      </form></tr>
      <tr>
<!--  Add order Edit capability-->
<td colspan="2" align="right">
<?php 

if ($shipp=="FEDEX"){
    // begin fedex label mod
    // determine whether this is on the test or production server
    $value_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FEDEX1_SERVER'");
    $value = tep_db_fetch_array($value_query);
    $fedex_gateway = $value['configuration_value'];

    $orders_query_raw = "select orders_id, fedex_tracking, orders_status from " . TABLE_ORDERS . " where orders_id = '" . (int)$HTTP_GET_VARS['oID'] . "'";
    $orders_query = tep_db_query($orders_query_raw);
    if (tep_db_num_rows($orders_query)) {
        $fedex_orders = tep_db_fetch_array($orders_query);
    }

    // check for a fedex tracking number in the order record
    // if yes tracking number, show "fedex label," "track" and "cancel" options
    $fedex_tracking = $fedex_orders['fedex_tracking'];

    if ($fedex_tracking) {
        // display the label
        echo '<a href="fedex_popup.php?num=' . $fedex_tracking . '&oID=' . $HTTP_GET_VARS['oID'] . '">' . tep_image_button('button_fedex_label.gif', IMAGE_ORDERS_FEDEX_LABEL) . '</a>&nbsp;';
        // cancel the request
        echo '<a href="' . tep_href_link(FILENAME_SHIP_FEDEX, 'oID=' . $HTTP_GET_VARS['oID'] . '&num=' . $fedex_tracking . '&action=cancel&fedex_gateway=' . $fedex_gateway) . '" onClick="return(window.confirm(\'Cancel shipment of order number ' . $order->orders_id . '?\'));">' . tep_image_button('button_cancel_shipment.gif', IMAGE_ORDERS_CANCEL_SHIPMENT) . '</a>&nbsp;';
    }
    elseif ($fedex_orders['orders_status'] != 3) {          // if the order has not been manually marked "delivered,"
	// display the "ship" button
	echo '<a href="' . tep_href_link(FILENAME_SHIP_FEDEX, 'oID=' . $HTTP_GET_VARS['oID'] . '&action=new&status=3') . '">' . tep_image_button('button_ship.gif', IMAGE_ORDERS_SHIP) . '</a>&nbsp;';
    }
}
else 
	echo '<a href="' . tep_href_link(FILENAME_ORDERS_USPS_SHIP, 'oID=' . $HTTP_GET_VARS['oID']) . '" TARGET="_blank">' . tep_image_button('button_labelshipping.gif', IMAGE_ORDERS_USPS_SHIP) . '</a>&nbsp;';
    // end fedex label mod
//echo '<input type="button" value="Print Label" onclick="javascript:printLabel('.$HTTP_GET_VARS['oID'].')" />&nbsp;<a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <input type="button" value="PDF invoice" onclick="javascript:generatePDF('.$HTTP_GET_VARS['oID'].')" /> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; 

echo '<input type="button" value="Print Label" onclick="javascript:printLabel('.$HTTP_GET_VARS['oID'].')" />&nbsp;<a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; 

?></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('oID', '', 'size="12"') . tep_draw_hidden_field('action', 'edit'); ?></td>
              </form></tr>

<!--- ORDER SEARCH -->
  			  <tr><?php echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get'); ?>
				<td class="smallText" align="right">Criteria :
				<?php
					$criteria_value = array(1=>"Billing Address",2=>"Shipping Address",3=>"Products",4=>"Payment Method",5=>"Comments",6=>"Shipping Method",7=>"PO number");
					echo "<select name='criteria'>";
					foreach ($criteria_value as $key=>$val) {

						if ($key == $criteria)
							echo "<option value=$key SELECTED>$val</option>";
						else
							echo "<option value=$key>$val</option>";
					}
					echo "</select>";
				?>
				</td>
              </tr>
              <tr>
                <td class="smallText" align="right"><?php echo HEADING_SEARCH . ' ' . tep_draw_input_field('searchstring', '', 'size="20"') . tep_draw_hidden_field('action', 'edit'); ?></td>
              </form></tr>
<!--- ORDER SEARCH -->


              <tr><?php echo tep_draw_form('status', FILENAME_ORDERS, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();"'); ?></td>
              </form></tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>

                <!--##CHANGES IN ORDERS.PHP ON 18-NOV-04-->
				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDERNO; ?>&nbsp;</td>
				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_PAYMENTMETHOD; ?>&nbsp;</td>
				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_SHIPMETHOD; ?>&nbsp;</td>
				<!--####################################-->

                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
<? if (!isset($searchstring)){?>
                <td class="dataTableHeadingContent" align="right"># Prev Orders</td>
                <td class="dataTableHeadingContent" align="right">$ Prev Orders</td>
<? } ?>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if (isset($HTTP_GET_VARS['cID'])) {
      $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);
      $orders_query_raw = "select o.orders_id, o.customers_id, o.iswholesale, o.currency, o.currency_value, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from (" . TABLE_ORDERS . " o) left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$cID . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by orders_id DESC";
    } elseif (isset($HTTP_GET_VARS['status'])) {
      $status = tep_db_prepare_input($HTTP_GET_VARS['status']);
      $orders_query_raw = "select o.orders_id, o.customers_id, o.iswholesale, o.currency, o.currency_value, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from (" . TABLE_ORDERS . " o) left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.orders_status_id = '" . (int)$status . "' and ot.class = 'ot_total' order by o.orders_id DESC";
    } else {
      $orders_query_raw = "select o.orders_id, o.customers_id, o.iswholesale, o.currency, o.currency_value, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from (" . TABLE_ORDERS . " o) left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by o.orders_id DESC";
    }

	//ORDER SEARCH STRING
	  if (isset($HTTP_GET_VARS['searchstring']) && ($HTTP_GET_VARS['searchstring'] <> '')) {
		$search_string = $HTTP_GET_VARS['searchstring'];
		if ($criteria == 1) {
			$orders_query_raw = "select o.orders_id, o.customers_id, o.iswholesale, o.currency, o.currency_value, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from (orders o) left join orders_total ot on (o.orders_id = ot.orders_id), orders_status s where (o.billing_name like '%". $search_string ."%' or o.billing_company like '%". $search_string ."%' or o.billing_country like '%". $search_string ."%' or o.billing_state like '%". $search_string ."%' or o.billing_postcode like '%". $search_string ."%' or o.billing_city like '%". $search_string ."%' or o.billing_suburb like '%". $search_string ."%' or o.billing_street_address like '%". $search_string ."%') and o.orders_status = s.orders_status_id and s.language_id = '1' and ot.class = 'ot_total' and ot.orders_id = o.orders_id order by o.orders_id DESC ";
		}
		elseif ($criteria == 2) {
			$orders_query_raw = "select o.orders_id, o.customers_id, o.iswholesale, o.currency, o.currency_value, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from (orders o) left join orders_total ot on (o.orders_id = ot.orders_id), orders_status s where (o.delivery_name like '%". $search_string ."%' or o.delivery_company like '%". $search_string ."%' or o.delivery_street_address like '%". $search_string ."%' or o.delivery_suburb like '%". $search_string ."%' or o.delivery_city like '%". $search_string ."%' or o.delivery_postcode like '%". $search_string ."%' or o.delivery_state like '%". $search_string ."%' or o.delivery_country like '%". $search_string ."%') and o.orders_status = s.orders_status_id and s.language_id = '1' and ot.class = 'ot_total' order by o.orders_id DESC ";
		}
		elseif ($criteria == 3) {
			$orders_query_raw = "select distinct(o.orders_id), o.customers_id, o.customers_name, o.iswholesale, o.currency, o.currency_value, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from (orders_products op, orders o) left join orders_total ot on (o.orders_id = ot.orders_id), orders_status s where (op.products_name like '%". $search_string ."%' or op.products_model like '%". $search_string ."%') and op.orders_id = o.orders_id and o.orders_status = s.orders_status_id and s.language_id = '1' and ot.class = 'ot_total' order by o.orders_id DESC";
		}
		elseif ($criteria == 4) {
			$orders_query_raw = "select o.orders_id, o.customers_id, o.iswholesale, o.currency, o.currency_value, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from (orders o) left join orders_total ot on (o.orders_id = ot.orders_id), orders_status s where o.payment_method like '%". $search_string ."%' and o.orders_status = s.orders_status_id and s.language_id = '1' and ot.class = 'ot_total' order by o.orders_id DESC ";
		}
		elseif ($criteria == 5) {
			$orders_query_raw = "select distinct(o.orders_id), o.customers_id, o.iswholesale, o.currency, o.currency_value, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from (orders o, orders_status_history osh) left join orders_total ot on (o.orders_id = ot.orders_id), orders_status s where osh.comments like '%". $search_string ."%' and o.orders_id = osh.orders_id and o.orders_status = s.orders_status_id and s.language_id = '1' and ot.class = 'ot_total' order by o.orders_id DESC ";
		}
		elseif ($criteria == 6) {
			$orders_query_raw = "select o.orders_id, o.customers_id, o.currency, o.currency_value, o.iswholesale, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from (orders o) left join orders_total ot on (o.orders_id = ot.orders_id), orders_status s where ot.title like '%". $search_string ."%' and o.orders_id = ot.orders_id and o.orders_status = s.orders_status_id and s.language_id = '1' and ot.class = 'ot_shipping' order by o.orders_id DESC ";
		}
		elseif ($criteria == 7) {
			$orders_query_raw = "select o.orders_id, o.customers_id, o.currency, o.currency_value, o.iswholesale, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from (orders o) left join orders_total ot on (o.orders_id = ot.orders_id), orders_status s where o.purchase_order_number like '%". $search_string ."%' and o.orders_status = s.orders_status_id and s.language_id = '1' and ot.class = 'ot_total' order by o.orders_id DESC ";
		}
		elseif ($criteria == 8) {
			$orders_query_raw = "select distinct(o.orders_id), o.customers_id, o.currency, o.currency_value, o.iswholesale, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from (orders o) left join orders_total ot on (o.orders_id = ot.orders_id), orders_status s where o.customers_id='". $search_string ."' and o.orders_status = s.orders_status_id and s.language_id = '1' and ot.class = 'ot_total' group by orders_id order by o.orders_id DESC ";
		}

	}

	//ORDER SEARCH STRING

    //NEWLY INSERTED TO AVOID NULL PAGE NUMBER
	$orders_query_count = tep_db_query($orders_query_raw);
	$orders_query_numrows_count = tep_db_num_rows($orders_query_count);
   //


    $orders_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders_query = tep_db_query($orders_query_raw);
    while ($orders = tep_db_fetch_array($orders_query)) {

    //##CHANGES IN ORDERS.PHP ON 18-NOV-04
	$qry = "select title from ". TABLE_ORDERS_TOTAL ." where orders_id = ".$orders['orders_id'] ." and class = 'ot_shipping'";
	$orders_total_ship_query = tep_db_query($qry);
	$orders_total_ship_result = tep_db_fetch_array($orders_total_ship_query);
	####################################

    if ((!isset($HTTP_GET_VARS['oID']) || (isset($HTTP_GET_VARS['oID']) && ($HTTP_GET_VARS['oID'] == $orders['orders_id']))) && !isset($oInfo)) {
        $oInfo = new objectInfo($orders);
      }

      if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $orders['customers_name']; ?></td>
                <!--//##CHANGES IN ORDERS.PHP ON 18-NOV-04-->
<?
if ($orders['iswholesale'] == 1) $style = 'border: 1px red solid; padding: 1px;'; else $style='';
?>
				<td class="dataTableContent" align="right"><span style='<?=$style?>'><?php echo $orders['orders_id'];?></span>&nbsp;</td>
				<td class="dataTableContent" align="left"><?php echo $orders['payment_method'];?></td>
				<td class="dataTableContent" align="left"><?php echo $orders_total_ship_result['title'];?>&nbsp;</td>
	            <!--####################################-->
                <td class="dataTableContent" align="right"><?php echo strip_tags($orders['order_total']); ?></td>
                <td class="dataTableContent" align="center"><?php echo tep_datetime_short($orders['date_purchased']); ?></td>
                <td class="dataTableContent" align="right"><?php echo $orders['orders_status_name']; ?></td>
<? if (!isset($searchstring)){?>
                <td class="dataTableContent" align="right"><?php $count = tep_db_fetch_array(tep_db_query("select count(*) as cnt from orders where customers_id='".$orders[customers_id]."'"));?><a href="orders.php?criteria=8&searchstring=<?=$orders[customers_id]?>&action=edit"><?=$count[cnt].'</a>'?></td>
                <td class="dataTableContent" align="right"><?php $summ = tep_db_fetch_array(tep_db_query("SELECT sum(ot.value) as cnt FROM orders o LEFT  JOIN orders_total ot ON ot.orders_id = o.orders_id WHERE o.customers_id =  '".$orders[customers_id]."' AND ot.class =  'ot_total'"));?> <a href="orders.php?criteria=8&searchstring=<?=$orders[customers_id]?>&action=edit"><?=$currencies->format($summ[cnt], true, $orders[currency], $orders[currency_value]).'</a>'?></td>
<? } ?>
                <td class="dataTableContent" align="right"><?php if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>

              </tr>
<?php
    }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <!--<td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>-->
                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows_count, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows_count, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDER . '</b>');

      $contents = array('form' => tep_draw_form('orders', FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($oInfo) && is_object($oInfo)) {
        $heading[] = array('text' => '<b>[' . $oInfo->orders_id . ']&nbsp;&nbsp;' . tep_datetime_short($oInfo->date_purchased) . '</b>');

				$value_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FEDEX1_SERVER'");
				$value = tep_db_fetch_array($value_query);
				$fedex_gateway = $value['configuration_value'];	

// check for a fedex tracking number in the order record
// if yes tracking number, show "fedex label," "track" and "cancel" options
				$fedex_tracking = $oInfo->fedex_tracking;

// get the current order status				
				$check_fedex_status_query = tep_db_query("select orders_status from " . TABLE_ORDERS . " where orders_id = '" . $oInfo->orders_id . "'");
       	$check_fedex_status = tep_db_fetch_array($check_fedex_status_query);

/*==================Define shipping method=============================*/
        $rqShipping = tep_db_query("SELECT title FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = " . $oInfo->orders_id. " AND class = 'ot_shipping'");
        $shipping = tep_db_fetch_array($rqShipping);
if (strpos($shipping[title], 'USPS')!==false) 
	$shipping = "USPS";        
else
	$shipping = "FEDEX";
/*==================Define shipping method=============================*/

if ($shipping=="FEDEX"){
				if ($fedex_tracking) {
					// display the label
					$contents[] = array('align' => 'center', 'text' => '<a href="fedex_popup.php?num=' . $fedex_tracking . '&oID=' . $oInfo->orders_id . '">' . tep_image_button('button_fedex_label.gif', IMAGE_ORDERS_FEDEX_LABEL) . '</a>');
					
					// track the package (no gateway needs to be specified)
					$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_TRACK_FEDEX, 'oID=' .$oInfo->orders_id . '&num=' . $fedex_tracking) . '&fedex_gateway=track">' . tep_image_button('button_track.gif', IMAGE_ORDERS_TRACK) . '</a>');

					// cancel the request				
					
					$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SHIP_FEDEX, 'oID=' .$oInfo->orders_id . '&num=' . $fedex_tracking . '&action=cancel&fedex_gateway=' . $fedex_gateway) . '" onClick="return(window.confirm(\'Cancel shipment of order number ' . $oInfo->orders_id . '?\'));">' . tep_image_button('button_cancel_shipment.gif', IMAGE_ORDERS_CANCEL_SHIPMENT) . '</a>');
				}
// if no fedex tracking number, AND if the order has not been manually marked "delivered,"
// display the "ship" button

//				elseif ((!$fedex_tracking) && (($check_status['orders_status']) != 3)) {
					elseif ((!$fedex_tracking) && (($check_fedex_status['orders_status']) != 3)) {			
					$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SHIP_FEDEX, 'oID=' .$oInfo->orders_id . '&action=new&status=3') . '">' . tep_image_button('button_ship.gif', IMAGE_ORDERS_SHIP) . '</a>');
				}

// end fedex
}
else
	$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS_USPS_SHIP, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . tep_image_button('button_labelshipping.gif', IMAGE_ORDERS_USPS_SHIP) . '</a>');

/*   Change to add edit orders     $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ORDER_CREATED . ' ' . tep_date_short($oInfo->date_purchased));
*/
$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '">' . tep_image_button('button_details.gif', IMAGE_DETAILS) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
//$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
//$contents[] = array('align' => 'left', 'text' => '<input type="button" value="PDF invoice" onclick="javascript:generatePDF('.$oInfo->orders_id.')" />');

$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');


$contents[] = array('text' => '<br>' . TEXT_DATE_ORDER_CREATED . ' ' . tep_date_short($oInfo->date_purchased));
        if (tep_not_null($oInfo->last_modified)) $contents[] = array('text' => TEXT_DATE_ORDER_LAST_MODIFIED . ' ' . tep_date_short($oInfo->last_modified));
        $contents[] = array('text' => '<br>' . TEXT_INFO_PAYMENT_METHOD . ' '  . $oInfo->payment_method);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
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