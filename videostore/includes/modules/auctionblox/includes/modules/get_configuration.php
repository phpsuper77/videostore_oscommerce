<?php
/*
  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004-2010 AuctionBlox
*/

require_once (DIR_FS_ABX5_FUNCTIONS . 'xmlparser.php');
require_once_cart('includes/classes/abxConfiguration.php'); 

class abxModule_Get_Configuration extends abxModule {

    function abxModule_Get_Configuration()
    {
      $abxConfiguration = new abxConfiguration();

		  $statusCodes =  $abxConfiguration->getOrderStatuses();
			      
      $categories = array();  // pass by reference
      $abxConfiguration->getCategories($categories);
      
      $config['store']['order']['statusCodes'] = $statusCodes;
      $config['store']['categories'] = $categories;
//var_dump($categories);	  
      header ("Content-Type: text/xml; charset=utf-8");			
      echo array2xml($config); 
      exit;
    }
}  //end class
?>