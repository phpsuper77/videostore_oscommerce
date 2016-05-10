<?
  require('includes/application_top.php');

$id = $_GET[products_id] ? $_GET[products_id] : '';
$ref_link = $ref ? "&ref=".$ref : '';

if (intval($id) != 0){
	$ip = $_SERVER['REMOTE_ADDR'];
	$session_id = tep_session_id();
	$link = tep_db_fetch_array(tep_db_query("select products_buy_download_link from products_description where products_id=".$id));
	if (trim($link[products_buy_download_link])!=''){
  		$row = array('customers_id' => $customer_id,
                          		'IP' => $ip,
                          		'time_entry' => time(),
                          		'products_id' => $id,
					'session_id'=>$session_id);
        tep_db_perform('download_link_stat', $row);
	header( 'Location: ' . $link[products_buy_download_link].$ref_link );
	}
}
?>