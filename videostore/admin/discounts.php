<?
  require('includes/application_top.php');
?>
<table border="1">
	<tr><th colspan="2">DISCOUNTS</ht></tr>
	<tr><td>REAL VALUE</td><td>GAINED VALUE</td></tr>
<?
$sql_query = "select * from orders_total where class='ot_qty_discount' and value>0 order by value asc";
$rows = tep_db_query($sql_query);

while ($row = tep_db_fetch_array($rows)){
$update_query = "update orders_total set value='-".$row[value]."' where orders_total_id='".$row[orders_total_id]."'";
echo "<tr><td>".$row[value]."</td><td>-".$row[value]."</td><td>".$update_query."</td></tr>";
//tep_db_query($update_query);
}
?>
</table>
<hr/>
<table border="1">
	<tr><th colspan="2">COUPON</ht></tr>
	<tr><td>REAL VALUE</td><td>GAINED VALUE</td></tr>
<?
$sql_query = "select * from orders_total where class='ot_coupon' and value>0 order by value asc";
$rows = tep_db_query($sql_query);

while ($row = tep_db_fetch_array($rows)){
$update_query = "update orders_total set value='-".$row[value]."' where orders_total_id='".$row[orders_total_id]."'";
echo "<tr><td>".$row[value]."</td><td>-".$row[value]."</td><td>".$update_query."</td></tr>";
//tep_db_query($update_query);
}
?>
</table>
<hr/>
<table border="1">
	<tr><th colspan="2">GIFT CARD</ht></tr>
	<tr><td>REAL VALUE</td><td>GAINED VALUE</td></tr>
<?
$sql_query = "select * from orders_total where class='ot_gv' and value>0 order by value asc";
$rows = tep_db_query($sql_query);

while ($row = tep_db_fetch_array($rows)){
$update_query = "update orders_total set value='-".$row[value]."' where orders_total_id='".$row[orders_total_id]."'";
echo "<tr><td>".$row[value]."</td><td>-".$row[value]."</td><td>".$update_query."</td></tr>";
//tep_db_query($update_query);
}
?>
</table>
