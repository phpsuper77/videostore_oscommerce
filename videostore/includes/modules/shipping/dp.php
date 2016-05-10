<?php
/*
$Id: dp.php,v 1.36 2003/03/09 02:14:35 harley_vb Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2002 - 2003 osCommerce

Released under the GNU General Public License
*/

class dp {
var $code, $title, $description, $enabled, $num_dp;

// class constructor
function dp() {
	global $order;

	$this->code = 'dp';
	$this->title = MODULE_SHIPPING_DP_TEXT_TITLE;
	$this->description = MODULE_SHIPPING_DP_TEXT_DESCRIPTION;
	$this->sort_order = MODULE_SHIPPING_DP_SORT_ORDER;
	$this->icon = DIR_WS_ICONS . 'shipping_dp.gif';
	$this->tax_class = MODULE_SHIPPING_DP_TAX_CLASS;
	$this->enabled = ((MODULE_SHIPPING_DP_STATUS == 'true') ? true : false);

	if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_DP_ZONE > 0) )
	{
	$check_flag = false;
	$check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_DP_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
		while ($check = tep_db_fetch_array($check_query))
		{
			if ($check['zone_id'] < 1) {
			$check_flag = true;
			break;
			} elseif ($check['zone_id'] == $order->delivery['zone_id']) {
			$check_flag = true;
			break;
			}
		}

		if ($check_flag == false)
		{
		$this->enabled = false;
		}
	}

	// CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
	$this->num_dp = 5;
}

// class methods
function quote($method = '')
{

	echo "hellooooooo";
	global $HTTP_POST_VARS, $order, $shipping_weight, $shipping_num_boxes;

	$dest_country = $order->delivery['country']['iso_code_2'];
	$dest_zone = 0;
	$error = false;

	for ($i=1; $i<=$this->num_dp; $i++) {
	$countries_table = constant('MODULE_SHIPPING_DP_COUNTRIES_' . $i);
	$country_zones = split("[,]", $countries_table);
		if (in_array($dest_country, $country_zones))
		{
		$dest_zone = $i;
		break;
		}
	}


	if ($dest_zone == 0) {
	$error = true;
	} else {
	$shipping = -1;
	$dp_cost = constant('MODULE_SHIPPING_DP_COST_' . $i);

	$dp_table = split("[:,]" , $dp_cost);
		for ($i=0; $i<sizeof($dp_table); $i+=2) {
			if ($shipping_weight <= $dp_table[$i]) {
			$shipping = $dp_table[$i+1];
			$shipping_method = MODULE_SHIPPING_DP_TEXT_WAY . ' [ ' . $dest_country . ' ]';
			break;
			}
		}

		if ($shipping == -1)
		{
		$shipping_cost = 0;
		$shipping_method = MODULE_SHIPPING_DP_UNDEFINED_RATE;
		} else {
		$shipping_cost = ($shipping + MODULE_SHIPPING_DP_HANDLING);
		}
	}

	$this->quotes = array('id' => $this->code,
					'module' => '',
					'methods' => array(array('id' => $this->code,
					//'title' => $shipping_method . ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . ' ' . MODULE_SHIPPING_DP_TEXT_UNITS .')',
					'title' => $shipping_method,
					'cost' => $shipping_cost * $shipping_num_boxes)));

	if ($this->tax_class > 0) {
	$this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
	}

	//if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

	if ($error == true) $this->quotes['error'] = MODULE_SHIPPING_DP_INVALID_ZONE;

	return $this->quotes;
}

function check() {
	if (!isset($this->_check)) {
	$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_DP_STATUS'");
	$this->_check = tep_db_num_rows($check_query);
	}
	return $this->_check;
}

function install()
{
	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Deutsche Post WorldNet', 'MODULE_SHIPPING_DP_STATUS', 'true', 'Wollen Sie den Versand �ber die deutsche Post anbieten?', '6', '0', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_DP_HANDLING', '0', 'Bearbeitungsgeb�hr f�r diese Versandart in Euro', '6', '0', now())");
	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Steuersatz', 'MODULE_SHIPPING_DP_TAX_CLASS', '0', 'W�hlen Sie den MwSt.-Satz f�r diese Versandart aus.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Versand Zone', 'MODULE_SHIPPING_DP_ZONE', '0', 'Wenn Sie eine Zone ausw�hlen, wird diese Versandart nur in dieser Zone angeboten.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Reihenfolge der Anzeige', 'MODULE_SHIPPING_DP_SORT_ORDER', '0', 'Niedrigste wird zuerst angezeigt.', '6', '0', now())");


	for ($i = 1; $i <= $this->num_dp; $i++) {
	$default_countries = '';
	if ($i == 1) {
		$default_countries = 'DE';
	}


	//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('DP Zone 1 Countries', 'MODULE_SHIPPING_DP_COUNTRIES_1', 'AD,AT,BE,CZ,DK,FO,FI,FR,GR,GL,IE,IT,LI,LU,MC,NL,PL,PT,SM,SK,SE,CH,VA,GB,SP', 'Comma separated list of two character ISO country codes that are part of Zone 1', '6', '0', now())");
	//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('DP Zone 1 Shipping Table', 'MODULE_SHIPPING_DP_COST_1', '5:16.50,10:20.50,20:28.50', 'Shipping rates to Zone 1 destinations based on a range of order weights. Example: 0-3:8.50,3-7:10.50,... Weights greater than 0 and less than or equal to 3 would cost 14.57 for Zone 1 destinations.', '6', '0', now())");
	//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('DP Zone 2 Countries', 'MODULE_SHIPPING_DP_COUNTRIES_2', 'AL,AM,AZ,BY,BA,BG,HR,CY,GE,GI,HU,IS,KZ,LT,MK,MT,MD,NO,SI,UA,TR,YU,RU,RO,LV,EE', 'Comma separated list of two character ISO country codes that are part of Zone 2', '6', '0', now())");
	//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('DP Zone 2 Shipping Table', 'MODULE_SHIPPING_DP_COST_2', '5:25.00,10:35.00,20:45.00', 'Shipping rates to Zone 2 destinations based on a range of order weights. Example: 0-3:8.50,3-7:10.50,... Weights greater than 0 and less than or equal to 3 would cost 23.78 for Zone 2 destinations.', '6', '0', now())");
	//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('DP Zone 3 Countries', 'MODULE_SHIPPING_DP_COUNTRIES_3', 'DZ,BH,CA,EG,IR,IQ,IL,JO,KW,LB,LY,OM,SA,SY,US,AE,YE,MA,QA,TN,PM', 'Comma separated list of two character ISO country codes that are part of Zone 3', '6', '0', now())");
	//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('DP Zone 3 Shipping Table', 'MODULE_SHIPPING_DP_COST_3', '5:29.00,10:39.00,20:59.00', 'Shipping rates to Zone 3 destinations based on a range of order weights. Example: 0-3:8.50,3-7:10.50,... Weights greater than 0 and less than or equal to 3 would cost 26.84 for Zone 3 destinations.', '6', '0', now())");
	//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('DP Zone 4 Countries', 'MODULE_SHIPPING_DP_COUNTRIES_4', 'AF,AS,AO,AI,AG,AR,AW,AU,BS,BD,BB,BZ,BJ,BM,BT,BO,BW,BR,IO,BN,BF,BI,KH,CM,CV,KY,CF,TD,CL,CN,CC,CO,KM,CG,CR,CI,CU,DM,DO,EC,SV,ER,ET,FK,FJ,GF,PF,GA,GM,GH,GD,GP,GT,GN,GW,GY,HT,HN,HK,IN,ID,JM,JP,KE,KI,KG,KP,KR,LA,LS', 'Comma separated list of two character ISO country codes that are part of Zone 4', '6', '0', now())");
	//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('DP Zone 4 Shipping Table', 'MODULE_SHIPPING_DP_COST_4', '5:35.00,10:50.00,20:80.00', 'Shipping rates to Zone 4 destinations based on a range of order weights. Example: 0-3:8.50,3-7:10.50,... Weights greater than 0 and less than or equal to 3 would cost 32.98 for Zone 4 destinations.', '6', '0', now())");
	//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('DP Zone 5 Countries', 'MODULE_SHIPPING_DP_COUNTRIES_5', 'MO,MG,MW,MY,MV,ML,MQ,MR,MU,MX,MN,MS,MZ,MM,NA,NR,NP,AN,NC,NZ,NI,NE,NG,PK,PA,PG,PY,PE,PH,PN,RE,KN,LC,VC,SN,SC,SL,SO,LK,SR,SZ,ZA,SG,TG,TH,TZ,TT,TO,TM,TV,VN,WF,VE,UG,UZ,UY,ST,SH,SD,TW,GQ,LR,DJ,CG,RW,ZM,ZW', 'Comma separated list of two character ISO country codes that are part of Zone 5', '6', '0', now())");
	//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('DP Zone 5 Shipping Table', 'MODULE_SHIPPING_DP_COST_5', '5:35.00,10:50.00,20:80.00', 'Shipping rates to Zone 5 destinations based on a range of order weights. Example: 0-3:8.50,3-7:10.50,... Weights greater than 0 and less than or equal to 3 would cost 32.98 for Zone 5 destinations.', '6', '0', now())");
	//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('DP Zone 6 Countries', 'MODULE_SHIPPING_DP_COUNTRIES_6', 'DE', 'Comma separated list of two character ISO country codes that are part of Zone 6', '6', '0', now())");
	//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('DP Zone 6 Shipping Table', 'MODULE_SHIPPING_DP_COST_6', '5:6.70,10:9.70,20:13.00', 'Shipping rates to Zone 6 destinations based on a range of order weights. Example: 0-3:8.50,3-7:10.50,... Weights greater than 0 and less than or equal to 3 would cost 5.62 for Zone 6 destinations.', '6', '0', now())");

	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Countries', 'MODULE_SHIPPING_DP_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Comma separated list of two character ISO country codes that are part of Zone " . $i . ".', '6', '0', now())");
	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Shipping Table', 'MODULE_SHIPPING_DP_COST_" . $i ."', '5:6.70,10:9.70,20:13.00', 'Shipping rates to Zone " . $i . " destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone " . $i . " destinations.', '6', '0', now())");
 //tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Handling Fee', 'MODULE_SHIPPING_DP_HANDLING_" . $i."', '0', 'Bearbeitungsgeb�hr f�r diese Versandart in Euro', '6', '0', now())");


	}
}

function remove() {
	tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
}

function keys() {
	$keys = array('MODULE_SHIPPING_DP_STATUS', 'MODULE_SHIPPING_DP_HANDLING', 'MODULE_SHIPPING_DP_TAX_CLASS', 'MODULE_SHIPPING_DP_ZONE', 'MODULE_SHIPPING_DP_SORT_ORDER');

	for ($i = 1; $i <= $this->num_dp; $i ++) {
	$keys[count($keys)] = 'MODULE_SHIPPING_DP_COUNTRIES_' . $i;
	$keys[count($keys)] = 'MODULE_SHIPPING_DP_COST_' . $i;
	}

	return $keys;
}
}
?>
