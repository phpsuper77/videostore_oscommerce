<?php
ob_start();
session_start();

if($_REQUEST['submit']!="true" && $_GET["ordersort"]=="" && $_GET["orderfield"]==""){
    $_SESSION['vendors_id']=""; 
	$_SESSION['type']="";
	$_SESSION['submit']="";
	$_SESSION['range']="";
	$_SESSION['year']="";
	$_SESSION['action']="";
}elseif($_REQUEST['submit']=="true")
{
	$_SESSION['vendors_id']=$_POST['vendors_id'];
	$_SESSION['type']=$_POST['type'];
	$_SESSION['submit']=$_POST['submit'];
	$_SESSION['range']=$_POST['range'];
	$_SESSION['year']=$_POST['year'];
	$_SESSION['action']=$_POST['action'];
	
}elseif($_GET["ctr"]!="" || ($_GET["ordersort"]!="" && $_GET["orderfield"]!="")){
  if($_POST['vendors_id']=="" && $_SESSION['vendors_id']!=""){
	  $_POST['vendors_id']=$_SESSION['vendors_id'];
  }
  if($_POST['type']=="" && $_SESSION['type']!=""){
	   $_POST['type']=$_SESSION['type'];
  }
  if($_POST['submit']=="" && $_SESSION['submit']!=""){
	   $_POST['submit']=$_SESSION['submit'];
  }
  if($_POST['range']=="" && $_SESSION['range']!=""){
	   $_POST['range']=$_SESSION['range'];
  }
  if($_POST['year']=="" && $_SESSION['year']!=""){
	   $_POST['year']=$_SESSION['year'];
  }
  if($_POST['action']=="" && $_SESSION['action']!=""){
	   $_POST['action']=$_SESSION['action'];
  }

}

#print_r($_SESSION);
#print_r($_POST);
if ($ordersort=='') $ordersort='desc';
$order = "order by o.date_purchased desc ";
if ($orderfield=='products_id') $order = "order by pd.products_id ".$ordersort;
if ($orderfield=='products_name') $order = "order by pd.products_name ".$ordersort;

/*
  $Id: vendors.php,v 1.00 2003/06/26 23:21:48 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2003 Fernando Borcel
  Copyright (c) 2004 Joseph Passavanti

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

    require(DIR_WS_CLASSES . 'currencies.php');
    $currencies = new currencies();
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
<?php require(DIR_WS_INCLUDES . 'column_left.php');?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">VENDORS REPORTS</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<script>
	function checkFields(){
		obj = document.f1.type;
		if (obj[obj.selectedIndex].value=='') {alert('Type field is empty!\n'); return false;}
		return true;
}
</script>
      <tr>
        <td colspan="8" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" align="right">
			   <table border="0" width="100%" cellspacing="0" cellpadding="2">
				<form action="vendor_reports.php" method="post" onSubmit="return checkFields();" name="f1">
					<input type="hidden" name="action" value="show" />
				<tr>
					<td class="smallText" align="right">Choose year: 
					<select name="year" style="width:196px;">
	<option value="All">All</option>
<?
if ($_POST[year]=="") $_POST[year] = date("Y");
	for ($i=2003;$i<date("Y")+1; $i++){
		$sel='';
		if ($_POST[year]==$i) $sel = 'selected';		
		echo "<option value='".$i."' ".$sel." >".$i."</option>";
}
?>
					</select></td>
				  </tr> 
				<tr>
					<td class="smallText" align="right">Choose date range: <select name="range" style="width:196px;"><option value="1" <? if ($_POST[range]==1) echo'selected';?>>All</option><option value="2" <? if ($_POST[range]==2) echo'selected';?>>January, 1 - June, 30</option><option value="3" <? if ($_POST[range]==3) echo'selected';?>>July, 1 - December, 31</option></select></td>
				  </tr>
				
				
				<tr>
					<td class="smallText" align="right">Choose type: <select name="type" style="width:196px;"><option value="">Select Type</option><option value="2" <?if ($_POST[type]==2) echo "selected";?>>Consignment</option>
					
					<option value="3" <?if ($_SESSION['type'])

					echo "selected";?>>Royalty</option></select></td>
				  </tr>
				<tr>
					<td class="smallText" align="right">Choose vendor: 
			<select name="vendors_id" style="width:195px;">
				<!--<option value="All">All</option>-->
				<?
	  $vendors = tep_db_query("SELECT c.vendors_id, c.vendors_name FROM orders_products a JOIN products_to_vendors b ON ( a.products_id = b.products_id ) JOIN vendors c ON ( b.vendors_id = c.vendors_id ) WHERE a.products_sale_type =2 OR a.products_sale_type =3 GROUP  BY vendors_id ORDER  BY vendors_name");
	        while ($row = tep_db_fetch_array($vendors)){
			$sel='';
		
			if ($_SESSION[vendors_id]==$row[vendors_id]) $sel = 'selected';
			echo "<option value='".$row[vendors_id]."' ".$sel.">".$row[vendors_name]."</option>";
		}
	
				?>
			</select>
					</td>
				  </tr>
				<tr>
					<td class="smallText" align="right">
			<input type="submit" name="button" value="&nbsp;&nbsp;Generate&nbsp;&nbsp;" />
				<input type="hidden" name="submit" value="true"/>
				</tr>

					</form>
				</table>
</table><table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
			  <td colspan=7>
<?php
  				if (isset($_POST['vendors_id']) || isset($_GET['vendors_id'])) {
				if (isset($_POST['vendors_id'])) $id = $_POST['vendors_id'];
				else if (isset($_GET['vendors_id'])) $id = $_GET['vendors_id'];
?>
<?php
if ($_POST["vendors_id"]!='All') {
	$vendors_condition_1 = ' and  pv.vendors_id = '.$_POST["vendors_id"];
	$vendors_condition_2 = ' and vendors_id = '.$_POST["vendors_id"];
	$vendors_condition_3 = ' vendor_id = '.$_POST["vendors_id"];
	}
	else {
	$vendors_condition_1 = '';
	$vendors_condition_2 = '';
	$vendors_condition_3 = '';
	}
	         
if ($_POST[year]!='All'){
if ($_POST[range]==2){
	$start = date("Y-m-d", mktime(0,0,1,12,31,$_POST[year]-1));
	$end = date("Y-m-d", mktime(23,59,59,7,1,$_POST[year]));
}
elseif ($_POST[range]==3){
	$start = date("Y-m-d", mktime(0,0,1,6,30,$_POST[year]));
	$end = date("Y-m-d", mktime(23,59,59,1,1,$_POST[year]+1));	
}
else{
	$start = date("Y-m-d", mktime(0,0,1,12,31,$_POST[year]-1));
	$end = date("Y-m-d", mktime(23,59,59,1,1,$_POST[year]+1));
}

	$startat="and TO_DAYS(o.date_purchased)>TO_DAYS('".$start."') and TO_DAYS(o.date_purchased)<TO_DAYS('".$end."')";
	$payment_startat="and TO_DAYS(date)>TO_DAYS('".$start."') and TO_DAYS(date)<TO_DAYS('".$end."')";
	$consignment_startat="and TO_DAYS(receive_date)>TO_DAYS('".$start."') and TO_DAYS(receive_date)<TO_DAYS('".$end."')";

}
					$prod_total_price = 0;
					$total_vendor_cost_price = 0;
					$total_qty_sold=0;
					$i=1;
					
		
$s=100;
//$s=1000;
if (!isset($_GET['ctr'])) $ctr=0; else $ctr=$_GET['ctr'];
$st = $ctr*$s;
$limit="limit $st, $s";			
 $sql_query = "select op.orders_id, pv.products_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_item_cost, op.products_sale_type, o.date_purchased, pd.products_name, pd.products_name_prefix, pd.products_name_suffix from ". TABLE_ORDERS_PRODUCTS ." op, ". TABLE_PRODUCTS_TO_VENDORS ." pv, ". TABLE_ORDERS ." o, ".TABLE_PRODUCTS_DESCRIPTION." pd where o.orders_id = op.orders_id and pd.products_id = pv.products_id and op.products_id = pv.products_id and op.products_sale_type=".$_POST['type'].$vendors_condition_1." ".$startat." ".$order." ". $limit;
  //echo $sql_query;
  
  $whos_online_query_full = tep_db_query("select op.orders_id, pv.products_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_item_cost, op.products_sale_type, o.date_purchased, pd.products_name, pd.products_name_prefix, pd.products_name_suffix from ". TABLE_ORDERS_PRODUCTS ." op, ". TABLE_PRODUCTS_TO_VENDORS ." pv, ". TABLE_ORDERS ." o, ".TABLE_PRODUCTS_DESCRIPTION." pd where o.orders_id = op.orders_id and pd.products_id = pv.products_id and op.products_id = pv.products_id and op.products_sale_type=".$_POST['type'].$vendors_condition_1." ".$startat." ".$order);
  
//echo htmlentities($sql_query);
				  	$products_sel = tep_db_query($sql_query);
				  	$total_found = tep_db_num_rows($products_sel);
?>

				<tr>
					<td colspan=6>
					
					<script>
	function getPDF(vendor_id, range, year, type){
	window.open("generate_vendor_report.php?vendor_id="+vendor_id+"&range="+range+"&year="+year+"&type="+type+"");
}
</script>
					<?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td><td colspan='2' align='right'><?if ($total_found!=0) echo '<input onclick="return getPDF(\''.$_SESSION['vendors_id'].'\',\''.$_POST[range].'\',\''.$_POST[year].'\', '.$_SESSION['type'].');" type="button" value="&nbsp;&nbsp;Generate PDF&nbsp;&nbsp;" />';?></td></tr>
				</tr>
					<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent" align="left" width="6%">
				<a href="vendor_reports.php?orderfield=products_id&ordersort=asc&ctr=<?=$_GET['ctr']?>">Order Id&nbsp;</a></td>
				<td class="dataTableHeadingContent" align="left" width="40%"><a href="vendor_reports.php?orderfield=products_name&ordersort=asc&ctr=<?=$_GET['ctr']?>">Products Name&nbsp;</a></td>
						<td class="dataTableHeadingContent" align="left" width="10%"><a href="vendor_reports.php?orderfield=products_id&ordersort=asc&ctr=<?=$_GET['ctr']?>">Q-ty Sold&nbsp;</a></td>
						<td class='dataTableHeadingContent' align='left' width='10%'><a href="vendor_reports.php?orderfield=products_id&ordersort=asc&ctr=<?=$_GET['ctr']?>">Vendor Item Cost&nbsp;</a></td>
						<td class="dataTableHeadingContent" align="left" width="5%"><a href="vendor_reports.php?orderfield=products_id&ordersort=asc&ctr=<?=$_GET['ctr']?>">Vendor Sale</a></td>
						<td class="dataTableHeadingContent" align="left" width="9%"><a href="vendor_reports.php?orderfield=products_id&ordersort=asc&ctr=<?=$_GET['ctr']?>">Selling Price</a></td>
						<td class="dataTableHeadingContent" align="left" width="15%"><a href="vendor_reports.php?orderfield=products_id&ordersort=asc&ctr=<?=$_GET['ctr']?>">Date Purchased</a></td>
						<td class="dataTableHeadingContent" align="left" width="10%"><a href="vendor_reports.php?orderfield=products_id&ordersort=asc&ctr=<?=$_GET['ctr']?>">Selling Type</a></td>
					</tr>

<?
if ($total_found!=0){
//echo tep_db_num_rows($products_sel);
                    while($products_fetch = tep_db_fetch_array($whos_online_query_full))
					{	
					   if ($products_fetch['orders_id']!=$prev) $order=$order+1;
					   $prev = $products_fetch['orders_id'];
					   $prod_ordered_price = $products_fetch['products_quantity'] * $products_fetch['final_price'];
						$prod_total_price += $prod_ordered_price;
						
						$tot_vc = $products_fetch[products_item_cost]*$products_fetch[products_quantity];
                        $total_vendor_cost_price = $total_vendor_cost_price + $tot_vc;
					}
					
					while($products_fetch = tep_db_fetch_array($products_sel))
					{	$tot_vc=0;
					//if ($products_fetch['orders_id']!=$prev) $order=$order+1;
						$prev = $products_fetch['orders_id'];
						$vendor_det = tep_db_query("select * from ". TABLE_PRODUCTS_TO_VENDORS ." where products_id = ".$products_fetch['products_id'].$vendors_condition_2);
						$vendor_det_rs = tep_db_fetch_array($vendor_det);

						//$prod_ordered_price = $products_fetch['products_quantity'] * $products_fetch['final_price'];
						//$prod_total_price += $prod_ordered_price;
						echo "<tr id=dataTableRow class=dataTableRow onmouseover=rowOverEffect(this) onmouseout=rowOutEffect(this)>";
						echo "<td class=dataTableContent align=left valign=top><a href=".FILENAME_VENDOR_CUSTOMER_PRODUCTS.	"?selected_box=vendors&vendors_id=".$id."&order_id=".$products_fetch['orders_id'].">".$products_fetch['orders_id']."</a></td>";
						echo "<td class=dataTableContent align=left valign=top>".tep_db_prepare_input(tep_output_string($products_fetch['products_name_prefix']))."&nbsp;".trim(tep_db_prepare_input(tep_output_string($products_fetch['products_name'])))."&nbsp;".trim(tep_db_prepare_input(tep_output_string($products_fetch['products_name_suffix'])))."</td>";
						echo "<td class=dataTableContent align=left valign=top>".$products_fetch['products_quantity']."</td>";
						echo "<td class=dataTableContent align=left valign=top><div id='item_cost_".$products_fetch[products_id]."_".$products_fetch[orders_id]."'>".$currencies->format($products_fetch[products_item_cost],true,'USD','1.000000')."</div><div id='item_cost_".$products_fetch[products_id]."_".$products_fetch[orders_id]."_a' style='display:none;'><input style='width:90px;' type='text' name='vendor_item_cost[$products_fetch[products_id]][$products_fetch[orders_id]]' value='".$products_fetch[products_item_cost]."' /></div></td>";
						
						echo "<td class=dataTableContent align=left valign=top>".$currencies->format($tot_vc,true,'USD','1.000000')."</td>";
						echo "<td class=dataTableContent align=left valign=top><div id='final_price_".$products_fetch[products_id]."_".$products_fetch[orders_id]."'>".$currencies->format($products_fetch['final_price'],true,'USD','1.000000')."</div><div id='final_price_".$products_fetch[products_id]."_".$products_fetch[orders_id]."_a' style='display:none;'><input style='width:90px;' type='text' name='vendor_final_price[$products_fetch[products_id]][$products_fetch[orders_id]]' value='".$products_fetch[final_price]."' /></div></td>";
						echo "<td class=dataTableContent align=left valign=top>".tep_datetime_short($products_fetch['date_purchased'])."</td>";
if ($products_fetch['products_sale_type']==1) $typ="Direct Purchase";
elseif ($products_fetch['products_sale_type']==2) $typ="Consignment";
else $typ = "Royalty";
						echo "<td class=dataTableContent align=left valign=top><div id='sale_type_".$products_fetch[products_id]."_".$products_fetch[orders_id]."'>".$typ."</div><div style='display:none;' id='sale_type_".$products_fetch[products_id]."_".$products_fetch[orders_id]."_a'><select id='vendor_selling_type[".$products_fetch[products_id]."][".$products_fetch[orders_id]."]' name='vendor_selling_type[".$products_fetch[products_id]."][".$products_fetch[orders_id]."]'><option value='1'>Direct Purchase</option><option value='2'>Cosignment</option><option value='3'>Royalty</option></select></div></td>";
					}	echo "</form></tr>";
					echo "<tr><td colspan=6>".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td><td colspan='2' align='right'>";
					if ($total_found!=0) echo '<input  onclick="return getPDF(\''.$_POST[vendors_id].'\',\''.$_POST[range].'\',\''.$_POST[year].'\', '.$_POST[type].');" type="button" value="&nbsp;&nbsp;Generate PDF&nbsp;&nbsp;" />';
					echo "</td></tr>";
				
				
if ($_POST[type]==2){
echo "<tr><td colspan=8 align=right class='pageHeading'><br/><br/>RECEIVED PRODUCTS</td></tr>";

$s=10;
//$s=1000;
if (!isset($_GET['ctr'])) $ctr=0; else $ctr=$_GET['ctr'];
$st = $ctr*$s;
$limit=" limit $st, $s";

$consignment = tep_db_query("SELECT b. * , c.products_model, d.products_name FROM  `products_to_vendors` a JOIN vendor_purchase_order_details b ON ( a.products_id = b.product_id ) LEFT  JOIN products c ON ( b.product_id = c.products_id ) LEFT  JOIN products_description d ON ( c.products_id = d.products_id ) WHERE a.vendors_id =".$_POST[vendors_id]." AND b.item_status =  'received".$limit);

$whos_online_query_full = tep_db_query("SELECT b. * , c.products_model, d.products_name FROM  `products_to_vendors` a JOIN vendor_purchase_order_details b ON ( a.products_id = b.product_id ) LEFT  JOIN products c ON ( b.product_id = c.products_id ) LEFT  JOIN products_description d ON ( c.products_id = d.products_id ) WHERE a.vendors_id =".$_POST[vendors_id]." AND b.item_status =  'received");}?>




	 <script language="javascript" type="text/javascript">
	 function nextPages(val){ 
		 window.location.href = "http://www.travelvideostore.com/admin/"+val;
	 }
	 </script>
		
					<?
					echo "<tr><td colspan=3 align=left class=main><b>Total Orders: ".tep_draw_separator('pixel_trans.gif', 45, '10').$order."</td>";?>
					
					<td colspan="3" style="padding-top:10px;"><b>Total number of items: <?php echo $num=mysql_num_rows($whos_online_query_full); ?></td>
					</tr>;
					<?
					echo "<tr><td colspan=3 align=left class=main><b>Total Vendor Sale: ".tep_draw_separator('pixel_trans.gif', 10, '10').$currencies->format($total_vendor_cost_price,true,'USD','1.000000')."</b></td>";
					?>
					<td class="smalltext" colspan="3" align="left">Pages:
		<select name="pagination" id="pagination" onChange="nextPages(this.options[this.selectedIndex].value);">
        <option value="">Go to page...</option>
       <? 
			$per24 = tep_db_num_rows($whos_online_query_full)/$s;
			for ($k24=1;$k24<$per24+1;$k24++){
				$t24=$k24-1;
				if($_GET["orderfield"]!="" && $_GET["ordersort"]!="" ){
				     echo '<option value="vendor_reports.php?orderfield='.$_GET["orderfield"].'&ordersort='.$_GET["ordersort"].'&ctr='.$t24.'">'.$k24.'</option>'; 	
			    }else{
					echo '<option value="vendor_reports.php?ctr='.$t24.'">'.$k24.'</option>'; 	
				}
			}
	 ?>
 
    </select>
</td></tr><?
					echo "<tr><td colspan=8 align=left class=main><b>Total Sale: ".tep_draw_separator('pixel_trans.gif', 60, '10').$currencies->format($prod_total_price,true,'USD','1.000000')."</b></td></tr>";
					
					
}
else
echo "<tr><td colspan=8 align=center class='main'><b>No products found</b></td></tr>";

echo "<tr><td><br/></td></tr>";
echo "<tr><td colspan=8 align=right class='pageHeading'>VENDOR PAYMENTS</td></tr>";

if ($vendors_condition_3!='' and $payment_startat!='') $where = " where ".$vendors_condition_3." ".$payment_startat;
if ($vendors_condition_3!='' and $payment_startat=='') $where = "where ".$vendors_condition_3;
if ($vendors_condition_3=='' and $payment_startat!='') $where = "where ".$payment_startat;
if ($_POST[type]==2) $typ = "Consignment";
if ($_POST[type]==3) $typ = "Royalty";

$where .= " and type='".$typ."'";

$sql_query = "select * from vendor_payments ".$where;
?>
<tr><td colspan="8" align="right">
<table width="60%">
					    <tr class="dataTableHeadingRow" bgcolor="#C9C9C9">
						<td class="dataTableHeadingContent" align="left" width="15%">Payment Id&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="15%">Payment Date&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="15%">Payment Method&nbsp;</td>
						<td class='dataTableHeadingContent' align='left' width='15%'>Payment Type&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="15%">Payment</td>
						<td class="dataTableHeadingContent" align="left" width="15%">Payment Status</td>
					</tr>
<?
	$payment = tep_db_query($sql_query);
	$found = tep_db_num_rows($payment);
if ($found!=0){
	while ($row = tep_db_fetch_array($payment)){
	echo "<tr id=dataTableRow class=dataTableRow onmouseover=rowOverEffect(this) onmouseout=rowOutEffect(this)>";
		echo "<td class=dataTableContent>".$row[id]."</td>";
		echo "<td class=dataTableContent>".$row[date]."</td>";
		echo "<td class=dataTableContent>".$row[method]."</td>";
		echo "<td class=dataTableContent>".$row[type]."</td>";
		echo "<td class=dataTableContent>".$currencies->format($row[payment],true,'USD','1.000000')."</td>";
		echo "<td class=dataTableContent>".$row[status]."</td>";
	echo "</tr>";
	}
}
	else{
	echo "<tr><td colspan='6' align='center' class='main'><b>No payments found</b></td></tr>";
}
	echo "</td></tr></table>";

?>		</tr>	              	






 
<tr><td colspan="8" align="right">
	
</tr>
<tr><td><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td></tr>	              	<tr><td><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td></tr>


            </table><?php
  }
?></td>
          </tr>

	        </table></td>
      </tr>
    </table> 
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