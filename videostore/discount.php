<?
if ($_GET['action']=="calculate"){
	$disc[] = array('value'=>$_POST['discount'], 'class'=>'ot_qty_discount', 'final'=>'');		
	$disc[] = array('value'=>$_POST['coupon'], 'class'=>'ot_coupon', 'final'=>'');	
	
$total = $_POST['price1']+$_POST['price2'];

if(count($disc)!=0){
foreach ($disc as $row){
	if (intval($total)!=0) $final = round(($row['value']*100/$total), 4);
	$row['final'] = $final;
	$total = $total-$row['value'];
	$new_disc[] = $row;
	}
}

function showPrice($new_desc, $product_price){
	for ($i=0;$i<count($new_desc);$i++){
		$sum = round(($product_price*$new_desc[$i]['final']/100), 4);
		$product_price = $product_price - $sum;
	}
	return round($product_price, 4);
}

if (intval($_POST['price1'])!=0){
$pp_price = showPrice($new_disc, $_POST['price1']);
echo "Final discount for product 1 value is: $".round(($pp_price*$_POST['percent']/100), 4);
}
echo "<br/>";
if (intval($_POST['price2'])!=0){
$pp_price = showPrice($new_disc, $_POST['price2']);
echo "Final discount for product 2 value is: $".round(($pp_price*$_POST['percent']/100), 4);
}

}
?>
<form name="f1" action="discount.php?action=calculate" method="POST">
Discount ($): <input type="text" name="discount" value="<?=$_POST['discount']?>"/><br/>
Coupon ($): <input type="text" name="coupon" value="<?=$_POST['coupon']?>"/><br/>
Vendor percentage (%): <input type="text" name="percent" value="<?=$_POST['percent']?>"/><br/>
Products price 1:($) <input type="text" name="price1" value="<?=$_POST['price1']?>"/><br/>
Products price 2:($) <input type="text" name="price2" value="<?=$_POST['price2']?>"/><br/>
<input type="submit" value="Calculate" />
</form>
