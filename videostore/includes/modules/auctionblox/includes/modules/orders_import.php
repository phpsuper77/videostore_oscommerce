<?php
/*
  $Id: orders_import.php,v 1.7 2009/10/12 14:08:11 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004-2008 AuctionBlox
*/

require_once(DIR_FS_ABX5_FUNCTIONS . 'xmlparser.php');
require_once(DIR_FS_ABX5_FUNCTIONS . 'formatter.php');
require_once_cart('includes/classes/abxOrderService.php'); 

class abxModule_Orders_Import extends abxModule {

    function abxModule_Orders_Import()
    {
        $this->process();
    }

    function process()
    {    
	  $result = file_get_contents('php://input');

	  $xmlAsArray = xml2array($result, 0, 'tag', 'UTF-8'); // ignore attributes since it messes up the xml
	  $order =& $xmlAsArray['order'];
			  
      $svc = new abxOrderService();
      $result = $svc->saveOrder($order);

	  echo '<?xml version="1.0" encoding="UTF-8"?>' .
	            '<result><code>' . $result['code'] . '</code>' . 
	            '<orderId>' . $result['orderId'] . '</orderId></result>';
    }     

}  //end class
?>