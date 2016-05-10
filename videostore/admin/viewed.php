<?

  require('includes/application_top.php');

$products = tep_db_query("select products_id from products order by products_id");
while($row = tep_db_fetch_array($products)){
	$count = tep_db_num_rows(tep_db_query("select * from products_data where products_id='".$row[products_id]."'"));
	if ($count==0){
		echo $row[products_id]." not in database<br/>";
		tep_db_query("insert into products_data set products_id=".$row[products_id].", language_id=1, products_viewed=0");
	}
}
echo "<b>DONE</b>";


/*
$products = tep_db_query("select products_id, products_viewed from products_description order by products_id");
$total = 0;
while($row = tep_db_fetch_array($products)){
	$count = tep_db_num_rows(tep_db_query("select * from products_data where products_id='".$row[products_id]."'"));
	if ($count==0){
		tep_db_query("insert into products_data set products_id=".$row[products_id].", language_id=1, products_viewed=0");
	}
	else{
		tep_db_query("update products_data set products_viewed=products_viewed+".intval($row[products_viewed])." where products_id='".$row[products_id]."'");
		tep_db_query("update products_description set products_viewed=0 where products_id='".$row[products_id]."'");		
		//echo "update products_data set products_viewed=products_viewed+".$row[products_viewed]." where products_id='".$row[products_id]."'<br/>";
		//echo "update products_description set products_viewed=0 where products_id='".$row[products_id]."'<br/><hr>";
	}
}
*/

?>