<?php
/*
  $Id: distributors.php,v 1.19 2003/06/09 22:17:13 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  $distributors_query = tep_db_query("select distributors_id, distributors_name from " . TABLE_DISTRIBUTORS . " order by distributors_name");
  if ($number_of_rows = tep_db_num_rows($distributors_query)) {
?>
<!-- distributors //-->
          <tr>
            <td>
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_DISTRIBUTORS);

    new infoBoxHeading($info_box_contents, false, false);

    if ($number_of_rows <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST) {
// Display a list
      $distributors_list = '';
      while ($distributors = tep_db_fetch_array($distributors_query)) {
        $distributors_name = ((strlen($distributors['distributors_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($distributors['distributors_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $distributors['distributors_name']);
        if (isset($HTTP_GET_VARS['distributors_id']) && ($HTTP_GET_VARS['distributors_id'] == $distributors['distributors_id'])) $distributors_name = '<b>' . $distributors_name .'</b>';
        $distributors_list .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'distributors_id=' . $distributors['distributors_id']) . '">' . $distributors_name . '</a><br>';
      }

      $distributors_list = substr($distributors_list, 0, -4);

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $distributors_list);
    } else {
// Display a drop-down
      $distributors_array = array();
      if (MAX_MANUFACTURERS_LIST < 2) {
        $distributors_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
      }

      while ($distributors = tep_db_fetch_array($distributors_query)) {
        $distributors_name = ((strlen($distributors['distributors_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($distributors['distributors_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $distributors['distributors_name']);
        $distributors_array[] = array('id' => $distributors['distributors_id'],
                                       'text' => $distributors_name);
      }

      $info_box_contents = array();

      $info_box_contents[] = array('form' => tep_draw_form('distributors', tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get'),
                                   'text' => tep_draw_pull_down_menu('distributors_id', $distributors_array, (isset($HTTP_GET_VARS['distributors_id']) ? $HTTP_GET_VARS['distributors_id'] : ''), 'onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" style="width: 100%"') . tep_hide_session_id());
								   
    }

    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- distributors_eof //-->
<?php
  }
?>