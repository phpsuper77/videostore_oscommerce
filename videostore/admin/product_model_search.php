<?

  require('includes/application_top.php');

?>
<html><head><title>Search SKU</title></head>
<script>
	function RunSelect(sku){
		window.opener.document.getElementById('new_model_number').value=sku;
		window.close();
	}
</script>

	<form action="product_model_search.php" method="get">
		<input type="hidden" name="cnt" value="<?=$cnt?>" />
		<input type="hidden" name="action" value="search" />
	<table width="100%" cellspacing="5" border="0" style="border: 1px solid #666666;border-collapse: collapse;">
	<tr><td width="60"><b>SKU:</b></td><td><input type="text" name="sku" value="<?=$sku?>" style="width:300px;"/></td></tr>
	<tr><td colspan="2" style="padding-top:10px;"><input type="submit" value="Search" /></td></tr>
	</table>
	</form>
<?

if ($action=='search'){

$s=10;
if (!isset($_GET['ctr'])) $ctr=0; else $ctr=$_GET['ctr'];
$st = $ctr*$s;
$limit="limit $st, $s";
$counter = tep_db_num_rows(tep_db_query("SELECT  * FROM  `products` WHERE products_model like '%".$sku."%'"));
$pages=ceil($counter/$s);
	$sql = tep_db_query("SELECT  * FROM  `products` WHERE products_model like '%".$sku."%'".$limit);
	echo "<table width=100% border=1 style='border: 1px solid #666666;border-collapse: collapse;'>";
	echo "<tr><td width='50%'><b>sku</b></td></td><td width='20%'><b>Price</b></td><td width='20%'><b>Qty On Stock</b></td><td width='10%'>Action</td></tr>";
	if (tep_db_num_rows($sql)>0){
	while($row = tep_db_fetch_array($sql)){
	echo "<tr><td>".$row[products_model]."</td></td><td>".$row[products_price]."</td><td>".$row[products_quantity]."</td><td><a href='#' onclick='RunSelect(\"".$row[products_model]."\",\"".$row[products_price]."\",\"".$row[products_quantity]."\",\"".$cnt."\")'>Select</a></td></tr>";
	}
   }
	else echo "<tr><td colspan='4' align='center'><i>No products found!</i></td></tr>";
	echo "</table>";
?>
<br/><center>
	Pages: <select name="ctr" onChange="window.location.href='amazon_search_sku.php?action=search&cnt=<?=$cnt?>&sku=<?=$sku?>&ctr='+this.value">
		<?
	for ($i=0;$i<$pages; $i++){
		$selected = "";
		if ($ctr==$i) $selected = "selected";
		echo "<option value=".$i." ".$selected.">".($i+1)."</option>";
	}
		?>
	</select> of <b><?=$pages?></b>
     </center>
<?
}
echo "<br/><center><a href='#' onclick='window.close();'><b>Close Window</b></center>";
?>
</html>