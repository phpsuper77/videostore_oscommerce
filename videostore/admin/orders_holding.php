<?php
/*
  $Id: orders_holding.php,v 1.1 2004-05-30 Nick Weisser, http://ganes.ch

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003-2004 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
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
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
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
    <td width="100%" valign="top"><table width="100%"  cellpadding="5">
      <tr>
        <td class="main"><p class="pageHeading">Holding Orders</p>
          <p>This module logs the order, products ordered and order total in three holding tables triggered at the checkout_confirmation.php page before the customer is passed to the payment processor. The process is seamless and doesn't interfere with the regular checkout process.</p>
          </td></tr>
      <tr>
        <td><table width="100%" class="smallText">
          <tr class="dataTableHeadingRow">
		  <th>Customer</th>
		  <th>Country</th>
		  <th>E-mail</th>
		  <th>checkout_confirmation.php</th>
		  <th>Date purchased</th>
		  <th>Checkout successful</th>
		  </tr>
		    <?php
		  $holding_orders = tep_db_query("select customers_id, customers_name, customers_email_address, customers_country, date_purchased from " . TABLE_HOLDING_ORDERS . " order by date_purchased desc");
		  while ($row = mysql_fetch_array ($holding_orders)) {
		  
		  $checkout_success = tep_db_query("select date_purchased from " . TABLE_ORDERS . " where customers_id = '" . $row['customers_id'] . "'");
		  mysql_num_rows ($checkout_success) > 0 ? $flag = 'yes' : $flag = 'no';
		  ?>
		    <tr<?php if ($flag == 'no') echo ' class="dataTableRowSelected"'?>;>
		     <td><?php echo $row['customers_name']; ?></td>
		     <td><?php echo $row['customers_country']; ?></td>		   
		     <td><a href="mailto:<?php echo $row['customers_email_address'];?>"><?php echo $row['customers_email_address']; ?></a></td>
		     <td><?php echo $row['date_purchased']; ?></td>	
		     <td><?php echo $success['date_purchased']; ?></td>			 
		     <td><?php echo $flag ?></td>			 			 
		    </tr>
		    <?php } ?>
	      </table></td></tr>
    </table>    </td>
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