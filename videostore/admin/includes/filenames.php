<?php
/*
  $Id: filenames.php,v 1.1 2003/06/20 00:18:30 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// define the filenames used in the project
  define('FILENAME_BACKUP', 'backup.php');
  define('FILENAME_BANNER_MANAGER', 'banner_manager.php');
  define('FILENAME_BANNER_STATISTICS', 'banner_statistics.php');
  define('FILENAME_CACHE', 'cache.php');
  define('FILENAME_CATALOG_ACCOUNT_HISTORY_INFO', 'account_history_info.php');
  define('FILENAME_CATEGORIES', 'categories.php');
  define('FILENAME_CONFIGURATION', 'configuration.php');
  define('FILENAME_COUNTRIES', 'countries.php');
  define('FILENAME_CURRENCIES', 'currencies.php');
  define('FILENAME_CUSTOMERS', 'customers.php');
  define('FILENAME_DEFAULT', 'index.php');
  define('FILENAME_DEFINE_LANGUAGE', 'define_language.php');
  define('FILENAME_DEFINE_MAINPAGE', 'define_mainpage.php');  // MaxiDVD Added WYSIWYG HTML Area
  define('FILENAME_DEFINE_FEED', 'feedfiles.php');  // For FeedFile Links
  define('FILENAME_DEFINE_FROOGLE', 'froogletxtfile.php');  // For Froogle Form Feed
  define('FILENAME_DEFINE_YAHOO', 'yahootxtfile.php');  // For Yahoo Form Feed
  define('FILENAME_DEFINE_AMAZON', 'amazonproducttxtfile.php');  // For Amazon Form Feed
  define('FILENAME_DEFINE_AMAZONCA', 'amazoncaproducttxtfile.php');  // For Amazon CA Form Feed
  define('FILENAME_DEFINE_BIZRATE', 'bizrate.php');  // For Bizrate Form Feed
  define('FILENAME_DEFINE_MYSIMON', 'mysimon.php');  // For MySimon Form Feed
  define('FILENAME_DEFINE_SHOPPING', 'shopping.php');  // For Shopping.com Form Feed
  define('FILENAME_DEFINE_NEXTAG', 'nextag.php');  // For Nextag Form Feed
  define('FILENAME_FILE_MANAGER', 'file_manager.php');
  define('FILENAME_GEO_ZONES', 'geo_zones.php');
  define('FILENAME_LANGUAGES', 'languages.php');
  define('FILENAME_MAIL', 'mail.php');
  define('FILENAME_MANUFACTURERS', 'manufacturers.php');
  define('FILENAME_DISTRIBUTORS', 'distributors.php');
  define('FILENAME_PRODUCERS', 'producers.php');
  define('FILENAME_SERIES', 'series.php');
  define('FILENAME_MODULES', 'modules.php');
  define('FILENAME_NEWSLETTERS', 'newsletters.php');
  define('FILENAME_ORDERS', 'orders.php');
  define('FILENAME_ORDERS_EDIT', 'edit_orders.php');
  define('FILENAME_ORDERS_INVOICE', 'invoice.php');
  define('FILENAME_ORDERS_PACKINGSLIP', 'packingslip.php');
  define('FILENAME_ORDERS_PACKINGSLIP_ALL', 'packingslip_all.php'); //CHANGES MADE FOR ORDERS.PHP
  define('FILENAME_ORDERS_STATUS', 'orders_status.php');
  define('FILENAME_POPUP_IMAGE', 'popup_image.php');
  define('FILENAME_PRODUCTS_ATTRIBUTES', 'products_attributes.php');
  define('FILENAME_PRODUCTS_EXPECTED', 'products_expected.php');
  define('FILENAME_REFERRALS', 'referrals.php'); //rmh referrals
  define('FILENAME_REVIEWS', 'reviews.php');
  define('FILENAME_SERVER_INFO', 'server_info.php');
  define('FILENAME_SHIPPING_MODULES', 'shipping_modules.php');
  define('FILENAME_SPECIALS', 'specials.php');
  define('FILENAME_STATS_CUSTOMERS', 'stats_customers.php');
  define('FILENAME_STATS_PRODUCTS_PURCHASED', 'stats_products_purchased.php');
  define('FILENAME_STATS_PRODUCTS_VIEWED', 'stats_products_viewed.php');
  define('FILENAME_STATS_REFERRAL_SOURCES', 'stats_referral_sources.php'); //rmh referrals
  define('FILENAME_TAX_CLASSES', 'tax_classes.php');
  define('FILENAME_TAX_RATES', 'tax_rates.php');
  define('FILENAME_WHOS_ONLINE', 'whos_online.php');
  define('FILENAME_WHOS_ONLINE_CART', 'whos_online_cart.php');
  define('FILENAME_ZONES', 'zones.php');
  define('FILENAME_FEATURED', 'featured.php');
  define('FILENAME_FEATURED_PRODUCTS', 'featured_products.php');
  define('FILENAME_STATS_UNSOLD_CARTS', 'stats_unsold_carts.php');
  define('FILENAME_STATS_MONTHLY_SALES', 'stats_monthly_sales.php');
  define('FILENAME_SEO_ASSISTANT', 'seo_assistant.php');
  define('FILENAME_QUICK_STOCKUPDATE', 'quick_stockupdate.php');
  define('FILENAME_DEFINE_VHS', 'define_mainpage1.php');  // MaxiVHS Added WYSIWYG HTML Area
  define('FILENAME_DEFINE_DVD', 'define_mainpage2.php');  // MaxiDVD Added WYSIWYG HTML Area
  define('FILENAME_DEFINE_CD', 'define_mainpage3.php');   // MaxiCD Added WYSIWYG HTML Area
  // VJ Links Manager v1.00 begin
    define('FILENAME_LINKS', 'links.php');
    define('FILENAME_LINK_CATEGORIES', 'link_categories.php');
    define('FILENAME_LINKS_CONTACT', 'links_contact.php');
// VJ Links Manager v1.00 end

  //KEYWORD SHOW
  define('FILENAME_KEYWORD_SHOW', 'keyword_show.php');
  define('FILENAME_KEYWORD_SHOW_CONFIG', 'keyword_show_config.php');

  //Discounts
  define('FILENAME_DISCOUNT_ENTRY', 'discount_entry.php');
  define('FILENAME_DISCOUNT','discount.php');
  define('FILENAME_DISCOUNT_REDEEM','discount_redeem.php');
//vendor contribution
  define('FILENAME_VENDORS', 'vendors.php');
  define('FILENAME_PRODS_VENDORS', 'prods_by_vendor.php');
  define('FILENAME_VENDOR_INFO', 'vendors_info.php'); // VENDORS INFO
  define('FILENAME_VENDOR_ORDER_PRODUCTS', 'vendor_order_products.php'); // VENDOR ORDER PRODUCTS
  define('FILENAME_VENDOR_CUSTOMER_PRODUCTS', 'vendor_customer_products.php'); // VENDOR CUSTOMER PRODUCTS
  define('FILENAME_VENDOR_ORDER_PRODUCTS_ALL', 'vendor_order_products_all.php'); // VENDOR ORDER PRODUCTS ALL
  define('FILENAME_VENDORS_ENTRY', 'vendors_entry.php'); // VENDOR ENTRY

//Product Notifications
  define('FILENAME_STATS_PRODUCTS_NOTIFICATIONS', 'stats_products_notifications.php');
// Backorder Report
define('FILENAME_STATS_PRODUCTS_BACKORDERED', 'stats_products_backordered.php');
// FAQ SYSTEM 2.1

  define('FILENAME_FAQ_MANAGER', 'faq_manager.php');
  define('FILENAME_FAQ_VIEW', 'faq_view.php');
  define('FILENAME_FAQ_VIEW_ALL', 'faq_view_all.php');

// FAQ SYSTEM 2.1
define('FILENAME_PAGE_MANAGER', 'page_manager.php');
//events_calendar
define('FILENAME_EVENTS_MANAGER', 'events_manager.php');
define('FILENAME_KEYWORDS', 'stats_keywords.php');
define('FILENAME_USER_TRACKING', 'user_tracking.php');
define('FILENAME_USER_TRACKING_CONFIG', 'user_tracking_config.php');
define('FILENAME_RECOVER_CART_SALES', 'recover_cart_sales.php');
define('FILENAME_STATS_RECOVER_CART_SALES', 'stats_recover_cart_sales.php');
define('FILENAME_CATALOG_LOGIN', 'login.php');
define('FILENAME_STATS_WISHLISTS', 'stats_wishlists.php');

// define the filenames used in the project
  define('FILENAME_TESTIMONIALS_MANAGER', 'testimonials_manager.php');
//DANIEL:begin
  define('FILENAME_RELATED_PRODUCTS', 'products_options.php');
//DANIEL:end

define('FILENAME_TRACK_FEDEX', 'track_fedex.php');
define('FILENAME_SHIP_FEDEX', 'ship_fedex.php');
define('FILENAME_SHIPPING_MANIFEST', 'shipping_manifest.php');

define('FILENAME_ORDERS_USPS_SHIP', 'usps_ship.php');

  define('FILENAME_VENDOR_PAYMENTS', 'vendor_payments.php');
  define('FILENAME_VENDOR_PO', 'vendor_purchase_orders.php');

  define('FILENAME_MYSQL_PERFORMANCE', 'mysqlperformance.php');
//BOF Dynamic Sitemap
  define('FILENAME_SITEMAP', 'sitemap.php');
  define('FILENAME_CREATE_XML_SITEMAPS', 'create_xml_sitemaps.php');
//EOF Dynamic Sitemap
?>