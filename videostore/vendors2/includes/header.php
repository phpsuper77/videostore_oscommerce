<table  background="../images/boxborderfs.gif" valign="top" border="0" width="100%"  cellspacing="0" cellpadding="0">

  <tr>

<td><img  src="../images/boxborderfs.gif"></td>
</tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td><IMG SRC="../images/movie_camera2.gif" ALT="Travel DVD's are more visually educational"></td><td>
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
						echo "<a href=".$aff_img_rs['affiliate_homepage']."><img src=../images/".$aff_img_rs['affiliate_logo']." border=\"0\" alt=\"Partnered with TravelVideoStore.com More Travel Videos to More Places - Click here to return to our partners website\" title=\"Partnered with TravelVideoStore.com More Travel Videos to More Places - Click here to return to our partners website\"></a></td>


";


					else
						echo "<a href=\"http://www.travelvideostore.com\"><img src=\"../images/tvs_banner.gif\" border=\"0\" alt=\"TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store\" title=\" TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store\"></a></td>




";


				}
				else

					echo "<a href=\"http://www.travelvideostore.com\"><img src=\"../images/tvs_banner.gif\" border=\"0\" alt=\"TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store\" title=\" TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store\"></a></td>




";

			?>
<td width=100 class="smallText"><center>Travel Videos are a <font color="blue">great vacation planning tool</font></center></td>
<td width=5></td>
<td width=110 class="smallText"><center><font color="red">
Fire up your sales force</font> - with a video of your incentive destination</center></td><td width=5></td>
<td width=100 class="smallText"><center>Give the<font color="green"> gift of travel</font> with Travel Videos</center></td><td width=2></td>


<td><A href="http://www.ala.org/"><IMG title="American Library Association" src="../images/logos/ala_logo.jpg" border=0 TARGET="_blank"></A></td><td><a href="returns.php"><img src="../images/100satesfaction.gif" border="0" alt="Your Satesfaction is Guaranteed at TravelVideoStore.com" title="TravelVideoStore.com offers the industry's only satisfaction guarantee on travel videos - If you buy one of the travel videos we sell and don't like it, then return it!" TARGET="_blank"></a></td<td align="center"><a href="/info_pages.php?pages_id=32"><img src="../images/freeshipping.gif" border="0" alt="Free Shipping on Travel Videos via media mail to US Addresses" title="Free Shipping on Travel Videos via media mail to US Addresses" TARGET="_blank"></a></td>



	    	<!-- Incuded newly for Affilate -->


    <td align="right" valign="bottom"><?php //echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_account.gif', HEADER_TITLE_MY_ACCOUNT) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">' . tep_image(DIR_WS_IMAGES . 'header_cart.gif', HEADER_TITLE_CART_CONTENTS) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_checkout.gif', HEADER_TITLE_CHECKOUT) . '</a>'; ?>&nbsp;&nbsp;</td>
  </tr>
</table>

<table  background="../images/boxborderfs.gif" valign="top" border="0" width="100%" height="6" cellspacing="0" cellpadding="0">

  <tr>

<td><img  src="../images/boxborderfs.gif"></td>
</tr>
</table>