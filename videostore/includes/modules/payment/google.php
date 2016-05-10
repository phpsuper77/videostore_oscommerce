<?php
/*
 
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
  
  Portions  Copyright (c) 2005 - 2006 Chain Reaction Works, Inc
        
*/

class google {
  var $code, $title, $description, $enabled, $sort_order, $form_google_url;

// class constructor
  function google() {
    global $order;
    $this->code = 'google';
    $this->title = MODULE_PAYMENT_GOOGLE_TEXT_TITLE;
    $this->description = MODULE_PAYMENT_GOOGLE_TEXT_DESCRIPTION;
    $this->sort_order = MODULE_PAYMENT_GOOGLE_SORT_ORDER;
    $this->enabled = ((MODULE_PAYMENT_GOOGLE_STATUS == 'True') ? true : false);
    $this->accepted_cc = MODULE_PAYMENT_GOOGLE_ACCEPTED_CC;

    if ((int)MODULE_PAYMENT_GOOGLE_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_GOOGLE_ORDER_STATUS_ID;
    }

    if (is_object($order)) $this->update_status();

    	if (MODULE_PAYMENT_GOOGLE_ACCOUNT_TYPE == 'checkout') {
       $this->form_google_url = 'https://checkout.google.com/cws/v2/Merchant/' . MODULE_PAYMENT_GOOGLE_MERCHANT_ID . '/checkout';
} elseif (MODULE_PAYMENT_GOOGLE_ACCOUNT_TYPE == 'sandbox') {
       $this->form_google_url = 'https://sandbox.google.com/checkout/cws/v2/Merchant/' . MODULE_PAYMENT_GOOGLE_MERCHANT_ID . '/checkout';
}
			$this->form_action_url = tep_href_link(FILENAME_GOOGLE_CHECKOUT_PROCESS, '', 'SSL');
  }

  function update_status() {
    global $order;
    if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_GOOGLE_ZONE > 0) ) {
      $check_flag = false;
      $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_GOOGLE_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
      while ($check = tep_db_fetch_array($check_query)) {
        if ($check['zone_id'] < 1) {
          $check_flag = true;
          break;
        } elseif ($check['zone_id'] == $order->billing['zone_id']) {
          $check_flag = true;
          break;
        }
      }
      if ($check_flag == false) {
        $this->enabled = false;
      }
    }
  }

	function javascript_validation() {
    return false;
  }

  function selection() {
  	$fields[] = array('title' => MODULE_PAYMENT_GOOGLE_MODULE_DESCRIPTION,
                      'field' => '<img src="includes/modules/payment/google/images/checkout_logo.gif" alt="">');
    return array('id' => $this->code,
                 'module' => $this->title,
                 'fields' => $fields);
  }

  function pre_confirmation_check() {
    return false;
  }

	function confirmation() {
		
    return false;
  }
  
  function process_button() {
		
    return $process_button_string;
  }

  function before_process() {
	  return false;
  }

  function after_process() {
  	global $insert_id;
  	
  	$data = $this->gen_shopping_cart($insert_id);
//		echo $data;die();
	  $signature = $this->calcHmacSha1($data);
	  $data = base64_encode($data);
	  $signature = base64_encode($signature);
	  $process_button_string = tep_draw_hidden_field('cart', $data) .
	  												 tep_draw_hidden_field('signature', $signature);
    require(DIR_WS_MODULES . 'payment/google/google_checkout_splash.inc.php');
  }

  function get_error() {
    global $HTTP_GET_VARS;

    $error = array('title' => MODULE_PAYMENT_GOOGLE_TEXT_ERROR,
                   'error' => stripslashes(urldecode($HTTP_GET_VARS['error'])));
    return $error;
  }

  function check() {
    if (!isset($this->_check)) {
      $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_GOOGLE_STATUS'");
      $this->_check = tep_db_num_rows($check_query);
    }
    return $this->_check;
  }

  function install() {
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Google Checkout Module', 'MODULE_PAYMENT_GOOGLE_STATUS', 'True', 'Do you want to accept payments through Google Checkout?', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant ID', 'MODULE_PAYMENT_GOOGLE_MERCHANT_ID', 'Your Merchant ID', 'The merchant ID used for the Google Checkout service', '6', '20', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant Key', 'MODULE_PAYMENT_GOOGLE_MERCHANT_KEY', 'Your Merchant Key', 'The merchant key used for the Google Checkout service', '6', '30', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Account Type', 'MODULE_PAYMENT_GOOGLE_ACCOUNT_TYPE', 'checkout', 'Merchant account type.', '6', '40', 'tep_cfg_select_option(array(\'checkout\', \'sandbox\'), ', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Google Checkout - Set Order Status', 'MODULE_PAYMENT_GOOGLE_ORDER_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID . "', 'Set the default status of orders made with this payment module to this value', '6', '50', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Chargeable Order Status', 'MODULE_PAYMENT_GOOGLE_ORDER_CHARGEABLE_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID . "', 'Set the status of orders made with this payment module to this value<br>(\'Pending\' recommended)', '6', '60', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Charging Order Status', 'MODULE_PAYMENT_GOOGLE_ORDER_CHARGING_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID . "', 'Set the status of <b>Charging</b> orders made with this payment module to this value', '6', '70', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Charged Order Status', 'MODULE_PAYMENT_GOOGLE_ORDER_CHARGED_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID . "', 'Set the status of <b>Charged</b> orders made with this payment module to this value', '6', '80', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Cancelled Order Status', 'MODULE_PAYMENT_GOOGLE_ORDER_CANCELLED_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID . "', 'Set the status of <b>Cancelled</b> orders made with this payment module to this value', '6', '90', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Cancelled by Google Order Status', 'MODULE_PAYMENT_GOOGLE_ORDER_CANCELLED_BY_GOOGLE_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID . "', 'Set the status of <b>Cancelled by Google</b> orders made with this payment module to this value', '6', '100', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Payment Declined Order Status', 'MODULE_PAYMENT_GOOGLE_ORDER_PAYMENT_DECLINED_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID . "', 'Set the status of <b>Payment Declined</b> orders made with this payment module to this value', '6', '110', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Reviewing Order Status', 'MODULE_PAYMENT_GOOGLE_ORDER_REVIEWING_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID . "', 'Set the status of <b>Reviewing</b> orders made with this payment module to this value', '6', '120', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Processing logo', 'MODULE_PAYMENT_GOOGLE_PROCESSING_LOGO', '', 'The image file name to display the store\'s checkout process', '6', '130', now())");

//  	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Send email', 'MODULE_PAYMENT_GOOGLE_SEND_EMAIL', 'New', 'When send email to customer? (Not implement yet)', '6', '1140', 'google_selectOptions(array(\'New\',\'Processing\', \'Shipped\'), ', now())");
  	
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order Of Display', 'MODULE_PAYMENT_GOOGLE_SORT_ORDER', '200', 'The order in which this payment type is dislayed. Lowest is displayed first.', '6', '150' , now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Google Checkout - Payment Zone', 'MODULE_PAYMENT_GOOGLE_ZONE', '0', 'Google Checkout - If a zone is selected, only enable this payment method for that zone.', '6', '160', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
    
    tep_db_query("CREATE TABLE IF NOT EXISTS google_checkout_logs (
										  google_checkout_logs_id int(11) NOT NULL auto_increment,
										  message_type varchar(255) NOT NULL default '',
										  msg_time datetime NOT NULL default '0000-00-00 00:00:00',
										  google_order_id varchar(255) NOT NULL default '',
										  xml text NOT NULL,
										  orders_id int(11) default NULL,
										  PRIMARY KEY  (google_checkout_logs_id),
										  KEY orders_id (orders_id),
										  KEY google_order_id (google_order_id));");
		$flag = true;
		$fields_query = tep_db_query("show fields from " . TABLE_ORDERS);
		while ($fields = tep_db_fetch_array($fields_query)) {
			if ($fields['Field'] == 'google_order_id') {
				$flag = false;
				break;
			}
		}
		if ($flag) {
			tep_db_query("ALTER TABLE orders ADD google_order_id VARCHAR(255);");
		}
  }

  function remove() {
    $keys = '';
    $keys_array = $this->keys();
    for ($i=0; $i<sizeof($keys_array); $i++) {
      $keys .= "'" . $keys_array[$i] . "',";
    }
    $keys = substr($keys, 0, -1);
    tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
  }

  function keys() {
    return array('MODULE_PAYMENT_GOOGLE_STATUS', 'MODULE_PAYMENT_GOOGLE_MERCHANT_ID', 'MODULE_PAYMENT_GOOGLE_MERCHANT_KEY', 'MODULE_PAYMENT_GOOGLE_PROCESSING_LOGO', 'MODULE_PAYMENT_GOOGLE_ACCOUNT_TYPE', 'MODULE_PAYMENT_GOOGLE_ORDER_STATUS_ID', 'MODULE_PAYMENT_GOOGLE_ORDER_CHARGEABLE_STATUS_ID', 'MODULE_PAYMENT_GOOGLE_ORDER_CHARGING_STATUS_ID', 'MODULE_PAYMENT_GOOGLE_ORDER_CHARGED_STATUS_ID', 'MODULE_PAYMENT_GOOGLE_ORDER_CANCELLED_STATUS_ID', 'MODULE_PAYMENT_GOOGLE_ORDER_CANCELLED_BY_GOOGLE_STATUS_ID', 'MODULE_PAYMENT_GOOGLE_ORDER_PAYMENT_DECLINED_STATUS_ID', 'MODULE_PAYMENT_GOOGLE_ORDER_REVIEWING_STATUS_ID', 'MODULE_PAYMENT_GOOGLE_SORT_ORDER', 'MODULE_PAYMENT_GOOGLE_ZONE');
  }
  
  function gen_shopping_cart($orders_id) {
  	global $order, $order_total_modules;
  	
  	//$default_tax_rate = number_format($order->info['tax'] / $order->info['subtotal'], 4, '.', '');
	$default_tax_rate = 0;
  	$eol = "\n";
	$alternate_tax = '';
  	$data = '<?xml version="1.0" encoding="UTF-8"?>' . $eol;
	$data .= '	<checkout-shopping-cart xmlns="http://checkout.google.com/schema/2">' . $eol;
  	$data .= '		<shopping-cart>' . $eol;
    	$data .= '			<items>' . $eol;
    reset($order->products);
    foreach ($order->products as $key => $value) {
    	//$tax = number_format($value['tax'] / 100, 4, '.', '');
	$tax = 0;
    	$data .= '	<item>' . $eol;
    	$data .= '		<item-name>' . htmlentities($value['name']) . '</item-name>' . $eol;
    	$data .= '		<item-description>' . htmlentities($value['name']) . '</item-description>' . $eol;
    	$data .= '		<unit-price currency="' . $order->info['currency'] . '">' . $value['final_price'] . '</unit-price>' . $eol;
    	$data .= '		<quantity>' . $value['qty'] . '</quantity>' . $eol;
    	$data .= '		<tax-table-selector>tax-' . $value['id'] . '</tax-table-selector>' . $eol;
    	$data .= '	</item>' . $eol;
    	$alternate_tax .= '	<alternate-tax-table name="tax-' . $value['id'] . '" standalone="false">' . $eol;
	$alternate_tax .= '		<alternate-tax-rules>' . $eol;
	$alternate_tax .= '			<alternate-tax-rule>' . $eol;
	$alternate_tax .= '				<rate>' . $tax . '</rate>' . $eol;
	$alternate_tax .= '				<tax-area>' . $eol;
	$alternate_tax .= '					<us-country-area country-area="FULL_50_STATES"/>' . $eol;
	$alternate_tax .= '				</tax-area>' . $eol;
	$alternate_tax .= '			</alternate-tax-rule>' . $eol;
	$alternate_tax .= '		</alternate-tax-rules>' . $eol;
	$alternate_tax .= '	</alternate-tax-table>' . $eol;	    
    }
    if (MODULE_ORDER_TOTAL_INSTALLED && is_array($order_total_modules->modules)) {
    	reset($order_total_modules->modules);
    	while (list(, $value) = each($order_total_modules->modules)) {
      	$class = substr($value, 0, strrpos($value, '.'));
        if ($GLOBALS[$class]->enabled) {
        	$size = sizeof($GLOBALS[$class]->output);
          for ($i=0; $i<$size; $i++) {
          	if ($value == 'ot_coupon.php' || $value == 'ot_gv.php' || $value == 'ot_qty_discount.php') {
          		$discount_title = htmlentities(substr(strip_tags($GLOBALS[$class]->output[$i]['title']), 0, strlen(strip_tags($GLOBALS[$class]->output[$i]['title'])) - 1));
          		$discoun_value = number_format($GLOBALS[$class]->output[$i]['value'], 2, '.', '');
	$data .= '	<item>' . $eol;
	$data .= '		<item-name>' . $discount_title . '</item-name>' . $eol;
	$data .= '		<item-description>' . $discount_title . '</item-description>' . $eol;
	$data .= '		<unit-price currency="' . $order->info['currency'] . '">-' . str_replace('-', '', $discoun_value) . '</unit-price>' . $eol;
	$data .= '		<quantity>1</quantity>' . $eol;
	$data .= '	</item>' . $eol;
			  		}

          	if ($value == 'ot_giftwrap.php' || $value == 'ot_insurance.php' || $value == 'ot_tax.php') {
          		$discount_title = htmlentities(substr(strip_tags($GLOBALS[$class]->output[$i]['title']), 0, strlen(strip_tags($GLOBALS[$class]->output[$i]['title'])) - 1));
          		$discoun_value = number_format($GLOBALS[$class]->output[$i]['value'], 2, '.', '');
	$data .= '	<item>' . $eol;
	$data .= '		<item-name>' . htmlentities(strip_tags($discount_title)) . '</item-name>' . $eol;
	$data .= '		<item-description>' . $discount_title . '</item-description>' . $eol;
	$data .= '		<unit-price currency="' . $order->info['currency'] . '">' . str_replace('-', '', $discoun_value) . '</unit-price>' . $eol;
	$data .= '		<quantity>1</quantity>' . $eol;
	$data .= '	</item>' . $eol;
			  		}

          }
        }
      }    	
    }
    $data .= '			</items>' . $eol;
    $data .= '			<merchant-private-data>' . $eol;
    $data .= '			<osc-orders-id>' . $orders_id . '</osc-orders-id>' . $eol;
    $data .= '			</merchant-private-data>' . $eol;
    $data .= '		</shopping-cart>' . $eol;
    $data .= '		<checkout-flow-support>' . $eol;
    $data .= '			<merchant-checkout-flow-support>' . $eol;
    $data .= '				<continue-shopping-url>' . htmlentities(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id=' . $orders_id)) . '</continue-shopping-url>' . $eol;
    $data .= '				<tax-tables>' . $eol;
    $data .= '					<default-tax-table>' . $eol;
    $data .= '						<tax-rules>' . $eol;
    $data .= '							<default-tax-rule>' . $eol;
    $data .= '								<shipping-taxed>false</shipping-taxed>' . $eol;
    $data .= '								<rate>' . $tax . '</rate>' . $eol;
    $data .= '								<tax-area>' . $eol;
    $data .= '									<us-country-area country-area="FULL_50_STATES"/>' . $eol;
    $data .= '								</tax-area>' . $eol;
    $data .= '							</default-tax-rule>' . $eol;
    $data .= '						</tax-rules>' . $eol;
    $data .= '					</default-tax-table>' . $eol;
    $data .= '					<alternate-tax-tables>' . $eol;
    $data .= $alternate_tax;
    $data .= '					</alternate-tax-tables>' . $eol;
    $data .= '				</tax-tables>' . $eol;
    if (tep_not_null($order->info['shipping_method'])) {
	
/*	

	$data .= '		<shipping-methods>' . $eol;
	$data .= '			<flat-rate-shipping name="' . htmlentities($order->info['shipping_method']) . '">' . $eol;
	$data .= '				<price currency="' . $order->info['currency'] . '">' . number_format($order->info['shipping_cost'], 2, '.', '') . '</price>' . $eol;
	$data .= '			</flat-rate-shipping>' . $eol;
	$data .= '		</shipping-methods>' . $eol;
*/	
    $data .= '        <shipping-methods>' . $eol;
    $data .= '            <flat-rate-shipping name="' . htmlentities($order->info['shipping_method']) . '">' . $eol;
    $data .= '                <price currency="' . $order->info['currency'] . '">' . number_format($order->info['shipping_cost'], 2, '.', '') . '</price>' . $eol;
    $data .= '                        <shipping-restrictions>' . $eol;
    $data .= '                            <allowed-areas>' . $eol;
    $data .= '                                <world-area/>' . $eol;
    $data .= '                            </allowed-areas>' . $eol;
    $data .= '                        </shipping-restrictions>' . $eol;
    $data .= '            </flat-rate-shipping>' . $eol;
    $data .= '        </shipping-methods>' . $eol;	
	
	
	
    }
	$data .= '		</merchant-checkout-flow-support>' . $eol;
	$data .= '	</checkout-flow-support>' . $eol;
	$data .= '	</checkout-shopping-cart>' . $eol;
mail ('x0661t@d-net.kiev.ua', 'google checkout data', $data);
		return $data;
  }
  
  function calcHmacSha1($data) {
    $key = MODULE_PAYMENT_GOOGLE_MERCHANT_KEY;
    $blocksize = 64;
    $hashfunc = 'sha1';
    if (strlen($key) > $blocksize) {
        $key = pack('H*', $hashfunc($key));
    }
    $key = str_pad($key, $blocksize, chr(0x00));
    $ipad = str_repeat(chr(0x36), $blocksize);
    $opad = str_repeat(chr(0x5c), $blocksize);
    $hmac = pack(
                    'H*', $hashfunc(
                            ($key^$opad).pack(
                                    'H*', $hashfunc(
                                            ($key^$ipad).$data
                                    )
                            )
                    )
                );
    return $hmac;
	}
}

function google_selectOptions($select_array, $key_value, $key = '') {
	for ($i=0; $i<(sizeof($select_array)); $i++) {
		$name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
		$string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
		$key_values = explode(", ", $key_value);
		if (in_array($select_array[$i], $key_values)) $string .= ' checked="checked"';
		$string .= '> ' . $select_array[$i];
	}
	return $string;
}
?>