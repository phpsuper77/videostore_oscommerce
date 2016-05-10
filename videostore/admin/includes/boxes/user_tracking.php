<?php
/*
  $Id: user_tracking.php,v 1.32 2003/03/11 10:20:11 robert Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- user_tracking //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_USER_TRACKING,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=user_tracking'));

  if ($selected_box == 'user_tracking') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_USER_TRACKING_CONFIG) . '" class="menuBoxContentLink">' . TEXT_USERTRACK_CONFIG . '</a><br>' .
				'<a href="' . tep_href_link(FILENAME_USER_TRACKING) . '" class="menuBoxContentLink">' . BOX_USER_TRACKING . '</a>'); 		   
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- user_tracking_eof //-->