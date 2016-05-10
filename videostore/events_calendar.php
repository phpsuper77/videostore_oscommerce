<?php
/*
  $Id: events_calendar v2.00 2003/06/16 18:09:20 ip chilipepper.it Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_EVENTS_CALENDAR);
  
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_EVENTS_CALENDAR, '', 'NONSSL'));
  $i =1;
  $cal = new Calendar;
  $cal->setStartDay(FIRST_DAY_OF_WEEK);
  $this_month = date('m');
  $this_year = date('Y');

  if ($HTTP_GET_VARS['_month']) {
  $month = $_month;
  $year = $_year;
  $a = $cal->adjustDate($month, $year);
  $month_ = $a[0];
  $year_= $a[1];
  }else{
  $year = date('Y');
  $month = date('m');
  $yeventear_= $year;
  $month_= $month;
  }
  
  if($HTTP_GET_VARS['_day']){
  $ev_query = tep_db_query("select event_id from ". TABLE_EVENTS_CALENDAR ." where DAYOFMONTH(start_date)= '". $_day ."' and MONTH(start_date) = '". $_month ."' and YEAR(start_date) = '". $_year ."' AND language_id = '". $languages_id ."'");
   if (tep_db_num_rows($ev_query) < 2){
    $ev = tep_db_fetch_array($ev_query);
    $single_event = true;
    $select_event = $ev['event_id'];
    }
  }
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?> ">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?> ">



<link rel="stylesheet" type="text/css" href="stylesheet.css">
				<?php
				// osCoders.biz - Analystics - start
				/*
				Conditional code added thank to  rrodkey and bfcase
				IMPORTANT -- IMPORTANT - IMPORTANT
				You'll need to update the "xxxx-x" in the samples (twice) above with your own Google Analytics account and profile number. 
				To find this number you can access your personalized tracking code in its entirety by clicking Check Status in the Analytics Settings page of your Analytics account.
				*/
				if ($request_type == 'SSL') {
				?>
				 <script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript">
				 </script>
				 <script type="text/javascript">
				   _uacct="UA-195024-1";
				   urchinTracker();
				 </script>
				<?php
				} else {
				?>
				 <script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
				 </script>
				   <script type="text/javascript">
				    _uacct="UA-195024-1";
				    urchinTracker();
				 </script>
				<?
				}
				// osCoders.biz - Analistics - end
				?>
<?php include ('includes/ssl_provider.js.php'); ?> 


</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?> " valign="top">
    <table border="0" width="<?php echo BOX_WIDTH; ?> " cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">

    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" nowrap><?php echo HEADING_TITLE ?><br><br><br></td></tr><tr><td align="right" class="main" nowrap>
<?php


echo tep_draw_form('goto_event', FILENAME_EVENTS_CALENDAR, '', 'get');
$ev_query = tep_db_query("select *, DAYOFMONTH(start_date) AS day, MONTH(start_date) AS month, YEAR(start_date) AS year from ". TABLE_EVENTS_CALENDAR ." where start_date > '". date('Y-m-d H:i:s') ."' and language_id = '". $languages_id ."' order by start_date");
if(tep_db_num_rows($ev_query) > 0){
    $event_array[]  = array('id' => '', 'text' => 'Select Event');
while ($q_events = tep_db_fetch_array($ev_query)){
    $year = $q_events['year'];
    $month = $q_events['month'];
    $day = $q_events['day'];
    $event_array[] = array('id' => $q_events['event_id'] .'-'. $month .'-'. $year, 'text' => $cal->monthNames[$month - 1] .' '. $day .' -> '.$q_events['title']);
  }
  echo TEXT_SELECT_EVENT .'&nbsp;'. tep_draw_pull_down_menu('select_event', $event_array, NULL, 'onChange="(this.value != \'\') ? this.form.submit() : \'\' " ;', $required = false);

}

echo '</form>';
?>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>See Photos from our prior trade show appearances below&nbsp;</td>
      </tr>
      <tr>
        <td>
<?php

if(isset($single_event) || $HTTP_GET_VARS['select_event']){
$events_query = tep_db_query("select *,  DAYOFMONTH(start_date) AS event from ". TABLE_EVENTS_CALENDAR ." where event_id = '". $select_event ."' and language_id = '". $languages_id ."'");

    while ($events = tep_db_fetch_array($events_query)){
          list ($year, $month, $day) = preg_split('/[/.-]/', $events['start_date']);
          $date_start = date("F d, Y", mktime(0,0,0,$month,$day,$year));
          echo '<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>';
          echo '<td class="main">' . $date_start . '</td>';
          echo '</tr></table><br>';

          list ($year, $month, $day) = preg_split('/[/.-]/', $events['start_date']);
          $date_start = date("F j, Y", mktime(0,0,0,$month,$day,$year));
          if($events['end_date']){
          list ($year_end, $month_end, $day_end) = preg_split('/[/.-]/', $events['end_date']);
          $date_end = date("F j, Y", mktime(0,0,0,$month_end,$day_end,$year_end));
          }
          $event_array = array('id' => $events['event_id'],
                               'title' => $events['title'],
                               'image' => $events['event_image'],
                               'description' => $events['description'],
                               'first_day' => $date_start,
                               'last_day' => $date_end,
                               'OSC_link' => $events['OSC_link'],
                               'link' => $events['link']);
          $clsp = 2;
          echo '<table border="0" width="100%" cellspacing="0" cellpadding="4" class="event">'.
               '<tr>'.
               '<td width="100%" bgcolor="#D9DEE6" class="main" style="border-bottom: 1px solid #D9DEE6" nowrap>'. TEXT_EVENT_TITLE .'&nbsp;&nbsp;'. $event_array['title'] .'</td>';
          if($event_array['last_day']){
          echo '<td bgcolor="#D9DEE6" align="center" nowrap><div class="event" style="border: 1px inset #F2F4F7">&nbsp;&nbsp;'. TEXT_EVENT_START_DATE .'&nbsp;&nbsp;'. $event_array['first_day'] .'&nbsp;&nbsp;</div></td><td bgcolor="#D9DEE6" align="center" nowrap><div class="event" style="border: 1px inset #F2F4F7">&nbsp;&nbsp;'. TEXT_EVENT_END_DATE .'&nbsp;&nbsp;'. $event_array['last_day'] .'&nbsp;&nbsp;</div></td>';
          $clsp++;
          }
          echo '</tr><tr>'.
               '<td colspan="'. $clsp . '" class="main">'. TEXT_EVENT_DESCRIPTION .'<br>';
               
               if ($event_array['image']){
                  echo'<table border="0" cellspacing="0" cellpadding="0" align="right"><tr>'.
                      '<td class="main">'. tep_image(DIR_WS_IMAGES .'events_images/' . $event_array['image'], $event_array['title'], '', '', 'align="right" hspace="5" vspace="5"') .'</td>'.
                      '</tr></table>';
                      }
                      
          echo stripslashes($event_array['description']) .'</td>';
                      
          if($event_array['OSC_link']){
          echo '</tr><tr>'.
               '<td colspan="'. $clsp . '"  bgcolor="#F5F5F5" align="left" class="main">'. TEXT_EVENT_OSC_LINK .'&nbsp;&nbsp;<a href="'. $event_array['OSC_link'] .'" >'. $event_array['OSC_link'] .'</a></td>';
          }
          if($event_array['link']){
          echo '</tr><tr>'.
               '<td colspan="'. $clsp . '"  bgcolor="#F5F5F5" align="left" class="main">'. TEXT_EVENT_LINK .'&nbsp;&nbsp;<a href="http://'. $event_array['link'] .'" target="_blank">'. $event_array['link'] .'</a></td>';
          }
          echo '</tr></table><br>';
    }

$other_events_query = tep_db_query("select *, DAYOFMONTH(start_date) AS event from ". TABLE_EVENTS_CALENDAR ." where DAYOFMONTH(start_date) = '". $day ."' and MONTH(start_date) = '". $month ."' and YEAR(start_date) = '". $year ."' and language_id = '". $languages_id ."' and event_id != '". $select_event ."'order by start_date");
if (tep_db_num_rows($other_events_query) > 0) {
          echo '<table border="0" width="100%" cellspacing="0" cellpadding="2" class="event"><tr>'.
               '<td class="main" colspan="2"><b>'. TEXT_OTHER_EVENTS .'</b></td>'.
               '</tr>';

          while ($other_events = tep_db_fetch_array($other_events_query)){
                 $event_array = array('id' => $other_events['event_id'],
                                      'event' => $other_events['event'],
                                      'title' => $other_events['title']);

          echo '<tr><td align="center" width="24" class="main" nowrap><b>'. $i .'</b></td><td width="100%" class="main"><a href="'. FILENAME_EVENTS_CALENDAR . '?select_event='. $event_array['id'] .'">'. $event_array['title'] .'</a></td></tr>';
          $i++;
          }

echo '</table>';
  }
}
elseif($HTTP_GET_VARS['year_view'] == 1){
          echo '<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>';
          echo '<td>'. $cal->getYearView($year_) .'</td>';
          echo '</tr></table>';
}

elseif($HTTP_GET_VARS['_day']){

$events_query_raw = "select *, DAYOFMONTH(start_date) AS event from ". TABLE_EVENTS_CALENDAR ." where DAYOFMONTH(start_date) = '". $_day ."' and MONTH(start_date) = '". $_month ."' and YEAR(start_date) = '". $_year ."' and language_id = '". $languages_id ."' order by start_date";
$events_split = new splitPageResults($events_query_raw, "10", 'DAYOFMONTH(start_date)');
$events_query = tep_db_query($events_query_raw);

      if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      </td>
      </tr>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES .' : '. $date); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
       </td>
      </tr>
      <tr><td>

<?php
}
    $row = 0;
    $events_query = tep_db_query($events_split->sql_query);

    while ($events = tep_db_fetch_array($events_query)){
          $row++;
          list ($year, $month, $day) = preg_split('/[/.-]/', $events['start_date']);
          $date = date("F j, Y", mktime(0,0,0,$month,$day,$year));
          $event_array = array('id' => $events['event_id'],
                            'event' => $events['event'],
                            'title' => $events['title'],
                            'link' => $events['link'],
                            'date' => $date );
          $clsp = 2;
          echo '<table border="0" width="100%" cellspacing="0" cellpadding="4" class="event">'.
               '<tr>'.
               '<td align="center" width="20" bgcolor="#F5F5F5" class="main" nowrap><b>'. $i .'</b></td><td width="100%" bgcolor="#D9DEE6" class="main" nowrap>'. TEXT_EVENT_DATE .'&nbsp;&nbsp;<a href="'. $cal->getDbLink($event_array['event'], $month_, $year_) .'">'. $event_array['date'] .'</a></td>';
               if($event_array['link']){
               echo '<td width="100%" bgcolor="#D9DEE6" class="main" nowrap>'. TEXT_EVENT_LINK .'&nbsp;&nbsp;<a href="http://'. $event_array['link'] .'" target="_blank">'. $event_array['link'] .'</a></td>';
               $clsp++;
               }
          echo '</tr><tr>'.
               '<td colspan="3" class="main">'. TEXT_EVENT_TITLE .'<br>'. $event_array['title'] .'&nbsp;&nbsp;<a href="'. FILENAME_EVENTS_CALENDAR . '?select_event='. $event_array['id'] .'">'. TEXT_EVENT_MORE .'</a></td>'.
               '</tr></table>'.
               tep_draw_separator('pixel_trans.gif', '100%', '4');
               $i++;
          }
     if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      </td>
      </tr>
      <tr>
        <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES .' : '. $date); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
    <td>
<?php
  }
}elseif($HTTP_GET_VARS['view'] == 'all_events'){

$events_query_raw = "select *, DAYOFMONTH(start_date) AS event from ". TABLE_EVENTS_CALENDAR ." where start_date > '". date('Y-m-d H:i:s') ."' and language_id = '". $languages_id ."' order by start_date";
$events_split = new splitPageResults($events_query_raw, MAX_DISPLAY_NUMBER_EVENTS, 'DAYOFMONTH(start_date)');
$events_query = tep_db_query($events_query_raw);

      if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      </td>
      </tr>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
       </td>
      </tr>
      <tr><td>

<?php
 }
    $row = 0;
    $events_query = tep_db_query($events_split->sql_query);

    while ($events = tep_db_fetch_array($events_query)){
          $row++;
          list ($year, $month, $day) = preg_split('/[/.-]/', $events['start_date']);
          $date = date("F j, Y", mktime(0,0,0,$month,$day,$year));
          $event_array = array('id' => $events['event_id'],
                            'event' => $events['event'],
                            'title' => $events['title'],
                            'link' => $events['link'],
                            'date' => $date );
          $clsp = 2;
          echo '<table border="0" width="100%" cellspacing="0" cellpadding="4" class="event">'.
               '<tr>'.
               '<td align="center" width="20" bgcolor="#F5F5F5" class="main" nowrap><b>'. $i .'</b></td><td width="100%" bgcolor="#D9DEE6" class="main" nowrap>'. TEXT_EVENT_DATE .'&nbsp;&nbsp;<a href="' . FILENAME_EVENTS_CALENDAR . '?select_event='. $event_array['id'] .'">'. $event_array['date'] .'</a></td>';
               if($event_array['link']){
               echo '<td width="100%" bgcolor="#D9DEE6" class="main" nowrap>'. TEXT_EVENT_LINK .'&nbsp;&nbsp;<a href="http://'. $event_array['link'] .'" target="_blank">'. $event_array['link'] .'</a></td>';
               $clsp++;
               }
          echo '</tr><tr>'.
               '<td colspan="3" class="main"><br><b>'. $event_array['title'] .'&nbsp;&nbsp;</b><a href="'. FILENAME_EVENTS_CALENDAR . '?select_event='. $event_array['id'] .'">'. TEXT_EVENT_MORE .'</a><br><br></td>'.
               '</tr></table>'.
               tep_draw_separator('pixel_trans.gif', '100%', '4');
               $i++;
          }
       if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      </td>
      </tr>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
    <td>

<?php
  }
}else{
$events_query_raw = "select *, DAYOFMONTH(start_date) AS event from ". TABLE_EVENTS_CALENDAR ." where MONTH(start_date) = '". $month_ ."' and YEAR(start_date) = '". $year_ ."' and language_id = '". $languages_id ."'  order by start_date";
$events_split = new splitPageResults($events_query_raw, MAX_DISPLAY_NUMBER_EVENTS, 'DAYOFMONTH(start_date)');
$months = $cal->monthNames[$month_ - 1];

echo '<table border="0" width="100%" cellspacing="0" cellpadding="2">'.
     '<tr><td colspan="3" class="main">' . $months .', '. $year_ .'</td>'.
     '</tr></table>'.
     '</td></tr>'.
     '<tr><td>&nbsp;</td></tr>'.
     '<tr><td>';
     
if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      </td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES .' : '. $months); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table><br></td>
      </tr>
      <tr><td>

<?php
}
    $row = 0;
    $events_query = tep_db_query($events_split->sql_query);

    while ($events = tep_db_fetch_array($events_query)){
          $row++;
          list ($year, $month, $day) = preg_split('/[/.-]/', $events['start_date']);
          $date = date("F j, Y", mktime(0,0,0,$month,$day,$year));
          
          $event_array = array('id' => $events['event_id'],
                               'event' => $events['event'],
                               'title' => $events['title'],
                               'link' => $events['link'],
                               'date' => $date );
          $clsp = 2;
          echo '<table border="0" width="100%" cellspacing="0" cellpadding="4" class="event">'.
               '<tr>'.
               '<td align="center"  bgcolor="#F5F5F5" class="main" nowrap><b>'. $i .'</b></td><td width="100%" bgcolor="#D9DEE6" class="main" nowrap>'. TEXT_EVENT_DATE .'&nbsp;&nbsp;<a href="'. $cal->getDbLink($event_array['event'], $month_, $year_) .'">'. $event_array['date'] .'</a></td>';
               if($event_array['link']){
               echo '<td width="100%" bgcolor="#D9DEE6" class="main" nowrap>'. TEXT_EVENT_LINK .'&nbsp;&nbsp;<a href="http://'. $event_array['link'] .'" target="_blank">'. $event_array['link'] .'</a></td>';
               $clsp++;
               }
          echo '</tr><tr>'.
               '<td colspan="3" class="main">'. TEXT_EVENT_TITLE .'<br>'. $event_array['title'] .'&nbsp;&nbsp;<a href="'. FILENAME_EVENTS_CALENDAR . '?select_event='. $event_array['id'] .'">'. TEXT_EVENT_MORE .'</a></td>'.
               '</tr></table>'.
               tep_draw_separator('pixel_trans.gif', '100%', '4');
          $i++;
          }
if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      </td>
      </tr>
      <tr>
        <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES .' : '. $months); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
    <td>
<?php

}else{

?>

<table border=0 width="100%" cellspacing="0" cellpadding="4"><tr>
<td class="main"><br><?php echo TEXT_NO_EVENTS; ?></td>
</tr></table>

<?php
    }
}
 
?>
<br><br>Rick Steves at Travel Retail and Trade Expo  2006 - Orlando FL  9/10/06-9/12/06<br>
<img src="images/09-10-06booth4.jpg" border="0" alt="TravelVideoStore.com display at Travel Retail and Trade Expo  2006 - Orlando FL  9/10/06-9/12/06"><br>
Jeffrey Lehamnn at Travel Retail and Trade Expo  2006 - Orlando FL  9/10/06-9/12/06<br>
<img src="images/09-10-06booth5.jpg" border="0" alt="TravelVideoStore.com display at Travel Retail and Trade Expo  2006 - Orlando FL  9/10/06-9/12/06"><br>

Travel Retail and Trade Expo  2006 - Orlando FL  9/10/06-9/12/06<br>
<img src="images/9-10-06booth1.jpg" border="0" alt="TravelVideoStore.com display at Travel Retail and Trade Expo  2006 - Orlando FL  9/10/06-9/12/06"><br>
Travel Retail and Trade Expo  2006 - Orlando FL  9/10/06-9/12/06<br>
<img src="images/09-10-06booth2.jpg" border="0" alt="TravelVideoStore.com display at Travel Retail and Trade Expo  2006 - Orlando FL  9/10/06-9/12/06"><br>
Travel Retail and Trade Expo  2006 - Orlando FL  9/10/06-9/12/06<br>
<img src="images/09-10-06booth3.jpg" border="0" alt="TravelVideoStore.com display at Travel Retail and Trade Expo  2006 - Orlando FL  9/10/06-9/12/06"><br>
Jeffrey Lehmann with some of our staff at Travel Retail and Trade Expo  2006 - Orlando FL  9/10/06-9/12/06<br>
<img src="images/09-10-06booth6.jpg" border="0" alt="TravelVideoStore.com display at Travel Retail and Trade Expo  2006 - Orlando FL  9/10/06-9/12/06"><br>


<br><br>Tour and Cruise TravelWorld 2006 - Tampa FL 3/24/06-3/25/06<br>
<img src="images/IMG_0545.JPG" border="0" alt="TravelVideoStore.com display at Tour and Cruise TravelWorld 2006 - Tampa, FL 3/24/06-3/25/06"><br>
Tour and Cruise TravelWorld 2006 - Tampa FL 3/24/06-3/25/06<br>
<img src="images/IMG_0546.JPG" border="0" alt="TravelVideoStore.com display at Tour and Cruise TravelWorld 2006 - Tampa, FL 3/24/06-3/25/06"><br>

<b>Justine Shapiro</b>, Internationally known host of the travel series "<b>Globe Trekker</b>" appearing at the TravelVideoStore.com booth during the Adventures in Travel Show in San Francisco - 11/11/05-11/13/05<br>
<img src="images/IMG_0220.JPG" border="0" alt="TravelVideoStore.com display at ISTTE Annual Conference - 10/20/05-10/23/05" ><br>
TravelVideoStore.com display at the Adventures in Travel Show in San Francisco - 11/11/05-11/13/05<br>
<img src="images/IMG_0215.JPG" border="0" alt="TravelVideoStore.com display at the Adventures in Travel Show in San Francisco - 11/11/05-11/13/05" ><br>


TravelVideoStore.com display at ISTTE Annual Conference - 10/20/05-10/23/05<br>
<img src="images/istte2005.jpg" border="0" alt="TravelVideoStore.com display at ISTTE Annual Conference - 10/20/05-10/23/05" ><br>

TravelVideoStore.com display at CLIA Cruise 360 - 9/29/05-10/1/05<br>
<img src="images/IMG_0115.JPG" border="0" alt="TravelVideoStore.com display at CLIA Cruise 360 - 9/29/05-10/1/05" ><br>
TravelVideoStore.com display at CLIA Cruise 360 - 9/29/05-10/1/05<br>
<img src="images/IMG_0112.JPG" border="0" alt="TravelVideoStore.com display at CLIA Cruise 360 - 9/29/05-10/1/05" ><br>
TravelVideoStore.com display at the Home Based Agent Show in Baltimore- 9/23/05-9/24/05<br>
<img src="images/IMG_0106.JPG" border="0" alt="TravelVideoStore.com display at the Home Based Agent Show in Baltimore- 9/23/05-9/24/05" ><br>
TravelVideoStore.com display at Adventures in Travel in New York - 1/14/05-1/16/05<br>
<img src="images/IMG_3588.JPG" border="0" alt="TravelVideoStore.com display at Adventures in Travel in New York - 1/14/05-1/16/05" ><br>
TravelVideoStore.com display at Travel Trade Cruise-a-Thon in Ft. Lauderdale - 12/4/04<br>
<img src="images/IMG_3413.JPG" border="0" alt="TravelVideoStore.com display at Travel Trade Cruise-a-Thon in Ft. Lauderdale - 12/4/04" >
</td>
  </tr>
    </table>
</td>

<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
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
