<?php
/*
  $Id: mail.php,v 1.31 2003/06/20 00:37:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

define('TEXT_EMAIL_BUTTON_TEXT', '<p><HR><b><font color="red">The Back Button has been DISABLE while HTML WYSIWG Editor is turned ON,</b></font> WHY? - Because if you click the back button to edit your HTML email, The PHP (php.ini - "Magic Quotes = On") will automatically add "\\\\\\\" backslashes everywhere Double Quotes " appear (HTML uses them in Links, Images and More) and this destorts the HTML and the pictures will dissapear once you submit the email again, If you turn OFF WYSIWYG Editor in Admin the HTML Ability of osCommerce is also turned OFF and the back button will re-appear. A fix for this HTML and PHP issue would be nice if someone knows a solution Iv\'e tried.<br><br><b>If you really need to Preview your emails before sending them, use the Preview Button located on the WYSIWYG Editor.<br><HR>');
define('TEXT_EMAIL_BUTTON_HTML', '<p><HR><b><font color="red">HTML is currently Disabled!</b></font><br><br>If you want to send HTML email, Enable WYSIWYG Editor for Email in: Admin-->Configuration-->WYSIWYG Editor-->Options<br>');
define('NOTICE_EMAIL_SENT_TO', 'Notice: Email sent to: %s');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if ( ($action == 'send_email_to_user') && isset($HTTP_POST_VARS['customers_email_address']) && !isset($HTTP_POST_VARS['back_x']) ) {
    switch ($HTTP_POST_VARS['customers_email_address']) {
      case '***':
        $mail_query = tep_db_query("select vendors_name, vendors_email from " . TABLE_VENDORS." where vendors_email!=''");
        $mail_sent_to = 'All Vendors';
        break;
      case '**D':
        $mail_query = tep_db_query("select vendors_name, vendors_email from " . TABLE_VENDORS . " where direct_purchase = '1'");
        $mail_sent_to = 'All Direct Purchase';
        break;
      case '**C':
        $mail_query = tep_db_query("select vendors_name, vendors_email from " . TABLE_VENDORS . " where consignment = '1'");
        $mail_sent_to = 'All Cosnignment';
        break;
      case '**R':
        $mail_query = tep_db_query("select vendors_name, vendors_email from " . TABLE_VENDORS . " where royalty = '1'");
        $mail_sent_to = 'All Royalty';
        break;

      default:
        $customers_email_address = tep_db_prepare_input($HTTP_POST_VARS['customers_email_address']);

        $mail_query = tep_db_query("select vendors_name, vendors_email from " . TABLE_VENDORS . " where vendors_email = '" . tep_db_input($customers_email_address) . "'");
        $mail_sent_to = $HTTP_POST_VARS['customers_email_address'];
        break;
    }

    $from = tep_db_prepare_input($HTTP_POST_VARS['from']);
    $subject = tep_db_prepare_input($HTTP_POST_VARS['subject']);
    $message = tep_db_prepare_input($HTTP_POST_VARS['message']);

    //Let's build a message object using the email class
    $mimemessage = new email(array('X-Mailer: osCommerce'));
    // add the message to the object
    
// MaxiDVD Added Line For WYSIWYG HTML Area: BOF (Send TEXT Email when WYSIWYG Disabled)
    if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable') {
    $mimemessage->add_text($message);
    } else {
    $mimemessage->add_html($message);
    }
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send HTML Email when WYSIWYG Enabled)

    $mimemessage->build_message();
    while ($mail = tep_db_fetch_array($mail_query)) {
      $mimemessage->send($mail['vendors_name'], $mail['vendors_email'], '', $from, $subject);
    }

 //   tep_redirect(tep_href_link('vendor_mail.php', 'mail_sent_to=' . urlencode($mail_sent_to)));
  }

  if ( ($action == 'preview') && !isset($HTTP_POST_VARS['customers_email_address']) ) {
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
  }

  if (isset($HTTP_GET_VARS['mail_sent_to'])) {
    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $HTTP_GET_VARS['mail_sent_to']), 'success');
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

       <script language="Javascript1.2"><!-- // load htmlarea
// MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 HTML Email HTML - <head>
      _editor_url = "<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>htmlarea/";  // URL to htmlarea files
        var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
         if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
          if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
           if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
       <?php if (HTML_AREA_WYSIWYG_BASIC_EMAIL == 'Basic'){ ?>  if (win_ie_ver >= 5.5) {
       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_basic.js"');
       document.write(' language="Javascript1.2"></scr' + 'ipt>');
          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
       <?php } else{ ?> if (win_ie_ver >= 5.5) {
       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_advanced.js"');
       document.write(' language="Javascript1.2"></scr' + 'ipt>');
          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
       <?php }?>
// --></script>
       <script language="JavaScript" src="htmlarea/validation.js"></script>
       <script language="JavaScript">
<!-- Begin
       function init() {
define('customers_email_address', 'string', 'Customer or Newsletter Group');
}
//  End -->
</script>
</head>
<body OnLoad="init()" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Send Email To Vendors</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ( ($action == 'preview') && isset($HTTP_POST_VARS['customers_email_address']) ) {
    switch ($HTTP_POST_VARS['customers_email_address']) {
      case '***':
        $mail_sent_to = 'All Vendors';
        break;
      case '**D':
        $mail_sent_to = 'All Direct Purchase';
        break;
      case '**C':
        $mail_sent_to = 'All Consignment';
        break;
      case '**R':
        $mail_sent_to = 'All Royalty';
        break;
      default:
        $mail_sent_to = $HTTP_POST_VARS['customers_email_address'];
        break;
    }
?>
          <tr><?php echo tep_draw_form('mail', 'vendor_mail.php', 'action=send_email_to_user'); ?>
            <td><table border="0" width="100%" cellpadding="0" cellspacing="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b>Vendor:</b><br><?php echo $mail_sent_to; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b>From:</b><br><?php echo htmlspecialchars(stripslashes($HTTP_POST_VARS['from'])); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b>Subject:</b><br><?php echo htmlspecialchars(stripslashes($HTTP_POST_VARS['subject'])); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b>Message:</b><br><?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable') { echo (stripslashes($HTTP_POST_VARS['message'])); } else { echo htmlspecialchars(stripslashes($HTTP_POST_VARS['message'])); } ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td>
<?php
/* Re-Post all POST'ed variables */
    reset($HTTP_POST_VARS);
    while (list($key, $value) = each($HTTP_POST_VARS)) {
      if (!is_array($HTTP_POST_VARS[$key])) {
        echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
      }
    }
?>
                    <tr>
                    <td align="right"><?php echo '<a href="vendor_mail.php">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' . tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>
                    </tr>
                    <td class="smallText">
                <?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable'){echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="back"');
                } ?><?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable') {echo(TEXT_EMAIL_BUTTON_HTML);
                 } else { echo(TEXT_EMAIL_BUTTON_TEXT); } ?>
                    </td>
                  </tr>
                </table></td>
             </tr>
            </table></td>
          </form></tr>
          
          
<?php
  } else {
?>
          <tr><?php echo tep_draw_form('mail', 'vendor_mail.php', 'action=preview'); ?>
            <td><table border="0" cellpadding="0" cellspacing="2">
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
<?php
    $customers = array();
    $customers[] = array('id' => '', 'text' => 'Select Vendor');
    $customers[] = array('id' => '***', 'text' => 'All Vendors');
    $customers[] = array('id' => '**D', 'text' => 'All Direct Purchase');
    $customers[] = array('id' => '**C', 'text' => 'All Consignment');
    $customers[] = array('id' => '**R', 'text' => 'All Royalty');
    $mail_query = tep_db_query("select vendors_email , vendors_name from " . TABLE_VENDORS . " where vendors_email!='' order by vendors_name");
    while($customers_values = tep_db_fetch_array($mail_query)) {
      $customers[] = array('id' => $customers_values['vendors_email'],
                           'text' => $customers_values['vendors_name'] . ' (' . $customers_values['vendors_email'] . ')');
    }
?>
              <tr>
                <td class="main">Vendor:</td>
                <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, (isset($HTTP_GET_VARS['customer']) ? $HTTP_GET_VARS['customer'] : ''));?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main">From:</td>
                <td><?php echo tep_draw_input_field('from', 'suppliersupport@travelvideostore.com'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main">Subject:</td>
                <td><?php echo tep_draw_input_field('subject'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td valign="top" class="main">Message:</td>
                <td><?php echo tep_draw_textarea_field('message', 'soft', '60', '15'); ?></td>

<?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable') { ?>
          <script language="JavaScript1.2" defer>
// MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 HTML Email HTML - <body>
           var config = new Object();  // create new config object
           config.width = "<?php echo EMAIL_AREA_WYSIWYG_WIDTH; ?>px";
           config.height = "<?php echo EMAIL_AREA_WYSIWYG_HEIGHT; ?>px";
           config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
           config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
           editor_generate('message',config);
<?php }
// MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 HTML Email HTML - <body>
   ?>
          </script>
                
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
              
              
                <td colspan="2" align="right">
                 <?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable'){ echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL, 'onClick="validate();return returnVal;"');
                   } else {
                echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); }?>
                </td>
                
                
              </tr>
            </table></td>
          </form></tr>
<?php
  }
?>
<!-- body_text_eof //-->
        </table></td>
      </tr>
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
