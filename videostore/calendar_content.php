<?php
/*
  $Id: events_calendar v1.00 2003/03/08 18:09:16 ip chilipepper.it Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_EVENTS_CALENDAR);
?>
<!-- events_calendar //-->
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<style type="text/css">
<!--
.button {border: 1px outset; margin: 0px; color: #000000; width: 20px; height: 20px;
}
-->
</style>
<?php
// Construct a calendar to show the current month
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
$month_= $month;
$year_= $year;
}

echo '<table bgcolor="#EDECE9" width="100%" height= "240" align="center" border="0" cellspacing="0" cellpadding="0" style="cursor: default">';
echo '<tr><td height="22" class="main" align="left">&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_EVENTS_CALENDAR, 'view=all_events') . '" title="'. BOX_CALENDAR_TITLE .'"target="_parent">'. HEADING_TITLE .' ÅÅÅ </td></tr>';
echo '<tr><td align="center" valign="top">' . $cal->getMonthView($month,$year) . '</td></tr>';
echo '<tr>';
?>
<form method="get" name="calendar" action="events_calendar.php">
  <tr>
     <td style="line-height: 2px;">&nbsp;
     </td>
  </tr>
  <tr>
    <td align="center" valign="top" nowrap>
<?php
$monthShort = explode("," ,MONTHS_SHORT_ARRAY);
echo '<select name="_month" class="select">';
$month = date('m');
while (list($key, $value) = each($monthShort)){
if ($HTTP_GET_VARS['_month']){
    if($key+1 == $_month){
        $key=$key+1;
        echo '<option value="'. $key .'" selected>'. $value .'</option>' . "\n";
    }else{
        $key=$key+1;
    echo '<option value="'. $key .'">'. $value .'</option>' . "\n";
    }
}else{
    if($key+1 == $month){
        $key=$key+1;
        echo '<option value="'. $key .'" selected>'. $value .'</option>' . "\n";
    }else{
        $key=$key+1;
    echo '<option value="'. $key .'">'. $value .'</option>' . "\n";
    }
  }
}
echo '</select>';
echo '<select name="_year" class="select">';
$year = date('Y');
$years = NUMBER_OF_YEARS;
for ($y=0; $y < $years; $y++){
$_y = $year+$y;
if ($HTTP_GET_VARS['_month']){
    if($_y == $_year){
    echo '<option value="'. $_y .'" selected>'. $_y .'</option>' . "\n";
    }else{
    echo '<option value="'. $_y .'">'. $_y .'</option>' . "\n";
    }
}else{
    if($_y == $year){
    echo '<option value="'. $_y .'" selected>'. $_y .'</option>' . "\n";
    }else{
    echo '<option value="'. $_y .'">'. $_y .'</option>' . "\n";
    }
  }
}
?>
</select>

<SCRIPT LANGUAGE="JavaScript">
function jump(view, url){
if (document.all||document.getElementById){
    month= document.calendar._month.options[document.calendar._month.selectedIndex].value;
    year=  document.calendar._year.options[document.calendar._year.selectedIndex].value;
    return url +'?_month='+ month +'&_year='+ year +'&year_view='+ view;
 }
}
</SCRIPT>
<input type="button" class="button" title="<?php echo BOX_GO_BUTTON_TITLE; ?>" value="<?php echo BOX_GO_BUTTON; ?>"  onclick="top.window.location=jump(0,'<?php echo  FILENAME_EVENTS_CALENDAR ; ?>')">
<?php
if (($month != $this_month) || ($month_ != $this_month)){
?>
<br>
<input type="button" class="button" title="<?php echo BOX_TODAY_BUTTON_TITLE; ?>" value="<?php echo BOX_TODAY_BUTTON; ?>" onclick=top.calendar.location="<?php echo  FILENAME_EVENTS_CALENDAR_CONTENT ; ?>?_month=<?php echo $this_month .'&_year='. $this_year ?>">
<?php
}
?>

<input type="button" class="button" title="<?php echo BOX_YEAR_VIEW_BUTTON_TITLE; ?>" value="<?php echo BOX_YEAR_VIEW_BUTTON; ?>" onclick="top.window.location=jump(1,'<?php echo  FILENAME_EVENTS_CALENDAR ; ?>')">
     </td>
    </tr>
    <tr>
     <td height="100%">&nbsp;
     </td>
    </tr>
    </form>
</table>
<!-- events_calendar //-->
