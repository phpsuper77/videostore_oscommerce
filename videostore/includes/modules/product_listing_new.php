<script src="pdf/xml_handle.js" type="text/javascript"></script>

<script type="text/javascript">

	function getRights(){
		window.open('<?php echo tep_href_link("rights.php");?>','Rights','height=450, width=400, toolbar=no,menubr=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
	}


	function getPdf(cPath, filter, sort, field, value){
	 	window.open('<?php echo tep_href_link("generate_catalog.php","");?>?cPath='+cPath+'&filter='+filter+'&sort='+sort+'&field='+field+'&value='+value);
	}



	function getChecked(val){
     	 var theForm = document.cart_multi['cartadd[]'], z = 0;
if (theForm.length>1){
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].value == val){
	  theForm[z].checked = true;
	  }
     }
}
	else document.cart_multi['cartadd[]'].checked = true;
	document.cart_multi.submit();

}

	function checkUncheckAll(theElement) {
     var theForm = theElement.form, z = 0;
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall'){
	  theForm[z].checked = theElement.checked;
	  }
     }
    }


<?php
		function fix_javascript_directs($string,$find){
			// echo "1: ".$string."\n";
			$new_string = preg_replace("/(&".$find."\d+)/","",$string);
			// echo "2: ".$new_string."\n";
			$new_string = preg_replace("/(\?".$find."\d+)/","",$new_string);
			// echo "3: ".$new_string."\n";
			$new_string = str_replace("&".$find,"",$new_string);
			// echo "4: ".$new_string."\n";
			$new_string = $new_string."&".$find;
			// echo "5: ".$new_string."\n";
			$new_string = preg_replace("/(\/&)/","/?",$new_string,1);
			$new_string = preg_replace("/(.php&)/",".php?",$new_string,1);
			// echo "6: ".$new_string."\n";
			return $new_string;
		}
?>

	function redirection(val){
	window.location.href="<?php echo fix_javascript_directs($REQUEST_URI,"per_page=");?>"+val;
	}

	function getRefresh(val){
	window.location.href="<?php echo fix_javascript_directs($REQUEST_URI,"filter=");?>"+val;
	}
</script>
<?php
$listing_sql=str_replace("p.has_rights, p.products_id", "p.has_rights, p.products_id, p.adgregate", $listing_sql);
$filter = $HTTP_GET_VARS['filter'];
if ($filter!=""){
	$filter_string="where p.products_media_type_id=".$filter." and ";
	$listing_sql=ereg_replace("where", $filter_string, $listing_sql);
}
// echo $listing_sql;
if (!isset($per_page)) $per_page = (int)$_GET['per_page'];
// echo $per_page."<br>";
if (intval($per_page)==0)
	$needed_str=10;
else
	$needed_str=$per_page;
// echo $needed_str."<br>";

/*
  $Id: product_listing.php,v 1.44 5003/06/09 22:49:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 5003 osCommerce

  Released under the GNU General Public License
*/
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADD_TO_CART_MSG);
//echo $_SERVER['REMOTE_ADDR'];
if (getenv("REMOTE_ADDR") == '194.242.117.148') echo $listing_sql;
  $listing_split = new splitPageResults($listing_sql, $needed_str, 'p.products_id');
  // echoarray_($listing_split);

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
 <tr>
<td bgcolor="yellow" width="100%" colspan="5"><center><font size="2">Use Heading Arrows for Ascending or Descending Sort - by Media, Title or Price</font></center></td>
  </tr>
  <TR>
	<TD align=left class="smallText">
	<table cellspacing="0" cellpadding="0" border="0">
	<tr><td>
	DVD/BLURAY/VHS: 
	<select name="filter" style='font-size:11px;' onChange="getRefresh(this.value);">
		<option value="" <?if ($filter=='') echo 'selected';?>>All
		<option value="8" <?if ($filter=='8') echo 'selected';?>>BluRay Only
		<option value="2" <?if ($filter=='2') echo 'selected';?>>DVD Only
		<option value="1" <?if ($filter=='1') echo 'selected';?>>VHS Only
	</select>
        NTSC/PAL: 
	<select name="format" style='font-size:11px;' onChange="getRefresh(this.value);">
		<option value="" <?if ($format=='') echo 'selected';?>>All
		<option value="2" <?if ($format=='1') echo 'selected';?>>NTSC Only
		<option value="1" <?if ($format=='2') echo 'selected';?>>PAL Only
	</select>
</td><td>
<?
if ($_GET[filter]!='')
	$filter_string = 'and p.products_media_type_id='.$_GET[filter]." ";


if ($_GET[producers_id]!=''){
	$field = "producers_id";
	$value = $_GET[producers_id];
	$sql_query = "SELECT p.products_date_available, p.adgregate, p.products_closed_captioned, media.products_media_type_name, p.products_release_date, p.products_price, sr.series_name, p.products_image_med, p.products_id, p.products_model, p.products_run_time, p.products_upc, p.products_release_date, f.products_video_format_name, pd.products_name_prefix, pd.products_description, pd.products_name, pd.products_name_suffix, pd.products_clip_url, pd.products_buy_download_link, c.producers_name, IF (s.status, s.specials_new_products_price, NULL ) AS specials_new_products_price, IF (s.status, s.specials_new_products_price, p.products_price) AS final_price FROM products p LEFT JOIN products_video_formats f ON p.products_video_format_id = f.products_video_format_id LEFT JOIN specials s ON p.products_id = s.products_id LEFT JOIN series sr ON p.series_id = sr.series_id LEFT JOIN products_description pd ON p.products_id = pd.products_id JOIN products_media_types media ON p.products_media_type_id=media.products_media_type_id ".$filter_string." JOIN producers c ON p.producers_id = c.producers_id and p.producers_id='".$_GET[producers_id]."' where p.products_status!=0 and p.products_out_of_print!=0 or (p.products_out_of_print=0 and p.products_quantity>0)  GROUP BY products_id ORDER BY c.producers_name";
}

if ($_GET[series_id]!=''){
	$field = "series_id";
	$value = $_GET[series_id];
	$sql_query = "SELECT p.products_date_available, p.adgregate, p.products_closed_captioned, media.products_media_type_name, p.products_release_date, p.products_price, sr.series_name, p.products_image_med, p.products_id, p.products_model, p.products_run_time, p.products_upc, p.products_release_date, f.products_video_format_name, pd.products_name_prefix, pd.products_description, pd.products_name, pd.products_name_suffix, pd.products_clip_url, pd.products_buy_download_link, c.producers_name, IF (s.status, s.specials_new_products_price, NULL ) AS specials_new_products_price, IF (s.status, s.specials_new_products_price, p.products_price) AS final_price FROM products p LEFT JOIN products_video_formats f ON p.products_video_format_id = f.products_video_format_id LEFT JOIN specials s ON p.products_id = s.products_id JOIN series sr ON p.series_id = sr.series_id and p.series_id='".$_GET[series_id]."' LEFT JOIN products_description pd ON p.products_id = pd.products_id JOIN products_media_types media ON p.products_media_type_id=media.products_media_type_id ".$filter_string." LEFT JOIN producers c ON p.producers_id = c.producers_id where p.products_status!=0 and p.products_out_of_print!=0 or (p.products_out_of_print=0 and p.products_quantity>0)  GROUP BY products_id ORDER BY c.producers_name";
}


if ($_GET[series_id]=='' and $_GET[producers_id]==''){
	$field = "";
	$value = "";
	$sql_query = "SELECT  p.products_date_available, p.adgregate, p.products_closed_captioned, media.products_media_type_name, p.products_release_date, p.products_price, sr.series_name, p.products_image_med, p.products_id, p.products_model, p.products_run_time, p.products_upc, p.products_release_date, f.products_video_format_name, pd.products_name_prefix, pd.products_description, pd.products_name, pd.products_name_suffix, pd.products_clip_url, pd.products_buy_download_link, c.producers_name, IF (s.status, s.specials_new_products_price, NULL ) AS specials_new_products_price, IF (s.status, s.specials_new_products_price, p.products_price) AS final_price FROM products p JOIN products_to_categories p2c ON p.products_id = p2c.products_id and p2c.categories_id = '".$current_category_id."' LEFT JOIN products_video_formats f ON p.products_video_format_id = f.products_video_format_id LEFT JOIN specials s ON p.products_id = s.products_id LEFT JOIN series sr ON p.series_id = sr.series_id LEFT JOIN products_description pd ON p.products_id = pd.products_id JOIN products_media_types media ON p.products_media_type_id=media.products_media_type_id ".$filter_string." LEFT JOIN producers c ON p.producers_id = c.producers_id where p.products_status!=0 and p.products_out_of_print!=0 or (p.products_out_of_print=0 and p.products_quantity>0)  GROUP BY products_id ORDER BY c.producers_name";
}

$products_query = tep_db_query($sql_query);
$total = ceil(tep_db_num_rows($products_query));
if ($total>0) {
?>
	&nbsp;&nbsp;&nbsp;<a href="#" onclick="getPdf('<?=$current_category_id?>','<?=$filter?>','<?=$sort?>','<?=$field?>','<?=$value?>'); return false;"><img src="images/pdfcatalog.gif" alt="Download your own catalog of your selections by pressing this button" title="Download your own catalog of your selections by pressing this button" border="0" ></a>
	<!--<input type="button" value="Generate new PDF" onclick="GetData('<?=$current_category_id?>','<?=$filter?>','<?=$sort?>','<?=$field?>','<?=$value?>');" />-->
<? }?></td></tr></table>
	</td>
	<td colspan=2 align=right class="smallText">Per Page:
<select name="per_page" onChange="redirection(this.value);" style='font-size:11px;'>
		<option value="10" <? if ($needed_str==10) echo 'selected';?>>10
		<option value="20" <? if ($needed_str==20) echo 'selected';?>>20
		<option value="30" <? if ($needed_str==30) echo 'selected';?>>30
		<option value="50" <? if ($needed_str==50) echo 'selected';?>>50
		<option value="100" <? if ($needed_str==100) echo 'selected';?>>100
	</SELECT>
	</td>
</tr>

  <tr>
    <td class="smallText" width="50%" valign=bottom><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="smallText" width="50%" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td></tr></table>
<?php
  }
  // echo $cPath;
  $cpath_string = ($cPath ? 'cPath=' . $cPath . '&' : '');
?>
<?
echo tep_draw_form('cart_multi', tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')). 'action=add_multi_product&amp;info_message='.MESSAGE1));
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
        $lc_text = '';
        $lc_align = 'right';
        break;

      case 'PRODUCT_LIST_IMAGE':
if ($sort_col==2 & $sort_order==a){
	$img2="_down.png";
	$sort2="2d"; 
	$text2="descendingly";
}elseif($sort_col==2 & $sort_order==d)  {
	$img2="_up.png";
	$sort2="2a";
	$text2="ascendingly";
}else{
	$img2="_up_down.png";
	$sort2="2d";
	$text2="descendingly";
}
        $lc_text = '<a href="index.php?cPath='.$cPath.'&amp;page=1&amp;sort='.$sort2.'" title="Sort products '.$text2.' by Release Date" class="productListing-heading">Release Date</a><BR>&nbsp;<img alt="this is image" src="images/sort'.$img2.'" width="18" height="10" border="0">&nbsp;';
        $lc_align = 'center';
        break;
      case 'PRODUCT_LIST_BUY_NOW':
        $lc_text = 'Add to Cart<input type="checkbox" name="checkall" onclick="checkUncheckAll(this);">';
        $lc_align = 'center';
        break;
    }

    if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
      $lc_text = tep_create_sort_heading($HTTP_GET_VARS['sort'], $col+1, $lc_text);
      if (($col==2)) $lc_text.="<td bgcolor='#D40000'>";
    }

    $list_box_contents[0][] = array('align' => $lc_align,
                                    'params' => 'class="productListing-heading"',
                                    'text' => '&nbsp;' . $lc_text . '&nbsp;');
  }

  if ($listing_split->number_of_rows > 0) {
    $rows = 0;
//echo $listing_split->sql_query;
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;
//$listing['products_buy_download_link'] ='te';
      if (($rows/2) == floor($rows/2)) {
        $list_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $list_box_contents[] = array('params' => 'class="productListing-odd"');
      }

      $cur_row = sizeof($list_box_contents) - 1;

      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        $lc_align = '';

$part = explode("-",substr($listing[products_date_available],0,10));
$date_available = mktime(0, 0, 1, $part[1], $part[2], $part[0]);
$curr_date = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
        switch ($column_list[$col]) {
		  case 'PRODUCT_LIST_MEDIA':
            $lc_align = 'right';
            //$lc_text = '<img src=../images/media_type/' . $listing['products_media_type_id'] . '.gif border=0></img><BR>' . $listing['products_set_type_name'];

			/*changes by kamaraj for VHS/DVD */
			            if($listing['products_media_type_id']=="1")
						{
								$lc_text = '<a href="javascript:void(0)"
			onClick="javascript:window.open(\'./includes/languages/english/vhs.html\',\'VHS\',\'width=400,height=400,scrollbars=yes\')"><img src="../images/media_type/' .
			$listing['products_media_type_id'].'.gif" border=0 alt="VHS - Click Here for More Info in VHS Formats"></a><BR>' . $listing['products_set_type_name'].'<br><b class="boxText">'.$listing['products_region_code_desc'].'</b>';
						}
						else if($listing['products_media_type_id']=="2")
						{
								$lc_text = '<a href="javascript:void(0)" onClick="javascript:window.open(\'./includes/languages/english/dvd.html\',\'DVD\',\'width=400,height=400,scrollbars=yes\')"><img src="../images/media_type/' .
			$listing['products_media_type_id'].'.gif" border=0 alt="DVD - Click Here for More Info on DVD Formats"></a><BR>' . $listing['products_set_type_name'].'<br><b class="boxText">'.$listing['products_region_code_desc'].'</b>';
						}
						else if($listing['products_media_type_id']=="4")
						{
								$lc_text = '<a href="javascript:void(0)" onClick="javascript:window.open(\'./includes/languages/english/cd.html\',\'CD\',\'width=400,height=400,scrollbars=yes\')"><img src="../images/media_type/' .
									$listing['products_media_type_id'].'.gif" border=0 alt="CD - Click Here for More Info on CD Formats"></a><BR>' . $listing['products_set_type_name'].'<br><b class="boxText">'.$listing['products_region_code_desc'].'</b>';
						}
						else
						{
								$lc_text = '<img src="../images/media_type/' . $listing['products_media_type_id'].'.gif" border=0 alt="this is image"><BR>' . $listing['products_set_type_name'].'<br><b class="boxText">'.$listing['products_region_code_desc'].'</b>';
						}
			/*changes by kamaraj for VHS/DVD ends here*/

            break;
          case 'PRODUCT_LIST_MODEL':
            $lc_align = '';
            $lc_text = '&nbsp;' . $listing['products_model'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_NAME':
            $lc_align = '';
if ($listing['has_rights']=='1')
	$has_rights = '&nbsp;<a href="#" style="color:red;" onclick="getRights(); return false;">Distributed by TravelVideoStore.com - Includes Limited Public Performance Rights</a><br/>';
	else
	$has_rights = '';
if (($listing['products_date_available']!='') and ($date_available<$curr_date)) $preorder = ''; else $preorder = "<div><span style='font-family:comic sans MS, Verdana, Arial, sans-serif; font-weight:bold; font-size:13px; color:blue;'>PRE-ORDER</span><br/><span style='font-family:comic sans MS, Verdana, Arial, sans-serif; font-weight:bold; font-size:10px; color:#4D4D4D;'>Ships ".$part[1]."-".$part[2]."-".$part[0]."</span></div>";
            if (isset($HTTP_GET_VARS['manufacturers_id'])) {

				$strip_tag_desc = strip_tags($listing['products_description'], '<b>');
				$products_combine = ($listing['products_byline'] . '&nbsp;&nbsp;' . $strip_tag_desc);
				if($listing['products_release_date']!='')
				$listing_products_year="<b>(".$listing['products_release_date'].")</b>";
				else
				$listing_products_year="";

if (tep_not_null($listing['products_clip_url'])) {
	$listing_products_summary = ((strlen($products_combine) > 1) ? substr($products_combine, 0, 500) .'...</td><td>'.$preorder.'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $cpath_string . 'products_id=' . $listing['products_id']) . '" title="More Video Info"><img src="images/moreinfo.gif" border="0" Align="Top" alt="More Info"></a><br>' .  '<a href="#" onClick="window.open(\'' . ($listing['products_clip_url']) . '\',\'_blank\',\'width=660,height=390\');return false"><img src="images/video_clip.gif" border="0" Align="Top" alt="View Free Video Clip"></a>' : $products_combine); 
	if (tep_not_null($listing['products_buy_download_link']) && $listing['adgregate']!=0) $listing_products_summary .= '<br/><a href="#" onclick="window.open(\'adgregate.php?id=' . ($listing[products_id]) . '\',\'_blank\',\'width=580,height=480\');return false" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, Click here to rent it online" title="Can\'t wait for the DVD, Click here to rent it online"></a>';
	//if (tep_not_null($listing['products_buy_download_link']) && $listing['adgregate']==0) $listing_products_summary .= '<br/><a href="video.php?products_id='.$listing[products_id].'" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, Click here to rent it online" title="Can\'t wait for the DVD, Click here to rent it online"></a>';

  if (tep_not_null($listing['products_buy_download_link'])) $listing_products_summary .= '<br/><a href="'.$listing[products_buy_download_link].'" target="paypal"><img src="images/download_mp4.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the MP4 download file instantly" title="Can\'t wait for the DVD, click here to purchase the MP4 download file instantly"></a>';

}
	else {
		$listing_products_summary = substr($products_combine, 0, 500).'...</td><td>'.$preorder.'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $cpath_string . 'products_id=' . $listing['products_id']) . '" title="More Video Info"><img src="images/moreinfo.gif" border="0" Align="Top" alt="More Info"></a>'; 


if ($listing['adgregate']==1) $listing_products_summary .= '<br/><a href="#" onclick="window.open(\'adgregate.php?id=' . ($listing[products_id]) . '\',\'_blank\',\'width=580,height=480\');return false" target=_new><img src="images/video_clip.gif" border="0" alt="View Free Video Clip" title="View Free Video Clip"></a>';

if ($listing['adgregate']==1) $listing_products_summary .= '<br/><a href="#" onclick="window.open(\'adgregate.php?id=' . ($listing[products_id]) . '\',\'_blank\',\'width=580,height=480\');return false" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, Click here to rent it online" title="Can\'t wait for the DVD, Click here to rent it online"></a>';

  if (tep_not_null($listing['products_buy_download_link'])) $listing_products_summary .= '<br/><a href="'.$listing[products_buy_download_link].'" target="paypal"><img src="images/download_mp4.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the MP4 download file instantly" title="Can\'t wait for the DVD, click here to purchase the MP4 download file instantly"></a>';


		$lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&amp;products_id=' . $listing['products_id']) . '"><b>' . $listing['series_name'] . '</b><BR>' . $listing['products_name_prefix'] . '&nbsp;<b>' . $listing['products_name'] . '</b>&nbsp;' . $listing['products_name_suffix'] . '</a>'.$listing_products_year.'<BR>'.$has_rights.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $listing_products_summary ;}
            } else {
				$strip_tag_desc = strip_tags($listing['products_description'], '<b>');
				$products_combine = ($listing['products_byline'] . '&nbsp;&nbsp;' . $strip_tag_desc);
				//$listing_products_summary = ((strlen($products_combine) > 500) ? substr($products_combine, 0, 500) . '...' : $products_combine);

if (tep_not_null($listing['products_clip_url'])) {
				//$listing_products_summary = ((strlen($products_combine) > 1) ? substr($products_combine, 0, 500) .'...</b></td><td'.$preorder.'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '" title="More Video Info"><img src="images/moreinfo.gif" border="0" Align="Top" alt="More Info"</a><br>' .  '<a href="#" onClick="window.open(\'' . ($listing['products_clip_url']) . '\',\'_blank\',\'width=660,height=390\');return false"><img src="images/video_clip.gif" border="0" Align="Top" alt="View Free Video Clip"</a>' : $products_combine); 

if ($listing['adgregate'] ==1) {
$listing_products_summary = ''.substr($products_combine, 0, 500) .'...</td><td>'.$preorder.'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $cpath_string . 'products_id=' . $listing['products_id']) . '" title="More Video Info"><img src="images/moreinfo.gif" border="0" Align="Top" alt="More Info"></a>';

if ($listing['adgregate'] ==1) $listing_products_summary .= '<br/><a href="#" onclick="window.open(\'adgregate.php?id=' . ($listing[products_id]) . '\',\'_blank\',\'width=320,height=320\');return false" target=_new><img src="images/video_clip.gif" border="0" alt="View Free Video Clip" title="View Free Video Clip"></a>';

  if (tep_not_null($listing['products_buy_download_link'])) $listing_products_summary .= '<br/><a href="'.$listing[products_buy_download_link].'" target="paypal"><img src="images/download_mp4.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the MP4 download file instantly" title="Can\'t wait for the DVD, click here to purchase the MP4 download file instantly"></a>';

} else {

				$listing_products_summary = ''.substr($products_combine, 0, 500) .'...</td><td>'.$preorder.'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $cpath_string . 'products_id=' . $listing['products_id']) . '" title="More Video Info"><img src="images/moreinfo.gif" border="0" Align="Top" alt="More Info"</a><br>' . '<a href="#" onClick="window.open(\'' . ($listing['products_clip_url']) . '\',\'_blank\',\'width=660,height=390\');return false"><img src="images/video_clip.gif" border="0" Align="Top" alt="View Free Video Clip"></a>';}



if ($listing['adgregate']==1) $listing_products_summary .= '<br/><a href="#" onclick="window.open(\'adgregate.php?id=' . ($listing[products_id]) . '\',\'_blank\',\'width=580,height=480\');return false" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, Click here to rent it online" title="Can\'t wait for the DVD, Click here to rent it online"></a>';

//if (tep_not_null($listing['products_buy_download_link']) && $listing['adgregate']!=0) $listing_products_summary .= '<br/><a href="#" onclick="window.open(\'adgregate.php?id=' . ($listing[products_id]) . '\',\'_blank\',\'width=580,height=480\');return false" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, Click here to rent it online" title="Can\'t wait for the DVD, Click here to rent it online"></a>';

//if (tep_not_null($listing['products_buy_download_link']) && $listing['adgregate']==0) $listing_products_summary .= '<br/><a href="video.php?products_id='.$listing[products_id].'" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, Click here to rent it online" title="Can\'t wait for the DVD, Click here to rent it online"></a>';

  if (tep_not_null($listing['products_buy_download_link'])) $listing_products_summary .= '<br/><a href="'.$listing[products_buy_download_link].'" target="paypal"><img src="images/download_mp4.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the MP4 download file instantly" title="Can\'t wait for the DVD, click here to purchase the MP4 download file instantly"></a>';

}
else
{
//$listing_products_summary = ((strlen($products_combine) > 100) ? substr($products_combine, 0, 500) .'...</b></i></td><td><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '" title="More Video Info"><img src="images/moreinfo.gif" border="0" Align="Top" alt="More Info"</a>' : $products_combine); 
$listing_products_summary = substr($products_combine, 0, 500).'...</td><td>'.$preorder.'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $cpath_string . 'products_id=' . $listing['products_id']) . '" title="More Video Info"><img src="images/moreinfo.gif" border="0" Align="Top" alt="More Info"></a>';

if ($listing['adgregate']==1) $listing_products_summary .= '<br/><a href="#" onclick="window.open(\''.tep_href_link('adgregate.php','id=' . ($listing[products_id])) . '\',\'_blank\',\'width=580,height=480\');return false" target=_new><img src="images/video_clip.gif" border="0" alt="View Free Video Clip" title="View Free Video Clip"></a>';

if ($listing['adgregate']==1) $listing_products_summary .= '<br/><a href="#" onclick="window.open(\''.tep_href_link('adgregate.php','id=' . ($listing[products_id])) . '\',\'_blank\',\'width=580,height=480\');return false" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, Click here to rent it online" title="Can\'t wait for the DVD, Click here to rent it online"></a>';

  if (tep_not_null($listing['products_buy_download_link'])) $listing_products_summary .= '<br/><a href="'.$listing[products_buy_download_link].'" target="paypal"><img src="images/download_mp4.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the MP4 download file instantly" title="Can\'t wait for the DVD, click here to purchase the MP4 download file instantly"></a>';


}

				if($listing['products_release_date']!='')
				$listing_products_year="<b>(".$listing['products_release_date'].")</b>";
				else
				$listing_products_year="";
              $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $cpath_string . 'products_id=' . $listing['products_id']) . '"><b>' . $listing['series_name'] . '</b><BR>' . $listing['products_name_prefix'] . '&nbsp;<b>' . $listing['products_name'] . '</b>&nbsp;' . $listing['products_name_suffix'] . '</a>'.$listing_products_year.'<BR>'.$has_rights.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . stripslashes($listing_products_summary) . '' ;
            }
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_align = '';
            $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a>&nbsp;';
            break;
          case 'PRODUCT_LIST_PRICE':
            $lc_align = 'center';
/*
            if (tep_not_null($listing['specials_new_products_price'])) {
              $lc_text = '&nbsp;<s>' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<BR><span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;';
            } else {
              $lc_text = '&nbsp;' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '&nbsp;';
            }
*/
/*=====================Price Conditions=======================*/
$price = '';
ob_start();
if ($whole['iswholesale'] == 1){
	if ($listing['products_distribution'] == 1){

	if (($whole['disc1'] == 1) && (intval($whole[distribution_percentage])>0)){
		echo '<s>' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($listing['products_price'], $listing['products_id'], 1), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';	
		}	
	elseif (($whole['disc1'] == 2) && (intval($whole[distribution_percentage])>0)){
		if (intval($listing['specials_new_products_price'])>0)
		echo '<s>' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($listing['specials_new_products_price'], $listing['products_id'], 1), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';	
		else
		echo '<s>' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($listing['products_price'], $listing['products_id'], 1), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';	
		}	
	else{
 	   if ($listing['specials_new_products_price']) {
      		echo '<s>' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';
    		} else {
      	   echo $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id']));
    		}
	   }

	}
	else{
	if (($whole['disc2'] == 1) && (intval($whole[nondistribution_percentage])>0)){
		echo '<s>' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($listing['products_price'], $listing['products_id'], 2), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';	
		}	
	
	elseif (($whole['disc2'] == 2) && (intval($whole[nondistribution_percentage])>0)){
		if (intval($listing['specials_new_products_price'])>0)
			echo '<s>' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($listing['specials_new_products_price'], $listing['products_id'], 2), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';	
			else
			echo '<s>' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($listing['products_price'], $listing['products_id'], 2), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';	
		}
	else{
 	   if ($listing['specials_new_products_price']) {
      		echo '<s>' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';
    		} else {
      	   echo $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id']));
    		}
	   }
	}
}
	else{
 	   if ($listing['specials_new_products_price']) {
      		echo '<s>' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';
    		} else {
      	   echo $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id']));
    		}
}
echo shippingLabel($listing[products_distribution]);
$price = ob_get_contents();
ob_end_clean();
$lc_text = $price;
/*=====================Price Conditions=======================*/

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
              $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&amp;products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['series_name'] . '&nbsp;' . $listing['products_name_prefix'] . '&nbsp;' . $listing['products_name'] . '&nbsp;' . $listing['products_name_suffix'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
            } else {
              $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $cpath_string . 'products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['series_name'] . '&nbsp;' . $listing['products_name_prefix'] . '&nbsp;' . $listing['products_name'] . '&nbsp;' . $listing['products_name_suffix'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
            }
            break;
          case 'PRODUCT_LIST_BUY_NOW':
            $lc_align = 'center';

$lc_text = '';
			if ($listing['products_always_on_hand'] == '1') $listing['products_quantity'] = '1000';

	   			if ($listing['products_quantity'] >= '1') {
	   			$lc_t = tep_image(DIR_WS_IMAGES . 'in_stock.gif', 'In Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
	   			} elseif (($listing['products_quantity'] <= '0') AND ($listing['products_out_of_print'] == '0')) {
	   			$lc_t = tep_image(DIR_WS_IMAGES . 'out_of_stock_0.gif', 'Out of Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
	   			} elseif (($listing['products_quantity'] <= '0') AND ($listing['products_out_of_print'] == '1')){
	   			$lc_t = tep_image(DIR_WS_IMAGES . 'out_of_stock_1.gif', 'Out of Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
	   			}

if (($whole[iswholesale]==1)) {
	$pos = strpos($lc_t, 'out_of_stock_0.gif');
	if ($pos == '') $lc_t = '';
	$img = "&nbsp;".tep_image(DIR_WS_IMAGES . 'dealer_button.gif', 'Dealer Price!', $image_width, $image_height, 'hspace="0" vspace="0"')."<br/>";
}
	$lc_text = '';
	ob_start();
?>
            <br><table border=0 width="100%">
		<tr><td colspan=2 align=center><?=$lc_t;?></TD></tr>


<?php
if($oplist[$listing['products_id']]){
echo '<tr><td colspan=2 class="smalltext" align=center><font color="red">' . ALREADY_PURCHASED . '</font></TD></tr>';
}
?>



		<tr><TD align=center><?php  if (($listing['products_quantity'] <= '0') and ($listing['products_out_of_print'] == '0')) echo ''; else { echo $img.'<a onclick="getChecked('.$listing[products_id].', this); return false;" href="' . tep_href_link(FILENAME_FEATURED_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=buy_now&amp;products_id=' . $listing['products_id']) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_IN_CART) . '</a>'; ?></td><td valign=top style='padding-top:8px;'><?php echo tep_draw_checkbox_field('cartadd[]', $listing['products_id'],'', $params); }?></TD></tr>
		</table>
<?
	$lc_text = ob_get_contents();
	ob_end_clean();

            break;
        }

        $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                               'params' => 'class="productListing-data"',
                                               'text'  => $lc_text);
      }
    }

    new productListingBox($list_box_contents);
     echo "<table width='100%' cellpadding=0 cellspacing=0 border=0><tr><td style='padding-top:2px;'>".tep_image_submit('button_in_cart_OLD.gif', IMAGE_BUTTON_IN_CART,'align="right"')."</td></tr></TABLE>";
     
    echo '</form>';
  } else {
    $list_box_contents = array();

    $list_box_contents[0] = array('params' => 'class="productListing-odd"');
    $list_box_contents[0][] = array('params' => 'class="productListing-data"',
                                   'text' => TEXT_NO_PRODUCTS);

    new productListingBox($list_box_contents);
  }
echo "<br>";
  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<input type="hidden" name="cartadd[]" value=""/>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
?>