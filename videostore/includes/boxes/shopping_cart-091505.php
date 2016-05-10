<?php
/*
  $Id: shopping_cart.php,v 1.18 2003/02/10 22:31:06 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- shopping_cart //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_SHOPPING_CART);

  new infoBoxHeading($info_box_contents, false, true, tep_href_link(FILENAME_SHOPPING_CART));

  $cart_contents_string = '';
  ###
  #	changes made by Nirmal
  ###
    // This code demonstrates how to lookup the country by IP Address
    //include_once("./includes/geoip/geoip.inc");
    //$gi = geoip_open("./includes/geoip/GeoIP.dat",GEOIP_STANDARD);

    //$ip = "71.99.177.139";
    //$ip = "161.184.200.75";
    //$country_code = geoip_country_code_by_addr($gi, $ip);
    //geoip_close($gi);

	$ip = $_SERVER['REMOTE_ADDR'];
    $country_query  = "SELECT countrySHORT, countryLONG FROM ipcountry WHERE ipFROM<=inet_aton('$ip') AND ipTO>=inet_aton('$ip') ";
    $country_exec = tep_db_query($country_query);
    $ccode_array = tep_db_fetch_array($country_exec);
    $country_code=$ccode_array['countrySHORT'];
    $country_name=$ccode_array['countryLONG'];
    $country_code = strtoupper($country_code);

    $country_flag_query ="select FLAG_NAME from ip2flag where COUNTRY_CODE2='$country_code'";
    $country_flag_exec = tep_db_query($country_flag_query);
    $ccode_flag_array = tep_db_fetch_array($country_flag_exec);
    $flagname = $ccode_flag_array['FLAG_NAME'];
    $caps_letter = strtoupper(substr($flagname,0,2));
    $flagname = "$caps_letter.gif";

    if(isset($flagname)){
    $flagname = "images/flags/".$flagname;
    }
  #########


  if ($cart->count_contents() > 0) {
    $cart_contents_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
    $products = $cart->get_products();
      ###
      #	Added by Nirmal
      ##
    	if(isset($flagname) && $country_code!='US'){
     	$cart_contents_string .= '<center>We ship to <b>'.$country_name.'</b>&nbsp;<img src='.$flagname.'></img></center><br><br>';
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

      $cart_contents_string .= $products[$i]['name'] . '</span></a></td></tr>';

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
     	$cart_contents_string .= '<center>We ship to <b>'.$country_name.'</b>&nbsp;<img src='.$flagname.'></img>&nbsp;</center><br>';
     }
     ###

  $cart_contents_string .= BOX_SHOPPING_CART_EMPTY;
  }

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $cart_contents_string);

  if ($cart->count_contents() > 0) {
    $info_box_contents[] = array('text' => tep_draw_separator());
    $info_box_contents[] = array('align' => 'right',
                                'text' => $currencies->format($cart->show_total()));
  }

  // *********************** new shopping cart enhancement ******************************* //

// Bof Shopping Cart Box Enhancement
$showcheckoutbutton = 1; // set to 1: show checkout button (default); set to 0: never show checkout button
$showhowmuchmore = 1;    // set to 1: show how much more to spend for free shipping (default); set to 0: don't show

$cart_show_string = '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td>' . tep_draw_separator('pixel_trans.gif', '100%', '4') . '</td></tr>';
$cart->cleanup();
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
           $cart_show_string .= '<tr><td class="extraSmallText" align="center">' . sprintf(TEXT_FREE_SHIPPING_LIMIT_INTERNATIONAL, MODULE_SHIPPING_FREECOUNT_NUMBER,tep_get_country_name(STORE_COUNTRY));
           }
           #####
       }
       else {
              		###
	          		#	Added by Nirmal
	          		###
	          	       	if(isset($flagname) && $country_code=='US'){
           $cart_show_string .= '<tr><td class="extraSmallText" align="center">' . sprintf(TEXT_FREE_SHIPPING_LIMIT_NATIONAL, MODULE_SHIPPING_FREECOUNT_NUMBER,tep_get_country_name(STORE_COUNTRY));
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
       $cart_show_string .='</td></tr>';
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
         $cart_show_string .= '<tr><td class="extraSmallText" align="center">' . sprintf(TEXT_FREE_SHIPPING_RECEIVED_INTERNATIONAL, tep_get_country_name(STORE_COUNTRY)) . '</td></tr>';       }
         }
         ###
       else {
              		###
	          		#	Added by Nirmal
	          		###
	          	       	if(isset($flagname) && $country_code=='US'){
         $cart_show_string .= '<tr><td class="extraSmallText" align="center">' . sprintf(TEXT_FREE_SHIPPING_RECEIVED_NATIONAL, tep_get_country_name(STORE_COUNTRY)) . '</td></tr>';
         }
         ###
       }
    }
}

if (($showcheckoutbutton==1)&&($cart->count_contents() > 0)) {
  $cart_show_string .= '<tr><td>' . tep_draw_separator('pixel_trans.gif', '100%', '7') . '</td></tr><tr><td align="center"><a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a></td></tr>';
  $cart_show_string .= '<tr><td>' . tep_draw_separator('pixel_trans.gif', '100%', '7') . '</td></tr><tr><td class="extraSmallText" align="center"><a href="./shopping_cart.php">' . tep_image_button('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART) . '</a><br>Select Update Cart to change Quantity or Remove items</td></tr>';
}
$cart_show_string .= '</table>';
$info_box_contents[] = array('text' => $cart_show_string);
// Eof Shopping Cart Box Enhancement

// *********************** end new shopping cart enhancement *************************** //


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
  if (tep_session_is_registered('cc_id') && $cc_id) {
    $info_box_contents[] = array('align' => 'left','text' => tep_draw_separator());
    //$info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . CART_COUPON . '</td><td class="smalltext" align="right" valign="bottom">' . '<a href="javascript:couponpopupWindow(\'' . tep_href_link(FILENAME_POPUP_COUPON_HELP, 'cID=' . $cc_id) . '\')">' . CART_COUPON_INFO . '</a>' . '</td></tr></table>');
    $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . CART_COUPON . '</td><td class="smalltext" align="right" valign="bottom">' . '<a onClick="Window.open(\'popup_coupon_help.php?cID='. $cc_id . '\')" style="cursor:hand">' . CART_COUPON_INFO . '</a>' . '</td></tr></table>');

  }

// ADDED FOR CREDIT CLASS GV END ADDITTION

  new infoBox($info_box_contents);

?>
            </td>
          </tr>
<!-- shopping_cart_eof //-->
