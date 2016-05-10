<?
require($abs_url.'includes/configure.php');
require($abs_url.'includes/functions/database.php');

tep_db_connect() or die('Unable to connect to database server!');

$log_list = tep_db_query("SELECT * FROM whos_online order by time_last_click desc");

while ($row = tep_db_fetch_array($log_list)) $log[] = $row;
//var_dump($log);
for ($i=0;$i<count($log); $i++){
	if ($i != count($log) - 1){
	$time = $log[$i][time_last_click] - $log[$i+1][time_last_click];	
//echo $time." = ".$log[$i][time_last_click]." - ".$log[$i+1][time_last_click]."<br/>";
	if ($time>300) {
		$counter = tep_db_num_rows(tep_db_query("select * from outrage_log where time='".$log[$i+1][time_last_click]."'"));
		if ($counter == 0)
			tep_db_query("insert into outrage_log set outrage_range='".$time."', time='".$log[$i+1][time_last_click]."'");
		}
	}
}
?>          