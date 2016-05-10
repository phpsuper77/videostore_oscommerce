<?php
/*
  Catalogue Request Infobox, v 1.0 01/10/2004 by Fred Doherty

  osCommerce
  http://www.oscommerce.com/

  Copyright (c) 2000,2001 osCommerce

  Released under the GNU General Public License

http://www.ideal-handling.com
go to any packages and see custom shop demo
*/
?>
<!-- cat_request //-->
          <tr>
            <td>
<img src="images/bar-clap.gif">
<?php 
  $info_box_contents = array(); 
  $info_box_contents[] = array('align' => 'left', 
                               'text'  => BOX_HEADING_CAT_REQUEST 
                              ); 
  new infoBoxHeading($info_box_contents, false, false); 

  $info_box_contents = array(); 
  $info_box_contents[] = array('align' => 'center', 
                               'text'  => '<a href="' . tep_href_link(FILENAME_CAT_REQUEST) . '">' . BOX_INFO_CAT_REQUEST_TEXT . '</a>');
							    new infoBox($info_box_contents);
?>
</td></tr>
<!-- cat_request_eof //-->