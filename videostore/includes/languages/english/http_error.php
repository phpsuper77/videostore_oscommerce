<?php
/*
  $Id: http_error.php,v 1.3 2004/06/30 20:55:17 chaicka Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', '%s ERROR');
define('TEXT_INFORMATION', 'We\'re sorry but the page you have requested has encountered the following error:
<br><br><b>%s</b><br><br>Please feel free to browse the rest of our store. You may also use the Search feature provided below to find the product you are looking for. We apologize for any inconvenience caused.');

define('EMAIL_BODY', 
'------------------------------------------------------' . "\n" .
'Site: %s.' . "\n" .
'Error Code: %s - %s' . "\n" .
'Occurred: %s' . "\n" .
'Requested URL: %s' . "\n" .
'User Address: %s' . "\n" .
'User Agent: %s' . "\n" .
'Referer: %s' . "\n" .
'------------------------------------------------------'
);

define('EMAIL_TEXT_SUBJECT', 'A Customer Received an HTTP Error');

//Client Error Codes 
define('ERROR_400_DESC', 'Bad Request');
define('ERROR_401_DESC', 'Authorization Required');
define('ERROR_403_DESC', 'Forbidden');
define('ERROR_404_DESC', 'Requested Page Not Found -  Many times this is due to website changes, please use the search bar to find the product you are searching for - TravelVideoStore.com - More Travel Videos to More Places!');
define('ERROR_405_DESC', 'Method Not Allowed');
define('ERROR_408_DESC', 'Request Timed Out');
define('ERROR_415_DESC', 'Unsupported Media Type');
define('ERROR_416_DESC', 'Requested Range Not Satisfiable');
define('ERROR_417_DESC', 'Expectation Failed');

//Server Error Codes
define('ERROR_500_DESC', 'Internal Server Error');
define('ERROR_501_DESC', 'Not Implemented');
define('ERROR_502_DESC', 'Bad Gateway');
define('ERROR_503_DESC', 'Service Unavailable');
define('ERROR_504_DESC', 'Gateway Timeout');
define('ERROR_505_DESC', 'HTTP Version Not Supported');
define('UNKNOWN_ERROR_DESC', 'Uknown Error');
?>