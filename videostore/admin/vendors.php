<?php

/*
  $Id: vendors.php,v 1.00 2003/06/26 23:21:48 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2003 Fernando Borcel
  Copyright (c) 2004 Joseph Passavanti

  Released under the GNU General Public License
*/

  require('includes/application_top.php');


  switch ($HTTP_POST_VARS['action']) {
    case 'insert':
    case 'save':
      $vendors_id = tep_db_prepare_input($HTTP_POST_VARS['vID']);
      $vendors_name = tep_db_prepare_input($HTTP_POST_VARS['vendors_name']);
      $vendors_contact = tep_db_prepare_input($HTTP_POST_VARS['vendors_contact']);
      $vendors_phone1 = tep_db_prepare_input($HTTP_POST_VARS['vendors_phone1']);
      $vendors_phone2 = tep_db_prepare_input($HTTP_POST_VARS['vendors_phone2']);
      $vendors_bill_addr1 = tep_db_prepare_input($HTTP_POST_VARS['vendors_bill_addr1']);
      $vendors_bill_addr2 = tep_db_prepare_input($HTTP_POST_VARS['vendors_bill_addr2']);
      $vendors_bill_city = tep_db_prepare_input($HTTP_POST_VARS['vendors_bill_city']);
      $vendors_bill_state = tep_db_prepare_input($HTTP_POST_VARS['vendors_bill_state']);
      $vendors_bill_zip = tep_db_prepare_input($HTTP_POST_VARS['vendors_bill_zip']);
      $vendors_bill_country = tep_db_prepare_input($HTTP_POST_VARS['vendors_bill_country']);
      $vendors_ship_addr1 = tep_db_prepare_input($HTTP_POST_VARS['vendors_ship_addr1']);
      $vendors_ship_addr2 = tep_db_prepare_input($HTTP_POST_VARS['vendors_ship_addr2']);
      $vendors_ship_city = tep_db_prepare_input($HTTP_POST_VARS['vendors_ship_city']);
      $vendors_ship_state = tep_db_prepare_input($HTTP_POST_VARS['vendors_ship_state']);
      $vendors_ship_zip = tep_db_prepare_input($HTTP_POST_VARS['vendors_ship_zip']);
      $vendors_ship_country = tep_db_prepare_input($HTTP_POST_VARS['vendors_ship_country']);
      $vendors_fax = tep_db_prepare_input($HTTP_POST_VARS['vendors_fax']);
      $vendors_email = tep_db_prepare_input($HTTP_POST_VARS['vendors_email']);
      $vendors_acct_num = tep_db_prepare_input($HTTP_POST_VARS['vendors_acct_num']);
      $vendors_terms = tep_db_prepare_input($HTTP_POST_VARS['vendors_terms']);
      $vendors_url = tep_db_prepare_input($HTTP_POST_VARS['vendors_url']);
      $vendors_comments = tep_db_prepare_input($HTTP_POST_VARS['vendors_comments']);
      $vendors_username = tep_db_prepare_input($HTTP_POST_VARS['vendors_username']); // NEWLY INSERTED USERNAME
      $sale_email         = tep_db_prepare_input($_POST['sale_email']);
      $email_detail       = tep_db_prepare_input($_POST['email_detail']);
      $sale_email_address = tep_db_prepare_input($_POST['sale_email_address']);
      $allow_product_edit = tep_db_prepare_input($_POST['allow_product_edit']);
      $po_email_address   = tep_db_prepare_input($_POST['po_email_address']);
      
      $sql_data_array = array('vendors_name' => $vendors_name,
      			      'vendors_contact' => $vendors_contact,
                              'vendors_phone1' => $vendors_phone1,
                              'vendors_phone2' => $vendors_phone2,
                              'vendors_bill_addr1' => $vendors_bill_addr1,
                              'vendors_bill_addr2' => $vendors_bill_addr2,
                              'vendors_bill_city' => $vendors_bill_city,
                              'vendors_bill_state' => $vendors_bill_state,
                              'vendors_bill_zip' => $vendors_bill_zip,
                              'vendors_bill_country' => $vendors_bill_country,
                              'vendors_ship_addr1' => $vendors_ship_addr1,
                              'vendors_ship_addr2' => $vendors_ship_addr2,
                              'vendors_ship_city' => $vendors_ship_city,
                              'vendors_ship_state' => $vendors_ship_state,
                              'vendors_ship_zip' => $vendors_ship_zip,
                              'vendors_ship_country' => $vendors_ship_country,
                              'vendors_fax' => $vendors_fax,
                              'vendors_email' => $vendors_email,
                              'vendors_acct_num' => $vendors_acct_num,
                              'vendors_terms' => $vendors_terms,
                              'vendors_url' => $vendors_url,
                              'vendors_comments' => $vendors_comments,
                              'vendors_username' => $vendors_username, // NEWLY INSERTED USERNAME
                              'vendors_password' => $vendors_password, // NEWLY INSERTED PASSWORD
                              'sale_email' => $sale_email,
                              'email_detail' => $email_detail,
                              'sale_email_address' => $sale_email_address,
                              'allow_product_edit' => $allow_product_edit,
                              'po_email_address' => $po_email_address,
                              'direct_purchase' => $_POST['direct_purchase'],
                              'consignment' => $_POST['consignment'],
                              'royalty' => $_POST['royalty']
      );

      if ($HTTP_POST_VARS['action'] == 'insert') {
        $insert_sql_data = array('date_added' => 'now()');
        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
        tep_db_perform(TABLE_VENDORS, $sql_data_array);
        $vendors_id = tep_db_insert_id();
      } elseif ($HTTP_POST_VARS['action'] == 'save') {
        $update_sql_data = array('last_modified' => 'now()');
        $sql_data_array = array_merge($sql_data_array, $update_sql_data);
        tep_db_perform(TABLE_VENDORS, $sql_data_array, 'update', "vendors_id = '" . tep_db_input($vendors_id) . "'");
      }

      if (USE_CACHE == 'true') {
        tep_reset_cache_block('vendors');
      }

      tep_redirect('vendors.php?vID=' . $vendors_id);
      break;
  }

      if (intval($_GET['vID']) != 0) {
		$sql_query = "select * from vendors where vendors_id=".$_GET[vID];
		$vendors = tep_db_fetch_array(tep_db_query($sql_query));
      }

      if ($_GET[action]=='delete') {
	tep_db_query("delete from vendors where vendors_id='".$_GET[vID]."'");
	tep_db_query("delete from products_to_vendors where vendors_id='".$_GET[vID]."'");
	tep_redirect("vendors.php?msg=Vendor has been deleted...");
	}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
						<td class="smallText" align="right"><?php echo 'Select Vendor:'; ?><?php echo tep_draw_form('vendors_report', FILENAME_VENDORS,'','get') . tep_draw_pull_down_menu('vID', tep_get_vendors(),'','onChange="this.form.submit()";');?></form></td>
					</tr>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
	<tr><Td colspan="5" align="center" style="color:red"><b><? if (!empty($msg)) echo $msg?></b></td></tr>
      <tr>
<form action="vendors.php" method="post">
	<input type="hidden" name="vID" value="<?=$_GET[vID]?>" />
<?
if (intval($_GET[vID])!=0) {?>
	<input type="hidden" name="action" value="save" />
	<?} else {?>
	<input type="hidden" name="action" value="insert" />
<?}?>
        <td>
		<table border="0" width="80%" align="center" cellspacing="0" cellpadding="0">
		<tr>
			<td width="50%" valign="top">
				<table cellspacing="5" cellpadding="0" border="0" width="100%">
				<tr><td class="smallText">Vendors Name:</td><td><input type="text" name="vendors_name" value="<?=$vendors[vendors_name]?>" style="width:250px;" /></td></tr>				
				<tr><td class="smallText">Vendors Contact:</td><td><input type="text" name="vendors_contact" value="<?=$vendors[vendors_contact]?>" style="width:250px;" /></td></tr>
				<tr><td class="smallText">Vendors Fax:</td><td><input type="text" name="vendors_fax" value="<?=$vendors[vendors_fax]?>" style="width:250px;" /></td></tr>
				<tr><td><b>Billing Address</b></td></tr>
				<tr><td colspan="2"><hr></td></tr>	
				<tr>
					<td colspan="2">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr><td class="smallText" width="157">Street Address 1:</td><td><input type="text" name="vendors_bill_addr1" value="<?=$vendors[vendors_bill_addr1]?>" style="width:250px;" /></td></tr>
						<tr><td class="smallText">Street Address 2:</td><td><input type="text" name="vendors_bill_addr2" value="<?=$vendors[vendors_bill_addr2]?>" style="width:250px;" /></td></tr>
						<tr><td class="smallText">City:</td><td><input type="text" name="vendors_bill_city" value="<?=$vendors[vendors_bill_city]?>" style="width:250px;" /></td></tr>
						<tr><td class="smallText">State:</td><td><input type="text" name="vendors_bill_state" value="<?=$vendors[vendors_bill_state]?>" style="width:250px;" /></td></tr>
						<tr><td class="smallText">Zipcode:</td><td><input type="text" name="vendors_bill_zip" value="<?=$vendors[vendors_bill_zip]?>" style="width:250px;" /></td></tr>
						<tr><td class="smallText">Country:</td><td><?=tep_draw_pull_down_menu('vendors_bill_country', tep_get_countries(), $vendors[vendors_bill_country],'style="width:250px"')?></td></tr>
					</table>
					</td>
				</tr>
				<tr><td colspan="2"><hr></td></tr>	
				<tr><td class="smallText">Account Number:</td><td><input type="text" name="vendors_acct_num" value="<?=$vendors[vendors_acct_num]?>" style="width:250px;" /></td></tr>
				<tr><td class="smallText">Terms:</td><td><?=tep_draw_pull_down_menu('vendors_terms', tep_get_vendors_terms_choices(),$vendors[vendors_terms],'style="width:250px;"')?></td></tr>
				<tr><td class="smallText">URL:</td><td><input type="text" name="vendors_url" value="<?=$vendors[vendors_url]?>" style="width:250px;" /></td></tr>
				<tr><td class="smallText">UserName:</td><td><input type="text" name="vendors_username" value="<?=$vendors[vendors_username]?>" style="width:250px;" /></td></tr>
				<tr><td class="smallText">Password:</td><td><input type="text" name="vendors_password" value="<?=$vendors[vendors_password]?>" style="width:250px;" /></td></tr>
				<tr><td class="smallText">Sale Email Address:</td><td><input type="text" name="sale_email_address" value="<?=$vendors[sale_email_address]?>" style="width:250px;" /></td></tr>
				<tr><td class="smallText">Purchase Email Address:</td><td><input type="text" name="po_email_address" value="<?=$vendors[po_email_address]?>" style="width:250px;" /></td></tr>
				</table>
			</td>
			<td width="50%" valign="top">
				<table border="0" cellspacing="5" cellpadding="0" width="100%">
				<tr><td class="smallText">Phone 1:</td><td><input type="text" name="vendors_phone1" value="<?=$vendors[vendors_phone1]?>" style="width:250px;" /></td></tr>				
				<tr><td class="smallText">Phone 2:</td><td><input type="text" name="vendors_phone2" value="<?=$vendors[vendors_phone2]?>" style="width:250px;" /></td></tr>
				<tr><td class="smallText">Vendors Email:</td><td><input type="text" name="vendors_email" value="<?=$vendors[vendors_email]?>" style="width:250px;" /></td></tr>
				<tr><td><b>Shipping Address</b></td></tr>
				<tr><td colspan="2"><hr></td></tr>	
				<tr>
					<td colspan="2">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr><td class="smallText" width="141">Street Address 1:</td><td><input type="text" name="vendors_ship_addr1" value="<?=$vendors[vendors_ship_addr1]?>" style="width:250px;" /></td></tr>
						<tr><td class="smallText">Street Address 2:</td><td><input type="text" name="vendors_ship_addr2" value="<?=$vendors[vendors_ship_addr2]?>" style="width:250px;" /></td></tr>
						<tr><td class="smallText">City:</td><td><input type="text" name="vendors_ship_city" value="<?=$vendors[vendors_ship_city]?>" style="width:250px;" /></td></tr>
						<tr><td class="smallText">State:</td><td><input type="text" name="vendors_ship_state" value="<?=$vendors[vendors_ship_state]?>" style="width:250px;" /></td></tr>
						<tr><td class="smallText">Zipcode:</td><td><input type="text" name="vendors_ship_zip" value="<?=$vendors[vendors_ship_zip]?>" style="width:250px;" /></td></tr>
						<tr><td class="smallText">Country:</td><td><?=tep_draw_pull_down_menu('vendors_ship_country', tep_get_countries(), $vendors[vendors_ship_country],'style="width:250px"')?></td></tr>
					</table>
					</td>
				</tr>
				<tr><td colspan="2"><hr></td></tr>	
				<tr><Td class="smallText">Comments:</td><td><textarea style="height:60px; width:250px;" name="vendors_comments"><?=$vendors[vendors_comments]?></textarea></td></tr>
<?
	if ($vendors[sale_email]==1)
		$selected1 = "selected";
	else
		$selected2 = "selected";
?>
				<tr><Td class="smallText">Sale Email:</td><td><select style="width:250px;" name="sale_email"><option value="0" <?=$selected2?>>NO</option><option value="1" <?=$selected1?>>YES</option></select></td></tr>
<?
	if ($vendors[email_detail]==D)
		$selected1 = "selected";
	else
		$selected2 = "selected";
?>

				<tr><Td class="smallText">Email Details:</td><td><select style="width:250px;" name="email_detail"><option value="D" <?=$selected1?>>Details</option><option value="S" <?=$selected2?>>Summary</option></select></td></tr>
<?
	if ($vendors[allow_product_edit]==0)
		$selected1 = "selected";
	else
		$selected2 = "selected";
?>

				<tr><Td class="smallText">Allow Products Edit:</td><td><select style="width:250px;" name="allow_product_edit"><option value="0" <?=$selected1?>>NO</option><option value="1" <?=$selected2?>>YES</option></select></td></tr>
<?
if ($vendors[direct_purchase]==1) $direct="checked";
if ($vendors[consignment]==1) $consignment="checked";
if ($vendors[royalty]==1) $royalty="checked";

?>
				<tr><Td class="smallText">Vendors Type:</td><td><input type="checkbox" name="direct_purchase" value=1 <?=$direct?>  />&nbsp;Direct Purchase<br/><input type="checkbox" name="consignment" value=1 <?=$consignment?> />&nbsp;Consignment<br/><input type="checkbox" name="royalty" value=1 <?=$royalty?> />&nbsp;Royalty</td></tr>
				</table>
			</td>
		</tr>
<?
if (intval($_GET[vID])!=0)
$but_call = 'Save';
else
$but_call = 'Add new';
?>
		<tr><Td colspan="2" align="center" style="padding-top:15px;"><input type="submit" value="<?=$but_call?>" /><? if (intval($_GET[vID])!=0) { ?>&nbsp;&nbsp;&nbsp;<input type="button" value="Delete" onclick="if (confirm('Are you sure you want ot delete this vendor?')) window.location.href='vendors.php?action=delete&vID=<?=$_GET[vID]?>'" /><? } ?>&nbsp;&nbsp;&nbsp;<input type="button" value="Cancel" onclick="window.location.href='vendors.php'" /></td></tr>
		</table>
	</td>
      </tr>
</form>
    </table></td>
<!-- body_text_eof //-->
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
