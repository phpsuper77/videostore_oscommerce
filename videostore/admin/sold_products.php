<?php
/*
  $Id: stats_customers.php,v 1.31 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

$hide = intval($_GET['hide'] ? $_GET['hide'] : $_POST['hide']);

$add = ($hide==1) ? ' and TO_DAYS(NOW())-TO_DAYS(b.products_date_available) >= 0' : '';




if ($ordersort=='') $ordersort='desc';

if ($orderfield=='model_no') $order = "order by a.products_model ".$ordersort;
if ($orderfield=='order_no') $order = "order by a.orders_id ".$ordersort;
if ($orderfield=='date_purchased') $order = "order by c.date_purchased ".$ordersort;
if ($orderfield=='master') $order = "order by d.master ".$ordersort;
if ($orderfield=='cover') $order = "order by d.cover ".$ordersort;
if ($orderfield=='label') $order = "order by d.label ".$ordersort;
if ($orderfield=='products_name') $order = "order by a.products_name ".$ordersort;
if ($orderfield=='real_quantity') $order = "order by real_quantity ".$ordersort;
if ($orderfield=='total') $order = "order by b.products_ordered ".$ordersort;

if ($_SESSION['ordersort']=='asc') $_SESSION['ordersort'] = 'desc'; else $_SESSION['ordersort']='asc';
if ($order=='') $order = "order by a.products_model";

if ($_POST['action']=='save'){

for ($i=0, $n=sizeof($HTTP_POST_VARS['prepared']); $i<$n; $i++){
	$part = explode("_", $HTTP_POST_VARS['prepared'][$i]);
	$sql_query = "update orders_products set products_prepared=1 where orders_id=".$part[0]." and products_id=".$part[1];
	//echo $sql_query."<br/>";
	tep_db_query($sql_query);
}

$i=0;
	for ($i=0, $n=sizeof($HTTP_POST_VARS['products_id']); $i<$n; $i++) {
		$sql_query = "update products set products_quantity='".$HTTP_POST_VARS['on_hand'][$i]."' where products_id=".$HTTP_POST_VARS['products_id'][$i];
		tep_db_query($sql_query);
	}

	echo "<script>window.location.href='sold_products.php?hide=".$hide."'</script>";

}
?>
<script>
	function CheckUncheck(val, total){
	if (document.getElementById(val).checked==true) flag = true; else flag = false;
		for(i=0;i<total;i++)
			document.getElementById(val+'_'+i).checked = flag;
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
            <td class="pageHeading">ON-DEMAND PRODUCTS PREPARATION TOOL</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?
  $query_raw = "SELECT b.products_quantity as real_quantity, b.products_ordered, d.master, d.cover, d.label, c.date_purchased, a. * , b.products_distribution FROM (((orders_products a) LEFT  JOIN products b ON a.products_id = b.products_id ) LEFT JOIN orders c ON c.orders_id = a.orders_id ) LEFT JOIN products_to_vendors d on d.products_id = a.products_id WHERE a.products_prepared=0 AND b.products_distribution=1 ".$add." group by products_id ".$order;
  $query = tep_db_query($query_raw);
  $total = tep_db_num_rows($query);
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<form action="sold_products.php" method="post">
	<input type="hidden" name="action" value="save" />
	<input type="hidden" name="hide" value="<?=$hide?>" />
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	      <tr><td colspan="3" style="font-size:12px;">Total found: <b><?=$total?></b></td><td colspan="8" style="padding-bottom:3px;" align="right"><input style="width:120px;" type="submit" value="Save" />&nbsp;&nbsp;<input style="width:120px;" type="button" value="<?=($hide)?'Show':'Hide'?> Pre-orders" onClick="location.href='sold_products?hide=<?=($hide)?'0':'1'?>'" /></td></trr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">Item No</td>
                <td class="dataTableHeadingContent"><a href="sold_products.php?orderfield=model_no&ordersort=<?=$_SESSION['ordersort']?>">Model No</a></td>
                <td class="dataTableHeadingContent" align="center">Orders</td>
                <td class="dataTableHeadingContent"><a href="sold_products.php?orderfield=master&ordersort=<?=$_SESSION['ordersort']?>">Master</a></td>
                <td class="dataTableHeadingContent"><a href="sold_products.php?orderfield=cover&ordersort=<?=$_SESSION['ordersort']?>">Cover</a></td>
                <td class="dataTableHeadingContent"><a href="sold_products.php?orderfield=label&ordersort=<?=$_SESSION['ordersort']?>">Label</a></td>
                <td class="dataTableHeadingContent"><a href="sold_products.php?orderfield=products_name&ordersort=<?=$_SESSION['ordersort']?>">Title</a></td>
                <td class="dataTableHeadingContent">Total Number</td>
                <td class="dataTableHeadingContent" align="center"><a href="sold_products.php?orderfield=real_quantity&ordersort=<?=$_SESSION['ordersort']?>">On Hand</a></td>
                <td class="dataTableHeadingContent">Is Prepared?</td>
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
if (($products[master]==1) and ($products[cover]==1) and ($products[label]==1))
	$bg = '#DBF7E2'; else $bg = '';
//#9CE5AF
?>
              <tr class="dataTableRow" style="background:<?=$bg?>;">
                <td class="dataTableContent"><?php echo $rows; ?>.</td>
                <td class="dataTableContent"><?=$products['products_model']?></td>
                <td class="dataTableContent">
		<table border="0" cellspacing="0" cellpadding="3" style="border: 1px solid black;">
		<tr class="smalltext"><td width="50">id</td><td width="80">status</td><td width="100">date</td><td width="50">Qty</td><td width="50">Prep?</td><td>Shipped?</td><td>Location</td></tr>
		<?
		$i=0;
		$order_query = tep_db_query("SELECT d.products_warehouse_location, c.orders_status_name, b.date_purchased, a.orders_id, a.products_id, a.date_shipped_checkbox, a.products_quantity FROM ((orders_products a, products d) LEFT JOIN orders b ON a.orders_id = b.orders_id ) LEFT JOIN orders_status c ON b.orders_status = c.orders_status_id WHERE products_prepared =0 AND a.products_id =".$products[products_id]." and d.products_id=".$products[products_id]."");
	  	while ($orders = tep_db_fetch_array($order_query)){
		$star = '';
		if (intval($orders['date_shipped_checkbox'])==1) $star = "*";
		echo '<tr><Td><a target="_new" href="orders.php?oID='.$orders[orders_id].'&action=edit">'.$orders[orders_id].'</a></td><td class="smalltext">'.$orders[orders_status_name].'</td><td class="smalltext">'.$orders[date_purchased].'</td><td class="smalltext">'.$orders['products_quantity'].'</td><td><input type="checkbox" id="'.$products['products_id'].'_'.$i.'" name="prepared[]" value="'.$orders[orders_id].'_'.$products['products_id'].'" /></td><td>'.$star.'</td><td class="smalltext">'.$orders[products_warehouse_location].'</td></tr>';
		if ($star=='') $total_ordered_products = $total_ordered_products+$orders['products_quantity'];
		$i++;
		}
		?>
		</table>		
		</td>
                <td class="dataTableContent" align="center"><?=$products[master]?></td>
                <td class="dataTableContent" align="center"><?=$products[cover]?></td>
                <td class="dataTableContent" align="center"><?=$products[label]?></td>
                <td class="dataTableContent"><?=$products['products_name']?></td>
                <td class="dataTableContent" align="center"><?=$total_ordered_products?>
		</td>
                <td class="dataTableContent" align="center"><input style="width:50px;" type="text" name="on_hand[]" value="<?=$products['real_quantity']?>"/></td>
                <td class="dataTableContent" align="right"><input onClick="CheckUncheck('<?=$products[products_id]?>','<?=$i?>')" type="checkbox" id="<?=$products[products_id]?>" value="<?=$products[orders_id]?>"/></td>
              </tr>
<?php
$grand_total = $grand_total + $total_ordered_products;
  }
?>

<tr><td colspan="6" style="font-size:12px;">Total found: <b><?=$total?></b></td><td align="right" class="smalltext">Printed Total: </td><td class="smalltext" align="center"><b><?=$grand_total?></b></td><td colspan="2" style="padding-top:3px;" align="right"><input  style="width:80px;" type="submit" value="Save" />&nbsp;&nbsp;<input style="width:120px;" type="button" value="<?=($hide)?'Show':'Hide'?> Pre-orders" onClick="location.href='sold_products?hide=<?=($hide)?'0':'1'?>'" /></td></tr>
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
