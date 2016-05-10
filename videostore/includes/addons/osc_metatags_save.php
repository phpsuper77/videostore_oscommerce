<?php

	define('OSC_BASE_KEYWORDS','');
	define('OSC_DEFAULT_DESC','');

	define('OSC_ENFORCE_TITLE_LIMIT', false);
	define('OSC_ENFORCE_KEYWORD_LIMIT', true);
	// Values can be 2006 or 1996
	define('OSC_KEYWORD_LIMIT_TYPE', '2006');

	
	// Advanced Setting
	// This is a regular expression to cause specific characters to appear in the description tag.
	define('OSC_CUSTOM_EREG','’\'\-\,\#\@\.');
	// define('OSC_CUSTOM_EREG','\-');
/*													*
 *		 DO NOT EDIT ANYTHING BELOW THIS LINE 		*
 *													*/

 	// error_reporting(E_ALL);
	// ini_set('display_errors', '1');
	/*
		1996 Rules:
		* Title tag: 5 - 10 words, including the company name and relevant keywords, 90 characters with spaces (90 cws).
		* Description tag: Concise summary of the page, an upper limit of perhaps, 170 characters with spaces (170 cws).
		* Keyword tag: An upper limit of 900 characters with spaces - keep it simple and relevant. 10 - 20 Keywords per page (900 cws). 
		* 			   
		
		2006 Rules:
			<title>17 words, 131 characters with spaces</title>
			<meta name="keywords" content="57 words, 450 characters with spaces" />
			<meta name="description" content="43 words, 223 characters with spaces" />		
	*/
	$osc_rules_array = array(
		'1996' => array(
					'title_limit' => array(
										'words' => 10,
										'chars' => 90
					),
					'description_limit' => array(
										'words' => -1,
										'chars' => 170
					),
					'keywords_limit' => array(
										'words' => 20,
										'chars' => 900
					)
		),
		'2006' => array(
					'title_limit' => array(
										'words' => 17,
										'chars' => 131
					),
					'description_limit' => array(
										'words' => 43,
										'chars' => 223
					),
					'keywords_limit' => array(
										'words' => 57,
										'chars' => 450
					)
		)
	);
		// error_reporting(E_ALL);
	// ini_set('display_errors', '1');
	if (!defined('STORE_NAME')) define('STORE_NAME',"");
	define('OSC_BASE_TITLE', STORE_NAME);
	define('OSC_TITLE_SEPERATOR'," | ");
	define('OSC_TITLE_SEPERATOR_PARSE',"||");
	define('OSC_METATAG_SEPERATOR',", ");
/*
CREATE TABLE `osc_metatags` (
`categories_id` VARCHAR( 32 ) NOT NULL ,
`products_id` INT NOT NULL ,
`metatags_title` VARCHAR( 90 ) NOT NULL ,
`metatags_h1` VARCHAR( 128 ) NOT NULL ,
`metatags_imgalt` VARCHAR( 128 ) NOT NULL ,
`metatags_description` VARCHAR( 170 ) NOT NULL ,
`metatags_keywords` TEXT NOT NULL
)
*/
	$ran_editable_check = false;
	if( table_exists(TABLE_OSC_METATAGS) ){
		if (isset($_GET['products_id']))
			$editable_metatag_sql_whereclause = "mt.products_id = '".$_GET['products_id']."'";
		else
			$editable_metatag_sql_whereclause = "mt.categories_id = '".$_GET['cPath']."'";
		$editable_metatag_sql = "
			SELECT
				metatags_title,
				metatags_h1,
				metatags_imgalt,
				metatags_description,
				metatags_keywords
			FROM
				".TABLE_OSC_METATAGS." as mt
			WHERE
				".$editable_metatag_sql_whereclause."
		;";
		$editable_metatag_search = mysql_query($editable_metatag_sql) or die('died ('.$editable_metatag_sql.'):'.mysql_error());
		$ran_editable_check = true;
	}
	if($ran_editable_check && mysql_num_rows($editable_metatag_search)>0){
		$row = mysql_fetch_assoc($metatagsearch);
		$osc_metatags = $row;
		$OSC_TITLE = $osc_metatags['metatags_title'];
		$OSC_DESC = $osc_metatags['metatags_description'];
		$OSC_KEYWORDS = $osc_metatags['metatags_keywords'];
		$OSC_H1_TAG = $osc_metatags['metatags_h1'];
		$OSC_IMGALT_TAG = $osc_metatags['metatags_imgalt'];
	}else{
		$OSC_TITLE = "";
		if (isset($_GET['cPath'])){
			// echo "incoming cPath: ".$_GET['cPath']."<br>";
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
			}else{
				$junk[0] = $_GET['cPath'];
			}
			// $cPathSQL = "
				// SELECT
					// meta.metatags_title,
					// meta.metatags_h1,
					// meta.metatags_imgalt,
					// meta.metatags_description,
					// meta.metatags_keywords
				// FROM
					// ".TABLE_METATAGS." as meta
				// WHERE
					// meta.categories_id = '".$_GET['cPath']."';
			// ";
			// echo $_GET['cPath']."<br>";
			// echo "<pre>".print_r($junk,false)."</pre><br>";
			
			// Reverse the category IDs so we can look from the most relevent upwards the chain.
			$junk = array_reverse($junk);
			
			// echo "<pre>".print_r($junk)."</pre><br>";
			foreach($junk as $key => $value){
				// echo $value."<Br>";
				if (checkField(TABLE_CATEGORIES_DESCRIPTION,"categories_description")){
					$cPathSQL = "
						SELECT
							cd.categories_name,
							cd.categories_description,
							c.categories_image
						FROM
							".TABLE_CATEGORIES." as c
						LEFT JOIN
							".TABLE_CATEGORIES_DESCRIPTION." as cd
						ON
							((c.categories_id = cd.categories_id)
						AND
							(cd.language_id = '1'))				
						WHERE
							c.categories_id = '".(int)$value."';
					";
				}else{
					$cPathSQL = "
						SELECT
							cd.categories_name,
							cd.categories_name as categories_description,
							c.categories_image
						FROM
							".TABLE_CATEGORIES." as c
						LEFT JOIN
							".TABLE_CATEGORIES_DESCRIPTION." as cd
						ON
							((c.categories_id = cd.categories_id)
						AND
							(cd.language_id = '1'))				
						WHERE
							c.categories_id = '".(int)$value."';
					";
				}
				// echo $cPathSQL."<br>";// exit;
				$metatagsearch = mysql_query($cPathSQL) or die('died ('.$cPathSQL.'):'.mysql_error());
				$row = mysql_fetch_assoc($metatagsearch);
				// $CAT_KEYWORDS = $row['metatags_keywords'];
				if (isset($osc_metatags)) unset($osc_metatags);
				$osc_metatags = $row;
				if (strlen($osc_metatags['categories_description']) > 0 ){
					// echo $osc_metatags['categories_description']."<br>";
					// Set the Desc as the current category ONLY
					if (!isset($OSC_DESC)) {
						$OSC_DESC = remove_html_garbage(strip_tags(trim(str_replace("><", "> <",$osc_metatags['categories_description']))));
						// $OSC_DESC = str_replace("><", "> <",$osc_metatags['categories_description']);
						// $OSC_DESC = $osc_metatags['categories_description'];
						// echo $OSC_DESC."<br>";
						// $OSC_DESC = trim($OSC_DESC);
						// echo $OSC_DESC."<br>";
						// $OSC_DESC = strip_tags($OSC_DESC);
						// echo $OSC_DESC."<br>";
						// $OSC_DESC = remove_html_garbage($OSC_DESC);
						// echo $OSC_DESC."<br>";
									
						// Set the Keywords Variable as the Desc for now, we may add a product keywords to it later.
						// $CAT_KEYWORDS = $OSC_DESC;
					}
					$TEMP_CAT_KEYWORDS[] = trim(remove_html_garbage(strip_tags(trim(str_replace("><", "> <",$osc_metatags['categories_description'])))));
				}
				// Set the H1 Tag to the current category ONLY
				if (!isset($OSC_H1_TAG)) $OSC_H1_TAG = $osc_metatags['categories_name'];
				
				// Create Array so we can parse category names later for a title.
				$CAT_TITLES[] = $osc_metatags['categories_name'];
			}
			// exit;
			// Reverse the category names so we can build from the top category to the bottom.
			$CAT_TITLES = array_reverse($CAT_TITLES);
			foreach($CAT_TITLES as $key => $value){
				if (!isset($OSC_TITLE))
					$OSC_TITLE = trim(strip_tags(str_replace("><", "> <",$value)));
				else
					$OSC_TITLE .= OSC_TITLE_SEPERATOR_PARSE.trim(strip_tags(str_replace("><", "> <",$value)));
				// echo $value."<Br>";
			}
			
			
			// $OSC_TITLE = trim($results['categories_name'] . " [" . $current_products_model . " - " . $current_products_price."]");
			
			// $categories_new_image_alt_tag = trim($current_products_image . " - " . $start_productsearch_result['categories_name']);

			// $categories_new_h1_tag = trim($start_productsearch_result['categories_name'] . " - " . $current_products_model);
			
			// $categories_new_head_desc_tag = remove_html_garbage(strip_tags( trim($HTTP_POST_VARS['categories_description'][$language_id])) );
			
			// $categories_new_head_keywords_tag = remove_html_garbage(clear_common_words(strip_tags( trim(str_replace($illegalstring,$replacestrings, $categories_head_keywords_tag)))));
		}
		// Set $PRODUCT_KEYWORDS so that there is no notice of undefined variable
		$PRODUCT_KEYWORDS = "";
		if (isset($_GET['products_id'])){
			/*
			$pPathSQL = "
				SELECT
					pd.products_name,
					pd.products_description,
					meta.metatags_imgalt,
					meta.metatags_description,
					meta.metatags_keywords
				FROM
					".TABLE_PRODUCTS_DESCRIPTION." as pd
				WHERE
					categories_id = '".$_GET['cPath']."';
			";
			*/
			$pPathSQL = "
				SELECT
					pd.products_name,
					pd.products_description,
					p.products_image,
					p.products_model
				FROM
					".TABLE_PRODUCTS." as p
				LEFT JOIN
					".TABLE_PRODUCTS_DESCRIPTION." as pd
				ON
					((p.products_id = pd.products_id)
				AND
					(pd.language_id = '1'))		
				WHERE
					p.products_id = '".(int)$_GET['products_id']."';
			";
			// echo $pPathSQL; exit;
			$metatagsearch = mysql_query($pPathSQL) or die('died ('.$pPathSQL.'):'.mysql_error());
			$row = mysql_fetch_assoc($metatagsearch);
			// $CAT_KEYWORDS = $row['metatags_keywords'];
			$osc_metatags = $row;
			$PRODUCT_KEYWORDS = $row['products_description'];
			// Set the Desc as the current product, overriding any category description
			// if (!isset($OSC_DESC)) {
				$OSC_DESC = remove_html_garbage(strip_tags(str_replace("><", "> <",$osc_metatags['products_description'])));
			// }
			
			// Set the H1 Tag to the current product, overriding any category description
			if (!isset($OSC_H1_TAG)) $OSC_H1_TAG = $osc_metatags['products_name'] . " [" . $osc_metatags['products_model'] . "]";
			
			$OSC_TITLE .= OSC_TITLE_SEPERATOR_PARSE.trim(strip_tags(str_replace("><", "> <",$osc_metatags['products_name'])));
			
			
			// $OSC_TITLE = trim($results['categories_name'] . " [" . $current_products_model . " - " . $current_products_price."]");
			
			$OSC_IMAGE = $osc_metatags['products_image'] . " - " . $osc_metatags['products_name'];
			// $categories_new_image_alt_tag = trim($current_products_image . " - " . $start_productsearch_result['products_name']);

			// $categories_new_h1_tag = trim($start_productsearch_result['categories_name'] . " - " . $current_products_model);
			
			// $categories_new_head_desc_tag = remove_html_garbage(strip_tags( trim($HTTP_POST_VARS['categories_description'][$language_id])) );
		}
		
		// if OSC_ENFORCE_TITLE_LIMIT is true force the title a limit to 10 words
		if (OSC_ENFORCE_TITLE_LIMIT){
			$base_count = count(explode(" ",OSC_BASE_TITLE));
			$junk = explode(OSC_TITLE_SEPERATOR_PARSE, $OSC_TITLE);
			$count = $base_count;
			$ii = 0;
			while($count < $osc_rules_array[OSC_KEYWORD_LIMIT_TYPE]['title_limit']['words']){
				$temp_title_array[] = $junk[(count($junk)-1)];
				unset($junk[(count($junk)-1)]);
				$count = $count + count($temp_title_array[(count($temp_title_array)-1)]);
				if ($count <= $osc_rules_array[OSC_KEYWORD_LIMIT_TYPE]['title_limit']['words'])
					$ii++;
			}
			$temp_title_array = array_reverse($temp_title_array);
			$i = 0;
			while($ii >= $i){
				if (!isset($temp_title))
					$temp_title = $temp_title_array[$i];
				else
					$temp_title .= OSC_TITLE_SEPERATOR.$temp_title_array[$i];
				$i++;
			}
			// foreach($junk as $key => $value)
				// if($key < 10){
					// $temp_title .= $value;
					// if ($key < 9)
						// $temp_title .= " ";
				// }
			// }
			$OSC_TITLE = $temp_title;
		}else{
			$OSC_TITLE = str_replace(OSC_TITLE_SEPERATOR_PARSE,OSC_TITLE_SEPERATOR,$OSC_TITLE);
		}
		// Add the base title which is the STORE_NAME
		$OSC_TITLE = OSC_BASE_TITLE.$OSC_TITLE;

		
		// Remove white spaces and new lines from $the_desc
		$OSC_DESC = clear_junk($OSC_DESC);
		// Concat the desc to 167 characters. Then add 3 periods to the end. For a total of 170 characters
		// echo $OSC_DESC."<br>\n";
		$OSC_DESC = strip_non_alphanum($OSC_DESC,OSC_CUSTOM_EREG);
		$OSC_DESC = preg_replace('/\s+/', ' ', $OSC_DESC);
		if (strlen($OSC_DESC) > $osc_rules_array[OSC_KEYWORD_LIMIT_TYPE]['description_limit']['chars']){
			// echo "to long";
			$backup_the_desc = $OSC_DESC;
			$OSC_DESC = substr($OSC_DESC, 0, ($osc_rules_array[OSC_KEYWORD_LIMIT_TYPE]['description_limit']['chars']-3));
			$OSC_DESC = trim_non_alphanum($OSC_DESC,OSC_CUSTOM_EREG);
			if ($backup_the_desc != $OSC_DESC)
				$OSC_DESC .= "...";
		}
		// echo $OSC_DESC."<br>\n";
		
		
		if (!isset($TEMP_CAT_KEYWORDS[0]))
			$CAT_KEYWORDS = "";
		else{
			$CAT_KEYWORDS = "";
			foreach($TEMP_CAT_KEYWORDS as $key => $value){
				if($key > 0)
					$CAT_KEYWORDS .= " ";
				// echo $value."<br><br>";
				$CAT_KEYWORDS .= $value;
			}
		}
		// Set the keywords based on product keywords then your base/default keywords then category keywords
		if ($PRODUCT_KEYWORDS == "")
			$OSC_KEYWORDS = $CAT_KEYWORDS." ".OSC_BASE_KEYWORDS;
		else
			$OSC_KEYWORDS = $PRODUCT_KEYWORDS." ".OSC_BASE_KEYWORDS.$CAT_KEYWORDS;
		// echo $OSC_KEYWORDS."<br>";
			// echo $OSC_KEYWORDS; exit;
		if (strlen($OSC_KEYWORDS) > 0){
			//remove any current commas
			$OSC_KEYWORDS = str_replace(",","",$OSC_KEYWORDS);
			// echo "before 'osc_format_string': ".$OSC_KEYWORDS."<br>";
			// echo $OSC_KEYWORDS."<br>";
			$OSC_KEYWORDS = osc_format_string($OSC_KEYWORDS,OSC_CUSTOM_EREG);
			// echo "after 'osc_format_string': ".$OSC_KEYWORDS."<br>";
			// if OSC_ENFORCE_KEYWORD_LIMIT is true force the keywords a limit to 20 keywords
			if (OSC_ENFORCE_KEYWORD_LIMIT){
				$junk = explode(OSC_METATAG_SEPERATOR, $OSC_KEYWORDS);
				$temp_keywords = "";
				foreach($junk as $key => $value){
					if($key < $osc_rules_array[OSC_KEYWORD_LIMIT_TYPE]['keywords_limit']['words']){
						$temp_keywords .= $value;
						if ($key < ($osc_rules_array[OSC_KEYWORD_LIMIT_TYPE]['keywords_limit']['words']-1))
							$temp_keywords .= OSC_METATAG_SEPERATOR;
					}
				}
				$OSC_KEYWORDS = $temp_keywords;
			}
			$OSC_METATAG_SEPERATOR_MINUSSPACE = str_replace(" ","",OSC_METATAG_SEPERATOR);
			$test_array = explode(OSC_METATAG_SEPERATOR,$OSC_KEYWORDS);
			// Concat Keywords to 900 characters max.
			$OSC_KEYWORDS = substr($OSC_KEYWORDS, 0, $osc_rules_array[OSC_KEYWORD_LIMIT_TYPE]['keywords_limit']['chars']);

			// Concat any text that would have been cut off. Only do so if the remaining text after cut off doesn't match the last word after before cut off.
			
			$cut_off = true;
			$test_string = "";
			// echo "'".$OSC_KEYWORDS."'<br>";
			$OSC_KEYWORDS = trim_non_alphanum($OSC_KEYWORDS);
			// echo "'".$OSC_KEYWORDS."' - ".strlen($OSC_KEYWORDS)."<br>\n";
			foreach($test_array as $key => $value){
				// if($key < 5){
					// echo "Test String #".$key." = '".$test_string."' - ".strlen($test_string)."<br>\n";
				// }
				$value = preg_replace('/\s+/', ' ', $value);
				if((strlen(str_replace(" ","",$value)) > 0)){
					if($key > 0)
						$test_string .= OSC_METATAG_SEPERATOR;
					$test_string .= $value;
				}
				if(strlen($test_string) == strlen($OSC_KEYWORDS)){
					$cut_off = false;
					break;
				}
			}
			// echo "\nstrlen($test_string): ".strlen($test_string)."<br>\n";
			// echo "strlen($OSC_KEYWORDS): ".strlen($OSC_KEYWORDS)."<br>\n";
			if ($cut_off)
				$OSC_KEYWORDS = substr($OSC_KEYWORDS, 0, strrpos($OSC_KEYWORDS,$OSC_METATAG_SEPERATOR_MINUSSPACE));		
		}
	// echo $OSC_KEYWORDS; exit;
		if($OSC_DESC == "") $OSC_DESC = OSC_DEFAULT_DESC;

		$OSC_DESC = str_replace("nbsp ","",$OSC_DESC);
		$OSC_DESC = str_replace("nbsp","",$OSC_DESC);
		$OSC_TITLE = trim_non_alphanum($OSC_TITLE,OSC_CUSTOM_EREG);
		// echo "'".$OSC_KEYWORDS."'<br>";
		$OSC_IMGALT_TAG = $OSC_IMAGE;
	}
	
	if (isset($_GET['ajax'])){
		echo '' . $OSC_TITLE . '|' . $OSC_DESC . '|' . $OSC_KEYWORDS . '|' . $OSC_H1_TAG . '|' . $OSC_IMGALT_TAG . '|';
	}else{
		// echo '<textarea cols="70" rows="70">';
		echo '<!-- addons/osc_metatags.php BOF: Generated Meta Tags -->' . "\n";
// NEW RELIC 
if(extension_loaded('newrelic')) { 
echo newrelic_get_browser_timing_header(); 
}
		echo '  <title>' . $OSC_TITLE . '</title>' . "\n";
		echo '  <META NAME="Description" Content="' . $OSC_DESC . '">' . "\n";
		echo '  <META NAME="Keywords" CONTENT="' . $OSC_KEYWORDS . '">' . "\n";
		//echo '  <META NAME="Reply-to" CONTENT="' . HEAD_REPLY_TAG_ALL . '">' . "\n";

		echo '<!-- EOF:  addons/osc_metatags.phpGenerated Meta Tags -->' . "\n";
		// echo '</textarea>';
	}
	// error_reporting(E_ALL & ~E_NOTICE);
	// ini_set('display_errors', '1');	
?>