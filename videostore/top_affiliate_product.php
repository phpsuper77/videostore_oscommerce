<?ob_start();?>
<?php
$gen_current_category_id = $products_id;
$gen_cpath = $products_id;
if ($gen_image_type=="small") $image_type="";
elseif ($gen_image_type=="medium") $image_type="_med";

  if (isset($gen_current_category_id) && ($gen_current_category_id > 0)) {
      $gen_best_sellers_query = tep_db_query("select p.products_price, p.products_quantity, p.adgregate, p.products_tax_class_id, mt.products_media_type_name, pd.products_buy_download_link, pd.products_name_prefix, pd.products_name_suffix, pd.products_clip_url, se.series_name, p.products_image".$image_type." as general_image, pd.products_id, pd.products_name, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join series se on (p.series_id = se.series_id) left join " . TABLE_MEDIA_TYPE . " mt on (p.products_media_type_id = mt.products_media_type_id) left join specials s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  }

  if (tep_db_num_rows($gen_best_sellers_query) >= MIN_DISPLAY_BESTSELLERS) {
?>
<link href="<?=HTTP_SERVER?>/stylesheet.css" rel="stylesheet" type="text/css">
<!-- best_sellers //-->
<table style="padding-bottom:10px;" cellpadding=0 cellspacing=0 border=0 width='<?=$HTTP_GET_VARS['width']?>' bgcolor="#F8F8F9">
<?php 
    while ($gen_best_sellers = tep_db_fetch_array($gen_best_sellers_query)) { 
	if ($HTTP_GET_VARS['gen_image']=="YES") $our_image = '<tr><td valign="top" align="center" style="padding:10px;">'.tep_image(DIR_WS_IMAGES . $gen_best_sellers['general_image']).'</td></tr>';
	else $our_image = '';

            if (tep_not_null($gen_best_sellers['specials_new_products_price'])) {
              $lc_text = '<s>'.$currencies->display_price($gen_best_sellers['products_price'], tep_get_tax_rate($gen_best_sellers['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<BR><span class="productSpecialPrice">' . $currencies->display_price($gen_best_sellers['specials_new_products_price'], tep_get_tax_rate($gen_best_sellers['products_tax_class_id'])) . '</span>&nbsp;';
            } else {
              $lc_text = '&nbsp;' . $currencies->display_price($gen_best_sellers['products_price'], tep_get_tax_rate($gen_best_sellers['products_tax_class_id'])) . '&nbsp;';
            }
$download_link	= '';
		if (tep_not_null($gen_best_sellers['products_clip_url'])) {
      $gen_rows++;

$aff_link = ($ref != '')? "&ref=".$ref : "";

if (tep_not_null($gen_best_sellers['products_buy_download_link']) && $gen_best_sellers['adgregate'] == 0) $download_link = '<br/><a href="video.php?products_id='.$gen_best_sellers['products_id'].$aff_link.'" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the download" title="Can\'t wait for the DVD, click here to purchase the download"></a>';
if (tep_not_null($gen_best_sellers['products_buy_download_link']) && $gen_best_sellers['adgregate'] != 0) $download_link = '<br/><a href="#" onclick="window.open(\'adgregate.php?id=' . ($gen_best_sellers[products_id]) . '\',\'_blank\',\'width=320,height=320\');return false" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the download" title="Can\'t wait for the DVD, click here to purchase the download"></a>';
$gen_bestsellers_list .= $our_image.'<tr><td class="infoBoxContents" colspan="2" align="center">' . '<a style="font-size:'.$HTTP_GET_VARS['gen_font'].'px;" target="_new" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'ref='.$ref.'&products_id=' . $gen_best_sellers['products_id']) . '">' . '' . $gen_best_sellers['series_name'] . '&nbsp;&nbsp;' . $gen_best_sellers['products_name_prefix'] . ' ' . $gen_best_sellers['products_name'] . '' . $gen_best_sellers['products_name_suffix'] . '</a>-' . $gen_best_sellers['products_media_type_name'] . '</td></tr><tr><td width="100%" align="center" style="font-size: '.$HTTP_GET_VARS[gen_font_header].'px">'.$lc_text.'</td></tr><tr><td width="100%" align="center"><div><a href="#" onClick="window.open(\'' . ($gen_best_sellers['products_clip_url']) . '\',\'_blank\',\'width=660,height=390\');return false"><img src="images/video_clip.gif" border="0" alt="View Free Video Clip"></a>'.$download_link.'</div><div><a target="_new" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'ref='.$ref.'&products_id=' . $gen_best_sellers['products_id']) . '"><img src="images/moreinfo.gif" border="0" alt="More Info"></a></div></td></tr>';
}
else
{
      $gen_rows++;
$gen_bestsellers_list .= $our_image.'<tr><td class="infoBoxContents" colspan="2" align="center">' . '<a  style="font-size:'.$HTTP_GET_VARS['gen_font'].'px;" target="_new" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'ref='.$ref.'&products_id=' . $gen_best_sellers['products_id']) . '">' . '' . $gen_best_sellers['series_name'] . '&nbsp;&nbsp;' . $gen_best_sellers['products_name_prefix'] . ' ' . $gen_best_sellers['products_name'] . ' ' . $gen_best_sellers['products_name_suffix'] . '</a>-' . $gen_best_sellers['products_media_type_name'] . '</td></tr><tr><td width="100%" align="center" style="font-size: '.$HTTP_GET_VARS[gen_font_header].'px">'.$lc_text.'</td></tr><tr><td width="100%" align="center"><div><a target="_new" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'ref='.$ref.'&products_id=' . $gen_best_sellers['products_id']) . '"><img src="images/moreinfo.gif" border="0" alt="More Info"></a></div></td></tr>';
    }
}
    $gen_bestsellers_list .= '</table>';
    $gen_info_box_contents = array();
    $gen_info_box_contents[] = array('text' => $gen_bestsellers_list);
    //new infoBox($gen_info_box_contents);
echo $gen_bestsellers_list;
?></td></tr></table>
<!-- best_sellers_eof //-->
<?php
  }
?>