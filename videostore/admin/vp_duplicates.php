<?php
/*
  $Id: distributors.php,v 1.55 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

$type = intval($_GET[type]?$_GET[type]:$_POST[type]);

if ($_POST[action]=='delete'){
	foreach($_POST[todel] as $key=>$value){
		if ($key!=""){
		$k = explode("_", $key);
		$sql_query = "delete from products_to_vendors where vendors_id='".intval($k[1])."' and products_id='".intval($k[0])."'";		
		tep_db_query($sql_query); 
		}
	}
        echo "<script>location.href='vp_duplicates.php?type=".$type."'</script>";
}

if ($type!=1)
$rows = tep_db_query("SELECT count( products_id ) , products_id, vendors_id FROM products_to_vendors GROUP BY products_id HAVING count( * ) >1 order by products_id");		
else
$rows = tep_db_query("SELECT products_id from products_to_vendors where vendors_id='0' or vendors_id is null or vendors_id=''");		
$total = tep_db_num_rows($rows);

?>
<hr><hr>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">DUPLICATED PRODUCTS FOR VENDORS</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
	<tr><Td>
		<? if ($type!=0){ ?>
		<input type="button" value="Duplicate Products"  onclick="location.href='vp_duplicates.php'" />
		<?} else {?>
		<input type="button" value="Products with no Vendor" onclick="location.href='vp_duplicates.php?type=1'" />
		<? } ?>
	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Save changes" onclick="if (confirm('Are you sure you want to delete selected items?')) document.f1.submit();" /></td></tr>
      <tr>
        <td>
<table border="0" width='100%' cellspacing="0" cellpadding="4">
	<tr class="dataTableHeadingRow">
		<td class="dataTableHeadingContent" width="10%">#</td>
		<td class="dataTableHeadingContent" width="10%">Vendors Name</td>
		<td class="dataTableHeadingContent" width="10%">Selling Type</td>
		<td class="dataTableHeadingContent" width="15%">Model Number</td>
		<td class="dataTableHeadingContent" width="35%">Name</td>
		<td class="dataTableHeadingContent" width="20%">UPC</td>
		<td class="dataTableHeadingContent" width="10%">Weight</td>
		<td class="dataTableHeadingContent" width="10%">Price</td>
		<td class="dataTableHeadingContent" width="10%"><b>Delete?</b></td>
	</tr>
<?php if ($total>0) {  ?>
<form action="vp_duplicates.php" method="post" name="f1">
	<input type="hidden" name="action" value="delete" />
	<input type="hidden" name="type" value="<?=$type?>" />
<?
	while ($rd = tep_db_fetch_array($rows)){		
		  $query = tep_db_query("select v.*, vp.vendors_name, d.products_set_type_name, b.products_name_prefix, b.products_name, b.products_name_suffix, p.* from products_to_vendors v LEFT JOIN products p on p.products_id=v.products_id LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." b on p.products_id=b.products_id LEFT JOIN products_set_types d ON p.products_set_type_id = d.products_set_type_id  LEFT JOIN vendors vp on v.vendors_id=vp.vendors_id where v.products_id='".$rd[products_id]."'");
		while ($row = tep_db_fetch_array($query)){
if ($row['products_sale_type']==1) $typ = 'Direct Purchase';
elseif($row['products_sale_type']==2) $typ = 'Consignment';
else $typ = 'Royalty';

			echo "<tr onmouseover='rowOverEffect(this)' onmouseout='rowOutEffect(this)' class='dataTableRow' onMouseOver=''><td class='dataTableContent'>".$row[products_id]."</td><td class='dataTableContent'>".$row[vendors_name]."</td><td class='dataTableContent'>".$typ."</td><td class='dataTableContent'>".$row[products_model]."</td><td class='dataTableContent'>".$row[products_name_prefix]." ".$row[products_name]." ".$row[products_name_suffix]."</td><td class='dataTableContent'>".$row[products_upc]."</td><td class='dataTableContent'>".$row[products_weight]."</td><td class='dataTableContent'>".$row[products_price]."</td><td class='dataTableContent' onclick=''><a href='categories.php?cPath=&pID=".$row[products_id]."&action=new_product&flag=1'>Edit</a><input type='checkbox' name='todel[".$row[products_id]."_".$row[vendors_id]."]' value='1' /></td></tr>";
		}
	}
?>
</form>
<?
 } 
 else
	echo "<tr><td colspan='5' align='center' class='smalltext'><b>No products found</b></td></tr>";
?>
</table>
		</td>
	      </tr>

    </table></td>
<!-- body_text_eof //-->
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
