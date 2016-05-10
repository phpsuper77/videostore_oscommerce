<?php
/*
  $Id: customers.php,v 1.82 2003/06/30 13:54:14 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

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
            <td class="pageHeading">BUG/PROJECT TRACKING SYSTEM</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
	<tr>
		<td>
<i>Here is the site where you could write me short message for me to know that something is wrong and we need to fix it as soon as possible <a href="http://www.kyivstar.net/sms/" target="_top">http://www.kyivstar.net/sms/</a> the only thing you need is to select <b>067</b> from dropdown menu insert mu mobile number is <b>7084039</b> and in the textarea you could write short message up to 160 symbols then you need to insert numbers that you will see under texarea in input field to the right from them and press button, I will get this message and will try to fix any bug as soon as it possilble you could test it right now of course if you have some time</i>
		</td>
	</tr>
		   <tr>
		<td>
<?
if ($_GET[action]=='' and $_POST[action]==''){

$order = $_GET[fieldname]." ".$_GET[direction];

if (trim($order)=='') $order = "status asc, priority desc, date_added desc";
if ($_GET[direction]=='asc') $direction='desc';
if (trim($direction)=='') $direction='asc';

	$sql_query = "select * from tracking order by ".$order;
	$query_count = tep_db_num_rows(tep_db_query($sql_query));
	$bugs_split = new splitPageResults($HTTP_GET_VARS['page'], 100, $sql_query, $query_count);
	$bugs = tep_db_query($sql_query);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td class="main" valign="top"><?php echo $bugs_split->display_count($query_count, 100, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="main" align="right"><?php echo $bugs_split->display_links($query_count, 100, 10, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
</table>
<br/>
<table width="100%"><tr><td align="right"><a href="tracking.php?action=details&id="><b>Add task</b></a></td></tr></table>
	<table width="100%" class="stdTable">		
		<tr>	
			<th><a href="tracking.php?page=<?=$_GET[page]?>&fieldname=ID&direction=<?=$direction?>" style="font-size: 11px;color: #B3BAC5;font-weight: bold;">ID</a></th>
			<th><a href="tracking.php?page=<?=$_GET[page]?>&fieldname=priority&direction=<?=$direction?>" style="font-size: 11px;color: #B3BAC5;font-weight: bold;">Priority</a></th>
			<th width="70%"><a href="tracking.php?page=<?=$_GET[page]?>&fieldname=title&direction=<?=$direction?>" style="font-size: 11px;color: #B3BAC5;font-weight: bold;">Title</a></th>
			<th><a href="tracking.php?page=<?=$_GET[page]?>&fieldname=date_added&direction=<?=$direction?>" style="font-size: 11px;color: #B3BAC5;font-weight: bold;">Date Added</a></th>
			<th><a href="tracking.php?page=<?=$_GET[page]?>&fieldname=status&direction=<?=$direction?>" style="font-size: 11px;color: #B3BAC5;font-weight: bold;">Status</a></th>
			<th>Action</th>
		</tr>
<?
	while ($bug = tep_db_fetch_array($bugs)){
		echo "<tr><td class='smalltext' align='center'>".$bug[id]."</td>";
if ($bug[priority]=='0') $priority = 'Low';
if ($bug[priority]=='1') $priority = '<font color="blue">Medium</font>';
if ($bug[priority]=='2') $priority = '<font color="red">High</font>';
		echo "<td class='smalltext' align='center'><b>".$priority."</b></td>";
		echo "<td class='smalltext' align='left'>".$bug[title]."</td>";
		echo "<td  align='center' class='smalltext'>".date("m-d-Y",$bug[date_added])."</td>";
if ($bug[status]=='Opened')$status = '<font color="red">'.$bug[status].'</font>';
		echo "<td align='center' class='smalltext'><b>".$bug[status]."</b></td></td>";
		echo "<td align='center' class='smalltext'><a href='tracking.php?page=".$_GET[page]."&action=details&id=".$bug[id]."'><img src='images/icons/edit.gif' border='0' alt='Edit Item'/></a>&nbsp;<a href='tracking.php?action=view&page=".$_GET[page]."&id=".$bug[id]."'><img src='images/icons/preview.gif' border='0' alt='Preview Item'/></a>&nbsp;<a onclick='return (confirm(\"Do you really want to delete this item?\"))' href='tracking.php?action=delete&page=".$_GET[page]."&id=".$bug[id]."'><img src='images/icons/delete.gif' border='0' alt='Delete Item'/></a></td></td></tr>";
	}
?>
	</table>
<br/>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td class="main" valign="top"><?php echo $bugs_split->display_count($query_count, 100, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="main" align="right"><?php echo $bugs_split->display_links($query_count, 100, 10, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
</table>

<?}

if ($_GET[action]=='details'){
	$sql_query = 'select * from tracking where id='.intval($id);
	$bug = tep_db_fetch_array(tep_db_query($sql_query));
?>

<script>
	function createAttach(){
	obj = document.getElementById('divholder');
	total = obj.childNodes.length+1;
	var y = document.createElement('div');
	y.id = 'attach_'+total;	
	y.name = 'attach_'+total;
	y.innerHTML = '<b>Attachments #'+total+':</b>&nbsp;<input type="file" id="attach_'+total+'" name="attach_'+total+'" />';	
	obj.appendChild(y);
	}

	function checkForm(){		
		title = document.bug.title.value
		error = '';
		if (title=='') error = error+'Title field is required!\n';
			if (error!=''){
				alert(error);
				return false;
			}
		else
				return true;

	}
</script>

<form action="tracking.php" method="post" enctype="multipart/form-data" name="bug" onsubmit="return checkForm();">
	<input type="hidden" name="id" value="<?=$bug[id]?>" />
	<input type="hidden" name="action" value="save" />
	<input type="hidden" name="page" value="<?=$_GET[page]?>" />
	<table width="100%">
		<tr>
		<td>
			<b>Title:</b><br/>
			<input type="text" name="title" value="<?=$bug[title]?>" style="width:700px" />
		</td>
		</tr>

		<tr>
		<td>
			<b>Description:</b><br/>
			<textarea name="description" style="width:700px;height:200px;"><?=$bug[description]?></textarea>
		</td>
		</tr>
<?
if ($bug[priority]=='0') $selected_1 = 'selected';
if ($bug[priority]=='1') $selected_2 = 'selected';
if ($bug[priority]=='2') $selected_3 = 'selected';
?>
		<tr>
		<td>
			<b>Priority:</b><br/>
			<select name="priority">
				<option value="0" <?=$selected_1?>>Low</option>
				<option value="1" <?=$selected_2?>>Medium</option>
				<option value="2" <?=$selected_3?>>High</option>
			</select>
		</td>
		</tr>
<?
if ($bug[status]=='Opened') $status_1 = 'selected';
if ($bug[status]=='Closed') $status_2 = 'selected';
?>

		<tr>
		<td>
			<div id='divholder'>
			<div><b>Attachments #1:</b>&nbsp;<input type="file" id="attach_1" name="attach_1" /></div>
			</div>
			<div align="right" style="width:315px;"><a href="#" onclick="createAttach(); return false;"><b>Add attachment</b></a></div>
		</td>
		</tr>

<?
if (intval($bug[id])!=0){
?>
		<tr>
		<td>
			<table class="stdTable" width="500">
				<tr><Th colspan="2">List of attaches:</th><th>Action</th></tr>
<?
if (is_dir("tmp/tracking/".$bug[id]."")){
$handle = opendir ("tmp/tracking/".$bug[id]."");
$i=0;
if ($handle){
while ($file = readdir($handle)){
		if ($file != "." && $file != ".."){
		$i++;
?>
				<tr><Td align="center" class="smalltext"><?=$i?></td><td width="95%" class="smalltext"><a target="_new" href="tmp/tracking/<?=$bug[id]?>/<?=$file?>"><?=$file?></a></td><td align="center"><a onclick="return (confirm('Do you really want to delete this file?'))" href="tracking.php?action=del_file&id=<?=$bug[id]?>&filename=<?=$file?>&page=<?=$_GET[page]?>"><img src="images/icons/delete.gif" alt="Delete <?=$file?>" title="Delete <?=$file?>" border="0" /></a></td></tr>
<?			}
		}
	}
}
?>
			</table>
		</td>
		</tr>
<?
}
?>
		<tr>
		<td>
			<b>Status:</b><br/>
			<select name="status">
				<option value="Opened" <?=$status_1?>>Opened</option>
				<option value="Closed" <?=$status_2?>>Closed</option>
			</select>
		</td>
		</tr>
		<tr>
		<td align="center">
			<input type="submit" value="&nbsp;Save&nbsp;" />&nbsp;&nbsp;&nbsp;<input type="button" value="&nbsp;Cancel&nbsp;" onclick="location.href='tracking.php?page=<?=$_GET[page]?>'" />
		</td>
		</tr>

	</table>
</form>
<?
}

if ($_POST[action]=='save'){
	if (intval($_POST[id])==0){
		$sql_query = "insert into tracking set title='".$_POST[title]."', description='".$_POST[description]."', priority='".$_POST[priority]."', status='".$_POST[status]."', date_added='".mktime(date("H", date("i"), date("s"), date("m"), date("d"), date("Y")))."'";
		tep_db_query($sql_query);
		$id = mysql_insert_id();
		}
	else{
		$sql_query = "update tracking set title='".$_POST[title]."', description='".$_POST[description]."', priority='".$_POST[priority]."', status='".$_POST[status]."' where id=".$_POST[id];
		tep_db_query($sql_query);
		$id = $_POST[id];
		}


for ($i=1;$i<count($_FILES)+1;$i++){		
        $name = '';
	$name = $_FILES['attach_'.$i]['name'].substr($name,strpos($name,'.')+1,strlen($name));
	if (!is_dir("tmp/tracking/".$id)) mkdir("tmp/tracking/".$id, 0777);
	move_uploaded_file($_FILES['attach_'.$i]['tmp_name'],  "tmp/tracking/".$id."/". $name);
	}
	echo "<script>window.location.href='tracking.php?page=".$_POST[page]."'</script>";
}

if ($_GET[action]=='del_file'){
	if (is_file("tmp/tracking/".$_GET[id]."/".$filename))
		unlink("tmp/tracking/".$_GET[id]."/".$filename);		
	echo "<script>window.location.href='tracking.php?action=details&id=".$_GET[id]."&page=".$_GET[page]."'</script>";	
}

if ($_GET[action]=='delete'){
	if (is_dir("tmp/tracking/".$_GET[id])){
		rmdirr("tmp/tracking/".$_GET[id]);		
	}

	tep_db_query("delete from tracking where id=".$_GET[id]);

	echo "<script>window.location.href='tracking.php?page=".$_GET[page]."'</script>";	
}

function rmdirr($dir) {
  if (substr($dir,-1) != "/") $dir .= "/";
  if (!is_dir($dir)) return false;

  if (($dh = opendir($dir)) !== false) {
   while (($entry = readdir($dh)) !== false) {
     if ($entry != "." && $entry != "..") {
       if (is_file($dir . $entry) || is_link($dir . $entry)) unlink($dir . $entry);
       else if (is_dir($dir . $entry)) rmdirr($dir . $entry);
     }
   }
   closedir($dh);
   rmdir($dir);

   return true;
  }
  return false;
}


if ($_GET[action]=='view'){
	$sql_query = 'select * from tracking where id='.intval($id);
	$bug = tep_db_fetch_array(tep_db_query($sql_query));
?>
	<table width="100%">
		<tr>
		<td class="smalltext">
			<b>Title:</b><br/>
			<font size="+1"><?=$bug[title]?></font><br/><br/>
		</td>
		</tr>

		<tr>
		<td style="font-size:15px;">
			<b>Description:</b><br/>
			<?=$bug[description]?><br/><br/>
		</td>
		</tr>
<?
if ($bug[priority]=='0') $selected = 'Low';
if ($bug[priority]=='1') $selected = '<font color="blue">Medium</font>';
if ($bug[priority]=='2') $selected = '<font color="кув">High</font>';
?>
		<tr>
		<td class="smalltext">
			<b>Priority:</b><br/><b><?=$selected?></b><br/><br/>
		</td>
		</tr>
<?
if ($bug[status]=='Opened') $status = 'Opened';
if ($bug[status]=='Closed') $status = 'Closed';

if (intval($bug[id])!=0){
?>
		<tr>
		<td>
			<table class="stdTable" width="500">
				<tr><Th colspan="2">List of attaches:</th></tr>
<?
if (is_dir("tmp/tracking/".$bug[id]."")){
$handle = opendir ("tmp/tracking/".$bug[id]."");
$i=0;
if ($handle){
while ($file = readdir($handle)){
		if ($file != "." && $file != ".."){
		$i++;
?>
				<tr><Td align="center" class="smalltext"><?=$i?></td><td width="95%" class="smalltext"><a target="_new" href="tmp/tracking/<?=$bug[id]?>/<?=$file?>"><?=$file?></a></td></tr>
<?			}
		}
	}
}
?>
			</table>
		</td>
		</tr>
<?
}
?>
		<tr>
		<td class="smalltext"><br/>
			<b>Status:</b><br/><b><?=$status?></b>
		</td>
		</tr>
		<tr>
		<td align="center">
			<input type="button" value="&nbsp;Back&nbsp;" onclick="location.href='tracking.php?page=<?=$_GET[page]?>'" />
		</td>
		</tr>

	</table>
</form>
<?
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
