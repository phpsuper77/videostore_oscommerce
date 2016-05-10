<?php
/*
  $Id: column_left.php,v 1.15 2002/01/11 05:03:25 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require(DIR_WS_BOXES . 'configuration.php');
  require(DIR_WS_BOXES . 'catalog.php');
  require(DIR_WS_BOXES . 'modules.php');
  require(DIR_WS_BOXES . 'customers.php');
  require(DIR_WS_BOXES . 'taxes.php');
  require(DIR_WS_BOXES . 'localization.php');
  require(DIR_WS_BOXES . 'reports.php');
  require(DIR_WS_BOXES . 'affiliate.php');
  require(DIR_WS_BOXES . 'tools.php');
  require(DIR_WS_BOXES . 'feed.php');
  require(DIR_WS_BOXES . 'vhs.php');
  // VJ Links Manager v1.00 begin
  require(DIR_WS_BOXES . 'links.php');
  // VJ Links Manager v1.00 end
// vendor contribution  
require(DIR_WS_BOXES . 'vendors.php');
  //KEYWORD SHOW
  require(DIR_WS_BOXES . 'keyword_show.php');

 //require(DIR_WS_BOXES . 'discount.php'); //BEGIN -- Discount
require(DIR_WS_BOXES . 'gv_admin.php');  // ICW CREDIT CLASS Gift Voucher Addittion
// FAQ SYSTEM 2.1

  require(DIR_WS_BOXES . 'faq.php');

// FAQ SYSTEM 2.1
require(DIR_WS_BOXES . 'user_tracking.php');
require(DIR_WS_BOXES . 'amazon.php');
require(DIR_WS_BOXES . 'tracking.php');

require_once(DIR_WS_BOXES . 'm1_export.php');
?>