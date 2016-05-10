<?

  require('includes/application_top.php');

$sql_query = tep_db_query("select * from orders_products a where a.products_price!=a.final_price");

while ($row = tep_db_fetch_array($sql_query))
	echo $row[orders_id]."-------".$row[products_price]."---".$row[final_price]."<br/>";

?>