<?
  require('includes/application_top.php');
ini_set("display_errors",1);
error_reporting(E_ALL);

/*
$query = tep_db_query("SELECT * FROM products_to_categories group by products_id");


while ($total = tep_db_fetch_array($query)) {
	$query_prod = tep_db_query("SELECT count(*) as cnt FROM products where products_id=".$total[products_id]."");
	$result = tep_db_fetch_array($query_prod);
if ($result[cnt]==0) { echo "Product_id in prod_to_cat: ".$total['products_id']." - found ".$result[cnt]." in products<BR>";
			//tep_db_query("delete FROM products_to_categories where products_id=".$total['products_id']."");
//echo "Deleted";
	}

}
*/

function getParentList($id, $list, $prod_id){
if (intval($id)!=0) {
	$query = tep_db_query("SELECT * FROM categories where categories_id=".$id." limit 0,1");
	$total = tep_db_fetch_array($query);
	$listId =$list."_".$total['parent_id']; 	
	getParentList($total['parent_id'], $listId, $prod_id);
	}
	else{
		//echo $list."<br>";
		$arr = explode("_",$list);		
		for ($i=0;$i<count($arr);$i++){
		//echo $arr[$i]."-".$prod_id."<br>";
		if ((intval($arr[$i])==0) or (intval($arr[$i])==959) or (intval($arr[$i])==808)){
		//echo "<font color=red style='font-weight:bold;'>".$arr[$i]."</font>- excluded from the list<br/>";
		}
		 else   {
		$query_to_prod = tep_db_query("SELECT count(*) as cnt FROM products_to_categories where categories_id=".$arr[$i]." and products_id=".$prod_id."");
		$general = tep_db_fetch_array($query_to_prod);
		if ($general['cnt']==0){
			//echo "insert into products_to_categories set categories_id=".$arr[$i].", products_id=".$prod_id."<BR>";
			tep_db_query("insert into products_to_categories set categories_id=".$arr[$i].", products_id=".$prod_id."");
			}			
		}
	   }
	}
	
}

 $query_list = tep_db_query("SELECT * FROM products_to_categories");
while ($final = tep_db_fetch_array($query_list)) {
//echo "<b>Product ID: </b><font color=red style='font-weight:bold;'>".$final['products_id']."</font><BR>";
	getParentList($final['categories_id'], $final['categories_id'], $final['products_id']);
}

?>