<?php
/*
  $Id: gv_redeem.php,v 1.1.1.1.2.1 2003/04/18 16:56:03 wilt Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Gift Voucher System v1.0
  Copyright (c) 2001,2002 Ian C Wilson
  http://www.phesis.org

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Redeem Gift Card');
define('HEADING_TITLE', 'Redeem Gift Card');
define('TEXT_INFORMATION', 'For more information regarding Gift Cards, please see our <a href="' . tep_href_link(FILENAME_GV_FAQ,'','NONSSL').'">'.GV_FAQ.'.</a>');
define('TEXT_INVALID_GV', '<br><br><b>The Gift Card number that you entered may be invalid or has already been redeemed. <br><br>Please check to be sure that you entered your Gift Card number correctly, using all dashes.  <br><br>To contact customer service, please use the Contact Page or call us at 1-800-288-5123</b>');
define('TEXT_VALID_GV', 'Congratulations, you have redeemed a Gift Card worth %s');
?>