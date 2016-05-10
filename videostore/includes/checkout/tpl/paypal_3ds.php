<iframe src="<?php echo DIR_WS_HTTPS_CATALOG . DIR_WS_INCLUDES; ?>paypal_wpp/<?php echo FILENAME_PAYPAL_WPP_3DS; ?>?blank=1" frameborder="0" id="paypal-wpp-3ds" name="paypal-wpp-3ds" style="width: 400px; height: 400px; border: 0;"></iframe>
<form name="paypal_wpp_form_3ds" method="post" action="<?php echo $_SESSION['cardinal_centinel']['acs_url']; ?>" target="paypal-wpp-3ds">
  <input type="hidden" name="PaReq" value="<?php echo $_SESSION['cardinal_centinel']['payload']; ?>">
  <input type="hidden" name="TermUrl" value="<?php echo tep_href_link(FILENAME_CHECKOUT, 'checkout_payment=paypal_wpp&action=cardinal_centinel_auth', 'SSL'); ?>">
  <input type="hidden" name="MD" value="<?php echo tep_session_id(); ?>">
</form>