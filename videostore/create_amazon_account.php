<?
/*
ini_set("memory_limit","64M");		

require('includes/application_top.php');

$cust = tep_db_query("select * from orders order by customers_id");
while ($row = tep_db_fetch_array($cust)){
	$quantity = tep_db_num_rows(tep_db_query("select * from customers where customers_email_address = '".mysql_escape_string($row[customers_email_address])."'"));
	if ($quantity==0){
$k++;

	$part = explode(" ", $row[delivery_name]);
	if (count($part)>2){
		$fname = $part[0]." ".$part[1];
		for ($i=2;$i<count($part);$i++) $lname .= $part[$i]." ";
	}
	else{
		$fname = $part[0];
		$lname = $part[1];
	}

		$sql_query = "insert into customers set purchased_without_account=0, customers_firstname='".mysql_escape_string($fname)."', customers_lastname='".mysql_escape_string($lname)."', customers_email_address='".mysql_escape_string($row[customers_email_address])."', customers_password='".tep_encrypt_password('travel')."', customers_type=0, customers_allow_purchase_order_entry='true'";
echo $sql_query."<br/>";
		//tep_db_query($sql_query);
		$customer_id = tep_db_insert_id();
		$country_id = tep_db_fetch_array(tep_db_query("select countries_id from countries where countries_name='".mysql_escape_string($row[customers_country])."' limit 1"));
		$zone_id = tep_db_fetch_array(tep_db_query("select zone_id from zones where zone_name='".mysql_escape_string($row[customers_state])."' limit 1"));
		$sql_query = "insert into address_book set customers_id='".$customer_id."', entry_company='".mysql_escape_string($row[delivery_company])."', entry_firstname='".mysql_escape_string($fname)."', entry_lastname='".mysql_escape_string($lname)."', entry_street_address='".mysql_escape_string($row[delivery_street_address])."', entry_postcode='".mysql_escape_string($row[delivery_postcode])."', entry_city='".mysql_escape_string($row[delivery_city])."', entry_state='".mysql_escape_string($row[delivery_state])."', entry_country_id='".$country_id[countries_id]."', entry_zone_id='".$zone_id[zone_id]."'";
echo $sql_query."<br/>";
		//tep_db_query($sql_query);
		$address_book_id = tep_db_insert_id();
		$sql_query = "update customers set customers_default_address_id='".$address_book_id."' where customers_id='".$customer_id."'";
echo $sql_query."<br/>";
		//tep_db_query($sql_query);

		$sql_query = "update orders set customers_id='".$customer_id."' where orders_id='".$row[orders_id]."'";
echo $sql_query."<br/>";
		//tep_db_query($sql_query);


	echo "Account creation for ".$row[customers_email_address]." and ".$row[customers_id]." with order id as ".$row[orders_id]."  has been successfully finished... Customer new ID is: ".$customer_id."<br/><hr>";
	}
}
echo "<br>Total: ".$k;
*/
?>