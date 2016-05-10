<?php
/*
  $Id: search.php,v 1.22 2003/02/10 22:31:05 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- discount Coupon //-->
          <tr>
            <td>
<script language="javascript">
function form_check()
{
	alert (document.discount_coupon.coupontfield.value);
	return false;
}
</script>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_DISCOUNT_COUPON);

  new infoBoxHeading($info_box_contents, false, false);
  $info_box_contents = array();

  if (((isset($coupontfield)) || (tep_session_is_registered('couponfield'))) && ((tep_not_null($coupontfield)) || (tep_not_null($_SESSION[couponfield]))))
  {
      if (tep_not_null($_SESSION["couponfield"])) $coupontfield = $_SESSION["couponfield"];
	  $discount_query_raw = "select * from discounts where discount_code = '$coupontfield'";
      $discount_query = tep_db_query($discount_query_raw);
	  if (tep_db_num_rows($discount_query) <= 0){
	  	$info_box_contents[] = array ('text' => '<center><font color=red>Invalid code!!!</font></center>');
	}
  }



  if (tep_session_is_registered('couponfield')){
	  $info_box_contents[] = array('form' => tep_draw_form('discount_coupon', tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL', false), 'get'),
                               'align' => 'left',
                               //'text' => '<center>Enter Coupon Code <br>'.tep_draw_input_field('coupontfield', '', 'value="'.$_SESSION["couponfield"].'" size="10" maxlength="30" style="width: ' . (BOX_WIDTH-20) . 'px"') . '<center>'. tep_hide_session_id().'<center>');
                               'text' => '<center>Enter Coupon Code <br>'.tep_draw_input_field('coupontfield','', 'value="'.$_SESSION["couponfield"].'" size="15" maxlength="30"') .'<br>'.tep_image_submit('button_redeem.gif', 'Click to Redeem'));
  }
  else {
    $info_box_contents[] = array('form' => tep_draw_form('discount_coupon', tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL', false), 'get'),
                                 'align' => 'left',
                                 //'text' => '<center>Enter Coupon Code <br>'.tep_draw_input_field('coupontfield', '', 'size="10" maxlength="30" style="width: ' . (BOX_WIDTH-20) . 'px"') . '<center>'. tep_hide_session_id().'<center>');
                                 'text' => '<center>Enter Coupon Code <br>'.tep_draw_input_field('coupontfield','', 'size="15" maxlength="30"') .'<br>'.tep_image_submit('button_redeem.gif', 'Click to Redeem'));
  }

  //echo $cart->show_total();
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!--Discount_coupon_eof //-->



