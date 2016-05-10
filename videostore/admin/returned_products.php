<?php
/*
  $Id: stats_customers.php,v 1.31 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

$filter = $_GET[filter]?$_GET[filter]:$_POST[filter];

if ($ordersort=='') $ordersort='desc';

if ($orderfield=='model_no') $order = "order by a.products_model ".$ordersort;
if ($orderfield=='order_no') $order = "order by a.orders_id ".$ordersort;
if ($orderfield=='date_purchased') $order = "order by c.date_purchased ".$ordersort;
if ($orderfield=='products_name') $order = "order by a.products_name ".$ordersort;
if ($orderfield=='real_quantity') $order = "order by real_quantity ".$ordersort;
if ($orderfield=='total') $order = "order by b.products_ordered ".$ordersort;

if ($_SESSION['ordersort']=='asc') $_SESSION['ordersort'] = 'desc'; else $_SESSION['ordersort']='asc';
if ($order=='') $order = "order by a.products_model";

if ($_POST['action']=='save'){


if (intval($filter)!=0) {
	$fil1 = " LEFT JOIN orders os on (os.orders_id=a.orders_id)";
	$fil2 = " and b.products_distribution!=1 and (os.orders_status='4' or os.orders_status='5' or os.orders_status='8' or os.orders_status='21')";
	$fil3 = " and (b.orders_status=4 or b.orders_status=5  or b.orders_status=8 or b.orders_status=21)";
}
	else {
	$fil1 = "";
	$fil2 = "";
	$fil3 = "";
}


$query_raw = "SELECT b.products_quantity as real_quantity, b.products_ordered, a. * , b.products_distribution FROM orders_products a LEFT  JOIN products b ON ( a.products_id = b.products_id ) LEFT JOIN products_to_vendors d on (d.products_id = a.products_id) ".$fil1." WHERE (a.date_shipped_checkbox!=1 or a.date_shipped_checkbox is NULL) ".$fil2." group by products_id ".$order;
$query = tep_db_query($query_raw);

  while ($products = tep_db_fetch_array($query)) {
                $order_query = tep_db_query("SELECT c.orders_status_name, b.date_purchased, a.orders_id, a.products_id, a.products_quantity, a.item_ordered_date, a.camefrom, a.ordered FROM orders_products a LEFT JOIN orders b ON ( a.orders_id = b.orders_id ) LEFT JOIN orders_status c ON (b.orders_status = c.orders_status_id ) WHERE (a.date_shipped_checkbox!=1 or a.date_shipped_checkbox is NULL) ".$fil3." AND a.products_id =".$products[products_id]);
                while ($orders = tep_db_fetch_array($order_query)){

        $sql_query = "update orders_products set item_ordered_date='', camefrom='', ordered='' where orders_id=".$orders[orders_id]." and products_id=".$products[products_id];
	//echo $sql_query."<br/>";
        tep_db_query($sql_query);
        
        }

}
//echo "<br/><hr/>";
for ($i=0, $n=sizeof($HTTP_POST_VARS['prepared']); $i<$n; $i++){
	$part = explode ("_",$HTTP_POST_VARS['prepared'][$i]);
	$sql_query = "update orders_products set item_ordered_date='".$HTTP_POST_VARS['dateordered'][$part[0]][$part[1]]."', camefrom='".$HTTP_POST_VARS['camefrom'][$part[0]][$part[1]]."', ordered=1 where orders_id=".$part[0]." and products_id=".$part[1];
	//echo $sql_query."<br/>";
	tep_db_query($sql_query);
}

//echo "<br/><hr/>";
$i=0;
	for ($i=0, $n=sizeof($HTTP_POST_VARS['products_id']); $i<$n; $i++) {
		$sql_query = "update products set products_quantity='".$HTTP_POST_VARS['on_hand'][$i]."' where products_id=".$HTTP_POST_VARS['products_id'][$i];
		//echo $sql_query."<br/>";
		tep_db_query($sql_query);
	}

	echo "<script>window.location.href='returned_products.php?filter=".$filter."'</script>";

}
?>
<script>
	function getDate(){
		var currentTime = new Date()
		var month = currentTime.getMonth() + 1
		var day = currentTime.getDate()
		var year = currentTime.getFullYear()
		return year + "-" + month + "-" + day;	
	}

	function CheckUncheck(val, total){
	if (document.getElementById(val).checked==true) flag = true; else flag = false;
		for(i=0;i<total;i++){
			document.getElementById(val+'_'+i).checked = flag;
			if (flag==true)
				document.getElementById("date_"+val+'_'+i).value = getDate();
				else{
				document.getElementById("date_"+val+'_'+i).value = "";
				document.getElementById("came_"+val+'_'+i).selectedIndex = 0;
			}
		}
	}
        
	function changeDate(obj, index, product_id){
		if (obj==true)
			document.getElementById("date_"+product_id+'_'+index).value = getDate();
			else{
			document.getElementById("date_"+product_id+'_'+index).value = "";
			document.getElementById("came_"+product_id+'_'+index).selectedIndex = 0;
		}
	}
</script>
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
            <td class="pageHeading">BACKORDERED ITEMS REPORT</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?

if (intval($filter)!=0) {
	$fil1 = " LEFT JOIN orders os on (os.orders_id=a.orders_id)";
	$fil2 = " and b.products_distribution!=1 and (os.orders_status='4' or os.orders_status='5')";
	$fil3 = " and (b.orders_status=4 or b.orders_status=5)";

	$fil2 = " and b.products_distribution!=1 and (os.orders_status='4' or os.orders_status='5' or os.orders_status='8' or os.orders_status='21')";
	$fil3 = " and (b.orders_status=4 or b.orders_status=5  or b.orders_status=8 or b.orders_status=21)";

}
	else {
	$fil1 = "";
	$fil2 = "";
	$fil3 = "";
}

  $query_raw = "SELECT b.products_quantity as real_quantity, b.products_upc, b.products_ordered, a. * , b.products_distribution FROM orders_products a LEFT  JOIN products b ON ( a.products_id = b.products_id ) LEFT JOIN products_to_vendors d on (d.products_id = a.products_id) ".$fil1." WHERE (a.date_shipped_checkbox!=1 or a.date_shipped_checkbox is NULL) ".$fil2." group by products_id ".$order;
//$query_raw = "SELECT b.products_quantity as real_quantity, b.products_upc, b.products_ordered, a. * , b.products_distribution FROM orders_products a LEFT  JOIN products b ON ( a.products_id = b.products_id ) LEFT JOIN products_to_vendors d on (d.products_id = a.products_id) WHERE (a.date_shipped_checkbox!=1 or a.date_shipped_checkbox is NULL) and b.products_distribution!=1  group by products_id ".$order;
//echo "<br><hr>".$query_raw;
//and b.products_distribution!=1
  $query = tep_db_query($query_raw);
  $total = tep_db_num_rows($query);
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<form action="returned_products.php" method="post">
	<input type="hidden" name="action" value="save" />
	<input type="hidden" name="filter" value="<?=$filter?>" />
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	      <tr><td colspan="3" style="font-size:12px;">Total found: <b><?=$total?></b></td><td colspan="8" style="padding-bottom:3px;" align="right"><input type="button" onclick="location.href='returned_products.php?filter=<? if ($filter==1) echo '0'; else echo '1'; ?>'" value="Show <? if ($filter==1) echo 'All'; else echo 'Backordered and not distribution';?> items" />&nbsp;&nbsp;&nbsp;<input style="width:80px;" type="submit" value="Save" /></td></trr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">Item No</td>
                <td class="dataTableHeadingContent"><a href="returned_products.php?orderfield=model_no&ordersort=<?=$_SESSION['ordersort']?>">Model No</a></td>
                <td class="dataTableHeadingContent" align="center">Orders</td>
                <td class="dataTableHeadingContent"><a href="returned_products.php?orderfield=products_name&ordersort=<?=$_SESSION['ordersort']?>">Title</a></td>
                <td class="dataTableHeadingContent">UPC</td>
                <td class="dataTableHeadingContent">Total Number</td>
                <td class="dataTableHeadingContent" align="center"><a href="returned_products.php?orderfield=real_quantity&ordersort=<?=$_SESSION['ordersort']?>">On Hand</a></td>
                <td class="dataTableHeadingContent">Change?</td>
              </tr>
<?php
  $rows = 0;
  while ($products = tep_db_fetch_array($query)) {
    $rows++;
$total_ordered_products = 0;
    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
<!-- onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_CUSTOMERS, 'search=' . $customers['customers_lastname'], 'NONSSL'); ?>'-->
		<input type="hidden" name="products_id[]" value="<?=$products[products_id]?>"/>
<?
if (($products[products_distribution]==1)) $bg = '#DBF7E2'; else $bg = '';

?>
              <tr class="dataTableRow" style="background:<?=$bg?>;">
                <td class="dataTableContent"><?php echo $rows; ?>.</td>
                <td class="dataTableContent"><?=$products['products_model']?></td>
                <td class="dataTableContent">
		<table border="0" cellspacing="0" cellpadding="3" width="500" style="border: 1px solid black;">
		<tr class="smalltext"><td width="50">id</td><td width="80">date ordered</td><td align="center" width="50">status</td><td width="80">Item ordered date</td><td align="center" width="15">Qty</td><!--td align="center" width="15">Shipped?</td--><td width="100">From</td><td width="50">Ordered?</td>
		<?
		$i=0;
		$order_query = tep_db_query("SELECT c.orders_status_name, b.date_purchased, a.date_shipped_checkbox, a.orders_id, a.products_id, a.products_quantity, a.item_ordered_date, a.camefrom, a.ordered FROM orders_products a LEFT JOIN orders b ON ( a.orders_id = b.orders_id ) LEFT JOIN orders_status c ON ( b.orders_status = c.orders_status_id ) WHERE (a.date_shipped_checkbox!=1 or a.date_shipped_checkbox is NULL) ".$fil3." AND a.products_id =".$products[products_id]);
//echo "SELECT c.orders_status_name, b.date_purchased, a.date_shipped_checkbox, a.orders_id, a.products_id, a.products_quantity, a.item_ordered_date, a.camefrom, a.ordered FROM orders_products a LEFT JOIN orders b ON ( a.orders_id = b.orders_id ) LEFT JOIN orders_status c ON ( b.orders_status = c.orders_status_id ) WHERE (a.date_shipped_checkbox!=1 or a.date_shipped_checkbox is NULL) AND a.products_id =".$products[products_id];
	  	while ($orders = tep_db_fetch_array($order_query)){
		$star = '';
		if (intval($orders['date_shipped_checkbox'])==1) $star = "*";
		$selected = ''; $droped1='';$droped2='';$droped3='';$droped4='';$droped5='';$droped6='';$droped7='';
		if ($orders[ordered]=='1') $selected = "checked";
		if ($orders[camefrom]=='Baker & Taylor') $droped0 = "selected";
		if ($orders[camefrom]=='Vendor') $droped1 = "selected";
		if ($orders[camefrom]=='Amazon') $droped2 = "selected";
		if ($orders[camefrom]=='Deep Discount') $droped3 = "selected";
		if ($orders[camefrom]=='Ebay') $droped4 = "selected";
		if ($orders[camefrom]=='Half.com') $droped5 = "selected";
		if ($orders[camefrom]=='Overstock') $droped6 = "selected";
		if ($orders[camefrom]=='Other') $droped7 = "selected";
		echo '<tr><Td><a target="_new" href="orders.php?oID='.$orders[orders_id].'&action=edit">'.$orders[orders_id].'</a></td><td class="smalltext">'.$orders[date_purchased].'</td><td class="smalltext" align="center" width="50">'.$orders['orders_status_name'].'</td><td class="smalltext"><input id="date_'.$products['products_id'].'_'.$i.'" type="text" name="dateordered['.$orders[orders_id].']['.$products[products_id].']" style="width:70px;" value="'.$orders['item_ordered_date'].'"/></td><td class="smalltext" align="center">'.$orders['products_quantity'].'</td><td><select id="came_'.$products['products_id'].'_'.$i.'" name="camefrom['.$orders[orders_id].']['.$products[products_id].']"><option value="Baker & Taylor" '.$droped0.'>Baker & Taylor</option><option value="Vendor" '.$droped1.'>Vendor</option><option value="Amazon" '.$droped2.'>Amazon</option><option value="Deep Discount" '.$droped3.'>Deep Discount</option><option value="Ebay" '.$droped4.'>Ebay</option><option value="Half.com" '.$droped5.'>Half.com</option><option value="Overstock" '.$droped6.'>Overstock</option><option value="Other" '.$droped7.'>Other</option></select></td><td><input onclick="changeDate(this.checked,\''.$i.'\',\''.$products[products_id].'\');" type="checkbox" id="'.$products['products_id'].'_'.$i.'" name="prepared[]" value="'.$orders[orders_id].'_'.$products[products_id].'" '.$selected.'/></td></tr>';
		if ($star=='') $total_ordered_products = $total_ordered_products+$orders['products_quantity'];

		$i++;
		}
		?>
		</table>		
		</td>
                <td class="dataTableContent"><?=$products['products_name']?></td>
                <td class="dataTableContent"><?=$products['products_upc']?></td>
                <td class="dataTableContent" align="center"><?=$total_ordered_products?>
		</td>
                <td class="dataTableContent" align="center"><input style="width:50px;" type="text" name="on_hand[]" value="<?=$products['real_quantity']?>"/></td>
                <td class="dataTableContent" align="right"><input onclick="CheckUncheck('<?=$products[products_id]?>','<?=$i?>')" type="checkbox" id="<?=$products[products_id]?>" value="<?=$products[orders_id]?>"/></td>
              </tr>
<?php
$grand_total = $grand_total + $total_ordered_products;
  }
?>

<tr><td colspan="4" style="font-size:12px;">Total found: <b><?=$total?></b></td><td align="right" class="smalltext">Qty Total: </td><td class="smalltext" align="center"><b><?=$grand_total?></b></td><td colspan="2" style="padding-top:3px;" align="right"><input  style="width:80px;" type="submit" value="Save" /></td></tr>
</form>
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
