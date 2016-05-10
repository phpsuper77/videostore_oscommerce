<?php
function tep_show_category($counter) {
	// BoF - Contribution Category Box Enhancement 1.1
	global $tree, $categories_string, $cPath_array, $cat_name;

	for ($i=0; $i<$tree[$counter]['level']; $i++) {
		$categories_string .= "&nbsp;&nbsp;";
	}
	$cPath_new = 'cPath=' . $tree[$counter]['path'];
	$categories_string .= '<a href="';
	$categories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">';
	if (tep_has_category_subcategories($counter)) {	
		if (($tree[$counter]['path'] == 808) or ($tree[$counter]['path'] == 959)) {
			$categories_string .= tep_image(DIR_WS_IMAGES . 'categories/arrow_bullet.gif', '');
		} else {
			if ($tree[$counter]['level'] == 0) {
				$categories_string .= tep_image(DIR_WS_IMAGES . 'categories/arrow_down.gif', '');
			} else {
				$categories_string .= tep_image(DIR_WS_IMAGES . 'categories/arrow_right.gif', '');
			}
		}
	} else {
		$categories_string .= tep_image(DIR_WS_IMAGES . 'categories/arrow_bullet.gif', '');
	}

	if (isset($cPath_array) && in_array($counter, $cPath_array)) {
		$categories_string .= '<b>';
	}

	if ($cat_name == $tree[$counter]['name']) {
		$categories_string .= '<span class="errorText">';
	}

	// display category name
	$categories_string .= $tree[$counter]['name'];

	if ($cat_name == $tree[$counter]['name']) {
		$categories_string .= '</span>';
	}

	if (isset($cPath_array) && in_array($counter, $cPath_array)) {
		$categories_string .= '</b>';
	}
	// 	EoF Category Box Enhancement

	$categories_string .= '</a>';
	if (SHOW_COUNTS == 'true') {
		$products_in_category = tep_count_products_in_category($counter);
		if ($products_in_category > 0) {
			$categories_string .= '&nbsp;(' . $products_in_category . ')';
		}
	}

	$categories_string .= '<br>';
	if ($tree[$counter]['next_id'] != false) {
		tep_show_category($tree[$counter]['next_id']);
	}
}
?>
<!-- categories //-->
<tr>
	<td>
		<img alt="this is image" src="images/bar-clap.gif" border="0">
		<?php
		// BoF - Contribution Category Box Enhancement 1.1
		if (isset($cPath_array)) {
			for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
				$categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
				if (tep_db_num_rows($categories_query) > 0) $categories = tep_db_fetch_array($categories_query);
			}
			$cat_name = $categories['categories_name']; 
		}
		// EoF Category Box Enhancement
		// display category name

		$info_box_contents = array();
		$info_box_contents[] = array('text' => BOX_HEADING_CATEGORIES);

		new infoBoxHeading($info_box_contents, false, false);

		$categories_string = '';
		$tree = array();

		$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status = '1' and c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
		while ($categories = tep_db_fetch_array($categories_query)) {
			$tree[$categories['categories_id']] = array('name' => $categories['categories_name'],
																									'parent' => $categories['parent_id'],
																									'level' => 0,
																									'path' => $categories['categories_id'],
																									'next_id' => false);

			if (isset($parent_id)) {
				$tree[$parent_id]['next_id'] = $categories['categories_id'];
			}

			$parent_id = $categories['categories_id'];
			if (!isset($first_element)) {
				$first_element = $categories['categories_id'];
			}
		}

		//------------------------
		$pos = strpos($cPath, '808');
		$pos1 = strpos($cPath, '959');

		if (($pos===false) & ($pos1===false)) {
			if (tep_not_null($cPath)) {
				$new_path = '';
				reset($cPath_array);
				while (list($key, $value) = each($cPath_array)) {
					unset($parent_id);
					unset($first_id);
					$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$value . "' and c.categories_status = '1' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
					if (tep_db_num_rows($categories_query)) {
						$new_path .= $value;
						while ($row = tep_db_fetch_array($categories_query)) {
							$tree[$row['categories_id']] = array(	'name' => $row['categories_name'],
																										'parent' => $row['parent_id'],
																										'level' => $key+1,
																										'path' => $new_path . '_' . $row['categories_id'],
																										'next_id' => false);

							if (isset($parent_id)) {
								$tree[$parent_id]['next_id'] = $row['categories_id'];
							}

							$parent_id = $row['categories_id'];

							if (!isset($first_id)) {
								$first_id = $row['categories_id'];
							}

							$last_id = $row['categories_id'];
						}
						$tree[$last_id]['next_id'] = $tree[$value]['next_id'];
						$tree[$value]['next_id'] = $first_id;
						$new_path .= '_';
					} else {
						break;
					}
				}
			}
		}
		tep_show_category($first_element); 

		$info_box_contents = array();
		$info_box_contents[] = array('text' => $categories_string);

		new infoBox($info_box_contents);
		?>
	</td>
</tr>
<!-- categories_eof //-->