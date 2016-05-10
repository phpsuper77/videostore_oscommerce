<?php
/*
  $Id: english.php,v 1.106 2003/06/20 00:18:31 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'en_US'
// on FreeBSD 4.0 I use 'en_US.ISO_8859-1'
// this may not work under win32 environments..
setlocale(LC_TIME, 'en_US.ISO_8859-1');
define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('PHP_DATE_TIME_FORMAT', 'm/d/Y H:i:s'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="en"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-1');

// page title
define('TITLE', 'osCommerce');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Administration');
define('HEADER_TITLE_SUPPORT_SITE', 'Support Site');
define('HEADER_TITLE_ONLINE_CATALOG', 'Online Catalog');
define('HEADER_TITLE_ADMINISTRATION', 'Administration');

// purchaseorders_1_4 start
define('TEXT_YES', 'YES');
define('TEXT_NO', 'NO');
//purchaseorders_1_4 end

//SALES REPORT v1.0
define('BOX_REPORTS_SALES_REPORT', 'Sales Report');

// text for gender
define('MALE', 'Male');
define('FEMALE', 'Female');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Configuration');
define('BOX_CONFIGURATION_MYSTORE', 'My Store');
define('BOX_CONFIGURATION_LOGGING', 'Logging');
define('BOX_CONFIGURATION_CACHE', 'Cache');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Modules');
define('BOX_MODULES_PAYMENT', 'Payment');
define('BOX_MODULES_SHIPPING', 'Shipping');
define('BOX_MODULES_ORDER_TOTAL', 'Order Total');
define('BOX_MODULES_GIFTWRAP', 'Giftwrap');##ADDED FOR GIFT WRAP

//Discount box text in includes/admin/discount_entry.php
define('BOX_HEADING_DISCOUNT', 'Discount Entry');


// categories box text in includes/boxes/catalog.php
define('BOX_CATALOG_QUICK_UPDATES', 'Quick Updates');
define('BOX_HEADING_CATALOG', 'Catalog');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categories/Products');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Products Attributes');
define('BOX_CATALOG_MANUFACTURERS', 'Manufacturers');
define('BOX_CATALOG_DISTRIBUTORS', 'Distributors');
define('BOX_CATALOG_PRODUCERS', 'Producers');
define('BOX_CATALOG_SERIES', 'Series');
define('BOX_CATALOG_REVIEWS', 'Reviews');
define('BOX_CATALOG_SPECIALS', 'Specials');
define('BOX_CATALOG_FEATURED', 'Featured');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Products Expected');
// MaxiDVD Added Line For WYSIWYG HTML Area: BOF
define('BOX_CATALOG_DEFINE_MAINPAGE', 'Define MainPage');
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF

// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Customers');
define('BOX_CUSTOMERS_CUSTOMERS', 'Customers');
define('BOX_CUSTOMERS_ORDERS', 'Orders');
define('BOX_CUSTOMERS_REFERRALS', 'Referrals'); //rmh referrals

//CHANGES MADE FOR ORDERS.PHP
define('BOX_CUSTOMERS_ORDERS_PRINT', 'Print Packing Slip');


// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Locations / Taxes');
define('BOX_TAXES_COUNTRIES', 'Countries');
define('BOX_TAXES_ZONES', 'Zones');
define('BOX_TAXES_GEO_ZONES', 'Tax Zones');
define('BOX_TAXES_TAX_CLASSES', 'Tax Classes');
define('BOX_TAXES_TAX_RATES', 'Tax Rates');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Reports');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Products Viewed');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Products Purchased');
define('BOX_REPORTS_REFERRAL_SOURCES', 'Referral Sources'); //rmh referrals
define('BOX_REPORTS_ORDERS_TOTAL', 'Customer Orders-Total');

// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Tools');
define('BOX_TOOLS_BACKUP', 'Database Backup');
define('BOX_TOOLS_BANNER_MANAGER', 'Banner Manager');
define('BOX_TOOLS_CACHE', 'Cache Control');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Define Languages');
define('BOX_TOOLS_FILE_MANAGER', 'File Manager');
define('BOX_TOOLS_MAIL', 'Send Email');
define('BOX_TOOLS_SEO_ASSISTANT', 'SEO Assistant');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Newsletter Manager');
define('BOX_TOOLS_SERVER_INFO', 'Server Info');
define('BOX_TOOLS_WHOS_ONLINE', 'Who\'s Online');

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Localization');
define('BOX_LOCALIZATION_CURRENCIES', 'Currencies');
define('BOX_LOCALIZATION_LANGUAGES', 'Languages');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Orders Status');

// VJ Links Manager v1.00 begin
// links manager box text in includes/boxes/links.php
define('BOX_HEADING_LINKS', 'Links Manager');
define('BOX_LINKS_LINKS', 'Links');
define('BOX_LINKS_LINK_CATEGORIES', 'Link Categories');
define('BOX_LINKS_LINKS_CONTACT', 'Links Contact');
// VJ Links Manager v1.00 end


// javascript messages
define('JS_ERROR', 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* The new product atribute needs a price value\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* The new product atribute needs a price prefix\n');

define('JS_PRODUCTS_NAME', '* The new product needs a name\n');
define('JS_PRODUCTS_DESCRIPTION', '* The new product needs a description\n');
define('JS_PRODUCTS_PRICE', '* The new product needs a price value\n');
define('JS_PRODUCTS_WEIGHT', '* The new product needs a weight value\n');
define('JS_PRODUCTS_QUANTITY', '* The new product needs a quantity value\n');
define('JS_PRODUCTS_MODEL', '* The new product needs a model value\n');
define('JS_PRODUCTS_IMAGE', '* The new product needs an image value\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* A new price for this product needs to be set\n');

define('JS_GENDER', '* The \'Gender\' value must be chosen.\n');
define('JS_FIRST_NAME', '* The \'First Name\' entry must have at least ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_LAST_NAME', '* The \'Last Name\' entry must have at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_DOB', '* The \'Date of Birth\' entry must be in the format: xx/xx/xxxx (month/date/year).\n');
define('JS_EMAIL_ADDRESS', '* The \'E-Mail Address\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_ADDRESS', '* The \'Street Address\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_POST_CODE', '* The \'Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n');
define('JS_CITY', '* The \'City\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n');
define('JS_STATE', '* The \'State\' entry is must be selected.\n');
define('JS_STATE_SELECT', '-- Select Above --');
define('JS_ZONE', '* The \'State\' entry must be selected from the list for this country.');
define('JS_COUNTRY', '* The \'Country\' value must be chosen.\n');
define('JS_TELEPHONE', '* The \'Telephone Number\' entry must have at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n');
define('JS_PASSWORD', '* The \'Password\' amd \'Confirmation\' entries must match amd have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Order Number %s does not exist!');

define('CATEGORY_PERSONAL', 'Personal');
define('CATEGORY_ADDRESS', 'Address');
define('CATEGORY_CONTACT', 'Contact');
define('CATEGORY_COMPANY', 'Company');
define('CATEGORY_OPTIONS', 'Options');

define('ENTRY_GENDER', 'Gender:');
define('ENTRY_GENDER_ERROR', '&nbsp;<span class="errorText">required</span>');
define('ENTRY_FIRST_NAME', 'First Name:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' chars</span>');
define('ENTRY_LAST_NAME', 'Last Name:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' chars</span>');
define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(eg. 05/21/1970)</span>');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' chars</span>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">The email address doesn\'t appear to be valid!</span>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<span class="errorText">This email address already exists!</span>');
define('ENTRY_COMPANY', 'Company name:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_STREET_ADDRESS', 'Street Address:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' chars</span>');
define('ENTRY_SUBURB', 'Suburb:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_POST_CODE', 'Post Code:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' chars</span>');
define('ENTRY_CITY', 'City:');
define('ENTRY_CITY_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</span>');
define('ENTRY_STATE', 'State:');
define('ENTRY_STATE_ERROR', '&nbsp;<span class="errorText">required</span>');
define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</span>');
define('ENTRY_FAX_NUMBER', 'Fax Number:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_YES', 'Subscribed');
define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');
define('ENTRY_NEWSLETTER_ERROR', '');
// purchaseorders_1_4 start
define('ENTRY_ALLOW_PURCHASE_ORDER_ENTRY', 'Allow Purchase Order Entry:');
// purchaseorders_1_4 end

// images
define('IMAGE_ANI_SEND_EMAIL', 'Sending E-Mail');
define('IMAGE_BACK', 'Back');
define('IMAGE_BACKUP', 'Backup');
define('IMAGE_CANCEL', 'Cancel');
define('IMAGE_CONFIRM', 'Confirm');
define('IMAGE_COPY', 'Copy');
define('IMAGE_COPY_TO', 'Copy To');
define('IMAGE_DETAILS', 'Details');
define('IMAGE_DELETE', 'Delete');
define('IMAGE_EDIT', 'Edit');
define('IMAGE_EMAIL', 'Email');
define('IMAGE_FILE_MANAGER', 'File Manager');
define('IMAGE_ICON_STATUS_GREEN', 'Active');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Set Active');
define('IMAGE_ICON_STATUS_RED', 'Inactive');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Set Inactive');
define('IMAGE_ICON_INFO', 'Info');
define('IMAGE_INSERT', 'Insert');
define('IMAGE_LOCK', 'Lock');
define('IMAGE_MODULE_INSTALL', 'Install Module');
define('IMAGE_MODULE_REMOVE', 'Remove Module');
define('IMAGE_MOVE', 'Move');
define('IMAGE_NEW_BANNER', 'New Banner');
define('IMAGE_NEW_CATEGORY', 'New Category');
define('IMAGE_NEW_COUNTRY', 'New Country');
define('IMAGE_NEW_CURRENCY', 'New Currency');
define('IMAGE_NEW_FILE', 'New File');
define('IMAGE_NEW_FOLDER', 'New Folder');
define('IMAGE_NEW_LANGUAGE', 'New Language');
define('IMAGE_NEW_NEWSLETTER', 'New Newsletter');
define('IMAGE_NEW_PRODUCT', 'New Product');
define('IMAGE_NEW_TAX_CLASS', 'New Tax Class');
define('IMAGE_NEW_TAX_RATE', 'New Tax Rate');
define('IMAGE_NEW_TAX_ZONE', 'New Tax Zone');
define('IMAGE_NEW_ZONE', 'New Zone');
define('IMAGE_ORDERS', 'Orders');
define('IMAGE_ORDERS_INVOICE', 'Invoice');
define('IMAGE_ORDERS_PACKINGSLIP', 'Packing Slip');
define('IMAGE_PREVIEW', 'Preview');
define('IMAGE_RESTORE', 'Restore');
define('IMAGE_RESET', 'Reset');
define('IMAGE_SAVE', 'Save');
define('IMAGE_SEARCH', 'Search');
define('IMAGE_SELECT', 'Select');
define('IMAGE_SEND', 'Send');
define('IMAGE_SEND_EMAIL', 'Send Email');
define('IMAGE_UNLOCK', 'Unlock');
define('IMAGE_UPDATE', 'Update');
define('IMAGE_UPDATE_CURRENCIES', 'Update Exchange Rate');
define('IMAGE_UPLOAD', 'Upload');
define('IMAGE_VENDORS', 'Vendors Entry');//Vendors

define('ICON_CROSS', 'False');
define('ICON_CURRENT_FOLDER', 'Current Folder');
define('ICON_DELETE', 'Delete');
define('ICON_ERROR', 'Error');
define('ICON_FILE', 'File');
define('ICON_FILE_DOWNLOAD', 'Download');
define('ICON_FOLDER', 'Folder');
define('ICON_LOCKED', 'Locked');
define('ICON_PREVIOUS_LEVEL', 'Previous Level');
define('ICON_PREVIEW', 'Preview');
define('ICON_STATISTICS', 'Statistics');
define('ICON_SUCCESS', 'Success');
define('ICON_TICK', 'True');
define('ICON_UNLOCKED', 'Unlocked');
define('ICON_WARNING', 'Warning');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Page %s of %d');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> banners)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> countries)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> customers)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> currencies)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> languages)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> manufacturers)');
define('TEXT_DISPLAY_NUMBER_OF_DISTRIBUTORS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> distributors)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> producers)');
define('TEXT_DISPLAY_NUMBER_OF_SERIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> series)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> newsletters)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders status)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products expected)');
define('TEXT_DISPLAY_NUMBER_OF_REFERRALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> referral sources)'); //rmh referrals
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> product reviews)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products on special)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax classes)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax zones)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax rates)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> zones)');

define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'default');
define('TEXT_SET_DEFAULT', 'Set as default');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Required</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Error: There is currently no default currency set. Please set one at: Administration Tool->Localization->Currencies');

define('TEXT_CACHE_CATEGORIES', 'Categories Box');
define('TEXT_CACHE_MANUFACTURERS', 'Manufacturers Box');
define('TEXT_CACHE_ALSO_PURCHASED', 'Also Purchased Module');
define('TEXT_CACHE_INFORMATION', 'Information');
define('TEXT_CACHE_INFORMATION4', 'Celebrity Hosts');
define('TEXT_CACHE_TOP_SELLERS', 'Top Sellers');
define('TEXT_CACHE_NEW_PRODUCTS', 'New Products');
define('TEXT_CACHE_FEATURED', 'Featured Products');
define('TEXT_CACHE_AFFILIATE', 'Affiliate Top Categories');
define('TEXT_CACHE_AFFILIATE_PRODUCT', 'Affiliate Products');

define('TEXT_OTHER', 'Other'); //rmh referral
define('TEXT_NONE', '--none--');
define('TEXT_TOP', 'Top');

define('ERROR_DESTINATION_DOES_NOT_EXIST', 'Error: Destination does not exist.');
define('ERROR_DESTINATION_NOT_WRITEABLE', 'Error: Destination not writeable.');
define('ERROR_FILE_NOT_SAVED', 'Error: File upload not saved.');
define('ERROR_FILETYPE_NOT_ALLOWED', 'Error: File upload type not allowed.');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Success: File upload saved successfully.');
define('WARNING_NO_FILE_UPLOADED', 'Warning: No file uploaded.');
define('WARNING_FILE_UPLOADS_DISABLED', 'Warning: File uploads are disabled in the php.ini configuration file.');
define('BOX_REPORTS_UNSOLD_CARTS', 'Unsold Cart');
define('BOX_REPORTS_SALES_REPORT2', 'SalesReport2');
define('BOX_REPORTS_MONTHLY_SALES', 'Monthly Sales/Tax');
// RJW Begin Meta Tags Code
  define('TEXT_META_TITLE', 'Meta Title');
  define('TEXT_META_DESCRIPTION', 'Meta Description');
  define('TEXT_META_KEYWORDS', 'Meta Keywords');
// RJW End Meta Tags Code

//KEYWORD SHOW
define('BOX_KEYWORD_SHOW', 'Keyword Show');
define('TEXT_KEYWORD_SHOW_CONFIG','Keyword Show Conf');

//Discount
define('TEXT_DISCOUNT_ENTRY','Discount Entry');
define('TEXT_DISCOUNT','Discount');
define('TEXT_DISCOUNT_REDEEM','Discount Redeem');

define('TABLE_HEADING_DISCOUNT_AMOUNT', 'Discount %	');
define('TABLE_HEADING_DISCOUNT_CODE', 'Discount Code');
define('TABLE_HEADING_DISCOUNT_NAME', 'Discount Name');
define('TABLE_HEADING_DISCOUNT_ID', 'Discount Id');
define('TEXT_DISCOUNT_AMOUNT', 'Enter Discount Amount');
define('TEXT_DISCOUNT_TOTAL_REDEEM', 'Total Redeem');
define('TEXT_DISCOUNT_TOTAL_AMOUNT', 'Total Amount');
define('TEXT_DISCOUNT_CODE', 'Enter Discount Code');

require(DIR_WS_LANGUAGES . 'add_ccgvdc_english.php');  // ICW CREDIT CLASS Gift Voucher Addittion
// Vendors add-in
define('BOX_HEADING_VENDORS', 'Vendors');
define('BOX_VENDORS_REPORTS_PROD', 'Prods/Vendors Map');
define('BOX_VENDORS_VENDORS', 'Add/Edit Vendors');
define('BOX_VENDORS_INFO', 'Vendors Information');// VENDORS
define('IMAGE_VENDORS', 'Vendors');
define('TEXT_DISPLAY_NUMBER_OF_VENDORS', 'Displaying <b>%d</b> to <b>%d</b> (of<b>%d</b> vendors)');
define('TEXT_DISPLAY_NUMBER_OF_PAYMENTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> payments)');
define('TEXT_DISPLAY_NUMBER_OF_PO', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)');
define('TEXT_INFO_CURRENT_VENDORS', 'Current Vendors');
define('TEXT_INFO_HEADING_VENDOR', 'Vendors');
define('TEXT_VENDORS', 'Add Vendor');
define('TEXT_VENDORS_TERMS', 'Vendors Terms');
define('TEXT_INFO_VENDORS_INTRO', 'Add or remove vendors that you order this product from.  Place a checkmark next to those to delete and/or select a vendor to add.');
define('ERROR_VENDOR_EXISTS', 'Vendor is already mapped to product.');
  // End Vendors add-in
// Backordered Report
define('BOX_REPORTS_PRODUCTS_BACKORDERED', 'Products Backordered');
// FAQ SYSTEM 2.1

  define('BOX_HEADING_FAQ', 'FAQ System');
  define('BOX_FAQ_MANAGER', 'FAQ Manager');
  define('BOX_FAQ_VIEW', 'FAQ View');
  define('BOX_FAQ_VIEW_ALL', 'FAQ View All');

// FAQ SYSTEM 2.1
define('BOX_TOOLS_PAGE_MANAGER', 'Page Manager');
define('TEXT_DISPLAY_NUMBER_OF_PAGES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Pages)');
define('BOX_TOOLS_EVENTS_MANAGER', 'Events Manager');

define('IMAGE_NEW_EVENT', 'New Event');
define('BOX_TOOLS_KEYWORDS', 'Keyword Manager');
define('BOX_USER_TRACKING', 'User Tracking');
define('TEXT_USERTRACK_CONFIG','User Track Config');
define('BOX_REPORTS_RECOVER_CART_SALES', 'Recovered Sales Results');
		define('BOX_TOOLS_RECOVER_CART', 'Recover Cart Sales');
// tools text in includes/boxes/tools.php
define('BOX_TOOLS_TESTIMONIALS_MANAGER', 'Testimonials Mgr');

// images
define('IMAGE_NEW_TESTIMONIAL', 'New Testimonial');
//DANIEL:begin
define('BOX_CATALOG_CATEGORIES_RELATED_PRODUCTS', 'Related Products');
//DANIEL:end
define('BOX_TOOLS_IMAGE_CHECK', 'Image Check');
define('BOX_REPORTS_WISHLISTS', 'Customer Wishlists');

define('IMAGE_ORDERS_USPS_SHIP', 'Create a shipping label at usps.com');
define('TEXT_INFO_SHIPPING_METHOD', 'Shipping Method:');

define('IMAGE_ORDERS_SHIP', 'Ship Package');
define('IMAGE_ORDERS_FEDEX_LABEL','View or Print FedEx Shipping Label');
define('IMAGE_ORDERS_TRACK','Track FedEx Shipment');
define('IMAGE_ORDERS_CANCEL_SHIPMENT','Cancel FedEx Shipment');
define('BOX_SHIPPING_MANIFEST', 'FedEx Shipping Manifest');

define('BOX_VENDOR_PAYMENTS', 'Add/Edit Payment');
define('BOX_VENDOR_PO', 'Add/Edit Purchase Orders');

define('TEXT_DISPLAY_NUMBER_OF_QUERIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> queries)');
define('BOX_TOOLS_MYSQL_PERFORMANCE', 'MySQL Performance');

//BOF Dynamic Sitemap
define('BOX_TOOLS_SITEMAP', 'SiteMap Maintenance');
define('IMAGE_EXCLUDE', 'Exclude');
//EOF Dynamic Sitemap


?>
