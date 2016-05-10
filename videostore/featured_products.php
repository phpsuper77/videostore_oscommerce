<?php

$filter = $HTTP_GET_VARS['filter'];
if ($filter!="") 
	$filter_string=" p.products_media_type_id=".$filter." and ";
	else
	$filter_string=" ";

if (intval($per_page)==0)
	$needed_str=10;
else
	$needed_str=$per_page;


    $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
    $sort_order = substr($HTTP_GET_VARS['sort'], 1);
    $order_str = ' order by ';
if ($sort_col=="")$order_str = ' p.products_always_on_hand desc, pd.products_name';
if ($sort_col==1) $order_str = "p.products_media_type_id " . ($sort_order == 'd' ? "desc" : "") . ", p.products_quantity desc, pd.products_name ";
if ($sort_col==2) $order_str = "p.products_release_date " . ($sort_order == 'd' ? "desc" : "");
if ($sort_col==3) $order_str = "pd.products_name " . ($sort_order == 'd' ? "desc" : "");
if ($sort_col==4) $order_str = "final_price " . ($sort_order == 'd' ? "desc" : "") . ", p.products_quantity desc, pd.products_name ";
/*
  $Id: featured_products.php,v 1.27 2003/06/09 22:35:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FEATURED_PRODUCTS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FEATURED_PRODUCTS));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script>
	function getRights(){
		window.open('<?php echo tep_href_link("rights.php");?>','Rights','height=450, width=400, toolbar=no,menubr=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
	}
</script>
<?php include ('includes/ssl_provider.js.php'); ?>


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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Featured Videos</td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
 <tr>
<td bgcolor="yellow" width="100%" colspan="5"><center><font size="2">Use Heading Arrows for Ascending or Descending Sort - by Media, Title or Price</font></center></td>
  </tr>
	<tr style="padding-top:2px;">
<td align=left class="smallText">
Show: 
	<select name="filter" style='font-size:11px;' onChange="getRefresh(this.value);">
		<option value="" <?if ($filter=='') echo 'selected';?>>All
		<option value="2" <?if ($filter=='2') echo 'selected';?>>DVD Only
		<option value="1" <?if ($filter=='1') echo 'selected';?>>VHS Only
	</select>
</td>
<td align=right class="smallText">
Per Page:
<select name="per_page" onChange="redirection(this.value);" style='font-size:11px;'>
		<option value="10" <? if ($needed_str==10) echo 'selected';?>>10
		<option value="20" <? if ($needed_str==20) echo 'selected';?>>20
		<option value="30" <? if ($needed_str==30) echo 'selected';?>>30
		<option value="50" <? if ($needed_str==50) echo 'selected';?>>50
		<option value="100" <? if ($needed_str==100) echo 'selected';?>>100
	</SELECT>
	</td></tr>
<?
  	        $featured_products_array = array();
                $featured_products_query_raw = "select p.products_release_date, p.adgregate ,p.has_rights, p.products_distribution, p.products_always_on_hand, p.products_date_available, p.products_id, p.products_quantity, p.products_out_of_print, p.products_media_type_id, IF(s.status, s.specials_new_products_price, p.products_price) as final_price, pr.products_region_code_desc, pr.products_region_code_id, pd.products_buy_download_link, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) left join " . TABLE_REGION_CODE . " pr on ( p.products_region_code_id = pr.products_region_code_id ) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where".$filter_string."p.products_status = '1' and f.status = '1' order by ".$order_str;
   		$featured_products_split = new splitPageResults($featured_products_query_raw, $needed_str);
?>
	<TR>
            <td class="smallText" width="50%"><?php echo $featured_products_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
            <td align="right" width="50%" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $featured_products_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
	</tr>
</table>
<?php 
echo tep_draw_form('cart_multi_s', tep_href_link("featured_products.php", tep_get_all_get_params(array('action')). 'cPath=150&action=add_multi_product&info_message='.MESSAGE1));
?>
<TABLE border="0" cellspacing="0" cellpadding="0" class="productListing" width="100%">
<?php
  if (($featured_products_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
<?
if ($sort_col==1 & $sort_order==a){
	$img1="_down.png";
	$sort1="1d"; 
	$text1="descendingly";
}elseif($sort_col==1 & $sort_order==d)  {
	$img1="_up.png";
	$sort1="1a";
	$text1="ascendingly";
}else{
	$img1="_up_down.png";
	$sort1="1a";
	$text1="ascendingly";
}
?>
    <td align="right" class="productListing-heading">
	<a href="featured_products.php?page=1&sort=<?=$sort1?>" title="Sort products <?=$text1?> by VHS<BR>DVD<BR>" class="productListing-heading">VHS<BR>DVD<BR>&nbsp;<img src="images/sort<?=$img1?>" width="18" height="10" border="0"></a>&nbsp;</td>
    <td align="center" class="productListing-heading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<?
if ($sort_col==3 & $sort_order==a){
	$img3="_down.png";
	$sort3="3d";         
	$text3="descendingly";
}elseif($sort_col==3 & $sort_order==d)  {
	$img3="_up.png";
	$sort3="3a";
	$text3="ascendingly";
}else{
	$img3="_up_down.png";
	$sort3="3a";
	$text3="ascendingly";
}

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

?>
    <td class="productListing-heading">
	<a href="featured_products.php?page=1&sort=<?=$sort3?>" title="Sort products <?=$text3?> by Product Name (Brief Description)" class="productListing-heading">Product Name (Brief Description)&nbsp;<img src="images/sort<?=$img3?>" width="18" height="10" border="0"></a>&nbsp;&nbsp;&nbsp;<a href="featured_products.php?page=1&sort=<?=$sort2?>" title="Sort products <?=$text2?> by Release Date" class="productListing-heading">Release Date&nbsp;<img src="images/sort<?=$img2?>" width="18" height="10" border="0"></a>
    </td>
    <TD class="productListing-heading">&nbsp;</td>	
<?
if ($sort_col==4 & $sort_order==a){
	$img4="_down.png";
	$sort4="4d"; 
	$text4="descendingly";
}elseif($sort_col==4 & $sort_order==d)  {
	$img4="_up.png";
	$sort4="4a";
	$text4="ascendingly";
}else{
	$img4="_up_down.png";
	$sort4="4a";
	$text4="ascendingly";
}
?>
    <td valign=top align="right" class="productListing-heading" style="padding-top:10px;">
<a href="featured_products.php?page=1&sort=<?=$sort4?>" title="Sort products <?=$text4?> by Price<BR>" class="productListing-heading">Price<BR>&nbsp;<img src="images/sort<?=$img4?>" width="18" height="10" border="0">&nbsp;</a>
</td>
    <td align="center" class="productListing-heading">&nbsp;Add to Cart<input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"&nbsp;</td>
      </tr>

<?php
  }
?>
      <tr>
<?php
  if ($featured_products_split->number_of_rows > 0) {
    $featured_products_query = tep_db_query($featured_products_split->sql_query);
	$index=0;
    while ($featured_products = tep_db_fetch_array($featured_products_query)) {
      $index++;

/*
      if ($new_price = tep_get_products_special_price($featured_products['products_id'])) {
        $products_price = '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';
      } else {
        $products_price = $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id']));

      }
*/

/*=====================Price Conditions=======================*/
if ($whole['iswholesale'] == 1){
	if ($featured_products['products_distribution'] == 1){

	if (($whole['disc1'] == 1) && (intval($whole[distribution_percentage])>0)){
		$products_price = '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($featured_products['products_price'], $featured_products['products_id'], 1), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';	
		}	
	elseif (($whole['disc1'] == 2) && (intval($whole[distribution_percentage])>0)){
		if (intval($new_price)>0)
		$products_price = '<s>' . $currencies->display_price($new_price, tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($new_price, $featured_products['products_id'], 1), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';	
		else
		$products_price = '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($featured_products['products_price'], $featured_products['products_id'], 1), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';	
		}	
	else{
 	   if ($new_price) {
      		$products_price = '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';
    		} else {
      	   $products_price = $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id']));
    		}
	   }

	}
	else{
	if (($whole['disc2'] == 1) && (intval($whole[nondistribution_percentage])>0)){
		$products_price = '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($featured_products['products_price'], $featured_products['products_id'], 2), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';	
		}	
	
	elseif (($whole['disc2'] == 2) && (intval($whole[nondistribution_percentage])>0)){
		if (intval($new_price)>0)
			$products_price = '<s>' . $currencies->display_price($new_price, tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($new_price, $featured_products['products_id'], 2), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';	
			else
			$products_price = '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price(tep_customer_price($featured_products['products_price'], $featured_products['products_id'], 2), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';	
		}
	else{
 	   if ($new_price) {
      		$products_price = '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';
    		} else {
      	   $products_price = $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id']));
    		}
	   }
	}
}
	else{
 	   if ($new_price) {
      		$products_price = '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';
    		} else {
      	   $products_price = $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id']));
    		}
}

/*=====================Price Conditions=======================*/

	$strip_tag_descript = strip_tags($featured_products['products_description'], '<b>');
	$products_combined = ($featured_products['products_byline'] . '&nbsp;&nbsp;' . $strip_tag_descript);
	//$products_summary = ((strlen($products_combined) > 100) ? substr($products_combined, 0, 500) . '...' : $products_combined);
	$products_summary ='';
	$products_summary = ((strlen($products_combined) > 100) ? substr($products_combined, 0, 500) . '</b></i>' : $products_combined);

?>

          <tr <?php if ($index%2==0) echo 'class="productListing-even"'; else echo 'class="productListing-odd"';?>>
		    <td valign="middle" align="center" class="productListing-data"><?php

		    /*echo '<img src="../images/media_type/' . $featured_products['products_media_type_id'] . '.gif" border="0"></img>'*/
		    /*changes by kamaraj for VHS/DVD */
						if($featured_products['products_media_type_id']=="1")
						   {
							echo '<a href="javascript:void(0)"	onClick=javascript:window.open("./includes/languages/english/vhs.html","VHS","width=400,height=400,scrollbars=yes")><img src=../images/media_type/' .
			$featured_products['products_media_type_id'].'.gif border=0 alt="VHS - Click Here for More Info on VHS Formats"></img></a><BR>' . $featured_products['products_set_type_name'].'<br><b class="boxText">'.$featured_products['products_region_code_desc'].'<b>';
						   }
						   else if($featured_products['products_media_type_id']=="2")
						   {
								echo '<a href="javascript:void(0)"	onClick=javascript:window.open("./includes/languages/english/dvd.html","DVD","width=400,height=400,scrollbars=yes")><img src=../images/media_type/' .
			$featured_products['products_media_type_id'].'.gif border=0 alt="DVD - Click Here for More Info on DVD Formats"></img></a><BR>' . $featured_products['products_set_type_name'].'<br><b class="boxText">'.$featured_products['products_region_code_desc'].'<b>';
						   }
						  else if($featured_products['products_media_type_id']=="4")
						   {
							echo '<a href="javascript:void(0)"	onClick=javascript:window.open("./includes/languages/english/cd.html","CD","width=400,height=400,scrollbars=yes")><img src=../images/media_type/' .
			$featured_products['products_media_type_id'].'.gif border=0 alt="CD - Click Here for More Info on CD Formats"></img></a><BR>' . $featured_products['products_set_type_name'].'<br><b class="boxText">'.$featured_products['products_region_code_desc'].'<b>';
							}
						   else
						   {
								echo '<img src=../images/media_type/' . $featured_products['products_media_type_id'] . '.gif border=0 ></img><BR>' .$featured_products['products_set_type_name'].'<br><b class="boxText">'.$featured_products['products_region_code_desc'].'<b>';
						}
			/*changes by kamaraj for VHS/DVD ends here */
$part = explode("-",substr($featured_products[products_date_available],0,10));
$date_available = mktime(0, 0, 1, $part[1], $part[2], $part[0]);
$curr_date = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));


			if ($featured_products['products_always_on_hand'] == '1') $featured_products['products_quantity'] = '1000';
			if (($featured_products['products_date_available']!='') and ($date_available<$curr_date)) $preorder = ''; else $preorder = "<div style='font-family:comic sans MS, Verdana, Arial, sans-serif; font-weight:bold; font-size:13px; color:blue;'>PRE-ORDER</span><br/><span style='font-family:comic sans MS, Verdana, Arial, sans-serif; font-weight:bold; font-size:10px; color:#4D4D4D;'>Ships ".$part[1]."-".$part[2]."-".$part[0]."</div>";

	   			if ($featured_products['products_quantity'] >= '1') {
	   			$lc_text = tep_image(DIR_WS_IMAGES . 'in_stock.gif', 'In Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
	   			} elseif (($featured_products['products_quantity'] <= '0') AND ($featured_products['products_out_of_print'] == '0')) {
	   			$lc_text = tep_image(DIR_WS_IMAGES . 'out_of_stock_0.gif', 'Out of Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
	   			} elseif (($featured_products['products_quantity'] <= '0') AND ($featured_products['products_out_of_print'] == '1')){
	   			$lc_text = tep_image(DIR_WS_IMAGES . 'out_of_stock_1.gif', 'Out of Stock ', $image_width, $image_height, 'hspace="0" vspace="0"');
	   			}

if (($whole[iswholesale]==1)) {
	$pos = strpos($lc_text, 'out_of_stock_0.gif');
	if ($pos == '') $lc_text = '';
	$img = "&nbsp;".tep_image(DIR_WS_IMAGES . 'dealer_button.gif', 'Dealer Price!', $image_width, $image_height, 'hspace="0" vspace="0"')."<br/>";
}

		    ?></td>
<?
if ($featured_products['has_rights']=='1')
	$has_rights = '&nbsp;<a href="#" style="color:red;" onclick="getRights(); return false;">Includes Limited Public Performance Rights</a><br/>';
	else
	$has_rights = '';


				if($featured_products['products_release_date']!='')
				$listing_products_year="<b>(".$featured_products['products_release_date'].")</b>";
				else
				$listing_products_year="";

?>
            <td align="center" class="productListing-data" width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?></td>
            <td valign="top" class="productListing-data"><?php echo '<b>' . $featured_products['series_name'] . '</b><br>' . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name_prefix'] . '&nbsp;<b>' . $featured_products['products_name'] . '</b>&nbsp;' . $featured_products['products_name_suffix'] . '</u></a>'.$listing_products_year.'<br>'.$has_rights.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>' . $products_summary.$tmp. '</i><br>'; $params="id=ids".$featured_products['products_id'];?></td>
 	    <TD align="center" class="productListing-data">
<?=$preorder?><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']);?>"><img src="images/moreinfo.gif" border="0"></a>

<?
/*
if (tep_not_null($featured_products['products_buy_download_link']) && $featured_products['products_buy_download_link']==0) echo '<br/><a href="video.php?products_id='.$featured_products[products_id].'" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, click here to purchase the download" title="Can\'t wait for the DVD, click here to purchase the download"></a>';
*/
if (tep_not_null($featured_products['adgregate']) && $featured_products['adgregate']!=0) echo '<br/><a href="#" onclick="window.open(\'adgregate.php?id=' . ($featured_products[products_id]) . '\',\'_blank\',\'width=580,height=360\');return false" target=_new><img src="images/download.gif" border="0" alt="Can\'t wait for the DVD, click here to rent it online" title="Can\'t wait for the DVD, click here to rent it online"></a>';
?>
	    </td>	
	    <TD valign="middle" align="center" class="productListing-data"><? echo $products_price.shippingLabel($featured_products[products_distribution]);; ?></td>
            <td  align="center" class="productListing-data" valign="top"><br>

<table border=0><tr><td colspan=2 align=center><?=$lc_text;?></TD>

</tr>


<?php
if($oplist[$featured_products[products_id]]){
echo '<tr><td colspan=2 class="smalltext" align=center><font color="red">' . ALREADY_PURCHASED . '</font></TD></tr>';
}
?>


<tr><TD align=center><?php if (($featured_products['products_quantity'] <= '0') AND ($featured_products['products_out_of_print'] == '0')) echo ''; else { echo $img.'<a onclick="getChecked('.$featured_products[products_id].', this); return false;" href="' . tep_href_link(FILENAME_FEATURED_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products['products_id']) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_IN_CART) . '</a>'; ?></td><td valign=top style='padding-top:8px;'><?php echo tep_draw_checkbox_field('cartadd[]', $featured_products['products_id'],'', $params);}?></TD></tr></table></td>
          </tr>
<?php
    }
  } else {
?>
          <tr>
            <td class="main"><?php echo TEXT_NO_NEW_PRODUCTS; ?></td>
          </tr>
<?php
  }
?>
<?php
  if (($featured_products_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>

</table>
<?php
  }
?>
<table width="100%" cellspacing=0 cellpadding=0 border=0>
      <tr>
        <td valign=top>
		  <table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td width=50%>&nbsp;</td>
<td width="50%" align="right" class="main style1">
<? echo tep_image_submit('button_in_cart_OLD.gif', IMAGE_BUTTON_IN_CART); ?></td>		
</tr>
          <tr>
            <td class="smallText"></td>
            <td style="padding-top:15px;" align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $featured_products_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
</td></tr>
    </table></FORM></td>
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
<script>
	function getChecked(val){
     	 var theForm = document.getElementById('ids'+val);
	  theForm.checked = true;
	document.cart_multi_s.submit();
}

	function checkUncheckAll(theElement) {
     var theForm = theElement.form, z = 0;
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall'){
	  theForm[z].checked = theElement.checked;
	  }
     }
    }
	function redirection(val){
<?
	if (strpos(substr($REQUEST_URI,1),"?")=="")
		$url=substr($REQUEST_URI,1)."?";
	else
		$url=substr($REQUEST_URI,1)."&";
?>
	window.location.href="<?=$url?>per_page="+val;
	}

	function getRefresh(val){
<?
	if (strpos(substr($REQUEST_URI,1),"?")=="")
		$url=substr($REQUEST_URI,1)."?";
	else
		$url=substr($REQUEST_URI,1)."&";
?>

	window.location.href="<?=$url?>&filter="+val;
	}
</script>