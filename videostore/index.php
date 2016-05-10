<?php
ob_start();
/*
  $Id: index.php,v 1.1 2003/06/11 17:37:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

/* CURRENT PROJECT FILE*/
  require('includes/application_top.php');

// the following cPath references come from application_top.php
  $category_depth = 'top';
  if (tep_not_null($cPath)) {

//  setcookie(Category_Path_Link,$cPath,time()+10800); //Cookie expire in 3 hours

  $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
    $categories_products = tep_db_fetch_array($categories_products_query);
    if ($categories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
 <?php
 /*
	// RJW Begin Meta Tags Code
	if (file_exists(DIR_WS_INCLUDES . 'meta_tags.php')) {
		require(DIR_WS_INCLUDES . 'meta_tags.php');
	} else {
		// BOF: WebMakers.com Changed: Header Tag Controller v1.0
		// Replaced by header_tags.php
		if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
			require(DIR_WS_INCLUDES . 'header_tags.php');
		} else {
?>
  <title><?php echo TITLE ?></title>
<?php
		}
// RJW End Meta Tags Code
	}
// EOF: WebMakers.com Changed: Header Tag Controller v1.0
*/
?>
<?php
 //error_reporting(E_ALL);ini_set('display_errors', '1');
require_once('includes/addons/osc_metatags.php');?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>
<link rel="stylesheet" type="text/css" href="menustyle.css" media="screen, print" >
<script src="menuscript.js"  type="text/javascript"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? "https://" : "http://")?>www.shawnolson.net/scripts/public_smo_scripts.js"></script>
<link rel="shortcut icon" href="/favicon.ico">
<link rel="icon" type="image/gif" href="animated_favicon1.gif" >
<link rel="apple-touch-icon" href="apple-touch-icon.png" >
<meta name="msvalidate.01" content="2234D77B2C0249194B9EFBE1275A0217" >
</head>
<body>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php');?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td colspan="3">

		</td>
	</tr>
  	<tr>
    	<td width="<?php echo BOX_WIDTH; ?>" valign="top">
    		<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    		</table>
    	</td>
<!-- body_text //-->
<?php
// CATEGORIES DESCRIPTION V1.5 ADDED "cd.categories_heading_title, cd.categories_description,"
if ($category_depth == 'nested') {
    $category_query = tep_db_query("select cd.categories_name, cd.categories_heading_title, cd.categories_description, c.categories_image, c.categories_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
    $category = tep_db_fetch_array($category_query);

?>
    	<td width="100%" valign="top">
    		<table border="0" width="100%" cellspacing="0" cellpadding="0">
      			<tr>
        			<td>
        				<table border="0" width="100%" cellspacing="0" cellpadding="0">
          					<tr>

<!-- REMOVED<td class="pageHeading">
<?php //echo HEADING_TITLE; ?>
</td>         -->
            					<td class="pageHeading" valign="top">
<?php
	if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_heading_title'])) ) {
    	echo $category['categories_heading_title'];
    } else {
    	echo HEADING_TITLE;
    }
?>
            					</td>
<!-- END CATEGORIES DESCRIPTION v1.5-->
<!--<td class="pageHeading" align="right">
<?php 
	echo tep_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
</td>-->
          					</tr>
<?php 
	if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_description'])) && ($category_depth != 'top') ) { 
?>
	  						<tr>
<?php // Write contents of categories_description to a tempory file so it can be included into index.php
		$tmp_html_file = fopen (DIR_FS_DOWNLOAD_PUBLIC . FILENAME_TEMP_PHP_OUTPUT, "w");
	  	if ($tmp_html_file) fwrite ($tmp_html_file, $category['categories_description']);
	  		fclose ($tmp_html_file); ?>
            					<td align="left" colspan="2" class="category_desc"><?php //echo $category['categories_description']; ?>
<?php // Include categories description file
include(DIR_FS_DOWNLOAD_PUBLIC . FILENAME_TEMP_PHP_OUTPUT);?>
								</td>
	  						</tr>
<?php 
		} 
?>

        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>


      <td></td></tr>
    </table>
<?php

  } elseif ($category_depth == 'products' || isset($HTTP_GET_VARS['distributors_id']) || isset($HTTP_GET_VARS['producers_id']) || isset($HTTP_GET_VARS['series_id'])) {

    if(isset($HTTP_GET_VARS['distributors_id']))
    {
  	  $distributors_query = tep_db_query("select d.distributors_name, d.distributors_description, d.distributors_image from distributors  d where d.distributors_id = '" . (int)$HTTP_GET_VARS['distributors_id']  . "'");
  	  $distributors = tep_db_fetch_array($distributors_query);
    }
    if(isset($HTTP_GET_VARS['producers_id']))
    {
  	  $producers_query = tep_db_query("select pr.producers_name, pr.producers_description, pr.producers_image from producers pr where pr.producers_id = '" . (int)$HTTP_GET_VARS['producers_id']  . "'");
  	  $producers = tep_db_fetch_array($producers_query);
    }
    if(isset($HTTP_GET_VARS['series_id']))
    {
  	  $series_query = tep_db_query("select s.series_name, s.series_description, s.series_image from series   s where s.series_id = '" . (int)$HTTP_GET_VARS['series_id']  . "'");
  	  $series = tep_db_fetch_array($series_query);
    }




// create column list
    $define_list = array('PRODUCT_LIST_MEDIA' => PRODUCT_LIST_MEDIA,
			 'PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_URL' => PRODUCT_LIST_URL,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,
                         'PRODUCT_LIST_REGION_CODE'=>PRODUCT_LIST_REGION_CODE); 
    asort($define_list);

    $column_list = array();

    reset($define_list);
    while (list($key, $value) = each($define_list)) {
      if ($value > 0) $column_list[] = $key;
    }

    $select_column_list = '';

    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
      switch ($column_list[$i]) {
        case 'PRODUCT_LIST_MEDIA':
          $select_column_list .= 'p.products_media_type_id, p.products_date_available, ';
          break;
        case 'PRODUCT_LIST_MODEL':
          $select_column_list .= 'p.products_model, ';
          break;
        case 'PRODUCT_LIST_NAME':
          $select_column_list .= 'pd.products_name, ';
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $select_column_list .= 'm.manufacturers_name, ';
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $select_column_list .= 'p.products_quantity, ';
          break;
        case 'PRODUCT_LIST_IMAGE':
          $select_column_list .= 'p.products_image, ';
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $select_column_list .= 'p.products_weight, ';
          break;
        case 'PRODUCT_LIST_URL':
          $select_column_list .= 'pd.products_clip_url, ';
          break;
        case 'PRODUCT_LIST_REGION_CODE':
          $select_column_list .= 'rc.products_region_code_desc, ';
          break;
      }
    }
// show the products of a specified manufacturer
/*SYSTWEAK: distributor, producers selected products enabled*/
$products_region_code = "pr.products_region_code_desc, pr.products_region_code_id";
$left_join_region_code = "left join " . TABLE_REGION_CODE . " pr on ( p.products_region_code_id = pr.products_region_code_id )";

    if (isset($HTTP_GET_VARS['distributors_id'])) {
      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {
		// We are asked to show only a specific category
/* SYSTWEAK: Modified the query to match the listing */
        $listing_sql = "select " . $select_column_list . " p.products_always_on_hand, p.products_distribution,  p.has_rights, p.products_id, p.products_quantity , p.products_out_of_print , p.products_release_date, d.distributors_id, p.products_price, p.products_tax_class_id, st.products_set_type_name, p.products_media_type_id, se.series_name, " . $products_region_code . ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name_suffix, pd.products_description, pd.products_clip_url, p.products_byline, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from (((" . TABLE_PRODUCTS . " p, " . TABLE_DISTRIBUTORS . " d, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c) left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id)) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " . $left_join_region_code . ") left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.distributors_id = d.distributors_id and d.distributors_id='" . (int)$HTTP_GET_VARS['distributors_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'";

      }
       else {
// We show them all
             $listing_sql = "select " . $select_column_list . " p.products_always_on_hand, p.products_distribution, p.has_rights, p.products_id, p.products_quantity , p.products_out_of_print , p.products_release_date,  d.distributors_id, p.products_price, p.products_tax_class_id, st.products_set_type_name, p.products_media_type_id, se.series_name, " . $products_region_code . ",  pd.products_buy_download_link, pd.products_name_prefix, pd.products_name_suffix, pd.products_description, pd.products_clip_url, p.products_byline, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from (((" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_DISTRIBUTORS . " d) left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id)) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " . $left_join_region_code . ") left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pd.products_id = p.products_id and p.distributors_id = d.distributors_id and d.distributors_id = '" . (int)$HTTP_GET_VARS['distributors_id'] . "'";
      }
   }
   elseif (isset($HTTP_GET_VARS['producers_id'])) {
      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {
		// We are asked to show only a specific category
/* SYSTWEAK: Modified the query to match the listing */
        $listing_sql = "select " . $select_column_list . " p.products_always_on_hand,p.has_rights, p.products_distribution,  p.products_id, p.products_quantity , p.products_out_of_print , p.products_release_date,  pcr.producers_id, p.products_price, p.products_tax_class_id, st.products_set_type_name, p.products_media_type_id, se.series_name, " . $products_region_code . ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name_suffix, pd.products_description, pd.products_clip_url, p.products_byline, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from (((" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCERS . " pcr, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c) left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id)) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " . $left_join_region_code . ") left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.producers_id = pcr.producers_id and pcr.producers_id='" . (int)$HTTP_GET_VARS['producers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'";

      }
       else {
// We show them all
             $listing_sql = "select " . $select_column_list . " p.products_always_on_hand,p.has_rights, p.products_distribution,   p.products_id, p.products_quantity , p.products_out_of_print , p.products_release_date,  pcr.producers_id, p.products_price, p.products_tax_class_id, st.products_set_type_name, p.products_media_type_id, se.series_name, " . $products_region_code . ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name_suffix, pd.products_description, pd.products_clip_url, p.products_byline, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from (((" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCERS . " pcr) left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id)) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " . $left_join_region_code . ") left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pd.products_id = p.products_id and p.producers_id = pcr.producers_id and pcr.producers_id = '" . (int)$HTTP_GET_VARS['producers_id'] . "'";
      }

}
 elseif (isset($HTTP_GET_VARS['series_id'])) {
      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {
		// We are asked to show only a specific category
 /*SYSTWEAK: Modified the query to match the listing*/
        $listing_sql = "select " . $select_column_list . "  p.products_always_on_hand, p.products_distribution, p.has_rights, p.products_id, p.products_quantity , p.products_out_of_print ,  p.products_release_date,  srs.series_id, p.products_price, p.products_tax_class_id, st.products_set_type_name, p.products_media_type_id, srs.series_name, " . $products_region_code . ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name_suffix, pd.products_description, pd.products_clip_url, p.products_byline, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from (((" . TABLE_PRODUCTS . " p, " . TABLE_SERIES . " srs, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c) left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id)) left join series rs on (p.series_id = rs.series_id) " . $left_join_region_code . ") left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.series_id = srs.series_id and srs.series_id='" . (int)$HTTP_GET_VARS['series_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'";

      }
       else {
// We show them all
             $listing_sql = "select " . $select_column_list . " p.products_always_on_hand, p.products_distribution, p.has_rights, p.products_id, p.products_quantity , p.products_out_of_print ,  p.products_release_date,  srs.series_id, p.products_price, p.products_tax_class_id, st.products_set_type_name, p.products_media_type_id, srs.series_name, " . $products_region_code . ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name_suffix, pd.products_description, pd.products_clip_url, p.products_byline, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from (((" . TABLE_PRODUCTS . " p, " . TABLE_SERIES . " srs, " . TABLE_PRODUCTS_DESCRIPTION . " pd) left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id)) left join series rs on (p.series_id = rs.series_id) " . $left_join_region_code . ") left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pd.products_id = p.products_id and p.series_id = srs.series_id and srs.series_id = '" . (int)$HTTP_GET_VARS['series_id'] . "'";
      }

}
   // show the products in a given categorie
   else{
     if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {
// We are asked to show only specific catgeory
        $listing_sql = "select " . $select_column_list . " p.products_always_on_hand, p.products_distribution, p.has_rights, p.products_id,  p.products_quantity , p.products_out_of_print , p.products_release_date,  p.distributors_id, p.products_price, p.products_tax_class_id, st.products_set_type_name, p.products_media_type_id, se.series_name, " . $products_region_code . ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name_suffix, pd.products_description, pd.products_clip_url, p.products_byline, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from (((" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c) left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id)) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " . $left_join_region_code . ") left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.distributors_id = d.distributors_id and d.distributors_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
//        die($listing_sql);
      } else {
// We show them all
        $listing_sql = "select " . $select_column_list . " p.products_always_on_hand, p.products_distribution, p.has_rights, p.products_id, p.products_quantity , p.products_out_of_print , p.products_release_date,  p.distributors_id, p.products_price, p.products_tax_class_id, st.products_set_type_name, p.products_media_type_id, se.series_name, " . $products_region_code . ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name_suffix, pd.products_description, pd.products_clip_url, p.products_byline, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from ((((" . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p) left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id)) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " . $left_join_region_code . ") left join " . TABLE_DISTRIBUTORS . " d on p.distributors_id = d.distributors_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
      }
}
//     die ($listing_sql);
    if ( (!isset($HTTP_GET_VARS['sort'])) || (!preg_match('/[1-8][ad]/', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {
      for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
        // if ($column_list[$i] == 'PRODUCT_LIST_NAME') { //Changed here for sorting
        if ($column_list[$i] == 'PRODUCT_LIST_IMAGE') {
        	#$HTTP_GET_VARS['sort'] = $i+1 . 'a';
          //$HTTP_GET_VARS['sort'] = $i+1 . 'd';//Changed here for sorting
          //$listing_sql .= " order by pd.products_name";
          //$listing_sql .= " order by p.products_quantity desc";//Changed here for sorting
          $listing_sql .= ' order by p.products_always_on_hand desc, p.products_quantity desc, pd.products_name';
          break;
        }
        else if ($column_list[$i] == 'PRODUCT_LIST_NAME') {
        $listing_sql .= " order by pd.products_name";
        }
      }
    } else {
      $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
      $sort_order = substr($HTTP_GET_VARS['sort'], 1);
      $listing_sql .= ' order by ';
      switch ($column_list[$sort_col-1]) {
        case 'PRODUCT_LIST_MEDIA':
          //$listing_sql .= "p.products_media_type_id " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          $listing_sql .= "p.products_media_type_id " . ($sort_order == 'd' ? "desc" : "") . ", p.products_quantity desc, pd.products_name ";//CHANGED HERE
          break;
        case 'PRODUCT_LIST_MODEL':
          $listing_sql .= "p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_NAME':
          $listing_sql .= "pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $listing_sql .= "m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $listing_sql .= "p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_IMAGE':
          //$listing_sql .= "pd.products_name"; // Changed here for sorting
          //$listing_sql .= "p.products_always_on_hand desc, p.products_quantity desc, pd.products_name";
          $listing_sql .= "p.products_release_date " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $listing_sql .= "p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
       case 'PRODUCT_LIST_URL':
          $listing_sql .= "pd.products_clip_url " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_PRICE':
          //$listing_sql .= "final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name"; //Changed here for sorting
          $listing_sql .= "final_price " . ($sort_order == 'd' ? "desc" : "") . ", p.products_quantity desc, pd.products_name ";//CHANGED HERE
          break;
      }
    }
// CATEGORIES DESCRIPTION v1.5
    // Get the category name and description
    $category_query = tep_db_query("select cd.categories_name, cd.categories_heading_title, cd.categories_description, c.categories_image, c.brochure_image, c.categories_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $current_category_id . "' and cd.categories_id = '" . $current_category_id . "' and cd.language_id = '" . $languages_id . "'");
    $category = tep_db_fetch_array($category_query);


?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
<!--REMOVED CATEGORIES DESCRIPTION v1.5<td class="pageHeading"><?php //echo HEADING_TITLE; ?></td>-->
<!-- CATEGORIES DESCRIPTON v1.5 -->
            <td class="pageHeading">
             <?php
               if ((ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_heading_title']))) {
                 echo $category['categories_heading_title'];
               } else {
                 echo HEADING_TITLE;
               }
             ?>
            </td>
<!-- END OF-->

<?php
// optional Product List Filter




    if (PRODUCT_LIST_FILTER > 0) {
/*      if (isset($HTTP_GET_VARS['manufacturers_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' order by cd.categories_name";
      }
      else*/
      if (isset($HTTP_GET_VARS['distributors_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.distributors_id = '" . (int)$HTTP_GET_VARS['distributors_id'] . "' order by cd.categories_name";
      }
      elseif (isset($HTTP_GET_VARS['producers_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.producers_id = '" . (int)$HTTP_GET_VARS['producers_id'] . "' order by cd.categories_name";
      }
      elseif (isset($HTTP_GET_VARS['series_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.series_id = '" . (int)$HTTP_GET_VARS['series_id'] . "' order by cd.categories_name";
      }
           /******************     else {
        $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
      } */

      $filterlist_query = tep_db_query($filterlist_sql);
/**********************************/
      if (tep_db_num_rows($filterlist_query) > 1) {
        echo '<td align="center" class="main">' . tep_draw_form('filter', FILENAME_DEFAULT, 'get') . '<b>Show</b>' . '&nbsp;';
/***********************************/
        /*if (isset($HTTP_GET_VARS['manufacturers_id'])) {
          echo tep_draw_hidden_field('manufacturers_id', $HTTP_GET_VARS['manufacturers_id']);
          $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
        }
        else*/

        if (isset($HTTP_GET_VARS['distributors_id'])) {
          echo tep_draw_hidden_field('distributors_id', $HTTP_GET_VARS['distributors_id']);
          $options = array(array('id' => '', 'text' => 'Show All Categories'));
        }
		elseif (isset($HTTP_GET_VARS['producers_id'])) {
          echo tep_draw_hidden_field('producers_id', $HTTP_GET_VARS['producers_id']);
          $options = array(array('id' => '', 'text' => 'Show All Categories'));
        }
        elseif (isset($HTTP_GET_VARS['series_id'])) {
          echo tep_draw_hidden_field('series_id', $HTTP_GET_VARS['series_id']);
          $options = array(array('id' => '', 'text' => 'Show All Categories'));
        }
        /*else {
          echo tep_draw_hidden_field('cPath', $cPath);
          $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
        } */
        echo tep_draw_hidden_field('sort', $HTTP_GET_VARS['sort']);
        while ($filterlist = tep_db_fetch_array($filterlist_query)) {
          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
        }
        echo tep_draw_pull_down_menu('filter_id', $options, (isset($HTTP_GET_VARS['filter_id']) ? $HTTP_GET_VARS['filter_id'] : ''), 'onchange="this.form.submit()"');
        echo '</form></td>' . "n";
      }
    }







// Get the right image for the top-right
    $image = DIR_WS_IMAGES . 'table_background_list.gif';
    /*if (isset($HTTP_GET_VARS['manufacturers_id'])) {
      $image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['manufacturers_image'];
    } else*/
    if ($current_category_id > 0) {
      $image = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['categories_image'];
    }
/*SYSTWEAK EDIT STARTS*/
    elseif (isset($HTTP_GET_VARS['distributors_id'])) {
      $image = tep_db_query("select distributors_image from " . TABLE_DISTRIBUTORS . " where distributors_id = '" . (int)$HTTP_GET_VARS['distributors_id'] . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['distributors_image'];
    }
    elseif (isset($HTTP_GET_VARS['producers_id'])) {
      $image = tep_db_query("select producers_image from " . TABLE_PRODUCERS . " where producers_id = '" . (int)$HTTP_GET_VARS['producers_id'] . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['producers_image'];
    }
    elseif (isset($HTTP_GET_VARS['series_id'])) {
      $image = tep_db_query("select series_image from " . TABLE_SERIES . " where series_id = '" . (int)$HTTP_GET_VARS['series_id'] . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['series_image'];
    }
    /*SYSTWEAK EDIT ENDS*/
?>
            <td align="right"><?php echo tep_image(DIR_WS_IMAGES . $image, HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
            </td>
          </tr>
<?php //CATEGORIES DESCRIPTIONS v1.5
			  if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_description'])) ) { ?>
	  <tr>
			  <?php // Write contents of categories_description to a tempory file so it can be included into index.php
			  $tmp_html_file = fopen (DIR_FS_DOWNLOAD_PUBLIC . FILENAME_TEMP_PHP_OUTPUT, "w");
			  if ($tmp_html_file)
				fwrite ($tmp_html_file, $category['categories_description']);
			  fclose ($tmp_html_file); ?>
					<td align="left" colspan="2" class="category_desc"><?php //echo $category['categories_description']; ?>
					<?php // Include categories description file
					  include(DIR_FS_DOWNLOAD_PUBLIC . FILENAME_TEMP_PHP_OUTPUT);?>
					</td>
			<?php
			//echo $category['categories_description']; ?>
	  </tr>
<?php } //END OF CATEGORIES DESCRIPTIONS?>
<?php //Distributors DESCRIPTIONS v1.5
			  if (tep_not_null($distributors['distributors_description'])) { ?>
	  <tr>
            <td align="left" colspan="2" class="category_desc"><?php echo $distributors['distributors_description']; ?></td>
	  </tr>
<?php } //END OF Distributors DESCRIPTIONS?>
<?php //Producers DESCRIPTIONS v1.5
			  if (tep_not_null($producers['producers_description'])) { ?>
	  <tr>
            <td align="left" colspan="2" class="category_desc"><?php echo $producers['producers_description']; ?></td>
	  </tr>
<?php } //END OF Producers DESCRIPTIONS?>
<?php //Series DESCRIPTIONS v1.5
			  if (tep_not_null($series['series_description'])) { ?>
	  <tr>
            <td align="left" colspan="2" class="category_desc"><?php echo $series['series_description']; ?></td>
	  </tr>
<?php } //END OF Series DESCRIPTIONS?>
<? if (trim($category[brochure_image]) != "") { ?>
<script>
	function winOpen(id){
	window.open('category_image.php?cID='+id,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
	}
</script>
<tr>
	<td align="right" colspan="2" class="main"><a href="#" onClick="winOpen('<?=$category[categories_id]?>'); return false;" style="color:blue; font-weight: bold; font-size: 14px;">View Category Brochure</a></td>
</tr>
<? } ?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php /*include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING)*/ include(DIR_WS_MODULES . 'product_listing_new.php')?></td>


      </tr>
    </table></td>
<?php
  } else { // default page
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php // echo tep_image(DIR_WS_IMAGES . 'table_background_default.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
            </td>
          </tr>
        </table></td>
      </tr>

          <tr>
            <td class="main"><?php include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFINE_MAINPAGE); ?></td>
          </tr>
      	<tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td><?php include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); ?></td>
          </tr>
		  <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
		  <tr>
            <td><?php include(DIR_WS_MODULES . FILENAME_FEATURED); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<?php
  }
?>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
