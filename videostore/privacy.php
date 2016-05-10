<?php
/*
  $Id: privacy.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRIVACY);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRIVACY));
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
            <!--<td class="pageHeading" align="right"><?php //echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>-->
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
<table><tr><td><img src="images/PrivacyGuarantee.gif" alt="" border="2">
</td><td class="main" ><b>Policy Dated: May 15th, 2004</b></td></tr></table>

TravelVideoStore.com knows that you care how information about you is used and shared, and we appreciate your trust that we will do so carefully and sensibly. This notice describes our privacy policy. <b>By visiting TravelVideoStore.com, you are accepting the practices described in this Privacy Notice. </b><BR><BR>

<b>What Personal Information About Customers Does TravelVideoStore.com Gather?</b><BR><BR>

The information we learn from customers helps us personalize and continually improve your shopping experience at TravelVideoStore.com. Here are the types of information we gather. <ul>

<LI><b>Information You Give Us:</b> We receive and store any information you enter on our Web site or give us in any other way.  You can choose not to provide certain information, but then you might not be able to take advantage of many of our features. We use the information that you provide for such purposes as responding to your requests, customizing future shopping for you, improving our stores, and communicating with you. </LI>

<LI><b>Automatic Information:</b> We receive and store certain types of information whenever you interact with us. For example, like many Web sites, we use "cookies," and we obtain certain types of information when your Web browser accesses TravelVideoStore.com. </LI>

<LI><b>E-mail Communications: </b> We collect your e-mail address for the purpose of providing and electronic reciept and confrimation of shipping to include trackingnumbers if applicable.  If you do not want to receive e-mail or other mail from us, please adjust your Customer Communication Preferences in your profile. </LI>

<LI><b>Information from Other Sources:</b> We might receive information about you from other sources and add it to our account information. Examples of this are from travel and tour operators when you have purchased a tour package, industry databases, and from friends that have elected to notify you about one of our products.</LI></UL>

<b>Does TravelVideoStore.com Share the Information It Receives?</b><BR><BR>

Information about our customers is an important part of our business, and we are not in the business of selling it to others. We share customer information only as described below:<ul>

<LI><B>Agents: </b>We employ other companies and individuals to perform functions on our behalf. Examples include fulfilling orders, delivering packages, sending postal mail and e-mail, removing repetitive information from customer lists, analyzing data, providing marketing assistance, providing search results and links (including paid listings and links), processing credit card payments, and providing customer service. They have access to personal information needed to perform their functions, but may not use it for other purposes. </li>

<LI><B>Business Transfers:</b> As we continue to develop our business, we might sell or buy stores, subsidiaries, or business units. In such transactions, customer information generally is one of the transferred business assets but remains subject to the promises made in any pre-existing Privacy Notice (unless, of course, the customer consents otherwise). Also, in the unlikely event that TravelVideoStore.com, Inc., or substantially all of its assets are acquired, customer information will of course be one of the transferred assets. </li>

<LI><B>Protection of TravelVideoStore.com and Others:</b> We release account and other personal information when we believe release is appropriate to comply with the law; enforce or apply our Conditions of Use and other agreements; or protect the rights, property, or safety of TravelVideoStore.com, our users, or others. This includes exchanging information with other companies and organizations for fraud protection and credit risk reduction. Obviously, however, this does not include selling, renting, sharing, or otherwise disclosing personally identifiable information from customers for commercial purposes in violation of the commitments set forth in this Privacy Notice. </li>

<LI><B>With Your Consent:</b> Other than as set out above, you will receive notice when information about you might go to third parties, and you will have an opportunity to choose not to share the information. </li></ul><BR>

<b>How Secure Is Information About Me? </b><BR><BR>

We work to protect the security of your information during transmission by using Secure Sockets Layer (SSL) software, which encrypts information you input. 
We reveal only the last five digits of your credit card numbers when confirming an order. Of course, we transmit the entire credit card number to the appropriate credit card company during order processing. <BR><BR>
It is important for you to protect against unauthorized access to your password and to your computer. Be sure to sign off when finished using a shared computer. <BR><BR>

<b>Which Information Can I Access?</b><BR><BR>

TravelVideoStore.com gives you access to a broad range of information about your account and your interactions with TravelVideoStore.com for the limited purpose of viewing and, in certain cases, updating that information, which will change as our Web site evolves. <BR><BR>

<B>What Choices Do I Have? </b><BR><BR>

As discussed above, you can always choose not to provide information, even though it might be needed to make a purchase or to take advantage of such TravelVideoStore.com features as Wish Lists, Save Shopping Carts and Customer Reviews. <BR><BR>

If you do not want to receive e-mail or other mail from us, please adjust your Customer Communication Preferences. (If you do not want to receive Conditions of Use and other legal notices from us, such as this Privacy Notice, those notices will still govern your use of TravelVideoStore.com, and it is your responsibility to review them for changes.) <BR><BR>

The Help portion of the toolbar on most browsers will tell you how to prevent your browser from accepting new cookies, how to have the browser notify you when you receive a new cookie, or how to disable cookies altogether.<BR><BR>

<b>Children</b><BR><BR>

TravelVideoStore.com does not sell products for purchase by children. We sell children's products for purchase by adults. If you are under 18, you may use TravelVideoStore.com only with the involvement of a parent or guardian. <BR><BR>

<b>Safe Harbor</b><BR><BR>

TravelVideoStore.com is a participant in the Safe Harbor program developed by the U.S. Department of Commerce and the European Union. We have certified that we adhere to the Safe Harbor Privacy Principles agreed upon by the U.S. and the E.U.  If you would like to contact TravelVideoStore.com directly about the Safe Harbor program, please send an e-mail to safeharbor@travelvideostore.com.<BR><BR>

<b>Conditions of Use, Notices, and Revisions</b><BR><BR> 

If you choose to visit TravelVideoStore.com, your visit and any dispute over privacy is subject to this Notice and our Conditions of Use, including limitations on damages, arbitration of disputes, and application of the law of the state of Florida. If you have any concern about privacy at TravelVideoStore.com, please contact us with a thorough description, and we will try to resolve it at <a href="privacy@travelvideostore.com">Privacy@TravelVideoStore.com</a>. <BR><BR>

Our business changes constantly, and our Privacy Notice and the Conditions of Use will change also. We may e-mail periodic reminders of our notices and conditions, unless you have instructed us not to, but you should check our Web site frequently to see recent changes. Unless stated otherwise, our current Privacy Notice applies to all information that we have about you and your account. We stand behind the promises we make, however, and will never materially change our policies and practices to make them less protective of customer information collected in the past without the consent of affected customers. 



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
