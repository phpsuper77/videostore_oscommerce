<?php
/*
  $Id: checkout_shipping.php,v 1.16 2003/06/09 23:03:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
//var_dump($cart);

define('MODULE_SHIPPING_INSTALLED','usps.php;fedex1.php;dhlairborne.php;table.php;ups.php;zones.php');

if (!empty($_POST[vendor])){
	foreach ($_POST[vendor] as $key=>$value){
		$_SESSION['vendor'][$key] = $value;
		}
}

if (intval($_SESSION["vendors_id"])==0)
  {
	tep_redirect("../index.php");
	exit;
  }

if (!is_object($cart)){
    header('Location: https://secure.travelvideostore.com/vendors/distribution.php?error=Shopping cart is empty');
	exit;
}

if (intval($cart->count_contents())==0){
    header('Location: https://secure.travelvideostore.com/vendors/distribution.php?error=Shopping cart is empty');
	exit;
	}
	

  define('DIR_WS_LANGUAGES','../includes/languages/');
  define('DIR_WS_MODULES','../includes/modules/');
  define('DIR_WS_CLASSES','../includes/classes/');


  require(DIR_WS_CLASSES . 'http_client.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  require('includes/classes/order.php');
  $order = new order;

/* =============================PAYMENT========================== 

  DEFINE('MODULE_PAYMENT_INSTALLED','linkpointms1.php') ;
//var_dump($order);
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
    header('Location: https://secure.travelvideostore.com/vendors/distribution.php?error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED));
    exit;
  }



  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }
 ============================= END OF PAYMENT ========================== */
//var_dump($order);
// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents

  //if (!tep_session_is_registered('cartID')) tep_session_register('cartID');

  $cartID = $cart->cartID;


// ---------- ZERO WEIGHT OR VIRTUAL PRODUCTS ADDEDD 05FEB2005 ----------

   if ($cart->show_weight_vendor() == 0 ) {
	$order->content_type = 'virtual_weight';
    }

// ---------- ZERO WEIGHT OR VIRTUAL PRODUCTS END ----------

// ICW CREDIT CLASS GV AMENDE LINE BELOW
//  if ($order->content_type == 'virtual') {
  if (($order->content_type == 'virtual') || ($order->content_type == 'virtual_weight') ) {
    if (!tep_session_is_registered('shipping')) tep_session_register('shipping');
    $shipping = false;
    $sendto = false;
//    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
    header('Location: https://secure.travelvideostore.com/vendors/distribution.php');
    exit;
  }


  $total_weight = $cart->show_weight_vendor();
  $total_count = $cart->count_contents();



if ($_SESSION['vendor']['pickup']=='T'){
        $shipping = array('id' => '1',
                    'title' => '',
                    'cost' => '0.00');
	$_SESSION[shipping] = $shipping;
	header('Location: https://secure.travelvideostore.com/vendors/checkout_2.php');
	exit;
}

// load all enabled shipping modules
  require('includes/classes/shipping.php');
  $shipping_modules = new shipping;

  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
    $pass = false;

    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
        if ($order->delivery['country_id'] == STORE_COUNTRY) {
          $pass = true;
        }
        break;
      case 'international':
        if ($order->delivery['country_id'] != STORE_COUNTRY) {
          $pass = true;
        }
        break;
      case 'both':
        $pass = true;
        break;
    }

    $free_shipping = false;

    if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
      $free_shipping = true;

      include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
    }
  } else {
    $free_shipping = false;
  }


// process the selected shipping method
  if ( isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process') ) {
	$_SESSION['comments'] = tep_db_prepare_input($HTTP_POST_VARS['comments']);

    if ( (tep_count_shipping_modules() > 0) || ($free_shipping == true) ) {

      if ( (isset($HTTP_POST_VARS['shipping'])) && (strpos($HTTP_POST_VARS['shipping'], '_')) ) {
        $shipping = $HTTP_POST_VARS['shipping'];

        list($module, $method) = explode('_', $shipping);
        if ( is_object($$module) || ($shipping == 'free_free') ) {
          if ($shipping == 'free_free') {
            $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
            $quote[0]['methods'][0]['cost'] = '0';
          } else {
            $quote = $shipping_modules->quote($method, $module);
          }

          if (isset($quote['error'])) {
	    unset($_SESSION['shipping']);
            //tep_session_unregister('shipping');
          } else {

            if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {

              $shipping = array('id' => $shipping,
                                'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
                                'cost' => $quote[0]['methods'][0]['cost']);

	      $_SESSION['shipping'] = $shipping;
              //tep_redirect(tep_href_link('vendors/checkout_2.php', '', 'SSL'));
	      header('Location: https://secure.travelvideostore.com/vendors/checkout_2.php');
    	      exit;

            }
          }
        } else {
	  unset($_SESSION['shipping']);
          //tep_session_unregister('shipping');
        }
      }
    } else {
      $shipping = false;

 //     tep_redirect(tep_href_link('checkout_2.php', '', 'SSL'));
      header('Location: https://secure.travelvideostore.com/vendors/checkout_2.php');
      exit;

    }
  }

//var_dump($order);
// get all available shipping quotes
  $quotes = $shipping_modules->quote();
//echo "<hR>";
//var_dump($quotes);
//echo "<hR>";

// if no shipping method has been selected, automatically select the cheapest method.
// if the modules status was changed when none were available, to save on implementing
// a javascript force-selection method, also automatically select the cheapest shipping
// method if more than one module is now enabled
  if ( !tep_session_is_registered('shipping') || ( tep_session_is_registered('shipping') && ($shipping == false) && (tep_count_shipping_modules() > 1) ) ) $shipping = $shipping_modules->cheapest();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING);

?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="../stylesheet.css">
<link rel="stylesheet" type="text/css" href="./includes/main.css">
<?php include ('../includes/ssl_provider.js.php'); ?>


<script language="javascript"><!--
var selected;

function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_address.shipping[0]) {
    document.checkout_address.shipping[buttonSelect].checked=true;
  } else {
    document.checkout_address.shipping.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require('./includes/column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="checkout_address" action="https://secure.travelvideostore.com/vendors/checkout_1.php" method="post"><?php echo tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>

      <!-- Newly inserted-->
	        <tr>
	  	  	          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	  	        </tr>
	        <tr>
	  	  	<td align="center">
	  	  		<table border="0" width="80%" cellspacing="0" cellpadding="0">
	  	  		<tr>
					<td width="25%">
	  	  				<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  	  				<tr>
	  	  					<td width=""><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
	  	  					<td width="100%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  				</tr>
	  	  				</table>

</td>
	  	  			<td width="25%">
	  	  				<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  	  				<tr>
	  	  					<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  					<td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
	  	  					<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  				</tr>
	  	  				</table>
	  	  			</td>
	  	  			<td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  			<td width="25%">
	  	  				<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  	  				<tr>
	  	  					<td width="100%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  					<td width=""><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
	  	  				</tr>
	  	  				</table>
	  	  			</td>
	  	  		</tr>
	  	  		<tr>
	  	  			<td align="center" width="25%" class="checkoutBarFrom"><a href="distribution.php" class="checkoutBarFrom">Shopping Cart</a></td>
	  	  			<td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>
	  	  			<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
	  	  			<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
	  	  		</tr>
	  	  		</table>
	  	  	</td>
	</tr>

	<!--Ends here-->

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b>Shipping and Billing Info</b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td width="50%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td align="left" width="50%" valign="top"><table border="0" cellspacing="0" cellpadding="2" border="0">
                  <tr>
                    <td class="main" align="center" valign="top"><?php echo '<b>Shipping Address</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" valign="top"><?php echo tep_address_format(2, $order->delivery, false, '', '<br/>'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="50%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td align="left" width="50%" valign="top"><table border="0" cellspacing="0" cellpadding="2" border="0">
                  <tr>
                    <td class="main" align="center" valign="top"><?php echo '<b>Billing Address</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" valign="top"><?php echo tep_address_format(2, $order->billing, false, '', '<br/>'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>

          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if (tep_count_shipping_modules() > 0) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
    if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?></td>
                <td class="main" width="50%" valign="top" align="right"><?php echo '<b>' . TITLE_PLEASE_SELECT . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    } elseif ($free_shipping == false) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    }

    if ($free_shipping == true) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><b><?php echo FREE_SHIPPING_TITLE; ?></b>&nbsp;<?php echo $quotes[$i]['icon']; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                  <tr id="defaultSelected" class="moduleRowSelected" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="selectRowEffect(this, 0)">
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" width="100%"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER,'true','USD','1.000000')) . tep_draw_hidden_field('shipping', 'free_free'); ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    } else {
      $radio_buttons = 0;
      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><b><?php echo $quotes[$i]['module']; ?></b>&nbsp;<?php if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
        if (isset($quotes[$i]['error'])) {
?>
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><?php echo $quotes[$i]['error']; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
        } else {
          for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
// set the radio button to be checked if it is the method chosen
            $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $shipping['id']) ? true : false);

            if ( ($checked == true) || ($n == 1 && $n2 == 1) ) {
              echo '                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
            } else {
              echo '                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
            }
?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" width="75%"><?php echo $quotes[$i]['methods'][$j]['title']; ?></td>
<?php
            if ( ($n > 1) || ($n2 > 1) ) {
?>
                    <td class="main"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0)),'true','USD','1.000000'); ?></td>
                    <td class="main" align="right"><?php echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked); ?></td>
<?php
            } else {
?>
                    <td class="main" align="right" colspan="2"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax']),'true','USD','1.000000') . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?></td>
<?php
            }
?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
            $radio_buttons++;
          }
        }
?>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>


	  </tr>
<?php
      }
    }
?>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b>Selecting a Shipping Method :</b></td>
          </tr>
		  <tr>
            <td class="main">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We support both USPS and UPS shipping solutions for domestic and international orders.  While both provide excellent service and reliability, there are some differences between the carriers.<BR>
			<BR>

			<ul><b>USPS - United States Postal Service :</b>
			<li>Delivery confimation with each Domestic USA order and package tracking for Express only.</li>
			<li>Will deliver to P.O., APO, FPO addresses.</li>
			<li>More economical as the price is usually lower for similar service.</li>
                                                <li>No Insurance on Packages except Express. Insurance is available on the next screen</li>
			<li>Delivery time varies and can only be estimated.</li>
                                                <li>International Delivery time varies and can only be estimated as delays can occur due to individual country customs procedures.</li>
			</ul>

			<ul><b>FedEx - Federal Express</b>
			<li>Full package tracking on all services and <b>Insurance</b> up to $100.00.</li>
			<li>Will <u>NOT</u> deliver to P.O., APO, and FPO address.</li>
			<li>Delivery time is more precise and very rarely misses an estimated date.</li>

			</ul>

			<ul><b>Time Sensitive Orders</b>
                                                <li>International Orders may be subject to Customs and Brokerage Fees
			<li>Orders placed by 4PM EST will be shipped same day in most cases.  Please view our
			<?php echo '<a href=' . tep_href_link('shipping.php', '', 'NONSSL', false, true) . '><u>Shipping Policies</u></a>&nbsp;for more information.'?>
			  </li>
			</ul>
			</td>
          </tr>
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>
                <td class="main" align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>



	        <tr>
	  	  	<td align="center">
	  	  		<table border="0" width="80%" cellspacing="0" cellpadding="0">
	  	  		<tr>
					<td width="25%">
	  	  				<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  	  				<tr>
	  	  					<td width=""><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
	  	  					<td width="100%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  				</tr>
	  	  				</table>

</td>
	  	  			<td width="25%">
	  	  				<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  	  				<tr>
	  	  					<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  					<td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
	  	  					<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  				</tr>
	  	  				</table>
	  	  			</td>
	  	  			<td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  			<td width="25%">
	  	  				<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  	  				<tr>
	  	  					<td width="100%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  					<td width=""><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
	  	  				</tr>
	  	  				</table>
	  	  			</td>
	  	  		</tr>
	  	  		<tr>
	  	  			<td align="center" width="25%" class="checkoutBarFrom"><a href="distribution.php" class="checkoutBarFrom">Shopping Cart</a></td>
	  	  			<td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>
	  	  			<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
	  	  			<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
	  	  		</tr>
	  	  		</table>
	  	  	</td>
	</tr>
    </table></form></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require('footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>