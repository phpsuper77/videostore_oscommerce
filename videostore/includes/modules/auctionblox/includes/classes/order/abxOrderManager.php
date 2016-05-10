<?php

/*
  $Id: abxOrderManager.php,v 1.7 2007/06/23 01:31:46 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/
require_once (DIR_FS_ABX5_EXTERNAL_CART . 'includes/classes/abxConfiguration.php');
require_once (DIR_FS_ABX5_EXTERNAL_CART . 'includes/classes/order/abxOrderManagerInterface.php');
require_once (DIR_FS_ABX5_CLASSES . 'abxAPIQueue.php');

class abxOrderManager extends abxOrderManagerInterface
{
	function getUpdateSaleApiCall()
	{
		return 'updateSale';
	}
	
	function getNewSaleApiCall()
	{
		return 'addNewSale';
	}
	
	function synchronize()  {
		
		$apiQueue = new abxAPIQueue();
		
		$this->processUpdatedSales($apiQueue);
		$this->processNewSales($apiQueue);		
	
		$apiQueue->process();    
	
	}
	
	function processUpdatedSales(abxAPIQueue &$apiQueue) {

		$lastHistoryId = $this->getLastProcessedOrderStatusHistoryId();
		$maxHistoryId  = $this->getMaxOrderStatusHistoryId();
		
		$this->addUpdatedSalesToApiQueue($lastHistoryId, $apiQueue);
		
		abxConfiguration::updateByKey('LAST_UPDATED_ORDERS_STATUS_HISTORY_ID', $maxHistoryId);
	}
	
	function processNewSales(abxAPIQueue &$apiQueue) {
		
		$lastOrderId = $this->getLastProcessedOrderId();
		$maxOrderId  = $this->getMaxOrderId();
		
		$this->addNewSaleToApiQueue($lastOrderId, $apiQueue);
		
		abxConfiguration::updateByKey('LAST_UPDATED_ORDERS_ID', $maxOrderId);
	}
	
	function addUpdatedSalesToApiQueue($lastProcessedOrderStatusHistoryId, abxAPIQueue &$apiQueue) {
	
		if($data = $this->parseUpdatedSales($lastProcessedOrderStatusHistoryId))
			$apiQueue->add($this->getUpdateSaleApiCall(), $data);

	}
	
	function addNewSaleToApiQueue($lastProcessedOrderId, abxAPIQueue &$apiQueue) {
			  
		if($data = $this->parseNewSalesByLastOrderId($lastProcessedOrderId))
			$apiQueue->add($this->getNewSaleApiCall(), $data);
  
	}
	
	function getLastProcessedOrderStatusHistoryId() {
		
		$configItem = abxConfiguration::queryByKey('LAST_UPDATED_ORDERS_STATUS_HISTORY_ID');
		$lastId = $configItem['configuration_value'];
		
		if ($lastId == null || intval($lastId) <= 0) {
		  // must be the first time, so let's just get the last order status id from osCommerce and
		  // store that for later.  old orders won't be processed
		  //$lastId = $this->getMaxOrderStatusHistoryId();//Nilesh_2/19/2009
		  $lastId = $this->getMinOrderStatusHistoryId();//Nilesh_2/19/2009
		}
		return $lastId;
	}
	
	function getLastProcessedOrderId() {
		
		$configItem = abxConfiguration::queryByKey('LAST_UPDATED_ORDERS_ID');
		$lastOrderId = $configItem['configuration_value'];
		
		if ($lastOrderId == null || intval($lastOrderId) <= 0) {
		  // must be the first time, so let's just get the last order status id from osCommerce and
		  // store that for later.  old orders won't be processed
		  //$lastOrderId = $this->getMaxOrderId();//Nilesh_2/19/2009
		  $lastOrderId = 0;//Nilesh_2/19/2009
		}
		return $lastOrderId;
	}

} //end class
?>