          <tr>
            <td>
<img alt="this is image" src="images/bar-clap.gif">
<?php
/*
  $Id: viewed_products.php, v 1.8 2005/01/09 00:00:00 gjw Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// if (((tep_session_is_registered('customer_id')) or (ENABLE_PAGE_CACHE == 'false')) and (!$spider_flag)){

// HQSQ added call to language define file for Viewed Items Mod START
 	require_once(DIR_WS_LANGUAGES . $language . '/' . 'viewed_products.php');
// HQSQ added call to language define file for Viewed Items Mod END

//*******************************************************************************
  DEFINE('HIST_ROWS', 6);         // number of rows per column on display
  DEFINE('HIST_MAX_ROWS', 6);     // max number of products on display
  DEFINE('HIST_MEM_TRIGGER', 5);  // number when memory threshold kicks in
//*******************************************************************************

  // register the array if not already done so

  if (tep_session_is_registered('viewed') && is_object($viewed)) {
  } else {
    tep_session_register('viewed');
    $viewed = new viewed_products;
    $viewed->reset();
  }

// start user switch //
  if (tep_session_is_registered('viewed_switch')) {
  } else {
    tep_session_register('viewed_switch');
    $viewed_switch = 'true';
  }
// end user switch //

  // empty the array if requested by the user
//  if (isset($HTTP_GET_VARS['action'])) {
//    if ($HTTP_GET_VARS['action'] == 'viewed_remove') {
//      $viewed->remove();
//    }
//  }

// start user switch //
  if (isset($HTTP_GET_VARS['action'])) {
    if ($HTTP_GET_VARS['action'] == 'viewed_remove') {
      $viewed->remove();
    } elseif ($HTTP_GET_VARS['action'] == 'viewed_switch') {
        if ($viewed_switch == 'true') {
          $viewed_switch = 'false';
        } else {
            $viewed_switch = 'true';
          }
      }
  }
// end user switch //

  // display the box if we have history
//  if ($viewed->count_viewed() > 0) { // displaying

// start shift from line 106 to here
 $items_ids_on_display = array();
// end shift

// start user switch //
  if (($viewed->count_viewed() > 0) and ($viewed_switch == 'true')){ // displaying
// end user switch //

  if (HIST_MAX_ROWS <= HIST_ROWS) {
    $hist_width= '100%';
  } else {
    $hist_width= '100%';
  }


  $recent_content .= '<table border="0" width="100%" cellpadding="0" cellspacing="0">';

    $row = 0;

    /* get the products array from the class containing all viewed products */

    $items = $viewed->get_viewed_items();

    $index = 1;

    /* determine the first and last record we want to display*/
    $first = sizeof($items)- HIST_MAX_ROWS;
    $last  = sizeof($items)-1;
    if (($last+1) < HIST_MAX_ROWS) {$disp = ($last+1);} else {$disp = HIST_MAX_ROWS;}
    if ($first < 0) {$first = 0;}

    /* only fetch the info for products on display */
//    $items_ids_on_display = array();			// shift to line 67
    for ($i=$last, $n=$first; $i>=$n; $i--) {
        $viewed_query = tep_db_query("select pd.products_name,
                                             p.products_image
                                      from " . TABLE_PRODUCTS . " p,
                                           " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                      where p.products_id = '" . $items[$i] . "' and
                                            pd.language_id = '" . $languages_id . "' and
                                            pd.products_id = p.products_id");
        if ($viewed_info = tep_db_fetch_array($viewed_query)) {
         $items_on_display[$i] = array('id' => $items[$i],
                                     'name' => $viewed_info['products_name'],
                                     'image' => $viewed_info['products_image']);
         $items_ids_on_display[]= $items[$i];
        }
    }

	$recent_header = "Your Recent History <br>(last $disp items)";
	$info_box_contents = array();
	$info_box_contents[] = array('align' => 'left', 'text'  => $recent_header);
	new infoBoxHeading($info_box_contents, false, false);


    for ($i=$last, $n=$first; $i>=$n; $i--) {
    $recent_content .= '<tr>
          <td width=1% valign="middle">';

      $recent_content .= tep_image(DIR_WS_IMAGES . 'indicator.gif');
      $recent_content .= '&nbsp;</td><td class="boxtext" valign="middle" align="left">';
      $recent_content .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $items_on_display[$i]['id']) . '">' . $items_on_display[$i]['name'] . '</a><br>';

      $row ++;
      $index++;
      if ($row > HIST_ROWS - 1) {
        $row = 0;
        $recent_content .= '</td>
              <td width="5"> </td>';
        if ($i > $n) {
        $recent_content .= '<td class="boxtext" nowrap valign="top" align="left">';
        } else {
           $recent_content .= '</td>';
          }
      }
    }


    /* find random product in displayed list */
    $random_number = rand($first,$last);
    $selected_product1 = $items_on_display[$random_number]['id'];

    /* do the also purchased query */

/*
    $orders_query = tep_db_query("select p.products_id,
                                         p.products_image,
                                        pd.products_name
                                  from " . TABLE_ORDERS_PRODUCTS . " opa,
                                       " . TABLE_ORDERS_PRODUCTS . " opb,
                                       " . TABLE_ORDERS . " o,
                                       " . TABLE_PRODUCTS . " p,
                                       " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                  where opa.products_id = '" . $selected_product1 . "' and
                                        opa.orders_id = opb.orders_id and
                                        opb.products_id = p.products_id and
                                        opb.products_id != '" . $selected_product1 . "' and
                                        opb.orders_id = o.orders_id and
                                          p.products_id = pd.products_id and
                                         pd.language_id = '" . $languages_id . "' and
                                        p.products_status = '1'
                                  group by p.products_id
                                  order by o.date_purchased desc
                                  limit 1");

*/

  $recent_content .= '</tr>
        <!--tr>
        <td colspan="100" class="boxtext" align="right"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=viewed_remove'). '"><img src="images/icon_delete.gif" border="0" alt="' . TEXT_RESET_VIEWED_ITEMS . '" title="' . TEXT_RESET_VIEWED_ITEMS . '"></a>&nbsp;&nbsp;</td>
        </tr-->
        </td>
        </tr>
        </table>';


	$info_box_contents = array();
	$info_box_contents[] = array('align' => 'left', 'text'  => $recent_content);
	new infoBox($info_box_contents, false, false);

  } // Displaying

  if (isset($HTTP_GET_VARS['products_id']) and ($HTTP_GET_VARS['action'] != 'viewed_remove')) {
    if (!in_array($HTTP_GET_VARS['products_id'], $items_ids_on_display)) {
      $viewed->add_viewed($HTTP_GET_VARS['products_id']);
    }
  }

?>
            </td>
          </tr>