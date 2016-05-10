<?php
/*
  $Id: vendors.php,v 1.21 2003/07/09 01:18:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

 

  Released under the GNU General Public License
*/
?>
<!-- vendors //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_VENDORS,
                     'link'  => tep_href_link(FILENAME_VENDORS, 'selected_box=vendors'));

  if ($selected_box == 'vendors') {
   $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_VENDORS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_VENDORS_VENDORS . '</a><br>' .
 				  '<a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_VENDORS_REPORTS_PROD . '</a><br>' .
 				  '<a href="' . tep_href_link(FILENAME_VENDOR_PAYMENTS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_VENDOR_PAYMENTS . '</a><br>' .
 				  '<a href="' . tep_href_link(FILENAME_VENDOR_PO, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_VENDOR_PO . '</a><br>' .
				  '<a href="' . tep_href_link(FILENAME_VENDOR_INFO, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_VENDORS_INFO .'</a><br/>'.
	   			  '<a href="' . tep_href_link('vendor_reports.php', '', 'NONSSL') . '" class="menuBoxContentLink">Vendor Reports</a><br/>'.
	   			  '<a href="' . tep_href_link('vendor_mail.php', '', 'NONSSL') . '" class="menuBoxContentLink">Mail Sending</a><br/>'.
	   			  '<a href="' . tep_href_link('receive.php', '', 'NONSSL') . '" class="menuBoxContentLink">Receiving Items</a><br/>'.
	   			  '<a href="' . tep_href_link('vendor_faq.php', '', 'NONSSL') . '" class="menuBoxContentLink">Faq Manager</a><br/>');
}
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->
