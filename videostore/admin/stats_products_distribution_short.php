<?php

ini_set("memory_limit","128M");

/*
  $Id: stats_products_viewed.php,v 1.29 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
?>
<style>

.table{
	border: 1px solid black;
	border-collapse: collapse;
	}

table.pad td{
	border: 1px solid #A3A3A3;
	border-collapse: collapse;
}

.l{
	border-left:1px solid black
}

.r{
	border-right:1px solid black
}

.t{
	border-top:1px solid black
}

.b{
	border-bottom:1px solid black
}

</style>

<?

if ($ordersort=='') $ordersort='desc';

if ($orderfield=='products_id') $order = "order by pd.products_id ".$ordersort;
if ($orderfield=='products_model') $order = "order by p.products_model ".$ordersort;
if ($orderfield=='customerflix_id') $order = "order by p.customerflix_id ".$ordersort;
if ($orderfield=='products_name') $order = "order by pd.products_name ".$ordersort;
if ($orderfield=='producers_name') $order = "order by c.producers_name ".$ordersort;
if ($orderfield=='products_vendors') $order = "order by v.vendors_name ".$ordersort;
if ($orderfield=='head_descr') $order = "order by pd.products_head_desc_tag ".$ordersort;
if ($orderfield=='master') $order = "order by pv.master ".$ordersort;
if ($orderfield=='cover') $order = "order by pv.cover ".$ordersort;
if ($orderfield=='label') $order = "order by pv.label ".$ordersort;
if ($orderfield=='web') $order = "order by pv.web ".$ordersort;
if ($orderfield=='amazon') $order = "order by pv.amazon ".$ordersort;
if ($orderfield=='mediazone') $order = "order by pv.mediazone ".$ordersort;
if ($orderfield=='google') $order = "order by pv.google ".$ordersort;
if ($orderfield=='akimbo') $order = "order by pv.akimbo ".$ordersort;
if ($orderfield=='ded') $order = "order by pv.distribution_end_date ".$ordersort;
if ($orderfield=='title') $order = "order by title ".$ordersort;

if ($_SESSION['ordersort']=='asc') $_SESSION['ordersort'] = 'desc'; else $_SESSION['ordersort']='asc';

if ($order=='') $order = "order by pd.products_name desc";

if ($action=='save'){

	/*if ($butt=='Generate PDF'){
		echo "<script>window.open('generate_pdf.php','Generator');</script>";
	}*/

	if ($butt=='Save all changes'){
	$sql_query = tep_db_query("SELECT p.products_id FROM ((((((((products p, products_description pd, producers c) LEFT JOIN products_to_vendors pv on p.products_id = pv.products_id) LEFT JOIN products_video_formats f on p.products_video_format_id = f.products_video_format_id) LEFT JOIN vendors v on pv.vendors_id=v.vendors_id) LEFT JOIN products_region_codes r on p.products_region_code_id=r.products_region_code_id) LEFT JOIN products_media_types m on p.products_media_type_id=m.products_media_type_id) LEFT JOIN products_packaging_types pk on p.products_packaging_type_id=pk.products_packaging_type_id) LEFT JOIN specials s on p.products_id = s.products_id) LEFT JOIN products_set_types st on p.products_set_type_id  = st.products_set_type_id WHERE p.producers_id = c.producers_id AND p.products_id = pd.products_id AND p.products_distribution=1 ".$order."");	
    	while ($prods = tep_db_fetch_array($sql_query)) { 
			$id = $prods['products_id'];		
		$sql = "update products_to_vendors set master='".$HTTP_POST_VARS['master_'.$id]."', cover='".$HTTP_POST_VARS['cover_'.$id]."', label='".$HTTP_POST_VARS['label_'.$id]."', web='".$HTTP_POST_VARS['web_'.$id]."', amazon='".$HTTP_POST_VARS['amazon_'.$id]."', mediazone='".$HTTP_POST_VARS['mediazone_'.$id]."', google='".$HTTP_POST_VARS['google_'.$id]."', akimbo='".$HTTP_POST_VARS['akimbo_'.$id]."', distribution_end_date='".$HTTP_POST_VARS['distribution_end_date_'.$id]."' where products_id=".$id;
		tep_db_query($sql);
	}
	echo "<script>window.location.href='stats_products_distribution_short.php'</script>";
}
	if ($butt=='Generate Report'){
		$sql = "SELECT p.products_id, p.customerflix_id, pv.master, pv.cover, pv.label, pv.web, pv.amazon, pv.mediazone, pv.google, pv.akimbo, pv.distribution_end_date, sr.series_name AS title, p.products_model, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_head_desc_tag, c.producers_name, v.vendors_name FROM (((((((((products p, products_description pd, producers c) LEFT  JOIN series sr ON p.series_id = sr.series_id) LEFT  JOIN products_to_vendors pv ON p.products_id = pv.products_id) LEFT  JOIN products_video_formats f ON p.products_video_format_id = f.products_video_format_id) LEFT  JOIN vendors v ON pv.vendors_id = v.vendors_id) LEFT  JOIN products_region_codes r ON p.products_region_code_id = r.products_region_code_id) LEFT  JOIN products_media_types m ON p.products_media_type_id = m.products_media_type_id) LEFT  JOIN products_packaging_types pk ON p.products_packaging_type_id = pk.products_packaging_type_id) LEFT  JOIN specials s ON p.products_id = s.products_id) LEFT  JOIN products_set_types st ON p.products_set_type_id = st.products_set_type_id WHERE p.producers_id = c.producers_id AND p.products_id = pd.products_id AND p.products_distribution=1 ".$order;
		$export = tep_db_query($sql);
		$fields = mysql_num_fields($export);
		for($i=0;$i<$fields;$i++){
			$header .=mysql_field_name($export, $i)."\t";		
	}

		  while ($row = tep_db_fetch_array($export)) {
			$k = 0;
			$line = '';
			foreach($row as $value){
 if ( ((( !isset( $value ) ) || ( $value == "" )) && (mysql_field_name($export, $k)!='title')) ) 
        {
            $value = "\t";
        } 
        else 
        {
           if ($k==1) $value = ($value==0) ? '' : $value;
           if ($k==11) $value=trim($row[title].' '.$row[products_name_prefix].' '.$row[products_name].' '.$row[products_name_suffix]);

   	    $value = str_replace("&nbsp;", " ", $value);
   	    $value = str_replace("&nbsp", " ", $value);
   	    $value = str_replace("&amp;", "&", $value);
   	    $value = str_replace("&amp", "&", $value);
   	    $value = str_replace("&#38;", "&", $value);
   	    $value = str_replace("&#38", "&", $value);

	    //$value = strip_tags($value);
 	    $str = "&#39";
   	    $value = str_replace($str, "", $value);
   	    $str = "&#34";
   	    $value = str_replace($str, "", $value);
   	    $str = "&#183";
   	    $value = str_replace($str, "", $value);
	    $value = preg_replace("/(\r\n|\n|\r)/", "<br>", $value)."\t";
   	    $value = strip_tags($value);
            //$value = str_replace( '"' , '""' , $value );
            //$value = '"' . $value . '"' . "\t";

        }
        $line .= $value;
	$k++;
	}
	$data .=trim($line)."\n";
    }
	$data = str_replace("\r","",$data);

$fp = fopen("tmp/generate_short.txt", "w+");
fputs($fp, $header."\n".$data);
fclose($fp);
touch("tmp/generate_short.txt");
echo "<script>window.location.href='stats_products_distribution_short.php?load=2'</script>";
}
		
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
<form action="stats_products_distribution_short.php" method="POST">
	<input type="hidden" name="action" value="save" />
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
	      <tr><td colspan="8" style="padding-bottom:10px;"><input type="submit" name="butt" value="Save all changes" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="See Full Report" onClick="javascript:window.location.href='stats_products_distribution.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="butt" value="Generate Report" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onClick="window.open('generate_pdf.php','Generator');" name="butt" value="Generate PDF" />
<?
if (is_file('tmp/generate_short.txt')){
	$real = intval(filesize('tmp/generate_short.txt'));
	$size = round(($real/1024/1024), 4);
	echo "<a target='_new' href='downloader.php?filename=tmp/generate_short.txt' style='font-weight:bold;color:red;font-size:14px;'>See Attachment </a>&nbsp;&nbsp;Size: (".$size." Mb), last generated: ".date ("F d Y H:i:s.", fileatime('tmp/generate_short.txt'));
}
?>
</td></tr>
          <tr>
            <td valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="2" class="table scrollTable">
              <tr class="dataTableHeadingRow">		
                      <td width="70" align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=products_id&ordersort=<?=$_SESSION['ordersort']?>">Products Id</a></td>
                      <td width="70" align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=customerflix_id&ordersort=<?=$_SESSION['ordersort']?>">Customerflix Id</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=master&ordersort=<?=$_SESSION['ordersort']?>" alt="Master" title="Master">M</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=cover&ordersort=<?=$_SESSION['ordersort']?>" alt="Cover" title="Cover">C</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=label&ordersort=<?=$_SESSION['ordersort']?>" alt="Label" title="Label">L</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=web&ordersort=<?=$_SESSION['ordersort']?>" alt="Web" title="Web">Web</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=amazon&ordersort=<?=$_SESSION['ordersort']?>" alt="Amazon" title="Amazon">Am</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=mediazone&ordersort=<?=$_SESSION['ordersort']?>" alt="MediaZone" title="MediaZone">Media</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=google&ordersort=<?=$_SESSION['ordersort']?>" alt="Google" title="Google">G</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=akimbo&ordersort=<?=$_SESSION['ordersort']?>" alt="Akimbo" title="Akimbo">Ak</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=ded&ordersort=<?=$_SESSION['ordersort']?>" alt="Distribution End Date" title="Distribution End Date">DED</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=title&ordersort=<?=$_SESSION['ordersort']?>">Title</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=products_model&ordersort=<?=$_SESSION['ordersort']?>">Product Model</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=products_name&ordersort=<?=$_SESSION['ordersort']?>">Product Title</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=head_desc&ordersort=<?=$_SESSION['ordersort']?>">Head Description</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=producers_name&ordersort=<?=$_SESSION['ordersort']?>">Producer Name</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=products_vendors&ordersort=<?=$_SESSION['ordersort']?>">Vendors Name</a></td>
              </tr>
<?php

$whos_online_query_full2=tep_db_query("SELECT sr.series_name as title, p.products_audio_languages, p.customerflix_id, p.products_subtitle_languages ,p.products_summary, p.products_quantity, p.products_id, p.products_model, p.products_run_time,p.products_upc, p.products_release_date, p.products_asin,p.products_price,f.products_video_format_name,pd.products_name_prefix, pd.products_description, pd.products_name, pd.products_name_suffix, pd.products_clip_url, pd.products_head_desc_tag,pd.products_head_keywords_tag,pd.products_url,pd.products_clip_name,c.producers_name, pv.vendors_id, v.vendors_name,	pv.master, pv.cover, pv.label, pv.web, pv.amazon, pv.mediazone, pv.google, pv.akimbo, pv.distribution_end_date, r.products_region_code_name,r.products_region_code_desc,m.products_media_type_name, pk.products_packaging_type_name,st.products_set_type_name, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price FROM (((((((((products p, products_description pd, producers c) LEFT JOIN series sr on p.series_id = sr.series_id) LEFT JOIN products_to_vendors pv on p.products_id = pv.products_id) LEFT JOIN products_video_formats f on p.products_video_format_id = f.products_video_format_id) LEFT JOIN vendors v on pv.vendors_id=v.vendors_id) LEFT JOIN products_region_codes r on p.products_region_code_id=r.products_region_code_id) LEFT JOIN products_media_types m on p.products_media_type_id=m.products_media_type_id) LEFT JOIN products_packaging_types pk on p.products_packaging_type_id=pk.products_packaging_type_id) LEFT JOIN specials s on p.products_id = s.products_id) LEFT JOIN products_set_types st on p.products_set_type_id  = st.products_set_type_id WHERE p.producers_id = c.producers_id AND p.products_id = pd.products_id AND p.products_distribution=1 ".$order);

$s86=20;
	$per = tep_db_num_rows($whos_online_query_full2)/$s86;
//$s=1000;
if (!isset($_GET['ctr'])) $ctr=0; else $ctr=$_GET['ctr'];
$st86 = $ctr*$s86;
$limit=" limit $st86, $s86";


 // if ($HTTP_GET_VARS['page'] > 1) $rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
  $products_query_raw = "SELECT sr.series_name as title, p.products_audio_languages, p.customerflix_id, p.products_subtitle_languages ,p.products_summary, p.products_quantity, p.products_id, p.products_model, p.products_run_time,p.products_upc, p.products_release_date, p.products_asin,p.products_price,f.products_video_format_name,pd.products_name_prefix, pd.products_description, pd.products_name, pd.products_name_suffix, pd.products_clip_url, pd.products_head_desc_tag,pd.products_head_keywords_tag,pd.products_url,pd.products_clip_name,c.producers_name, pv.vendors_id, v.vendors_name,	pv.master, pv.cover, pv.label, pv.web, pv.amazon, pv.mediazone, pv.google, pv.akimbo, pv.distribution_end_date, r.products_region_code_name,r.products_region_code_desc,m.products_media_type_name, pk.products_packaging_type_name,st.products_set_type_name, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price FROM (((((((((products p, products_description pd, producers c) LEFT JOIN series sr on p.series_id = sr.series_id) LEFT JOIN products_to_vendors pv on p.products_id = pv.products_id) LEFT JOIN products_video_formats f on p.products_video_format_id = f.products_video_format_id) LEFT JOIN vendors v on pv.vendors_id=v.vendors_id) LEFT JOIN products_region_codes r on p.products_region_code_id=r.products_region_code_id) LEFT JOIN products_media_types m on p.products_media_type_id=m.products_media_type_id) LEFT JOIN products_packaging_types pk on p.products_packaging_type_id=pk.products_packaging_type_id) LEFT JOIN specials s on p.products_id = s.products_id) LEFT JOIN products_set_types st on p.products_set_type_id  = st.products_set_type_id WHERE p.producers_id = c.producers_id AND p.products_id = pd.products_id AND p.products_distribution=1 ".$order.$limit;
 // $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);

  $products_query = tep_db_query($products_query_raw);
  while ($products = tep_db_fetch_array($products_query)) {
    $rows++;

if ($rows%22==0) { ?>
              <tr class="dataTableHeadingRow">		
                      <td width="70" align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=products_id&ordersort=<?=$_SESSION['ordersort']?>">Products Id</a></td>
                      <td width="70" align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=customerflix_id&ordersort=<?=$_SESSION['ordersort']?>">Customerflix Id</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=master&ordersort=<?=$_SESSION['ordersort']?>" alt="Master" title="Master">M</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=cover&ordersort=<?=$_SESSION['ordersort']?>" alt="Cover" title="Cover">C</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=label&ordersort=<?=$_SESSION['ordersort']?>" alt="Label" title="Label">L</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=web&ordersort=<?=$_SESSION['ordersort']?>" alt="Web" title="Web">Web</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=amazon&ordersort=<?=$_SESSION['ordersort']?>" alt="Amazon" title="Amazon">Am</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=mediazone&ordersort=<?=$_SESSION['ordersort']?>" alt="MediaZone" title="MediaZone">Media</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=google&ordersort=<?=$_SESSION['ordersort']?>" alt="Google" title="Google">G</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=akimbo&ordersort=<?=$_SESSION['ordersort']?>" alt="Akimbo" title="Akimbo">Ak</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=ded&ordersort=<?=$_SESSION['ordersort']?>" alt="Distribution End Date" title="Distribution End Date">DED</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=title&ordersort=<?=$_SESSION['ordersort']?>">Title</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=products_model&ordersort=<?=$_SESSION['ordersort']?>">Product Model</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=products_name&ordersort=<?=$_SESSION['ordersort']?>">Product Title</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=head_desc&ordersort=<?=$_SESSION['ordersort']?>">Head Description</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=producers_name&ordersort=<?=$_SESSION['ordersort']?>">Producer Name</a></td>
                      <td align="center" class="dataTableHeadingContent r"><a href="stats_products_distribution_short.php?orderfield=products_vendors&ordersort=<?=$_SESSION['ordersort']?>">Vendors Name</a></td>
              </tr>

<?php }
    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
              <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" >
                <td width="70" class="dataTableContent t r" align="center"><?php echo $products['products_id']; ?></td>
                <td width="70" class="dataTableContent t r" align="center"><?php echo ($products['customerflix_id']==0) ? '': $products['customerflix_id']; ?></td>
                <td class="dataTableContent t r" align="center"><input type="checkbox" name="master_<?=$products[products_id]?>" value="1" <?if ($products[master]==1) echo 'checked';?> /></td>
                <td class="dataTableContent t r" align="center"><input type="checkbox" name="cover_<?=$products[products_id]?>" value="1" <?if ($products[cover]==1) echo 'checked';?> /></td>
                <td class="dataTableContent t r" align="center"><input type="checkbox" name="label_<?=$products[products_id]?>" value="1" <?if ($products[label]==1) echo 'checked';?> /></td>
                <td class="dataTableContent t r" align="center"><input type="checkbox" name="web_<?=$products[products_id]?>" value="1"<?if ($products[web]==1) echo 'checked';?> /></td>
                <td class="dataTableContent t r" align="center"><input type="checkbox" name="amazon_<?=$products[products_id]?>" value="1"<?if ($products[amazon]==1) echo 'checked';?> /></td>
                <td class="dataTableContent t r" align="center"><input type="checkbox" name="mediazone_<?=$products[products_id]?>" value="1"<?if ($products[mediazone]==1) echo 'checked';?> /></td>
                <td class="dataTableContent t r" align="center"><input type="checkbox" name="google_<?=$products[products_id]?>" value="1"<?if ($products[google]==1) echo 'checked';?> /></td>
                <td class="dataTableContent t r" align="center"><input type="checkbox" name="akimbo_<?=$products[products_id]?>" value="1"<?if ($products[akimbo]==1) echo 'checked';?> /></td>
                <td class="dataTableContent t r" align="center"><input type="text" name="distribution_end_date_<?=$products[products_id]?>" value="<?=$products[distribution_end_date]?>" style="width:80px;"/></td>
		<td class="dataTableContent t r" align="center"><?php echo trim($products['title'].' '.$products['products_name_prefix']." ".$products['products_name']." ".$products['products_name_suffix']); ?></td>
		<td class="dataTableContent t r" align="center"><?php echo $products['products_model']; ?></td>
                <td class="dataTableContent t r" align="center"><?php echo $products['products_name_prefix']." ".$products['products_name']." ".$products['products_name_suffix']; ?></td>
                <td class="dataTableContent t r" align="center"><?php echo $products['products_head_desc_tag']; ?></td>
                <td class="dataTableContent t r" align="center"><?php echo $products['producers_name']; ?></td>				
                <td class="dataTableContent t r" align="center"><?php echo $products['vendors_name']; ?></td>				
              </tr>
<?php
  }
?>
            </table></td>
          </tr>
      <tr><td colspan="8" style="padding-top:10px;"><input type="submit" name="butt" value="Save all changes" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="See Full Report" onClick="javascript:window.location.href='stats_products_distribution.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="butt" value="Generate Report" />&nbsp;
<?
if (is_file('tmp/generate_short.txt')){
	$real = intval(filesize('tmp/generate_short.txt'));
	$size = round(($real/1024/1024), 4);
	echo "<a target='_new' href='downloader.php?filename=tmp/generate_short.txt' style='font-weight:bold;color:red;font-size:14px;'>See Attachment </a>&nbsp;&nbsp;Size: (".$size." Mb), last generated: ".date ("F d Y H:i:s.", fileatime('tmp/generate_short.txt'));
}
?>
</td></tr>
      <tr><td colspan="8" style="padding-top:10px;"><b>Total number of items: <?=$rows?></b></td></tr>
</form>
         <!-- <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"></td>
                <td class="smallText" align="right"></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>  -->
    </table></td>
<!-- body_text_eof //-->
  </tr>
  <tr>
		<td class="smalltext" colspan="8" align="left">Pages:
        
         
        
		<select name="pagination" id="pagination" onChange="nextPages(this.options[this.selectedIndex].value);">
        <option value="">Go to page...</option>
       <? 
	   
	   
	   
	
			for ($k1=1;$k1<$per+1;$k1++){
				$t1=$k1-1;
				
				echo '<option value="stats_products_distribution_short.php?ctr='.$t1.'">'.$k1.'</option>'; 	
				
			}
	 ?>
 
    </select>
	
	 <? /*
	$per = tep_db_num_rows($whos_online_query_full)/$s;
	for ($k=1;$k<$per+1;$k++){
		$t=$k-1;
	if ($ctr!=$t)
		echo "<a href='whos_online.php?ctr=".$t."' style='font-weight:bold;'>".$k."</a>&nbsp;&nbsp;";
		else
		echo "<font style='font-weight:bold; color:red;'>".$k."</font>&nbsp;&nbsp;";
		} */
	 ?>
	 <script language="javascript" type="text/javascript">
	 function nextPages(val){ 
		 window.location.href = "http://www.travelvideostore.com/admin/"+val;
	 }
	 </script>
		</td>
	      </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); 
if ($_GET['load']==2)
	echo "<script>window.onload=function(){ window.location.href='downloader.php?filename=tmp/generate_short.txt&load=2';}</script>";	
?>