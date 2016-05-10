<?php
/*
  $Id: shopping_cart.php,v 1.18 2003/02/10 22:31:06 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

include_once("includes/modules/order_total/ot_qty_discount.php");
global $one_freeitem;
?>
<!-- shopping_cart //-->
          <tr>
            <td>
<img alt="this is image" src="images/bar-clap.gif">
        <script type="text/javascript"><!--
	function couponpopupWindow(url) {
		  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=600,height=300,screenX=150,screenY=150,top=150,left=150')
	}
	//--></script>

<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_SHOPPING_CART);

  new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_SHOPPING_CART));

  $cart_contents_string = '';
  
  if ($cart->count_contents() > 0) {
    $cart_contents_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
    $products = $cart->get_products();
      ###
      #	Added by Nirmal
      ##
    	if(isset($flagname) && $country_code!='US'){
     	$cart_contents_string .= '<center>We ship to <b>'.$country_name.'</b>&nbsp;<img alt="this is image" src="images/flags/'.$flagname.'"></center><br><br>';
     }
     #####
  for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      $cart_contents_string .= '<tr><td align="right" valign="top" class="infoBoxContents">';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
       $cart_contents_string .= '<span class="newItemInCart-modified">';
      } else {
        $cart_contents_string .= '<span class="infoBoxContents">';
      }

      $cart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;</span></td><td valign="top" class="infoBoxContents"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        $cart_contents_string .= '<span class="newItemInCart-modified">';
       } else {
        $cart_contents_string .= '<span class="infoBoxContents">';
      }

      $cart_contents_string .= $products[$i]['name'] . '</span></a>';


if($oplist[$products[$i]['id']]){
$cart_contents_string .= '<br><font color="green">' . ALREADY_PURCHASED . '</font>';
}


$cart_contents_string .= '<br>-----</td></tr>';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        tep_session_unregister('new_products_id_in_cart');
      }
    }
    $cart_contents_string .= '</table>';
  } else {
    	###
    	#	Added by Nirmal
    	###
    	if(isset($flagname) && $country_code!='US'){
     	$cart_contents_string .= '<center>We ship to <b>'.$country_name.'</b>&nbsp;<img alt="this is image" src="images/flags/'.$flagname.'">&nbsp;</center><br>';
     }
     ###

  $cart_contents_string .= BOX_SHOPPING_CART_EMPTY;
  }

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $cart_contents_string);

  if ($cart->count_contents() > 0) {
if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
  $cartID = $cart->cartID;
  tep_session_register('car_amt'); //for Affiliate Coupon

$_SESSION['car_amt'] = $cart->show_total();
$final_total = $_SESSION['car_amt'];

if ($whole['iswholesale'] != 1) {
include_once("includes/modules/order_total/ot_coupon.php");

$coupons_right = new ot_coupon();
//$coupon_value = $coupons_right->calculate_credit($_SESSION['car_amt']);
$coupons_right->collect_posts();

$coupon_value = $coupons_right->pre_confirmation_check($final_total);

if ($_SESSION['cc_id']!=''){
$coupon_query_desc=tep_db_query("select coupon_description from coupons_description where coupon_id='".$_SESSION['cc_id']."'");
$coupon_result_desc=tep_db_fetch_array($coupon_query_desc); 
$desc = $coupon_result_desc['coupon_description'];
$amounts = tep_db_fetch_array(tep_db_query("select * from coupons where coupon_id='".$cc_id."' limit 0,1"));




if ($amounts['lowest_price'] == '1') {
	$coupon_value = $one_freeitem;
	//$coupon_value = $coupons_right->calculate_credit($_SESSION['car_amt']);
}elseif ($amounts['coupon_type']=='P'){
	$coupon_value = ($_SESSION['car_amt'] * $amounts['coupon_amount'])/100;
} else {
	$coupon_value = $coupons_right->calculate_credit($_SESSION['car_amt']);
}
//echo $coupon_value;

if ($amounts['coupon_type']=='P') $desc .=" (".round($amounts['coupon_amount'],2)."%)";
}

$final_total = $_SESSION['car_amt']-round($coupon_value,2);
$discount = new ot_qty_discount();
$discount_value = $discount->calculate_discount($final_total);
$final_total = $final_total-round($discount_value,2);


include_once("includes/modules/order_total/ot_gv.php");
$cards = new ot_gv();
$cards_value = $cards->calculate_credit($final_total);
$final_total = $final_total-round($cards_value,2);

//$_SESSION['car_amt'] = $final_total;
    $info_box_contents[] = array('text' => tep_draw_separator());
if ($coupon_value > 0)
    $info_box_contents[] = array('align'=>'right','text' => $desc.": -".$currencies->format($coupon_value));
if ($discount_value>0)
    $info_box_contents[] = array('align'=>'right','text' => "Quantity Discount ".$discount->getTitle($discount_value).": -".$currencies->format($discount_value));
if ($cards_value>0)
    $info_box_contents[] = array('align'=>'right','text' => "Gift Card Balance: -".$currencies->format($cards_value));

}

//    $_SESSION['car_amt'] = $fianl_total;
    $info_box_contents[] = array('align' => 'right',
                                'text' => "Sub-Total: ".$currencies->format($final_total));
  }

  // *********************** new shopping cart enhancement ******************************* //

// Bof Shopping Cart Box Enhancement
$showcheckoutbutton = 1; // set to 1: show checkout button (default); set to 0: never show checkout button
$showhowmuchmore = 1;    // set to 1: show how much more to spend for free shipping (default); set to 0: don't show

$cart_show_string = '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td>' . tep_draw_separator('pixel_trans.gif', '100%', '4') . '</td></tr>';
$cart->cleanup();

  if ($_SESSION['countryname']==''){
    		$countryname = tep_get_country_name(STORE_COUNTRY);
		$_SESSION['countryname'] = $countryname;
	}
	else{
		//$countryname = $_SESSION['countryname'];
		$countryname = 'US';
	}
#### Added by Don
if ($whole['iswholesale'] != 1) {
#### End Don
if (MODULE_SHIPPING_FREECOUNT_STATUS == 'True') {


   
    if (($cart->count_contents()) < MODULE_SHIPPING_FREECOUNT_NUMBER) {
       if (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION == 'both') {
              		###
	          		#	Added by Nirmal
	          		###
	          	       	if(isset($flagname) && $country_code=='US'){
           $cart_show_string .= '<tr><td class="extraSmallText" align="center">' . sprintf(TEXT_FREE_SHIPPING_LIMIT, MODULE_SHIPPING_FREECOUNT_NUMBER);
          }
          ###
       }
       else if (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION == 'international') {
              		###
	          		#	Added by Nirmal
	          		###
	          	       	if(isset($flagname) && $country_code=='US'){
           $cart_show_string .= '<tr><td class="extraSmallText" align="center">' . sprintf(TEXT_FREE_SHIPPING_LIMIT_INTERNATIONAL, MODULE_SHIPPING_FREECOUNT_NUMBER,$countryname) .  "</td></tr>";
           }
           #####
       }
       else {
              		###
	          		#	Added by Nirmal
	          		###
	          	       	if(isset($flagname) && $country_code=='US'){
           $cart_show_string .= '<tr><td class="extraSmallText" align="center">' . sprintf(TEXT_FREE_SHIPPING_LIMIT_NATIONAL, MODULE_SHIPPING_FREECOUNT_NUMBER,$countryname) . "</td></tr>";
           }
           ###
       }
       if (($showhowmuchmore) && ($cart->count_contents() > 0)) {
       		       		###
			       		#	Added by Nirmal
			       		###
			       	       	if(isset($flagname) && $country_code=='US'){
                $cart_show_string .= '<br>' . sprintf(TEXT_ADD_TO_GET_FREE_SHIPPING, MODULE_SHIPPING_FREECOUNT_NUMBER - ($cart->count_contents()));
               }
               ###
       }
  //     $cart_show_string .='</td></tr>';
    }
    if ((($cart->count_contents()) >= MODULE_SHIPPING_FREECOUNT_NUMBER)&&($showhowmuchmore)) {
       if (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION == 'both') {
       		       		###
			       		#	Added by Nirmal
			       		###
			       	       	if(isset($flagname) && $country_code=='US'){
         $cart_show_string .= '<tr><td class="extraSmallText" align="center">' . TEXT_FREE_SHIPPING_RECEIVED . '</td></tr>';
         }
         ###
       }
       else if (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION == 'international') {
       	       		###
		       		#	Added by Nirmal
		       		###
		       	       	if(isset($flagname) && $country_code=='US'){
         $cart_show_string .= '<tr><td class="extraSmallText" align="center">' . sprintf(TEXT_FREE_SHIPPING_RECEIVED_INTERNATIONAL, $countryname) . '</td></tr>';       }
         }
         ###
       else {
              		###
	          		#	Added by Nirmal
	          		###
	          	       	if(isset($flagname) && $country_code=='US'){
         $cart_show_string .= '<tr><td class="extraSmallText" align="center">' . sprintf(TEXT_FREE_SHIPPING_RECEIVED_NATIONAL, $countryname) . '</td></tr>';
         }
         ###
       }
    }
}

#### Added by Don
}
#### End Don
$currUrl = $_SERVER['REQUEST_URI'];
$pos = strpos($currUrl, "shopping_cart.php");
if ($pos === false )
	$linkz = 'shopping_cart.php';
	else
	$linkz = 'checkout_shipping.php';

 if (($showcheckoutbutton==1)&&($cart->count_contents() > 0)) {
  $cart_show_string .= '<tr><td>' . tep_draw_separator('pixel_trans.gif', '100%', '7') . '</td></tr><tr><td align="center"><a href="./'.$linkz.'">' . tep_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a></td></tr>';
}

$cart_show_string .= '</table>';
$info_box_contents[] = array('text' => $cart_show_string);
// Eof Shopping Cart Box Enhancement

// *********************** end new shopping cart enhancement *************************** //

if ($whole['iswholesale'] != 1){
// ICW ADDED FOR CREDIT CLASS GV
  if (tep_session_is_registered('customer_id')) {
    $gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'");
    $gv_result = tep_db_fetch_array($gv_query);
    if ($gv_result['amount'] > 0 ) {
      $info_box_contents[] = array('align' => 'left','text' => tep_draw_separator());
      $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . VOUCHER_BALANCE . '</td><td class="smalltext" align="right" valign="bottom">' . $currencies->format($gv_result['amount']) . '</td></tr></table>');
      $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext"><a href="'. tep_href_link(FILENAME_GV_SEND) . '">' . BOX_SEND_TO_FRIEND . '</a></td></tr></table>');
    }
  }
  if (tep_session_is_registered('gv_id')) {
    $gv_query = tep_db_query("select coupon_amount from " . TABLE_COUPONS . " where coupon_id = '" . $gv_id . "'");
    $coupon = tep_db_fetch_array($gv_query);
    $info_box_contents[] = array('align' => 'left','text' => tep_draw_separator());
    $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . VOUCHER_REDEEMED . '</td><td class="smalltext" align="right" valign="bottom">' . $currencies->format($coupon['coupon_amount']) . '</td></tr></table>');

  }
 /*if (tep_session_is_registered('cc_id') && $cc_id) {
    $info_box_contents[] = array('align' => 'left','text' => tep_draw_separator());
    $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . CART_COUPON . '</td><td class="smalltext" align="right" valign="bottom">' . '<a href="javascript:couponpopupWindow(\'' . tep_href_link(FILENAME_POPUP_COUPON_HELP, 'cID=' . $cc_id) . '\')">' . CART_COUPON_INFO . '</a>' . '</td></tr></table>');
    //$info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . CART_COUPON . '</td><td class="smalltext" align="right" valign="bottom">' . '<a onClick="Window.open(\'popup_coupon_help.php?cID='. $cc_id . '\')" style="cursor:hand">' . CART_COUPON_INFO . '</a>' . '</td></tr></table>');

  }*/
//if ($coupon_value > 0) {
if (tep_session_is_registered('cc_id') && $cc_id) {
 $coupon_query = tep_db_query("select * from " . TABLE_COUPONS . " where coupon_id = '" . $cc_id . "'");
 $coupon = tep_db_fetch_array($coupon_query);
 $coupon_desc_query = tep_db_query("select * from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $cc_id . "' and language_id = '" . $languages_id . "'");
 $coupon_desc = tep_db_fetch_array($coupon_desc_query);
 $text_coupon_help = sprintf("%s",$coupon_desc['coupon_name']);
   $info_box_contents[] = array('align' => 'left','text' => tep_draw_separator());
   $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="infoBoxContents">' . CART_COUPON . '&nbsp<b>' . $text_coupon_help . '</b><br>&nbsp&nbsp' . '<a href="javascript:couponpopupWindow(\'' . tep_href_link(FILENAME_POPUP_COUPON_HELP, 'cID=' . $cc_id) . '\')">' . '<FONT COLOR="red">' . CART_COUPON_INFO . '</a>' . '</font></td></tr></table>');
   }
//}
// ADDED FOR CREDIT CLASS GV END ADDITTION
}
  new infoBox($info_box_contents);
//echo $amounts['coupon_type'];

?>
            </td>
          </tr>
<!-- shopping_cart_eof //-->