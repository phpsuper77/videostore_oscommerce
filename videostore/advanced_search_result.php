<?php
/*
  $Id: advanced_search_result.php,v 1.72 2003/06/23 06:50:11 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

////////////////
// Original
////////

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);
  //require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADD_TO_CART_TEXT);
  $error = false;
//****checkbox submit process****
/*$product = $HTTP_POST_VARS["cartadd"];
$result = count($product);*/

//echo $result." - No. of element selected<br>";
/*	if($product!=''){
		foreach ($product as $w)
		{
			$i++;
			echo $w."--".$i."<br>";
		}
	}*/
//****checkbox submit process end****



  if ( (isset($HTTP_GET_VARS['keywords']) && empty($HTTP_GET_VARS['keywords'])) ) {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  } else {
    $keywords = '';

    if (isset($HTTP_GET_VARS['keywords'])) {
      $keywords = $HTTP_GET_VARS['keywords'];
    }

    if (tep_not_null($keywords)) {
      if (!tep_parse_search_string($keywords, $search_keywords)) {
        $error = true;

        $messageStack->add_session('search', ERROR_INVALID_KEYWORDS);
      }
    }
  }

  if (empty($keywords)) {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  }

  if ($error == true) {
    tep_redirect(tep_href_link(FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(), 'NONSSL', true, false));
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ADVANCED_SEARCH));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, tep_get_all_get_params(), 'NONSSL', true, false));
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>
<style type="text/css">
<!--
.style1 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>


</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->

<?php 
require(DIR_WS_INCLUDES . 'header.php'); ?>
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
    <td width="100%" valign="top">
	<!--Form Starting-->
 <!--<form name="cart_multi" action="advanced_search.php?&action=add_multi_product&info_message="<? //.MESSAGE1; ?>">-->
 <?
//	print_r(tep_get_all_get_params(array('action')))."<br>";
//	print_r(tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, tep_get_all_get_params(array('action')) . 'action=buy_now&info_message='.MESSAGE1));
echo tep_draw_form('cart_multi', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, tep_get_all_get_params(array('action')). 'action=add_multi_product&info_message='.MESSAGE1));
	//.'&info_message='.MESSAGE1
//echo tep_draw_form('cart_quantity', tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id'], $method = 'post');

	?>
	
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          
		  <tr>
            <td class="pageHeading">Search Results</td>
            <td class="pageHeading" align="right"></td>
          </tr>
		 
<tr>
<td bgcolor="yellow" width="100%"><center><font size="2">Use Heading Arrows for Ascending or Descending Sort - by Media, Title or Price</font></center></td>
  </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2">
		<!--<table border="1">
		<tr><td>-->
<?php
// create column list
  $define_list = array('PRODUCT_LIST_MEDIA' => PRODUCT_LIST_MEDIA,
  		       'PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                       'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                       'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                       'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                       'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                       'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                       'PRODUCT_LIST_CLIP' => PRODUCT_LIST_CLIP,
                       'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                       'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

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
        $select_column_list .= 'p.products_media_type_id, ';
        break;

      case 'PRODUCT_LIST_MODEL':
        $select_column_list .= 'p.products_model, ';
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
      case 'PRODUCT_LIST_CLIP':
        $select_column_list .= 'pd.products_clip_url, ';
        break;

    }
  }

  $select_str = "select distinct " . $select_column_list . " p.products_release_date, p.products_always_on_hand, p.products_byline, p.products_distribution, p.has_rights, p.products_date_available, m.manufacturers_id, pr.products_region_code_desc,p.products_id,p.products_quantity , p.products_out_of_print ,  p.products_release_date,  p.products_model, p.products_media_type_id, pd.products_buy_download_link, pd.products_name, pd.products_name_prefix, pd.products_name_suffix, pd.products_clip_url, st.products_set_type_name, se.series_name, pd.products_description, pd.products_head_keywords_tag, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price ";

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
    $select_str .= ", SUM(tr.tax_rate) as tax_rate ";
  }

  $from_str = "from ((((" . TABLE_PRODUCTS . " p) left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd) left join ".TABLE_REGION_CODE." pr on (p. products_region_code_id=pr. products_region_code_id) left join " . TABLE_SPECIALS . " s on (p.products_id = s.products_id)) left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id)) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id), " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c";

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
    if (!tep_session_is_registered('customer_country_id')) {
      $customer_country_id = STORE_COUNTRY;
      $customer_zone_id = STORE_ZONE;
    }
    $from_str .= " left join " . TABLE_TAX_RATES . " tr on p.products_tax_class_id = tr.tax_class_id left join " . TABLE_ZONES_TO_GEO_ZONES . " gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = '0' or gz.zone_country_id = '" . (int)$customer_country_id . "') and (gz.zone_id is null or gz.zone_id = '0' or gz.zone_id = '" . (int)$customer_zone_id . "')";
  }

  // #################### Added Enable / Disable Categorie ###################
  // $where_str = " where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ";
     $where_str = " where p.products_status = '1' and c.categories_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ";
  // ################### End Added Enable / Disable Categorie ###################

  if (isset($HTTP_GET_VARS['categories_id']) && tep_not_null($HTTP_GET_VARS['categories_id'])) {
    if (isset($HTTP_GET_VARS['inc_subcat']) && ($HTTP_GET_VARS['inc_subcat'] == '1')) {
      $subcategories_array = array();
      tep_get_subcategories($subcategories_array, $HTTP_GET_VARS['categories_id']);

      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and (p2c.categories_id = '" . (int)$HTTP_GET_VARS['categories_id'] . "'";

      for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
        $where_str .= " or p2c.categories_id = '" . (int)$subcategories_array[$i] . "'";
      }

      $where_str .= ")";
    } else {
      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$HTTP_GET_VARS['categories_id'] . "'";
    }
  }

  if (isset($HTTP_GET_VARS['manufacturers_id']) && tep_not_null($HTTP_GET_VARS['manufacturers_id'])) {
    $where_str .= " and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'";
  }

  if (isset($search_keywords) && (sizeof($search_keywords) > 0)) {
    $where_str .= " and (";
    for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {
      switch ($search_keywords[$i]) {
        case '(':
        case ')':
        case 'and':
        case 'or':
          $where_str .= " " . $search_keywords[$i] . " ";
          break;
        default:
          $keyword = tep_db_prepare_input($search_keywords[$i]);
          $where_str .= "(pd.products_head_keywords_tag like '%" . tep_db_input($keyword) . "%' or p.products_model like '%" . tep_db_input($keyword) . "%'";
          //if (isset($HTTP_GET_VARS['search_in_description']) && ($HTTP_GET_VARS['search_in_description'] == '1'))
		  $where_str .= " or pd.products_description like '%" . tep_db_input($keyword) . "%'";
		  $where_str .= " or p.products_upc like '%" . tep_db_input($keyword) . "%'";
		  $where_str .= " or p.products_isbn like '%" . tep_db_input($keyword) . "%'";
          $where_str .= ')';
          break;
      }
    }
    $where_str .= " )";
  }

  if (DISPLAY_PRICE_WITH_TAX == 'true') {
    if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= " . (double)$pfrom . ")";
    if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= " . (double)$pto . ")";
  } else {
    if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) >= " . (double)$pfrom . ")";
    if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) <= " . (double)$pto . ")";
  }

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
    $where_str .= " group by p.products_id, tr.tax_priority";
  }

  if ( (!isset($HTTP_GET_VARS['sort'])) || (!preg_match('/[1-8][ad]/i', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {
    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
      if ($column_list[$i] == 'PRODUCT_LIST_IMAGE') {

        #$HTTP_GET_VARS['sort'] = $i+1 . 'a';
		$order_str = ' order by p.products_always_on_hand desc, p.products_quantity desc, pd.products_name';
        #$order_str = ' order by p.products_always_on_hand desc, pd.products_name';
        break;
      }
    }
  } else {
    $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
    $sort_order = substr($HTTP_GET_VARS['sort'], 1);

    $order_str = ' order by ';

    switch ($column_list[$sort_col-1]) {
      case 'PRODUCT_LIST_MEDIA':
        //$order_str .= "p.products_media_type_id " . ($sort_order == 'd' ? "desc" : "") . ", p.products_quantity desc, pd.products_name " . ($sort_order == 'd' ? "desc" : "");//CHANGED HERE
        $order_str .= " p.products_always_on_hand desc, p.products_media_type_id " . ($sort_order == 'd' ? "desc" : "") . ", p.products_quantity desc, pd.products_name ";//CHANGED HERE
        break;
      case 'PRODUCT_LIST_MODEL':
        $order_str .= " p.products_always_on_hand desc, p.products_model " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_NAME':
        $order_str .= " p.products_always_on_hand desc, pd.products_name " . ($sort_order == 'd' ? "desc" : "");
        //$order_str .= " p.products_always_on_hand desc, p.products_quantity desc, pd.products_name " . ($sort_order == 'd' ? "desc" : "");//CHANGED HERE
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $order_str .= " p.products_always_on_hand desc, m.manufacturers_name " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $order_str .= " p.products_always_on_hand desc, p.products_quantity " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_IMAGE':
        //$order_str .= "pd.products_name";//CHANGED HERE
        //$order_str .= " p.products_always_on_hand desc, p.products_quantity desc, pd.products_name";
        $order_str .= " p.products_release_date " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $order_str .= " p.products_always_on_hand desc, p.products_weight " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_CLIPS':
        $order_str .= " p.products_always_on_hand desc, pd.products_clip_url " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;

      case 'PRODUCT_LIST_PRICE':
        //$order_str .= "final_price " . ($sort_order == 'd' ? "desc" : "") . ", p.products_quantity desc, pd.products_name " . ($sort_order == 'd' ? "desc" : "");//CHANGED HERE
        $order_str .= " p.products_always_on_hand desc, final_price " . ($sort_order == 'd' ? "desc" : "") . ", p.products_quantity desc, pd.products_name ";//CHANGED HERE
        break;
    }
  }

  $listing_sql = $select_str . $from_str . $where_str . $order_str;
   //print_r($listing_sql);

  require(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);
  ?><!--</td></tr></table>-->        </td>
      </tr>
    </table>
	</form>
	<!--Form Ending-->
	</td>
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