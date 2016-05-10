<?php
ob_start();
$language = 'english';

// set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// include server parameters
  require('includes/configure.php');

// define the project version
  define('PROJECT_VERSION', 'osCommerce 2.2-MS2');

// set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// customization for the design layout
  define('BOX_WIDTH', 160); // how wide the boxes should be in pixels (default: 125)

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// define general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

// check if sessions are supported, otherwise use the php3 compatible session class

    include(DIR_WS_FUNCTIONS. 'sessions.php');
    $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
    while ($configuration = tep_db_fetch_array($configuration_query)) {
	if ($configuration['cfgKey']!='MODULE_PAYMENT_INSTALLED' and $configuration['cfgKey']!='MODULE_ORDER_TOTAL_INSTALLED' and $configuration['cfgKey']!='MODULE_SHIPPING_INSTALLED')
      		define($configuration['cfgKey'], $configuration['cfgValue']);
    }

  require('includes/header.php');


// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');


  //$cart = new shoppingCart;

session_start();


if (empty($_SESSION['product'])) 
	$_SESSION['product'] = array();

if ($_GET[action]=='update'){
	unset($_SESSION['product']);
	foreach($_POST[products_qty] as $key=>$value){
		if (intval($value)!=0)	$_SESSION['product'][$key] = intval($value);
	}

}

$cart = $_SESSION['cart'];

if ($_GET[action]=='update'){

    	   $cart = new shoppingCart;
          $product = $HTTP_POST_VARS["products_qty"];          
          if($product!=''){
          foreach ($product as $key=>$value)
          {
          if (intval($value)>0) {          
          	$cart->add_cart($key, $cart->get_quantity(tep_get_uprid($key, $params))+intval($value));
          		}
          
          	}
	  $cart->calculate_vendor();
           $_SESSION['cart'] = $cart;
         }
	echo '<script>window.location.href="distribution.php"</script>';
}

?>
<!--<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr class="header">
    <td valign="middle"><a href="http://www.travelvideostore.com"><img src="../../images/tvs_banner.gif" border="0" alt="TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store" title=" TravelVideoStore.com More Travel Videos to More Places - TravelVideoStore.com offers more travel videos than any other store making TravelVideoStore.com the best Travel Video Store"></a></td>
<td align="right">

<a href="http://www.adventureexpo.com/"><img src="../../images/ate_dates.gif" border="0" alt="Join TravelVideoStore.com at the Adventures in Travel Expo - Dallas October 21-23, San Francisco November 11-13, Chicago January 9-11, New York January 13-15, Los Angeles January 28-29, and Washington DC February 10-12 as we provide a Travel Video Pavillion" title="Travel Video Pavillion provided by TravelVideoStore.com and Travel Shows Nationwide" TARGET="_blank"></a>

<a href="http://www.travelvideostore.com/returns.php"><img src="../../images/100satesfaction.gif" border="0" alt="Your Satesfaction is Guaranteed at TravelVideoStore.com" title="TravelVideoStore.com offers the industry's only satisfaction guarantee on travel videos - If you watch one of the travel videos we sell and don't like it, then return it!" TARGET="_blank"></a>
</td>
  </tr>
</table>-->

<?

// infobox
  require(DIR_WS_CLASSES . 'boxes.php');

// set the language
  if (!tep_session_is_registered('language') || isset($HTTP_GET_VARS['language'])) {
    if (!tep_session_is_registered('language')) {
      tep_session_register('language');
      tep_session_register('languages_id');
    }

    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language();

    if (isset($HTTP_GET_VARS['language']) && tep_not_null($HTTP_GET_VARS['language'])) {
      $lng->set_language($HTTP_GET_VARS['language']);
    } else {
      $lng->get_browser_language();
    }

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
  }


// include the language translations
  require(DIR_WS_LANGUAGES . 'english.php');


// include the Banner
  require(DIR_WS_FUNCTIONS .'banner.php');

?>