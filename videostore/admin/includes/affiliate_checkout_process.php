<?
$affiliate_total = $RunningSubTotal;

$aff=tep_db_query("select affiliate_id, affiliate_percent from affiliate_sales where affiliate_orders_id='".(int)$oID."'");


while ($row = tep_db_fetch_array($aff)){
	$affiliate_ref = $row['affiliate_id'];
  	$affiliate_payment = tep_round(($affiliate_total * $row['affiliate_percent'] / 100), 2);

  if ($affiliate_payment>0) {
	tep_db_query("update ".TABLE_AFFILIATE_SALES." set affiliate_value='".$affiliate_total."', affiliate_payment='".$affiliate_payment."' where affiliate_orders_id='".(int)$oID."' and affiliate_id='".$affiliate_ref."'");
  }

}
?>