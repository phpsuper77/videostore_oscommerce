<!--$cart_contents_string = '';
if ($cart->count_contents() > 0) {
	$cart_contents_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#FB7B26">';
	$cart_contents_string .='<tr><td colspan=2><strong>Your item has been added to your cart - View your cart on the right panel or by selecting shopping cart on the title bar</strong></td>	</tr>';
	$products = $cart->get_products();
	
	for ($i=0, $n=sizeof($products); $i<$n; $i++) {
		$count=$i+1;
		$cart_contents_string .= '<tr class="boxText"><td>'."$count".'</td><td align="left" valign="top">';
			if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
			$cart_contents_string .= '<span>';
			} 
		
		$cart_contents_string .= $products[$i]['name'] . '</span></td></tr>';
			if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
			tep_session_unregister('new_products_id_in_cart');
			}
	}
	$cart_contents_string .= '</table>';
} 
/*else {
	if(isset($flagname) && $country_code!='US'){
	$cart_contents_string .= '<center>We ship to <b>'.$country_name.'</b>&nbsp;<img src='.$flagname.'></img>&nbsp;</center><br>';
	}*/
//$cart_contents_string .= BOX_SHOPPING_CART_EMPTY;
//}

$info_box_contents = array();
$info_box_contents[] = array('text' => $cart_contents_string);

	/*if ($cart->count_contents() > 0) {
	$info_box_contents[] = array('text' => tep_draw_separator());
	}*/

//$showcheckoutbutton = 0; // set to 1: show checkout button (default); set to 0: never show checkout button
//$showhowmuchmore = 0;    // set to 1: show how much more to spend for free shipping (default); set to 0: don't show
//$cart->cleanup();

new infoBox($info_box_contents);-->

<?php
$cart_contents_string = '';
if ($cart->count_contents() > 0) {
$products = $cart->get_products();
	
	for ($i=0, $n=sizeof($products); $i<$n; $i++) {
		$count=$i+1;
		}
	$cart_contents_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#FFCCCC">';
	//$cart_contents_string .='<tr><td colspan=2 align=center><strong>Your item has been added to your cart -  '."$count".' items are in your cart.</strong></td>	</tr>';
	$cart_contents_string .='<tr><td colspan=2 align=center><strong>Your item has been added to your cart - View your cart on the right panel or by selecting shopping cart on the title bar</strong></td>	</tr>';
	
	$cart_contents_string .= '</table>';
} 
/*else {
	if(isset($flagname) && $country_code!='US'){
	$cart_contents_string .= '<center>We ship to <b>'.$country_name.'</b>&nbsp;<img src='.$flagname.'></img>&nbsp;</center><br>';
	}*/
//$cart_contents_string .= BOX_SHOPPING_CART_EMPTY;
//}

$info_box_contents = array();
$info_box_contents[] = array('text' => $cart_contents_string);

	/*if ($cart->count_contents() > 0) {
	$info_box_contents[] = array('text' => tep_draw_separator());
	}*/

//$showcheckoutbutton = 0; // set to 1: show checkout button (default); set to 0: never show checkout button
//$showhowmuchmore = 0;    // set to 1: show how much more to spend for free shipping (default); set to 0: don't show
//$cart->cleanup();

new infoBox($info_box_contents);
?>