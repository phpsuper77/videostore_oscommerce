<?php
/*
  $Id: database_tables.php,v 1.1 2003/06/20 00:18:30 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// define the database table names used in the project
  define('TABLE_ADDRESS_BOOK', 'address_book');
  define('TABLE_ADDRESS_FORMAT', 'address_format');
  define('TABLE_BANNERS', 'banners');
  define('TABLE_BANNERS_HISTORY', 'banners_history');
  define('TABLE_CATEGORIES', 'categories');
  define('TABLE_CATEGORIES_DESCRIPTION', 'categories_description');
  define('TABLE_CONFIGURATION', 'configuration');
  define('TABLE_CONFIGURATION_GROUP', 'configuration_group');
  define('TABLE_COUNTRIES', 'countries');
  define('TABLE_CURRENCIES', 'currencies');
  define('TABLE_CUSTOMERS', 'customers');
  define('TABLE_CUSTOMERS_BASKET', 'customers_basket');
  define('TABLE_CUSTOMERS_BASKET_ATTRIBUTES', 'customers_basket_attributes');
  define('TABLE_CUSTOMERS_INFO', 'customers_info');
  define('TABLE_LANGUAGES', 'languages');
  define('TABLE_MANUFACTURERS', 'manufacturers');
  define('TABLE_MANUFACTURERS_INFO', 'manufacturers_info');
  define('TABLE_NEWSLETTERS', 'newsletters');
  define('TABLE_ORDERS', 'orders');
  define('TABLE_ORDERS_PRODUCTS', 'orders_products');
  define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES', 'orders_products_attributes');
  define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', 'orders_products_download');
  define('TABLE_ORDERS_STATUS', 'orders_status');
  define('TABLE_ORDERS_STATUS_HISTORY', 'orders_status_history');
  define('TABLE_ORDERS_TOTAL', 'orders_total');
  define('TABLE_PRODUCTS', 'products');
  define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD', 'products_attributes_download');
  define('TABLE_PRODUCTS_DESCRIPTION', 'products_description');
  define('TABLE_PRODUCTS_DATA', 'products_data'); //newly inserted for products_data
  define('TABLE_PRODUCTS_NOTIFICATIONS', 'products_notifications');
  define('TABLE_PRODUCTS_OPTIONS', 'products_options');
  define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_values');
  define('TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS', 'products_options_values_to_products_options');
  define('TABLE_PRODUCTS_TO_CATEGORIES', 'products_to_categories');
  define('TABLE_REVIEWS', 'reviews');
  define('TABLE_REVIEWS_DESCRIPTION', 'reviews_description');
  define('TABLE_SESSIONS', 'sessions');
  define('TABLE_SOURCES', 'sources'); //rmh referrals
  define('TABLE_SOURCES_OTHER', 'sources_other'); //rmh referrals
  define('TABLE_SPECIALS', 'specials');
  define('TABLE_TAX_CLASS', 'tax_class');
  define('TABLE_TAX_RATES', 'tax_rates');
  define('TABLE_GEO_ZONES', 'geo_zones');
  define('TABLE_ZONES_TO_GEO_ZONES', 'zones_to_geo_zones');
  define('TABLE_WHOS_ONLINE', 'whos_online');
  define('TABLE_ZONES', 'zones');
  define('TABLE_MEDIA_TYPE', 'products_media_types');
  define('TABLE_VIDEO_FORMAT', 'products_video_formats');
  define('TABLE_REGION_CODE', 'products_region_codes');
  define('TABLE_SET_TYPE', 'products_set_types');
  define('TABLE_PACKAGING_TYPE', 'products_packaging_types');
  define('TABLE_DISTRIBUTORS', 'distributors');
  define('TABLE_PRODUCERS', 'producers');
  define('TABLE_SERIES', 'series');
  define('TABLE_FEATURED', 'featured');
  define('TABLE_AUDIO', 'products_audios');
  define('TABLE_ASPECT_RATIO', 'products_aspect_ratios');
// RJW Begin Meta Tags Code
  define('TABLE_META_TAGS', 'meta_tags');
// RJW End Meta Tags Code
  define('TABLE_SEO_GOOGLE', 'seo_google');
  define('TABLE_SEO_YAHOO', 'seo_yahoo');
  // VJ Links Manager v1.00 begin
    define('TABLE_LINK_CATEGORIES', 'link_categories');
    define('TABLE_LINK_CATEGORIES_DESCRIPTION', 'link_categories_description');
    define('TABLE_LINKS', 'links');
    define('TABLE_LINKS_DESCRIPTION', 'links_description');
    define('TABLE_LINKS_TO_LINK_CATEGORIES', 'links_to_link_categories');
    define('TABLE_LINKS_STATUS', 'links_status');
// VJ Links Manager v1.00 end

  define('TABLE_DISCOUNTS', 'discounts'); //DISCOUNTS
  define('TABLE_DISCOUNT_CUSTOMER', 'discount_customers'); //DISCOUNTS
// vendor contribution
  define('TABLE_VENDORS', 'vendors');
  define('TABLE_PRODUCTS_TO_VENDORS', 'products_to_vendors');
  define('TABLE_VENDORS_TERMS', 'vendors_terms');
  define('TABLE_VENDORS_PRODUCT_PAYMENT_TYPE', 'vendors_product_payment_type');
  define('TABLE_VENDORS_PRODUCT_SALE_TYPE', 'vendors_product_sale_type');
  define('TABLE_VENDOR_PAYMENTS', 'vendor_payments');
  define('TABLE_VENDOR_PO', 'vendor_purchase_orders');
  define('TABLE_VENDOR_PO_DETAILS', 'vendor_purchase_order_details');

// FAQ SYSTEM 2.1

  define('TABLE_FAQ', 'faq');

// FAQ SYSTEM 2.1
define('TABLE_PAGES', 'pages');
define('TABLE_PAGES_DESCRIPTION', 'pages_description');
//events_calendar
define('TABLE_EVENTS_CALENDAR', 'events_calendar');
define('TABLE_USER_TRACKING', 'user_tracking'); 
define('TABLE_SCART', 'scart');
// define the database table names used in the project
  define('TABLE_TESTIMONIALS', 'customer_testimonials');
//DANIEL: begin
  define('TABLE_PRODUCTS_OPTIONS_PRODUCTS', 'products_options_products');
//DANIEL: end
define('TABLE_HOLDING_ORDERS', 'holding_orders');
define('TABLE_HOLDING_ORDERS_PRODUCTS', 'holding_orders_products');
define('TABLE_HOLDING_ORDERS_TOTAL', 'holding_orders_total');
define('TABLE_HOLDING_ORDERS_PRODUCTS_ATTRIBUTES', 'holding_orders_products_attributes');

define('TABLE_WISHLIST', 'customers_wishlist');
define('TABLE_WISHLIST_ATTRIBUTES', 'customers_wishlist_attributes');

	define('TABLE_SHIPPING_MANIFEST','shipping_manifest');
        define('TABLE_MYSQL_PERFORMANCE', 'qlog');
//BOF Dynamic Sitemap
  define('TABLE_SITEMAP_EXCLUDE', 'sitemap_exclude');
//EOF Dynamic Sitemap
?>