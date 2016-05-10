<?php
/*
  $Id: distributors.php,v 1.55 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');


$type = $_POST['type'] ? $_POST['type'] : $_GET['type'];

if ($_GET['action']=='search'){
	switch($type){
		case '1':
		$rows = tep_db_query("select a.*, c.products_set_type_name, b.products_name_prefix, b.products_name, b.products_name_suffix from ".TABLE_PRODUCTS." a LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." b on a.products_id=b.products_id LEFT JOIN products_set_types c on a.products_set_type_id=c.products_set_type_id  where a.products_weight='0.00' or a.products_weight='' or a.products_weight is NULL ORDER BY a.products_model");		
		break;
		
		case '2':
		$rows = tep_db_query("select a.*, c.products_set_type_name, b.products_name_prefix, b.products_name, b.products_name_suffix from ".TABLE_PRODUCTS." a LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." b on a.products_id=b.products_id LEFT JOIN products_set_types c on a.products_set_type_id=c.products_set_type_id  where a.products_price='0.00' or a.products_price='' or a.products_price is NULL ORDER BY a.products_model");		
		break;

		case '3':
		$rows = tep_db_query("select distinct(a.products_id), d.products_set_type_name, a.*, b.products_name_prefix, b.products_name, b.products_name_suffix from ".TABLE_PRODUCTS." a LEFT JOIN ".TABLE_PRODUCTS." c on a.products_model=c.products_model LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." b on a.products_id=b.products_id LEFT JOIN products_set_types d ON a.products_set_type_id = d.products_set_type_id WHERE a.products_id!=c.products_id and a.products_model IS NOT NULL ORDER BY a.products_model");		
		break;

		case '4':
		$rows = tep_db_query("select a.*, d.products_set_type_name, b.products_name_prefix, b.products_name, b.products_name_suffix from ".TABLE_PRODUCTS." a LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." b on a.products_id=b.products_id LEFT JOIN products_set_types d ON a.products_set_type_id = d.products_set_type_id where (a.products_image is NULL or a.products_image like '%noimage.jpg%') or (a.products_image_med is NULL or a.products_image_med like '%noimage.jpg%') or (a.products_image_lrg is NULL or a.products_image_lrg like '%noimage.jpg%') ORDER BY a.products_model");		break;

		case '5':
		$rows = tep_db_query("SELECT products_model, products_id, products_upc FROM products WHERE products_upc IS NOT NULL AND products_upc != '0' AND products_upc != '' GROUP BY products_upc HAVING count( * ) >1 ORDER BY products_model");		
		break;

		case '6':
		$rows = tep_db_query("select a.*, d.products_set_type_name, b.products_name_prefix, b.products_name, b.products_name_suffix from ".TABLE_PRODUCTS." a LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." b on a.products_id=b.products_id LEFT JOIN products_set_types d ON a.products_set_type_id = d.products_set_type_id where a.products_weight>1 ORDER BY a.products_model");		
		break;

		case '7':
		$rows = tep_db_query("SELECT a.*, d.products_set_type_name, c.products_name_prefix, c.products_name, c.products_name_suffix FROM `products` a LEFT JOIN products_description c ON c.products_id = a.products_id LEFT JOIN products_set_types d ON a.products_set_type_id = d.products_set_type_id LEFT JOIN products_to_categories b ON b.products_id = a.products_id GROUP BY a.products_id HAVING count( b.categories_id ) <2");
		break;

		case '8':
		$rows = tep_db_query("SELECT products_model, products_id, customerflix_id FROM products WHERE customerflix_id IS NOT NULL AND customerflix_id != '0' AND customerflix_id != '' GROUP BY customerflix_id HAVING count( * ) >1 ORDER BY customerflix_id");	
		break;

	
	}

$total = tep_db_num_rows($rows);
}
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
            <td class="pageHeading">QUALITY CONTROL</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
		<table width="100%">
<tr><td colspan="12" align="right" class='smalltext'>Choose Type:&nbsp;<select name="type" onchange="location.href='quality_control.php?action=search&type='+this.value"><option value="0">Select Report Type</option><option value="1" <?=($type==1)?'selected':''?>>Zero Weight</option><option value="2" <?=($type==2)?'selected':''?>>Zerro Price</option><option value="3" <?=($type==3)?'selected':''?>>Duplicate Model Number</option><option value="5" <?=($type==5)?'selected':''?>>Duplicate UPC</option><option value="4" <?=($type==4)?'selected':''?>>No images</option><option value="6" <?=($type==6)?'selected':''?>>Weight more then 1 pound</option><option value="7" <?=($type==7)?'selected':''?>>Less then in 2 categories</option><option value="8" <?=($type==8)?'selected':''?>>Duplicate Createspace Id</option></select></td></tr>
</table>
<table border="0" width='100%' cellspacing="0" cellpadding="4">
	<tr class="dataTableHeadingRow">
		<td class="dataTableHeadingContent" width="10%">#</td>
		<td class="dataTableHeadingContent" width="10%">Set Type</td>
		<td class="dataTableHeadingContent" width="15%">Model Number</td>
		<td class="dataTableHeadingContent" width="35%">Name</td>
		<td class="dataTableHeadingContent" width="20%">UPC</td>
		<td class="dataTableHeadingContent" width="10%">Weight</td>
		<td class="dataTableHeadingContent" width="10%">Price</td>
		<td class="dataTableHeadingContent" width="10%">Images (S, M, L)</td>
	</tr>
<?php if ($total>0) { 
	if ($type!=5){
	while ($row = tep_db_fetch_array($rows)){

	echo "<tr onmouseover='rowOverEffect(this)' onmouseout='rowOutEffect(this)' class='dataTableRow' onMouseOver='' onclick='location.href=\"categories.php?cPath=&pID=".$row[products_id]."&action=new_product&flag=1\"'><td class='dataTableContent'>".$row[products_id]."</td><td class='dataTableContent'>".$row[products_set_type_name]."</td><td class='dataTableContent'>".$row[products_model]."</td><td class='dataTableContent'>".$row[products_name_prefix]." ".$row[products_name]." ".$row[products_name_suffix]."</td><td class='dataTableContent'>".$row[products_upc]."</td><td class='dataTableContent'>".$row[products_weight]."</td><td class='dataTableContent'>".$row[products_price]."</td>";

	if ((strpos($row[products_image], 'noimage.jpg') !== false)  and ($row[products_image] != '')) $regular = "<img src='../images/".$row[products_image]."' alt='Small' width='43' height='60'/>"; else $regular = '';
	if ((strpos($row[products_image_med], 'noimage.jpg') !== false)  and ($row[products_image_med] != '')) $medium = "<img src='../images/".$row[products_image_med]."' alt='Medium' width='43' height='60'/>"; else $medium = '';
	if ((strpos($row[products_image_lrg], 'noimage.jpg') !== false)  and ($row[products_image_lrg] != '')) $large = "<img src='../images/".$row[products_image_lrg]."' alt='Large' width='43' height='60'/>"; else $large = '';


	echo "<td class='dataTableContent'>".$regular."&nbsp;".$medium."&nbsp;".$large."</td></tr>";

	}
   }
	else{
	while ($rd = tep_db_fetch_array($rows)){		
		$query = tep_db_query("select a.*, c.products_set_type_name, b.products_name_prefix, b.products_name, b.products_name_suffix from ".TABLE_PRODUCTS." a LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." b on a.products_id=b.products_id LEFT JOIN products_set_types c on a.products_set_type_id = c.products_set_type_id where a.products_upc='".$rd[products_upc]."'");
		while ($row = tep_db_fetch_array($query)){
			echo "<tr onmouseover='rowOverEffect(this)' onmouseout='rowOutEffect(this)' class='dataTableRow' onMouseOver='' onclick='location.href=\"categories.php?cPath=&pID=".$row[products_id]."&action=new_product\"'><td class='dataTableContent'>".$row[products_id]."</td><td class='dataTableContent'>".$row[products_set_type_name]."</td><td class='dataTableContent'>".$row[products_model]."</td><td class='dataTableContent'>".$row[products_name_prefix]." ".$row[products_name]." ".$row[products_name_suffix]."</td><td class='dataTableContent'>".$row[products_upc]."</td><td class='dataTableContent'>".$row[products_weight]."</td><td class='dataTableContent'>".$row[products_price]."</td><td class='dataTableContent'>".$row[customerflix_id]."</td>";

			if ((strpos($row[products_image], 'noimage.jpg') !== false)  and ($row[products_image] != '')) $regular = "<img src='../images/".$row[products_image]."' alt='Small' width='43' height='60'/>"; else $regular = '';
			if ((strpos($row[products_image_med], 'noimage.jpg') !== false)  and ($row[products_image_med] != '')) $medium = "<img src='../images/".$row[products_image_med]."' alt='Medium' width='43' height='60'/>"; else $medium = '';
			if ((strpos($row[products_image_lrg], 'noimage.jpg') !== false)  and ($row[products_image_lrg] != '')) $large = "<img src='../images/".$row[products_image_lrg]."' alt='Large' width='43' height='60'/>"; else $large = '';


			echo "<td class='dataTableContent'>".$regular."&nbsp;".$medium."&nbsp;".$large."</td></tr>";	

		}
	}
}
 } else {
echo "<tr><td colspan='5' align='center' class='smalltext'><b>No products found</b></td></tr>";
}
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