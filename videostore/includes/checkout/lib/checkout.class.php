<?php
/*
 * Dynamo Checkout by Dynamo Effects
 * Copyright 2008 - Dynamo Effects.  All Rights Reserved
 * http://www.dynamoeffects.com
 */
  require_once(CHECKOUT_WS_LIB . 'json.php');
 
  class checkout {
    var $checkout_info;
    var $country_zone_abbreviated;
    var $json;

  	function checkout() {
      /* Countries that use abbreviated state/province names */
      $this->country_zone_abbreviated = array('US','CA','MX','IT','AU','BR');
      
      $this->fix_currency_encoding();

      $country_id = 0;
      
      $this->json = new Services_JSON();
      
      if (strlen(CHECKOUT_COUNTRY_DEFAULT) >= 2) {
        $country_query = tep_db_query("SELECT countries_id FROM " . TABLE_COUNTRIES . " WHERE countries_iso_code_2 = '" . tep_db_input(CHECKOUT_COUNTRY_DEFAULT) . "' LIMIT 1");
        if (tep_db_num_rows($country_query) > 0) {
          $country = tep_db_fetch_array($country_query);
          
          $country_id = $country['countries_id'];
        } else {
          $country_query = tep_db_query("SELECT countries_id FROM " . TABLE_COUNTRIES . " LIMIT 1");
          $country = tep_db_fetch_array($country_query);
          
          $country_id = $country['countries_id'];
        }
      }
      
      define('CHECKOUT_COUNTRY_DEFAULT_ID', $country_id);
      
      $this->copy_table('orders', TABLE_CHECKOUT_ORDERS);
      $this->copy_table('orders_products', TABLE_CHECKOUT_ORDERS_PRODUCTS);
      $this->copy_table('orders_products_attributes', TABLE_CHECKOUT_ORDERS_PRODUCTS_ATTRIBUTES);
      $this->copy_table('orders_products_download', TABLE_CHECKOUT_ORDERS_PRODUCTS_DOWNLOAD);
      $this->copy_table('orders_status_history', TABLE_CHECKOUT_ORDERS_STATUS_HISTORY);
      $this->copy_table('orders_total', TABLE_CHECKOUT_ORDERS_TOTAL);
    }

    /* REQUIRES MySQL 4.1 or greater */
    function copy_table($original_table, $new_table, $syncronize = false) {
      $table_check = tep_db_query("SHOW TABLES LIKE '" . $new_table . "'");
      
      $table_exists = false;
      
      /* If the table exists, make sure all columns are updated */
      if (tep_db_num_rows($table_check) > 0) $table_exists = true;
      
      if (!$table_exists) {
        tep_db_query("CREATE TABLE `" . $new_table . "` LIKE `" . $original_table . "`");
        return true;
      }
      
      if ($table_exists && $syncronize == false) return true;

      $primary_key = '';
      $columns = array();
      $table_orig = array();
      $table_copy = array();

      /* ORIGINAL TABLE */
      $table_info_query = tep_db_query("DESCRIBE " . $original_table);
      
      while ($table_info = tep_db_fetch_array($table_info_query)) {
        $table_orig[$table_info['Field']] = $table_info;
      }

      /* CLONE TABLE */
      $table_info_query = tep_db_query("DESCRIBE " . $new_table);
      
      while ($table_info = tep_db_fetch_array($table_info_query)) {
        $table_copy[$table_info['Field']] = $table_info;
      }
     
      foreach ($table_orig as $column) {
        if (!array_key_exists($column['Field'], $table_copy)) {
          $sql = "ADD `" . $column['Field'] . "` " . $column['Type'];
        } elseif (count($diffs = array_diff($column, $table_copy[$column['Field']])) > 0) {
          /* If the only difference is an index, keep going */
          if (count($diffs) == 1 && array_key_exists('Key', $diffs)) {
            if ($diffs['Key'] == 'MUL') continue;
          }
          
          $sql = "MODIFY `" . $column['Field'] . "` " . $column['Type'];
        } else {
          continue;
        }
        
        if ($column['Null'] == 'YES') {
          $sql .= " default NULL";
        } else {
          $sql .= " NOT NULL";
          if (!is_null($column['Default']) && strlen($column['Default']) > 0) {
            $sql .= " default '" . $column['Default'] . "'";
          }
        }

        if ($column['Extra'] != '') {
          $sql .= ' ' . $column['Extra'];
        }
        
        $columns[] = $sql;
        
        if ($column['Key'] == 'PRI') {
          $primary_key = ' DROP PRIMARY KEY, ADD PRIMARY KEY(`' . $column['Field'] . '`)';
        }
      }
      
      if (count($columns) < 1) return true;
      
      $sql_query = 'ALTER TABLE `' . $new_table . '` ' . implode(',', $columns);

      if ($primary_key != '') $sql_query .= ',' . $primary_key;

      tep_db_query($sql_query);
      
      return true;
    }
    
  	function order_total() {
	

	    global $cart, $currencies, $order, $order_total_modules, $payment_modules, $shipping;
		
			
		
	    if (!tep_session_is_registered('customer_zone_id')) tep_session_register('customer_zone_id');

      if (is_numeric($_POST['s']) && (int)$_POST['s'] > 0) {
        $_SESSION['customer_zone_id'] = $customer_zone_id = (int)$_POST['s'];
      } else {
        $_SESSION['customer_zone_id'] = $customer_zone_id = -1;
      }
      
      if (!tep_session_is_registered('customer_country_id')) tep_session_register('customer_country_id');
      
      $_SESSION['customer_country_id'] = $customer_country_id = (isset($_POST['cn']) && (int)$_POST['cn'] > 0 ? (int)$_POST['cn'] : -1);
	    if (isset($_POST['shipping'])) {
	      $ship = urldecode($_POST['shipping']);
	      
	      if (strpos($ship, '_') !== false) {
	        $ship = explode('_', $ship);
	      } else {
	        $ship = array($ship, '');
	      }


		$shipping = $_SESSION['shipping_quotes'][$ship[0]][$ship[1]];
		
		$shipping['id'] = $ship[0] . '_' . $ship[1];
      }

	    $order = new order;

      $order->delivery['firstname'] = $_POST['fn'];
      $order->delivery['lastname'] = $_POST['ln'];
      $order->delivery['street_address'] = $_POST['a1'];
      $order->delivery['suburb'] = $_POST['a2'];
      $order->delivery['city'] = $_POST['c'];
      $order->delivery['postcode'] = $_POST['pc'];
      
      if (isset($_POST['cn']) && (int)$_POST['cn'] > 0) {
        $order->delivery['country'] = array('id' => (int)$_POST['cn']);
      } else {
        $order->delivery['country'] = array('id' => CHECKOUT_COUNTRY_DEFAULT_ID);
      }

      $country = tep_get_countries($order->delivery['country']['id'], true);
      
      $order->delivery['country'] = array('id' => $order->delivery['country']['id'], 
                                          'title' => $country['countries_name'], 
                                          'iso_code_2' => $country['countries_iso_code_2'], 
                                          'iso_code_3' => $country['countries_iso_code_3']);
                                          
      $order->delivery['country_id'] = $order->delivery['country']['id'];
      
      if ((is_numeric($_POST['s']) && (int)$_POST['s'] > 0) && trim($_POST['s']) != '') {
        $order->delivery['state'] = tep_get_zone_name($order->delivery['country_id'], $_POST['s'], $_POST['s']);
        $order->delivery['zone_id'] = (int)$_POST['s'];
      } else {
        $order->delivery['state'] = (!empty($_POST['s']) ? trim($_POST['s']) : '');
        $order->delivery['zone_id'] = '0';
      }
      $additional_totals = 0;
      
      if (CHECKOUT_CONTRIB_GIFTWRAP) {
        $additional_totals += $order->info['giftwrap_cost'];
      }
      
      if (isset($_POST['payment'])) {
        $payment = $_POST['payment'];
        $GLOBALS['payment'] = $payment;
      }
      
      if ($payment == 'quickpay') {
        if (!is_object($payment_modules)) {
          require_once(DIR_WS_CLASSES . 'payment.php');
          $payment_modules = new payment;
        }

        if (@is_object($GLOBALS[$payment])) {
          if (method_exists($GLOBALS[$payment], 'get_order_fee')) {
            global $quickpay_fee;
            $order->info['payment_method'] = MODULE_PAYMENT_QUICKPAY_TEXT_TITLE;
            $GLOBALS[$payment]->get_order_fee();
          }
        }
      }
      
	    if (isset($_POST['shipping'])) {
        if (isset($_SESSION['shipping_quotes'][$ship[0]][$ship[1]]['module']) && $_SESSION['shipping_quotes'][$ship[0]][$ship[1]]['module'] != '') {
  	      $order->info['shipping_method'] = $this->c($_SESSION['shipping_quotes'][$ship[0]][$ship[1]]['module'] . " (" . 
                                            $_SESSION['shipping_quotes'][$ship[0]][$ship[1]]['title'] . ")");
        
          $cost = $this->clean_float($_SESSION['shipping_quotes'][$ship[0]][$ship[1]]['cost']);
  	      $order->info['shipping_cost'] = tep_add_tax($cost, $_SESSION['shipping_quotes'][$ship[0]][$ship[1]]['tax']);
        }

        if ($order->info['shipping_cost'] > 0) {
          //Add the shipping cost only if the total is missing it
          if (DISPLAY_PRICE_WITH_TAX == 'true') {
            if (round($order->info['subtotal'], 2) + round($additional_totals, 2) + round($order->info['shipping_cost'], 2) == round($order->info['total'], 2) + round($order->info['shipping_cost'], 2)) {
              $order->info['total'] += $order->info['shipping_cost'];
            }
          } else {
            if (round($order->info['tax'], 2) + round($additional_totals, 2) + round($order->info['subtotal'], 2) + round($order->info['shipping_cost'], 2) == round($order->info['total'], 2) + round($order->info['shipping_cost'], 2)) {
              $order->info['total'] += $order->info['shipping_cost'];
            }
          }

          if (file_exists(DIR_WS_MODULES . 'shipping/' . $ship[0] . '.php')) {
            include_once(DIR_WS_MODULES . 'shipping/' . $ship[0] . '.php');
            $sm = new $ship[0];

            if ($sm->tax_class > 0) {
              $shipping_tax = tep_get_tax_rate($sm->tax_class, $customer_country_id, $customer_zone_id);
              $shipping_tax_description = tep_get_tax_description($sm->tax_class, $customer_country_id, $customer_zone_id);

              $order->info['tax'] += tep_calculate_tax($cost, $shipping_tax);
              $order->info['tax_groups']["$shipping_tax_description"] += tep_calculate_tax($cost, $shipping_tax);
              $order->info['total'] += tep_calculate_tax($cost, $shipping_tax);
            }

          }
        }
	    }

	    if (CHECKOUT_CONTRIB_CCGV) {
        if (@method_exists($order_total_modules, 'pre_confirmation_check')) {
          $order_total_modules->pre_confirmation_check();
        } else {
          $this->log_error('CCGV support is enabled, but does not appear to be installed.', false);
        }
	    }
      
      if (CHECKOUT_CONTRIB_POINTS_AND_REWARDS) {
        if (tep_session_is_registered('customer_shopping_points_spending')) {
          $_POST['customer_shopping_points_spending'] = $_SESSION['customer_shopping_points_spending'];
        }
      }

	    $order_totals = $order_total_modules->process();

	    $order->totals = $order_totals;
      
      for ($x = 0; $x < count($order->totals); $x++) {
        $order->totals[$x]['text'] = $this->c($order->totals[$x]['text']);
      }
      
      echo $this->json->encode($order->totals);
  	}
  	
    function get_zones($c = '', $n = '', $d = 0, $return_output = false) {
	    global $_SESSION, $order;

	    $country_id = (int)$c;
	    $zones_array = array(array('id' => '', 'text' => ''));
      
	    if ($country_id < 1) $country_id = CHECKOUT_COUNTRY_DEFAULT_ID;
	    
	    if ($country_id > 0) {
        $country_code = '';
        $country_query = tep_db_query(
          "SELECT countries_iso_code_2 " .
          "FROM " . TABLE_COUNTRIES . " " .
          "WHERE countries_id = " . (int)$country_id . " " .
          "LIMIT 1"
        );
                                       
        if (tep_db_num_rows($country_query) > 0) {
          $country = tep_db_fetch_array($country_query);
          
          $country_code = $country['countries_iso_code_2'];
        }

        /* Display the State/Province field depending on store owner's preference. */
        if (CHECKOUT_STATE_ABBREVIATION == 'full') {
          $column = 'zone_name';
        } elseif (CHECKOUT_STATE_ABBREVIATION == 'short') {
          $column = 'zone_code as zone_name';
        } else {
          if ($country_code != '' && in_array($country_code, $this->country_zone_abbreviated)) {
            $column = 'zone_code as zone_name';
          } else {
            $column = 'zone_name';
          }
        }

	      $zones_query = tep_db_query(
          "SELECT zone_id, " .
          "   " . $column . " " .
          "FROM " . TABLE_ZONES . " " .
          "WHERE zone_country_id = " . $country_id . " " .
          "ORDER BY zone_name"
        );

	      if (tep_db_num_rows($zones_query)) {
        
	        if (!tep_session_is_registered('customer_country_id')) tep_session_register('customer_country_id');
          
	        $_SESSION['customer_country_id'] = $country_id;
	        
	        while ($zones_values = tep_db_fetch_array($zones_query)) {
	          $zones_array[] = array(
              'id' => $zones_values['zone_id'], 
              'text' => htmlentities($zones_values['zone_name'])
            );
	        }
	      }
	    }
      
      if ($return_output) {
        return $zones_array;
      } else {
        echo $this->json->encode(array('zones' => $zones_array));
      }
	  }
    
    function display_zones($c = '', $n = '', $d = 0) {
      $zones = $this->get_zones($c, $n, $d, true);
      
      if (count($zones) > 1) {
	      echo tep_draw_pull_down_menu($n, $zones, $d, 'class="checkout-select" id="' . $n . '" onchange="Checkout.GetPaymentShipping()"');
	    } else {
	      echo tep_draw_input_field($n, $d, 'class="checkout-input-text" id="' . $n . '" onkeyup="Checkout.TimeoutGetPaymentShipping()"');
      }
    }
    
    function verify_coupon($coupon, $shipping) {
      global $_SESSION, $currencies, $order, $customer_id, $order_total_modules;

      if (CHECKOUT_CONTRIB_DISCOUNT_COUPON) {
        $coupon_found = false;
        
        if (!tep_session_is_registered('coupon')) tep_session_register('coupon');
        
        if ($coupon != '') {
          $_SESSION['coupon'] = $coupon;
        }

        if( !is_object( $order->coupon ) ) {
          include_once( DIR_WS_CLASSES . 'discount_coupon.php' );
          $order->coupon = new discount_coupon( $coupon, $this->delivery );
          if (method_exists($order->coupon, 'total_valid_products')) {
            $order->coupon->total_valid_products( $products );
          }
      	}
        
        $order->coupon->verify_code();
        
        if( MODULE_ORDER_TOTAL_DISCOUNT_COUPON_DEBUG != 'true' ) {
          if( !$order->coupon->is_errors() ) {
            $coupon_found = true;
          } else {
            if( tep_session_is_registered('coupon') ) tep_session_unregister('coupon');
          }
        } else {
          //If debug mode is turned on, accept all valid coupons
          $coupon_found = true;
        }
        
      	if ($coupon_found) {
      	  return true;
      	} else {
          if (method_exists($order->coupon, 'get_messages')) {
            $error_message = implode( ' ', $order->coupon->get_messages() );
          }
          if (trim($error_message) == '') {
            $error_message = ENTRY_DISCOUNT_COUPON_ERROR;
          }
      	  return $error_message;
      	}
      } elseif (CHECKOUT_CONTRIB_CCGV) {
        $_POST['gv_redeem_code'] = $coupon;

      	$coupon_query = tep_db_query(
          "SELECT coupon_id, 
                 coupon_amount, 
                 coupon_type, 
                 coupon_minimum_order,
                 uses_per_coupon, 
                 uses_per_user, 
                 coupon_start_date, 
                 coupon_expire_date, 
                 restrict_to_products,
                 restrict_to_categories 
          FROM " . TABLE_COUPONS . " 
          WHERE coupon_start_date <= now() 
            AND coupon_expire_date >= now() 
            AND coupon_code = '" . tep_db_prepare_input($coupon) . "' 
            AND coupon_active = 'Y'"
        );

        if (tep_db_num_rows($coupon_query) < 1) {
          return ERROR_NO_INVALID_REDEEM_COUPON;
        }

        $coupon_result = tep_db_fetch_array($coupon_query);

      	$coupon_count = tep_db_query("SELECT coupon_id 
                                      FROM " . TABLE_COUPON_REDEEM_TRACK . " 
                                      WHERE coupon_id = '" . $coupon_result['coupon_id']."'");

      	$coupon_count_customer = tep_db_query("SELECT coupon_id 
                                               FROM " . TABLE_COUPON_REDEEM_TRACK . " 
                                               WHERE coupon_id = '" . $coupon_result['coupon_id'] . "' 
                                                 AND customer_id = '" . (int)$customer_id . "'");

      	if (tep_db_num_rows($coupon_count) >= $coupon_result['uses_per_coupon'] && $coupon_result['uses_per_coupon'] > 0) {
      		return ERROR_INVALID_USES_COUPON . $coupon_result['uses_per_coupon'];
      	}

      	if (tep_db_num_rows($coupon_count_customer) >= $coupon_result['uses_per_user'] && $coupon_result['uses_per_user'] > 0) {
      		return ERROR_INVALID_USES_USER_COUPON;
      	}

      	if ($coupon_result['coupon_type'] == 'S') {
      		$coupon_amount = $order->info['shipping_cost'];
      	} else {
      		$coupon_amount = $currencies->format($coupon_result['coupon_amount']) . ' ';
      	}

      	if ($coupon_result['coupon_type']=='P') $coupon_amount = $coupon_result['coupon_amount'] . '% ';
      	if ($coupon_result['coupon_minimum_order']>0) $coupon_amount .= 'on orders greater than ' . $coupon_result['coupon_minimum_order'];
      	if (!tep_session_is_registered('cc_id')) tep_session_register('cc_id');

      	$_SESSION['cc_id'] = $coupon_result['coupon_id'];
      	$cc_id = $coupon_result['coupon_id'];

      	$order_total_modules->pre_confirmation_check();
      	$order_total_modules->process();
      	$coupon_found = false;

      	foreach ($order_total_modules->modules as $ot) {
      	  if ($ot == 'ot_coupon.php') {
      	    $coupon_found = true;
      	    break;
      	  }
      	}
      	
      	if ($coupon_found) {
      	  return true;
      	} else {
      	  return ERROR_NO_INVALID_REDEEM_COUPON;
      	}
    	
      	if ($_POST['submit_redeem_coupon_x'] && !$coupon) 
          return ERROR_NO_REDEEM_CODE;
      }
    }
    
    function apply_coupon() {
      global $cart, $currencies, $order;
      
      if (trim($_POST['coupon']) == '') return false;
      $shipping = array('id' => (strpos($_POST['shipping'], '_') !== false ? $_POST['shipping'] : ''));
      
      require_once(DIR_WS_CLASSES . 'order_total.php');
      require_once(DIR_WS_CLASSES . 'order.php');

      if (tep_session_is_registered('coupon')) tep_session_unregister('coupon');
      tep_session_register('coupon');

      $coupon = $_POST['coupon'];
      $_SESSION['coupon'] = $coupon;
      
      $order = new order;

      $ret = $this->verify_coupon($coupon, $shipping);

      if ($ret === true) {
        echo $this->json->encode(array(
          "status" => 1,
          "text" => CHECKOUT_COUPON_SUCCESS . ' <a href="javascript:void(0);" onclick="Checkout.RemoveCoupon();">' . CHECKOUT_COUPON_REMOVE . '</a>'
        ));
      } else {
        echo $this->json->encode(array(
          "status" => 0,
          "text" => $ret
        ));
      }
    }
    
    function remove_coupon() {
      global $order;
      
      if (tep_session_is_registered('coupon')) tep_session_unregister('coupon');
      if (tep_session_is_registered('cc_id')) tep_session_unregister('cc_id');
      
      echo '{}';
    }
    
    function apply_gift_card($code = '') {
      $code = trim($code);
      
      if ($code == '') return false;

      $gift_card_check = tep_db_query(
        "SELECT * " .
        "FROM " . TABLE_GIFT_CARDS . " " .
        "WHERE gift_cards_code = '" . tep_db_prepare_input($code) . "' " . 
        "  AND gift_cards_enabled='1'"
      );
        
        
      if (tep_db_num_rows($gift_card_check) > 0) {
        $_SESSION['gift_card'] = $code;
        
        echo $this->json->encode(array(
          "status" => 1,
          "text" => CHECKOUT_GIFT_CARD_SUCCESS . ' <a href="javascript:void(0);" onclick="Checkout.RemoveGiftCard();">' . CHECKOUT_GIFT_CARD_REMOVE . '</a>'
        ));

      } else {
        echo $this->json->encode(array(
          "status" => 0,
          "text" => CHECKOUT_GIFT_CARD_FAILURE
        ));
      }
    }
    
    function remove_gift_card() {
      unset($_SESSION['gift_card']);
      echo '{}';
    }
    
    function update_gift_card_balance($code = '') {
      global $order;
      
      $code = trim($code);
      
      if ($code == '') return false;

      $gift_card_query = tep_db_query(
        "SELECT gift_cards_amount_remaining, gift_cards_id " .
        "FROM " . TABLE_GIFT_CARDS . " " .
        "WHERE gift_cards_code = '" . tep_db_prepare_input($code) . "' " . 
        "  AND gift_cards_enabled='1'"
      );
        
      if (tep_db_num_rows($gift_card_query) > 0) {
        $gift_card_row = tep_db_fetch_array($gift_card_query);
        $remaining_amount = $gift_card_row['gift_cards_amount_remaining'];

        if ($remaining_amount > 0) {
          if ($order->info['total'] > $remaining_amount) {
            $remaining_amount = 0;
          } else {
            $remaining_amount -= $order->info['total'];
          }
        }

        tep_db_query(
          "UPDATE " . TABLE_GIFT_CARDS . " " .
          "SET gift_cards_amount_remaining = " . $remaining_amount . "," .
          "    gift_cards_enabled = " . ($remaining_amount > 0 ? '1' : '0') . " " .
          "WHERE gift_cards_id = " . (int)$gift_card_row['gift_cards_id']
        );
      }
      
      unset($_SESSION['gift_card']);
    }
    function apply_points($status = 0) {
      global $order;

      if (CHECKOUT_CONTRIB_POINTS_AND_REWARDS && USE_POINTS_SYSTEM == 'true' && USE_REDEEM_SYSTEM == 'true') {
        if (!$status) {
          if (tep_session_is_registered('customer_shopping_points_spending')) tep_session_unregister('customer_shopping_points_spending');
        } else {
      
      
          $customer_shopping_points = tep_get_shopping_points();
          
          $enable = $status;
          
          if (tep_calc_shopping_pvalue($customer_shopping_points) < $order->info['total']) {
            if (tep_session_is_registered('customer_shopping_points_spending')) tep_session_unregister('customer_shopping_points_spending');
            $_SESSION['customer_shopping_points_spending'] = $customer_shopping_points;
            $customer_shopping_points_spending = $customer_shopping_points;
          } else {
            if (tep_session_is_registered('customer_shopping_points_spending')) tep_session_unregister('customer_shopping_points_spending');
          }
        }
      }
      
      echo $this->json->encode(array());
    }
    
    function apply_giftwrap($type) {
      global $order;
      

      if (!tep_session_is_registered('giftwrap_info')) tep_session_register('giftwrap_info');


      if ($type == 'giftwrap_giftwrap' || $type == 'nogiftwrap_nogiftwrap') {
      
        if ($type == 'nogiftwrap_nogiftwrap') {
          tep_session_unregister('giftwrap_info');
          unset($order->info['giftwrap_method']);
          $order->info['giftwrap_cost'] = 0;
        }
        
        require(DIR_WS_CLASSES . 'gift.php');
        $giftwrap_modules = new gift($type);
        
        
        
        if (tep_count_giftwrap_modules() > 0) {

          list($module, $method) = explode('_', $type);
        
          $quote1 = $giftwrap_modules->quote1($module, $method);

          if (isset($quote1['error'])) {
            tep_session_unregister('giftwrap_info');
          } else {
            if ( (isset($quote1[0]['methods'][0]['title'])) && (isset($quote1[0]['methods'][0]['cost'])) ) {
              $_SESSION['giftwrap_info'] = array('id' => $type,
                                                 'title' => $quote1[0]['module'] . ' (' . $quote1[0]['methods'][0]['title'] . ')',
                                                 'cost' => $quote1[0]['methods'][0]['cost']);
                                                 
              $giftwrap_info =& $_SESSION['giftwrap_info'];
            }
          }

        }
      } else {
        $giftwrap_info = false;
      }
      echo json_encode($order);
      //echo '{"type":"' . $type . '"}';
    }
    
    function fix_currency_encoding() {
      global $currencies;
      
      $currency_codes = array(
        'AFN' => '&#1547;','ALL' => '&#76;','ANG' => '&#402;','ARS' => '$','AUD' => '$',
        'AWG' => '&#402;','AZN' => '&#1084;','BAM' => '&#75;','BBD' => '$','BGN' => '&#1083;',
        'BMD' => '$','BND' => '$','BOB' => '$','BRL' => '&#82;','BSD' => '$',
        'BWP' => '&#80;','BYR' => '&#112;','BZD' => '&#66;','CAD' => '$','CHF' => '&#67;',
        'CLP' => '$','CNY' => '&#165;','COP' => '$','CRC' => '&#8353;','CUP' => '&#8369;',
        'CZK' => '&#75;','DOP' => '&#82;','EEK' => '&#107;','EGP' => '&#163;',
        'EUR' => '&euro;','FJD' => '$','FKP' => '&#163;','GBP' => '&#163;','GGP' => '&#163;',
        'GHC' => '&#162;','GIP' => '&#163;','GTQ' => '&#81;','GYD' => '$','HKD' => '$',
        'HNL' => '&#76;','HRK' => '&#107;','HUF' => '&#70;','IDR' => '&#82;','ILS' => '&#8362;',
        'IMP' => '&#163;','INR' => '&#8360;','IRR' => '&#65020;','ISK' => '&#107;','JEP' => '&#163;',
        'JMD' => '&#74;','JPY' => '&#165;','KGS' => '&#1083;','KHR' => '&#6107;','KPW' => '&#8361;',
        'KYD' => '$','KZT' => '&#1083;','LAK' => '&#8365;','LBP' => '&#163;','LKR' => '&#8360;',
        'LRD' => '$','LTL' => '&#76;','LVL' => '&#76;','MKD' => '&#1076;','MNT' => '&#8366;',
        'MUR' => '&#8360;','MXN' => '$','MYR' => '&#82;','MZN' => '&#77;','NAD' => '$',
        'NGN' => '&#8358;','NIO' => '&#67;','NOK' => '&#107;','NPR' => '&#8360;','NZD' => '$',
        'OMR' => '&#65020;','PAB' => '&#66;','PEN' => '&#83;','PHP' => '&#80;','PKR' => '&#8360;',
        'PLN' => '&#122;','PYG' => '&#71;','QAR' => '&#65020;','RON' => '&#108;','RSD' => '&#1044;',
        'RUB' => '&#1088;','SAR' => '&#65020;','SBD' => '$','SCR' => '&#8360;',
        'SGD' => '$','SHP' => '&#163;','SOS' => '&#83;','SRD' => '$','SVC' => '$',
        'SYP' => '&#163;','THB' => '&#3647;','TRL' => '&#8356;','TRY' => '&#84;','TTD' => '&#84;',
        'TVD' => '$','TWD' => '&#78;','UAH' => '&#8372;','USD' => '$','UYU' => '$',
        'UZS' => '&#1083;','VEF' => '&#66;','VND' => '&#8363;','XCD' => '$','YER' => '&#65020;',
        'ZAR' => '&#82;','ZWD' => '&#90;'
      );
      
      $currency_array = $currencies->currencies;
      
      foreach ($currencies->currencies as $id => $data) {
        if (array_key_exists($id, $currency_codes)) {
        
          if (tep_not_null($currency_array[$id]['symbol_left']) && !preg_match('/([A-Z]{3})/', strtoupper($currency_array[$id]['symbol_left']))) {
            $currency_array[$id]['symbol_left'] = $currency_codes[$id];
          }
          
          if (tep_not_null($currency_array[$id]['symbol_right']) && !preg_match('/([A-Z]{3})/', strtoupper($currency_array[$id]['symbol_right']))) {
            $currency_array[$id]['symbol_right'] = $currency_codes[$id];
          }
        }
      }
      
      $currencies->currencies = $currency_array;
      
      return false;
    }
    
    function get_payment_shipping($json_output = true) {
      global $order, $cart, $currency, $currencies, $language, $payment_modules, $checkout_payment_disable, $shipping_modules;
	  
	  $output = array();

      if (!tep_session_is_registered('customer_zone_id')) tep_session_register('customer_zone_id');

      $_SESSION['customer_zone_id'] = (is_numeric($_POST['bs']) && (int)$_POST['bs'] > 0 ? (int)$_POST['bs'] : '');
      $customer_zone_id = $_SESSION['customer_zone_id'];
      $customer_country_id = (isset($_POST['bcn']) && (int)$_POST['bcn'] > 0 ? (int)$_POST['bcn'] : '');
      require_once(DIR_WS_CLASSES . 'order.php');
      require_once(DIR_WS_CLASSES . 'shipping.php');

      $order = new order;
      
      $order->delivery['firstname'] = $_POST['sfn'];
      $order->delivery['lastname'] = $_POST['sln'];
      $order->delivery['street_address'] = $_POST['sa1'];
      $order->delivery['suburb'] = $_POST['sa2'];
      $order->delivery['city'] = $_POST['sc'];
      $order->delivery['postcode'] = $_POST['spc'];
      
      if (isset($_POST['scn']) && (int)$_POST['scn'] > 0) {
        $order->delivery['country'] = array('id' => (int)$_POST['scn']);
      } else {
        $order->delivery['country'] = array('id' => CHECKOUT_COUNTRY_DEFAULT_ID);
      }

      $country = tep_get_countries($order->delivery['country']['id'], true);
      
      $order->delivery['country'] = array('id' => $order->delivery['country']['id'], 
                                          'title' => $country['countries_name'], 
                                          'iso_code_2' => $country['countries_iso_code_2'], 
                                          'iso_code_3' => $country['countries_iso_code_3']);
                                          
      $order->delivery['country_id'] = $order->delivery['country']['id'];
      
      if (is_numeric($_POST['ss']) && $_POST['ss'] != '') {
        $order->delivery['state'] = tep_get_zone_name($order->delivery['country_id'], $_POST['ss'], $_POST['ss']);
        $order->delivery['zone_id'] = (int)$_POST['ss'];
      } else {
        $order->delivery['state'] = $_POST['ss'];
        $order->delivery['zone_id'] = '0';
      }
      
      $order->billing['firstname'] = $_POST['bfn'];
      $order->billing['lastname'] = $_POST['bln'];
      $order->billing['street_address'] = $_POST['ba1'];
      $order->billing['suburb'] = $_POST['ba2'];
      $order->billing['city'] = $_POST['bc'];
      $order->billing['postcode'] = $_POST['bpc'];
      
      if (isset($_POST['bcn']) && (int)$_POST['bcn'] > 0) {
        $order->billing['country'] = array('id' => (int)$_POST['bcn']);
      } else {
        $order->billing['country'] = array('id' => CHECKOUT_COUNTRY_DEFAULT_ID);
      }

      $country = tep_get_countries($order->billing['country']['id'], true);
      
      $order->billing['country'] = array('id' => $order->billing['country']['id'], 
                                          'title' => $country['countries_name'], 
                                          'iso_code_2' => $country['countries_iso_code_2'], 
                                          'iso_code_3' => $country['countries_iso_code_3']);
                                          
      $order->billing['country_id'] = $order->billing['country']['id'];
      
      if (is_numeric($_POST['bs']) && $_POST['bs'] != '') {
        $order->billing['state'] = tep_get_zone_name($order->billing['country_id'], $_POST['bs'], $_POST['bs']);
        $order->billing['zone_id'] = (int)$_POST['bs'];
      } else {
        $order->billing['state'] = $_POST['bs'];
        $order->billing['zone_id'] = '0';
      }
      
      $order->customer = $order->billing;

      $shipping_modules = new shipping;
      $free_shipping = false;
      
      if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
        $pass = false;

        switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
          case 'national':
            if ($order->delivery['country_id'] == STORE_COUNTRY) $pass = true;
            break;
          case 'international':
            if ($order->delivery['country_id'] != STORE_COUNTRY) $pass = true;
            break;
          case 'both':
            $pass = true;
            break;
        }

        if ($pass && $order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) {
          $free_shipping = true;

          include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
        }
      }
      
      if (!tep_session_is_registered('shipping_quotes')) tep_session_register('shipping_quotes');
      
      $_SESSION['shipping_quotes'] = array();

      if ($free_shipping) {
        $quotes = array(
          array(
            'id' => 'free',
            'module' => FREE_SHIPPING_TITLE,
            'methods' => array(array(
              'id' => 'free',
              'title' => sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)),
              'cost' => 0,
              'tax' => 0
            )),
            'cheapest' => 1
          )
        );
      } else
	   {
	 	 	//echo "else";
        $quotes = $shipping_modules->quote();
			
      }
      
      $radio_buttons = 0;

      $cheapest = array('title' => '', 'cost' => '');
      $output = '';
      $shipping_options = array();

      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
      
        $_SESSION['shipping_quotes'][$quotes[$i]['id']] = array();

        $shipping_options[$i] = $quotes[$i];

        if (!isset($quotes[$i]['error'])) {
          $shipping_options[$i]['error'] = '';
          
          for ($j=0, $n2 = count($quotes[$i]['methods']); $j<$n2; $j++)
		   {
		   	
		  	$shipping_options[$i]['methods'][$j]['cheapest'] = 0;
            
            $_SESSION['shipping_quotes'][$quotes[$i]['id']][$quotes[$i]['methods'][$j]['id']] = array(
              'id' => $quotes[$i]['methods'][$j]['id'],
              'module' => $quotes[$i]['module'],
              'title' => $this->c($quotes[$i]['methods'][$j]['title']),
              'cost' => $quotes[$i]['methods'][$j]['cost'],
              'tax' => $quotes[$i]['tax']
            );

            if ($_SESSION['shipping_quotes'][$quotes[$i]['id']][$quotes[$i]['methods'][$j]['id']]['cost'] < $cheapest['cost'] || $cheapest['title'] == '') {
              $cheapest_method = array($i, $j);
              $cheapest['title'] = $_SESSION['shipping_quotes'][$quotes[$i]['id']][$quotes[$i]['methods'][$j]['id']]['title'];
              $cheapest['cost'] = $_SESSION['shipping_quotes'][$quotes[$i]['id']][$quotes[$i]['methods'][$j]['id']]['cost'];
            }

            $cost = $quotes[$i]['methods'][$j]['cost'];
            if (DISPLAY_PRICE_WITH_TAX == 'true') $cost += tep_calculate_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax']);
            $shipping_options[$i]['methods'][$j]['cost'] = $currencies->format($cost);
          }
          
          
        }
      }

      
      $shipping_options[$cheapest_method[0]]['methods'][$cheapest_method[1]]['cheapest'] = 1;

      $order->info['shipping_method'] = $cheapest['title'];;
      $order->info['shipping_cost'] = $cheapest['cost'];
      
      if (!is_object($payment_modules)) {
        require_once(DIR_WS_CLASSES . 'payment.php');
        $payment_modules = new payment;
      }

      $payment_options_unfiltered = $payment_modules->selection();
      $payment_options = array();
      
      if (count($payment_options_unfiltered) > 0) {
        foreach ($payment_options_unfiltered as $pou) {
          if (!in_array($pou['id'], (array)$checkout_payment_disable)) {
            $option = $pou;
            
            if (!is_array($pou['fields'])) {
              $fields = $GLOBALS[$option['id']]->confirmation();
              
              if (is_array($fields['fields'])) {
                $option['fields'] = $fields['fields'];
              } elseif (isset($fields['title'])) {
                $option['fields'] = array($fields);
              } 
            }

            if ($option['id'] == 'quickpay') {
              $pos = strpos($option['module'], '<script');
              
              if ($pos !== false) {
                $option['module'] = substr($option['module'], 0, $pos);
                
                $option['module'] .= '<script language="javascript"><!-- 
                try {
                  function setQuickPay() { Checkout.UpdateOrderTotal(); return false; }
                  function selectQuickPayRowEffect(object, buttonSelect) { return false; }
                } catch(e) {}
                //--></script>';
              }
            }

            $payment_options[] = $option;
            
/*
            if ($option['id'] == 'paypal_wpp' && MODULE_PAYMENT_PAYPAL_DP_BUTTON_PAYMENT_METHOD == 'Yes') {
              $option = array(
                'id' => 'paypal_wpp_ec',
                'module' => MODULE_PAYMENT_PAYPAL_EC_TEXT_TITLE,
                'fields' => array('title' => '<span class="main">' . TEXT_PAYPALWPP_EC_HEADER . '</span>')
              );
              $payment_options[] = $option;
            }
            */
          }
        }
      }
      
      if (count($payment_options) > 0) {
        foreach ($payment_options as $payment_option) {
          if (file_exists(DIR_WS_LANGUAGES . $language . '/modules/payment/' . $payment_option['id'] . '.php')) {
            @include(DIR_WS_LANGUAGES . $language . '/modules/payment/' . $payment_option['id'] . '.php');
          }
        }
      }
      
      $output['shipping'] = $shipping_options;
      $output['payment'] = $payment_options;
      
      if ($json_output) {
        echo $this->json->encode($output);
        return true;
      }
      
      return $output;
    }
    
    function get_cart($return_output = false) {
      global $languages_id, $cart, $currencies;

      $output = array();
      
      $any_out_of_stock = 0;
      $products = $cart->get_products();
	  
	 
	  
	  
      $total_products = count($products);
      
      if ($total_products > 0) {
        foreach ($products as $product) {
          $product_output = array();

          $image_query = tep_db_query(
            "SELECT products_image " .
            "FROM " . TABLE_PRODUCTS . " " .
            "WHERE products_id = " . (int)$product['id'] . " " .
            "LIMIT 1"
          );
          
          $image = tep_db_fetch_array($image_query);
          
          $product_output = array(
            'id' => $product['id'],
            'name' => $this->c($product['name']),
			'distribution' => $this->c($product['distribution']),
            'link' => tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['id']),
            'model' => $this->c($product['model']),
            'qty' => $product['quantity'],
            'tax_class_id' => $product['tax_class_id'],
            'image' => DIR_WS_IMAGES . $image['products_image'],
            'in_stock' => (tep_not_null(tep_check_stock($product['id'], $product['quantity'])) ? '1' : '0'),
            'unit_price' => $currencies->display_price($product['final_price'], tep_get_tax_rate($product['tax_class_id'])),
            'final_price' => $currencies->display_price($product['final_price'], tep_get_tax_rate($product['tax_class_id']), $product['quantity']),
            'attributes' => array()
          );

          if (isset($product['attributes']) && is_array($product['attributes'])) {
            foreach ($product['attributes'] as $option => $value) {
              $attributes_query = tep_db_query(
                "SELECT popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix " .
                "FROM " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa " .
                "WHERE pa.products_id = '" . (int)$product['id'] . "' " .
                "  AND pa.options_id = '" . (int)$option . "' " .
                "  AND pa.options_id = popt.products_options_id " .
                "  AND pa.options_values_id = '" . (int)$value . "' " .
                "  AND pa.options_values_id = poval.products_options_values_id " .
                "  AND popt.language_id = '" . (int)$languages_id . "' " .
                "  AND poval.language_id = '" . (int)$languages_id . "' " .
                "LIMIT 1"
              );
              
              $attributes_values = tep_db_fetch_array($attributes_query);
              
              if (CHECKOUT_CONTRIB_OPTIONS_TYPE) {
                if ($value == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {                       

                  $attr_value = $product['attributes_values'][$option];
                  $attr_name_sql_raw = 'SELECT po.products_options_name FROM ' .
                    TABLE_PRODUCTS_OPTIONS . ' po, ' .
                    TABLE_PRODUCTS_ATTRIBUTES . ' pa WHERE ' .
                    ' pa.products_id="' . tep_get_prid($product['id']) . '" AND ' .
                    ' pa.options_id="' . $option . '" AND ' .
                    ' pa.options_id=po.products_options_id AND ' .
                    ' po.language_id="' . $languages_id . '" ';
                  $attr_name_sql = tep_db_query($attr_name_sql_raw);
                  if ($arr = tep_db_fetch_array($attr_name_sql)) {
                    $attr_name  = $arr['products_options_name'];
                  }
                  
                } else {
                  $attr_value = $attributes_values['products_options_values_name'];
                  $attr_name  = $attributes_values['products_options_name'];
                }
              } else {
                $attr_name = $attributes_values['products_options_name'];
                $attr_value =  $attributes_values['products_options_values_name'];
              }
              
              $product_output['attributes'][] = array(
                'option_name' => $this->c($attr_name),
                'option_id' => $option,
                'value_name' => $this->c($attr_value),
                'value_id' => $value,
                'price' => $attributes_values['price_prefix'] . $currencies->display_price($attributes_values['options_values_price'], 0),
              );
            }
          }
          
          $output[] = $product_output;
        }
      }
      
      $output = array('cart' => $output);
      
      if ($return_output)
        return $output;
      else
        echo $this->json->encode($output);
    }
    
    function update_cart() {
      global $cart;
      
      if ($cart->get_products() > 0) {     
        for ($i=0, $n=sizeof($_POST['products_id']); $i<$n; $i++) {
        
          /* Get 1 Free Support */
          if (CHECKOUT_CONTRIB_GET_1_FREE === true && $_POST['free'][$i] == 1) {
            continue;
          }
          
          $quantity = (int)$_POST['cart_quantity'][$i];
          $out_of_stock = false;
          if (STOCK_CHECK == 'true') {
            if (tep_check_stock($_POST['products_id'][$i], $quantity)) {
              $out_of_stock = true;
            }
            
            if (STOCK_ALLOW_CHECKOUT != 'true' && $out_of_stock === true) {
              $quantity_query = tep_db_query(
                "SELECT products_quantity as qty " .
                "FROM " . TABLE_PRODUCTS . " " .
                "WHERE products_id = " . (int)$_POST['products_id'][$i] . " " .
                "LIMIT 1"
              );
              
              if (tep_db_num_rows($quantity_query) > 0) {
                $q = tep_db_fetch_array($quantity_query);
                
                $quantity = ($q['qty'] >= 0 ? $q['qty'] : 0);
              }
            }
          }
        
          if (in_array($_POST['products_id'][$i], (is_array($_POST['cart_delete']) ? $_POST['cart_delete'] : array()))) {
            $cart->remove($_POST['products_id'][$i]);
          } else {
            $attributes = ($_POST['id'][$_POST['products_id'][$i]]) ? $_POST['id'][$_POST['products_id'][$i]] : '';
            $cart->add_cart($_POST['products_id'][$i], $quantity, $attributes, false);
          }
        }
      }
      
      if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
      $_SESSION['cartID'] = $cart->cartID;
  
      if ($cart->get_products() > 0) {
        $json = $this->json->encode($this->get_cart(true));
        $output = 'var cart = ' . $json . ';parent.Checkout.UpdateCart(cart);';
      } else {
        $output = 'parent.location.href = "' . tep_href_link(FILENAME_REAL_SHOPPING_CART) . '";';
      }
      echo '<script type="text/javascript">' . $output . '</script>';
    }
    
    function remove_product() {
      global $cart;

      $cart->remove($_POST['id']);
      
      if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
      $_SESSION['cartID'] = $cart->cartID;
      
      $this->get_cart();
    }
    
    function get_cart_table() {
	
	
	
      global $languages_id, $cart, $currencies;
      
?>
<table class="productListing" border="0" cellpadding="2" cellspacing="1" width="100%">
  <tbody>
    <tr>
      <td class="productListing-heading" width="120"><?php echo $this->c(CHECKOUT_ITEM); ?></td>
      <td class="productListing-heading"><?php echo $this->c(CHECKOUT_ITEM_NAME); ?></td>
      <td class="productListing-heading" width="40"><?php echo $this->c(CHECKOUT_QTY); ?></td>
      <td class="productListing-heading" width="80"><?php echo $this->c(CHECKOUT_UNIT_PRICE); ?></td>
      <td class="productListing-heading" width="80"><?php echo $this->c(CHECKOUT_TOTAL_PRICE); ?></td>
      <td class="productListing-heading" width="20">&nbsp;</td>
    </tr>
<?php
      $any_out_of_stock = 0;
       $products = $this->get_cart(true);
		
		 foreach ($products['cart'] as $product) {
		 
		
      
        if (($i/2) == floor($i/2)) {
          $row_class = 'productListing-odd';
        } else {
          $row_class = 'productListing-even';
        }
        
        echo '<tr class="' . $row_class . '">';
        echo '<td class="productListing-data" valign="middle"><b>' . $product['model'] . '</b></td>';

        $cur_row = sizeof($info_box_contents) - 1;

        if (STOCK_CHECK == 'true') {
          $stock_check = tep_check_stock($product['id'], $product['quantity']);
          if (tep_not_null($stock_check)) {
            $any_out_of_stock = 1;

            $products_name .= $stock_check;
          }
        }
        
        $products_name  = '<table border="0" cellspacing="2" cellpadding="2">' .
                         '  <tr>';
        if (CHECKOUT_CART_IMAGES) {
          $products_name .= '    <td class="productListing-data"><img src="' . $product['image'] . '" height="80" /></td>';
        }
        
        $products_name .= '    <td class="productListing-data" valign="middle"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['id']) . '"><b>' . $this->c($product['name']) . '</b></a>';

        if (isset($product['attributes']) && is_array($product['attributes']))
		 {
			foreach ($product['attributes'] as $attribute)
		   {
				$products_name .= '<br><small><i> - ' . $this->c($attribute['option_name'] . ' ' . $attribute['value_name']) . '</i></small>';
           }
        }

        $products_name .= '    </td>' .
                          '  </tr>' .
                          '</table>';
                          
                          
        echo '<td class="productListing-data" valign="middle">' . $products_name . '</td>';
?>

      <td class="productListing-data" valign="middle">
<?php 
  echo tep_draw_input_field('cart_quantity[]', $product['qty'], 'size="4"') . tep_draw_hidden_field('products_id[]', $product['id']);
  
  if (isset($product['attributes']) && is_array($product['attributes'])) {
    while (list($option, $value) = each($product['attributes'])) {
      echo tep_draw_hidden_field('id[' . $product['id'] . '][' . $value['option_id'] . ']', $value['value_id']);
    }
  }
  
  if (CHECKOUT_CONTRIB_GET_1_FREE === true) {
    echo tep_draw_hidden_field('free[]', $product['free']);
  }
?>
      </td>
      <td class="productListing-data" valign="middle"><b><?php echo $product['unit_price']; ?></b></td>
      <td class="productListing-data" valign="middle"><b><?php echo $product['final_price']; ?></b></td>
      <td class="productListing-data" align="center" valign="middle">
        <a href="javascript:void(0);" onclick="Checkout.RemoveProduct('<?php echo $product['id']; ?>')">
        
          <img src="<?php echo CHECKOUT_WS_INCLUDES; ?>images/cross.gif" border="0" alt="<?php echo $this->c(CHECKOUT_REMOVE); ?>" title="<?php echo $this->c(CHECKOUT_REMOVE); ?>" />
        </a>
      </td>
    </tr>
<?php } ?>
  </tbody>
</table>
<?php
    }
    
    function do_login($email_address,$password) {
      global $cart;
      global $customer_id;
      global $customer_default_address_id;
      global $customer_first_name;
      global $customer_password;
      global $customer_country_id;
      global $customer_zone_id;

      $email_address = tep_db_prepare_input($email_address);
      $password = tep_db_prepare_input($password);
      $error = false;

      $check_customer_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_password, c.customers_email_address, c.customers_default_address_id from " . TABLE_CUSTOMERS . " c LEFT JOIN " . TABLE_CUSTOMERS_INFO . " ci ON (c.customers_id = ci.customers_info_id) where c.customers_email_address = '" . tep_db_input($email_address) . "'");
      if (!tep_db_num_rows($check_customer_query)) {
        $error = true;
      } else {
        $check_customer = tep_db_fetch_array($check_customer_query);

        if (!tep_validate_password($password, $check_customer['customers_password'])) {
          $error = true;
        } else {

          $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
          $check_country = tep_db_fetch_array($check_country_query);

          $customer_id = $check_customer['customers_id'];
          $customer_default_address_id = $check_customer['customers_default_address_id'];
          $customer_first_name = $check_customer['customers_firstname'];
          $customer_password = $password;
          $customer_country_id = $check_country['entry_country_id'];
          $customer_zone_id = $check_country['entry_zone_id'];

          tep_session_register('customer_id');
          tep_session_register('customer_default_address_id');
          tep_session_register('customer_first_name');
          tep_session_register('customer_password');
          tep_session_register('customer_country_id');
          tep_session_register('customer_zone_id');
          
          if (CHECKOUT_CONTRIB_HOW_DID_YOU_HEAR) {
            tep_session_unregister('referral_id');
          }

          tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");

          $cart->restore_contents();
        }
      }

      return $error;
    }
    
    function clean_integer($int) {
      return (int)preg_replace('/[^0-9]/', '', $int);
    }
    
    function clean_float($flt) {
      return preg_replace('/[^0-9\.]/', '', $flt);
    }

    function login_window() {
      include(CHECKOUT_WS_INCLUDES . 'checkout_login.php');
    }
    
    function get_country_info($cID) {
    
      $cID = $this->clean_integer($cID);
      
      if ($cID > 0) {
        $country_query = tep_db_query("SELECT countries_id as id, countries_name as title, countries_iso_code_2 as iso_code_2, countries_iso_code_3 as iso_code_3 FROM " . TABLE_COUNTRIES . " WHERE countries_id = " . (int)$cID . " LIMIT 1");
        if (@tep_db_num_rows($country_query) > 0) {
          $country = tep_db_fetch_array($country_query);
          return $country;
        }
      }
      return false;
    }

    function send_email($template, $data) {
      global $language;

      if (empty($language)) $language = 'english';
      
      if (!file_exists(CHECKOUT_WS_INCLUDES . 'languages/' . $language . '/emails/' . $template . '.txt') || !tep_validate_email($data['EMAIL_ADDRESS'])) return false;

      $template = file_get_contents(CHECKOUT_WS_INCLUDES . 'languages/' . $language . '/emails/' . $template . '.txt');
      
      foreach ($data as $key => $val) {
        $template = str_replace('{' . $key . '}', $val, $template);
      }
      
      if (empty($data['SENDER_NAME'])) {
        $data['SENDER_NAME'] = STORE_OWNER;
      }
      
      if (empty($data['SENDER_EMAIL'])) {
        $data['SENDER_EMAIL'] = STORE_OWNER_EMAIL_ADDRESS;
      }

      @tep_mail($data['NAME'], $data['EMAIL_RECIPIENT'], $data['SUBJECT'], $template, $data['SENDER_NAME'], $data['SENDER_EMAIL']);
    }
    
    function reduce_stock($product) {
      if (STOCK_LIMITED == 'true') {
        if (DOWNLOAD_ENABLED == 'true') {
          $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename 
                              FROM " . TABLE_PRODUCTS . " p
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                               ON p.products_id=pa.products_id
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                               ON pa.products_attributes_id=pad.products_attributes_id
                              WHERE p.products_id = '" . tep_get_prid($product['id']) . "'";

          $products_attributes = $product['attributes'];
          if (is_array($products_attributes)) {
            foreach ($products_attributes as $attr) {
              $stock_query_raw .= " AND pa.options_id = '" . $attr['option_id'] . "' AND pa.options_values_id = '" . $attr['value_id'] . "'";
            }
          }
          $stock_query = tep_db_query($stock_query_raw);
        } else {
          $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($product['id']) . "'");
        }
        $stock_values = tep_db_fetch_array($stock_query);

        if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
          $stock_left = $stock_values['products_quantity'] - $product['qty'];
        } else {
          $stock_left = $stock_values['products_quantity'];
        }
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($product['id']) . "'");
        
        if ($stock_left < 1 && STOCK_ALLOW_CHECKOUT == 'false') {
          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($product['id']) . "'");
        }
      }
      
      tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $product['qty']) . " where products_id = '" . tep_get_prid($product['id']) . "'");
    }
    
    function finalize_order($original_orders_id, $staged_order = true) {
      global $customer_id, $order, $order_total_modules, $currencies;
      
      if ((int)$original_orders_id < 1) return false;
      
      if (tep_session_is_registered('checkout_order_id')) tep_session_unregister('checkout_order_id');
      
      $original_orders_id = (int)$original_orders_id;
      
      if ($staged_order) {
        $queries = array();
        
        /* Orders Table */
        $query = tep_db_query("SELECT * FROM " . TABLE_CHECKOUT_ORDERS . " WHERE orders_id = " . (int)$original_orders_id . " LIMIT 1");
        
        if (tep_db_num_rows($query) < 1) return false;
        
        $result = tep_db_fetch_array($query);
        
        $result['orders_id'] = '';
        
        $insert = $this->parse_query_result(TABLE_ORDERS, $result);
        
        tep_db_query($insert);

        $id = tep_db_query("SELECT LAST_INSERT_ID() as id");
        
        $id_query = tep_db_fetch_array($id);
        
        $orders_id = $id_query['id'];
        
        tep_db_query("UPDATE " . TABLE_ORDERS . " SET last_modified = NULL WHERE orders_id = " . $orders_id . " LIMIT 1");
        /* Orders Products */
        $query = tep_db_query("SELECT * FROM " . TABLE_CHECKOUT_ORDERS_PRODUCTS . " WHERE orders_id = " . (int)$original_orders_id);
        
        $products_ordered = '';
        
        while ($result = tep_db_fetch_array($query)) {
          $original_orders_products_id = $result['orders_products_id'];
          $result['orders_products_id'] = "''";
          $result['orders_id'] = $orders_id;
          $products_ordered .= $result['products_quantity'] . ' x ' . $result['products_name'] . ' (' . $result['products_model'] . ') = ' . $currencies->display_price($result['final_price'], $result['products_tax'], $result['products_quantity']);
          
          $insert = $this->parse_query_result(TABLE_ORDERS_PRODUCTS, $result);
          
          tep_db_query($insert);
          
          $orders_products_id = tep_db_insert_id();
          
          $subquery = tep_db_query("SELECT * FROM " . TABLE_CHECKOUT_ORDERS_PRODUCTS_ATTRIBUTES . " WHERE orders_id = " . (int)$original_orders_id . " AND orders_products_id = " . (int)$original_orders_products_id);
          
          $products_ordered_attributes = '';
          if (tep_db_num_rows($subquery) > 0) {
            while ($subresult = tep_db_fetch_array($subquery)) {
            
              $subresult['orders_products_attributes_id'] = '';
              $subresult['orders_products_id'] = $orders_products_id;
              $subresult['orders_id'] = $orders_id;
              
              $products_ordered_attributes .= "\n\t" . $subresult['products_options'] . ' ' . $subresult['products_options_values'];
              $insert = $this->parse_query_result(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $subresult);
              
              tep_db_query($insert);
            }
          }
          $products_ordered .= $products_ordered_attributes . "\n";
          
          $subquery = tep_db_query("SELECT * FROM " . TABLE_CHECKOUT_ORDERS_PRODUCTS_DOWNLOAD . " WHERE orders_id = " . (int)$original_orders_id . " AND orders_products_id = " . (int)$original_orders_products_id);
          
          if (tep_db_num_rows($subquery) > 0) {
            while ($subresult = tep_db_fetch_array($subquery)) {
            
              $subresult['orders_products_download_id'] = '';
              $subresult['orders_products_id'] = $orders_products_id;
              $subresult['orders_id'] = $orders_id;
              
              $insert = $this->parse_query_result(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $subresult);
              
              tep_db_query($insert);
            }
          }
        }
        
        $query = tep_db_query("SELECT * FROM " . TABLE_CHECKOUT_ORDERS_STATUS_HISTORY . " WHERE orders_id = " . (int)$original_orders_id);
        
        if (tep_db_num_rows($query) > 0) {
          while ($result = tep_db_fetch_array($query)) {
          
            $result['orders_status_history_id'] = '';
            $result['orders_id'] = $orders_id;
            
            $insert = $this->parse_query_result(TABLE_ORDERS_STATUS_HISTORY, $result);
            
            tep_db_query($insert);
          }
        }

        $query = tep_db_query("SELECT * FROM " . TABLE_CHECKOUT_ORDERS_TOTAL . " WHERE orders_id = " . (int)$original_orders_id);
        
        if (tep_db_num_rows($query) > 0) {
          while ($result = tep_db_fetch_array($query)) {
          
            $result['orders_total_id'] = '';
            $result['orders_id'] = $orders_id;
            
            $insert = $this->parse_query_result(TABLE_ORDERS_TOTAL, $result);
            
            tep_db_query($insert);
          }
        }

        $this->delete_order($original_orders_id);
      } else {
        $orders_id = $original_orders_id;
      }
      
      require_once(DIR_WS_CLASSES . 'order.php');
      
      $order = new order($orders_id);
      
      $order_comments_query = tep_db_query(
        "SELECT comments " .
        "FROM " . TABLE_ORDERS_STATUS_HISTORY . " " .
        "WHERE orders_id = " . (int)$orders_id . " " .
        "ORDER BY orders_status_history_id ASC " . 
        "LIMIT 1"
      );
      
      if (tep_db_num_rows($order_comments_query) > 0) {
        $order_comments = tep_db_fetch_array($order_comments_query);
        
        $order->info['comments'] = $order_comments['comments'];
      }
      
      require_once(DIR_WS_CLASSES . 'order_total.php');
      if (!is_object($order_total_modules)) {
        $order_total_modules = new order_total;
      }
      
      if (tep_session_is_registered('customer_id')) {
        if ($order->info['payment_method'] == 'Purchase Order Account') {
          $check_credit = tep_db_query("select customers_credit_left from " . TABLE_CUSTOMERS . " where customers_id ='" . (int)$customer_id . "'");
          $credit = tep_db_fetch_array($check_credit);
          $subamt = $credit['customers_credit_left'] - $order->info['total'];
          $credit_tot = tep_db_query("select customers_credit_amount from " . TABLE_CUSTOMERS . " where customers_id ='" . (int)$customer_id . "'");
          $credit_tot = tep_db_fetch_array($credit_tot);
          $credit_amount = $credit_tot['customers_credit_amount'];
          tep_db_query("update " . TABLE_CUSTOMERS . " set customers_credit_left ='" . $subamt . "' where customers_id = '" . (int)$customer_id . "'");
        }
        
        $this->redeem_points();
        
        if(CHECKOUT_CONTRIB_DISCOUNT_COUPON && tep_session_is_registered('coupon') && is_object($order->coupon)) {
      	  $sql_data_array = array( 'coupons_id' => $order->coupon->coupon['coupons_id'],
                                   'orders_id' => $insert_id );
                                   
      	  tep_db_perform( TABLE_DISCOUNT_COUPONS_TO_ORDERS, $sql_data_array );
        }
      }
      
      for ($i=0; $i<sizeof($order->products); $i++) {

        $this->reduce_stock($order->products[$i]);

        if (CHECKOUT_CONTRIB_CCGV) {
          $order_total_modules->update_credit_account($i);
        }
      }

      if (CHECKOUT_CONTRIB_CCGV && tep_session_is_registered('customer_id')) {
        $order_total_modules->apply_credit();
      }

      if (CHECKOUT_CONTRIB_CCGV) {
        if(tep_session_is_registered('credit_covers')) tep_session_unregister('credit_covers');
        $order_total_modules->clear_posts();
        if (tep_session_is_registered('cc_id')) tep_session_unregister('cc_id');
      }

      if (CHECKOUT_CONTRIB_DISCOUNT_COUPON) {
        if (tep_session_is_registered('coupon')) tep_session_unregister('coupon');
      }
      
      if (CHECKOUT_CONTRIB_GIFT_CARD === true) {
        if (tep_session_is_registered('gift_card')) {
          $this->update_gift_card_balance($_SESSION['gift_card']);
        }
      }
      if (empty($language)) $language = 'english';
      require_once(DIR_WS_LANGUAGES . $language . '/checkout_process.php');
      
      $email_data = array();
      
      $email_data['STORE_NAME'] = STORE_NAME;
      $email_data['STORE_OWNER_EMAIL_ADDRESS'] = STORE_OWNER_EMAIL_ADDRESS;
      $email_data['SUBJECT'] = sprintf(CHECKOUT_INVOICE_SUBJECT_CUSTOMER, $orders_id);
      $email_data['NAME'] = $order->customer['name'];
      $email_data['EMAIL_ADDRESS'] = $order->customer['email_address'];
      $email_data['EMAIL_RECIPIENT'] = $order->customer['email_address'];
      $email_data['ORDER_ID'] = $orders_id;
      $email_data['INVOICE_URL'] = tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders_id, 'SSL', false);
      $email_data['DATE_LONG'] = strftime(DATE_FORMAT_LONG);
      $email_data['TELEPHONE'] = $order->customer['telephone'];
      $email_data['SENDER_NAME'] = STORE_OWNER;
      $email_data['SENDER_EMAIL'] = STORE_OWNER_EMAIL_ADDRESS;
        
      $email_data['COMMENTS'] = tep_db_output($order->info['comments']);
      $email_data['PRODUCTS'] = $products_ordered;
      
      $email_data['ORDER_TOTALS'] = '';
      
      $order_totals_query = tep_db_query("SELECT title, text FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = " . (int)$orders_id . " ORDER BY sort_order");

      if (tep_db_num_rows($order_totals_query) > 0) {
        while ($ot = tep_db_fetch_array($order_totals_query)) {
          $email_data['ORDER_TOTALS'] .= strip_tags($ot['title']) . ' ' . strip_tags($ot['text']) . "\n";
        }
      }
      
      $email_data['DELIVERY_ADDRESS'] = '';

      if ($order->content_type != 'virtual') {
        $email_data['DELIVERY_ADDRESS'] = $order->delivery['name'] . "\n" . 
                                          $order->delivery['street_address'] . "\n";
                        
        if ($order->delivery['suburb'] != '') {
          $email_data['DELIVERY_ADDRESS'] .= $order->delivery['suburb'] . "\n";
        }
        
        $email_data['DELIVERY_ADDRESS'] .= $order->delivery['city'] . ', ' . $order->delivery['state'] . '  ' . $order->delivery['postcode'] . "\n";
        $email_data['DELIVERY_ADDRESS'] .= (is_array($order->delivery['country']) ? $order->delivery['country']['title'] : $order->delivery['country']);
      }

      $email_data['BILLING_ADDRESS'] = '';
      
      $email_data['BILLING_ADDRESS'] = $order->billing['name'] . "\n" . 
                                       $order->billing['street_address'] . "\n";
                      
      if ($order->billing['suburb'] != '') {
        $email_data['BILLING_ADDRESS'] .= $order->billing['suburb'] . "\n";
      }
      
      $email_data['BILLING_ADDRESS'] .= $order->billing['city'] . ', ' . $order->billing['state'] . '  ' . $order->billing['postcode'] . "\n";
      $email_data['BILLING_ADDRESS'] .= (is_array($order->billing['country']) ? $order->billing['country']['title'] : $order->billing['country']);
                
      $email_data['PAYMENT_METHOD'] = $order->info['payment_method'] . "\n\n";
      
      /* TODO: Add This
      if (is_object($$payment)) {
        $payment_class = $$payment;
        $email_data['PAYMENT_METHOD'] = $order->info['payment_method'] . "\n\n";
        if ($payment_class->email_footer) {
          $email_data['PAYMENT_METHOD'] .= $payment_class->email_footer;
        }
      }
      */
      
      $email_temp = $email_data;
      
      foreach ($email_temp as $key => $val) {
        $email_data[$key] = $this->c($val);
      }
      if (!empty($order->customer['email_address'])) {
        $this->send_email('invoice', $email_data);
      }
      
      if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
        $email_data['NAME'] = STORE_NAME;
        $email_data['EMAIL_RECIPIENT'] = SEND_EXTRA_ORDER_EMAILS_TO;
        $email_data['SUBJECT'] = sprintf(CHECKOUT_INVOICE_SUBJECT_MERCHANT, $orders_id);
        $email_data['SENDER_NAME'] = $order->customer['name'];
        $email_data['SENDER_EMAIL'] = $order->customer['email_address'];
        $this->send_email('invoice', $email_data);
      }
      
      return $orders_id;
    }
    
    function parse_query_result($table, $result) {
      if (!is_array($result)) return false;
      
      $columns = '';
      $values = '';

      foreach ($result as $key => $val) {
        if ($columns != '') $columns .= ',';
        $columns .= '`' . $key . '`';

        if ($values != '') $values .= ',';

        $values .= "'" . tep_db_input($val) . "'";
      }
      
      return "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ")";
    }
    
    function delete_order($orders_id) {
      if ((int)$orders_id < 1) return false;
      
      $orders_id = (int)$orders_id;
           
      tep_db_query("DELETE FROM " . TABLE_CHECKOUT_ORDERS . " WHERE orders_id = " . $orders_id);
      tep_db_query("DELETE FROM " . TABLE_CHECKOUT_ORDERS_PRODUCTS . " WHERE orders_id = " . $orders_id);
      tep_db_query("DELETE FROM " . TABLE_CHECKOUT_ORDERS_PRODUCTS_ATTRIBUTES . " WHERE orders_id = " . $orders_id);
      tep_db_query("DELETE FROM " . TABLE_CHECKOUT_ORDERS_PRODUCTS_DOWNLOAD . " WHERE orders_id = " . $orders_id);
      tep_db_query("DELETE FROM " . TABLE_CHECKOUT_ORDERS_STATUS_HISTORY . " WHERE orders_id = " . $orders_id);
      tep_db_query("DELETE FROM " . TABLE_CHECKOUT_ORDERS_TOTAL . " WHERE orders_id = " . $orders_id);
      
      return true;
    }
        
    function redeem_points() {
      global $customer_id, $order, $insert_id, $points_toadd, $points_comment, $points_type, $customer_shopping_points;
      
      if (CHECKOUT_CONTRIB_POINTS_AND_REWARDS && tep_session_is_registered('customer_id')) {
        if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {
          $proceed = true;
          /*
          if (function_exists('get_award_discounted')) {
            $proceed = get_award_discounted($order);
          }
          */
          // customer pending points added 
          if (($order->info['total'] > 0) && $proceed) {
            $points_toadd = get_points_toadd($order);
            $points_comment = 'TEXT_DEFAULT_COMMENT';
            $points_type = 'SP';
            if ((get_redemption_awards($customer_shopping_points) == true) && ($points_toadd >0)) {
              tep_add_pending_points($customer_id, $insert_id, $points_toadd, $points_comment, $points_type);
            }
          }
          // customer referral points added 
          if (tep_not_null(USE_REFERRAL_SYSTEM) && tep_not_null($_POST['points_and_rewards_referred'])) {
            $referral_twice_query = tep_db_query("select unique_id from " . TABLE_CUSTOMERS_POINTS_PENDING . " where orders_id = '". $insert_id ."' and points_type = 'RF' limit 1");
            if (!tep_db_num_rows($referral_twice_query)) {
              $points_toadd = USE_REFERRAL_SYSTEM;
              $points_comment = 'TEXT_DEFAULT_REFERRAL';
              $points_type = 'RF';
              
              $customer_query = tep_db_query("SELECT customers_id FROM " . TABLE_CUSTOMERS . " WHERE customers_email_address = '" . tep_db_prepare_input($_POST['points_and_rewards_referred']) . "' LIMIT 1");
              if (tep_db_num_rows($customer_query) > 0) {
                $cid = tep_db_fetch_array($customer_query);
                tep_add_pending_points($cid['customers_id'], $insert_id, $points_toadd, $points_comment, $points_type);
              }
            }
          }
          // customer shoppping points account balanced 
          if ($_SESSION['customer_shopping_points_spending']) {
            tep_redeemed_points($customer_id, $insert_id, $customer_shopping_points);
          }
        }
      }
    }
    
    function send_status($lvl, $msg, $completed = false) {
      @ob_end_clean();
      echo '<script type="text/javascript">';
      echo 'parent.Checkout.SetStatus("' . $lvl . '", "' . str_replace('"', '\"', $msg) . '");';
      
      if ($completed) {
        echo 'parent.Checkout.checkoutCompleted = true;';
      }
      echo '</script>';

      echo '                                                  ';
      echo '                                                  ';
      echo '                                                  ';
      echo '                                                  ';
      echo '                                                  ' . "\n";

      usleep(50000);
      @ob_flush();
      @flush();
      usleep(50000);

    }
    
    function send_error($field, $msg) {
      @ob_end_clean();
      echo '<script type="text/javascript">';
      echo 'parent.$(\'input[name=' . $field . '\').removeClass().addClass("checkout-input-error");';
      echo 'parent.$(\'div#ERROR_' . $field . '\').show().html("' . $msg . '");';
      echo '</script>';
      usleep(50000);
      //@ob_flush();
      @flush();
      usleep(50000);
    }
    
    function die_error($msg, $closable = false) {
      global $language;
      
      if (empty($language)) $language = 'english';
      @ob_end_clean();
?>
      <script type="text/javascript">
        parent.Checkout.SendError('<?php echo addslashes($msg); ?>', <?php echo ($closable ? 'true' : 'false'); ?>);
      </script>
<?php
      usleep(50000);
      //@ob_flush();
      flush();
      usleep(50000);
      die();
    }
    
    function format_error($errno, $errstr, $errfile, $errline) {
      $error_name = '';
      $error = '';
      
      switch ($errno) {
        case 1:
          $error_name = 'Fatal Run-Time Error';
          break;
        case 2:
          $error_name = 'Warning';
          break;
        case 4:
          $error_name = 'Compile-Time Parse Error';
          break;
        case 8:
          $error_name = 'Notice';
          break;
        case 16:
          $error_name = 'Fatal Core Error';
          break;
        case 32:
          $error_name = 'Core Warning';
          break;
        case 64:
          $error_name = 'Fatal Compile-Time Error';
          break;
        case 128:
          $error_name = 'Compile-Time Warning';
          break;
        case 256:
          $error_name = 'Fatal Error (User Generated)';
          break;
        case 512:
          $error_name = 'Warning (User Generated)';
          break;
        case 1024:
          $error_name = 'Notice (User Generated)';
          break;
        case 2048:
          $error_name = 'Strict Notice';
          break;
        case 4096:
          $error_name = 'Catachable Fatal Error';
          break;
        default:
          $error_name = 'Unknown';
          break;
      }
      
      $error  = $error_name . " [" . $errno . "]\n";
      $error .= "Line: " . $errline . "\n";
      $error .= "File: " . basename($errfile) . "\n";
      $error .= $errstr;
      
      return $error;
    }
    
    function error_handler($errno, $errstr, $errfile, $errline) {

      switch ($errno) {

        case E_WARNING:
        case E_NOTICE:
        case E_USER_WARNING:
        case E_USER_NOTICE:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_RECOVERABLE_ERROR:
        case E_STRICT:
        case 8192:
          break;

        case E_ERROR:
        case E_PARSE:
        case E_USER_ERROR:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        default:
          $error = $this->format_error($errno, $errstr, $errfile, $errline);
          
          $this->log_error($error);

          $this->die_error($errstr, true);
          exit(1);

          break;
      }

      return true;
    }

    function log_error($error = '', $output_null = true) {
      $error = trim($error);
      
      if (trim($error) != '' && CHECKOUT_DEBUG_MODE) {
        $error = "\n\nError encountered at " . STORE_NAME . " [" . HTTP_SERVER . "] by " . $_SERVER['REMOTE_ADDR'] . " on " . date(DATE_RFC822) . "\n" . $error;
        
        $error_log = CHECKOUT_WS_INCLUDES . 'error_log.txt';

        if ($fh = fopen($error_log, 'a')) {
          fwrite($fh, "\n\n" . stripslashes($error));
          fclose($fh);
        }
        
        if (CHECKOUT_SEND_BUG_REPORTS) {
          @tep_mail("Bug Report", "bugs@dynamoeffects.com", "Dynamo Checkout Bug Report from " . STORE_NAME . " [" . HTTP_SERVER . "]", stripslashes($error), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
          @tep_mail("Bug Report", "donwyatt@travelvideostore.com", "Dynamo Checkout Bug Report from " . STORE_NAME . " [" . HTTP_SERVER . "]", stripslashes($error), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

        }
      }
      
      if ($output_null) echo '{}';
    }
    
    function detect_payment_module_error() {
      global $GLOBALS;
      
      $error = false;
      
      if (@isset($_GET['payment_error'])) {
        $payment_module = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['payment_error']);
        
        if (!@is_object($GLOBALS[$payment_module])) {
          $payment_modules = new payment;
        }
        
        if (@is_object($GLOBALS[$payment_module])) {
        
          $error = $GLOBALS[$payment_module]->get_error();
          if (@is_array($error) && @isset($error['error'])) {
            $error = $error['error'];
          }
        }
        
        if (!$error) $error = $_GET['payment_error'];
      }
      
      if (!$error) {
        if (!empty($_GET['error_message'])) {
          $error = $_GET['error_message'];
        } elseif (!empty($_GET['error'])) {
          $error = $_GET['error'];
        } elseif (!empty($_REQUEST['error'])) {
          $error = $_REQUEST['error'];
        } elseif (!empty($_GET['amp;error'])) {
          $error = $_GET['amp;error'];
        } elseif (!empty($_SESSION['paypal_error'])) {
          $error = $_SESSION['paypal_error'];
          tep_session_unregister('paypal_error');
        }
      }
      
      return $error;
    }
    
    function safeJS($def) {
      $def = $this->c(constant($def));
      return str_replace('"', '\"', stripslashes($def));
    }
    
    function c($content) {
      return utf8fix($content);
    } 
  }
  
  if (!function_exists('utf8fix')) {
    function utf8fix($content) {
      if(!mb_check_encoding($content, 'UTF-8') || !($content === mb_convert_encoding(mb_convert_encoding($content, 'UTF-32', 'UTF-8' ), 'UTF-8', 'UTF-32'))) {
        $content = mb_convert_encoding($content, 'UTF-8');
      }
      return $content;
    }
  }
  
  if (!function_exists('tep_redirect')) {
    function tep_redirect($url) {
      global $payment;
      
      $target = 'window';
      /* PayPal Pro compatibility */
      if (isset($_SESSION['paypal_error'])) {
        $url = tep_href_link(FILENAME_CHECKOUT_PROCESS, 'error=' . $_SESSION['paypal_error'], 'SSL');
        tep_session_unregister('paypal_error');
      /*
       * This adds support for PayPal Pro with 3DS support
       */
      } elseif (defined('FILENAME_PAYPAL_WPP_3DS') && defined('MODULE_PAYMENT_PAYPAL_DP_CC_ENABLE') && MODULE_PAYMENT_PAYPAL_DP_CC_ENABLE == 'Yes') {
        if (strpos($url, FILENAME_PAYPAL_WPP_3DS) !== false && strpos($url, 'cardinal_centinel_lookup') !== false) {
        
?>
        <script type="text/javascript">
          parent.$('iframe#checkout-gateway').unbind('load');
          try {
            parent.$.fancybox({
              width: 400,
              height: 400,
              scrolling: false,
              onComplete: function() {
                parent.$("form[name='paypal_wpp_form_3ds']").submit();
              },
              content: '<iframe src="<?php echo DIR_WS_HTTPS_CATALOG . DIR_WS_INCLUDES; ?>paypal_wpp/<?php echo FILENAME_PAYPAL_WPP_3DS; ?>?blank=1" frameborder="0" id="paypal-wpp-3ds" name="paypal-wpp-3ds" style="width: 400px; height: 400px; border: 0;"></iframe>'+
                '<form name="paypal_wpp_form_3ds" method="post" action="<?php echo $_SESSION['cardinal_centinel']['acs_url']; ?>" target="paypal-wpp-3ds">'+
                '  <input type="hidden" name="PaReq" value="<?php echo $_SESSION['cardinal_centinel']['payload']; ?>">'+
                '  <input type="hidden" name="TermUrl" value="<?php echo tep_href_link(FILENAME_CHECKOUT, 'checkout_payment=paypal_wpp&action=cardinal_centinel_auth', 'SSL'); ?>">'+
                '  <input type="hidden" name="MD" value="<?php echo tep_session_id() ?>">'+
                '</form>'
            }).click();
          } catch(e) {}
          
        </script>
<?php
          @ob_flush();
          flush();
          die();
        }
      } elseif (strncmp($payment, 'sveawebpay', 10) == 0) {
        $parts = explode('?', $url, 2);
        $parts[1] = urldecode($parts[1]);
        
        $params = explode('&', $parts[1]);
        $params_len = count($params);
        
        $remove = false;
        
        for ($x = 0; $x < $params_len; $x++) {
          $p = explode('=', $params[$x], 2);
          if ($p[0] == 'ResponseURL') {
            $params[$x] = 'ResponseURL=' . urlencode(tep_href_link(CHECKOUT_WS_INCLUDES . 'checkout_notification.php', '', 'SSL'));
          } elseif ($p[0] == 'MD5') {
            $remove = $x;
          } else {
            $params[$x] = $p[0] . '=' . urlencode($p[1]);
          }
        }
        
        if ($remove !== false) {
          unset($params[$remove]);
        }

        $url = $parts[0] . '?' . mb_convert_encoding(implode('&', $params), 'utf-8');
        
        if ($payment == 'sveawebpay_creditcard') {
          $password = MODULE_PAYMENT_SWPCREDITCARD_PASSWORD;
        } elseif ($payment == 'sveawebpay_internetbank') {
          $password = MODULE_PAYMENT_SWPINTERNETBANK_PASSWORD;
        } elseif ($payment == 'sveawebpay_invoice') {
          $password = MODULE_PAYMENT_SWPINVOICE_PASSWORD;
        } elseif ($payment == 'sveawebpay_partpay') {
          $password = MODULE_PAYMENT_SWPPARTPAY_PASSWORD;
        }

        $url .= '&MD5=' . md5($url.$password);
        
        $target = 'parent';
      }

      echo '<script type="text/javascript">' . $target . '.location.href="' . addslashes($url) . '";</script></body></html>';
      @ob_flush();
      flush();
      die();
    }
  }
?>