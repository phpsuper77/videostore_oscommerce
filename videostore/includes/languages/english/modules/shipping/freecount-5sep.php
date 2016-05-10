<?php
/*
  $Id: item.php,v 1.5 2002/11/19 01:48:08 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
if(($_SESSION[whole][iswholesale] == 1)&& ($_SESSION[whole][shipping1] == 1)&& ($_SESSION[whole][shipping2] == 1))
{
	define('MODULE_SHIPPING_FREECOUNT_TEXT_TITLE', 'SHIPPING PER AGREEMENT');
}
elseif(($_SESSION[whole][iswholesale] == 1)&& ($_SESSION[whole][shipping1] == 1))
{
	define('MODULE_SHIPPING_FREECOUNT_TEXT_TITLE', 'SHIPPING PER AGREEMENT');
}
else
{
	define('MODULE_SHIPPING_FREECOUNT_TEXT_TITLE', 'FREE SHIPPING!');
}
define('MODULE_SHIPPING_FREECOUNT_TEXT_DESCRIPTION', 'Free Count2');
define('MODULE_SHIPPING_FREECOUNT_TEXT_WAY', 'Standard Mail (5 - 16 Business Days)');
?>
