<?php

/*

  $Id: conditions.php,v 1.22 2003/06/05 23:26:22 hpdl Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/



  require('includes/application_top.php');



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONDITIONS);



  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CONDITIONS));

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

            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

            <td class="pageHeading" align="right"></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td class="main">

<u>Welcome to TRAVELVIDEOSTORE.com</u>. TRAVELVIDEOSTORE.com and its affiliates provide their services to you subject to the following conditions. If you visit or shop at TRAVELVIDEOSTORE.com, you accept these conditions. Please read them carefully. In addition, when you use any current or future TRAVELVIDEOSTORE.com service or visit or purchase from any business affiliated with TRAVELVIDEOSTORE.com, whether or not included in the TRAVELVIDEOSTORE.com Web site, you also will be subject to the guidelines and conditions applicable to such service or business. <BR><BR>



<b>PRIVACY</b> <BR><BR>



Please review our Privacy Notice, which also governs your visit to TRAVELVIDEOSTORE.com, to understand our practices. <BR><BR>



<b>ELECTRONIC COMMUNICATIONS</b><BR><BR>



When you visit TRAVELVIDEOSTORE.com or send e-mails to us, you are communicating with us electronically. You consent to receive communications from us electronically. We will communicate with you by e-mail or by posting notices on this site. You agree that all agreements, notices, disclosures and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing. <BR><BR>



<b>COPYRIGHT</b><BR><BR>



All content included on this site, such as text, graphics, logos, button icons, images, audio clips, digital downloads, data compilations, and software, is the property of TRAVELVIDEOSTORE.com or its content suppliers and protected by United States and international copyright laws. The compilation of all content on this site is the exclusive property of TRAVELVIDEOSTORE.com and protected by U.S. and international copyright laws. All software used on this site is the property of TRAVELVIDEOSTORE.com or its software suppliers and protected by United States and international copyright laws. <BR><BR>



<b>TRADEMARKS </b><BR><BR>



TRAVELVIDEOSTORE.COM  and other marks indicated on our site are registered trademarks of TRAVELVIDEOSTORE.com, Inc. or its subsidiaries, in the United States and other countries.  Other TRAVELVIDEOSTORE.com graphics, logos, page headers, button icons, scripts, and service names are trademarks or trade dress of TRAVELVIDEOSTORE.com, Inc. or its subsidiaries. TRAVELVIDEOSTORE.com's trademarks and trade dress may not be used in connection with any product or service that is not TRAVELVIDEOSTORE.com's, in any manner that is likely to cause confusion among customers, or in any manner that disparages or discredits TRAVELVIDEOSTORE.com. All other trademarks not owned by TRAVELVIDEOSTORE.com or its subsidiaries that appear on this site are the property of their respective owners, who may or may not be affiliated with, connected to, or sponsored by TRAVELVIDEOSTORE.com or its subsidiaries. <BR><BR>



<b>LICENSE AND SITE ACCESS</b><BR><BR> 



TRAVELVIDEOSTORE.com grants you a limited license to access and make personal use of this site and not to download (other than page caching) or modify it, or any portion of it, except with express written consent of TRAVELVIDEOSTORE.com. This license does not include any resale or commercial use of this site or its contents; any collection and use of any product listings, descriptions, or prices; any derivative use of this site or its contents; any downloading or copying of account information for the benefit of another merchant; or any use of data mining, robots, or similar data gathering and extraction tools. This site or any portion of this site may not be reproduced, duplicated, copied, sold, resold, visited, or otherwise exploited for any commercial purpose without express written consent of TRAVELVIDEOSTORE.com. <BR><BR>You may not frame or utilize framing techniques to enclose any trademark, logo, or other proprietary information (including images, text, page layout, or form) of TRAVELVIDEOSTORE.com and our affiliates without express written consent. You may not use any meta tags or any other "hidden text" utilizing TRAVELVIDEOSTORE.com's name or trademarks without the express written consent of TRAVELVIDEOSTORE.com. Any unauthorized use terminates the permission or license granted by TRAVELVIDEOSTORE.com. You are granted a limited, revocable, and nonexclusive right to create a hyperlink to the home page of TRAVELVIDEOSTORE.com so long as the link does not portray TRAVELVIDEOSTORE.com, its affiliates, or their products or services in a false, misleading, derogatory, or otherwise offensive matter. You may not use any TRAVELVIDEOSTORE.com logo or other proprietary graphic or trademark as part of the link without express written permission. <BR><BR>



<b>YOUR ACCOUNT </b><BR><BR>



If you use this site, you are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer, and you agree to accept responsibility for all activities that occur under your account or password. TRAVELVIDEOSTORE.com does sell products for children, but it sells them to adults, who can purchase with a credit card. If you are under 18, you may use TRAVELVIDEOSTORE.com only with involvement of a parent or guardian. TRAVELVIDEOSTORE.com and its affiliates reserve the right to refuse service, terminate accounts, remove or edit content, or cancel orders in their sole discretion. <BR><BR>



<b>REVIEWS, COMMENTS, COMMUNICATIONS, AND OTHER CONTENT</b><BR><BR>



Visitors may post reviews, comments, and other content; send e-cards and other communications; and submit suggestions, ideas, comments, questions, or other information, so long as the content is not illegal, obscene, threatening, defamatory, invasive of privacy, infringing of intellectual property rights, or otherwise injurious to third parties or objectionable and does not consist of or contain software viruses, political campaigning, commercial solicitation, chain letters, mass mailings, or any form of "spam." You may not use a false e-mail address, impersonate any person or entity, or otherwise mislead as to the origin of a card or other content. TRAVELVIDEOSTORE.com reserves the right (but not the obligation) to remove or edit such content, but does not regularly review posted content.<BR><BR>



If you do post content or submit material, and unless we indicate otherwise, you grant TRAVELVIDEOSTORE.com and its affiliates a nonexclusive, royalty-free, perpetual, irrevocable, and fully sublicensable right to use, reproduce, modify, adapt, publish, translate, create derivative works from, distribute, and display such content throughout the world in any media. You grant TRAVELVIDEOSTORE.com and its affiliates and sublicensees the right to use the name that you submit in connection with such content, if they choose. You represent and warrant that you own or otherwise control all of the rights to the content that you post; that the content is accurate; that use of the content you supply does not violate this policy and will not cause injury to any person or entity; and that you will indemnify TRAVELVIDEOSTORE.com or its affiliates for all claims resulting from content you supply. TRAVELVIDEOSTORE.com has the right but not the obligation to monitor and edit or remove any activity or content. TRAVELVIDEOSTORE.com takes no responsibility and assumes no liability for any content posted by you or any third party. 

<BR><BR>

<b>COPYRIGHT COMPLAINTS</b><BR><BR>



TRAVELVIDEOSTORE.com and its affiliates respect the intellectual property of others. If you believe that your work has been copied in a way that constitutes copyright infringement, please follow our Notice and Procedure for Making Claims of Copyright Infringement.<BR><BR>



<b>RISK OF LOSS</b><BR><BR>



All items purchased from TRAVELVIDEOSTORE.com are made pursuant to a shipment contract. This means that the risk of loss and title for such items pass to you upon our delivery to the carrier.<BR><BR>



<b>PRODUCT DESCRIPTIONS </b><BR><BR>



TRAVELVIDEOSTORE.com and its affiliates attempt to be as accurate as possible. However, TRAVELVIDEOSTORE.com does not warrant that product descriptions or other content of this site is accurate, complete, reliable, current, or error-free. If a product offered by TRAVELVIDEOSTORE.com itself is not as described, your sole remedy is to return it in unused condition. <BR><BR>



<b>PRICING</b><BR><BR>



Except where noted otherwise, the List Price displayed for products on our website represents the full retail price listed on the product itself, suggested by the manufacturer or supplier, or estimated in accordance with standard industry practice. The List Price is a comparative price estimate and may or may not represent the prevailing price in every area on any particular day. For certain items that are offered as a set, the List Price may represent "open-stock" prices, which means the aggregate of the manufacturer's estimated or suggested retail price for each of the items included in the set. Where an item is offered for sale by one of our merchants, the List Price may be provided by the merchant. <BR><BR>



With respect to items sold by TRAVELVIDEOSTORE.com, we cannot confirm the price of an item until you order; however, we do NOT charge your credit card until after your order has entered the shipping process. Despite our best efforts, a small number of the items in our catalog may be mispriced. If we discover a mispricing, we will do one of the following: <BR><BR>



If an item's correct price is lower than our stated price, we will charge the lower amount and ship you the item. 

If an item's correct price is higher than our stated price, we will, at our discretion, either contact you for instructions before shipping or cancel your order and notify you of such cancellation. 

Please note that this policy applies only to products sold and shipped by TRAVELVIDEOSTORE.com. Your purchases from third-party sellers using TRAVELVIDEOSTORE.com Payments are charged at the time you place your order, and third-party sellers may follow different policies in the event of a mispriced item.<BR><BR>





<b>DISCLAIMER OF WARRANTIES AND LIMITATION OF LIABILITY </b><BR><BR>



THIS SITE IS PROVIDED BY TRAVELVIDEOSTORE.COM ON AN "AS IS" AND "AS AVAILABLE" BASIS. TRAVELVIDEOSTORE.COM MAKES NO REPRESENTATIONS OR WARRANTIES OF ANY KIND, EXPRESS OR IMPLIED, AS TO THE OPERATION OF THIS SITE OR THE INFORMATION, CONTENT, MATERIALS, OR PRODUCTS INCLUDED ON THIS SITE. YOU EXPRESSLY AGREE THAT YOUR USE OF THIS SITE IS AT YOUR SOLE RISK. <BR><BR>



TO THE FULL EXTENT PERMISSIBLE BY APPLICABLE LAW, TRAVELVIDEOSTORE.COM DISCLAIMS ALL WARRANTIES, EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. TRAVELVIDEOSTORE.COM DOES NOT WARRANT THAT THIS SITE, ITS SERVERS, OR E-MAIL SENT FROM TRAVELVIDEOSTORE.COM ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS. TRAVELVIDEOSTORE.COM WILL NOT BE LIABLE FOR ANY DAMAGES OF ANY KIND ARISING FROM THE USE OF THIS SITE, INCLUDING, BUT NOT LIMITED TO DIRECT, INDIRECT, INCIDENTAL, PUNITIVE, AND CONSEQUENTIAL DAMAGES. <BR><BR>



CERTAIN STATE LAWS DO NOT ALLOW LIMITATIONS ON IMPLIED WARRANTIES OR THE EXCLUSION OR LIMITATION OF CERTAIN DAMAGES. IF THESE LAWS APPLY TO YOU, SOME OR ALL OF THE ABOVE DISCLAIMERS, EXCLUSIONS, OR LIMITATIONS MAY NOT APPLY TO YOU, AND YOU MIGHT HAVE ADDITIONAL RIGHTS. </b><BR><BR>



<b>APPLICABLE LAW</b><BR><BR> 



By visiting TravelVideoStore.com, you agree that the laws of the state of Florida, without regard to principles of conflict of laws, will govern these Conditions of Use and any dispute of any sort that might arise between you and TravelVideoStore.com or its affiliates. <BR><BR>



<b>DISPUTES</b><BR><BR>



Any dispute relating in any way to your visit to TravelVideoStore.com or to products you purchase through TravelVideoStore.com shall be submitted to confidential arbitration in Tampa, Florida, except that, to the extent you have in any manner violated or threatened to violate TravelVideoStore.com's intellectual property rights, TravelVideoStore.com may seek injunctive or other appropriate relief in any state or federal court in the state of Florida, and you consent to exclusive jurisdiction and venue in such courts. Arbitration under this agreement shall be conducted under the rules then prevailing of the American Arbitration Association. The arbitrator's award shall be binding and may be entered as a judgment in any court of competent jurisdiction. To the fullest extent permitted by applicable law, no arbitration under this Agreement shall be joined to an arbitration involving any other party subject to this Agreement, whether through class arbitration proceedings or otherwise. <BR><BR>



<b>SITE POLICIES, MODIFICATION, AND SEVERABILITY</b><BR><BR>



Please review our other policies posted on this site. These policies also govern your visit to TRAVELVIDEOSTORE.com. We reserve the right to make changes to our site, policies, and these Conditions of Use at any time. If any of these conditions shall be deemed invalid, void, or for any reason unenforceable, that condition shall be deemed severable and shall not affect the validity and enforceability of any remaining condition. <BR><BR>



<b>OUR ADDRESS </b><BR><BR>



TravelVideoStore.com<BR>

5420 Boran Drive<BR>

Tampa, FL 33610 <BR>

http://www.travelvideostore.com







</td>

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
