<?php
/*
 * Dynamo Checkout by Dynamo Effects
 * Copyright 2008 - Dynamo Effects.  All Rights Reserved
 * http://www.dynamoeffects.com
 */
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONDITIONS);
?>
<h2><?php echo HEADING_TITLE; ?></h2>
<?php echo TEXT_INFORMATION; ?>
<div style="text-align: right">
  <a href="#" class="checkout-lightbox-close"><img src="<?php echo tep_href_link(DIR_WS_LANGUAGES . $language . '/images/buttons/button_continue.gif', '', 'SSL'); ?>" border="0" alt="<?php echo IMAGE_BUTTON_CONTINUE; ?>" /></a>
</div>