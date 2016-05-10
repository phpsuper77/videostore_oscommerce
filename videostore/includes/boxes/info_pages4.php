<?php
/*
  $Id: info_pages.php,v 1.21 2003/06/09 22:07:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

    $page_query = tep_db_query("select 
                                   p.pages_id, 
                                   p.sort_order,
                                   p.status,
                                   s.pages_title,
                                   s.pages_html_text,
                                   s.intorext,
                                   s.externallink,
                                   s.link_target  
                                from 
                                   " . TABLE_PAGES . " p LEFT JOIN " .TABLE_PAGES_DESCRIPTION . " s on p.pages_id = s.pages_id
                                where 
                                   p.status = 1 
                                and 
                                   p.page_type = 4 
                                and 
                                   s.language_id = '" . (int)$languages_id . "'
                                order by 
                                   p.sort_order, s.pages_title");


?>
<!-- info_pages //-->
          <tr>
            <td>
<img src="images/bar-clap.gif">
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => 'Celebrity Hosts');

    new infoBoxHeading($info_box_contents, false, false);

    $rows = 0;
    $page_list = '<table border="0" width="100%" cellspacing="0" cellpadding="1">';




    while ($page = tep_db_fetch_array($page_query)) {
      $rows++;


$target="";
if($page['link_target']== 1)  {
$target="_blank";
}



if($page['pages_title'] != 'Contact Us'){

$link = FILENAME_PAGES . '?pages_id=' . $page['pages_id'];



}else{

$link = FILENAME_CONTACT_US;
}

if($page['intorext'] == 1)  {
$page_list .= '<tr><td class="infoBoxContents"><a target="'.$target.'" href="' . $page['externallink'] . '">' . $page['pages_title'] . '</a></td></tr>';
}
else {
      $page_list .= '<tr><td class="infoBoxContents"><a target="'.$target.'" href="' . tep_href_link($link) . '">' . $page['pages_title'] . '</a></td></tr>';
}
    }



    $page_list .= '</table>';

    $info_box_contents = array();
    $info_box_contents[] = array('text' => $page_list);


    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- info_pages_eof //-->

