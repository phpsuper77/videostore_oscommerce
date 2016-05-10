<?php
/*
  $Id: abxAPIQueue.php,v 1.5 2008/03/13 14:33:05 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/
  include_once(DIR_FS_ABX5_EXTERNAL_CART . 'includes/classes/abxAPIQueueInterface.php');
  include_once(DIR_FS_ABX5_CLASSES . 'API/abxAPI.php');

  class abxAPIQueue extends abxAPIQueueInterface {

	function add($method, $data, $custom = null) {

		$api = array(
		  'method' 		=> $method,
		  'data'        => serialize($data),
		  'status'		=> 0,
		  'created'     => now(),
		  'custom1'		=> $custom
		);
		
		return $this->insertAPIQueue($api);
    }
    
    function process() {

      $count = 0;
      
      // Let's only process 5 API calls at a time
      $this->loadGetAPIQueue(0,3,5);
      
      while ($api_method = $this->getAPIQueue()) {
      	
     	$data = unserialize($api_method['data']);

      	// do the api call
        $response = abxAPI::call($api_method['method'], $data, 'array');
        if(abxAPI::isError($response) !== 1) {
        	
			// capture the error and add a retry.  We stop retrying after 3 failures.
			$update = array(
			  'status'		=> 0,
			  'retries'		=> $api_method['retries'] + 1,
			  'updated'     => now(),
			  'return_code' => $response['faultstring']
			);
			$this->updateAPIQueue($update, 'id = ' . $api_method['id']);
        } else {
        	
			// change the status code so that we do not re-process
			$update = array(
			  'status'		=> 1,
			  'updated'     => now(),
			  'return_code' => $response['result']
			);
			$this->updateAPIQueue($update, 'id = ' . $api_method['id']);
        }

		$count++;
      }
      $this->resetGetAPIQueue();

      return $count;
    }
  }//end class
?>