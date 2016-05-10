<?php
/*
  $Id: series.php,v 1.19 2003/06/09 22:17:13 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  $series_query = tep_db_query("select series_id, series_name from " . TABLE_SERIES . " order by series_name");
  if ($number_of_rows = tep_db_num_rows($series_query)) {
?>
<!-- series //-->
          <tr>
            <td>
<img src="images/bar-clap.gif">
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_SERIES);

    new infoBoxHeading($info_box_contents, false, false);

    if ($number_of_rows <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST) {
// Display a list
      $series_list = '';
      while ($series = tep_db_fetch_array($series_query)) {
        $series_name = ((strlen($series['series_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($series['series_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $series['series_name']);
        if (isset($HTTP_GET_VARS['series_id']) && ($HTTP_GET_VARS['series_id'] == $series['series_id'])) $series_name = '<b>' . $series_name .'</b>';
        $series_list .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'series_id=' . $series['series_id']) . '">' . $series_name . '</a><br>';
      }

      $series_list = substr($series_list, 0, -4);

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $series_list);
    } else {
// Display a drop-down
      $series_array = array();
      if (MAX_MANUFACTURERS_LIST < 2) {
        $series_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
      }

      while ($series = tep_db_fetch_array($series_query)) {
        $series_name = ((strlen($series['series_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($series['series_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $series['series_name']);
        $series_array[] = array('id' => $series['series_id'],
                                       'text' => $series_name);
      }

      $info_box_contents = array();
      $info_box_contents[] = array('form' => tep_draw_form('series', tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get'),
                                   'text' => tep_draw_pull_down_menu('series_id', $series_array, (isset($HTTP_GET_VARS['series_id']) ? $HTTP_GET_VARS['series_id'] : ''), 'onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" style="width: 100%"') . tep_hide_session_id());
    }

    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- series_eof //-->
<?php
  }
?>