<?php
/*
 * Dynamo Checkout by Dynamo Effects
 * Copyright 2008 - Dynamo Effects.  All Rights Reserved
 * http://www.dynamoeffects.com
 */
?>
<form name="login" action="<?php echo tep_href_link(FILENAME_LOGIN, 'action=process&exists=1', 'SSL'); ?>" method="POST">
<i><?php echo CHECKOUT_FASTER_SHOPPING; ?></i><br /><h2><?php echo CHECKOUT_LOGIN; ?></h2>
<label for="email_address"><?php echo CHECKOUT_EMAIL_ADDRESS; ?></label>
<?php echo tep_draw_input_field('email_address', '', 'class="checkout-input-text"'); ?>
<br /><br />
<label for="password"><?php echo CHECKOUT_PASSWORD; ?></label>
<?php echo tep_draw_password_field('password', '', 'class="checkout-input-text"'); ?>
<br /><br />
<div style="text-align:center">
<?php 
  echo tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN) . 
  '&nbsp;&nbsp;<a href="#" class="checkout-lightbox-close">' . 
  tep_image_button('button_back.gif', IMAGE_BUTTON_LOGIN) . '</a>'; 
?>
</div>
</form>
