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

 $heading[] = array('text' => 'Feed files','link'  => tep_href_link(FILENAME_DEFINE_FEED,'selected_box=feed'));


if ($selected_box == 'feed') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_DEFINE_FEED) . '" class="menuBoxContentLink">' . 'Feed files'. '</a><br>'
                                    );
   }


# $heading[] = array('text' => 'Feed files','link'  => tep_href_link('feedfiles.php','selected_box=feed'));
#
#
#if ($selected_box == 'feed') {
#    $contents[] = array('text'  => '<a href="' . tep_href_link('feedfiles.php') . '" class="menuBoxContentLink">' . 'Feed files'. '</a><br>'
#                                    );
#   }


$box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->
