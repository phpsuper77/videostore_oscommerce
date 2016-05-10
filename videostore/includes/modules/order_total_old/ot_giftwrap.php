<?php
/*
  $Id: ot_shipping.php,v 1.12 2002/12/09 19:07:16 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class ot_giftwrap {
    var $title, $output;

    function ot_giftwrap() {
      $this->code = 'ot_giftwrap';
      $this->title = MODULE_ORDER_TOTAL_GIFTWRAP_TITLE;
      $this->description = MODULE_ORDER_TOTAL_GIFTWRAP_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_GIFTWRAP_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_GIFTWRAP_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies;

      if (tep_not_null($order->info['giftwrap_method'])) {
        if (MODULE_ORDER_TOTAL_GIFTWRAP_TAX_CLASS > 0) {
          $giftwrap_tax = tep_get_tax_rate(MODULE_ORDER_TOTAL_GIFTWRAP_TAX_CLASS);

          $order->info['tax'] += tep_calculate_tax($order->info['giftwrap_cost'], $giftwrap_tax);
          $order->info['tax_groups']["{$giftwrap_tax}"] += tep_calculate_tax($order->info['giftwrap_cost'], $giftwrap_tax);
          $order->info['total'] += tep_calculate_tax($order->info['giftwrap_cost'], $giftwrap_tax);

          if (DISPLAY_PRICE_WITH_TAX == true) $order->info['giftwrap_cost'] += tep_calculate_tax($order->info['giftwrap_cost'], $giftwrap_tax);
        }

        $this->output[] = array('title' => $order->info['giftwrap_method'] . ':',
                                'text' => $currencies->format($order->info['giftwrap_cost'], true, $order->info['currency'], $order->info['currency_value']),
                                'value' => $order->info['giftwrap_cost']);
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_GIFTWRAP_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_GIFTWRAP_STATUS', 'MODULE_ORDER_TOTAL_GIFTWRAP_SORT_ORDER', 'MODULE_ORDER_TOTAL_GIFTWRAP_TAX_CLASS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display GiftWrap', 'MODULE_ORDER_TOTAL_GIFTWRAP_STATUS', 'true', 'Do you want to display the order giftwrap cost?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_GIFTWRAP_SORT_ORDER', '3', 'Sort order of display.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_ORDER_TOTAL_GIFTWRAP_TAX_CLASS', '0', 'Use the following tax class on the giftwrap fee.', '6', '6', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
