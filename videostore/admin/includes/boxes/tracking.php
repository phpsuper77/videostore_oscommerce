<?php
/*
  $Id: tools.php,v 1.21 2003/07/09 01:18:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- tools //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => 'Program Requests',
                     'link'  => tep_href_link('tracking.php?selected_box=tracking', '', 'NONSSL'));

  if ($selected_box == 'tracking') {
    $contents[] = array('text'  => 
'<a href="'.tep_href_link('tracking.php', '', 'NONSSL').'" class="menuBoxContentLink">Bug Tracking</a><br/>'.
'<a href="'.tep_href_link('timetracker.php', '', 'NONSSL').'" class="menuBoxContentLink">Time Tracking</a><br/>');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->