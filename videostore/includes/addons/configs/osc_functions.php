<?php
	function echoarray($array){
		echo "<pre>".print_r($array,true)."</pre><br />\n";
	}

	function start_time(){
	   global $starttime;
	   $mtime = microtime();
	   $mtime = explode(" ",$mtime);
	   $mtime = $mtime[1] + $mtime[0];
	   $starttime = $mtime;
	   return $starttime;
	}
	function total_time(){
	   global $starttime,$total_time;
	   $mtime = microtime();
	   $mtime = explode(" ",$mtime);
	   $mtime = $mtime[1] + $mtime[0];
	   $endtime = $mtime;
	   $totaltime = ($endtime - $starttime);
	   return $totaltime;
	}
	if (!function_exists('endsWith')){
		function endsWith($string, $char)
		{
			$length = strlen($char);
			$start =  $length *-1; //negative
			return (substr($string, $start, $length) === $char);
		}	
	}
?>