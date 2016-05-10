<?php
		// products seo url
		$products_seo_url = $_POST['surls_name'];
		$surls_id = (int)$_POST['surls_id'];
		$seo_url_oldname = $_POST['surls_oldname'];
		if (!isset($categories_id)) $categories_id = $_POST['cID'];
		// if surls_id is being passed, just update
		if( $surls_id > 0 ) {
		
			// update existing SEO URL by surls_id lookup
			$surls_name = $products_seo_url;

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
			//$surl_name_check_sql = "select seo.surls_name from " . TABLE_SEO_URLS . " seo where seo.surls_name = '" . $surls_name . "' and seo.surls_param NOT LIKE '%cPath=" . $categories_id . "' AND seo.surls_param NOT LIKE 'cPath=" . (int)$categories_id . "&products_id=".(int)$products_id."' and seo.surls_id != '" . $surls_id . "'";
			// $surl_name_check_sql = "select seo.surls_name from " . TABLE_SEO_URLS . " seo where seo.surls_name = '" . $surls_name . "' and seo.surls_param NOT LIKE '%cPath=" . $categories_id . "%' and seo.surls_id != '" . $surls_id . "'";
			$surl_name_check = tep_db_query($surl_name_check_sql);
			$surl_name_exists = tep_db_num_rows($surl_name_check) > 0 ? true : false;
		  
			if( $surl_name_exists ) {
			  $messageStack->add_session('Sorry, that SEO URL name already exists.  Please choose another one. - '.$surl_name_check_sql, 'error');
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
				mysql_query($sql);
			}
		}
		else {
			
		}
		// if (isset($sql_data_array['surls_name']))
			// unset($sql_data_array['surls_name']);
?>