<?php

$filter = $HTTP_GET_VARS['filter'];
if ($filter!="") 
	$filter_string="p.products_media_type_id=".$filter." and ";
	else
	$filter_string=" ";

if (intval($per_page)==0)
	$needed_str=10;
else
	$needed_str=$per_page;

    $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
    $sort_order = substr($HTTP_GET_VARS['sort'], 1);
    $order_str = ' order by ';

if ($sort_col=="") $sort_col=3;
if ($sort_col==1) $order_str = "p.products_media_type_id " . ($sort_order == 'd' ? "desc" : "") . ", p.products_quantity desc, pd.products_name ";
if ($sort_col==2) $order_str = "p.products_release_date " . ($sort_order == 'd' ? "desc" : "");
if ($sort_col==3) $order_str = "pd.products_name " . ($sort_order == 'd' ? "desc" : "");
if ($sort_col==4) $order_str = "final_price " . ($sort_order == 'd' ? "desc" : "") . ", p.products_quantity desc, pd.products_name ";

/*
  $Id: new_products_more.php,v 1.27 5003/06/09 22:35:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 5003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FEATURED_PRODUCTS);
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADD_TO_CART_MSG);


  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FEATURED_PRODUCTS));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script>
	function getRights(){
		window.open('<?php echo tep_href_link("rights.php");?>','Rights','height=500, width=500, toolbar=no,menubr=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
	}
</script>
<?php include ('includes/ssl_provider.js.php'); ?>


</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">New Travel Videos Recently Added</td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php
 echo tep_draw_form('cart_multi_s', tep_href_link("new_products_more.php", tep_get_all_get_params(array('action')). 'cPath=150&action=add_multi_product&info_message='.MESSAGE1));
 echo tep_draw_separator('pixel_trans.gif', '100%', '10'); 
?></td>
      </tr>
<?php
///// To random featured products
//  list($usec, $sec) = explode(' ', microtime());
//  srand( (float) $sec + ((float) $usec * 100000) );
//  $mtm= rand();
//////
   $featured_products_array = array();
	$products_region_code = "pr.products_region_code_desc, pr.products_region_code_id";
	$left_join_region_code = "left join " . TABLE_REGION_CODE . " pr on ( p.products_region_code_id = pr.products_region_code_id )";
	
	if($HTTP_GET_VARS['sort']=='1a')
	{
		//$featured_products_query_raw = "select p.products_id, p.products_media_type_id, pd.products_buy_download_link, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, p.products_image, p.products_price,p.products_quantity, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description, pd.products_clip_url from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 30 order by p.products_media_type_id";// changes by kamaraj
		$featured_products_query_raw = "select st.products_set_type_name, p.products_release_date, p.adgregate, p.has_rights, p.products_distribution, p.products_always_on_hand, p.products_id, p.products_date_available, p.products_media_type_id, " .$products_region_code. ",pd.products_buy_download_link, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_clip_url, p.products_image, p.products_price,p.products_quantity, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " .$left_join_region_code ." left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) where".$filter_string."TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 90 and p.products_status = '1' order by p.products_media_type_id, p.products_quantity desc, pd.products_name ".$limit;//CHANGED HERE
	}
	elseif($HTTP_GET_VARS['sort']=='1d')
	{
		//$featured_products_query_raw = "select p.products_id, p.products_media_type_id, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, p.products_image, p.products_price,p.products_quantity, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description, pd.products_clip_url from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 90 order by p.products_media_type_id DESC";// changes by kamaraj
		$featured_products_query_raw = "select st.products_set_type_name, p.products_release_date, p.adgregate, p.has_rights, p.products_distribution, p.products_always_on_hand, p.products_id, p.products_date_available, p.products_media_type_id, " .$products_region_code. ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_clip_url, p.products_image, p.products_price,p.products_quantity, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " .$left_join_region_code ." left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) where".$filter_string."TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 90 and p.products_status = '1'  order by p.products_media_type_id DESC, p.products_quantity desc, pd.products_name ".$limit;//CHANGED HERE
	}
	elseif($HTTP_GET_VARS['sort']=='2a')
	{
		$featured_products_query_raw = "select st.products_set_type_name, p.products_release_date, p.adgregate, p.has_rights, p.products_distribution, p.products_always_on_hand, p.products_id, p.products_date_available, p.products_media_type_id, " .$products_region_code. ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_clip_url, p.products_image, p.products_price,p.products_quantity, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " .$left_join_region_code ." left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) where".$filter_string."TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 90 and p.products_status = '1'  order by p.products_release_date ".$limit;// changes by kamaraj
	}
	elseif($HTTP_GET_VARS['sort']=='2d')
	{
		$featured_products_query_raw = "select st.products_set_type_name, p.products_release_date, p.adgregate, p.has_rights, p.products_distribution, p.products_always_on_hand, p.products_id, p.products_date_available, p.products_media_type_id, " .$products_region_code. ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_clip_url, p.products_image, p.products_price,p.products_quantity, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " .$left_join_region_code ." left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) where".$filter_string."TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 90 and p.products_status = '1'  order by p.products_release_date DESC ".$limit;// changes by kamaraj
	}
	elseif($HTTP_GET_VARS['sort']=='3a')
	{
		$featured_products_query_raw = "select st.products_set_type_name, p.products_release_date, p.adgregate, p.has_rights, p.products_distribution, p.products_always_on_hand, p.products_id, p.products_date_available, p.products_media_type_id, " .$products_region_code. ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_clip_url, p.products_image, p.products_price,p.products_quantity, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " .$left_join_region_code ." left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) where".$filter_string."TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 30 and p.products_status = '1'  order by pd.products_name ".$limit;// changes by kamaraj
	}
	elseif($HTTP_GET_VARS['sort']=='3d')
	{
		$featured_products_query_raw = "select st.products_set_type_name, p.products_release_date, p.adgregate, p.has_rights, p.products_distribution, p.products_always_on_hand, p.products_id, p.products_date_available, p.products_media_type_id, " .$products_region_code. ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_clip_url, p.products_image, p.products_price,p.products_quantity, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " .$left_join_region_code ." left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) where".$filter_string."TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 90 and p.products_status = '1'  order by pd.products_name DESC ".$limit;// changes by kamaraj
	}
	elseif($HTTP_GET_VARS['sort']=='4a')
	{
		//$featured_products_query_raw = "select p.products_id, p.products_media_type_id, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_clip_url, p.products_image, p.products_price,p.products_quantity, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where".$filter_string."TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 90 and p.products_status = '1'  order by p.products_price ";// changes by kamaraj
		$featured_products_query_raw = "select st.products_set_type_name, p.products_release_date, p.adgregate, p.has_rights, p.products_distribution, p.products_always_on_hand, p.products_id, p.products_date_available, p.products_media_type_id, " .$products_region_code. ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_clip_url, p.products_image, p.products_price,p.products_quantity, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " .$left_join_region_code ." left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) where p.products_status = '1' and TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 90 and p.products_status = '1'  order by final_price , p.products_quantity desc, pd.products_name ".$limit;//CHANGED HERE
	}
	elseif($HTTP_GET_VARS['sort']=='4d')
	{
			//$featured_products_query_raw = "select p.products_id, p.products_media_type_id, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_clip_url, p.products_image, p.products_price,p.products_quantity, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 90 order by p.products_price DESC ";// changes by kamaraj
			$featured_products_query_raw = "select st.products_set_type_name, p.products_release_date, p.adgregate, p.has_rights, p.products_distribution, p.products_always_on_hand, p.products_id, p.products_date_available, p.products_media_type_id, " .$products_region_code. ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_clip_url, p.products_image, p.products_price,p.products_quantity, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " .$left_join_region_code ." left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) where".$filter_string."TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 90 and p.products_status = '1'  order by final_price DESC, p.products_quantity desc, pd.products_name ".$limit;//CHANGED HERE
	}
	else
	{
		//$featured_products_query_raw = "select p.products_id, p.products_media_type_id, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_clip_url, p.products_image, p.products_price,p.products_quantity, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where DATE_FORMAT(p.products_date_added,'%Y%m')=DATE_FORMAT(CURDATE(),'%Y%m') order by p.products_date_added";// changes by kamaraj
		//$featured_products_query_raw = "select p.products_id, p.products_media_type_id, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, p.products_image, p.products_price, p.products_quantity, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 90 order by p.products_quantity DESC";// changes by kamaraj
		$featured_products_query_raw = "select st.products_set_type_name, p.products_release_date, p.adgregate, p.has_rights, p.products_distribution, p.products_always_on_hand, p.products_date_available, p.products_id, p.products_media_type_id, " .$products_region_code. ", pd.products_buy_download_link, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_clip_url, p.products_image, p.products_price, p.products_quantity, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) " .$left_join_region_code ." left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) where".$filter_string."TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= 90 and p.products_status = '1'  order by p.products_always_on_hand desc, p.products_quantity desc, pd.products_name ".$limit;//CHANGED HERE
	}
// to random//  $featured_products_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_out_of_print, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_status = '1' and f.status = '1' order by rand($mtm)";
//echo $featured_products_query_raw;
   $featured_products_split = new splitPageResults($featured_products_query_raw, $needed_str);

  if (($featured_products_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
//echo $featured_products_query_raw;
?>
<tr>
<td colspan=2 bgcolor="yellow" width="100%"><center><font size=2>Use Heading Arrows for Ascending or Descending Sort - by Media, Title or Price</center></font></td>
</tr>
<?}?>
	<tr style="padding-top:2px;">
<td align=left class="smallText">
Show: 
	<select name="filter" style='font-size:11px;' onChange="getRefresh(this.value);">
		<option value="" <?if ($filter=='') echo 'selected';?>>All
		<option value="2" <?if ($filter=='2') echo 'selected';?>>DVD Only
		<option value="1" <?if ($filter=='1') echo 'selected';?>>VHS Only
	</select>
</td>
<td align=right class="smallText">
Per Page:
<select name="per_page" onChange="redirection(this.value);" style='font-size:11px;'>
		<option value="10" <? if ($needed_str==10) echo 'selected';?>>10
		<option value="20" <? if ($needed_str==20) echo 'selected';?>>20
		<option value="30" <? if ($needed_str==30) echo 'selected';?>>30
		<option value="50" <? if ($needed_str==50) echo 'selected';?>>50
		<option value="100" <? if ($needed_str==100) echo 'selected';?>>100
	</SELECT>
	</td></tr>
<?
  if (($featured_products_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td colspan=2><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText" width="50%"><?php echo $featured_products_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
            <td align="right" class="smallText" width="50%"><?php echo TEXT_RESULT_PAGE . ' ' . $featured_products_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>

<?php
  }
?>
      <tr>
        <td colspan=2> <!-- Featured Products Main Page Box -->
		 <!--<table bgcolor="ffffff" border="1" width="100%" cellspacing="0" cellpadding="2">-->
<?php
$define_list = array('PRODUCT_LIST_MEDIA' => PRODUCT_LIST_MEDIA,
			 'PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
		         'PRODUCT_LIST_URL' => PRODUCT_LIST_URL,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

    asort($define_list);

    $column_list = array();

    reset($define_list);
    while (list($key, $value) = each($define_list)) {
      if ($value > 0) $column_list[] = $key;
    }


$list_box_contents = array();

  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
	   case 'PRODUCT_LIST_MEDIA':
        $lc_text = 'VHS<BR>DVD<BR>';
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_MODEL':
        $lc_text = '';
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_NAME':
        $lc_text = 'Product Name (Brief Description)<br/>';
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $lc_text = '';
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_PRICE':
        $lc_text = 'Price<BR>';
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $lc_text = 'Stock';
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $lc_text = '';
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_URL':
        $lc_text = '';
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_IMAGE':
        $lc_text = '';
        $lc_align = 'center';
        break;
      case 'PRODUCT_LIST_BUY_NOW':
        $lc_text = 'Add to Cart<input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"';
        $lc_align = 'center';
        break;
    }

    if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
    	$srt=$HTTP_GET_VARS['sort'];
    	if(strlen($srt)==0)
    	{
      		$lc_text = tep_create_sort_heading('a', $col+1, $lc_text);
      	}
      	else
      	{
      		$lc_text = tep_create_sort_heading($HTTP_GET_VARS['sort'], $col+1, $lc_list);
      	}
      if (($col==2)) $lc_text.="<td bgcolor='#D50000'>";
    }

	$list_box_contents[0][] = array('align' => $lc_align,
                                    'params' => 'class="productListing-heading"',
                                    'text' => '&nbsp;' . $lc_text . '&nbsp;');

  }

  ?>

<table border="0" width="100%" cellspacing="0" cellpadding="0" class="productListing">
      <tr>
    <td align="right" class="productListing-heading" width="60">&nbsp;
<?
if ($sort_col==1 & $sort_order==a){
	$img1="_down.png";
	$sort1="1d"; 
	$text1="descendingly";
}elseif($sort_col==1 & $sort_order==d)  {
	$img1="_up.png";
	$sort1="1a";
	$text1="ascendingly";
}else{
	$img1="_up_down.png";
	$sort1="1a";
	$text1="ascendingly";
}
?>
	<a href="new_products_more.php?page=1&sort=<?=$sort1?>" title="Sort products <?=$text1?> by VHS<BR>DVD<BR>" class="productListing-heading">VHS<BR>DVD<BR>&nbsp;<img src="images/sort<?=$img1?>" width="18" height="10" border="0"></a>&nbsp;
    </td>
    <td align="center" class="productListing-heading">&nbsp;</td>
    <td class="productListing-heading">&nbsp;
<?
if ($sort_col==3 & $sort_order==a){
	$img3="_down.png";
	$sort3="3d";         
	$text3="descendingly";
}elseif($sort_col==3 & $sort_order==d)  {
	$img3="_up.png";
	$sort3="3a";
	$text3="ascendingly";
}else{
	$img3="_up_down.png";
	$sort3="3a";
	$text3="ascendingly";
}

if ($sort_col==2 & $sort_order==a){
	$img2="_down.png";
	$sort2="2d";         
	$text2="descendingly";
}elseif($sort_col==2 & $sort_order==d)  {
	$img2="_up.png";
	$sort2="2a";
	$text2="ascendingly";
}else{
	$img2="_up_down.png";
	$sort2="2d";
	$text2="descendingly";
}

?>
	<a href="new_products_more.php?page=1&sort=<?=$sort3?>" title="Sort products <?=$text3?> by Product Name (Brief Description)" class="productListing-heading">Product Name (Brief Description)&nbsp;&nbsp;<img src="images/sort<?=$img3?>" width="18" height="10" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="new_products_more.php?page=1&sort=<?=$sort2?>" title="Sort products <?=$text2?> by Release Date" class="productListing-heading">Release Date&nbsp;&nbsp;<img src="images/sort<?=$img2?>" width="18" height="10" border="0"></a>
</td>
    <TD class="productListing-heading">&nbsp;</td>	
    <td align="right" class="productListing-heading" style="padding-top:10px;">
<?
if ($sort_col==4 & $sort_order==a){
	$img4="_down.png";
	$sort4="4d"; 
	$text4="descendingly";
}elseif($sort_col==4 & $sort_order==d)  {
	$img4="_up.png";
	$sort4="4a";
	$text4="ascendingly";
}else{
	$img4="_up_down.png";
	$sort4="4a";
	$text4="ascendingly";
}
?>
	<a href="new_products_more.php?page=1&sort=<?=$sort4?>" title="Sort products <?=$text4?> by Price<BR>" class="productListing-heading">Price<BR>&nbsp;<img src="images/sort<?=$img4?>" width="18" height="10" border="0">&nbsp;</a>
</td>
    <td align="center" class="productListing-heading">&nbsp;Add to Cart<input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"&nbsp;</td>
      </tr>

<?php


  if ($featured_products_split->number_of_rows > 0) {
    $featured_products_query = tep_db_query($featured_products_split->sql_query);

	//made by kamaraj
	$rows = 0;

    while ($featured_products = tep_db_fetch_array($featured_products_query)) {
		 $rows++;
		 if (($rows/2) == floor($rows/2)) {
		 	        $list_box_contents[] = array('params' => 'class="productListing-even"');
		 	      } else {
		 	        $list_box_contents[] = array('params' => 'class="productListing-odd"');
		       }

		       $cur_row = sizeof($list_box_contents) - 1;
		       for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
			           $lc_align = '';

$part = explode("-",substr($featured_products[products_date_available],0,10));
$date_available = mktime(0, 0, 1, $part[1], $part[2], $part[0]);
$curr_date = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));

			           switch ($column_list[$col]) {
			   		  case 'PRODUCT_LIST_MEDIA':
			               $lc_align = 'right';
			               if($featured_products['products_media_type_id']=="1")
						   {
								$lc_text = '<a href="javascript:void(0)" onClick=javascript:window.open("./includes/languages/english/vhs.html","VHS","width=500,height=500,scrollbars=yes") alt="VHS - Click Here for More Info in VHS Formats"><img src=../images/media_type/' . $featured_products['products_media_type_id'] . '.gif border=0 alt="VHS - Click Here for More Info on VHS Formats"></img></a><BR>' . $featured_products['products_set_type_name'].'<br><b class="boxText">'.$featured_products['products_region_code_desc'].'<b>';
						   }
						   else if($featured_products['products_media_type_id']=="2")
						   {
								$lc_text = '<a href="javascript:void(0)" onClick=javascript:window.open("./includes/languages/english/dvd.html","DVD","width=500,height=500,scrollbars=yes") alt="DVD - Click Here for More Info on DVD Formats"><img src=../images/media_type/' . $featured_products['products_media_type_id'] . '.gif border=0 alt="DVD - Click Here for More Info on DVD Formats"></img></a><BR>' . $featured_products['products_set_type_name'].'<br><b class="boxText">'.$featured_products['products_region_code_desc'].'<b>';
						   }
						   else if($featured_products['products_media_type_id']=="4")
						   {
								$lc_text = '<a href="javascript:void(0)" onClick=javascript:window.open("./includes/languages/english/cd.html","CD","width=500,height=500,scrollbars=yes") alt="DVD - Click Here for More Info on DVD Formats"><img src=../images/media_type/' . $featured_products['products_media_type_id'] . '.gif border=0 alt="CD - Click Here for More Info on CD Formats"></img></a><BR>' . $featured_products['products_set_type_name'].'<br><b class="boxText">'.$featured_products['products_region_code_desc'].'<b>';
   							}
						   else
						   {
								$lc_text = '<img src=../images/media_type/' . $featured_products['products_media_type_id'] . '.gif border=0></img><BR>' . $featured_products['products_set_type_name'].'<br><b class="boxText">'.$featured_products['products_region_code_desc'].'<b>';
   							}
			               //$lc_text = '<img src=../images/media_type/' . $featured_products['products_media_type_id'] . '.gif border=0></img><BR>' . $featured_products['products_set_type_name'];
			               break;
			             case 'PRODUCT_LIST_MODEL':
			               $lc_align = '';
			               $lc_text = '&nbsp;' . $featured_products['products_model'] . '&nbsp;';
			               break;
			             case 'PRODUCT_LIST_NAME':
			               $lc_align = '';
if ($featured_products['has_rights']=='1')
	$has_rights = '&nbsp;<a href="#" style="color:red;" onclick="getRights(); return false;">Includes Limited Public Performance Rights</a><br/>';
	else
	$has_rights = '';

			               if (isset($HTTP_GET_VARS['manufacturers_id'])) {
			   				$strip_tag_desc = strip_tags($featured_products['products_description'], '<b>');
			   				$products_combine = ($featured_products['products_byline'] . '&nbsp;&nbsp;' . $strip_tag_desc);
			   				if($featured_products['products_release_date']!='')
			   				$listing_products_year="<b>(".$featured_products['products_release_date'].")</b>";
			   				else
			   				$listing_products_year="";
			   				//$listing_products_summary = ((strlen($products_combine) > 500) ? substr($products_combine, 0, 500) . '...' : $products_combine);
			   				if (tep_not_null($featured_products['products_clip_url'])) {				
$listing_products_summary = ((strlen($products_combine) > 500) ? substr($products_combine, 0, 500) .'</b></i><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '" title="More Video Info"><img src="images/moreinfo.gif" border="0" Align="Top" alt="More Info"</a>' .  '<a href="#" onClick="window.open(\'' . ($featured_products['products_clip_url']) . '\',\'_blank\',\'width=640,height=460\');return false"><img src="images/free_video_clip.gif" border="0" Align="Top" alt="View Free Video Clip"</a>' : $products_combine);}


else {

$listing_products_summary = ((strlen($products_combine) > 500) ? substr($products_combine, 0, 500) .'</b></i><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '" title="More Video Info"><img src="images/moreinfo.gif" border="0" alt="More Info"</a>' : $products_combine);}

			   			$lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $featured_products['products_id']) . '"><b>' . $featured_products ['series_name'] . '</b><BR>' . $featured_products['products_name_prefix'] . '&nbsp;<b>' . $featured_products['products_name'] . '</b>&nbsp;' . $featured_products['products_name_suffix'] . '</a>'.$listing_products_year.'<BR>'.$has_rights.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $listing_products_summary ;
			               } else {
			   				$strip_tag_desc = strip_tags($featured_products['products_description'], '<b>');
			   				$products_combine = ($featured_products['products_byline'] . '&nbsp;&nbsp;' . $strip_tag_desc);
			   				//$listing_products_summary = ((strlen($products_combine) > 500) ? substr($products_combine, 0, 500) . '...' : $products_combine);
			   				if (tep_not_null($featured_products['products_clip_url'])) {				
$listing_products_summary = ((strlen($products_combine) > 500) ? substr($products_combine, 0, 500) .'</b></i><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '" title="More Video Info"><img src="images/moreinfo.gif" border="0" Align="Top" alt="More Info"</a>' .  '<a href="#" onClick="window.open(\'' . ($featured_products['products_clip_url']) . '\',\'_blank\',\'width=640,height=460\');return false"><img src="images/free_video_clip.gif" border="0" alt="View Free Video Clip"</a>' : $products_combine);}
else {
$listing_products_summary = ((strlen($products_combine) > 500) ? substr($products_combine, 0, 500) .'</b></i><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '" title="More Video Info"><img src="images/moreinfo.gif" border="0" Align="Top" alt="More Info"</a>' : $products_combine);}
			   				if($featured_products['products_release_date']!='')
			   				$listing_products_year="<b>(".$featured_products['products_release_date'].")</b>";
			   				else
			   				$listing_products_year="";
			                 $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $featured_products['products_id']) . '"><b>' . $featured_products['series_name'] . '</b><BR>' . $featured_products['products_name_prefix'] . '&nbsp;<b>' . $featured_products['products_name'] . '</b>&nbsp;' . $featured_products['products_name_suffix'] . '</a>'.$listing_products_year.'<BR>'.$has_rights.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>' . stripslashes($listing_products_summary) . '</i>' ;
			               }
			               break;
			             case 'PRODUCT_LIST_MANUFACTURER':
			               $lc_align = '';
			               $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $featured_products['manufacturers_id']) . '">' . $featured_products['manufacturers_name'] . '</a>&nbsp;';
			               break;
			             case 'PRODUCT_LIST_PRICE':
			               $lc_align = 'right';
/*
			               if (tep_not_null($featured_products['specials_new_products_price'])) {
			                 $lc_text = '&nbsp;<s>' .  $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<BR><span class="productSpecialPrice">' . $currencies->display_price($featured_products['specials_new_products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>&nbsp;';
			               } else {
			                 $lc_text = '&nbsp;' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '&nbsp;';
			               }
*/
			               break;
			             case 'PRODUCT_LIST_QUANTITY':
			               $lc_align = 'right';
			               $lc_text = '&nbsp;' . $featured_products['products_quantity'] . '&nbsp;';
			               break;
			             case 'PRODUCT_LIST_WEIGHT':
			               $lc_align = 'right';
			               $lc_text = '&nbsp;' . $featured_products['products_weight'] . '&nbsp;';
			               break;
			             case 'PRODUCT_LIST_URL':
			               $lc_align = 'right';
			               $lc_text = '&nbsp;' . $featured_products['products_clip_url'] . '&nbsp;';
			               break;
			             case 'PRODUCT_LIST_IMAGE':
			               $lc_align = 'center';
			               if (isset($HTTP_GET_VARS['manufacturers_id'])) {
			                 $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['series_name'] . '&nbsp;' . $featured_products['products_name_prefix'] . '&nbsp;' . $featured_products['products_name'] . '&nbsp;' . $featured_products['products_name_suffix'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
			               } else {
			                 $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['series_name'] . '&nbsp;' . $featured_products['products_name_prefix'] . '&nbsp;' . $featured_products['products_name'] . '&nbsp;' . $featured_products['products_name_suffix'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
			               }
			               break;
			             case 'PRODUCT_LIST_BUY_NOW':
			               $lc_align = 'center';
			               $lc_text='';
						if ($featured_products['products_always_on_hand'] == '1') $featured_products['products_quantity'] = '1000';
			               //print "#######$featured_products['products_quantity']";
			if (intval($date_available)<$curr_date){
			   			if ($featured_products['products_quantity'] >= '1') {
			   			$lc_text = tep_image(DIR_WS_IMAGES . 'in_stock.gif', 'In Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
			   			} elseif (($featured_products['products_quantity'] <= '0') OR ($featured_products['products_out_of_print'] == '0')) {
			   			$lc_text = tep_image(DIR_WS_IMAGES . 'out_of_stock_0.gif', 'Out of Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
			   			} elseif (($featured_products['products_quantity'] <= '0') AND ($featured_products['products_out_of_print'] == '1')){
			   			$lc_text = tep_image(DIR_WS_IMAGES . 'out_of_stock_1.gif', 'Out of Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
			   			}
}
else{
			$lc_text = "<span style='font-family:comic sans MS, Verdana, Arial, sans-serif; font-weight:bold; font-size:13px; color:blue;'>PRE-ORDER</span><br/><span style='font-family:comic sans MS, Verdana, Arial, sans-serif; font-weight:bold; font-size:10px; color:#4D4D4D;'>Ships ".$part[1]."-".$part[2]."-".$part[0]."</span>";	
}
//////Changes made here for qty and out_of_flag
						if (($featured_products['products_quantity'] <= '0') && ($featured_products['products_out_of_print'] == '0'))
						{
							$lc_text .= '<br>&nbsp;';
						}
						else
						{
							if ($session_started) {
			            	   $lc_text.= '<br><table>';
                                




                               $lc_text.= '<tr><TD><a onclick="getChecked('.$featured_products[products_id].', this); return false;" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products['products_id'] . '&info_message='.TEXT_ITEM_ADDED) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;</td><td>';
					   $params="id=ids".$featured_products['products_id'];
					   $lc_text .=	"<td valign=top style='padding-top:8px;'>".tep_draw_checkbox_field('cartadd[]', $featured_products['products_id'],'', $params)."</TD></tr></table>";
			            	} else {$lc_text = '&nbsp;';}
			           	}
///////

			               break;
			           }



			           $list_box_contents[$cur_row][] = array('align' => $lc_align,
			                                                  'params' => 'class="productListing-data"',
			                                                  'text'  => $lc_text);

      }
		//new productListingBox($list_box_contents);
/*
      if ($new_price = tep_get_products_special_price($featured_products['products_id'])) {
        $products_price = '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <br><span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';
      } else {
        $products_price = $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id']));
      }
*/

/*=====================Price Conditions=======================*/
$products_price = '';
ob_start();
if ($whole['iswholesale'] == 1){
	if ($featured_products['products_distribution'] == 1){

	if (($whole['disc1'] == 1) && (intval($whole[distribution_percentage])>0)){
		echo '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($featured_products['products_price'], $featured_products['products_id'], 1), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';	
		}	
	elseif (($whole['disc1'] == 2) && (intval($whole[distribution_percentage])>0)){
		if (intval($featured_products['specials_new_products_price'])>0)
		echo '<s>' . $currencies->display_price($featured_products['specials_new_products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($featured_products['specials_new_products_price'], $featured_products['products_id'], 1), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';	
		else
		echo '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($featured_products['products_price'], $featured_products['products_id'], 1), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';	
		}	
	else{
 	   if ($featured_products['specials_new_products_price']) {
      		echo '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($featured_products['specials_new_products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';
    		} else {
      	   echo $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id']));
    		}
	   }

	}
	else{
	if (($whole['disc2'] == 1) && (intval($whole[nondistribution_percentage])>0)){
		echo '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($featured_products['products_price'], $featured_products['products_id'], 2), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';	
		}	
	
	elseif (($whole['disc2'] == 2)  && (intval($whole[nondistribution_percentage])>0)){
		if (intval($featured_products['specials_new_products_price'])>0)
			echo '<s>' . $currencies->display_price($featured_products['specials_new_products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($featured_products['specials_new_products_price'], $featured_products['products_id'], 2), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';	
		else
			echo '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($featured_products['products_price'], $featured_products['products_id'], 2), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';	
		}
	else{
 	   if ($featured_products['specials_new_products_price']) {
      		echo '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($featured_products['specials_new_products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';
    		} else {
      	   echo $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id']));
    		}
	   }
	}
}
	else{
 	   if ($featured_products['specials_new_products_price']) {
      		echo '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($featured_products['specials_new_products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';
    		} else {
      	   echo $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id']));
    		}
}
echo shippingLabel($featured_products[products_distribution]);
$price = ob_get_contents();
ob_end_clean();
$products_price = $price;
/*=====================Price Conditions=======================*/
	$strip_tag_descript = strip_tags($featured_products['products_description'], '<b>');
	$products_combined = ($featured_products['products_byline'] . '&nbsp;&nbsp;' . $strip_tag_descript);
	$products_summary = ((strlen($products_combined) > 100) ? substr($products_combined, 0, 500) . '...' : $products_combined);


       if (($featured_products['products_date_available']!='') and ($date_available<$curr_date)) $preorder = ''; else $preorder = "<span style='font-family:comic sans MS, Verdana, Arial, sans-serif; font-weight:bold; font-size:13px; color:blue;'>PRE-ORDER</span><br/><span style='font-family:comic sans MS, Verdana, Arial, sans-serif; font-weight:bold; font-size:10px; color:#4D4D4D;'>Ships ".$part[1]."-".$part[2]."-".$part[0]."</span>";
?>
          <tr class="<? if ($rows%2==0) echo 'productListing-even'; else echo 'productListing-odd'; ?>">
	    <td align=center valign="middle" class="productListing-data"><?php echo '<img src="../images/media_type/' . $featured_products['products_media_type_id'] . '.gif" border="0"></img><br/>'.$featured_products['products_set_type_name'].'<br/><b class="boxText">'.$featured_products['products_region_code_desc'] ?></b></td>
            <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" valign="middle" class="productListing-data" align=center><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?></td>
<?
				if($featured_products['products_release_date']!='')
				$listing_products_year="<b>(".$featured_products['products_release_date'].")</b>";
				else
				$listing_products_year="";
?>
            <td valign="top" class="productListing-data"><?php echo '<b>' . $featured_products['series_name'] . '</b><br>' . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name_prefix'] . '&nbsp;<b>' . $featured_products['products_name'] . '</b>&nbsp;' . $featured_products['products_name_suffix'] . '</u></a>'.$listing_products_year.'<br>'.$has_rights.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>' . $products_summary . '</i><br>'?></td>
            <td valign="middle" class="productListing-data"><?=$preorder?>
		      <a href="product_info.php?products_id=<?=$featured_products['products_id']?>"><img src="images/moreinfo.gif" border="0" /></a><br>
		<?		
		if ($featured_products['products_clip_url']!="") {?>
		<a href="#" onClick="window.open('<?=$featured_products['products_clip_url']?>','_blank','width=660,height=390');return false"><img src="images/video_clip.gif" border="0" /></a>
		<? }
if (tep_not_null($featured_products['products_buy_download_link']) && $featured_products['adgregate']!=0) echo '<br/><a href="#" onclick="window.open(\'adgregate.php?id=' . ($featured_products[products_id]) . '\',\'_blank\',\'width=320,height=320\');return false" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the download" title="Can\'t wait for the DVD, click here to purchase the download"></a>';
if (tep_not_null($featured_products['products_buy_download_link']) && $featured_products['adgregate']==0) echo '<br/><a href="video.php?products_id='.$featured_products[products_id].'" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the download" title="Can\'t wait for the DVD, click here to purchase the download"></a>';
?>
	    </td>
            <td valign="middle" align="center" class="productListing-data"><?php echo  $products_price?></td>
            <td align="right" valign="top" class="productListing-data">
<?
			if ($featured_products['products_always_on_hand'] == '1') $featured_products['products_quantity'] = '1000';

	   			if ($featured_products['products_quantity'] >= '1') {
	   			$lc_text = ($preorder=='')?tep_image(DIR_WS_IMAGES . 'in_stock.gif', 'In Stock ', $image_width, $image_height, 'hspace="0" vspace="0"'):$preorder;
	   			} elseif (($featured_products['products_quantity'] <= '0') AND ($featured_products['products_out_of_print'] == '0')) {
	   			$lc_text = tep_image(DIR_WS_IMAGES . 'out_of_stock_0.gif', 'Out of Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
	   			} elseif (($featured_products['products_quantity'] <= '0') AND ($featured_products['products_out_of_print'] == '1')){
	   			$lc_text = ($preorder=='')?tep_image(DIR_WS_IMAGES . 'out_of_stock_1.gif', 'Out of Stock ', $image_width, $image_height, 'hspace="0" vspace="0"'):$preorder;
	   			}

		$params="id=ids".$featured_products['products_id'];

if (($whole[iswholesale]==1)) {
	$pos = strpos($lc_text, 'out_of_stock_0.gif');
	if ($pos == '') $lc_text = '';
	$img = "&nbsp;".tep_image(DIR_WS_IMAGES . 'dealer_button.gif', 'Dealer Price!', $image_width, $image_height, 'hspace="0" vspace="0"')."<br/>";
}

?>		
            <br><table border=0 width="100%">

<?php
if($oplist[$featured_products['products_id']]){
 $lc_text.= '<tr><td colspan=2 class="smalltext" align=center><font color="red">' . ALREADY_PURCHASED . '</font></TD></tr>';
}
?>

		<tr><td colspan=2 align=center><?=$lc_text;?></TD></tr>
		<tr><TD align=center><?php  if (($featured_products['products_quantity'] <= '0') and ($featured_products['products_out_of_print'] == '0')) echo ''; else { echo $img.'<a onclick="getChecked('.$featured_products[products_id].', this); return false;" href="' . tep_href_link(FILENAME_FEATURED_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products['products_id']) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_IN_CART) . '</a>'; ?></td><td valign=top style='padding-top:8px;'><?php echo tep_draw_checkbox_field('cartadd[]', $featured_products['products_id'],'', $params); }?></TD></tr>
		</table>
	   </td>
          </tr>
          <tr>
            <td colspan="7"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php

		}

		//  new productListingBox($list_box_contents);
  } else {
?>
          <tr>
            <td class="main" width="100%" colspan=7><?php echo TEXT_NO_NEW_PRODUCTS; ?></td>
          </tr>
          <tr>
            <td colspan=7><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
  }
?>
        </table>
<!--</td>
      </tr>-->
<?php
  if (($featured_products_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {

?>
      <tr>
        <td colspan=2>
		  <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="50%" class="main"><?php //echo '<a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(array('sort', 'page')), 'NONSSL', true, false) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>        
<td width="50%" align="right" class="main style1">
<? echo tep_image_submit('button_in_cart_OLD.gif', IMAGE_BUTTON_IN_CART); ?></td>		
      </tr>
          <tr>
            <td class="smallText"></td>
            <td style="padding-top:15px;" align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $featured_products_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
	   </td>
      </tr>
</FORM>
<?php
  }
?>
<script>
	function getChecked(val){
     	 var theForm = document.getElementById('ids'+val);
	  theForm.checked = true;
	document.cart_multi_s.submit();
}

	function checkUncheckAll(theElement) {
     var theForm = theElement.form, z = 0;
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall'){
	  theForm[z].checked = theElement.checked;
	  }
     }
    }

	function redirection(val){
<?
	if (strpos(substr($REQUEST_URI,1),"?")=="")
		$url=substr($REQUEST_URI,1)."?";
	else
		$url=substr($REQUEST_URI,1)."&";
?>
	window.location.href="<?=$url?>per_page="+val;
	}


	function getRefresh(val){
<?
	if (strpos(substr($REQUEST_URI,1),"?")=="")
		$url=substr($REQUEST_URI,1)."?";
	else
		$url=substr($REQUEST_URI,1)."&";
?>

	window.location.href="<?=$url?>&filter="+val;
	}

</script>
    </table></td>
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