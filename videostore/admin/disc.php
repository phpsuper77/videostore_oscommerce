<?  require('includes/application_top.php'); ?>

<table border="1" width="400">
	<tr><td>Order ID</td><td>Total</td><td>Subtotal</td><td>Discount</td></tr>
<?
$order = tep_db_query('select * from orders where year(date_purchased)=2007 order by date_purchased desc');

while ($row = tep_db_fetch_array($order)) {
	$sql = tep_db_query('select * from orders_total where orders_id='.$row[orders_id]);

	$total = 0;
	$subtotal = 0;

	while($inner_row = tep_db_fetch_array($sql)) {
		if ($inner_row['class'] == 'ot_total')      $total = $inner_row[value];
		if ($inner_row['class'] == 'ot_subtotal')   $subtotal = $inner_row[value];
		if ($inner_row['class'] == 'ot_qty_discount')   $dst = $inner_row[value];
	}
	
	if ($total<$subtotal) {
?>
	<tr><td><a href="orders.php?oID=<?=$row[orders_id]?>&action=edit" target="_new"><?=$row[orders_id]?></a></td><td><?=$total?></td><td><?=$subtotal?></td><td><?=$dst?></td></tr>
<?
	}
}
?>

</table>
