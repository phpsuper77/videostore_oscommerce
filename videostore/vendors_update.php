<?
/*
ini_set("memory_limit","64M");
  include('includes/application_top.php');

$sql = "select * from orders_products where products_sale_type = '' order by orders_products_id desc";
$orders = tep_db_query($sql);
$total = tep_db_num_rows(tep_db_query($sql));
echo "Total: ".$total."<br/>";
while ($rows = tep_db_fetch_array($orders)){
    $sql_query = "SELECT vendors_item_cost, vendors_product_payment_type, vendors_consignment, vendors_consignment_percentage, vendors_royalty , vendors_royalty_percentage FROM products_to_vendors where products_id=".tep_get_prid($rows['products_id']." limit 0,1");
//echo $sql_query."<br>";
    $prod_vendor = tep_db_fetch_array(tep_db_query($sql_query));
	switch($prod_vendor['vendors_product_payment_type']){
	case "1":
		$final_prod_vendor = $prod_vendor['vendors_item_cost'];		
	break;

	case "2":
		if (intval($prod_vendor['vendors_consignment'])==1) $final_prod_vendor = $prod_vendor['vendors_item_cost'];				
		if (intval($prod_vendor['vendors_consignment'])==2) $final_prod_vendor = ($rows['products_price']/100)*$prod_vendor['vendors_consignment_percentage'];			
		if (intval($prod_vendor['vendors_consignment'])==3) $final_prod_vendor = ($rows['final_price']/100)*$prod_vendor['vendors_consignment_percentage'];				
	break;

	case "3":
		if (intval($prod_vendor['vendors_royalty'])==1) $final_prod_vendor = $prod_vendor['vendors_item_cost'];				
		if (intval($prod_vendor['vendors_royalty'])==2) $final_prod_vendor = ($rows['products_price']/100)*$prod_vendor['vendors_royalty_percentage'];				
		if (intval($prod_vendor['vendors_royalty'])==3) $final_prod_vendor = ($rows['final_price']/100)*$prod_vendor['vendors_royalty_percentage'];
	break;
	default: 
		//$final_prod_vendor_end = '0.0000';
		$prod_vendor['vendors_product_payment_type'] = '1';
	break;
	}

//$final_prod_vendor_end = $final_prod_vendor * $rows['products_quantity'];

if ($rows[orders_products_id]!=''){
	$sql_update = "update orders_products set products_sale_type='".$prod_vendor[vendors_product_payment_type]."', products_item_cost='".$final_prod_vendor_end."' where orders_products_id=".$rows[orders_products_id];
echo $sql_update."<br/><hr>";
	//tep_db_query($sql_update);
	}
}
*/
?>