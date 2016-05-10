<?php
/*
  $Id: specials.php,v 1.31 2003/06/09 22:21:03 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if ($random_product = tep_random_select("select p.products_id, p.products_media_type_id, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price, se.series_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s, " . TABLE_SERIES . " se where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and p.series_id = se.series_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added desc limit " . MAX_RANDOM_SELECT_SPECIALS)) {
?>
<!-- specials //-->
          <tr>
            <td>
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_SPECIALS);

    new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_SPECIALS));

    $info_box_contents = array();

	if($random_product['products_media_type_id']=='1')
				{
				  $info_box_contents[] = array('align' => 'center',
													   'params' => 'class="smallText" width="33%"
		valign="top"','text' =>'<a href="javascript:void(0)" onClick=javascript:window.open("./includes/languages/english/vhs.html","VHS","width=400,height=400,scrollbars=yes")>'. tep_image(DIR_WS_MEDIA_TYPES .
		$random_product['products_media_type_id'] . '.gif',"VHS - Click Here for More Info on VHS Formats") . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .
		$random_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'],
		strip_tags($random_product['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) .
		'</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' .
		$random_product['products_name'] . '</a><br><s>' . $currencies->display_price($random_product['products_price'],
		tep_get_tax_rate($random_product['products_tax_class_id'])) . '</s><br><span class="productSpecialPrice">' .

		$currencies->display_price($random_product['specials_new_products_price'],
		tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>');
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
		$random_product['products_name'] . '</a><br><s>' . $currencies->display_price($random_product['products_price'],
		tep_get_tax_rate($random_product['products_tax_class_id'])) . '</s><br><span class="productSpecialPrice">' .

		$currencies->display_price($random_product['specials_new_products_price'],
		tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>');
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
		$random_product['products_name'] . '</a><br><s>' . $currencies->display_price($random_product['products_price'],
		tep_get_tax_rate($random_product['products_tax_class_id'])) . '</s><br><span class="productSpecialPrice">' .

		$currencies->display_price($random_product['specials_new_products_price'],
		tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>');
			}
			else
			{
				$info_box_contents[] = array('align' => 'center','params' => 'class="smallText" width="33%" valign="top"',                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . $random_product['products_name'] . '</a><br>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])));
			}


    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- specials_eof //-->
<?php
  }
?>
