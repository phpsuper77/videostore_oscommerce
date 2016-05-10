<?php
/*
  $Id: countries.php,v 1.28 2003/06/29 22:50:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

?>

<?

system("/usr/local/bin/php /home/terrae2/public_html/clear_cart.php");

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  include(DIR_WS_CLASSES . 'order.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : $HTTP_POST_ACTION);

if (!empty($action)) {
	$year = $_POST['year'];
	$month = $_POST['month'];
	if ($_POST['day'])   $day = $_POST['day'];
	if ($_POST['hour'])   $hour = $_POST['hour'];
}
else {
	$year = date("Y");
	$month = date("m");
	$day = date("j");
	$hour = date("G");
}

$searchDate = mktime($hour, 0, 1, $month, $day, $year);

$where  = ' where year(created_at)="'.$year.'"';
$where .= ' and month(created_at)="'.$month.'"';
if ($day!='') $where .= ' and dayofmonth(created_at)="'.$day.'"';
if ($hour!='') $where .= ' and hour(created_at)="'.$hour.'"';


	switch($action) {
	case 'search':
	default:
		$sql = 'select * from daily_cart '.$where.' order by created_at desc';
		$rows = tep_db_query($sql);
		$numRows = tep_db_num_rows($rows);
	break;
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">CART ACTIVITY</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
	  <tr>
	<td colspan="2" align="right">
<fieldset>
	<legend>Search</legend>
<form name="search" action="cart_activity.php?action=search" method="post">
	<table>
	<tr><td>Year:</td>
<td>
<select name="year">
<?
for($i=2003; $i<date("Y")+1;$i++) {
if ($i==$year) $sel = 'selected'; else $sel = '';
	echo "<option value=".$i." ".$sel.">".$i."</option>";
}
?>
</select>
			</td>
		</tr>
		<tr>
			<td>Month:</td>
			<td>
<select name="month">
<?
for($i=1; $i<13;$i++) {
if ($i==$month) $sel = 'selected'; else $sel = '';
	echo "<option value=".$i." ".$sel.">".date("F", mktime(0, 0, 1, $i, 1, 2000))."</option>";
}
?>
</select>

			</td>
		</tr>
		<tr>
			<td>Day:</td>
			<td>
<select name="day">
	<option value="">All</option>
<?
for($i=1; $i<32;$i++) {
if ($i==$day) $sel = 'selected'; else $sel = '';
	echo "<option value=".$i." ".$sel.">".$i."</option>";
}
?>
</select>
</td></tr>
		<tr>
			<td>Hours:</td>
			<td>
<select name="hour">
	<option value="">All</option>
<?
for($i=0; $i<24;$i++) {
if ($i==$hour && $hour!='') $sel = 'selected'; else $sel = '';
	echo "<option value=".$i." ".$sel.">".$i."</option>";
}
?>
</select>
</td></tr>

		<tr><td><input type="submit" value="Generate" /></td></tr>
	</table>
</fieldset>
	</td>
</form>
	</tr>
        </table></td>
      </tr>
      <tr>
        <td>
	<table width="100%">
		<tr class="dataTableHeadingRow">
			<td class="dataTableHeadingContent">Customer Name/IP</td>
			<td class="dataTableHeadingContent">Time Last Click</td>
			<td class="dataTableHeadingContent">Purchased/Abandoned (P/A)</td>
			<td class="dataTableHeadingContent">Cart Item Total</td>
			<td class="dataTableHeadingContent">Cart Content</td>
			<td class="dataTableHeadingContent">Previous Orders #</td>
			<td class="dataTableHeadingContent">Previous Orders $</td>
		</tr>
<?
$total_customers = 0;
$total_bought = 0;
$total_abandoned = 0;
$realSession = $_SESSION;
	while ($row = tep_db_fetch_array($rows)) { ?>	
<?
$total_customers++;
unset($session);
unset($order);
if ($row[is_finished] != 0) $order = new order($row[is_finished]);
else {
	session_decode($row[sessvalue]);
	$session = $_SESSION;
}
?>
		<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
			<td class="dataTableContent">
<?php 
if ($row[is_finished] == 0) {
	$customer = tep_db_fetch_array(tep_db_query("select customers_firstname, customers_lastname from customers where customers_id='".$session[customer_id]."'"));
	echo $customer[customers_firstname].' '.$customer[customers_lastname];
}
elseif ($order) {
	echo $order->info[cc_owner];
}
else {
	echo 'No name';
}

echo '<br/>'.$row[IP];
?>
</td>
			<td class="dataTableContent"><?=$row[created_at]?></td>
			<td class="dataTableContent"><?=($row[is_finished]) ? 'Purchased' : 'Abandoned'?></td>
			<td class="dataTableContent">
<?
if ($row[is_finished] == 0) {
$total_price = 0;
$total_value = 0;
foreach($session[cart]->contents as $key=>$value) { 
	$total_value = $total_value + $value[qty];
	$price = tep_db_fetch_array(tep_db_query("select products_price from products where products_id='".$key."'"));
	$total_price = $total_price + floatval($price[products_price]*$value[qty]);
	$total_abandoned = $total_abandoned + $total_price;
	}
echo "Total Qty: " . $total_value."<br/>";
echo "Total Price: " . $currencies->format($total_price, true, 'USD','1.000000')."<br/>";
	}
else {
$order = new order($row[is_finished]);
$order_totals = $order->totals;
  echo "Total Qty: " . sizeof($order->products)."<br/>";
  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
	echo $order_totals[$i]['title'] ." ".$order_totals[$i]['text']."<br/>";	
	if ($order_totals[$i]['class'] == 'ot_total') $total_bought = $total_bought+$order_totals[$i]['value'];
  }                                      

}
?>			

</td>
			<td class="dataTableContent">
	<table>
<?
if ($row[is_finished]!=0) { ?>
<?
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) { ?>
	<tr>
		<td class="dataTableContent"><?=$order->products[$i]['qty']?> X </td>
		<td class="dataTableContent"><?=$order->products[$i]['name']?></td></tr>	
<?  }
}
else {
	if (count($session[cart]->contents) == 0) echo "No items in cart";
	else {  
foreach($session[cart]->contents as $key=>$value) { ?>
		<tr>
			<td class="dataTableContent"><?=$value[qty]?> X </td>
			<td class="dataTableContent">
			<?
			$product = tep_db_fetch_array(tep_db_query("select p.products_price, pd.products_name_prefix, pd.products_name, pd.products_name_suffix from products p left join products_description pd on p.products_id=pd.products_id where p.products_id='".$key."'"));
			echo $product[products_name_prefix]." ".$product[products_name]." ".$product[products_name_suffix];
			?>
			</td>
		</tr>		
<? } ?>
<?
	}
}
?>
	</table>
			</td>
			<td class="dataTableContent">
<?
if ($row[is_finished]!=0) $cc_onwer_id = $order->info[customers_id]; else $cc_onwer_id = $session[customer_id];

if ($cc_onwer_id!='')
$count = tep_db_fetch_array(tep_db_query("select count(*) as cnt from orders where customers_id='".$cc_onwer_id."'"));
else 
$count[cnt] = 0;
?>
<? 
if ($count[cnt]>0) {?>
<a href="orders.php?criteria=8&searchstring=<?=$order->info['cc_owner_id']?>&action=edit"><?=$count[cnt]?></a>
<? } else echo "0"?>
			</td>
			<td class="dataTableContent">
<?php 
if ($cc_onwer_id!='')
	$summ = tep_db_fetch_array(tep_db_query("SELECT sum(ot.value) as cnt FROM orders o LEFT  JOIN orders_total ot ON ot.orders_id = o.orders_id WHERE o.customers_id =  '".$cc_onwer_id."' AND ot.class =  'ot_total'"));
else
	$summ[cnt] = 0;

if ($summ[cnt]>0) { ?>
<a href="orders.php?criteria=8&searchstring=<?=$order->info['customers_id']?>&action=edit"><?=$currencies->format($summ[cnt], true, $order->info['currency'], $order->info['currency_value'])?></a>
<? } else echo '$0.00'?>
</td>
		</tr>
<?
	}
$_SESSION = $realSession;
?>
<tr><td colspan="8" align="left" class="smalltext">Total Users: <?=$total_customers?></td></tr>
<tr><td colspan="8" align="left" class="smalltext">Total Bought: <?=$currencies->format($total_bought, true, 'USD', '1.000000')?></td></tr>
<tr><td colspan="8" align="left" class="smalltext">Total Abondoned: <?=$currencies->format($total_abandoned, true, 'USD', '1.000000')?></td></tr>

	</table>
	</td>
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
