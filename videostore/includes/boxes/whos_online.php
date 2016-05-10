<?php
/*
  $Id: whos_online.php, v 2.0 2004/03/10 by Ingo (info@gamephisto.de)
  thx2 mattice@xs4all.nl

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

?>
<!-- whos_online 2.0 by Ingo (info@gamephisto.de) //-->
          <tr>
            <td>
<?php
$n_members=0;$n_guests=0;$member_list='';

$whos_online_query = tep_db_query("select customer_id from " . TABLE_WHOS_ONLINE);
while ($whos_online = tep_db_fetch_array($whos_online_query)) {
  if (!$whos_online['customer_id'] == 0) {
     $n_members++;
     $member = tep_db_fetch_array(tep_db_query("select customers_firstname from ".TABLE_CUSTOMERS." where customers_id = '".$whos_online['customer_id']."'"));
     $member_list .= (($n_members > 1)?', ':'') . $member['customers_firstname'];
     }
   if ($whos_online['customer_id'] == 0) $n_guests++;
   }

$user_total = sprintf(tep_db_num_rows($whos_online_query));
$there_is_are = (($user_total == 1)? BOX_WHOS_ONLINE_THEREIS:BOX_WHOS_ONLINE_THEREARE);
$word_guest = '&nbsp;'.(($n_guests == 1)? BOX_WHOS_ONLINE_GUEST:BOX_WHOS_ONLINE_GUESTS);
$word_member = '&nbsp;' .(($n_members == 1)? BOX_WHOS_ONLINE_MEMBER:BOX_WHOS_ONLINE_MEMBERS);
if (($n_guests >= 1) && ($n_members >= 1)) $word_and = '&nbsp;' . BOX_WHOS_ONLINE_AND . '<br>';

$textstring = $there_is_are.'<br>';
if ($n_guests >= 1) $textstring .= '&nbsp;'.$n_guests . $word_guest; 

$textstring .= $word_and; 
if ($n_members >= 1) {
  $textstring .= '&nbsp;'. $n_members . $word_member;
  if ((WHOS_ONLINE_LIST=='ja') || (WHOS_ONLINE_LIST=='yes')) $textstring .= '<br>('.$member_list.')';
  }
$textstring .= '<br>&nbsp;online.'; 
 
$info_box_contents = array();
$info_box_contents[] = array('align' => 'left', 'text'  => BOX_HEADING_WHOS_ONLINE );
new infoBoxHeading($info_box_contents, false, false);

$info_box_contents = array();
$info_box_contents[] = array('align' => 'left', 'text'  =>  $textstring );
new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- whos_online_eof //-->