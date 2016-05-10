<?php
// /catalog/includes/languages/english/header_tags.php
// WebMakers.com Added: Header Tags Generator v2.3
// Add META TAGS and Modify TITLE
//
// DEFINITIONS FOR /includes/languages/english/header_tags.php

// Define your email address to appear on all pages
define('HEAD_REPLY_TAG_ALL',STORE_OWNER_EMAIL_ADDRESS);

// For all pages not defined or left blank, and for products not defined
// These are included unless you set the toggle switch in each section below to OFF ( '0' )
// The HEAD_TITLE_TAG_ALL is included BEFORE the specific one for the page
// The HEAD_DESC_TAG_ALL is included AFTER the specific one for the page
// The HEAD_KEY_TAG_ALL is included AFTER the specific one for the page
define('HEAD_TITLE_TAG_ALL','');
define('HEAD_DESC_TAG_ALL','Free Travel Video Clips -  Free Shipping - TravelVideoStore.com offer thousands of travel videos worldwide, buy more tourist, destination, cultural, excursion, vacation, tour, and planning travel videos in VHS and DVD to Inlcude Films, Movies, and travel Video DVDs and VHS - We Sell More Travel Videos to More Places');
define('HEAD_KEY_TAG_ALL','travel, video, videos, dvd, DVDs, VHS, film, Films, movie, Movies, tourist, Destination, Vacation, Excursion,  Cultural, Sightseeing, travelogue, documentary, guide, trek, buy, sell, shop, purchase, acquire, view, free shipping');

// DEFINE TAGS FOR INDIVIDUAL PAGES

// index.php
define('HTTA_DEFAULT_ON','0'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_DEFAULT_ON','0'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_DEFAULT_ON','0'); // Include HEAD_DESC_TAG_ALL in Description
define('HEAD_TITLE_TAG_DEFAULT', 'Travel Video Store Featuring VHS and DVD Videos');
define('HEAD_DESC_TAG_DEFAULT','Free Travel Video Clips - Free Shipping -TravelVideoStore.com offers thousands of travel videos worldwide, buy more tourist, destination, cultural, excursion, vacation, tour, and planning videos in VHS and DVD to Include Films, Movies, and Video DVDs and VHS - We Sell More Travel Videos to More Places');
define('HEAD_KEY_TAG_DEFAULT','travel, video, videos, dvd, DVDs, VHS, film, Films, movie, Movies, tourist, Destination, Vacation, Excursion,  Cultural, Sightseeing, travelogue, documentary, guide, trek, buy, sell, shop, purchase, acquire, view, free shipping');

// product_info.php - if left blank in products_description table these values will be used
define('HTTA_PRODUCT_INFO_ON','1');
define('HTKA_PRODUCT_INFO_ON','1');
define('HTDA_PRODUCT_INFO_ON','0');
define('HEAD_TITLE_TAG_PRODUCT_INFO','');
define('HEAD_DESC_TAG_PRODUCT_INFO','Free Travel Video Clips -  Free Shipping - TravelVideoStore.com offers thousands of travel videos worldwide featuring tourist, destination, cultural, excursion, vacation, tour, and planning videos in VHS and DVD to Inlcude Films, Movies, and Video DVDs and VHS - Buy More Travel Videos to More Places');
define('HEAD_KEY_TAG_PRODUCT_INFO','excursion,  cultural, guide, trek, buy, sell, shop, purchase, acquire, view, free shipping');


//define('HEAD_KEY_TAG_PRODUCT_INFO','travel, video, videos, dvd, DVDs, VHS, film, Films, movie, Movies, tourist, Destination, Vacation, Excursion,  Cultural, Sightseeing, travelogue, documentary, guide, trek');

// products_new.php - whats_new
define('HTTA_WHATS_NEW_ON','1');
define('HTKA_WHATS_NEW_ON','1');
define('HTDA_WHATS_NEW_ON','1');
define('HEAD_TITLE_TAG_WHATS_NEW','New Products');
define('HEAD_DESC_TAG_WHATS_NEW','I am ON PRODUCTS_NEW as HEAD_DESC_TAG_WHATS_NEW and over ride the HEAD_DESC_TAG_ALL');
define('HEAD_KEY_TAG_WHATS_NEW','I am on PRODUCTS_NEW as HEAD_KEY_TAG_WHATS_NEW and over ride HEAD_KEY_TAG_ALL');

// specials.php
// If HEAD_KEY_TAG_SPECIALS is left blank, it will build the keywords from the products_names of all products on special
define('HTTA_SPECIALS_ON','0');
define('HTKA_SPECIALS_ON','0');
define('HTDA_SPECIALS_ON','1');
define('HEAD_TITLE_TAG_SPECIALS','Travel Video Specials');
define('HEAD_DESC_TAG_SPECIALS','See our Specials, discounts from 10-75% off');
define('HEAD_KEY_TAG_SPECIALS','');

// product_reviews_info.php and product_reviews.php - if left blank in products_description table these values will be used
define('HTTA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTKA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTDA_PRODUCT_REVIEWS_INFO_ON','1');
define('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO','');
define('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO','');
define('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO','');

?>