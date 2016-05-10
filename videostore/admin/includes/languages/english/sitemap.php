<?php
/*
  $Id: specials.php,v 1.10 2002/03/16 15:07:21 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Site Map Maintenance');

define('TITLE_CATALOG_FILES', 'Files in Catalog<br /><small>('.DIR_FS_CATALOG.')</small>');
define('TITLE_CATALOG_BOXES', 'Catalog boxes<br /><small>('.DIR_FS_CATALOG.DIR_WS_BOXES.')</small>');
define('TEXT_EXCLUDE_FOR_ALL', 'Exclude completely');
define('TEXT_EXCLUDE_FOR_UNREG', 'Exclude for unregistered users');
define('TEXT_INCLUDE', 'Include');
define('TEXT_ALL', 'all');
define('TEXT_UNREG', 'guests');
define('TITLE_EXCLUDED_CATALOG_FILES', 'Excluded catalog files');
define('TITLE_EXCLUDED_CATALOG_BOXES', 'Excluded catalog boxes');

define('ERROR_CANNOT_OPEN_CATALOG_BOXES_DIR', 'Error! Can&rsquo;t open catalog boxes dir : ');
define('ERROR_CANNOT_OPEN_CATALOG_DIR', 'Error! Can&rsquo;t open catalog dir : ');
define('TEXT_CREATE_XML','Click submit button to create new XML sitemaps and notify Google.');
define('TEXT_CONTROL_XML','Use the controls below to determine if any of the main files will be excluded from both the main files XML sitemap and the dynamic sitemap available to normal users of this site.');
define('TEXT_CHOOSE_CATMFG_FREQ','Select from below the frequency that pages in the categories or manufacturers listings are likely to change. This would be determined by how frequently products are added or removed or go in or out of stock.');
define('TEXT_CHOOSE_SALE_FREQ','Select from below the frequency that pages in the specials listing are likely to change. This would be determined by how frequently products go on or off sale.');
define('TEXT_ALWAYS','The listing is likely to change every time it is accessed.');
define('TEXT_HOURLY','The listing is likely to change on an hourly basis.');
define('TEXT_DAILY','The listing is likely to change every day.');
define('TEXT_WEEKLY','The listing is likely to change every week.');
define('TEXT_MONTHLY','The listing is likely to change every month.');
define('TEXT_YEARLY','The listing is likely to change every year.');
define('TEXT_NEVER','The listing will seldom if ever change.');
define('TEXT_SELECT_TIMEZONE','Please select your time zone from the list below:');
?>
