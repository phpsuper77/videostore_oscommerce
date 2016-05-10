<?php
		if (!function_exists("tep_redirect")){
			function tep_redirect($url){
				zen_redirect($url);
			}
		}
		if (!function_exists("tep_href_link")){
			function tep_href_link($page,$params){
				zen_href_link($page,$params);
			}
		}

		// category seo url
		$category_seo_url = $_POST['surls_name'];
		$surls_id = (int)$_POST['surls_id'];
		$seo_url_oldname = $_POST['surls_oldname'];

		// if surls_id is being passed, just update
		if( $surls_id > 0 ) {
		
			// update existing SEO URL by surls_id lookup
			$surls_name = $category_seo_url;

			// modify surls_name
			$surls_name = str_replace(" ", "", $surls_name);
			$surls_name = str_replace("_", "-", $surls_name);
			$surls_name = str_replace("?", "", $surls_name);
			$surls_name = str_replace("&", "", $surls_name);
			// $surls_name = str_replace("/", "", $surls_name);
			$surls_name = str_replace("\\", "", $surls_name);

			$error = 0;
		  
			// if directory already exists, notify admin user that this is an error and they have to choose an alternate name
			if( is_dir("../../" . $surls_name) ) {
			  $messageStack->add_session('Sorry, there is a folder under your store directory with the same name.  Please choose a different SEO URL.', 'error');
			  tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
			  $error = 1;
			}
			
			// does surl_name already exist?
			$surl_name_check_sql = "
				SELECT
					seo.surls_name 
				FROM
					" . TABLE_SEO_URLS . " as seo
				WHERE
					seo.surls_name = '" . $surls_name . "'
				AND
					seo.surls_name != '" . $surls_oldname . "';";
						// $surl_name_check_sql = "select seo.surls_name from " . TABLE_SEO_URLS . " seo where seo.surls_id != '".$surls_id."' and seo.surls_name = '" . $surls_name . "' and seo.surls_param != 'cPath=" . $categories_id . "'";
			$surl_name_check = mysql_query($surl_name_check_sql);
			$surl_name_exists = mysql_num_rows($surl_name_check) > 0 ? true : false;
		  
			if( $surl_name_exists ) {
			  $messageStack->add_session('Sorry, that SEO URL name already exists.  Please choose another one.', 'error');
			  tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
			  $error = 1;
			}
			
			if( $error == 0 ) {
				$sql = "
					UPDATE
						".TABLE_SEO_URLS."
					SET
						surls_name = '".$surls_name."'
					WHERE
						surls_name = '".$seo_url_oldname."';
				";
				// $sql_data_array = array('surls_name' => tep_db_prepare_input($surls_name) );
				// tep_db_perform(TABLE_SEO_URLS, $sql_data_array, 'update', "surls_id = '" . (int)$surls_id . "'");
				// echo "test";
				mysql_query($sql);
			}
		}
		else {
			
		}
?>