<?php
/*
  $Id: order.php,v 1.7 2003/06/20 16:23:08 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class order {
    var $info, $totals, $products, $customer, $delivery;

    function order($order_id) {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();

      $this->query($order_id);
    }

    function query($order_id) {
// purchaseorders_1_4 start
   // Added purchase_order_number to query.
      $order_query = tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
      $order = tep_db_fetch_array($order_query);
      $totals_query = tep_db_query("select title, text, class from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
      while ($totals = tep_db_fetch_array($totals_query)) {
        $this->totals[] = array('title' => $totals['title'],
                                'text' => $totals['text'],
                                'value' => $totals['value'],
                                'class' => $totals['class']);
      }

      $this->info = array('currency' => $order['currency'],
                          'currency_value' => $order['currency_value'],
                          'payment_method' => $order['payment_method'],
                          'cc_type' => $order['cc_type'],
                          'cc_owner' => $order['cc_owner'],
                          'cc_number' => $order['cc_number'],
                          'cc_expires' => $order['cc_expires'],
// purchaseorders_1_4 start
                          'purchase_order_number' => $order['purchase_order_number'],
// purchaseorders_1_4 end
                          'date_purchased' => $order['date_purchased'],
                          'orders_status' => $order['orders_status'],
                          'comments_slip' => $order['comments_slip'],
                          'last_modified' => $order['last_modified'],
			  'customers_referer_url' => $order['customers_referer_url'],
			  'customers_id' => $order['customers_id'],
                          'iswholesale' => $order['iswholesale']);

      $this->customer = array('name' => stripslashes($order['customers_name']),
                              'company' => stripslashes($order['customers_company']),
                              'street_address' => stripslashes($order['customers_street_address']),
                              'suburb' => stripslashes($order['customers_suburb']),
                              'city' => stripslashes($order['customers_city']),
                              'postcode' => $order['customers_postcode'],
                              'state' => $order['customers_state'],
                              'country' => stripslashes($order['customers_country']),
                              'format_id' => $order['customers_address_format_id'],
                              'telephone' => $order['customers_telephone'],
                              'email_address' => stripslashes($order['customers_email_address']),
                              'ipaddy' => $order['ipaddy'],
			      			  'ipisp' => $order['ipisp']);

      $this->delivery = array('name' => stripslashes($order['delivery_name']),
                              'company' => stripslashes($order['delivery_company']),
                              'street_address' => stripslashes($order['delivery_street_address']),
                              'suburb' => stripslashes($order['delivery_suburb']),
                              'city' => stripslashes($order['delivery_city']),
                              'postcode' => $order['delivery_postcode'],
                              'state' => $order['delivery_state'],
                              'country' => stripslashes($order['delivery_country']),
                              'format_id' => $order['delivery_address_format_id']);

      $this->billing = array('name' => stripslashes($order['billing_name']),
                             'company' => stripslashes($order['billing_company']),
                             'street_address' => stripslashes($order['billing_street_address']),
                             'suburb' => stripslashes($order['billing_suburb']),
                             'city' => stripslashes($order['billing_city']),
                             'postcode' => $order['billing_postcode'],
                             'state' => $order['billing_state'],
                             'country' => stripslashes($order['billing_country']),
                             'format_id' => $order['billing_address_format_id']);

      $index = 0;
      $orders_products_query = tep_db_query("select orders_products_id, back, is_allied, date_shipped, date_shipped_checkbox, returned_reason,returned_reason_checkbox, products_name, products_id, products_model, products_price, products_tax, products_quantity, final_price from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "' order by products_model");
      while ($orders_products = tep_db_fetch_array($orders_products_query)) {
        $this->products[$index] = array('qty' => $orders_products['products_quantity'],
                                        'name' => $orders_products['products_name'],
					'id' => $orders_products['products_id'],
					'ordered_products_id' => $orders_products['orders_products_id'],
                                        'date_shipped' => $orders_products['date_shipped'],
                                        'returned_reason' => $orders_products['returned_reason'],
                                        'date_shipped_checkbox' => $orders_products['date_shipped_checkbox'],
                                        'returned_reason_checkbox' => $orders_products['returned_reason_checkbox'],
                                        'model' => $orders_products['products_model'],
                                        'tax' => $orders_products['products_tax'],
                                        'price' => $orders_products['products_price'],
                                        'back' => $orders_products['back'],
                                        'is_allied' => $orders_products['is_allied'],
                                        'final_price' => $orders_products['final_price']);

        $subindex = 0;
        $attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'");
        if (tep_db_num_rows($attributes_query)) {
          while ($attributes = tep_db_fetch_array($attributes_query)) {
            $this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
                                                                     'value' => $attributes['products_options_values'],
                                                                     'prefix' => $attributes['price_prefix'],
                                                                     'price' => $attributes['options_values_price']);

            $subindex++;
          }
        }
        $index++;
      }
    }
  }
?>