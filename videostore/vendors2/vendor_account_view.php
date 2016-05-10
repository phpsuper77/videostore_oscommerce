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
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

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
  require(DIR_WS_LANGUAGES . 'english/'.  FILENAME_VENDOR_ACCOUNT_VIEW);
  require(DIR_WS_CLASSES . 'message_stack.php');
  require(DIR_WS_LANGUAGES .'english/'. FILENAME_VENDOR_PRODUCTS_DISP);


?>
<script>

function changeState(id, type){	
var arr = new Array('223', '38', '81', '14', '204', '195');
flag = 0;
for (i=0;i<arr.length;i++){
	if (arr[i]==id){
		flag = 1;
		document.getElementById(type+'_state_'+id).disabled=false;
		document.getElementById(type+'_country_'+id).style.display='block';
		}
	else{
		document.getElementById(type+'_state_'+arr[i]).disabled=true;
		document.getElementById(type+'_country_'+arr[i]).style.display='none';
	}
}
	if (flag!=1){
		document.getElementById(type+'_state_default').disabled='';
		document.getElementById(type+'_country_default').style.display='';
		}
		else {
		document.getElementById(type+'_state_default').disabled=true;
		document.getElementById(type+'_country_default').style.display='none';
		}

}

	function showHide(obj, letter){
	if (obj.value!='223'){
		document.getElementById(letter+'1').style.display='none';
		document.getElementById(letter+'2').style.display='block';
	}
	else{
		document.getElementById(letter+'1').style.display='block';
		document.getElementById(letter+'2').style.display='none';
	}
}
</script>
<?
	if ($_POST['action']=='process'){
      $sql_query = array('vendors_name' => $_POST['vendors_name'], 
                         'vendors_contact' => $_POST['vendors_contact'], 
                         'vendors_phone1' => $_POST['vendors_phone1'], 
                         'vendors_phone2' => $_POST['vendors_phone2'], 
                         'vendors_bill_addr1' => $_POST['vendors_bill_addr1'], 
                         'vendors_bill_addr2' => $_POST['vendors_bill_addr2'], 
                         'vendors_bill_city' => $_POST['vendors_bill_city'],
			 'sale_email' => $_POST['sale_email'],
                         'vendors_bill_state' => $_POST['bill_state'],
                         'vendors_bill_zip' => $_POST['vendors_bill_zip'],
                         'vendors_bill_country' => $_POST['bill_country'],
                         'vendors_ship_addr1' => $_POST['vendors_ship_addr1'],
                         'vendors_ship_addr2' => $_POST['vendors_ship_addr2'],
                         'vendors_ship_city' => $_POST['vendors_ship_city'],
                         'vendors_ship_state' => $_POST['ship_state'],
                         'vendors_ship_zip' => $_POST['vendors_ship_zip'],
                         'vendors_ship_country' => $_POST['ship_country'],
                         'vendors_fax' => $_POST['vendors_fax'],
                         'vendors_email' => $_POST['vendors_email'],
                         'vendors_url' => $_POST['vendors_url'],
                         'vendors_acct_num' => $_POST['vendors_acct_num'],
                         'vendors_comments' => $_POST['vendors_comments'],
                         'last_modified' => date("Y-m-d H:i:m", mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))),
                         'sale_email_address' => $_POST['sale_email_address'],
                         'po_email_address' => $_POST['po_email_address']);
tep_db_perform(TABLE_VENDORS, $sql_query,'update','vendors_id="'.$_SESSION[vendors_id].'"');
echo "<script>window.location.href='vendor_account_view.php';</script>";	
	}
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
	<td width="100%" class="headerNavigation">&nbsp;<a href="index.php" class="headerNavigation"><?php echo TXT_HOME?></a>&nbsp;-&nbsp;<a href="vendor_account_view.php" class="headerNavigation"><B><?php echo TXT_INFO_ACCOUNT;?></B></a></td>
</tr>
</table>
<table border="0" width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
    	<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php
	tep_session_start();
	if (tep_session_is_registered('vendors_id'))
		require('./includes/column_left.php');
?>
<!-- left_navigation_eof //-->
			</table>
		</td>
<!-- body_text //-->
		<td width="100%" valign="top">
			<table width="98%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading1" colspan="2"><?php echo HEADING_TITLE; ?></td>
      </tr>
      <tr>
      </tr>
      <tr>
	      <td class="main" colspan="2"><b><?php echo MY_ACCOUNT_TITLE; ?></b></td>
      </tr>

<?php
	$messageStack = new messageStack();
	if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'update'))
	{

	 //tep_db_query("update " . TABLE_VENDORS . " set vendors_name = '$HTTP_POST_VARS[name]', vendors_contact='$HTTP_POST_VARS[contact]',vendors_phone1='$HTTP_POST_VARS[phone1]',vendors_phone2='$HTTP_POST_VARS[phone2]',vendors_bill_addr1='$HTTP_POST_VARS[bill_addr1]',vendors_bill_addr2='$HTTP_POST_VARS[bill_addr2]',vendors_bill_city='$HTTP_POST_VARS[bill_city]',vendors_bill_state='$HTTP_POST_VARS[bill_state]',vendors_bill_zip='$HTTP_POST_VARS[bill_zip]',vendors_bill_country='$HTTP_POST_VARS[bill_country]',vendors_ship_addr1='$HTTP_POST_VARS[ship_addr1]',vendors_ship_addr2='$HTTP_POST_VARS[ship_addr2]',vendors_ship_city='$HTTP_POST_VARS[ship_city]',vendors_ship_state='$HTTP_POST_VARS[ship_state]',vendors_ship_zip='$HTTP_POST_VARS[ship_zip]',vendors_ship_country='$HTTP_POST_VARS[ship_country]',vendors_fax='$HTTP_POST_VARS[fax]',vendors_email='$HTTP_POST_VARS[email]',vendors_url='$HTTP_POST_VARS[url]',vendors_acct_num='$HTTP_POST_VARS[acc_num]',vendors_terms='$HTTP_POST_VARS[terms]',vendors_comments='$HTTP_POST_VARS[comments]'  where vendors_id = '" . $vendors_id . "'");

?>

	<tr align="LEFT">
		<td class="main"><?php echo TXT_UPDATE_SUCCESS; ?></td>
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
<form action="vendor_account_view.php" method="post" name="f1">
	<input type="hidden" name='action' value='process' />
	<tr valign="top">
		<td>
			<table width="100%">
				<tr>
			  	<th class="infoBoxContents" colspan="2"><b><?php echo TXT_PERSONAL_INFO; ?></b></th>
			  </tr>
			  <tr>
					<td class="main" style="width: 125px"><?php echo TXT_VENDORS_NAME; ?></td><td><input type="text" name="vendors_name" style="width:250px;" value='<?=$account[vendors_name]?>'/></td>
			  </tr>
			  <tr>
				  <td class="main"><?php echo TXT_VENDORS_CONTACT; ?></td><td><input type="text" name="vendors_contact" style="width:250px;" value='<?=$account[vendors_contact]?>' /></td>
			  </tr>
			  <tr>
					<td class="main"><?php echo TXT_VENDORS_PHONE1; ?></td><td><input type="text" name="vendors_phone1" style="width:250px;" value='<?=$account[vendors_phone1]?>'/></td>
			  </tr>
			  <tr>
					<td class="main"><?php echo TXT_VENDORS_PHONE2; ?></td><td><input type="text" name="vendors_phone2" style="width:250px;" value='<?=$account[vendors_phone2]?>' /></td>
			  </tr>
			</table>
		</td>
		<td>
			<table width="100%" border="0">
			  <tr>
			  	<th class="infoBoxContents" colspan="6"><b><?php echo TXT_BILLING_ADDRESS; ?></b></th>
			  </tr>
			  <tr>
					<td class="main" style="width: 100px"><?php echo TXT_ADDRESS1; ?></td><td colspan="5"><input type="text" name="vendors_bill_addr1" style="width:250px;" value='<?=$account['vendors_bill_addr1']?>' /></td>
			  </tr>
			  <tr>
					<td class="main"><?php echo TXT_ADDRESS2; ?></td><td colspan="5"><input type="text" name="vendors_bill_addr2" style="width:250px;" value='<?=$account[vendors_bill_addr2]?>' /></td>
			  </tr>
			  <?
			  		$bill_country_sel = tep_db_query("select * from ". TABLE_COUNTRIES ." where countries_id = ". $account['vendors_bill_country']);
			  		$bill_country = tep_db_fetch_array($bill_country_sel);
			  ?>
			  <tr>
					<td class="main"><?php echo TXT_CITY; ?></td><td><input type="text" name="vendors_bill_city" style="width:250px;" value='<?=$account['vendors_bill_city']?>' /></td>
					<td class="main"><?php echo TXT_STATE; ?></td>
					<td>
<span id="billing_country_223" style="display:none;">
<?
        $zones_array_223 = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '223' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array_223[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('bill_state', $zones_array_223, $account[vendors_bill_state],'id="billing_state_223"');
?>
</span>
<span id="billing_country_38" style="display:none;">
<?
        $zones_array_38 = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '38' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array_38[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('bill_state', $zones_array_38, $account[vendors_bill_state],'id="billing_state_38"');
?>
</span>
<span id="billing_country_81" style="display:none;">
<?
        $zones_array_81 = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '81' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array_81[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('bill_state', $zones_array_81, $account[vendors_bill_state],'id="billing_state_81"');
?>
</span>
<span id="billing_country_14" style="display:none;">
<?
        $zones_array_14 = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '14' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array_14[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('bill_state', $zones_array_14, $account[vendors_bill_state],'id="billing_state_14"');
?>
</span>
<span id="billing_country_204" style="display:none;">
<?
        $zones_array_204 = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '204' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array_204[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('bill_state', $zones_array_204, $account[vendors_bill_state],'id="billing_state_204"');
?>
</span>
<span id="billing_country_195" style="display:none;">
<?
        $zones_array_195 = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '195' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array_195[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('bill_state', $zones_array_195, $account[vendors_bill_state],'id="billing_state_195"');
?>
</span>
<span id="billing_country_default" style="display:none;">
	<?=tep_draw_input_field('bill_state',$account[vendors_bill_state],'id="billing_state_default"')?>
</span>
</td>
</tr>
			 	<tr>
					<td class="main"><?php echo TXT_COUNTRY; ?></td><td><?php echo tep_get_country_list('bill_country',$account[vendors_bill_country],'id="billing_country" onchange="changeState(this.value, \'billing\')"');?></td>
					<td class="main"><?php echo TXT_ZIP; ?></td><td><input type="text" name="vendors_bill_zip" style="width:80px;" value='<?=$account['vendors_bill_zip']?>' /?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr valign="top">
		<td>
			<table width="100%" border="0">
				<tr>
			  	<th class="infoBoxContents" colspan="2"><b><?php echo TXT_GENERAL_INFO; ?></b></th>
			  </tr>
			  <tr>
			  	<td class="main" style="width: 75px"><?php echo TXT_FAX; ?></td><td><input type="text" name="vendors_fax" style="width:250px;" value='<?=$account['vendors_fax']?>' /></td>
			  </tr>
			  <tr>
			  	<td class="main"><?php echo TXT_EMAIL; ?></td><td><input type="text" name="vendors_email" style="width:250px;" value='<?=$account['vendors_email']?>'/></td>
			  </tr>
			  <tr>
			  	<td class="main">Purchase Email</td><td><input type="text" name="po_email_address" style="width:250px;" value='<?=$account['po_email_address']?>'/></td>
			  </tr>
			  <tr>
			  	<td class="main">Sale Email</td><td><input type="text" name="sale_email_address" style="width:250px;" value='<?=$account['sale_email_address']?>'/></td>
			  </tr>
			  <tr>
			  	<td class="main">Email on Sale</td><td><input type="checkbox" name="sale_email" value='1' <?if ($account['sale_email']==1) echo 'checked'; ?> /></td>
			  </tr>
			  <tr>
			  	<td class="main"><?php echo TXT_URL; ?></td><td><input type="text" name="vendors_url" style="width:250px;" value='<?=$account['vendors_url']?>'/></td>
			  </tr>
			  <tr>
			  	<td class="main"><?php echo TXT_ACCT_NUM; ?></td><td><input type="text" name="vendors_acct_num" style="width:250px;" value='<?=$account['vendors_acct_num']?>'/></td>
			  </tr>
			</table>
		</td>
		<td>
			<table width="100%">
				<tr>
			  	<th class="infoBoxContents" colspan="6"><b><?php echo TXT_SHIPPING_ADDRESS; ?></b></th>
			  </tr>
			  <tr>
			  	<td class="main" style="width: 100px"><?php echo TXT_ADDRESS1; ?></td><td><input type="text" name="vendors_ship_addr1" style="width:250px;" value='<?=$account['vendors_ship_addr1']?>'/></td>
			  </tr>
			  <tr>
			  	<td class="main"><?php echo TXT_ADDRESS2; ?></td><td><input type="text" name="vendors_ship_addr2" style="width:250px;" value='<?=$account['vendors_ship_addr2']?>'/></td>
			  </tr>
			  <?
			  		$ship_country_sel = tep_db_query("select * from ". TABLE_COUNTRIES ." where countries_id = ". $account['vendors_ship_country']);
			  		$ship_country = tep_db_fetch_array($ship_country_sel);
			  ?>
			  <tr>
			  	<td class="main"><?php echo TXT_CITY; ?></td><td><input type="text" name="vendors_ship_city" style="width:250px;" value='<?=$account['vendors_ship_city']?>'/></td>
			  	<td class="main"><?php echo TXT_STATE; ?></td>
				<td>
<span id="shipping_country_223" style="display:none;">
<?
        echo tep_draw_pull_down_menu('ship_state', $zones_array_223, $account[vendors_ship_state],'id="shipping_state_223"');
?>
</span>
<span id="shipping_country_38" style="display:none;">
<?
        echo tep_draw_pull_down_menu('ship_state', $zones_array_38, $account[vendors_ship_state],'id="shipping_state_38"');
?>
</span>
<span id="shipping_country_81" style="display:none;">
<?
        echo tep_draw_pull_down_menu('ship_state', $zones_array_81, $account[vendors_ship_state],'id="shipping_state_81"');
?>
</span>
<span id="shipping_country_14" style="display:none;">
<?
        echo tep_draw_pull_down_menu('ship_state', $zones_array_14, $account[vendors_ship_state],'id="shipping_state_14"');
?>
</span>
<span id="shipping_country_204" style="display:none;">
<?
        echo tep_draw_pull_down_menu('ship_state', $zones_array_204, $account[vendors_ship_state],'id="shipping_state_204"');
?>
</span>
<span id="shipping_country_195" style="display:none;">
<?
        echo tep_draw_pull_down_menu('ship_state', $zones_array_195, $account[vendors_ship_state],'id="shipping_state_195"');
?>
</span>
<span id="shipping_country_default" style="display:none;">
	<?=tep_draw_input_field('ship_state',$account[vendors_ship_state],'id="shipping_state_default"')?>
</span>
</td>
			  </tr>
			  <tr>
					<td class="main"><?php echo TXT_COUNTRY; ?></td>
					<td>
					<?php echo tep_get_country_list('ship_country',$account[vendors_ship_country],'id="shipping_country" onchange="changeState(this.value, \'shipping\')"');?>
					</td>
			  		<td class="main"><?php echo TXT_ZIP; ?></td><td><input type="text" name="vendors_ship_zip" style="width:80px;" value='<?=$account['vendors_ship_zip']?>'/></td>
			  </tr>
			  <tr>
			  	<td class="main"><?php echo TXT_COMMENTS; ?></td><td><textarea name="vendors_comments" style="width:250px;height:50px;"><?=stripslashes($account['vendors_comments'])?></textarea></td>
			  </tr>

			</table>
		</td>
	</tr>
	
	<tr>
		<td colspan="2"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
	</tr>
	<tr>
		<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
	</tr>
	<tr>
		<td align="left"><a href="vendor_account_update.php"><?php echo tep_image_submit_vendors(DIR_WS_INCLUDES_LOCAL.'images/button_update.gif', IMAGE_BUTTON_UPDATE); ?></a></td>
	</tr>
	<tr>
		<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
	</tr>
</form>

<script>
	changeState('<?=$account[vendors_bill_country]?>','billing');
	changeState('<?=$account[vendors_ship_country]?>','shipping');
</script>


	<tr valign="top">
		<td>
			<table width="100%">
				<tr>
			  	<th class="infoBoxContents" colspan="2"><b>PRODUCTS (Listed)</b></th>
			  </tr>
<?
$total = tep_db_fetch_array(tep_db_query("SELECT  count(*) as sum FROM products_to_vendors a LEFT  JOIN products b ON ( a.products_id = b.products_id ) WHERE a.vendors_id =".$_SESSION[vendors_id]." AND b.products_status =1 AND vendors_product_payment_type=1"));
?>
			  <tr>
					<td class="main" style="width: 110px">Direct Purchase:</td><td><?=round($total['sum'],2)?>&nbsp;&nbsp;(<a href="products_details.php?type=1&product_type=listed">View Details</a>)</td>
			  </tr>
<?
$total = tep_db_fetch_array(tep_db_query("SELECT  count(*) as sum FROM products_to_vendors a LEFT  JOIN products b ON ( a.products_id = b.products_id ) WHERE a.vendors_id =".$_SESSION[vendors_id]." AND b.products_status =1 AND vendors_product_payment_type=2"));
?>
			  <tr>
				  <td class="main">Consignment:</td><td><?=round($total['sum'],2)?>&nbsp;&nbsp;(<a href="products_details.php?type=2&product_type=listed">View Details</a>)</td>
			  </tr>
<?
$total = tep_db_fetch_array(tep_db_query("SELECT  count(*) as sum FROM products_to_vendors a LEFT  JOIN products b ON ( a.products_id = b.products_id ) WHERE a.vendors_id =".$_SESSION[vendors_id]." AND b.products_status =1 AND vendors_product_payment_type=3"));
?>

			  <tr>
					<td class="main">Royalty:</td><td><?=round($total['sum'],2)?>&nbsp;&nbsp;(<a href="products_details.php?type=3&product_type=listed">View Details</a>)</td>
			  </tr>
			</table>
		</td>
		<td>
			<table width="100%">
<?
/*
$total1 = tep_db_num_rows(tep_db_query("SELECT * FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=1 GROUP BY op.orders_id ORDER  BY o.date_purchased DESC"));
$total2 = tep_db_num_rows(tep_db_query("SELECT * FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=2 GROUP BY op.orders_id ORDER  BY o.date_purchased DESC"));
$total3 = tep_db_num_rows(tep_db_query("SELECT * FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=3 GROUP BY op.orders_id ORDER  BY o.date_purchased DESC"));
*/
$total_1 = tep_db_fetch_array(tep_db_query("SELECT sum(op.products_quantity) as tot FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=1 ORDER  BY o.date_purchased DESC"));
$total_2 = tep_db_fetch_array(tep_db_query("SELECT sum(op.products_quantity) as tot FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=2 ORDER  BY o.date_purchased DESC"));
$total_3 = tep_db_fetch_array(tep_db_query("SELECT sum(op.products_quantity) as tot FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=3 ORDER  BY o.date_purchased DESC"));
$total_4 = tep_db_fetch_array(tep_db_query("SELECT sum(op.products_quantity) as tot FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=4 ORDER  BY o.date_purchased DESC"));
?>


			  <tr>
			  	<th class="infoBoxContents" colspan="2"><b>GROSS SALES - Total Items <?=($total_1[tot]+$total_2[tot]+$total_3[tot]+$total_4[tot])?></b></th>
			  </tr>
<?
	$sql_query = tep_db_query("SELECT op.final_price, op.products_quantity FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=1 ORDER  BY o.date_purchased DESC");
	$sum_1 = 0;
	while($row=tep_db_fetch_array($sql_query))
		$sum_1 = $sum_1+($row[final_price]*$row['products_quantity']);
?>
			  <tr>
					<td class="main" style="width: 110px">Direct Purchase:</td><td><?=$currencies->format($sum_1,true,'USD','1.000000')?>&nbsp;&nbsp;(<a href="sale_details.php?type=1">View Details</a>)</td>
			  </tr>
<?
	$sql_query = tep_db_query("SELECT op.final_price, op.products_quantity FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=2 ORDER  BY o.date_purchased DESC");
	$sum_2 = 0;
	while($row=tep_db_fetch_array($sql_query))
		$sum_2 = $sum_2+($row[final_price]*$row['products_quantity']);
?>

			  <tr>
					<td class="main">Consignment:</td><td><?=$currencies->format($sum_2,true,'USD','1.000000')?>&nbsp;&nbsp;(<a href="sale_details.php?type=2">View Details</a>)</td>
			  </tr>
<?
	$sql_query = tep_db_query("SELECT op.final_price, op.products_quantity FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=3 ORDER  BY o.date_purchased DESC");
	$sum_3 = 0;
	while($row=tep_db_fetch_array($sql_query))
		$sum_3 = $sum_3+($row[final_price]*$row['products_quantity']);
?>

			  <tr>
					<td class="main">Royalty:</td><td><?=$currencies->format($sum_3,true,'USD','1.000000')?>&nbsp;&nbsp;(<a href="sale_details.php?type=3">View Details</a>)</td>
        </tr>

<?
	$sql_query = tep_db_query("SELECT op.final_price, op.products_quantity FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=4 ORDER  BY o.date_purchased DESC");
	$sum_4 = 0;
	while($row=tep_db_fetch_array($sql_query))
		$sum_4 = $sum_4+($row[final_price]*$row['products_quantity']);
?>

			  <tr>
					<td class="main">Duplication:</td><td><?=$currencies->format($sum_4,true,'USD','1.000000')?>&nbsp;&nbsp;(<a href="sale_details.php?type=4">View Details</a>)</td>
        </tr>
			</table>
		</td>
	</tr>



	<tr valign="top">
		<td>
			<table width="100%" border="0">
				<tr>
			  	<th class="infoBoxContents" colspan="2"><b>PRODUCTS (Sold) - Total items <?=($total_1[tot]+$total_2[tot]+$total_3[tot]+$total_4[tot])?></b></th>
			  </tr>
			  <tr>
					<td class="main" style="width: 110px">Direct Purchase:</td><td><?=round($total_1[tot],2)?>&nbsp;&nbsp;(<a href="products_details_sold.php?type=1&product_type=sold">View Details</a>)</td>
			  </tr>
			  <tr>
				  <td class="main">Consignment:</td><td><?=round($total_2[tot],2)?>&nbsp;&nbsp;(<a href="products_details_sold.php?type=2&product_type=sold">View Details</a>)</td>
			  </tr>
			  <tr>
					<td class="main">Royalty:</td><td><?=round($total_3[tot],2)?>&nbsp;&nbsp;(<a href="products_details_sold.php?type=3&product_type=sold">View Details</a>)</td>
			  </tr>
			  <tr>
					<td class="main">Duplication:</td><td><?=round($total_4[tot],2)?>&nbsp;&nbsp;(<a href="products_details_sold.php?type=4&product_type=sold">View Details</a>)</td>
			  </tr>
			</table>
		</td>
		<td>
			<table width="100%">
			  <tr>
			  	<th class="infoBoxContents" colspan="2"><b>VENDOR SALES - Total items <?=($total_1[tot]+$total_2[tot]+$total_3[tot]+$total_4[tot])?></b></th>
			  </tr>
<?
	$total = tep_db_fetch_array(tep_db_query("SELECT sum(op.products_item_cost*op.products_quantity) as sum FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=1 ORDER  BY o.date_purchased DESC"));
?>
			  <tr>
					<td class="main" style="width: 110px">Direct Purchase:</td><td><?=$currencies->format($total[sum],true,'USD','1.000000')?>&nbsp;&nbsp;(<a href="sale_details.php?type=1">View Details</a>)</td>
			  </tr>
<?
	$total = tep_db_fetch_array(tep_db_query("SELECT sum(op.products_item_cost*op.products_quantity) as sum FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=2 ORDER  BY o.date_purchased DESC"));
?>

			  <tr>
					<td class="main">Consignment:</td><td><?=$currencies->format($total[sum],true,'USD','1.000000')?>&nbsp;&nbsp;(<a href="sale_details.php?type=2">View Details</a>)</td>
			  </tr>
<?
	$total = tep_db_fetch_array(tep_db_query("SELECT sum(op.products_item_cost*op.products_quantity) as sum FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=3 ORDER  BY o.date_purchased DESC"));
?>

			  <tr>
					<td class="main">Royalty:</td><td><?=$currencies->format($total[sum],true,'USD','1.000000')?>&nbsp;&nbsp;(<a href="sale_details.php?type=3">View Details</a>)</td>
        </tr>
<?
	$total = tep_db_fetch_array(tep_db_query("SELECT sum(op.products_item_cost*op.products_quantity) as sum FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=4 ORDER  BY o.date_purchased DESC"));
?>
			  <tr>
					<td class="main" style="width: 110px">Duplication:</td><td><?=$currencies->format($total[sum],true,'USD','1.000000')?>&nbsp;&nbsp;(<a href="sale_details.php?type=4">View Details</a>)</td>
			  </tr>

			</table>
		</td>
	</tr>

	<tr valign="top">
		<td>
			<table width="100%">
				<tr>
			  	<th class="infoBoxContents" colspan="2"><b>PAYMENTS</b></th>
			  </tr>
<?
$total_pay_1 = tep_db_fetch_array(tep_db_query("SELECT sum(payment) AS sum FROM `vendor_payments` WHERE vendor_id=".$_SESSION['vendors_id']." and type='Direct Purchase' and status='active'"));
?>

			  <tr>
					<td class="main" style="width: 110px">Direct Purchase:</td><td><?=$currencies->format($total_pay_1[sum],true,'USD','1.000000')?>&nbsp;&nbsp;(<a href="payment_details.php?type=1&vID=<?=$_SESSION['vendors_id']?>">View Details</a>)</td>
			  </tr>
<?
$total_pay_2 = tep_db_fetch_array(tep_db_query("SELECT sum(payment) AS sum FROM `vendor_payments` WHERE vendor_id=".$_SESSION['vendors_id']." and type='Consignment' and status='active'"));
?>

			  <tr>
				  <td class="main">Consignment:</td><td><?=$currencies->format($total_pay_2[sum],true,'USD','1.000000')?>&nbsp;&nbsp;(<a href="payment_details.php?type=2&vID=<?=$_SESSION['vendors_id']?>">View Details</a>)</td>
			  </tr>
<?
$total_pay_3 = tep_db_fetch_array(tep_db_query("SELECT sum(payment) AS sum FROM `vendor_payments` WHERE vendor_id=".$_SESSION['vendors_id']." and type='Royalty' and status='active'"));
?>


			  <tr>
					<td class="main">Royalty:</td><td><?=$currencies->format($total_pay_3[sum],true,'USD','1.000000')?>&nbsp;&nbsp;(<a href="payment_details.php?type=3&vID=<?=$_SESSION['vendors_id']?>">View Details</a>)</td>
			  </tr>
<?
$total_pay_4 = tep_db_fetch_array(tep_db_query("SELECT sum(payment) AS sum FROM `vendor_payments` WHERE vendor_id=".$_SESSION['vendors_id']." and type='Duplication' and status='active'"));
?>

			  <tr>
					<td class="main" style="width: 110px">Duplication:</td><td><?=$currencies->format($total_pay_4[sum],true,'USD','1.000000')?>&nbsp;&nbsp;(<a href="payment_details.php?type=4&vID=<?=$_SESSION['vendors_id']?>">View Details</a>)</td>
			  </tr>

			</table>
		</td>
		<td>
			<table width="100%">
			  <tr>
			  	<th class="infoBoxContents" colspan="2"><b>AMOUNTS DUE (pending)</b></th>
			  </tr>
<?
$total = tep_db_fetch_array(tep_db_query("SELECT sum(products_item_cost*products_quantity) as sum FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=1  ORDER  BY o.date_purchased DESC"));
?>

			  <tr>
					<td class="main" style="width: 110px">Direct Purchase:</td><td><? $final = $total['sum']-$total_pay_1['sum']; echo $currencies->format($final,true,'USD','1.000000')?></td>
			  </tr>
<?
$total = tep_db_fetch_array(tep_db_query("SELECT sum(products_item_cost*products_quantity) as sum FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=2  ORDER  BY o.date_purchased DESC"));
?>

			  <tr>
					<td class="main">Consignment:</td><td><? $final = $total['sum']-$total_pay_2['sum']; echo $currencies->format($final,true,'USD','1.000000')?></td>
			  </tr>
<?
$total = tep_db_fetch_array(tep_db_query("SELECT sum(products_item_cost*products_quantity) as sum FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=3  ORDER  BY o.date_purchased DESC"));
?>
			  <tr>
					<td class="main">Royalty:</td><td><? $final = $total['sum']-$total_pay_3['sum']; echo $currencies->format($final,true,'USD','1.000000')?></td>
        </tr>

<?
$total = tep_db_fetch_array(tep_db_query("SELECT sum(products_item_cost*products_quantity) as sum FROM orders_products op, products_to_vendors pv, orders o, products_description pd WHERE o.orders_id = op.orders_id AND pd.products_id = pv.products_id AND op.products_id = pv.products_id AND pv.vendors_id =".$_SESSION['vendors_id']." and op.products_sale_type=4  ORDER  BY o.date_purchased DESC"));
?>
			  <tr>
					<td class="main">Duplication:</td><td><? $final = $total['sum']-$total_pay_4['sum']; echo $currencies->format($final,true,'USD','1.000000')?></td>
        </tr>

			</table>
		</td>
	</tr>

	</table>
	</td></tr>
</table>
<br>
<!-- footer //-->
<?php require('footer.php'); ?>