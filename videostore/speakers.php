<?php
/*
  $Id: shipping.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $breadcrumb->add('Speakers Bureau', tep_href_link(FILENAME_SPEAKERS));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?> 
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Speakers Bureau</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">
<center><i>"To Travel is to possess the world"</i><BR>
Burton Holmes<BR>
<table bgcolor="#CC9966" border="0"  cellpadding=7 cellspacing=5>
<tr>
<td align=center>
<p><img src="images/sldsho1.gif" width=100 height=80 alt="" border="0"></p>
</td>
<td align=center>
<p><img src="images/sldsho2.gif" width=100 height=80 alt="" border="0"></p>
</td>
<td align=center>
<p><img src="images/sldsho3.gif" width=100 height=80 alt="" border="0"></p>
</td>
<td align=center>
<p><img src="images/sldsho4.gif" width=100 height=80 alt="" border="0"></p>
</td>
</tr>
</table></center>
<BR>
<i>Good Evening, Ladies and Gentleman.  Tonight we are going to take a wonderful trip to .....!</i><BR><BR>

You may ask, what are Travel Adventure Cinema presentations?  Professional cinematographers who love travel and adventure. Before filming, they thoroughly research the area or topic. On location they seek out unique and interesting places for filming.  Using award-winning video, our master storytellers bring the world home, sharing engaging, behind-the-scenes accounts of their travels with your organization, school, travel agency event, and church groups while viewing their quality film.  With high powered digital cameras, very bright projection equipment and a lust for adventure, these modern day traveloguers are creating a new world of high quality family entertainment.  

<table><tr><td class=main>
TravelVideoStore.com is a proud member of the Travel Adventure Cinema Society (TRACS), preserving the tradition and quality of Professional Travelogue Lecturers.</td><td><img src="images/banr_tracs.gif" alt="" border="2"></td></tr></table>
With every performance, our travelogers provide an escape, a reminder that their are beautiful things in the world, and there are quality people of every nationality, while sharing their unique stories of adventure.  Whether you are planning a lecture series or need to entertain a large audience with stories of adventure and discovery or motivate your employees through interesting anecdotes of success, or simply raise money for your organization, we have speakers to meet your needs.  Enjoy attending or sponsoring a vivid armchair experience in the safety of your own hometown theater. <BR><BR>

Contact us at <a href="emailto:speakers@travelvideostore.com">Speakers@TravelVideoStore.com</a> for more information about engaging a travelogue lecturer<BR><BR>

<table><tr><td><img src="images/travelcinemamagazine.jpg" alt="" border="2"></td><td class=main>
TravelVideoStore.com is a proud member of the Travel Adventure Cinema Society (TRACS), preserving the tradition and quality of Professional Travelogue Lecturers.  Travel Adventure Cinema magazine, is the trade journal among travel cinematographers, lecture series booking agencies, and cinematagraher want-to-be's, at only $10 per 2 issue year, fall and spring issues, it is an excellent source of travel video experiences, subscribe by sending us an email at <a href="mailto:subscriptions@travelvideostore.com">Subscriptions@TravelVideoStore.com</a>. </td></tr></table><BR>

Interested in getting involoved in Travel Adventure Cinema?  Starting a new series? Or looking for one in your town or neighborhood?  Or perhaps you would like to learn more about the elite group of photojournalist and cinematagraphers that travel the globe looking for stories to bring home to you.  For more information about joining the Travel Adventure Cinema Society, send us a request at <a href="mailto:tracs@travelvideostore.com">TRACS@travelvideostore.com</a>.<BR><BR>

<b>Speaker List</b>  Coming Soon, direct links to video products and biographies of Travelogue lectuers we serve.

		</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
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
