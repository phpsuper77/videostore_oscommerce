<?php
error_reporting(E_ALL);

$starttime = 0;

function timer_start() {
	global $starttime;

	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$starttime = $mtime; 
}

function timer_end() {
	global $starttime;

	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$endtime = $mtime;
	$totaltime = ($endtime - $starttime);
 	echo "This page was created in ".$totaltime." seconds";
 }

$_404PAGE="http_error.php?error_id=404";  // place in document root
$_500PAGE="404.php";  // place in document root
function endsWith($string, $char)
{
    $length = strlen($char);
    $start =  $length *-1; //negative
    return (substr($string, $start, $length) === $char);
}


function debug_(){
	if (($_SERVER['REMOTE_ADDR'] == "216.70.173.202") || ($_SERVER['REMOTE_ADDR'] == "71.119.130.153") || ($_SERVER['REMOTE_ADDR'] == "96.247.83.141"))
		return true;
	return false;
}

function echoarray_($array){
	if (debug_())
		echo "<pre>".print_r($array,true)."</pre><br>";
}

function phpinfo_(){
	if (debug_())
		phpinfo();
}

function echo_($string){
	if (debug_())
		echo $string."<br>";
}
// phpinfo_();
require("includes/configure.php");


// custom overwrite
$_SERVER['REDIRECT_URL'] = $_SERVER['REQUEST_URI'];

$store_dir = "";
// if( strstr($_SERVER['REDIRECT_URL'], "/store2/") ) {
	// $_SERVER['REDIRECT_URL'] = substr($_SERVER['REDIRECT_URL'], 6, strlen($_SERVER['REDIRECT_URL']));
// }

if (isset($_SERVER['REDIRECT_URL'])) {
	$REDIRURL=$_SERVER['REDIRECT_URL'];
		// echo_($REDIRURL);
	if (!endsWith($REDIRURL, "/")){
		$REDIRURL = $REDIRURL."/";
	}
	$junk = explode("/",$REDIRURL);
	// echoarray_($junk);
	$firstarray_done = false;
	$set_restofline = false;
	$searchurl  = "";
	$restofline = "";
	foreach($junk as $key => $value){
		if (strstr($value, ".php") || strstr($value, "?") || strstr($value, "&") ){
			$restofline = $value;
	
			$set_restofline = true;
		}else{
			if ($value != ""){
				if ($set_restofline){
					$restofline .= "/".$value;
				}else{
					if (!$firstarray_done)
						$firstarray_done = true;
					else
						$searchurl .= "/";

					$searchurl .= $value;
				}
			}
		}
	}
	unset($junk);
	// echo_($searchurl);
	// echo_($restofline);
	// $url_parts=explode('/',$REDIRURL);
	// $REDIRURL= "/".$url_parts[count($url_parts)-2]."/";
	
	if (substr_count($REDIRURL,'/')>1){

		// echo_($REDIRURL);
		// list($undef,$searchurl,$restofline)=explode('/',$REDIRURL,3) ;
		// $searchurl = preg_replace("/[^a-z0-9\-\(\)\"]+/i", "", $searchurl);
		

		// $Lconn=mysql_pconnect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD);
		mysql_pconnect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
		
		// mysql_select_db(DB_DATABASE,$Lconn);
		mysql_select_db(DB_DATABASE);

		// echo_($searchurl);
		$query="
			SELECT
				*
			FROM
				".TABLE_SEO_URLS."
			WHERE
				surls_name='".$searchurl."'
			ORDER BY
				surls_param
			ASC
			LIMIT
				1;";		

		$results=mysql_query($query) or die('Query failed: ' . mysql_error());

		if ($reshash=mysql_fetch_array($results, MYSQL_ASSOC)) {

			$newurl=$reshash{'surls_script'};
			list($nvar,$nval)=explode("=",$reshash{'surls_param'},2);
			// $junk = explode("&",$rehash['surls_param']);
			// foreach($junk as $key => $value){
				// $junk2 = explode("=",$rehash['surls_param']);
				// foreach($junk2 as $key2 => $value2){
					// $passargs[$key2]=$value2;
				// }
			// }
			if ($restofline > "")
			{
				if (preg_match('/(.+)-(.+)-(.+)\.php/',$restofline,$matches))
				{
					//$newurl=$matches[1].".php";
					$passargs['page']=$matches[2];
					$passargs['sort']=$matches[3];
					// $passargs[$nvar]=$nval;
				} elseif (preg_match('/(.+)-(.+)\.php/',$restofline,$matches))
				{
					//$newurl=$matches[1].".php";
					$passargs['page']=$matches[2];
					// $passargs[$nvar]=$nval;
				} elseif (preg_match('/(.+)/',$restofline,$matches))
				{
					//$newurl=$matches[1];
					$passargs[$nvar]=$nval;
				}
			} else {
				$passargs[$nvar]=$nval;
			}
			if (substr_count($restofline,"&")>0) {
				$restofline2 = $restofline;
				if (endsWith($restofline2, "/")){
					$restofline2 = substr($restofline2, 0, -1);
				}
				$xplodes = explode('&',$restofline2,2);
				$restofline2 = $xplodes[1];
				$xplodes = explode('&',$restofline2);
				foreach($xplodes as $key => $value){
					$xplodes2 = explode('=',$value);
					$passargs[$xplodes2[0]]=$xplodes2[1];
					//$restofline2 = str_replace($value,"",$restofline2);
				}
			}
// echo $restofline."<br><pre>".print_r($passargs,true)."</pre><br><br>";
// echo $query;
// exit;			
			// Zencart override
			$surls_param = $reshash['surls_param'];
			// echo $surls_param . "<br>";
			
			$datablocks = explode("&", $surls_param);
			foreach( $datablocks as $aa ) {
				$dv = explode("=", $aa);
				$passargs[$dv[0]] = $dv[1];
			}
			
			//$passargs['main_page']="index";
			//$passargs['cPath']=53;
			// end Zencart override
			
			// print_r( $passargs );
			// exit;
			// echoarray_($passargs);
			foreach ($passargs as $ark => $arv)
			{
				$_GET[$ark]=$arv;
				$_REQUEST[$ark]=$arv;
				$HTTP_GET_VARS[$ark]=$arv;
			}

			$newscript=DIR_WS_HTTP_CATALOG.$newurl;

			$HTTP_SERVER_VARS['SCRIPT_NAME']=$newscript;
			$_SERVER['SCRIPT_NAME']=$newscript;
			$HTTP_SERVER_VARS['PHP_SELF']=$newscript;
			$_SERVER['PHP_SELF']=$newscript;
			$PHP_SELF=$newscript;
			// mysql_close($Lconn);
			
			// echo "NEW Url: " . $newurl . "<br>";
			
			// echo $newurl."<br>".print_r($passargs,true);
			if (((substr_count($newurl,".php")>0) && (file_exists($newurl))) || isset($passargs['page'])) {
				if (!(substr_count($newurl,".php")>0)) $newurl = "index.php";
				include($newurl);
				exit;
			}
		}

		// mysql_close($Lconn);
	}
}

//header("HTTP/1.0 404 Not Found");
if (isset($_404PAGE) && !($_404PAGE =="")){
	if (substr_count($_404PAGE,"?")>0){
		$xplodes = explode('?',$_404PAGE,2);
		$_404PAGE = $xplodes[0];
		$params = $xplodes[1];
		$xplodes = explode('&',$params);
		foreach($xplodes as $key => $value){
			$xplodes2 = explode('=',$value);
			$passargs[$xplodes2[0]]=$xplodes2[1];
			//$restofline2 = str_replace($value,"",$restofline2);
		}
		foreach ($passargs as $ark => $arv)
		{
			$_GET[$ark]=$arv;
			$_REQUEST[$ark]=$arv;
			$HTTP_GET_VARS[$ark]=$arv;
		}		
	}
	//	http_error.php?error_id=404
	if (file_exists($_404PAGE))	include($_404PAGE);
}

/* header("Location: http://dugouthats.com/shop/page_not_found.php"); */
?>
