<?php
/*
  $Id: product_info.php,v 1.98 2003/09/02 18:52:33 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<?php
// BOF: WebMakers.com Changed: Header Tag Controller v1.0
// Replaced by header_tags.php
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
  <title><?php echo TITLE ?></title>
<?php
}
// EOF: WebMakers.com Changed: Header Tag Controller v1.0
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
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
    <td width="100%" valign="top"><?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  if ($product_check['total'] < 1) {
?>
      <tr>
        <td><?php new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_name_prefix, pd.products_name_suffix, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_audio_id, p.products_date_available, p.manufacturers_id, p.products_media_type_id, di.distributors_name, pr.producers_name, se.series_name, vf.products_video_format_name, au.products_audio_name, asp.products_aspect_ratio_name, p.products_closed_captioned, p.products_pc_bonus, rc.products_region_code_name, st.products_set_type_name, pt.products_packaging_type_name, p.products_byline, p.products_run_time, p.products_audio_languages, p.products_subtitle_languages, p.products_release_date, p.products_isbn, p.products_upc, p.products_out_of_print from " . TABLE_PRODUCTS . " p left join " . TABLE_DISTRIBUTORS . " di on (p.distributors_id = di.distributors_id) left join " . TABLE_PRODUCERS . " pr on (p.producers_id = pr.producers_id) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) left join " . TABLE_VIDEO_FORMAT . " vf on (p.products_video_format_id = vf.products_video_format_id) left join " . TABLE_AUDIOS . " au on (p.products_audio_id = au.products_audio_id) left join " . TABLE_ASPECT . " asp on (p.products_aspect_ratio_id = asp.products_aspect_ratio_id) left join " . TABLE_REGION_CODE . " rc on (p.products_region_code_id = rc.products_region_code_id) left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) left join " . TABLE_PACKAGING_TYPE . " pt on (p.products_packaging_type_id = pt.products_packaging_type_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
// EOF MaxiDVD: Modified For Ultimate Images Pack!
    $product_info = tep_db_fetch_array($product_info_query);


    //tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
    tep_db_query("update " . TABLE_PRODUCTS_DATA . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");//Newly inserted for products_data

    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    } else {
      $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
    }

    if (tep_not_null($product_info['products_model'])) {
      $products_name = $product_info['products_name_prefix'] . '&nbsp;<b>' . $product_info['products_name'] . '</b>&nbsp;' .  $product_info['products_name_suffix'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
    } else {
      $products_name = $product_info['products_name_prefix'] . '&nbsp;<b>' . $product_info['products_name'] . '</b>&nbsp;' .  $product_info['products_name_suffix'];
    }
?>
      <tr>
        <td>
		<table border="1" bordercolor="EBA000" width="100%" cellspacing="0" cellpadding="2"><tr><td>
		<table border="0" width="100%" cellspacing="0" cellpadding="2" bordercolor="EBA000" bgcolor="FFF9EC">
          <tr>
		    <td align="center" valign="middle" width="50"><?php  echo '<img src=../images/media_type/' . $product_info['products_media_type_id'] . '.gif border=0></img><BR><span class="smallText">' . $product_info['products_set_type_name'] . '</span>' ?></td>
            <td valign="top"><font face="Arial, Helvetica, sans-serif"><?php  echo '<b>' . $product_info['series_name'] . '</b><BR>' . $products_name;?></font></td>
            <td class="pageHeading" align="right" valign="top">
			<?php echo $products_price . '<br>';

			if ($product_info['products_quantity'] >= '1') {
			echo tep_image(DIR_WS_IMAGES . 'in_stock.gif', 'In Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
			} elseif (($product_info['products_quantity'] <= '0') AND ($product_info['products_out_of_print'] == '0')) {
			echo tep_image(DIR_WS_IMAGES . 'out_of_stock_0.gif', 'Out of Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
			} elseif (($product_info['products_quantity'] <= '0') AND ($product_info['products_out_of_print'] == '1')){
			echo tep_image(DIR_WS_IMAGES . 'out_of_stock_1.gif', 'Out of Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
			}
			?>
			</td>
			<td align="center" width="10"><center>
			<?php
			if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
			echo tep_image(DIR_WS_IMAGES . 'sale_button.gif', 'This Item is On Sale!', $image_width, $image_height, 'hspace="0" vspace="0"');
			}
			 if (($product_info['products_quantity'] <= '0') AND ($product_info['products_out_of_print'] == '0')) {
			 ;
			 } else {
			echo '<br>' . tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('button_buy_now.gif', IMAGE_BUTTON_IN_CART);
			}
			?></center></td>
          </tr>
        </td></tr></table>
		</table>
		</td>
      </tr>
      <tr>
        <td height="10"></td>
      </tr>
      <tr>
        <td class="main">


<?php
    if (tep_not_null($product_info['products_image'])) {
?>
          <table border="0" cellspacing="0" cellpadding="2" align="right" bgcolor="FFF9EC">
            <tr>
              <td align="center" class="smallText">

<!-- // BOF MaxiDVD: Modified For Ultimate Images Pack! //-->
<?php
	echo  '<b>' . $product_info['products_run_time'] . '</b><BR>';

 if ($product_info['products_image_med']!='') {
          $new_image = $product_info['products_image_med'];
          $image_width = MEDIUM_IMAGE_WIDTH;
          $image_height = MEDIUM_IMAGE_HEIGHT;
         } else {
          $new_image = $product_info['products_image'];
          $image_width = SMALL_IMAGE_WIDTH;
          $image_height = SMALL_IMAGE_HEIGHT;}?>
<script language="javascript"><!--
      document.write('<?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id'] . '&amp;amp;amp;amp;amp;amp;image=0') . '\')">' . tep_image(DIR_WS_IMAGES . $new_image, addslashes($product_info['products_name_prefix']) . '&amp;amp;amp;amp;amp;amp;nbsp;' . addslashes($product_info['products_name']) . '&amp;amp;amp;amp;amp;amp;nbsp;' . addslashes($product_info['products_name_suffix']), $image_width, $image_height, 'hspace="5" vspace="5"') . '<br>' . tep_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>');
//--></script>
<noscript>
      <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_med']) . '">' . tep_image(DIR_WS_IMAGES . $new_image . '&image=0',  addslashes($product_info['products_name_prefix']) . '&nbsp;' . addslashes($product_info['products_name']) . '&nbsp;' . addslashes($product_info['products_name_suffix']), $image_width, $image_height, 'hspace="5" vspace="5"') . '<br>' . tep_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
</noscript>
<!-- // EOF MaxiDVD: Modified For Ultimate Images Pack! //-->

              </td>
            </tr>

		<tr>
			<td align="right">
			<?php
			if ($product_info['products_closed_captioned'] == '0') {
			echo tep_image(DIR_WS_IMAGES . 'cc.gif', 'Closed Captioned', '', '', 'hspace="0" vspace="0"') . '<br>';
			}
			if ($product_info['products_audio_id'] == '1') {
			echo tep_image(DIR_WS_IMAGES . 'dolby.gif', 'Dolby Analog', '', '', 'hspace="0" vspace="0"') . '<br>';
			}
			if ($product_info['products_audio_id'] == '4') {
			echo tep_image(DIR_WS_IMAGES . 'dolby_51_surround.gif', 'Closed Captioned', '', '', 'hspace="0" vspace="0"') . '<br>';
			}
			if ($product_info['products_audio_id'] == '3') {
			echo tep_image(DIR_WS_IMAGES . 'dolby_digital.gif', 'Closed Captioned', '', '', 'hspace="0" vspace="0"') . '<br>';
			}
			if ($product_info['products_audio_id'] == '2') {
			echo tep_image(DIR_WS_IMAGES . 'dolby_surround.gif', 'Closed Captioned', '', '', 'hspace="0" vspace="0"') . '<br>';
			}
			if ($product_info['products_audio_id'] == '7') {
			echo tep_image(DIR_WS_IMAGES . 'dolby_51_surround.gif', 'Closed Captioned', '', '', 'hspace="0" vspace="0"') . '<BR>' . tep_image(DIR_WS_IMAGES . 'dts.gif', 'Dolby Digital 5.1 / DTS Surround', '', '', 'hspace="0" vspace="0"') . '<BR>';
			}
			if ($product_info['products_pc_bonus'] == '0') {
			echo tep_image(DIR_WS_IMAGES . 'pc_bonus.gif', 'Closed Captioned', '', '', 'hspace="0" vspace="0"');
			}

			?>
			</td>
			</tr>

<tr>
            <td align="center" class="smalltext"><a href="ask_a_question.php?products_id=<?php echo $product_info['products_id']; ?>" ONMOUSEOVER="window.status='.:: Ask a question about this item ::.' ; return true">Ask a Question about<br>or notify us of an<br>error on this page with:<br><b><?php echo $product_info['products_name']; ?></b></a></td>
            </tr>


          </table>
<?php
    }
?>

			<p><b><?php echo $product_info['products_byline']?></p></b>
          	<p><?php echo stripslashes($product_info['products_description']); ?></p>

        </td>
      </tr>
	  <tr>
	   <td>
	   <table class="smallText" border="0" bordercolor="EBA000" cellspacing="0" cellpadding="0">
	   	<tr>
	     <td class="smallText" colspan="2" bgcolor="#FFF9EC" align="center">Product Details</td>
	    </tr>
		<tr>
	     <td class="smallText">Video Format -</td><td class="smallText"><?php echo $product_info['products_video_format_name']?></td>
	    </tr>
		<tr>
	     <td class="smallText">Aspect Ratio -</td><td class="smallText"><?php echo $product_info['products_aspect_ratio_name']?></td>
	    </tr>
		<tr>
	     <td class="smallText">Region Code -</td><td class="smallText"><?php echo $product_info['products_region_code_name']?></td>
	    </tr>
		<tr>
	     <td class="smallText">Packaging Type -</td><td class="smallText"><?php echo $product_info['products_packaging_type_name']?></td>
	    </tr>
		<tr>
	     <td class="smallText">Audio Languages -</td><td class="smallText"><?php echo $product_info['products_audio_languages']?></td>
	    </tr>
		<tr>
	     <td class="smallText">CC / Subtitle Languages -</td><td class="smallText"><?php echo $product_info['products_subtitle_languages']?></td>
	    </tr>
		<tr>
	     <td class="smallText">Release Date -</td><td class="smallText"><?php echo $product_info['products_release_date']?></td>
	    </tr>
		<tr>
	     <td class="smallText">ISBN -</td><td class="smallText"><?php echo $product_info['products_isbn']?></td>
	    </tr>
		<tr>
	     <td class="smallText">UPC -</td><td class="smallText"><?php echo $product_info['products_upc']?></td>
	    </tr>
       </table>
	  </tr>
	 </td>
<?php
// BOF MaxiDVD: Modified For Ultimate Images Pack!
 if (ULTIMATE_ADDITIONAL_IMAGES == 'enable') { include(DIR_WS_MODULES . 'additional_images.php'); }
// BOF MaxiDVD: Modified For Ultimate Images Pack!
; ?>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "'");
    $reviews = tep_db_fetch_array($reviews_query);
    if ($reviews['count'] > 0) {
?>
      <tr>
        <td class="main"><?php echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
    }

    if (tep_not_null($product_info['products_url'])) {
?>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info['products_url']), 'NONSSL', true, false)); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
    }

    if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_DATE_ADDED, tep_date_long($product_info['products_date_added'])); ?></td>
      </tr>
<?php
    }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()) . '">' . tep_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS) . '</a>'; ?></td>
                <td class="main" align="right"><?php
				if (($product_info['products_quantity'] <= '0') AND ($product_info['products_out_of_print'] == '0')) {
			 ;
			 } else {
			echo '<br>' . tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART);
			}

	         ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
    if ((USE_CACHE == 'true') && empty($SID)) {
      echo tep_cache_also_purchased(3600);
    } else {
      include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
    }
  }
?>
        </td>
      </tr>
    </table></form></td>
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
