<?php
ob_start();
/*
  $Id: vendor_order_products.php,v 1.80 2005/25/08 11:40:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . 'english/'.  FILENAME_VENDOR_ORDER_PRODUCTS);

  require(DIR_WS_INCLUDES .'database_tables.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  require('includes/split_page_results.php');
  require('includes/functions.php');

  session_start();
  if (isset($_SESSION["vendors_id"]) && $_SESSION["vendors_id"] <> "")
  {
  	//$products_id_sql = tep_db_query("select products_id from ". TABLE_PRODUCTS_TO_VENDORS ." where vendors_id = ". $_SESSION["vendors_id"]);
  }
  else
  {
	tep_redirect("../index.php");
	exit;
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="./includes/main.css">
<link rel="stylesheet" type="text/css" href="../stylesheet.css">

<script language="javascript"><!--
function rowOverEffect(object) {
  if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
}


//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<table border="0" width="100%" cellspacing="1" cellpadding="1">
<tr>
	<td width="100%" class="headerNavigation">&nbsp;<a href="../index.php" class="headerNavigation"><?php echo TXT_HOME?></span></a>&nbsp;-&nbsp;<a href="vendor_account_products.php" class="headerNavigation"><?php echo TXT_PRODUCT_INFO;?></a>&nbsp;-&nbsp;<a href="payment_details.php?vID=<?=$_GET[vID]?>&type=<?=$_GET[type]?>" class="headerNavigation"><b>Payment</b></td>
</tr>
</table>

<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php
	if (tep_session_is_registered('vendors_id'))
		require('./includes/column_left.php');
?>
<!-- left_navigation_eof //-->
    </table></td>
        <td width="100%" valign="top"><br/>
		<div class="pageHeading1" colspan=6>PAYMENTS</div>
<table width="100%">
<?
if ($_GET['type']==1) $stat = "Direct Purchase";
if ($_GET['type']==2) $stat = "Consignment";
if ($_GET['type']==3) $stat = "Royalty";

$products_sel_sql = "select  * from vendor_payments where vendor_id=".$_GET['vID']." and type='".$stat."'";
$products_sel = tep_db_query($products_sel_sql);
?>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">Payment ID</td>
		<td class="dataTableHeadingContent">Payment Date</td>
		<td class="dataTableHeadingContent">PO_ID</td>
		<td class="dataTableHeadingContent">Method</td>
		<td class="dataTableHeadingContent">Type</td>
		<td class="dataTableHeadingContent">Status</td>
		<td class="dataTableHeadingContent">Total</td>
	      </tr>
<?
	while($row = tep_db_fetch_array($products_sel)){
	echo '<tr><td class="dataTableContent">'.$row[id].'</td><td class="dataTableContent">'.$row[date].'</td><td class="dataTableContent">'.$row[po_id].'</td><td class="dataTableContent">'.$row[method].'</td><td class="dataTableContent">'.$row[type].'</td><td class="dataTableContent">'.$row[status].'</td><td class="dataTableContent">'.$currencies->format($row[payment],true,'USD','1.000000').'</td></tr>';
}
?>
</table>
</td>
      </tr>
<?
	              echo "<tr><td colspan=9 align=right valign=top><a href='vendor_account_view.php'>".tep_image(DIR_WS_INCLUDES_LOCAL.'images/button_back.gif')."</td></tr>";
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<br/><br/>
<!-- footer //-->
<?php require('footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
