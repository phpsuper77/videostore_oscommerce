<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  AuctionBlox, Inc.
  Copyright (c) 2008 AuctionBlox, Inc.
  http://www.auctionblox.com

  Released under the GNU General Public License
*/

  class_exists('abxCurrencies') || require_once_cart('includes/classes/abxCurrencies.php');    
  class_exists('abxAttributes') || require_once_cart('includes/classes/abxAttributes.php');
  
//  define('ABX_DEFAULT_ORDER_STATUS_ID', DEFAULT_ORDERS_STATUS_ID); // You can change that do any order status id
  define('AUCTIONBLOX_ORDER_PENDING_STATUS', 2);
  define('AUCTIONBLOX_ORDER_PROCESSED_STATUS', DEFAULT_ORDERS_STATUS_ID);
  
  class abxOrderService
  {
    var $orderId,
        $customerId,
        $customerPassword,
        $currency;
    
    function saveOrder(&$orderInfo)
    {
      global $abxDatabase;
   
      if(isset($orderInfo['orderItem']['title']))
      {
        // Means we only have *1* order item, and the array structure is different...sucks!
        $orderItems = array($orderInfo['orderItem']);
      }
      else
      {
        // Already have an array like we want when order items >= 2
        $orderItems = $orderInfo['orderItem'];
      }
      
//      $attr = new abxAttributes();      
//      
//      // Determine the correct order status
//      reset($orderItems);
//      $orderStatus = AUCTIONBLOX_ORDER_PROCESSED_STATUS;    // We assume no attributes first!
//      foreach($orderItems as $orderItem)
//      {
//        if($attr->hasAttributes($orderItem['productId']))
//        {
//          // We have at least one product with attributes, so put the order in pending status
//          $orderStatus = AUCTIONBLOX_ORDER_PENDING_STATUS;
//        }
//      }

      $orderStatus = $orderInfo['status'];

      // get buyer info
      $buyerCountryId       = $this->getCountryIdByIsoCode($orderInfo['billingAddress']['country']);
      $buyerZoneId          = $this->getZoneIdByState($orderInfo['billingAddress']['state'],$buyerCountryId);
      $buyerCountryInfo     = $this->getCountryInfo($buyerCountryId);    
      
      //  Create the customer details
      if ($customerDetails = $this->getCustomer($orderInfo['buyer']['email'])) {
            $customerCountryDetails = $this->getCountryInfo($customerDetails['entry_country_id']); 
        
            $customerArray = array(   
                'customers_id'                  => $customerDetails['customers_id'],
                'customers_name'                => $customerDetails['customers_firstname'] . ' ' . $customerDetails['customers_lastname'],
                'customers_company'             => null,
                'customers_street_address'      => $customerDetails['entry_street_address'],
                'customers_city'                => $customerDetails['entry_city'],
                'customers_postcode'            => $customerDetails['entry_postcode'],
                'customers_state'               => $customerDetails['entry_state'],
                'customers_country'             => $customerCountryDetails['countries_name'],
                'customers_telephone'           => $orderInfo['billingAddress']['phone'],
                'customers_email_address'       => $orderInfo['buyer']['email'],
                'customers_address_format_id'   => $customerCountryDetails['address_format_id'])
            ;
      } else {
            $customerId = $this->createCustomer($orderInfo, $buyerCountryId, $buyerZoneId);
            $customerArray = array(   
                'customers_id'                  => $customerId,
                'customers_name'                => $orderInfo['billingAddress']['fullName'],
                'customers_company'             => null,
                'customers_street_address'      => $this->implodeStreetAddress($orderInfo['billingAddress']),
                'customers_city'                => $orderInfo['billingAddress']['city'],
                'customers_postcode'            => $orderInfo['billingAddress']['postalCode'],
                'customers_state'               => $orderInfo['billingAddress']['state'],
                'customers_country'             => $buyerCountryInfo['countries_name'],
                'customers_telephone'           => $orderInfo['billingAddress']['phone'],
                'customers_email_address'       => $orderInfo['buyer']['email'],
                'customers_address_format_id'   => $buyerCountryInfo['address_format_id'])
            ;       
      }
        
      
      $sql_data_array = array(    
            'delivery_name'                 => $orderInfo['shippingAddress']['fullName'],
            'delivery_company'              => null,
            'delivery_street_address'       => $this->implodeStreetAddress($orderInfo['shippingAddress']),
            'delivery_city'                 => $orderInfo['shippingAddress']['city'],
            'delivery_postcode'             => $orderInfo['shippingAddress']['postalCode'],
            'delivery_state'                => $orderInfo['shippingAddress']['state'],
            'delivery_country'              => $buyerCountryInfo['countries_name'],
            'delivery_address_format_id'    => $buyerCountryInfo['address_format_id'],
            'billing_name'                  => $orderInfo['billingAddress']['fullName'],
            'billing_company'               => null,
            'billing_street_address'        => $this->implodeStreetAddress($orderInfo['billingAddress']),
            'billing_city'                  => $orderInfo['billingAddress']['city'],
            'billing_postcode'              => $orderInfo['billingAddress']['postalCode'],
            'billing_state'                 => $orderInfo['billingAddress']['state'],
            'billing_country'               => $buyerCountryInfo['countries_name'],
            'billing_address_format_id'     => $buyerCountryInfo['address_format_id'],
            'payment_method'                => $orderInfo['transaction']['paymentMethod'],
            'last_modified'                 => date('Y-m-d H:i:s'), // we are using current time since Shipworks uses that
//            'last_modified'                 => date('Y-m-d H:i:s', parse_iso8601_datetime($orderInfo['updatedOn'])), // UTC to local time 
            'date_purchased'                => date('Y-m-d H:i:s'), // we are using current time since Shipworks uses that
//            'date_purchased'                 => date('Y-m-d H:i:s', parse_iso8601_datetime($orderInfo['createdOn'])), // UTC to local time 
            'orders_status'                 => $orderStatus,
            'currency'                      => $orderInfo['currency'],
            'currency_value'                => 1
      ); 
      
      $sql_data_array = array_merge($customerArray, $sql_data_array);

      $this->orderId = $abxDatabase->insert(TABLE_ORDERS, $sql_data_array);

      // create items
      reset($orderItems);
      foreach ($orderItems as $orderItem) 
      {
        $this->addOrderProduct($orderItem);
        $listingIds[] = $orderItem['listingId'];         
      }

      $subTotal = (DISPLAY_PRICE_WITH_TAX == 'true') ? $orderInfo['total'] : $orderInfo['total'] - $orderInfo['tax'];
      
      $this->orderTotals[] = $this->createOrderTotal($this->orderId, 'ot_subtotal', 1, $orderInfo['subtotal'], $orderInfo['currency'], 'Subtotal');
      $this->orderTotals[] = $this->createOrderTotal($this->orderId, 'ot_tax',      2, $orderInfo['tax'],      $orderInfo['currency'], 'Tax');
      $this->orderTotals[] = $this->createOrderTotal($this->orderId, 'ot_shipping', 3, $orderInfo['shipping'], $orderInfo['currency'], $orderInfo['shippingMethod']); 
      $this->orderTotals[] = $this->createOrderTotal($this->orderId, 'ot_total',    4, $orderInfo['total'],    $orderInfo['currency'], 'Total', true);

      $comment =    "AuctionBlox Order Import" . "\r\n"  .
                    " Payment Status    = " . $orderInfo['transaction']['status'] . "\r\n" .
                    " Payment Method    = " . $orderInfo['transaction']['paymentMethod'] . "\r\n" . 
                    " Payment Id        = " . $orderInfo['transaction']['transactionId'] . "\r\n" .
                    " Payment Date      = " . $orderInfo['transaction']['timestamp'].  " UTC\r\n" .
                    " Payment Amount    = " . abxCurrencies::format($orderInfo['transaction']['amount'], $orderInfo['transaction']['currency']) . "\r\n" . 
                    " Payment Currency  = " . $orderInfo['transaction']['currency'] .  "\r\n" .
                    " eBay User ID      = " . $orderInfo['buyer']['userId'] . "\r\n" .
                    " eBay Item(s)      = " . implode(', ', $listingIds);

      
      $this->orderStatusHistory = $this->createStatusHistory($this->orderId, $orderStatus, $comment, date('Y-m-d H:i:s', parse_iso8601_datetime($orderInfo['updatedOn'])));

      return array('code' => 'success', 'orderId' => $this->orderId);
    }
    
    function implodeStreetAddress($address)
    {
      if(isset($address['street2']) && is_string($address['street2']))
        return $address['street1'] . ', ' . $address['street2'];
      else
        return $address['street1'];
    }

    function createOrderTotal ($orderId, $class, $sortOrder, $value, $currencyCode, $title, $bold=false) {

        global $abxDatabase;

        $formattedValue = abxCurrencies::format($value, $currencyCode);

        $text = ($bold)
              ? "<b>" . $formattedValue . "</b>"
              : $formattedValue;

        $sql_data_array = array(
            'orders_id'         => $orderId,
            'title'             => $title,
            'text'              => $text,
            'value'             => $value,
            'class'             => $class,
            'sort_order'        => $sortOrder
        );

        $sql_data_array['id'] = $abxDatabase->insert(TABLE_ORDERS_TOTAL, $sql_data_array);

        return $sql_data_array;
    }

    /*
        This function should be extended to notify customer etc.
    */
    function createStatusHistory ($orderId, $status, $comment, $timestamp) {

        global $abxDatabase;
        $sql_data_array = array(
            'orders_id'         => $orderId,
            'orders_status_id'  => $status,
            'date_added'        => $timestamp,
            'customer_notified' => 0,
            'comments'          => $comment
        );

        $sql_data_array['id'] = $abxDatabase->insert(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

        return $sql_data_array;
    }

    function addOrderProduct (&$itemDetails) {

        global $abxDatabase;

        $model = $this->getProductModel($itemDetails['productId']);

        // calculate net from gross
        $taxRate = 0;

        if ($itemDetails['tax'] > 0)
            $taxRate = number_format($itemDetails['tax'] / $itemDetails['subtotal'] * 100, 2);

        // Update order items
        $sql_data_array = array(
          'orders_id'           => $this->orderId,
          'products_id'         => $itemDetails['productId'],
          'products_model'      => $model,
          'products_name'       => $itemDetails['title'],
          'products_price'      => ($itemDetails['subtotal']/$itemDetails['quantity']),
          'final_price'         => ($itemDetails['total']/$itemDetails['quantity']),
          'products_tax'        => $taxRate,
          'products_quantity'   => $itemDetails['quantity']
        );

        $order_products_id = $abxDatabase->insert(TABLE_ORDERS_PRODUCTS, $sql_data_array);
	
        $abxDatabase->query("update " . TABLE_PRODUCTS . " set products_quantity=products_quantity-{$itemDetails['quantity']} where products_id = " . (int)$itemDetails['productId']);

        return $order_products_id;
    }

    function getCustomer($email) {

        global $abxDatabase;

        $sQuery = "SELECT" .
                 " c.customers_id," .
                 " c.customers_firstname," .
                 " c.customers_lastname," .
                 " c.customers_default_address_id," .
                 " a.entry_street_address," .
                 " a.entry_suburb," .
                 " a.entry_postcode," .
                 " a.entry_city," .
                 " a.entry_state," .
                 " a.entry_country_id," .
                 " a.entry_zone_id" .
                 " FROM %s c" .
                 " LEFT JOIN %s a ON c.customers_id = a.customers_id" .
                 " WHERE a.address_book_id = c.customers_default_address_id" .
                 " AND c.customers_email_address = '%s'" .
                 " LIMIT 1";

        $sQuery = sprintf(
          $sQuery,
          TABLE_CUSTOMERS,
          TABLE_ADDRESS_BOOK,
          $abxDatabase->escape($email)
        );

        return $abxDatabase->fetch_row($sQuery);

    }

    function createCustomer(&$orderInfo, $countryID, $zoneId) {

      global $abxDatabase;

      $nameArray = $this->convertName($orderInfo['billingAddress']['fullName']);

      $aCustomer = array(
        'customers_gender'        => 'U',   // unknown
        'customers_firstname'     => $nameArray['firstName'],
        'customers_lastname'      => $nameArray['lastName'],
        'customers_email_address' => $orderInfo['buyer']['email'],
        //'customers_telephone'     => $orderInfo['billingAddress']['phone'], //CRE 6.3 does not have this
        'customers_newsletter'    => '0',
        'customers_password'      => $this->getNewPassword()
      );

      $this->customerId = $abxDatabase->insert(TABLE_CUSTOMERS, $aCustomer);

      $aInfo = array(
        'customers_info_id'                         => $this->customerId,
        'customers_info_number_of_logons'           => 0,
        'customers_info_date_account_created'       => date("Y-m-d H:i:s"),
        'customers_info_date_account_last_modified' => '0000-00-00 00:00:00'
      );

      $abxDatabase->insert(TABLE_CUSTOMERS_INFO, $aInfo);

      $aAddress = array(
        'customers_id'         => $this->customerId,
        'entry_gender'         => 'U',
        'entry_firstname'      => $nameArray['firstName'],
        'entry_lastname'       => $nameArray['lastName'],
        'entry_company'        => '',
        'entry_street_address' => rtrim(implode(",", array($orderInfo['billingAddress']['street1'],$orderInfo['billingAddress']['street2'])),","),
        'entry_suburb'         => '',
        'entry_city'           => $orderInfo['billingAddress']['city'],
        'entry_postcode'       => $orderInfo['billingAddress']['postalCode'],
        'entry_state'          => $orderInfo['billingAddress']['state'],
        'entry_country_id'     => $countryID,
        'entry_zone_id'        => $zoneId
      );

      $addressId = $abxDatabase->insert(TABLE_ADDRESS_BOOK, $aAddress);

      $abxDatabase->update(
        TABLE_CUSTOMERS,
        array('customers_default_address_id' => $addressId),
        'customers_id='. $this->customerId
      );

      return $this->customerId;

    }

    // May not be required, just adds a new address to address book without verifying
    // that it is different from current default address
    function customerUpdate(&$order)
    {
        return true;

/*      global $abxDatabase;

      $nameArray = $this->convertName($order['buyer']['name']);

      $aAddress = array(
        'customers_id'         => $this->customerId,
        'entry_gender'         => 'U',
        'entry_firstname'      => $nameArray['firstName'],
        'entry_lastname'       => $nameArray['lastName'],
        'entry_company'        => '',
        'entry_street_address' => implode(",", array($order['buyer']['street1'],$order['buyer']['street2'])),
        'entry_suburb'         => '',
        'entry_city'           => $order['buyer']['city'],
        'entry_postcode'       => $order['buyer']['zip'],
        'entry_state'          => $order['buyer']['state'],
        'entry_country_id'     => $this->buyerCountryId,
        'entry_zone_id'        => $this->buyerZoneId
      );

      $addressId = $abxDatabase->insert(TABLE_ADDRESS_BOOK, $aAddress);

      $abxDatabase->update(
        TABLE_CUSTOMERS,
        array('customers_default_address_id' => $addressId),
        'customers_id='.$this->customerId
      );

      $sWhere = sprintf(
        "ext_email_address = '%s' LIMIT 1",
        $abxDatabase->escape($this->sEmail)
      );

      $aUpdate = array(
        'customers_id' => $this->customerId
      );

      $abxDatabase->update(TABLE_ABX_WINNERS, $aUpdate, $sWhere);
*/
    }

    function getNewPassword() {

        $sPassword = '';
        $nLength   = 8;

        for($i=0; $i<$nLength; $i++) {
            $sChar = chr(rand(48, 122));

        while(!ereg("[a-zA-Z0-9]", $sChar)) {
          if ($sChar == @$sLastChar) {
            continue;
          }
          $sChar = chr(rand(48, 90));
        }
        $sPassword .= $sChar;
        $sLastChar  = $sChar;
        }

        $this->customerPassword = $sPassword;
        $sSalt                  = substr(md5($sPassword), 0, 2);
        $sEncryptedPassword     = md5($sSalt.$sPassword).':'.$sSalt;

        return $sEncryptedPassword;

    }

    function convertName($name) {

        $firstName = $name;
        $lastName = '';

        $nameArray = explode(' ', $name);

        if (is_array($nameArray)) {

        $firstName = $nameArray[0];  // firstname should be first element
        $name = array_shift($nameArray);  // pop firstname off array

        foreach ($nameArray as $key => $lname)
            $lastName .= $lname . ' ';
        }

          return array('firstName' => $firstName,
                   'lastName' => rtrim($lastName));
    }

    function getCountryIdByIsoCode($sCountryIsoCode) {

        $aCountries = $this->getCountries();

        foreach($aCountries as $nCountryId => $aCountry) {
            if ($aCountry['countries_iso_code_2'] == $sCountryIsoCode) {
              return $nCountryId;
            }
        }
    }

    function getCountries($nCountryId = null) {
        static $aCountries;

        if (!$aCountries) {
            global $abxDatabase;

            $sQuery = "SELECT countries_id,"
                            ."countries_name,"
                            ."countries_iso_code_2,"
                            ."countries_iso_code_3,"
                            ."c.address_format_id,"
                            ."address_format "
                    ."FROM %s c "
                    ."LEFT JOIN %s a on c.address_format_id = a.address_format "
                    ."ORDER BY countries_name";

            $sQuery = sprintf($sQuery, TABLE_COUNTRIES, TABLE_ADDRESS_FORMAT);
            $Query = $abxDatabase->query($sQuery);

            while($aCountry = $Query->next()) {
              $aCountries[$aCountry['countries_id']] = $aCountry;
            }
        }

        return (!$nCountryId) ? $aCountries : $aCountries[$nCountryId];
    }

    function hasCountryZones($nCountry = null) {

        static $aCountries;

        if (!($nCountry = intval($nCountry))) {
            $nCountry = $this->nCountryId;
        }

        if ($nCountry = intval($nCountry)) {
            if (!isset($aCountries[$nCountry])) {
              global $abxDatabase;

              $sQuery = "SELECT count(*) AS total "
                       ."FROM %s "
                       ."WHERE zone_country_id = %d";

              $aRes = $abxDatabase->fetch_row(sprintf($sQuery, TABLE_ZONES, $nCountry));

              $aCountries[$nCountry] = ($aRes['total'] > 0);
            }

            return $aCountries[$nCountry];
        }

        return false;
    }

    function getZoneIdByState($sState, $nCountry) {

        global $abxDatabase;

        $bCountryZones = $this->hasCountryZones($nCountry);

        if ($bCountryZones) {
            $sQuery = "SELECT DISTINCT zone_id "
                     ."FROM %s "
                     ."WHERE zone_country_id = %d "
                      ."AND (zone_name = '%s' OR zone_code = '%s')";

            $sQuery = sprintf(
              $sQuery,
              TABLE_ZONES,
              $nCountry,
              $abxDatabase->escape($sState),
              $abxDatabase->escape($sState)
            );
            
            $Query = $abxDatabase->query($sQuery);
            
            if ($Query->numRows() == 1) {
              return $Query->valueInt('zone_id');
            }
        }
    }
    
    function getCountryInfo($countryId) {
        
       global $abxDatabase;

        $sQuery = "SELECT DISTINCT c.countries_id,"
                        ."c.countries_name,"
                        ."c.countries_iso_code_2,"
                        ."c.countries_iso_code_3,"
                        ."c.address_format_id,"
                        ."a.address_format "
                ."FROM %s c "
                ."LEFT JOIN %s a on c.address_format_id = a.address_format "
                ."WHERE c.countries_id = %d ";

        $sQuery = sprintf($sQuery, TABLE_COUNTRIES, TABLE_ADDRESS_FORMAT,$countryId);
        $Query = $abxDatabase->query($sQuery);

        // Should only be one row
        return $Query->next();
    }
    
    function getProductModel ($productId) {
        
      global $abxDatabase;
        
          $sql = "SELECT products_model "
              . " FROM " . TABLE_PRODUCTS 
              . " WHERE products_id = '" . (int)$productId . "'";
        
          $results =  $abxDatabase->fetch_row($sql, TABLE_PRODUCTS);
        
          return $results['products_model'];
    }
}  // end class
?>