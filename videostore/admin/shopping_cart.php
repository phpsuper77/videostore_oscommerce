<?php
/*
  $Id: shopping_cart.php,v 1.73 2003/06/09 23:03:56 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require("includes/application_top.php");
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));
?>
<script>
	function getShoppingCartPdf(){
	window.open('generate_catalog_cart.php');
	}
</script>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script>
	function getRights(){
		window.open('<?php echo tep_href_link("rights.php");?>','Rights','height=450, width=400, toolbar=no,menubr=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
	}
</script>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>
<link rel="stylesheet" type="text/css" href="menustyle.css" media="screen, print" />
<script src="menuscript.js" language="javascript" type="text/javascript"></script>

<script type="text/javascript" language="JavaScript"><!--
function DoSubmission() {
document.cart_quantity.submit();
}
//--></script>
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
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product')); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
		<input type="hidden" name="wish_product_id" value="" />
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?><font size="1">--- (Please login or create an account to save your cart contents for future purchase)<BR><BR>To <u>Change Cart Quantities</u>, change the quantity and press the UPDATE button below<BR>To <u>Delete Items</u> from your cart, Click the remove box<BR>Earn <u>Additional Quantity Discounts</u> Buy 10 or more items 10%, 25 or more items 20%, 50 or more items 30%</font></td>
            <td class="pageHeading" align="right"><?php //echo tep_image(DIR_WS_IMAGES . 'table_background_cart.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($cart->count_contents() > 0) {
?>
      <tr>
        <td>
<?php
    $info_box_contents = array();
    $info_box_contents[0][] = array('align' => 'center',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_REMOVE);

    $info_box_contents[0][] = array('params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_PRODUCTS);

    $info_box_contents[0][] = array('align' => 'center',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_QUANTITY);

	$info_box_contents[0][] = array('align' => 'center',
                                    'params' => 'class="productListing-heading"',
                                    'text' => PRICE);									
									
									
    $info_box_contents[0][] = array('align' => 'right',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_TOTAL);

    $any_out_of_stock = 0;
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
// Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        while (list($option, $value) = each($products[$i]['attributes'])) {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . $products[$i]['id'] . "'
                                       and pa.options_id = '" . $option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . $value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . $languages_id . "'
                                       and poval.language_id = '" . $languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);

          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
        }
      }
    }

    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      if (($i/2) == floor($i/2)) {
        $info_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $info_box_contents[] = array('params' => 'class="productListing-odd"');
      }

      $cur_row = sizeof($info_box_contents) - 1;

      $info_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'class="productListing-data" valign="top"',
                                                                                     'text' => tep_draw_checkbox_field('cart_delete[]', $products[$i]['id'], '0', 'onClick="DoSubmission();"'));

      $products_name = '<table border="0" cellspacing="2" cellpadding="2">' .
                       '  <tr>' .
                       '    <td class="productListing-data" align="center" valign="middle"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><img src=../images/media_type/' .  $products[$i]['media_id'] . '.gif align="absmiddle" border=0>' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['series'] . '&nbsp;' . $products[$i]['name_prefix'] . '&nbsp;' . $products[$i]['name_'] . '&nbsp;' . $products[$i]['name_suffix'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td>' .
                       '    <td class="productListing-data" valign="top"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><b>' . $products[$i]['series'] . '</b><br>' . $products[$i]['name_prefix'] . '&nbsp;<b>' . $products[$i]['name_'] . '</b>&nbsp;' . $products[$i]['name_suffix'] . '<br>' . $products[$i]['set_type'] . '</a>';

if ($products[$i][has_rights]=='1')
$products_name .= '<br/><span><a href="#" style="color:red;" onclick="getRights(); return false;">Includes Limited Public Performance Rights</a></span><br/>';

if($oplist[$products[$i]['id']]){
$products_name .= '<br><font color="green">' . ALREADY_PURCHASED . '</font>';
}


$part = explode("-",substr($products[$i][products_date_available],0,10));
$date_available = mktime(0, 0, 1, $part[1], $part[2], $part[0]);
$curr_date = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));


if ((intval($date_available)>$curr_date) and ($products[$i]['products_out_of_print']=='1')){
define(STOCK_CHECK,'false');
$products_name .= "&nbsp;<span class='markProductOutOfStock'>**Pre-Ordered Item will ship on ".$part[1]."-".$part[2]."-".$part[0]."**</span>";
}
else{
if ($products[$i][products_always_on_hand]!=1){
      if (STOCK_CHECK == 'true') {
        $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
        if (tep_not_null($stock_check)) {
          $any_out_of_stock = 1;

          $products_name .= $stock_check;
        }
      }
  }
}
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
          $products_name .= '<br><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
        }
      }

      $products_name .= '    </td>' .
                        '  </tr>' .
                        '</table>';

      $info_box_contents[$cur_row][] = array('params' => 'class="productListing-data"',
                                             'text' => $products_name);

      $info_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'class="productListing-data" valign="top"',
                                             'text' => tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4"') . tep_draw_hidden_field('products_id[]', $products[$i]['id'])); 

											 
      $info_box_contents[$cur_row][] = array('align' => 'center',
              'params' => 'class="productListing-data" valign="top"',
           'text' => tep_draw_input_field('$cart_price[]', $products[$i]['price'], 'size="4"') . tep_draw_hidden_field('products_id[]', $products[$i]['id'])); 											 
											 
											 
      $info_box_contents[$cur_row][] = array('align' => 'right',
                                             'params' => 'class="productListing-data" valign="top"',
                                             'text' => '<b>' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b>
											 <div style="padding-right:3px;padding-top:3px;"><input onclick="document.getElementById(\'wish_product_id\').value='.$products[$i][id].'" type="image" src="images/copytowishlist.gif" border="0" alt="Add to Wishlist" title=" Add to Wishlist " name="wishlist" value="wishlist" border="0"></div>');
    }

    new productListingBox($info_box_contents);
?>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>

		<!-- Start of Code For Discount -->
		<?php
			if (isset($coupontfield) || tep_session_is_registered('couponfield'))
			{
				if (isset($coupontfield))
					$_SESSION["couponfield"] = $coupontfield;

				if (tep_session_is_registered('couponfield'))
						$coupontfield = $_SESSION["couponfield"];

			  $discount_query_raw = "select * from discounts where discount_code = '$coupontfield'";
			  $discount_query = tep_db_query($discount_query_raw);
			  if (tep_db_num_rows($discount_query) > 0){

					if (isset($coupontfield) && tep_not_null($coupontfield) || tep_session_is_registered('couponfield')) { //Code For Discount
						if (!tep_session_is_registered('couponfield')) tep_session_register('couponfield');
						if (!tep_not_null($_SESSION["couponfield"]))
							$_SESSION["couponfield"]=$coupontfield;
		?>
		<!-- End of Code For Discount -->

      <tr>
        <td align="right" class="main"><b><?php echo SUB_TITLE_SUB_TOTAL; ?> <?php echo $currencies->format($cart->show_total()); ?></b></td>
      </tr>

	  <!-- Start of Discount Code -->
	  <tr>
		<!--  code for discount -->
		<?
		  $discount_query_raw = "select *  from discounts where discount_code = '$_SESSION[couponfield]'";
		  $discount_query = tep_db_query($discount_query_raw);
		  $row_discount_query = tep_db_fetch_array($discount_query);
		  $disc_per = $row_discount_query["discount_amount"];
		  $disc_name = $row_discount_query["discount_name"];


			$cval=$cart->show_total();
			$damt=($cval*$disc_per)/100;
		?>

		<td align="right" class="main"><b><?php echo $disc_name.":"; ?>&nbsp; -<?php echo $currencies->format($damt); ?></b></td>
	  </tr>
	  <!-- Dicount Code End -->
	  <tr>
		<td align="right" class="main"><b><?php echo SUB_TITLE_SUB_TOTAL; ?> <?php echo $currencies->format(($cart->show_total()-$damt)); ?></b></td>
	  </tr>

<? //Code For Discount
				}
			 }
		 	else
		 	{
?>
		 	   <tr>
			  		<td align="right" class="main"><b><?php echo SUB_TITLE_SUB_TOTAL; ?> <?php echo $currencies->format(($cart->show_total())); ?></b></td>
			    </tr>
<? //Code For Discount
			}
		}
	  	else
		{

$final_center = $cart->show_total();

$GLOBALS['cc_id'] = $cc_id;
if ($whole['iswholesale'] != 1){
include_once("includes/modules/order_total/ot_coupon.php");
$coupons = new ot_coupon();
$coupon_value = $coupons->calculate_credit($final_center);
$coupon_query_desc=tep_db_query("select coupon_description from coupons_description where coupon_id='".$cc_id."'");
$coupon_result_desc=tep_db_fetch_array($coupon_query_desc);
$desc = $coupon_result_desc['coupon_description'];
if ($desc!=''){
$amounts = tep_db_fetch_array(tep_db_query("select * from coupons where coupon_id='".$cc_id."' limit 0,1"));
if ($amounts['coupon_type']=='P') $desc .=$desc." (".round($amounts[coupon_amount],2)."%)";
}


$final_center = $final_center-round($coupon_value,2);


include_once("includes/modules/order_total/ot_qty_discount.php");
$discount_center = new ot_qty_discount();
$discount_value = $discount_center->calculate_discount($final_center);
$final_center = $final_center-round($discount_value,2);

include_once("includes/modules/order_total/ot_gv.php");
$cards = new ot_gv();
$cards_value = $cards->calculate_credit($final_center);

$final_center = $final_center-round($cards_value,2);
}

if ($coupon_value>0){
?>
      <tr>
        <td align="right" class="main"><b><?=$desc?>: <?php echo "-".$currencies->format($coupon_value); ?></b></td>
      </tr>
<?}

if ($discount_value>0){
?>
      <tr>
        <td align="right" class="main"><b>Quantity Discount <?=$discount_center->getTitle($discount_value)?>: <?php echo "-".$currencies->format($discount_value); ?></b></td>
      </tr>
<?}

if ($cards_value>0){
?>
      <tr>
        <td align="right" class="main"><b>Gift Card Balance: <?php echo "-".$currencies->format($cards_value); ?></b></td>
      </tr>
<?}?>

	  		  <tr>
	  			<td align="right" class="main"><b><?php echo SUB_TITLE_SUB_TOTAL; ?> <?php echo $currencies->format($final_center); ?></b></td>
	  		  </tr>
<? //Code For Discount
		}
?>
	<!-- End of Discount Code -->




<?php
    if ($any_out_of_stock == 1) {
      if (STOCK_ALLOW_CHECKOUT == 'true') {
?>
      <tr>
        <td class="stockWarning" align="center"><br><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></td>
      </tr>
<?php
      } else {
?>
      <tr>
        <td class="stockWarning" align="center"><br><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></td>
      </tr>
<?php
      }
    }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
<?php
    $back = sizeof($navigation->path)-2;
    if (isset($navigation->path[$back])) {
?>
                <td class="main" width="30%"><?php echo '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>'; ?></td>
<?php
    }

  if ($cart->count_contents() > 0) {
?>
                <td class="main" valign="middle"><a href="#" onclick="getShoppingCartPdf(); return false;"><img border="0" src='images/pdfcatalog.gif' title="Download your own catalog of your selections by pressing this button" alt="Download your own catalog of your selections by pressing this button" /></a></td>
<? } ?>
                <td class="main" width="63%" align="right"><?php echo tep_image_submit('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART); ?></td>
                <td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td align="center" class="main"><?php new infoBox(array(array('text' => TEXT_CART_EMPTY))); ?></td>
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
                <td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>