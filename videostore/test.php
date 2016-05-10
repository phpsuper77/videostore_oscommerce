<?php
ob_start();
/*
  $Id: index.php,v 1.1 2003/06/11 17:37:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

/* CURRENT PROJECT FILE*/
  require('includes/application_top.php');


// the following cPath references come from application_top.php
  $category_depth = 'top';
  if (tep_not_null($cPath)) {

//  setcookie(Category_Path_Link,$cPath,time()+10800); //Cookie expire in 3 hours

  $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
    $categories_products = tep_db_fetch_array($categories_products_query);
    if ($categories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
 <?php
// RJW Begin Meta Tags Code
if (file_exists(DIR_WS_INCLUDES . 'meta_tags.php')) {
  require(DIR_WS_INCLUDES . 'meta_tags.php');
} else {
?>
<?php
// BOF: WebMakers.com Changed: Header Tag Controller v1.0
// Replaced by header_tags.php
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>

  <title><?php echo TITLE ?></title>
<?php
}
// RJW End Meta Tags Code
?>
<?php
}
// EOF: WebMakers.com Changed: Header Tag Controller v1.0
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php');?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="170" valign="top"><table border="0" width="170" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<!-- categories //-->
          <tr>
            <td>
<img src="images/bar-clap.gif">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td height="14" class="infoBoxHeading"><img src="images/infobox/corner_right_left.gif" border="0" alt="" width="11" height="14"></td>
    <td width="100%" height="14" class="infoBoxHeading">Categories</td>
    <td height="14" class="infoBoxHeading" nowrap><img src="images/pixel_trans.gif" border="0" alt="" width="11" height="14"></td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="1" class="infoBox">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="3" class="infoBoxContents">
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
  <tr>
    <td class="boxText"><a href="http://www.travelvideostore.com/index.php?cPath=97"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Africa</a><br><a href="http://www.travelvideostore.com/index.php?cPath=515"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Antarctica</a><br><a href="http://www.travelvideostore.com/index.php?cPath=149"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Asia</a><br><a href="http://www.travelvideostore.com/index.php?cPath=259"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Caribbean</a><br><a href="http://www.travelvideostore.com/index.php?cPath=58"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Europe</a><br><a href="http://www.travelvideostore.com/index.php?cPath=148"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Middle East</a><br><a href="http://www.travelvideostore.com/index.php?cPath=33"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">North America</a><br><a href="http://www.travelvideostore.com/index.php?cPath=34"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">South America</a><br><a href="http://www.travelvideostore.com/index.php?cPath=150"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">South Pacific</a><br><a href="http://www.travelvideostore.com/index.php?cPath=262"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Special Categories</a><br><a href="http://www.travelvideostore.com/index.php?cPath=407"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Box Sets</a><br><a href="http://www.travelvideostore.com/index.php?cPath=401"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Cruise Videos</a><br><a href="http://www.travelvideostore.com/index.php?cPath=516"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">IMAX Films</a><br><a href="http://www.travelvideostore.com/index.php?cPath=266"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">National Parks</a><br><a href="http://www.travelvideostore.com/index.php?cPath=405"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Railway Journeys</a><br><a href="http://www.travelvideostore.com/index.php?cPath=449"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Religious Journeys</a><br><a href="http://www.travelvideostore.com/index.php?cPath=444"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Scenic Musical Journeys</a><br><a href="http://www.travelvideostore.com/index.php?cPath=808"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Travel Video Series</a><br><a href="http://www.travelvideostore.com/index.php?cPath=959"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Travel Video Producers</a><br><a href="http://www.travelvideostore.com/index.php?cPath=1253"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">DVD Slim Pack Sets</a><br><a href="http://www.travelvideostore.com/index.php?cPath=1254"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Royalty-Free Clips</a><br></td>
  </tr>
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
</table>
</td>
  </tr>
</table>
            </td>
          </tr>
<!-- categories_eof //--><!-- info_pages //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="170" valign="top"><table border="0" width="170" cellspacing="0" cellpadding="2">
          <tr>
            <td>
<img src="images/bar-clap.gif">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td height="14" class="infoBoxHeading"><img src="images/infobox/corner_right_left.gif" border="0" alt="" width="11" height="14"></td>
    <td width="100%" height="14" class="infoBoxHeading">Information</td>
    <td height="14" class="infoBoxHeading" nowrap><img src="images/pixel_trans.gif" border="0" alt="" width="11" height="14"></td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="1" class="infoBox">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="3" class="infoBoxContents">
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
  <tr>
    <td class="boxText"><table border="0" width="100%" cellspacing="0" cellpadding="1"><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=34">Association Memberships</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=4">Educators</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=32">Free Shipping Offer</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/faq.php">Frequently Ask Questions</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=5">Libraries</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=33">Payment Methods</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=35">Press Room</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=8">Product Submission</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=9">Purchase Orders</a></td></tr><tr><td class="infoBoxContents">
<a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=40">Quantity Discounts</a></td></tr><tr><td class="infoBoxContents">

<a target="" href="http://www.travelvideostore.com/redeem.php">Redeem Gift Card</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=11">Satisfaction Guarantee</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=26">See us in/on</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=10">Shipping Information</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=7">Speakers Bureau</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/info_pages.php?pages_id=6">Travel Agents</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/events_calendar.php?view=all_events">Travel Show Appearances</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/contact_us.php">Contact Us</a></td></tr></table></td>
  </tr>
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
</table>
</td>
  </tr>
</table>
            </td>
          </tr>
<!-- info_pages_eof //--><!-- info_pages //-->
          <tr>
            <td>
<img src="images/bar-clap.gif">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td height="14" class="infoBoxHeading"><img src="images/infobox/corner_right_left.gif" border="0" alt="" width="11" height="14"></td>
    <td width="100%" height="14" class="infoBoxHeading">Celebrity Hosts</td>
    <td height="14" class="infoBoxHeading" nowrap><img src="images/pixel_trans.gif" border="0" alt="" width="11" height="14"></td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="1" class="infoBox">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="3" class="infoBoxContents">
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
  <tr>
    <td class="boxText"><table border="0" width="100%" cellspacing="0" cellpadding="1"><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/index.php?cPath=1632_1638">Estelle Bingham</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/index.php?cPath=1632_1636">Ian Wright</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/index.php?cPath=1632_1635">Justine Shapiro</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/index.php?cPath=1632_1634">Megan McCormick</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/index.php?cPath=1640_1641">Rick Ray</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/index.php?cPath=1632_1633">Rick Steves</a></td></tr><tr><td class="infoBoxContents"><a target="" href="http://www.travelvideostore.com/index.php?cPath=1632_1631">Rudy Maxa</a></td></tr></table></td>
  </tr>
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
</table>
</td>
  </tr>
</table>
            </td>
          </tr>
<!-- info_pages_eof //-->

<!-- cat_request //-->
          <tr>
            <td>
<img src="images/bar-clap.gif">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td height="14" class="infoBoxHeading"><img src="images/infobox/corner_right_left.gif" border="0" alt="" width="11" height="14"></td>
    <td width="100%" height="14" class="infoBoxHeading">Catalog Request</td>
    <td height="14" class="infoBoxHeading" nowrap><img src="images/pixel_trans.gif" border="0" alt="" width="11" height="14"></td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="1" class="infoBox">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="3" class="infoBoxContents">
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
  <tr>
    <td align="center" class="boxText"><a href="http://www.travelvideostore.com/cat_request.php">Click here to request a Free catalog!</a></td>
  </tr>
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
</table>
</td>
  </tr>
</table>
</td></tr>
<!-- cat_request_eof //--><!-- information //-->
          <tr>
            <td>
<img src="images/bar-clap.gif">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td height="14" class="infoBoxHeading"><img src="images/infobox/corner_right_left.gif" border="0" alt="" width="11" height="14"></td>
    <td width="100%" height="14" class="infoBoxHeading">Useful Links</td>
    <td height="14" class="infoBoxHeading" nowrap><img src="images/pixel_trans.gif" border="0" alt="" width="11" height="14"></td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="1" class="infoBox">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="3" class="infoBoxContents">
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
  <tr>
    <td class="boxText"><center><a href="http://www.travelvideostore.com/links.php?lPath=1"> Links</a><br></center></td>
  </tr>
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
</table>
</td>
  </tr>
</table>
            </td>
          </tr>
<!-- information_eof //--><!-- ssl_provider info box //-->
<tr>
	<td>
<img src="images/bar-clap.gif">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td height="14" class="infoBoxHeading"><img src="images/infobox/corner_right_left.gif" border="0" alt="" width="11" height="14"></td>
    <td width="100%" height="14" class="infoBoxHeading">Secured by Comodo</td>
    <td height="14" class="infoBoxHeading" nowrap><img src="images/pixel_trans.gif" border="0" alt="" width="11" height="14"></td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="1" class="infoBox">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="3" class="infoBoxContents">
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
  <tr>
    <td align="center" class="boxText"><script language="javascript" type="text/javascript">TrustLogo("http://www.travelvideostore.com/images/secure_site.gif", "SC", "none");</script></td>
  </tr>
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
</table>
</td>
  </tr>
</table>
  </td>
</tr>
<!-- ssl_provider_eof //-->
<tr>
	<td>
<img src="images/bar-clap.gif">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td height="14" class="infoBoxHeading"><img src="images/infobox/corner_right_left.gif" border="0" alt="" width="11" height="14"></td>
    <td width="100%" height="14" class="infoBoxHeading">Affiliate Program</td>
    <td height="14" class="infoBoxHeading" nowrap><img src="images/pixel_trans.gif" border="0" alt="" width="11" height="14"></td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="1" class="infoBox">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="3" class="infoBoxContents">
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
  <tr>

<tr><td class="infoBoxContents"><A 
                        href="http://www.travelvideostore.com/affiliate_info.php">Affiliate 
                        Information</A><BR><A 
                        href="http://www.travelvideostore.com/affiliate_faq.php">Affiliate 
                        Program FAQ</A><BR><A 
                        href="https://www.travelvideostore.com/affiliate_affiliate.php">Affiliate 
                        Log In</A></td></tr>



  </tr>
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
</table>
</td>
  </tr>
</table>
  </td>
</tr>


<!--     //-->

</table>
  </td>
</tr>



<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
     <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"></td>
            <td class="pageHeading" align="right">            </td>
          </tr>
        </table></td>
      </tr>

          <tr>
            <td class="main"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
<TBODY>
<TR>
<TD bgColor=lightskyblue>
<CENTER><B><U>Travel Videos</U></B>&nbsp;including 
<STRONG><EM><U>Sightseeing</U></EM></STRONG>, 
<EM><STRONG><U>Destination</U></STRONG></EM>, 
<EM><STRONG><U>Vacation</U></STRONG></EM>, 
<EM><STRONG><U>Cultural</U></STRONG></EM>, 
<EM><STRONG><U>Souvenir</U></STRONG></EM>, 
<EM><STRONG><U>Tourist</U></STRONG></EM>, and 
<EM><STRONG><U>Travelogue</U></STRONG></EM> films on DVD.&nbsp; Free 
Shipping&nbsp;on <U>US&nbsp;Delivered Orders</U>.</CENTER>
<CENTER><STRONG>VIEW THOUSANDS OF SAMPLE FREE TRAVEL VIDEO 
CLIPS</STRONG></CENTER></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0>
<TBODY>
<TR>
<TD align=middle width="40%" bgColor=beige><SMALL>Select a Region to 
Find<BR>Your<STRONG> Travel Videos</STRONG> by Country<BR></SMALL><img src="images/maps/map-world_small.gif" border="0" alt="Travel Videos to the World" title=" Travel Videos to the World " width="200" height="108" usemap="#map-world_small.gif"><MAP 
name=map-world_small.gif><AREA shape=POLY 
alt="North America Travel Videos and Films in VHS and DVD" 
coords=92,2,60,0,5,9,5,22,43,56,47,49,39,40,64,27 
href="index.php?cPath=33"><AREA shape=POLY 
alt="Caribbean Travel Videos and Films in VHS and DVD" 
coords=40,40,48,35,64,41,56,48,48,48 href="index.php?cPath=259"><AREA shape=POLY 
alt="South America Travel Videos and Films in VHS and DVD" 
coords=48,49,42,62,55,101,64,101,76,60,58,48 href="index.php?cPath=34"><AREA 
shape=POLY alt="Africa Travel Videos and Films in VHS and DVD" 
coords=115,35,124,51,128,50,127,80,104,86,81,49,87,31,105,30 
href="index.php?cPath=97"><AREA shape=POLY 
alt="Europe Travel Videos and Films in VHS and DVD" 
coords=116,32,105,29,87,30,88,13,111,2,129,5,126,21 
href="index.php?cPath=58"><AREA shape=POLY 
alt="Asia Travel Videos and Films in VHS and DVD" 
coords=130,5,141,1,188,9,174,45,187,58,183,66,177,65,167,68,141,53,138,44,146,33,141,30,123,26,128,20 
href="index.php?cPath=149"><AREA shape=POLY 
alt="Middle East Travel Videos and Films in VHS and DVD" 
coords=146,33,141,30,124,26,116,33,123,49,136,45 
href="index.php?cPath=148"><AREA shape=POLY 
alt="South Pacific Travel Videos and Films in VHS and DVD" 
coords=187,58,196,61,199,87,175,94,156,81,167,68,184,66 
href="index.php?cPath=150"></MAP><BR><FONT face="Arial, Helvetica, sans-serif" 
size=2><A href="index.php?cPath=97">Africa </A><A 
href="index.php?cPath=515">Antarctica </A><A href="index.php?cPath=149">Asia 
</A><A href="index.php?cPath=259">Caribbean </A><A 
href="index.php?cPath=58">Europe </A><A href="index.php?cPath=148">Middle East 
</A><A href="index.php?cPath=33">North &amp; Central America </A><A 
href="index.php?cPath=34">South America </A><A href="index.php?cPath=150">South 
Pacific </A></FONT></TD>
<TD width="30%" bgColor=beige><FONT size=3><B>Popular&nbsp;Interests 
</B></FONT><BR><FONT color=#ff0000 size=2><I>Select&nbsp;Special 
Interest</I></FONT><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=262_401"><FONT size=2>Cruise Ship Videos 
</FONT></A><BR><FONT size=2>&nbsp;&nbsp;•&nbsp;</FONT><A 
href="index.php?cPath=808_1986"><FONT size=2>Porthole TV </FONT></A><BR><FONT 
size=2>•&nbsp;</FONT><A href="index.php?cPath=262_405"><FONT size=2>Railway 
Journeys</FONT></A><BR><FONT size=1>•&nbsp;</FONT><A 
href="index.php?cPath=262_449"><FONT size=2>Religious 
Journeys</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=262_266_398_399"><FONT size=2>US National Parks 
</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=262_266_398_406"><FONT size=2>Canada Nat'l 
Parks</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=262_269"><FONT size=2>Children's 
Travel</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=262_407"><FONT size=2>Box Set Travel</FONT></A><BR><FONT 
size=2>•&nbsp;</FONT><A href="index.php?cPath=262_397"><FONT size=2>Castles, 
Palaces, &amp; Inns</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=262_525"><FONT size=2>Underwater &amp; 
Diving</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=262_444"><FONT size=2>Musical Journey</FONT></A><BR><FONT 
size=2>&nbsp;&nbsp;•&nbsp;</FONT><A href="index.php?cPath=808_2107"><FONT 
size=2>Terra Mystica</FONT></A><BR><FONT 
size=2>&nbsp;&nbsp;•&nbsp;</FONT><A href="index.php?cPath=808_2105"><FONT 
size=2>ESOVISION Relaxation</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=262_270"><FONT size=2>Theme Park</FONT></A><FONT size=2> 
</FONT></TD>
<TD width="30%" bgColor=beige>
<P><FONT size=3><B>Popular Series</B><BR></FONT><FONT color=#ff0000><FONT 
size=2><I>Select Popular Series/Producer</I></FONT><BR></FONT><FONT 
size=2>•&nbsp;</FONT><A href="index.php?cPath=808_825"><FONT size=2>Globe 
Trekker Videos</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=808_1618"><FONT size=2>Rick Steves 
Videos</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=959_1092"><FONT size=2>Reader's Digest Videos 
</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=808_1755"><FONT size=2>Weekend Explorer 
Videos</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=808_1990"><FONT size=2>Get Outta Town</FONT></A><BR><FONT 
size=2>•&nbsp;</FONT><A href="index.php?cPath=808_1984"><FONT size=2>Alternate 
Routes</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=808_2108"><FONT size=2>Extravaganza</FONT></A><BR><FONT 
size=2>•&nbsp;</FONT><A href="index.php?cPath=808_936"><FONT size=2>Discoveries 
America</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=1632_1631"><FONT size=2>Rudy Maxa 
Videos</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="/index.php?cPath=808_2101"><FONT size=2>Global 
Treasures</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=959_1150"><FONT size=2>Travel Video 
Int'l</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="index.php?cPath=262_516"><FONT size=2>IMAX&nbsp;Videos 
</FONT></A><BR><FONT size=2>•&nbsp;</FONT><A href="index.php?cPath=2160"><FONT 
size=2>The Seasoned Traveler </FONT></A><BR><FONT size=2>•&nbsp;</FONT><A 
href="/index.php?cPath=2129"><FONT size=2>Samantha Brown 
Europe</FONT></A><BR></P></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
<TBODY>
<TR>
<TD align=middle width="100%" bgColor=lightskyblue><FONT size=2><B><FONT 
size=3>Travel Videos</FONT></B> are great for <U>planning a vacation</U>, 
<U>reliving a memorable trip</U>, <U>learning about different cultures</U>, 
<U>experiencing the wonders of our world</U>, or if <U>relocating</U>, getting 
to know your new town.</FONT><BR><FONT size=3><B>SEE THE WORLD FROM THE COMFORT 
OF YOUR OWN HOME</B></FONT></TD></TR>
<TR>
<TD align=middle width="100%"><FONT size=3>•&nbsp;<B>On-line 
Inventory</B>&nbsp;•&nbsp;<U>Overnight Shipping</U> if ordered by 4pm EST 
M-F<BR>•&nbsp;<FONT color=#ff0000 size=3><U>Customer Service and Telephone 
Orders</U><B> 1-(800) 288-5123 </B></FONT><BR>•&nbsp;<FONT size=3>We accept 
<U>Public School</U> and <U>Library</U> <B>Purchase 
Order's</B></FONT></FONT></TD></TR></TBODY></TABLE></td>
          </tr>
</tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>
            <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
          </tr>
          <tr>
            <td><!-- new_products //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td height="24" class="infoBoxHeading"><img src="images/infobox/corner_left.gif" border="0" alt="" width="15" height="15"></td>
    <td height="24" class="infoBoxHeading" width="100%">New Products For July<a class="headerNavigation1" href="http://www.travelvideostore.com/new_products_more.php"> &nbsp;&nbsp;more...</a></td>
    <td height="24" class="infoBoxHeading"><img src="images/infobox/corner_right_left.gif" border="0" alt="" width="11" height="14"></td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="1" class="infoBox">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="4" class="infoBoxContents">
  <tr>
    <td align="center" class="smallText" width="33%" valign="top"><a href="javascript:void(0)"
	onClick=javascript:window.open("./includes/languages/english/dvd.html","DVD","width=400,height=400,scrollbars=yes")><img src="images/media_type/2.gif" border="0" alt="DVD - Click Here for More Info on DVD Formats" title=" DVD - Click Here for More Info on DVD Formats " width="30" height="15"><a href="http://www.travelvideostore.com/product_info.php?products_id=7594"><img src="images/product_small/IMAX_DVD_4662.jpg" border="0" alt="IMAX&nbsp;Greece:&nbsp;Secrets of the Past" title=" IMAX&nbsp;Greece:&nbsp;Secrets of the Past " width="43" height="60"></a><br><a href="http://www.travelvideostore.com/product_info.php?products_id=7594"><b>IMAX</b><BR>&nbsp;<B>Greece:</b>&nbsp;Secrets of the Past</a><br>1 DVD<br>$19.99</td>
    <td align="center" class="smallText" width="33%" valign="top"><a href="javascript:void(0)"
	onClick=javascript:window.open("./includes/languages/english/dvd.html","DVD","width=400,height=400,scrollbars=yes")><img src="images/media_type/2.gif" border="0" alt="DVD - Click Here for More Info on DVD Formats" title=" DVD - Click Here for More Info on DVD Formats " width="30" height="15"><a href="http://www.travelvideostore.com/product_info.php?products_id=7592"><img src="images//product_small/PTE_DVD_1226.jpg" border="0" alt="Passport to Europe&nbsp;Seven Fabulous Cities&nbsp;" title=" Passport to Europe&nbsp;Seven Fabulous Cities&nbsp; " width="43" height="60"></a><br><a href="http://www.travelvideostore.com/product_info.php?products_id=7592"><b>Passport to Europe</b><BR>&nbsp;<B>Seven Fabulous Cities</b>&nbsp;</a><br>1 DVD<br>$14.99</td>
    <td align="center" class="smallText" width="33%" valign="top"><a href="javascript:void(0)"
	onClick=javascript:window.open("./includes/languages/english/dvd.html","DVD","width=400,height=400,scrollbars=yes")><img src="images/media_type/2.gif" border="0" alt="DVD - Click Here for More Info on DVD Formats" title=" DVD - Click Here for More Info on DVD Formats " width="30" height="15"><a href="http://www.travelvideostore.com/product_info.php?products_id=7591"><img src="images//product_small/PTE_DVD_1127.jpg" border="0" alt="Passport to Europe&nbsp;Germany,Switzerland & Austria&nbsp;" title=" Passport to Europe&nbsp;Germany,Switzerland & Austria&nbsp; " width="43" height="60"></a><br><a href="http://www.travelvideostore.com/product_info.php?products_id=7591"><b>Passport to Europe</b><BR>&nbsp;<B>Germany,Switzerland & Austria</b>&nbsp;</a><br>1 DVD<br>$14.99</td>
  </tr>
  <tr>
    <td align="center" class="smallText" width="33%" valign="top"><a href="javascript:void(0)"
	onClick=javascript:window.open("./includes/languages/english/dvd.html","DVD","width=400,height=400,scrollbars=yes")><img src="images/media_type/2.gif" border="0" alt="DVD - Click Here for More Info on DVD Formats" title=" DVD - Click Here for More Info on DVD Formats " width="30" height="15"><a href="http://www.travelvideostore.com/product_info.php?products_id=7590"><img src="images//product_small/PTE_DVD_0922.jpg" border="0" alt="Passport to Europe&nbsp;England, Ireland, & Scotland&nbsp;" title=" Passport to Europe&nbsp;England, Ireland, & Scotland&nbsp; " width="43" height="60"></a><br><a href="http://www.travelvideostore.com/product_info.php?products_id=7590"><b>Passport to Europe</b><BR>&nbsp;<B>England, Ireland, & Scotland</b>&nbsp;</a><br>1 DVD<br>$14.99</td>
    <td align="center" class="smallText" width="33%" valign="top"><a href="javascript:void(0)"
	onClick=javascript:window.open("./includes/languages/english/dvd.html","DVD","width=400,height=400,scrollbars=yes")><img src="images/media_type/2.gif" border="0" alt="DVD - Click Here for More Info on DVD Formats" title=" DVD - Click Here for More Info on DVD Formats " width="30" height="15"><a href="http://www.travelvideostore.com/product_info.php?products_id=7589"><img src="images/product_small/ABNR_DVD_9392.jpg" border="0" alt="Anthony Bourdain&nbsp;No Reservations&nbsp;" title=" Anthony Bourdain&nbsp;No Reservations&nbsp; " width="43" height="60"></a><br><a href="http://www.travelvideostore.com/product_info.php?products_id=7589"><b></b><BR>Anthony Bourdain&nbsp;<B>No Reservations</b>&nbsp;</a><br>4 DVD Set<br>$24.99</td>
    <td align="center" class="smallText" width="33%" valign="top"><a href="javascript:void(0)"
	onClick=javascript:window.open("./includes/languages/english/dvd.html","DVD","width=400,height=400,scrollbars=yes")><img src="images/media_type/2.gif" border="0" alt="DVD - Click Here for More Info on DVD Formats" title=" DVD - Click Here for More Info on DVD Formats " width="30" height="15"><a href="http://www.travelvideostore.com/product_info.php?products_id=7588"><img src="images//product_small/PTE_DVD_6621.jpg" border="0" alt="Passport to Europe&nbsp;France & Italy&nbsp;" title=" Passport to Europe&nbsp;France & Italy&nbsp; " width="43" height="60"></a><br><a href="http://www.travelvideostore.com/product_info.php?products_id=7588"><b>Passport to Europe</b><BR>&nbsp;<B>France & Italy</b>&nbsp;</a><br>1 DVD<br>$14.99</td>
  </tr>
</table>
</td>
  </tr>
</table>

<!-- new_products_eof //-->
</td>
          </tr>
		  <tr>
            <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
          </tr>
		  <tr>
            <td><!-- featured_products //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td height="24" class="infoBoxHeading"><img src="images/infobox/corner_left.gif" border="0" alt="" width="15" height="15"></td>
    <td height="24" class="infoBoxHeading" width="100%">Featured Products<a class="headerNavigation1" href="http://www.travelvideostore.com/featured_products.php"> &nbsp;&nbsp;more...</a></td>
    <td height="24" class="infoBoxHeading"><img src="images/infobox/corner_right_left.gif" border="0" alt="" width="11" height="14"></td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="1" class="infoBox">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="4" class="infoBoxContents">
  <tr>
    <td align="center" class="smallText" width="33%" valign="top"><a href="http://www.travelvideostore.com/product_info.php?products_id=3703"><img src="images/product_small/QUE_DVD_QD3484.jpg" border="0" alt="<b>Cruise</b><br>The Ultimate&nbsp;<b>Cruise</b>&nbsp;Collection - 6 DVD Slimpack Set<BR>6 DVD Set" title=" <b>Cruise</b><br>The Ultimate&nbsp;<b>Cruise</b>&nbsp;Collection - 6 DVD Slimpack Set<BR>6 DVD Set " width="45" height="60"></a><br><a href="http://www.travelvideostore.com/product_info.php?products_id=3703"><b>Cruise</b><br>The Ultimate&nbsp;<b>Cruise</b>&nbsp;Collection - 6 DVD Slimpack Set<BR>6 DVD Set</a><br>$39.99</td>
    <td align="center" class="smallText" width="33%" valign="top"><a href="http://www.travelvideostore.com/product_info.php?products_id=4598"><img src="images/product_small/QUE_DVD_QD3587.jpg" border="0" alt="<b>Europe to the Max</b><br>&nbsp;<b>Box Set</b>&nbsp;Europe To The Max<BR>6 DVD Set" title=" <b>Europe to the Max</b><br>&nbsp;<b>Box Set</b>&nbsp;Europe To The Max<BR>6 DVD Set " width="43" height="60"></a><br><a href="http://www.travelvideostore.com/product_info.php?products_id=4598"><b>Europe to the Max</b><br>&nbsp;<b>Box Set</b>&nbsp;Europe To The Max<BR>6 DVD Set</a><br>$59.99</td>
    <td align="center" class="smallText" width="33%" valign="top"><a href="http://www.travelvideostore.com/product_info.php?products_id=6640"><img src="images/product_small/EBTD_DVD_0760.jpg" border="0" alt="<b>Rick Steves</b><br>All 70 Shows&nbsp;<b>Europe</b>&nbsp;2000-2007<BR>12 DVD Set" title=" <b>Rick Steves</b><br>All 70 Shows&nbsp;<b>Europe</b>&nbsp;2000-2007<BR>12 DVD Set " width="43" height="60"></a><br><a href="http://www.travelvideostore.com/product_info.php?products_id=6640"><b>Rick Steves</b><br>All 70 Shows&nbsp;<b>Europe</b>&nbsp;2000-2007<BR>12 DVD Set</a><br>$99.95</td>
  </tr>
</table>
</td>
  </tr>
</table>

<!-- featured_products_eof //-->
</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
