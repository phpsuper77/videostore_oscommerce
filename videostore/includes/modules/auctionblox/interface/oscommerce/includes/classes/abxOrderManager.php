<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  AuctionBlox, Inc.
  Copyright (c) 2008 AuctionBlox, Inc.
  http://www.auctionblox.com

  Released under the GNU General Public License
*/

  require_once(DIR_FS_ABX5_FUNCTIONS . 'formatter.php');    
  class_exists('abxCurrencies') || require_once_cart('includes/classes/abxCurrencies.php');    
  class_exists('abxAttributes') || require_once_cart('includes/classes/abxAttributes.php');
  class_exists('order') || require_once('includes/classes/order.php');
    
  class abxOrderManager
  {
    function getOrderUpdates($timestamp)
    {
      global $abxDatabase, $languages_id;
      
      $orders = array();
      
      $timestamp = date('Y-m-d H:i:s', parse_iso8601_datetime($timestamp));

      // Let's not download more than 25 orders at a time.  Otherwise, on busy sites we could have some issues
      $order_ids = array();
      $order_id_query = $abxDatabase->query("select distinct orders_id from " . TABLE_ORDERS_STATUS_HISTORY . " where date_added > '" . addslashes($timestamp) . "' order by date_added asc limit 100");
	  while ($order_id_result = $order_id_query->next()) {
	    $order_ids[] = $order_id_result['orders_id'];
	  } 
	  
	  if(count($order_ids) > 0)
	  {
        $order_query = $abxDatabase->query("select distinct orders_id, orders_status, orders_tracking_number, orders_ship_carrier from " . TABLE_ORDERS . " where orders_id in (" . implode(',',$order_ids) . ")");
  	    while ($order_id_result = $order_query->next()) {
  	    $order = new order($order_id_result['orders_id']);
  	    if($order != null)
  	    {
            $aOrder = array();
            
            $aOrder['orderNumber'] = $order_id_result['orders_id'];
            $aOrder['createdOn'] = gmDate("Y-m-d\TH:i:s\Z", strtotime($order->info['date_purchased']));
            $aOrder['updatedOn'] = gmDate("Y-m-d\TH:i:s\Z", strtotime($order->info['last_modified']));
            $aOrder['status'] = $order_id_result['orders_status'];
            
            $aBuyer = array();
            $aBuyer['email'] = $order->customer['email_address'];
            $aBuyer['firstName'] = $this->_getFirstName($order->customer['name']);
            $aBuyer['lastName'] = $this->_getLastName($order->customer['name']);
            $aBuyer['name'] = $order->customer['name'];
            $aOrder['buyer'] = $aBuyer;
            
            $aBillingAddress = array();
            $aBillingAddress['firstName'] = $this->_getFirstName($order->billing['name']);
            $aBillingAddress['lastName'] = $this->_getLastName($order->billing['name']);
            $aBillingAddress['fullName'] = $order->billing['name'];
            $aBillingAddress['company'] = $order->billing['company'];
            $aBillingAddress['street1'] = $this->_getStreet1($order->billing['street_address']);
            $aBillingAddress['street2'] = $this->_getStreet2($order->billing['street_address']);
            $aBillingAddress['city'] = $order->billing['city'];
            $aBillingAddress['state'] = $order->billing['state'];
            $aBillingAddress['postalCode'] = $order->billing['postcode'];
            $aBillingAddress['country'] = $order->billing['country'];
            $aBillingAddress['phone'] = $order->billing['telephone'];
            $aOrder['billingAddress'] = $aBillingAddress;
            
            $aShippingAddress = array();
            $aShippingAddress['firstName'] = $this->_getFirstName($order->delivery['name']);
            $aShippingAddress['lastName'] = $this->_getLastName($order->delivery['name']);
            $aShippingAddress['fullName'] = $order->delivery['name'];
            $aShippingAddress['company'] = $order->delivery['company'];
            $aShippingAddress['street1'] = $this->_getStreet1($order->delivery['street_address']);
            $aShippingAddress['street2'] = $this->_getStreet2($order->delivery['street_address']);
            $aShippingAddress['city'] = $order->delivery['city'];
            $aShippingAddress['state'] = $order->delivery['state'];
            $aShippingAddress['postalCode'] = $order->delivery['postcode'];
            $aShippingAddress['country'] = $order->delivery['country'];
            $aShippingAddress['phone'] = $order->delivery['telephone'];
            $aOrder['shippingAddress'] = $aShippingAddress;          
            
            
            $tax = 0.0;
            $subtotal = 0.0;
            foreach($order->products as $product)
            {
              $tax += (double)$product['tax'];
              $subtotal += (double)$product['price'];
              
              $oi = array();
              $oi['productId'] = $product['id'];
              $oi['sku'] = $product['model'];
              $oi['name'] = $product['name'];
              $oi['qtyPurchased'] = $product['qty'];
              //+++AUCTIONBLOX - Removed this to avoid sending -1 for products "always on hand"
	      //$oi['qtyInStock'] = $this->_getProductStockQuantity($product['id']);
              $oi['taxRate'] = $product['tax'];
              $oi['taxAmount'] = (double)$product['final_price'] - (double)$product['price'];
              $oi['itemPrice'] = $product['price'];
              
              $aOrder['orderItem'][] = $oi;
            }
  
            $aOrder['subTotal'] = $subtotal;
            $aOrder['tax'] = $tax;
            $aOrder['shipping'] = $order->info['shipping_cost'] == null ? 0 : $order->info['shipping_cost'];
            $aOrder['total'] = $order->info['total_value'];
            $aOrder['currency'] = $order->info['currency'];
            $aOrder['paymentMethod'] = $order->info['payment_method'];
            $aOrder['shippingMethod'] = $order->info['shipping_method'];  
            $aOrder['trackingNumber'] = $order_id_result['orders_tracking_number'];  
            $aOrder['shippingCarrier'] = $order_id_result['orders_ship_carrier'];  
  
            $sql_history = "SELECT" .
  		     " osh.date_added," .
               " osh.orders_status_id, os.orders_status_name FROM " 
                 . TABLE_ORDERS_STATUS_HISTORY . " osh, " 
                 . TABLE_ORDERS_STATUS . " os " 
                 . " WHERE osh.orders_status_id = os.orders_status_id"
                 //. " AND date_added > '" . addslashes($timestamp) . "'"
                 . " AND osh.orders_id = " . $order_id_result['orders_id']
                 . " AND os.language_id = " . $languages_id;
                           
            $order_history_query = $abxDatabase->query($sql_history);
  	      while ($order_history_result = $order_history_query->next()) {
  	        $aHistory['name'] = $order_history_result['orders_status_name'];
  	        $aHistory['statusId'] = $order_history_result['orders_status_id'];
              $aHistory['createdOn'] = gmDate("Y-m-d\TH:i:s\Z", strtotime($order_history_result['date_added']));
  	        	        
  	        $aOrder['orderHistory'][] = $aHistory;
  	      }
            
            
            $orders['order'][] = $aOrder;
  	      }
  	    }
      }
      return $orders;
    }
    
    function _getFirstName($name)
    {
      $index = strrpos($name, ' ');
      if($index == false)
        return '';
      
      return substr($name, 0, $index);
       
    }
    
    function _getLastName($name)
    {
      $index = strrpos($name, ' ');
      if($index == false)
        return $name;
      
      return substr($name, $index+1);      
      
    }
    
      function _getStreet1($name)
    {
      $index = strrpos($name, ',');
      if($index == false)
        return $name;
      
      return substr($name, 0, $index);
       
    }
    
    function _getStreet2($name)
    {
      $index = strrpos($name, ',');
      if($index == false)
        return '';
      
      return substr($name, $index+1);      
      
    }

    function _getProductStockQuantity($productId)
    {
      global $abxDatabase;
      
      return $abxDatabase->fetch_value('products_quantity', TABLE_PRODUCTS, 'products_id = ' . (int)$productId);
    }
}  // end class
?>