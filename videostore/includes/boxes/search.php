<?php
/*
  $Id: search.php,v 1.22 2003/02/10 22:31:05 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

Search for videos using one or more <b>KEYWORDS</b> (Paris, Germany, Cruise, Rome DVD, etc.), <b>ISBN</b> or <b>UPC</b> numbers (No Dashes).


. '<br>'


*/
?>
<!-- search //-->
          <tr>
            <td>
<img alt="this is image" src="images/bar-clap.gif">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_SEARCH);

  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('form' => tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get'),
                               'align' => 'left',
                               'text' =>''  . tep_draw_input_field('keywords', '', 'size="10" maxlength="40" style="width: ' . (BOX_WIDTH-20) . 'px"') . '&nbsp;' . tep_hide_session_id() . tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH));

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- search_eof //-->

