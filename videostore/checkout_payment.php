<?php
/*
  $Id: checkout_payment.php,v 1.113 2003/06/29 23:03:27 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
tep_redirect(tep_href_link(FILENAME_CHECKOUT));

  /* ---- Code Change By Karthik. if the Coupon id And Gift Voucher is registered, Unrigester Session value,  ---*/
  tep_session_unregister('cot_gv');
  tep_session_unregister('gv_redeem_code');
  tep_session_unregister('gv_id');
  //tep_session_unregister('od_amount');
  unset($_SESSION['insur_stat']);
  unset($_SESSION['tot_cart_val']);


  /* ---------- Code End ----------------------------*/

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// if we have been here before and are coming back get rid of the credit covers variable
	if(tep_session_is_registered('credit_covers')) tep_session_unregister('credit_covers');  //ICW ADDED FOR CREDIT CLASS SYSTEM


// Stock Check
  if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
        break;
      }
    }
  }

// if no billing destination address was selected, use the customers own address as default
  if (!tep_session_is_registered('billto')) {
    tep_session_register('billto');
    $billto = $customer_default_address_id;
  } else {
// verify the selected billing address
    $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
    $check_address = tep_db_fetch_array($check_address_query);

    if ($check_address['total'] != '1') {
      $billto = $customer_default_address_id;
      if (tep_session_is_registered('payment')) tep_session_unregister('payment');
    }
  }

// purchaseorders_1_4 start
    $qry_cust_allow_po_entry = tep_db_query("select customers_allow_purchase_order_entry from " . TABLE_CUSTOMERS . " where customers_id = " . (int)$customer_id);
    $cust_allow_po_entry = tep_db_fetch_array($qry_cust_allow_po_entry);
// purchaseorders_1_4 end

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;
  require(DIR_WS_CLASSES . 'order_total.php');//ICW ADDED FOR CREDIT CLASS SYSTEM
  $order_total_modules = new order_total;//ICW ADDED FOR CREDIT CLASS SYSTEM


  if (!tep_session_is_registered('comments')) tep_session_register('comments');
  if (!tep_session_is_registered('comments_slip')) tep_session_register('comments_slip');

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();
  $total_count = $cart->count_contents_virtual(); //ICW ADDED FOR CREDIT CLASS SYSTEM

// load all enabled payment modules
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>


<script language="javascript"><!--
var selected;
<?php// following jscript function ICW ADDED FOR CREDIT CLASS SYSTEM ?>
var submitter = null;
function submitFunction() {
   submitter = 1;
   }
<?php// END OF ICW ADDED FOR ORDER_TOTAL CREDIT SYSTEM ?>

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
  if (document.checkout_payment.payment[0]) {
    document.checkout_payment.payment[buttonSelect].checked=true;
  } else {
    document.checkout_payment.payment.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=500,screenX=150,screenY=30,top=30,left=150')
}
//--></script>
<?php echo $payment_modules->javascript_validation(); ?>
				<?php
				// osCoders.biz - Analystics - start
				/*
				Conditional code added thank to  rrodkey and bfcase
				IMPORTANT -- IMPORTANT - IMPORTANT
				You'll need to update the "xxxx-x" in the samples (twice) above with your own Google Analytics account and profile number.
				To find this number you can access your personalized tracking code in its entirety by clicking Check Status in the Analytics Settings page of your Analytics account.
				*/
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
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<?
###
#	changes made by Ramesh for Purchase order/check
###
  // This code demonstrates how to lookup the country by IP Address
  //include_once("./includes/geoip/geoip.inc");
  //$gi = geoip_open("./includes/geoip/GeoIP.dat",GEOIP_STANDARD);
  //$ip = $_SERVER['REMOTE_ADDR'];
  //$ip = "71.99.177.139";
  //$ip = "161.184.200.75";
  //$country_code = geoip_country_code_by_addr($gi, $ip);
  //geoip_close($gi);

  $ip = $_SERVER['REMOTE_ADDR'];
  $country_query  = "SELECT countrySHORT FROM ipcountry WHERE ipFROM<=inet_aton('$ip') AND ipTO>=inet_aton('$ip') ";
  $country_exec = tep_db_query($country_query);
  $ccode_array = tep_db_fetch_array($country_exec);
  $country_code=strtoupper($ccode_array['countrySHORT']);
#########


?>

<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', 'onsubmit="return check_form();"'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
<?php
  if (isset($HTTP_GET_VARS['payment_error']) && is_object(${$HTTP_GET_VARS['payment_error']}) && ($error = ${$HTTP_GET_VARS['payment_error']}->get_error())) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo tep_output_string_protected($error['title']); ?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
          <tr class="infoBoxNoticeContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" valign="top"><?php echo tep_output_string_protected($error['error']); ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
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
<!-- Newly inserted -->
<!---Start of Discount Code-->
<!--<script language='javascript'>
var disc_arr = new Array();
<?
	  $discount_query_raw = "select *  from discounts order by discount_id";
	  $discount_query = tep_db_query($discount_query_raw);
	  $i=1;
	  while ($row_discount_query = tep_db_fetch_array($discount_query))
	  {

?>
		disc_arr[<?php echo $i?>] = '<?php echo $row_discount_query[discount_code];?>';
<?
	    $i++;
	  }
?>

function check_disc()
{
	if (document.checkout_payment.coupontfield.value != '')
	{
		flag = 0;
		for (i=0;i<disc_arr.length;i++)
		{
			if (document.checkout_payment.coupontfield.value == disc_arr[i])
			{
				flag=1;
				break;
			}
		}
			if (flag == 0)
			{
				alert("Enter the valid code");
				document.checkout_payment.coupontfield.value='';
				document.checkout_payment.coupontfield.focus();
			}


	}
}
</script>-->
<!---End of Discount Code-->

<script language="javascript">
function checkform()
{
	var cc_no;
	cc_no = document.checkout_payment.cc_number.value;

	if (cc_no)
	{
      		alert ("You have already entered the Credit Card Number.");
      		document.checkout_payment.payment[0].checked=true;
           	document.checkout_payment.payment[0].focus();
	}
}
</script>
<tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><b><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>
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
	          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
	            <tr>
	              <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
	                <tr>
	                  <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
	                  <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	                </tr>
	              </table></td>
	              <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
	                <tr>
	                  <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	                  <td><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
	                  <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	                </tr>
	              </table></td>
	              <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	              <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
	                <tr>
	                  <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	                  <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
	                </tr>
	              </table></td>
	            </tr>
	            <tr>
	              <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>
	              <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
	              <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
	              <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
	            </tr>
	          </table></td>
      </tr>

      <!--Ends here -->

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_BILLING_ADDRESS; ?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo TEXT_SELECTED_BILLING_DESTINATION; ?><br><br><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '">' . tep_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS) . '</a>'; ?></td>
                <td align="right" width="50%" valign="top"><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main" align="center" valign="top"><b><?php echo TITLE_BILLING_ADDRESS; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" valign="top"><?php echo tep_address_label($customer_id, $billto, true, ' ', '<br>'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>

      <!--- Start of Code For Discount -->
      <!--tr>
	          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
	            <tr>
	              <td class="main"><br><b><?php echo TABLE_HEADING_DISCOUNT_COUPON; ?></b></td>
	            </tr>
	          </table></td>
       		 </tr>
	        <tr>
	          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
	            <tr class="infoBoxContents">
	              <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
	                <tr>
						<td class="main" width="50%" valign="top"><center><?php echo TEXT_SELECTED_DISCOUNT_COUPON; ?></td>
	                </tr>
	                <tr>
						<td><center>
						<?php
						if ((tep_session_is_registered('couponfield')) && (tep_not_null($_SESSION["couponfield"]))) {
							echo tep_draw_input_field('coupontfield', '', ' onBlur="return check_disc();" value="'.$_SESSION["couponfield"].'" size="10" maxlength="30" style="width: ' . (BOX_WIDTH-20) . 'px"') . '<center>'. tep_hide_session_id();
						}
						else
						{
							echo tep_draw_input_field('coupontfield', '', 'onBlur="return check_disc();" size="10" maxlength="30" style="width: ' . (BOX_WIDTH-20) . 'px"') . '<center>'. tep_hide_session_id();
						}
						?>
						</center></td>
	                </tr>
	              </table></td>
	            </tr>
	          </table></td>
      </tr-->
      <!--- End of Code For Discount -->


      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></b></td>
          </tr>
        </table></td>
      </tr>
			<tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  $selection = $payment_modules->selection();

if ($_SERVER['REMOTE_ADDR']!='194.242.117.148'){
  for($i=0, $n=sizeof($selection); $i<$n; $i++) {
    if($selection[$i]['id'] == 'google') {
      array_splice($selection, $i, 1);	
      break;   
    }
  }
}
  if (sizeof($selection) > 1) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></td>
                <td class="main" width="50%" valign="top" align="right"><b><?php echo TITLE_PLEASE_SELECT; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
  } else {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
  }

  $radio_buttons = 0;
  for ($i=0, $n=sizeof($selection); $i<$n; $i++)
  {
  	if (($selection[$i]['module'] == 'Purchase Order' || $selection[$i]['module'] == 'Check/Money Order') && ($country_code != 'US'))
	{
		// Do nothing
	}
  	else
  	{
// purchaseorders_1_4 start
  // If the customer is not allowed to enter a purchase order, then do not
	// display it.
  if($cust_allow_po_entry['customers_allow_purchase_order_entry'] == 'false')
  {
    if($selection[$i]['id'] == 'po')
    {
		 continue;
    }
  }
// purchaseorders_1_4 end
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php

    if(($selection[$i]['id'] == $payment) || ($n == 1))
		{
      echo '                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
    }
		else
		{
      echo '                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
    } // if - else
?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><b>
                    <?php
                    	echo $selection[$i]['module'];
                    ?>
                     </b></td>
                    <td class="main" align="right">
<?php
    if (sizeof($selection) > 1)
    {
    	if($i==0)
	{
		echo tep_draw_radio_field('payment', $selection[$i]['id'], true);
	}
	else
	{
		echo tep_draw_radio_field('payment', $selection[$i]['id'], false, 'onFocus="checkform(); return false"');
	}

    }
    else
    {
      echo tep_draw_hidden_field('payment', $selection[$i]['id']);
    }
?>
                    </td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
    if (isset($selection[$i]['error'])) {
?>
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="4"><?php echo $selection[$i]['error']; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
    } elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
?>
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td colspan="4"><table border="0" cellspacing="0" cellpadding="2">
<?php
      for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
?>
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
<?php
      }
?>
                    </table></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    $radio_buttons++;
    }
  }
?>
            </table></td>
          </tr>
        </table></td>

<?
// BEGIN Shipping Insurance 2.0 with customer choice
if (($order->info['total'] >= MODULE_ORDER_TOTAL_INSURANCE_OVER) && (MODULE_ORDER_TOTAL_INSURANCE_STATUS == 'true') && (MODULE_ORDER_TOTAL_INSURANCE_USE == 'true')) {
?>
			</tr      ><tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
         <td class="main"><b><?php echo TEXT_SHIPPING_INSURANCE_TITLE; ?></b></td>
      </tr>
			<tr>
				<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
					<tr class="infoBoxContents">
						<td class="main" width="100%" valign="top" align="right"><b><? echo TEXT_SHIPPING_INSURANCE_CHOICE; ?>&nbsp;&nbsp;<input type="checkbox" name="choose_insurance" value="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
					</tr>
				</table></td>
			</tr      ><tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?
}
// END Shipping Insurance 2.0 with customer choice
?>



      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($_SESSION['affiliate_id_banner'] <> '')
  {
  	$aff_couponbox_sql = tep_db_query("select affiliate_display_coupon_box from affiliate_affiliate where affiliate_id ='". $_SESSION['affiliate_id_banner'] ."'");
  	$aff_couponbox_fetch = tep_db_fetch_array($aff_couponbox_sql);
  	/*if ($aff_couponbox_fetch['affiliate_display_coupon_box'] == 'Y') Changed By Karthik on Jan27_2006 */
  		echo $order_total_modules->credit_selection();//ICW ADDED FOR CREDIT CLASS SYSTEM
  }
  else
  {
	  //tep_session_unregister('cc_id');
	  echo $order_total_modules->credit_selection();//ICW ADDED FOR CREDIT CLASS SYSTEM
  }
?>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
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
<!---Newely added by matross 05/10/2007 -->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
	<tr><td class="main"><b>Comments to appear on your Packing Slip.  ie. Happy Birthday, Ship together, etc.</b></td></tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_textarea_field('comments_slip', 'soft', '60', '5'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
	
<!---Newely added by matross 05/10/2007 -->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><b><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
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
