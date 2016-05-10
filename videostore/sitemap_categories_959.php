<?php
/*
  $Id: sitemap.php,v 1.0 2004/05/21 by johnww (jw at perfectproof.com)

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
  ---------------------------------------------
  Based on all_products.php,v 3.0 2004/02/21 by Ingo (info@gamephisto.de)
  ---------------------------------------------
  Based on Snake SEO (Search Engine Optimizer) by www.snakelab.de
  ---------------------------------------------

*/

  require('includes/application_top_sitemap.php');

  include('includes/sitemap_includes.php');

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo SITEMAP_TITLE; ?></title>
<meta name="Keywords" content="<?php echo SITEMAP_KEYWORDS; ?>">
<meta name="Description" content="<?php echo SITEMAP_DESCRIPTION; ?>">
<META NAME="coverage" CONTENT="Worldwide">
<META NAME="robots" CONTENT="index,follow"> 
<META NAME="country" CONTENT="<?php echo SITEMAP_COUNTRYCODE; ?>">
<META NAME="author" CONTENT="<?php echo SITEMAP_AUTHOR; ?>">
<META NAME="organization-Email" CONTENT="<?php echo SITEMAP_EMAIL; ?>">
<META NAME="copyright" CONTENT="<?php echo SITEMAP_COPYRIGHT; ?>">

<base href="<?php echo  HTTP_SERVER . DIR_WS_HTTP_CATALOG; ?>">


<style type="text/css">
h1 {font-family: Verdana, Arial, sans-serif; font-size: 11px; color: #000000; }
a {font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #000000; }
body {font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #000000; }
</style>
</head>
<body>
   
<?php

$category_links = '';
$products_links = '';

$included_categories_query = tep_db_query("SELECT c.categories_id, c.parent_id, cd.categories_name, cd.categories_heading_title FROM " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd WHERE c.categories_id = cd.categories_id AND cd.language_id = FLOOR($languages_id)  AND c.parent_id =959 ORDER BY cd.categories_name");

$inc_cat = array();
while ($included_categories = tep_db_fetch_array($included_categories_query)) {
  $inc_cat[] = array (
     'id' => $included_categories['categories_id'],
     'parent' => $included_categories['parent_id'],
     'name' => $included_categories['categories_name']);
  }
$cat_info = array();
for ($i=0; $i<sizeof($inc_cat); $i++)
  $cat_info[$inc_cat[$i]['id']] = array (
    'parent'=> $inc_cat[$i]['parent'],
    'name'  => $inc_cat[$i]['name'],
    'path'  => $inc_cat[$i]['id']);

for ($i=0; $i<sizeof($inc_cat); $i++) {
  $cat_id = $inc_cat[$i]['id'];
  while ($cat_info[$cat_id]['parent'] > 0){
    $cat_info[$inc_cat[$i]['id']]['path'] = $cat_info[$cat_id]['parent'] . '_' . $cat_info[$inc_cat[$i]['id']]['path'];
    $cat_id = $cat_info[$cat_id]['parent'];
    }
 
  $category_links = $category_links . '<a href="category_' . $cat_info[$inc_cat[$i]['id']]['path'] . '.html#' .  $cat_info[$inc_cat[$i]['id']]['name'] . ' DVD VHS' . '"><h1>' . $cat_info[$inc_cat[$i]['id']]['name'] . ' DVD VHS' . '</h1></a><br />';   
  }

  echo $category_links;
?>

</body>
</html>

<?php
  require(DIR_WS_INCLUDES . 'application_bottom_sitemap.php');
?>