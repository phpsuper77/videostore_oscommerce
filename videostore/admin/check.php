<?
  require('includes/application_top.php');
$prod = tep_db_query("select a.orders_id,a.customers_company, b.products_id, b.products_quantity from orders a left join orders_products b on (a.orders_id=b.orders_id) where a.orders_id=126004 or a.orders_id=125797");

while ($row = tep_db_fetch_array($prod)){
	echo $row[orders_id]."----".$row[customers_company]."-----".$row[products_quantity]."------".$row[products_id]."<br/>";
	echo "update products set products_ordered = products_ordered - " . $row[products_quantity] . ", products_quantity = products_quantity + " . $row[products_quantity]." where products_id = '" . $row[products_id] . "'<br/>";
    tep_db_query("update products set products_ordered = products_ordered - " . $row[products_quantity] . ", products_quantity = products_quantity + " . $row[products_quantity]." where products_id = '" . $row[products_id] . "'");

    tep_db_query("DELETE from orders where orders_id = '" . $row[orders_id] . "'");
    tep_db_query("DELETE from orders_products where orders_id = '" . $row[orders_id] . "'");
    tep_db_query("DELETE from orders_total where orders_id = '" . $row[orders_id] . "'");
}
?>