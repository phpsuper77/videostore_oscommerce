<?php
/*
  $Id: stats_products_viewed.php,v 1.29 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
?>
<script>
	function checkUncheckAll(theElement) {
     var theForm = theElement.form, z = 0;
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall'){
	  theForm[z].checked = theElement.checked;
	  }
     }
    }
</script>
<?

if ($action=='save'){


	if ($butt=='Generate All'){
	$sql_query = tep_db_query("select * from products_description order by products_name");	
    	while ($prods = tep_db_fetch_array($sql_query)) { 

ob_start();
	getDescrWords($prods['products_description']);
	$keywords = eregi_replace('\/','',stripslashes(strip_tags(ob_get_contents())));
ob_end_clean();

ob_start();
$sql = "select b.categories_name from categories_description b left join products_to_categories a on (a.categories_id=b.categories_id) where a.products_id=".$prods[products_id]."";
$sql = tep_db_query($sql);
$cat_list = '';
  while ($cats = tep_db_fetch_array($sql)) {
	echo $cats['categories_name'].", ";
}
	$categs = ob_get_contents();
ob_end_clean();

/*
start - video, videos, film, films, movie, movies, clips, clip,
end - , sightseeing, tourist, destination, tour, travelogue, travelog, planning, vacation, holiday, trek, geography, about, on, of
*/


$total_categs = eregi_replace("&","and",$categs);
$start_at = "travel, documentary";
$end_at = "";
if (trim($prods['products_name_prefix']!=="")) $prods['products_name_prefix'] = $prods['products_name_prefix'].", ";
if (trim($prods['products_name']!=="")) $prods['products_name'] = $prods['products_name'].", ";
if (trim($prods['products_name_suffix']!=="")) $prods['products_name_suffix'] = $prods['products_name_suffix'].", ";
$final = $start_at. $prods['products_name_prefix']. $prods['products_name'] . $prods['products_name_suffix'] . $total_categs. $keywords. $end_at;
$final = eregi_replace(", ,", ",", $final);
$final = eregi_replace("&","and",$final);
$final = eregi_replace('\"', '', $final);
$final = eregi_replace("\'", '', $final);
$final = eregi_replace("\.", '', $final);
$final = eregi_replace(",,", ",", $final);

		$ids = $prods[products_id];
		$query1 = "update products_description set products_url='http://www.travelvideostore.com/product_info.php?products_id=".$ids."', products_head_keywords_tag='".mysql_escape_string($final)."' where products_id=".$ids;
		tep_db_query($query1);
		}
	echo "<script>window.location.href='stats_products_keywords.php';</script>";
	}


	if ($butt=='Generate Selected'){
		for ($i=0; $i<count($HTTP_POST_VARS['products']); $i++) {
			$id = $HTTP_POST_VARS['products'][$i];
			$sql_query = "update products_description set products_url='http://www.travelvideostore.com/product_info.php?products_id=".$id."', products_head_keywords_tag='".mysql_escape_string($HTTP_POST_VARS['descr_'.$id])."' where products_id=".$id;
			tep_db_query($sql_query);
		}				
	}



}

function getDescrWords($val){
/*
preg_match_all("|<strong>(.*)</strong>|Ui", $val, $out, PREG_SET_ORDER);
for ($i=0;$i<count($out); $i++)
echo $out[$i][1].",";

preg_match_all("|<b>(.*)</b>|Ui", $val, $out, PREG_SET_ORDER);
for ($i=0;$i<count($out); $i++)
echo $out[$i][1].",";
*/
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Products Keywords</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<form action="stats_products_keywords.php" method="POST" name="prod_list">
	<input type="hidden" name="action" value="save" />
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
	  <tr><TD colspan="3" style="padding-bottom:5px;"><input type="submit" name="butt" value="Generate All" />&nbsp;&nbsp;&nbsp;<input type="submit" name="butt" value="Generate Selected" /></td></tr>
          <tr>
            <td valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                      <td width="70" align="center" class="dataTableHeadingContent">Products Id</td>
		      <td width="50" class="dataTableHeadingContent">Check All&nbsp;<input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"></td>
                      <td width="200" align="center" class="dataTableHeadingContent">Product Model</td>
                      <td width="350" align="center" class="dataTableHeadingContent">Product Title</td>
                      <td width="350" align="center" class="dataTableHeadingContent">Database Keywords</td>
                      <td width="350" align="center" class="dataTableHeadingContent">New Generated Keywords</td>
              </tr>
<?php
  if ($HTTP_GET_VARS['page'] > 1) $rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
  $products_query_raw = "select pd.products_head_keywords_tag, pd.products_name_suffix, pd.products_name_prefix, p.products_id, p.products_model, pd.products_name, pd.products_description from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id. "' group by pd.products_id order by pd.products_name";
  $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);

  $products_query = tep_db_query($products_query_raw);
  while ($products = tep_db_fetch_array($products_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
ob_start();
	getDescrWords($products['products_description']);
	$keywords = eregi_replace('\/','',stripslashes(strip_tags(ob_get_contents())));
ob_end_clean();

ob_start();
$sql_query = "select b.categories_name from categories_description b left join products_to_categories a on (a.categories_id=b.categories_id) where a.products_id=".$products[products_id]."";
$sql_query = tep_db_query($sql_query);
$cat_list = '';
  while ($cats = tep_db_fetch_array($sql_query)) {
	echo $cats['categories_name'].", ";
}
	$categs = ob_get_contents();
ob_end_clean();

$total_categs = eregi_replace("&","and",$categs);
$start_at = "video, videos, films, film, movies, movie, clips, clip, ";
$end_at = "travel, sightseeing, tourist, destination, tour, travelogue, travelog, planning, vacation, holiday, trek, documentary, geography, about, on, of";
if (trim($products['products_name_prefix']!=="")) $products['products_name_prefix'] = $products['products_name_prefix'].", ";
if (trim($products['products_name']!=="")) $products['products_name'] = $products['products_name'].", ";
if (trim($products['products_name_suffix']!=="")) $products['products_name_suffix'] = $products['products_name_suffix'].", ";
$final = $start_at. $products['products_name_prefix']. $products['products_name'] . $products['products_name_suffix'] . $total_categs. $keywords. $end_at;
$final = eregi_replace(", ,", ",", $final);
$final = eregi_replace("&","and",$final);
$final = eregi_replace('\"', '', $final);
$final = eregi_replace("\'", '', $final);
$final = eregi_replace("\.", '', $final);
$final = eregi_replace(",,", ",", $final);
?>
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                <td class="dataTableContent" align="center"><?php echo $products['products_id']; ?></td>
		<td width="20"><input type="checkbox" name="products[]" value="<?=$products['products_id']?>" /></td>
                <td class="dataTableContent" align="center"><?php echo $products['products_model']; ?></td>
                <td class="dataTableContent"><?php echo '<a target=_new href="http://www.travelvideostore.com/product_info.php?products_id='. $products[products_id] .'">' . $products['products_name_prefix'] . $products['products_name'] . $products['products_name_suffix'] . '</a>'; ?></td>				
                <td class="dataTableContent" align="center"><textarea style="width:300px; height:50px;" readonly><?=$products['products_head_keywords_tag']?></textarea></td>
		<td class="dataTableContent" align="center"><textarea style="width:300px; height:50px;" name="descr_<?=$products[products_id]?>"><?=$final?></textarea></td>
              </tr>
<?php
  }
?>
	  <tr><TD colspan="3" style="padding-top:5px;"><input type="submit" name="butt" value="Generate All" />&nbsp;&nbsp;&nbsp;<input type="submit" name="butt" value="Generate Selected" /></td></tr>
            </table></td>
          </tr>
</form>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>