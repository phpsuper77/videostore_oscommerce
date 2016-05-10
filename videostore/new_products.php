<?php
/*
  $Id: new_products.php,v 1.34 2003/06/09 22:49:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- new_products //-->
<?php

////////////////////////////////////////////
//Newly inserted for New Products
////////////////////////////////////////////
$today_bestseller = getdate();
$temp_day = $today_bestseller['mday'];

if($temp_day <= 28)
{
  $this_bestday = $temp_day;
}
else
{
  $this_bestday = '28';
}
$temp_month = $today_bestseller['mon'];
if ($temp_month  > 1)
{
  $this_bestmonth = $today_bestseller['mon'] - 1;
  $this_bestyear = $today_bestseller['year'] ;
}
else
{
  $this_bestmonth = $today_bestseller['mon'] + 11;
  $this_bestyear = $today_bestseller['year'] - 1;
}
//////////////////////////////////////////////

  $info_box_contents = array();
  //$info_box_contents[] = array('text' => sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')).'<a class="headerNavigation" href="' . tep_href_link('./new_products_more.php') . '">' .' more...'. '</a>');
  $info_box_contents[] = array('text' => sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')).'<a class="headerNavigation" href="' . tep_href_link('./new_products_more_new.php') . '">' .' more...'. '</a>');

  new contentBoxHeading($info_box_contents);

  if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
  	//$new_products_query = tep_db_query("select p.products_id, p.series_id, p.products_image, p.products_media_type_id, p.products_tax_class_id, st.products_set_type_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where products_status = '1' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  	  $new_products_query = tep_db_query("select p.products_id, p.series_id, p.products_image, p.products_media_type_id, p.products_tax_class_id, st.products_set_type_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where products_status = '1' and p.products_date_added > ('" . $this_year . "-" . $this_month . "-" . $this_day."') order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  } else {
    //$new_products_query = tep_db_query("select distinct p.products_id, p.products_image, p.series_id, st.products_set_type_name, p.products_media_type_id, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id), " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$new_products_category_id . "' and p.products_status = '1' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
	  $new_products_query = tep_db_query("select distinct p.products_id, p.products_image, p.series_id, st.products_set_type_name, p.products_media_type_id, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id), " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$new_products_category_id . "' and p.products_status = '1' and p.products_date_added > ('" . $this_year . "-" . $this_month . "-" . $this_day."') order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  }



  $row = 0;
  $col = 0;
  $info_box_contents = array();

  while ($new_products = tep_db_fetch_array($new_products_query)) {
##
#Newly Inserted
##
	$productid = $new_products['products_id'];
	$price_query = tep_db_query("select products_price from products where products_id = '$productid'");
	print "select products_price from products where products_id = '$productid'";
	$price_product = tep_db_fetch_array($price_query);

	$new_products['specials_new_products_price'] = tep_get_products_special_price($new_products['products_id']);

	if (tep_not_null($new_products['specials_new_products_price'])) {
  		//$new_price = '<s>' . $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</s><br>';
  		##
  		#Newly Inserted
  		##
      $new_price = '<s>' . $currencies->display_price($price_product['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</s><br>';
      $new_price .= '<span class="productSpecialPrice">' . $currencies->display_price($new_products['specials_new_products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</span>';
    } else {
      $new_price = $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id']));
    }


    $new_products['products_name'] = '<b>' . tep_get_products_series($new_products['series_id']) . '</b><BR>' . tep_get_products_name_prefix($new_products['products_id']) . '&nbsp;<B>' . tep_get_products_name($new_products['products_id']) . '</b>&nbsp;' . tep_get_products_name_suffix($new_products['products_id']);

    /*$info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => tep_image(DIR_WS_MEDIA_TYPES . $new_products['products_media_type_id'] . '.gif') . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $new_products['products_image'], strip_tags($new_products['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>' . tep_image(DIR_WS_MEDIA_TYPES . '4' . '.gif') . '<br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . $new_products['products_name'] . '</a><br>' . $new_products['products_set_type_name'] . '<br>' . $new_price);*/
	 /*changes by kamaraj for VHS/DVD */

	if($new_products['products_media_type_id']=="1")
	    {
	    	$info_box_contents[$row][$col] = array('align' => 'center',
	                                           'params' => 'class="smallText" width="33%" valign="top"',
	                                           'text' => '<a href="javascript:void(0)"
	onClick=javascript:window.open("./includes/languages/english/vhs.html","VHS","width=200,height=200")>'.tep_image(DIR_WS_MEDIA_TYPES .
	$new_products['products_media_type_id'] . '.gif',"VHS - Click Here for More Info on VHS Formats") . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .
	$new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $new_products['products_image'],
	strip_tags($new_products['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>' .'<br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' .
	$new_products['products_name'] . '</a><br>' . $new_products['products_set_type_name'] . '<br>' . $new_price."--");
	 	}
		else if($new_products['products_media_type_id']=="2")
		{
			$info_box_contents[$row][$col] = array('align' => 'center',
			                                           'params' => 'class="smallText" width="33%" valign="top"',
	                                           'text' => '<a href="javascript:void(0)"
	onClick=javascript:window.open("./includes/languages/english/dvd.html","DVD","width=200,height=200")>'.tep_image(DIR_WS_MEDIA_TYPES .
	$new_products['products_media_type_id'] . '.gif',"DVD - Click Here for More Info on DVD Formats") . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .
	$new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $new_products['products_image'],
	strip_tags($new_products['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>' . '<br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' .
	$new_products['products_name'] . '</a><br>' . $new_products['products_set_type_name'] . '<br>' . $new_price);
		}
		else
		{
				$info_box_contents[$row][$col] = array('align' => 'center',
					                                           'params' => 'class="smallText" width="33%" valign="top"',
	                                           'text' => tep_image(DIR_WS_MEDIA_TYPES . $new_products['products_media_type_id'] . '.gif') .
	'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES .
	$new_products['products_image'], strip_tags($new_products['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>' .
	 '<br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .
	$new_products['products_id']) . '">' . $new_products['products_name'] . '</a><br>' . $new_products['products_set_type_name'] . '<br>' .
	$new_price);
		}
	/*changes by kamaraj for VHS/DVD  ends here */



    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }

  new contentBox($info_box_contents);
?>
<!-- new_products_eof //-->
