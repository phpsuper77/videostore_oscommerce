<?php
  require('includes/application_top.php');
//print_r($_SESSION);


define('MODULE_SHIPPING_INSTALLED','usps.php;fedex1.php;dhlairborne.php;table.php;ups.php;zones.php');

if (intval($_SESSION["vendors_id"])==0)
  {
	tep_redirect("../index.php");
	exit;
  }


  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

//  DEFINE('MODULE_ORDER_TOTAL_INSTALLED','ot_coupon.php;ot_qty_discount.php;ot_subtotal.php;ot_tax.php;ot_giftwrap.php;ot_shipping.php;ot_insurance.php;ot_gv.php;ot_total.php');
if ($_SESSION['vendor']['pickup']=='T')
DEFINE('MODULE_ORDER_TOTAL_INSTALLED','ot_subtotal.php;ot_tax.php;ot_total.php');
else  
DEFINE('MODULE_ORDER_TOTAL_INSTALLED','ot_subtotal.php;ot_tax.php;ot_shipping.php;ot_total.php');

/*------------------Don't touch it uses in tep_get_tax_rate function in general.php */
$checkout_flag = true;
/*------------------Don't touch it uses in tep_get_tax_rate function in general.php */



// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    header('Location: https://www.travelvideostore.com/vendors/distribution.php');
    exit;
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
    //tep_redirect(tep_href_link('vendors/checkout_1.php', '', 'SSL'));
    header('Location: https://www.travelvideostore.com/vendors/checkout_1.php');
    exit;
    }
  }


// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (empty($_SESSION[shipping])) {
//    tep_redirect(tep_href_link('vendors/checkout_1.php', '', 'SSL'));
    header('Location: https://www.travelvideostore.com/vendors/checkout_1.php');
    exit;
  }

  if ($_SESSION['comments']!='') {
    $comments = $_SESSION['comments'];
  }

  require('includes/classes/order.php');
  $order = new order;


//  if (!tep_session_is_registered('payment')) tep_session_register('payment');
//  if (isset($HTTP_POST_VARS['payment'])) $payment = $HTTP_POST_VARS['payment'];

  $payment = 'linkpointms1';

// load the selected payment module

  DEFINE('MODULE_PAYMENT_INSTALLED','linkpointms1.php') ;

  require('includes/classes/payment.php');
  if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS


  $payment_modules = new payment($payment);


//ICW ADDED FOR CREDIT CLASS SYSTEM
  require(DIR_WS_CLASSES . 'order_total.php');

      if (ereg('^4[0-9]{12}([0-9]{3})?$', $_SESSION['vendor']['cc_number'])) {
        $cc_type_name = 'Visa';
      } elseif (ereg('^5[1-5][0-9]{14}$', $_SESSION['vendor']['cc_number'])) {
        $cc_type_name = 'Master Card';
      } elseif (ereg('^3[47][0-9]{13}$', $_SESSION['vendor']['cc_number'])) {
        $cc_type_name = 'American Express';
      } elseif (ereg('^3(0[0-5]|[68][0-9])[0-9]{11}$', $_SESSION['vendor']['cc_number'])) {
        $cc_type_name = 'Diners Club';
      } elseif (ereg('^6011[0-9]{12}$', $_SESSION['vendor']['cc_number'])) {
        $cc_type_name = 'Discover';
      } elseif (ereg('^(3[0-9]{4}|2131|1800)[0-9]{11}$', $_SESSION['vendor']['cc_number'])) {
        $cc_type_name = 'JCB';
      } elseif (ereg('^5610[0-9]{12}$', $_SESSION['vendor']['cc_number'])) { 
        $cc_type_name = 'Australian BankCard';
      } else {
        $cc_type_name = '';
      }

$_SESSION['cc_type'] = $cc_type_name;


//var_dump($order);
  $payment_modules->update_status();
//ICW ADDED FOR CREDIT CLASS SYSTEM
  $order_total_modules = new order_total;
//ICW ADDED FOR CREDIT CLASS SYSTEM
  $order_total_modules->collect_posts();
//ICW ADDED FOR CREDIT CLASS SYSTEM
  $order_total_modules->pre_confirmation_check();


// ICW CREDIT CLASS Amended Line
//if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
    if ( (is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($$payment)) && (!$credit_covers) ) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }



  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }


// load the selected shipping module
  require('includes/classes/shipping.php');
  $shipping_modules = new shipping($shipping);


//ICW Credit class amendment Lines below repositioned
//  require(DIR_WS_CLASSES . 'order_total.php');
//  $order_total_modules = new order_total;


//var_dump($_SESSION[shipping]);
// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="../stylesheet.css">
<link rel="stylesheet" type="text/css" href="./includes/main.css">
<?php include ('../includes/ssl_provider.js.php'); ?>

				<?php
				// osCoders.biz - Analystics - start
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

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require('includes/column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?> </td><tr><td bgcolor="FFFF00" class="largeText">YOU MUST PRESS THE CONFIRM ORDER BUTTON TO COMPLETE YOUR ORDER</td></tr>
          </tr>
	  <tr><td align="center" style="color:red;"><b><? if (!empty($error_message)) echo $error_message?></b></td></tr>
        </table></td>
      </tr>

	<!-- New insertion starts here-->
      <tr>
	  	          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
	  	            <tr>
	  	              <td align="right" class="main">
											  	</td>
					  </tr>
					  </table>
				</td>
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
	  				            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
	  				              <tr>
	  				                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  				                <td><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
	  				                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  				              </tr>
	  				            </table></td>
	  				            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
	  				              <tr>
	  				                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  				                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
	  				              </tr>
	  				            </table></td>
	  				          </tr>
	  				          <tr>
	  				            <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link('vendors/distribution.php', '', 'SSL') . '" class="checkoutBarFrom">Shopping Cart</a>'; ?></td>
	  				            <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link('vendors/checkout_1.php', '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>
	  				            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
	  				            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
	  				          </tr>
	          </table></td>

      </tr>
	<!-- ends here-->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
<?php
  if (!empty($order->delivery)) {
?>
            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_DELIVERY_ADDRESS . '</b> <a href="https://www.travelvideostore.com/vendors/distribution.php"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?></td>
              </tr>
<?php
    if ($order->info['shipping_method']) {
?>
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_SHIPPING_METHOD . '</b> <a href="https://www.travelvideostore.com/vendors/checkout_1.php"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo $order->info['shipping_method']; ?></td>
              </tr>
<?php
    }
?>
            </table></td>
<?php
  }
?>
            <td width="<?php if (!empty($order->delivery)) echo '70%'; else echo '100%'; ?>" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (sizeof($order->info['tax_groups']) > 1) {
?>
                  <tr>
                    <td class="main" colspan="2"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="https://www.travelvideostore.com/vendors/distribution.php"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TAX; ?></b></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>
                  </tr>
<?php
 } else {
?>
                  <tr>
                    <td class="main" colspan="3"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="https://www.travelvideostore.com/vendors/distribution.php"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
                  </tr>
<?php
}

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    echo '          <tr>' . "" .
         '            <td class="main" align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "" .
         '            <td class="main" valign="top">' . $order->products[$i]['name'];

$part = explode("-",substr($order->products[$i][products_date_available],0,10));
$date_available = mktime(0, 0, 1, $part[1], $part[2], $part[0]);
$curr_date = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));

if ((intval($date_available)>$curr_date) and ($order->products[$i]['products_out_of_print']=='1')){
	 echo "<span class='markProductOutOfStock'>**Pre-Ordered Item will ship on ".$part[1]."-".$part[2]."-".$part[0]."**</span>";
}
else{
if ($order->products[$i]['products_always_on_hand']!=1){
    if (STOCK_CHECK == 'true') {
      echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
    }
  }
}
    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>';
      }
    }

    echo '</td>' . "";
    if (sizeof($order->info['tax_groups']) > 1) echo '<td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "";

    echo '            <td class="main" align="right" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'])*$order->products[$i]['qty'], 'true','USD', '1.000000') . '</td>' . "" .
         '          </tr>' . "";
$total = $total+trim(str_replace("$","", $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'])*$order->products[$i]['qty'], 'true','USD', '1.000000')));
  }

echo "<tr><td colspan='5' align='right'>Total: ".$currencies->format($total,true,'USD','1.000000')."</td></tr>";
?>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo HEADING_BILLING_INFORMATION; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_BILLING_ADDRESS . '</b> <a href="https://www.travelvideostore.com/vendors/distribution.php"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b> <a href="https://www.travelvideostore.com/vendors/distribution.php"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo $order->info['payment_method']; ?></td>
              </tr>
            </table></td>
            <td width="70%" valign="top" align="right"><table border="0" cellspacing="0" cellpadding="2">
<?php
//  print_r(MODULE_ORDER_TOTAL_INSTALLED);
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $holding_order_totals = $order_total_modules->process();
    echo $order_total_modules->output();
}  
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo HEADING_PAYMENT_INFORMATION; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" colspan="4"><?php echo $confirmation['title']; ?></td>
              </tr>
<?php
      for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
?>
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo $confirmation['fields'][$i]['field']; ?></td>
              </tr>
<?php
      }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
    }
  }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if (tep_not_null($order->info['comments'])) {
?>
      <tr>
        <td class="main"><?php echo '<b>' . HEADING_ORDER_COMMENTS . '</b> <a href="https://www.travelvideostore.com/vendors/checkout_1.php"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
            <td align="right" class="main">
<?php
  if (isset($$payment->form_action_url)) {
    $form_action_url = $$payment->form_action_url;
  } else {
    $form_action_url = 'https://www.travelvideostore.com/vendors/checkout_complete.php';
  }

  echo tep_draw_form('checkout_confirmation', $form_action_url, 'post');

	function stripSymbols($str){
	
	return $str;
}
//echo htmlspecialchars($payment_modules->process_button());

	if (is_array($payment_modules->modules)) {
	//$str = eregi_replace("#", "", $payment_modules->process_button());
	//$str = eregi_replace("&", "", $str);
    echo $payment_modules->process_button();
  }

  //echo tep_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER) . '</form>' . "";
?>
<input onclick="document.getElementById('confirm_button').style.display='none';document.getElementById('in_process').style.display='block';" type="image" id="confirm_button" src="../includes/languages/english/images/buttons/button_confirm_order.gif" border="0" alt="Confirm Order" title=" Confirm Order "><span id="in_process" style="display:none;"><font style="font-size:15px;" color="red">Processing Your Order</font>&nbsp;&nbsp;&nbsp;<img src="../images/waiting_small.gif" alt="In porcess" title="In process"  /></span>
            </td>
          </tr>

		<tr>
          <td class="main">
          	<!--ORDER IP RECORDER-->
          	<?php echo HEADING_IPRECORDED_1;
				$ip1 = $HTTP_SERVER_VARS["REMOTE_ADDR"];
				$client = gethostbyaddr($HTTP_SERVER_VARS["REMOTE_ADDR"]);
				$str = preg_split("/\./", $client);
				$i = count($str);
				$x = $i - 1;
				$n = $i - 2;
				$isp = $str[$n] . "." . $str[$x]; ?>
				<?php echo "<br><span class='main'>Your ip address:<b> $ip1</b><br>Your Internet Service Provider:<b> $isp</b></span>"; ?>
			<!--ORDER IP RECORDER-->
			</td>
        </tr>

        </table></td>
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
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link('vendors/distribution.php', '', 'SSL') . '" class="checkoutBarFrom">Shopping Cart</a>'; ?></td>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link('vendors/checkout_1.php', '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>

                                    
<!-- body_eof //-->

<?php
//Log order into holding tables before checkout is passed to payment processor

	  $sql_data_array = array('customers_id' => $customer_id,
	                          'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
	                          'customers_street_address' => $order->customer['street_address'],
	                          'customers_suburb' => $order->customer['suburb'],
	                          'customers_city' => $order->customer['city'],
	                          'customers_postcode' => $order->customer['postcode'], 
	                          'customers_state' => $order->customer['state'], 
	                          'customers_country' => $order->customer['country']['title'],
	                          'customers_telephone' => $order->customer['telephone'], 
	                          'customers_email_address' => $order->customer['email_address'],
	                          'customers_address_format_id' => $order->customer['format_id'], 
	                          'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'], 
	                          'delivery_street_address' => $order->delivery['street_address'], 
	                          'delivery_suburb' => $order->delivery['suburb'], 
	                          'delivery_city' => $order->delivery['city'], 
	                          'delivery_postcode' => $order->delivery['postcode'], 
	                          'delivery_state' => $order->delivery['state'], 
	                          'delivery_country' => $order->delivery['country']['title'],
	                          'delivery_address_format_id' => $order->delivery['format_id'], 
				  'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'], 
	                          'billing_street_address' => $order->billing['street_address'], 
	                          'billing_suburb' => $order->billing['suburb'], 
	                          'billing_city' => $order->billing['city'], 
	                          'billing_postcode' => $order->billing['postcode'], 
	                          'billing_state' => $order->billing['state'], 
	                          'billing_country' => $order->billing['country']['title'],
	                          'billing_address_format_id' => $order->billing['format_id'],
	                          'payment_method' => $order->info['payment_method'], 
	                          'cc_type' => $order->info['cc_type'], 
	                          'cc_owner' => $order->info['cc_owner'], 
	                          'cc_number' => $order->info['cc_number'], 
	                          'cc_expires' => $order->info['cc_expires'], 
	                          'date_purchased' => 'now()', 
	                          'orders_status' => '0', 
	                          'comments' => $order->info['comments'], 
	                          'currency' => $order->info['currency'], 
	                          'currency_value' => $order->info['currency_value']);
//var_dump($order->info);
//echo "<br><br>";
//var_dump($sql_data_array);
//break;
			 tep_db_perform(TABLE_HOLDING_ORDERS, $sql_data_array);
			 $insert_id = tep_db_insert_id();
			$_SESSION['insert_order_id'] = $insert_id;

		   for ($i=0, $n=sizeof($holding_order_totals); $i<$n; $i++) {
		   	      $sql_data_array = array('orders_id' => $insert_id,
		   	                              'title' => $holding_order_totals[$i]['title'],
		   	                              'text' => $holding_order_totals[$i]['text'],
		   	                              'value' => $holding_order_totals[$i]['value'], 
		   	                              'class' => $holding_order_totals[$i]['code'], 
		   	                              'sort_order' => $holding_order_totals[$i]['sort_order']);
		   tep_db_perform(TABLE_HOLDING_ORDERS_TOTAL, $sql_data_array);
			 }
       
       for ($i=0; $i<sizeof($order->products); $i++) {
       $sql_data_array = array('orders_id' => $insert_id, 
                             'products_id' => tep_get_prid($order->products[$i]['id']), 
                             'products_model' => $order->products[$i]['model'], 
                             'products_name' => $order->products[$i]['name'], 
                             'products_price' => $order->products[$i]['price'], 
                             'final_price' => $order->products[$i]['final_price'], 
                             'products_tax' => $order->products[$i]['tax'], 
                             'products_quantity' => $order->products[$i]['qty']);
       tep_db_perform(TABLE_HOLDING_ORDERS_PRODUCTS, $sql_data_array);
       $order_products_id = tep_db_insert_id();
    	 if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
       	for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
       		$attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
       		$attributes_values = tep_db_fetch_array($attributes);
       		$sql_data_array = array('orders_id' => $insert_id, 
       	                        'orders_products_id' => $order->products[$i]['id'], 
       	                        'products_options' => $attributes_values['products_options_name'],
       	                        'products_options_values' => $attributes_values['products_options_values_name'], 
       	                        'options_values_price' => $attributes_values['options_values_price'], 
       	                        'price_prefix' => $attributes_values['price_prefix']);
       	
       	tep_db_perform(TABLE_HOLDING_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
      	}
      }
    }
          
//End log order before payment
?>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');  ?>
