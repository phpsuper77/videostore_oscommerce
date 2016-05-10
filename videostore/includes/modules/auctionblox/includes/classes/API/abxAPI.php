<?php
/*
  $Id: abxAPI.php,v 1.14 2006/10/02 03:06:51 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/

  require_once dirname(__FILE__).'/abxToken.php';

  class abxAPI {
  	
    function isValidAction($action) {

    }

	// param type = 'array' or 'string'
    function call($action, $params = array(), $paramType) {

		$client = new SoapClient(NULL, array(
									         "location" 		=> ABX_SOAP_API_URL,
											 "style"    		=> SOAP_RPC,
											 "uri"	   			=> ABX_SOAP_API_URL,
											 "exceptions"		=> true,
											 "trace"    		=> 1
										    ));
           
        $headerVars = abxAPI::createAuthToken($action);
        
        foreach ($headerVars as $key => $value) {	
        	$header[] = new SoapHeader(ABX_SOAP_API_URL,$key, $value);        	
        }

        $client->__setSoapHeaders($header);
        
        // build Params
        if ($paramType === 'string') {
        	foreach ($params as $key => $value) {
        		$soapParams[] = new SoapParam($value, $key);
        	}
        } else {
        	$soapParams = $params;
        }

       /* try {
        	$result = ($paramType === 'string') 
        		    ? $client->__call($action, $soapParams) 
        		    : $client->__call($action, array(new SoapVar($soapParams, XSD_ANYTYPE, $action, ABX_SOAP_API_URL)));
			        
        } catch(SoapFault $fault) {
			echo $client->__getLastRequest();
			return array('result' => null, 'status' => 0, 'faultstring' => $fault->faultstring);
        }*/
        
        return array('result' => $result, 'status' => 1, 'faultstring' => null);
		
    }

    function createAuthToken($sMethod) {
		$aParam = array();
		
		// authentication token
		$aParam['perform']   = $sMethod;
		$aParam['timestamp'] = abxConfiguration::getIso8601TimeStamp();
		$aParam['email']     = AUCTIONBLOX_USERID;
		
		$aParam['signature'] = abxToken::sign($aParam, AUCTIONBLOX_PASSCODE);

		return $aParam;
    }

    // --------------------------------------------------------------------

    function isError(&$aResult) {

    	return (intval($aResult['status']));
    }

    // --------------------------------------------------------------------

  }//end class

  // ----------------------------------------------------------------------

?>