<?php
/*
  $Id: categories.php,v 2.0 2004/11/16 02:59:49 ChBu Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
Modified for single image tabs with subtabs by Tim Hanekamp because 
single tabs by amanda  are really beautiful 
and subtabs by Carine Bruyndoncx are really usefull
original author Chuck Burgess.

* v2.0 by Christophe Buchi:
* - No need to edit the file to change the last tab ID
* - Option to show the Home page on first tab
* - Option to not show categories having sort_order=0
* - Images sizes are now in DEFINE, easier to change them.
* - Sub-categories moved to categories_subtab.php, so it's now like an option.
*/

// START CONFIGURATION
  define (CAT_TABS_SHOW_HOME,true); // Set to true to display the Home
  define (CAT_TABS_HEIGHT,'20'); // Height of the tabs
  define (CAT_TABS_WIDTH_LEFT,'55'); // Width of the left part of the first tab
  define (CAT_TABS_WIDTH_MIDDLE,'28'); // Width of the image between tabs
  define (CAT_TABS_WIDTH_RIGHT,'11'); // Width of right part of the last tab
  define (CAT_TABS_SHOW_ALL,true); // Display or not categories having sort order=0 (true=>display)
// END CONFIGURATION

  $cfg_query_and = (CAT_TABS_SHOW_ALL==true ? ' and sort_order >0 ':'');

// $counter = category id $start = first tab $last_on = if the previous tab was selected
function show_category_tabs($counter, $last_element, $start = 1, $last_on = false) 
{
	global $foo, $categories_string, $id, $HTTP_GET_VARS;
    $onpage = false;

// Prepares new cPath if not home page	
	if (($foo[$counter]['parent'] == 0) and ($counter!=0)) {
		$cPath_new = 'cPath=' . $counter;
	}
		
// We are on the home page
	if (!isset($HTTP_GET_VARS['cPath']))  {
	  if ($counter==0) $onpage = true;
	}
	  elseif (($HTTP_GET_VARS['cPath'] != 0) and ($counter!=0)){
		$base = substr($HTTP_GET_VARS['cPath'], 0, strpos($HTTP_GET_VARS['cPath'], '_'));
		if ($counter == $HTTP_GET_VARS['cPath']) {
			$onpage = true;
		} elseif ($counter == $base) {
			$onpage = true;
		}
	}

      if ($counter == $last_element) {
        $last_tab = true;
      } else {
          $last_tab = false;
        }

	if ($onpage) { //Tab is on
        if ($start) {//1st tab on
		  $categories_string .= '<td class="tabsNavigation" valign="top" height="'.CAT_TABS_HEIGHT.'" width="'.CAT_TABS_WIDTH_LEFT.'" nowrap background="images/curve/left_on.gif">'."\n";
        } else { //middle or last on
    		  $categories_string .= '<td class="tabsNavigation" valign="top" height="'.CAT_TABS_HEIGHT.'" width="'.CAT_TABS_WIDTH_MIDDLE.'" nowrap background="images/curve/middle_on_left.gif">'."\n";
        }
	} else { //Tab is off
        if ($start) { //1st tab off
		  $categories_string .= '<td class="tabsNavigation" valign="top" height="'.CAT_TABS_HEIGHT.'" width="'.CAT_TABS_WIDTH_LEFT.'" nowrap background="images/curve/left_off.gif">'."\n";
        } else {
         	if ($last_on) { //Middle or last tab, previous tab was on
    		  $categories_string .= '<td class="tabsNavigation" valign="top" height="'.CAT_TABS_HEIGHT.'" width="'.CAT_TABS_WIDTH_MIDDLE.'" nowrap background="images/curve/middle_on_right.gif">'."\n";
            } else { //Middle or last tab, previous tab not on
    		  $categories_string .= '<td class="tabsNavigation" valign="top" height="'.CAT_TABS_HEIGHT.'" width="'.CAT_TABS_WIDTH_MIDDLE.'" nowrap background="images/curve/middle_off.gif">'."\n";
            }
        }
	}  	

	$categories_string .= '</td>'."\n";

	if ($onpage) {
		$categories_string .= '<td class="tabsNavigation" valign="middle" height="'.CAT_TABS_HEIGHT.'"  nowrap background="images/curve/menu0_on.gif">'."\n";
	} else {
		$categories_string .= '<td class="tabsNavigation" valign="middle" height="'.CAT_TABS_HEIGHT.'"  nowrap background="images/curve/menu0_off.gif">'."\n";
	}  	

// if tab selected we dont need a link
      if (!$onpage) {
	$categories_string .= '<a class="tabsNavigation" href="';
	$categories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new);
	$categories_string .= '">';
      }

	// display category name
	$categories_string .= $foo[$counter]['name'];

      if (!$onpage) {
	 $categories_string .= '</a>';
      }

	$categories_string .= '</td>';

      if ($last_tab) {
	  if ($onpage) {
	    $categories_string .= '<td class="tabsNavigation" valign="top" height="'.CAT_TABS_HEIGHT.'" width="'.CAT_TABS_WIDTH_RIGHT.'" nowrap background="images/curve/right_on.gif">'."\n";
	  } else {
		$categories_string .= '<td class="tabsNavigation" valign="top" height="'.CAT_TABS_HEIGHT.'" width="'.CAT_TABS_WIDTH_RIGHT.'" nowrap background="images/curve/right_off.gif">'."\n";
	    }  	
	}
	$categories_string .= '</td>'."\n";

	if ($foo[$counter]['next_id']) {
        if ($onpage) {
		  show_category_tabs($foo[$counter]['next_id'],$last_element, 0, true);
        } else {
		  show_category_tabs($foo[$counter]['next_id'], $last_element, 0);
        }
	}
}
	// start the tabs
?>

<table border="0" cellspacing="0" cellpadding="0" WIDTH="100%">
<tr>
<?php
// needed in case other part of site use same variable.
	$categories_string=''; 
	unset ($first_element);
	unset ($prev_id);

if (CAT_TABS_SHOW_HOME) {
    $foo[0]=array(
			'name' => HEADER_TITLE_TOP,
			'parent' => '',
			'level' => 0,
			'path' => '',
			'next_id' => false
		  );
	$prev_id=0; $first_element=0; 		
}		
	
	$categories_query = tep_db_query("select c.categories_id, 
                                               cd.categories_name, 
                                               c.parent_id 
                                        from " . TABLE_CATEGORIES . " c, 
                                             " . TABLE_CATEGORIES_DESCRIPTION . " cd 
                                        where c.parent_id = '0' and c.categories_status= '1'
                                              c.categories_id = cd.categories_id and 
                                              cd.language_id='" . $languages_id ."'
											  ".$cfg_query_and."
                                        order by sort_order, cd.categories_name");
					
										
	while ($categories = tep_db_fetch_array($categories_query))  {
	  $foo[$categories['categories_id']] = array(
			'name' => $categories['categories_name'],
			'parent' => $categories['parent_id'],
			'level' => 0,
			'path' => $categories['categories_id'],
			'next_id' => false
		  );
	
	  if (isset($prev_id)) {
		$foo[$prev_id]['next_id'] = $categories['categories_id'];
	  }
	
	  $prev_id = $categories['categories_id'];
	  if (!isset($first_element)) {
		$first_element = $categories['categories_id'];
	  }
      $last_element=$categories['categories_id'];
	}
	show_category_tabs($first_element, $last_element); 
	echo $categories_string;
  
?>
</tr>
</table>
          
<!-- categories_eof //-->
  
