<?php
/*
  $Id: configure.php,v 1.28 2008/04/02 18:49:18 devosc Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/
define('AUCTIONBLOX_VERSION', '5.3.0');
define('ABX_CART_INTEGRATION', 'oscommerce');

define('ABX_FEED_PRODUCTS_MAX_PER_PAGE', 100);


// define('ABX_SOAP_API_URL', 'http://www.auctionblox.ws/services/');
define('ABX_SOAP_API_URL', 'http://www.auctionbloxdev.net:10081/services/');
define('ABX_SOAP_OPTION_USE_CURL', false);
define('ABX_SOAP_OPTION_DEBUG', true);

define('DIR_FS_ABX5', realpath(dirname(__FILE__) . '/..') . '/');

//define('DIR_FS_MYABX', realpath(DIR_FS_ABX.'/../MyAuctionBlox').'/'.ABX_CART_INTEGRATION.'/');
  
define('DIR_FS_ABX5_INCLUDES', DIR_FS_ABX5 . 'includes/');

define('DIR_FS_ABX5_CLASSES', DIR_FS_ABX5_INCLUDES . 'classes/');

define('DIR_FS_ABX5_MODULES', DIR_FS_ABX5_INCLUDES . 'modules/');
//define('DIR_WS_ABX5_MODULES', DIR_WS_ABX5_INCLUDES . 'modules/');

define('DIR_FS_ABX5_FUNCTIONS', DIR_FS_ABX5_INCLUDES . 'functions/');

define('DIR_FS_ABX5_EXTERNAL', DIR_FS_ABX5 . 'external/');

define('DIR_FS_ABX5_INTERFACE', DIR_FS_ABX5 . 'interface/');

//cart specific code -------------------------------
define('DIR_FS_ABX5_EXTERNAL_CART', DIR_FS_ABX5_INTERFACE . ABX_CART_INTEGRATION.'/' );


//define("ABX_DEFAULT_IPN_IMPORT_ENV", "sandbox");
//define("ABX_PAYPAL_IPN_LOG", DIR_FS_ABX5_EXTERNAL . "paypal/log/paypal-ipn.log");
//define("ABX_PAYPAL_LOG_ERRORS", true);
?>