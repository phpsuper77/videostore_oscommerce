<?php
/*
  $Id: order.php,v 1.33 2003/06/09 22:25:35 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class order {
    var $info, $totals, $products, $customer, $delivery, $content_type;

    function order($order_id = '') {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();

      if (tep_not_null($order_id)) {
        $this->query($order_id);
      } else {
        $this->cart();
      }
    }

    function query($order_id) {
      global $languages_id;

      $order_id = tep_db_prepare_input($order_id);

// purchaseorders_1_4 start
                        // Added purchase_order_number to query.
      $order_query = tep_db_query("select customers_id, customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, currency, currency_value, purchase_order_number, date_purchased, orders_status, last_modified from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
      $order = tep_db_fetch_array($order_query);

      $totals_query = tep_db_query("select title, text from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
      while ($totals = tep_db_fetch_array($totals_query)) {
        $this->totals[] = array('title' => $totals['title'],
                                'text' => $totals['text']);
      }

      $order_total_query = tep_db_query("select text from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_total'");
      $order_total = tep_db_fetch_array($order_total_query);

      $shipping_method_query = tep_db_query("select title from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_shipping'");
      $shipping_method = tep_db_fetch_array($shipping_method_query);

      ###########################ADDED FOR GIFT WRAP###############################
      $giftwrap_method_query = tep_db_query("select title from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $order_id . "' and class = 'ot_giftwrap'");
      $giftwrap_method = tep_db_fetch_array($giftwrap_method_query);
	  #################################################################################

      $order_status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . $order['orders_status'] . "' and language_id = '" . (int)$languages_id . "'");
      $order_status = tep_db_fetch_array($order_status_query);

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
                          'orders_status' => $order_status['orders_status_name'],
                          'last_modified' => $order['last_modified'],
                          'total' => strip_tags($order_total['text']),
                          'giftwrap_method' => ((substr($giftwrap_method['title'], -1) == ':') ? substr(strip_tags($giftwrap_method['title']), 0, -1) : strip_tags($giftwrap_method['title'])),
                          'shipping_method' => ((substr($shipping_method['title'], -1) == ':') ? substr(strip_tags($shipping_method['title']), 0, -1) : strip_tags($shipping_method['title'])));

      $this->customer = array('id' => $order['customers_id'],
                              'name' => $order['customers_name'],
                              'company' => $order['customers_company'],
                              'street_address' => $order['customers_street_address'],
                              'suburb' => $order['customers_suburb'],
                              'city' => $order['customers_city'],
                              'postcode' => $order['customers_postcode'],
                              'state' => $order['customers_state'],
                              'country' => $order['customers_country'],
                              'format_id' => $order['customers_address_format_id'],
                              'telephone' => $order['customers_telephone'],
                              'email_address' => $order['customers_email_address']);

      $this->delivery = array('name' => $order['delivery_name'],
                              'company' => $order['delivery_company'],
                              'street_address' => $order['delivery_street_address'],
                              'suburb' => $order['delivery_suburb'],
                              'city' => $order['delivery_city'],
                              'postcode' => $order['delivery_postcode'],
                              'state' => $order['delivery_state'],
                              'country' => $order['delivery_country'],
                              'format_id' => $order['delivery_address_format_id']);

      if (empty($this->delivery['name']) && empty($this->delivery['street_address'])) {
        $this->delivery = false;
      }

      $this->billing = array('name' => $order['billing_name'],
                             'company' => $order['billing_company'],
                             'street_address' => $order['billing_street_address'],
                             'suburb' => $order['billing_suburb'],
                             'city' => $order['billing_city'],
                             'postcode' => $order['billing_postcode'],
                             'state' => $order['billing_state'],
                             'country' => $order['billing_country'],
                             'format_id' => $order['billing_address_format_id']);

      $index = 0;
      $orders_products_query = tep_db_query("select orders_products_id, products_id, products_name, products_model, products_price, products_tax, products_quantity, final_price from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
      while ($orders_products = tep_db_fetch_array($orders_products_query)) {

      $always_query = tep_db_query("select products_always_on_hand from " . TABLE_PRODUCTS . " where products_id = '" . $orders_products['products_id'] . "'");
      $always = tep_db_fetch_array($always_query);

        $this->products[$index] = array('qty' => $orders_products['products_quantity'],
	                                'id' => $orders_products['products_id'],
                                        'name' => $orders_products['products_name'],
                                        'model' => $orders_products['products_model'],
                                        'tax' => $orders_products['products_tax'],
                                        'price' => $orders_products['products_price'],
                                        'products_always_on_hand' => $always['products_always_on_hand'],
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

        $this->info['tax_groups']["{$this->products[$index]['tax']}"] = '1';

        $index++;
      }
    }

    function cart() {
      global $customer_id, $sendto, $billto, $cart, $languages_id, $currency, $currencies, $shipping, $giftwrap_info, $payment;

      $customer = tep_db_query("select * from vendors where vendors_id='".$_SESSION[vendors_id]."' limit 0, 1");
      $customer_address = tep_db_fetch_Array($customer);

      $this->content_type = $cart->get_content_type();



      $ship_state = tep_db_fetch_array(tep_db_query("select zone_code as st from zones where zone_code='".$_SESSION[vendor][ship_state]."' or zone_name='".$_SESSION[vendor][ship_state]."'"));
      $shipping_address_country = tep_db_fetch_array(tep_db_query("select * from countries where countries_id='".$_SESSION[vendor][ship_country]."'"));
      $shipping_zone = tep_db_fetch_array(tep_db_query("select * from zones where zone_country_id='".$_SESSION[vendor][ship_country]."' and zone_code='".$ship_state[st]."'"));


      $bill_state = tep_db_fetch_array(tep_db_query("select zone_code as st from zones where zone_code='".$_SESSION[vendor][bill_state]."' or zone_name='".$_SESSION[vendor][ship_state]."'"));
      $billing_address_country = tep_db_fetch_array(tep_db_query("select * from countries where countries_id='".$_SESSION[vendor][bill_country]."'"));
      $billing_zone = tep_db_fetch_array(tep_db_query("select * from zones where zone_country_id='".$_SESSION[vendor][bill_country]."' and zone_code='".$bill_state[st]."'"));

      $this->info = array('order_status' => DEFAULT_ORDERS_STATUS_ID,
                          'currency' => 'USD',
                          'currency_value' => '1.00000000',
                          'payment_method' => 'Credit Card',
                          'cc_type' => $_SESSION['vendor'][cc_type],
                          'cc_owner' => $_SESSION['vendor']['cc_owner'],
                          'cc_number' => $_SESSION['vendor']['cc_number'],
                          'cc_expires' => $_SESSION['vendor'][cc_expires_month].$_SESSION['vendor'][cc_expires_year],
                          'purchase_order_number' => $_SESSION['vendor']['purchase_order_number'],
                          'shipping_method' => $_SESSION['shipping']['title'],
                          'shipping_cost' => $_SESSION['shipping']['cost'],
                          'giftwrap_method' => '', //ADDED FOR GIFT WRAP
                          'giftwrap_cost' => '', //ADDED FOR GIFT WRAP
                          'subtotal' => 0,
                          'tax' => 0,
                          'tax_groups' => array(),
                          'comments' => $_SESSION['comments']);

//var_dump($this->info);
//echo "<hr>";
//var_dump($_SESSION[vendor]);
//echo "<hr>";
      $this->customer = array('firstname' => $_SESSION['vendor']['vendors_bill_fname'],
                              'lastname' => $_SESSION['vendor']['vendors_bill_lname'],
                              'company' => $_SESSION['vendor']['vendors_bill_company'],
                              'street_address' => $_SESSION['vendor']['vendors_bill_addr1'],
                              'suburb' => $_SESSION['vendor']['vendors_bill_addr2'],
                              'city' => $_SESSION['vendor']['vendors_bill_city'],
                              'postcode' => $_SESSION['vendor']['vendors_bill_zip'],
                              'state' => $_SESSION['vendor']['bill_state'],
                              'zone_id' => intval($billing_zone['zone_id']),
                              'country' => array('id' => $_SESSION['vendor']['bill_country'], 'title' => $billing_address_country['countries_name'], 'iso_code_2' => $billing_address_country['countries_iso_code_2'], 'iso_code_3' => $billing_address_country['countries_iso_code_3']),
                              'format_id' => '2',
                              'telephone' => $customer_address['vendors_phone1']?$customer_address['vendors_phone1']:$customer_address['vendors_phone2'],
                              'email_address' => $customer_address['po_email_address']);

      $this->delivery = array('firstname' => $_SESSION['vendor']['vendors_ship_fname'],
                              'lastname' => $_SESSION['vendor']['vendors_ship_lname'],
                              'company' => $_SESSION['vendor']['vendors_ship_company'],
                              'street_address' => $_SESSION['vendor']['vendors_ship_addr1'],
                              'suburb' => $_SESSION['vendor']['vendors_ship_addr2'],
                              'city' => $_SESSION['vendor']['vendors_ship_city'],
                              'postcode' => $_SESSION['vendor']['vendors_ship_zip'],
                              'state' => $_SESSION['vendor']['ship_state'],
                              'zone_id' => intval($shipping_zone['zone_id']),
                              'country' => array('id' => $_SESSION['vendor']['ship_country'], 'title' => $shipping_address_country['countries_name'], 'iso_code_2' => $shipping_address_country['countries_iso_code_2'], 'iso_code_3' => $shipping_address_country['countries_iso_code_3']),
                              'country_id' => $_SESSION['vendor']['ship_country'],
                              'format_id' => '2');

      $this->billing = array('firstname' => $_SESSION['vendor']['vendors_bill_fname'],
                             'lastname' => $_SESSION['vendor']['vendors_bill_lname'],
                             'company' => $_SESSION['vendor']['vendors_bill_company'],
                             'street_address' => $_SESSION['vendor']['vendors_bill_addr1'],
                             'suburb' => $_SESSION['vendor']['vendors_bill_addr2'],
                             'city' => $_SESSION['vendor']['vendors_bill_city'],
                             'postcode' => $_SESSION['vendor']['vendors_bill_zip'],
                             'state' => $_SESSION['vendor']['bill_state'],
                             'zone_id' => intval($billing_zone['zone_id']),
                             'country' => array('id' => $_SESSION['vendor']['bill_country'], 'title' => $billing_address_country['countries_name'], 'iso_code_2' => $billing_address_country['countries_iso_code_2'], 'iso_code_3' => $billing_address_country['countries_iso_code_3']),
                             'country_id' => $_SESSION['vendor']['bill_country'],
                             'format_id' => '2');

      $index = 0;
      $products = $cart->get_products();
      for ($i=0, $n=sizeof($products); $i<$n; $i++) {

      $always_query = tep_db_query("select products_out_of_print, products_date_available, products_always_on_hand from " . TABLE_PRODUCTS . " where products_id = '" . $products[$i]['id'] . "'");
      $always = tep_db_fetch_array($always_query);

        $this->products[$index] = array('qty' => $products[$i]['quantity'],
                                        'name' => $products[$i]['name'],
                                        'model' => $products[$i]['model'],
                                        'tax' => tep_get_tax_rate($products[$i]['tax_class_id'], $tax_address['entry_country_id'], $tax_address['entry_zone_id']),
                                        'tax_description' => tep_get_tax_description($products[$i]['tax_class_id'], $tax_address['entry_country_id'], $tax_address['entry_zone_id']),
                                        'price' => '0.01',
                                        'final_price' => '2.00' + $cart->attributes_price($products[$i]['id']),
                                        'products_always_on_hand' => $always['products_always_on_hand'],
                                        'products_date_available' => $always['products_date_available'],
                                        'products_out_of_print' => $always['products_out_of_print'],
                                        'weight' => $products[$i]['weight'],
                                        'id' => $products[$i]['id']);

        if ($products[$i]['attributes']) {
          $subindex = 0;
          reset($products[$i]['attributes']);
          while (list($option, $value) = each($products[$i]['attributes'])) {
            $attributes_query = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . (int)$products[$i]['id'] . "' and pa.options_id = '" . (int)$option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . (int)$value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . (int)$languages_id . "' and poval.language_id = '" . (int)$languages_id . "'");
            $attributes = tep_db_fetch_array($attributes_query);

            $this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options_name'],
                                                                     'value' => $attributes['products_options_values_name'],
                                                                     'option_id' => $option,
                                                                     'value_id' => $value,
                                                                     'prefix' => $attributes['price_prefix'],
                                                                     'price' => $attributes['options_values_price']);

            $subindex++;
          }
        }

        $shown_price = tep_add_tax($this->products[$index]['final_price'], $this->products[$index]['tax']) * $this->products[$index]['qty'];
        $this->info['subtotal'] += $shown_price;

        $products_tax = $this->products[$index]['tax'];
        $products_tax_description = $this->products[$index]['tax_description'];
        if (DISPLAY_PRICE_WITH_TAX == 'true') {
          $this->info['tax'] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
          if (isset($this->info['tax_groups']["$products_tax_description"])) {
            $this->info['tax_groups']["$products_tax_description"] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
          } else {
            $this->info['tax_groups']["$products_tax_description"] = $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
          }
        } else {
          $this->info['tax'] += ($products_tax / 100) * $shown_price;
          if (isset($this->info['tax_groups']["$products_tax_description"])) {
            $this->info['tax_groups']["$products_tax_description"] += ($products_tax / 100) * $shown_price;
          } else {
            $this->info['tax_groups']["$products_tax_description"] = ($products_tax / 100) * $shown_price;
          }
        }

        $index++;
      }
$this->info['total'] = $this->info['subtotal'];
    }
  }
?>