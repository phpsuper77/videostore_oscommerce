<?php
/*
  $Id: column_right.php,v 1.17 2003/06/09 22:06:41 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
//  include(DIR_WS_BOXES . 'add_favourites.php');
  require(DIR_WS_BOXES . 'search.php');
  include(DIR_WS_BOXES . 'currencies.php');
 // include(DIR_WS_BOXES . 'discount_coupon.php');
  //require(DIR_WS_BOXES . 'shopping_cart.php');

  if (!tep_session_is_registered('customer_id') && ENABLE_PAGE_CACHE == 'true' && class_exists('page_cache') ) {
        echo "<%CART_CACHE%>";
        } else {
        	require(DIR_WS_BOXES . 'shopping_cart.php');
        }

  //if($wishList->count_wishlist() != '0') {
	require(DIR_WS_BOXES . 'wishlist.php');
//  }

//   require(DIR_WS_BOXES . 'customer_testimonials.php');



  if (isset($HTTP_GET_VARS['products_id'])) include(DIR_WS_BOXES . 'manufacturer_info.php');

  //if (tep_session_is_registered('customer_id')) include(DIR_WS_BOXES . 'order_history.php');
	include(DIR_WS_MODULES . 'viewed_products.php');
  if (isset($HTTP_GET_VARS['products_id'])) {
    if (tep_session_is_registered('customer_id')) {
      $check_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "' and global_product_notifications = '1'");
      $check = tep_db_fetch_array($check_query);
      if ($check['count'] > 0) {

  if ((USE_CACHE == 'false')) {
   	echo tep_cache_best_seller_box();
        //include(DIR_WS_BOXES . 'best_sellers.php');
}
else{
	include(DIR_WS_BOXES . 'best_sellers.php');
}

      } else {
        //include(DIR_WS_BOXES . 'product_notifications.php');
      }
    } else {
      //include(DIR_WS_BOXES . 'product_notifications.php');
    }
  } else {
  if ((USE_CACHE == 'false')) {
   	echo tep_cache_best_seller_box();
        //include(DIR_WS_BOXES . 'best_sellers.php');
}
else{
	include(DIR_WS_BOXES . 'best_sellers.php');
}

  }
  if (isset($HTTP_GET_VARS['products_id'])) {
//    if (basename($PHP_SELF) != FILENAME_TELL_A_FRIEND) { 
                include(DIR_WS_BOXES . 'tell_a_friend.php');
    }
/*    if (isset($HTTP_GET_VARS['products_id'])) {
                include(DIR_WS_BOXES . 'product_notifications.php');
  } else {
   //  include(DIR_WS_BOXES . 'product_notifications.php');

  }
*/
//    include(DIR_WS_BOXES . 'specials.php');
//    include(DIR_WS_BOXES . 'whats_new.php');
// BOF: WebMakers.com Added: Banner Manager - Column Banner
//    require(DIR_WS_BOXES . 'column_banner_right.php');
//    require(DIR_WS_BOXES . 'column_banner.php');
 // EOF: WebMakers.com Added: Banner Manager - Column Banner

 /* if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
      include(DIR_WS_BOXES . 'languages.php');
   include(DIR_WS_BOXES . 'currencies.php');
  }*/

// if ($request_type == NONSSL) {   /* only show adsense in NON SSL else it causes warning */
//     include(DIR_WS_BOXES . 'resources.php');
//  }
//events_calendar
//require(DIR_WS_BOXES . 'calendar.php');
//   require(DIR_WS_BOXES . 'paymentmethods.php');
//   require(DIR_WS_BOXES . 'information.php');

//      require(DIR_WS_BOXES . 'affiliate.php');
//   include(DIR_WS_BOXES . 'ssl_provider.php');
?>