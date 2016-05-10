<?php
/*
  $Id: catalog.php,v 1.21 2003/07/09 01:18:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- catalog //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text' => 'VHS/DVD/CD','link'  => tep_href_link(FILENAME_DEFINE_VHS,'selected_box=vhs_dvd'));


if ($selected_box == 'vhs_dvd') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_DEFINE_VHS) . '" class="menuBoxContentLink">' . 'VHS'. '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_DEFINE_DVD) . '" class="menuBoxContentLink">' . 'DVD'. '</a><br>'.
                                   '<a href="' . tep_href_link(FILENAME_DEFINE_CD) . '" class="menuBoxContentLink">' . 'CD'. '</a><br>');
   }


$box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->
