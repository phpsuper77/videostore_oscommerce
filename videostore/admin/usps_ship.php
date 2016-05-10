<?php
/*
  $Id: usps_ship.php,v 2.0.2 2006/08/21 Brandon Clark

  Based on usps_shippinglabel
  but completely redone to keep up with the USPS upgrades and add international support
  added configuration changes from dwdonline and other features
  
  Copyright (c) 2002-2006 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
$oID = (int)tep_db_prepare_input($HTTP_GET_VARS['oID']);
include(DIR_WS_CLASSES . 'order.php');
$order = new order($oID);

$USPS_file_dir = DIR_WS_INCLUDES . 'usps_ship_files';    //directory where all support files are

//Create a random package id for usps
$packageID = rand(100000000,1000000000);

//Gets the return & billing address two digit state code
$shipping_zone_query = tep_db_query("select z.zone_code from " . TABLE_ZONES . " z, " . TABLE_COUNTRIES . " c where zone_name = '" . $order->delivery['state'] . "' AND c.countries_name = '" . $order->delivery['country'] . "' AND c.countries_id = z.zone_country_id");
$shipping_zone = tep_db_fetch_array($shipping_zone_query);
$shipping_zone_code = ($shipping_zone['zone_code'] == '' ? $order->delivery['state'] : $shipping_zone['zone_code']);  // if the query result was empty, then use the state name
if ($order->billing['state'] == $order->delivery['state']) {  // if billing and shipping states are the same, then we can save a query
  $billing_zone_code = $shipping_zone_code;
  } else {
  $billing_zone_query = tep_db_query("select z.zone_code from " . TABLE_ZONES . " z, " . TABLE_COUNTRIES . " c where z.zone_name = '" . $order->billing['state'] . "' AND c.countries_name = '" . $order->billing['country'] . "' AND c.countries_id = z.zone_country_id");
  $billing_zone = tep_db_fetch_array($billing_zone_query);
  $billing_zone_code = ($billing_zone['zone_code'] == '' ? $order->billing['state'] : $billing_zone['zone_code']); // if the query result was empty, then use the state name
  }

// Weight Calculations
if (USPS_WEIGHT_OVERRIDE != '') {
  $shipping_weight = USPS_WEIGHT_OVERRIDE;
  } else {
  $weight_query = tep_db_query("select sum(op.products_quantity * p.products_weight) as weight from " . TABLE_PRODUCTS . " p, " . TABLE_ORDERS_PRODUCTS . " op where op.products_id = p.products_id AND op.orders_id = '" . (int)$oID . "'");
  $total_weight = tep_db_fetch_array($weight_query);
  $shipping_weight =  $total_weight['weight'] + SHIPPING_BOX_WEIGHT;  // adds the "Package Tare weight" configuration value to the package value
  $shipping_weight = ($shipping_weight < 0.0625 ? 0.0625 : $shipping_weight); // if shipping weight is less than one ounce then make it one ounce
  }
$shipping_weight = ceil($shipping_weight*16)/16;  // rounds up to the next ounce, 4.6 oz becomes 5 oz, 15.7 oz becomes 1 lb
$shipping_pounds = floor ($shipping_weight);
$shipping_ounces = (16 * ($shipping_weight - floor($shipping_weight)));

//Value of the contents
// Note: Value of Contents takes the first value of order_totals, which is usually the subtotal.
//       If any other total modules are first, then you may have problems.
$contents_value = ceil(substr(strip_tags($order->totals[0]['text']),1));
$send_value = (USPS_SEND_VALUE_OVER > $contents_value ? '' : $contents_value); 

//setting of the countries
//this is creating an array of all the country names in OSC and mapping them to the code used at usps.com
//If the names weren't the same, then I just made them empty so that it would prompt you to select a country
$country_list = array(
"Afghanistan" => 10000,
"Albania" => 10001,
"Algeria" => 10002,
"American Samoa" => "",
"Andorra" => 10003,
"Angola" => 10004,
"Anguilla" => 10005,
"Antarctica" => "",
"Antigua and Barbuda" => 10006,
"Argentina" => 10009,
"Armenia" => 10010,
"Aruba" => 10011,
"Australia" => 10013,
"Austria" => 10026,
"Azerbaijan" => 10027,
"Bahamas" => 10028,
"Bahrain" => 10029,
"Bangladesh" => 10030,
"Barbados" => 10031,
"Belarus" => 10032,
"Belgium" => 10033,
"Belize" => 10034,
"Benin" => 10036,
"Bermuda" => 10038,
"Bhutan" => 10039,
"Bolivia" => 10040,
"Bosnia and Herzegowina" => 10041,
"Botswana" => 10042,
"Bouvet Island" => "",
"Brazil" => 10043,
"British Indian Ocean Territory" => "",
"Brunei Darussalam" => 10045,
"Bulgaria" => 10046,
"Burkina Faso" => 10047,
"Burundi" => 10050,
"Cambodia" => 10051,
"Cameroon" => 10053,
"Canada" => 10054,
"Cape Verde" => 10057,
"Cayman Islands" => 10058,
"Central African Republic" => 10059,
"Chad" => 10060,
"Chile" => 10062,
"China" => 10063,
"Christmas Island" => "",
"Cocos (Keeling) Islands" => "",
"Colombia" => 10067,
"Comoros" => 10069,
"Congo" => "",
"Cook Islands" => 12163,
"Costa Rica" => 10080,
"Cote D'Ivoire" => "",
"Croatia" => 10082,
"Cuba" => 10083,
"Cyprus" => 10085,
"Czech Republic" => 10086,
"Denmark" => 10087,
"Djibouti" => 10088,
"Dominica" => 10091,
"Dominican Republic" => 10092,
"East Timor" => 12162,
"Ecuador" => 10093,
"Egypt" => 10094,
"El Salvador" => 10095,
"Equatorial Guinea" => 10100,
"Eritrea" => 10103,
"Estonia" => 10104,
"Ethiopia" => 10105,
"Falkland Islands (Malvinas)" => 10106,
"Faroe Islands" => 10108,
"Fiji" => 10109,
"Finland" => 10111,
"France" => 10113,
"France, Metropolitan" => 10114,
"French Guiana" => 10117,
"French Polynesia" => 10120,
"French Southern Territories" => "",
"Gabon" => 10134,
"Gambia" => 10135,
"Georgia" => 10136,
"Germany" => 10137,
"Ghana" => 10138,
"Gibraltar" => 10139,
"Greece" => 10156,
"Greenland" => 10169,
"Grenada" => 10170,
"Guadeloupe" => 10174,
"Guam" => "",
"Guatemala" => 10181,
"Guinea" => 10182,
"Guinea-bissau" => 10183,
"Guyana" => 10185,
"Haiti" => 10186,
"Heard and Mc Donald Islands" => "",
"Honduras" => 10187,
"Hong Kong" => 10189,
"Hungary" => 10191,
"Iceland" => 10192,
"India" => 10197,
"Indonesia" => 10202,
"Iran (Islamic Republic of)" => 10206,
"Iraq" => 10208,
"Ireland" => 10210,
"Israel" => 10211,
"Italy" => 10212,
"Jamaica" => 10213,
"Japan" => 10214,
"Jordan" => 10221,
"Kazakhstan" => 10223,
"Kenya" => 10224,
"Kiribati" => 10230,
"Korea, Democratic People's Republic of" => 10232,
"Korea, Republic of" => 10234,
"Kuwait" => 10236,
"Kyrgyzstan" => 10237,
"Lao People's Democratic Republic" => "",
"Latvia" => 10239,
"Lebanon" => 10240,
"Lesotho" => 10241,
"Liberia" => 10242,
"Libyan Arab Jamahiriya" => "",
"Liechtenstein" => 10247,
"Lithuania" => 10248,
"Luxembourg" => 10249,
"Macau" => 12084,
"Macedonia, The Former Yugoslav Republic of" => 10252,
"Madagascar" => 10253,
"Malawi" => 10256,
"Malaysia" => 10265,
"Maldives" => 10278,
"Mali" => 10279,
"Malta" => 10282,
"Marshall Islands" => 10468,
"Martinique" => 10283,
"Mauritania" => 10284,
"Mauritius" => 10285,
"Mayotte" => 12262,
"Mexico" => 10287,
"Micronesia, Federated States of" => 10469,
"Moldova, Republic of" => 10288,
"Monaco" => 12276,
"Mongolia" => 10289,
"Montserrat" => 10290,
"Morocco" => 10291,
"Mozambique" => 10292,
"Myanmar" => 12269,
"Namibia" => 10293,
"Nauru" => 10295,
"Nepal" => 10296,
"Netherlands" => 10298,
"Netherlands Antilles" => 10301,
"New Caledonia" => 10313,
"New Zealand" => 10324,
"Nicaragua" => 10335,
"Niger" => 10336,
"Nigeria" => 10337,
"Niue" => 12258,
"Norfolk Island" => 12259,
"Northern Mariana Islands" => "",
"Norway" => 10338,
"Oman" => 10341,
"Pakistan" => 10343,
"Palau" => "",
"Panama" => 10344,
"Papua New Guinea" => 10352,
"Paraguay" => 10353,
"Peru" => 10354,
"Philippines" => 10355,
"Pitcairn" => 10356,
"Poland" => 10357,
"Portugal" => 10362,
"Puerto Rico" => "",
"Qatar" => 10364,
"Reunion" => 10367,
"Romania" => 10368,
"Russian Federation" => "",
"Rwanda" => 10370,
"Saint Kitts and Nevis" => "",
"Saint Lucia" => 10403,
"Saint Vincent and the Grenadines" => 10407,
"Samoa" => "",
"San Marino" => 10371,
"Sao Tome and Principe" => 10372,
"Saudi Arabia" => 10373,
"Senegal" => 10374,
"Seychelles" => 10376,
"Sierra Leone" => 10377,
"Singapore" => 10378,
"Slovakia (Slovak Republic)" => "",
"Slovenia" => 10380,
"Solomon Islands" => 10382,
"Somalia" => 10384,
"South Africa" => 10386,
"South Georgia and the South Sandwich Islands" => "",
"Spain" => 10395,
"Sri Lanka" => 10398,
"St. Helena" => 10519,
"St. Pierre and Miquelon" => 10520,
"Sudan" => 10408,
"Suriname" => 10409,
"Svalbard and Jan Mayen Islands" => "",
"Swaziland" => 10410,
"Sweden" => 10411,
"Switzerland" => 10412,
"Syrian Arab Republic" => 10413,
"Taiwan" => 10418,
"Tajikistan" => 10419,
"Tanzania, United Republic of" => 10421,
"Thailand" => 10424,
"Togo" => 10425,
"Tokelau" => "",
"Tonga" => 10427,
"Trinidad and Tobago" => 10429,
"Tunisia" => 10431,
"Turkey" => 10432,
"Turkmenistan" => 10433,
"Turks and Caicos Islands" => 10435,
"Tuvalu" => 10437,
"Uganda" => 10438,
"Ukraine" => 10439,
"United Arab Emirates" => 10448,
"United Kingdom" => 10150,
"United States" => 1,
"United States Minor Outlying Islands" => "",
"Uruguay" => 10449,
"Uzbekistan" => 10495,
"Vanuatu" => 10499,
"Vatican City State (Holy See)" => 10500,
"Venezuela" => 10501,
"Viet Nam" => 10502,
"Virgin Islands (British)" => 12023,
"Virgin Islands (U.S.)" => "",
"Wallis and Futuna Islands" => 10504,
"Western Sahara" => "",
"Yemen" => 10513,
"Yugoslavia" => 12226,
"Zaire" => 12025,
"Zambia" => 10514,
"Zimbabwe" => 10516);

$country_code_delivery = $country_list[$order->delivery['country']];
$country_code_billing = $country_list[$order->billing['country']];
$country_code = (USPS_SHIP_ADDRESS == 'Shipping' ? $country_code_delivery : $country_code_billing);

?>


<html><head>
<meta http-equiv="expires" content="0">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache, must-revalidate">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta content="USPS" -="" enter="" label="" information="" name="title">
<meta content="" name="author">
<meta content="Create A Shipping Label" name="description">
<meta content="01/01/2011" name="expiration date">
<meta content="Click-N-Ship, Shipping Labels, Print Shipping Labels, Online Labels, PC Postage, Express Mail, Priority Mail, The United States Postal Service" name="keywords">
<meta content="index,follow" name="robots">

<link href="<?php echo $USPS_file_dir; ?>/styles.css" type="text/css" rel="stylesheet">
<link href="<?php echo $USPS_file_dir; ?>/stylesprint.css" type="text/css" rel="stylesheet" media="print">
<script src="<?php echo $USPS_file_dir; ?>/testRadioButton.js"></script>
<script src="<?php echo $USPS_file_dir; ?>/logonSessionMaintenance.js"></script>
<script src="<?php echo $USPS_file_dir; ?>/addressPopup.js"></script>
<script src="<?php echo $USPS_file_dir; ?>/common.js"></script>
<script src="<?php echo $USPS_file_dir; ?>/signIn.js"></script>

<script language="JavaScript">
function clearAddress (addrType)
{
  theForm = document.forms[0];
  if (addrType == "return")
    {
    theForm.returnFullName.value = "";
    theForm.returnShortName.value = "";
    theForm.returnCompanyName.value = "";
    theForm.returnAddressOne.value = "";
    theForm.returnAddressTwo.value = "";
    theForm.returnUrbanization.value = "";
    theForm.returnCity.value = "";
    theForm.returnState.value = "";
    theForm.returnZipcode.value = "";
    theForm.returnPhoneNumber.value = "" ;
    theForm.shipFromZipCode[0].checked = true ;
    theForm.otherZipCode.value = "" ;
    theForm.shippingDate.selectedIndex = <?php echo (date('G') < USPS_CUTOFF_HOUR ? 0 : 1);?> ;
    if (theForm.return_save_addr)
      theForm.return_save_addr.checked = false;
    theForm.returnFullName.focus();
    }
  else if (addrType == "return_here")
    {
    theForm.returnFullName.value = "<?php echo USPS_RETURN_NAME; ?>";
    theForm.returnShortName.value = "";
    theForm.returnCompanyName.value = "<?php echo USPS_RETURN_COMPANY; ?>";
    theForm.returnAddressOne.value = "<?php echo USPS_RETURN_STREET; ?>";
    theForm.returnAddressTwo.value = "<?php echo USPS_RETURN_STREET2; ?>";
    theForm.returnUrbanization.value = "";
    theForm.returnCity.value = "<?php echo USPS_RETURN_CITY; ?>";
    theForm.returnState.value = "<?php echo USPS_RETURN_STATE; ?>";
    theForm.returnZipcode.value = "<?php echo USPS_RETURN_ZIP; ?>";
    theForm.returnPhoneNumber.value = "<?php echo USPS_PHONE; ?>" ;
    theForm.shipFromZipCode[<?php echo (USPS_SHIP_FROM_ZIP == ''  ? '0' : '1'); ?>].checked = true ;
    theForm.otherZipCode.value = "<?php echo (USPS_SHIP_FROM_ZIP == ''  ? '' : USPS_SHIP_FROM_ZIP); ?>" ;
    theForm.shippingDate.selectedIndex = <?php echo (date('G') < USPS_CUTOFF_HOUR ? 0 : 1);?> ;
    if (theForm.return_save_addr)
      theForm.return_save_addr.checked = false;
    theForm.returnFullName.focus();
    }
  else if (addrType == "return_cust_shipping")
    {
    theForm.returnFullName.value = "<?php echo $order->delivery['name'];?>";
    theForm.returnShortName.value = "";
    theForm.returnCompanyName.value = "<?php echo $order->delivery['company'];?>";
    theForm.returnAddressOne.value = "<?php echo $order->delivery['street_address'];?>";
    theForm.returnAddressTwo.value = "<?php echo $order->delivery['suburb'];?>";
    theForm.returnUrbanization.value = "";
    theForm.returnCity.value = "<?php echo $order->delivery['city'];?>";
    theForm.returnState.value = "<?php echo $shipping_zone_code ;?>";
    theForm.returnZipcode.value = "<?php echo $order->delivery['postcode'];?>";
    theForm.returnPhoneNumber.value = "<?php echo preg_replace('/[^0-9]/','',$order->customer['telephone']); ?>" ;
    theForm.shipFromZipCode[0].checked = true ;
    theForm.otherZipCode.value = "" ;
    theForm.shippingDate.selectedIndex = 3 ;
    if (theForm.return_save_addr)
      theForm.return_save_addr.checked = false;
    theForm.returnFullName.focus();
    }
  else if (addrType == "return_cust_billing")
    {
    theForm.returnFullName.value = "<?php echo $order->billing['name'];?>";
    theForm.returnShortName.value = "";
    theForm.returnCompanyName.value = "<?php echo $order->billing['company'];?>";
    theForm.returnAddressOne.value = "<?php echo $order->billing['street_address'];?>";
    theForm.returnAddressTwo.value = "<?php echo $order->billing['suburb'];?>";
    theForm.returnUrbanization.value = "";
    theForm.returnCity.value = "<?php echo $order->billing['city'];?>";
    theForm.returnState.value = "<?php echo $billing_zone_code ;?>";
    theForm.returnZipcode.value = "<?php echo $order->billing['postcode'];?>";
    theForm.returnPhoneNumber.value = "<?php echo preg_replace('/[^0-9]/','',$order->customer['telephone']); ?>" ;
    theForm.shipFromZipCode[0].checked = true ;
    theForm.otherZipCode.value = "" ;
    theForm.shippingDate.selectedIndex = 3 ;
    if (theForm.return_save_addr)
      theForm.return_save_addr.checked = false;
    theForm.returnFullName.focus();
    }
  else if (addrType == "delivery")
  {
    if (theForm.shortName)
    {
      theForm.shortName.selectedIndex = 0;
      theForm.shortName.value = "";
    }
    theForm.deliveryCountry.value = "1";
    theForm.deliveryFullName.value = "";
    theForm.deliveryCompanyName.value = "";
    theForm.deliveryAddressOne.value = "";
    theForm.deliveryAddressTwo.value = "";
    theForm.deliveryAddressThree.value = "";
    theForm.deliveryUrbanization.value = "";
    theForm.deliveryCity.value = "";
    theForm.deliveryState.value = "";
    theForm.deliveryZipcode.value = "";
    theForm.deliveryPostalCode.value = "";
    theForm.deliveryPhoneNumber.value = "" ;
    theForm.deliveryFaxNumber.value = "" ;
    theForm.deliveryEmail.value = "";
    theForm.province.value = "" ;
    if (theForm.saveDeliveryAddress)
      theForm.saveDeliveryAddress.checked = false;
    theForm.emailNotification.checked = false;
    hideElement('UpdateAddress');
    showElement('SaveAddress');
    doCountry();
    theForm.deliveryFullName.focus();
  }
  else if (addrType == "delivery_here")
  {
    if (theForm.shortName)
    {
      theForm.shortName.selectedIndex = 0;
      theForm.shortName.value = "";
    }
    theForm.deliveryCountry.value = "1";
    theForm.deliveryFullName.value = "<?php echo USPS_RETURN_NAME; ?>";
    theForm.deliveryCompanyName.value = "<?php echo USPS_RETURN_COMPANY; ?>";
    theForm.deliveryAddressOne.value = "<?php echo USPS_RETURN_STREET; ?>";
    theForm.deliveryAddressTwo.value = "<?php echo USPS_RETURN_STREET2; ?>";
    theForm.deliveryAddressThree.value = "";
    theForm.deliveryUrbanization.value = "";
    theForm.deliveryCity.value = "<?php echo USPS_RETURN_CITY; ?>";
    theForm.deliveryState.value = "<?php echo USPS_RETURN_STATE; ?>";
    theForm.deliveryZipcode.value = "<?php echo USPS_RETURN_ZIP; ?>";
    theForm.deliveryPostalCode.value = "";
    theForm.deliveryPhoneNumber.value = "<?php echo USPS_PHONE; ?>" ;
    theForm.deliveryFaxNumber.value = "" ;
    theForm.deliveryEmail.value = "";
    theForm.province.value = "" ;
    if (theForm.saveDeliveryAddress)
      theForm.saveDeliveryAddress.checked = false;
    theForm.emailNotification.checked = false;
    hideElement('UpdateAddress');
    showElement('SaveAddress');
    doCountry();
    theForm.deliveryFullName.focus();
  }
  else if (addrType == "delivery_cust_shipping")
  {
    if (theForm.shortName)
    {
      theForm.shortName.selectedIndex = 0;
      theForm.shortName.value = "";
    }
    theForm.deliveryCountry.value = "<?php echo $country_code_delivery; ?>";
    theForm.deliveryFullName.value = "<?php echo $order->delivery['name'];?>";
    theForm.deliveryCompanyName.value = "<?php echo $order->delivery['company'];?>";
    theForm.deliveryAddressOne.value = "<?php echo $order->delivery['street_address'];?>";
    theForm.deliveryAddressTwo.value = "<?php echo $order->delivery['suburb'];?>";
    theForm.deliveryAddressThree.value = "";
    theForm.deliveryUrbanization.value = "";
    theForm.deliveryCity.value = "<?php echo $order->delivery['city'];?>";
<?php
    if ($country_code_delivery == 1) { ?> 
    theForm.deliveryState.value = "<?php echo $shipping_zone_code ;?>";
    theForm.deliveryZipcode.value = "<?php echo $order->delivery['postcode'];?>";
    theForm.province.value = "" ;
    theForm.deliveryPostalCode.value = "";
<?php } else { ?>
    theForm.deliveryState.value = "";
    theForm.deliveryZipcode.value = "";
    theForm.province.value = "<?php echo $order->delivery['state']; ?>" ;
    theForm.deliveryPostalCode.value = "<?php echo $order->delivery['postcode'];?>";
<?php } ?>
    theForm.deliveryPhoneNumber.value = "<?php echo $order->customer['telephone']; ?>" ;
    theForm.deliveryFaxNumber.value = "" ;
    theForm.deliveryEmail.value = "<?php echo $order->customer['email_address']; ?>";
    if (theForm.saveDeliveryAddress)
      theForm.saveDeliveryAddress.checked = false;
    theForm.emailNotification.checked = <?php echo (USPS_EMAIL == 'YES' ? 'true': 'false');?>;
    hideElement('UpdateAddress');
    showElement('SaveAddress');
    doCountry();
    theForm.deliveryFullName.focus();
  }
  else if (addrType == "delivery_cust_billing")
  {
    if (theForm.shortName)
    {
      theForm.shortName.selectedIndex = 0;
      theForm.shortName.value = "";
    }
    theForm.deliveryCountry.value = "<?php echo $country_code_billing; ?>";
    theForm.deliveryFullName.value = "<?php echo $order->billing['name'];?>";
    theForm.deliveryCompanyName.value = "<?php echo $order->billing['company'];?>";
    theForm.deliveryAddressOne.value = "<?php echo $order->billing['street_address'];?>";
    theForm.deliveryAddressTwo.value = "<?php echo $order->billing['suburb'];?>";
    theForm.deliveryAddressThree.value = "";
    theForm.deliveryUrbanization.value = "";
    theForm.deliveryCity.value = "<?php echo $order->billing['city'];?>";
<?php
    if ($country_code_billing == 1) { ?> 
    theForm.deliveryState.value = "<?php echo $billing_zone_code;?>";
    theForm.deliveryZipcode.value = "<?php echo $order->billing['postcode'];?>";
    theForm.deliveryPostalCode.value = "";
    theForm.province.value = "" ;
<?php } else { ?>
    theForm.deliveryState.value = "";
    theForm.deliveryZipcode.value = "";
    theForm.deliveryPostalCode.value = "<?php echo $order->billing['postcode'];?>";
    theForm.province.value = "<?php echo $order->billing['state']; ?>" ;
<?php } ?>
    theForm.deliveryPhoneNumber.value = "<?php echo $order->customer['telephone']; ?>" ;
    theForm.deliveryFaxNumber.value = "" ;
    theForm.deliveryEmail.value = "<?php echo $order->customer['email_address']; ?>";
    if (theForm.saveDeliveryAddress)
      theForm.saveDeliveryAddress.checked = false;
    theForm.emailNotification.checked = <?php echo (USPS_EMAIL == 'YES' ? 'true': 'false');?>;
    hideElement('UpdateAddress');
    showElement('SaveAddress');
    doCountry();
    theForm.deliveryFullName.focus();
  }
}
function load_script ()
{
document.forms[0].deliveryCountry.value = "<?php echo $country_code;?>";
doCountry();
}
</script>


<title>USPS - Enter Label Information</title></head><body leftmargin="0" topmargin="0" onload="javascript:load_script()" alink="#cc0000" bgcolor="#ffffff" link="#003399" marginheight="0" marginwidth="0" vlink="#999999">

<script src="<?php echo $USPS_file_dir; ?>/windows.js"></script>
<span style="display: none;">Your sign in session will expire after 15
minutes of server inactivity. In order to maintain your data stored in
this session, you will need to navigate to another page in this
application within that timeframe.</span>

<table summary="This table is used to format the header of the page." border="0" cellpadding="0" cellspacing="0" width="720">
  <tbody>
    <tr>
      <td colspan="4"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="7" width="720"></td>
    </tr>
    <tr>
      <td><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="1" width="8"></td>
      <td><a href="http://www.usps.com/" tabindex="1"><img src="<?php echo $USPS_file_dir; ?>/hdr_uspsLogo.jpg" alt="USPS Homepage" border="0" height="25" width="157"></a><a href="#content" tabindex="2"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="Skip Navigation" border="0" height="1" width="1"></a></td>
      <td><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="30" width="332"></td>
      <td class="utilitybar" align="right" valign="bottom" width="222"><span class="prntfriendlynoprnt">
              
        <a class="utilitybar" href="http://www.usps.com/" title="USPS Homepage" tabindex="3">Home</a> | 
        
          <a href="https://ecap21.usps.com/cgi-bin/ecapbv/scripts/login.jsp?app=GSS&appURL=http%3A%2F%2F<?php echo rawurlencode($_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]); ?>" tabindex="4" title="Sign In">Sign In</a>
        
        
        </span>
          </td>
    </tr><tr>
      <td colspan="4"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="9" width="720"></td>
    </tr>
  </tbody>
</table>



<table summary="This table is used to format the Tool Bar navigation." border="0" cellpadding="0" cellspacing="0" width="720">
  <tbody>
    <tr>
      <td bgcolor="#cc0000"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="6" width="1"><br>
      <img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="1" width="1"></td>
    </tr>
  </tbody>
</table>
<div class="titlebars">
<table summary="This table is used to display the top navigation." border="0" cellpadding="0" cellspacing="0" width="720">
  <tbody>
    <tr>
      <td colspan="8"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="1" width="1"></td>
    </tr>
    <tr>
      <td colspan="8"><img src="<?php echo $USPS_file_dir; ?>/top_bar3.gif" alt="" border="0" height="25" width="720"></td>
    </tr>
    
    
      <tr>
        <td height="2" width="167"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="2" width="167"></td>
        <td height="2" width="128"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="2" width="128"></td>
        <td height="2" width="58"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="2" width="58"></td>
        <td height="2" width="96"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="2" width="96"></td>
        <td height="2" width="89"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="2" width="89"></td>
        <td height="2" width="29"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="2" width="29"></td>
        <td height="2" width="59"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="2" width="59"></td>
        <td height="2" width="94"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="2" width="94"></td>
      </tr>
      <tr>
        <td height="18" width="167"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="1" width="28"><img src="<?php echo $USPS_file_dir; ?>/light_blue_signedin_bar.gif" alt="" border="0" height="18" width="139"></td>
        <!-- changed text below to print shipping labels -->
        <td class="bluebarbold" align="center" background="<?php echo $USPS_file_dir; ?>/light_blue_signedin_bar2.gif" height="18" width="128"><a href="https://sss-web.usps.com/cns/landing.do" tabindex="5" class="bluebarbold" title="Print Shipping Labels">Print Shipping Labels</a>&nbsp;</td>
        <td class="bluebarbold" align="center" background="<?php echo $USPS_file_dir; ?>/light_blue_signedin_bar3.gif" height="18" width="58"><a title="FAQs" class="bluebarbold" href="http://www.usps.com/clicknship/faqs.htm" tabindex="6" target="faqWin" onclick='void openWindow("http://www.usps.com/clicknship/faqs.htm", "faqWin", "width=825, height=400", 30, 30, window, "resizable=1,menubar=1,toolbar=1,location=1,personalbar=1,status=1,scrollbars=1")'>FAQs</a>&nbsp;</td>
        <td class="smmainText" align="center" background="<?php echo $USPS_file_dir; ?>/addressbook_bkgd.gif" height="18" width="96"><a href="https://sss-web.usps.com/cns/addressSummary.do" tabindex="7" class="barkerText" title="Address Book">Address Book</a></td>
        <td class="barkerTextgray" align="center" background="<?php echo $USPS_file_dir; ?>/orderhist_bkgd.gif" height="18" width="89">
          <a href="https://sss-web.usps.com/cns/shippingHistoryView.do" tabindex="8" class="barkerText" title="Shipping History">Shipping History</a></td>
          <td><img src="<?php echo $USPS_file_dir; ?>/shoppingcartgray.gif" alt="Shipping Cart" border="0" height="18" width="29"></td>
          <td class="barkerTextgray" background="<?php echo $USPS_file_dir; ?>/cart_bkgd.gif" height="18" width="59">Cart Empty</td>
        <td class="bluebarbold" align="center" background="<?php echo $USPS_file_dir; ?>/myaccount_bkgd.gif" height="18" width="94">&nbsp;&nbsp;&nbsp;<a href="https://sss-web.usps.com/cns/myAccountView.do" tabindex="10" class="bluebarbold" title="My Account">My Account</a></td>
      </tr>
  </tbody>
</table>
</div>

<script src="<?php echo $USPS_file_dir; ?>/labelInformation.js"></script>

<form name="labelInformationForm" method="post" action="https://sss-web.usps.com/cns/labelInformation.do"><div><input name="org.apache.struts.taglib.html.TOKEN" value="0099fa610794d456dbed5272c5a3fab9" type="hidden"></div>
<input type="hidden" name="shortName" value="">
<input type="hidden" name="returnShortName" value="">
<input type="hidden" name="submitControl" value="Go">
<input type="hidden" name="previousPage" value="landingView">
<input type="hidden" name="previousPageParameters" value="deliveryCountry=<?php echo $country_code; ?>">
<input type="hidden" name="nextPage" value="noPage">
<input type="hidden" name="submitType" value="">
<input type="hidden" name="packageId" value="<?php echo $packageID; ?>">
<input type="hidden" name="batch" value="false">
<input type="hidden" name="labelId" value="">

<table summary="This table is used to format page content." border="0" cellpadding="0" cellspacing="0" width="720">
    <tbody><tr>
        <td valign="top" width="45"></td>
        <td align="left" valign="top" width="675">
            <a name="content"></a>
            <table summary="This table is used to display the top navigation." border="0" cellpadding="0" cellspacing="0" width="675">
                <tbody><tr> <td colspan="2" height="49" valign="top" width="675"><img src="<?php echo $USPS_file_dir; ?>/header_print_shipping.gif" alt="Print Shipping Labels" border="0" height="41" width="202"></td> </tr>
                <tr> <td colspan="2" height="1" width="675"></td> </tr>
                <tr> <td colspan="2" bgcolor="#cccccc" height="2" width="675"></td> </tr>
                <tr>
                    <td colspan="2" valign="top" width="620">
                        <table summary="This table is used to format page content." border="0" cellpadding="0" cellspacing="0" width="675">
                            <tbody><tr>
                                <td bgcolor="#cccccc" height="2" width="2"></td>
                                <td valign="top">
                                    <table summary="This table is used for formatting." border="0" cellpadding="0" cellspacing="0" width="671">
                                        <tbody><tr>
                                            <td height="40" width="24"></td>
                                            <td class="mainText" height="36" valign="top" width="647"><img src="<?php echo $USPS_file_dir; ?>/headline_labelinfo.gif" alt="Label Information" border="0" height="29" width="126"></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" width="24"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="1" width="24"></td>
                                            <td width="647">
                                                <table summary="This table is used for formatting." border="0" cellpadding="0" cellspacing="0" width="647">
                                                    <tbody><tr>
                                                        <td class="mainText" width="347">Please fill in the following information:&nbsp;&nbsp;&nbsp;(<a class="redbarkerText">* </a><a class="smmainText">Required Fields)</a></td>
                                                        
                                                          
                                                          
                                                            <td colspan="4" width="300"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="1" width="1"></td>
                                                          
                                                        
                                                        
                                                    </tr>
                                                </tbody></table>
                                            </td>
                                        </tr>
                                    
                                        <tr> <td colspan="2" height="3" valign="top" width="671"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="3" width="1"></td> </tr>
                                        <tr> <td colspan="2" height="13" width="671"><img src="<?php echo $USPS_file_dir; ?>/grey_divider.gif" alt="" border="0" height="13" width="671"></td> </tr>

                                        <tr>
                                            <td colspan="2" height="41" width="671">
                                                <table summary="This table formats page information." border="0" cellpadding="0" cellspacing="0" width="646">
                                                    <tbody><tr>
                                                        <td><a href="javascript:document.forms[0].submitControl.value='Back';document.forms[0].submit()" tabindex="75"><img src="<?php echo $USPS_file_dir; ?>/button_back.gif" alt="Go Back" border="0" height="17" hspace="3" width="43"></a></td>
                                                        <td align="right"><a href="javascript:submitContinue();" tabindex="76"><img src="<?php echo $USPS_file_dir; ?>/button_continue.gif" alt="Continue" border="0" height="17" hspace="3" width="62"></a></td>
                                                    </tr>
                                                </tbody></table>
                                            </td>
                                        </tr>


                                    </tbody></table>
                                    <table summary="This table formats the form information." border="0" cellpadding="0" cellspacing="0" width="671">
                                        <tbody><tr> <td colspan="5" height="10" width="671"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="1" width="1"></td> </tr>
                                        <tr>
                                            <td height="1" width="24"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="1" width="1"></td>
                                            <td valign="top" width="300">
                                                <!-- start of the return address block -->
                                                



 






<input name="returnCountry" value="" type="hidden">

<table summary="This table is used to format delivery address data." border="0" cellpadding="0" cellspacing="0" width="300">
    <tbody><tr>
        <td colspan="2" height="32" valign="top"><img src="<?php echo $USPS_file_dir; ?>/sub_enterreturn_address.gif" alt="Enter Return Address" border="0" height="21" width="119"></td>
        <td colspan="2" align="right" height="32"><a href="javascript:clearAddress('return_here');" tabindex="16" title="Return address = STORE address">Store</a> <a href="javascript:clearAddress('return_cust_shipping');" tabindex="16" title="Return address = CUSTOMERS SHIPPING address (& set ship date to +3 days)">Ship</a> <a href="javascript:clearAddress('return_cust_billing');" tabindex="16" title="Return address = CUSTOMERS BILLING address (& set ship date to +3 days)">Bill</a> <a href="javascript:clearAddress('return');" tabindex="16" title="Clear the return address"><img src="<?php echo $USPS_file_dir; ?>/button_clear.gif" alt="Clear Return Address" border="0" height="17" width="45"></a></td>
    </tr>
    <tr>
        <td bgcolor="#cccccc" height="1" width="1"></td>
        <td bgcolor="#cccccc" width="149"></td>
        <td bgcolor="#cccccc" width="149"></td>
        <td bgcolor="#cccccc" height="1" width="1"></td>
    </tr>
    <tr>
        <td bgcolor="#cccccc" width="1"></td>
        <td colspan="2">
            <table summary="This table is used to format the Delivery Address information." border="0" cellpadding="0" cellspacing="0" width="298">
                <tbody><tr>
                    <!-- top white space inside box -->
                    <td height="10" width="13"></td>
                    <td height="10" width="285"></td>
                </tr>
                <tr>
                    <td width="13"></td>
                    <td>
                        <table summary="This table is used to format the Delivery Address information." border="0" cellpadding="0" cellspacing="0" width="285">
                        
                            <tbody><tr>
                              <td colspan="2"></td>
                                <td>
                  
                    
                      <a href="javascript:loadAddrBookPopupWindow('return', 'false')" tabindex="17" class="mainTextDfltClr" title="Use Address Book">Use Address Book</a>
                    
                    
                  
                                </td>
                            </tr>
                            <tr> <td colspan="3" height="10"></td> </tr>
                            
                            
              
              
                            <tr>
                                <td class="mainText" align="right" width="93"><span class="mainTextRed">* </span><span class="label">Full Name</span></td>
                                <td width="7"></td>
                                <td><input name="returnFullName" maxlength="38" size="19" tabindex="19" value="<?php echo USPS_RETURN_NAME;?>" id="returnFullName" style="width: 175px;" title="Return Address Full Name" type="text"><div style="display: none;"><label for="returnFullName">* Full Name</label></div></td>
              </tr>
              
                        
                    
                            <tr><td colspan="3" height="10"></td> </tr>
                            <tr>
                              <td class="mainText" align="right"><span class="label">Company Name</span></td>
                              <td width="7"></td>
                              <td><input name="returnCompanyName" maxlength="38" size="19" tabindex="20" value="<?php echo USPS_RETURN_COMPANY;?>" id="returnCompanyName" style="width: 175px;" title="Return Address Company Name" type="text"><div style="display: none;"><label for="returnCompanyName">Company Name</label></div></td>
                            </tr>
                            <tr> <td colspan="3" height="10"></td> </tr>
              
              
              
                            
                            <tr>
                                <td class="mainText" align="right"><span class="mainTextRed">* </span><span class="label">Address 1</span></td>
                                <td width="7"></td>
                                <td><input name="returnAddressOne" maxlength="38" size="19" tabindex="21" value="<?php echo USPS_RETURN_STREET; ?>" id="returnAddressOne" style="width: 175px;" title="Return Address Line 1" type="text"><div style="display: none;"><label for="returnAddressOne">* Address 1</label></div></td>
                            </tr>
                            
                        
             
                            <tr> <td colspan="3" height="10"></td> </tr>
                            <tr>
                              <td class="mainText" align="right"><span class="label">Address 2</span></td>
                                <td width="7"></td>
                                <td><input name="returnAddressTwo" maxlength="38" size="19" tabindex="22" value="<?php echo USPS_RETURN_STREET2;?>" id="returnAddressTwo" style="width: 175px;" title="Return Address Line 2" type="text"><div style="display: none;"><label for="returnAddressTwo">Address 2&nbsp;(Apt, floor, suite, PMB, etc)</label></div></td>
                            </tr>
                            <tr>
                <td colspan="2"></td>
                                <td>
                                    <table summary="This table is used to format the Return Address information." border="0" cellpadding="0" cellspacing="0" width="185">
                                        <tbody><tr>
                                            <td class="box" width="160">(Apt, floor, suite, PMB, etc)</td>
                                            <td width="25"></td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                            <tr> <td colspan="3" height="10"></td> </tr>
                            
                            
              
              
                            <tr>
                                <td class="mainText" align="right"><span class="mainTextRed">* </span><span class="label">City</span></td>
                                <td width="7"></td>
                                <td><input name="returnCity" maxlength="38" size="19" tabindex="23" value="<?php echo USPS_RETURN_CITY; ?>" id="returnCity" style="width: 175px;" title="Return Address City" type="text"><div style="display: none;"><label for="returnCity">*  City</label></div></td>
                            </tr>
                            
                        
                            
                            <tr> <td colspan="3" height="10"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="1" width="1"></td> </tr>
                            
                            
              
                            
                            <tr>
                                <td class="mainText" align="right"><span class="mainTextRed">* </span><span class="label">State</span></td>
                                <td width="7"></td>
                                <td><input name="returnState" maxlength="38" size="19" tabindex="24" value="<?php echo USPS_RETURN_STATE;?>" id="returnState" style="width: 175px;" type="text" onchange="checkLabelInfoUrbanization('return', true, this.form)" ><div style="display: none;"><label for="returnState">* State</label></div></td>
                            </tr>
                         
                    
                        <tr> <td colspan="3" height="10"></td> </tr>
                        
                        
                            
                            
                            <tr style="display: none; visibility: hidden;" id="returnUrbanizationRow1">
                <td class="mainText" align="right"><span class="mainText">Urbanization</span></td>
                <td width="7"></td>
                <td><input disabled="disabled" name="returnUrbanization" maxlength="28" size="19" tabindex="25" value="" id="returnUrbanization" style="width: 175px;" title="Return Address Urbanization" type="text"><div style="display: none;"><label for="returnUrbanization">Urbanization&nbsp;(Puerto Rico addresses only)</label></div></td>
              </tr>
              <tr style="display: none; visibility: hidden;" id="returnUrbanizationRow2">
                <td colspan="2"></td>
                <td>
                  <table summary="This table is used to format the Return Address information." border="0" cellpadding="0" cellspacing="0" width="185">
                    <tbody>
                    <tr>
                      <td class="box" width="160">Puerto Rico addresses only</td>
                      <td width="25"></td>
                    </tr>
                    
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr style="display: none; visibility: hidden;" id="returnUrbanizationRow3">
                <td colspan="3" height="10"></td>
              </tr>
                            
                            
              
                            <tr id="returnZipcodeId">
                                <td class="mainText" align="right"><span class="mainTextRed">* </span><span class="label">ZIP Code&#8482;</span></td>
                                <td width="7"></td>
                                <td><input name="returnZipcode" maxlength="10" size="19" tabindex="26" value="<?php echo USPS_RETURN_ZIP; ?>" id="returnZipcode" style="width: 175px;" title="Return Address Zip Code" type="text"><div style="display: none;"><label for="returnZipcode">* ZIP Code&#8482;</label></div></td>
                            </tr>
                            
                            
              
              <tr id="returnZipcodeId2">
                <td colspan="3" height="10"></td>
              </tr>                                                 
                        
                        
              
                            
                            <tr style="display: none; visibility: hidden;" id="returnPhoneNumberId">
                                <td class="mainText" align="right"><span class="mainTextRed">* </span><span class="label">Phone Number</span></td>
                                <td width="7"></td>
                                <td><input name="returnPhoneNumber" maxlength="10" size="19" tabindex="27" value="<?php echo USPS_PHONE; ?>" id="returnPhoneNumber" style="width: 175px;" title="Return Address Phone Number" type="text"><div style="display: none;"><label for="returnPhoneNumber">* Phone Numbner&#8482;</label></div></td>
                            </tr>
                            
                        
                        
                        <tr style="display: none; visibility: hidden;" id="10DigitFormatId">
                              <td align="right"></td>
                                <td width="7"></td>
                                <td>
                                  <table summary="This table is used to format the Return Address information." border="0" cellpadding="0" cellspacing="0" width="185">
                                      <tbody><tr>
                                          <td class="box" width="160">Use 10 digit format 1115550000</td>
                                            <td width="25"></td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                            <tr> <td colspan="3" height="10" width="25"></td> </tr>
                        </tbody></table>
                    </td>
                    <!-- end of text boxes column -->
                </tr>
                <tr>
                <!-- bottom white space inside box -->
                    <td height="7" width="13"></td>
                    <td height="7" width="272"></td>
                </tr>
            </tbody></table>
        </td>
        <td bgcolor="#cccccc" height="15" width="1"></td>
    </tr>
    <tr>
        <td bgcolor="#cccccc" height="1" width="1"></td>
        <td colspan="2" bgcolor="#cccccc"></td>
        <td bgcolor="#cccccc" height="1" width="1"></td>
    </tr>
</tbody></table>

 
                                                <!-- end of the Return Address section -->
                                            </td>
                                            <td width="23"></td>
                                            <td valign="top" width="300">
                                                <!-- begin of Delivery Address section -->


<table summary="This table is used to format delivery address data." border="0" cellpadding="0" cellspacing="0" width="300">
    <tbody><tr>                                                      
        <td colspan="2" height="32" valign="top"><img src="<?php echo $USPS_file_dir; ?>/sub_enterdelivery_address.gif" alt="Enter Delivery Address" border="0" height="23" width="129"></td>                                                        
        <td align="right" height="32">
        <a href="javascript:clearAddress('delivery_here');" title="Make the delivery address the STORE address">Store</a> <a href="javascript:clearAddress('delivery_cust_shipping');" title="Delivery address = CUSTOMERS SHIPPING address">Ship</a> <a href="javascript:clearAddress('delivery_cust_billing');" title="Delivery address = CUSTOMERS BILLING address">Bill</a> <a href="javascript:clearAddress('delivery');" tabindex="28" title="Clear the delivery address"><img src="<?php echo $USPS_file_dir; ?>/button_clear.gif" alt="Clear Delivery Address" border="0" height="17" width="45"></a></td>
    </tr>
    <tr>
        <td bgcolor="#cccccc" height="1" width="1"></td>
        <td bgcolor="#cccccc" width="149"></td>
        <td bgcolor="#cccccc" width="149"></td>
        <td bgcolor="#cccccc" height="1" width="1"></td>
    </tr>
    <tr>
        <td bgcolor="#cccccc" width="1"></td>
        <td colspan="2">
            <table summary="This table is used to format the Delivery Address information." border="0" cellpadding="0" cellspacing="0" width="298">
                <tbody><tr>
                    <!-- top white space inside box -->
                    <td height="10" width="13"></td>
                    <td height="10" width="285"></td>
                </tr>
                <tr>
                    <td width="13"></td>
                    <td>
                        <table summary="This table is used to format the Delivery Address information." border="0" cellpadding="0" cellspacing="0" width="285">
                            <tbody><tr>
                                <td colspan="2"></td>
                                <td>
                  
                    
                      <a href="javascript:loadAddrBookPopupWindow('delivery', 'false')" tabindex="29" class="mainTextDfltClr" title="Use Address Book">Use Address Book</a>
                    
                    
                  
                                </td>
                            </tr>
                            <tr> <td colspan="3" height="10"></td> </tr>

                             
            

                            <tr>
                              
                              
                              
                                <td class="mainText" align="right" height="32"><span class="mainTextRed">* </span><span class="label">Ship to</span></td>
                                <td width="7"></td>
                                <td><?php if ($country_code < 1) echo '<span class="mainTextRed">' . (USPS_SHIP_ADDRESS == 'Shipping' ? $order->delivery['country'] : $order->billing['country']) . ' not found</span><br>';?>
                                    <select name="deliveryCountry" size="1" tabindex="31"  onchange="javascript:doCountry()" id="deliveryCountry" style="width: 175px;" class="mainText"><option value="">Select</option>
<option value="1" selected="selected">United States</option>
<option value="12268">Abu Dhabi</option>
<option value="10440">Abu Dhabi (United Arab Emirates)</option>
<option value="12130">Admiralty Islands</option>
<option value="10345">Admiralty Islands (Papua New Guinea)</option>
<option value="12076">Afars</option>
<option value="10000">Afghanistan</option>
<option value="12131">Aitutaki</option>
<option value="10314">Aitutaki (Cook Islands) (New Zealand)</option>
<option value="12132">Ajman</option>
<option value="10441">Ajman (United Arab Emirates)</option>
<option value="12133">Aland Island</option>
<option value="10110">Aland Island (Finland)</option>
<option value="10001">Albania</option>
<option value="12000">Alberta (Canada)</option>
<option value="12134">Alderney</option>
<option value="10140">Alderney, Channel Islands (Great Britain)</option>
<option value="10002">Algeria</option>
<option value="12135">Alhucemas</option>
<option value="10388">Alhucemas (Spain)</option>
<option value="12145">Alofi Island</option>
<option value="10309">Alofi Island (New Caledonia)</option>
<option value="12137">Andaman Islands</option>
<option value="10193">Andaman Islands (India)</option>
<option value="10003">Andorra</option>
<option value="10004">Angola</option>
<option value="10005">Anguilla</option>
<option value="12129">Anjouan</option>
<option value="10068">Anjouan (Comoros)</option>
<option value="12139">Annobon Island</option>
<option value="10097">Annobon Island (Equatorial Guinea)</option>
<option value="12140">Antigua</option>
<option value="10006">Antigua and Barbuda</option>
<option value="10009">Argentina</option>
<option value="10010">Armenia</option>
<option value="10011">Aruba</option>
<option value="10012">Ascension</option>
<option value="12141">Astypalaia</option>
<option value="10152">Astypalaia (Greece)</option>
<option value="12142">Atafu</option>
<option value="10505">Atafu (Western Samoa)</option>
<option value="12143">Atiu</option>
<option value="12026">Atiu (Cook Islands)</option>
<option value="10013">Australia</option>
<option value="10026">Austria</option>
<option value="12121">Avarua</option>
<option value="10315">Avarua (New Zealand)</option>
<option value="10027">Azerbaijan</option>
<option value="10358">Azores</option>
<option value="10359">Azores (Portugal)</option>
<option value="10028">Bahamas</option>
<option value="10029">Bahrain</option>
<option value="12138">Balearic Islands</option>
<option value="10389">Balearic Islands (Spain)</option>
<option value="12136">Baluchistan</option>
<option value="10342">Baluchistan (Pakistan)</option>
<option value="10030">Bangladesh</option>
<option value="12114">Bank Island</option>
<option value="10496">Banks Island (Vanuatu)</option>
<option value="10031">Barbados</option>
<option value="12115">Barbuda</option>
<option value="10007">Barbuda (Antigua and Barbuda)</option>
<option value="12116">Barthelemy</option>
<option value="10171">Barthelemy (Guadeloupe)</option>
<option value="10032">Belarus</option>
<option value="10033">Belgium</option>
<option value="10034">Belize</option>
<option value="10036">Benin</option>
<option value="10038">Bermuda</option>
<option value="10039">Bhutan</option>
<option value="12117">Bismark Archipelago</option>
<option value="10346">Bismark Archipelago (Papua New Guinea)</option>
<option value="10040">Bolivia</option>
<option value="12118">Bonaire</option>
<option value="10299">Bonaire (Netherlands Antilles)</option>
<option value="12128">Borabora</option>
<option value="10118">Borabora (French Polynesia)</option>
<option value="10199">Borneo (Indonesia)</option>
<option value="10200">Borneo (Kalimantan) (Indonesia)</option>
<option value="10258">Borneo (North) (Malaysia)</option>
<option value="10041">Bosnia-Herzegovina</option>
<option value="10042">Botswana</option>
<option value="12122">Bougainville</option>
<option value="10347">Bougainville (Papua New Guinea)</option>
<option value="12123">Bourbon</option>
<option value="10366">Bourbon (Reunion)</option>
<option value="10043">Brazil</option>
<option value="12001">British Columbia (Canada)</option>
<option value="12124">British Guiana</option>
<option value="10184">British Guiana (Guyana)</option>
<option value="12125">British Honduras</option>
<option value="10035">British Honduras (Belize)</option>
<option value="10044">British Virgin Islands</option>
<option value="10045">Brunei Darussalam</option>
<option value="12126">Buka</option>
<option value="10348">Buka (Papua New Guinea)</option>
<option value="10046">Bulgaria</option>
<option value="10047">Burkina Faso</option>
<option value="10048">Burma</option>
<option value="10050">Burundi</option>
<option value="12127">Caicos Islands</option>
<option value="10434">Caicos Islands (Turks and Caicos Islands)</option>
<option value="10051">Cambodia</option>
<option value="10053">Cameroon</option>
<option value="10054">Canada</option>
<option value="12147">Canary Islands</option>
<option value="10390">Canary Islands (Spain)</option>
<option value="12172">Canton Island</option>
<option value="10225">Canton Island (Kiribati)</option>
<option value="10057">Cape Verde</option>
<option value="10058">Cayman Islands</option>
<option value="10059">Central African Republic</option>
<option value="12144">Ceuta</option>
<option value="10391">Ceuta (Spain)</option>
<option value="12165">Ceylon</option>
<option value="10397">Ceylon (Sri Lanka)</option>
<option value="10060">Chad</option>
<option value="12166">Chaferinas Islands</option>
<option value="10392">Chaferinas Islands (Spain)</option>
<option value="12167">Chalki</option>
<option value="10153">Chalki (Greece)</option>
<option value="12168">Channel Islands</option>
<option value="10141">Channel Islands (Jersey, Guernsey, Alderney and Sark) (Great Britain)</option>
<option value="10062">Chile</option>
<option value="10063">China</option>
<option value="10014">Christmas Island (Australia)</option>
<option value="10226">Christmas Island (Kiribati)</option>
<option value="10453">Chuuk (Truk), Micronesia</option>
<option value="12169">Cocos Island</option>
<option value="10015">Cocos Island (Australia)</option>
<option value="10067">Colombia</option>
<option value="10069">Comoros</option>
<option value="10072">Congo (Brazzaville),Republic of the</option>
<option value="10073">Congo, Democratic Republic of the</option>
<option value="12163">Cook Islands</option>
<option value="10316">Cook Islands (New Zealand)</option>
<option value="12174">Corisco Island</option>
<option value="10098">Corisco Island (Equatorial Guinea)</option>
<option value="12173">Corsica</option>
<option value="10112">Corsica (France)</option>
<option value="10080">Costa Rica</option>
<option value="12004">Cote d Ivoire</option>
<option value="10081">Cote d Ivoire (Ivory Coast)</option>
<option value="12175">Crete</option>
<option value="10154">Crete (Greece)</option>
<option value="10082">Croatia</option>
<option value="10083">Cuba</option>
<option value="12176">Cumino Island</option>
<option value="10280">Cumino Island (Malta)</option>
<option value="12177">Curacao</option>
<option value="10300">Curacao (Netherlands Antilles)</option>
<option value="12178">Cyjrenaica</option>
<option value="10243">Cyjrenaica (Libya)</option>
<option value="10085">Cyprus</option>
<option value="10086">Czech Republic</option>
<option value="12155">Dahomey</option>
<option value="10037">Dahomey (Benin)</option>
<option value="12153">Damao</option>
<option value="10194">Damao (India)</option>
<option value="12170">Danger Islands</option>
<option value="10317">Danger Islands (New Zealand)</option>
<option value="12095">Democratic People's Republic of Korea</option>
<option value="12179">Democratic Republic of the Congo</option>
<option value="10087">Denmark</option>
<option value="12148">Desirade Island</option>
<option value="10172">Desirade Island (Guadeloupe)</option>
<option value="12149">Diu</option>
<option value="10195">Diu (India)</option>
<option value="10088">Djibouti</option>
<option value="12150">Dodecanese Islands</option>
<option value="10155">Dodecanese Islands (Greece)</option>
<option value="12151">Doha</option>
<option value="10363">Doha (Qatar)</option>
<option value="10091">Dominica</option>
<option value="10092">Dominican Republic</option>
<option value="12152">Dubai</option>
<option value="10442">Dubai (United Arab Emirates)</option>
<option value="12162">East Timor</option>
<option value="10201">East Timor (Indonesia)</option>
<option value="10456">Ebeye, Marshall Islands</option>
<option value="10093">Ecuador</option>
<option value="10094">Egypt</option>
<option value="12146">Eire</option>
<option value="10209">Eire (Ireland)</option>
<option value="10095">El Salvador</option>
<option value="12119">Ellice Islands</option>
<option value="10436">Ellice Islands (Tuvalu)</option>
<option value="12157">Elobey Islands</option>
<option value="10099">Elobey Islands (Equatorial Guinea)</option>
<option value="12158">Enderbury Island</option>
<option value="10227">Enderbury Island (Kiribati)</option>
<option value="12159">England</option>
<option value="10142">England (Great Britain and Northern Ireland)</option>
<option value="10100">Equatorial Guinea</option>
<option value="10103">Eritrea</option>
<option value="10104">Estonia</option>
<option value="10105">Ethiopia</option>
<option value="12160">Fakaofo</option>
<option value="10506">Fakaofo (Western Samoa)</option>
<option value="10106">Falkland Islands</option>
<option value="12161">Fanning Island</option>
<option value="10228">Fanning Island (Kiribati)</option>
<option value="10108">Faroe Islands</option>
<option value="10457">Federated States of Micronesia</option>
<option value="12164">Fernando Po</option>
<option value="10101">Fernando Po (Equatorial Guinea)</option>
<option value="12102">Fezzan</option>
<option value="10244">Fezzan (Libya)</option>
<option value="10109">Fiji</option>
<option value="10111">Finland</option>
<option value="12080">Formosa</option>
<option value="10414">Formosa (Taiwan)</option>
<option value="10113">France</option>
<option value="10114">France, Metropolitan (France)</option>
<option value="10117">French Guiana</option>
<option value="12079">French Oceania</option>
<option value="10119">French Oceania (French Polynesia)</option>
<option value="10120">French Polynesia</option>
<option value="12078">French Somaliland</option>
<option value="10089">French Somaliland (Djibouti)</option>
<option value="10090">French Territory of the Afars and Issas (Djibouti)</option>
<option value="12075">French West Indies</option>
<option value="10173">French West Indies (Guadeloupe or Martinique)</option>
<option value="12002">French West Indies (Martinique)</option>
<option value="12074">Friendly Islands</option>
<option value="10426">Friendly Islands (Tonga)</option>
<option value="12073">Fujairah</option>
<option value="10443">Fujairah (United Arab Emirates)</option>
<option value="12071">Futuna</option>
<option value="10503">Futuna (Wallis and Futuna Islands)</option>
<option value="10134">Gabon</option>
<option value="10135">Gambia</option>
<option value="12081">Gambier</option>
<option value="10121">Gambier (French Polynesia)</option>
<option value="10136">Georgia, Republic of</option>
<option value="10137">Germany</option>
<option value="10138">Ghana</option>
<option value="10139">Gibraltar</option>
<option value="12069">Gilbert Islands</option>
<option value="10229">Gilbert Islands (Kiribati)</option>
<option value="12068">Goa</option>
<option value="10196">Goa (India)</option>
<option value="12066">Gozo Island</option>
<option value="10281">Gozo Island (Malta)</option>
<option value="12067">Grand Comoro</option>
<option value="10070">Grand Comoro (Comoros)</option>
<option value="12062">Great Britain</option>
<option value="10143">Great Britain and Northern Ireland</option>
<option value="10156">Greece</option>
<option value="10169">Greenland</option>
<option value="10170">Grenada</option>
<option value="12063">Grenadines</option>
<option value="10406">Grenadines (St. Vincent and the Grenadines)</option>
<option value="10174">Guadeloupe</option>
<option value="10181">Guatemala</option>
<option value="12003">Guernsey</option>
<option value="10144">Guernsey, Channel Islands (Great Britain)</option>
<option value="10182">Guinea</option>
<option value="10183">Guinea-Bissau</option>
<option value="10185">Guyana</option>
<option value="12064">Hainan Island</option>
<option value="10064">Hainan Island (China)</option>
<option value="10186">Haiti</option>
<option value="12065">Hashemite Kingdom</option>
<option value="10220">Hashemite Kingdom (Jordan)</option>
<option value="12111">Hervey</option>
<option value="10318">Hervey (Cook Islands) (New Zealand)</option>
<option value="12098">Hivaoa</option>
<option value="10122">Hivaoa (French Polynesia)</option>
<option value="12099">Holland</option>
<option value="10297">Holland (Netherlands)</option>
<option value="10187">Honduras</option>
<option value="10189">Hong Kong</option>
<option value="12100">Huahine</option>
<option value="10123">Huahine (French Polynesia)</option>
<option value="12101">Huan Island</option>
<option value="10310">Huan Island (New Caledonia)</option>
<option value="10191">Hungary</option>
<option value="10192">Iceland</option>
<option value="10197">India</option>
<option value="10202">Indonesia</option>
<option value="10206">Iran</option>
<option value="10208">Iraq</option>
<option value="10210">Ireland</option>
<option value="12096">Irian Barat</option>
<option value="10203">Irian Barat (Indonesia)</option>
<option value="12112">Isle of Man</option>
<option value="10145">Isle of Man (Great Britain)</option>
<option value="12104">Isle of Pines</option>
<option value="12072">Isle of Pines</option>
<option value="10311">Isle of Pines (New Caledonia)</option>
<option value="10084">Isle of Pines, West Indies (Cuba)</option>
<option value="10211">Israel</option>
<option value="12077">Issas</option>
<option value="10212">Italy</option>
<option value="10213">Jamaica</option>
<option value="10214">Japan</option>
<option value="12005">Jersey</option>
<option value="10146">Jersey (Channel Islands) (Great Britain)</option>
<option value="12097">Johore</option>
<option value="10259">Johore (Malaysia)</option>
<option value="10221">Jordan</option>
<option value="12120">Kalimantan</option>
<option value="12106">Kalymnos</option>
<option value="10157">Kalymnos (Greece)</option>
<option value="12107">Kampuchea</option>
<option value="10052">Kampuchea (Cambodia)</option>
<option value="12108">Karpathos</option>
<option value="10158">Karpathos (Greece)</option>
<option value="12109">Kassos</option>
<option value="10159">Kassos (Greece)</option>
<option value="12110">Kastellorizon</option>
<option value="10160">Kastellorizon (Greece)</option>
<option value="10223">Kazakhstan</option>
<option value="12082">Kedah</option>
<option value="10260">Kedah (Malaysia)</option>
<option value="12105">Keeling Islands</option>
<option value="10016">Keeling Islands (Australia)</option>
<option value="12103">Kelantan</option>
<option value="10261">Kelantan (Malaysia)</option>
<option value="10224">Kenya</option>
<option value="10230">Kiribati</option>
<option value="10232">Korea, Democratic Peoples Republic of (North Korea)</option>
<option value="10234">Korea, Republic of (South Korea)</option>
<option value="12093">Kos</option>
<option value="10161">Kos (Greece)</option>
<option value="10464">Kosrae, Micronesia</option>
<option value="12092">Kowloon</option>
<option value="10190">Kowloon (Hong Kong)</option>
<option value="10236">Kuwait</option>
<option value="10465">Kwajalein, Marshall Islands</option>
<option value="10237">Kyrgyzstan</option>
<option value="12091">Labrador</option>
<option value="10055">Labrador (Canada)</option>
<option value="12090">Labuan</option>
<option value="10262">Labuan (Malaysia)</option>
<option value="10238">Laos</option>
<option value="10239">Latvia</option>
<option value="10240">Lebanon</option>
<option value="12089">Leipsos</option>
<option value="10162">Leipsos (Greece)</option>
<option value="12088">Leros</option>
<option value="10163">Leros (Greece)</option>
<option value="12087">Les Saints Island</option>
<option value="10175">Les Saints Island (Guadeloupe)</option>
<option value="10241">Lesotho</option>
<option value="10242">Liberia</option>
<option value="10245">Libya</option>
<option value="10247">Liechtenstein</option>
<option value="10248">Lithuania</option>
<option value="12086">Lord Howe Island</option>
<option value="10017">Lord Howe Island (Australia)</option>
<option value="12085">Loyalty Islands</option>
<option value="10312">Loyalty Islands (New Caledonia)</option>
<option value="10249">Luxembourg</option>
<option value="10250">Macao</option>
<option value="12084">Macau</option>
<option value="10251">Macau (Macao)</option>
<option value="10252">Macedonia, Republic of</option>
<option value="10253">Madagascar</option>
<option value="12083">Madeira Islands</option>
<option value="10360">Madeira Islands (Portugal)</option>
<option value="10361">Maderia Islands</option>
<option value="10466">Majuro, Marshall Islands</option>
<option value="12156">Malacca</option>
<option value="10263">Malacca (Malaysia)</option>
<option value="12310">Malagasy Republic</option>
<option value="10254">Malagasy Republic (Madagascar)</option>
<option value="10256">Malawi</option>
<option value="12263">Malaya</option>
<option value="10264">Malaya (Malaysia)</option>
<option value="10265">Malaysia</option>
<option value="10278">Maldives</option>
<option value="10279">Mali</option>
<option value="10282">Malta</option>
<option value="12264">Manahiki</option>
<option value="10319">Manahiki (New Zealand)</option>
<option value="12265">Manchuria</option>
<option value="10065">Manchuria (China)</option>
<option value="12266">Mangaia</option>
<option value="10320">Mangaia (Cook Islands) (New Zealand)</option>
<option value="12006">Manitoba (Canada)</option>
<option value="12267">Manuai</option>
<option value="10321">Manuai (Cook Islands) (New Zealand)</option>
<option value="12197">Marie Galante</option>
<option value="10176">Marie Galante (Guadeloupe)</option>
<option value="12278">Marquesas Islands</option>
<option value="10124">Marquesas Islands (French Polynesia)</option>
<option value="10468">Marshall Islands</option>
<option value="10283">Martinique</option>
<option value="12154">Mauke</option>
<option value="10322">Mauke (Cook Islands) (New Zealand)</option>
<option value="10284">Mauritania</option>
<option value="10285">Mauritius</option>
<option value="12262">Mayotte</option>
<option value="10115">Mayotte (France)</option>
<option value="12272">Melilla</option>
<option value="10393">Melilla (Spain)</option>
<option value="10287">Mexico</option>
<option value="10469">Micronesia, Federated States of</option>
<option value="12273">Miquelon</option>
<option value="10404">Miquelon (St. Pierre and Miquelon)</option>
<option value="12274">Mitiaro</option>
<option value="10323">Mitiaro (Cook Islands) (New Zealand)</option>
<option value="12275">Moheli</option>
<option value="10071">Moheli (Comoros)</option>
<option value="10288">Moldova</option>
<option value="12276">Monaco</option>
<option value="10116">Monaco (France)</option>
<option value="10289">Mongolia</option>
<option value="10290">Montserrat</option>
<option value="12254">Moorea</option>
<option value="10125">Moorea (French Polynesia)</option>
<option value="10291">Morocco</option>
<option value="10292">Mozambique</option>
<option value="12271">Muscat</option>
<option value="10340">Muscat (Oman)</option>
<option value="12269">Myanmar</option>
<option value="10049">Myanmar (Burma)</option>
<option value="10293">Namibia</option>
<option value="12247">Nansil Islands</option>
<option value="10215">Nansil Islands (Ryukyu Islands) (Japan)</option>
<option value="10295">Nauru</option>
<option value="12248">Negri Sembilan</option>
<option value="10266">Negri Sembilan (Malaysia)</option>
<option value="10296">Nepal</option>
<option value="10298">Netherlands</option>
<option value="10301">Netherlands Antilles</option>
<option value="12007">Netherlands Antilles (Aruba)</option>
<option value="12008">Netherlands Antilles (Bonaire)</option>
<option value="12009">Netherlands Antilles (Curacao)</option>
<option value="12010">Netherlands Antilles (St. Maarten)</option>
<option value="12249">Netherlands West Indies</option>
<option value="10302">Netherlands West Indies (Netherlands Antilles)</option>
<option value="12250">Nevis</option>
<option value="10399">Nevis (St. Christopher and Nevis)</option>
<option value="12251">New Britain</option>
<option value="10349">New Britain (Papua New Guinea)</option>
<option value="12011">New Brunswick (Canada)</option>
<option value="10313">New Caledonia</option>
<option value="12261">New Hanover</option>
<option value="10350">New Hanover (Papua New Guinea)</option>
<option value="12253">New Hebrides</option>
<option value="10497">New Hebrides (Vanuatu)</option>
<option value="12246">New Ireland</option>
<option value="10351">New Ireland (Papua New Guinea)</option>
<option value="12255">New South Wales</option>
<option value="10018">New South Wales (Australia)</option>
<option value="10324">New Zealand</option>
<option value="12256">Newfoundland</option>
<option value="10056">Newfoundland (Canada)</option>
<option value="10335">Nicaragua</option>
<option value="10336">Niger</option>
<option value="10337">Nigeria</option>
<option value="12257">Nissiros</option>
<option value="10164">Nissiros (Greece)</option>
<option value="12258">Niue</option>
<option value="10325">Niue (New Zealand)</option>
<option value="12259">Norfolk Island</option>
<option value="10019">Norfolk Island (Australia)</option>
<option value="12113">North Borneo</option>
<option value="10267">North Borneo (Malaysia)</option>
<option value="12252">North Korea</option>
<option value="10233">North Korea (Korea, Democratic People's Republic of)</option>
<option value="12277">Northern Ireland</option>
<option value="10147">Northern Ireland (Great Britain and Northern Ireland)</option>
<option value="12012">Northwest Territory (Canada)</option>
<option value="10338">Norway</option>
<option value="12013">Nova Scotia (Canada)</option>
<option value="12296">Nukahiva</option>
<option value="10126">Nukahiva (French Polynesia)</option>
<option value="12297">Nukunonu</option>
<option value="10507">Nukunonu (Western Samoa)</option>
<option value="12298">Nyasaland</option>
<option value="10257">Nyasaland (Malawi)</option>
<option value="12299">Ocean Island</option>
<option value="10231">Ocean Island (Kiribati)</option>
<option value="12300">Okinawa</option>
<option value="10216">Okinawa (Japan)</option>
<option value="10217">Okinawa (Ryukyu Islands) (Japan)</option>
<option value="10341">Oman</option>
<option value="12014">Ontario (Canada)</option>
<option value="12301">Pahang</option>
<option value="10268">Pahang (Malaysia)</option>
<option value="10343">Pakistan</option>
<option value="12311">Palmerston</option>
<option value="10326">Palmerston (Avarua) (New Zealand)</option>
<option value="10344">Panama</option>
<option value="10352">Papua New Guinea</option>
<option value="10353">Paraguay</option>
<option value="12303">Parry</option>
<option value="10327">Parry (Cook Islands) (New Zealand)</option>
<option value="12295">Patmos</option>
<option value="10165">Patmos (Greece)</option>
<option value="12305">Pemba</option>
<option value="10420">Pemba (Tanzania)</option>
<option value="12306">Penang</option>
<option value="10269">Penang (Malaysia)</option>
<option value="12307">Penghu Islands</option>
<option value="10415">Penghu Islands (Taiwan)</option>
<option value="12308">Penon de Velez de la Gomera</option>
<option value="10394">Penon de Velez de la Gomera (Spain)</option>
<option value="12309">Penrhyn</option>
<option value="10328">Penrhyn (Tongareva) (New Zealand)</option>
<option value="12287">Perak</option>
<option value="10270">Perak (Malaysia)</option>
<option value="12304">Perlis</option>
<option value="10271">Perlis (Malaysia)</option>
<option value="12302">Persia</option>
<option value="10207">Persia (Iran)</option>
<option value="10354">Peru</option>
<option value="12280">Pescadores Islands</option>
<option value="10416">Pescadores Islands (Taiwan)</option>
<option value="12281">Petite Terre</option>
<option value="10177">Petite Terre (Guadeloupe)</option>
<option value="10355">Philippines</option>
<option value="10356">Pitcairn Island</option>
<option value="10477">Pohnpei, Micronesia</option>
<option value="10357">Poland</option>
<option value="10362">Portugal</option>
<option value="12015">Prince Edward Island (Canada)</option>
<option value="12282">Province Wellesley</option>
<option value="10272">Province Wellesley (Malaysia)</option>
<option value="12283">Pukapuka</option>
<option value="10329">Pukapuka (New Zealand)</option>
<option value="10364">Qatar</option>
<option value="12016">Quebec (Canada)</option>
<option value="12284">Queensland</option>
<option value="10020">Queensland (Australia)</option>
<option value="12294">Quemoy</option>
<option value="10417">Quemoy (Taiwan)</option>
<option value="12286">Raiatea</option>
<option value="10127">Raiatea (French Polynesia)</option>
<option value="12279">Rakaanga</option>
<option value="10330">Rakaanga (New Zealand)</option>
<option value="12288">Rapa</option>
<option value="10128">Rapa (French Polynesia)</option>
<option value="12289">Rarotonga</option>
<option value="10331">Rarotonga (Cook Islands) (New Zealand)</option>
<option value="12290">Ras al Kaimah</option>
<option value="10444">Ras al Kaimah (United Arab Emirates)</option>
<option value="12291">Redonda</option>
<option value="10008">Redonda (Antigua and Barbuda)</option>
<option value="12070">Republic of Georgia</option>
<option value="12094">Republic of Korea</option>
<option value="12171">Republic of the Congo</option>
<option value="10479">Republic of the Marshall Islands</option>
<option value="10367">Reunion</option>
<option value="12292">Rhodesia</option>
<option value="10515">Rhodesia (Zimbabwe)</option>
<option value="12293">Rio Muni</option>
<option value="10102">Rio Muni (Equatorial Guinea)</option>
<option value="12244">Rodos</option>
<option value="10166">Rodos (Greece)</option>
<option value="12194">Rodrigues</option>
<option value="10286">Rodrigues (Mauritius)</option>
<option value="10368">Romania</option>
<option value="10369">Russia</option>
<option value="10370">Rwanda</option>
<option value="12260">Saba</option>
<option value="10303">Saba (Netherlands Antilles)</option>
<option value="12198">Sabah</option>
<option value="10273">Sabah (Malaysia)</option>
<option value="12199">Saint Bartholomew</option>
<option value="10178">Saint Bartholomew (Guadeloupe)</option>
<option value="10400">Saint Christopher (St. Kitts) and Nevis</option>
<option value="12200">Saint Christopher and Nevis</option>
<option value="12201">Saint Eustatius</option>
<option value="10304">Saint Eustatius (Netherlands Antilles)</option>
<option value="10402">Saint Helena</option>
<option value="12202">Saint Kitts</option>
<option value="10401">Saint Kitts (St. Christopher and Nevis)</option>
<option value="10403">Saint Lucia</option>
<option value="12212">Saint Maarten</option>
<option value="10305">Saint Maarten (Netherlands Antilles)</option>
<option value="12204">Saint Martin (France)</option>
<option value="10179">Saint Martin (French part) (Guadeloupe)</option>
<option value="10306">Saint Martin (Netherlands part)(Netherlands Antilles)</option>
<option value="12196">Saint Martin (Netherlands)</option>
<option value="10405">Saint Pierre and Miquelon</option>
<option value="10407">Saint Vincent and the Grenadines</option>
<option value="12017">Sainte Marie de Madagascar (Madagascar)</option>
<option value="12206">Salvador</option>
<option value="10096">Salvador (El Salvador)</option>
<option value="10371">San Marino</option>
<option value="12207">Santa Cruz Islands</option>
<option value="10381">Santa Cruz Islands (Solomon Island)</option>
<option value="10372">Sao Tome and Principe</option>
<option value="12208">Sarawak</option>
<option value="10274">Sarawak (Malaysia)</option>
<option value="12209">Sark</option>
<option value="10148">Sark, Channel Islands (Great Britain)</option>
<option value="12018">Saskatchewan (Canada)</option>
<option value="10373">Saudi Arabia</option>
<option value="12210">Savage Island</option>
<option value="10332">Savage Island (Niue) (New Zealand)</option>
<option value="12188">Savaii Island</option>
<option value="10508">Savaii Island (British) (Western Samoa)</option>
<option value="12205">Scotland</option>
<option value="10149">Scotland (Great Britain and Northern Ireland)</option>
<option value="12203">Selangor</option>
<option value="10275">Selangor (Malaysia)</option>
<option value="10374">Senegal</option>
<option value="10375">Serbia-Montenegro</option>
<option value="10376">Seychelles</option>
<option value="12181">Sharja</option>
<option value="10445">Sharja (United Arab Emirates)</option>
<option value="12182">Shikoku</option>
<option value="10218">Shikoku (Japan)</option>
<option value="12183">Siam</option>
<option value="10423">Siam (Thailand)</option>
<option value="10377">Sierra Leone</option>
<option value="12184">Sikkim</option>
<option value="10198">Sikkim (India)</option>
<option value="10378">Singapore</option>
<option value="10379">Slovak Republic</option>
<option value="10380">Slovenia</option>
<option value="12185">Society Islands</option>
<option value="10129">Society Islands (French Polynesia)</option>
<option value="10382">Solomon Islands</option>
<option value="12195">Somali Democratic Republic</option>
<option value="10383">Somali Democratic Republic (Somalia)</option>
<option value="10384">Somalia</option>
<option value="12187">Somaliland</option>
<option value="10385">Somaliland (Somalia)</option>
<option value="10386">South Africa</option>
<option value="12180">South Australia</option>
<option value="10021">South Australia (Australia)</option>
<option value="12189">South Georgia</option>
<option value="10107">South Georgia (Falkland Islands)</option>
<option value="12190">South Korea</option>
<option value="10235">South Korea (Korea, Republic of)</option>
<option value="12191">South-West Africa</option>
<option value="10294">South-West Africa (Namibia)</option>
<option value="10395">Spain</option>
<option value="12192">Spitzbergen</option>
<option value="10339">Spitzbergen (Norway)</option>
<option value="10398">Sri Lanka</option>
<option value="12019">St. Barthelemy</option>
<option value="10517">St. Christopher and Nevis</option>
<option value="10307">St. Eustatius (Netherlands Antilles)</option>
<option value="10519">St. Helena</option>
<option value="12020">St. Kitts</option>
<option value="12021">St. Lucia</option>
<option value="10308">St. Maarten (Netherlands Antilles)</option>
<option value="10180">St. Martin (Guadeloupe)</option>
<option value="10520">St. Pierre and Miquelon</option>
<option value="12022">St. Vincent</option>
<option value="10518">St. Vincent and the Grenadines</option>
<option value="12193">Ste. Marie de Madagascar</option>
<option value="10255">Ste. Marie de Madagascar (Madagascar)</option>
<option value="10408">Sudan</option>
<option value="10409">Suriname</option>
<option value="12219">Suwarrow Islands</option>
<option value="10333">Suwarrow Islands (New Zealand)</option>
<option value="12186">Swan Islands</option>
<option value="10188">Swan Islands (Honduras)</option>
<option value="10410">Swaziland</option>
<option value="10411">Sweden</option>
<option value="10412">Switzerland</option>
<option value="12211">Symi</option>
<option value="10167">Symi (Greece)</option>
<option value="10413">Syrian Arab Republic</option>
<option value="12230">Tahaa</option>
<option value="10130">Tahaa (French Polynesia)</option>
<option value="12231">Tahiti</option>
<option value="10131">Tahiti (French Polynesia)</option>
<option value="10418">Taiwan</option>
<option value="10419">Tajikistan</option>
<option value="10421">Tanzania</option>
<option value="12232">Tasmania</option>
<option value="10022">Tasmania (Australia)</option>
<option value="12233">Tchad</option>
<option value="10061">Tchad (Chad)</option>
<option value="10424">Thailand</option>
<option value="12234">Thursday Island</option>
<option value="10023">Thursday Island (Australia)</option>
<option value="12235">Tibet</option>
<option value="10066">Tibet (China)</option>
<option value="12245">Tilos</option>
<option value="10168">Tilos (Greece)</option>
<option value="12237">Timor</option>
<option value="10204">Timor (Indonesia)</option>
<option value="12229">Tobago</option>
<option value="10428">Tobago (Trinidad and Tobago)</option>
<option value="10425">Togo</option>
<option value="10509">Tokelau (Union) Group (Western Samoa)</option>
<option value="12239">Tokelau Group</option>
<option value="10427">Tonga</option>
<option value="12240">Tongareva</option>
<option value="10334">Tongareva (New Zealand)</option>
<option value="12241">Tori Shima</option>
<option value="10219">Tori Shima (Ryukyu Islands) (Japan)</option>
<option value="12242">Torres Island</option>
<option value="10498">Torres Island (Vanuatu)</option>
<option value="12243">Trans-Jordan</option>
<option value="10222">Trans-Jordan (Hashemite Kingdom) (Jordan)</option>
<option value="12221">Transkei</option>
<option value="10387">Transkei (South Africa)</option>
<option value="12238">Trengganu</option>
<option value="10276">Trengganu (Malaysia)</option>
<option value="10429">Trinidad and Tobago</option>
<option value="12236">Tripolitania</option>
<option value="10246">Tripolitania (Libya)</option>
<option value="10430">Tristan da Cunha</option>
<option value="12214">Trucial States</option>
<option value="10446">Trucial States (United Arab Emirates)</option>
<option value="10488">Truk (See Chuuk Island)</option>
<option value="12215">Tuamotou</option>
<option value="10132">Tuamotou (French Polynesia)</option>
<option value="12216">Tubuai</option>
<option value="10133">Tubuai (French Polynesia)</option>
<option value="10431">Tunisia</option>
<option value="10432">Turkey</option>
<option value="10433">Turkmenistan</option>
<option value="10435">Turks and Caicos Islands</option>
<option value="10437">Tuvalu</option>
<option value="10438">Uganda</option>
<option value="10439">Ukraine</option>
<option value="12217">Umm Said</option>
<option value="10365">Umm Said (Qatar)</option>
<option value="12218">Umm al Quaiwain</option>
<option value="10447">Umm al Quaiwain (United Arab Emirates)</option>
<option value="12228">Union Group</option>
<option value="10510">Union Group (Western Samoa)</option>
<option value="10448">United Arab Emirates</option>
<option value="10150">United Kingdom (Great Britain)</option>
<option value="12220">Upolu Island</option>
<option value="10511">Upolu Island (Western Samoa)</option>
<option value="10449">Uruguay</option>
<option value="10495">Uzbekistan</option>
<option value="10499">Vanuatu</option>
<option value="10500">Vatican City</option>
<option value="10501">Venezuela</option>
<option value="12213">Victoria</option>
<option value="10024">Victoria (Australia)</option>
<option value="10502">Vietnam</option>
<option value="12023">Virgin Islands (British)</option>
<option value="12222">Wales</option>
<option value="10151">Wales (Great Britain and Northern Ireland)</option>
<option value="10504">Wallis and Futuna Islands</option>
<option value="12223">Wellesley</option>
<option value="10277">Wellesley, Province (Malaysia)</option>
<option value="12224">West New Guinea</option>
<option value="10205">West New Guinea (Indonesia)</option>
<option value="12225">Western Australia</option>
<option value="10025">Western Australia (Australia)</option>
<option value="10512">Western Samoa</option>
<option value="10494">Yap, Micronesia</option>
<option value="10513">Yemen</option>
<option value="12226">Yugoslavia</option>
<option value="12029">Yugoslavia (see individual republics)</option>
<option value="12024">Yukon Territory (Canada)</option>
<option value="12227">Zafarani Islands</option>
<option value="10396">Zafarani Islands (Spain)</option>
<option value="12025">Zaire (Congo Dem Rep)</option>
<option value="10514">Zambia</option>
<option value="12270">Zanzibar</option>
<option value="10422">Zanzibar (Tanzania)</option>
<option value="10516">Zimbabwe</option></select>
                                    <div style="display: none;"><label for="deliveryCountry">* Country</label></div>
                                </td>
                                
                                
                            </tr>
                            
                        
                        
                        <tr><td colspan="3" style="background-image: url(<?php echo $USPS_file_dir; ?>/light_gray.gif);" height="10"></td> </tr>
                            <tr><td colspan="3" style="background-image: url(<?php echo $USPS_file_dir; ?>/light_gray.gif);" class="mainTextbold"><div style="margin-left: 5px;"><span class="mainTextRed">*</span> Full Name and/or Company Name</div></td></tr>
                            <tr><td colspan="3" style="background-image: url(<?php echo $USPS_file_dir; ?>/light_gray.gif);" height="10"></td> </tr>
                            
                            
              

                            <tr>
                                <td style="background-image: url(<?php echo $USPS_file_dir; ?>/light_gray.gif);" class="mainText" align="right" width="93"><span class="label">Full Name</span></td>
                                <td style="background-image: url(<?php echo $USPS_file_dir; ?>/light_gray.gif);" width="7"></td>
                                <td style="background-image: url(<?php echo $USPS_file_dir; ?>/light_gray.gif);"><input name="deliveryFullName" maxlength="38" size="19" tabindex="32" value="<?php echo (USPS_SHIP_ADDRESS == 'Shipping' ? $order->delivery['name'] : $order->billing['name']);?>" id="deliveryFullName" style="width: 175px;" title="Delivery Address Full Name" type="text"><div style="display: none;"><label for="deliveryFullName">* Full Name</label></div></td>
                            </tr>

                        
                      
                            <tr> <td style="background-image: url(<?php echo $USPS_file_dir; ?>/light_gray.gif);" colspan="3" height="10"></td> </tr>
                            <tr>
                              <td style="background-image: url(<?php echo $USPS_file_dir; ?>/light_gray.gif);" class="mainText" align="right"><span class="label">Company Name</span></td>
                              <td style="background-image: url(<?php echo $USPS_file_dir; ?>/light_gray.gif);" width="7"></td>
                              <td style="background-image: url(<?php echo $USPS_file_dir; ?>/light_gray.gif);"><input name="deliveryCompanyName" maxlength="38" size="19" tabindex="33" value="<?php echo (USPS_SHIP_ADDRESS == 'Shipping' ? $order->delivery['company'] : $order->billing['company']);?>" id="deliveryCompanyName" style="width: 175px;" title="Delivery Address Company Name" type="text"><div style="display: none;"><label for="deliveryCompanyName">Company Name</label></div></td>
                            </tr>
                            <tr> <td style="background-image: url(<?php echo $USPS_file_dir; ?>/light_gray.gif);" colspan="3" height="10"></td> </tr>

              
              
              
                            <tr>
                                <td class="mainText" align="right"><span class="mainTextRed">* </span><span class="label">Address 1</span></td>
                                <td width="7"></td>
                                <td height="32">
                                    <input name="deliveryAddressOne" maxlength="38" size="19" tabindex="34" value="<?php echo (USPS_SHIP_ADDRESS == 'Shipping' ? $order->delivery['street_address'] : $order->billing['street_address']);?>" id="deliveryAddressOne" style="width: 175px;" title="Delivery Address Line 1" type="text">
                  <div style="display: none;"><label for="deliveryAddressOne">* Address 1</label></div>
                                </td>
                            </tr>
                            
                        
                      
                            <tr> <td colspan="3" height="5"></td> </tr>
                            <tr>
                                <td class="mainText" align="right"><span class="label">Address 2</span></td>
                                <td width="7"></td>
                                <td><input name="deliveryAddressTwo" maxlength="38" size="19" tabindex="35" value="<?php echo (USPS_SHIP_ADDRESS == 'Shipping' ? $order->delivery['suburb'] : $order->billing['suburb']);?>" id="deliveryAddressTwo" style="width: 175px;" title="Delivery Address Line 2" type="text"><div style="display: none;"><label for="deliveryAddressTwo">Address 2&nbsp;(Apt, floor, suite, PMB, etc)</label></div></td>
                            </tr>
                            <tr style="visibility: visible;" id="aptId">
                                <td colspan="2"></td>
                                <td>
                                    <table summary="This table is used to format the Delivery Address information." border="0" cellpadding="0" cellspacing="0" width="185">
                                        <tbody><tr>
                                            <td class="box" width="160">(Apt, floor, suite, PMB, etc)</td>
                                            <td width="25"></td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                            <tr> <td colspan="3" height="10"></td> </tr>
                            
                            
              
                            <tr style="display: none; visibility: hidden;" id="deliveryAddressThreeId">
                                <td class="mainText" align="right"><span class="mainTextRed"> </span><span class="label">Address 3</span></td>
                                <td width="7"></td>
                                <td>
                                    <input name="deliveryAddressThree" maxlength="38" size="19" tabindex="36" value="" id="deliveryAddressThree" style="width: 175px;" title="Delivery Address Line 3" type="text">
                  <div style="display: none;"><label for="deliveryAddressThree">* Address 3</label></div>
                                </td>
                            </tr>
  
                      
              
                            <tr style="display: none; visibility: hidden;" id="deliveryAddressThreeId2"> <td colspan="3" height="10"></td> </tr>
                            
              
                            <tr>
                                <td class="mainText" align="right"><span class="mainTextRed">* </span><span class="label">City</span></td>
                                <td width="7"></td>
                                <td><input name="deliveryCity" maxlength="38" size="19" tabindex="37" value="<?php echo (USPS_SHIP_ADDRESS == 'Shipping' ? $order->delivery['city'] : $order->billing['city']);?>" id="deliveryCity" style="width: 175px;" title="Delivery Address City" type="text"><div style="display: none;"><label for="deliveryCity">* City</label></div></td>
                            </tr>
                            
            
            
                            <tr> <td colspan="3" height="10"></td> </tr>

                            
              
              
                            <tr style="visibility: visible;" id="deliveryStateId">
                                <td class="mainText" align="right"><span class="mainTextRed">* </span><span class="label">State</span></td>
                                <td width="7"></td>
                                <td><input name="deliveryState" maxlength="38" size="19" tabindex="38" value="<?php echo ($country_code == 1 ? (USPS_SHIP_ADDRESS == 'Shipping' ? $shipping_zone_code : $billing_zone_code): '');?>" id="deliveryState" style="width: 175px;" type="text" onchange="checkLabelInfoUrbanization('delivery', true, this.form)" ><div style="display: none;"><label for="deliveryState">* State</label></div></td>
                            </tr>
                            
            
            
                            <tr style="visibility: visible;" id="deliveryStateId2"> <td colspan="3" height="10"></td> </tr>
                            
                            
                            
              
                            <tr style="display: none; visibility: hidden;" id="deliveryUrbanizationRow1">
                <td class="mainText" align="right"><span class="mainText">Urbanization</span></td>
                <td width="7"></td>
                <td><input disabled="disabled" name="deliveryUrbanization" maxlength="28" size="19" tabindex="39" value="" id="deliveryUrbanization" style="width: 175px;" title="Delivery Address Urbanization" type="text"><div style="display: none;"><label for="deliveryUrbanization">Urbanization&nbsp;(Puerto Rico addresses only)</label></div></td>
              </tr>
              <tr style="display: none; visibility: hidden;" id="deliveryUrbanizationRow2">
                <td colspan="2"></td>
                <td>
                  <table summary="This table is used to format the Delivery Address information." border="0" cellpadding="0" cellspacing="0" width="185">
                    <tbody>
                    <tr>
                      <td class="box" width="160">Puerto Rico addresses only</td>
                      <td width="25"></td>
                    </tr>
                    
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr style="display: none; visibility: hidden;" id="deliveryUrbanizationRow3">
                <td colspan="3" height="10"></td>
              </tr>
              
              
              
              
                            <tr style="visibility: visible;" id="deliveryZipcodeId">
                                <td class="mainText" align="right"><span class="label">ZIP Code&#8482;</span></td>
                                <td width="7"></td>
                                <td>
                                    <input name="deliveryZipcode" maxlength="10" size="19" tabindex="40" value="<?php echo ($country_code == 1 ? (USPS_SHIP_ADDRESS == 'Shipping' ? $order->delivery['postcode'] : $order->billing['postcode']) : '');?>" id="deliveryZipcode" style="width: 175px;" title="Delivery Address Zip Code" type="text">
                  <div style="display: none;"><label for="deliveryZipcode">ZIP Code&#8482;&nbsp;Address will be standardized as necessary.</label></div>
                                </td>
                            </tr>
                            <tr style="visibility: visible;" id="standardizeId">
                                <td colspan="2"></td>
                                <td>
                                    <table summary="This table is used to format the Delivery Address information." border="0" cellpadding="0" cellspacing="0" width="185">
                                        <tbody><tr>
                                            <td class="box" width="160">
                                                Address will be 
                                                <a href="https://sss-web.usps.com/cns/html/popUpStandardization.html" target="someName31" tabindex="41" title="address standardization" onclick="javascript:void openWindow ('https://sss-web.usps.com/cns/html/popUpStandardization.html', 'someName31', 'width=245,height=385', 225, 112, window, 'resizable=1,menubar=0,toolbar=0,location=0,personalbar=0,status=0,scrollbars=0');">standardized</a>
                                            </td>
                                            <td width="25"></td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                            
                        
            
                            <tr style="visibility: visible;" id="standardizeId2"> <td colspan="3" height="10"></td> </tr>
                            <tr style="display: none; visibility: hidden;" id="provinceId">
                              <td class="mainText" align="right"><span class="label">Province</span></td>
                                <td width="7"></td>
                                <td>
                                  <input name="province" maxlength="25" size="19" tabindex="42" value="<?php echo ($country_code != 1 ? (USPS_SHIP_ADDRESS != 'Shipping' ? $order->delivery['state'] : $order->billing['state']) : '') ; ?>" id="province" style="width: 175px;" title="Delivery Address Province" type="text">
                  <div style="display: none;"><label for="province">Province</label></div>
                                </td>
                            </tr>
                            <tr style="display: none; visibility: hidden;" id="provinceId2"> <td colspan="3" height="10"></td> </tr>
                            <tr style="display: none; visibility: hidden;" id="postalCodeReturnId">
                                <td class="mainText" align="right"><span class="label">Postal Code</span></td>
                                <td width="7"></td>
                                <td>
                                    <input name="deliveryPostalCode" maxlength="10" size="19" tabindex="43" value="<?php echo ($country_code != 1 ? (USPS_SHIP_ADDRESS == 'Shipping' ? $order->delivery['postcode'] : $order->billing['postcode']):'');?>" id="deliveryPostalCode" style="width: 175px;" title="Delivery Address Zip Code" type="text">
                  <div style="display: none;"><label for="deliveryPostalCode">Postal Code</label></div>
                                </td>
                            </tr>
                            <tr style="display: none; visibility: hidden;" id="postalCodeReturnId3">
                      <td bgcolor="#ffffff"></td>
                                <td width="7"></td>
                                <td bgcolor="#ffffff" width="100"><div style="width: 110px;" class="bigbox" title="Postal Code Lookup">
                                  <a href="http://www.upu.int/" target="_blank" tabindex="44" title="Postal Code Lookup">Postal Code Lookup</a>
                        </div></td>
                    </tr>
                            <tr style="display: none; visibility: hidden;" id="postalCodeReturnId2"> <td colspan="3" height="10"></td> </tr>
                            
                            
              
              
                            <tr style="display: none; visibility: hidden;" id="deliveryPhoneNumberId">
                                <td class="mainText" align="right"><span class="mainTextRed">* </span><span class="label">Phone Number</span></td>
                                <td width="7"></td>
                                <td>
                                  <input name="deliveryPhoneNumber" maxlength="25" size="13" tabindex="45" value="<?php echo $order->customer['telephone']; ?>" id="deliveryPhoneNumber" style="width: 175px;" title="Delivery Address Phone Number" type="text">
                  <div style="display: none;"><label for="deliveryPhoneNumber">Phone Number</label></div>
                                </td>
                            </tr>
                            
                        
             
                            <tr style="display: none; visibility: hidden;" id="deliveryPhoneNumberId2"> <td colspan="3" height="10"></td> </tr>
                              
                            
              
              
                            <tr style="display: none; visibility: hidden;" id="deliveryFaxNumberId">
                                <td class="mainText" align="right"><span class="label">Fax Number</span></td>
                                <td width="7"></td>
                                <td>
                                    <input name="deliveryFaxNumber" maxlength="25" size="19" tabindex="46" value="" id="deliveryFaxNumber" style="width: 175px;" title="Delivery Address Fax Number" type="text">
                  <div style="display: none;"><label for="deliveryFaxNumber">Fax Number</label></div>
                              </td>
                          </tr>
                       
                    
            
                            <tr style="display: none; visibility: hidden;" id="deliveryFaxNumberId2"> <td colspan="3" height="10"></td> </tr>

                            
              
              
                            <tr>
                                <td class="mainText" align="right"><span class="label">Email</span></td>
                                <td width="7"></td>
                                <td><input name="deliveryEmail" maxlength="55" size="19" tabindex="47" value="<?php echo $order->customer['email_address']; ?>" onchange="checkEmailAddress('deliveryEmail', 'emailNotification')" id="deliveryEmail" style="width: 175px;" title="Delivery Address Email" type="text"><div style="display: none;"><label for="deliveryEmail">email</label></div></td>
                            </tr>
                            
                      
            
                        <tr> <td colspan="3" height="10"></td> </tr>
                        <tr>
                              <td class="mainText" align="right"></td>
                              <td width="7"></td>
                              <td>
                                  <table summary="This table is used to format page content." border="0" cellpadding="0" cellspacing="0" width="160">
                                      <tbody><tr>
                                          <td bgcolor="#cccccc" width="1"></td>
                                          <td bgcolor="#cccccc" width="27"></td>
                                          <td bgcolor="#cccccc" width="131"></td>
                                          <td bgcolor="#cccccc" width="1"></td>
                                      </tr>
                                      <tr>
                                          <td bgcolor="#cccccc" width="1"></td>
                                          <td valign="top" width="27"><input name="emailNotification" tabindex="48" value="true" id="emailNotification" title="Notify recipient of shipping." type="checkbox"<?php echo (USPS_EMAIL == 'YES' ? ' checked="checked"': '');?>><div style="display: none;"><label for="emailNotification"><span class="smmainText">Notify recipient of shipping, via email notification</span></label></div></td>
                                            <td class="smmainText" align="left" valign="middle" width="131"><span class="label">Notify recipient of shipping, via <a href="https://sss-web.usps.com/cns/html/popUpEmailShipNotification.html" target="someName31" tabindex="49" title="Email Ship Notification" onclick="javascript:void openWindow ('https://sss-web.usps.com/cns/html/popUpEmailShipNotification.html', 'someName31', 'width=250,height=180', 225, 112, window, 'resizable=1,menubar=0,toolbar=0,location=0,personalbar=0,status=0,scrollbars=0');">Email Ship Notification</a>.</span></td>
                                            <td bgcolor="#cccccc" width="1"></td>
                                        </tr>
                                        <tr>
                                            <td bgcolor="#cccccc" width="1"></td>
                                            <td bgcolor="#cccccc" width="27"></td>
                                            <td bgcolor="#cccccc" width="131"></td>
                                            <td bgcolor="#cccccc" width="1"></td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
              
                            
                            <tr>
                <td class="mainText" align="right"><a href="https://sss-web.usps.com/cns/html/ab_referenceNbr.html" target="someName31" tabindex="50" onclick="void openWindow('https://sss-web.usps.com/cns/html/ab_referenceNbr.html', 'someName31', 'width=250,height=185', 400, 150, window, 'resizable=1,menubar=0,toolbar=0,location=0,personalbar=0,status=0,scrollbars=0');"><img src="<?php echo $USPS_file_dir; ?>/question_icon.gif" alt="What is a Reference Number?" align="absmiddle" border="0" height="16" width="16"></a><span id="spanRefNbr">  Reference Number</span></td>
                                <td width="7"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="1" width="1"></td>
                <td><input name="deliveryRefNbr" maxlength="10" tabindex="51" value="<?php echo $HTTP_GET_VARS['oID']; ?>" style="width: 175px;" title="Delivery Address Reference Number" type="text"> 
              </td></tr>
              
                                       
                            <tr> <td colspan="3" height="10"></td> </tr>
                            
                        
                            <tr>
                                <td class="mainText" align="right"></td>
                                <td width="7"></td>
                                <td>
                                    <table summary="This table is used to format page content." border="0" cellpadding="0" cellspacing="0" width="160">
                                        <tbody><tr>
                                            <td bgcolor="#cccccc" width="1"></td>
                                            <td bgcolor="#cccccc" width="27"></td>
                                            <td bgcolor="#cccccc" width="131"></td>
                                            <td bgcolor="#cccccc" width="1"></td>
                                        </tr>
                                        <tr>
                                            <td bgcolor="#cccccc" width="1"></td>
                                            <td valign="top" width="27"><input name="saveDeliveryAddress" tabindex="52" value="Y" id="saveDeliveryAddress" title="Store in Address Book" type="checkbox"><div style="display: none;"><label for="saveDeliveryAddress"><span class="smmainText">Save in Address Book</span></label></div></td>
                                            <td class="mainText" align="left" valign="middle" width="131"><span style="visibility: visible;" class="label" id="SaveAddress">Save in Address Book</span><span style="display: none; visibility: hidden;" class="label" id="UpdateAddress">Update Address</span></td>
                                            <td bgcolor="#cccccc" width="1"></td>
                                        </tr>
                                        <tr>
                                            <td bgcolor="#cccccc" width="1"></td>
                                            <td bgcolor="#cccccc" width="27"></td>
                                            <td bgcolor="#cccccc" width="131"></td>
                                            <td bgcolor="#cccccc" width="1"></td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                            <tr> <td colspan="3" height="10"></td> </tr>
                        
                           
                        </tbody></table>
                    </td>
                    <!-- end of text boxes column -->
                </tr>
                <tr>
                    <!-- bottom white space inside box -->
                    <td height="7" width="13"></td>
                    <td height="7" width="272"></td>
                </tr>
            </tbody></table>
        </td>
        <td bgcolor="#cccccc" height="15" width="1"></td>
    </tr>
    <tr>
        <td bgcolor="#cccccc" height="1" width="1"></td>
        <td colspan="2" bgcolor="#cccccc"></td>
        <td bgcolor="#cccccc" height="1" width="1"></td>
    </tr>
</tbody></table>


  <script language="JavaScript">
     var shortName = document.forms[0].shortName.value;
   if(shortName != null && shortName.length > 0) {
      showElement('UpdateAddress');
      hideElement('SaveAddress');
   }else {
      hideElement('UpdateAddress');
      showElement('SaveAddress');
   }
  </script>
                                            </td>
                                            <td width="24"></td>
                                        </tr>
                                    </tbody></table>
                                    <table summary="This table formats the form information." border="0" cellpadding="0" cellspacing="0" width="671">
                                        <!-- enter package information -->
                                        <tbody><tr>
                                            <td height="10" width="24"></td>
                                            <td height="10" width="1"></td>
                                            <td height="10" width="10"></td>
                                            <td height="10" width="10"></td>
                                            <td height="10" width="611"></td>
                                            <td height="10" width="10"></td>
                                        </tr>
                                        <tr>
                                            <td width="24"></td>
                                            <td colspan="5" height="32" valign="top" width="647"><img src="<?php echo $USPS_file_dir; ?>/sub_enterpackage_info.gif" alt="Enter Package Information" border="0" height="23" width="150"></td>
                                        </tr>
                                        <tr>
                                        <td height="1" valign="top" width="24"></td>
                                        <td bgcolor="#cccccc" width="1"></td>
                                        <td colspan="4" bgcolor="#cccccc" height="1" valign="top" width="646"></td>
                                        </tr><tr>
                                            <td height="10" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" height="10" valign="top" width="1"></td>
                                            <td colspan="4" height="10" valign="top" width="646"></td>
                                        </tr>
                                    
                                        <tr>
                                            <td class="mainText" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" width="1"></td>
                                            <td colspan="4" width="646">
                                              <table summary="This table is used to format content." border="0" cellpadding="0" cellspacing="0" width="646">
                                                  <tbody><tr>
                                                      <td class="mainText" align="right" width="260"><span class="mainTextRed">* </span><span class="label">Weight</span></td>
                                                      <td width="12"></td>
                                                      <td align="left" bgcolor="#ffffff" height="20" width=""><input name="shippingWeightInPounds" maxlength="2" size="5" tabindex="55" value="<?php echo $shipping_pounds;?>" id="shippingWeightInPounds" style="width: 50px;" title="Shipping Weight in Pounds" type="text"><div style="display: none;"><label for="shippingWeightInPounds">Weight Pounds</label></div></td>
                                                      <td class="smmainText" align="left" valign="bottom" width=""><span class="smmainText">&nbsp;pounds</span></td>
                                                      <td align="left" bgcolor="#ffffff" height="20" width=""><input name="shippingWeightInOunces" maxlength="2" size="5" tabindex="56" value="<?php echo $shipping_ounces;?>" id="shippingWeightInOunces" style="width: 50px;" title="Shipping Weight in Ounces" type="text"><div style="display: none;"><label for="shippingWeightInOunces">Weight Ounces</label></div></td>
                                                      <td class="smmainText" align="left" valign="bottom" width=""><span class="smmainText">&nbsp;ounces</span></td>
                                                      <td width="">
                              <span id="totalPackageWeightId"><span style="width: 130px; margin-right: 140px; margin-bottom: 4px; float: right;" class="bigbox">Use 
                                <a href="https://sss-web.usps.com/cns/html/popUpTotalWeight.html" target="someName31" tabindex="57" title="See total package weight description" onclick="javascript:void openWindow ('https://sss-web.usps.com/cns/html/popUpTotalWeight.html', 'someName31', 'width=250,height=164', 225, 112, window, 'resizable=1,menubar=0,toolbar=0,location=0,personalbar=0,status=0,scrollbars=0');">total package weight</a></span></span>
                              </td>
                                                  </tr>
                                              
                                                  <tr> <td colspan="7" height="10" width="646"></td> </tr>
                                                  <tr style="visibility: visible;" id="girthExceedMaxId">
                                                      <td class="mainText" align="right" valign="top" width=""><span class="mainTextRed">* </span>Size</td>
                                                      <td width=""></td>
                                                      <td class="mainText" colspan="5" align="left" bgcolor="#ffffff" valign="top" width="">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                          <tbody><tr>
                                                            <td class="mainText">Is your package larger than 84 inches in length and girth?</td>
                                                            <td class="mainText"><span class="box"><a href="https://sss-web.usps.com/cns/html/popUpSizeRequirements.html" target="someName31" tabindex="58" title="See Size Requirements" onclick="javascript:void openWindow ('https://sss-web.usps.com/cns/html/popUpSizeRequirements.html', 'someName31', 'width=680,height=587', 225, 0, window, 'resizable=1,menubar=0,toolbar=0,location=0,personalbar=0,status=0,scrollbars=0');">See Size Requirements</a></span></td>
                                                          </tr>
                                                          <tr>
                                                            <td colspan="2" class="mainText"><input name="girthExceedMax" tabindex="59" value="N" checked="checked" id="sizeno" title="No" type="radio"> <label for="sizeno">No</label><input name="girthExceedMax" tabindex="60" value="Y" id="sizeyes" title="Yes" type="radio"> <label for="sizeyes">Yes</label></td>
                                                          </tr>
                                                        </tbody></table>
                                                      </td>
                                                  </tr>
                                                  
                          
                                                  <tr style="display: none; visibility: hidden;" id="valueContentsId">
                                                      <td class="mainText" align="right" valign="top" width=""><span class="mainTextRed">* </span><span class="mainText">Value of Contents</span></td>
                                                      <td width=""></td>
                                                      <td class="mainText" colspan="4" align="left" bgcolor="#ffffff" width=""><input name="intlContentsValue" maxlength="8" size="8" tabindex="61" value="<?php echo $contents_value ?>" id="intlContentsValue" style="width: 60px;" title="Value of Contents" type="text"><div style="display: none;"><label for="intlContentsValue">* Value of Contents</label></div></td>
                                                      <td valign="top" width="">&nbsp;</td>
                                                  </tr>
                                                  
                                                  <tr> <td colspan="7" height="10" width="646"></td> </tr>
                                                  <tr>
                                                      <td class="mainText" align="right" width=""><span class="mainTextRed">* </span><span class="label">Shipping Date</span></td>
                                                      <td width=""></td>
                                                      <td colspan="5" class="mainText" align="left" bgcolor="#ffffff" width=""><select name="shippingDate" size="1" tabindex="62" id="shippingDate" class="mainText">
                                                        <option value='<?php echo date('l m/d/y')?>'<?php echo (date('G') < USPS_CUTOFF_HOUR ? ' selected="selected"' : '');?>><?php echo date('l m/d/y')?></option>
                                                        <option value='<?php echo date('l m/d/y',mktime (0,0,0,date("m")  ,date("d")+1,date("Y")));?>'<?php echo (date('G') > USPS_CUTOFF_HOUR ? ' selected="selected"' : '');?>><?php echo date('l m/d/y',mktime (0,0,0,date("m")  ,date("d")+1,date("Y")));?></option>
                                                        <option value='<?php echo date('l m/d/y',mktime (0,0,0,date("m")  ,date("d")+2,date("Y")));?>'><?php echo date('l m/d/y',mktime (0,0,0,date("m")  ,date("d")+2,date("Y")));?></option>
                                                        <option value='<?php echo date('l m/d/y',mktime (0,0,0,date("m")  ,date("d")+3,date("Y")));?>'><?php echo date('l m/d/y',mktime (0,0,0,date("m")  ,date("d")+3,date("Y")));?></option>
                                                        </select><div style="display: none;"><label for="shipping_date">* Shipping Date</label></div></td>
                                                  </tr>
                                                  <tr> <td colspan="7" height="10" width="646"></td> </tr>
                                                  
                          
                                                  <tr>
                                                      <td class="mainText" align="right" width=""><span class="mainTextRed">* </span><span class="label">Shipping from ZIP Code&#8482;</span></td>
                                                      <td width=""></td>
                                                      <td colspan="5" class="mainText" align="left" bgcolor="#ffffff" width=""><input name="shipFromZipCode" tabindex="63" value="same" onclick="document.getElementById('otherZipCode').value=''" id="ship_from_same_zipcode" title="Ship from Same Zipcode" type="radio" <?php echo (USPS_SHIP_FROM_ZIP == ''  ? 'CHECKED' : ''); ?>><label for="ship_from_same_zipcode">Same as return address from above</label></td>
                                                  </tr>
                                                  <tr>
                                                      <td class="mainText" align="right" width=""></td>
                                                      <td width=""></td>
                                                      <td colspan="5" width="">
                                                          <table summary="This table is for formatting content." border="0" cellpadding="0" cellspacing="0" width="479">
                                                              <tbody><tr>
                                                                <td class="MainText" align="left" bgcolor="#ffffff" width="55"><input name="shipFromZipCode" tabindex="64" value="new" id="ship_from_other_zipcode" title="Ship from Other ZIP Code" type="radio" <?php echo (USPS_SHIP_FROM_ZIP == ''  ? '' : 'CHECKED'); ?>><label for="ship_from_other_zipcode">Other</label></td>
                                                                <td width="7"></td>
                                                                <td bgcolor="#ffffff" width="100"><input name="otherZipCode" maxlength="5" size="10" tabindex="65" value="<?php echo (USPS_SHIP_FROM_ZIP == ''  ? '' : USPS_SHIP_FROM_ZIP); ?>" onclick="document.getElementById('ship_from_other_zipcode').checked=true" id="otherZipCode" style="width: 90px;" title="Ship From Other ZIP Code" type="text"><div style="display: none;"><label for="other_zipcode">Shipping from ZIP Code Other ZIP Code&nbsp;If different from return address</label></div></td>
                                                                <td width="317"><span class="box">If different from return address</span></td>
                                                              </tr>
                                                              <tr>
                                                                <td bgcolor="#ffffff" width="55"></td>
                                                                <td width="7"></td>
                                                                <td bgcolor="#ffffff" width="100"><div style="width: 95px;" class="bigbox" title="ZIP Code Lookup">
                                                                  <a href="#" tabindex="66" title="ZIP Code Lookup" onclick="openBrWindow('http://www.usps.com/zip4/welcome.htm','window2','scrollbars=yes,width=775,height=700')">ZIP Code Lookup</a>
                                                                  </div>
                                                                </td>
                                                                <td width="317"></td>
                                                              </tr>
                                                          </tbody></table>
                                                      </td>
                                                  </tr>
                                                  
                                     <!-- Return To Sender -->
                                     
                                     <tr style="display: none; visibility: hidden;" id="returnToSenderId"><td colspan="8" height="10" width="1"></td></tr>
                                     <tr style="display: none; visibility: hidden;" id="returnToSenderId1">
                                                      <td class="mainText" align="right" width=""><span class="label">In Case of Non-Delivery</span></td>
                                                      <td width=""></td>
                                                      <td colspan="3" class="mainText" align="left" bgcolor="#ffffff"><input name="returnToSender" tabindex="67" value="Y" id="returnToSender" title="Return to Sender" type="checkbox" checked><label for="returnToSender"> Return to sender </label><a href="https://sss-web.usps.com/cns/html/popUpNonDelivery.html" target="someName31" tabindex="68" title="See size requirements" onclick="javascript:void openWindow ('https://sss-web.usps.com/cns/html/popUpNonDelivery.html', 'someName31', 'width=250,height=250', 400, 150, window, 'resizable=1,menubar=0,toolbar=0,location=0,personalbar=0,status=0,scrollbars=0');"><img src="<?php echo $USPS_file_dir; ?>/question_icon_002.gif" alt="What is In Case of Non-Delivery?" align="absmiddle" border="0" height="16" width="16"></a></td>
                                                      <td colspan="2"><div style="width: 90px;" class="bigbox">Fees may apply</div></td>
                                                  </tr>
                                              </tbody></table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="mainText" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" height="10" width="1"></td>
                                            <td colspan="5"></td>
                                        </tr>
                                        <tr style="visibility: visible;" id="weightMessageId">
                                            <td class="mainText" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" width="1"></td>
                                            <td colspan="2" width="15"></td>
                                            <td class="mainText" colspan="3" width="595"><span><b>Note:&nbsp;</b>If
the weight you entered is less than the actual weight of the package,
the Postal Service&#8482; will require additional postage either at the time
of mailing or delivery.</span></td>
                                        </tr>
                                        <tr>
                                            <td height="10" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" height="10" valign="top" width="1"></td>
                                            <td colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td height="1" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" height="1" valign="top" width="1"></td>
                                            <td colspan="4" bgcolor="#cccccc" height="1" valign="top" width="646"></td>
                                        </tr>
                                        <tr>
                                            <td height="10" width="24"></td>
                                            <td height="10" width="1"></td>
                                            <td height="10" width="10"></td>
                                            <td height="10" width="10"></td>
                                            <td height="10" width="611"></td>
                                            <td height="10" width="10"></td>
                                        </tr>
                                        <!--privacy act-->
                              <tr style="display: none; visibility: hidden;" id="privacyActId">
                                  <td width="24"></td>
                                  <td colspan="5" height="32" valign="top" width="647"><img src="<?php echo $USPS_file_dir; ?>/sub_privacy_statement.gif" alt="Privacy Act Statement" border="0" height="29" width="123"></td>
                              </tr>
                              <tr style="display: none; visibility: hidden;" id="privacyActId1">
                                  <td width="24"></td>
                                  <td bgcolor="#cccccc" width="1"></td>
                                  <td colspan="4" bgcolor="#cccccc" width="646"></td>
                              </tr>
                              
                    
                              <tr style="display: none; visibility: hidden;" id="privacyActId2">
                                  <td height="10" width="24"></td>
                                  <td bgcolor="#cccccc" height="10" width="1"></td>
                                  <td class="mainText" colspan="4" height="86" width="646">
                          <div style="margin-left: 100px; margin-right: 30px;">
                          <div style="display: none;"><span class="mainTextbold"><label for="privacy">Privacy Act Statement</label></span></div><br>
                          <textarea name="textarea" tabindex="69" cols="50" rows="3" readonly="readonly" id="privacy" title="Privacy Act Statement">Your
information will be used to satisfy reporting requirements for customs
purposes. Collection is authorized by 39 USC 401, 403, and 404.
Providing the information is voluntary, but if not provided, we may not
process your transaction. We do not disclose your information to third
parties without your consent, except to facilitate the transaction, to
act on your behalf or request, or as legally required. This included
the following limited circumstances: to a congressional office on your
behalf; to financial entities regarding financial transaction issues;
to a SUPS auditor; to entities, including law enforcement, as required
by law or in legal proceedings; to contractors and other entities
aiding us to fulfill the service (service providers); and to domestic
and international customs pursuant to federal law and agreements.</textarea>
                          </div>
                         <br>
                          <div style="border: 1px solid rgb(204, 204, 204); margin-left: 100px; margin-right: 260px; margin-bottom: 20px;">&nbsp;<span class="mainTextRed">* </span>            
                          <input name="privacyActStmt" tabindex="70" value="on" id="privacyActStmt" title="I understand and acknowledge the statement above." type="checkbox" checked> 
                          <label for="privacyActStmt"><span class="smmainTextgray">I understand and acknowledge the statement above.</span></label>
                          
                          </div>
                        </td>
                              </tr>   
                              <tr style="display: none; visibility: hidden;" id="privacyActId3">
                                  <td width="24"></td>
                                  <td bgcolor="#cccccc" width="1"></td>
                                  <td colspan="4" bgcolor="#cccccc" width="646"></td>
                              </tr>  
                                        <tr style="visibility: visible;" id="insuranceOptionId">
                                            <td width="24"></td>
                                            <td colspan="5" height="28" valign="top" width="647">
                                                <img src="<?php echo $USPS_file_dir; ?>/sub_insurance_option.gif" alt="Insurance Option" border="0" height="29" width="94">
                                                <a href="https://sss-web.usps.com/cns/html/popUpInsuranceOnline.html" target="someName31" tabindex="71" onclick="javascript:void openWindow ('https://sss-web.usps.com/cns/html/popUpInsuranceOnline.html', 'someName31', 'width=403,height=454', 380, 40, window, 'resizable=1,menubar=0,toolbar=0,location=0,personalbar=0,status=0,scrollbars=0');"><img src="<?php echo $USPS_file_dir; ?>/question_icon_002.gif" alt="What is the Insurance Option?" border="0" height="16" width="16"></a>
                                            </td>
                                        </tr>
                                        <tr style="visibility: visible;" id="insuranceOptionId1">
                                            <td height="1" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" width="1"></td>
                                            <td colspan="4" bgcolor="#cccccc" height="1" valign="top" width="646"></td>
                                        </tr>
                                        <tr style="visibility: visible;" id="insuranceOptionId2">
                                            <td height="10" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" height="10" valign="top" width="1"></td>
                                            <td colspan="4" height="10" valign="top" width="646"></td>
                                        </tr>
                                        <tr style="visibility: visible;" id="insuranceOptionId3">
                                            <td class="mainText" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" width="1"></td>
                                            <td colspan="4" width="646">
                                              
                        
                                                <table summary="This table is used to format content." border="0" cellpadding="0" cellspacing="0" width="646">
                                                    <tbody><tr>
                                                        <td class="mainText" align="right" width="160"><span class="mainText">Value of Contents</span></td>
                                                        <td width="7"></td>
                                                        <td class="mainText" width="10">$&nbsp;</td>
                                                        <td class="mainText" width="100"><input name="contentsValue" size="8" tabindex="72" value="<?php echo $send_value; ?>" id="contentsValue" style="width: 80px;" title="Value of Contents" type="text"><div style="display: none;"><label for="contents_value">Value of Contents</label></div></td>
                                                        <td width="250"><span class="box">
                                                          <a href="https://sss-web.usps.com/cns/html/popUpPackageRequirements.html" target="someName31" tabindex="73" title="See Packaging Requirements" onclick="void openWindow ('https://sss-web.usps.com/cns/html/popUpPackageRequirements.html', 'someName31', 'width=250,height=460', 225, 112, window, 'resizable=1,menubar=0,toolbar=0,location=0,personalbar=0,status=0,scrollbars=0');">See Packaging Requirements</a>
                              </span></td>
                                                        <td width="129"></td>
                                                    </tr>
                                                
                                                </tbody></table>
                                            </td>
                                        </tr>
                                        <tr style="visibility: visible;" id="insuranceOptionId4">
                                            <td height="10" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" height="10" valign="top" width="1"></td>
                                            <td colspan="4"></td>
                                        </tr>
                                        <tr style="visibility: visible;" id="insuranceOptionId5">
                                            <td class="mainText" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" width="1"></td>
                                            <td colspan="2" width="15"></td>
                                            <td class="mainText" colspan="2" width="595"><span class="mainTextbold">Note: </span>Maximum coverage is $500.00.</td>
                                        </tr>
                                        <tr style="visibility: visible;" id="insuranceOptionId6">
                                            <td height="10" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" height="10" valign="top" width="1"></td>
                                            <td colspan="4"></td>
                                        </tr>
                                        <tr style="visibility: visible;" id="insuranceOptionId7">
                                            <td height="1" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" height="1" valign="top" width="1"></td>
                                            <td colspan="4" bgcolor="#cccccc" height="1" valign="top" width="646"></td>
                                        </tr>
                                        
                                    
                                        <!-- start a batch order style="display: none; visibility: hidden;" -->
                                        <tr style="visbility: visible;" id="startBatchOrderId">
                                            <td height="10" width="24"></td>
                                            <td height="10" width="1"></td>
                                            <td height="10" width="10"></td>
                                            <td height="10" width="10"></td>
                                            <td height="10" width="611"></td>
                                            <td height="10" width="10"></td>
                                        </tr>
                                        <tr style="visbility: visible;" id="startBatchOrderId1">
                                            <td width="24"></td>
                                            <td colspan="5" height="32" valign="top" width="647"><img src="<?php echo $USPS_file_dir; ?>/sub_startabatch_order.gif" alt="Start a Batch Order" border="0" height="22" width="109"></td>
                                        </tr>
                                        <tr style="visbility: visible;" id="startBatchOrderId2">
                                          <td height="1" valign="top" width="24"></td>
                                          <td bgcolor="#cccccc" width="1"></td>
                                          <td colspan="4" bgcolor="#cccccc" height="1" valign="top" width="646"></td>
                                        </tr>
                                        <tr style="visbility: visible;" id="startBatchOrderId3">
                                            <td height="10" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" height="10" valign="top" width="1"></td>
                                            <td colspan="4" height="10" valign="top" width="646"></td>
                                        </tr>
                                        <tr style="visbility: visible;" id="startBatchOrderId4">
                                            <td class="mainText" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" width="1"></td>
                                            <td colspan="2" width="15"></td>
                                            <td class="mainText" colspan="2" width="595"><span>Using
Batch Order allows you to create multiple labels using the same package
weight, service option, and return address.&nbsp;&nbsp;You must fill in
the fields above to start your Batch Order.</span></td>
                                        </tr>
                                        <tr style="visbility: visible;" id="startBatchOrderId5">
                                            <td class="mainText" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" height="10" width="1"></td>
                                            <td colspan="4"></td>
                                        </tr>
                                        <tr style="visbility: visible;" id="startBatchOrderId6">
                                            <td class="mainText" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" width="1"></td>
                                            <td colspan="4" width="646">
                                                <table summary="This page is used to format content." border="0" cellpadding="0" cellspacing="0" width="646">
                                                    <tbody><tr>
                                                        <td width="167"></td>
                                                        <td class="mainText" width="137"><input name="startBatchOrder" tabindex="74" value="Y" id="startBatchOrder" title="Start Batch Order" type="checkbox"><label for="start_batch_order">Start a Batch Order<span style="display: none;">(Registration Required)</span></label></td>
                                                        <td width="342"></td>
                                                    </tr>
                                                </tbody></table>
                                            </td>
                                        </tr>
                                        <tr style="visbility: visible;" id="startBatchOrderId7">
                                            <td height="10" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" height="10" valign="top" width="1"></td>
                                            <td colspan="4"></td>
                                        </tr>
                                        <tr style="visbility: visible;" id="startBatchOrderId8">
                                            <td height="1" valign="top" width="24"></td>
                                            <td bgcolor="#cccccc" height="1" valign="top" width="1"></td>
                                            <td colspan="4" bgcolor="#cccccc" height="1" valign="top" width="646"></td>
                                        </tr>
                                    
                                    
                                        <tr>
                                            <td height="1" valign="top" width="24"></td>
                                            <td height="1" valign="top" width="1"></td>
                                            <td colspan="4" height="41" width="646">
                                                <table summary="This table formats page information." border="0" cellpadding="0" cellspacing="0" width="646">
                                                    <tbody><tr>
                                                        <td width="60"><a href="javascript:document.forms[0].submitControl.value='Back';document.forms[0].submit()" tabindex="75"><img src="<?php echo $USPS_file_dir; ?>/button_back.gif" alt="Go Back" border="0" height="17" hspace="3" width="43"></a></td>
                                                        <td></td>
                                                        <td align="right"><a href="javascript:submitContinue();" tabindex="76"><img src="<?php echo $USPS_file_dir; ?>/button_continue.gif" alt="Continue" border="0" height="17" hspace="3" width="62"></a><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="1" width="28"></td>
                                                    </tr>
                                                </tbody></table>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                                <td bgcolor="#cccccc" height="10"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="10" width="2"></td>
                            </tr>
                            <tr><td bgcolor="#cccccc" height="11" width="2"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="11" width="2"></td>
                                <td colspan="2" align="right" bgcolor="#cccccc" height="11" valign="top" width="673"><img src="<?php echo $USPS_file_dir; ?>/grey_diagonal2.gif" alt="" border="0" height="11" width="10"></td>
                            </tr>
                            <tr><td colspan="3" height="2" width="675"><img src="<?php echo $USPS_file_dir; ?>/spacer_002.gif" alt="" border="0" height="2" width="2"></td></tr>
                            <tr><td colspan="3" height="1" width="675"><img src="<?php echo $USPS_file_dir; ?>/bottom_grey.gif" alt="" border="0" height="1" width="665"></td></tr>
                        </tbody></table>
                    </td>
                </tr>
                <tr> <td colspan="2" align="right" height="9" valign="top">&nbsp;&nbsp;&nbsp;</td> </tr>
            </tbody></table>
        </td>
    </tr>
</tbody></table>
</form>

<script language="JavaScript">
  checkLabelInfoUrbanization('return', false, document.forms[0]);
  checkLabelInfoUrbanization('delivery', false, document.forms[0]);
  doCountry();
  findFocalPoint('deliveryFullName');
</script>







<noindex>
<!-- Footer table begins here -->

</noindex><table summary="This table is used to format the page footer" border="0" cellpadding="0" cellspacing="0" width="720">
    <tbody><tr>
        <td colspan="7" bgcolor="#cccccc" height="1"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="1" width="720"></td>
    </tr>
    <tr>
        <td colspan="7" bgcolor="#6699cc" height="1"><img alt="" src="<?php echo $USPS_file_dir; ?>/spacer.gif" border="0" height="1" width="720"></td>
    </tr>
    <tr>
        <td colspan="7" bgcolor="#ffffff" height="1"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="1" width="720"></td>
    </tr>
    <tr>
        <td colspan="7" bgcolor="#cccccc" height="2"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="2" width="720"></td>
    </tr>
    <tr>
        <td background="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="34" width="1"></td>
        <td colspan="5" align="center" background="<?php echo $USPS_file_dir; ?>/footer_background1.gif">
&nbsp;&nbsp;&nbsp;<a class="blueTextbold" href="http://www.usps.com/homearea/sitemap.htm?from=global&amp;page=0075sitemap">Site Map</a>
&nbsp;&nbsp;&nbsp;<a class="blueTextbold" href="http://www.usps.com/help/contactus/welcome.htm?from=global&amp;page=contactus">Contact Us</a>
&nbsp;&nbsp;&nbsp;<a class="blueTextbold" href="http://www.usps.com/forms/welcome.htm?from=global&amp;page=forms">Forms</a>
&nbsp;&nbsp;&nbsp;<a class="blueTextbold" href="http://www.usps.com/homearea/category/govtlinks.htm?from=global&amp;page=govtservices">Gov't Services</a>
&nbsp;&nbsp;&nbsp;<a class="blueTextbold" href="http://www.usps.com/employment/welcome.htm?from=global&amp;page=employment">Jobs</a>
&nbsp;&nbsp;&nbsp;<a class="blueTextbold" href="http://www.usps.com/homearea/docs/privpol.htm?from=global&amp;page=0080privacy">Privacy Policy</a>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="blueTextbold">|</span>&nbsp;&nbsp;&nbsp;&nbsp;<a class="blueTextbold" href="http://www.usps.com/nationalpremieraccounts/welcome.htm?from=global&amp;page=nationalpremier">National &amp; Premier Accounts</a>
</td>
        <td><img src="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" alt="" border="0" height="34" width="1"></td>
    </tr>
    <tr>
        <td><img src="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" alt="" border="0" height="1" width="1"></td>
        <td colspan="5" background="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="1" width="718"></td>
        <td><img src="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" alt="" border="0" height="1" width="1"></td>
    </tr>
    <tr>
        <td background="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="1" width="1"></td>
        <td colspan="5" class="smallgraytext" align="center" background="<?php echo $USPS_file_dir; ?>/footer_background2.gif" valign="bottom">Copyright © 1999-2006 USPS. All Rights Reserved.&nbsp;&nbsp;<a class="smallgraytext" href="http://www.usps.com/homearea/docs/termsofuse.htm?from=global&amp;page=0079termsofuse">Terms of Use</a>&nbsp;&nbsp;<a href="http://www.usps.com/nofearact/welcome.htm?from=global&amp;page=nofearact" class="smallgraytext">No FEAR Act EEO Data</a></td>
        <td background="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif"><img src="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" alt="" border="0" height="20" width="1"></td>
    </tr>
    <tr>
        <td background="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" width="1"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="42" width="1"></td>
        <td class="utilitybar" align="right" background="<?php echo $USPS_file_dir; ?>/footer_background2.gif" valign="middle" width="238"><a class="utilitybar" href="http://www.usps.com/postalinspectors?from=global&amp;page=postalinspectors"><img src="<?php echo $USPS_file_dir; ?>/badge_final.gif" alt="Postal Inspectors Web Page" border="0" height="29" hspace="2" width="29"></a>&nbsp;</td>
        <td class="utilitybar" align="left" background="<?php echo $USPS_file_dir; ?>/footer_background2.gif" valign="middle" width="110"><a class="utilitybar" href="http://www.usps.com/postalinspectors?from=global&amp;page=postalinspectors">Postal Inspectors<br>Preserving the Trust</a></td>
        <td align="center" background="<?php echo $USPS_file_dir; ?>/footer_background2.gif" valign="top" width="21"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="21" width="1"></td>
        <td align="right" background="<?php echo $USPS_file_dir; ?>/footer_background2.gif" valign="middle" width="34"><a href="http://www.usps.com/all/oig/welcome.htm?from=global&amp;page=oig"><img src="<?php echo $USPS_file_dir; ?>/badge_ig.gif" alt="Inspector General Web Page" border="0" height="34" hspace="2" width="26"></a></td>
        <td class="utilitybar" align="left" background="<?php echo $USPS_file_dir; ?>/footer_background2.gif" valign="middle" width="315"><a class="utilitybar" href="http://www.usps.com/all/oig/welcome.htm?from=global&amp;page=oig">Inspector General<br>Promoting Integrity</a></td>
        <td background="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" width="1"><img src="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" alt="" border="0" height="42" width="1"></td>
    </tr>
    <tr>
        <td background="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" width="1"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="1" width="1"></td>
        <td background="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" width="238"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="1" width="238"></td>
        <td background="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" width="110"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="1" width="110"></td>
        <td background="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" width="21"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="1" width="21"></td>
        <td background="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" width="34"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="1" width="34"></td>
        <td background="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" width="315"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="1" width="315"></td>
        <td background="<?php echo $USPS_file_dir; ?>/footer_bg_border.gif" width="1"><img src="<?php echo $USPS_file_dir; ?>/spacer.gif" alt="" border="0" height="1" width="1"></td>
    </tr>
</tbody></table>

<!-- Footer table ends here -->






</body></html>
