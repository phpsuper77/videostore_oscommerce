<?php
//  require('includes/application_top.php');

//Assign current time
$current_time = time();
$WHOS_ONLINE_AGO = '10800';

//Calculate the time that the user is logged in
$xx_mins_ago = ($current_time - $WHOS_ONLINE_AGO);

// remove entries that have expired
 $connec = mysql_connect("localhost", "terrae2_prod", "12261957");
 $db = mysql_select_db("terrae2_osCommerce");
 $sql = "delete from whos_online where time_last_click < '" . $xx_mins_ago . "'";
 $sql_rs = mysql_query($sql);
 mysql_close($connec);

//tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");
//echo $current_time."-".WHOS_ONLINE_AGO."=".$xx_mins_ago;
?>
