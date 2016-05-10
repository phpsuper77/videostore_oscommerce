<?php
/*
  $Id: create_xml_sitemaps.php,v 1.10 2007/10/05 Kevin L. Shelton $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
define('HEADING_TITLE', 'Creating XML Site Maps');
define('TEXT_CREATE_INDEX','Creating site map index file dated: ');
define('ERROR_INDEX_FILE','Unable to create site map index file!');
define('TEXT_FINDING_FILES','Finding files in '.HTTP_CATALOG_SERVER.DIR_WS_CATALOG.'<br>');
define('TEXT_CREATE_MAIN','Creating main files sitemap.<br>File URL --> Date Last Modified<br>');
define('ERROR_MAIN_FILE','Unable to create main site map file!');
define('TEXT_TOTAL_FILES',' total files<br>');
define('TEXT_CREATE_PRODUCTS','Creating products sitemap.<br>Product URL --> Date And Time Last Modified<br>');
define('ERROR_PRODUCTS_FILE','Unable to create products site map file!');
define('TEXT_TOTAL_PRODUCTS',' total products<br>');
define('TEXT_CREATE_CATEGORIES','Creating categories sitemap.<br>Category ID --> URL<br>');
define('ERROR_CATEGORIES_FILE','Unable to create categories site map file!');
define('TEXT_TOTAL_CATEGORIES',' total categories ');
define('TEXT_TOTAL_PAGES',' total pages<br>');
define('TEXT_CREATE_MANUFACTURERS','Creating manufacturers sitemap.<br>Manufacturer --> URL<br>');
define('ERROR_MANUFACTURERS_FILE','Unable to create manufacturers site map file!');
define('TEXT_TOTAL_MANUFACTURERS',' total manufacturers ');
define('TEXT_CREATE_SPECIALS','Creating Specials sitemap.<br>Page --> URL<br>');
define('ERROR_SPECIALS_FILE','Unable to create specials site map file!');
define('TEXT_COMPLETED',' total pages of specials<p>XML Site Map Creation Completed Successfully!<p>');
define('TEXT_TO_MAINTENANCE','Click here to return to Site Map Maintenance.');
define('GOOGLE_NOTIFIED', "Google has been successfully notified of your sitemap.");
define('GOOGLE_PROBLEM', "There was a problem notifying Google.");
?>