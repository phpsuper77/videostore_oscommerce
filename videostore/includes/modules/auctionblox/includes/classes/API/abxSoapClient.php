<?php
/*
  $Id: abxSoapClient.php,v 1.14 2006/10/02 03:06:51 auctionblox Exp $
*/
  require_once (DIR_FS_ABX5_EXTERNAL . 'nusoap/lib/nusoap.php');
  
  class abxSoapClient
  {
    function abxSoapClient()
    {
    }
    
    function callAuthenticated($service, $method, $params = array())
    {
        $header = 
            "<ser:authToken>" .
            " <username>anthony@auctionblox.com</username>" .
            "</ser:authToken>";
            
        return abxSoapClient::call($service, $method, $params, $header);
    }
    
    function call($service, $method, $params = array(), $headers = null)
    {
        $client = new nusoap_client(ABX_SOAP_API_URL . $service, false);
        $client->setUseCurl(ABX_SOAP_OPTION_USE_CURL);
        $client->soap_defencoding = 'UTF-8';
        
        $payload = '';
        foreach($params as $name => $value)
        {
          $value = utf8_encode(htmlspecialchars($value));
        	$payload .= "<$name>$value</$name>";
        }
        
        $body = "<ser:$method>$payload</ser:$method>";
       
        $request = $client->serializeEnvelope($body, $headers, array("ser" => "http://www.auctionbloxdev.net:10080/"),'document','literal');
        $result = $client->send($request, ABX_SOAP_API_URL . $service);
       
        if(ABX_SOAP_OPTION_DEBUG === true)
        {
            echo '<h2>Request</h2><pre>' . nl2br(htmlspecialchars($client->request, ENT_QUOTES)) . '</pre>';
            echo '<h2>Response</h2><pre>' . nl2br(htmlspecialchars($client->response, ENT_QUOTES)) . '</pre>';
            echo '<h2>Debug</h2><pre>' . nl2br(htmlspecialchars($client->getDebug(), ENT_QUOTES)) . '</pre>';
        }

        if($client->fault)
        {
            return $client->getError();
        }   
        return $result;
    }
}
