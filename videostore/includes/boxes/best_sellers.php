<?php
/*
  $Id: best_sellers.php,v 1.21 2003/06/09 22:07:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
////////////////////////////////////////////
//Newly inserted for BestSeller Contribution
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
if ($temp_month  > 2)
{
  $this_bestmonth = $today_bestseller['mon'] - 1;
  $this_bestyear = $today_bestseller['year'] ;
}
else
{
  $this_bestmonth = $today_bestseller['mon'] + 10;
  $this_bestyear = $today_bestseller['year'] - 1;
}
//////////////////////////////////////////////
//echo $current_category_id;
  if (isset($current_category_id) && ($current_category_id > 0)) {
	$best_sellers_query = tep_db_query("select distinct p.products_id, p.adgregate, p.products_ordered_periodical_quantity, pd.products_buy_download_link, pd.products_name, pd.products_name_prefix, pd.products_name_suffix, pd.products_clip_url, p.products_media_type_id, se.series_name, mt.products_media_type_name from products_description pd, products p left join products_media_types mt on (p.products_media_type_id = mt.products_media_type_id) left join series se on (p.series_id = se.series_id), products_to_categories p2c, categories c where p.products_status = '1' and (products_quantity>0 or products_always_on_hand=1) and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '" . (int)$current_category_id . "' in (c.categories_id, c.parent_id) order by p.products_ordered_periodical_quantity desc, p.products_ordered desc, pd.products_name limit ". MAX_DISPLAY_BESTSELLERS);
//echo "select distinct p.products_id, p.products_ordered_periodical_quantity, pd.products_buy_download_link, pd.products_name, pd.products_name_prefix, pd.products_name_suffix, pd.products_clip_url, p.products_media_type_id, se.series_name, mt.products_media_type_name from products p, products_description pd left join products_media_types mt on (p.products_media_type_id = mt.products_media_type_id) left join series se on (p.series_id = se.series_id), products_to_categories p2c, categories c where p.products_status = '1' and (products_quantity>0 or products_always_on_hand=1) and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '" . (int)$current_category_id . "' in (c.categories_id, c.parent_id) order by p.products_ordered_periodical_quantity desc, p.products_ordered desc, pd.products_name limit ". MAX_DISPLAY_BESTSELLERS;
  } else {
	$best_sellers_query = tep_db_query("select distinct p.products_id, p.adgregate, p.products_ordered_periodical_quantity, pd.products_buy_download_link,  pd.products_name,  pd.products_name_prefix, pd.products_name_suffix, pd.products_clip_url, p.products_media_type_id, se.series_name, mt.products_media_type_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MEDIA_TYPE . " mt on (p.products_media_type_id = mt.products_media_type_id) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id)  where p.products_status = '1'and (products_quantity>0 or products_always_on_hand=1) and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'  order by p.products_ordered_periodical_quantity desc, pd.products_name limit ".MAX_DISPLAY_BESTSELLERS);

  }

  if (tep_db_num_rows($best_sellers_query) >= MIN_DISPLAY_BESTSELLERS) {

  ////////////////////////////////////////////
  //Newly inserted for BestSeller Contribution
  ////////////////////////////////////////////
  if (isset($HTTP_GET_VARS['cPath']) && $HTTP_GET_VARS['cPath'] <> '')
  {
  	$cpath_exp = explode("_",$HTTP_GET_VARS['cPath']);
  	$cpath_exp_pos = sizeof($cpath_exp) - 1;

  	$top_banner = tep_db_query("select categories_name from ". TABLE_CATEGORIES_DESCRIPTION ." where categories_id = ". $cpath_exp[$cpath_exp_pos]);
  	$top_banner_rec = tep_db_fetch_array($top_banner);

/*  	$total_videos = tep_db_query("select count(*) counter from ". TABLE_PRODUCTS_TO_CATEGORIES." where categories_id = ". $cpath_exp[$cpath_exp_pos]);
  	$top_videos_rec = tep_db_fetch_array($total_videos);
  	$top_videos_count = $top_videos_rec['counter'] - tep_db_num_rows($best_sellers_query);
*/
	if ($listing_split->number_of_rows > tep_db_num_rows($best_sellers_query))
		$top_videos_count = $listing_split->number_of_rows - tep_db_num_rows($best_sellers_query);

 }
  ////////////////////////////////////////////


?>
<!-- best_sellers //-->
          <tr>
            <td>
<img alt="this is image" src="images/bar-clap.gif">
<?php

	if (isset ($top_banner_rec))
	{
		$heading = "Top Sellers for ". $top_banner_rec['categories_name'];
	}
	else
	{
		$heading = "Top Sellers for TravelVideoStore.com";
	}

    $info_box_contents = array();
	//$info_box_contents[] = array('text' => BOX_HEADING_BESTSELLERS);
	$info_box_contents[] = array('text' => $heading); //Newly Inserted for Best Seller

    new infoBoxHeading($info_box_contents, false, false);

    $rows = 0;
    $bestsellers_list = '<table border="0" cellspacing="0" cellpadding="1" WIDTH="100%">';
    while ($best_sellers = tep_db_fetch_array($best_sellers_query)) { 
	$download_clip = '';
	if ($rows == 0) $sep = ''; else $sep = " and ";
	$addition = $addition."".$sep." p.products_id!=".$best_sellers['products_id'];
		$rows++;


if($oplist[$best_sellers['products_id']]){
$app = '<br><font color="green">' . ALREADY_PURCHASED . '</font>';
}else{
$app = '';
}




	if ($best_sellers['adgregate'] == 1) {
      	$download_clip = '&nbsp;&nbsp;&nbsp;<a href="#" onclick="window.open(\''.tep_href_link('adgregate.php','id=' . ($best_sellers['products_id'])) . '\',\'_blank\',\'width=580,height=480\');return false" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the download" title="Can\'t wait for the DVD, click here to purchase the download"></a>';
      
	$video_clip = '<br><a href="#" onclick="window.open(\''.tep_href_link('adgregate.php','id=' . ($best_sellers['products_id'])) . '\',\'_blank\',\'width=580,height=480\');return false" target=_new><img src="images/video_clip.gif" border="0" alt="video_clip.gif" title="video_clip.gif"></a>';
      
	$bestsellers_list .= '<tr><td class="infoBoxContents" valign="top">' . tep_row_number_format($rows) . '.</td><td class="infoBoxContents">' . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id']) . '">' . '<b>' . $best_sellers['series_name'] . '&nbsp;</b>&nbsp;' . $best_sellers['products_name_prefix'] . ' <b>' . $best_sellers['products_name'] . '</b> ' . $best_sellers['products_name_suffix'] . '</a>-' . $best_sellers['products_media_type_name'] . $app . $video_clip . $download_clip.'</td></tr>';

	}
	else{
if (tep_not_null($best_sellers['products_buy_download_link'])) {
      	$download_clip = '&nbsp;&nbsp;&nbsp;<a href="'.tep_href_link('video.php','products_id='.$best_sellers['products_id']).'" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the download" title="Can\'t wait for the DVD, click here to purchase the download"></a>'; } else { $download_clip = ''; }
	if (tep_not_null($best_sellers['products_clip_url'])) {

      	$video_clip = '<br><a href="#" onClick="window.open(\'' . ($best_sellers['products_clip_url']) . '\',\'_blank\',\'width=580,height=480\');return false"><img src="images/video_clip.gif" border="0" alt="View Free Video Clip"></a>'; }  else {$video_clip = ''; }

      	$bestsellers_list .= '<tr><td class="infoBoxContents" valign="top">' . tep_row_number_format($rows) . '.</td><td class="infoBoxContents">' . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id']) . '">' . '<b>' . $best_sellers['series_name'] . '&nbsp;</b>&nbsp;' . $best_sellers['products_name_prefix'] . ' <b>' . $best_sellers['products_name'] . '</b> ' . $best_sellers['products_name_suffix'] . '</a>-' . $best_sellers['products_media_type_name'] . $app . $video_clip .$download_clip.'</td></tr>';

	}
}
//echo "select distinct p.products_id, p.products_ordered_periodical_quantity, pd.products_name, pd.products_name_prefix, pd.products_name_suffix, pd.products_clip_url, p.products_media_type_id, se.series_name, mt.products_media_type_name  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_MEDIA_TYPE . " mt on (p.products_media_type_id = mt.products_media_type_id) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id), " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_ORDERS . " o, "  . TABLE_ORDERS_PRODUCTS . " op where ((p.products_out_of_print = '0' and p.products_quantity > 0) or p.products_always_on_hand='1') and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '" . (int)$current_category_id . "' in (c.categories_id, c.parent_id) and op.orders_id = o.orders_id and op.products_id = p.products_id and ".$addition." and TO_DAYS(NOW()) - TO_DAYS(o.date_purchased) > 30 order by p.products_ordered desc, pd.products_name limit " . $max;


    //Newly inserted for Bestsellers
    if ($top_videos_count <> 0 || $top_videos_count <> '')
    	$bestsellers_list .= '<tr><td class="infoBoxContents" valign="top" colspan="2" align="left"><b> and ' . $top_videos_count . ' other videos</b></td></tr>';

    $bestsellers_list .= '</table>';

    $info_box_contents = array();
    $info_box_contents[] = array('text' => $bestsellers_list);

    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- best_sellers_eof //-->
<?php
  }
?>