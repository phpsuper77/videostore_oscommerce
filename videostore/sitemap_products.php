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

<base href="<?php echo  HTTP_SERVER . DIR_WS_CATALOG; ?>">

<style type="text/css">
h1 {font-family: Verdana, Arial, sans-serif; font-size: 11px; color: #000000; }
a {font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #000000; }
body {font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #000000; }
</style>
</head>
<body>
   

<?php
   $products_query = tep_db_query("SELECT p.products_id, pd.products_name, pd. products_head_title_tag, pd.products_head_keywords_tag FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE p.products_id = pd.products_id AND p.products_status = 1 AND pd.language_id = FLOOR($languages_id) ORDER BY pd. products_head_title_tag");

   while($products = tep_db_fetch_array($products_query)) {
      $products_links = $products_links . '<a href="'.tep_href_link('','products_id='. $products['products_id']).'">'  . $products['products_head_keywords_tag'] . ' Travel Video Store DVD and Digital Downloads' . '</a><br />';
   }

  echo $products_links;

?>

</body>
</html>

<?php
  require(DIR_WS_INCLUDES . 'application_bottom_sitemap.php');
?>