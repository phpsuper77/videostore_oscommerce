<?php
/*
 
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
  
  Portions  Copyright (c) 2005 - 2006 Chain Reaction Works, Inc
        
*/
	  
	require('includes/application_top.php');
	require(DIR_WS_MODULES . 'payment/google/xml.php');
	require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_GOOGLE_IPN);
	require(DIR_WS_MODULES . 'payment/google/google_func.php');
	
	if (!google_notification_check()) {
		$fp = @fopen("gclog/google_log.txt","a");
		@fwrite($fp, date('Y-m-d H:m:s') . "------------------------\n");
		@fwrite($fp, "User Name: " . $_SERVER['PHP_AUTH_USER'] . "\nPassword: " . $_SERVER['PHP_AUTH_PW'] . "\n");
		@fwrite($fp, "Notification Check Error!\n\n");
		@fclose($fp);
		die();
	}
	
	$xml_data = $HTTP_RAW_POST_DATA;

/*
	$xml_data = '<?xml version="1.0" encoding="UTF-8"?>
<order-state-change-notification xmlns="http://checkout.google.com/schema/2" serial-number="37bf27f7-3242-4ac1-9d54-af889f89e7e3">
  <timestamp>2006-09-16T02:49:40.454Z</timestamp>
  <google-order-number>204111019466343</google-order-number>
  <new-fulfillment-order-state>NEW</new-fulfillment-order-state>
  <new-financial-order-state>CHARGEABLE</new-financial-order-state>
  <previous-fulfillment-order-state>NEW</previous-fulfillment-order-state>
  <previous-financial-order-state>REVIEWING</previous-financial-order-state>
</order-state-change-notification>';
*/

	if ($xml_data == '') {
		die();
	}
	$fp = @fopen("gclog/google_log.txt","a");
	@fwrite($fp, date('Y-m-d H:m:s') . "------------------------\n");
	@fwrite($fp, $xml_data . "\n\n");
	@fclose($fp);
	
	$xml = new XMLParser($xml_data);
	
	switch (strtolower($xml->data[0]['name'])) {
		case 'new-order-notification':
			$orders_id = google_get_orders_id($xml->data[0]);
			$google_order_id = google_get_top_value($xml->data[0], 'google-order-number');
			$sql_data = array('message_type' => 'new-order-notification',
												'msg_time' => 'now()',
												'google_order_id' => $google_order_id,
												'xml' => $xml_data,
												'orders_id' => $orders_id);
			tep_db_perform(TABLE_GOOGLE_CHECKOUT_LOGS, $sql_data);
			tep_db_query("update " . TABLE_ORDERS . " set google_order_id = '" . $google_order_id . "' where orders_id = '" . $orders_id . "'");
			break;
		case 'order-state-change-notification':
			$google_order_id = google_get_top_value($xml->data[0], 'google-order-number');
			$orders_id = tep_db_fetch_array(tep_db_query("select orders_id from " . TABLE_GOOGLE_CHECKOUT_LOGS . " where google_order_id = '" . $google_order_id . "'"));
			$sql_data = array('message_type' => 'order-state-change-notification',
												'msg_time' => 'now()',
												'google_order_id' => $google_order_id,
												'xml' => $xml_data,
												'orders_id' => $orders_id['orders_id']);
			tep_db_perform(TABLE_GOOGLE_CHECKOUT_LOGS, $sql_data);
			$google_orders_status = google_get_top_value($xml->data[0], 'new-financial-order-state');
			$google_fulfillment_status = google_get_top_value($xml->data[0], 'new-fulfillment-order-state');
//			$orders_status = google_get_orders_status();
			switch ($google_orders_status) {
				case 'CHARGEABLE':
					$orders_status_id = MODULE_PAYMENT_GOOGLE_ORDER_CHARGEABLE_STATUS_ID;
					break;
				case 'REVIEWING':
					$orders_status_id = MODULE_PAYMENT_GOOGLE_ORDER_REVIEWING_STATUS_ID;
					break;
				case 'CHARGING':
					$orders_status_id = MODULE_PAYMENT_GOOGLE_ORDER_CHARGING_STATUS_ID;
					break;
				case 'CHARGED':
					$orders_status_id = MODULE_PAYMENT_GOOGLE_ORDER_CHARGED_STATUS_ID;
					break;
				case 'PAYMENT_DECLINED':
					$orders_status_id = MODULE_PAYMENT_GOOGLE_ORDER_PAYMENT_DECLINED_STATUS_ID;
					break;
				case 'CANCELLED':
					$orders_status_id = MODULE_PAYMENT_GOOGLE_ORDER_CANCELLED_STATUS_ID;
					break;
				case 'CANCELLED_BY_GOOGLE':
					$orders_status_id = MODULE_PAYMENT_GOOGLE_ORDER_CANCELLED_BY_GOOGLE_STATUS_ID;
					break;
			}
			$customer_notification = '0';
			tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . $orders_status_id . "' where orders_id = '" . $orders_id['orders_id'] . "'");
			if ($google_orders_status == 'CHARGEABLE' && $google_fulfillment_status == 'NEW') {
				require(DIR_WS_CLASSES . 'order.php');
				$order = new order($orders_id['orders_id']);
				google_notifyCustomer(&$order, $orders_id['orders_id'], $orders_status_id);
				$customer_notification = '1';
//				if (MODULE_PAYMENT_GOOGLE_CHARGE_TYPE == 'Auto Charge') {
//					google_charge_money($order, $google_order_id);
//				}
				google_set_order_number($orders_id['orders_id'], $google_order_id);
			}			
	    tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . $orders_id['orders_id'] . "', '" . $orders_status_id . "', now(), '" . $customer_notification . "', '" . GOOGLE_STATUS_CHANGE_COMMENTS . "')");
	    break;
		default:
			$google_order_id = google_get_top_value($xml->data[0], 'google-order-number');
			$orders_id = tep_db_fetch_array(tep_db_query("select orders_id from " . TABLE_GOOGLE_CHECKOUT_LOGS . " where google_order_id = '" . $google_order_id . "'"));
			$sql_data = array('message_type' => strtolower($xml->data[0]['name']),
												'msg_time' => 'now()',
												'google_order_id' => $google_order_id,
												'xml' => $xml_data,
												'orders_id' => $orders_id['orders_id']);
			tep_db_perform(TABLE_GOOGLE_CHECKOUT_LOGS, $sql_data);
			break;		
	}
	
	include('includes/application_bottom.php');
?>