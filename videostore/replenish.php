<?

$abs_url = '/home/terrae2/public_html/';
//$abs_url = '/home/testserver/domains/domain.com/public_html/';
require($abs_url.'includes/configure.php');
require($abs_url.'includes/functions/database.php');
require($abs_url.'includes/functions/general.php');
tep_db_connect() or die('Unable to connect to database server!');

$sql_query = "SELECT * from vendors where sale_email_address!=''";
$vendors_query = tep_db_query($sql_query);
while($vendors = tep_db_fetch_array($vendors_query)){
	$counter = 0;
	$sql = "SELECT b.vendors_item_number, d.products_name, d.products_name_prefix, d.products_name_suffix, c.products_quantity, b.replenish, b.products_id, c.products_id FROM `vendors` a LEFT JOIN products_to_vendors b ON ( a.vendors_id = b.vendors_id ) LEFT JOIN products c ON ( b.products_id = c.products_id ) LEFT JOIN products_description d ON (c.products_id = d.products_id) WHERE a.vendors_id =".$vendors[vendors_id]." AND b.replenish != '' AND b.replenish != '0' AND b.vendors_product_payment_type!='3' AND c.products_status!=0 AND c.products_quantity < b.replenish";
	$products_query	= tep_db_query($sql);
	$counter = tep_db_num_rows($products_query);
	$letter = 'This is an automated email to let you know that products you have with TravelVideoStore.com have reached the quantity on hand level that you have requested to be notified.';
	$letter .= '<table width="500"><tr><th>ITEM NUMBER</th><th>INVENTORY</th><th>TITLE</th></tr>';
	while ($products = tep_db_fetch_array($products_query)){
		$letter .= "<tr><td>".$products[vendors_item_number]."</td><td align='center'>".$products[products_quantity]."</td><td>".$products[products_name_prefix]." ".$products[products_name]." ".$products[products_name_suffix]."</td></tr>";
	}
		$letter .= "</table><br><br>";
		$letter .= "Supplier Support<br>TravelVIdeoStore.com<br>5420 Boran Dr<br>Tampa, FL 33610<br>suppliersupport@travelvideostore.com";

	if ($counter>0){
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: TravelVideoStore.com Supplier Support <suppliersupport@travelvideostore.com>\r\n";
		$headers .= "To: ".$vendors[vendors_name]." <".$vendors[sale_email_address].">\r\n";
		//$headers .= "To: 480 Digital <donwyatt@travelvideostore.com>\r\n";
		mail($vendors[sale_email_address], 'TravelVideoStore.com Low Inventory Notice', $letter, $headers);
	}

}

?>