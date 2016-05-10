<?php
/*
  $Id: keyword_show.php, v1.2 2003/07/13 ola svensson Exp $

  osCommerce 2.2 Milestone 2
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- keyword_show //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_KEYWORD_SHOW,
                     'link'  => tep_href_link(FILENAME_KEYWORD_SHOW_CONFIG, 'selected_box=keyword_show'));

  if ($selected_box == 'keyword_show') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_KEYWORD_SHOW_CONFIG, '', 'NONSSL') . '" class="menuBoxContentLink">' . TEXT_KEYWORD_SHOW_CONFIG . '</a><br>' .
				'<a href="' . tep_href_link(FILENAME_KEYWORD_SHOW) . '" class="menuBoxContentLink">' . BOX_KEYWORD_SHOW . '</a>'); 		   
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- keyword_show_eof //-->