<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Featured Products V1.1
  Displays a list of featured products, selected from admin
  For use as an Infobox instead of the "New Products" Infobox
*/
?>
<!-- featured_products //-->
<?php
 if(FEATURED_PRODUCTS_DISPLAY == 'true')
 {
  $featured_products_category_id = $new_products_category_id;
  $cat_name_query = tep_db_query("select categories_name from categories_description where categories_id = '" . $featured_products_category_id . "' limit 1");
  $cat_name_fetch = tep_db_fetch_array($cat_name_query);
  $cat_name = $cat_name_fetch['categories_name'];
  $info_box_contents = array();

  if ( (!isset($featured_products_category_id)) || ($featured_products_category_id == '0') ) {
    $info_box_contents[] = array('align' => 'left', 'text' => 'Featured Products<a class="headerNavigation1" href="' . tep_href_link(FILENAME_FEATURED_PRODUCTS) . '">' .' &nbsp;&nbsp;more...'. '</a>');

  list($usec, $sec) = explode(' ', microtime());
  srand( (float) $sec + ((float) $usec * 100000) );
  $mtm= rand();

    $featured_products_query = tep_db_query("select p.products_id,p.products_distribution, p.products_image, p.products_tax_class_id, s.status as specstat, s.specials_new_products_price, p.products_price, p.series_id, p.products_set_type_id, p.products_media_type_id from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_status = '1' and f.status = '1' order by rand($mtm) DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
  } else {
    $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TABLE_HEADING_FEATURED_PRODUCTS_CATEGORY, $cat_name));
    $featured_products_query = tep_db_query("select distinct p.products_id,p.products_distribution, p.series_id, p.products_set_type_id, p.products_image, p.products_tax_class_id, s.status as specstat, s.specials_new_products_price, p.products_price, p.products_media_type_id from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . $featured_products_category_id . "' and p.products_status = '1' and f.status = '1' order by rand() DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
  }

  $row = 0;
  $col = 0;
  $num = 0;
  while ($featured_products = tep_db_fetch_array($featured_products_query)) {
    $num ++; if ($num == 1) { new contentBoxHeading($info_box_contents); }

/*=====================Price Conditions=======================*/
$price = '';
if($oplist[$featured_products['products_id']]){
$aaa = '<br><font color="red">' . ALREADY_PURCHASED . '</font>';
}else{
$aaa = '';
}

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
	
	elseif (($whole['disc2'] == 2) && (intval($whole[nondistribution_percentage])>0)){
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
$price = ob_get_contents();
ob_end_clean();
/*=====================Price Conditions=======================*/


    $featured_products['products_name'] = '<b>' . tep_get_products_series($featured_products['series_id']) . '</b><br>' . tep_get_products_name_prefix($featured_products['products_id']) . '&nbsp;<b>' .  tep_get_products_name($featured_products['products_id']) . '</b>&nbsp;' . tep_get_products_name_suffix($featured_products['products_id']) . '<BR>' . tep_get_products_set_type($featured_products['products_set_type_id']);
    if($featured_products['specstat']) {
      /*$info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => tep_image(DIR_WS_MEDIA_TYPES . $featured_products['products_media_type_id'] . '.gif') . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], strip_tags($featured_products['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . tep_image(DIR_WS_MEDIA_TYPES . '4' . '.gif') . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name'] . '</a><br><s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s><br><span class="productSpecialPrice">' .
                                           $currencies->display_price($featured_products['specials_new_products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>');*/

	/*changes by kamaraj for VHS/DVD */
			if($featured_products['products_media_type_id']=='1')
			{
			  $info_box_contents[$row][$col] = array('align' => 'center',
												   'params' => 'class="smallText" width="33%"
	valign="top"',
												   'text' =>'<a href="javascript:void(0)"
	onClick=javascript:window.open("./includes/languages/english/vhs.html","VHS","width=400,height=400,scrollbars=yes")>'. tep_image(DIR_WS_MEDIA_TYPES .
	$featured_products['products_media_type_id'] . '.gif',"VHS - Click Here for More Info on VHS Formats") . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .
	$featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'],
	strip_tags($featured_products['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) .
	'</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' .
	$featured_products['products_name'] . '</a>' . $aaa . '<br>'.$price);
			}
			else if($featured_products['products_media_type_id']=='2')
			{
				$info_box_contents[$row][$col] = array('align' => 'center',
												   'params' => 'class="smallText" width="33%"
	valign="top"',
												   'text' =>'<a href="javascript:void(0)"
	onClick=javascript:window.open("./includes/languages/english/dvd.html","DVD","width=400,height=400,scrollbars=yes")>'. tep_image(DIR_WS_MEDIA_TYPES .
	$featured_products['products_media_type_id'] . '.gif',"DVD - Click Here for More Info on DVD Formats") . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .
	$featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'],
	strip_tags($featured_products['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' .
	$featured_products['products_name'] . '</a>' . $aaa . '<br>'.$price);
			}

			else if($featured_products['products_media_type_id']=='4')
			{
				$info_box_contents[$row][$col] = array('align' => 'center',
												   'params' => 'class="smallText" width="33%"
	valign="top"',
												   'text' =>'<a href="javascript:void(0)"
	onClick=javascript:window.open("./includes/languages/english/cd.html","CD","width=400,height=400,scrollbars=yes")>'. tep_image(DIR_WS_MEDIA_TYPES .
	$featured_products['products_media_type_id'] . '.gif',"CD - Click Here for More Info on CD Formats") . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .
	$featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'],
	strip_tags($featured_products['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' .
	$featured_products['products_name'] . '</a>' . $aaa . '<br>' . $price);
			}

		/*changes by kamaraj for VHS/DVD  ends*/

    } else {
      $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name'] . $aaa . '</a><br>' . $price);
    }
    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }
  if($num) {

      new contentBox($info_box_contents);
  }
 } else // If it's disabled, then include the original New Products box
 {
   include (DIR_WS_MODULES . FILENAME_NEW_PRODUCTS);
 }
?>
<!-- featured_products_eof //-->
