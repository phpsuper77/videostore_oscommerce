<?php
   function tep_datetime_short($raw_datetime) {
    if ( ($raw_datetime == '0000-00-00 00:00:00') || ($raw_datetime == '') ) return false;

    $year = (int)substr($raw_datetime, 0, 4);
    $month = (int)substr($raw_datetime, 5, 2);
    $day = (int)substr($raw_datetime, 8, 2);
    $hour = (int)substr($raw_datetime, 11, 2);
    $minute = (int)substr($raw_datetime, 14, 2);
    $second = (int)substr($raw_datetime, 17, 2);

    return strftime(DATE_TIME_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
  }
// USPS Methods.  Added by Greg Deeth
// Alias function for Store configuration values in the Administration Tool.
// Creates a text input box on either side of the option, adds <= OPTION <= and makes a list.
// Remember to add blank default values: 1, 2, , , 5, 6, ...
  function tep_cfg_multiinput_duallist_oz($select_array, $key_value, $key = '') {
    $key_values = explode( ", ", $key_value);
    $string .= '<center>';

    for ($i=0; $i<sizeof($select_array); $i++) {
	$current_key_value = current($key_values);

      $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
      $string .= '<br><input type="text" name="' . $name . '" size="3" value="' . $current_key_value . '"><i>oz</i>';
	$string .= ' <b><</b> ' . $select_array[$i] . ' <u><b><</b></u>';
	next($key_values);
	$current_key_value = current($key_values);
	$string .= '<input type="text" name="' . $name . '" size="3" value="' . $current_key_value . '"><i>oz</i>';
	next($key_values);
    }
    $string .= '<input type="hidden" name="' . $name . '" value="--none--">';

    $string .= '</center>';
    return $string;
  }
  function tep_cfg_multiinput_duallist_lb($select_array, $key_value, $key = '') {
    $key_values = explode( ", ", $key_value);
    $string .= '<center>';

    for ($i=0; $i<sizeof($select_array); $i++) {
	$current_key_value = current($key_values);

      $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
      $string .= '<br><input type="text" name="' . $name . '" size="3" value="' . $current_key_value . '"><i>lbs</i>';
	$string .= ' <b><</b> ' . $select_array[$i] . ' <u><b><</b></u>';
	next($key_values);
	$current_key_value = current($key_values);
	$string .= '<input type="text" name="' . $name . '" size="3" value="' . $current_key_value . '"><i>lbs</i>';
	next($key_values);
    }
    $string .= '<input type="hidden" name="' . $name . '" value="--none--">';

    $string .= '</center>';
    return $string;
  }
?>