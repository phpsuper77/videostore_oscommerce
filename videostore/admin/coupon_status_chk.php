<?php
  require('includes/application_top.php');

$couponid=$_GET['id'];
$val=$_GET['x'];
//echo "update coupons set lowest_price='1' where coupon_id='".$couponid."'";


 
 $sql_set_coupon=mysql_query("update coupons set lowest_price='".$val."' where coupon_id='".$couponid."'");
  

?>