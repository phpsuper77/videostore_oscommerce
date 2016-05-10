<?php
/*

  $Id: product_listing.php,v 1.44 2003/06/09 22:49:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
 <tr>
<td bgcolor="yellow" width="100%"><center><font size=1>Click on Heading Arrows for Ascending or Descending Sort - by Media, Title or Price<BR>For more product Detail and larger Images - Click on Image or Video Title</center></font></td>
  </tr>
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td></tr></table>
<?php
  }

  $list_box_contents = array();

  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
	   case 'PRODUCT_LIST_MEDIA':
        $lc_text = 'VHS DVD<BR>';
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_MODEL':
        $lc_text = '';
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_NAME':
        $lc_text = 'Product Name (Brief Description)<BR>';
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
        $lc_text = 'Clip';
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_IMAGE':
        $lc_text = '';
        $lc_align = 'center';
        break;
      case 'PRODUCT_LIST_BUY_NOW':
        $lc_text = 'Add to Cart';
        $lc_align = 'center';
        break;
    }

    if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
      $lc_text = tep_create_sort_heading($HTTP_GET_VARS['sort'], $col+1, $lc_text);
    }

    $list_box_contents[0][] = array('align' => $lc_align,
                                    'params' => 'class="productListing-heading"',
                                    'text' => '&nbsp;' . $lc_text . '&nbsp;');
  }

  if ($listing_split->number_of_rows > 0) {
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;

      if (($rows/2) == floor($rows/2)) {
        $list_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $list_box_contents[] = array('params' => 'class="productListing-odd"');
      }

      $cur_row = sizeof($list_box_contents) - 1;

      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        $lc_align = '';

        switch ($column_list[$col]) {
		  case 'PRODUCT_LIST_MEDIA':
            $lc_align = 'right';
            $lc_text = '<img src=../images/media_type/' . $listing['products_media_type_id'] . '.gif border=0></img><BR>' . $listing['products_set_type_name'];
            break;
          case 'PRODUCT_LIST_MODEL':
            $lc_align = '';
            $lc_text = '&nbsp;' . $listing['products_model'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_NAME':
            $lc_align = '';
            if (isset($HTTP_GET_VARS['manufacturers_id'])) {
				$strip_tag_desc = strip_tags($listing['products_description'], '<b>');
				$products_combine = ($listing['products_byline'] . '&nbsp;&nbsp;' . $strip_tag_desc);
				if($listing['products_release_date']!='')
				$listing_products_year="<b>(".$listing['products_release_date'].")</b>";
				else
				$listing_products_year="";
				//$listing_products_summary = ((strlen($products_combine) > 200) ? substr($products_combine, 0, 200) . '...' : $products_combine);
				$listing_products_summary = ((strlen($products_combine) > 200) ? substr($products_combine, 0, 200) .'</b></i><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '">'. '<b>...(more info)</b></a>' : $products_combine);

			$lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '"><b>' . $listing['series_name'] . '</b><BR>' . $listing['products_name_prefix'] . '&nbsp;<b>' . $listing['products_name'] . '</b>&nbsp;' . $listing['products_name_suffix'] . '</a>'.$listing_products_year.'<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $listing_products_summary ;
            } else {
				$strip_tag_desc = strip_tags($listing['products_description'], '<b>');
				$products_combine = ($listing['products_byline'] . '&nbsp;&nbsp;' . $strip_tag_desc);
				//$listing_products_summary = ((strlen($products_combine) > 200) ? substr($products_combine, 0, 200) . '...' : $products_combine);
				$listing_products_summary = ((strlen($products_combine) > 200) ? substr($products_combine, 0, 200) .'</b></i><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '">'. '<b>...(more info2)</b></a>' : $products_combine);
				if($listing['products_release_date']!='')
				$listing_products_year="<b>(".$listing['products_release_date'].")</b>";
				else
				$listing_products_year="";
              $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '"><b>' . $listing['series_name'] . '</b><BR>' . $listing['products_name_prefix'] . '&nbsp;<b>' . $listing['products_name'] . '</b>&nbsp;' . $listing['products_name_suffix'] . '</a>'.$listing_products_year.'<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>' . stripslashes($listing_products_summary) . '</i>' ;
            }
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_align = '';
            $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a>&nbsp;';
            break;
          case 'PRODUCT_LIST_PRICE':
            $lc_align = 'right';
            if (tep_not_null($listing['specials_new_products_price'])) {
              $lc_text = '&nbsp;<s>' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<BR><span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;';
            } else {
              $lc_text = '&nbsp;' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '&nbsp;';
            }
            break;
          case 'PRODUCT_LIST_QUANTITY':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing['products_quantity'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_WEIGHT':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing['products_weight'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_URL':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing['products_clip_url'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_IMAGE':
            $lc_align = 'center';
            if (isset($HTTP_GET_VARS['manufacturers_id'])) {
              $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['series_name'] . '&nbsp;' . $listing['products_name_prefix'] . '&nbsp;' . $listing['products_name'] . '&nbsp;' . $listing['products_name_suffix'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
            } else {
              $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['series_name'] . '&nbsp;' . $listing['products_name_prefix'] . '&nbsp;' . $listing['products_name'] . '&nbsp;' . $listing['products_name_suffix'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
            }
            break;
          case 'PRODUCT_LIST_BUY_NOW':
            $lc_align = 'center';
			if ($listing['products_quantity'] >= '1') {
			$lc_text = tep_image(DIR_WS_IMAGES . 'in_stock.gif', 'In Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
			} elseif (($listing['products_quantity'] <= '0') AND ($listing['products_out_of_print'] == '0')) {
			$lc_text = tep_image(DIR_WS_IMAGES . 'out_of_stock_0.gif', 'Out of Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
			} elseif (($listing['products_quantity'] <= '0') AND ($listing['products_out_of_print'] == '1')){
			$lc_text = tep_image(DIR_WS_IMAGES . 'out_of_stock_1.gif', 'Out of Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
			}

if (tep_not_null($listing['products_clip_url'])) {
                                                $lc_text = '&nbsp;<a href="' . $listing['products_clip_url'] . '" target=blank title="View Free Video Clips"><img src="images/free_video_clip.gif" border="0" alt="View Free Video Clip"</a>'; }

            $lc_text .= '<br><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id']) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;';
            break;
        }

        $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                               'params' => 'class="productListing-data"',
                                               'text'  => $lc_text);
      }
    }

    new productListingBox($list_box_contents);
  } else {
    $list_box_contents = array();

    $list_box_contents[0] = array('params' => 'class="productListing-odd"');
    $list_box_contents[0][] = array('params' => 'class="productListing-data"',
                                   'text' => TEXT_NO_PRODUCTS);

    new productListingBox($list_box_contents);
  }

  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
?>
