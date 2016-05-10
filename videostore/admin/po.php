<?
/*
  require('includes/application_top.php');

if ($_GET[action]=='save'){
	$products1 = tep_db_query("select orders_id, purchase_order_number from orders where purchase_order_number like '058%' and length(purchase_order_number)<13 order by orders_id desc limit 400");	
	while ($row1 = tep_db_fetch_array($products1)){
		$orders_id = $row1[orders_id];
//, customers_address_format_id=2, delivery_address_format_id=2, billing_address_format_id=2
		$sql_query = "update orders set purchase_order_number='".$po[$orders_id]."' where orders_id='".$orders_id."'";
		tep_db_query($sql_query);
		//echo $sql_query."<br/>";
	}
	echo "<script>window.location.href='po.php';</script>";
}


$products = tep_db_query("select orders_id, purchase_order_number from orders where purchase_order_number like '058%' and length(purchase_order_number)<13  order by purchase_order_number  desc limit 900");
echo "Total: ".tep_db_num_rows($products)."<br/><form action='po.php?action=save' method='post'>";
while($row = tep_db_fetch_array($products)) {
	echo $row[orders_id]."-------<input type='text' name='po[$row[orders_id]]' value='".$row[purchase_order_number]."' /><br/>";
}
echo "<input type='submit' value='Save' /></form>";
*/
?>