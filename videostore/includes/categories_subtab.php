<?php
/*
  $Id: categories_subtab.php,v 2.0 2004/11/16 02:59:49 ChBu Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License


* This file displays a row with links to sub-categories of the selected category.
* Use this file together with categories_tab.php. 
*/


function show_subcategories($counter) 
{
	global $fooa, $subcategories_string, $id, $HTTP_GET_VARS;
	$cPath_new = 'cPath=' . $fooa[$counter]['path'];
	
	$subcategories_string .= '<a href="';
	$subcategories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new);
	$subcategories_string .= '"  class="subheaderNavigation">';
	
	// display category name
	$subcategories_string .= $fooa[$counter]['name'];
	
	$subcategories_string .= '</a> ';
	
	if ($fooa[$counter]['next_id']) {
		$subcategories_string .= '| ';
		show_subcategories($fooa[$counter]['next_id']);
	}else{
		$subcategories_string .= '&nbsp;';
	}
}
?>

<!-- subcategories //-->
<table border="0" cellspacing="0" cellpadding="4" WIDTH="100%">
<tr class="subheaderNavigation"><td ALIGN="CENTER" class="subheaderNavigation">
<?php
	if ($cPath) {
		$subcategories_string = '';
		$new_path = '';
		$id = split('_', $cPath);
		reset($id);
		while (list($key, $value) = each($id)) {
			unset($prev_id);
			unset($first_id);
			$subcategories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $value . "' and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."' order by sort_order, cd.categories_name");
			$subcategory_check = tep_db_num_rows($subcategories_query);
			if ($subcategory_check > 0) {
				$new_path .= $value;
				while ($row = tep_db_fetch_array($subcategories_query)) {
					$fooa[$row['categories_id']] = array(
						'name' => $row['categories_name'],
						'parent' => $row['parent_id'],
						'level' => $key+1,
						'path' => $new_path . '_' . $row['categories_id'],
						'next_id' => false
					);
					if (isset($prev_id)) {
						$fooa[$prev_id]['next_id'] = $row['categories_id'];
					}
	
					$prev_id = $row['categories_id'];
					
					if (!isset($first_id)) {
						$first_id = $row['categories_id'];
					}
	
					$last_id = $row['categories_id'];
				}
				$fooa[$last_id]['next_id'] = $fooa[$value]['next_id'];
				$fooa[$value]['next_id'] = $first_id;
				$new_path .= '_';
			} else {
				break;
			}
		}
	}

	if ($id[0] != ''){
		show_subcategories($id[0]); 
		echo $subcategories_string;
	}else{
		echo "&nbsp;";
	}

?>
</td>
</tr>
</table>
          
<!-- subcategories_eof //-->
