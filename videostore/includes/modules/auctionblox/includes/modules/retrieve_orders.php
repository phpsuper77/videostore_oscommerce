<?php
/*
  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004-2010 AuctionBlox
*/

require_once (DIR_FS_ABX5_FUNCTIONS . 'xmlparser.php');
require_once (DIR_FS_ABX5_EXTERNAL_CART . 'includes/classes/abxOrderManager.php'); 
require_once (DIR_FS_ABX5_EXTERNAL_CART . 'includes/classes/abxConfiguration.php'); 

class abxModule_Retrieve_Orders extends abxModule {

    function abxModule_Retrieve_Orders()
    {
        $this->process();
    }

    function process()
    {    
			//$result = file_get_contents('php://input');

		  //$xmlAsArray = xml2array($result, 0); // ignore attributes since it messes up the xml
	    //$order =& $xmlAsArray['order'];
		
		$orderMgr = new abxOrderManager();
		$timestamp = $_GET['updatedAfter'];	  
			
		$orders = $orderMgr->getOrderUpdates($timestamp);
			
		header ("Content-Type: text/xml; charset=utf-8");
		echo '<?xml version="1.0" encoding="utf-8"?> ' .
			 '<orders>' . array2xml($orders) . '</orders>';
		exit;
    }     

}  //end class
?>