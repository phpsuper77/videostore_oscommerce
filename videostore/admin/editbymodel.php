<?php
/*
  $Id: distributors.php,v 1.55 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');


$model_number = $_POST['model_number'] ? $_POST['model_number'] : $_GET['model_number'];

if ($_GET['action']=='search'){
	$sql_query = tep_db_query("SELECT products_id from products where products_model like '%".tep_db_prepare_input($model_number)."%' limit 1");
	$total = tep_db_num_rows($sql_query);
	$row = tep_db_fetch_array($sql_query);
	if ($total == 1) {
	echo "<script>window.location.href='categories.php?cPath=&pID=".$row[products_id]."&action=new_product'</script>";
	}
}
?>
<hr><hr>
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">SEARCH BY MODEL NUMBER</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
		<table width="100%">
<form action="editbymodel.php?action=search" method="POST">	
<tr><td colspan="12" align="right">Search by products model:&nbsp;<input type="text" name="model_number" value="<?=$model_number?>" style="width:150px;" />&nbsp;<input type="submit" value="Search" />&nbsp;<input type="button" value="Reset" onclick="window.location.href='editbymodel.php?action=reset'" /></td></tr>
</form>	
<? if ($total == 0) echo "<tr><td colspan='3' align='center' class='smalltext'>No products found</td></tr>";?>
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
