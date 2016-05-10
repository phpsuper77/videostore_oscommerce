<?php
/*
  $Id: discount.php, v1.2 2003/07/13 ola svensson Exp $

  osCommerce 2.2 Milestone 2

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- Discount //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => TEXT_DISCOUNT,
                     'link'  => tep_href_link(FILENAME_DISCOUNT_REDEEM,tep_get_all_get_params(array('selected_box')) . 'selected_box=discount'));

  if ($selected_box == 'discount') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_DISCOUNT_ENTRY, '', 'NONSSL') .'" class="menuBoxContentLink">' . TEXT_DISCOUNT_ENTRY . '</a><br>' .
								   '<a href="' . tep_href_link(FILENAME_DISCOUNT_REDEEM, '', 'NONSSL') .'" class="menuBoxContentLink">' . TEXT_DISCOUNT_REDEEM . '</a><br>');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
	<!-- Discount //-->