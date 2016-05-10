<?php
/*
  $Id: cache.php,v 1.11 2003/07/01 14:34:54 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
//! Write out serialized data.
//  write_cache uses serialize() to store $var in $filename.
//  $var      -  The variable to be written out.
//  $filename -  The name of the file to write to.
  function write_cache(&$var, $filename) {
    $filename = DIR_FS_CACHE . $filename;
    $success = false;

// try to open the file
    if ($fp = @fopen($filename, 'w')) {
// obtain a file lock to stop corruptions occuring
      flock($fp, 2); // LOCK_EX
// write serialized data
      fputs($fp, serialize($var));
// release the file lock
      flock($fp, 3); // LOCK_UN
      fclose($fp);
      $success = true;
    }

    return $success;
  }

////
//! Read in seralized data.
//  read_cache reads the serialized data in $filename and
//  fills $var using unserialize().
//  $var      -  The variable to be filled.
//  $filename -  The name of the file to read.
  function read_cache(&$var, $filename, $auto_expire = false){
    $filename = DIR_FS_CACHE . $filename;
    $success = false;

    if (($auto_expire == true) && file_exists($filename)) {
      $now = time();
      $filetime = filemtime($filename);
      $difference = $now - $filetime;

      if ($difference >= $auto_expire) {
        return false;
      }
    }

// try to open file
    if ($fp = @fopen($filename, 'r')) {
// read in serialized data
      $szdata = fread($fp, filesize($filename));
      fclose($fp);
// unserialze the data
      $var = unserialize($szdata);

      $success = true;
    }

    return $success;
  }

////
//! Get data from the cache or the database.
//  get_db_cache checks the cache for cached SQL data in $filename
//  or retreives it from the database is the cache is not present.
//  $SQL      -  The SQL query to exectue if needed.
//  $filename -  The name of the cache file.
//  $var      -  The variable to be filled.
//  $refresh  -  Optional.  If true, do not read from the cache.
  function get_db_cache($sql, &$var, $filename, $refresh = false){
    $var = array();

// check for the refresh flag and try to the data
    if (($refresh == true)|| !read_cache($var, $filename)) {
// Didn' get cache so go to the database.
//      $conn = mysql_connect("localhost", "apachecon", "apachecon");
      $res = tep_db_query($sql);
//      if ($err = mysql_error()) trigger_error($err, E_USER_ERROR);
// loop through the results and add them to an array
      while ($rec = tep_db_fetch_array($res)) {
        $var[] = $rec;
      }
// write the data to the file
      write_cache($var, $filename);
    }
  }

////
//! Cache the categories box
// Cache the categories box
  function tep_cache_categories_box($auto_expire = false, $refresh = false) {
    global $cPath, $language, $languages_id, $tree, $cPath_array, $categories_string, $cat_name;

    if (($refresh == true) || !read_cache($cache_output, 'categories_box-' . $language . '.cache' . $cPath, $auto_expire)) {
      ob_start();
      include(DIR_WS_BOXES . 'categories.php');
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'categories_box-' . $language . '.cache' . $cPath);
    }

    return $cache_output;
  }

   function tep_cache_info_box($auto_expire = false, $refresh = false){
    global $language, $languages_id;

    if (($refresh == true) || !read_cache($cache_output, 'info_box-' . $language . '.cache_info', $auto_expire)) {
      ob_start();
      require(DIR_WS_BOXES . 'info_pages.php');
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'info_box-' . $language . '.cache_info');
    }

    return $cache_output;

}

   function tep_cache_info4_box($auto_expire = false, $refresh = false){
    global $language, $languages_id;

    if (($refresh == true) || !read_cache($cache_output, 'info_box4-' . $language . '.cache_info4', $auto_expire)) {
      ob_start();
      require(DIR_WS_BOXES . 'info_pages4.php');
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'info_box4-' . $language . '.cache_info4');
    }

    return $cache_output;

}
   function tep_cache_best_seller_box($auto_expire = false, $refresh = false){
    global $cPath, $language, $languages_id, $current_category_id, $ref;

    if (($refresh == true) || !read_cache($cache_output, 'top_sellers-' . $language . '.cache' . $cPath.'_'.$ref, $auto_expire)) {
      ob_start();
      include(DIR_WS_BOXES . 'best_sellers.php');
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'top_sellers-' . $language . '.cache' . $cPath."_".$ref);
    }

    return $cache_output;
	
}

   function tep_cache_new_products_box($auto_expire = false, $refresh = false){
    global $language, $languages_id, $new_products_category_id, $currencies;

    if (($refresh == true) || !read_cache($cache_output, 'new_products-' . $language . '.cache', $auto_expire)) {
      ob_start();
      include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS);
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'new_products-' . $language . '.cache');
    }

    return $cache_output;
	
}

   function tep_cache_featured_box($auto_expire = false, $refresh = false){
    global $language, $languages_id, $currencies, $new_products_category_id;

    if (($refresh == true) || !read_cache($cache_output, 'featured-' . $language . '.cache', $auto_expire)) {
      ob_start();
      include(DIR_WS_MODULES . FILENAME_FEATURED);
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'featured-' . $language . '.cache');
    }

    return $cache_output;
	
}

   function tep_cache_category_affiliate_box($auto_expire = false, $refresh = false){
    global $HTTP_GET_VARS, $language, $languages_id, $currencies, $individual_banner_id, $affiliate_id, $ref;

if ($affiliate_id=='') $affiliate_id = $HTTP_GET_VARS['ref'];

    if (($refresh == true) || !read_cache($cache_output, 'affiliate_top-' . $language . '.cache_'.$individual_banner_id.'_'.$affiliate_id.$ref, $auto_expire)) {
      ob_start();
      include "top_affiliate_category.php";
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'affiliate_top-' . $language . '.cache_'.$individual_banner_id.'_'.$affiliate_id.$ref);
    }

    return $cache_output;
	
}


  // Cache the distributors box
  function tep_cache_distributors_box($auto_expire = false, $refresh = false) {
    global $HTTP_GET_VARS, $language;

    $distributors_id = '';
    if (isset($HTTP_GET_VARS['distributors_id']) && tep_not_null($HTTP_GET_VARS['distributors_id'])) {
      $distributors_id = $HTTP_GET_VARS['distributors_id'];
    }

    if (($refresh == true) || !read_cache($cache_output, 'distributors_box-' . $language . '.cache' . $distributors_id, $auto_expire)) {
      ob_start();
      include(DIR_WS_BOXES . 'distributors.php');
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'distributors_box-' . $language . '.cache' . $distributors_id);
    }

    return $cache_output;
  }
  
  // Cache the producers box
  function tep_cache_producers_box($auto_expire = false, $refresh = false) {
    global $HTTP_GET_VARS, $language;

    $producers_id = '';
    if (isset($HTTP_GET_VARS['producers_id']) && tep_not_null($HTTP_GET_VARS['producers_id'])) {
      $producers_id = $HTTP_GET_VARS['producers_id'];
    }

    if (($refresh == true) || !read_cache($cache_output, 'producers_box-' . $language . '.cache' . $producers_id, $auto_expire)) {
      ob_start();
      include(DIR_WS_BOXES . 'producers.php');
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'producers_box-' . $language . '.cache' . $producers_id);
    }

    return $cache_output;
  }
  
  // Cache the series box
  function tep_cache_series_box($auto_expire = false, $refresh = false) {
    global $HTTP_GET_VARS, $language;

    $series_id = '';
    if (isset($HTTP_GET_VARS['series_id']) && tep_not_null($HTTP_GET_VARS['series_id'])) {
      $series_id = $HTTP_GET_VARS['series_id'];
    }

    if (($refresh == true) || !read_cache($cache_output, 'series_box-' . $language . '.cache' . $series_id, $auto_expire)) {
      ob_start();
      include(DIR_WS_BOXES . 'series.php');
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'series_box-' . $language . '.cache' . $series_id);
    }

    return $cache_output;
  }

////
//! Cache the also purchased module
// Cache the also purchased module
  function tep_cache_also_purchased($auto_expire = false, $refresh = false) {
    global $HTTP_GET_VARS, $language, $languages_id;

    if (($refresh == true) || !read_cache($cache_output, 'also_purchased-' . $language . '.cache' . $HTTP_GET_VARS['products_id'], $auto_expire)) {
      ob_start();
      include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'also_purchased-' . $language . '.cache' . $HTTP_GET_VARS['products_id']);
    }

    return $cache_output;
  }

   function tep_cache_category_affiliate_product($auto_expire = false, $refresh = false){
    global $HTTP_GET_VARS, $language, $languages_id, $currencies, $affiliate_banner_id, $affiliate_id, $products_id, $ref;

if ($affiliate_id=='') $affiliate_id = $HTTP_GET_VARS['ref'];

    if (($refresh == true) || !read_cache($cache_output, 'top_affiliate_product-' . $language . '.cache_'.$individual_banner_id.'_'.$affiliate_id.'_'.$products_id.$ref, $auto_expire)) {
      ob_start();
      include "top_affiliate_product.php";
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'top_affiliate_product-' . $language . '.cache_'.$individual_banner_id.'_'.$affiliate_id.'_'.$products_id.$ref);
    }
    return $cache_output;
  }

?>