<?php
ob_start();
/*
  $Id: information.php,v 1.6 2003/02/10 22:31:00 hpdl Exp $

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
  $info_box_contents[] = array('text' => BOX_HEADING_INFORMATION);

  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();

	if ((isset($HTTP_GET_VARS["cPath"])) || (isset($HTTP_GET_VARS["lPath"])) || (isset($HTTP_COOKIE_VARS['Category_Path_Link'])) )
		{
			if (isset($HTTP_GET_VARS["cPath"]))
			{
				$Path = explode("_",$HTTP_GET_VARS["cPath"]);
				$Category_Path_Link = $HTTP_GET_VARS["cPath"];
			}
			elseif (isset($HTTP_GET_VARS["lPath"]))
			{
				$Path = explode("_",$HTTP_GET_VARS["lPath"]);
				$Category_Path_Link = $HTTP_GET_VARS["lPath"];
			}
			elseif (isset($HTTP_COOKIE_VARS['Category_Path_Link'])  && tep_not_null($HTTP_COOKIE_VARS['Category_Path_Link']))
			{
				$Path = explode("_",$HTTP_COOKIE_VARS['Category_Path_Link']);
				$Category_Path_Link = $HTTP_COOKIE_VARS['Category_Path_Link'];
			}

			$Last_Element_Count = count($Path);
			if ($Last_Element_Count > 1)
			{
				//$Category_Path_Link = $Path[$Last_Element_Count-1];
				$link_path = $Path[$Last_Element_Count-1];
				$Links = FILENAME_LINKS.'?lPath='.$Category_Path_Link.'&cPath='.$HTTP_GET_VARS["cPath"];

				//CODE TO FETCH THE CATEGORY NAME
				$categories_query_sublink = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$link_path  . "'");
				if (tep_db_num_rows($categories_query_sublink) > 0) {
					$categories_sublink = tep_db_fetch_array($categories_query_sublink);
					$link_name = $categories_sublink['categories_name'];
				}
			}
			elseif (isset($HTTP_GET_VARS["cPath"]))
			{
						$Links = FILENAME_LINKS.'?&cPath='.$HTTP_GET_VARS["cPath"];
			}
			else
			{
				$Links = FILENAME_LINKS;
			}
		}

		else
			$Links = FILENAME_LINKS."?lPath=1";




/*  $info_box_contents[] = array('text' =>
   														'<a href="' . tep_href_link(FILENAME_FAQ) . '">' . 'F.A.Q.' . '</a><br>' .


 										 	'<a href="' . tep_href_link(FILENAME_EDUCATORS) . '">' . '---Educators' . '</a><br>' .

				'<a href="' . tep_href_link(FILENAME_LIBRARY) . '">' . '---Libraries' . '</a><br>' .
															  								'<a href="' . tep_href_link(FILENAME_TRAVEL_AGENTS) . '">' . '---Travel Agents' . '</a><br>' .
													'<a href="' . tep_href_link(FILENAME_SPEAKERS) . '">' . 'Speakers Bureau' . '</a><br>' .

'<a href="' . tep_href_link(FILENAME_PRODUCT_SUBMISSION) . '">' . 'Product Submissions' . '</a><br>' .


'<a href="' . tep_href_link(FILENAME_PURCHASE_ORDERS) . '">' . 'Purchase Orders' . '</a><br>' .


											'<a href="' . tep_href_link(FILENAME_SHIPPING) . '">' . BOX_INFORMATION_SHIPPING . '</a><br>' .
                                         	'<a href="' . tep_href_link(FILENAME_LINKS) . '">' .   BOX_INFORMATION_LINKS . '</a><br>' .  *// VJ Links Manager v1.00 added


$info_box_contents[] = array('text' =>
										'<a href="' . tep_href_link(FILENAME_FAQ) . '">' . 'F.A.Q.' . '</a><br>' .
										'<a href="' . tep_href_link(FILENAME_EDUCATORS) . '">' . '---Educators' . '</a><br>' .
										'<a href="' . tep_href_link(FILENAME_LIBRARY) . '">' . '---Libraries' . '</a><br>' .
										'<a href="' . tep_href_link(FILENAME_TRAVEL_AGENTS) . '">' . '---Travel Agents' . '</a><br>' .
										'<a href="' . tep_href_link(FILENAME_SPEAKERS) . '">' . 'Speakers Bureau' . '</a><br>' .
										'<a href="' . tep_href_link(FILENAME_PRODUCT_SUBMISSION) . '">' . 'Product Submissions' . '</a><br>' .
										'<a href="' . tep_href_link(FILENAME_PURCHASE_ORDERS) . '">' . 'Purchase Orders' . '</a><br>' .
										'<a href="' . tep_href_link(FILENAME_SHIPPING) . '">' . BOX_INFORMATION_SHIPPING . '</a><br>' .

            /*CHAGES MADE HERE*/	   	'<a href="' . tep_href_link($Links) . '">' . $link_name .' ' .BOX_INFORMATION_LINKS . '</a>'); // VJ Links Manager v1.00 added


new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- information_eof //-->


