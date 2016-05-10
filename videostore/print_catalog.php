<?php
/*
  $Id: print_catalog.php,v 1.2 2004/09/17 Stephen Walker SNJ Computers$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRINT_CATALOG);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRINT_CATALOG, '', 'NONSSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
 <title><?php echo TITLE; ?></title>
 <base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
 <link rel="stylesheet" type="text/css" href="catalogstylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<table class="products" width="775" align="center" border="0" cellspacing="1" cellpadding="1">
 <tr>
<!-- header -->
  <td><table class="header" width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td><table class="headerContents" width="100%" border="0" cellspacing="2" cellpadding="2">
     <tr>
      <td align="left"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
      <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'oscommerce.gif', 'osCommerce'); ?></td>
     </tr>
    </table></td>
   </tr>
   <tr>
    <td><table class="headerContents" width="100%" border="0" cellspacing="2" cellpadding="2">
     <tr>
      <td><?php echo (HEADER_TEXT_1) . '&nbsp;' . strftime(DATE_FORMAT_LONG); ?></td>
     </tr>
    </table></td>
   </tr>
  </table></td>
<!-- header-eof -->
 </tr>
<?php
 $print_catalog_query_raw = "select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, cd.categories_name, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p.products_id=p2c.products_id left join " . TABLE_CATEGORIES . " c on p2c.categories_id=c.categories_id left join " .	TABLE_CATEGORIES_DESCRIPTION . " cd on c.parent_id='0' and c.categories_id=cd.categories_id left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where products_status = '1' order by cd.categories_name, c.parent_id, c.sort_order, c.categories_id, pd.products_name";

 //$print_catalog_split = new splitPageResults($print_catalog_query_raw, MAX_DISPLAY_PRINT_CATALOG);
 //$featured_products_split = new splitPageResults($featured_products_query_raw, MAX_DISPLAY_FEATURED_PRODUCTS);
$print_catalog_query = tep_db_query($print_catalog_query_raw);
 while ($print_catalog = tep_db_fetch_array($print_catalog_query)) {
       $print_catalog_array[] = array('id' => $print_catalog['products_id'],
			                          'name' => $print_catalog['products_name'],
			                          'description' => $print_catalog['products_description'],
                                      'model' => $print_catalog['products_model'],
                                      'image' => $print_catalog['products_image'],
	                                  'price' => $print_catalog['products_price'],
                                      'specials_price' => $print_catalog['specials_new_products_price'],
 	                                  'tax_class_id' => $print_catalog['products_tax_class_id'],
	                                  'date_added' => tep_date_long($print_catalog['products_date_added']),
	                                  'manufacturer' => $print_catalog['manufacturers_name']);
 }
?>
 <tr>
<!-- top page navigation -->
<!--  <td><table class="header" border="0" width="100%" cellspacing="2" cellpadding="2">
   <tr>
    <td class="smallText">
<?php 
 //echo $print_catalog_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); 
?>
    </td>						
    <td align="right" class="smallText">
<?php 
 //echo TEXT_RESULT_PAGE;
// echo $print_catalog_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); 
?>
    </td>
   </tr>
  </table></td> -->
<!-- top page navigation-eof -->
 </tr>
 <tr>
<!-- catalog -->
  <td><?php require(DIR_WS_MODULES  . 'print_catalog.php');?></td>
<!-- catalog-eof -->
 </tr>
<?php
 // if (($print_catalog_split->number_of_rows > 0) && (PREV_NEXT_BAR_LOCATION == '2' || PREV_NEXT_BAR_LOCATION == '3')) {
?>
 <tr>
<!-- bottom page navigation -->
<!--  <td><table class="header" border="0" width="100%" cellspacing="2" cellpadding="2">
   <tr>
    <td class="smallText">
<?php
 //echo $print_catalog_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); 
?>
    </td>
    <td align="right" class="smallText">
<?php 
 //echo TEXT_RESULT_PAGE;
 //echo $print_catalog_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); 
?>
    </td>
   </tr>
  </table></td> -->
<!-- bottom page navigation-eof -->
 </tr>
<?php
//  }
?>
 <tr>
<!-- footer -->
  <td><table class="footer" width="100%" border="0" cellspacing="2" cellpadding="2">
   <tr>
    <td align="center"><?php echo (FOOTER_TEXT_BODY); ?></td>
   </tr>
  </table></td>
<!-- footer-eof -->
 </tr>
</table>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>