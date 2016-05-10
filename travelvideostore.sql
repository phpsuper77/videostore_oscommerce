-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2016 at 09:24 AM
-- Server version: 5.6.26
-- PHP Version: 5.5.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travelvideostore`
--

-- --------------------------------------------------------

--
-- Table structure for table `address_book`
--

CREATE TABLE IF NOT EXISTS `address_book` (
  `address_book_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL DEFAULT '0',
  `entry_gender` char(1) NOT NULL DEFAULT '',
  `entry_company` varchar(32) DEFAULT NULL,
  `entry_firstname` varchar(32) NOT NULL DEFAULT '',
  `entry_lastname` varchar(32) NOT NULL DEFAULT '',
  `entry_street_address` varchar(64) NOT NULL DEFAULT '',
  `entry_suburb` varchar(32) DEFAULT NULL,
  `entry_postcode` varchar(10) NOT NULL DEFAULT '',
  `entry_city` varchar(32) NOT NULL DEFAULT '',
  `entry_state` varchar(32) DEFAULT NULL,
  `entry_country_id` int(11) NOT NULL DEFAULT '0',
  `entry_zone_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `address_format`
--

CREATE TABLE IF NOT EXISTS `address_format` (
  `address_format_id` int(11) NOT NULL,
  `address_format` varchar(128) NOT NULL DEFAULT '',
  `address_summary` varchar(48) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_affiliate`
--

CREATE TABLE IF NOT EXISTS `affiliate_affiliate` (
  `affiliate_id` int(11) NOT NULL,
  `affiliate_gender` char(1) NOT NULL DEFAULT '',
  `affiliate_firstname` varchar(32) NOT NULL DEFAULT '',
  `affiliate_lastname` varchar(32) NOT NULL DEFAULT '',
  `affiliate_dob` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_email_address` varchar(96) NOT NULL DEFAULT '',
  `affiliate_telephone` varchar(32) NOT NULL DEFAULT '',
  `affiliate_fax` varchar(32) NOT NULL DEFAULT '',
  `affiliate_password` varchar(40) NOT NULL DEFAULT '',
  `affiliate_homepage` varchar(96) NOT NULL DEFAULT '',
  `affiliate_street_address` varchar(64) NOT NULL DEFAULT '',
  `affiliate_suburb` varchar(64) NOT NULL DEFAULT '',
  `affiliate_city` varchar(32) NOT NULL DEFAULT '',
  `affiliate_postcode` varchar(10) NOT NULL DEFAULT '',
  `affiliate_state` varchar(32) NOT NULL DEFAULT '',
  `affiliate_country_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_zone_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_agb` tinyint(4) NOT NULL DEFAULT '0',
  `affiliate_company` varchar(60) NOT NULL DEFAULT '',
  `affiliate_company_taxid` varchar(64) NOT NULL DEFAULT '',
  `affiliate_commission_percent` decimal(4,2) NOT NULL DEFAULT '0.00',
  `affiliate_payment_check` varchar(100) NOT NULL DEFAULT '',
  `affiliate_payment_paypal` varchar(64) NOT NULL DEFAULT '',
  `affiliate_payment_bank_name` varchar(64) NOT NULL DEFAULT '',
  `affiliate_payment_bank_branch_number` varchar(64) NOT NULL DEFAULT '',
  `affiliate_payment_bank_swift_code` varchar(64) NOT NULL DEFAULT '',
  `affiliate_payment_bank_account_name` varchar(64) NOT NULL DEFAULT '',
  `affiliate_payment_bank_account_number` varchar(64) NOT NULL DEFAULT '',
  `affiliate_date_of_last_logon` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_number_of_logons` int(11) NOT NULL DEFAULT '0',
  `affiliate_date_account_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_date_account_last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_lft` int(11) NOT NULL DEFAULT '0',
  `affiliate_rgt` int(11) NOT NULL DEFAULT '0',
  `affiliate_root` int(11) NOT NULL DEFAULT '0',
  `affiliate_newsletter` char(1) NOT NULL DEFAULT '1',
  `affiliate_logo` varchar(255) DEFAULT NULL,
  `affiliate_display_logo` char(1) NOT NULL DEFAULT 'N',
  `affiliate_coupon` varchar(255) NOT NULL DEFAULT '',
  `affiliate_display_coupon_box` char(1) NOT NULL DEFAULT 'N',
  `affiliate_order_notification` varchar(10) NOT NULL DEFAULT '',
  `affiliate_notification_email` varchar(255) NOT NULL DEFAULT '',
  `affiliate_email_code` varchar(5) NOT NULL DEFAULT '',
  `currency` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_banners`
--

CREATE TABLE IF NOT EXISTS `affiliate_banners` (
  `affiliate_banners_id` int(11) NOT NULL,
  `affiliate_banners_title` varchar(64) NOT NULL DEFAULT '',
  `affiliate_products_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_category_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_cpath` varchar(24) DEFAULT NULL,
  `affiliate_banners_image` varchar(64) NOT NULL DEFAULT '',
  `affiliate_banners_group` varchar(10) NOT NULL DEFAULT '',
  `affiliate_banners_html_text` text,
  `affiliate_expires_impressions` int(7) DEFAULT '0',
  `affiliate_expires_date` datetime DEFAULT NULL,
  `affiliate_date_scheduled` datetime DEFAULT NULL,
  `affiliate_date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_date_status_change` datetime DEFAULT NULL,
  `affiliate_status` int(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_banners_history`
--

CREATE TABLE IF NOT EXISTS `affiliate_banners_history` (
  `affiliate_banners_history_id` int(11) NOT NULL,
  `affiliate_banners_products_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_banners_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_banners_affiliate_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_banners_shown` int(11) NOT NULL DEFAULT '0',
  `affiliate_banners_clicks` tinyint(4) NOT NULL DEFAULT '0',
  `affiliate_banners_history_date` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_clickthroughs`
--

CREATE TABLE IF NOT EXISTS `affiliate_clickthroughs` (
  `affiliate_clickthrough_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_clientdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_clientbrowser` varchar(200) DEFAULT 'Could Not Find This Data',
  `affiliate_clientip` varchar(50) DEFAULT 'Could Not Find This Data',
  `affiliate_clientreferer` varchar(200) DEFAULT 'none detected (maybe a direct link)',
  `affiliate_products_id` int(11) DEFAULT '0',
  `affiliate_cpath` varchar(24) DEFAULT NULL,
  `affiliate_banner_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_newsletters`
--

CREATE TABLE IF NOT EXISTS `affiliate_newsletters` (
  `affiliate_newsletters_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `module` varchar(255) NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_sent` datetime DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `locked` int(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_news_contents`
--

CREATE TABLE IF NOT EXISTS `affiliate_news_contents` (
  `affiliate_news_contents_id` int(11) NOT NULL,
  `affiliate_news_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_news_languages_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_news_headlines` varchar(255) NOT NULL DEFAULT '',
  `affiliate_news_contents` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_payment`
--

CREATE TABLE IF NOT EXISTS `affiliate_payment` (
  `affiliate_payment_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_payment` decimal(15,2) NOT NULL DEFAULT '0.00',
  `affiliate_payment_tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `affiliate_payment_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `affiliate_payment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_payment_last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_payment_status` int(5) NOT NULL DEFAULT '0',
  `affiliate_firstname` varchar(32) NOT NULL DEFAULT '',
  `affiliate_lastname` varchar(32) NOT NULL DEFAULT '',
  `affiliate_street_address` varchar(64) NOT NULL DEFAULT '',
  `affiliate_suburb` varchar(64) NOT NULL DEFAULT '',
  `affiliate_city` varchar(32) NOT NULL DEFAULT '',
  `affiliate_postcode` varchar(10) NOT NULL DEFAULT '',
  `affiliate_country` varchar(32) NOT NULL DEFAULT '0',
  `affiliate_company` varchar(60) NOT NULL DEFAULT '',
  `affiliate_state` varchar(32) NOT NULL DEFAULT '0',
  `affiliate_address_format_id` int(5) NOT NULL DEFAULT '0',
  `affiliate_last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_payment_status`
--

CREATE TABLE IF NOT EXISTS `affiliate_payment_status` (
  `affiliate_payment_status_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_language_id` int(11) NOT NULL DEFAULT '1',
  `affiliate_payment_status_name` varchar(32) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_payment_status_history`
--

CREATE TABLE IF NOT EXISTS `affiliate_payment_status_history` (
  `affiliate_status_history_id` int(11) NOT NULL,
  `affiliate_payment_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_new_value` int(5) NOT NULL DEFAULT '0',
  `affiliate_old_value` int(5) DEFAULT NULL,
  `affiliate_date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_notified` int(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_sales`
--

CREATE TABLE IF NOT EXISTS `affiliate_sales` (
  `affiliate_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_browser` varchar(100) NOT NULL DEFAULT '',
  `affiliate_ipaddress` varchar(20) NOT NULL DEFAULT '',
  `affiliate_orders_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `affiliate_payment` decimal(15,2) NOT NULL DEFAULT '0.00',
  `affiliate_clickthroughs_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_billing_status` int(5) NOT NULL DEFAULT '0',
  `affiliate_payment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_payment_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_percent` decimal(4,2) NOT NULL DEFAULT '0.00',
  `affiliate_salesman` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `amazonuk`
--

CREATE TABLE IF NOT EXISTS `amazonuk` (
  `title` varchar(255) NOT NULL DEFAULT '',
  `asin` varchar(26) NOT NULL DEFAULT '',
  `upc_ean` varchar(13) NOT NULL DEFAULT '',
  `cat_numbr` varchar(30) NOT NULL DEFAULT '',
  `price_uk` mediumint(15) NOT NULL DEFAULT '0',
  `disc` mediumint(15) NOT NULL DEFAULT '0',
  `cost` mediumint(15) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `amazonukpo`
--

CREATE TABLE IF NOT EXISTS `amazonukpo` (
  `PO_NUMBER` varchar(20) NOT NULL DEFAULT '',
  `UPC` varchar(13) NOT NULL DEFAULT '',
  `CAT` varchar(30) NOT NULL DEFAULT '',
  `ASIN` varchar(15) NOT NULL DEFAULT '',
  `Title` varchar(255) NOT NULL DEFAULT '',
  `List` varchar(8) NOT NULL DEFAULT '',
  `Disc` varchar(8) NOT NULL DEFAULT '',
  `Cost` varchar(8) NOT NULL DEFAULT '',
  `Ordered` smallint(4) NOT NULL DEFAULT '0',
  `Confirmed` smallint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `articles_id` int(11) NOT NULL,
  `articles_date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `articles_last_modified` datetime DEFAULT NULL,
  `articles_date_available` datetime DEFAULT NULL,
  `articles_status` tinyint(1) NOT NULL DEFAULT '0',
  `authors_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articles_description`
--

CREATE TABLE IF NOT EXISTS `articles_description` (
  `articles_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `articles_name` varchar(64) NOT NULL DEFAULT '',
  `articles_description` text,
  `articles_url` varchar(255) DEFAULT NULL,
  `articles_viewed` int(5) DEFAULT '0',
  `articles_head_title_tag` varchar(80) DEFAULT NULL,
  `articles_head_desc_tag` text,
  `articles_head_keywords_tag` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articles_to_topics`
--

CREATE TABLE IF NOT EXISTS `articles_to_topics` (
  `articles_id` int(11) NOT NULL DEFAULT '0',
  `topics_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articles_xsell`
--

CREATE TABLE IF NOT EXISTS `articles_xsell` (
  `ID` int(10) NOT NULL,
  `articles_id` int(10) unsigned NOT NULL DEFAULT '1',
  `xsell_id` int(10) unsigned NOT NULL DEFAULT '1',
  `sort_order` int(10) unsigned NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `article_reviews`
--

CREATE TABLE IF NOT EXISTS `article_reviews` (
  `reviews_id` int(11) NOT NULL,
  `articles_id` int(11) NOT NULL DEFAULT '0',
  `customers_id` int(11) DEFAULT NULL,
  `customers_name` varchar(64) NOT NULL DEFAULT '',
  `reviews_rating` int(1) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `reviews_read` int(5) NOT NULL DEFAULT '0',
  `approved` tinyint(3) unsigned DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `article_reviews_description`
--

CREATE TABLE IF NOT EXISTS `article_reviews_description` (
  `reviews_id` int(11) NOT NULL DEFAULT '0',
  `languages_id` int(11) NOT NULL DEFAULT '0',
  `reviews_text` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE IF NOT EXISTS `authors` (
  `authors_id` int(11) NOT NULL,
  `authors_name` varchar(32) NOT NULL DEFAULT '',
  `authors_image` varchar(64) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `authors_info`
--

CREATE TABLE IF NOT EXISTS `authors_info` (
  `authors_id` int(11) NOT NULL DEFAULT '0',
  `languages_id` int(11) NOT NULL DEFAULT '0',
  `authors_description` text,
  `authors_url` varchar(255) NOT NULL DEFAULT '',
  `url_clicked` int(5) NOT NULL DEFAULT '0',
  `date_last_click` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `banners_id` int(11) NOT NULL,
  `banners_title` varchar(64) NOT NULL DEFAULT '',
  `banners_url` varchar(255) NOT NULL DEFAULT '',
  `banners_image` varchar(64) NOT NULL DEFAULT '',
  `banners_group` varchar(25) NOT NULL DEFAULT '',
  `banners_html_text` text,
  `expires_impressions` int(7) DEFAULT '0',
  `expires_date` datetime DEFAULT NULL,
  `date_scheduled` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_status_change` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `banners_open_new_windows` tinyint(1) NOT NULL DEFAULT '1',
  `banners_on_ssl` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banners_history`
--

CREATE TABLE IF NOT EXISTS `banners_history` (
  `banners_history_id` int(11) NOT NULL,
  `banners_id` int(11) NOT NULL DEFAULT '0',
  `banners_shown` int(5) NOT NULL DEFAULT '0',
  `banners_clicked` int(5) NOT NULL DEFAULT '0',
  `banners_history_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `catalog_request`
--

CREATE TABLE IF NOT EXISTS `catalog_request` (
  `catalog_date_requested` date NOT NULL DEFAULT '0000-00-00',
  `catalog_email` varchar(100) NOT NULL DEFAULT '',
  `catalog_name` varchar(100) NOT NULL DEFAULT '',
  `catalog_address` varchar(100) NOT NULL DEFAULT '',
  `catalog_address_line2` varchar(100) NOT NULL DEFAULT '',
  `catalog_city` varchar(100) NOT NULL DEFAULT '',
  `catalog_state` varchar(100) NOT NULL DEFAULT '',
  `catalog_postal_code` varchar(100) NOT NULL DEFAULT '',
  `catalog_country` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `categories_id` int(11) NOT NULL,
  `categories_image` varchar(64) DEFAULT NULL,
  `brochure_image` varchar(255) NOT NULL DEFAULT '',
  `categories_affiliate_banner1` varchar(64) NOT NULL DEFAULT '',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(3) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `categories_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `category_info_status` int(1) NOT NULL DEFAULT '1',
  `categories_ebay_store_categories` int(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories_description`
--

CREATE TABLE IF NOT EXISTS `categories_description` (
  `categories_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `categories_name` varchar(128) DEFAULT NULL,
  `categories_heading_title` varchar(64) DEFAULT NULL,
  `categories_description` longtext,
  `categories_surls_id` int(11) NOT NULL,
  `ebay_store_category` varchar(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `checkout_orders`
--

CREATE TABLE IF NOT EXISTS `checkout_orders` (
  `orders_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL DEFAULT '0',
  `customers_name` varchar(64) NOT NULL DEFAULT '',
  `customers_company` varchar(32) DEFAULT NULL,
  `customers_street_address` varchar(64) NOT NULL DEFAULT '',
  `customers_suburb` varchar(32) DEFAULT NULL,
  `customers_city` varchar(32) NOT NULL DEFAULT '',
  `customers_postcode` varchar(10) NOT NULL DEFAULT '',
  `customers_state` varchar(32) DEFAULT NULL,
  `customers_country` varchar(32) NOT NULL DEFAULT '',
  `customers_telephone` varchar(32) NOT NULL DEFAULT '',
  `customers_email_address` varchar(96) NOT NULL DEFAULT '',
  `customers_address_format_id` int(5) NOT NULL DEFAULT '0',
  `delivery_name` varchar(64) NOT NULL DEFAULT '',
  `delivery_company` varchar(32) DEFAULT NULL,
  `delivery_street_address` varchar(64) NOT NULL DEFAULT '',
  `delivery_suburb` varchar(64) DEFAULT NULL,
  `delivery_city` varchar(32) NOT NULL DEFAULT '',
  `delivery_postcode` varchar(10) NOT NULL DEFAULT '',
  `delivery_state` varchar(32) DEFAULT NULL,
  `delivery_country` varchar(32) NOT NULL DEFAULT '',
  `delivery_address_format_id` int(5) NOT NULL DEFAULT '0',
  `billing_name` varchar(64) NOT NULL DEFAULT '',
  `billing_company` varchar(32) DEFAULT NULL,
  `billing_street_address` varchar(64) NOT NULL DEFAULT '',
  `billing_suburb` varchar(32) DEFAULT NULL,
  `billing_city` varchar(32) NOT NULL DEFAULT '',
  `billing_postcode` varchar(10) NOT NULL DEFAULT '',
  `billing_state` varchar(32) DEFAULT NULL,
  `billing_country` varchar(32) NOT NULL DEFAULT '',
  `billing_address_format_id` int(5) NOT NULL DEFAULT '0',
  `payment_method` varchar(32) NOT NULL DEFAULT '',
  `cc_type` varchar(20) DEFAULT NULL,
  `cc_owner` varchar(64) DEFAULT NULL,
  `cc_number` varchar(32) DEFAULT NULL,
  `cc_expires` varchar(4) DEFAULT NULL,
  `giftwrap` varchar(20) NOT NULL DEFAULT '',
  `last_modified` datetime DEFAULT NULL,
  `date_purchased` datetime DEFAULT NULL,
  `orders_status` int(5) NOT NULL DEFAULT '0',
  `orders_date_finished` datetime DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `currency_value` decimal(14,6) DEFAULT NULL,
  `purchase_order_number` varchar(50) NOT NULL DEFAULT '',
  `purchased_without_account` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ipaddy` varchar(15) NOT NULL DEFAULT '',
  `ipisp` varchar(15) NOT NULL DEFAULT '',
  `customers_referer_url` varchar(255) DEFAULT NULL,
  `shipping_tax` int(8) NOT NULL DEFAULT '0',
  `fedex_tracking` varchar(255) NOT NULL DEFAULT '',
  `comments_slip` text NOT NULL,
  `iswholesale` int(3) NOT NULL DEFAULT '0',
  `google_order_id` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `checkout_orders_products`
--

CREATE TABLE IF NOT EXISTS `checkout_orders_products` (
  `orders_products_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `products_id` int(11) NOT NULL DEFAULT '0',
  `products_model` varchar(25) DEFAULT NULL,
  `products_name` varchar(120) NOT NULL DEFAULT '',
  `products_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `final_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_tax` decimal(7,4) NOT NULL DEFAULT '0.0000',
  `products_quantity` int(2) NOT NULL DEFAULT '0',
  `products_sale_type` varchar(20) NOT NULL DEFAULT '',
  `products_item_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_prepared` tinyint(1) NOT NULL DEFAULT '0',
  `date_shipped_checkbox` char(2) DEFAULT NULL,
  `date_shipped` varchar(20) NOT NULL DEFAULT '',
  `returned_reason_checkbox` char(2) NOT NULL DEFAULT '',
  `returned_reason` varchar(255) NOT NULL DEFAULT '',
  `item_ordered_date` varchar(50) NOT NULL DEFAULT '',
  `camefrom` varchar(100) NOT NULL DEFAULT '',
  `ordered` int(2) NOT NULL DEFAULT '0',
  `back` int(2) NOT NULL DEFAULT '0',
  `is_allied` smallint(1) NOT NULL DEFAULT '0',
  `date_sent_to_allied` varchar(20) DEFAULT NULL,
  `date_ship_by_allied` varchar(20) DEFAULT NULL,
  `days_between_allied` int(11) NOT NULL DEFAULT '0',
  `date_confirm_by_allied` varchar(20) DEFAULT NULL,
  `fulfilled_by` varchar(5) NOT NULL,
  `order_discount_percentage` int(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `checkout_orders_products_attributes`
--

CREATE TABLE IF NOT EXISTS `checkout_orders_products_attributes` (
  `orders_products_attributes_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `orders_products_id` int(11) NOT NULL DEFAULT '0',
  `products_options` varchar(32) NOT NULL DEFAULT '',
  `products_options_values` varchar(32) NOT NULL DEFAULT '',
  `options_values_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `price_prefix` char(1) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `checkout_orders_products_download`
--

CREATE TABLE IF NOT EXISTS `checkout_orders_products_download` (
  `orders_products_download_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `orders_products_id` int(11) NOT NULL DEFAULT '0',
  `orders_products_filename` varchar(255) NOT NULL DEFAULT '',
  `download_maxdays` int(2) NOT NULL DEFAULT '0',
  `download_count` int(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `checkout_orders_status_history`
--

CREATE TABLE IF NOT EXISTS `checkout_orders_status_history` (
  `orders_status_history_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `orders_status_id` int(5) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `customer_notified` int(1) DEFAULT '0',
  `comments` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `checkout_orders_total`
--

CREATE TABLE IF NOT EXISTS `checkout_orders_total` (
  `orders_total_id` int(10) unsigned NOT NULL,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `text` varchar(255) NOT NULL DEFAULT '',
  `value` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `class` varchar(32) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `configuration_id` int(11) NOT NULL,
  `configuration_title` varchar(64) NOT NULL DEFAULT '',
  `configuration_key` text NOT NULL,
  `configuration_value` text NOT NULL,
  `configuration_description` varchar(255) NOT NULL DEFAULT '',
  `configuration_group_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(5) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `use_function` varchar(255) DEFAULT NULL,
  `set_function` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `configuration-1`
--

CREATE TABLE IF NOT EXISTS `configuration-1` (
  `configuration_id` int(11) NOT NULL,
  `configuration_title` varchar(64) NOT NULL DEFAULT '',
  `configuration_key` text NOT NULL,
  `configuration_value` text NOT NULL,
  `configuration_description` varchar(255) NOT NULL DEFAULT '',
  `configuration_group_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(5) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `use_function` varchar(255) DEFAULT NULL,
  `set_function` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `configuration_group`
--

CREATE TABLE IF NOT EXISTS `configuration_group` (
  `configuration_group_id` int(11) NOT NULL,
  `configuration_group_title` varchar(64) NOT NULL DEFAULT '',
  `configuration_group_description` varchar(255) NOT NULL DEFAULT '',
  `sort_order` int(5) DEFAULT NULL,
  `visible` int(1) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `counter`
--

CREATE TABLE IF NOT EXISTS `counter` (
  `startdate` char(8) DEFAULT NULL,
  `counter` int(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `counter_history`
--

CREATE TABLE IF NOT EXISTS `counter_history` (
  `month` char(8) DEFAULT NULL,
  `counter` int(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `countries_id` int(11) NOT NULL,
  `countries_name` varchar(64) NOT NULL DEFAULT '',
  `countries_iso_code_2` char(2) NOT NULL DEFAULT '',
  `countries_iso_code_3` char(3) NOT NULL DEFAULT '',
  `address_format_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE IF NOT EXISTS `coupons` (
  `coupon_id` int(11) NOT NULL,
  `coupon_type` char(1) NOT NULL DEFAULT 'F',
  `coupon_code` varchar(32) NOT NULL DEFAULT '',
  `coupon_amount` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `coupon_minimum_order` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `coupon_start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `coupon_expire_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uses_per_coupon` int(5) NOT NULL DEFAULT '1',
  `uses_per_user` int(5) NOT NULL DEFAULT '0',
  `restrict_to_products` varchar(255) DEFAULT NULL,
  `restrict_to_categories` varchar(255) DEFAULT NULL,
  `restrict_to_customers` text,
  `coupon_active` char(1) NOT NULL DEFAULT 'Y',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lowest_price` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupons_description`
--

CREATE TABLE IF NOT EXISTS `coupons_description` (
  `coupon_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '0',
  `coupon_name` varchar(32) NOT NULL DEFAULT '',
  `coupon_description` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_email_track`
--

CREATE TABLE IF NOT EXISTS `coupon_email_track` (
  `unique_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL DEFAULT '0',
  `customer_id_sent` int(11) NOT NULL DEFAULT '0',
  `sent_firstname` varchar(32) DEFAULT NULL,
  `sent_lastname` varchar(32) DEFAULT NULL,
  `emailed_to` varchar(32) DEFAULT NULL,
  `date_sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_gv_customer`
--

CREATE TABLE IF NOT EXISTS `coupon_gv_customer` (
  `customer_id` int(5) NOT NULL DEFAULT '0',
  `amount` decimal(8,4) NOT NULL DEFAULT '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_gv_queue`
--

CREATE TABLE IF NOT EXISTS `coupon_gv_queue` (
  `unique_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL DEFAULT '0',
  `order_id` int(5) NOT NULL DEFAULT '0',
  `amount` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ipaddr` varchar(32) NOT NULL DEFAULT '',
  `release_flag` char(1) NOT NULL DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_redeem_track`
--

CREATE TABLE IF NOT EXISTS `coupon_redeem_track` (
  `unique_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL DEFAULT '0',
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `redeem_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `redeem_ip` varchar(32) NOT NULL DEFAULT '',
  `order_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `createspace_status`
--

CREATE TABLE IF NOT EXISTS `createspace_status` (
  `product_type` varchar(32) NOT NULL,
  `title` varchar(128) NOT NULL,
  `status` varchar(32) NOT NULL,
  `upc` varchar(13) NOT NULL,
  `title_id` varchar(7) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `currencies_id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL DEFAULT '',
  `code` char(3) NOT NULL DEFAULT '',
  `symbol_left` varchar(12) DEFAULT NULL,
  `symbol_right` varchar(12) DEFAULT NULL,
  `decimal_point` char(1) DEFAULT NULL,
  `thousands_point` char(1) DEFAULT NULL,
  `decimal_places` char(1) DEFAULT NULL,
  `value` float(13,8) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `customers_id` int(11) NOT NULL,
  `purchased_without_account` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `customers_gender` char(1) NOT NULL DEFAULT '',
  `customers_firstname` varchar(32) NOT NULL DEFAULT '',
  `customers_lastname` varchar(32) NOT NULL DEFAULT '',
  `customers_dob` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `customers_email_address` varchar(96) NOT NULL DEFAULT '',
  `customers_default_address_id` int(11) NOT NULL DEFAULT '0',
  `customers_telephone` varchar(32) NOT NULL DEFAULT '',
  `customers_fax` varchar(32) DEFAULT NULL,
  `customers_password` varchar(40) NOT NULL DEFAULT '',
  `customers_newsletter` char(1) DEFAULT NULL,
  `customers_allow_purchase_order_entry` varchar(12) NOT NULL DEFAULT 'true',
  `customers_type` int(11) NOT NULL DEFAULT '0',
  `iswholesale` int(11) DEFAULT NULL,
  `distribution_percentage` int(11) DEFAULT NULL,
  `nondistribution_percentage` int(11) DEFAULT NULL,
  `disc1` int(11) NOT NULL DEFAULT '0',
  `disc2` int(11) NOT NULL DEFAULT '0',
  `shipping1` int(11) NOT NULL DEFAULT '0',
  `shipping2` int(11) NOT NULL DEFAULT '0',
  `customers_paypal_payerid` varchar(20) DEFAULT NULL,
  `customers_paypal_ec` tinyint(1) unsigned DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers_basket`
--

CREATE TABLE IF NOT EXISTS `customers_basket` (
  `customers_basket_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL DEFAULT '0',
  `products_id` tinytext NOT NULL,
  `customers_basket_quantity` int(2) NOT NULL DEFAULT '0',
  `final_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `customers_basket_date_added` varchar(8) DEFAULT NULL,
  `item_date_added` int(15) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers_basket_attributes`
--

CREATE TABLE IF NOT EXISTS `customers_basket_attributes` (
  `customers_basket_attributes_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL DEFAULT '0',
  `products_id` tinytext NOT NULL,
  `products_options_id` int(11) NOT NULL DEFAULT '0',
  `products_options_value_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers_info`
--

CREATE TABLE IF NOT EXISTS `customers_info` (
  `customers_info_id` int(11) NOT NULL DEFAULT '0',
  `customers_info_date_of_last_logon` datetime DEFAULT NULL,
  `customers_info_number_of_logons` int(5) DEFAULT NULL,
  `customers_info_date_account_created` datetime DEFAULT NULL,
  `customers_info_date_account_last_modified` datetime DEFAULT NULL,
  `customers_info_source_id` int(11) NOT NULL DEFAULT '0',
  `global_product_notifications` int(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers_type`
--

CREATE TABLE IF NOT EXISTS `customers_type` (
  `customers_type_id` int(11) NOT NULL,
  `customers_type_name` varchar(64) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers_wishlist`
--

CREATE TABLE IF NOT EXISTS `customers_wishlist` (
  `products_id` tinytext NOT NULL,
  `customers_id` int(13) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers_wishlist_attributes`
--

CREATE TABLE IF NOT EXISTS `customers_wishlist_attributes` (
  `customers_wishlist_attributes_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL DEFAULT '0',
  `products_id` tinytext NOT NULL,
  `products_options_id` int(11) NOT NULL DEFAULT '0',
  `products_options_value_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_testimonials`
--

CREATE TABLE IF NOT EXISTS `customer_testimonials` (
  `testimonials_title` varchar(64) NOT NULL DEFAULT '',
  `testimonials_id` int(5) NOT NULL,
  `testimonials_html_text` longtext NOT NULL,
  `testimonials_name` varchar(50) NOT NULL DEFAULT '',
  `testimonials_url` varchar(150) NOT NULL DEFAULT '',
  `testimonials_url_title` varchar(150) NOT NULL DEFAULT '',
  `date_added` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `daily_cart`
--

CREATE TABLE IF NOT EXISTS `daily_cart` (
  `sesskey` varchar(40) NOT NULL DEFAULT '',
  `sessvalue` text NOT NULL,
  `last_click` text NOT NULL,
  `IP` varchar(50) NOT NULL DEFAULT '',
  `is_finished` int(10) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `descrption`
--

CREATE TABLE IF NOT EXISTS `descrption` (
  `A` int(5) DEFAULT NULL,
  `B` int(1) DEFAULT NULL,
  `C` varchar(10) DEFAULT NULL,
  `D` varchar(33) DEFAULT NULL,
  `E` varchar(10) DEFAULT NULL,
  `F` varchar(1974) DEFAULT NULL,
  `G` varchar(10) DEFAULT NULL,
  `H` int(1) DEFAULT NULL,
  `I` varchar(57) DEFAULT NULL,
  `J` varchar(57) DEFAULT NULL,
  `K` varchar(57) DEFAULT NULL,
  `L` varchar(10) DEFAULT NULL,
  `M` varchar(10) DEFAULT NULL,
  `N` varchar(10) DEFAULT NULL,
  `O` int(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE IF NOT EXISTS `discounts` (
  `discount_id` int(11) NOT NULL,
  `discount_code` varchar(15) NOT NULL DEFAULT '',
  `discount_amount` float NOT NULL DEFAULT '0',
  `discount_name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `discount_customers`
--

CREATE TABLE IF NOT EXISTS `discount_customers` (
  `discount_cust_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `discount_id` int(11) NOT NULL DEFAULT '0',
  `sub_total` float NOT NULL DEFAULT '0',
  `discount_total` float(5,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `distributors`
--

CREATE TABLE IF NOT EXISTS `distributors` (
  `distributors_id` int(11) NOT NULL,
  `distributors_name` varchar(64) NOT NULL DEFAULT '',
  `distributors_description` text NOT NULL,
  `distributors_image` varchar(64) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `download_link_stat`
--

CREATE TABLE IF NOT EXISTS `download_link_stat` (
  `id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL DEFAULT '0',
  `IP` varchar(50) NOT NULL DEFAULT '',
  `time_entry` int(11) NOT NULL DEFAULT '0',
  `products_id` int(10) NOT NULL DEFAULT '0',
  `session_id` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ebay_store_categories`
--

CREATE TABLE IF NOT EXISTS `ebay_store_categories` (
  `ebay_category_id` int(9) NOT NULL,
  `ebay_category_name` char(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events_calendar`
--

CREATE TABLE IF NOT EXISTS `events_calendar` (
  `event_id` int(3) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` varchar(20) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `event_image` varchar(64) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `OSC_link` varchar(255) DEFAULT NULL,
  `description` text,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `faq_id` tinyint(3) unsigned NOT NULL,
  `visible` enum('1','0') NOT NULL DEFAULT '1',
  `v_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `language` varchar(32) NOT NULL DEFAULT '',
  `type` int(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `featured`
--

CREATE TABLE IF NOT EXISTS `featured` (
  `featured_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL DEFAULT '0',
  `featured_date_added` datetime DEFAULT NULL,
  `featured_last_modified` datetime DEFAULT NULL,
  `expires_date` datetime DEFAULT NULL,
  `date_status_change` datetime DEFAULT NULL,
  `status` int(1) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `geo_zones`
--

CREATE TABLE IF NOT EXISTS `geo_zones` (
  `geo_zone_id` int(11) NOT NULL,
  `geo_zone_name` varchar(32) NOT NULL DEFAULT '',
  `geo_zone_description` varchar(255) NOT NULL DEFAULT '',
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `google_checkout_logs`
--

CREATE TABLE IF NOT EXISTS `google_checkout_logs` (
  `google_checkout_logs_id` int(11) NOT NULL,
  `message_type` varchar(255) NOT NULL DEFAULT '',
  `msg_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `google_order_id` varchar(255) NOT NULL DEFAULT '',
  `xml` text NOT NULL,
  `orders_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `holding_orders`
--

CREATE TABLE IF NOT EXISTS `holding_orders` (
  `orders_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL DEFAULT '0',
  `customers_name` varchar(64) NOT NULL DEFAULT '',
  `customers_company` varchar(32) DEFAULT NULL,
  `customers_street_address` varchar(64) NOT NULL DEFAULT '',
  `customers_suburb` varchar(32) DEFAULT NULL,
  `customers_city` varchar(32) NOT NULL DEFAULT '',
  `customers_postcode` varchar(10) NOT NULL DEFAULT '',
  `customers_state` varchar(32) DEFAULT NULL,
  `customers_country` varchar(32) NOT NULL DEFAULT '',
  `customers_telephone` varchar(32) NOT NULL DEFAULT '',
  `customers_email_address` varchar(96) NOT NULL DEFAULT '',
  `customers_address_format_id` int(5) NOT NULL DEFAULT '0',
  `delivery_name` varchar(64) NOT NULL DEFAULT '',
  `delivery_company` varchar(32) DEFAULT NULL,
  `delivery_street_address` varchar(64) NOT NULL DEFAULT '',
  `delivery_suburb` varchar(32) DEFAULT NULL,
  `delivery_city` varchar(32) NOT NULL DEFAULT '',
  `delivery_postcode` varchar(10) NOT NULL DEFAULT '',
  `delivery_state` varchar(32) DEFAULT NULL,
  `delivery_country` varchar(32) NOT NULL DEFAULT '',
  `delivery_address_format_id` int(5) NOT NULL DEFAULT '0',
  `billing_name` varchar(64) NOT NULL DEFAULT '',
  `billing_company` varchar(32) DEFAULT NULL,
  `billing_street_address` varchar(64) NOT NULL DEFAULT '',
  `billing_suburb` varchar(32) DEFAULT NULL,
  `billing_city` varchar(32) NOT NULL DEFAULT '',
  `billing_postcode` varchar(10) NOT NULL DEFAULT '',
  `billing_state` varchar(32) DEFAULT NULL,
  `billing_country` varchar(32) NOT NULL DEFAULT '',
  `billing_address_format_id` varchar(5) NOT NULL DEFAULT '0',
  `payment_method` varchar(32) NOT NULL DEFAULT '',
  `cc_type` varchar(20) DEFAULT NULL,
  `cc_owner` varchar(64) DEFAULT NULL,
  `cc_number` varchar(32) DEFAULT NULL,
  `cc_expires` varchar(4) DEFAULT NULL,
  `giftwrap` varchar(20) NOT NULL DEFAULT '',
  `last_modified` datetime DEFAULT NULL,
  `date_purchased` datetime DEFAULT NULL,
  `orders_status` int(5) NOT NULL DEFAULT '0',
  `orders_date_finished` datetime DEFAULT NULL,
  `comments` text,
  `currency` char(3) DEFAULT NULL,
  `currency_value` decimal(14,6) DEFAULT NULL,
  `purchase_order_number` varchar(12) NOT NULL DEFAULT '',
  `purchased_without_account` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ipdaddy` varchar(15) NOT NULL DEFAULT '',
  `ipisp` varchar(15) NOT NULL DEFAULT '',
  `customers_referer_url` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `holding_orders_products`
--

CREATE TABLE IF NOT EXISTS `holding_orders_products` (
  `orders_products_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `products_id` int(11) NOT NULL DEFAULT '0',
  `products_model` varchar(12) DEFAULT NULL,
  `products_name` varchar(64) NOT NULL DEFAULT '',
  `products_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `final_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_tax` decimal(7,4) NOT NULL DEFAULT '0.0000',
  `products_quantity` int(2) NOT NULL DEFAULT '0',
  `products_sale_type` varchar(20) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `holding_orders_products_attributes`
--

CREATE TABLE IF NOT EXISTS `holding_orders_products_attributes` (
  `orders_products_attributes_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `orders_products_id` int(11) NOT NULL DEFAULT '0',
  `products_options` varchar(32) NOT NULL DEFAULT '',
  `products_options_values` varchar(32) NOT NULL DEFAULT '',
  `options_values_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `price_prefix` char(1) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `holding_orders_total`
--

CREATE TABLE IF NOT EXISTS `holding_orders_total` (
  `orders_total_id` int(10) unsigned NOT NULL,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `text` varchar(255) NOT NULL DEFAULT '',
  `value` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `class` varchar(32) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hollydan`
--

CREATE TABLE IF NOT EXISTS `hollydan` (
  `product_model` varchar(30) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ip2flag`
--

CREATE TABLE IF NOT EXISTS `ip2flag` (
  `COUNTRY_CODE2` char(2) DEFAULT NULL,
  `FLAG_NAME` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ipcountry`
--

CREATE TABLE IF NOT EXISTS `ipcountry` (
  `ipFROM` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `ipTO` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `countrySHORT` char(2) NOT NULL DEFAULT '',
  `countryLONG` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address_book`
--
ALTER TABLE `address_book`
  ADD PRIMARY KEY (`address_book_id`),
  ADD KEY `idx_address_book_customers_id` (`customers_id`);

--
-- Indexes for table `address_format`
--
ALTER TABLE `address_format`
  ADD PRIMARY KEY (`address_format_id`);

--
-- Indexes for table `affiliate_affiliate`
--
ALTER TABLE `affiliate_affiliate`
  ADD PRIMARY KEY (`affiliate_id`);

--
-- Indexes for table `affiliate_banners`
--
ALTER TABLE `affiliate_banners`
  ADD PRIMARY KEY (`affiliate_banners_id`);

--
-- Indexes for table `affiliate_banners_history`
--
ALTER TABLE `affiliate_banners_history`
  ADD PRIMARY KEY (`affiliate_banners_history_id`,`affiliate_banners_products_id`);

--
-- Indexes for table `affiliate_clickthroughs`
--
ALTER TABLE `affiliate_clickthroughs`
  ADD PRIMARY KEY (`affiliate_clickthrough_id`),
  ADD KEY `refid` (`affiliate_id`);

--
-- Indexes for table `affiliate_newsletters`
--
ALTER TABLE `affiliate_newsletters`
  ADD PRIMARY KEY (`affiliate_newsletters_id`);

--
-- Indexes for table `affiliate_news_contents`
--
ALTER TABLE `affiliate_news_contents`
  ADD PRIMARY KEY (`affiliate_news_contents_id`),
  ADD KEY `affiliate_news_id` (`affiliate_news_id`),
  ADD KEY `affiliate_news_languages_id` (`affiliate_news_languages_id`);

--
-- Indexes for table `affiliate_payment`
--
ALTER TABLE `affiliate_payment`
  ADD PRIMARY KEY (`affiliate_payment_id`);

--
-- Indexes for table `affiliate_payment_status`
--
ALTER TABLE `affiliate_payment_status`
  ADD PRIMARY KEY (`affiliate_payment_status_id`,`affiliate_language_id`),
  ADD KEY `idx_affiliate_payment_status_name` (`affiliate_payment_status_name`);

--
-- Indexes for table `affiliate_payment_status_history`
--
ALTER TABLE `affiliate_payment_status_history`
  ADD PRIMARY KEY (`affiliate_status_history_id`);

--
-- Indexes for table `affiliate_sales`
--
ALTER TABLE `affiliate_sales`
  ADD PRIMARY KEY (`affiliate_orders_id`,`affiliate_id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`articles_id`),
  ADD KEY `idx_articles_date_added` (`articles_date_added`);

--
-- Indexes for table `articles_description`
--
ALTER TABLE `articles_description`
  ADD PRIMARY KEY (`articles_id`,`language_id`),
  ADD KEY `articles_name` (`articles_name`);

--
-- Indexes for table `articles_to_topics`
--
ALTER TABLE `articles_to_topics`
  ADD PRIMARY KEY (`articles_id`,`topics_id`);

--
-- Indexes for table `articles_xsell`
--
ALTER TABLE `articles_xsell`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `article_reviews`
--
ALTER TABLE `article_reviews`
  ADD PRIMARY KEY (`reviews_id`);

--
-- Indexes for table `article_reviews_description`
--
ALTER TABLE `article_reviews_description`
  ADD PRIMARY KEY (`reviews_id`,`languages_id`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`authors_id`),
  ADD KEY `IDX_AUTHORS_NAME` (`authors_name`);

--
-- Indexes for table `authors_info`
--
ALTER TABLE `authors_info`
  ADD PRIMARY KEY (`authors_id`,`languages_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`banners_id`);

--
-- Indexes for table `banners_history`
--
ALTER TABLE `banners_history`
  ADD PRIMARY KEY (`banners_history_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categories_id`),
  ADD KEY `idx_categories_parent_id` (`parent_id`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Indexes for table `categories_description`
--
ALTER TABLE `categories_description`
  ADD PRIMARY KEY (`categories_id`,`language_id`),
  ADD KEY `idx_categories_name` (`categories_name`);

--
-- Indexes for table `checkout_orders`
--
ALTER TABLE `checkout_orders`
  ADD PRIMARY KEY (`orders_id`),
  ADD KEY `purchase_order_number` (`purchase_order_number`),
  ADD KEY `idx_orders_id` (`orders_id`),
  ADD KEY `idx_customers_id` (`customers_id`),
  ADD KEY `date_purchased` (`date_purchased`);

--
-- Indexes for table `checkout_orders_products`
--
ALTER TABLE `checkout_orders_products`
  ADD PRIMARY KEY (`orders_products_id`),
  ADD KEY `idx_orders_products_id` (`orders_products_id`),
  ADD KEY `idx_orders_id` (`orders_id`),
  ADD KEY `idx1_orders_products` (`products_id`);

--
-- Indexes for table `checkout_orders_products_attributes`
--
ALTER TABLE `checkout_orders_products_attributes`
  ADD PRIMARY KEY (`orders_products_attributes_id`),
  ADD KEY `idx_orders_products_attributes_id` (`orders_products_attributes_id`);

--
-- Indexes for table `checkout_orders_products_download`
--
ALTER TABLE `checkout_orders_products_download`
  ADD PRIMARY KEY (`orders_products_download_id`),
  ADD KEY `idx_orders_products_download_id` (`orders_products_download_id`);

--
-- Indexes for table `checkout_orders_status_history`
--
ALTER TABLE `checkout_orders_status_history`
  ADD PRIMARY KEY (`orders_status_history_id`),
  ADD KEY `idx_orders_status_history_id` (`orders_status_history_id`),
  ADD KEY `idx_orders_id` (`orders_id`),
  ADD KEY `idx_date_added` (`date_added`);

--
-- Indexes for table `checkout_orders_total`
--
ALTER TABLE `checkout_orders_total`
  ADD PRIMARY KEY (`orders_total_id`),
  ADD KEY `idx_orders_total_orders_id` (`orders_id`),
  ADD KEY `idx_orders_total_id` (`orders_total_id`),
  ADD KEY `idx_orders_id` (`orders_id`);

--
-- Indexes for table `configuration`
--
ALTER TABLE `configuration`
  ADD PRIMARY KEY (`configuration_id`),
  ADD KEY `idx_sort_order` (`sort_order`),
  ADD KEY `idx_date_added` (`date_added`);

--
-- Indexes for table `configuration-1`
--
ALTER TABLE `configuration-1`
  ADD PRIMARY KEY (`configuration_id`),
  ADD KEY `idx_sort_order` (`sort_order`),
  ADD KEY `idx_date_added` (`date_added`);

--
-- Indexes for table `configuration_group`
--
ALTER TABLE `configuration_group`
  ADD PRIMARY KEY (`configuration_group_id`);

--
-- Indexes for table `counter`
--
ALTER TABLE `counter`
  ADD KEY `idx_counter` (`counter`),
  ADD KEY `idx_startdate` (`startdate`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`countries_id`),
  ADD KEY `IDX_COUNTRIES_NAME` (`countries_name`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `coupons_description`
--
ALTER TABLE `coupons_description`
  ADD KEY `coupon_id` (`coupon_id`);

--
-- Indexes for table `coupon_email_track`
--
ALTER TABLE `coupon_email_track`
  ADD PRIMARY KEY (`unique_id`);

--
-- Indexes for table `coupon_gv_customer`
--
ALTER TABLE `coupon_gv_customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `coupon_gv_queue`
--
ALTER TABLE `coupon_gv_queue`
  ADD PRIMARY KEY (`unique_id`),
  ADD KEY `uid` (`unique_id`,`customer_id`,`order_id`);

--
-- Indexes for table `coupon_redeem_track`
--
ALTER TABLE `coupon_redeem_track`
  ADD PRIMARY KEY (`unique_id`);

--
-- Indexes for table `createspace_status`
--
ALTER TABLE `createspace_status`
  ADD PRIMARY KEY (`title_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`currencies_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customers_id`),
  ADD KEY `purchased_without_account` (`purchased_without_account`),
  ADD KEY `idx_customers_id` (`customers_id`),
  ADD KEY `idx_customers_email_address` (`customers_email_address`),
  ADD KEY `idx_customers_password` (`customers_password`),
  ADD KEY `idx_customers_firstname` (`customers_firstname`),
  ADD KEY `idx_customers_lastname` (`customers_lastname`);

--
-- Indexes for table `customers_basket`
--
ALTER TABLE `customers_basket`
  ADD PRIMARY KEY (`customers_basket_id`);

--
-- Indexes for table `customers_basket_attributes`
--
ALTER TABLE `customers_basket_attributes`
  ADD PRIMARY KEY (`customers_basket_attributes_id`);

--
-- Indexes for table `customers_info`
--
ALTER TABLE `customers_info`
  ADD PRIMARY KEY (`customers_info_id`);

--
-- Indexes for table `customers_type`
--
ALTER TABLE `customers_type`
  ADD PRIMARY KEY (`customers_type_id`);

--
-- Indexes for table `customers_wishlist_attributes`
--
ALTER TABLE `customers_wishlist_attributes`
  ADD PRIMARY KEY (`customers_wishlist_attributes_id`);

--
-- Indexes for table `customer_testimonials`
--
ALTER TABLE `customer_testimonials`
  ADD PRIMARY KEY (`testimonials_id`);

--
-- Indexes for table `daily_cart`
--
ALTER TABLE `daily_cart`
  ADD KEY `key` (`sesskey`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`discount_id`);

--
-- Indexes for table `discount_customers`
--
ALTER TABLE `discount_customers`
  ADD PRIMARY KEY (`discount_cust_id`);

--
-- Indexes for table `distributors`
--
ALTER TABLE `distributors`
  ADD PRIMARY KEY (`distributors_id`),
  ADD KEY `IND_DISTRIBUTORS_NAME` (`distributors_name`);

--
-- Indexes for table `download_link_stat`
--
ALTER TABLE `download_link_stat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events_calendar`
--
ALTER TABLE `events_calendar`
  ADD PRIMARY KEY (`event_id`,`language_id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`faq_id`);

--
-- Indexes for table `featured`
--
ALTER TABLE `featured`
  ADD PRIMARY KEY (`featured_id`);

--
-- Indexes for table `geo_zones`
--
ALTER TABLE `geo_zones`
  ADD PRIMARY KEY (`geo_zone_id`),
  ADD KEY `idx_geo_zone_id` (`geo_zone_id`);

--
-- Indexes for table `google_checkout_logs`
--
ALTER TABLE `google_checkout_logs`
  ADD PRIMARY KEY (`google_checkout_logs_id`),
  ADD KEY `orders_id` (`orders_id`),
  ADD KEY `google_order_id` (`google_order_id`);

--
-- Indexes for table `holding_orders`
--
ALTER TABLE `holding_orders`
  ADD PRIMARY KEY (`orders_id`);

--
-- Indexes for table `holding_orders_products`
--
ALTER TABLE `holding_orders_products`
  ADD PRIMARY KEY (`orders_products_id`);

--
-- Indexes for table `holding_orders_products_attributes`
--
ALTER TABLE `holding_orders_products_attributes`
  ADD PRIMARY KEY (`orders_products_attributes_id`);

--
-- Indexes for table `holding_orders_total`
--
ALTER TABLE `holding_orders_total`
  ADD PRIMARY KEY (`orders_total_id`),
  ADD KEY `idx_orders_total_orders_id` (`orders_id`);

--
-- Indexes for table `ipcountry`
--
ALTER TABLE `ipcountry`
  ADD PRIMARY KEY (`ipFROM`,`ipTO`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address_book`
--
ALTER TABLE `address_book`
  MODIFY `address_book_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `address_format`
--
ALTER TABLE `address_format`
  MODIFY `address_format_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `affiliate_affiliate`
--
ALTER TABLE `affiliate_affiliate`
  MODIFY `affiliate_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `affiliate_banners`
--
ALTER TABLE `affiliate_banners`
  MODIFY `affiliate_banners_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `affiliate_banners_history`
--
ALTER TABLE `affiliate_banners_history`
  MODIFY `affiliate_banners_history_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `affiliate_clickthroughs`
--
ALTER TABLE `affiliate_clickthroughs`
  MODIFY `affiliate_clickthrough_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `affiliate_newsletters`
--
ALTER TABLE `affiliate_newsletters`
  MODIFY `affiliate_newsletters_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `affiliate_news_contents`
--
ALTER TABLE `affiliate_news_contents`
  MODIFY `affiliate_news_contents_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `affiliate_payment`
--
ALTER TABLE `affiliate_payment`
  MODIFY `affiliate_payment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `affiliate_payment_status_history`
--
ALTER TABLE `affiliate_payment_status_history`
  MODIFY `affiliate_status_history_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `articles_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `articles_description`
--
ALTER TABLE `articles_description`
  MODIFY `articles_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `articles_xsell`
--
ALTER TABLE `articles_xsell`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `article_reviews`
--
ALTER TABLE `article_reviews`
  MODIFY `reviews_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `authors_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `banners_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `banners_history`
--
ALTER TABLE `banners_history`
  MODIFY `banners_history_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categories_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `checkout_orders`
--
ALTER TABLE `checkout_orders`
  MODIFY `orders_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `checkout_orders_products`
--
ALTER TABLE `checkout_orders_products`
  MODIFY `orders_products_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `checkout_orders_products_attributes`
--
ALTER TABLE `checkout_orders_products_attributes`
  MODIFY `orders_products_attributes_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `checkout_orders_products_download`
--
ALTER TABLE `checkout_orders_products_download`
  MODIFY `orders_products_download_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `checkout_orders_status_history`
--
ALTER TABLE `checkout_orders_status_history`
  MODIFY `orders_status_history_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `checkout_orders_total`
--
ALTER TABLE `checkout_orders_total`
  MODIFY `orders_total_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `configuration`
--
ALTER TABLE `configuration`
  MODIFY `configuration_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `configuration-1`
--
ALTER TABLE `configuration-1`
  MODIFY `configuration_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `configuration_group`
--
ALTER TABLE `configuration_group`
  MODIFY `configuration_group_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `countries_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coupon_email_track`
--
ALTER TABLE `coupon_email_track`
  MODIFY `unique_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coupon_gv_queue`
--
ALTER TABLE `coupon_gv_queue`
  MODIFY `unique_id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coupon_redeem_track`
--
ALTER TABLE `coupon_redeem_track`
  MODIFY `unique_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `currencies_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customers_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customers_basket`
--
ALTER TABLE `customers_basket`
  MODIFY `customers_basket_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customers_basket_attributes`
--
ALTER TABLE `customers_basket_attributes`
  MODIFY `customers_basket_attributes_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customers_type`
--
ALTER TABLE `customers_type`
  MODIFY `customers_type_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customers_wishlist_attributes`
--
ALTER TABLE `customers_wishlist_attributes`
  MODIFY `customers_wishlist_attributes_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_testimonials`
--
ALTER TABLE `customer_testimonials`
  MODIFY `testimonials_id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `discount_customers`
--
ALTER TABLE `discount_customers`
  MODIFY `discount_cust_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `distributors`
--
ALTER TABLE `distributors`
  MODIFY `distributors_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `download_link_stat`
--
ALTER TABLE `download_link_stat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `events_calendar`
--
ALTER TABLE `events_calendar`
  MODIFY `event_id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `faq_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `featured`
--
ALTER TABLE `featured`
  MODIFY `featured_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `geo_zones`
--
ALTER TABLE `geo_zones`
  MODIFY `geo_zone_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `google_checkout_logs`
--
ALTER TABLE `google_checkout_logs`
  MODIFY `google_checkout_logs_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `holding_orders`
--
ALTER TABLE `holding_orders`
  MODIFY `orders_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `holding_orders_products`
--
ALTER TABLE `holding_orders_products`
  MODIFY `orders_products_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `holding_orders_products_attributes`
--
ALTER TABLE `holding_orders_products_attributes`
  MODIFY `orders_products_attributes_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `holding_orders_total`
--
ALTER TABLE `holding_orders_total`
  MODIFY `orders_total_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
