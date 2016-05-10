<?php
/*
  $Id: Ask a Question.php,v 1.36 2003/02/17 07:55:10 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
    if ( $_SERVER['REMOTE_ADDR'] == '96.254.126.27' ) {
           ini_set('error_reporting', ALL & E_STRICT & ~E_DEPRECATED & ~E_NOTICE);
           ini_set('display_errors', '1');
           ini_set('display_startup_errors', 'On');
           }
  if (tep_session_is_registered('customer_id')) {
    $account = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
    $account_values = tep_db_fetch_array($account);
  } elseif (ALLOW_GUEST_TO_TELL_A_FRIEND == 'false') {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  $error = false;
  tep_session_register('osCsid1');
  if(!empty($HTTP_GET_VARS['osCsid']) && ($HTTP_GET_VARS['osCsid'] !=""))
 {
  $osCsid1 = $HTTP_GET_VARS['osCsid'];
 }

	//VISUAL VERIFY CODE start
	require(DIR_WS_FUNCTIONS . 'visual_verify_code.php');
    $visualerror = false;
	$code_query = tep_db_query("select code from visual_verify_code where oscsid = '" . $osCsid1 . "'");
	$code_array = tep_db_fetch_array($code_query);
	$code = $code_array['code'];

	tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . $vvcode_oscsid . "'"); //remove the visual verify code associated with this session to clean database and ensure new results

	if(isset($HTTP_POST_VARS['visual_verify_code'])){
	$user_entered_code = $HTTP_POST_VARS['visual_verify_code'];
	if (!(strcasecmp($user_entered_code, $code) == 0)) { //make the check case insensitive
   // if($user_entered_code != $code){
        $visualerror = true;
		$error = true;
		$user_entered_code ='';unset($HTTP_POST_VARS['visual_verify_code']);
		$messageStack->add('contact', VISUAL_VERIFY_CODE_ENTRY_ERROR);
	}
	//VISUAL VERIFY CODE stop
    }
  $valid_product = false;
  if (isset($HTTP_GET_VARS['products_id'])) {
    $product_info_query = tep_db_query("select pd.products_name, p.products_model,  p.products_image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $valid_product = (tep_db_num_rows($product_info_query) > 0);
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ASK_QUESTION);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ASK_QUESTION, 'send_to=' . $HTTP_GET_VARS['send_to'] . '&products_id=' . $HTTP_GET_VARS['products_id']));
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
<?php
  if ($valid_product == false) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE_ERROR; ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ERROR_INVALID_PRODUCT; ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
    $product_info = tep_db_fetch_array($product_info_query);
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><b><?php echo sprintf(HEADING_TITLE, $product_info['products_name']); ?> - (<?php echo $product_info['products_model'] ?>)</B></td>
            <td class="pageHeading" align="center"><?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
    

    if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process') && !tep_validate_email(trim($HTTP_POST_VARS['friendemail']))) {
      $friendemail_error = true;
      $error = true;
    } else {
      $friendemail_error = false;
    }

    if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process') && empty($HTTP_POST_VARS['friendname'])) {
      $friendname_error = true;
      $error = true;
    } else {
      $friendname_error = false;
    }

    if (tep_session_is_registered('customer_id')) {
      $from_name = $account_values['customers_firstname'] . ' ' . $account_values['customers_lastname'];
      $from_email_address = $account_values['customers_email_address'];
    } else {
      $from_name = $HTTP_POST_VARS['yourname'];
      $from_email_address = $HTTP_POST_VARS['from'];
    }
	  
    if (!tep_session_is_registered('customer_id')) {
      if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process') && !tep_validate_email(trim($from_email_address))) {
        $fromemail_error = true;
        $error = true;
      } else {
        $fromemail_error = false;
      }
    }

    if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process') && empty($from_name)) {
      $fromname_error = true;
      $error = true;
    } else {
      $fromname_error = false;
    }

    if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process') && ($error == false)) {
      $email_subject = sprintf(TEXT_EMAIL_SUBJECT, $from_name, STORE_NAME);
      $email_body = sprintf(TEXT_EMAIL_INTRO, $HTTP_POST_VARS['friendname'], $from_name, $HTTP_POST_VARS['products_name'], $HTTP_POST_VARS['products_model']) . "\n\n";

      if (tep_not_null($HTTP_POST_VARS['yourmessage'])) {
        $email_body .= $HTTP_POST_VARS['yourmessage'] . "\n\n";
      }

      $email_body .= sprintf(TEXT_EMAIL_LINK, tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id'])) . "\n\n" .
                     sprintf(TEXT_EMAIL_SIGNATURE, STORE_NAME . "\n" . HTTP_SERVER . DIR_WS_CATALOG . "\n");

//      tep_mail($HTTP_POST_VARS['friendname'], $HTTP_POST_VARS['friendemail'], $email_subject, stripslashes($email_body), '', $from_email_address);
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $email_subject, stripslashes($email_body), '', $from_email_address);
?>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo sprintf(TEXT_EMAIL_SUCCESSFUL_SENT, stripslashes($HTTP_POST_VARS['products_name']), $HTTP_POST_VARS['friendemail']); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id']) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
      </tr>
<?php
    } else {
      if (tep_session_is_registered('customer_id')) {
        $your_name_prompt = $account_values['customers_firstname'] . ' ' . $account_values['customers_lastname'];
        $your_email_address_prompt = $account_values['customers_email_address'];
		 if ($visualerror == true) $visualerror1 = '&nbsp;<span class="errorText">' . VISUAL_VERIFY_CODE_ENTRY_ERROR . '</span>';     
      } else {
        $your_name_prompt = tep_draw_input_field('yourname', (($fromname_error == true) ? $HTTP_POST_VARS['yourname'] : $HTTP_GET_VARS['yourname']));
        if ($fromname_error == true) $your_name_prompt .= '&nbsp;<span class="errorText">' . ERROR_FROM_NAME . '</span>';
        $your_email_address_prompt = tep_draw_input_field('from', (($fromemail_error == true) ? $HTTP_POST_VARS['from'] : $HTTP_GET_VARS['from']));
        if ($fromemail_error == true) $your_email_address_prompt .= '&nbsp;<span class="errorText">' . ERROR_FROM_ADDRESS . '</span>';
        if ($visualerror == true) $visualerror1 = '&nbsp;<span class="errorText">' . VISUAL_VERIFY_CODE_ENTRY_ERROR . '</span>';     
	 }
?>
      <tr>
        <td><?php echo tep_draw_form('email_friend', tep_href_link(FILENAME_ASK_QUESTION, 'action=process&products_id=' . $HTTP_GET_VARS['products_id'])) . tep_draw_hidden_field('products_name', $product_info['products_name']); ?><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="formAreaTitle"><?php echo FORM_TITLE_CUSTOMER_DETAILS; ?></td>
          </tr>
          <tr>
            <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
              <tr>
                <td class="main"><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><?php echo FORM_FIELD_CUSTOMER_NAME; ?></td>
                    <td class="main"><?php echo $your_name_prompt; ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo FORM_FIELD_CUSTOMER_EMAIL; ?></td>
                    <td class="main"><?php echo $your_email_address_prompt; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
<!--  VISUAL VERIFY CODE start -->
      <tr>
        <td class="main"><b><?php echo VISUAL_VERIFY_CODE_CATEGORY; ?></b></td>
      </tr>
      <tr>
        <td class="main"><?php echo VISUAL_VERIFY_CODE_TEXT_INTRO; ?><br></td>
      </tr>
	  
	  <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo VISUAL_VERIFY_CODE_TEXT_INSTRUCTIONS; ?></td>
                <td class="main"><?php echo tep_draw_input_field('visual_verify_code') . '&nbsp;' . '<span class="inputRequirement">' . VISUAL_VERIFY_CODE_ENTRY_TEXT . '</span>'; ?></td>

                <td class="main">
                  <?php
                      //can replace the following loop with $visual_verify_code = substr(str_shuffle (VISUAL_VERIFY_CODE_CHARACTER_POOL), 0, rand(3,6)); if you have PHP 4.3
                    $visual_verify_code = ""; 
                    for ($i = 1; $i <= rand(3,6); $i++){
                          $visual_verify_code = $visual_verify_code . substr(VISUAL_VERIFY_CODE_CHARACTER_POOL, rand(0, strlen(VISUAL_VERIFY_CODE_CHARACTER_POOL)-1), 1);
                     }
                     $vvcode_oscsid = $HTTP_GET_VARS['osCsid'];
                     tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . $vvcode_oscsid . "'");
                     $sql_data_array = array('oscsid' => $vvcode_oscsid, 'code' => $visual_verify_code);
                     tep_db_perform(TABLE_VISUAL_VERIFY_CODE, $sql_data_array);
                     $visual_verify_code = "";
                     echo('<img src="' . FILENAME_VISUAL_VERIFY_CODE_DISPLAY . '?vvc=' . $vvcode_oscsid . '">');
                  ?><br>
				  <?php echo $visualerror1;?>
                </td>
                <td class="main"><?php echo VISUAL_VERIFY_CODE_BOX_IDENTIFIER; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<!--
  VISUAL VERIFY CODE stop   -->

          <tr>
           <td class="main">
 <?php echo tep_draw_hidden_field('friendname', STORE_OWNER,  (($friendname_error == true) ? $HTTP_POST_VARS['friendname'] : $HTTP_GET_VARS['friendname']));
 if ($friendname_error == true) echo '&nbsp;<span class="errorText">' . ERROR_FROM_NAME . '</span>';?>
<?php echo tep_draw_hidden_field('friendemail', STORE_OWNER_EMAIL_ADDRESS, (($friendemail_error == true) ? $HTTP_POST_VARS['friendemail'] : $HTTP_GET_VARS['send_to']));
 if ($friendemail_error == true) echo ERROR_FROM_ADDRESS;?></td>

          </tr> 
          <td class="formAreaTitle"><br><?php echo FORM_TITLE_FRIEND_MESSAGE; ?></td>
          </tr>
          <tr>
            <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
              <tr>
                <td><?php echo tep_draw_textarea_field('yourmessage', 'soft', 40, 8);?>
				
				</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id']) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right" class="main"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></form></td>
      </tr>
<?php
    }
  }
?>
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