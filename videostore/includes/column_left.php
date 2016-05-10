<?php

/*

  $Id: column_left.php,v 1.15 2003/07/01 14:34:54 hpdl Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/


//   require(DIR_WS_BOXES . 'column_banner.php');

  if ((USE_CACHE == 'true') && empty($SID)) {

//    echo tep_cache_categories_box();

  } else {

//    include(DIR_WS_BOXES . 'categories.php');

  }

/*  

  if ((USE_CACHE == 'true')) {

    echo tep_cache_series_box();

  } else {

    include(DIR_WS_BOXES . 'series.php');

  }

  if ((USE_CACHE == 'true')) {
    echo tep_cache_producers_box();

  } else {
    include(DIR_WS_BOXES . 'producers.php');

  }

    if ((USE_CACHE == 'true')) {
    echo tep_cache_distributors_box();

  } else {
    include(DIR_WS_BOXES . 'distributors.php');

  } 

*/
  if ((USE_CACHE == 'true')) {
    echo tep_cache_categories_box();
  } else {
   include(DIR_WS_BOXES . 'categories.php');

  }
//    require(DIR_WS_BOXES . 'information.php');
//    require(DIR_WS_BOXES . 'discount_coupon.php');
// BOF: WebMakers.com Added: Banner Manager - Column Banner

// EOF: WebMakers.com Added: Banner Manager - Column Banner
//   require(DIR_WS_BOXES . 'reviews.php');
//   include (DIR_WS_BOXES . 'whos_online.php');

/*
  if ((USE_CACHE == 'true')) {
   //echo tep_cache_info_box();
   require(DIR_WS_BOXES . 'info_pages.php');
}
else{
   require(DIR_WS_BOXES . 'info_pages.php');
}

  if ((USE_CACHE == 'true')) {
   //echo tep_cache_info4_box();
   require(DIR_WS_BOXES . 'info_pages4.php');
}
else{
   require(DIR_WS_BOXES . 'info_pages4.php');
}

*/
//   include (DIR_WS_BOXES . 'cat_request.php');
//   require(DIR_WS_BOXES . 'information.php');
//   require(DIR_WS_BOXES . 'paymentmethods.php');
//   require(DIR_WS_BOXES . 'squaretrade.php');
//   require(DIR_WS_BOXES . 'memberof.php');
//   require(DIR_WS_BOXES . 'affiliate.php');
//   require(DIR_WS_BOXES . 'information.php');
//   include(DIR_WS_BOXES . 'ssl_provider.php');
  require(DIR_WS_BOXES . 'column_banner.php');
  require(DIR_WS_BOXES . 'column_banner.php');
  require(DIR_WS_BOXES . 'column_banner.php');
   require(DIR_WS_BOXES . 'affiliate.php');
   include(DIR_WS_BOXES . 'ssl_provider.php');
?>