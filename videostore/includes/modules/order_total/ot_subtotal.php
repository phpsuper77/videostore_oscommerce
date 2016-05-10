<?php
/*
  $Id: ot_subtotal.php,v 1.7 2003/02/13 00:12:04 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ot_subtotal {
    var $title, $output;

    function ot_subtotal() {
      $this->code = 'ot_subtotal';
      $this->title = MODULE_ORDER_TOTAL_SUBTOTAL_TITLE;
      $this->description = MODULE_ORDER_TOTAL_SUBTOTAL_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_SUBTOTAL_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER;

      $this->output = array();
    }

    function process() {
    global $order, $currencies;

    if (isset($_SESSION['od_amount']) && $_SESSION['od_amount'] <> '')
	{
		$od_amount = $_SESSION['od_amount'];
		//$subtotal = $order->info['subtotal'] - $od_amount; ###This is changed to below line. Because of Precision Problem
		$subtotal = bcsub($order->info['subtotal'],$od_amount,2);
		//$order->info['subtotal'] = $order->info['subtotal'] - $od_amount; ###This is changed to below line. Because of Precision Problem
		$order->info['subtotal'] = bcsub($order->info['subtotal'],$od_amount,2);
    }
	//echo $order->info['subtotal'];
	if($order->info['subtotal'] > 0)
	{
/*
		######CHANGED FOR GIFTWRAP###############
		if ($order->info['giftwrap_cost'] <> '')
			$order->info['subtotal']  += $order->info['giftwrap_cost'];
		#########################################


		######CHANGED FOR SHIPPING INSURANCE########
		if (isset($_SESSION['ins_amt']) && $_SESSION['ins_amt'] <> '')
			$order->info['subtotal']  += $_SESSION['ins_amt'];
		############################################
*/

		$order->info['total'] = ($order->info['shipping_cost'] + $order->info['subtotal']); #####NEWLY INSERTED
		$order->info['total'] = $order->info['total'] + $order->info['tax'];
	}
	else
	{
		//$order->info['subtotal']=$order->info['total'];
		$order->info['subtotal']=0.00;
	}
	
	
	
	
//echo "---".$order->info['subtotal']."---";
	$this->output[] = array('title' => $this->title . ':',
                                'text' => $currencies->format($order->info['subtotal'], true, $order->info['currency'], $order->info['currency_value']),
                                'value' => $order->info['subtotal']);
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_SUBTOTAL_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Sub-Total', 'MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'true', 'Do you want to display the order sub-total cost?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER', '1', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
