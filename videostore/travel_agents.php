<?php
/*
  $Id: shipping.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $breadcrumb->add('Travel Agents', tep_href_link(FILENAME_TRAVEL_AGENTS));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Travel Agents : <?php echo TITLE; ?></title>
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
            <td class="pageHeading">Travel Agents</td>
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
<a href="http://www.astanet.com"><img src="images/ASTA-LOGO.gif" border="0"  alt="TravelVideoStore.com is a Proud Member of the American Society of Travel Agents - Integrity in Travel" title="TravelVideoStore.com is a Proud Member of the American Society of Travel Agents - Integrity in Travel" TARGET="_blank"></a>
<a href="http://www.cruising.org"><img src="images/CLIA_logo.gif" border="0" alt="TravelVideoStore.com is a Proud Member of the Cruise Line Industry Association - you haven't lived until you've crusied - TravelVideoStore.com carries more cruise travel videos than anyone else" title="TravelVideoStore.com is a Proud Member of the Cruise Line Industry Association - you haven't lived until you've crusied - TravelVideoStore.com carries more cruise travel videos than anyone else" TARGET="_blank"></a>
<a href="http://www.nacta.com"><img src="images/NACTA_LOGO.jpg" border="0"  alt="TravelVideoStore.com is a Proud Member of the National Association of Commisioned Travel Agents" title="TravelVideoStore.com is a Proud Member of the National Association of Commisioned Travel Agents" TARGET="_blank"></a>


<BR><BR>

TravelVideoStore.com supports the travel industry and professional travel agents by being your best and largest source for travel videos with experienced customer support staff to assist you.  We carry thousands of videos to destinations around the world, adding new titles daily.  <BR><BR>

<STRONG>PROMOTE TRAVEL VIDEOS! - THEY PROMOTE TRAVEL!</STRONG><BR><BR>

<b>Why buy Travel Videos?</b><ul>

<LI>Travel Videos can help you sell destinations to your clients</li>
<LI>Recommending Travel Videos can help your customers get the most out of their vacation</li>
<LI>Travel Videos can help you plan itineraries for your clients</LI>
<LI>Travel Videos make an excellent way to show your customer appreciation by giving them a gift that will help them enjoy the vacation you arranged for them.</LI> </ul>

<b>COMMONLY ASKED QUESTIONS BY TRAVEL AGENTS</b><BR><BR>

<b>Can I have you ship your videos to my clients?  Do you offer gift wrapping?  Will my clients know that the video is from me?</b><BR><BR>

At TravelVideoStore.com we support your business by providing shipping to alternate addresses, allowing you to save your alternate addresses for future purchases for that client and we offer gift wrap service for a nominal fee.  In addition, the packing slip (<a href="images/packingslip.jpg" target=" " ><i>sample</i></a>) we send will note that you are the purchaser, but not how much you spent and you can also include a small note by entering it in on your order comments.  We offer multiple shipping methods that allow you to deliver a video tomorrow or ship it more economical methods.<BR><BR>

<b>How can travel videos help me sell more travel?</b><BR><BR>

Travel Videos allow you to generate excitement and desire amongst your clients by visually showing what different destinations have to offer from a cultural and historical perspective along with exciting things to do in each location.  Most successful travel agents own a complete library of travel videos that they loan out to potential clients.  Travel Videos also educate you on destinations, making you a valuable resource for your customers to seek advice from.  With the growing number of travel purchases being made on the internet, travel agents today need to be more informed, more educated, and more experience in travel destinations to gain a loyal customer following, Travel Videos help you gain that education at a fraction of the cost.<BR><BR>

<b>How can travel videos help my clients enjoy their vacation more?</b><BR><BR>
The more educated your customer is about the destination they are embarking to, the more they will enjoy the nuances that make each destination unique.  As personal taste varies, your customer can eliminate certain sites on their visit and focus their time on the more enjoyable attractions that they will identify through prior video preparation.  Have you ever been on a tour where you spent more time trying to listen to the tour guide than actually enjoying the sites?  Travel videos gives your clients the background info that they would get from a tour guide, allowing them to better experience the environment.<BR><BR>

<b>Do you have a discount program for travel agents?</b><BR><BR>

We do not currently offer a discount program for travel agents, though by joining our affiliate program, travel agents can earn extra income and receive commissions back on their purchases.  With our affiliate program you can put a link on your website, sending your customers to us, earning a commission for each video they purchase.  If you website is segmented into different destinations, you can link directly to a corresponding location in our site.  In addition, we offer the ability to generate individual product links that you can insert to your website or into emails that you send directly to your customers.  Our technical staff can help prepare customized links to meet your needs.<BR><BR>

At TravelVideoStore.com, all we do is Travel Videos, we have knowledgable customer service staff that can help you find the best video to meet your needs.  Should you have any questions, please call us at 1-800-288-5123.  Send Travel Agent related questions to us at <a href="mailto:travelagents@travelvideostore.com">TravelAgents@TravelVideoStore.com</a>
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
