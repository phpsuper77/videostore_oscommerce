<?php
ob_start();
/*
  $Id: vendor_order_products.php,v 1.80 2005/25/08 11:40:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . 'english/'.  FILENAME_VENDOR_ACCOUNT_VIEW);
  require(DIR_WS_LANGUAGES . 'english/'.  FILENAME_VENDOR_ORDER_PRODUCTS);

  require(DIR_WS_INCLUDES .'database_tables.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  require('includes/split_page_results.php');
  require('includes/functions.php');


//var_dump($cart);

  if (isset($_SESSION["vendors_id"]) && $_SESSION["vendors_id"] <> "")
  {
  	$products_sql = tep_db_query("SELECT b.products_description, c.products_image, c.products_quantity, c. products_model, c. products_set_type_id, b.products_name, b.products_id, b.products_name_prefix, b.products_name_suffix FROM products_to_vendors a LEFT JOIN products_description b ON ( a.products_id = b.products_id ) LEFT JOIN products c ON ( a.products_id = c.products_id ) WHERE a.vendors_product_payment_type =  '3' AND c.products_status = '1' AND a.vendors_id ='".$_SESSION[vendors_id]."'");
	$total_products = tep_db_num_rows($products_sql);
  }
  else
  {
	tep_redirect("../index.php");
	exit;
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="./includes/main.css">
<link rel="stylesheet" type="text/css" href="../stylesheet.css">

<script language="javascript"><!--

function trimAll(sString) 
{
while (sString.substring(0,1) == ' ')
{
sString = sString.substring(1, sString.length);
}
while (sString.substring(sString.length-1, sString.length) == ' ')
{
sString = sString.substring(0,sString.length-1);
}
return sString;
}

	function t_set(obj,index){
		for(i=0;i<obj.length;i++){
			if (obj[i].value==index){
				obj.selectedIndex=i;
				break;
			}
		}				
	}	

function checkFormValue(){
	error = '';
	if (trimAll(document.getElementById('cc_owner').value)=='') error = 'Credit Card owner is required field!\n';
	if (trimAll(document.getElementById('cc_number').value)=='') error = error + 'Credit Number is required field!\n';
	if (trimAll(document.getElementById('billing_fname').value)=='') error = error + 'Billing First Name is required field!\n';
	if (trimAll(document.getElementById('billing_lname').value)=='') error = error + 'Billing Last Name is required field!\n';
	if (trimAll(document.getElementById('billing_addr1').value)=='') error = error + 'Billing Address 1 is required field!\n';
	if (trimAll(document.getElementById('billing_city').value)=='') error = error + 'Billing City is required field!\n';	
	billing_country_id = document.getElementById('billing_country').options[document.getElementById('billing_country').selectedIndex].value
	if (billing_country_id=='') error = error + 'Billing Country is required field!\n';

if (billing_country_id!='223' && billing_country_id!='38' && billing_country_id!='81' && billing_country_id!='14' && billing_country_id!='204' && billing_country_id!='195'){
	if (trimAll(document.getElementById('billing_state_default').value)=='') error = error + 'Billing State is required field!\n';
}
	if (trimAll(document.getElementById('billing_zip').value)=='') error = error + 'Billing Zip is required field!\n';


	if (trimAll(document.getElementById('shipping_fname').value)=='') error = error + 'Shipping First Name is required field!\n';
	if (trimAll(document.getElementById('shipping_lname').value)=='') error = error + 'Shipping Last Name is required field!\n';
	if (trimAll(document.getElementById('shipping_addr1').value)=='') error = error + 'Shipping Address 1 is required field!\n';
	if (trimAll(document.getElementById('shipping_city').value)=='') error = error + 'Shipping City is required field!\n';	
	shipping_country_id = document.getElementById('shipping_country').options[document.getElementById('shipping_country').selectedIndex].value;
	if (shipping_country_id=='') error = error + 'Shipping Country is required field!\n';
if (shipping_country_id!='223' && shipping_country_id!='38' && shipping_country_id!='81' && shipping_country_id!='14' && shipping_country_id!='204' && shipping_country_id!='195'){
	if (trimAll(document.getElementById('shipping_state_default').value)=='') error = error + 'Shipping State is required field!\n';
}

	if (trimAll(document.getElementById('shipping_zip').value)=='') error = error + 'Shipping Zip is required field!\n';

	if (trimAll(error)=='') return true;
	else{
	alert(error);
	return false;
	}
}

function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=500,screenX=150,screenY=30,top=30,left=150')
}

function checkDigit() //ver 1.02
{
	if ((event.keyCode == 8) || (event.keyCode == 9) || (event.keyCode == 13))
	{
	return true;
	}
	else
	{
// alert(event.keyCode);
	if((event.keyCode > 45 && event.keyCode < 58) || (event.keyCode > 95 && event.keyCode < 106) || (event.keyCode > 36 && event.keyCode < 41))
	{
	return true;
	}
	else
	{
	return false;
	}
	}
}

function rowOverEffect(object) {
  if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
}

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
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<table border="0" width="100%" cellspacing="1" cellpadding="1">
<tr>
	<td width="100%" class="headerNavigation">&nbsp;<a href="../index.php" class="headerNavigation"><?php echo TXT_HOME?></span></a>&nbsp;-&nbsp;<a href="vendor_account_products.php" class="headerNavigation"><?php echo TXT_PRODUCT_INFO;?></a>&nbsp;-&nbsp;<span><b>Distribution</b></span></td>
</tr>
</table>

<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php
	if (tep_session_is_registered('vendors_id'))
		require('./includes/column_left.php');
?>
<!-- left_navigation_eof //-->
    </table></td>
        <td width="100%" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
	          <tr>
	            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	            <tr>
					<td colspan=6 width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
					  <tr><td class="pageHeading" colspan=6>Shopping Cart</td></tr>
					</table>
					</td>
      			</tr>
			<tr><td colspan="6" align="center" style="color:red;"><b><? if (!empty($error)) echo $error?></b></td></tr>
	        <tr>
	  	  	<td align="center" colspan="6">
	  	  		<table border="0" width="80%" cellspacing="0" cellpadding="0">
	  	  		<tr>
					<td width="25%">
	  	  				<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  	  				<tr>
	  	  					<td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
	  	  					<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  				</tr>
	  	  				</table>

</td>
	  	  			<td width="25%">
	  	  				<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  	  				<tr>
	  	  					<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  					<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  				</tr>
	  	  				</table>
	  	  			</td>
	  	  			<td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  			<td width="25%">
	  	  				<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  	  				<tr>
	  	  					<td width="100%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  					<td width=""><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
	  	  				</tr>
	  	  				</table>
	  	  			</td>
	  	  		</tr>
	  	  		<tr>
	  	  			<td align="center" width="25%" class="checkoutBarCurrent">Shopping Cart</td>
	  	  			<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>
	  	  			<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
	  	  			<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
	  	  		</tr>
	  	  		</table>
	  	  	</td>
	</tr>

				<tr>
					<td colspan=6><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td>
				</tr>

<form action="distribution.php?action=update" method="post">
<tr><td colspan="2"><a href='vendor_account_view.php'><?=tep_image(DIR_WS_INCLUDES_LOCAL.'images/button_back.gif')?></td><td colspan="3" align="right"><? if ($total_products>0){ ?><input type="image" src="../includes/languages/english/images/buttons/button_update_cart.gif" border="0" alt="Update Cart" title=" Update Cart "><?}?></td></tr>
<tr><td><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td></tr>
					<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent" align="left" width="10%">Model No&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="">Image&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="75%"><?php echo TXT_PRODUCT_NAME; ?>&nbsp;</td>
						<td class="dataTableHeadingContent" align="left" width="10%">Price</td>
						<td class="dataTableHeadingContent" align="left" width="10%">Qty</td>
					</tr>
<?
if ($total_products>0){
	while($products = tep_db_fetch_array($products_sql)){
	$rows++;
	if (($rows/2) == floor($rows/2))
		$class="productListing-even";
	else
		$class="productListing-odd";

	echo "<tr class='".$class."'>";
	echo "<td class='smalltext'>".$products[products_model]."</td>";
	echo "<td class='smalltext'>".tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name_prefix'] . '&nbsp;' . $products['products_name'] . '&nbsp;' . $products['products_name_suffix'])."</td>";
	echo "<td class='smalltext' valign='top'>".$products[products_name_prefix]." ".$products[products_name]." ".$products[products_name_suffix]."<br/>".substr(strip_tags($products[products_description]),0, 200)."...</td>";
    if ($products[products_set_type_id] ==11) {echo "<td><span class='productSpecialPrice'>$2.00</span></td>";}
    if ($products[products_set_type_id] ==12) {echo "<td><span class='productSpecialPrice'>$3.55</span></td>";}	
    if ($products[products_set_type_id] ==23) {echo "<td><span class='productSpecialPrice'>$3.45</span></td>";}

$qty = 0;
//var_dump($_SESSION['product']);
if (array_key_exists($products[products_id], $_SESSION['product'])) $qty = $_SESSION['product'][$products[products_id]];
	echo "<td class='smalltext' valign='top'><input type='text' name='products_qty[".$products[products_id]."]' value='".intval($qty)."' style='width:35px;' maxlength='3' onKeyDown='return checkDigit();' onpaste='return false' /></td>";
	echo "</tr>";
}
}
else
	echo "<tr><td colspan='5' align='center'><b>No items found</b></td></tr>";
?>
	              	<tr><td><?php echo tep_draw_separator('pixel_trans.gif', 1, 10);?></td></tr>
<tr><td colspan="2"><a href='vendor_account_view.php'><?=tep_image(DIR_WS_INCLUDES_LOCAL.'images/button_back.gif')?></td><td colspan="3" align="right"><? if ($total_products>0){ ?><input type="image" src="../includes/languages/english/images/buttons/button_update_cart.gif" border="0" alt="Update Cart" title=" Update Cart "><?}?></td></tr>
</form>
        </table><br></td>
      </tr>
<tr><td colpsna="4">
<table width="100%" border="0">
<?
if ($total_products>0){
if (empty($_SESSION['vendor'])){
  $account_query = tep_db_query("select * from " . TABLE_VENDORS . " where vendors_id = '" . $_SESSION['vendors_id']. "'");
  $vendor = tep_db_fetch_array($account_query);
  $vendor[cc_expires_month] = date("m");
  $vendor[cc_expires_years] = date("Y");
  $vendor[vendors_bill_company] = $vendor[vendors_name];
  $vendor[vendors_ship_company] = $vendor[vendors_name];
  $vendor[bill_country] = $vendor[vendors_bill_country];
  $vendor[ship_country] = $vendor[vendors_ship_country];
  $vendor[bill_state] = $vendor[vendors_bill_state];
  $vendor[ship_state] = $vendor[vendors_ship_state];


	$part = explode(" ", $vendor[vendors_contact]);
	if (count($part)>2){
		$fname = $part[0]." ".$part[1];
		for ($i=2;$i<count($part);$i++) $lname .= $part[$i]." ";
	}
	else{
		$fname = $part[0];
		$lname = $part[1];
	}
  $vendor[vendors_bill_fname] = $fname;
  $vendor[vendors_bill_lname] = $lname;
  $vendor[vendors_ship_fname] = $fname;
  $vendor[vendors_ship_lname] = $lname;

}
else{
	foreach ($_SESSION['vendor'] as $key=>$value){
		$vendor[$key] = $value;
	}

}

      for ($i=1; $i<13; $i++) {
        if ($i <= 9) {
                     $numeric = '0' . $i;
          }else{
                     $numeric = $i;
        }
$expires_month[] = array('id' => sprintf('%02d', $i), 'text' => $numeric . ' ' . strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate();
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }
?>

<script>
function markShipping(obj){
	if (obj.checked==true){
		document.getElementById('shipping_fname').value='Don';
		document.getElementById('shipping_lname').value='Wyatt';
		document.getElementById('shipping_company').value='TravelVideoStore.com';
		document.getElementById('shipping_addr1').value='5420 Boran Dr';
		document.getElementById('shipping_city').value='Tampa';	
		document.getElementById('shipping_zip').value='33610';	
		t_set(document.getElementById('shipping_country'),'223');
		changeState('223','shipping');
		t_set(document.getElementById('shipping_state_223'),'Florida');
		document.getElementById('pickup').value='T';	
	}
	else{
		document.getElementById('shipping_fname').value='<?=$vendor[vendors_ship_fname]?>';
		document.getElementById('shipping_lname').value='<?=$vendor[vendors_ship_lname]?>';
		document.getElementById('shipping_company').value='<?=$vendor[vendors_ship_company]?>';
		document.getElementById('shipping_addr1').value='<?=$vendor[vendors_ship_addr1]?>';
		document.getElementById('shipping_addr2').value='<?=$vendor[vendors_ship_addr2]?>';
		document.getElementById('shipping_city').value='<?=$vendor[vendors_ship_city]?>';	
		document.getElementById('shipping_zip').value='<?=$vendor[vendors_ship_zip]?>';	
		t_set(document.getElementById('shipping_country'),'<?=$vendor[ship_country]?>');
		changeState('<?=$vendor[ship_country]?>','shipping');
		if ((document.getElementById('shipping_country').value=='223') || (document.getElementById('shipping_country').value=='38') || (document.getElementById('shipping_country').value=='81') || (document.getElementById('shipping_country').value=='14') || (document.getElementById('shipping_country').value=='204') || (document.getElementById('shipping_country').value=='195') && (document.getElementById('shipping_country').value!=''))
		t_set(document.getElementById('shipping_state_<?=$vendor[ship_country]?>'),'<?=$vendor[vendors_ship_state]?>');
		document.getElementById('pickup').value='F';	
	}
}
</script>
	<tr><td colspan="2">Fields marked with [<span class="red">*</span>] are required</td></tr>
<form action="checkout_1.php" method="post" name="f1" onsubmit="return checkFormValue(this);">
	<input type="hidden" name='action' value='process' />
	<input type="hidden" name='vendor[pickup]' id="pickup" value='<? if ($vendor[pickup]=='T') echo "T"; else echo "F";?>' />
	<tr>
		<td colspan="2">
<table border="0" cellspacing="0" cellpadding="2" width="100%">
			  <tr>
			  	<th class="infoBoxContents" colspan="8">&nbsp;<b>Credit Card Info</b></th>
			  </tr>
                      <tr>
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main"><span class="red">*</span> Credit Card Owner:</td>
                        <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main" width="80%"><input type="text" id="cc_owner" name="vendor[cc_owner]" value="<?=$vendor[cc_owner]?>"></td>
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                      </tr>
                      <tr>
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main"><span class="red">*</span> Credit Card Number:</td>
                        <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main"><input type="text" id="cc_number" name="vendor[cc_number]" value="<?=$vendor[cc_number]?>" onKeyDown='return checkDigit();'><img src="../images/credit_cards.gif" border="0" alt="Credit cards we accept: Visa, Mastercard, American Express, Discover. Your credit card type is detected automatically." title=" Credit cards we accept: Visa, Mastercard, American Express, Discover. Your credit card type is detected automatically. " width="110" height="15"></td>
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>

                      </tr>
                      <tr>
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main"><span class="red">*</span> Expiration Date:</td>
                        <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main"><? echo tep_draw_pull_down_menu('vendor[cc_expires_month]', $expires_month, $vendor[cc_expires_month]) . '&nbsp;' . tep_draw_pull_down_menu('vendor[cc_expires_year]', $expires_year, $vendor[cc_expires_year]); ?></td>
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                      </tr>
                      <tr>
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>                        <td class="main">Card Verification Value:</td>
                        <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main"><input type="text" name="vendor[cc_checkcode]" value="<? if (intval($vendor[cc_checkcode])!=0) echo $vendor[cc_checkcode]?>"  size="4" maxlength="4">&nbsp;&nbsp;  Not present<input type="checkbox" name="vendor[cc_checkcode]" value="0" <? if ($vendor[cc_checkcode]=='0') echo "checked";?> >&nbsp;&nbsp;</small><a href="javascript:popupWindow('http://www.travelvideostore.com/popup_cvs_help.php')">What is my CVV Number[?]</a></td>
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                      </tr>
                    </table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table width="100%" border="0">
			  <tr>
			  	<th class="infoBoxContents" colspan="6">&nbsp;<b>Pickup products?</b>&nbsp;&nbsp;<input type="checkbox" id="checkbox_pickup" name="checkbox_pickup" value="T" onclick="markShipping(this);" <?if ($vendor[pickup]=='T') echo "checked";?> /></th>
			  </tr>
		</td>
	</tr>
	<tr valign="top">
		<td width="50%">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			  	<th class="infoBoxContents" colspan="6">&nbsp;<b><?php echo TXT_BILLING_ADDRESS; ?></b></th>
			  </tr>
			  <tr>
					<td class="main" style="width: 100px"><span class="red">*</span> First Name: </td><td colspan="5"><input id="billing_fname" type="text" name="vendor[vendors_bill_fname]" style="width:250px;" value='<?=$vendor[vendors_bill_fname]?>' /></td>
			  </tr>
			  <tr>
					<td class="main" style="width: 100px"><span class="red">*</span> Last Name: </td><td colspan="5"><input id="billing_lname" type="text" name="vendor[vendors_bill_lname]" style="width:250px;" value='<?=$vendor[vendors_bill_lname]?>' /></td>
			  </tr>
			  <tr>
					<td class="main" style="width: 100px">Company Name: </td><td colspan="5"><input type="text" name="vendor[vendors_bill_company]" style="width:250px;" value='<?=$vendor[vendors_bill_company]?>' /></td>
			  </tr>
			  <tr>
					<td class="main" style="width: 100px"><span class="red">*</span> <?php echo TXT_ADDRESS1; ?></td><td colspan="5"><input id="billing_addr1" type="text" name="vendor[vendors_bill_addr1]" style="width:250px;" value='<?=$vendor['vendors_bill_addr1']?>' /></td>
			  </tr>
			  <tr>
					<td class="main"><?php echo TXT_ADDRESS2; ?></td><td colspan="5"><input type="text" name="vendor[vendors_bill_addr2]" style="width:250px;" value='<?=$vendor[vendors_bill_addr2]?>' /></td>
			  </tr>
			  <tr>
					<td class="main"><span class="red">*</span> <?php echo TXT_CITY; ?></td><td><input type="text" id="billing_city" name="vendor[vendors_bill_city]" style="width:250px;" value='<?=$vendor['vendors_bill_city']?>' /></td></tr>
			  <?
			  		$bill_country_sel = tep_db_query("select * from ". TABLE_COUNTRIES ." where countries_id = ". intval($vendor['bill_country']));
			  		$bill_country = tep_db_fetch_array($bill_country_sel);
			  ?>
			 	<tr>
					<td class="main"><span class="red">*</span> <?php echo TXT_COUNTRY; ?></td><td><?php echo tep_get_country_list('vendor[bill_country]',$vendor[bill_country],'id="billing_country" onChange="changeState(this.value,\'billing\');"');?></td>
				</tr>
				<tr>
					<td class="main"><span class="red">*</span> <?php echo TXT_STATE; ?></td>
					<td>
<span id="billing_country_223" style="display:none;">
<?
        $zones_array_223 = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '223' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array_223[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('vendor[bill_state]', $zones_array_223, $vendor[bill_state],'id="billing_state_223"');
?>
</span>
<span id="billing_country_38" style="display:none;">
<?
        $zones_array_38 = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '38' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array_38[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('vendor[bill_state]', $zones_array_38, $vendor[bill_state],'id="billing_state_38"');
?>
</span>
<span id="billing_country_81" style="display:none;">
<?
        $zones_array_81 = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '81' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array_81[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('vendor[bill_state]', $zones_array_81, $vendor[bill_state],'id="billing_state_81"');
?>
</span>
<span id="billing_country_14" style="display:none;">
<?
        $zones_array_14 = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '14' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array_14[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('vendor[bill_state]', $zones_array_14, $vendor[bill_state],'id="billing_state_14"');
?>
</span>
<span id="billing_country_204" style="display:none;">
<?
        $zones_array_204 = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '204' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array_204[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('vendor[bill_state]', $zones_array_204, $vendor[bill_state],'id="billing_state_204"');
?>
</span>
<span id="billing_country_195" style="display:none;">
<?
        $zones_array_195 = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '195' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array_195[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('vendor[bill_state]', $zones_array_195, $vendor[bill_state],'id="billing_state_195"');
?>
</span>
<span id="billing_country_default" style="display:none;">
	<?=tep_draw_input_field('vendor[bill_state]',$vendor[bill_state],'id="billing_state_default"')?>
</span>
					</td>
				</tr>
				<tr>
					<td class="main"><span class="red">*</span> <?php echo TXT_ZIP; ?></td>
					<td><input type="text" id="billing_zip" name="vendor[vendors_bill_zip]" style="width:80px;" value='<?=$vendor['vendors_bill_zip']?>' onKeyDown='return checkDigit();' /></td>
				</tr>
			</table>
		</td>
		<td width="50%">
			<table width="100%" cellspacing="0" cellpadding="0">
				<tr>
			  	<th class="infoBoxContents" colspan="6">&nbsp;<b><?php echo TXT_SHIPPING_ADDRESS; ?></b></th>
			  </tr>
			  <tr>
					<td class="main" style="width: 100px"><span class="red">*</span> First Name: </td><td colspan="5"><input type="text" id="shipping_fname" name="vendor[vendors_ship_fname]" style="width:250px;" value='<?=$vendor[vendors_ship_fname]?>' /></td>
			  </tr>
			  <tr>
					<td class="main" style="width: 100px"><span class="red">*</span> Last Name: </td><td colspan="5"><input type="text" id="shipping_lname" name="vendor[vendors_ship_lname]" style="width:250px;" value='<?=$vendor[vendors_ship_lname]?>' /></td>
			  </tr>
			  <tr>
					<td class="main" style="width: 100px">Company Name: </td><td colspan="5"><input type="text" name="vendor[vendors_ship_company]" style="width:250px;" value='<?=$vendor[vendors_ship_company]?>' id="shipping_company" /></td>
			  </tr>
			  <tr>
			  	<td class="main" style="width: 100px"><span class="red">*</span> <?php echo TXT_ADDRESS1; ?></td><td><input type="text" id="shipping_addr1" name="vendor[vendors_ship_addr1]" style="width:250px;" value='<?=$vendor['vendors_ship_addr1']?>'/></td>
			  </tr>
			  <tr>
			  	<td class="main"><?php echo TXT_ADDRESS2; ?></td><td><input type="text" name="vendor[vendors_ship_addr2]" id="shipping_addr2" style="width:250px;" value='<?=$vendor['vendors_ship_addr2']?>'/></td>
			  </tr>
			  <tr>
			  	<td class="main"><span class="red">*</span> <?php echo TXT_CITY; ?></td><td><input type="text" id="shipping_city" name="vendor[vendors_ship_city]" style="width:250px;" value='<?=$vendor['vendors_ship_city']?>'/></td>
			  </tr>
			  <?
			  		$ship_country_sel = tep_db_query("select * from ". TABLE_COUNTRIES ." where countries_id = ". intval($vendor['ship_country']));
			  		$ship_country = tep_db_fetch_array($ship_country_sel);
			  ?>
			  <tr>
					<td class="main"><span class="red">*</span> <?php echo TXT_COUNTRY; ?></td>
					<td>
					<?php echo tep_get_country_list('vendor[ship_country]',$vendor[ship_country],'id="shipping_country" onchange="changeState(this.value, \'shipping\')"');?>
					</td>
			</tr>		
			<tr>
			  	<td class="main"><span class="red">*</span> <?php echo TXT_STATE; ?></td>
				<td>
<span id="shipping_country_223" style="display:none;">
<?
        echo tep_draw_pull_down_menu('vendor[ship_state]', $zones_array_223, $vendor[ship_state],'id="shipping_state_223"');
?>
</span>
<span id="shipping_country_38" style="display:none;">
<?
        echo tep_draw_pull_down_menu('vendor[ship_state]', $zones_array_38, $vendor[ship_state],'id="shipping_state_38"');
?>
</span>
<span id="shipping_country_81" style="display:none;">
<?
        echo tep_draw_pull_down_menu('vendor[ship_state]', $zones_array_81, $vendor[ship_state],'id="shipping_state_81"');
?>
</span>
<span id="shipping_country_14" style="display:none;">
<?
        echo tep_draw_pull_down_menu('vendor[ship_state]', $zones_array_14, $vendor[ship_state],'id="shipping_state_14"');
?>
</span>
<span id="shipping_country_204" style="display:none;">
<?
        echo tep_draw_pull_down_menu('vendor[ship_state]', $zones_array_204, $vendor[ship_state],'id="shipping_state_204"');
?>
</span>
<span id="shipping_country_195" style="display:none;">
<?
        echo tep_draw_pull_down_menu('vendor[ship_state]', $zones_array_195, $vendor[ship_state],'id="shipping_state_195"');
?>
</span>
<span id="shipping_country_default" style="display:none;">
	<?=tep_draw_input_field('vendor[ship_state]',$vendor[ship_state],'id="shipping_state_default"')?>
</span>
</td>
			</tr>
			  <tr><td class="main"><span class="red">*</span> <?php echo TXT_ZIP; ?></td><td><input id="shipping_zip" type="text" name="vendor[vendors_ship_zip]" style="width:80px;" value='<?=$vendor['vendors_ship_zip']?>' onKeyDown='return checkDigit();' /></td></tr>
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
		<td align="right" colspan="8"><input type="image" src="<? echo '../includes/languages/english/images/buttons/button_continue.gif';?>" /></td>
	</tr>
	<tr>
		<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
	</tr>
</form>
<? } ?>
	        <tr>
	  	  	<td align="center" colspan="6">
	  	  		<table border="0" width="80%" cellspacing="0" cellpadding="0">
	  	  		<tr>
					<td width="25%">
	  	  				<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  	  				<tr>
	  	  					<td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
	  	  					<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  				</tr>
	  	  				</table>

</td>
	  	  			<td width="25%">
	  	  				<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  	  				<tr>
	  	  					<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  					<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  				</tr>
	  	  				</table>
	  	  			</td>
	  	  			<td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  			<td width="25%">
	  	  				<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  	  				<tr>
	  	  					<td width="100%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
	  	  					<td width=""><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
	  	  				</tr>
	  	  				</table>
	  	  			</td>
	  	  		</tr>
	  	  		<tr>
	  	  			<td align="center" width="25%" class="checkoutBarCurrent">Shopping Cart</td>
	  	  			<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>
	  	  			<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
	  	  			<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
	  	  		</tr>
	  	  		</table>
	  	  	</td>
	</tr>
	</table></td></tr>
    </table>
<br/>
</td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require('footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?if ($total_products>0) {?>
<script>
	changeState('<?=$vendor[bill_country]?>','billing');
	changeState('<?=$vendor[ship_country]?>','shipping');
	markShipping(document.getElementById('checkbox_pickup'));
</script>
<?}?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
