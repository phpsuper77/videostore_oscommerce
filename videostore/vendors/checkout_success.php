<?php

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();


if (intval($_SESSION["order_id_complete"])==0)
  {
	//tep_redirect("../index.php");
	header('Location: https://secure.travelvideostore.com/vendors/distribution.php');
	exit;
  }

if (intval($_SESSION["vendors_id"])==0)
  {
	tep_redirect("../index.php");
	exit;
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'update')) {
	unset($_SESSION['cart']);	
	unset($_SESSION['insert_order_id']);	
	unset($_SESSION['order_id_complete']);	
	header('Location: https://secure.travelvideostore.com/vendors/distribution.php');
	exit;
	}

if ($_SESSION[insert_order_id]!='') tep_db_query("update " . TABLE_HOLDING_ORDERS . " set orders_status='1' where orders_id='".$_SESSION[insert_order_id]."'");

 $_SESSION['insert_order_id']='';

  if (intval($_SESSION[vendors_id]==0)) {
	tep_redirect("../index.php");
  }


  require(DIR_WS_LANGUAGES . 'english/' . FILENAME_CHECKOUT_SUCCESS);

if ($_SESSION[order_id_complete]!=''){
 $order_update = array('purchased_without_account' => '1');
 tep_db_perform(TABLE_ORDERS, $order_update, 'update', "orders_id = '".$_SESSION[order_id_complete]."'");
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="../stylesheet.css">
<link rel="stylesheet" type="text/css" href="./includes/main.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require('includes/column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form action="https://secure.travelvideostore.com/vendors/checkout_success.php?action=update" method="post"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="4" cellpadding="2">
          <tr>
            <!--td valign="top"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_man_on_board.gif', HEADING_TITLE); ?></td-->
            <td valign="top" class="main" align="center"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?><div align="center" class="pageHeading"><?php echo HEADING_TITLE; ?></div><!--<br><?php echo TEXT_SUCCESS; ?><br><br>-->
<?
  require('includes/classes/order.php');
  $order = new order($_SESSION[order_id_complete]);

?>
            <h3><?php echo TEXT_THANKS_FOR_SHOPPING; ?><br><br>
            	Your Order Number is : <?php echo $_SESSION[order_id_complete]?><br/>
		Date : <?php echo $order->info['date_purchased']; ?><br/>
		<a target="_new" href="../invoice.php?oID=<?php echo $_SESSION[order_id_complete]?>"><img src="../images/print_invoice.gif" border="0" alt="Print Invoice" title="Print Invoice" /></a>&nbsp;<input type="image" src='../includes/languages/english/images/buttons/button_continue.gif' />
	   </h3></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" width="25%" class="checkoutBarFrom">Shopping Cart</td>
            <td align="center" width="25%" class="checkoutBarFrom">Delivery Information</td>
            <td align="center" width="25%" class="checkoutBarFrom">Confirm</td>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
          </tr>
        </table><br/><br/></td>
      </tr>
<tr>
	<td align="center">
	<table width="100%" border="0">
		<tr>
			<td width="50%" valign="top">
<fieldset style="height:220px;padding:0px 10px 10px 10px;">
	<legend><h3>Billing Information:</h3></legend>
<?php echo strtoupper(tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>')); ?><br/>
<?php echo $order->customer['telephone']; ?><br/>
<?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?><br/><br/>
<b>Payment method:</b>&nbsp;<?php echo $order->info['payment_method']; ?>
</fieldset>
			</td>
			<td width="50%" valign="top">
<fieldset style="height:220px;padding:0px 10px 10px 10px;">
	<legend><h3>Shipping Information:</h3></legend>
<?php echo strtoupper(tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>')); ?>
</fieldset>
			</td>
		</tr>
<tr><td colspan="2">
<fieldset style="padding:0px 10px 10px 10px;">
	<legend><h3>Product Details:</h3></legend>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" colspan="2">Products</td>
        <td class="dataTableHeadingContent">Products Model</td>
        <td class="dataTableHeadingContent" align="right">Price</td>
        <td class="dataTableHeadingContent" align="right">Total</td>
      </tr>
<?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" .
           '        <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

      if (isset($order->products[$i]['attributes']) && (($k = sizeof($order->products[$i]['attributes'])) > 0)) {
        for ($j = 0; $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
          echo '</i></small></nobr>';
        }
      }

      echo '        </td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n";
      echo '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '      </tr>' . "\n";
    }
?>
      <tr>
        <td align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">
<?php
  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
    echo '          <tr>' . "\n" .
         '            <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
         '            <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
        </table></td></TR>
    </table>
</fieldset>
</td>
</tr>
	</table>
	</td>
</tr>
<tr>
<td class="smallText" style="padding:5px;">
Please write this down if you wish to track this order in our system.<br/>You will receive an Order Confirmation via email shortly.<br/><br/>
Note:<br/>
If you do not receive the confirmation message within next several hours, check your spam folder in case the confirmation email got delivered there instead of your inbox. If so, select the confirmation message and click This is Not Spam (AOL), Not Junk (Hotmail), Not Spam (Yahoo), Not Spam (Gmail) or the like, which will allow future messages to get through.<br/><br/>
To ensure security, this order may require voice confirmation with our Credit Department prior to shipping. If this is necessary, a representative will call or email you while processing your order. Pricing and availability are subject to change. We reserve the right to cancel any order before it is shipped.<br/><br/>
Multiple Items <br/>
If you ordered multiple items, we may deliver them in separate shipments to ensure the fastest service.  However, you won't be charged additional shipping fees for this faster service.<br/><br/>
Order Tracking<br/>
Tracking the status of your order is easy.  All you need is your email address and password.  <a href="https://www.travelvideostore.com/account.php" style="color:#0000ff">Click here</a> to track your order online.
How is my order processed?<br/><br/>
Payment Processing<br/>
Your credit card is charged immediately upon placing your order. Your order is then thoroughly reviewed by our Credit Card Department to ensure that the order is accurate, the payment method is valid, and that you are authorized to use this payment method.  Once your order has passed the rigorous review by our Credit Card Department, it is immediately forwarded to Fulfillment.<br/><br/>
Order Fulfillment<br/>
If the item(s) on your order are in-stock, we will ship them immediately from our main Tampa, Florida warehouse.  All orders are reviewed for accuracy and are delivered safely and accurately via the shipping method that you selected.<br/><br/>
Overnight Delivery<br/>
In-stock items ordered by 4pm (EST) and designated for overnight delivery will be released from our warehouse the date the order was placed (pending credit card approval and verification.)  In-stock items ordered after 4pm (EST) with overnight delivery may take an additional business day for delivery.  We reserve the right to switch the ship method on out of stock items.<br/><br/>
This receipt of order does not signify our acceptance of your order, nor does it constitute confirmation of our offer to sell.
</td>
</tr>
<?php //require('add_checkout_success.php'); //ICW CREDIT CLASS/GV SYSTEM ?>

<?php if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php'); ?>
    </table></form></td>
<!-- body_text_eof //-->
  </tr>
</table>
<SCRIPT LANGUAGE="JavaScript">
<!-- Overture Services Inc. 07/15/2003
var cc_tagVersion = "1.0";
var cc_accountID = "1195816829";
var cc_marketID =  "0";
var cc_protocol="http";
var cc_subdomain = "convctr";
if(location.protocol == "https:")
{
    cc_protocol="https";
     cc_subdomain="convctrs";
}
var cc_queryStr = "?" + "ver=" + cc_tagVersion + "&aID=" + cc_accountID + "&mkt=" + cc_marketID +"&ref=" + escape(document.referrer);
var cc_imageUrl = cc_protocol + "://" + cc_subdomain + ".overture.com/images/cc/cc.gif" + cc_queryStr;
var cc_imageObject = new Image();
cc_imageObject.src = cc_imageUrl;
// -->
</SCRIPT>
<!-- Google Code for Purchase Conversion Page -->
<script language="JavaScript" type="text/javascript">
<!--
var google_conversion_id = 1070868310;
var google_conversion_language = "en_US";
var google_conversion_format = "2";
var google_conversion_color = "666666";
if (1.0) {
  var google_conversion_value = 1.0;
}
var google_conversion_label = "Purchase";
//-->
</script>
<script language="JavaScript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<img height=1 width=1 border=0 src="https://www.googleadservices.com/pagead/conversion/1070868310/?value=1.0&label=Purchase&script=0">
</noscript>
<!-- Google Code for Purchase Conversion Page -->
<script language="JavaScript" type="text/javascript">
<!--
var google_conversion_id = 1071828622;
var google_conversion_language = "en_US";
var google_conversion_format = "2";
var google_conversion_color = "666666";
if (1.0) {
  var google_conversion_value = 1.0;
}
var google_conversion_label = "Purchase";
//-->
</script>
<script language="JavaScript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<img height=1 width=1 border=0 src="https://www.googleadservices.com/pagead/conversion/1071828622/?value=1.0&label=Purchase&script=0">
</noscript>


<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
<?php 
	// osCoders.biz - Analystics - start 
		//include(DIR_WS_MODULES . 'analytics/analytics.php'); 
	// osCoders.biz - Analistics - end
	?>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>