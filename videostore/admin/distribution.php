<?php
/*
  $Id: distributors.php,v 1.55 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

if ($_GET['field']!='') $field = $_GET['field'];
if ($_GET['direction']!='') $direction = $_GET['direction'];
if ($_GET['direction']!='') $direction_root = $_GET['direction'];
if ($_POST['direction']!='') $direction_root = $_POST['direction'];


if ($field=='model') $order = "order by a.products_model ".$direction;
if ($field=='qty') $order = "order by a.products_quantity ".$direction;
if ($field=='vendor') $order = "order by c.vendors_name ".$direction;
if ($field=='p_type') $order = "order by b.vendors_product_payment_type ".$direction;
if ($field=='s_type') $order = "order by b.vendors_product_sale_type ".$direction;
if ($field=='consignment') $order = "order by b.vendors_consignment ".$direction;
if ($field=='consignment_per') $order = "order by b.vendors_consignment_percentage ".$direction;
if ($field=='royalty') $order = "order by b.vendors_royalty ".$direction;
if ($field=='royalty_per') $order = "order by b.vendors_royalty_percentage ".$direction;
if ($field=='upc') $order = "order by a.products_upc ".$direction;
if ($field=='asin') $order = "order by a.products_asin ".$direction;
if ($field=='flix') $order = "order by a.customerflix_id ".$direction;

if ($direction=='asc') $direction = 'desc'; else $direction='asc';
if ($order=='') $order = "order by a.products_model";

if ($_GET['editable']=='1') $editable = 1;
if ($_GET['editable']=='0') $editable = 0;
if ($_GET['editable']=='') $editable = $_SESSION['editable'];


$_SESSION['editable'] = $editable;	

if ($_GET['action']=='search'){
	$sstring = $_POST['sstring'];
	$field = $_POST['field'];
	$direction = $_POST['direction'];
}

if ($_GET['action']=='save'){
	foreach($_POST['products_model'] as $name=>$value){
		$id = $name;
		$sql = "update products set products_model='".$_POST['products_model'][$id]."', products_quantity='".$_POST['products_quantity'][$id]."', products_upc='".$_POST['products_upc'][$id]."',  products_asin='".$_POST['products_asin'][$id]."',  products_upc='".$_POST['customerflix_id'][$id]."'  where products_id='".$id."'";
		//echo $sql."<br/>";
		tep_db_query($sql);
		$sql = "update products_to_vendors set vendors_id='".$_POST['vendors_id'][$id]."', vendors_product_payment_type='".$_POST['vendors_payment_type'][$id]."', vendors_product_sale_type='".$_POST['vendors_sale_type'][$id]."', vendors_consignment='".$_POST['vendors_consignment'][$id]."', vendors_consignment_percentage='".$_POST['vendors_consignment_percentage'][$id]."', vendors_royalty='".$_POST['vendors_royalty'][$id]."', vendors_royalty_percentage='".$_POST['vendors_royalty_percentage'][$id]."' where products_id='".$id."'";
		tep_db_query($sql);
		//echo $sql."<br/>";
		$addon = '';
		if ($_POST['sstring']!='') $addon .='&sstring='.$_POST['sstring'];
		if ($_POST['ctr']!='') $addon .='&ctr='.$_POST['ctr'];
		if ($_POST['direction']!='') $addon .='&direction='.$_POST['direction'];
		if ($_POST['field']!='') $addon .='&field='.$_POST['field'];
		echo "<script>window.location.href='distribution.php?".$addon."';</script>";
	}
}

if ($_GET['action']==''){
	$sql_query = tep_db_query("SELECT a.products_id as products_id, b.products_id as vendor_products_id FROM products a LEFT JOIN products_to_vendors b ON ( a.products_id = b.products_id ) WHERE a.products_distribution =1 and b.products_id is NULL");

while($row = tep_db_fetch_array($sql_query)){
		$sql = "insert into products_to_vendors set products_id='".$row['products_id']."',  vendors_id='', vendors_item_number='', vendors_item_cost='', vendors_product_payment_type='', vendors_product_sale_type='', vendors_consignment='', vendors_consignment_percentage='', vendors_royalty='', vendors_royalty_percentage='', master='', cover='', label='', web='', amazon='', mediazone='', google='', Akimbo='', distribution_end_date='', replenish=''";
		tep_db_query($sql);
	}

}

$vendors = tep_db_query("SELECT vendors_id, vendors_name FROM  `vendors` where vendors_name is not null and vendors_name!='' order by vendors_name");
$all_vendors[] = array('id'=>'', 'text'=>'Select Vendor');
while ($vendor = tep_db_fetch_array($vendors)) {
         $all_vendors[] = array('id' => $vendor['vendors_id'], 'text' => $vendor['vendors_name']);
}

$ptype[] = array('id'=>'', 'text'=>'Select Payment');
$ptype[] = array('id'=>'1', 'text'=>'Direct Purchase');
$ptype[] = array('id'=>'2', 'text'=>'Consignment');
$ptype[] = array('id'=>'3', 'text'=>'Royalty');

$stype[] = array('id'=>'', 'text'=>'Select Sale');
$stype[] = array('id'=>'1', 'text'=>'Video on Demand');
$stype[] = array('id'=>'2', 'text'=>'DVD on Demand');
$stype[] = array('id'=>'3', 'text'=>'In Stock');

$btype[] = array('id'=>'1', 'text'=>'N/A');
$btype[] = array('id'=>'2', 'text'=>'Fixed');
$btype[] = array('id'=>'3', 'text'=>'Variable');

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
            <td class="pageHeading">DISTRIBUTION ITEMS</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
		<table width="100%">
<form action="distribution.php?action=search" method="POST">	
<tr><td colspan="12" align="right">Search by products model:&nbsp;<input type="text" name="sstring" value="<?=$sstring?>" style="width:150px;" />&nbsp;<input type="submit" value="Search" />&nbsp;<input type="button" value="Reset" onclick="window.location.href='distribution.php'" /></td></tr>
</form>	
<form action="distribution.php?action=save" method="POST">	
	<input type="hidden" name="ctr" value="<?=$ctr?>" />
	<input type="hidden" name="sstring" value="<?=$sstring?>" />
	<input type="hidden" name="field" value="<?=$field?>" />
	<input type="hidden" name="direction" value="<?=$direction_root?>" />
<?
$addurl = '';
if($sstring!='') $addurl.='sstring='.$sstring;
if($ctr!='') $addurl.='&ctr='.$ctr;
if($field!='') $addurl.='&field='.$field;
if($direction!='') $addurl.='&direction='.$direction_root;
?>
<tr><td colspan="12"><input type="submit" value="Save" />&nbsp;&nbsp;&nbsp;<input type="button" value="<?if ($editable!=1) echo 'Show editable fields'; else echo 'Show list'?>" onclick="window.location.href='distribution.php?<?=$addurl?>&editable=<?if ($editable==1) echo '0'; else echo '1'?>'" /></td></tr>
			<tr class="dataTableHeadingRow">
				<td style="padding:3px;" class="dataTableHeadingContent"><a href="distribution.php?<?=$addurl?>&field=model&direction=<?=$direction?>">Products Model</a></td>
				<td class="dataTableHeadingContent"><a href="distribution.php?<?=$addurl?>&field=qty&direction=<?=$direction?>">Qty on Hand</a></td>
				<td class="dataTableHeadingContent"><a href="distribution.php?<?=$addurl?>&field=vendor&direction=<?=$direction?>">Vendor Name</a></td>
				<td class="dataTableHeadingContent"><a href="distribution.php?<?=$addurl?>&field=p_type&direction=<?=$direction?>">Payment Type</a></td>
				<td class="dataTableHeadingContent"><a href="distribution.php?<?=$addurl?>&field=s_type&direction=<?=$direction?>">Sale Type</a></td>
				<td class="dataTableHeadingContent"><a href="distribution.php?<?=$addurl?>&field=consignment&direction=<?=$direction?>">Consignment</a></td>
				<td class="dataTableHeadingContent"><a href="distribution.php?<?=$addurl?>&field=consignment_per&direction=<?=$direction?>">Consignment Percentage</a></td>
				<td class="dataTableHeadingContent"><a href="distribution.php?<?=$addurl?>&field=royalty&direction=<?=$direction?>">Royalty</a></td>
				<td class="dataTableHeadingContent"><a href="distribution.php?<?=$addurl?>&field=royalty_per&direction=<?=$direction?>">Royalty Percentage</a></td>
				<td class="dataTableHeadingContent"><a href="distribution.php?<?=$addurl?>&field=upc&direction=<?=$direction?>">UPC</a></td>
				<td class="dataTableHeadingContent"><a href="distribution.php?<?=$addurl?>&field=asin&direction=<?=$direction?>">ASIN</a></td>
				<td class="dataTableHeadingContent"><a href="distribution.php?<?=$addurl?>&field=flix&direction=<?=$direction?>">Customflix_id</a></td>
			</tr>
<?
$s = 100;
if (!isset($_GET['ctr'])) $ctr=0; else $ctr=$_GET['ctr'];
$st = $ctr*$s;
$limit="limit $st, $s";

if ($sstring!='') $addon = "and products_model like '%".$sstring."%'";
	$sql_query = tep_db_query("SELECT c.vendors_name, a.products_id, a.products_upc, a.products_asin, a.customerflix_id, a.products_model, a.products_quantity, b.vendors_product_payment_type, b.vendors_product_sale_type, b.vendors_consignment, b.vendors_consignment_percentage, b.vendors_royalty, b.vendors_royalty_percentage, b.vendors_item_cost FROM products a LEFT JOIN products_to_vendors b ON ( a.products_id = b.products_id ) LEFT JOIN vendors c ON ( b.vendors_id = c.vendors_id ) WHERE a.products_distribution=1 ".$addon." ".$order." ".$limit);
	$sql_query_full = tep_db_query("SELECT a.products_id FROM products a LEFT JOIN products_to_vendors b ON ( a.products_id = b.products_id ) LEFT JOIN vendors c ON ( b.vendors_id = c.vendors_id ) WHERE a.products_distribution=1 ".$addon."");

while($row = tep_db_fetch_array($sql_query)){
 if ($editable==1){
	echo "<tr>";
	echo "<td class='smalltext'><input type='text' name='products_model[".$row[products_id]."]' value='".$row['products_model']."' style='width:100px;' /></td>";
	echo "<td class='smalltext'><input type='text' name='products_quantity[".$row[products_id]."]' value='".$row['products_quantity']."' style='width:50px;' /></td>";
	echo "<td class='smalltext'>";
if ($row['vendors_name']==''){
$vendors_id = "vendors_id[".$row[products_id]."]";
	echo tep_draw_pull_down_menu($vendors_id, $all_vendors, $row[vendors_id],'');
}
else
echo $row['vendors_name'];
	echo "</td>";
$payment_type = "vendors_payment_type[".$row[products_id]."]";
	echo "<td class='smalltext'>".tep_draw_pull_down_menu($payment_type, $ptype, $row['vendors_product_payment_type'],'')."</td>";
$sale_type = "vendors_sale_type[".$row[products_id]."]";
	echo "<td class='smalltext'>".tep_draw_pull_down_menu($sale_type, $stype, $row['vendors_product_sale_type'],'')."</td>";
$vendors_consignment = "vendors_consignment[".$row[products_id]."]";
	echo "<td class='smalltext'>".tep_draw_pull_down_menu($vendors_consignment, $btype, $row['vendors_consignment'],'')."</td>";
	echo "<td class='smalltext'><input type='text' name='vendors_consignment_percentage[".$row[products_id]."]' value='".$row['vendors_consignment_percentage']."' style='width:100px;' /></td>";
$vendors_royalty = "vendors_royalty[".$row[products_id]."]";
	echo "<td class='smalltext'>".tep_draw_pull_down_menu($vendors_royalty, $btype, $row['vendors_royalty'],'')."</td>";
	echo "<td class='smalltext'><input type='text' name='vendors_royalty_percentage[".$row[products_id]."]' value='".$row['vendors_royalty_percentage']."' style='width:100px;' /></td>";
	echo "<td class='smalltext'><input type='text' name='products_upc[".$row[products_id]."]' value='".$row['products_upc']."' style='width:100px;' /></td>";
	echo "<td class='smalltext'><input type='text' name='products_asin[".$row[products_id]."]' value='".$row['products_asin']."' style='width:100px;' /></td>";
	echo "<td class='smalltext'><input type='text' name='customerflix_id[".$row[products_id]."]' value='".$row['customerflix_id']."' style='width:100px;' /></td>";
	echo "</tr>";
	}
	else{
	echo "<tr>";
	echo "<td class='smalltext'>".$row['products_model']."</td>";
	echo "<td class='smalltext'>".$row['products_quantity']."</td>";
	echo "<td class='smalltext'>".$row['vendors_name']."</td>";
$p_id = $row['vendors_product_payment_type'];
	echo "<td class='smalltext'>".$ptype[$p_id][text]."</td>";
$s_id = $row['vendors_product_sale_type'];
	echo "<td class='smalltext'>".$stype[$s_id][text]."</td>";
$c_id = $row['vendors_consignment'];
	echo "<td class='smalltext'>".$btype[$c_id][text]."</td>";
	echo "<td class='smalltext'>".$row['vendors_consignment_percentage']."</td>";
$v_id = $row['vendors_royalty'];
	echo "<td class='smalltext'>".$btype[$v_id][text]."</td>";
	echo "<td class='smalltext'>".$row['vendors_royalty_percentage']."</td>";
	echo "<td class='smalltext'>".$row['products_upc']."</td>";
	echo "<td class='smalltext'>".$row['products_asin']."</td>";
	echo "<td class='smalltext'>".$row['customerflix_id']."</td>";
	echo "</tr>";
	}
}
?>
	</table>
	</td>
      </tr>
<tr><td colspan="12"><input type="submit" value="Save" />&nbsp;&nbsp;&nbsp;<input type="button" value="<?if ($editable!=1) echo 'Show editable fields'; else echo 'Show list'?>" onclick="window.location.href='distribution.php?<?=$addurl?>&editable=<?if ($editable==1) echo '0'; else echo '1'?>'" /></td></tr>
	      <tr>
		<td class="smalltext" colspan="8" align="left">Pages: 
	<?
	$per = tep_db_num_rows($sql_query_full)/$s;
	for ($k=1;$k<$per+1;$k++){
		$t=$k-1;
	if ($ctr!=$t)
		echo "<a href='distribution.php?sstring=".$sstring."&field=".$field."&direction=".$direction_root."&ctr=".$t."' style='font-weight:bold;'>".$k."</a>&nbsp;&nbsp;";
		else
		echo "<font style='font-weight:bold; color:red;'>".$k."</font>&nbsp;&nbsp;";
		}
	?>
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
