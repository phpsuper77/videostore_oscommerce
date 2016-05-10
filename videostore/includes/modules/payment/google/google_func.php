<?php
/*
 
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
  
  Portions  Copyright (c) 2005 - 2006 Chain Reaction Works, Inc
        
*/

	function google_get_orders_id($xml) {
		foreach ($xml['child'] as $value) {
			if (strtolower($value['name']) == 'shopping-cart') {
				$shopping_cart = $value['child'];
				break;
			}
		}
		foreach ($shopping_cart as $value) {
			if (strtolower($value['name']) == 'merchant-private-data') {
				$private_data = $value['child'];
				break;
			}
		}
		foreach ($private_data as $value) {
			if (strtolower($value['name']) == 'osc-orders-id') {
				return $value['content'];
			}
		}
	}
	
	function google_get_top_value($xml, $val) {
		foreach ($xml['child'] as $value) {
			if (strtolower($value['name']) == $val) {
				return $value['content'];
			}
		}
	}
	
	function google_get_orders_status() {
		global $languages_id;
		
		$orders_status = array();
		$sql_query = tep_db_query("select * from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
		while ($sql_array = tep_db_fetch_array($sql_query)) {
			$orders_status[str_replace(' ', '_', $sql_array['orders_status_name'])] = $sql_array['orders_status_id'];
		}
		return $orders_status;
	}
	
	function google_getCustomerComments($orders_id) {
    $orders_history_query = tep_db_query("select comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$orders_id . "' order by date_added limit 1");
    if (tep_db_num_rows($orders_history_query)) {
      $orders_history = tep_db_fetch_array($orders_history_query);
      return $orders_history['comments'];
    }
    return false;
  }
  
  function google_updateProducts(&$order, $orders_id) {
  	global $languages_id, $currencies;
  	
    $products_ordered = '';
    $subtotal = 0;
    $total_tax = 0;
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (STOCK_LIMITED == 'true') {
        if (DOWNLOAD_ENABLED == 'true') {
          $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename
                              FROM " . TABLE_PRODUCTS . " p
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                               ON p.products_id=pa.products_id
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                               ON pa.products_attributes_id=pad.products_attributes_id
                              WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
          $products_attributes = $order->products[$i]['attributes'];
          if (is_array($products_attributes)) {
            $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
          }
          $stock_query = tep_db_query($stock_query_raw);
        } else {
          $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
        }
        if (tep_db_num_rows($stock_query) > 0) {
          $stock_values = tep_db_fetch_array($stock_query);
          // do not decrement quantities if products_attributes_filename exists
          if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
            $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
          } else {
            $stock_left = $stock_values['products_quantity'];
          }
//          tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
          if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
//            tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
          }
        }
      }
      // Update products_ordered (for bestsellers list)
//      tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
      //------insert customer choosen option to order--------
      $attributes_exist = '0';
      $products_ordered_attributes = '';
      if (isset($order->products[$i]['attributes'])) {
        $attributes_exist = '1';
        for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
          if (DOWNLOAD_ENABLED == 'true') {
            $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
                                 from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                 left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                  on pa.products_attributes_id=pad.products_attributes_id
                                 where pa.products_id = '" . $order->products[$i]['id'] . "'
                                  and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                  and pa.options_id = popt.products_options_id
                                  and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                  and pa.options_values_id = poval.products_options_values_id
                                  and popt.language_id = '" . $languages_id . "'
                                  and poval.language_id = '" . $languages_id . "'";
            $attributes = tep_db_query($attributes_query);
          } else {
            $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
          }
          $attributes_values = tep_db_fetch_array($attributes);
          if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename']) ) {
            $sql_data_array = array('orders_id' => $orders_id,
                                    'orders_products_id' => $order->products[$i]['orders_products_id'],
                                    'orders_products_filename' => $attributes_values['products_attributes_filename'],
                                    'download_maxdays' => $attributes_values['products_attributes_maxdays'],
                                    'download_count' => $attributes_values['products_attributes_maxcount']);
//            tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
          }
          $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
        }
      }
      //------insert customer choosen option eof ----
      $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
      $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
      $total_cost += $total_products_price;

      $products_ordered_price = $currencies->display_price($order->products[$i]['final_price'],$order->products[$i]['tax'],$order->products[$i]['qty']);

      $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $products_ordered_price . $products_ordered_attributes . "\n";
    }
    return $products_ordered;
  }
  
	function google_notifyCustomer(&$order, $orders_id) {
		global $language;
		
		require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);
		
		$products_ordered = google_updateProducts(&$order, $orders_id);
    
    $email_order = STORE_NAME . "\n" .
                   EMAIL_SEPARATOR . "\n" .
                   EMAIL_TEXT_ORDER_NUMBER . ' ' . $orders_id . "\n" .
                   EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders_id, 'SSL', false) . "\n" .
                   EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";

    $customerComments = google_getCustomerComments($orders_id);
    if ($customerComments) {
      $email_order .= tep_db_output($customerComments) . "\n\n";
    }
    $email_order .= EMAIL_TEXT_PRODUCTS . "\n" . EMAIL_SEPARATOR . "\n" . $products_ordered . EMAIL_SEPARATOR . "\n";
    for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
      $email_order .= strip_tags($order->totals[$i]['title']) . ' ' . strip_tags($order->totals[$i]['text']) . "\n";
		}
    if ($order->content_type != 'virtual') {
      $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" .
                           EMAIL_SEPARATOR . "\n";
      if ($order->delivery['company']) {
        $email_order .= $order->delivery['company'] . "\n";
			}
      $email_order .= $order->delivery['name'] . "\n" . $order->delivery['street_address'] . "\n";
      if ($order->delivery['suburb']) {
        $email_order .= $order->delivery['suburb'] . "\n";
			}
      $email_order .= $order->delivery['city'] . ', ' . $order->delivery['postcode'] . "\n";
      if ($order->delivery['state']) {
        $email_order .= $order->delivery['state'] . ', ';
			}
      $email_order .= $order->delivery['country'] . "\n";
    }
    $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" . EMAIL_SEPARATOR . "\n";
    if ($order->billing['company']) {
      $email_order .= $order->billing['company'] . "\n";
		}
    $email_order .= $order->billing['name'] . "\n" . $order->billing['street_address'] . "\n";
    if ($order->billing['suburb']) {
      $email_order .= $order->billing['suburb'] . "\n";
		}
    $email_order .= $order->billing['city'] . ', ' . $order->billing['postcode'] . "\n";
    if ($order->billing['state']) {
      $email_order .= $order->billing['state'] . ', ';
		}
    $email_order .= $order->billing['country'] . "\n\n";
    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . EMAIL_SEPARATOR . "\n" . $order->info['payment_method'] . "\n\n";

    tep_mail($order->customer['name'],$order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');

    if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
      tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT,  $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');
    }
  }
  
  function google_notification_check() {
		if ($_SERVER['PHP_AUTH_USER'] != MODULE_PAYMENT_GOOGLE_MERCHANT_ID || $_SERVER['PHP_AUTH_PW'] != MODULE_PAYMENT_GOOGLE_MERCHANT_KEY) {
			return false;
		}
		return true;
  }
  
  function google_charge_money($order, $google_order_number) {
  	if (MODULE_PAYMENT_GOOGLE_ACCOUNT_TYPE == 'checkout') {
     $url = 'https://checkout.google.com/cws/v2/Merchant/' . MODULE_PAYMENT_GOOGLE_MERCHANT_ID . '/request';
} elseif (MODULE_PAYMENT_GOOGLE_ACCOUNT_TYPE == 'sandbox') {
     $url = 'https://sandbox.google.com/checkout/cws/v2/Merchant/' . MODULE_PAYMENT_GOOGLE_MERCHANT_ID . '/request';
}
  	$data = '<?xml version="1.0" encoding="UTF-8"?>';
		if (MODULE_PAYMENT_GOOGLE_ACCOUNT_TYPE == 'checkout') {
     $data .= '<charge-order xmlns="http://checkout.google.com/schema/2" google-order-number="' . $google_order_number . '">';
} elseif (MODULE_PAYMENT_GOOGLE_ACCOUNT_TYPE == 'sandbox') {
     $data .= '<charge-order xmlns="http://sandbox.google.com/checkout/schema/2" google-order-number="' . $google_order_number . '">';
}
  		$data .= '<amount currency="' . $order->info['currency'] . '">' . $order->info['total_value'] . '</amount>';
		$data .= '</charge-order>';
		$header = array('Content-type: application/xml', 'Accept: application/xml');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_USERPWD, MODULE_PAYMENT_GOOGLE_MERCHANT_ID . ':' . MODULE_PAYMENT_GOOGLE_MERCHANT_KEY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		curl_close($ch);		
  }
  
  function google_set_order_number($orders_id, $google_order_number) {
  	if (MODULE_PAYMENT_GOOGLE_ACCOUNT_TYPE == 'checkout') {
     $url = 'https://checkout.google.com/cws/v2/Merchant/' . MODULE_PAYMENT_GOOGLE_MERCHANT_ID . '/request';
} elseif (MODULE_PAYMENT_GOOGLE_ACCOUNT_TYPE == 'sandbox') {
     $url = 'https://sandbox.google.com/checkout/cws/v2/Merchant/' . MODULE_PAYMENT_GOOGLE_MERCHANT_ID . '/request';
}
  	$data = '<?xml version="1.0" encoding="UTF-8"?>';
		if (MODULE_PAYMENT_GOOGLE_ACCOUNT_TYPE == 'checkout') {
   $data .= '<add-merchant-order-number xmlns="http://checkout.google.com/schema/2" google-order-number="' . $google_order_number . '">';
} elseif (MODULE_PAYMENT_GOOGLE_ACCOUNT_TYPE == 'sandbox') {
   $data .= '<add-merchant-order-number xmlns="http://sandbox.google.com/checkout/schema/2" google-order-number="' . $google_order_number . '">';
}
  		$data .= '<merchant-order-number>' . $orders_id . '</merchant-order-number>';
		$data .= '</add-merchant-order-number>';
		$header = array('Content-type: application/xml', 'Accept: application/xml');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_USERPWD, MODULE_PAYMENT_GOOGLE_MERCHANT_ID . ':' . MODULE_PAYMENT_GOOGLE_MERCHANT_KEY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		curl_close($ch);		
  }
?>