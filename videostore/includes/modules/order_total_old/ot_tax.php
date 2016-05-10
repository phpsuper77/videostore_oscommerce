<?php
/*
  $Id: ot_tax.php,v 1.14 2003/02/14 05:58:35 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ot_tax {
    var $title, $output;

    function ot_tax() {
      $this->code = 'ot_tax';
      $this->title = MODULE_ORDER_TOTAL_TAX_TITLE;
      $this->description = MODULE_ORDER_TOTAL_TAX_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_TAX_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_TAX_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies, $cc_id, $tax_rate_cal;


      reset($order->info['tax_groups']);
      while (list($key, $value) = each($order->info['tax_groups'])) {
        if ($value > 0) {

        ################################################### NEWLY INSERTED
        if (isset($cc_id) && ($cc_id <> ''))
		{
			$coupon_query_cnt=tep_db_query("select * from " . TABLE_COUPONS . " where coupon_id=".$cc_id." and coupon_active='Y'");
			$coupon_result_cnt=tep_db_fetch_array($coupon_query_cnt);
			$coupon_type=$coupon_result_cnt['coupon_type'];
			if ($coupon_type <> 'S')
			{
				$subtotal = $order->info['subtotal'] - round($od_amount,2);
				$order->info['tax'] = ($order->info['subtotal'] * $tax_rate_cal) / 100;
				$value=$order->info['tax'] ;
			}
			$order->info['total'] = ($order->info['shipping_cost'] + $order->info['subtotal']); #####NEWLY INSERTED
			$order->info['total'] = $order->info['total'] + $order->info['tax'];

		}
        ###################################################


		########################################################################################################
		if (isset($_SESSION['qty_disc_tax']) && $_SESSION['qty_disc_tax'] == 1)
		{
			$order_info['tax']=tep_calculate_tax($order->info['subtotal'],$order->products[0][tax]);
			$value = $order_info['tax'];
		}
		########################################################################################################


         $this->output[] = array('title' => $key . ':',
                                  'text' => $currencies->format($value, true, $order->info['currency'], $order->info['currency_value']),
                                  'value' => $value);
        }
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_TAX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_TAX_STATUS', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Tax', 'MODULE_ORDER_TOTAL_TAX_STATUS', 'true', 'Do you want to display the order tax value?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER', '3', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
