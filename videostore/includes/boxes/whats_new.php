<?php
/*
  $Id: whats_new.php,v 1.31 2003/02/10 22:31:09 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

//  if ($random_product = tep_random_select("select products_id, products_media_type_id, products_image, products_tax_class_id, products_price from " . TABLE_PRODUCTS . " where products_status = '1' order by products_date_added desc limit " . MAX_RANDOM_SELECT_NEW)) {

    if ($random_product = tep_random_select("select p.products_id, p.products_media_type_id, p.products_image, p.products_tax_class_id, p.products_price, p.series_id, se.series_name, st.products_set_type_name from " . TABLE_PRODUCTS . " p, " . TABLE_SERIES . " se left   join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) where p.products_status = '1' and p.series_id = se.series_id order by p.products_date_added desc limit " . MAX_RANDOM_SELECT_NEW)) {
?>


<!-- whats_new //-->
          <tr>
            <td>
<?php
    $random_product['products_name'] = '<b>' . $random_product['series_name'] . '</b><BR>' . tep_get_products_name_prefix($random_product['products_id']) . '&nbsp;<b>' . tep_get_products_name($random_product['products_id']) . '</b>&nbsp;' . tep_get_products_name_suffix($random_product['products_id']) . '<BR>' . $random_product['products_set_type_name'];
    $random_product['specials_new_products_price'] = tep_get_products_special_price($random_product['products_id']);

    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_WHATS_NEW);

    new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_PRODUCTS_NEW));

    if (tep_not_null($random_product['specials_new_products_price'])) {
      $whats_new_price = '<s>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</s><br>';
      $whats_new_price .= '<span class="productSpecialPrice">' . $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
    } else {
      $whats_new_price = $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id']));
    }

    $info_box_contents = array();
    /*$info_box_contents[] = array('align' => 'center',
                                 'text' => tep_image(DIR_WS_MEDIA_TYPES . $random_product['products_media_type_id'] . '.gif') . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . tep_image(DIR_WS_MEDIA_TYPES . '4' . '.gif') .  '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . $random_product['products_name'] . '</a><br>' . $whats_new_price);*/


	if($random_product['products_media_type_id']=='1')
					{
					  $info_box_contents[] = array('align' => 'center',
														   'params' => 'class="smallText" width="33%"
			valign="top"','text' =>'<a href="javascript:void(0)" onClick=javascript:window.open("./includes/languages/english/vhs.html","VHS","width=400,height=400,scrollbars=yes")>'. tep_image(DIR_WS_MEDIA_TYPES .
			$random_product['products_media_type_id'] . '.gif',"VHS - Click Here for More Info on VHS Formats") . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .
			$random_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'],
			strip_tags($random_product['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) .
			'</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' .
			$random_product['products_name'] . '</a><br></s>' . $whats_new_price);
				}

		else if($random_product['products_media_type_id']=='2')
					{
						$info_box_contents[] = array('align' => 'center',
														   'params' => 'class="smallText" width="33%"
			valign="top"',
														   'text' =>'<a href="javascript:void(0)"
			onClick=javascript:window.open("./includes/languages/english/dvd.html","DVD","width=400,height=400,scrollbars=yes")>'. tep_image(DIR_WS_MEDIA_TYPES .
			$random_product['products_media_type_id'] . '.gif',"DVD - Click Here for More Info on DVD Formats") . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .
			$random_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'],
			strip_tags($random_product['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' .
			$random_product['products_name'] . '</a><br></s>' . $whats_new_price);
					}

					else if($random_product['products_media_type_id']=='4')
					{
						$info_box_contents[] = array('align' => 'center',
														   'params' => 'class="smallText" width="33%"
			valign="top"',
														   'text' =>'<a href="javascript:void(0)"
			onClick=javascript:window.open("./includes/languages/english/cd.html","CD","width=400,height=400,scrollbars=yes")>'. tep_image(DIR_WS_MEDIA_TYPES .
			$random_product['products_media_type_id'] . '.gif',"CD - Click Here for More Info on CD Formats") . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .
			$random_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'],
			strip_tags($random_product['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' .
			$random_product['products_name'] . '</a><br></s>' . $whats_new_price);
				}
				else
				{
					$info_box_contents[] = array('align' => 'center','params' => 'class="smallText" width="33%" valign="top"',                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . $random_product['products_name'] . '</a><br>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])));
				}



    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- whats_new_eof //-->
<?php
  }
?>