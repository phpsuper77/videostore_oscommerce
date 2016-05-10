<?
////////////////////////////////////////////
//Newly inserted for BestSeller Contribution
////////////////////////////////////////////
$gen_today_bestseller = getdate();
$gen_temp_day = $gen_today_bestseller['mday'];

if($gen_temp_day <= 28)
{
  $gen_this_bestday = $gen_temp_day;
}
else
{
  $gen_this_bestday = '28';
}
$gen_temp_month = $gen_today_bestseller['mon'];
if ($gen_temp_month  > 2)
{
  $gen_this_bestmonth = $gen_today_bestseller['mon'] - 1;
  $gen_this_bestyear = $gen_today_bestseller['year'] ;
}
else
{
  $gen_this_bestmonth = $gen_today_bestseller['mon'] + 10;
  $gen_this_bestyear = $gen_today_bestseller['year'] - 1;
}
//////////////////////////////////////////////
if ($individual_banner_id = 2039) $individual_banner_id = 605;
$gen_current_category_id = $individual_banner_id;

$gen_cpath = $individual_banner_id;

$limit=10; 
  if (isset($gen_current_category_id) && ($gen_current_category_id > 0)) {
	  $gen_best_sellers_query = tep_db_query("select distinct p.products_id, p.adgregate, p.products_ordered_periodical_quantity, pd.products_name, pd.products_buy_download_link, pd.products_name_prefix, pd.products_name_suffix, pd.products_clip_url, p.products_image, p.products_media_type_id, se.series_name, mt.products_media_type_name from products_description pd, products p left join products_media_types mt on (p.products_media_type_id = mt.products_media_type_id) left join series se on (p.series_id = se.series_id), products_to_categories p2c, categories c where p.products_status = '1' and (products_quantity>0 or products_always_on_hand = 1) and p.products_id = pd.products_id and pd.language_id = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '" . (int)$gen_current_category_id . "' in (c.categories_id, c.parent_id) order by p.products_ordered_periodical_quantity desc, p.products_ordered desc, pd.products_name limit ". $limit);
  } else {
	  $gen_best_sellers_query = tep_db_query("select distinct p.products_id, p.adgregate, p.products_image, p.products_ordered_periodical_quantity, pd.products_buy_download_link,  pd.products_name,  pd.products_clip_url, pd.products_name_prefix, pd.products_name_suffix, pd.products_clip_url, p.products_media_type_id, se.series_name, mt.products_media_type_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MEDIA_TYPE . " mt on (p.products_media_type_id = mt.products_media_type_id) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id)  where p.products_status = '1' and p.products_ordered_periodical_quantity > 0 and p.products_id = pd.products_id and pd.language_id = '1'  order by p.products_ordered_periodical_quantity desc, pd.products_name limit ".$limit);	  
  }

	$get_name = tep_db_query("select categories_name from ".TABLE_CATEGORIES_DESCRIPTION." where categories_id=".$gen_current_category_id);
  	$cat_name = tep_db_fetch_array($get_name);	

  if (tep_db_num_rows($gen_best_sellers_query) >= MIN_DISPLAY_BESTSELLERS) {

  ////////////////////////////////////////////
  //Newly inserted for BestSeller Contribution
  ////////////////////////////////////////////
  if (isset($gen_cpath) && $gen_cpath <> '')
  {
  	$gen_cpath_exp = explode("_",$gen_cpath);
  	$gen_cpath_exp_pos = sizeof($gen_cpath_exp) - 1;

  	$gen_top_banner = tep_db_query("select categories_name from ". TABLE_CATEGORIES_DESCRIPTION ." where categories_id = ". $gen_cpath_exp[$gen_cpath_exp_pos]);
  	$gen_top_banner_rec = tep_db_fetch_array($gen_top_banner);

  	$gen_total_videos = tep_db_query("select count(*) counter from ". TABLE_PRODUCTS_TO_CATEGORIES." where categories_id = ". $gen_cpath_exp[$gen_cpath_exp_pos]);
  	$gen_top_videos_rec = tep_db_fetch_array($gen_total_videos);
  	$gen_top_videos_count = $gen_top_videos_rec['counter'] - tep_db_num_rows($gen_best_sellers_query);

	if ($gen_listing_split->number_of_rows > tep_db_num_rows($gen_best_sellers_query))
		$gen_top_videos_count = $gen_listing_split->number_of_rows - tep_db_num_rows($gen_best_sellers_query);

 }
  ////////////////////////////////////////////
if (tep_db_num_rows($gen_best_sellers_query)>0){
?>
<link href="<?=HTTP_SERVER?>/stylesheet.css" rel="stylesheet" type="text/css">
<style>
BODY{
	scrollbar-3dlight-color: #F8F8F9; 
	scrollbar-arrow-color:#B8B8B9; 
	scrollbar-darkshadow-color:#F8F8F9; 
	scrollbar-face-color:#C4C4C6; 
	scrollbar-highlight-color:#F8F8F9; 
	scrollbar-shadow-color: #F8F8F9;
}
</style>
<!-- best_sellers //-->
<?
	if (isset ($gen_top_banner_rec))
	{
		$gen_heading = "Top Sellers for ". $gen_top_banner_rec['categories_name'];
		//$gen_heading = ("#country#", $gen_top_banner_rec['categories_name'], $HTTP_GET_VARS['gen_header']);
}
/*	else
	{
		$gen_heading = "Top Sellers for TravelVideoStore.com";
	}
*/
?>
<table cellpadding=0 cellspacing=0 border=0 width='<?=$HTTP_GET_VARS['gen_width']?>' bgcolor="#F8F8F9">
<tr><td align="center" bgcolor=black style="padding:2px;color:white;font-size:<?=$HTTP_GET_VARS['gen_font_header']?>px;font-weight:bold;"><?=stripslashes($gen_heading)?></td></tr>
          <tr>
            <td valign="top" bgcolor="#F8F8F9">
<?php $gen_rows = 0; $gen_bestsellers_list='<table bgcolor="#F8F8F9" border="0" cellspacing="3" cellpadding="1" WIDTH="'.$HTTP_GET_VARS[gen_width].'" valign=top>';if ($gen_top_videos_count <> 0 || $gen_top_videos_count <> '')
if ($gen_current_category_id!=0){
	$gen_bestsellers_list .= '<tr><td class="infoBoxContents" valign="top" colspan="3" align="left" style="font-size:'.$HTTP_GET_VARS['gen_font'].'px;"> and ' . $gen_top_videos_count . ' other <a  target="_new" href="'.HTTP_SERVER.'/index.php?cPath='.$gen_current_category_id.'&ref='.$HTTP_GET_VARS['ref'].'">'.$cat_name['categories_name'].'</a> videos</td></tr>';
	$gen_bestsellers_list .= '<tr><td colspan="2" align="center" height="1"><hr color="black" style="height:1px;"/></td></tr>';
}

    while ($gen_best_sellers = tep_db_fetch_array($gen_best_sellers_query)) { 
	$download_link = '';
	if ($HTTP_GET_VARS['gen_image']=="YES") $our_image = '<td>'.tep_image(DIR_WS_IMAGES . $gen_best_sellers['products_image']).'</td>';
	else $our_image = '';
		if (tep_not_null($gen_best_sellers['products_clip_url'])) {
      $gen_rows++;
$aff_link = ($ref != '')? "&ref=".$ref : "";
if (tep_not_null($gen_best_sellers['products_buy_download_link']) && $gen_best_sellers['adgregate'] != 0) $download_link = '<br/><a href="#" onclick="window.open(\'adgregate.php?id=' . ($gen_best_sellers[products_id]) . '\',\'_blank\',\'width=320,height=320\');return false" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the download" title="Can\'t wait for the DVD, click here to purchase the download"></a>';
if (tep_not_null($gen_best_sellers['products_buy_download_link']) && $gen_best_sellers['adgregate'] == 0) $download_link = '<br/><a href="video.php?products_id='.$gen_best_sellers['products_id'].$aff_link.'" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the download" title="Can\'t wait for the DVD, click here to purchase the download"></a>';
$gen_bestsellers_list .= '<tr><td class="infoBoxContents" colspan="2" style="font-size:'.$HTTP_GET_VARS['gen_font'].'px;">' . '<a style="font-size:'.$HTTP_GET_VARS['gen_font'].'px;" target="_new" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'ref='.$ref.'&products_id=' . $gen_best_sellers['products_id']) . '">' . '' . $gen_best_sellers['series_name'] . '&nbsp;&nbsp;' . $gen_best_sellers['products_name_prefix'] . ' ' . $gen_best_sellers['products_name'] . '' . $gen_best_sellers['products_name_suffix'] . '</a>-' . $gen_best_sellers['products_media_type_name'] . '</td></tr><tr>'.$our_image.'<td width="100%" align="left"><div><a href="#" onClick="window.open(\'' . ($gen_best_sellers['products_clip_url']) . '\',\'_blank\',\'width=660,height=390\');return false"><img src="images/video_clip.gif" border="0" alt="View Free Video Clip"></a>'.$download_link.'</div><div><a target="_new" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'ref='.$ref.'&products_id=' . $gen_best_sellers['products_id']) . '"><img src="images/moreinfo.gif" border="0" alt="More Info"></a></div></td></tr>';
}
else
{
      $gen_rows++;
      //$gen_bestsellers_list .= '<tr><td class="infoBoxContents" valign="top" style="font-size:'.$HTTP_GET_VARS['gen_font'].'px;">' . tep_row_number_format($gen_rows) . '.</td><td class="infoBoxContents" colspan="2" style="font-size:'.$HTTP_GET_VARS['gen_font'].'px;">' . '<a  style="font-size:'.$HTTP_GET_VARS['gen_font'].'px;" target="_new" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'ref='.$ref.'&products_id=' . $gen_best_sellers['products_id']) . '">' . '' . $gen_best_sellers['series_name'] . '&nbsp;&nbsp;' . $gen_best_sellers['products_name_prefix'] . ' ' . $gen_best_sellers['products_name'] . ' ' . $gen_best_sellers['products_name_suffix'] . '</a>-' . $gen_best_sellers['products_media_type_name'] . '</td></tr><tr><td></td><td width="39">'.tep_image(DIR_WS_IMAGES . $gen_best_sellers['products_image']).'</td><td width="100%" align="left"><div><a target="_new" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'ref='.$ref.'&products_id=' . $gen_best_sellers['products_id']) . '"><img src="images/moreinfo.gif" border="0" alt="More Info"></a></div></td></tr>';
$gen_bestsellers_list .= '<tr><td class="infoBoxContents" colspan="2" style="font-size:'.$HTTP_GET_VARS['gen_font'].'px;">' . '<a  style="font-size:'.$HTTP_GET_VARS['gen_font'].'px;" target="_new" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'ref='.$ref.'&products_id=' . $gen_best_sellers['products_id']) . '">' . '' . $gen_best_sellers['series_name'] . '&nbsp;&nbsp;' . $gen_best_sellers['products_name_prefix'] . ' ' . $gen_best_sellers['products_name'] . ' ' . $gen_best_sellers['products_name_suffix'] . '</a>-' . $gen_best_sellers['products_media_type_name'] . '</td></tr><tr>'.$our_image.'<td width="100%" align="left"><div><a target="_new" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'ref='.$ref.'&products_id=' . $gen_best_sellers['products_id']) . '"><img src="images/moreinfo.gif" border="0" alt="More Info"></a></div></td></tr>';
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
//	 	}
	}
}
?>