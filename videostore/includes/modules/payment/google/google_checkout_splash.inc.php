<?php
/*
 
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
  
  Portions  Copyright (c) 2005 - 2006 Chain Reaction Works, Inc
        
*/
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title><?php echo STORE_NAME; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<style type="text/css">
body {background-color:#FFFFFF;}
body, td, div {font-family: verdana, arial, sans-serif;}
</style>
</head>
<body onload="return document.google_payment_info.submit();">

<?php 
echo "\n".tep_draw_form('google_payment_info', $this->form_google_url, 'post'); 
?>

<table cellpadding="0" width="100%" height="100%" cellspacing="0" style="border:1px solid #003366;">
  <tr><td align="center" style="height:100%; vertical-align:middle;">
    <div>
<?php 
			if (tep_not_null(MODULE_PAYMENT_GOOGLE_PROCESSING_LOGO)) {
    		echo tep_image(DIR_WS_IMAGES . MODULE_PAYMENT_GOOGLE_PROCESSING_LOGO);
    	} else {
    		echo tep_image('includes/modules/payment/google/images/logo.gif'); 
    	}
?>
		</div>
    <div style="color:#003366"><h1><?php echo MODULE_PAYMENT_GOOGLE_TEXT_TITLE_PROCESSING . '<img src="includes/modules/payment/google/images/period_ani.gif" alt="..." />'; ?></h1></div>
    <div style="margin:10px;padding:10px;"><?php echo MODULE_PAYMENT_GOOGLE_TEXT_DESCRIPTION_PROCESSING?></div>
    <div style="margin:10px;padding:10px;"><input type="image" src="includes/modules/payment/google/images/button_gcheckout.gif" alt="Google Checkout" /></div>
  </td></tr>
</table>

<?php
echo $process_button_string; 
?>
</body>
</html>
