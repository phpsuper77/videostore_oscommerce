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

  $heading[] = array('text'  => BOX_HEADING_TOOLS,
                     'link'  => tep_href_link(FILENAME_BACKUP, 'selected_box=tools'));

  if ($selected_box == 'tools') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_BACKUP) . '" class="menuBoxContentLink">' . BOX_TOOLS_BACKUP . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_TESTIMONIALS_MANAGER) . '" class="menuBoxContentLink">' . BOX_TOOLS_TESTIMONIALS_MANAGER . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_BANNER_MANAGER) . '" class="menuBoxContentLink">' . BOX_TOOLS_BANNER_MANAGER . '</a><br>' .
/* Begin Imagecheck add-on */
                                   '<a href="' . tep_href_link(FILENAME_IMAGE_CHECK) . '" class="menuBoxContentLink">' . BOX_TOOLS_IMAGE_CHECK . '</a><br>' .
                                   /* End Imagecheck add-on */
                                   '<a href="' . tep_href_link(FILENAME_CACHE) . '" class="menuBoxContentLink">' . BOX_TOOLS_CACHE . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_DEFINE_LANGUAGE) . '" class="menuBoxContentLink">' . BOX_TOOLS_DEFINE_LANGUAGE . '</a><br>' .
'<a href="' . tep_href_link(FILENAME_PAGE_MANAGER) . '" class="menuBoxContentLink">' . BOX_TOOLS_PAGE_MANAGER . '</a><br>' .
'<a href="' . tep_href_link(FILENAME_FILE_MANAGER) . '" class="menuBoxContentLink">' . BOX_TOOLS_FILE_MANAGER . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_MAIL) . '" class="menuBoxContentLink">' . BOX_TOOLS_MAIL . '</a><br>' . 
                                   '<a href="' . tep_href_link(FILENAME_SEO_ASSISTANT) . '" class="menuBoxContentLink">' . BOX_TOOLS_SEO_ASSISTANT . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_RECOVER_CART_SALES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TOOLS_RECOVER_CART . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_KEYWORDS) . '" class="menuBoxContentLink">' . BOX_TOOLS_KEYWORDS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_NEWSLETTERS) . '" class="menuBoxContentLink">' . BOX_TOOLS_NEWSLETTER_MANAGER . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_SERVER_INFO) . '" class="menuBoxContentLink">' . BOX_TOOLS_SERVER_INFO . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_WHOS_ONLINE) . '" class="menuBoxContentLink">' . BOX_TOOLS_WHOS_ONLINE . '</a><br>' .
'<a href="' . tep_href_link(FILENAME_MYSQL_PERFORMANCE, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TOOLS_MYSQL_PERFORMANCE . '</a><br>' .
'<a href="' . tep_href_link(FILENAME_EVENTS_MANAGER) . '" class="menuBoxContentLink">' . BOX_TOOLS_EVENTS_MANAGER . '</a><br/>'.
'<a href="' . tep_href_link('sold_products.php', '', 'NONSSL') . '" class="menuBoxContentLink">On-Demand Products</a><br/>'.
'<a href="' . tep_href_link('locations.php', '', 'NONSSL') . '" class="menuBoxContentLink">Warehouse Locations</a><br/>'.
'<a target="_new" href="' . tep_href_link('viewed.php', '', 'NONSSL') . '" class="menuBoxContentLink">Viewed Products<br/>Recreation</a><br/>'.
// BOF Dynamic Sitemap 
                                   '<a href="' . tep_href_link(FILENAME_SITEMAP) . '" class="menuBoxContentLink">' . BOX_TOOLS_SITEMAP . '</a><br>' .
// EOF Dynamic Sitemap 
'<a href="' . tep_href_link('iptocountry.php', '', 'NONSSL') . '" class="menuBoxContentLink">IPtoCountry Uploader</a><br/>'.
'<a href="' . tep_href_link('distribution.php', '', 'NONSSL') . '" class="menuBoxContentLink">Distribution Items</a><br/>');


  }


	if (isset($contents[0]) && isset($contents[0]['text'])){
		$contents[0]['text'] .= '<br \><a href="' . tep_href_link(FILENAME_SEO_URLS) . '" class="menuBoxContentLink">' . BOX_TOOLS_SEO_URLS . '</a>';
	}
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->