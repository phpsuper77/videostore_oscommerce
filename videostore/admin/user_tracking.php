<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Modified by Robert Hellemans
  Sept 14th, 2002
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_USER_TRACKING);
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  require(DIR_WS_INCLUDES . 'geoip.inc');
  $gi = geoip_open(DIR_WS_INCLUDES . 'GeoIP.dat',GEOIP_STANDARD);

  $LIMIT_DISPLAY_SESSIONS = CONFIG_USER_TRACKING_SESSION_LIMIT; 
//  $MIN_CLICK_COUNT = 1;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="smallText">

<?php 
  if ($custmid == '0') 
  { 
    tep_db_query("DELETE FROM " . TABLE_USER_TRACKING . " where customer_id = 0"); 
    echo "<font color=red>" . TEXT_DELETE_CUSTOMER_GUEST . '</font><p>'; 
  }
  if ($purge == '72') 
  { 
    tep_db_query("DELETE FROM " . TABLE_USER_TRACKING . " where time_last_click < '"  . (time() - ($purge * 3600))."'"); 
    echo "<font color=red>" . TEXT_HAS_BEEN_PURGED . '</font><p>'; 
  }
     if ($purge == '0') 
  { 
    tep_db_query("DELETE FROM " . TABLE_USER_TRACKING . " where time_last_click < '"  . (time() - ($purge * 3600))."'"); 
    echo "<font color=red>" . TEXT_HAS_BEEN_PURGED . '</font><p>';
  }	
  if ($delip == '1')
  {
    tep_db_query("DELETE FROM " . TABLE_USER_TRACKING . " WHERE ip_address = '" . CONFIG_USER_TRACKING_EXCLUDED . "'");
    echo "<font color=red>" . TEXT_DELETE_IP_OK_1 . CONFIG_USER_TRACKING_EXCLUDED . ' ' . TEXT_DELETE_IP_OK_2 . '.</font><p>';
    $delip='0';
  }
  if ($delip == '2')
  {
    tep_db_query("DELETE FROM " . TABLE_USER_TRACKING . " WHERE ip_address = '" . $user_tracking_excluded . "'");
    echo TEXT_DELETE_IP_OK_1 . $user_tracking_excluded . ' ' . TEXT_DELETE_IP_OK_2 . '.<p>';
    $delip='0';
  }
if ($delnosession == '1')
  {
    tep_db_query("DELETE FROM " . TABLE_USER_TRACKING . " WHERE session_id = ''");
    echo TEXT_DELETE_OK . '<p>';
    $delnosession='0';
   }
  if ($delsession)
  {
    tep_db_query("DELETE FROM " . TABLE_USER_TRACKING . " WHERE session_id = '" . $delsession . "'");
    echo $delsession . ' has been deleted. <p>';
   }

  echo EXPLAINATION, "<p>";

  // some time routines
  $time_frame = time();
  if ($HTTP_GET_VARS['time'])
  { 
    $time_frame = $HTTP_GET_VARS['time'];
  }

// only display if all sessions are displayed
if (!isset($HTTP_GET_VARS['viewsession']) && $HTTP_GET_VARS['viewsession'] == '')
{
  echo '<b>' . TEXT_SELECT_VIEW .': </b>';
  echo '<a href="' . FILENAME_USER_TRACKING . '?time=' ;
  echo $time_frame - 86400 . '">' . "<font color=blue><b>" . TEXT_BACK_TO . ' ' . date("d M Y", $time_frame - 86400) . '</b></font color></a> ';
    if (time() > $time_frame + 86400)
  {
    echo '| <a href="' . FILENAME_USER_TRACKING . '?time=' ;
    echo $time_frame + 86400 . '">' . "<font color=blue><b>" . TEXT_FORWARD_TO . date("d M Y", $time_frame + 86400) . '</b></font color></a>';
  }
  

  echo "<p>" . TEXT_DISPLAY_START . $LIMIT_DISPLAY_SESSIONS . TEXT_DISPLAY_END . '<p>';
  echo TEXT_PURGE_START . ' <a href="' . FILENAME_USER_TRACKING . '?purge=72">'. "<font color=blue><b>" . TEXT_PURGE_RECORDS. '</a></b></font color> ' .  TEXT_PURGE_END. '</font><p>';
  echo TEXT_PURGE_START . ' <a href="' . FILENAME_USER_TRACKING . '?custmid=0">'. "<font color=blue><b>" . TEXT_PURGE_RECORDS. '</a></b></font color> ' .  TEXT_PURGE_CUSTMID_END. '</font><p>';
  echo TEXT_PURGE_START . ' <a href="' . FILENAME_USER_TRACKING . '?purge=0">'. "<font color=blue><b>" . TEXT_PURGE_RECORDS. '</a></b></font color> ' .  TEXT_PURGE_ALL. ' </font>' . '<font color=red><b>' . TEXT_BEWAREDELETE . '</b></font color><p>';
  echo TEXT_DELETE_IP . CONFIG_USER_TRACKING_EXCLUDED . ' <a href="' . FILENAME_USER_TRACKING . '?delip=1">'. "<font color=blue><b>" . TEXT_PURGE_RECORDS. '</a></b></font><p>';
  echo TEXT_DELETE_NOIP . ' <a href="' . FILENAME_USER_TRACKING . '?delnosession=1">' . "<font color=blue><b>" . TEXT_DELETE_NOW . '</b></a> ' . '</font><p>';
php?>
<tr>
  <form name="del_ip" <?php echo 'action="' . tep_href_link(FILENAME_USER_TRACKING . '?delip=2') . '"'; ?> method="post">
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>


            <td class="main"><?php echo '<font size="1">' . TEXT_DELETE_IP . '</font>'; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('user_tracking_excluded', (isset($sInfo->user_tracking_excluded) ? $sInfo->user_tracking_excluded : '')); ?></td>
            <td class="main" valign="top"><?php echo (($form_action == 'user_tracking_excluded') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_delete.gif', IMAGE_DELETE)). '</a>'; ?></td>
          </tr>
        </table></td>
   </form>
</tr>
<?
}

// we need to slurp all the customer tracking information out of the database
if (isset($HTTP_GET_VARS['viewsession']) && $HTTP_GET_VARS['viewsession'] != '')
{
  // display selected session
  $whos_online_query = 
                   tep_db_query("select customer_id, full_name, ip_address, time_entry, time_last_click, last_page_url, page_desc, referer_url," .
                   " session_id from " . TABLE_USER_TRACKING  . 
                   " where session_id = '" . $HTTP_GET_VARS['viewsession'] . "' order by time_last_click desc");
} else {
  // read all sessions
  $whos_online_query = 
                   tep_db_query("select customer_id, full_name, ip_address, time_entry, time_last_click, last_page_url, page_desc, referer_url," .
                   " session_id from " . TABLE_USER_TRACKING  . 
                   " where time_entry > " . ($time_frame - 86400) . 
                   " and time_entry < " . $time_frame . 
                   " order by time_last_click desc");
}
  $results = 0;
  while ($whos_online = tep_db_fetch_array($whos_online_query)) 
  {
     $user_tracking[$whos_online['session_id']]['session_id']=$whos_online['session_id'];
     $user_tracking[$whos_online['session_id']]['ip_address']=$whos_online['ip_address'];
     $user_tracking[$whos_online['session_id']]['customer_id']=$whos_online['customer_id'];
	 $user_tracking[$whos_online['session_id']]['referer_url']=$whos_online['referer_url'];


if ($whos_online['full_name'] != 'Guest') 
        $user_tracking[$whos_online['session_id']]['full_name'] = '<font color="0000ff"><b>' . $whos_online['full_name'] . '</b></font>';

     $user_tracking[$whos_online['session_id']]['last_page_url'][$whos_online['time_last_click']] = $whos_online['last_page_url'];
     $user_tracking[$whos_online['session_id']]['page_desc'][$whos_online['time_last_click']] = $whos_online['page_desc']; 

     if (($user_tracking[$whos_online['session_id']]['time_entry'] > $whos_online['time_entry']) ||
         (!$user_tracking[$whos_online['session_id']]['time_entry']))
          $user_tracking[$whos_online['session_id']]['time_entry'] = $whos_online['time_entry'];
     if (($user_tracking[$whos_online['session_id']]['end_time'] < $whos_online['time_entry']) ||
         (!$user_tracking[$whos_online['session_id']]['end_time']))
          $user_tracking[$whos_online['session_id']]['end_time'] = $whos_online['time_entry'];
     $results ++;
  }

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr> 
      <td valign="top" align=center><table border="0" width="95%" cellspacing="0" cellpadding="2"> 

<?php

  // now let's display it

 $listed=0;
 if ($results)
 while (($ut = each($user_tracking)) && ($listed++ < $LIMIT_DISPLAY_SESSIONS)) 
 {

//  if (count($ut['value']['last_page_url']) > $MIN_CLICK_COUNT) {
  if (count($ut['value']['last_page_url']) > CONFIG_USER_TRACKING_CLICK_COUNT) {

   $time_online = (time() - $ut['value']['time_entry']);
   if ( ((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $ut['value']['session_id'])) && (!$info) ) {
     $info = $ut['value']['session_id'];
    }
echo '
       <tr class="dataTableHeadingRow"> 
        <td class="dataTableHeadingContent" colspan="5">'.TABLE_HEADING_SESSION_ID .'</td> 
        <td class="dataTableHeadingContent" colspan="1 width="150">'.TEXT_USER_SHOPPING_CART.'</td> 
       </tr>';


      echo '              <tr class="dataTableRowSelected">' . "\n";

      if ($ut['value']['full_name'] == "")
        $ut['value']['full_name'] = "Guest";
?>
				<td colspan = "5" class="dataTableContent" valign="top"></b><a name="<?php echo $ut['value']['session_id'];?>"><?php echo $ut['value']['full_name'] . '</b>&nbsp;(' . $ut['value']['session_id'] . ")&nbsp;&nbsp;&nbsp;<a href=\"user_tracking.php?time=" . $time_frame . "&delsession=" . $ut['value']['session_id'] . "\"><font color=red>[" . TEXT_DELETE_SESSION . "]</font></a>" . " <a href=\"user_tracking.php?time=" . $time_frame . "&viewsession=" . $ut['value']['session_id'] . "#" . $ut['value']['session_id'] . "\"><font color=green>[" . TEXT_VIEW_SESSION . "]</font></a>";?></td>
<?php

    // shopping cart decoding
    $session_data = tep_db_query("select value from " . TABLE_SESSIONS . " WHERE sesskey = '" . $ut['value']['session_id'] . "'");
    if (tep_db_num_rows($session_data)) {
      $session_data = tep_db_fetch_array($session_data);
      $session_data = trim($session_data['value']);
    } else {
      $session_data = @file(tep_session_save_path() . '/sess_' . $ut['value']['session_id']);
      $session_data = trim($session_data[0]);
    }   
    $cart = "";
//    $referer_url = "";
    $num_sessions ++;
    session_decode($session_data);
    
    $contents = array();
    if (is_object($cart)) {
      $products = $cart->get_products();
      for ($i=0; $i<sizeof($products); $i++) {
        $contents[] = array('text' => $products[$i]['quantity'] . ' x ' . $products[$i]['name']);
      } 
      if (sizeof($products) > 0) {
        $contents[] = array('text' => tep_draw_separator('pixel_black.gif', '100%', '1'));
        $contents[] = array('align' => 'right', 'text'  => TEXT_SHOPPING_CART_SUBTOTAL . ' ' . $currencies->format($cart->show_total(), true, $currency));
      } else {
        $contents[] = array('text' => '&nbsp;');
      }
    }

    $heading = array();

    if (tep_not_null($contents)) 
    {
      echo '            <td rowspan="4" valign="top">' . "\n";
      $box = new box;
      echo $box->infoBox($heading, $contents);       
      echo '            </td>' . "\n";
    }
    else 
    {
      echo '            <td rowspan="4" valign="top" class="dataTableContent" align="center">session expired' . "\n";
      echo '            </td>' . "\n";
    }

?>

              </tr>
		      <tr>
        <td class="dataTableContent" align="right" valign="top"><b>Click Count:</b></td> 
        <td class="dataTableContent" valign="top"><font color=FF0000><b><?php echo count($ut['value']['last_page_url']);?></b></font></td>
        <td class="dataTableContent" colspan=2 rowspan=4 align="center"> 
<table border="0" width="100%" cellspacing="0" cellpadding="2"> 
   <?php
      $today = getdate();
      $midnight = mktime(0, 0, 0, $today['mon'], $today['mday'], $today['year']);
   ?>
  <tr> 
    <td class="dataTableContent" align="right" valign="top"><b><?php echo TABLE_HEADING_ENTRY_TIME; ?></b></td>
    <td class="dataTableContent" colspan="2" valign="top"><?php echo date('d/m/Y H:i:s', $ut['value']['time_entry']); ?></td>
    <td class="dataTableContent" align="right" valign="top"><b><?php echo TEXT_IDLE_TIME ?></b></td>
    <td class="dataTableContent" colspan="2" valign="top"><?php echo date('H:i:s', ($midnight + time() - $ut['value']['end_time'])); ?></td>
  </tr>
  <tr>
    <td class="dataTableContent" align="right" valign="top"><b><?php echo TABLE_HEADING_END_TIME; ?></b></td>
    <td class="dataTableContent" colspan="2" valign="top"><?php echo date('d/m/Y H:i:s', $ut['value']['end_time']); ?></td>
    <td class="dataTableContent" align="right" valign="top"><b><?php echo TEXT_TOTAL_TIME ?></b></td>
    <td class="dataTableContent" colspan="2" valign="top"><b><?php echo date('H:i:s', ($midnight + $ut['value']['end_time'] - $ut['value']['time_entry'])); ?></b></td> 
  </tr>
</table> 
        </td> 
			  </tr>
              <tr>
        <td class="dataTableContent" align="right" valign="top"><b><?php echo TABLE_HEADING_COUNTRY ?></b></td> 
        <td class="dataTableContent" valign="top"><?php echo tep_image(DIR_WS_FLAGS . strtolower(geoip_country_code_by_addr($gi, $ut['value']['ip_address'])) . '.gif', geoip_country_name_by_addr($gi, $ut['value']['ip_address'])); ?>&nbsp;<?php echo geoip_country_name_by_addr($gi, $ut['value']['ip_address']); ?></td> 
       </tr> 
              <tr>
        <td class="dataTableContent" align="right" valign="top"><b><?php echo TABLE_HEADING_IP_ADDRESS ?></b></td> 
        <td class="dataTableContent" valign="top"><?php echo '<a href="'  . USER_TRACKING_WHOIS_URL . $ut['value']['ip_address'] ; ?>" target="_new"><?php echo $ut['value']['ip_address'] ; ?></a></td> 
       </tr> 
       <tr> 
        <td class="dataTableContent" align="right" valign="top"><b><?php echo TABLE_HEADING_HOST ?></b></td> 
        <td class="dataTableContent" valign="top"><?php echo gethostbyaddr($ut['value']['ip_address']); ?></td> 
       </tr> 
       <tr> 
        <td class="dataTableContent" align="right" valign="top"><b><?php echo TEXT_ORIGINATING_URL ?></b></td> 
<?php 
$ref_name = chunk_split($referer_url,40,"<br>"); 
?> 
<td class="dataTableContent" align="left" valign="top" colspan=3><?php echo '<a href="'. $ut['value']['referer_url'] .'" target="_new">'. $ut['value']['referer_url'] .'</a>'; ?>&nbsp;</td> 
       </tr> 
       <tr> 
        <td class="dataTableContent"></td> 
        <td class="dataTableContent" colspan=3> 
        <table border="0" cellspacing="1" cellpadding="2" bgcolor=999999 width=100%>
<?php 
// View session
if ($viewsession != '' && $viewsession == $ut['value']['session_id']){ 
  while (($pu = each($ut['value']['last_page_url']))&&($du = each($ut['value']['page_desc']))) 
  { 
    
?> 
          <tr bgcolor=ffffff> 
            <td class="dataTableContent" valign=top align="right"><?php echo date('H:i:s', $pu['key']); ?></td> 
            <td class="dataTableContent" nowrap valign=top align="left">&nbsp;<a href="<?php echo $pu['value']; ?>" target="_new"><?php if ($du['value']!=''){ echo $du['value'];} ?></a>&nbsp;</td> 
            <td class="dataTableContent" width=100% align="left"><a href="<?php echo $pu['value']; ?>" target="_new"><?php echo chunk_split($pu['value'],40,"<br>"); ?></a></td> 
          </tr> 
<?php 
  } 
} 
echo'        </table> 
      </td> 
     </tr> ';
 }
 } 
?> 
       <tr> 
        <td class="smallText" colspan="7"><b><font color=blue><?php echo sprintf(TEXT_NUMBER_OF_PAGES, tep_db_num_rows($whos_online_query)); echo TEXT_NUMBER_OF_CUSTOMERS, $num_sessions . "."; ?></font></b></td> 
       </tr> 
      </table></td> 
     </tr> 
    </table> 
   </td> 
   </tr> 
  </table></td> 
<!-- body_text_eof //--> 
 </tr> 
</table> 
<!-- body_eof //--> 

<!-- footer //--> 
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?> 
<!-- footer_eof //--> 
<br> 
</body> 
</html> 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>