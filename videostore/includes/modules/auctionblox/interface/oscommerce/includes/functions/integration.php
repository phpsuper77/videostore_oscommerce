<?php
/*
  $Id: integration.php,v 1.14 2008/04/13 23:27:53 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require_once(DIR_WS_FUNCTIONS . 'general.php');
  
  function abx_get_subcategories(&$subcategories_array, $parent_id = '0'){
    $subcategories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$parent_id . "'");
    while ($subcategories = tep_db_fetch_array($subcategories_query)) {
      $subcategories_array[sizeof($subcategories_array)] = $subcategories['categories_id'];
      if ($subcategories['categories_id'] != $parent_id) {
        abx_get_subcategories($subcategories_array, $subcategories['categories_id']);
      }
    }
  }

  function abx_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
	return tep_draw_radio_field($name, $value, $checked, $parameters);
  }

  function abx_draw_hidden_field($name, $value = '', $parameters = '') {
  	return tep_draw_hidden_field($name, $value, $parameters);
  }
  
  function abx_session_id($sessid = ''){
  	return tep_session_id($sessid);
  }
  
  function abx_session_name($name = ''){
  	return tep_session_name($name);
  }
  
  function abx_image_submit($image, $alt = '', $parameters = '') {
    return tep_image_submit($image, $alt, $parameters);
  }

  function abx_date_raw($date, $reverse = false)
  {
    return tep_date_raw($date, $reverse = false);
  }

  function abx_href_link($page = '', $parameters = '', $connection = 'NONSSL')
  {
    return tep_href_link($page,$parameters,$connection);
  }

  function abx_catalog_href_link($page = '', $parameters = '', $connection = 'NONSSL')
  {
    return tep_catalog_href_link($page,$parameters,$connection);
  }
/*
  function abx_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false)
  {
    return tep_get_category_tree($parent_id,$spacing,$exclude,$category_tree_array,$include_itself);
  }
*/

  function abx_get_prid($uprid)
  {
    return tep_get_prid($uprid);
  }

  function abx_display_tax_value($value, $padding = TAX_DECIMAL_PLACES)
  {
    return tep_display_tax_value($value,$padding);
  }

  function abx_round($value, $precision)
  {
    return tep_round($value, $precision);
  }

  function abx_calculate_tax($price, $tax)
  {
    return tep_calculate_tax($price, $tax);
  }

  function abx_add_tax($price, $tax)
  {
    return tep_add_tax($price,$tax);
  }

  function abx_get_tax_description($class_id, $country_id, $zone_id)
  {
    static $tax_descriptions;

    if (isset($tax_descriptions["{$class_id}"]["{$country_id}"]["{$zone_id}"]) === false) {

      $tax_query = tep_db_query("select tax_description from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' order by tr.tax_priority");

      if (tep_db_num_rows($tax_query)) {

        $tax_description = '';

        while ($tax = tep_db_fetch_array($tax_query))

          $tax_description .= $tax['tax_description'] . ' + ';

        $tax_description = substr($tax_description, 0, -3);

        return $tax_descriptions["{$class_id}"]["{$country_id}"]["{$zone_id}"] = $tax_description;

      } else {

        return TEXT_UNKNOWN_TAX_RATE;

      }

    } else {

      return $tax_descriptions["{$class_id}"]["{$country_id}"]["{$zone_id}"];

    }

  }

  function abx_get_tax_rate($class_id, $country_id = -1, $zone_id = -1)
  {
    return tep_get_tax_rate($class_id,$country_id,$zone_id);
  }

  function abx_get_countries($default = '')
  {
        global $abxDatabase;
    $countries_array = array();
    if ($default) {
      $countries_array[] = array('id' => '',
                                 'text' => $default);
    }
    $countries_query =  $abxDatabase->query("select countries_id, countries_name, countries_iso_code_2 from " . TABLE_COUNTRIES . " order by countries_name");
    while ($countries =  $countries_query->next()) {
      $countries_array[] = array('id' => $countries['countries_id'],
                                 'text' => $countries['countries_name'],
                                 'iso_code_2' => $countries['countries_iso_code_2']);
    }

    return $countries_array;
  }

  function abx_get_countries_with_iso_codes()
  {
    global $abxDatabase;

    $query = $abxDatabase->query("select countries_iso_code_2, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
    while ($query->next())
    {
      $code = $query->value('countries_iso_code_2');
      if (trim(strtoupper($code)) === "GB")
        $code = 'UK';     //eBay uses UK instead of GB

      $countries_array[] = array('id' => $code, 'text' => $query->value('countries_name'));
    }

    return $countries_array;
  }

  function abx_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address)
  {
    return tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
  }

  function abx_get_languages()
  {
    return tep_get_languages();
  }

  function abx_cfg_select_option($select_array, $key_value, $key = '')
  {
    return tep_cfg_select_option($select_array,$key_value,$key);
  }

  function abx_not_null($value)
  {
    return tep_not_null($value);
  }

  function abx_address_format($address_format_id, $address, $html, $boln, $eoln)
  {
    return tep_address_format($address_format_id, $address, $html, $boln, $eoln);
  }

  function abx_salesOrderCustomerEmailNotify($info)
  {
    extract($info);
    $email_order = STORE_NAME . "\n" .
                   EMAIL_SEPARATOR . "\n" .
                   EMAIL_TEXT_ORDER_NUMBER . ' ' . $order_id . "\n" .
                   EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";

    if (empty($comments) === false)
      $email_order .= htmlspecialchars($comments) . "\n\n";

    $email_order .= TABLE_HEADING_PRODUCTS . "\n" .
                    EMAIL_SEPARATOR . "\n" . $products_ordered .
                    EMAIL_SEPARATOR . "\n";

    $email_order .= $order_totals."\n";

    $email_order .= "\n" . ENTRY_SHIPPING_ADDRESS . "\n" .
                    EMAIL_SEPARATOR . "\n" .
                    abx_address_format($delivery_format_id, $delivery_address, false, '', "\n") . "\n";

    $email_order .= "\n" . ENTRY_BILLING_ADDRESS . "\n" .
                    EMAIL_SEPARATOR . "\n" .
                    abx_address_format($billing_format_id, $billing_address, false, '', "\n") . "\n\n";

    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" .
                    EMAIL_SEPARATOR . "\n";

    $email_order .= $payment_method . "\n\n";

    tep_mail($customer_firstname . ' ' . $customer_lastname, $customer_email_address, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

    // send emails to other people
    if (SEND_EXTRA_ORDER_EMAILS_TO != '')
      tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  }

  function abx_db_install($sql_file,$db_tables,$errorReporting = false)
  {
    global $abxDatabase;

    if (file_exists($sql_file)) {

      $fd = fopen($sql_file, 'rb');

      $restore_query = fread($fd, filesize($sql_file));

      fclose($fd);

    } else {

      return array(
        'Message' => 'SQL file does not exist: ' . $sql_file,
        'ErrorLevel' => 1
      );

    }

    $sql_array = array();
    $sql_length = strlen($restore_query);
    $pos = strpos($restore_query, ';');
    for ($i=$pos; $i<$sql_length; $i++) {
      if ($restore_query[0] == '#') {
        $restore_query = ltrim(substr($restore_query, strpos($restore_query, "\n")));
        $sql_length = strlen($restore_query);
        $i = strpos($restore_query, ';')-1;
        continue;
      }
      if ($restore_query[($i+1)] == "\n") {
        for ($j=($i+2); $j<$sql_length; $j++) {
          if (trim($restore_query[$j]) != '') {
            $next = substr($restore_query, $j, 6);
            if ($next[0] == '#') {
// find out where the break position is so we can remove this line (#comment line)
              for ($k=$j; $k<$sql_length; $k++) {
                if ($restore_query[$k] == "\n") break;
              }
              $query = substr($restore_query, 0, $i+1);
              $restore_query = substr($restore_query, $k);
// join the query before the comment appeared, with the rest of the dump
              $restore_query = $query . $restore_query;
              $sql_length = strlen($restore_query);
              $i = strpos($restore_query, ';')-1;
              continue 2;
            }
            break;
          }
        }
        if ($next == '') { // get the last insert query
          $next = 'insert';
        }
        if ((preg_match('/alter/i', $next)) || (preg_match('/create/i', $next)) || (eregi('insert', $next)) || (eregi('drop t', $next)) ) {
          $next = '';
          $sql_array[] = substr($restore_query, 0, $i);
          $restore_query = ltrim(substr($restore_query, $i+1));
          $sql_length = strlen($restore_query);
          $i = strpos($restore_query, ';')-1;
        }
      }
    }

    $abxDatabase->setErrorReporting($errorReporting);
    //$abxDatabase->query("drop table if exists " . $db_tables);

    for ($i=0; $i<sizeof($sql_array); $i++)
      $abxDatabase->query($sql_array[$i]);

    return array(
      'Message' => 'Import Success',
      'ErrorLevel' => 0
    );
  }
  function abx_cfg_pull_down_zone_classes($zone_class_id, $key = ''){
    return tep_cfg_pull_down_zone_classes($zone_class_id, $key );
  }

  function abx_cfg_select_drop_down($select_array, $key_value, $key = '') {
    return tep_cfg_select_drop_down($select_array, $key_value, $key );
  }

  function abx_cfg_pull_down_tax_classes($tax_class_id, $key = ''){
    return tep_cfg_pull_down_tax_classes($tax_class_id, $key);
  }

  function abx_get_zone_class_title($zone_class_id){
    return tep_get_zone_class_title($zone_class_id);
  }
  function abx_get_tax_class_title($zone_class_id){
    return tep_get_tax_class_title($zone_class_id);
  }

  function abx_redirect($url){
    tep_redirect($url);
  }
  function abx_get_language_id() {
     global $language_id;
     return $language_id;  
  }
  
  function abx_install()
  {
    global $abxDatabase;

    $abxDatabase->query("update " . TABLE_PRODUCTS . " set products_date_added = '1990-01-01' where products_date_added < '1990-01-01'");
    $abxDatabase->query("update " . TABLE_PRODUCTS . " set products_last_modified = '1990-01-01' where products_last_modified < '1990-01-01'");
    $abxDatabase->query("ALTER TABLE " . TABLE_PRODUCTS . " CHANGE products_last_modified products_last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");

    return true;
  }  
?>