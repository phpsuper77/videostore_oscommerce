<?
$abs_url = '/home/terrae2/public_html/';


          if ($dir = @opendir($abs_url."tmp/")) {
            while ($cache_file = readdir($dir)) {
              $cached_file = $cache_blocks[$i]['file'];
                if (preg_match('/^/' . "top_sellers", $cache_file)) {
                  @unlink($abs_url."tmp/". $cache_file);
                }

                if (preg_match('/^/' . "affiliate_top", $cache_file)) {
                  @unlink($abs_url."tmp/". $cache_file);
                }

              }
            closedir($dir);
          }


require($abs_url.'includes/configure.php');
require($abs_url.'includes/functions/database.php');

tep_db_connect() or die('Unable to connect to database server!');

$query = tep_db_query("SELECT products_id FROM products");

    	while ($products = tep_db_fetch_array($query)) {
	$counter = 0;
		$query_1 = tep_db_query("select o.orders_id, op.products_id, op.products_quantity from orders_products op LEFT JOIN orders o on (o.orders_id=op.orders_id) where op.products_id=".$products[products_id]." and TO_DAYS(NOW()) - TO_DAYS(o.date_purchased) < 30");
    		while ($orders_products = tep_db_fetch_array($query_1)) {		
						$counter = $counter+intval($orders_products['products_quantity']);
			}
if ($products[products_id]!=''){
//echo "<hr>";
//echo "Products_id: ".$products[products_id]." = Quantity: ". $counter."<br/>";
//	echo "update products set products_ordered_periodical_quantity='".$counter."' where products_id=".$products[products_id];
//	tep_db_query("update products set products_ordered_periodical_quantity='".$counter."' where products_id=".$products[products_id]);
//echo "<hr>";
	}
}
?>
