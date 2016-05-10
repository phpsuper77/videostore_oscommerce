<?php
/*
  $Id: producers.php,v 1.19 2003/06/09 22:17:13 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  $producers_query = tep_db_query("select producers_id, producers_name from " . TABLE_PRODUCERS . " order by producers_name");
  if ($number_of_rows = tep_db_num_rows($producers_query)) {
?>
<!-- producers //-->
          <tr>
            <td>
<img src="images/bar-clap.gif">
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_PRODUCERS);

    new infoBoxHeading($info_box_contents, false, false);

    if ($number_of_rows <= MAX_DISPLAY_MANUFACTURER_IN_A_LIST) {
// Display a list
      $producers_list = '';
      while ($producers = tep_db_fetch_array($producers_query)) {
        $producers_name = ((strlen($producers['producers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($producers['producers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $producers['producers_name']);
        if (isset($HTTP_GET_VARS['producers_id']) && ($HTTP_GET_VARS['producers_id'] == $producers['producers_id'])) $producers_name = '<b>' . $producers_name .'</b>';
        $producers_list .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'producers_id=' . $producers['producers_id']) . '">' . $producers_name . '</a><br>';
      }

      $producers_list = substr($producers_list, 0, -4);

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $producers_list);
    } else {
// Display a drop-down
      $producers_array = array();
      if (MAX_PRODUCERS_LIST < 2) {
        $producers_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
      }

      while ($producers = tep_db_fetch_array($producers_query)) {
        $producers_name = ((strlen($producers['producers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($producers['producers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $producers['producers_name']);
        $producers_array[] = array('id' => $producers['producers_id'],
                                       'text' => $producers_name);
      }

      $info_box_contents = array();
      $info_box_contents[] = array('form' => tep_draw_form('producers', tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get'),
                                   'text' => tep_draw_pull_down_menu('producers_id', $producers_array, (isset($HTTP_GET_VARS['producers_id']) ? $HTTP_GET_VARS['producers_id'] : ''), 'onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" style="width: 100%"') . tep_hide_session_id());
    }

    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- producers_eof //-->
<?php
  }
?>