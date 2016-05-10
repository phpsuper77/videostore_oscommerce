<?php
/*
 * Created on Oct 15, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class abxCurrencies {
    
  var $currencies;
    
  // class constructor
  function getCurrencies() {
    
    global $abxDatabase;
    
    $currencies =  array();
    
    $currencies_query = "select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value" .
                        " from " . TABLE_CURRENCIES;

    $currenciesRs = $abxDatabase->query($currencies_query);

    while($currency = $currenciesRs->next()) {
      $currencies[$currency['code']] = array(
        'title' => $currency['title'],
        'symbol_left' => $currency['symbol_left'],
        'symbol_right' => $currency['symbol_right'],
        'decimal_point' => $currency['decimal_point'],
        'thousands_point' => $currency['thousands_point'],
        'decimal_places' => $currency['decimal_places'],
        'value' => $currency['value']);
    }
    
    return $currencies;
  }  
  
  function format($number, $currency_type) {
    
    $currencies = abxCurrencies::getCurrencies();

    $format_string = $currencies[$currency_type]['symbol_left'] . number_format(round($number, $currencies[$currency_type]['decimal_places']), $currencies[$currency_type]['decimal_places'], $currencies[$currency_type]['decimal_point'], $currencies[$currency_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];
    return $format_string;
  }
}
?>