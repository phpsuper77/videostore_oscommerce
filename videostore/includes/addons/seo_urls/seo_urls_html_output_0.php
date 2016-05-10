<?php
	if (!function_exists("new_zen_get_path")){
		  function new_zen_get_path($current_category_id = '') {
			global $db;

			$cPath_array = array();
			
			$cPath_array[] = $current_category_id;
			
			if (tep_not_null($current_category_id)) {
			
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
	function get_category_id($prod_id){
		$main_category_query = "select categories_id
								   from " . TABLE_PRODUCTS_TO_CATEGORIES . "
								   where products_id = '" . $prod_id . "'";

		$main_category = mysql_query($main_category_query);
		if (mysql_num_rows($main_category) > 0)
			$main_category_results = mysql_fetch_assoc($main_category);
		else
			$main_category_results['categories_id'] = 0;
		$main_category = $main_category_results['categories_id'];
		return $main_category;
	}
	function get_cache_surl($surls_script, $surls_param, $language_id) {
		global $surls_cache;

		//if (debug_page($surls_script)) echo "test1<br>";
		$empty = array('surls_id' => '', 'surls_name' => '', 'surls_script' => '');
		$cacheId = strval($language_id) . ':' . $surls_script . ':' . $surls_param;
		if (isset($surls_cache[$cacheId]) && ($surls_cache[$cacheId]['surls_id'] == "")) unset($surls_cache[$cacheId]);

		if (!isset($surls_cache[$cacheId])) {
			//if (debug_page($surls_param)) echo "test3<br>";
			if ($surls_script) $script_query = "su.surls_script = '" . $surls_script . "' and ";
			else $script_query = '';
	  
			/*
			// Code to handle cPathTrees from links only sending us the cPath
			if (strstr($surls_param, 'cPath=')){
				$surls_param_temp = explode("&",$surls_param);
				$surls_param = "";
				$counter = 0;
				$prod_id = false;
				// Run through the array and grab the product ID if it's there.
				foreach($surls_param_temp as $key => $value){
					$surls_param_temp2 = explode("=",$value);
					if ($surls_param_temp2[0] == 'products_id')
						$prod_id = $surls_param_temp2[1];
				}
				foreach($surls_param_temp as $key => $value){
					$surls_param_temp2 = explode("=",$value);
					echoarray_($surls_param_temp2);
					$counter++;
					if ($surls_param_temp2[0] == 'cPath'){
						if ($prod_id)
							$surls_param_temp2[1] = new_zen_get_path(get_category_id($prod_id));
						else
							$surls_param_temp2[1] = new_zen_get_path($surls_param_temp2[1]);
						$surls_param .= $surls_param_temp2[1];
					}else
						$surls_param .= $surls_param_temp2[0].'='.$surls_param_temp2[1];
					if ($counter < count($surls_param_temp))
						$surls_param .= "&";
				}
			}
			*/
			// echo_($surls_param.' - '.$counter);
			$language_id = 1;
	  
			$surls_sql = "
				select 
					su.surls_id, 
					su.surls_name, 
					su.surls_script 
				from " . TABLE_SEO_URLS . " su 
					where " . $script_query . "su.surls_param = '" . $surls_param . "' and su.language_id = '" . (int)$language_id . "'
				ORDER BY
					su.surls_id
				ASC;";
			if (($surls_param != '') && strstr($surls_param, '&products_id=7240')) {
				// echo_($surls_sql);
				// exit;
			}
			if ($surls_param == "products_id=31"){
				// echo $surls_sql."<br>";
				// echo $surls_script.", ".$surls_param.", ".$language_id."||<br>";
			}

			$surls_query = mysql_query($surls_sql);
	  
			if (mysql_num_rows($surls_query) > 0) $surls_cache[$cacheId] = mysql_fetch_array($surls_query);
			else $surls_cache[$cacheId] = $empty;
		}
		return $surls_cache[$cacheId];
	}
	function get_surls_page($page, $surls_param, $language_id, &$surls_script) {
		$surls = tep_get_cache_surl($surls_script, $surls_param, $language_id);
		if ($surls['surls_name']) {
			$surls_script = $surls['surls_script'];
			return $surls['surls_name'] . '/';
		} else return '';
	}		
	function get_script_surls_name($surls_script, $language_id, &$surls_id) {
		$surls_id = NULL;
		$surls = tep_get_cache_surl($surls_script, '', $language_id);
		if ($surls['surls_name']) {
			$surls_id = $surls['surls_id'];
			return $surls['surls_name'] . '/';
		} else return '';
	}
	function href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true, $static = false, $use_dir_ws_catalog = true){
		if (function_exists('zen_href_link'))
			return zencart_href_link($page, $parameters, $connection, $add_session_id, $search_engine_safe, $static, $use_dir_ws_catalog);
		if (function_exists('tep_href_link'))
			return oscommerce_href_link($page, $parameters, $connection, $add_session_id, $search_engine_safe, $static, $use_dir_ws_catalog);
		
	}
	
	
	if (!function_exists('tep_get_surls_page')){
		function tep_get_surls_page($page, $surls_param, $language_id, &$surls_script) {
			return get_surls_page($page, $surls_param, $language_id, &$surls_script);
		}
	}
	if (!function_exists('tep_get_script_surls_name')){
		function tep_get_script_surls_name($surls_script, $language_id, &$surls_id) {
			return get_script_surls_name($surls_script, $language_id, &$surls_id);
		}
	}
	if (!function_exists('tep_get_cache_surl')){
		function tep_get_cache_surl($surls_script, $surls_param, $language_id) {
			return get_cache_surl($surls_script, $surls_param, $language_id);
		}	
	}
	if (!function_exists('zen_get_surls_page')){
		function zen_get_surls_page($page, $surls_param, $language_id, &$surls_script) {
			return get_surls_page($page, $surls_param, $language_id, &$surls_script);
		}
	}
	if (!function_exists('zen_get_script_surls_name')){
		function zen_get_script_surls_name($surls_script, $language_id, &$surls_id) {
			return get_script_surls_name($surls_script, $language_id, &$surls_id);
		}
	}
	if (!function_exists('zen_get_cache_surl')){
		function zen_get_cache_surl($surls_script, $surls_param, $language_id) {
			return get_cache_surl($surls_script, $surls_param, $language_id);
		}	
	}
	
	function zencart_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true, $static = false, $use_dir_ws_catalog = true) {
		global $request_type, $session_started, $http_domain, $https_domain, $db;

		if (!zen_not_null($page)) {
			die('</td></tr></table></td></tr></table><br /><br /><strong class="note">Error!<br /><br />Unable to determine the page link!</strong><br /><br /><!--' . $page . '<br />' . $parameters . ' -->');
		}


		////////// seo urls //////////
		$seo_link = "";
		if( strstr($parameters, "cPath") ) {
			$seo_urls_sql = "
								SELECT
									surls_name
								FROM
									" . TABLE_SEO_URLS . "
								WHERE
									surls_param like 'main_page=index&" . $parameters . "'
								OR
									surls_param like 'main_page=product_info&" . $parameters . "'
								LIMIT
									1
			";

			// echo $seo_urls_sql . "<br>";

			$seo_urls = $db->Execute($seo_urls_sql);

			if ($seo_urls->RecordCount()) {
				$seo_link = $seo_urls->fields['surls_name'];

			// echo $seo_link . "<br>";
			}
		}
		else {

			// echo $parameters . "<br>";

			$datablocks = explode("&", $parameters);
			foreach( $datablocks as $aa ) {
				$dv = explode("=", $aa);
				//$$dv[0] = $dv[1];
				/*
				if ($dv[1] == 239) {

				echo $dv[0] . "=" . $dv[1] . "<br>";
				}
				*/
				if ($dv[0] == "products_id"){
					$products_id = $dv[1];
				}
			}

			$seo_urls_sql = "
							SELECT
								surls_name
							FROM
								" . TABLE_SEO_URLS . "
							WHERE
								surls_param like '%main_page=product_info%'
							AND
								surls_param like '%products_id=" . (int)$products_id . "%'
							LIMIT
								1
			";

			// echo $seo_urls_sql . "<br>";

			$seo_urls = $db->Execute($seo_urls_sql);

			if ($seo_urls->RecordCount()) {
				if( $products_id != "" ) {
					$seo_link = $seo_urls->fields['surls_name'];
				}
			}
		}
		//////////////////////////////



		// echo "enable SSL: " . ENABLE_SSL . "<br>";

		if ($connection == 'NONSSL') {
			$link = HTTP_SERVER;
		} elseif ($connection == 'SSL') {
			if (ENABLE_SSL == 'true') {
				$link = HTTPS_SERVER ;
			} else {
				$link = HTTP_SERVER;
			}
		} else {
			die('</td></tr></table></td></tr></table><br /><br /><strong class="note">Error!<br /><br />Unable to determine connection method on a link!<br /><br />Known methods: NONSSL SSL</strong><br /><br />');
		}

		if ($use_dir_ws_catalog) {
			if ($connection == 'SSL' && ENABLE_SSL == 'true') {
				$link .= DIR_WS_HTTPS_CATALOG;
			} else {
				$link .= DIR_WS_CATALOG;
			}
		}


		// echo "page: " . $page . "<br>";


		$main_page = $_GET['main_page'];

		// $static = true;
		if (!$static) {
			if (zen_not_null($parameters)) {

				$link .= 'index.php?main_page='. $page . "&" . zen_output_string($parameters);
			} else {
				$link .= 'index.php?main_page=' . $page;
			}
		} else {
			if (zen_not_null($parameters)) {
				$link .= $page . "?" . zen_output_string($parameters);
			} else {
				$link .= $page;
			}
		}

		$separator = '&';

		while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

		// Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
		if ( ($add_session_id == true) && ($session_started == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
			if (defined('SID') && zen_not_null(SID)) {
				$sid = SID;
			//      } elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL_ADMIN == 'true') ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) {
			} elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == 'true') ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) {
				if ($http_domain != $https_domain) {
					$sid = zen_session_name() . '=' . zen_session_id();
				}
			}
		}

		// clean up the link before processing
		while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);
		while (strstr($link, '&amp;&amp;')) $link = str_replace('&amp;&amp;', '&amp;', $link);

		if ( (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && ($search_engine_safe == true) ) {
			while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

			$link = str_replace('&amp;', '/', $link);
			$link = str_replace('?', '/', $link);
			$link = str_replace('&', '/', $link);
			$link = str_replace('=', '/', $link);

			$separator = '?';
		}

		if (isset($sid)) {
			$link .= $separator . zen_output_string($sid);
		}

		// clean up the link after processing
		while (strstr($link, '&amp;&amp;')) $link = str_replace('&amp;&amp;', '&amp;', $link);

		$link = preg_replace('/&/', '&amp;', $link);


		if (isset($sid)) {
			$seo_linkr = "?" . zen_output_string($sid);
		}


		if( $seo_link != "" ) return $seo_link . "/" . $seo_linkr;

		return $link;
	}
	
	function oscommerce_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
		global $request_type, $session_started, $SID;
		global $kill_sid, $_GET;
/*
	//if (debug_page($parameters)) echo $page." - test1<br>";
	//return 1;
			$languages_id = 1;
		$search_engine_friendly_urls = SEARCH_ENGINE_FRIENDLY_URLS;
		$search_engine_friendly_urls = 'true';
		
		// start seo url tool - osCommerceWebDevelopment.com
		$surls_page = false; $surls_script = $page; $no_surls = false;
		if ( ($connection == 'NONSSL') && ($search_engine_friendly_urls == 'true') && ($search_engine_safe == true) && !preg_match("/(action=[^\&]*)(?:\&|$)/s",$parameters,$templist) ) {
		
		  if (preg_match("/sort=(([0-8])[ad])(?:\&|$)/s",$parameters,$templist,PREG_OFFSET_CAPTURE)) {
			if ($templist[2][0] != '0' || $templist[2][0].'d' == $templist[1][0]) $no_surls = true;
			else $parameters = substr_replace($parameters,'',$templist[0][1],strlen($templist[0][0]));
		  }
		  if (preg_match("/currency=(\w+)(?:\&|$)/s",$parameters,$templist,PREG_OFFSET_CAPTURE)) $no_surls = true;
		  if (!$no_surls) {
			$original_parameters = $parameters;
			$surls_lid = $languages_id;
			if (preg_match("/language=(\w+)(?:\&|$)/s",$parameters,$templist,PREG_OFFSET_CAPTURE)) {
			  if (!is_numeric($templist[1][0]) || $surls_lid != $templist[1][0]) {
				if ( is_numeric($templist[1][0]) ) {
				  foreach( $lng->catalog_languages as $code => $catalog_language ) {
					if ( $templist[1][0] == $catalog_language['id'] ) {
					  $surls_lid = $catalog_language['id'];
					  break;
					}
				  }
				} elseif ( isset($lng->catalog_languages[$templist[1][0]]) )
				  $surls_lid = $lng->catalog_languages[$templist[1][0]]['id'];
			  }
			  $parameters = substr_replace($parameters,'',$templist[0][1],strlen($templist[0][0]));
			}
			if (preg_match("/((products|articles|pages)_id=(\d*))(?:\&|$)/s",$parameters,$templist,PREG_OFFSET_CAPTURE)){
			//if (debug_page($parameters)) echo strlen($templist[3][0])."<br>".$templist[3][0]."<br>";

				if ( !strlen($templist[3][0]) || ($page == ($templist[2][0]=='products'?FILENAME_PRODUCT_INFO:($templist[2][0]=='articles'?FILENAME_ARTICLE_INFO:FILENAME_PAGES))) ){
				if ($surls_page = tep_get_surls_page($page, $templist[1][0], $surls_lid, $surls_script))
					$parameters = substr_replace($parameters,'',$templist[0][1],strlen($templist[0][0]));
				//if (debug_page($parameters)) echo $surls_page.", ".$page.", ".$templist[1][0].", ".$surls_lid.", ".$surls_script.", ".$surls_id."||<br>";
			}
		}
			if (preg_match("/((manufacturers|authors)_id=(\d*))(?:\&|$)/s",$parameters,$templist,PREG_OFFSET_CAPTURE)
			 && (strlen($surls_page) || !strlen($templist[3][0]) || ($surls_page === false && $page == ($templist[2][0]=='manufacturers'?FILENAME_DEFAULT:FILENAME_ARTICLES) && ($surls_page = tep_get_surls_page($page, $templist[1][0], $surls_lid, $surls_script)))))
			  $parameters = substr_replace($parameters,'',$templist[0][1],strlen($templist[0][0]));
			if (preg_match("/(c|t)Path=(?:\d+_)*(\d*)(?:\&|$)/s",$parameters,$templist,PREG_OFFSET_CAPTURE)
			 && (strlen($surls_page) || !strlen($templist[2][0]) || ($surls_page === false && $page == ($templist[1][0]=='c'?FILENAME_DEFAULT:FILENAME_ARTICLES) && ($surls_page = tep_get_surls_page($page, $templist[1][0].'Path='.$templist[2][0], $surls_lid, $surls_script)))))
			  $parameters = substr_replace($parameters,'',$templist[0][1],strlen($templist[0][0]));

	//echo "surls_page:".gettype($surls_page)."<br>";
		  if ($parameters == "products_id=31"){
	//$page, $templist[1][0], $surls_lid, $surls_script
			// echo $page.", ".$templist[1][0].", ".$surls_lid.", ".$surls_script.", ".$surls_id."||<br>";
		}
			  if ($surls_page === false) $surls_page = tep_get_script_surls_name($page, $surls_lid, $surls_id);
			if ($surls_page) {
			  if (preg_match("/(page|reviews_id|testimonial_id)=(\d*)(?:\&|$)/s",$parameters,$templist,PREG_OFFSET_CAPTURE)) {
				$parameters = substr_replace($parameters,'',$templist[0][1],strlen($templist[0][0]));
				if (strlen($templist[2][0]) && ($templist[1][0] != 'page' || $templist[2][0] != '1')) $page = substr_replace($page, '-' . $templist[2][0], strrpos($page,'.'), 0);
			  }
			  if ($surls_script == $page) $page = $surls_page;
			  else $page = $surls_page . $page;
			} else {
			  $parameters = $original_parameters;
			  if ($page == FILENAME_DEFAULT && (!$parameters || $parameters == '&')) $page = '';
			}
		//if (debug_page($parameters)) echo $parameters." - test2<br>";
		//if (debug_page($parameters)) echo $page." - test3<br>";
		////if (debug_page($parameters)) echo $page.", ".$templist[1][0].", ".$surls_lid.", ".$surls_script.", ".$surls_id."||<br>";
	//	echo $parameters."<br>";
	//	echo $page."<br>";

	//	if ($page == "product_info.php" && substr_count($parameters, "=31") > 0){
		if ($page == "product_info.php" && ($parameters == "products_id=31")){
			//echo $page." - ".$parameters." - ".$surls_page." - ".$surls_lid." - ".$surls_id." - <pre>".print_r($templist,true)."</pre><br>";
		}

			}
		}
		if (substr(trim($parameters),-1)=='&') $parameters = substr(trim($parameters),0,strlen(trim($parameters))-1);
		// end seo url tool
*/		
		// if (strstr($parameters,"3579"))
		// $surls_page = "";
		// $backup_page = $page;
		// if(($page == "index.php") || ($page == "product_info.php")){
		// echo $page."<br>";
		$new_params = explode("&",$parameters);
		foreach($new_params as $key => $value){
			$temp_explode = explode("=",str_replace("?","",$value));
			$new_params2[$temp_explode[0]] = $temp_explode[1];
		}
		$languages_id = 1;
		$surls_lid = $languages_id;
		$orig_parameters = $parameters;
/*
		if (preg_match("/(page|reviews_id|testimonial_id)=(\d*)(?:\&|$)/s",$parameters,$templist,PREG_OFFSET_CAPTURE)) {
			// echo "<pre>".print_r($templist,true)."</pre>";
			// exit;
			$parameters = substr_replace($parameters,'',($templist[0][1]-1),(strlen($templist[0][0])+1));
			if (strlen($templist[2][0]) && $templist[2][0] != '1') $page_index = substr_replace($page, '-' . $templist[2][0], strrpos($page,'.'), 0);
		}
*/
		// echoarray_($new_params2);
		// if( strstr($parameters, "product_id") ) $page = "product_info.php";
		// else $page = "index.php";
		$backup_page = $page;
		if (isset($new_params2['cPath'])){
			// $page = "index.php";
			// Int the cPath if it's a cPath Tree
			if (strstr($_GET['cPath'],"_")){
				$junk = explode("_",$_GET['cPath']);
				// echo "$junk = <pre>".print_r($junk,false)."</pre><br>";
				$_GET['cPath'] = "";
				$junk_count = count($junk)-1;
				foreach ($junk as $key => $value){
					$_GET['cPath'] .= (int)$value;
					if ($key < $junk_count)
						$_GET['cPath'] .= "_";
					// echo $_GET['cPath']."<br>";
				}
			}			
			// if (strstr($new_params2['cPath'],"_")){
				// $junk = explode("_",$new_params2['cPath'],2);
				// echoarray($junk);
				// $new_params2['cPath'] = (int)$junk[0]."_".(int)$junk[1];
			// }
			$parameters = "cPath=".$new_params2['cPath'];
			if (isset($new_params2['products_id'])) {
				// $page = "product_info.php";
				$parameters .= "&products_id=".(int)$new_params2['products_id'];
			}
		}elseif (isset($new_params2['products_id'])) {
			// $page = "product_info.php";
			$parameters = "products_id=".(int)$new_params2['products_id'];
		}
		$surls_script = $page;

		// echoarray_($parameters);
		$surls_page = tep_get_surls_page($page, $parameters, $surls_lid, $surls_script);
		// echo $parameters."<br>";
		// echo $surls_page."<br>";
		// echo "<pre>".print_r($new_params2, true)."</pre><br>";


		
		
		if ($surls_page != ''){
			// echo_($page." - ".$parameters." - ".$surls_page." - ".$surls_lid." - ".$surls_id."<br>");
			$page = $surls_page;
			if (isset($new_params2['page']) && ($new_params2['page'] > 1)) $page .= "index-".$new_params2['page'].".php";
		
			$separator = '?';
			$first_param = false;
			foreach($new_params2 as $key => $value){
				if ( ($key != 'cPath') && ($key != 'products_id') && ($key != 'page') ){
					if ($key != ""){
						$separator = '&amp;';
						if (!$first_param){
							$first_param = true;
							$page .= "?";
						}else{
							$page .= "&amp;";
						}
						$page .= $key."=".$value;
					}
				}
			}
		}else{
			$page = $backup_page;
			$separator = '?';
			$first_param = false;
			foreach($new_params2 as $key => $value){
				if ($key != ""){
					$separator = '&amp;';
					if (!$first_param){
						$first_param = true;
						$page .= "?";
					}else{
						$page .= "&amp;";
					}
					$page .= $key."=".$value;
				}
			}
		}
/*
		if ($surls_page != ''){
		
			if ($surls_page) {
				if (preg_match("/(page|reviews_id|testimonial_id)=(\d*)(?:\&|$)/s",$parameters,$templist,PREG_OFFSET_CAPTURE)) {
					$parameters = substr_replace($parameters,'',$templist[0][1],strlen($templist[0][0]));
					if (strlen($templist[2][0]) && ($templist[1][0] != 'page' || $templist[2][0] != '1')) $page = substr_replace($page, '-' . $templist[2][0], strrpos($page,'.'), 0);
				}
				if ($surls_script == $page) $page = $surls_page;
				else $page = $surls_page;// . $page;
			} else {
				$parameters = $original_parameters;
				//if ($page == FILENAME_DEFAULT && (!$parameters || $parameters == '&')) $page = '';
			}
			

			//  && ($surls_page = tep_get_surls_page($page, $templist[1][0].'Path='.$templist[2][0], $surls_lid, $surls_script))
			if (preg_match("/(c|t)Path=(?:\d+_)*(\d*)(?:\&|$)/s",$parameters,$templist,PREG_OFFSET_CAPTURE)
			 && (strlen($surls_page) || !strlen($templist[2][0]) || ($surls_page === false && $page == ($templist[1][0]=='c'?FILENAME_DEFAULT:FILENAME_ARTICLES))))
			  $parameters = substr_replace($parameters,'',$templist[0][1],strlen($templist[0][0]));

			  
			if (preg_match("/((products|articles|pages)_id=(\d*))(?:\&|$)/s",$parameters,$templist,PREG_OFFSET_CAPTURE)){
				// if ($orig_parameters == "?products_id=8931"){
					// echo "<pre>".print_r($templist,true)."</pre>";
					// exit;					
				// }
				if (isset($templist[0]))
					$parameters = substr_replace($parameters,'',$templist[0][1],strlen($templist[0][0]));
			}

			if (isset($page_index)){
				$page = $page.$page_index;
			}
		}
*/

		if ($connection == 'NONSSL') {
		  $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
		} elseif ($connection == 'SSL') {
		  if (ENABLE_SSL == true) {
			$link = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG;
		  } else {
			$link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
		  }
		} else {
		  die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br><br>');
		}
/*		
		if ($_GET['language'] && $kill_sid) {
		  $l = ereg('[&\?/]?language[=/][a-z][a-z]', $parameters, $m);
		  if ($l) {
			$parameters = ereg_replace("[&\?/]?language[=/][a-z][a-z]", "", $parameters);
			$_GET['language'] = substr($m[0],-2);
		  }
		  if (tep_not_null($parameters)) {
			$parameters .= "&language=" . $_GET['language'];
		  } else {
			$parameters = "language=" . $_GET['language'];
		  }
		}
*/
		
		 $link .= $page;
/*
		if (tep_not_null($parameters)) {
		  $link .= $page . '?' . tep_output_string($parameters);
		  $separator = '&';
		} else {
		  $link .= $page;
		  $separator = '?';
		  if(strpos($page,"=") ){
			$separator = '&';
			}
		}

		while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);
*/

	// Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
		if ( ($add_session_id == true) && ($session_started == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
		  if (tep_not_null($SID)) {
			$_sid = $SID;
		  } elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) {
			if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
			  $_sid = tep_session_name() . '=' . tep_session_id();
			}
		  }
		}

	
		/*
		if ( (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && ($search_engine_safe == true) ) {
		  while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

		  $link = str_replace('?', '/', $link);
		  $link = str_replace('&', '/', $link);
		  $link = str_replace('=', '/', $link);

		  $separator = '?';
		}
		*/
		
		/*if (isset($_sid)) {
		  $link .= $separator . tep_output_string($_sid);
		}*/
		

			
		if (isset($_sid) && ( !$kill_sid ) ) {
			  $link .= $separator . $_sid;
			}
			// echo $link . ' - ' . $orig_parameters;
		 return $link;
	}	
?>
