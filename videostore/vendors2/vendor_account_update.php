<?php
ob_start();
/*
  $Id: login.php,v 1.80 2003/06/05 23:28:24 hpdl Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $error = false;
  session_start();
  if (!isset($_SESSION["vendors_id"]) && !($_SESSION["vendors_id"] <> ""))
  {
	tep_redirect("index.php");
	exit;
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

    }
  require(DIR_WS_LANGUAGES .'english/'.  FILENAME_VENDOR_ACCOUNT_UPDATE);
  require(DIR_WS_CLASSES . 'message_stack.php');
  require(DIR_WS_LANGUAGES .'english/'. FILENAME_VENDOR_PRODUCTS_DISP);


?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="../stylesheet.css">
<link rel="stylesheet" type="text/css" href="./includes/main.css">
<?php require('../includes/form_check.js.php'); ?>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->

<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="1" cellpadding="1">
<tr>
	<td width="100%" class="headerNavigation">&nbsp;<a href="index.php" class="headerNavigation"><b><?php echo TXT_HOME?></b></a>&nbsp;-&nbsp;<a href="vendor_account_update.php" class="headerNavigation"><?php echo TXT_EDIT_ACCOUNT;?></a></td>
</tr>
</table>

<table border="0" width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php
	tep_session_start();
	if (tep_session_is_registered('vendors_id'))
		require('./includes/column_left.php');
?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('login', tep_href_link("vendors/".FILENAME_VENDOR_ACCOUNT_UPDATE, 'action=update', 'NONSSL'), 'post', 'onSubmit="return check_form(account_edit);"'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="80%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading1"><?php echo HEADING_TITLE; ?></td>
                      </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="60%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo MY_ACCOUNT_TITLE; ?></b></td>
              </tr>
            </table></td>
          </tr>

<?php
	$messageStack = new messageStack();
	if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'update'))
	{

	 tep_db_query("update " . TABLE_VENDORS . " set vendors_name = '$HTTP_POST_VARS[name]', vendors_contact='$HTTP_POST_VARS[contact]',vendors_phone1='$HTTP_POST_VARS[phone1]',vendors_phone2='$HTTP_POST_VARS[phone2]',vendors_bill_addr1='$HTTP_POST_VARS[bill_addr1]',vendors_bill_addr2='$HTTP_POST_VARS[bill_addr2]',vendors_bill_city='$HTTP_POST_VARS[bill_city]',vendors_bill_state='$HTTP_POST_VARS[bill_state]',vendors_bill_zip='$HTTP_POST_VARS[bill_zip]',vendors_bill_country='$HTTP_POST_VARS[bill_country]',vendors_ship_addr1='$HTTP_POST_VARS[ship_addr1]',vendors_ship_addr2='$HTTP_POST_VARS[ship_addr2]',vendors_ship_city='$HTTP_POST_VARS[ship_city]',vendors_ship_state='$HTTP_POST_VARS[ship_state]',vendors_ship_zip='$HTTP_POST_VARS[ship_zip]',vendors_ship_country='$HTTP_POST_VARS[ship_country]',vendors_fax='$HTTP_POST_VARS[fax]',vendors_email='$HTTP_POST_VARS[email]',vendors_url='$HTTP_POST_VARS[url]',vendors_acct_num='$HTTP_POST_VARS[acc_num]',vendors_terms='$HTTP_POST_VARS[terms]',vendors_comments='$HTTP_POST_VARS[comments]', `sale_email`='{$_POST['sale_email']}', `sale_email_address`='{$_POST['sale_email_address']}', `po_email_address`='{$_POST['po_email_address']}' where vendors_id = '" . $vendors_id . "'");
?>

		<tr>
        <td>
        	<table border="0" width="60%" cellspacing="0" cellpadding="2" bgcolor="#99FF00">
              <tr align="LEFT">
                <td class="main"><?php echo TXT_UPDATE_SUCCESS; ?></td>
              </tr>
            </table>
           </td>
          </tr>
<?
		}
?>
<?

  $account_query = tep_db_query("select * from " . TABLE_VENDORS . " where vendors_id = '" . (int)$vendors_id . "'");
  $account = tep_db_fetch_array($account_query);

  $vendor_qry="select * from vendors_terms";
  $vendor_terms_query = tep_db_query($vendor_qry);
  $vend_terms=array();
  $i=0;
  while($row = tep_db_fetch_array($vendor_terms_query))
  {

  		if($i==0)
  			$vend_terms[]= array('id' => $i, 'text' => 'Select Terms');
  		else
  			$vend_terms[]= array('id' => $i, 'text' => $row['vendors_terms']);

  		$i++;
  }

?>

          <tr>
            <td><table border="0" width="60%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" cellspacing="2" cellpadding="2">
                  <tr>
					  <td class="infoBoxContents" colspan=2><b><?php echo TXT_PERSONAL_INFO; ?></b></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_VENDORS_NAME; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('name', $account['vendors_name']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_VENDORS_CONTACT; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('contact', $account['vendors_contact']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_VENDORS_PHONE1; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('phone1', $account['vendors_phone1']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_VENDORS_PHONE2; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('phone2', $account['vendors_phone2']); ?></td>
                  </tr>
                  <tr>
					  <td class="infoBoxContents" colspan=2><b><?php echo TXT_BILLING_ADDRESS; ?></b></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_ADDRESS1; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('bill_addr1', $account['vendors_bill_addr1']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_ADDRESS2; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('bill_addr2', $account['vendors_bill_addr2']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_CITY; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('bill_city', $account['vendors_bill_city']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_STATE; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('bill_state', $account['vendors_bill_state']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_ZIP; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('bill_zip', $account['vendors_bill_zip']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_COUNTRY; ?></td>
					<td class="main"><?php echo tep_get_country_list('bill_country',$account['vendors_bill_country']); ?></td>
                  </tr>
                  <tr>
					  <td class="infoBoxContents" colspan=2><b><?php echo TXT_SHIPPING_ADDRESS; ?></b></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_ADDRESS1; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('ship_addr1', $account['vendors_ship_addr1']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_ADDRESS2; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('ship_addr2', $account['vendors_ship_addr2']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_CITY; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('ship_city', $account['vendors_ship_city']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_STATE; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('ship_state', $account['vendors_ship_state']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_ZIP; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('ship_zip', $account['vendors_ship_zip']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_COUNTRY; ?></td>
					<td class="main"><?php echo tep_get_country_list('ship_country',$account['vendors_ship_country']); ?></td>
                  </tr>
                  <tr>
					  <td class="infoBoxContents" colspan=2><b><?php echo TXT_GENERAL_INFO; ?></b></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_COMMENTS; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('comments', $account['vendors_comments']) . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></td>
                  </tr>

        		  <tr>
                    <td class="main"><?php echo TXT_FAX; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('fax', $account['vendors_fax']) . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_EMAIL; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('email', $account['vendors_email']) . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_URL; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('url', $account['vendors_url']) . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_ACCT_NUM; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('acc_num', $account['vendors_acct_num']) . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo TXT_TERMS; ?></td>
                    <!-- <td class="main"><?php echo tep_draw_input_field('terms', $vendor_terms['vendors_terms']) . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></td>-->
                    <td class="main"><?php echo tep_draw_pull_down_menu_new('terms',$vend_terms,$account['vendors_terms']); ?> </td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo 'Send e-mail on sale?'; ?></td>
                    <td class="main"><?php echo tep_draw_pull_down_menu('sale_email', array(array('id' => '0', 'text' => 'No'), array('id' => '1', 'text' => 'Yes')), $account['sale_email']); ?> </td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo 'Sale e-mail address:'; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('sale_email_address', $account['sale_email_address']); ?> </td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo 'Purchase order e-mail address:'; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('po_email_address', $account['po_email_address']); ?> </td>
                  </tr>
	              	<tr>
                		<td align="right"><?php echo tep_image_submit_vendors(DIR_WS_INCLUDES_LOCAL.'images/button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
	              	</tr>
	             </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
</table>
<br>
<!-- footer //-->
<?php require('footer.php'); ?>
<!-- footer_eof //-->