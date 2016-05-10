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
/*
  $heading[] = array('text'  => 'Amazon Orders',
                     'link'  => '' . tep_href_link('amazon_parse.php?selected_box=amazons', '', 'NONSSL') . '');
*/
  $heading[] = array('text'  => 'External Orders',
                     'link'  => '' . tep_href_link('new_order_parser.php', '', 'NONSSL') . '');


  if ($selected_box == 'amazons') {
    $contents[] = array('text'  => 
'<a href="' . tep_href_link('amazon_parse.php', '', 'NONSSL') . '" class="menuBoxContentLink">Amazon File Processing</a><br/>'.
'<a href="' . tep_href_link('amazonuk_parse.php', '', 'NONSSL') . '" class="menuBoxContentLink">Amazon UK File Processing</a><br/>'.
'<a href="' . tep_href_link('amazonCA_parse.php', '', 'NONSSL') . '" class="menuBoxContentLink">Amazon CA File Processing</a><br/>'.
'<a href="' . tep_href_link('vendor_email_body.php', '', 'NONSSL') . '" class="menuBoxContentLink">Amazon Order Confirmation</a><br/>'.
'<a href="' . tep_href_link('vendoruk_email_body.php', '', 'NONSSL') . '" class="menuBoxContentLink">Amazon UK Order Confirmation</a><br/>'.
'<a href="' . tep_href_link('vendorCA_email_body.php', '', 'NONSSL') . '" class="menuBoxContentLink">Amazon CA Order Confirmation</a><br/>'.
'<a href="' . tep_href_link('customflix.php', '', 'NONSSL') . '" class="menuBoxContentLink">Customflix</a><br/>' .
'<a href="' . tep_href_link('new_order_parser.php', '', 'NONSSL') . '" class="menuBoxContentLink">External Orders</a><br/>');

  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
//'<a href="amazon_orders.php" class="menuBoxContentLink">Amazon Orders</a><br/>';
?>
            </td>
          </tr>
<!-- tools_eof //-->