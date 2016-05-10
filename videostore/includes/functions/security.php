<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
###### includes/functions/security.php ####
###### Security Pro                    ####
###### FWR Media                       ####
###### www.fwrmedia.co.uk              ####
###### 3rd March 2008                  ####
###### Version 1.0.2                   ####
##########################################*/
// Changelog - removed %(percent) added urldecode
function tep_clean_get__recursive($get_var)
  {
  if (!is_array($get_var))
  return preg_replace("/[^ {}a-zA-Z0-9_.-]/i", "", urldecode($get_var));

  // Add the preg_replace to every element.
  return array_map('tep_clean_get__recursive', $get_var);
  }
function fwr_clean_global($get_var) {
  foreach ($get_var as $key => $value)
  ( isset($GLOBALS[$key]) ? $GLOBALS[$key] = $get_var[$key] : NULL );
}
?>