<?php

/*

  $Id: column_left.php,v 1.15 2005/08/18 14:34:54 hpdl Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/
?>
<!-- information //-->
	          <tr>
	            <td>
<?php
	$info_box_contents = array();
	$info_box_contents[] = array('text' => "Vendor");

	new infoBoxHeading($info_box_contents, false, false);

	$info_box_contents = array();

	$info_box_contents[] = array('align' => 'left',
								 'params' => 'class="smallText" width="33%" valign="top"',
								 'text' =>'<a href="vendor_account_view.php">My Account</a>');

	$info_box_contents[] = array('align' => 'left',
								 'params' => 'class="smallText" width="33%" valign="top"',
								 'text' =>'<a href="'.FILENAME_VENDOR_PRODUCTS.'">Product Information</a>');

	$info_box_contents[] = array('align' => 'left',
								 'params' => 'class="smallText" width="33%" valign="top"',
								 'text' =>'<a href="distribution.php">Duplication Ordering</a>');

	$info_box_contents[] = array('align' => 'left',
								 'params' => 'class="smallText" width="33%" valign="top"',
								 'text' =>'<a href="faq.php">Frequently Ask<br/>Questions</a>');

	$info_box_contents[] = array('align' => 'left',
								 'params' => 'class="smallText" width="33%" valign="top"',
								 'text' =>'<a href="'.FILENAME_VENDOR_LOGOUT.'">Logout</a>');


	new infoBox($info_box_contents);
?>
	            </td>
	          </tr>
	<!-- information_eof //-->