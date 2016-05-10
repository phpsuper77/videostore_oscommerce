<?php
/*
$Id: usps.php 3.0 2008-03-01 04:59:07Z user $
  ++++ Original contribution by Brad Waite and Fritz Clapp ++++
  ++++ incorporating USPS revisions to service names ++++
  Copyright 2008 osCommerce
  Released under the GNU General Public License
*/
//LAST UPDATED: May 28th, 2008 by Greg Deeth
//Modified by Greg Deeth April 30, 2008 to use API v.3.0
//Modified by Greg Deeth May 12, 2008 for API Change
//Please refer to http://www.usps.com/webtools/_pdf/Rate-Calculators-v1-2.pdf for more information on RateV3 syntax.
  class usps {
    var $code, $title, $description, $icon, $enabled, $countries;
// class constructor
    function usps() {
      global $order;
      $this->code = 'usps';
      $this->title = MODULE_SHIPPING_USPS_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_USPS_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_USPS_SORT_ORDER;
      $this->icon = DIR_WS_ICONS . 'shipping_usps.gif';
      $this->tax_class = MODULE_SHIPPING_USPS_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_USPS_STATUS == 'True') ? true : false);
        $this->testing = 0;
      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_USPS_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_USPS_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
        }
        if ($check_flag == false) {
          $this->enabled = false;
        }
      }

//Modified Jan 4 2011 by Fulluv Scents to include only standard domestic shipping options
	$this->types = array('Express Mail' => 'Express Mail', // ID="39"
		'Priority Mail' => 'Priority Mail', // ID="40"
		'Parcel Post' => 'Parcel Post', // ID="38"
		'Media Mail' => 'Media Mail', // ID="37"
		'First-Class Mail' => 'First-Class Mail' // ID="51"
		);
		

//Modified Jan 4 2011 by Fulluv Scents to remove redundant international options
//******************************************************************* */
//Added by Greg Deeth on May 12th, 2008
//INTERNATIONAL MAIL OPTIONS
//Change the values to the option you would like
//FIRST CLASS MAIL INTERNATIONAL OPTION:
//        $this->FirstClassIntType = 'Letters';                           //OPTIONS: 'Letters', 'Large Envelope', 'Package'
//PRIORITY FLAT-RATE BOX INTERNATIONAL OPTION:
//        $this->PriorityFlatRateBoxType = 'Flat-Rate Box';               //OPTIONS: 'Flat-Rate Box', 'Large Flat-Rate Box'
//****************************************************************** */
        
//Modified Jan 3 2011 by Fulluv Scents to update International Options
    $this->intl_types = array(
        'Global Express' => 'Global Express Guaranteed (GXG)**', // ID="4" 'Global Express Guaranteed (GXG)'
        'Global Express Non-Doc Rect' => 'Global Express Guaranteed Non-Document Rectangular', // ID="6" 'Global Express Guaranteed Non-Document Rectangular'
        'Global Express Non-Doc Non-Rect' => 'Global Express Guaranteed Non-Document Non-Rectangular', // ID="7" 'Global Express Guaranteed Non-Document Non-Rectangular'
        'USPS GXG Envelopes' => 'USPS GXG Envelopes**', // ID="12" 'USPS GXG Envelopes'
        'Express Mail Int' => 'Express Mail International', // ID="1" 'Express Mail International'
        'Express Mail Int Flat Rate Env' => 'Express Mail International Flat Rate Envelope', // ID="10" 'Express Mail International Flat Rate Envelope'
        'Express Mail Int Legal' => 'Express Mail International Legal Flat Rate Envelope', // ID="17"
        'Priority Mail International' => 'Priority Mail International', // ID="2" 'Priority Mail International'
        'Priority Mail Int Flat Rate Lrg Box' => 'Priority Mail International Large Flat Rate Box', // ID="11" 'Priority Mail International Large Flat Rate Box'
        'Priority Mail Int Flat Rate Med Box' => 'Priority Mail International Medium Flat Rate Box', // ID="9" 'Priority Mail International Medium Flat Rate Box'
        'Priority Mail Int Flat Rate Small Box' => 'Priority Mail International Small Flat Rate Box**', // ID="16" 'Priority Mail International Small Flat Rate Box'
        'Priority Mail Int DVD' => 'Priority Mail International DVD Flat Rate Box**', // ID="24"
        'Priority Mail Int Lrg Video' => 'Priority Mail International Large Video Flat Rate Box**', // ID="25"
        'Priority Mail Int Flat Rate Env' => 'Priority Mail International Flat Rate Envelope**', // ID="8" 'Priority Mail International Flat Rate Envelope',
        'Priority Mail Int Legal Flat Rate Env' => 'Priority Mail International Legal Flat Rate Envelope**', // ID="22"
        'Priority Mail Int Padded Flat Rate Env' => 'Priority Mail International Padded Flat Rate Envelope**', // ID="23"
        'Priority Mail Int Gift Card Flat Rate Env' => 'Priority Mail International Gift Card Flat Rate Envelope**', // ID=18
        'Priority Mail Int Small Flat Rate Env' => 'Priority Mail International Small Flat Rate Envelope**', // ID="20"
        'First Class Mail Int Lrg Env' => 'First-Class Mail International Large Envelope**', // ID="14" 'First-Class Mail International Large Envelope'
        'First Class Mail Int Package' => 'First-Class Mail International Package**', // ID="15" 'First-Class Mail International Package'
        'First Class Mail Int Letter' => 'First-Class Mail International Letter**' // ID="13" 'First-Class Mail International Letter' // ID="13"
        );

                       
      $this->countries = $this->country_list();
    }
// class methods
    function quote($method = '') {
      global $order, $shipping_weight, $shipping_num_boxes, $transittime;
      if ( tep_not_null($method) && (isset($this->types[$method]) || in_array($method, $this->intl_types)) ) {
        $this->_setService($method);
      }
// usps doesnt accept zero weight
// Modified by Greg Deeth on May 27th 2008
      $shipping_weight = ($shipping_weight < 0.0625 ? 0.0625 : $shipping_weight);
      $shipping_pounds = floor ($shipping_weight);
      $shipping_ounces = (16 * ($shipping_weight - floor($shipping_weight)));
      $this->_setWeight($shipping_pounds, $shipping_ounces);
// Added by Kevin Chen (kkchen@uci.edu); Fixes the Parcel Post Bug July 1, 2004
// Refer to http://www.usps.com/webtools/htm/Domestic-Rates.htm documentation
// Thanks Ryan
// End Kevin Chen July 1, 2004
       
      if (in_array('Display weight', explode(', ', MODULE_SHIPPING_USPS_OPTIONS)))
        if (!function_exists('round_up')) { function round_up($valueIn, $places=0) {
        if ($places < 0) { $places = 0; }
        $mult = pow(10, $places);
        return (ceil($valueIn * $mult) / $mult);
          }
//      if (in_array('Display weight', explode(', ', MODULE_SHIPPING_USPS_OPTIONS))) {
//        function round_up($valueIn, $places=0) {
//              if ($places < 0) { $places = 0; }
//              $mult = pow(10, $places);
//              return (ceil($valueIn * $mult) / $mult);
//        }
        $shiptitle = ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . 'lbs)' . ' (' . round_up($shipping_pounds, 4) . 'lbs, ' . round_up($shipping_ounces, 4) . 'oz)';
      } else {
        $shiptitle = '';
      }
      $uspsQuote = $this->_getQuote();
      if (is_array($uspsQuote)) {
        if (isset($uspsQuote['error'])) {
          $this->quotes = array('module' => $this->title,
                                'error' => $uspsQuote['error']);
        } else {
          $this->quotes = array('id' => $this->code,
                                'module' => $this->title . $shiptitle);
          $methods = array();
          $size = sizeof($uspsQuote);
          for ($i=0; $i<$size; $i++) {
            list($type, $cost) = each($uspsQuote[$i]);
            $title = ((isset($this->types[$type])) ? $this->types[$type] : $type);
            if(in_array('Display transit time', explode(', ', MODULE_SHIPPING_USPS_OPTIONS)))    $title .= $transittime[$type];
            $methods[] = array('id' => $type,
                               'title' => $title,
                               'cost' => ($cost + MODULE_SHIPPING_USPS_HANDLING) * $shipping_num_boxes);
          }
          $this->quotes['methods'] = $methods;
          if ($this->tax_class > 0) {
            $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          }
        }
      } else {
        $this->quotes = array('module' => $this->title,
                              'error' => MODULE_SHIPPING_USPS_TEXT_ERROR);
      }
      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);
      return $this->quotes;
    }
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_USPS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }
    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable USPS Shipping', 'MODULE_SHIPPING_USPS_STATUS', 'True', 'Do you want to offer USPS shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the USPS User ID', 'MODULE_SHIPPING_USPS_USERID', 'NONE', 'Enter the USPS USERID assigned to you.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the USPS Password', 'MODULE_SHIPPING_USPS_PASSWORD', 'NONE', 'See USERID, above.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Which server to use', 'MODULE_SHIPPING_USPS_SERVER', 'production', 'An account at USPS is needed to use the Production server', '6', '0', 'tep_cfg_select_option(array(\'test\', \'production\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_USPS_HANDLING', '0', 'Handling fee for this shipping method.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_USPS_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_USPS_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_USPS_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
//Modified Jan 4 2011 by Fulluv Scents to include only standard domestic shipping options
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Domestic Shipping Methods', 'MODULE_SHIPPING_USPS_TYPES', 'Express Mail, Priority Mail, Parcel Post, Media Mail, First-Class Mail', 'Select the domestic services to be offered:', '6', '0', 'tep_cfg_select_multioption(array(\'Express Mail\', \'Priority Mail\', \'Parcel Post\', \'Media Mail\', \'First-Class Mail\',), ', now())");
//Modified Jan 3 2011 by Fulluv Scents to update International Options
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Int\'l Shipping Methods', 'MODULE_SHIPPING_USPS_TYPES_INTL',
      'Global Express, Global Express Non-Doc Rect, Global Express Non-Doc Non-Rect, USPS GXG Envelopes, Express Mail Int, Express Mail Int Flat Rate Env, Express Mail Int Legal, Priority Mail International, Priority Mail Int Flat Rate Env, Priority Mail Int Flat Rate Small Box, Priority Mail Int Flat Rate Med Box, Priority Mail Int Flat Rate Lrg Box, Priority Mail Int DVD, Priority Mail Int Lrg Video, Priority Mail Int Legal Flat Rate Env, Priority Mail Int Padded Flat Rate Env, Priority Mail Int Gift Card Flat Rate Env, Priority Mail Int Small Flat Rate Env, First Class Mail Int Lrg Env, First Class Mail Int Package, First Class Mail Int Letter',
	  'Select the international services to be offered:', '6', '0', 'tep_cfg_select_multioption(
      array(\'Global Express\', \'Global Express Non-Doc Rect\', \'Global Express Non-Doc Non-Rect\', \'USPS GXG Envelopes\', \'Express Mail Int\', \'Express Mail Int Flat Rate Env\', \'Express Mail Int Legal\', \'Priority Mail International\', \'Priority Mail Int Flat Rate Env\', \'Priority Mail Int Flat Rate Small Box\', \'Priority Mail Int Flat Rate Med Box\', \'Priority Mail Int Flat Rate Lrg Box\', \'Priority Mail Int DVD\', \'Priority Mail Int Lrg Video\', \'Priority Mail Int Legal Flat Rate Env\', \'Priority Mail Int Padded Flat Rate Env\', \'Priority Mail Int Gift Card Flat Rate Env\', \'Priority Mail Int Small Flat Rate Env\', \'First Class Mail Int Lrg Env\', \'First Class Mail Int Package\', \'First Class Mail Int Letter\'), ',  now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('USPS Options', 'MODULE_SHIPPING_USPS_OPTIONS', 'Display weight, Display transit time', 'Select from the following the USPS options.', '6', '0', 'tep_cfg_select_multioption(array(\'Display weight\', \'Display transit time\'), ', now())");
    }
    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
    function keys() {
      return array('MODULE_SHIPPING_USPS_STATUS', 'MODULE_SHIPPING_USPS_USERID', 'MODULE_SHIPPING_USPS_PASSWORD', 'MODULE_SHIPPING_USPS_SERVER', 'MODULE_SHIPPING_USPS_HANDLING', 'MODULE_SHIPPING_USPS_TAX_CLASS', 'MODULE_SHIPPING_USPS_ZONE', 'MODULE_SHIPPING_USPS_SORT_ORDER', 'MODULE_SHIPPING_USPS_OPTIONS', 'MODULE_SHIPPING_USPS_TYPES', 'MODULE_SHIPPING_USPS_TYPES_INTL');
    }
    function round_up($valueIn, $places=0) {
        if ($places < 0) { $places = 0; }
        $mult = pow(10, $places);
        return (ceil($valueIn * $mult) / $mult);
    }
    function _setService($service) {
      $this->service = $service;
    }
    function _setWeight($pounds, $ounces=0) {
      $this->pounds = $pounds;
      $this->ounces = $ounces;
    }
/*
    function _setContainer($container) {
      $this->container = $container;
    }
    function _setSize($size) {
      $this->size = $size;
    }
*/
    function _setMachinable($machinable) {
      $this->machinable = $machinable;
    }
    function _getQuote() {
      global $order, $transittime;
      if(in_array('Display transit time', explode(', ', MODULE_SHIPPING_USPS_OPTIONS))) $transit = TRUE;
//RateRequest changed to RateV3Request by Greg Deeth April 30, 2008
      if ($order->delivery['country']['id'] == SHIPPING_ORIGIN_COUNTRY) {
        $request  = '<RateV3Request USERID="' . MODULE_SHIPPING_USPS_USERID . '" PASSWORD="' . MODULE_SHIPPING_USPS_PASSWORD . '">';
        $services_count = 0;
        if (isset($this->service)) {
          $this->types = array($this->service => $this->types[$this->service]);
        }
        $dest_zip = str_replace(' ', '', $order->delivery['postcode']);
        if ($order->delivery['country']['iso_code_2'] == 'US') $dest_zip = substr($dest_zip, 0, 5);
        reset($this->types);
        $allowed_types = explode(", ", MODULE_SHIPPING_USPS_TYPES);
        while (list($key, $value) = each($this->types)) {
          if ( !in_array($key, $allowed_types) ) continue;
/********************************************************************** */
//DOMESTIC MAIL OPTIONS
//For Options list, go to page 9 of document: http://www.usps.com/webtools/_pdf/Rate-Calculators-v1-2.pdf
                //$this->size ='Regular'  //Set default value of Regular unless different value is applied below
//FIRST CLASS MAIL OPTIONS
                if ($key == 'First-Class Mail'){
                        //WEIGHT THRESHOLD OPTIONS (LETTER is changed to FLAT automatically by USPS when over 3.5oz)
                        if($this->pounds == 0 && $this->ounces <= 3.5){
                                //IF WEIGHT MATCHES STATEMENT, THEN:
                                //IF YOU DO/DON'T WANT MACHINABLE THRESHOLD, REMOVE/ADD COMMENT MARKS AT BEGINNING OF LINE "//"
                        $this->machinable = 'true';                     //OPTIONS: 'true', 'false'
                                //IF YOU DO/DON'T WANT CONTAINER THRESHOLD, REMOVE/ADD COMMENT MARKS AT BEGINNING OF LINE "//"                  
                                $this->FirstClassMailType = 'LETTER';   //OPTIONS: 'LETTER, 'FLAT', 'PARCEL'
                        }
                else{
                                //IF WEIGHT DOES NOT MATCH STATEMENT, THEN:
                        //IF YOU DO/DON'T WANT MACHINABLE THRESHOLD, REMOVE/ADD COMMENT MARKS AT BEGINNING OF LINE "//"
                        $this->machinable = 'false';                    //OPTIONS: 'true', 'false'
                                //IF YOU DO/DON'T WANT CONTAINER THRESHOLD, REMOVE/ADD COMMENT MARKS AT BEGINNING OF LINE "//"                  
                                $this->FirstClassMailType = 'FLAT';     //OPTIONS: 'LETTER, 'FLAT', 'PARCEL'
                }
                }
//PRIORITY MAIL OPTIONS
                if ($key == 'Priority Mail'){
                        $this->container = '';                          //OPTIONS: '', 'FLAT RATE BOX', 'FLAT RATE ENVELOPE'
                        $this->size = 'REGULAR';
                }
//PRIORITY MAIL FLAT-RATE OPTIONS
        //ENVELOPE
                if ($key == 'Priority FltRt Envelope'){
                        $key = 'Priority Mail'; //DO NOT CHANGE
                        $this->container = 'FLAT RATE ENVELOPE';        //OPTIONS: 'FLAT RATE ENVELOPE', 'FLAT RATE BOX'
                        $this->size = 'REGULAR';
                }
        //BOX
                if ($key == 'Priority FltRt Box'){
                        $key = 'Priority Mail'; //DO NOT CHANGE
                        $this->container = 'FLAT RATE BOX';             //OPTIONS: 'FLAT RATE BOX', 'FLAT RATE ENVELOPE'
                        $this->size = 'LARGE';
                }
//EXPRESS MAIL OPTIONS
                if ($key == 'Express Mail'){
                        $this->container = '';          //OPTIONS: '', 'FLAT RATE ENVELOPE'
                        $this->size = 'REGULAR';                                //OPTIONS: 'REGULAR', 'LARGE'
                }
//PARCEL POST OPTIONS
                if ($key == 'Parcel Post'){
                        //WEIGHT THRESHOLD OPTIONS (DEFAULT MACHINABLE: WEIGHT<70lbs WEIGHT CANNOT EXCEED 70lbs ANYWAY)
                        if($this->pounds <= 70 && $this->ounces <= 0){
                                //IF WEIGHT MATCHES STATEMENT, THEN:
                                //IF YOU DO/DON'T WANT MACHINABLE THRESHOLD, REMOVE/ADD COMMENT MARKS AT BEGINNING OF LINE "//"
                        $this->machinable = 'true';                     //OPTIONS: 'true', 'false'
                                //IF YOU DO/DON'T WANT SIZE THRESHOLD, REMOVE/ADD COMMENT MARKS AT BEGINNING OF LINE "//"                       
                                $this->size = 'REGULAR';                        //OPTIONS: 'REGULAR', 'LARGE', 'OVERSIZE'
                        }
                else{
                                //IF WEIGHT DOES NOT MATCH STATEMENT, THEN:
                        //IF YOU DO/DON'T WANT MACHINABLE THRESHOLD, REMOVE/ADD COMMENT MARKS AT BEGINNING OF LINE "//"
                        $this->machinable = 'true';                     //OPTIONS: 'true', 'false'
                                //IF YOU DO/DON'T WANT SIZE THRESHOLD, REMOVE/ADD COMMENT MARKS AT BEGINNING OF LINE "//"                       
                                $this->size = 'REGULAR';                                //OPTIONS: 'REGULAR', 'LARGE', 'OVERSIZE'
                }
                }
//BPM OPTIONS
                if ($key == 'Bound Printed Matter'){
                        $this->size = 'REGULAR';                                //OPTIONS: 'REGULAR', 'LARGE'
                }
//MEDIA MAIL OPTIONS
                if ($key == 'Media Mail'){
                        $this->size = 'REGULAR';                                //OPTIONS: 'REGULAR, 'LARGE'
                }
//LIBRARY MAIL OPTIONS
/*************************************************************************** */
          $request .= '<Package ID="' . $services_count . '">' .
                      '<Service>' . $key . '</Service>' .
                            '<FirstClassMailType>' . $this->FirstClassMailType . '</FirstClassMailType>' .
                      '<ZipOrigination>' . SHIPPING_ORIGIN_ZIP . '</ZipOrigination>' .
                      '<ZipDestination>' . $dest_zip . '</ZipDestination>' .
                      '<Pounds>' . $this->pounds . '</Pounds>' .
                      '<Ounces>' . $this->ounces . '</Ounces>' .
                      '<Container>' . $this->container . '</Container>' .
                      '<Size>' . $this->size . '</Size>' .
                      '<Machinable>' . $this->machinable . '</Machinable>' .
                      '</Package>';
          if($transit){
            $transitreq  = 'USERID="' . MODULE_SHIPPING_USPS_USERID .
                         '" PASSWORD="' . MODULE_SHIPPING_USPS_PASSWORD . '">' .
                         '<OriginZip>' . STORE_ORIGIN_ZIP . '</OriginZip>' .
                         '<DestinationZip>' . $dest_zip . '</DestinationZip>';
            switch ($key) {
              case 'Express Mail':  $transreq[$key] = 'API=ExpressMail&XML=' .
                               urlencode( '<ExpressMailRequest ' . $transitreq . '</ExpressMailRequest>');
                               break;
              case 'Priority Mail': $transreq[$key] = 'API=PriorityMail&XML=' .
                               urlencode( '<PriorityMailRequest ' . $transitreq . '</PriorityMailRequest>');
                               break;
              case 'Parcel Post':   $transreq[$key] = 'API=StandardB&XML=' .
                               urlencode( '<StandardBRequest ' . $transitreq . '</StandardBRequest>');
                               break;
              default:         $transreq[$key] = '';
                               break;
            }
          }
          $services_count++;
        }
        $request .= '</RateV3Request>'; //'</RateRequest>'; //Changed by Greg Deeth April 30, 2008
        $request = 'API=RateV3&XML=' . urlencode($request);
      } else {
        $request  = '<IntlRateRequest USERID="' . MODULE_SHIPPING_USPS_USERID . '" PASSWORD="' . MODULE_SHIPPING_USPS_PASSWORD . '">' .
                    '<Package ID="0">' .
                    '<Pounds>' . $this->pounds . '</Pounds>' .
                    '<Ounces>' . round ($this->ounces) . '</Ounces>' .
                    '<MailType>Package</MailType>' .
                    '<Country>' . $this->countries[$order->delivery['country']['iso_code_2']] . '</Country>' .
                    '</Package>' .
                    '</IntlRateRequest>';
        $request = 'API=IntlRate&XML=' . urlencode($request);
      }
      switch (MODULE_SHIPPING_USPS_SERVER) {
        case 'production': $usps_server = 'production.shippingapis.com'; //'stg-production.shippingapis.com'; // or  stg-secure.shippingapis.com //'production.shippingapis.com';
                           $api_dll = 'shippingapi.dll'; //'shippingapi.dll';
                           break;
        case 'test':
        default:           $usps_server = 'stg-production.shippingapis.com'; //Fixed by Greg Deeth April 30, 2008
                           $api_dll = 'shippingapitest.dll'; //'shippingapi.dll'; //Fixed by Greg Deeth April 30, 2008
                           break;
      }
      $body = '';
      if (!class_exists('httpClient')) {
        include('includes/classes/http_client.php');
      }
      $http = new httpClient();
      if ($http->Connect($usps_server, 80)) {
        $http->addHeader('Host', $usps_server);
        $http->addHeader('User-Agent', 'osCommerce');
        $http->addHeader('Connection', 'Close');
        if ($http->Get('/' . $api_dll . '?' . $request)) $body = $http->getBody();
  mail('user@localhost.com','USPS rate quote response',$body,'From: <user@localhost.com>');
        if ($transit && is_array($transreq) && ($order->delivery['country']['id'] == STORE_COUNTRY)) {
          while (list($key, $value) = each($transreq)) {
            if ($http->Get('/' . $api_dll . '?' . $value)) $transresp[$key] = $http->getBody();
          }
        }
        $http->Disconnect();
      } else {
        return false;
      }

//Modified Jan 4 2011 by Fulluv Scents to remove registered trademarks
$body = str_replace('&amp;lt;sup&amp;gt;&amp;amp;reg;&amp;lt;/sup&amp;gt;', '', $body);
$body = str_replace('&amp;lt;sup&amp;gt;&amp;amp;trade;&amp;lt;/sup&amp;gt;', '', $body);

      $response = array();
      while (true) {
        if ($start = strpos($body, '<Package ID=')) {
          $body = substr($body, $start);
          $end = strpos($body, '</Package>');
          $response[] = substr($body, 0, $end+10);
          $body = substr($body, $end+9);
        } else {
          break;
        }
      }
        $rates = array();
      $rates_sorter = array();
      if ($order->delivery['country']['id'] == SHIPPING_ORIGIN_COUNTRY) {
        if (sizeof($response) == '1') {
          if (ereg('<Error>', $response[0])) {
            $number = ereg('<Number>(.*)</Number>', $response[0], $regs);
            $number = $regs[1];
            $description = ereg('<Description>(.*)</Description>', $response[0], $regs);
            $description = $regs[1];
            return array('error' => $number . ' - ' . $description);
          }
        }
        $n = sizeof($response);
        for ($i=0; $i<$n; $i++) {
          if (strpos($response[$i], '<Rate>')) {
            $service = ereg('<MailService>(.*)</MailService>', $response[$i], $regs);
                        
//new code
  		$service = htmlspecialchars_decode($regs[1]);
  		$service = preg_replace('/\&lt;sup\&gt;\&amp;reg;\&lt;\/sup\&gt;/', '<sup>&reg;</sup>', $service);
//end new code
                        
                        //$service = $regs[1];
                        
            $postage = ereg('<Rate>(.*)</Rate>', $response[$i], $regs);
            $postage = $regs[1];
                $rates[] = array($service => $postage);
            $rates_sorter[] = $postage;
            if ($transit) {
              switch ($service) {
                case 'Express Mail':     $time = ereg('<MonFriCommitment>(.*)</MonFriCommitment>', $transresp[$service], $tregs);
                                    $time = $tregs[1];
                                    if ($time == '' || $time == 'No Data') {
                                      $time = 'Estimated 1 - 2 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    } else {
                                      $time = 'Tomorrow by ' . $time;
                                    }
                                    break;
                case 'Express Mail Flat-Rate Envelope':     $time = ereg('<MonFriCommitment>(.*)</MonFriCommitment>', $transresp[$service], $tregs);
                                    $time = $tregs[1];
                                    if ($time == '' || $time == 'No Data') {
                                      $time = 'Estimated 1 - 2 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    } else {
                                      $time = 'Tomorrow by ' . $time;
                                    }
                                    break;
                case 'Priority Mail':    $time = ereg('<Days>(.*)</Days>', $transresp[$service], $tregs);
                                    $time = $tregs[1];
                                    if ($time == '' || $time == 'No Data') {
                                      $time = 'Estimated 1 - 3 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    } elseif ($time == '1') {
                                      $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAY;
                                    } else {
                                      $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    }
                                    break;
                case 'Priority Mail Flat-Rate Envelope':    $time = ereg('<Days>(.*)</Days>', $transresp[$service], $tregs);
                                    $time = $tregs[1];
                                    if ($time == '' || $time == 'No Data') {
                                      $time = 'Estimated 1 - 3 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    } elseif ($time == '1') {
                                      $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAY;
                                    } else {
                                      $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    }
                                    break;
                case 'Priority Mail Flat-Rate Box':    $time = ereg('<Days>(.*)</Days>', $transresp[$service], $tregs);
                                    $time = $tregs[1];
                                    if ($time == '' || $time == 'No Data') {
                                      $time = 'Estimated 1 - 3 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    } elseif ($time == '1') {
                                      $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAY;
                                    } else {
                                      $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    }
                                    break;
                case 'Parcel Post':      $time = ereg('<Days>(.*)</Days>', $transresp[$service], $tregs);
                                    $time = $tregs[1];
                                    if ($time == '' || $time == 'No Data') {
                                      $time = 'Estimated 2 - 9 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    } elseif ($time == '1') {
                                      $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAY;
                                    } else {
                                      $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    }
                                    break;
//Modified Jan 4 2011 by Fulluv Scents to show shipping time for all first class methods
                case 'First-Class Mail': $time = 'Estimated 1 - 5 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    break;
                					default:            $time = '';
                                    break;
                case 'First-Class Mail Flat': $time = 'Estimated 1 - 5 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    break;
                					default:            $time = '';
                                    break;
                case 'First-Class Mail Letter': $time = 'Estimated 1 - 5 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    break;
                					default:            $time = '';
                                    break;
                case 'First-Class Mail Package': $time = 'Estimated 1 - 5 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    break;
                					default:            $time = '';
                                    break;
                case 'Media Mail':              $time = 'Estimated 2 - 9 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    break;
                					default:            $time = '';
                                    break;
                case 'Bound Printed Matter':                    $time = 'Estimated 2 - 9 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    break;
                					default:            $time = '';
                                    break;
              }
              if ($time != '') $transittime[$service] = ': ' . $time . '';
            }
          }
        }
      } else {
        if (ereg('<Error>', $response[0])) {
          $number = ereg('<Number>(.*)</Number>', $response[0], $regs);
          $number = $regs[1];
          $description = ereg('<Description>(.*)</Description>', $response[0], $regs);
          $description = $regs[1];
          return array('error' => $number . ' - ' . $description);
        } else {
          $body = $response[0];
          $services = array();
          while (true) {
            if ($start = strpos($body, '<Service ID=')) {
              $body = substr($body, $start);
              $end = strpos($body, '</Service>');
              $services[] = substr($body, 0, $end+10);
              $body = substr($body, $end+9);
            } else {
              break;
            }
          }
          $allowed_types = array();
          foreach( explode(", ", MODULE_SHIPPING_USPS_TYPES_INTL) as $value ) $allowed_types[$value] = $this->intl_types[$value];
                
          $size = sizeof($services);
                  
                 for ($i=0, $n=$size; $i<$n; $i++) {
            if (strpos($services[$i], '<Postage>')) {
                                
              $service = ereg('<SvcDescription>(.*)</SvcDescription>', $services[$i], $regs);
                  $service = $regs[1];
                  
// Commented out Jan 4 2011 by Fulluv Scents -- This code did not perform the intended function
//new code
//              $service = str_replace('&amp;lt;sup&amp;gt;&amp;amp;reg;&amp;lt;/sup&amp;gt;', '', $service);
//              $service = str_replace('&amp;lt;sup&amp;gt;&amp;amp;trade;&amp;lt;/sup&amp;gt;', '', $service);
//end new code
                          
                          $postage = ereg('<Postage>(.*)</Postage>', $services[$i], $regs);
              $postage = $regs[1];
              $time = ereg('<SvcCommitments>(.*)</SvcCommitments>', $services[$i], $tregs);
              $time = $tregs[1];
              $time = preg_replace('/Weeks$/', MODULE_SHIPPING_USPS_TEXT_WEEKS, $time);
              $time = preg_replace('/Days$/', MODULE_SHIPPING_USPS_TEXT_DAYS, $time);
              $time = preg_replace('/Day$/', MODULE_SHIPPING_USPS_TEXT_DAY, $time);
              if( !in_array($service, $allowed_types) ) continue;
              if (isset($this->service) && ($service != $this->service) ) {
                continue;
              }
                  $rates[] = array($service => $postage);
              $rates_sorter[] = $postage;
              if ($time != '') $transittime[$service] = ' (' . $time . ')';
            }
          }
        }
      }
        //Sort Rates
        asort($rates_sorter);
        $sorted_rates = array();
        foreach (array_keys($rates_sorter) as $key){
                $sorted_rates[] = $rates[$key];
        }
        return ((sizeof($sorted_rates) > 0) ? $sorted_rates : false);
    }
    function country_list() {
      $list = array('AF' => 'Afghanistan',
                    'AL' => 'Albania',
                    'DZ' => 'Algeria',
                    'AD' => 'Andorra',
                    'AO' => 'Angola',
                    'AI' => 'Anguilla',
                    'AG' => 'Antigua and Barbuda',
                    'AR' => 'Argentina',
                    'AM' => 'Armenia',
                    'AW' => 'Aruba',
                    'AU' => 'Australia',
                    'AT' => 'Austria',
                    'AZ' => 'Azerbaijan',
                    'BS' => 'Bahamas',
                    'BH' => 'Bahrain',
                    'BD' => 'Bangladesh',
                    'BB' => 'Barbados',
                    'BY' => 'Belarus',
                    'BE' => 'Belgium',
                    'BZ' => 'Belize',
                    'BJ' => 'Benin',
                    'BM' => 'Bermuda',
                    'BT' => 'Bhutan',
                    'BO' => 'Bolivia',
                    'BA' => 'Bosnia-Herzegovina',
                    'BW' => 'Botswana',
                    'BR' => 'Brazil',
                    'VG' => 'British Virgin Islands',
                    'BN' => 'Brunei Darussalam',
                    'BG' => 'Bulgaria',
                    'BF' => 'Burkina Faso',
                    'MM' => 'Burma',
                    'BI' => 'Burundi',
                    'KH' => 'Cambodia',
                    'CM' => 'Cameroon',
                    'CA' => 'Canada',
                    'CV' => 'Cape Verde',
                    'KY' => 'Cayman Islands',
                    'CF' => 'Central African Republic',
                    'TD' => 'Chad',
                    'CL' => 'Chile',
                    'CN' => 'China',
                    'CX' => 'Christmas Island (Australia)',
                    'CC' => 'Cocos Island (Australia)',
                    'CO' => 'Colombia',
                    'KM' => 'Comoros',
                    'CG' => 'Congo (Brazzaville),Republic of the',
                    'ZR' => 'Congo, Democratic Republic of the',
                    'CK' => 'Cook Islands (New Zealand)',
                    'CR' => 'Costa Rica',
                    'CI' => 'Cote d\'Ivoire (Ivory Coast)',
                    'HR' => 'Croatia',
                    'CU' => 'Cuba',
                    'CY' => 'Cyprus',
                    'CZ' => 'Czech Republic',
                    'DK' => 'Denmark',
                    'DJ' => 'Djibouti',
                    'DM' => 'Dominica',
                    'DO' => 'Dominican Republic',
                    'TP' => 'East Timor (Indonesia)',
                    'EC' => 'Ecuador',
                    'EG' => 'Egypt',
                    'SV' => 'El Salvador',
                    'GQ' => 'Equatorial Guinea',
                    'ER' => 'Eritrea',
                    'EE' => 'Estonia',
                    'ET' => 'Ethiopia',
                    'FK' => 'Falkland Islands',
                    'FO' => 'Faroe Islands',
                    'FJ' => 'Fiji',
                    'FI' => 'Finland',
                    'FR' => 'France',
                    'GF' => 'French Guiana',
                    'PF' => 'French Polynesia',
                    'GA' => 'Gabon',
                    'GM' => 'Gambia',
                    'GE' => 'Georgia, Republic of',
                    'DE' => 'Germany',
                    'GH' => 'Ghana',
                    'GI' => 'Gibraltar',
                    'GB' => 'Great Britain and Northern Ireland',
                    'GR' => 'Greece',
                    'GL' => 'Greenland',
                    'GD' => 'Grenada',
                    'GP' => 'Guadeloupe',
                    'GT' => 'Guatemala',
                    'GN' => 'Guinea',
                    'GW' => 'Guinea-Bissau',
                    'GY' => 'Guyana',
                    'HT' => 'Haiti',
                    'HN' => 'Honduras',
                    'HK' => 'Hong Kong',
                    'HU' => 'Hungary',
                    'IS' => 'Iceland',
                    'IN' => 'India',
                    'ID' => 'Indonesia',
                    'IR' => 'Iran',
                    'IQ' => 'Iraq',
                    'IE' => 'Ireland',
                    'IL' => 'Israel',
                    'IT' => 'Italy',
                    'JM' => 'Jamaica',
                    'JP' => 'Japan',
                    'JO' => 'Jordan',
                    'KZ' => 'Kazakhstan',
                    'KE' => 'Kenya',
                    'KI' => 'Kiribati',
                    'KW' => 'Kuwait',
                    'KG' => 'Kyrgyzstan',
                    'LA' => 'Laos',
                    'LV' => 'Latvia',
                    'LB' => 'Lebanon',
                    'LS' => 'Lesotho',
                    'LR' => 'Liberia',
                    'LY' => 'Libya',
                    'LI' => 'Liechtenstein',
                    'LT' => 'Lithuania',
                    'LU' => 'Luxembourg',
                    'MO' => 'Macao',
                    'MK' => 'Macedonia, Republic of',
                    'MG' => 'Madagascar',
                    'MW' => 'Malawi',
                    'MY' => 'Malaysia',
                    'MV' => 'Maldives',
                    'ML' => 'Mali',
                    'MT' => 'Malta',
                    'MQ' => 'Martinique',
                    'MR' => 'Mauritania',
                    'MU' => 'Mauritius',
                    'YT' => 'Mayotte (France)',
                    'MX' => 'Mexico',
                    'MD' => 'Moldova',
                    'MC' => 'Monaco (France)',
                    'MN' => 'Mongolia',
                    'MS' => 'Montserrat',
                    'MA' => 'Morocco',
                    'MZ' => 'Mozambique',
                    'NA' => 'Namibia',
                    'NR' => 'Nauru',
                    'NP' => 'Nepal',
                    'NL' => 'Netherlands',
                    'AN' => 'Netherlands Antilles',
                    'NC' => 'New Caledonia',
                    'NZ' => 'New Zealand',
                    'NI' => 'Nicaragua',
                    'NE' => 'Niger',
                    'NG' => 'Nigeria',
                    'KP' => 'North Korea (Korea, Democratic People\'s Republic of)',
                    'NO' => 'Norway',
                    'OM' => 'Oman',
                    'PK' => 'Pakistan',
                    'PA' => 'Panama',
                    'PG' => 'Papua New Guinea',
                    'PY' => 'Paraguay',
                    'PE' => 'Peru',
                    'PH' => 'Philippines',
                    'PN' => 'Pitcairn Island',
                    'PL' => 'Poland',
                    'PT' => 'Portugal',
                    'QA' => 'Qatar',
                    'RE' => 'Reunion',
                    'RO' => 'Romania',
                    'RU' => 'Russia',
                    'RW' => 'Rwanda',
                    'SH' => 'Saint Helena',
                    'KN' => 'Saint Kitts (St. Christopher and Nevis)',
                    'LC' => 'Saint Lucia',
                    'PM' => 'Saint Pierre and Miquelon',
                    'VC' => 'Saint Vincent and the Grenadines',
                    'SM' => 'San Marino',
                    'ST' => 'Sao Tome and Principe',
                    'SA' => 'Saudi Arabia',
                    'SN' => 'Senegal',
                    'YU' => 'Serbia-Montenegro',
                    'SC' => 'Seychelles',
                    'SL' => 'Sierra Leone',
                    'SG' => 'Singapore',
                    'SK' => 'Slovak Republic',
                    'SI' => 'Slovenia',
                    'SB' => 'Solomon Islands',
                    'SO' => 'Somalia',
                    'ZA' => 'South Africa',
                    'GS' => 'South Georgia (Falkland Islands)',
                    'KR' => 'South Korea (Korea, Republic of)',
                    'ES' => 'Spain',
                    'LK' => 'Sri Lanka',
                    'SD' => 'Sudan',
                    'SR' => 'Suriname',
                    'SZ' => 'Swaziland',
                    'SE' => 'Sweden',
                    'CH' => 'Switzerland',
                    'SY' => 'Syrian Arab Republic',
                    'TW' => 'Taiwan',
                    'TJ' => 'Tajikistan',
                    'TZ' => 'Tanzania',
                    'TH' => 'Thailand',
                    'TG' => 'Togo',
                    'TK' => 'Tokelau (Union) Group (Western Samoa)',
                    'TO' => 'Tonga',
                    'TT' => 'Trinidad and Tobago',
                    'TN' => 'Tunisia',
                    'TR' => 'Turkey',
                    'TM' => 'Turkmenistan',
                    'TC' => 'Turks and Caicos Islands',
                    'TV' => 'Tuvalu',
                    'UG' => 'Uganda',
                    'UA' => 'Ukraine',
                    'AE' => 'United Arab Emirates',
                    'UY' => 'Uruguay',
                    'UZ' => 'Uzbekistan',
                    'VU' => 'Vanuatu',
                    'VA' => 'Vatican City',
                    'VE' => 'Venezuela',
                    'VN' => 'Vietnam',
                    'WF' => 'Wallis and Futuna Islands',
                    'WS' => 'Western Samoa',
                    'YE' => 'Yemen',
                    'ZM' => 'Zambia',
                    'ZW' => 'Zimbabwe');
      return $list;
    }
  }
?>