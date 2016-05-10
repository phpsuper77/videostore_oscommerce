<?
  require('includes/application_top.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
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
            <td class="pageHeading">WARE HOUSE LOCATIONS:</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top">
<?
if ($_POST[action]=="save"){
		$i = 0;
if (strpos($_POST[from], "*") === false) $search = "p.products_warehouse_location='".$_POST[from]."'"; else $search = "p.products_warehouse_location like '".str_replace('*','%',$_POST[from])."'";
		$products = tep_db_query("select p.*, pd.* from products p left join products_description pd on (p.products_id=pd.products_id) where ".$search." order by p.products_warehouse_location ASC, p.products_model ASC");	
	while ($row = tep_db_fetch_array($products)){		
		$id = $row[products_id];		
		if ($location[$id]!='') {
			$i++;
			$sql_query = "update products set products_warehouse_location='".$location[$id]."', products_quantity='".$quantity[$id]."', products_status='".$status[$id]."' where products_id='".$row[products_id]."'";
			//echo $sql_query."<br/>";
			tep_db_query($sql_query);
			}
	}
	echo "<script>window.location.href='locations.php?msg=".$i." products successullfy updated!';</script>";
}

if ($_POST[action]=="change"){
	echo "<form action='locations.php' method='post'><input type='hidden' name='action' value='save' /><input type='hidden' name='from' value='".$_POST[from]."' /><table border=1><tr style='font-weight:bold;'><td>PRODUCTS ID</td><td>PRODUCTS MODEL</td><td>PRODUCTS NAME</td><td>CURRENT LOCATION</td><td>NEW LOCATION</td><td>ON HAND</td><td>STATUS</td></tr>";
if (strpos($_POST[from], "*") === false) $search = "p.products_warehouse_location='".$_POST[from]."'"; else $search = "p.products_warehouse_location like '".str_replace('*','%',$_POST[from])."'";
		$products = tep_db_query("select p.*, pd.* from products p left join products_description pd on (p.products_id=pd.products_id) where ".$search." order by p.products_warehouse_location");
	while ($row = tep_db_fetch_array($products)){
		if ($row[products_status]==1) { $sel1 = ''; $sel2='selected';} else { $sel1 = 'selected'; $sel2='';}
		echo "<tr><td>".$row[products_id]."</td><td>".$row[products_model]."</td><td>".$row[products_name_prefix]." ".$row[products_name]." ".$row[products_name_suffix]."</td><td>".$row[products_warehouse_location]."</td><td><input type='text' name='location[$row[products_id]]' value='".$_POST[to]."' /></td><td><input type='text' name='quantity[$row[products_id]]' value='".$row[products_quantity]."' /></td><td><select name='status[$row[products_id]]'><option value='0' ".$sel1.">Inactive</option><option value='1' ".$sel2.">Active</option></select></td></tr>";
	}
	echo "</table><br/><input type='button' value='Back' onclick='window.location.href=\"locations.php\"'/>&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='Save'/></form><hr>";	
	//echo "<script>window.location.href='locations.php';</script>";
}

if ($_GET[msg]!='') echo "<b>".$_GET[msg]."</b>";
?>
<form action="locations.php" method="post">
	<input type="hidden" name="action" value="change" />
	<b>From:</b> <input type="text" name="from" id="from" value="<?=$_POST[from]?>"- <small> Use * as wildcard, ie. A3* gives A3, A3A, A3B, A3C, A3d.</small><br/><br/>
	<b>To:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="to" id="to" value="<?=$_POST[to]?>"> - <small> optional field. Will insert default data for new warehouse location.</small><br/><br/>
<input type="submit" value="Show products" />
</form>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>