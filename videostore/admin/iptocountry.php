<?php

  require('includes/application_top.php');

ini_set("memory_limit","128M");
ini_set("max_execution_time","240");

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'upload':

		if (is_uploaded_file($_FILES['sql_query']['tmp_name']))
		{
			$name = $_FILES['sql_query']['name'];
			move_uploaded_file($_FILES['sql_query']['tmp_name'], 'tmp/'.$name);		

$handle = @fopen ('tmp/'.$name, "r");

if ($handle) {

   tep_db_query("delete from ipcountry1 where 1>0");

   while (!feof($handle)) {
       $lines = fgets($handle, 8096);

	if (trim($lines)!=""){
	$pos = strpos(strtoupper($lines), "INSERT INTO");
	if ($pos!==false) tep_db_query(trim($lines));
	}
	
   }
   fclose($handle);
} 

//unlink('tmp/'.$name);
}
        tep_redirect('iptocountry.php?msg=Successufully Uploaded');
        break;
  }
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
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
            <td class="pageHeading">IP to Country Uploader</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
	<tr><td colspan="2"><font color="red"><b><? if ($msg!='') echo $msg;?></b></font></td></tr>
      <tr>
        <td>
	<table>
<form action="iptocountry.php?action=upload" method="post" enctype="multipart/form-data">
		<tr>
			<td><b>Upload File:</b></td>
			<td><input type="file" name="sql_query" />&nbsp;<input type="submit" value="&nbsp;Run&nbsp;"/></td>
	</table>
	</td>
              </tr>
</form>
            </table></td>
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
on_bottom.php'); ?>
