<?php
	// Set box to:
	$_GET['selected_box'] = "tools";
	$HTTP_GET_VARS['selected_box'] = $_GET['selected_box'];

	$ADDITIONAL_PNAMEFIELDS = array();
	$ADDITIONAL_PNAMEFIELDS['DEFAULTselect'] = "
		pd.products_name as products_name,
	";
	$ADDITIONAL_PNAMEFIELDS['DEFAULTfrom'] = "";
	$ADDITIONAL_PNAMEFIELDS['select'] = $ADDITIONAL_PNAMEFIELDS['DEFAULTselect'];
	$ADDITIONAL_PNAMEFIELDS['from'] = $ADDITIONAL_PNAMEFIELDS['DEFAULTfrom'];
	
	$ADDITIONAL_PNAMEFIELDS['select'] = "
		CONCAT_WS(' ',s.series_name, pd.products_name_prefix, pd.products_name, pd.products_name_suffix) as products_name,
	";
	$ADDITIONAL_PNAMEFIELDS['from'] = "
		left join
				series s on (p.series_id = s.series_id)
	";
	
	$ADDITIONAL_CNAMEFIELDS = array();
	$ADDITIONAL_CNAMEFIELDS['DEFAULTselect'] = "
		cd.categories_name as categories_name,
	";
	$ADDITIONAL_CNAMEFIELDS['DEFAULTfrom'] = "";
	$ADDITIONAL_CNAMEFIELDS['select'] = $ADDITIONAL_CNAMEFIELDS['DEFAULTselect'];
	$ADDITIONAL_CNAMEFIELDS['select'] = "
		cd.categories_heading_title as categories_name,
	";
	$ADDITIONAL_CNAMEFIELDS['from'] = $ADDITIONAL_CNAMEFIELDS['DEFAULTfrom'];
//products_name_prefix 	products_name 	products_name_suffix
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = '';
	define('HTTP_SELF', 'http://'.$host.$uri.'/'.$extra);
	if (!defined('LCHAR_BACKSLASH')) define('LCHAR_BACKSLASH', str_replace(' ','','\ '));
    if (!function_exists('bugFixRequirePath')){
        function bugFixRequirePath($newPath){
            $stringPath = dirname(__FILE__);
            if (strstr($stringPath,":")) $stringExplode = LCHAR_BACKSLASH.LCHAR_BACKSLASH;
            else $stringExplode = "/";
            $paths = explode($stringExplode,$stringPath);
            $newPaths = explode("/",$newPath);
            if (count($newPaths) > 0){
                for($i=0;$i<count($newPaths);$i++){
                    if ($newPaths[$i] == "..") array_pop($paths);  
                }
                for($i=0;$i<count($newPaths);$i++){
                    if ($newPaths[$i] == "..") unset($newPaths[$i]);
                }
                reset($newPaths);
                $stringNewPath = implode($stringExplode,$paths).$stringExplode.implode($stringExplode,$newPaths);
                return $stringNewPath;
            }
        }
    }
	ini_set('include_path',ini_get('include_path').':'.bugFixRequirePath('../'));	
	ini_set('include_path',ini_get('include_path').':'.bugFixRequirePath('../../'));	
	define('OSC_ADMIN_FOLDER',bugFixRequirePath('../'));
	define('OSC_STORE_FOLDER',bugFixRequirePath('../../'));
	define('OSC_CONFIG_FOLDER',OSC_STORE_FOLDER.'includes/addons/configs/');
	define('OSC_FUNCTION_FOLDER',OSC_STORE_FOLDER.'includes/addons/');
	require_once(OSC_CONFIG_FOLDER.'osc_functions.php');
	require_once(OSC_CONFIG_FOLDER.'osc_seourls_ap.php');
	// echo ini_get('include_path')."<br>";
	
	
	if (file_exists(OSC_STORE_FOLDER."includes/version.php")){
		include_once(OSC_STORE_FOLDER."includes/version.php");
	}
	/*
	// zen cart overrides
	// define('DIR_WS_INCLUDES', OSC_ADMIN_FOLDER."includes/");
	// define('DIR_WS_FUNCTIONS', OSC_ADMIN_FOLDER."includes/" . 'functions/');
	// define('DIR_WS_CLASSES', OSC_ADMIN_FOLDER."includes/" . 'classes/');
	if (function_exists("zen_not_null")){
		define('DIR_WS_BOXES', OSC_ADMIN_FOLDER."includes/"."boxes/");
		define('DIR_WS_MODULES', OSC_ADMIN_FOLDER."includes/" . 'modules/');
		define('DIR_WS_LANGUAGES', OSC_ADMIN_FOLDER."includes/"."languages/");
		// require(OSC_STORE_FOLDER."includes/version.php");
	}
	if(defined('PROJECT_VERSION') && (substr_count(strtolower(PROJECT_VERSION), 'cre') > 0)){
		// define('DIR_WS_BOXES', OSC_ADMIN_FOLDER."includes/"."boxes/");
		// define('DIR_WS_MODULES', OSC_ADMIN_FOLDER."includes/" . "modules/");
		// define('DIR_WS_LANGUAGES', OSC_ADMIN_FOLDER."includes/"."languages/");
		// echo "tesT";
		// define('DIR_WS_INCLUDES', OSC_ADMIN_FOLDER."includes/");
	}
	*/
	error_reporting(E_ALL);
	ini_set('display_errors', '1');		
	require(bugFixRequirePath('../includes/application_top.php'));
	error_reporting(E_ALL);
	ini_set('display_errors', '1');	
	start_time();

	if (!function_exists("zen_not_null")){
		  function zen_not_null($value) {
			if (is_array($value)) {
			  if (sizeof($value) > 0) {
				return true;
			  } else {
				return false;
			  }
			} elseif( is_a( $value, 'queryFactoryResult' ) ) {
			  if (sizeof($value->result) > 0) {
				return true;
			  } else {
				return false;
			  }
			} else {
			  if ( (is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) {
				return true;
			  } else {
				return false;
			  }
			}
		  }	
	}
	if (!function_exists("new_zen_get_path")){
		  function new_zen_get_path($current_category_id = '') {
			global $db;

			$cPath_array = array();
			
			$cPath_array[] = $current_category_id;
			
			if (zen_not_null($current_category_id)) {
			
				$root_id = 0;
				$loop_category_id = (int)$current_category_id;
				
				while( $root_id == 0 ) {
					$current_category_query = "select parent_id
											   from " . TABLE_CATEGORIES . "
											   where categories_id = '" . $loop_category_id . "'";

					$current_category = mysql_query($current_category_query);
					if (mysql_num_rows($current_category) > 0)
						$current_category_results = mysql_fetch_assoc($current_category);
					else
						$current_category_results['parent_id'] = 0;
					$parent_id = $current_category_results['parent_id'];

					if( $parent_id == 0 ) $root_id = 1;
					else {
						$loop_category_id = $parent_id;
						$cPath_array[] = $parent_id;
					}
				}
			}

			$cPath_new = implode("_", array_reverse($cPath_array));
			return 'cPath=' . $cPath_new;
		  }
	}
	function findstringpos($string,$endpos) {
		$offset = 0;
		$absend = $endpos;
		for ( $counter = 0; $counter <= $absend; $counter += 1) {
		$offset = strpos($string, "-", $offset + 1);
		}
		return $offset;
	}
				
	function cleanurlstring($cleanedprodname) {
		// Strip HTML Tags from the prod name before parsing.
		$cleanedprodname = strip_tags($cleanedprodname);
		$clear_dup_hyphs = array('--','---','----','---','--');
	
	   if (empty($_GET['checksurlnum'])) {
	 	  $pregreplacestr = '/[^a-zA-Z_-]+/';
	   } else {
	 	  $pregreplacestr = '/[^a-zA-Z0-9_-]+/';
	   }
	   // echo $cleanedprodname."<br>";
	   $cleanedprodname = str_replace("'","",$cleanedprodname);
	   $cleanedprodname = trim(preg_replace($pregreplacestr, '-' , trim(strtolower($cleanedprodname))));
	   // echo $cleanedprodname."<br>";
	   // clear tailing hyphens
	   $cleanedprodname = rtrim($cleanedprodname, '');
	   // echo $cleanedprodname."<br>";
	   // clear starting hyphens
	   $cleanedprodname = ltrim($cleanedprodname, '');
	   // echo $cleanedprodname."<br>";
	   
	   if (($_GET['amthyphens'] != 'x') && substr_count($cleanedprodname,"-") > $_GET['amthyphens']) {
		   $cleanedprodnamenew = substr($cleanedprodname,0,findstringpos($cleanedprodname,$_GET['amthyphens']));
		   $cleanedprodname = $cleanedprodnamenew;
	   }
	   
	   // extra hyphen clear
	   $cleanedprodname = str_replace($clear_dup_hyphs,'-',$cleanedprodname);
	   
	   // limit to db set default $numchars character length
	   //$cleanedprodname = substr($cleanedprodname, 0, 144);
	   
	   return $cleanedprodname;
	}
				
   function findlastseourlid() {
	   global $db;
	   
	   $ssql = "select surls_id from " . TABLE_SEO_URLS . " order by surls_id desc limit 1";
	   $last_surlsid = mysql_query($ssql);
	   $last_surlsid_results = mysql_fetch_assoc($last_surlsid);
	   return $last_surlsid_results['surls_id'];
   }
// echo cleanurlstring("america's fight");
   if (isset($_GET['action']) && ($_GET['action'] == 'run')) {
		//echo $_GET['action'] . "<br />";
		// updates products seo urls
		   // $qsql = "
			// select 
				// pd.products_id,
				// ".$ADDITIONAL_PNAMEFIELDS['select']."
				// pd.language_id,
				// p2c.categories_id
			// from 
				// products p join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id
			// left join
				// products_to_categories p2c on (p2c.products_id = pd.products_id)
			// ".$ADDITIONAL_PNAMEFIELDS['from']."
			// where 
				// (pd.products_surls_id is null or pd.products_surls_id = '')".""/*and p.products_status = 1*/." 
			// order by 
				// products_id asc;";
				// echo $qsql;
				// exit;		
		$SEOURLSDONE = 0;
		if (isset($_GET['products']) && ($_GET['products'] == 1)) {
			//echo $_POST['products'] . "<br />";
		   
		   $blank_prod = 0;
		   
		   $qsql = "
			select 
				pd.products_id,
				".$ADDITIONAL_PNAMEFIELDS['select']."
				pd.language_id,
				p2c.categories_id
			from 
				".TABLE_PRODUCTS." p join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id
			left join
				".TABLE_PRODUCTS_TO_CATEGORIES." p2c on (p2c.products_id = pd.products_id)
			".$ADDITIONAL_PNAMEFIELDS['from']."
			where 
				(pd.products_surls_id is null or pd.products_surls_id = '' or pd.products_surls_id = '0')".""/*and p.products_status = 1*/." 
			order by 
				products_id asc";
				
			if (isset($_GET['ptotalrows']))
				$qsql .= " LIMIT 3000";
				
			$qsql .= ";";
				// echo $qsql . "<br />";
				// exit;

			$start_productsearch = mysql_query($qsql) or die('died ('.$qsql.'):'.mysql_error());
			// if (isset($nsql) && $nsql != ""){
				// $product_namesearch = mysql_query($nsql);
				// $row2 = mysql_fetch_assoc($product_namesearch);
			// }
			//while (!$start_productsearch->EOF) {
			$direct_prodlinks = array();
			if (!isset($_GET['elapsed_time'])) $_GET['elapsed_time'] = 0;
			if (isset($_GET['done'])) $ddone = $_GET['done'];
			else {
				$ddone = 0;
				$_GET['done'] = 0;
			}
			$temp_prod_id = false;
			while ($row = mysql_fetch_assoc($start_productsearch)) {
				if (!$temp_prod_id) $temp_prod_id = $row['products_id'];
				if (!isset($_GET['ptotalrows'])) $_GET['ptotalrows'] = mysql_num_rows($start_productsearch) or die("Failed: ".mysql_error());
				
				$ddone = $_GET['done'] + $SEOURLSDONE;
				if ($ddone == $_GET['ptotalrows']) {
					$SEOURLSDONE = $_GET['limit'] + 1;
				}
				if ($temp_prod_id != $row['products_id']) {
					// Update for a new products_id
					mysql_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_surls_id = '" . $first_surlsid . "' where products_id = '" . $temp_prod_id . "'") or die("Failed: ".mysql_error());
					// echo "updated: ".$temp_prod_id.", new prod id: ".$row['products_id']." (".$first_surlsid.")<br>";
					unset($first_surlsid);
					$temp_prod_id = $row['products_id'];
					if ($SEOURLSDONE > $_GET['limit']){
						$_GET['done'] = ($_GET['done'] + $SEOURLSDONE-1);
						$_GET['elapsed_time'] = $_GET['elapsed_time'] + total_time();
						$done_text = "";
						$locstring = "?";
						$junk = false;
						foreach($_GET as $key => $value){
							if ($junk) $locstring .= "&";
							$locstring .= $key."=".$value;
							if (!$junk) $junk = true;
						}
						// echo "Location: ". HTTP_SELF.FILENAME_SEO_URLS_REDIRECT.$locstring.$done_text;
						// exit;
						$percent_complete = number_format(($_GET['done']/$_GET['ptotalrows']*100),0);
						echo '<div style="width:100%;height:100%;margin:auto;"><div style="margin:auto; font-size:24px;">';
						echo 'PRODUCTS: <br />';
						echo 'Last product ID completed: '.$temp_prod_id.'<br />';
						echo 'Completed records: '.$_GET['done'].'<br />';
						echo 'Total records: '.$_GET['ptotalrows'].'<br />';
						echo $_GET['done'].' / '.$_GET['ptotalrows'].' ('.$percent_complete.'%)<br />';
						$strftime_format = "%M:%S";
						if ($_GET['elapsed_time'] > 3600)
							$strftime_format = "%H:%M:%S";
						echo 'Elapsed time: '.strftime($strftime_format,$_GET['elapsed_time']).' (estimated remaining time: '.strftime($strftime_format,(($_GET['elapsed_time']/$percent_complete)*(100-$percent_complete))).')<br />';
						echo '</div></div>';
						if ($_GET['done'] == $_GET['ptotalrows']) exit;
						echo '
								<script type="text/javascript">
									<!--
										window.location = "'.HTTP_SELF.FILENAME_SEO_URLS_REDIRECT.$locstring.$done_text.'"
									//-->
								</script>
						';
						//header("Location: ". HTTP_SELF.FILENAME_SEO_URLS_REDIRECT.$locstring.$done_text);
						exit;
					}
				}
				
				// get product cPath
				$product_cPath = new_zen_get_path($row['categories_id']);
				//cleans new seo_url string
				$pprodid = $row['products_id'];
				if (!isset($_GET['longurls']))
					$cleanedprodname = cleanurlstring($row['products_name']);
				else{
					$product_cPathTree = explode("_", str_replace("cPath=", "",$product_cPath));
					// echoarray($product_cPathTree);
					$prod_cat = "";
					foreach($product_cPathTree as $key => $value){
						$cNameSQL = "
							SELECT
								cd.categories_name as categories_name_backup,
								".$ADDITIONAL_CNAMEFIELDS['select']."
								cd.categories_id
							FROM
								".TABLE_CATEGORIES_DESCRIPTION." as cd
							WHERE
								cd.categories_id = '". $value."';
						";
					   $catname_query = mysql_query($cNameSQL);
					   // echo $cNameSQL."<br>";
					   // exit;
					   $catname_row = mysql_fetch_assoc($catname_query);

					   // echoarray($catname_row);
					   
						if (!isset($catname_row['categories_name']) || (isset($catname_row['categores_name']) && ($catname_row['categores_name'] == "")))
							$catname_row['categories_name'] = $catname_row['categories_name_backup'];
						$prod_cat .= cleanurlstring($catname_row['categories_name'])."/";
					}
					$cleanedprodname = $prod_cat.cleanurlstring($row['products_name']);
					// echo $cleanedprodname;
					// exit;
				}
				//echo $row['products_name']."<br>";
			   // echo $cprodid . "<br>";
				
				
			   //echo "good - 2<br>";
			 //  if (!isset($_GET['checksurlnum'])){
			//	$cleanedprodname = preg_replace(array('([0-9]-)', '(-[0-9])', '([0-9])'), "", $cleanedprodname);
			//	echo $cleanedprodname;	
			//   }
				$unique = 0;
				$cleanedprodname_orig = $cleanedprodname;
				while( $unique == 0 ) {
					if(isset($multiple_name[$cleanedprodname_orig]) && ($multiple_name[$cleanedprodname_orig] > 0 )) {
						$cleanedprodname = $cleanedprodname_orig . "-" . $multiple_name[$cleanedprodname_orig];
						// $multiple_name[$cleanedprodname_orig]++;
					}
					if (!isset($multiple_name[$cleanedprodname_orig]))
						$multiple_name[$cleanedprodname_orig] = 0;
					$multiple_name[$cleanedprodname_orig]++;
				   
				   $check_seourl = mysql_query("select surls_name from ".TABLE_SEO_URLS." where surls_name = '" . $cleanedprodname . "';") or die('died'.mysql_error());
					if (mysql_num_rows($check_seourl) > 0 )
						$check_seourl_results = mysql_fetch_array($check_seourl);
					else
						$check_seourl_results['surls_name'] = "";
				   if ( $check_seourl_results['surls_name'] == "" ) {
					$unique = 1;
					// $multiple_name[$cleanedprodname_orig]++;
				   }
				}
			  

			   //check for SEO_URL is already is use
			   // $ssql = "select surls_name from ". TABLE_SEO_URLS . " where surls_name = '" . $cleanedprodname . "';";
			   // $check_seourl = mysql_query($ssql);
			   // $check_seourl_results = mysql_fetch_assoc($check_seourl);
			   //echo "good - 1<br>";
			   
			   // assign new seo_urls id num
			   $new_surlsid = findlastseourlid() + 1;
			   $new_surlsid2 = $new_surlsid + 1;
			   $new_surlsid3 = $new_surlsid2 + 1;
			   
				// echo "check: ".$check_seourl_results['surls_name']."<br>";
				if (( $check_seourl_results['surls_name'] == "" ) && ($pprodid != "")) {
			   
				 // Blank URL reference??? WHY? disable for now

				
					if( $cleanedprodname != "" ) {
						//echo "before execute <br \>";
							

						//$product_cPath = "cPath=".$row['categories_id'];
						// echo $cleanedprodname . "<br>";
						// echo $pprodid."<br>";
						// echo $product_cPath."<br>";
						// exit;
						unset($surl_insert_sql1);
						unset($surl_insert_sql2);
						unset($surl_insert_sql3);
						// echo PROJECT_VERSION;
						// exit;
						if(substr_count(strtolower(PROJECT_VERSION), 'oscommerce') > 0){
							$prod_script = "product_info.php";
							$prod_param_prefix = "";
						}elseif(substr_count(strtolower(PROJECT_VERSION), 'cre') > 0){
							$prod_script = "product_info.php";
							$prod_param_prefix = "";
						}else{
							$prod_script = "index.php";
							$prod_param_prefix = "main_page=product_info&";
						}
						// echo PROJECT_VERSION;
						if((substr_count(strtolower(PROJECT_VERSION), 'oscommerce') > 0) || (substr_count(strtolower(PROJECT_VERSION), 'cre') > 0)){
							if (!isset($direct_prodlinks[$pprodid])){
								if (!isset($first_surlsid)) $first_surlsid = $new_surlsid;
								// Covers product links directly to the products.
								$sql1_insert = "products_id=" . $pprodid;
								$surl_insert_sql1 = "insert into " . TABLE_SEO_URLS . " (surls_id, surls_name, surls_script, surls_param, language_id) values ('" . $new_surlsid . "', '" . $cleanedprodname . "', '".$prod_script."', '".$sql1_insert."', '" . $row['language_id'] . "')";
								// echo $sql1_insert . "<br>";
								$direct_prodlinks[$pprodid] = true;
								$sql2_insert = "cPath=" . $row['categories_id'] . "&products_id=" . $pprodid;
								// Covers product Cpath option
								$surl_insert_sql2 = "insert into " . TABLE_SEO_URLS . " (surls_id, surls_name, surls_script, surls_param, language_id) values ('" . $new_surlsid2 . "', '" . $cleanedprodname . "', '".$prod_script."', '".$sql2_insert."', '" . $row['language_id'] . "')";
								// echo $sql2_insert . "<br>";
								$direct_cpathlinks[$sql2_insert] = true;
								if (!($row['categories_id'] == str_replace("cPath=", "",$product_cPath))){
									$sql3_insert = $product_cPath . "&products_id=" . $pprodid;
								// . $prod_param_prefix. $product_cPath . "&products_id=" . $pprodid .
									// Covers Product Cpath tree option
									$surl_insert_sql3 = "insert into " . TABLE_SEO_URLS . " (surls_id, surls_name, surls_script, surls_param, language_id) values ('" . $new_surlsid3 . "', '" . $cleanedprodname . "', '".$prod_script."', '".$sql3_insert."', '" . $row['language_id'] . "')";
									// echo $sql3_insert . "<br>";
								}
							}else{
								$sql2_insert = "cPath=" . $row['categories_id'] . "&products_id=" . $pprodid;
								if (!isset($direct_cpathlinks[$sql2_insert])){
									// Covers product Cpath option
									$surl_insert_sql2 = "insert into " . TABLE_SEO_URLS . " (surls_id, surls_name, surls_script, surls_param, language_id) values ('" . $new_surlsid2 . "', '" . $cleanedprodname . "', '".$prod_script."', '".$sql2_insert."', '" . $row['language_id'] . "')";
									// echo $sql2_insert . "<br>";
									$direct_cpathlinks[$sql2_insert] = true;
								}
								if (!($row['categories_id'] == str_replace("cPath=", "",$product_cPath))){
									$sql3_insert = $product_cPath . "&products_id=" . $pprodid;
									// Covers Product Cpath tree option
									$surl_insert_sql3 = "insert into " . TABLE_SEO_URLS . " (surls_id, surls_name, surls_script, surls_param, language_id) values ('" . $new_surlsid3 . "', '" . $cleanedprodname . "', '".$prod_script."', '".$sql3_insert."', '" . $row['language_id'] . "')";
									// echo $sql3_insert . "<br>";
								}
								// Covers product Cpath option
								//$surl_insert_sql1 = "insert into " . TABLE_SEO_URLS . " (surls_id, surls_name, surls_script, surls_param, language_id) values ('" . $new_surlsid . "', '" . $cleanedprodname . "', 'product_info.php', '" . $product_cPath . "&products_id=" . $pprodid . "', '" . $row['language_id'] . "')";
							}
						}else{
							// $prod_param_prefix = "main_page=product_info&";
							// if(substr_count(strtolower(PROJECT_VERSION), 'cre') > 0){
								// $prod_param_prefix = "";
							// }						
							if (!isset($direct_prodlinks[$pprodid])){
								if (!isset($first_surlsid)) $first_surlsid = $new_surlsid;
								$surl_insert_sql1 = "insert into " . TABLE_SEO_URLS . " (surls_id, surls_name, surls_script, surls_param, language_id) values ('" . $new_surlsid . "', '" . $cleanedprodname . "', '".$prod_script."', '". $prod_param_prefix. $product_cPath . "&products_id=" . $pprodid . "', '" . $row['language_id'] . "')";
								$direct_prodlinks[$pprodid] = true;
							}
						}
						// echo "SURL Insert SQL: " . $surl_insert_sql1 . "<br>";
						
						if (isset($surl_insert_sql1)) mysql_query($surl_insert_sql1);
						if (isset($surl_insert_sql2)) mysql_query($surl_insert_sql2);
						if (isset($surl_insert_sql3)) mysql_query($surl_insert_sql3);
						$SEOURLSDONE++;

						//echo "after execute <br \>";
					}
					else {
						$blank_prod++;
						//echo "blank <br \>";
					}
				
			   //echo "good - 3<br>";
					if (!isset($newlinks)) $newlinks = "";
					$newlinks .= '<a href="' .  HTTP_SELF . '/shop/'. $cleanedprodname . '/">' .  HTTP_SELF . '/shop/'. $cleanedprodname . '/' . '</a><br>';
				} else {
					$error .= '<a href="' . tep_href_link(FILENAME_CATEGORIES . '?pID='.$row['products_id'].'&action=new_product', '', 'NONSSL') . '">' . $cleanedprodname . ' already exists within db</a><br>';
				}
				
			   //echo "good - 4<br>";
			   //exit;
			   
			   //$start_productsearch->MoveNext();
		   }
	
		   // echo "BLANK PRODS: " . $blank_prod . "<br>";
		}
		
		// Dummy check to insert if the limit was never met. Usually happens on the last product.
		if (isset($first_surlsid) && isset($temp_prod_id))
			mysql_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_surls_id = '" . $first_surlsid . "' where products_id = '" . $temp_prod_id . "'") or die("Failed: ".mysql_error());

		
		
		//echo "test" . "<br />";
	    // assign categories seo urls
		$CSEOURLSDONE = 0;
	    if (isset($_GET['categories']) && ($_GET['categories'] == 1)) {
			unset($first_surlsid);
			//echo $_POST['categories'] . "<br />";
		
		   //echo "run categories<br>";
			$cSQL = "
				SELECT
					cd.categories_id,
					cd.categories_name as categories_name_backup,
					".$ADDITIONAL_CNAMEFIELDS['select']."
					cd.language_id
				FROM
					".TABLE_CATEGORIES_DESCRIPTION." as cd
					".$ADDITIONAL_CNAMEFIELDS['from']."
				WHERE
					categories_surls_id is null
				OR
					categories_surls_id = ''
				OR
					categories_surls_id = '0'
				ORDER BY
					categories_id
				ASC;
			";
			// echo $cSQL; exit;
		   $start_categoriessearch = mysql_query($cSQL) or die(mysql_error());
		   //echo "test - A<br>";
		   //$start_categoriessearch_results = mysql_fetch_assoc($start_categoriessearch);
		   //foreach($start_categoriessearch_results as $key => $value) {
			$temp_cat_id = false;
			if (!isset($_GET['celapsed_time'])) $_GET['celapsed_time'] = 0;
			if (isset($_GET['cdone'])) $ddone = $_GET['cdone'];
			else {
				$ddone = 0;
				$_GET['cdone'] = 0;
			}
			while ($row = mysql_fetch_assoc($start_categoriessearch)) {
				if (!$temp_cat_id) $temp_cat_id = $row['categories_id'];
				if (!isset($_GET['ctotalrows'])) $_GET['ctotalrows'] = mysql_num_rows($start_categoriessearch);
				$ddone = $_GET['cdone'] + $CSEOURLSDONE;
				if ($ddone == $_GET['ctotalrows']) {
					$CSEOURLSDONE = $_GET['limit'] + 1;
				}		   
				if ($temp_cat_id != $row['categories_id']) {
					mysql_query("update " . TABLE_CATEGORIES_DESCRIPTION . " set categories_surls_id = '" . $first_surlsid . "' where categories_id = '" . $temp_cat_id . "'");
					unset($first_surlsid);
					// update for a new categories_id
					$temp_cat_id = $row['categories_id'];
					if ($CSEOURLSDONE > $_GET['limit']){
						$done_text = "";
						$_GET['cdone'] = ($_GET['cdone'] + $CSEOURLSDONE-1);
						$locstring = "?";
						$junk = false;
						foreach($_GET as $key => $value){
							if ($junk) $locstring .= "&";
							$locstring .= $key."=".$value;
							if (!$junk) $junk = true;
						}
						// echo "Location: ". HTTP_SELF.FILENAME_SEO_URLS_REDIRECT.$locstring.$done_text;
						// exit;
						$percent_complete = number_format(($_GET['cdone']/$_GET['ctotalrows']*100),0);
						echo '<div style="width:100%;height:100%;margin:auto;"><div style="margin:auto; font-size:24px;">';
						echo 'CATEGORIES: <br />';
						echo 'Completed records: '.$_GET['cdone'].'<br />';
						echo 'Total records: '.$_GET['ctotalrows'].'<br />';
						echo $_GET['cdone'].' / '.$_GET['ctotalrows'].' ('.number_format(($_GET['cdone']/$_GET['ctotalrows']*100),0).'%)<br />';
						echo $_GET['cdone'].' / '.$_GET['ctotalrows'].' ('.$percent_complete.'%)<br />';
						$strftime_format = "%M:%S";
						if ($_GET['celapsed_time'] > 3600)
							$strftime_format = "%H:%M:%S";
						echo 'Elapsed time: '.strftime($strftime_format,$_GET['celapsed_time']).' (estimated remaining time: '.strftime($strftime_format,(($_GET['celapsed_time']/$percent_complete)*(100-$percent_complete))).')<br />';
						echo '</div></div>';
						echo '
								<script type="text/javascript">
									<!--
										window.location = "'.HTTP_SELF.FILENAME_SEO_URLS_REDIRECT.$locstring.$done_text.'"
									//-->
								</script>
						';		
						//header("Location: ". HTTP_SELF.FILENAME_SEO_URLS_REDIRECT.$locstring.$done_text);
						exit;
					}
				}
// echoarray($row);
				if (!isset($row['categories_name']) || (isset($row['categories_name']) && ($row['categories_name'] == "")))
					$row['categories_name'] = $row['categories_name_backup'];
					
				// get product cPath
				$categories_cPath = new_zen_get_path($row['categories_id']);
				//cleans new seo_url string
				$cppathid = $row['categories_id'];
				if (!isset($_GET['longurls']))
					$cleanedprodname = cleanurlstring($row['categories_name']);
				else{
					$categories_cPathTree = explode("_", str_replace("cPath=", "",$categories_cPath));
					// echoarray($product_cPathTree);
					$cat_cat = "";
					foreach($categories_cPathTree as $key => $value){
						$cNameSQL = "
							SELECT
								cd.categories_name as categories_name_backup,
								".$ADDITIONAL_CNAMEFIELDS['select']."
								cd.categories_id
							FROM
								".TABLE_CATEGORIES_DESCRIPTION." as cd
							WHERE
								cd.categories_id = '". $value."';
						";
					   $catname_query = mysql_query($cNameSQL);
					   // echo $cNameSQL."<br>";
					   // exit;
					   $catname_row = mysql_fetch_assoc($catname_query);


					   
						if (!isset($catname_row['categories_name']) || (isset($catname_row['categories_name']) && ($catname_row['categories_name'] == "")))
							$catname_row['categories_name'] = $catname_row['categories_name_backup'];
						$cat_cat .= cleanurlstring($catname_row['categories_name']);
						if ((count($categories_cPathTree) > 1) && ((count($categories_cPathTree)-1) > $key )) $cat_cat .= "/";
						// echo count($categories_cPathTree)."<br>";
						// echo $key."<br>";
						// echo $cat_cat."<br>";
					}
					$cleanedprodname = $cat_cat;
					// echo $cleanedprodname."<br>";
					// exit;
				}					
				//cleans new seo_url string
				// $cleanedprodname = cleanurlstring($row['categories_name']);
				// echo "test - B<br>";
					   
			   // make unique SEO URLS
			   $unique = 0;
			   while( $unique == 0 ) {
				   if( isset($multiple_name[$cleanedprodname]) && $multiple_name[$cleanedprodname] > 0 ) {
					 $cleanedprodname = $cleanedprodname . "-" . $multiple_name[$cleanedprodname];
					 // echo $cleanedprodname . "<br>";
				   }
					if (!isset($multiple_name[$cleanedprodname]))
						$multiple_name[$cleanedprodname] = 0;
					$multiple_name[$cleanedprodname]++;
				   
				   
				   $check_seourl = mysql_query("select surls_name from ".TABLE_SEO_URLS." where surls_name = '" . $cleanedprodname . "';");
				   $check_seourl_results = mysql_fetch_assoc($check_seourl);
				   if ( $check_seourl_results['surls_name'] == "" ) {
					$unique = 1;
				   }
			   }
				   
	   
			   //check for SEO_URL is already is use
			   // $ssql = "select surls_name from ".TABLE_SEO_URLS." where surls_name = '" . $cleanedprodname . "';";
			   //echo $ssql . "<br>";
			   // $check_seourl = mysql_query($ssql);

			   // assign new seo_urls id num
			   $new_surlsid = findlastseourlid() + 1;
			   $new_surlsid2 = $new_surlsid + 1;

			   //echo "test - C[b]<br>";
			   //echo "SEO<br>";
			   //echo "SEO Count: " . $check_seourl->RecordCount() . "<br>";
			   
			   // SEO URL record not found, go ahead and insert new record
				if ( $check_seourl_results['surls_name'] == "" ) {
			   
				 // Blank URL reference??? WHY? disable for now

				
					if( $cleanedprodname != "" ) {
			   			unset($surl_insert_sql2);
						unset($surl_insert_sql1);
						if(substr_count(strtolower(PROJECT_VERSION), 'oscommerce') > 0){
							if (!isset($direct_cpathlinks[$row['categories_id']])){
								if (!isset($first_surlsid)) $first_surlsid = $new_surlsid;
								// Covers product Cpath option
								$sql1_insert = "cPath=" . $row['categories_id'];
								$surl_insert_sql1 = "insert into " . TABLE_SEO_URLS . " (surls_id, surls_name, surls_script, surls_param, language_id) values ('" . $new_surlsid . "', '" . $cleanedprodname . "', 'index.php', '" . $sql1_insert . "', '" . $row['language_id'] . "')";
								// echo $sql1_insert . "<br>";
								$direct_cpathlinks[$row['categories_id']] = true;
								$direct_cpathlinks[$sql1_insert] = true;
								if (!($row['categories_id'] == str_replace("cPath=", "",$categories_cPath))){
									$sql2_insert = $categories_cPath;
									// Covers Product Cpath tree option
									$surl_insert_sql2 = "insert into " . TABLE_SEO_URLS . " (surls_id, surls_name, surls_script, surls_param, language_id) values ('" . $new_surlsid2 . "', '" . $cleanedprodname . "', 'index.php', '".$sql2_insert."', '" . $row['language_id'] . "')";
									//echo $sql3_insert . "<br>";
								}
							}else{
								if (!isset($direct_cpathlinks[$row['categories_id']])){
									// Covers product Cpath option
									$sql1_insert = "cPath=" . $row['categories_id'];
									$surl_insert_sql1 = "insert into " . TABLE_SEO_URLS . " (surls_id, surls_name, surls_script, surls_param, language_id) values ('" . $new_surlsid . "', '" . $cleanedprodname . "', 'index.php', '".$sql1_insert."', '" . $row['language_id'] . "')";
									echo $sql1_insert . "<br>";
									$direct_cpathlinks[$sql1_insert] = true;
								}
								if (!($row['categories_id'] == str_replace("cPath=", "",$categories_cPath))){
									$sql2_insert = $categories_cPath;
									// Covers Product Cpath tree option
									$surl_insert_sql2 = "insert into " . TABLE_SEO_URLS . " (surls_id, surls_name, surls_script, surls_param, language_id) values ('" . $new_surlsid2 . "', '" . $cleanedprodname . "', 'index.php', '".$sql2_insert."', '" . $row['language_id'] . "')";
									//echo $sql2_insert . "<br>";
								}
								// Covers product Cpath option
								//$surl_insert_sql1 = "insert into " . TABLE_SEO_URLS . " (surls_id, surls_name, surls_script, surls_param, language_id) values ('" . $new_surlsid . "', '" . $cleanedprodname . "', 'product_info.php', '" . $product_cPath . "&products_id=" . $pprodid . "', '" . $row['language_id'] . "')";
							}
						}else{
							$cat_param_prefix = "main_page=index&";
							if(substr_count(strtolower(PROJECT_VERSION), 'cre') > 0){
								$cat_param_prefix = "";
							}
						
							if (!isset($direct_cpathlinks[$row['categories_id']])){
								if (!isset($first_surlsid)) $first_surlsid = $new_surlsid;
								$surl_insert_sql1 = "insert into " . TABLE_SEO_URLS . " (surls_id, surls_name, surls_script, surls_param, language_id) values ('" . $new_surlsid . "', '" . $cleanedprodname . "', 'index.php', '" .$cat_param_prefix. $categories_cPath ."', '" . $row['language_id'] . "')";
								// echo $surl_insert_sql1; exit;
								$direct_cpathlinks[$row['categories_id']] = true;
							}
						}
						// echo "SURL Insert SQL: " . $surl_insert_sql1 . "<br>";
						if (isset($surl_insert_sql1)) mysql_query($surl_insert_sql1);
						if (isset($surl_insert_sql2)) mysql_query($surl_insert_sql2);
						if (isset($surl_insert_sql3)) mysql_query($surl_insert_sql3);
						$CSEOURLSDONE++;
					}
				}
			}
		}
		// Dummy check to insert if the limit was never met. Usually happens on the last product.
		if (isset($first_surlsid) && isset($temp_cat_id))
			mysql_query("update " . TABLE_CATEGORIES_DESCRIPTION . " set categories_surls_id = '" . $first_surlsid . "' where categories_id = '" . $temp_cat_id . "'");
		
		header("Location: ". HTTP_SELF.FILENAME_SEO_URLS_REDIRECT);
	}
			   
    if (isset($_GET['clear'])) {
	   switch($_GET['clear']) {
	   
		   case 'products':
			   mysql_query("UPDATE ".TABLE_PRODUCTS_DESCRIPTION." SET products_surls_id = 0");
			   mysql_query("DELETE FROM ".TABLE_SEO_URLS." WHERE surls_param LIKE '%products_id%';");
			   // mysql_query("DELETE FROM ".TABLE_SEO_URLS." WHERE surls_script = 'product_info.php' AND surls_param LIKE '%products_id%';");
			   break;
		   case 'categories':
			   mysql_query("UPDATE ".TABLE_CATEGORIES_DESCRIPTION." SET categories_surls_id = 0");
			   mysql_query("DELETE FROM ".TABLE_SEO_URLS." WHERE surls_script = 'index.php' AND surls_param LIKE '%cPath%' AND surls_param NOT LIKE '%products_id%';");
			   break;
	   }
	   // echo HTTP_SELF."addons/".FILENAME_SEO_URLS_REDIRECT;
	   // exit;
	   header("Location: ". HTTP_SELF.FILENAME_SEO_URLS_REDIRECT);
   }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_SELF;?>../includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_SELF;?>../includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="<?php echo HTTP_SELF;?>../includes/menu.js"></script>
<script language="javascript" src="<?php echo HTTP_SELF;?>../includes/general.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
  // -->
</script>
<script language="javascript"><!--
function getObject(name) {
   var ns4 = (document.layers) ? true : false;
   var w3c = (document.getElementById) ? true : false;
   var ie4 = (document.all) ? true : false;

   if (ns4) return eval('document.' + name);
   if (w3c) return document.getElementById(name);
   if (ie4) return eval('document.all.' + name);
   return false;
}
//--></script>
<style type="text/css">
  label{display:block;width:200px;float:left;}
  .limiters{width:200px;}
  .buttonRow{padding:5px 0;}
  .forward{float:right;}
  table#googleFiles { margin-left: 0px; border-collapse:collapse; border:1px solid #036; font-size: small; width: 100%; }
  table#googleFiles th { background-color:#036; border-bottom:1px double #fff; color: #fff; text-align:center; padding:8px; }
  table#googleFiles td { border:1px solid #036; vertical-align:top; padding:5px 10px; }
  #contentwrapper{float:left;width:100%;}
  #columnLeft{}
  .container{margin:0 10px 10px;}
  #columnRight{float:left;margin-left:-250px;width:250px;}
</style>
</head>
<body onload="init()">
<?php //echo HTTP_SELF;?>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<div id="body">

<?php
	if(defined('PROJECT_VERSION') && ((substr_count(strtolower(PROJECT_VERSION), 'oscommerce') > 0) || (substr_count(strtolower(PROJECT_VERSION), 'cre') > 0))){
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2" class="body-table">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
		<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
		</table>
	</td>
<!-- body_text //-->
    <td width="100%" valign="top">
<!-- body //-->
<?php
	}
?>
<div id="contentwrapper">
  <div id="columnLeft">
    <div class="container">
	
	  <div style="text-align: center;">
	  
      <h1><?php echo "SEO URL Auto-Populate"; ?></h1>
  
      <?php
	  
		   // echo "cPath: " . new_zen_get_path(69) . "<br>";
		
		   //products seo urls check
		   $unassigned_seo_urls_sql = "select count(*) as cnt from ".TABLE_PRODUCTS." p join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where (pd.products_surls_id is null or pd.products_surls_id = '' or pd.products_surls_id = '0')";

		   // echo $unassigned_seo_urls_sql;
		   $unassigned_seo_urls_count = mysql_query($unassigned_seo_urls_sql) or die('died'.mysql_error());
		   if (mysql_num_rows($unassigned_seo_urls_count) > 0 ){
				$unassigned_seo_urls_count_results = mysql_fetch_assoc($unassigned_seo_urls_count);
			}else{
				$unassigned_seo_urls_count_results['cnt'] = 0;
			}
		   //categories seo urls check
		   $unassigned_seo_urls_categories_count = mysql_query("select count(*) as cnt from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_surls_id is null or categories_surls_id = '' or categories_surls_id = '0';");
			if (mysql_num_rows($unassigned_seo_urls_categories_count) > 0 ){
				$unassigned_seo_urls_categories_count_results = mysql_fetch_assoc($unassigned_seo_urls_categories_count);
			}else{
				$unassigned_seo_urls_count_results['cnt'] = 0;
			}
		   		   
		   $totalcnt = $unassigned_seo_urls_count_results['cnt'] + $unassigned_seo_urls_categories_count_results['cnt'];
		   
			  
		   //  echo "total count: " . $totalcnt;
		   echo "<br>";
			   
	       if (!isset($_GET['action'])) {
			   if ($totalcnt > 0) {
			   //products seo urls count
			   if ($unassigned_seo_urls_count_results['cnt'] > 0) echo 'Products found without SEO_URLS assigned: <font color="red" >' . $unassigned_seo_urls_count_results['cnt'] . '</font><br>';

			   //categories seo urls count
			   if ($unassigned_seo_urls_categories_count_results['cnt'] > 0) echo 'Categories found without SEO_URLS assigned: <font color="red" >' . $unassigned_seo_urls_categories_count_results['cnt'] . '</font><br>';

			   echo '<hr style="width:50%">';
			   
			   //echo zen_draw_form('seo_url_autopopulate', FILENAME_SEOURL.'.php', $urlpage . 'action=run', 'post', '"');
			   echo '<form name="seo_url_autopopulate" action="'.HTTP_SELF.FILENAME_SEO_URLS_REDIRECT.'" method="get">';
			   echo '<input type="hidden" name="action" value="run"><input type="hidden" name="limit" value="500">';
			   echo '<table align=center cellpadding="10"><tr><td valign="top">';
			   $hyphenarrey = array('2','3','4','5','6','x');
			   echo 'Number of Hyphens to Allow:<br>';
			   echo '<hr style="width:50%">';
			   echo '<center><select name="amthyphens"><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="x" SELECTED>x</option></select></center><br>';
			   echo 'Allow numbers within seo_url?<br>';
			   echo '<hr style="width:50%">';
			   echo '<center><input type="checkbox" name="checksurlnum" CHECKED></center>';
			   echo 'Use Category Names in URL?<br>';
			   echo '<hr style="width:50%">';
			   echo '<center><input type="checkbox" name="longurls" CHECKED></center>';
			   echo '</td><td style="border-left:2px solid black" valign="top">';
			   echo 'Choose section(s) to assign SEO_URLS:<br>';
			   echo '<hr style="width:50%">';
			   if ($unassigned_seo_urls_count_results['cnt'] > 0) echo 'Products: <input type="checkbox" name="products" value="1" CHECKED><br>';
			   if ($unassigned_seo_urls_categories_count_results['cnt'] > 0) echo 'Categories: <input type="checkbox" name="categories" value="1" CHECKED><br>';

			   // if ($unassigned_seo_urls_pages_count['cnt'] > 0)echo 'Pages: <input type="checkbox" name="pages" value="1" CHECKED>';
			   echo '</td></tr></table>';
			   
			   echo '<hr style="width:50%">';
			   echo '<br><div style="padding: 3px;"><font style="font-family: arial; font-size: 9pt; font-weight: bold;">Click the button below to autopopulate SEO URLS</font></div>';
			   echo '<input type="image" src="../includes/languages/english/images/buttons/button_update.gif" border="0" alt="Submit changes" title=" Submit changes "></form>';
			   } else {
			   echo 'All products have an SEO_URL assigned.';
			   }
			}
			   
		   echo '<br><p><center><a href="'.HTTP_SELF.FILENAME_SEO_URLS_REDIRECT.'?clear=products">Clear Existing Products SEO URLS</a></center>';
		   echo '<center><a href="'.HTTP_SELF.FILENAME_SEO_URLS_REDIRECT.'?clear=categories">Clear Existing Categories SEO URLS</a></center>';
		   
		   if (isset($_GET['action']) && $_GET['action'] == 'run') {
		   //echo $newlinks;
		   if (isset($error)) echo $error;
		   }   
	  ?>

	  </div>
    </div>
  </div>
</div>
</div>

<?php
	if(defined('PROJECT_VERSION') && ((substr_count(strtolower(PROJECT_VERSION), 'oscommerce') > 0) || (substr_count(strtolower(PROJECT_VERSION), 'cre') > 0))){
?>
	</td>
  </tr>
 </table>
<?php
	}
?>
<div id="columnRight">
</div>
<div style="clear:both;">
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>