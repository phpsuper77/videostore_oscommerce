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

  $heading[] = array('text'  => BOX_HEADING_CATALOG,
                     'link'  => tep_href_link('editbymodel.php?selected_box=catalog', '', 'NONSSL'));
// echo FILENAME_MANUFACTURERS."<br>";
  if ($selected_box == 'catalog') {
    $contents[] = array('text'  => 
                                   '<a href="' . tep_href_link('editbymodel.php', '', 'NONSSL') . '" class="menuBoxContentLink">Edit product<br/>By Model Number</a><BR>'.
                                   '<a href="' . tep_href_link('categories.php', '', 'NONSSL') . '" class="menuBoxContentLink">Product List</a><BR>'.
				   '<a href="' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_CATEGORIES_PRODUCTS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES . '</a><br>' .
								   '<a href="' . tep_href_link('easypopulate.php', '', 'NONSSL') . '" class="menuBoxContentLink">Easy Populate</a><br>' .
//DANIEL:begin
                                   '<a href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_CATEGORIES_RELATED_PRODUCTS . '</a><br>' .
//DANIEl:end
	                               '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_MANUFACTURERS . '</a><br>' .
								   '<a href="' . tep_href_link(FILENAME_DISTRIBUTORS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_DISTRIBUTORS . '</a><br>' .
								   '<a href="' . tep_href_link(FILENAME_PRODUCERS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_PRODUCERS . '</a><br>' .
								   '<a href="' . tep_href_link(FILENAME_SERIES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_SERIES . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_REVIEWS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_SPECIALS . '</a><br>' .
								   '<a href="' . tep_href_link(FILENAME_FEATURED, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_FEATURED . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_PRODUCTS_EXPECTED . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_QUICK_STOCKUPDATE, '', 'NONSSL') . '" class="menuBoxContentLink">' . 'Quick Stock Update' . '</a><br>' .
                                   // MaxiDVD Added Line For WYSIWYG HTML Area: BOF
                                   '<a href="' . tep_href_link(FILENAME_DEFINE_MAINPAGE, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_DEFINE_MAINPAGE . '</a><BR>');
                                   // MaxiDVD Added Line For WYSIWYG HTML Area: EOF
								   // Easy Populate : BOF
								   // Easy Populate : EOF
								   
								   
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->