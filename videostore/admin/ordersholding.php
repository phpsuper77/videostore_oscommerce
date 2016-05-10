<?php
/*
  $Id: orders_holding.php,v 1.1 2004-05-30 Nick Weisser, http://ganes.ch

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003-2004 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
	if ($delete) {
    tep_db_query("delete from " . TABLE_HOLDING_ORDERS . " where orders_id = '" . $delete . "'");
    tep_db_query("delete from " . TABLE_HOLDING_ORDERS_PRODUCTS . " where orders_id = '" . $delete . "'");
    tep_db_query("delete from " . TABLE_HOLDING_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . $delete . "'");
    tep_db_query("delete from " . TABLE_HOLDING_ORDERS_TOTAL . " where orders_id = '" . $delete . "'");
  }

$s=30;
if (!isset($_GET['ctr'])) $ctr=0; else $ctr=$_GET['ctr'];
$st = $ctr*$s;
$limit="limit $st, $s";
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript">
 function really(){
    if(confirm("Really Delete This Order In Holding?")){
         return true;
    } else {
    	return false;
    }
 }
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
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
    <td width="100%" valign="top"><table width="100%"  cellpadding="5">
      <tr>
        <td class="main"><p class="pageHeading">Copies of Orders in Orders Holding...<br><font size="2">In case your customer fails to return after Paypal or 2Checkout...</font></p>
          <p>These orders may or may not have been completed!  This module logs all orders before the customer is passed to payment processing. The process is seamless and doesn't interfere with the regular checkout process.</p>
          <p>Please note that EVERY TIME a customer returns to the Order Confirmation Page, the order is stored in holding!  Some orders may be saved several times!  Use the last one of any such set of orders!</p>
          <p>You can view or delete the orders with a click of the appropriate icon!</p>
          </td></tr>
      <tr>
        <td align="center"><table border=0 cellpadding="2" width="100%" class="smallText">
<?
		  $total_holding_orders = tep_db_query("select orders_id, customers_id, customers_name, customers_email_address, customers_telephone, payment_method, date_purchased from " . TABLE_HOLDING_ORDERS . " order by date_purchased desc");
		  $total = tep_db_num_rows($total_holding_orders);
		  $total_pages = ceil(tep_db_num_rows($total_holding_orders)/$s);
		  $current = ceil($total/$s);
?>
<tr>
	<td colspan="3" align="left" style="font-size:12px;">Displaying <?if ($st==0) echo '<B>1</B>'; else echo '<b>'.$st.'</b>';?> to <b><?=$st+30?></b> (of <b><?=$total?></b>)</TD>
	<td colspan="6" align="right" style="font-size:12px;">Result Pages:
	<?
if ($ctr>0){
	$prev = $ctr-1;
	$prev_10 = $ctr-10;
	if ($prev_10<0) $prev_10=0;
	echo "<a class='pageResults' style='text-decoration:underline;color:BLUE;font-size:12px;' href='ordersholding.php?ctr=".$prev."'>[<< Previous]</A>";
	echo " <a class='pageResults' style='color:BLUE;font-size:12px;' href='ordersholding.php?ctr=".$prev_10."'>...</A> ";
}

	if ($ctr+10>$total_pages) $general = ceil($total_pages); else $general=ceil($ctr+10);
		for ($i=$ctr;$i<$general;$i++){
		$k=$i+1;
	if ($ctr!=$i)
		echo "<a class='pageResults' style='text-decoration:underline;color:BLUE;font-size:12px;' href='ordersholding.php?ctr=".$i."'>".$k."</A> ";
	else
		echo "<b>".$k."</b> ";
	}
if ($ctr<$total_pages){
	$next = $ctr+1;
	$next_10 = ceil($ctr+10);
	if ($next_10<$total_pages) echo " <a class='pageResults' style='color:BLUE;font-size:12px;' href='ordersholding.php?ctr=".$next_10."'>...</A> ";

	echo "<a class='pageResults' style='text-decoration:underline;color:BLUE;font-size:12px;' href='ordersholding.php?ctr=".$next."'>[Next >>]</A>";
}
	?>
	</TD>
</tr>

          <tr class="dataTableHeadingRow">
		  <th>Customer</th>
		  <th>Country</th>
		  <th>E-mail</th>
		  <th>Telephone</th>
		  <th>Order Date & Time</th>
		  <th>Total Order</th>
		  <th>Payment Method</th>
		  <th>Checkout successful</th>
		  <th>Actions</th>
		  </tr>
		    <?php
		  $holding_orders = tep_db_query("select orders_id, customers_id, customers_name, customers_email_address, customers_country, customers_telephone, payment_method, date_purchased, orders_status from " . TABLE_HOLDING_ORDERS . " order by date_purchased desc ".$limit);

		  while ($row = mysql_fetch_array ($holding_orders)) {
		  $_total=tep_db_query("SELECT text FROM ".TABLE_HOLDING_ORDERS_TOTAL ." WHERE orders_id = '".$row['orders_id']."' AND class = 'ot_total'");
		  $thistotal= mysql_fetch_array ($_total);
		  $thisone=$thistotal[text];

		  $checkout_success = tep_db_query("select date_purchased from " . TABLE_ORDERS . " where customers_id = '" . $row['customers_id'] . "'");
		  mysql_num_rows ($checkout_success) > 0 ? $flag = 'yes' : $flag = 'no';
		  $success = mysql_fetch_array ($checkout_success);
if ($row['orders_status']==1) $_flag='Yes'; else $_flag='No';
		  ?>
		    <tr<?php if ($flag == 'no') echo ' class="dataTableRowSelected"'?>;>
		     <td><?php echo $row['customers_name']; ?></td>
		     <td><?php echo $row['customers_country']; ?></td>
		     <td><?php echo $row['customers_email_address'];?></td>
		     <td><?php echo $row['customers_telephone']; ?></td>	
		     <td>1<?php echo $row['date_purchased']; ?>1</td>	
		     <td align="right"><?php echo $thisone; ?></td>			 
		     <td><?php echo $row['payment_method']; ?></td>	
		     <td align="center"><?php echo $_flag; ?></td>	
		     <td align="center"><a href="ordersheld.php?oID=<? echo $row['orders_id']; ?>">View</a>  <a href="ordersholding.php?delete=<? echo $row['orders_id']; ?>" onclick="return really();">Delete</a></td>			 			 
		    </tr>
		    <?php } ?>
<tr>
	<td colspan="3" align="left" style="font-size:12px;">Displaying <?if ($st==0) echo '<B>1</B>'; else echo '<b>'.$st.'</b>';?> to <b><?=$st+30?></b> (of <b><?=$total?></b>)</TD>
	<td colspan="6" align="right" style="font-size:12px;">Result Pages:
	<?
if ($ctr>0){
	$prev = $ctr-1;
	$prev_10 = $ctr-10;
	if ($prev_10<0) $prev_10=0;
	echo "<a class='pageResults' style='text-decoration:underline;color:BLUE;font-size:12px;' href='ordersholding.php?ctr=".$prev."'>[<< Previous]</A>";
	echo " <a class='pageResults' style='color:BLUE;font-size:12px;' href='ordersholding.php?ctr=".$prev_10."'>...</A> ";
}

	if ($ctr+10>$total_pages) $general = ceil($total_pages); else $general=ceil($ctr+10);
		for ($i=$ctr;$i<$general;$i++){
		$k=$i+1;
	if ($ctr!=$i)
		echo "<a class='pageResults' style='text-decoration:underline;color:BLUE;font-size:12px;' href='ordersholding.php?ctr=".$i."'>".$k."</A> ";
	else
		echo "<b>".$k."</b> ";
	}
if ($ctr<$total_pages){
	$next = $ctr+1;
	$next_10 = ceil($ctr+10);
	if ($next_10<$total_pages) echo " <a class='pageResults' style='color:BLUE;font-size:12px;' href='ordersholding.php?ctr=".$next_10."'>...</A> ";

	echo "<a class='pageResults' style='text-decoration:underline;color:BLUE;font-size:12px;' href='ordersholding.php?ctr=".$next."'>[Next >>]</A>";
}
	?>
	</TD>
</tr>
	      </table></td></tr>
    </table>    </td>
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
