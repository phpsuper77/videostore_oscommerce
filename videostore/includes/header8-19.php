<?php
$db_queries_count = 0;
$_db_queries_list = "";
/*
  $Id: header.php,v 1.42 2003/06/10 18:20:38 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// check if the 'install' directory exists, and warn of its existence
  if (WARN_INSTALL_EXISTENCE == 'true') {
    if (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install')) {
      $messageStack->add('header', WARNING_INSTALL_DIRECTORY_EXISTS, 'warning');
    }
  }

// check if the configure.php file is writeable
  if (WARN_CONFIG_WRITEABLE == 'true') {
    if ( (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) && (is_writeable(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) ) {
      $messageStack->add('header', WARNING_CONFIG_FILE_WRITEABLE, 'warning');
    }
  }

// check if the session folder is writeable
  if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
    if (STORE_SESSIONS == '') {
      if (!is_dir(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NON_EXISTENT, 'warning');
      } elseif (!is_writeable(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NOT_WRITEABLE, 'warning');
      }
    }
  }

// check session.auto_start is disabled
  if ( (function_exists('ini_get')) && (WARN_SESSION_AUTO_START == 'true') ) {
    if (ini_get('session.auto_start') == '1') {
      $messageStack->add('header', WARNING_SESSION_AUTO_START, 'warning');
    }
  }

  if ( (WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') ) {
    if (!is_dir(DIR_FS_DOWNLOAD)) {
      $messageStack->add('header', WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT, 'warning');
    }
  }

  if ($messageStack->size('header') > 0) {
    echo $messageStack->output('header');
  }
?>
<table  background="images/boxborderfs.gif" valign="top" border="1" width="100%"  cellspacing="0" cellpadding="0">

  <tr>

<td><img  src="images/boxborderfs.gif"></td>
</tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td><IMG SRC="images/movie_camera2.gif" ALT="Travel DVD's are more visually educational"></td><td>
            	<!-- Included newly for Affilate -->
			<?php
				$ref_id = $HTTP_GET_VARS['ref'];
				if (isset($ref_id))
				{
					//$_SESSION['cc_id'] = $ref_id;
					tep_session_register('affiliate_id_banner');
					$_SESSION['affiliate_id_banner'] = $ref_id;
					#########
					#####Affiliate Coupon
					#########
					$affiliate_coupon_sql = tep_db_query("select affiliate_coupon from affiliate_affiliate where affiliate_id = $ref_id");
					$affiliate_coupon_rs = tep_db_fetch_array($affiliate_coupon_sql);
					$affiliate_coupon_code = $affiliate_coupon_rs['affiliate_coupon'];

					if ($affiliate_coupon_code <> '')
					{
						$coupon_active_sql = tep_db_query("select count(*) as cnt from ".TABLE_COUPONS." where coupon_id = '".$affiliate_coupon_code."' and coupon_active = 'Y'");
						$coupon_active_rs = tep_db_fetch_array($coupon_active_sql);

						if ($coupon_active_rs['cnt'] > 0)
						{
							tep_session_register('cc_id'); //for Affiliate Coupon
							$_SESSION['cc_id'] = $affiliate_coupon_code;
						}
					}
					else
					{
						unset($_SESSION['affiliate_image']);
						unset($_SESSION['cc_id']);
					}
				}
		//if ($_SESSION['affiliate_image']==''){
				if (tep_session_is_registered('affiliate_id_banner') && $_SESSION['affiliate_id_banner'] <> '' || isset($ref_id))
				{
					$aff_img = tep_db_query("select affiliate_homepage,affiliate_logo, affiliate_display_logo from " . TABLE_AFFILIATE . " where affiliate_id = '" . $_SESSION['affiliate_id_banner']. "'");
					$aff_img_rs = tep_db_fetch_array($aff_img);
					if (($aff_img_rs['affiliate_display_logo'] == "Y") && $aff_img_rs['affiliate_logo'] <> '')
						echo "<a href=".$aff_img_rs['affiliate_homepage']."><img src=images/".$aff_img_rs['affiliate_logo']." border=\"0\" alt=\"Partnered with TravelVideoStore.com More Travel Videos to More Places - Click here to return to our partners website\" title=\"Partnered with TravelVideoStore.com More Travel Videos to More Places - Click here to return to our partners website\"></a></td>


";


					else
					echo "<a href=\"http://www.travelvideostore.com\"><img src=\"/images/tvs_banner.gif\" border=\"0\" alt=\"TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store\" title=\" TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store\"></a></td>




";


				}
				else

					echo "<a href=\"http://www.travelvideostore.com\"><img src=\"/images/tvs_banner.gif\" border=\"0\" alt=\"TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store\" title=\" TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store\"></a></td>




";
//}

//echo $_SESSION['affiliate_image'];
			?>

<td width=100 class="smallText"><center>Travel Videos are a <font color="blue">great vacation planning tool</font></center></td>
<td width=5></td>
<td width=110 class="smallText"><center><font color="red">
Fire up your sales force</font> - with a video of your incentive destination</center></td><td width=5></td>
<td width=100 class="smallText"><center>Give the<font color="green"> gift of travel</font> with Travel Videos</center></td><td width=2></td>
<td width=100 class="smallText"><center>Automatic<font color="red"> Quantity Discounts</font> on our Travel Videos</center></td><td width=2></td>



		
<td><a href="returns.php"><img src="images/100satesfaction.gif" border="0" alt="Your Satesfaction is Guaranteed at TravelVideoStore.com" title="TravelVideoStore.com offers the industry's only satisfaction guarantee on travel videos - If you buy one of the travel videos we sell and don't like it, then return it!" TARGET="_blank"></a></td><td align="center" style='padding-top:2px;'>

<!-- BEGIN: Constant Contact Square Email List Button -->
<div align="center">
<table width="100" border="0" cellpadding="0" cellspacing="0">
<tr>
<td><img src="/images/visitor/bevel_tl_black.gif" width="6" height="6" alt=""></td>
<td background="/images/visitor/bevel_bg_top_black.gif"> </td>
<td><img src="/images/visitor/bevel_tr_black.gif" width="6" height="6" alt=""></td>
</tr>
<tr>
<td background="/images/visitor/bevel_bg_left_black.gif" width="6"></td>
<td bgcolor="#000000" align="center" style="padding:10px;"><a href="http://visitor.constantcontact.com/d.jsp?m=1101097531388&p=oi" target="_blank" style="text-decoration:none; font-weight: bold;  font-family:Arial,Helvetica,sans-serif; font-size:10px; color:#FFFFFF;" width="188">Join our Catalog Mailing List </a></td>
<td background="/images/visitor/bevel_bg_right_black.gif" width="6"></td>
</tr>
<tr>
<td background="/images/visitor/bevel_bg_left_blue.gif"></td>
<td bgcolor="#003399"align="right"><div  style="margin-top:2"><a href="http://visitor.constantcontact.com/d.jsp?m=1101097531388&p=oi" target="_blank" style="text-decoration:none; font-weight:bold;  font-family:Arial,Helvetica,sans-serif; font-size:10px; color:#ffffff;">&raquo; Click Here</a></div></td>
<td background="/images/visitor/bevel_bg_right_blue.gif"></td>
</tr>
<tr>
<td><img src="/images/visitor/bevel_bl_blue.gif" width="6" height="6" alt=""></td>
<td background="/images/visitor/bevel_bg_bottom_blue.gif"> </td>
<td><img src="/images/visitor/bevel_br_blue.gif" width="6" height="6" alt=""></td>
</tr>
</table>
</div>
<!-- END: Constant Contact Square Email List Button -->
<!-- BEGIN: SafeSubscribe -->
<div align="center" style="padding-top:5px;">
<a href="http://www.constantcontact.com/safesubscribe.jsp" target="_blank"><img src="images/safe_subscribe_logo.gif" border="0" width="168" height="14" alt=""/></a>
</div>
<!-- END: SafeSubscribe -->


<td>

<?
if ($_SESSION['country_code']=='' or $_SESSION['country_name']==''){
    $ip = $_SERVER['REMOTE_ADDR'];
    $country_query  = "SELECT countrySHORT, countryLONG FROM ipcountry WHERE ipFROM<=inet_aton('$ip') AND ipTO>=inet_aton('$ip') ";
    $country_exec = tep_db_query($country_query);
    $ccode_array = tep_db_fetch_array($country_exec);
    $country_code=$ccode_array['countrySHORT'];
    $country_name=$ccode_array['countryLONG'];
    $country_code = strtoupper($country_code);
$_SESSION['country_code'] = $country_code;
$_SESSION['country_name'] = $country_name;
}
	else {
$country_code = $_SESSION['country_code'];
$country_name = $_SESSION['country_name'];
}

if ($_SESSION['flagname']==''){
    $country_flag_query ="select FLAG_NAME from ip2flag where COUNTRY_CODE2='$country_code'";
    $country_flag_exec = tep_db_query($country_flag_query);
    $ccode_flag_array = tep_db_fetch_array($country_flag_exec);
    $flagname = $ccode_flag_array['FLAG_NAME'];
    $caps_letter = strtoupper(substr($flagname,0,2));
    $flagname = "$caps_letter.gif";
    $_SESSION['flagname'] = $flagname;
}
	else{
    $flagname = $_SESSION['flagname'];
}
if ($whole[iswholesale] != 1){
?>
<a href="/info_pages.php?pages_id=32">
<?if ($country_code=='US') {?>
<img src="images/freeshipping.gif" border="0" alt="Free Shipping on Travel Videos via media mail to US Addresses" title="Free Shipping on Travel Videos via media mail to US Addresses" TARGET="_blank">
<?} else {?>
<img src="images/worldwideshipping.gif" border="0" alt="WorldWide Shipping on Travel Videos" title="WorldWide Shipping on Travel Videos" TARGET="_blank">
<?
	}
}	else {
?>
<img src="images/dedalerpricing.jpg" border="0" alt="Dealer Pricing" title="Dealer Pricing">
<? } ?>
</a>
</td>



	    	<!-- Incuded newly for Affilate -->


    <td align="right" valign="bottom"><?php //echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_account.gif', HEADER_TITLE_MY_ACCOUNT) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">' . tep_image(DIR_WS_IMAGES . 'header_cart.gif', HEADER_TITLE_CART_CONTENTS) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_checkout.gif', HEADER_TITLE_CHECKOUT) . '</a>'; ?>&nbsp;&nbsp;</td>
  </tr>
</table>

<table  background="images/boxborderfs.gif" valign="top" border="0" width="100%" height="6" cellspacing="0" cellpadding="0">

  <tr>

<td><img  src="images/boxborderfs.gif"></td>
</tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="1">


  <tr class="headerNavigation">
    <td class="headerNavigation">&nbsp;&nbsp;<?php echo $breadcrumb->trail(' &raquo; '); ?></td>
    <td align="right" class="headerNavigation"><?php if (tep_session_is_registered('customer_id')) { ?><a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>" class="headerNavigation"><?php echo HEADER_TITLE_LOGOFF; ?></a> &nbsp;|&nbsp; <?php } ?><a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="headerNavigation"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></a> &nbsp;|&nbsp; <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="headerNavigation"><?php echo HEADER_TITLE_CART_CONTENTS; ?></a> &nbsp;|&nbsp; <a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>" class="headerNavigation"><?php echo HEADER_TITLE_CHECKOUT; ?></a> &nbsp;&nbsp;</td>
  </tr>
</table>

<?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerError">
    <td class="headerError"><?php echo htmlspecialchars(urldecode($HTTP_GET_VARS['error_message'])); ?></td>
  </tr>
</table>
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo htmlspecialchars('Your item has been added to your cart - View your cart on the right panel or by selecting shopping cart on the title bar');
$HTTP_GET_VARS['info_message'] = "";
  ?></td>
  </tr>
</table>
<?php
  }
?>