<?php

require_once (DIR_FS_ABX5_FUNCTIONS . 'xmlparser.php');
require_once (DIR_FS_ABX5_CLASSES . 'service/abxOrderService.php');  
require_once (DIR_FS_ABX5_EXTERNAL_CART . 'includes/classes/abxOrdersImportManager.php'); 
require_once (DIR_FS_ABX5_EXTERNAL_CART . 'includes/classes/abxConfiguration.php'); 

class abxModule_test_import {
	
    function abxModule_test_import()
    {
		  $this->process();      
    }
    
		function process()
		{
		  $xmlAsArray = xml2array($this->getOrderXml(), 0); // ignore attributes since it messes up the xml
	    $order =& $xmlAsArray['order'];
			  
      $orderMgr = new abxOrdersImportManager();
      $result = $orderMgr->saveOrder($order);
      
      var_dump($result);//    exit;
		}    
		
		function getOrderXml()
		{
			return "
		     <order>
                <orderNumber>38D51DD6C0A8006501044F782181E892</orderNumber>
                <createdOn>2008-04-10T14:53:26Z</createdOn>
                <updatedOn>2008-04-10T14:53:26Z</updatedOn>
				<status>PAID</status>
                <subtotal>149.97</subtotal>
                <tax>0.00</tax>
                <shipping>4.99</shipping>
                <total>155.97</total>
                <currency>USD</currency>			     
                <buyer>
                   <name>Anthony Musselwhite</name>
                   <email>auctionbloxseller8@auctionblox.com</email>
                   <userId>auctionbloxseller8</userId>
                </buyer>
                <billingAddress>
                     <fullName>Anthony Musselwhite</fullName>
                     <street1>4021 dutch harbor ct</street1>
                     <street2>Suite 201</street2>
                     <city>raleigh</city>
                     <state>NC</state>
                     <postalCode>27606</postalCode>
                     <country>US</country>
                     <phone>(919) 555-1212 ext.: 7411</phone>
				</billingAddress>
	            <shippingAddress>
                     <fullName>Kim Musselwhite</fullName>
                     <street1>211 Trent Woods Way</street1>
                     <street2></street2>
                     <city>Greenville</city>
                     <state>NC</state>
                     <postalCode>27512</postalCode>
                     <country>US</country>
                     <phone>(919) 334-8279</phone>
				 </shippingAddress>
    	         <orderItem>
                   <model/>
                   <productId>38</productId>
                   <originOfSale>ebay</originOfSale>
                   <listingId>1234274563</listingId>
                   <saleId>3580B42FC0A80dfd011BFDE428241167</saleId>
                   <position>0</position>
                   <quantity>1</quantity>
                   <subtotal>49.99</subtotal>
                   <tax>0.00</tax>
                   <title>Product-1</title>
                   <total>49.99</total>
                </orderItem>
                <orderItem>
                   <model/>
                   <productId>31</productId>
                   <originOfSale>ebay</originOfSale>
                   <listingId>1234234363</listingId>
                   <saleId>3580B42FC0A80065011BFDE428241167</saleId>
                   <position>1</position>
                   <quantity>1</quantity>
                   <subtotal>49.99</subtotal>
                   <tax>0.00</tax>
                   <title>Product-2</title>
                   <total>49.99</total>
                </orderItem>
                <orderItem>
                   <model/>
                   <productId>32</productId>
                   <originOfSale>ebay</originOfSale>
                   <listingId>1100223</listingId>
                   <saleId>3580B42FC0A80065011BEE7428241167</saleId>
                   <position>2</position>
                   <quantity>1</quantity>
                   <subtotal>49.99</subtotal>
                   <tax>0.00</tax>
                   <title>Product-3</title>
                   <total>49.99</total>
                </orderItem>
                <transaction>
                  <currency>USD</currency>
                  <amount>155.45</amount>
                  <paymentMethod>PayPal</paymentMethod>
    	          <transactionId>3423423423</transactionId>
                  <timestamp>2008-04-10T14:53:26Z</timestamp>
                </transaction>
     	        <shippingMethod>USPS First Class</shippingMethod>
             </order>";
	}
}
?>