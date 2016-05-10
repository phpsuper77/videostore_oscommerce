<?php
/*
  $Id: sitemap.php,v 1.0 2004/05/21 by johnww (jw at perfectproof.com)

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
  ---------------------------------------------
  Based on all_distributors.php,v 3.0 2004/02/21 by Ingo (info@gamephisto.de)
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
   $distributors_query = tep_db_query("SELECT distributors_id, distributors_name FROM " . TABLE_DISTRIBUTORS . " ORDER BY distributors_name ");

   while($distributors = tep_db_fetch_array($distributors_query)) {
      $distributors_links = $distributors_links . '<a href="index.php/distributors_id/' . $distributors['distributors_id'] . '.html#' . $distributors['distributors_name'] . '">'  . $distributors['distributors_name'] . ' Travel Video Store' . '</a><br />';
   }

  echo $distributors_links;

?>

</body>
</html>

<?php
  require(DIR_WS_INCLUDES . 'application_bottom_sitemap.php');
?>