<?
session_start();
require($abs_url.'includes/configure.php');
require($abs_url.'includes/functions/database.php');

tep_db_connect() or die('Unable to connect to database server!');



$sql_query = tep_db_query("select * from daily_cart order by created_at desc");

while ($row = tep_db_fetch_array($sql_query)) {
	session_decode($row[sessvalue]);
	$session = $_SESSION;
	$arr = get_object_vars($session[cart]);
	if(count($arr['contents'])== 0) {
		tep_db_query('delete from daily_cart where sesskey="'.$row[sesskey].'"');
	}
}

?>