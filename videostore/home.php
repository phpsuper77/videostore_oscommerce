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
 /*
	// RJW Begin Meta Tags Code
	if (file_exists(DIR_WS_INCLUDES . 'meta_tags.php')) {
		require(DIR_WS_INCLUDES . 'meta_tags.php');
	} else {
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
	}
// EOF: WebMakers.com Changed: Header Tag Controller v1.0
*/
?>
<?php
 //error_reporting(E_ALL);ini_set('display_errors', '1');
require_once('includes/addons/osc_metatags.php');?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>
<link rel="stylesheet" type="text/css" href="menustyle.css" media="screen, print" >
<script src="menuscript.js"  type="text/javascript"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? "https://" : "http://")?>www.shawnolson.net/scripts/public_smo_scripts.js"></script>
<link rel="shortcut icon" href="/favicon.ico">
<link rel="icon" type="image/gif" href="animated_favicon1.gif" >
<link rel="apple-touch-icon" href="apple-touch-icon.png" >
<meta name="msvalidate.01" content="2234D77B2C0249194B9EFBE1275A0217" >
</head>
<body>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php');?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td colspan="3">

		</td>
	</tr>
  	<tr>
    	<td width="<?php echo BOX_WIDTH; ?>" valign="top">
    		<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- categories //-->
<tr>
	<td>
		<img alt="this is image" src="images/bar-clap.gif" border="0">
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
    <td class="boxText"><a href="http://www.travelvideostore.com/2014-new-releases/"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">2014 New Releases</a><br><a href="http://www.travelvideostore.com/2015-new-releases/"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">2015 New Releases</a><br><a href="http://www.travelvideostore.com/africa/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Africa</a><br><a href="http://www.travelvideostore.com/antarctica/"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Antarctica</a><br><a href="http://www.travelvideostore.com/asia/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Asia</a><br><a href="http://www.travelvideostore.com/box-sets/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Box Sets</a><br><a href="http://www.travelvideostore.com/caribbean/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Caribbean</a><br><a href="http://www.travelvideostore.com/celebrity-hosts/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Celebrity Hosts</a><br><a href="http://www.travelvideostore.com/cruise/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Cruise</a><br><a href="http://www.travelvideostore.com/europe/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Europe</a><br><a href="http://www.travelvideostore.com/imax/"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Imax</a><br><a href="http://www.travelvideostore.com/middle-east/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Middle East</a><br><a href="http://www.travelvideostore.com/national-parks/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">National Parks</a><br><a href="http://www.travelvideostore.com/north-america/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">North America</a><br><a href="http://www.travelvideostore.com/railway-journeys/"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Railway Journeys</a><br><a href="http://www.travelvideostore.com/religious-journeys-videos/"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Religious Journeys Videos</a><br><a href="http://www.travelvideostore.com/royalty-free/"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Royalty Free</a><br><a href="http://www.travelvideostore.com/scenic-musical-journeys/"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Scenic Musical Journeys</a><br><a href="http://www.travelvideostore.com/south-america/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">South America</a><br><a href="http://www.travelvideostore.com/south-pacific/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">South Pacific</a><br><a href="http://www.travelvideostore.com/special-interest/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Special Interest</a><br><a href="http://www.travelvideostore.com/tracs/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">Tracs</a><br><a href="http://www.travelvideostore.com/travel-video-producers/"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Travel Video Producers</a><br><a href="http://www.travelvideostore.com/travel-video-series/"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Travel Video Series</a><br><a href="http://www.travelvideostore.com/travel-videos-1/"><img src="images/categories/arrow_bullet.gif" border="0" alt="" width="9" height="9">Travel Videos</a><br><a href="http://www.travelvideostore.com/unesco/"><img src="images/categories/arrow_down.gif" border="0" alt="" width="9" height="9">UNESCO</a><br></td>
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
<!-- categories_eof //--><!-- column_banner //-->
          <tr>
            <td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td height="14" class="infoBoxHeading"><img src="images/infobox/corner_right_left.gif" border="0" alt="" width="11" height="14"></td>
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
    <td align="center" class="boxText"><script type="text/javascript"><!--
var google_ad_client = "ca-pub-8269488350960681";
/* travel */
var google_ad_slot = "1842608873";
var google_ad_width = 200;
var google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></td>
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
<!-- column_banner_eof //-->
<!-- column_banner //-->
          <tr>
            <td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td height="14" class="infoBoxHeading"><img src="images/infobox/corner_right_left.gif" border="0" alt="" width="11" height="14"></td>
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
    <td align="center" class="boxText"><script type="text/javascript"><!--
var google_ad_client = "ca-pub-8269488350960681";
/* travel */
var google_ad_slot = "1842608873";
var google_ad_width = 200;
var google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></td>
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
<!-- column_banner_eof //-->
<!-- column_banner //-->
          <tr>
            <td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td height="14" class="infoBoxHeading"><img src="images/infobox/corner_right_left.gif" border="0" alt="" width="11" height="14"></td>
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
    <td align="center" class="boxText"><script type="text/javascript"><!--
var google_ad_client = "ca-pub-8269488350960681";
/* travel */
var google_ad_slot = "1842608873";
var google_ad_width = 200;
var google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></td>
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
<!-- column_banner_eof //-->
          
<tr>
            <td>
<img alt="this is image" src="images/bar-clap.gif">
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
    <td align="left" class="boxText"><a href="http://www.travelvideostore.com/affiliate_info.php">Affiliate Information</a><br><a href="http://www.travelvideostore.com/affiliate_faq.php">Affiliate Program FAQ</a><br><a href="https://secure.travelvideostore.com/affiliate_affiliate.php">Affiliate Log In</a></td>
  </tr>
  <tr>
    <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
</table>
</td>
  </tr>
</table>
            </td>
          </tr><!-- ssl_provider info box //-->
<tr>
	<td>

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
<!-- left_navigation_eof //-->
    		</table>
    	</td>
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
            <td class="main"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=1>
<TBODY>
<TR>
<TD vAlign=top rowSpan=2 width="25%" align=center><FONT size=2 face="Arial, Helvetica, sans-serif"><STRONG>Select a Region</STRONG> </FONT>
<DIV align=center><img src="images/maps/map-world_small.gif" border="0" alt="Travel Videos to the World" title=" Travel Videos to the World " width="200" height="108" usemap="#map-world_small.gif"><MAP name=map-world_small.gif><AREA href="http://www.travelvideostore.com/north-america/" shape=POLY alt="North America Travel Videos and Films in VHS and DVD" coords=92,2,60,0,5,9,5,22,43,56,47,49,39,40,64,27><AREA href="http://www.travelvideostore.com/caribbean/" shape=POLY alt="Caribbean Travel Videos and Films in VHS and DVD" coords=40,40,48,35,64,41,56,48,48,48><AREA href="http://www.travelvideostore.com/south-america/" shape=POLY alt="South America Travel Videos and Films in VHS and DVD" coords=48,49,42,62,55,101,64,101,76,60,58,48><AREA href="http://www.travelvideostore.com/africa/" shape=POLY alt="Africa Travel Videos and Films in VHS and DVD" coords=115,35,124,51,128,50,127,80,104,86,81,49,87,31,105,30><AREA href="http://www.travelvideostore.com/europe/" shape=POLY alt="Europe Travel Videos and Films in VHS and DVD" coords=116,32,105,29,87,30,88,13,111,2,129,5,126,21><AREA href="http://www.travelvideostore.com/asia/" shape=POLY alt="Asia Travel Videos and Films in VHS and DVD" coords=130,5,141,1,188,9,174,45,187,58,183,66,177,65,167,68,141,53,138,44,146,33,141,30,123,26,128,20><AREA href="http://www.travelvideostore.com/middle-east/" shape=POLY alt="Middle East Travel Videos and Films in VHS and DVD" coords=146,33,141,30,124,26,116,33,123,49,136,45><AREA href="http://www.travelvideostore.com/south-pacific/" shape=POLY alt="South Pacific Travel Videos and Films in VHS and DVD" coords=187,58,196,61,199,87,175,94,156,81,167,68,184,66></MAP><BR><FONT size=1 face="Arial, Helvetica, sans-serif"><A href="http://www.travelvideostore.com/africa/">Africa </A><A href="http://www.travelvideostore.com/antarctica/">Antarctica </A><A href="http://www.travelvideostore.com/asia/">Asia </A><A href="http://www.travelvideostore.com/caribbean/">Caribbean </A><BR><A href="http://www.travelvideostore.com/europe/">Europe </A><A href="http://www.travelvideostore.com/middle-east/">Middle East </A><BR><A href="http://www.travelvideostore.com/north-america/">N. America </A><A href="http://www.travelvideostore.com/south-america/">S. America </A><A href="http://www.travelvideostore.com/south-pacific/">S. Pacific </A></FONT></DIV></TD>
<TD style="BACKGROUND-IMAGE: url(/images/header/b02.jpg)" height=75 width="75%"><BR><BR><BR></TD></TR>
<TR>
<TD bgColor=#f0f1f1 width="75%" align=center>
<P align=center><B><U>Travel Videos</U></B> including <STRONG><EM><U>Sightseeing</U></EM></STRONG>, <EM><STRONG><U>Destination</U></STRONG></EM>, <EM><STRONG><U>Vacation</U></STRONG></EM>, <EM><STRONG><U>Cultural</U></STRONG></EM>, <EM><STRONG><U>Souvenir</U></STRONG></EM>, <EM><STRONG><U>Tourist</U></STRONG></EM>, and <EM><STRONG><U>Travelogue</U></STRONG></EM> films on DVD.  </P></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
<TBODY>
<TR>
<TD vAlign=top width="33%">
<H3><FONT size=3><B><FONT color=#0000cc>
<P><FONT size=3><FONT color=#0000cc><STRONG>Narrated Series</STRONG></FONT> </FONT><FONT size=1><FONT color=#000000><BR><STRONG>(No On-camera Speaker)</STRONG> </FONT></FONT></P></FONT></B></FONT></H3><FONT size=3><A href="http://www.travelvideostore.com/travel-video-series/7-days/">7 Days</A>   <FONT color=#0033ff size=1>Country & Regional Tours</FONT> <BR><A href="http://www.travelvideostore.com/travel-video-series/a-west-coast-experience/">A West Coast Experience</A> <BR><A href="http://www.travelvideostore.com/travel-video-producers/a-worldly-pursuits-production/">A Worldly Pursuits Productions</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/aerial-adventures/">Aerial Adventures</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/african-secrets/">African Secrets</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/back-roads-of-europe/">Back Roads of Europe</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/british-birds/">British Birds</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/buses-around-britian/">Buses Around Britain</A> <BR><A href="http://www.travelvideostore.com/travel-video-producers/canadian-wildlife-productions/">Canadian Wildlife</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/cities-of-the-world-1/">Cities of the World</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/cosmos-global/">Cosmos Global Documentaries</A> <BR><A href="http://www.travelvideostore.com/travel-video-producers/daval-productions/">Destination...</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/diesel-trains/">Diesel Trains</A> <BR><A <br=""><A href="http://www.travelvideostore.com/travel-video-series/discover-the-world-1/">Discover the World</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/discovering-provence/">Discovering Provence</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/extravaganza/">Extravaganza</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/extreme-environments/">Extreme Environments</A> <BR><A <br=""><A href="http://www.travelvideostore.com/travel-video-producers/8-star-entertainment/">Ghosts of Great Britain</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/great-american-festivals/">Great American Festivals</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/great-cruises/">Great Cruises</A> <BR><A href="http://www.travelvideostore.com/travel-video-producers-travel-dvd-videos/gwendolene-and-charles-gray/">Gwendolene & Charles Gray Productions </A><BR><A href="http://www.travelvideostore.com/travel-video-producers/hiltz-squared-media-productions/">Hiltz Squared Media</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/icelands-favourite-places/">Iceland's Favourite Places</A> <BR><A hr<br=""><A href="http://www.travelvideostore.com/travel-video-series/legacy-of-ancient-civilizations/">Legacy of Ancient Civilizations</A> <BR><A hr<br=""><A href="http://www.travelvideostore.com/travel-video-series/mysteries/">Mysteries</A> <BR><A hr<br=""><A href="http://www.travelvideostore.com/travel-video-series/narrowboats/">Narrowboats</A> <BR><A hr<br=""><A href="http://www.travelvideostore.com/travel-video-series/nature-tracks/">Nature Tracks</A> <BR><A hr<br=""><A href="http://www.travelvideostore.com/travel-video-series/new-frontiers-chinese-civilization/">New Frontiers - Chinese Civilizations</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/new-frontiers-road-to-civilization/">New Frontiers - Road to Civilizations </A><BR><A href="http://www.travelvideostore.com/travel-video-series/nourished-by-the-same-river/">Nourished by the Same River</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/on-tour-/">On Tour...</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/rediscovering-china/">Rediscovering China</A> <BR><A <br=""><A href="http://www.travelvideostore.com/travel-video-series/rediscovering-the-yangtze-river/">Rediscovering the Yangtze River</A> <BR><A <br=""><A href="http://www.travelvideostore.com/travel-video-producers/round-table-associates/">Round Table Associates</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/southampton/">Southampton</A> <BR><A href="http://www.travelvideostore.com/travel-video-producers/usa-travel-dvd/">Take a Tour</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/terra-mystica/">Terra Mystica</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/the-treasures-of-ancient-hellas/">The Treasures of Ancient Hellas</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/the-world-atlas/">The World Atlas</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/top-destinations/">Top Destinations</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/travel-girls/">Travel Girls</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/travelview-international/">Travelview International</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/video-postcards/">Video Postcards</A> <BR><A href="http://www.travelvideostore.com/travel-video-producers/videotime-productions/">VideoTime Productions</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/vietnam/">Vietnam</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/vista-point/">Vista Point</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/warfile/">Warfile</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/wild-venezuela/">Wild Venezuela</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/world-destinations/">World Destinations</A> <BR><A href="http://www.travelvideostore.com/travel-video-series/worlds-greatest-festivals/">World's Greatest Festivals</A> <BR>
<H3></H3>
<H3><FONT size=3><FONT color=#0000cc>SHORT FORMAT FILMS</FONT> </FONT><FONT size=1><BR><FONT color=#000000>(Perfect for short presentations i.e.  classrooms, travel <BR>agents or as a souvenir of a specific destination)<BR>(**typically 10 mins in length**)</FONT></FONT> </H3>
<P><A href="http://www.travelvideostore.com/travel-video-series/a-video-postcard/">A Video Postcard</A><BR><A href="http://www.travelvideostore.com/travel-video-series/global-treasures/">Global Treasures</A><BR><A href="http://www.travelvideostore.com/travel-video-series/modern-times-wonders/">Modern Times Wonders</A><BR><A href="http://www.travelvideostore.com/travel-video-series/nature-wonders/">Nature Wonders</A> </P></FONT></TD>
<TD vAlign=top width="33%">
<H3>
<P><FONT size=3><FONT color=#0000cc><STRONG>Hosted Series</STRONG></FONT> </FONT><FONT size=1><FONT color=#000000><BR><STRONG>(On-camera Speaker)</STRONG> </FONT></FONT></P></H3><A href="http://www.travelvideostore.com/travel-video-series/beyond-the-list/">Beyond the List</A><BR><A href="http://www.travelvideostore.com/travel-video-series/bikini-destinations/">Bikini Destinations</A><BR><A href="http://www.travelvideostore.com/travel-video-series/bump-/">Bump!</A><BR><A href="http://www.travelvideostore.com/travel-video-series/carolina-road-trips/">Carolina Road Trips</A><BR><A href="http://www.travelvideostore.com/travel-video-series/countries-less-travelled/">Countries Less Traveled</A><BR><A href="http://www.travelvideostore.com/travel-video-series/cruising-carolina/">Cruising Carolina</A><BR><A href="http://www.travelvideostore.com/travel-video-series-travel-dvd-videos/crusing-the-world/">Cruising the World</A><BR><A href="http://www.travelvideostore.com/travel-video-series/europe-after-dark/">Europe After Dark</A><BR><A href="http://www.travelvideostore.com/travel-video-series/europes-classic-romantic-inns/">Europe's Classic Romantic Inns</A><BR><A href="http://www.travelvideostore.com/travel-video-series/exotic-worlds/">Exotic Worlds</A><BR><A href="http://www.travelvideostore.com/travel-video-series/explore/">Explore</A><BR><A href="http://www.travelvideostore.com/travel-video-series/exploring-horizons/">Exploring Horizons</A><BR><A href="http://www.travelvideostore.com/travel-video-series/forever-new-orleans/">Forever New Orleans</A><BR><A href="http://www.travelvideostore.com/travel-video-series/garden-travels/">Garden Travels</A><BR><A href="http://www.travelvideostore.com/travel-video-series/global-gumshoe/">Global Gumshoe</A><BR><A href="http://www.travelvideostore.com/travel-video-series/hawaii-the-action-islands/">Hawaii the Action Islands</A><BR><A href="http://www.travelvideostore.com/travel-video-series-travel-dvd-videos/heritage-hunter/">Heritage Hunter</A><BR><A href="http://www.travelvideostore.com/travel-video-series/justsaygo/">JustSayGo</A><BR><A href="http://www.travelvideostore.com/travel-video-series/karma-trekkers/">Karma Trekkers</A><BR><A href="http://www.travelvideostore.com/travel-video-series/lets-shop/">Let's Shop</A><BR><A href="http://www.travelvideostore.com/travel-video-series/laura-mckenzies-traveler/">Laura Mckenzie's Traveler</A><BR><A href="http://www.travelvideostore.com/travel-video-series/oh-no-its-kato-/">Oh No! It's Kato</A><BR><A href="http://www.travelvideostore.com/travel-video-series/passport-to-adventure/">Passport to Adventure</A><BR><A href="http://www.travelvideostore.com/travel-video-series/porthole-tv/">Porthole TV</A><BR><A href="http://www.travelvideostore.com/celebrity-hosts/rudy-maxa/">Rudy Maxa</A><BR><A href="http://www.travelvideostore.com/travel-video-series/spontaneous-adventures/">Spontaneous Adventures</A><BR><A href="http://www.travelvideostore.com/travel-video-series/tanlines/">Tanlines</A><BR><A href="http://www.travelvideostore.com/travel-video-series/the-best-of-california/">The Best of California</A><BR><A href="http://www.travelvideostore.com/travel-video-series/the-brewshow/">The Brewshow</A><BR><A href="http://www.travelvideostore.com/travel-video-series/the-city-walker/">The City Walker</A><BR><A href="http://www.travelvideostore.com/travel-video-series/the-compulsive-traveler/">The Compulsive Traveler</A><BR><A href="http://www.travelvideostore.com/travel-video-series/the-seasoned-traveler/">The Seasoned Traveler</A><BR><A href="http://www.travelvideostore.com/travel-video-series/travelogue/">Travelogue</A><BR><A href="http://www.travelvideostore.com/travel-video-series/traveloz/">Travel Oz</A><BR><A href="http://www.travelvideostore.com/travel-video-series-travel-dvd-videos/travel-wild/">Travel Wild</A><BR><A href="http://www.travelvideostore.com/travel-video-series/treasures/">Treasures</A><BR><A href="http://www.travelvideostore.com/travel-video-series/weekend-explorer/">Weekend Explorer</A><BR><A href="http://www.travelvideostore.com/travel-video-series/wine-tours-the-sweet-life/">Wine Tours</A> 
<P></P>
<H3><FONT size=3><FONT color=#0000cc><STRONG>OUTDOOR ADVENTURES</STRONG></FONT> </FONT><FONT size=1><BR><FONT color=#000000><STRONG>(Fishing, Diving, Surfing, Horseback Riding,Skiing, Hunting & Golfing)</STRONG></FONT> </FONT></H3><A href="http://www.travelvideostore.com/travel-video-series/clay-shooting/">Clay Shooting</A><BR><A href="http://www.travelvideostore.com/travel-video-series/dive-travel/">Dive Travel</A><BR><A href="http://www.travelvideostore.com/travel-video-series/equitrekking/">EquiTrekking</A><BR><A href="http://www.travelvideostore.com/travel-video-series/fishing-for-beginners/">Fishing for Beginners</A><BR><A href="http://www.travelvideostore.com/travel-video-series/fishing-with-the-experts/">Fishing with the Experts</A><BR><A href="http://www.travelvideostore.com/travel-video-series/good-time-golf/">Good Time Golf</A><BR><A href="http://www.travelvideostore.com/travel-video-series/island-hoppers/">Island Hoppers</A><BR><A href="http://www.travelvideostore.com/travel-video-series-travel-dvd-videos/just-fish-it/">Just Fish It Inc</A><BR><A href="http://www.travelvideostore.com/travel-video-series-travel-dvd-videos/outdoors-with-eddie-brochin/">Outdoors with Eddie Brochin</A><BR><A href="http://www.travelvideostore.com/travel-video-series-travel-dvd-videos/sportsfishing-with-dan-hernandez/">Sport Fishing with Dan Hernandez</A><BR><A href="http://www.travelvideostore.com/travel-video-series-travel-dvd-videos/sports-safaris/">Sports Safaris</A> <BR><A <br=""><A href="http://www.travelvideostore.com/travel-video-series/the-best-of-skiing/">The Best of Skiing</A><BR><A href="http://www.travelvideostore.com/travel-video-series/ultimate-outdoors/">Ultimate Outdoors with Eddie Brochin</A><BR><A <p="">
<P></P>
<P><FONT size=3 <font=""><FONT color=#0000cc><STRONG>YOUTH TRAVEL SERIES</STRONG></FONT> </FONT><FONT size=1><BR><FONT color=#000000><STRONG>(4 - 26 year age group)</STRONG></FONT> </FONT>
<H3></H3></A>
<P><A <p=""><A href="http://www.travelvideostore.com/travel-video-series/alternate-routes/">Alternate Routes</A><BR><A href="http://www.travelvideostore.com/travel-video-series/extremists/">Extremists</A><BR><A href="http://www.travelvideostore.com/travel-video-series/get-outta-town/">Get Outta Town</A><BR><A href="http://www.travelvideostore.com/travel-video-series/passport-to-explore/">Passport to Explore</A><BR><A href="http://www.travelvideostore.com/travel-video-series/the-little-travelers/">The Little Travelers</A><BR><A href="http://www.travelvideostore.com/travel-video-series/wide-world-of-kids/">Wide World of Kids</A> </P>
<P> </P></TD>
<TD vAlign=top width="34%">
<H3>
<P><FONT size=3><FONT color=#0000cc><STRONG>Travelogue Films</STRONG></FONT> </FONT><FONT size=1><FONT color=#000000><BR><STRONG>(Typically Longer Formats)</STRONG> </FONT></FONT></P></H3><A href="http://www.travelvideostore.com/travel-video-producers/a2zcds/">A2ZCDS</A><BR><A><A href="http://www.travelvideostore.com/travel-video-producers/abcd-video/">ABCD</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/buddy-hatton-productions/">Buddy Hatton Productions</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/clint-denn-digital-cinema/">Clint Denn Digital Cinema</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/international-travel-films/">Doug Jones - International Travel Films</A><U><FONT color=#800080> </FONT></U><BR><A href="http://www.travelvideostore.com/travel-video-producers/flying-monk-films/">Flying Monk Films</A><BR><A href="http://www.travelvideostore.com/travel-video-series/globeriders/">Globe Riders</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/globescope-expeditions/">Globescope Expeditions - Academic Media</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/frank-m-klicar-video-production/">Frank Klicar Productions</A><BR><A href="http://www.travelvideostore.com/travel-video-producers-travel-dvd-videos/marlin-darrah-international-film-video/">Marlin Darrah - International Film & Video</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/media-lab/">Media Lab</A><BR><A href="http://www.travelvideostore.com/travel-video-series/monty-marsha-brown/">Monty & Marsha Brown</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/panorama-australia/">Panorama Australia</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/photos-of-africa/">Photos of Africa</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/rda-productions/">RDA Productions</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/richard-hunt/">Richard Hunt</A><BR><A href="http://www.travelvideostore.com/travel-video-series-travel-dvd-videos/rivers-of-our-time/">Rivers of Our Time</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/shidog-films/">Shidog Films</A><BR><A href="http://www.travelvideostore.com/travel-video-series/the-new-great-game/">The New Great Game</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/trailwood-films/">Trailwood Films</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/wilson-wilkins-productions/">Wilson & Wilkens Productions</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/worldwide-travel-films/">World Travel Films</A> 
<H3><FONT size=3><FONT color=#0000cc>RELAXATION SERIES</FONT><BR><FONT size=1>(No Narration - Beautiful scenery set to music or natural sounds)</FONT> </FONT></H3><A href="http://www.travelvideostore.com/travel-video-series/esovision/">Esovisions</A><BR><A href="http://www.travelvideostore.com/travel-video-series/gardens-of-the-world-1/">Gardens of the World</A><BR><A href="http://www.travelvideostore.com/travel-video-series-travel-dvd-videos/naxos-scenic-musical-journeys/">Naxos Scenic Musical Journeys</A><BR><A href="http://www.travelvideostore.com/travel-video-series/travelscapes/">Travelscapes</A> 
<H3></H3>
<H3><FONT size=3><FONT color=#0000cc>
<H3><FONT size=3><FONT color=#0000cc><STRONG>EXERCISE SERIES </STRONG></FONT><STRONG><FONT size=1><BR><FONT color=#000000>(Virtual bike rides and other exercise films)</FONT></FONT></STRONG><FONT size=1> </FONT></FONT></H3></FONT></FONT></H3><FONT size=3><A href="http://www.travelvideostore.com/travel-video-series-travel-dvd-videos/country-roads/">Country Roads</A><BR><A href="http://www.travelvideostore.com/travel-video-series/viva-fit-n-fun/">Viva Fit'n'Fun</A> 
<H3></H3>
<H3><FONT size=3><FONT color=#0000cc>Adult Content Series <FONT size=1><BR><FONT color=#000000><STRONG>(Contains adult material, nudity, language)</STRONG></FONT> </FONT></FONT></FONT></H3><A href="http://www.travelvideostore.com/travel-video-series/adventures-in-europe/">Adventures in Europe</A><BR><A href="http://www.travelvideostore.com/travel-video-series/bangkok-bound/">Bangkok Bound</A><BR>
<H3><FONT size=3><FONT color=#0000cc>
<H3><FONT size=3><FONT color=#0000cc>Stock Footage Collections<FONT size=1><FONT color=#000000><STRONG><BR>(Collections of Royalty-free stockfootage)</STRONG></FONT> </FONT></FONT></FONT></H3></FONT></FONT></H3><A href="http://www.travelvideostore.com/travel-video-series/the-globescope-collection/">Globescope Collection</A><BR><A href="http://www.travelvideostore.com/travel-video-producers/intense-films/">Intense Films </A><BR><A href="http://www.travelvideostore.com/travel-video-series/planet-terra/">Planet Terra </A>
<P></P>
<H3><FONT color=#0000cc size=3>NATIONAL PARKS</FONT> </H3>
<P><A href="http://www.travelvideostore.com/travel-video-series/nature-parks/">Nature Parks</A></P>
<P><FONT size=3><FONT color=#0000cc><STRONG>WORLD CUISINE SERIES</STRONG></FONT> </FONT><FONT size=1><BR><STRONG>(Learn about a destination while you cook)</STRONG> </FONT></P>
<P><A href="http://www.travelvideostore.com/travel-video-series/accidental-chef/">Accidental Chef</A><BR><A href="http://www.travelvideostore.com/travel-video-series/african-food-adventures/">African Food Adventures</A><BR><A href="http://www.travelvideostore.com/travel-video-series/cuisine-culture/">Cuisine Culture</A><BR><A href="http://www.travelvideostore.com/travel-video-series/culinary-travels/">Culinary Travels w/Dave Eckert</A><BR><A href="http://www.travelvideostore.com/travel-video-series/flavors-of-france/">Flavors of France</A><BR><A href="http://www.travelvideostore.com/travel-video-series/flavors-of-italy/">Flavors of Italy</A><BR><A href="http://www.travelvideostore.com/travel-video-series/global-gourmet/">Global Gourmet</A><BR><A href="http://www.travelvideostore.com/travel-video-series/great-chefs-of-austria/">Great Chefs of Austria</A> </P></FONT></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
<TBODY>
<TR>
<TD bgColor=#f0f1f1 width="100%" align=center>
<DIV align=center><FONT size=2><B><FONT size=3>Travel Videos</FONT></B> are great for <U>planning a vacation</U>, <U>reliving a memorable trip</U>, <U>learning about different cultures</U>, <U>experiencing the wonders of our world</U>, or if <U>relocating</U>, getting to know your new town. </FONT><BR><FONT size=3><B>SEE THE WORLD FROM THE COMFORT OF YOUR OWN HOME</B> </FONT></DIV></TD></TR>
<TR>
<TD width="100%" align=center>
<DIV align=center><FONT size=3> <B>On-line Inventory</B>  <U>Overnight Shipping</U> if ordered by 4pm EST M-F<BR>  <FONT color=#ff0000 size=3><U>Customer Service and Telephone Orders</U><B> 1-(800) 288-5123 </B></FONT><BR> <FONT size=3>We accept <U>Public School</U> and <U>Library</U> <B>Purchase Order's</B></FONT> </FONT></DIV></TD></TR></TBODY></TABLE></td>
          </tr>
      	<tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>
            <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
          </tr>
          <tr>
            <td><!-- new_products //-->
</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="170" valign="top"><table border="0" width="170" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
  
  <table border="0" width="100%" cellspacing="0" cellpadding="1">
	<tr class="footer">
	  <td class="footer">&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
  </table>
  
<div  align="center"> 
  <table cellspacing="0" cellpadding="0">
  <tr>
    <td height="18" width="25%"><font size="3"><b><u>COMPANY INFO</u></b></font></td>
    <td width="25%"><font size="3"><b><u>INFORMATION</u></b></font></td>
    <td width="25%"><font size="3"><b><u>CUSTOMERS</u></b></font></td>
    <td width="25%"><font size="3"><b><u>GENERAL INFO</u></b></font></td>
  </tr>
  <tr>
    <td height="14" width="25%"><font size="2"><a href="../info_pages.php?pages_id=3" target="">About    Us</a></font></td>
    <td width="25%"></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=4" target="">Educators</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=11" target="">Satisfaction    Guarantee</a></font></td>
  </tr>
  <tr>
    <td height="14" width="25%"><font size="2"><a href="../info_pages.php?pages_id=19" target="">Conditions    of Use</a></font></td>
    <td width="25%"><font size="2"><a href="../faq.php" target="">Frequently Ask    Questions&nbsp&nbsp&nbsp</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=5" target="">Libraries</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=40" target="">Quantity    Discounts</a></font></td>
  </tr>
  <tr>
    <td height="14" width="25%"><font size="2"><a href="../info_pages.php?pages_id=44" target="">Join    Our Team</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=33" target="">Payment    Methods</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=6" target="">Travel    Agents</a></font></td>
    <td width="25%"><font size="2"><a href="../events_calendar.php?view=all_events" target="">Travel Show Appearances</a></font></td>
  </tr>
  <tr>
    <td height="14" width="25%"><font size="2"><a href="../info_pages.php?pages_id=7" target="">Speakers    Bureau</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=10" target="">Shipping    Information</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=48" target="">Senior Centers</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=8" target="">Product Submissions</a></font></td>  
  </tr>
  <tr>
    <td height="14" width="25%"><font size="2"><a href="../info_pages.php?pages_id=35" target="">Press    Room</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=32" target="">Free    Shipping Offer</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=49" target="">BookStores/Video Stores&nbsp&nbsp&nbsp</a></font></td>
<td width="25%"><font size="2"><a href="https://secure.travelvideostore.com/vendors/vendor_account.php" target="">Producer Login</a></font></td>
  </tr>
  <tr>
    <td height="14" width="25%"><font size="2"><a href="../contact_us.php" target="">Contact Us</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=9" target="">Purchase    Orders</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=50" target="">Gift Shops</a></font></td>
    <td></td>
  </tr>
  <tr>
    <td height="14"></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=53"  target="">Public Performance</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=51" target="">Catalog Companies</a></font></td>
    <td></td>
  </tr>
  <tr>
    <td height="14"></td>
    <td width="25%"><font size="2"><a href="../redeem.php" target="">Gift Card Redeem</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=52" target="">Websites</a></font></td>
    <td></td>
  </tr>
  <tr>
    <td height="14"></td>
    <td></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=54" target="">Tour Bus Companies</a></font></td>
    <td></td>
  </tr>
   <tr>
    <td height="14"></td>
    <td></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=43" target="">Wholesale    - Distribution</a></font></td>
    <td></td>
  </tr>

</table><br>
<b><A href="contact_us.php">Contact Us</A> |  <A href="info_pages.php?pages_id=19">Conditions of Use</A> |  <A href="sitemap.php">Sitemap</A> |  <A href="info_pages.php?pages_id=18">Privacy Policy</A></b><BR>TravelVideoStore.com 5420 Boran Dr Tampa FL 33610<BR><b>Tollfree (800) 288-5123</b>   Direct 813-630-9778   <b>FAX 813-627-0334</b><BR>
<span style="display:inline-block; vertical-align:middle"> 
 Copyright &copy; 2003-2015
<a href="http://www.dmca.com/Protection/Status.aspx?id=4b9105a3-8c22-46a4-a64c-c85fb982b842" title="DMCA"> <img src ="/images/dmca_protected_sml_120l.png"  align="middle" alt="DMCA.com" /></a>
</span> </div>
<?php
/*
//  <script type="text/javascript">if(!NREUMQ.f){NREUMQ.f=function(){NREUMQ.push(["load",new Date().getTime()]);var e=document.createElement("script");e.type="text/javascript";e.async=true;e.src="https://d1ros97qkrwjf5.cloudfront.net/30/eum/rum.js";document.body.appendChild(e);if(NREUMQ.a)NREUMQ.a();};NREUMQ.a=window.onload;window.onload=NREUMQ.f;};NREUMQ.push(["nrf2","beacon-1.newrelic.com","523f3371f1",215914,"ZVRXbEJRXUAFB0JQXlweYEpZH1pdAAFOF0FaQQ==",0,227,new Date().getTime()]);</script>
*/ ?>
<!-- footer_eof //-->
<br>
</body>
</html>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>