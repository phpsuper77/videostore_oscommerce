<?php
/*
  $Id: application_top.php,v 1.162 2003/07/12 09:39:03 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

$HTTP_GET_VARS['language'] = "english";

// Start the clock for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// Set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);
//  error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
//  ini_set('error_reporting', E_ALL);
//  ini_set('display_errors', 'On');
//  ini_set('display_startup_errors', 'On');


    if ( $_SERVER['REMOTE_ADDR'] == '96.254.126.27' ) {
           ini_set('error_reporting', E_STRICT & ~E_DEPRECATED & ~E_NOTICE);
           ini_set('display_errors', '1');
           ini_set('display_startup_errors', 'On');
           }





// Check if register_globals is enabled.
// Since this is a temporary measure this message is hardcoded. The requirement will be removed before 2.2 is finalized.
  if (function_exists('ini_get')) {
    ini_get('register_globals') or exit('FATAL ERROR: register_globals is disabled in php.ini, please enable it!');
  }

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');

// Include application configuration parameters
  require('includes/configure.php');

// Define the project version
  define('PROJECT_VERSION', 'osCommerce 2.2-MS2');

// set php_self in the local scope
  $PHP_SELF = (isset($HTTP_SERVER_VARS['PHP_SELF']) ? $HTTP_SERVER_VARS['PHP_SELF'] : $HTTP_SERVER_VARS['SCRIPT_NAME']);

// Used in the "Backup Manager" to compress backups
  define('LOCAL_EXE_GZIP', '/usr/bin/gzip');
  define('LOCAL_EXE_GUNZIP', '/usr/bin/gunzip');
  define('LOCAL_EXE_ZIP', '/usr/local/bin/zip');
  define('LOCAL_EXE_UNZIP', '/usr/local/bin/unzip');

// include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// customization for the design layout
  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)

// Define how do we update currency exchange rates
// Possible values are 'oanda' 'xe' or ''
  define('CURRENCY_SERVER_PRIMARY', 'ECBank');
  define('CURRENCY_SERVER_BACKUP', 'xe');

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// set application wide parameters
//  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
//  while ($configuration = tep_db_fetch_array($configuration_query)) {
//    define($configuration['cfgKey'], $configuration['cfgValue']);
//  }

// set application wide parameters
// Configuration Cache modification start
  require ('includes/configuration_cache_read.php');
// Configuration Cache modification end


// define our general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

// include the password crypto functions
  require(DIR_WS_FUNCTIONS . 'password_funcs.php');

// initialize the logger class
  require(DIR_WS_CLASSES . 'logger.php');

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// check to see if php implemented session management functions - if not, include php3/php4 compatible session class
  if (!function_exists('session_start')) {
    define('PHP_SESSION_NAME', 'osCAdminID');
    define('PHP_SESSION_PATH', '/');
    define('PHP_SESSION_SAVE_PATH', SESSION_WRITE_DIRECTORY);

    include(DIR_WS_CLASSES . 'sessions.php');
  }

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// set the session name and save path
  tep_session_name('osCAdminID');
  tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
   if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, DIR_WS_ADMIN);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', DIR_WS_ADMIN);
  }

// lets start our session
//ob_start();
  tep_session_start();
//ob_end_flush();

// set the language
  if (!tep_session_is_registered('language') || isset($HTTP_GET_VARS['language'])) {
    if (!tep_session_is_registered('language')) {
      tep_session_register('language');
      tep_session_register('languages_id');
    }

    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language();

    if (isset($HTTP_GET_VARS['language']) && tep_not_null($HTTP_GET_VARS['language'])) {
      $lng->set_language($HTTP_GET_VARS['language']);
    } else {
      $lng->get_browser_language();
    }

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
  }

// include the language translations
  require(DIR_WS_LANGUAGES . $language . '.php');
  $current_page = basename($PHP_SELF);
  if (file_exists(DIR_WS_LANGUAGES . $language . '/' . $current_page)) {
    include(DIR_WS_LANGUAGES . $language . '/' . $current_page);
  }

// define our localization functions
  require(DIR_WS_FUNCTIONS . 'localization.php');

// Include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

// setup our boxes
  require(DIR_WS_CLASSES . 'table_block.php');
  require(DIR_WS_CLASSES . 'box.php');

// initialize the message stack for output messages
  require(DIR_WS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;

// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

// entry/item info classes
  require(DIR_WS_CLASSES . 'object_info.php');

// email classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

// file uploading class
  require(DIR_WS_CLASSES . 'upload.php');

// calculate category path
  if (isset($HTTP_GET_VARS['cPath'])) {
    $cPath = $HTTP_GET_VARS['cPath'];
  } else {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }

// default open navigation box
  if (!tep_session_is_registered('selected_box')) {
    tep_session_register('selected_box');
    $selected_box = 'configuration';
  }

  if (isset($HTTP_GET_VARS['selected_box'])) {
    $selected_box = $HTTP_GET_VARS['selected_box'];
  }

// the following cache blocks are used in the Tools->Cache section
// ('language' in the filename is automatically replaced by available languages)
  $cache_blocks = array(array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories', 'file' => 'categories_box-language.cache', 'multiple' => true),
						array('title' => 'Distributors Box', 'code' => 'distributors', 'file' => 'distributors_box-language.cache', 'multiple' => true),
						array('title' => 'Producers Box', 'code' => 'producers', 'file' => 'producers_box-language.cache', 'multiple' => true),
						array('title' => 'Series Box', 'code' => 'series', 'file' => 'series_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_ALSO_PURCHASED, 'code' => 'also_purchased', 'file' => 'also_purchased-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_INFORMATION, 'code' => 'info_box', 'file' => 'info_box-language.cache_info', 'multiple' => false),
                        array('title' => TEXT_CACHE_INFORMATION4, 'code' => 'info_box4', 'file' => 'info_box4-language.cache_info4', 'multiple' => false),
                        array('title' => TEXT_CACHE_TOP_SELLERS, 'code' => 'top_sellers', 'file' => 'top_sellers-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_NEW_PRODUCTS, 'code' => 'new_products', 'file' => 'new_products-language.cache', 'multiple' => false),
                        array('title' => TEXT_CACHE_FEATURED, 'code' => 'featured', 'file' => 'featured-language.cache', 'multiple' => false),
                        array('title' => TEXT_CACHE_AFFILIATE, 'code' => 'affiliate_category', 'file' => 'affiliate_top-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_AFFILIATE_PRODUCT, 'code' => 'affiliate_product', 'file' => 'top_affiliate_product-language.cache', 'multiple' => true)
                       );

// check if a default currency is set
  if (!defined('DEFAULT_CURRENCY')) {
    $messageStack->add(ERROR_NO_DEFAULT_CURRENCY_DEFINED, 'error');
  }

// check if a default language is set
  if (!defined('DEFAULT_LANGUAGE')) {
    $messageStack->add(ERROR_NO_DEFAULT_LANGUAGE_DEFINED, 'error');
  }

  if (function_exists('ini_get') && ((bool)ini_get('file_uploads') == false) ) {
    $messageStack->add(WARNING_FILE_UPLOADS_DISABLED, 'warning');
  }

  // Include OSC-AFFILIATE
  require('includes/affiliate_application_top.php');

  define('FILENAME_STATS_SALES_REPORT', 'stats_sales_report.php');
  define('FILENAME_STATS_SALES_REPORT2', 'stats_sales_report2.php');

  //###INCLUDE FOR ORDERS.PHP
  define('TABLE_HEADING_ORDERNO', 'Order #');
  define('TABLE_PAYMENTMETHOD', 'Payment Method');
  define('TABLE_SHIPMETHOD', 'Shipping Method');
  //#########################

require(DIR_WS_INCLUDES . 'add_ccgvdc_application_top.php');  // ICW CREDIT CLASS Gift Voucher Addittion
// vendor contribution
  define('TABLE_VENDORS', 'vendors');
  define('TABLE_PRODUCTS_TO_VENDORS', 'products_to_vendors');
  define('TABLE_VENDORS_TERMS', 'vendors_terms');
  define('FILENAME_VENDORS', 'vendors.php');
  define('FILENAME_PRODS_VENDORS', 'prods_by_vendor.php');
define('FILENAME_IMAGE_CHECK', 'imagecheck.php');
?>