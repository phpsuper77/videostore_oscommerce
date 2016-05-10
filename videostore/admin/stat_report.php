<?php
/*
  $Id: currencies.php,v 1.49 2003/06/29 22:50:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $year = $_GET['year']?$_GET['year']:date("Y");
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Day/Monthly Consignment/Royalty Report</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
	<form action="stat_report.php" method="get" name="f1">
        <td align="right" class="smalltext">
		Select Year: 
		<select name="year" onChange='document.f1.submit();'>
		<?
			for($i=2003;$i<date("Y")+1; $i++){
			if ($year==$i) $sel = 'selected'; else $sel = '';
			echo "<option value='".$i."' ".$sel.">".$i."</option>";
			}
		?>
		</select>
	</td>
	</form>
      </tr>
      <tr>
	<td>
<? if ($_GET[action]==''){ ?>
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<?
			if ($year==date("Y")) $last_month = date("n")+1; else $last_month=13;
			for ($i=1;$i<$last_month;$i++){
			if (strlen($i)==1) $index = "0".$i; else $index = $i;
		?>
		<tr><td class="smalltext"><u><b><? echo date("F", mktime(0, 0, 1, $index, 1, $year)); ?></b></u></td></tr>
<?
	$c_total = tep_db_fetch_array(tep_db_query("select sum(op.products_item_cost*op.products_quantity) as total from orders_products op LEFT JOIN orders o on o.orders_id=op.orders_id where op.products_sale_type=2 and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));;
	$r_total = tep_db_fetch_array(tep_db_query("select sum(op.products_item_cost*op.products_quantity) as total from orders_products op LEFT JOIN orders o on o.orders_id=op.orders_id where op.products_sale_type=3 and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));
	//$s_total = tep_db_fetch_array(tep_db_query("select sum(op.final_price*op.products_quantity) as total from orders_products op LEFT JOIN orders o on o.orders_id=op.orders_id where DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));
	$s_total = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));

?>
		<tr><td align="" class="smalltext"><b>Total Consignment: <?=$currencies->format($c_total[total],true,'USD','1.000000')?></b>&nbsp;&nbsp;&nbsp;<b>Total Royalty: <?=$currencies->format($r_total[total],true,'USD','1.000000')?></b>&nbsp;&nbsp;&nbsp;<b>Total Sales: <?=$currencies->format($s_total[total],true,'USD','1.000000')?></b> <a href='stat_report.php?action=details&year=<?=$year?>&month=<?=$index?>'>See Details</a></td></tr>
		<tr><td><hr color="black" style='height: 1px;' /></td></tr>
		<?
			$gc_total = $gc_total+$c_total[total];
			$gr_total = $gr_total+$r_total[total];
			$gs_total = $gs_total+$s_total[total];
			}
		?>
		</table>
	</td>
      </tr>
      <tr><td>Grand Consignment Total: <?=$currencies->format($gc_total,true,'USD','1.000000')?><br/>Grand Royalty Total: <?=$currencies->format($gr_total,true,'USD','1.000000')?><br/>Grand Sale Total: <?=$currencies->format($gs_total,true,'USD','1.000000')?></td></tr>
    </table>
<? } 

if ($_GET[action]=='details'){
?>
		<tr><td class="smalltext"><u><b><? echo date("F", mktime(0, 0, 1, $month, 1, $year)); ?></b></u></td></tr>	
		<tr>
			<td>
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td align="center" bgcolor="#f1f1f1"><table cellspacing="0" cellpadding="0" border="0" width="100%"><tr align="center"><td width='5%'>&nbsp;</td><td width='31%' class="smalltext"><b>Consignment</b></td><td width='31%' class="smalltext"><b>Royalty</b></td><td width='31%' class="smalltext"><b>Sales</b></td></tr></table></td>
						<td align="center" bgcolor="#f1f1f1"><table cellspacing="0" cellpadding="0" border="0" width="100%"><tr align="center"><td width='5%'>&nbsp;</td><td width='31%' class="smalltext"><b>Consignment</b></td><td width='31%' class="smalltext"><b>Royalty</b></td><td width='31%' class="smalltext"><b>Sales</b></td></tr></table></td>
					</tr>
					<tr>				
						<td width="50%" valign="top">
							<table cellspacing="0" cellpadding="0" border="1" width="100%">
						<?
						for($p=1;$p<date("t", mktime(0, 0, 1, $month, 1, $year))+1;$p++){
						?>
						<tr>
							<td class='smalltext' width="5%"><?=$p?></td>
							<td class='smalltext' width="31%" align="center">
							<?
							if (strlen($p)==1) $d_index = "0".$p; else $d_index=$p;
							$result = tep_db_fetch_array(tep_db_query("select sum(op.products_item_cost*op.products_quantity) as total from orders_products op LEFT JOIN orders o on o.orders_id=op.orders_id where op.products_sale_type=2 and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true', 'USD', '1.000000')."&nbsp;<a href='stat_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=2'>Details</a>"; else echo "&nbsp;";
							?>
							</td>
							<td class='smalltext' width="31%" align="center">
							<?
							$result = tep_db_fetch_array(tep_db_query("select sum(op.products_item_cost*op.products_quantity) as total from orders_products op LEFT JOIN orders o on o.orders_id=op.orders_id where op.products_sale_type=3 and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true','USD','1.000000')."&nbsp;<a href='stat_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=3'>Details</a>"; else echo "&nbsp;";
							?>
							</td>
							<td class='smalltext' width="47%" align="center">
							<?
							$result = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true', 'USD','1.000000')."&nbsp;<a href='stat_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=all'>Details</a>"; else echo "&nbsp;";
							?>
							</td>
						</tr>
						<?
						if(($p%15==0) && ($p<30)) echo "</table></td><td width='50%' valign='top'><table cellspacing='0' cellpadding='0' border='1' width='100%'>";
							}
	$c_total = tep_db_fetch_array(tep_db_query("select sum(op.products_item_cost*op.products_quantity) as total from orders_products op LEFT JOIN orders o on o.orders_id=op.orders_id where op.products_sale_type=2 and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$month."'"));
	$r_total = tep_db_fetch_array(tep_db_query("select sum(op.products_item_cost*op.products_quantity) as total from orders_products op LEFT JOIN orders o on o.orders_id=op.orders_id where op.products_sale_type=3 and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$month."'"));
	$s_total = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$month."'"));

						?>
						</table></td>
					</tr>
				</table>
			</td>
		</tr>
      <tr><td>Grand Consignment Total: <?=$currencies->format($c_total[total],true,'USD','1.000000')?><br/>Grand Royalty Total: <?=$currencies->format($r_total[total],true,'USD','1.000000')?><br/>Grand Sale Total: <?=$currencies->format($s_total[total],true,'USD','1.000000')?></td></tr>
      <tr><td align="center"><a href='stat_report.php?year=<?=$year?>'>&laquo;&nbsp;Back</a></td></tr>
<?
}

if ($_GET[action]=='day_detail'){
	if (intval($type)!=0) $condition = "op.products_sale_type=".$type." and";

	$products = tep_db_query("select * from orders_products op LEFT JOIN orders o on o.orders_id=op.orders_id where ".$condition." DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."' order by date_purchased");
?>
<h3>
<?
if ($type==2) echo "Consignment";
if ($type==3) echo "Royalty";
if ($type=='all') echo "Sale";
echo " Report on ".$year."-".$month."-".$day;
?></h3>
<table width="100%" cellspacing="0" cellpadding="5" border="1">
	<tr class='smalltext' bgcolor="#f1f1f1" style="font-weight:bold">
		<td>Date Purchased</td>
		<td>Order Number</td>
		<td>Products Name</td>
		<td>Price</td>
		<td>Qty</td>
		<td>Total</td>
		<td>Due Sale</td>
		<td>Vendor Name</td>
	</tr>
<?
while ($row = tep_db_fetch_array($products)){
	$vname = tep_db_fetch_array(tep_db_query("select v.vendors_name as name from products_to_vendors pv left join vendors v on (pv.vendors_id=v.vendors_id) where pv.products_id=".$row[products_id].""));
echo "<tr class='smalltext'><td>".$row[date_purchased]."</td><td><a href='orders.php?oID=".$row[orders_id]."&action=edit'>".$row[orders_id]."</a></td><td>".$row[products_name]."</td><td>".$currencies->format($row[final_price], 'true','USD', '1.000000')."</td><td>".$row[products_quantity]."</td><td>".($currencies->format($row[products_quantity]*$row[final_price], 'true','USD','1.000000'))."</td><td>".($currencies->format($row[products_item_cost]*$row[products_quantity], 'true','USD', '1.000000'))."</td><td>".$vname[name]."</td></tr>";
if (intval($type)!=0) $tot = $tot + ($row[products_item_cost]*$row[products_quantity]);
}
?>
<tr><td colspan="11" class="smalltext"><b>
<?
if ($type==2) echo "Consignment";
if ($type==3) echo "Royalty";
if ($type=='all') echo "Sale";

if (intval($type)==0) {
	$ttl = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."'"));
	$tot = $ttl[total];
}
?>
 Total: <?=$currencies->format($tot,true,'USD','1.000000')?></b></td></tr>
<tr><td colspan="11" align="center"><a href="stat_report.php?action=details&year=<?=$year?>&month=<?=$month?>">&laquo;&nbsp;Back</a></td></tr>
</table>
<?
}
?>
</td>
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
