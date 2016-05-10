<?php
/*
  $Id: customers.php,v 1.82 2003/06/30 13:54:14 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

function timeDiff($timestamp1,$timestamp2){

$difference = $timestamp1 - $timestamp2;

$days = floor($difference/86400);
$difference = $difference - ($days*86400);

$hours = floor($difference/3600);
$difference = $difference - ($hours*3600);

$minutes = floor($difference/60);
$difference = $difference - ($minutes*60);

$seconds = $difference;
//$output = "$days Days, $hours Hours, $minutes Minutes, $seconds Seconds";
$output = "$hours:$minutes:$seconds";

return $output;
}

function timeSumm($timestamp1,$timestamp2){

$difference = $timestamp1 + $timestamp2;

$days = floor($difference/86400);
$difference = $difference + ($days*86400);

$hours = floor($difference/3600);
$difference = $difference + ($hours*3600);

$minutes = floor($difference/60);
$difference = $difference + ($minutes*60);

$seconds = $difference;
//$output = "$days Days, $hours Hours, $minutes Minutes, $seconds Seconds";
$output = "$hours:$minutes:$seconds";

return $output;
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style>
table.stdTable{
	border-collapse: collapse;
}

table.stdTable th {
	padding:2px;
	border: 1px solid #B3BAC5; 
	color: #B3BAC5;
	font-weight: bold;
	text-align:center;
	font-size: 11px;
}

table.stdTable th a {
	font-size: 11px;
	color: #B3BAC5;
	font-weight: bold;
}


table.stdTable td {
	padding:2px;
	border: 1px solid #B3BAC5; 	
}
</style>
<script language="javascript" src="includes/general.js"></script>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
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
            <td class="pageHeading">TIME TRACKER</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
		   <tr>
		<td>
<?
if ((($_GET[action]=='') and ($_POST[action]=='')) || ($_POST[action]=='search')){


$month = isset($_POST[month]) ? $_POST[month] : date("m");
$year = isset($_POST[year]) ? $_POST[year] : date("Y");
$sstring = isset($_POST[sstring]) ? $_POST[sstring] : '';

if ($sstring) $addon = " and description like '%".$sstring."%'";

$starting = mktime(0,0,0, $month, 1, $year);
$ending = mktime(0,0,0, $month+1, 1, $year);
$where ="where timestart>=".$starting." and timeend<".$ending;

	$sql_query = "select * from timetracker ".$where.$addon." order by timestart desc";
	$bugs = tep_db_query($sql_query);
?>
<br/>
<form action="timetracker.php" method="post">
<input type="hidden" name="action" value="search" />
<table>
	<tr>
		<Td class="main">
			Search string: <input type="text" name="sstring" value="<?=$sstring?>" style="width: 150px;"/>&nbsp;&nbsp;
			<select name="month">
				<option value="01" <?if ($month=='01') echo 'selected';?>>January</option>
				<option value="02" <?if ($month=='02') echo 'selected';?>>February</option>
				<option value="03" <?if ($month=='03') echo 'selected';?>>March</option>
				<option value="04" <?if ($month=='04') echo 'selected';?>>April</option>
				<option value="05" <?if ($month=='05') echo 'selected';?>>May</option>
				<option value="06" <?if ($month=='06') echo 'selected';?>>June</option>
				<option value="07" <?if ($month=='07') echo 'selected';?>>July</option>
				<option value="08" <?if ($month=='08') echo 'selected';?>>August</option>
				<option value="09" <?if ($month=='09') echo 'selected';?>>September</option>
				<option value="10" <?if ($month=='10') echo 'selected';?>>October</option>
				<option value="11" <?if ($month=='11') echo 'selected';?>>November</option>
				<option value="12" <?if ($month=='12') echo 'selected';?>>December</option>
			</select>
		</td>
		<td>
			<select name="year" >
				<option value="2007" <?if ($year=='2007') echo 'selected';?>>2007</option>
				<option value="2008" <?if ($year=='2008') echo 'selected';?>>2008</option>
				<option value="2009" <?if ($year=='2009') echo 'selected';?>>2009</option>
				<option value="2010" <?if ($year=='2010') echo 'selected';?>>2010</option>

                                                                <option value="2011" <?if ($year=='2011') echo 'selected';?>>2011</option>
                                                                <option value="2012" <?if ($year=='2012') echo 'selected';?>>2012</option>
			</select>
		</td>
		<td><input type="submit" value="&nbsp;Search&nbsp;" /></td>
	</tr>
</table>
</form>
<table width="100%"><tr><td align="right"><a href="timetracker.php?action=details&id="><b>Add Memo</b></a></td></tr></table>
	<table width="100%" class="stdTable">		
		<tr>	
			<th>ID</th>
			<th>Time Start</a></th>
			<th>Time Finished</a></th>
			<th>Total Hours</a></th>
			<th width="60%">Description</th>
			<th>Action</th>
		</tr>
<?
$hours = 0; $mins = 0; $secs = 0;
	while ($bug = tep_db_fetch_array($bugs)){
$starttime = date("m-d-Y H:i:s", mktime(date("H", $bug[timestart]), date("i", $bug[timestart]), date("s", $bug[timestart]), date("m", $bug[timestart]), date("d", $bug[timestart]), date("Y", $bug[timestart])));
if (intval($bug[timeend])!=0) 
$endtime = date("m-d-Y H:i:s", mktime(date("H", $bug[timeend]), date("i", $bug[timeend]), date("s", $bug[timeend]), date("m", $bug[timeend]), date("d", $bug[timeend]), date("Y", $bug[timeend])));

		echo "<tr><td class='smalltext' align='center'>".$bug[id]."</td>";
		echo "<td class='smalltext' align='center'>".$starttime."</td>";
		echo "<td  align='center' class='smalltext'>".$endtime."</td>";
if (intval($bug[timeend])!=0)
$diff = timediff($bug[timeend], $bug[timestart]);
else
$diff = "0:00:00";
$pos = explode(":",$diff);
$hours = $hours+$pos[0];
$mins = $mins+$pos[1];
$secs = $secs+$pos[2];
		echo "<td  align='center' class='smalltext'>".$diff."</td>";
		echo "<td align='center' class='smalltext'><b>".$bug[description]."</b></td></td>";
		echo "<td align='center' class='smalltext'><a href='timetracker.php?action=details&id=".$bug[id]."'><img src='images/icons/edit.gif' border='0' alt='Edit Item'/></a>&nbsp;<a onclick='return (confirm(\"Do you really want to delete this item?\"))' href='timetracker.php?action=delete&id=".$bug[id]."'><img src='images/icons/delete.gif' border='0' alt='Delete Item'/></a></td></td></tr>";
	}
$total = ($hours*60)+$mins+($secs/60);
$total = $total/60;
?>
<tr class="smalltext"><td colspan="3" align="right"><b>Total: </b></td><td colspan="3"><b><?=round($total,2)?></b></td></tr>
	</table>
<?}

if ($_GET[action]=='delete'){
	tep_db_query("delete from timetracker where id='".$_GET[id]."'");
	echo "<script>window.location.href='timetracker.php'</script>";		
}

if ($_GET[action]=='details'){
	$sql_query = 'select * from timetracker where id='.intval($id);
	$bug = tep_db_fetch_array(tep_db_query($sql_query));
?>

<form action="timetracker.php" method="post" name="bug">
	<input type="hidden" name="id" value="<?=$bug[id]?>" />
	<input type="hidden" name="action" value="save" />
	<table width="100%">
		<tr>
		<td>
			<b>Time Start:</b><br/>
			<input type="text" name="timestart" value="
<?
if (intval($bug[timestart])!=0)
	echo date("m-d-Y H:i:s", mktime(date("H", $bug[timestart]), date("i", $bug[timestart]), date("s", $bug[timestart]), date("m", $bug[timestart]), date("d", $bug[timestart]), date("Y", $bug[timestart])));
	else
	echo date("m-d-Y H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
	
?>
" style="width:200px" />
		</td>
		</tr>

		<tr>
		<td>
			<b>Time Finished:</b><br/>
			<input type="text" name="timeend" value="
<?
if (intval($_GET[id]!=0)){
if (intval($bug[timeend])!=0)
	echo date("m-d-Y H:i:s", mktime(date("H", $bug[timeend]), date("i", $bug[timeend]), date("s", $bug[timeend]), date("m", $bug[timeend]), date("d", $bug[timeend]), date("Y", $bug[timeend])));
	else
	echo date("m-d-Y H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));	
}
?>

" style="width:200px" />
		</td>
		</tr>
				
		<tr>
		<td>
			<b>Description:</b><br/>
			<textarea name="description" style="width:700px;height:200px;"><?=$bug[description]?></textarea>
		</td>
		</tr>
		<tr>
		<td align="center">
			<input type="submit" value="&nbsp;Save&nbsp;" />&nbsp;&nbsp;&nbsp;<input type="button" value="&nbsp;Cancel&nbsp;" onclick="location.href='timetracker.php'" />
		</td>
		</tr>

	</table>
</form>
<?
}

if ($_POST[action]=='save'){

if ($_POST[timestart]!=''){
	$pos = explode(" ", $_POST[timestart]);
	$dd = explode("-", $pos[0]);
	$dt = explode(":", $pos[1]);
	$starttime = mktime($dt[0], $dt[1], $dt[2], $dd[0], $dd[1], $dd[2]);
}

if ($_POST[timeend]!=''){
	$pos = explode(" ", $_POST[timeend]);
	$dd = explode("-", $pos[0]);
	$dt = explode(":", $pos[1]);
	$endtime = mktime($dt[0], $dt[1], $dt[2], $dd[0], $dd[1], $dd[2]);
}

	if (intval($_POST[id])==0)
		$sql_query = "insert into timetracker set description='".$_POST[description]."', timestart='".$starttime."', timeend='".$endtime."'";
	else
		$sql_query = "update timetracker set description='".$_POST[description]."', timestart='".$starttime."', timeend='".$endtime."' where id=".$_POST[id];

	tep_db_query($sql_query);
	echo "<script>window.location.href='timetracker.php'</script>";	
}
?>

		</td>
                   </tr>
      
  </table></td>
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