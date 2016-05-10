<?php
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
<td align="center" WIDTH="580" HEIGHT="90" border="0">
<font face="comic sans MS, new york, times" size=2>Where do you want to Explore?</font> 
<br>
<a href="http://www.travelvideostore.com/index.php?cPath=97" onmouseover="setOverImg_menuhdr('1','_menuhdr');overSub_menuhdr=true;showSubMenu_menuhdr('submenu1_menuhdr','button1_menuhdr');" onmouseout="setOutImg_menuhdr('1','_menuhdr');overSub_menuhdr=false;setTimeout('hideSubMenu_menuhdr(\'submenu1_menuhdr\')',delay_menuhdr);" target=""><img src="buttons/button1up_menuhdr.gif" border="0" id="button1_menuhdr" vspace="0" hspace="0"></a><a href="http://www.travelvideostore.com/index.php?cPath=515" onmouseover="setOverImg_menuhdr('2','_menuhdr');overSub_menuhdr=true;showSubMenu_menuhdr('submenu2_menuhdr','button2_menuhdr');" onmouseout="setOutImg_menuhdr('2','_menuhdr');overSub_menuhdr=false;setTimeout('hideSubMenu_menuhdr(\'submenu2_menuhdr\')',delay_menuhdr);" target=""><img src="buttons/button2up_menuhdr.gif" border="0" id="button2_menuhdr" vspace="0" hspace="0"></a><a href="http://www.travelvideostore.com/index.php?cPath=149" onmouseover="setOverImg_menuhdr('3','_menuhdr');overSub_menuhdr=true;showSubMenu_menuhdr('submenu3_menuhdr','button3_menuhdr');" onmouseout="setOutImg_menuhdr('3','_menuhdr');overSub_menuhdr=false;setTimeout('hideSubMenu_menuhdr(\'submenu3_menuhdr\')',delay_menuhdr);" target=""><img src="buttons/button3up_menuhdr.gif" border="0" id="button3_menuhdr" vspace="0" hspace="0"></a><a href="http://www.travelvideostore.com/index.php?cPath=259" onmouseover="setOverImg_menuhdr('4','_menuhdr');overSub_menuhdr=true;showSubMenu_menuhdr('submenu4_menuhdr','button4_menuhdr');" onmouseout="setOutImg_menuhdr('4','_menuhdr');overSub_menuhdr=false;setTimeout('hideSubMenu_menuhdr(\'submenu4_menuhdr\')',delay_menuhdr);" target=""><img src="buttons/button4up_menuhdr.gif" border="0" id="button4_menuhdr" vspace="0" hspace="0"></a><a href="http://www.travelvideostore.com/index.php?cPath=58" onmouseover="setOverImg_menuhdr('5','_menuhdr');overSub_menuhdr=true;showSubMenu_menuhdr('submenu5_menuhdr','button5_menuhdr');" onmouseout="setOutImg_menuhdr('5','_menuhdr');overSub_menuhdr=false;setTimeout('hideSubMenu_menuhdr(\'submenu5_menuhdr\')',delay_menuhdr);" target=""><img src="buttons/button5up_menuhdr.gif" border="0" id="button5_menuhdr" vspace="0" hspace="0"></a><br><IMG SRC="images/movieclapboard.gif" ALT="Plan, Experience &amp; Explore with Destination Videos on DVD and VHS">
<a href="http://www.travelvideostore.com/index.php?cPath=148" onmouseover="setOverImg_menuhdr('6','_menuhdr');overSub_menuhdr=true;showSubMenu_menuhdr('submenu6_menuhdr','button6_menuhdr');" onmouseout="setOutImg_menuhdr('6','_menuhdr');overSub_menuhdr=false;setTimeout('hideSubMenu_menuhdr(\'submenu6_menuhdr\')',delay_menuhdr);" target=""><img src="buttons/button6up_menuhdr.gif" border="0" id="button6_menuhdr" vspace="0" hspace="0"></a><a href="http://www.travelvideostore.com/index.php?cPath=33" onmouseover="setOverImg_menuhdr('7','_menuhdr');overSub_menuhdr=true;showSubMenu_menuhdr('submenu7_menuhdr','button7_menuhdr');" onmouseout="setOutImg_menuhdr('7','_menuhdr');overSub_menuhdr=false;setTimeout('hideSubMenu_menuhdr(\'submenu7_menuhdr\')',delay_menuhdr);" target=""><img src="buttons/button7up_menuhdr.gif" border="0" id="button7_menuhdr" vspace="0" hspace="0"></a><a href="http://www.travelvideostore.com/index.php?cPath=34" onmouseover="setOverImg_menuhdr('8','_menuhdr');overSub_menuhdr=true;showSubMenu_menuhdr('submenu8_menuhdr','button8_menuhdr');" onmouseout="setOutImg_menuhdr('8','_menuhdr');overSub_menuhdr=false;setTimeout('hideSubMenu_menuhdr(\'submenu8_menuhdr\')',delay_menuhdr);" target=""><img src="buttons/button8up_menuhdr.gif" border="0" id="button8_menuhdr" vspace="0" hspace="0"></a><a href="http://www.travelvideostore.com/index.php?cPath=150" onmouseover="setOverImg_menuhdr('9','_menuhdr');overSub_menuhdr=true;showSubMenu_menuhdr('submenu9_menuhdr','button9_menuhdr');" onmouseout="setOutImg_menuhdr('9','_menuhdr');overSub_menuhdr=false;setTimeout('hideSubMenu_menuhdr(\'submenu9_menuhdr\')',delay_menuhdr);" target=""><img src="buttons/button9up_menuhdr.gif" border="0" id="button9_menuhdr" vspace="0" hspace="0"></a><IMG SRC="images/movieclapboard.gif" ALT="Travel Videos are great for planning your vacation or reliving a memory"></td>
<td align="center"><IMG SRC="images/movie_camera2.gif" ALT="Travel DVD's are more visually educational">
            	<!-- Included newly for Affilate -->
			<?php
				$ref_id = $HTTP_GET_VARS['ref'];
				if (isset($ref_id))
				{
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
						unset($_SESSION['cc_id']);
					}
				}

				if (tep_session_is_registered('affiliate_id_banner') && $_SESSION['affiliate_id_banner'] <> '' || isset($ref_id))
				{
					$aff_img = tep_db_query("select affiliate_homepage,affiliate_logo, affiliate_display_logo from " . TABLE_AFFILIATE . " where affiliate_id = '" . $_SESSION['affiliate_id_banner']. "'");
					$aff_img_rs = tep_db_fetch_array($aff_img);
					if (($aff_img_rs['affiliate_display_logo'] == "Y") && $aff_img_rs['affiliate_logo'] <> '')
						echo "<a href=".$aff_img_rs['affiliate_homepage']."><img src=images/".$aff_img_rs['affiliate_logo']." border=\"0\" alt=\"Partnered with TravelVideoStore.com More Travel Videos to More Places - Click here to return to our partners website\" title=\"Partnered with TravelVideoStore.com More Travel Videos to More Places - Click here to return to our partners website\"></a><br><font face=\"comic sans MS, new york, times\" size=3><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Travel Video Store</b></font></td>


";


					else
						echo "<a href=\"http://www.travelvideostore.com\"><img src=\"/images/tvs_banner.gif\" border=\"0\" alt=\"TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store\" title=\" TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store\"></a><br><font face=\"comic sans MS, new york, times\" size=2>More TRAVEL VIDEOS to MORE PLACES!</font></td>




";


				}
				else

					echo "<a href=\"http://www.travelvideostore.com\"><img src=\"/images/tvs_banner.gif\" border=\"0\" alt=\"TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store\" title=\" TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store\"></a><br><font face=\"comic sans MS, new york, times\" size=2>More TRAVEL VIDEOS to MORE PLACES!</font></td>




";

			?>

	    	<!-- Incuded newly for Affilate -->


    <td align="right" valign="bottom"><?php //echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_account.gif', HEADER_TITLE_MY_ACCOUNT) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">' . tep_image(DIR_WS_IMAGES . 'header_cart.gif', HEADER_TITLE_CART_CONTENTS) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_checkout.gif', HEADER_TITLE_CHECKOUT) . '</a>'; ?>&nbsp;&nbsp;</td>
  </tr>
</table>



<table border="0" width="100%" cellspacing="0" cellpadding="1">


  <tr class="headerNavigation">
    <td class="headerNavigation">&nbsp;&nbsp;<?php echo $breadcrumb->trail(' &raquo; '); ?></td>
    <td align="right" class="headerNavigation"><?php if (tep_session_is_registered('customer_id')) { ?><a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>" class="headerNavigation"><?php echo HEADER_TITLE_LOGOFF; ?></a> &nbsp;|&nbsp; <?php } ?><a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="headerNavigation"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></a> &nbsp;|&nbsp; <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="headerNavigation"><?php echo HEADER_TITLE_CART_CONTENTS; ?></a> &nbsp;|&nbsp; <a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>" class="headerNavigation"><?php echo HEADER_TITLE_CHECKOUT; ?></a> &nbsp;&nbsp;</td>
  </tr>
</table>
<table  background="images/boxborderfs.gif" valign="top" border="0" width="100%" height="6" cellspacing="0" cellpadding="0">

  <tr>

<td><img  src="images/boxborderfs.gif"></td>
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
    <td class="headerInfo"><?php echo htmlspecialchars($HTTP_GET_VARS['info_message']);  ?></td>
  </tr>
</table>
<?php
  }
?>