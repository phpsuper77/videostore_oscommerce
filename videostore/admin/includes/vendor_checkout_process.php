<?
$final_prod_vendor = 0;
$summ_total = 0;
$disc = array();
$new_disc = array();

foreach($_POST['update_products'] as $ordersid => $products_details_clone){
	$summ_total = $summ_total + $products_details_clone[final_price];
}

$ttt = tep_db_query("select * from orders_total where orders_id='".(int)$oID."'");

while($row = tep_db_fetch_array($ttt)){
	if (($row['class']=='ot_coupon') or ($row['class']=='ot_qty_discount')){
		$disc[] = array('value'=>abs($row['value']), 'class'=>$row['class'], 'final'=>'');
	}
}

//mail('x0661t@d-net.kiev.ua', 'test', $summ_total);

if(count($disc)!=0){
foreach ($disc as $row){
	if (intval($summ_total)!=0) $final = round(($row['value']*100/$summ_total), 4);
	$row['final'] = $final;
	$summ_total = $summ_total-$row['value'];
	$new_disc[] = $row;
	}
}


if ($action!='add_product')
	$product_id = $order['products_id'];
else
	$product_id = $_POST['add_product_products_id'];


    $sql_query = "SELECT vendors_item_cost, vendors_product_payment_type, vendors_consignment, vendors_consignment_percentage, vendors_royalty , vendors_royalty_percentage FROM products_to_vendors where products_id=".(int)$product_id." limit 0,1";
//mail('x0661t@d-net.kiev.ua', 'test', $sql_query);
    $prod_vendor = tep_db_fetch_array(tep_db_query($sql_query));

    $sql_query = "select products_price, final_price, products_sale_type from orders_products where orders_id = '" . (int)$oID . "' and orders_products_id = '$orders_products_id'";
    $real_data = tep_db_fetch_array(tep_db_query($sql_query));

    if ($real_data['products_sale_type']=='1')	$final_prod_vendor = $prod_vendor['vendors_item_cost'];

    if ($real_data['products_sale_type']=='2'){
		if (intval($prod_vendor['vendors_consignment'])==1) $final_prod_vendor = $prod_vendor['vendors_item_cost'];				
		if (intval($prod_vendor['vendors_consignment'])==2) $final_prod_vendor = ($real_data[products_price]/100)*$prod_vendor['vendors_consignment_percentage'];
		if (intval($prod_vendor['vendors_consignment'])==3) {
			//$final_prod_vendor = ($real_data[final_price]/100)*$prod_vendor['vendors_consignment_percentage'];
			$pp_price = showPrice($new_disc, $real_data[final_price]);
			$final_prod_vendor = round(($pp_price*$prod_vendor['vendors_consignment_percentage']/100), 4);
		}				
	}
	
    if ($real_data['products_sale_type']=='3'){
		if (intval($prod_vendor['vendors_royalty'])==1) $final_prod_vendor = $prod_vendor['vendors_item_cost'];				
		if (intval($prod_vendor['vendors_royalty'])==2) $final_prod_vendor = ($real_data[products_price]/100)*$prod_vendor['vendors_royalty_percentage'];
		if (intval($prod_vendor['vendors_royalty'])==3) {
			//$final_prod_vendor = ($real_data[final_price]/100)*$prod_vendor['vendors_royalty_percentage'];
			$pp_price = showPrice($new_disc, $real_data[final_price]);
			$final_prod_vendor = round(($pp_price*$prod_vendor['vendors_royalty_percentage']/100), 4);
		}
	}

	$_sql = "update ".TABLE_ORDERS_PRODUCTS." set products_item_cost='".$final_prod_vendor."' where orders_products_id='".$orders_products_id."'";
	tep_db_query($_sql);
	//mail('x0661t@d-net.kiev.ua','matross',$_sql);
?>
