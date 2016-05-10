<?php
/*
  $Id: sitemap.php,v 1.0 2004/05/21 by johnww (jw at perfectproof.com)

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
  ---------------------------------------------
   Based on Snake SEO (Search Engine Optimizer for Postnuke) by www.snakelab.de
  ---------------------------------------------

*/

// All the information below is designed solely for search engines. It will not appear
// in your normal pages.

define('SITEMAP_DOMAIN', HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_INDEX);   
// URL to your true home page
define('SITEMAP_TITLE', 'TravelVideoStore.com featuring Travel Videos in VHS  and DVD');                              
// Page Title
define('SITEMAP_KEYWORDS', 'travel videos VHS DVD film movie travel destination tourist sightseeing excursion destination travel tour travelogue trekker planning videos');            
// Keywords
define('SITEMAP_DESCRIPTION', 'Travel Video Store featuring Travel Videos of Sightseeing, Destination, Vacation, Cultural, Souvenir, Tourist, and Travelogue films, movies and videos in VHS and DVD.  Travel Videos are great for planning a vacation, reliving a memorable trip, learning about different cultures, experiencing the wonders of our world, or if relocating, getting to know your new town.

SEE THE WORLD FROM THE COMFORT OF YOUR OWN HOME');        
// Description
define('SITEMAP_COUNTRYCODE','US');                                             
// Country code of site (Optional)
define('SITEMAP_AUTHOR','Don Wyatt');                                       
// Site Author
define('SITEMAP_EMAIL','webmaster@travelvideostore.com');                                
// Contact e-mail
define('SITEMAP_COPYRIGHT','(c) 2004 TravelVideoStore.com. All rights reserved.');        
// Copyright
define('SITEMAP_CATEGORYLIST','List of product categories');                    
// Text displayed at top of sitemap_categories
define('SITEMAP_PRODUCTLIST','List of products');                               
// Text displayed at top of sitemap_products
define('SITEMAP_LINKS','<a href="http://www.travelvideostore.com/index.php">Another</a>');     // Add extra links to other sites

    function redirectBrowser()
    {
        print '<script language="JavaScript" type="text/javascript" src="sitemap.js"></script>';
    }
?>
