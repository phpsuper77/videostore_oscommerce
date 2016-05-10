<?php
  /*
  $Id: edit_orders.php, v2.2 2006/04/01 10:42:44 ams Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
 
  Original file written by Jonathan Hilgeman of SiteCreative.com
    
*/
  
  // First things first: get the required includes, classes, etc.
  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  include(DIR_WS_CLASSES . 'order.php');

function showPrice($new_desc, $product_price){
	for ($i=0;$i<count($new_desc);$i++){
		$sum = round(($product_price*$new_desc[$i]['final']/100), 4);
		$product_price = $product_price - $sum;
	}
	return round($product_price, 4);
}


// Next up: define the functions unique to this file 
 // Function    : tep_get_country_id
  // Arguments   : country_name		country name string
  // Return      : country_id
  // Description : Function to retrieve the country_id based on the country's name
  function tep_get_country_id($country_name) {
    $country_id_query = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_name = '" . $country_name . "'");
    if (!tep_db_num_rows($country_id_query)) {
      return 0;
    }
    else {
      $country_id_row = tep_db_fetch_array($country_id_query);
      return $country_id_row['countries_id'];
    }
  }

   // Function    : tep_get_zone_id
  // Arguments   : country_id		country id string    zone_name		state/province name
  // Return      : zone_id
  // Description : Function to retrieve the zone_id based on the zone's name
  function tep_get_zone_id($country_id, $zone_name) {
    $zone_id_query = tep_db_query("select * from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_name = '" . $zone_name . "'");
    if (!tep_db_num_rows($zone_id_query)) {
      return 0;
    }
    else {
      $zone_id_row = tep_db_fetch_array($zone_id_query);
      return $zone_id_row['zone_id'];
    }
  }
  
  // Function    : tep_field_exists
  // Arguments   : table	table name  field  	field name
  // Return      : true/false
  // Description : Function to check the existence of a database field
  function tep_field_exists($table,$field) {
    $describe_query = tep_db_query("describe $table");
    while($d_row = tep_db_fetch_array($describe_query))
    {
      if ($d_row["Field"] == "$field")
      return true;
    }
    return false;
  }

  // Function    : tep_html_quotes
  // Arguments   : string	any string
  // Return      : string with single quotes converted to html equivalent
  // Description : Function to change quotes to HTML equivalents for form inputs.
  function tep_html_quotes($string) {
    return str_replace("'", "&#39;", $string);
  }

  // Function    : tep_html_unquote
  // Arguments   : string	any string
  // Return      : string with html equivalent converted back to single quotes
  // Description : Function to change HTML equivalents back to quotes
  function tep_html_unquote($string) {
    return str_replace("&#39;", "'", $string);
  }
  //
  
  //Function : tep_get_tax_rates_description
  //courtesy of Hartmut Holzgraefe
  //code included for possible future changes to use of $RunningTax
  function tep_get_tax_rates_description($tax_class_id) {
   if ($tax_class_id == '0') {
      return TEXT_NONE;
    } else {
      $classes_query = tep_db_query("select tax_description from " . TABLE_TAX_RATES . " where tax_class_id = '" . (int)$tax_class_id . "'");
      $classes = tep_db_fetch_array($classes_query);

      return $classes['tax_description'];
    }
  }
  
  //set a default tax class, just in case
  // set default tax rate
  //code included for possible future changes to use of $RunningTax
   $default_tax_class = 1; 
   $default_tax_name  = tep_get_tax_rates_description($default_tax_class);
   
   
   // Then we get down to the nitty gritty
   
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : 'edit');

  // Update Inventory Quantity
  if (tep_not_null($action)) {
    switch ($action) {

	case 'assignNew':    	
		$obj = mysql_fetch_object(mysql_query("select count(*) as cnt from orders where orders_id='".$_GET[newId]."'"));
		if ($obj->cnt == 0){
			mysql_query("update orders set orders_id = '".$_GET[newId]."' where orders_id = '".$_GET[oID]."'");
			mysql_query("update orders_products set orders_id = '".$_GET[newId]."' where orders_id = '".$_GET[oID]."'");
			mysql_query("update orders_total set orders_id = '".$_GET[newId]."' where orders_id = '".$_GET[oID]."'");
			mysql_query("update orders_status_history set orders_id = '".$_GET[newId]."' where orders_id = '".$_GET[oID]."'");
			echo "<script>location.href='edit_orders.php?oID=".$_GET[newId]."'</script>";
		}
		else {
		        $messageStack->add('Order number '.$_GET[newId].' already exists in the system!', 'error');
		}
	break;

	// 1. UPDATE ORDER ###############################################################################################
	case 'update_order':
		
		$oID = tep_db_prepare_input($_GET['oID']);
		$order = new order($oID);
		$status = tep_db_prepare_input($_POST['status']);
		
		// 1.1 UPDATE ORDER INFO #####
		
		$UpdateOrders = "UPDATE " . TABLE_ORDERS . " set 
			customers_name = '" . tep_db_input(stripslashes($_POST['update_customer_name'])) . "',
			customers_company = '" . tep_db_input(stripslashes($_POST['update_customer_company'])) . "',
			customers_street_address = '" . tep_db_input(stripslashes($_POST['update_customer_street_address'])) . "',
			customers_suburb = '" . tep_db_input(stripslashes($_POST['update_customer_suburb'])) . "',
			customers_city = '" . tep_db_input(stripslashes($_POST['update_customer_city'])) . "',
			customers_state = '" . tep_db_input(stripslashes($_POST['update_customer_state'])) . "',
			customers_postcode = '" . tep_db_input($_POST['update_customer_postcode']) . "',
			customers_country = '" . tep_db_input(stripslashes($_POST['update_customer_country'])) . "',
			customers_telephone = '" . tep_db_input($_POST['update_customer_telephone']) . "',
			customers_email_address = '" . tep_db_input($_POST['update_customer_email_address']) . "',";
		
		$UpdateOrders .= "billing_name = '" . tep_db_input(stripslashes($_POST['update_billing_name'])) . "',
			billing_company = '" . tep_db_input(stripslashes($_POST['update_billing_company'])) . "',
			billing_street_address = '" . tep_db_input(stripslashes($_POST['update_billing_street_address'])) . "',
			billing_suburb = '" . tep_db_input(stripslashes($_POST['update_billing_suburb'])) . "',
			billing_city = '" . tep_db_input(stripslashes($_POST['update_billing_city'])) . "',
			billing_state = '" . tep_db_input(stripslashes($_POST['update_billing_state'])) . "',
			billing_postcode = '" . tep_db_input($_POST['update_billing_postcode']) . "',
			billing_country = '" . tep_db_input(stripslashes($_POST['update_billing_country'])) . "',";
		
		$UpdateOrders .= "delivery_name = '" . tep_db_input(stripslashes($_POST['update_delivery_name'])) . "',
			delivery_company = '" . tep_db_input(stripslashes($_POST['update_delivery_company'])) . "',
			delivery_street_address = '" . tep_db_input(stripslashes($_POST['update_delivery_street_address'])) . "',
			delivery_suburb = '" . tep_db_input(stripslashes($_POST['update_delivery_suburb'])) . "',
			delivery_city = '" . tep_db_input(stripslashes($_POST['update_delivery_city'])) . "',
			delivery_state = '" . tep_db_input(stripslashes($_POST['update_delivery_state'])) . "',
			delivery_postcode = '" . tep_db_input($_POST['update_delivery_postcode']) . "',
			delivery_country = '" . tep_db_input(stripslashes($_POST['update_delivery_country'])) . "',
			payment_method = '" . tep_db_input($_POST['update_info_payment_method']) . "',
			purchase_order_number = '" . tep_db_input($_POST['purchase_order_number']) . "',
			date_purchased = '" . tep_db_input($_POST['update_date_purchased']) . "',
			comments_slip = '" . stripslashes(tep_db_input($_POST['comments_slip'])) . "',
			cc_type = '" . tep_db_input($_POST['update_info_cc_type']) . "',
			cc_owner = '" . tep_db_input($_POST['update_info_cc_owner']) . "',
			cc_number = '" . tep_db_input($_POST['update_info_cc_number']) . "',
			cc_expires = '" . tep_db_input($_POST['update_info_cc_expires']) . "',
			shipping_tax = '" . tep_db_input($_POST['update_shipping_tax']) . "'";
		
		$UpdateOrders .= " where orders_id = '" . tep_db_input($_GET['oID']) . "';";

		tep_db_query($UpdateOrders);
		$order_updated = true;

    // 1.2 UPDATE STATUS HISTORY & SEND EMAIL TO CUSTOMER IF NECESSARY #####

    $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased, comments_slip, payment_method, purchase_order_number from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $check_status = tep_db_fetch_array($check_status_query); 
	
  if (($check_status['orders_status'] != $_POST['status']) || (tep_not_null($_POST['comments']))) {

        tep_db_query("UPDATE " . TABLE_ORDERS . " SET 
					  orders_status = '" . tep_db_input($_POST['status']) . "', 
                      last_modified = now() 
                      WHERE orders_id = '" . (int)$oID . "'");
		
		 // Notify Customer ?
      $customer_notified = '0';
			if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
			  $notify_comments = '';
			  if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
			    $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $_POST['comments']) . "\n\n";
			  }
			  $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]) . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE2);

			  if (trim($check_status['payment_method']) == 'Amazon Marketplace' || trim($check_status['payment_method']) == 'Amazon UK Marketplace')
	   			$subject = $check_status['payment_method'] . ' Order '.$check_status[purchase_order_number].' Update';
			  else
	   			$subject = EMAIL_TEXT_SUBJECT;

			  tep_mail($check_status['customers_name'], $check_status['customers_email_address'], $subject, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			  $customer_notified = '1';
			}			  
          		
			tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " 
				(orders_id, 
				orders_status_id, 
				date_added, 
				customer_notified, 
				comments) 
				values ('" . tep_db_input($_GET['oID']) . "', 
				'" . tep_db_input($_POST['status']) . "', 
				now(), 
				" . tep_db_input($customer_notified) . ", 
				'" . tep_db_input($_POST['comments'])  . "')");
			}

		// 1.3 UPDATE PRODUCTS #####
		$RunningSubTotal = 0;
		$RunningTax = 0;
		//$RunningTax = array($default_tax_name => 0);

    // Do pre-check for subtotal field existence (CWS)
		$ot_subtotal_found = false;
		$ot_total_found = false;
		if (is_array ($_POST['update_totals'])) {
	foreach($_POST['update_totals'] as $total_details) {
		  extract($total_details,EXTR_PREFIX_ALL,"ot");
			if($ot_class == "ot_subtotal") {
			  $ot_subtotal_found = true;
    	break;
			}
			
			if($ot_class == "ot_total"){
			$ot_total_found = true;
			break;
			}
		}//end foreach() 
		}//end if (is_array())
		        
		// 1.3.1 Update orders_products Table
		if (is_array ($_POST['update_products'])){
		foreach($_POST['update_products'] as $orders_products_id => $products_details)	{
		$ondelete = 0;
/* ========================== Back to Stock ====================== */
		if (intval($products_details["back"])>0){
		//$ondelete = 1;
			$currency_info = tep_db_fetch_array(tep_db_query("select currency, currency_value from orders where orders_id='".$_GET['oID']."'"));

			$products_price = tep_db_fetch_array(tep_db_query("select final_price as price from orders_products where orders_products_id='".$products_details[back]."'"));
			$back_subtotal = tep_db_fetch_array(tep_db_query("select value as subtotal from orders_total where class='ot_subtotal' and orders_id='".$_GET['oID']."'"));
			$back_total = tep_db_fetch_array(tep_db_query("select value as total from orders_total where class='ot_total' and orders_id='".$_GET['oID']."'"));			

$back_subtotal_value = $back_subtotal[subtotal] - $products_price[price];
$back_total_value = $back_total[total] - $products_price[price];

$text = $currencies->format($back_subtotal_value,true,$currency_info[currency],$currency_info[currency_value]);
			tep_db_query("update orders_total set value='".$back_subtotal_value."', text='<b>".$text."</b>' where class='ot_subtotal' and orders_id='".$_GET['oID']."'");			
//echo "update orders_total set value='".$back_subtotal_value."', text='<b>".$text."</b>' where class='ot_subtotal' and orders_id='".$_GET['oID']."'<br/>";

$text = $currencies->format($back_total_value,true,$currency_info[currency], $currency_info[currency_value]);
			tep_db_query("update orders_total set value='".$back_total_value."', text='<b>".$text."</b>' where class='ot_total' and orders_id='".$_GET['oID']."'");			
//echo "update orders_total set value='".$back_total_value."', text='<b>".$text."</b>' where class='ot_total' and orders_id='".$_GET['oID']."'<br/>";


    			tep_db_query("update products set products_ordered = products_ordered - " . $products_details[qty] . ", products_quantity = products_quantity + " . $products_details[qty]." where products_id = '" . $products_details[back] . "'");
//echo "update products set products_ordered = products_ordered - " . $products_details[qty] . ", products_quantity = products_quantity + " . $products_details[qty]." where products_id = '" . $products_details[back] . "'<br/>";
			tep_db_query("update orders_products set products_price = '0.00', final_price = '0.00', products_tax='0.00', products_item_cost='0.00', back=1 where orders_products_id = '" . $products_details[back] . "'");
//echo "update orders_products set products_price = '0.00', final_price = '0.00', products_tax='0.00', products_item_cost='0.00', back=1 where orders_products_id = '" . $products_details[back] . "'<br/>";
		}

/* ========================== End of Back to Stock ====================== */

			// 1.3.1.1 Update Inventory Quantity
			$order_query = tep_db_query("SELECT products_id, products_quantity 
			FROM " . TABLE_ORDERS_PRODUCTS . " 
			WHERE orders_id = '" . (int)$oID . "'
			AND orders_products_id = '$orders_products_id'");
			$order = tep_db_fetch_array($order_query);
			
			// First we do a stock check 
			if ($products_details["qty"] != $order['products_quantity']){
			$quantity_difference = ($products_details["qty"] - $order['products_quantity']);
				if (STOCK_CHECK == 'true'){
				    tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET 
					products_quantity = products_quantity - " . $quantity_difference . ",
					products_ordered = products_ordered + " . $quantity_difference . " 
					WHERE products_id = '" . (int)$order['products_id'] . "'");
					} else {
					tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
					products_ordered = products_ordered + " . $quantity_difference . "
					WHERE products_id = '" . (int)$order['products_id'] . "'");
				}
			}
               
			 //Then we check if the product should be deleted  
			 if (isset($products_details['delete'])){
			 //$ondelete = 1;
			 //update quantities first
			 if (STOCK_CHECK == 'true'){
				    tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET 
					products_quantity = products_quantity + " . $products_details["qty"] . ",
					products_ordered = products_ordered - " . $products_details["qty"] . " 
					WHERE products_id = '" . (int)$order['products_id'] . "'");
					} else {
					tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
					products_ordered = products_ordered - " . $products_details["qty"] . "
					WHERE products_id = '" . (int)$order['products_id'] . "'");
					}
					
			//then delete the little bugger
			$Query = "DELETE FROM " . TABLE_ORDERS_PRODUCTS . " 
			WHERE orders_id = '" . (int)$oID . "' 
			AND orders_products_id = '$orders_products_id';";
				tep_db_query($Query);
							
				// and all its attributes
				if(isset($products_details[attributes]))
				{
				$Query = "DELETE from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " 
				WHERE orders_id = '" . (int)$oID . "' 
				AND orders_products_id = '$orders_products_id';";
				tep_db_query($Query);
				}
				
			
			}// end of if (isset($products_details['delete']))
			
			   else { // if we don't delete, we update
				$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS . " SET
					products_model = '" . $products_details["model"] . "',
					products_name = '" . tep_html_quotes($products_details["name"]) . "',
					final_price = '" . $products_details["final_price"] . "',
					products_tax = '" . $products_details["tax"] . "',
					date_shipped = '".$products_details["shipped"]."',
					returned_reason = '".tep_html_quotes($products_details["reason"])."',
					date_shipped_checkbox = '".$products_details["date_shipped_checkbox"]."',
					returned_reason_checkbox = '".$products_details["returned_reason_checkbox"]."',
					products_quantity = '" . $products_details["qty"] . "'
					WHERE orders_id = '" . (int)$oID . "'
					AND orders_products_id = '$orders_products_id';";
				tep_db_query($Query);
require(DIR_WS_INCLUDES . 'vendor_checkout_process.php');                        	
//echo $ondelete."----".$products_details["final_price"]."----";
			if ($ondelete==0){
   				//update subtotal and total during update function
				if (DISPLAY_PRICE_WITH_TAX == 'true') {
				$RunningSubTotal += (($products_details['tax']/100 + 1) * ($products_details['qty'] * $products_details['final_price'])); 
				} else {
//echo $products_details["qty"]." * ".$products_details["final_price"]."<br/>";
				$RunningSubTotal += $products_details["qty"] * $products_details["final_price"];
				}
//echo "|||".$RunningSubTotal."|||<br/>";

				$RunningTax += (($products_details['tax']/100) * ($products_details['qty'] * $products_details['final_price']));

				//$RunningTax[$products_details['tax_description']] += (($products_details['tax']/100) * ($products_details['qty'] * $products_details['final_price']));

				// Update Any Attributes
				if(isset($products_details[attributes]))
				{
					foreach($products_details["attributes"] as $orders_products_attributes_id => $attributes_details)
					{
						$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
							products_options = '" . $attributes_details["option"] . "',
							products_options_values = '" . $attributes_details["value"] . "',
							options_values_price ='" . $attributes_details["price"] . "',
							price_prefix ='" . $attributes_details["prefix"] . "'
							where orders_products_attributes_id = '$orders_products_attributes_id';";
						tep_db_query($Query);
					}//end of foreach($products_details["attributes"]
				}// end of if(isset($products_details[attributes]))
				}// end of if/else (isset($products_details['delete']))
			
		}//end of foreach
		}//end of if (is_array())
		
		// 1.4 UPDATE SHIPPING, CUSTOM FEES, DISOUNTS, TAXES, AND TOTALS #####
//mail('x0661t@d-net.kiev.ua', 'subtotal', $RunningSubTotal);

if ($_POST[flagged]!=1) require(DIR_WS_INCLUDES . 'affiliate_checkout_process.php');

	// 1.4.0.1 Shipping Tax

			if (is_array ($_POST['update_totals'])){
			foreach($_POST['update_totals'] as $total_index => $total_details)
			{
				extract($total_details,EXTR_PREFIX_ALL,"ot");
				if($ot_class == "ot_shipping")
				{
				    if (DISPLAY_PRICE_WITH_TAX == 'true') {
					$RunningTax += ($ot_value / (($_POST['update_shipping_tax'] / 100) + 1)) * ($_POST['update_shipping_tax'] / 100);
					} else {
					$RunningTax += (($_POST['update_shipping_tax'] / 100) * $ot_value);
					}
				}
			  }
		    }
		
		//1.4.1.0
		$RunningTotal = 0;
		$sort_order = 0;
			
			// 1.4.1.1 Do pre-check for Tax field existence
			$ot_tax_found = 0;
			if (is_array ($_POST['update_totals'])){
			foreach($_POST['update_totals'] as $total_details)	{
				extract($total_details,EXTR_PREFIX_ALL,"ot");
				if($ot_class == "ot_tax")
				{
					$ot_tax_found = 1;
					break;
				}
				
  //This section is, for reasons I cannot yet comprehend, necessary for section 1.4.1.1, 
  //below, to work properly.  Without it the text value is written to the db as '0'
		if ($ot_class == "ot_total" || $ot_class == "ot_tax" || $ot_class == "ot_subtotal" || $ot_class == "ot_shipping" || $ot_class == "ot_custom" || $ot_class == "ot_loworderfee") {
		$order = new order($oID);
        $RunningTax += 0 * $products_details['tax'] / $order->info['currency_value'] / 100 ; 
		 }
  ///////////////////End bizarro code section
			
			}//end foreach
			}//end if (is_array
}				
					

			// 1.4.1.1  If ot_tax doesn't exist, but $RunningTax has been calculated, create an appropriate entry in the db and add tax to the subtotal or total as appropriate
			if (($RunningTax != 0) && ($ot_tax_found != 1)) {
			//if ((array_sum($RunningTax) != 0) && ($ot_tax_found != 1)) {
			//foreach ($RunningTax as $key => $val) {
			//	if ($val > 0) {
			$Query = "INSERT INTO " . TABLE_ORDERS_TOTAL . " set
							orders_id = '" . $oID . "',
							title ='" . ENTRY_TAX . "',
							text = '" . $currencies->format($RunningTax, true, $order->info['currency'], $order->info['currency_value']) . "',
				            value = '" . $RunningTax . "',
							class = 'ot_tax',
							sort_order = '2'";
						tep_db_query($Query);
						$ot_tax_found = 1;
						
						if (DISPLAY_PRICE_WITH_TAX == 'true') {
						    $RunningSubTotal += $RunningTax;
						   } else {
						    $RunningTotal += $RunningTax;
						  }
						// }
						// } 
						}
						
				////////////////////OPTIONAL- create entries for subtotal and/or total if none exists
				/*			
			//1.4.1.2
			/////////////////////////Add in subtotal to db if it doesn't already exist
			if (($RunningSubTotal >0) && ($ot_subtotal_found != true)) {
				$Query = 'INSERT INTO ' . TABLE_ORDERS_TOTAL . ' SET
							orders_id = "' . $oID . '",
							title ="' . ENTRY_SUB_TOTAL . '",
							text = "' . $currencies->format($RunningSubTotal, true, $order->info['currency'], $order->info['currency_value']) . '",
				            value = "' . $RunningSubTotal . '",
							class = "ot_subtotal",
							sort_order = "1"';
						tep_db_query($Query);
						$ot_subtotal_found = true;
						$RunningTotal += $RunningSubTotal;
						}
						//1.4.1.3
			/////////////////////////Add in total to db if it doesn't already exist
			if (($RunningTotal >0) && ($ot_total_found != true)) {
				$Query = 'INSERT INTO ' . TABLE_ORDERS_TOTAL . ' SET
							orders_id = "' . $oID . '",
							title ="' . ENTRY_TOTAL . '",
							text = "' . $currencies->format($RunningTotal, true, $order->info['currency'], $order->info['currency_value']) . '",
				            value = "' . $RunningTotal . '",
							class = "ot_total",
							sort_order = "4"';
						tep_db_query($Query);
						$ot_total_found = true;
						}
						*/
						//////////////////////////end optional section
/*STARTED*/
$disc = 0;

			// 1.4.2. Summing up total
			if (is_array ($_POST['update_totals'])) {
			foreach($_POST['update_totals'] as $total_index => $total_details)	
			
			{
			
		 	 // 1.4.2.1 Prepare Tax Insertion			
			extract($total_details,EXTR_PREFIX_ALL,"ot");
			
			
		 // 1.4.2.2 Update ot_subtotal, ot_tax, and ot_total classes aka "The Final Countdown"
				if (trim($ot_title) && trim($ot_value)) 
				
				{
//echo $RunningTotal."<br/>";
					$sort_order++;
					if ($ot_class == "ot_qty_discount") {
						$disc = $ot_value;
						
					}	

					if ($ot_class == "ot_subtotal") {
						$subTotal = $RunningSubTotal + $disc;
						$ot_value = $RunningSubTotal + $disc;
						
					}	


					//this calculation is way too simple, because					

					if ($ot_class == "ot_tax") {
						$tax = (trim($_POST['update_shipping_tax']=='')) ? 7 : $_POST['update_shipping_tax'];
						$ot_value = ($subTotal/100) * intval($tax);//$RunningTax;
						//$ot_value = $RunningTax[preg_replace("/:$/","",$ot_title)];
						// there might be more than one tax....
					}

				    // Check for existence of subtotals (CWS)                      
					if ($ot_class == "ot_total") {
					$ot_value = $RunningTotal-$disc;
					
				         		          
				          if ( !$ot_subtotal_found ) 
				          { // There was no subtotal on this order, lets add the running subtotal in.
				               $ot_value +=  $RunningSubTotal;
				          }
				     
				     }
									
					// Set $ot_text (display-formatted value)
                    			$order = new order($oID);
					$ot_text = $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']);
						
				//this little ditty writes the total into the database in with <b> and </b>
					if ($ot_class == "ot_total") {
						$ot_text = "<b>" . $ot_text . "</b>";
					}
					
					if($ot_total_id > 0) { // Already in database --> Update
						$Query = "UPDATE " . TABLE_ORDERS_TOTAL . " set
							title = '" . $ot_title . "',
							text = '" . $ot_text . "',
							value = '" . $ot_value . "',
							sort_order = '" . $sort_order . "'
							WHERE orders_total_id = '". $ot_total_id . "'";
//echo $Query."<br/>";
						tep_db_query($Query);
					} else { // New Insert (does this even work?)
						$Query = "INSERT INTO " . TABLE_ORDERS_TOTAL . " SET
							orders_id = '" . $oID . "',
							title = '" . $ot_title . "',
							text = '" . $ot_text . "',
							value = '" . $ot_value . "',
							class = '" . $ot_class . "',
							sort_order = '" . $sort_order . "'";
						tep_db_query($Query);
					}

					if ($ot_class == "ot_shipping" || $ot_class == "ot_custom" || $ot_class == "ot_loworderfee") {
			// Again, because products are calculated in terms of default currency, we need to align shipping, custom etc. values with default currency
						//$RunningTotal += $ot_value / $order->info['currency_value'];
						$RunningTotal += $ot_value;
					}
					
					elseif ($ot_class == "ot_tax") {
					
					if (DISPLAY_PRICE_WITH_TAX != 'true') { 
					//we don't add tax to the total here because it's already added to the subtotal
						$RunningTotal += $ot_value;
						}
						} else {
						$RunningTotal += $ot_value;
						}
					
			
				}
				
	elseif (($ot_total_id > 0) && ($ot_class != "ot_shipping")) { // value = 0 => Delete Total Piece
				
					$Query = "DELETE from " . TABLE_ORDERS_TOTAL . " 
					WHERE orders_id = '" . (int)$oID . "' 
					AND orders_total_id = '$ot_total_id'";
					tep_db_query($Query);
				}

			}
		
}//end if (is_array())
/*FINISHED*/
//exit;
		// 1.5 SUCCESS MESSAGE #####
		
		if ($order_updated)	{
			$messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
		}

		tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit'));

	break;
	// 1.5. ADD A PRODUCT BY MODEL ###############################################################################################
	case 'add_product_model':
		if ($_GET[submit]==1){
		$oID = tep_db_prepare_input($_GET['oID']);		
		$products_model = tep_db_prepare_input($_POST['new_model_number']);		
		$products_qty = tep_db_prepare_input($_POST['new_model_number_quantity']);
		$cnt = tep_db_fetch_array(tep_db_query("select * from products where products_model='".$products_model."' limit 1"));
		if (sizeof($cnt) == 0) { echo "<script>location.href='".$_SERVER['PHP_SELF']."?oID=".$oID."&action=add_product_model&error=1&num=".$products_model."&cnt=".$products_qty."'</script>"; exit;}

			// 2.1 GET ORDER INFO #####			
			$order = new order($oID);

			// 2.1.2 Get Product Info
			$InfoQuery = "select 
			              p.products_model,
						  p.products_price,
						  pd.products_name,
						  p.products_tax_class_id 
						  from " . TABLE_PRODUCTS . " 
						  p left 
						  join " . TABLE_PRODUCTS_DESCRIPTION . " 
						  pd on pd.products_id=p.products_id 
						  where p.products_id=" . $cnt['products_id'] . " 
						  and pd.language_id = '" . (int)$languages_id . "'";
			$result = tep_db_query($InfoQuery);

			$row = tep_db_fetch_array($result);
			extract($row, EXTR_PREFIX_ALL, "p");
			
			// 2.1.3  Pull specials price from db if there is an active offer
			$special_price = tep_db_query("select specials_new_products_price 
			from " . TABLE_SPECIALS . " 
			where products_id =". $cnt['products_id'] . " 
			and status");
			$new_price = tep_db_fetch_array($special_price);
			
			if ($new_price) 
			{
			$p_products_price = $new_price['specials_new_products_price'];
			}
			
			// Following two functions are defined at the top of this file
			$CountryID = tep_get_country_id($order->delivery["country"]);
			$ZoneID = tep_get_zone_id($CountryID, $order->delivery["state"]);
			$ProductsTax = tep_get_tax_rate($p_products_tax_class_id, $CountryID, $ZoneID);
			
			// 2.2 UPDATE ORDER ####
            $Query = "INSERT INTO " . TABLE_ORDERS_PRODUCTS . " set
              orders_id = '" . $oID . "',
              products_id = '" . $cnt['products_id'] . "',
              products_model = '" . $p_products_model . "',
              products_name = '" . tep_html_quotes($p_products_name) . "',
              products_price = '". $p_products_price . "',
              final_price = '" . ($p_products_price + $AddedOptionsPrice) . "',
              products_tax = '" . $ProductsTax . "',
              products_quantity = '" . $products_qty . "'";
              tep_db_query($Query);
              $new_product_id = tep_db_insert_id();
              $orders_products_id = $new_product_id;


			// 2.2.1 Update inventory Quantity
			//This is only done if store is set up to use stock
			if (STOCK_CHECK == 'true'){
			tep_db_query("UPDATE " . TABLE_PRODUCTS . " set
			products_quantity = products_quantity - " . $products_qty . " 
			where products_id = '" . $cnt['products_id'] . "'");
			}
			
			//2.2.1.1 Update products_ordered info
			tep_db_query ("UPDATE " . TABLE_PRODUCTS . " set
			products_ordered = products_ordered + " . $products_qty . "
			where products_id = '" . $cnt['products_id'] . "'");
           			
			// 2.2.2 Calculate Tax and Sub-Totals
			$order = new order($oID);
			$RunningSubTotal = 0;
			$RunningTax = 0;

       		//just adding in shipping tax, don't mind me
			$ot_shipping_query = tep_db_query("select value as value from " . TABLE_ORDERS_TOTAL . " where class= 'ot_shipping' and orders_id = '" . (int)$oID . "'");
			$ot_shipping_value = tep_db_fetch_array($ot_shipping_query);
			
			
			if (DISPLAY_PRICE_WITH_TAX == 'true') {
					$RunningTax += ($ot_shipping_value['value'] / (($order->info['shipping_tax'] / 100) + 1)) * ($order->info['shipping_tax'] / 100);
					} else {
					$RunningTax += (($order->info['shipping_tax'] / 100) * $ot_shipping_value['value']);
					}
			 // end shipping tax calcs
			 
		for ($i=0; $i<sizeof($order->products); $i++) {

  // This calculatiion of Subtotal and Tax is part of the 'add a product' process
		if (DISPLAY_PRICE_WITH_TAX == 'true') {
		$RunningSubTotal += (($order->products[$i]['tax'] / 100 + 1) * ($order->products[$i]['qty'] * $order->products[$i]['final_price']));
		} else {
		$RunningSubTotal += ($order->products[$i]['qty'] * $order->products[$i]['final_price']);
		}
		
		$RunningTax += (($order->products[$i]['tax'] / 100) * ($order->products[$i]['qty'] * $order->products[$i]['final_price']));			
			}// end of for ($i=0; $i<sizeof($order->products); $i++) {
			
  require(DIR_WS_INCLUDES . 'vendor_checkout_process_add.php');

if ($_POST[flagged]!=1)  require(DIR_WS_INCLUDES . 'affiliate_checkout_process.php');			
			
		// 2.2.2.1 Tax
			$Query = 'UPDATE ' . TABLE_ORDERS_TOTAL . ' set
				text = "' . $currencies->format($RunningTax, true, $order->info['currency'], $order->info['currency_value']) . '",
				value = "' . $RunningTax . '"
				WHERE class= "ot_tax" AND orders_id= "' . $oID . '"';
			tep_db_query($Query);
			
			// 2.2.2.2 Sub-Total
			$Query = 'UPDATE ' . TABLE_ORDERS_TOTAL . ' set
				text = "' . $currencies->format($RunningSubTotal, true, $order->info['currency'], $order->info['currency_value']) . '",
				value = "' . $RunningSubTotal . '"
				WHERE class="ot_subtotal" AND orders_id= "' . $oID . '"';
			tep_db_query($Query);
			
			// 2.2.2.3 Total
			if (DISPLAY_PRICE_WITH_TAX == 'true') {
			$Query = 'SELECT sum(value) AS total_value from ' . TABLE_ORDERS_TOTAL . '
			WHERE class != "ot_total" AND class != "ot_tax" AND orders_id= "' . $oID . '"';
			$result = tep_db_query($Query);
			$row = tep_db_fetch_array($result);
			$Total = $row['total_value'];
			} else {
			$Query = 'SELECT sum(value) AS total_value from ' . TABLE_ORDERS_TOTAL . '
			WHERE class != "ot_total" AND orders_id= "' . $oID . '"';
			$result = tep_db_query($Query);
			$row = tep_db_fetch_array($result);
			$Total = $row['total_value'];
			}

			$Query = 'UPDATE ' . TABLE_ORDERS_TOTAL . ' set
				text = "' . $currencies->format($Total, true, $order->info['currency'], $order->info['currency_value']) . '",
				value = "' . $Total . '"
				WHERE class="ot_total" and orders_id= "' . $oID . '"';
			tep_db_query($Query);
			// 2.3 REDIRECTION #####
			echo '<script>window.location.href="'.tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit').'";</script>';
			//tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit'));
		}
	break;

	// 2. ADD A PRODUCT ###############################################################################################
	case 'add_product':
	
		if($_POST['step'] == 5)
		{
			// 2.1 GET ORDER INFO #####
			
			$oID = tep_db_prepare_input($_GET['oID']);
			$order = new order($oID);

			$AddedOptionsPrice = 0;

			// 2.1.1 Get Product Attribute Info
			if(is_array ($_POST['add_product_options']))
			{
				foreach($_POST['add_product_options'] as $option_id => $option_value_id)
				{
					$result = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " 
					pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po 
					ON po.products_options_id=pa.options_id 
					LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov 
					ON pov.products_options_values_id=pa.options_values_id 
					WHERE products_id=" . $_POST['add_product_products_id'] . " 
					and options_id=" . $option_id . " 
					and options_values_id=" . $option_value_id . " 
					and po.language_id = '" . (int)$languages_id . "' 
					and pov.language_id = '" . (int)$languages_id . "'");
					
					$row = tep_db_fetch_array($result);
					extract($row, EXTR_PREFIX_ALL, "opt");
					$AddedOptionsPrice += $opt_options_values_price;
					$option_value_details[$option_id][$option_value_id] = array ("options_values_price" => $opt_options_values_price);
					$option_names[$option_id] = $opt_products_options_name;
					$option_values_names[$option_value_id] = $opt_products_options_values_name;
				}
			}

			// 2.1.2 Get Product Info
			$InfoQuery = "select 
			              p.products_model,
						  p.products_price,
						  pd.products_name,
						  p.products_tax_class_id 
						  from " . TABLE_PRODUCTS . " 
						  p left 
						  join " . TABLE_PRODUCTS_DESCRIPTION . " 
						  pd on pd.products_id=p.products_id 
						  where p.products_id=" . $_POST['add_product_products_id'] . " 
						  and pd.language_id = '" . (int)$languages_id . "'";
			$result = tep_db_query($InfoQuery);

			$row = tep_db_fetch_array($result);
			extract($row, EXTR_PREFIX_ALL, "p");
			
			// 2.1.3  Pull specials price from db if there is an active offer
			$special_price = tep_db_query("select specials_new_products_price 
			from " . TABLE_SPECIALS . " 
			where products_id =". $_POST['add_product_products_id'] . " 
			and status");
			$new_price = tep_db_fetch_array($special_price);
			
			if ($new_price) 
			{
			$p_products_price = $new_price['specials_new_products_price'];
			}
			
			// Following two functions are defined at the top of this file
			$CountryID = tep_get_country_id($order->delivery["country"]);
			$ZoneID = tep_get_zone_id($CountryID, $order->delivery["state"]);
			$ProductsTax = tep_get_tax_rate($p_products_tax_class_id, $CountryID, $ZoneID);
			
			// 2.2 UPDATE ORDER ####
            $Query = "INSERT INTO " . TABLE_ORDERS_PRODUCTS . " set
              orders_id = '" . $oID . "',
              products_id = '" . $_POST['add_product_products_id'] . "',
              products_model = '" . $p_products_model . "',
              products_name = '" . tep_html_quotes($p_products_name) . "',
              products_price = '". $p_products_price . "',
              final_price = '" . ($p_products_price + $AddedOptionsPrice) . "',
              products_tax = '" . $ProductsTax . "',
              products_quantity = '" . $_POST['add_product_quantity'] . "'";
              tep_db_query($Query);
              $new_product_id = tep_db_insert_id();
              $orders_products_id = $new_product_id;


			// 2.2.1 Update inventory Quantity
			//This is only done if store is set up to use stock
			if (STOCK_CHECK == 'true'){
			tep_db_query("UPDATE " . TABLE_PRODUCTS . " set
			products_quantity = products_quantity - " . $_POST['add_product_quantity'] . " 
			where products_id = '" . $_POST['add_product_products_id'] . "'");
			}
			
			//2.2.1.1 Update products_ordered info
			tep_db_query ("UPDATE " . TABLE_PRODUCTS . " set
			products_ordered = products_ordered + " . $_POST['add_product_quantity'] . "
			where products_id = '" . $_POST['add_product_products_id'] . "'");
           			
			//2.2.1.2 keep a record of the products attributes
			if (is_array ($_POST['add_product_options'])) {
				foreach($_POST['add_product_options'] as $option_id => $option_value_id) {
				$Query = "INSERT INTO " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
						orders_id = '" . $oID . "',
						orders_products_id = '" . $new_product_id . "',
						products_options = '" . $option_names[$option_id] . "',
						products_options_values = '" . tep_db_input($option_values_names[$option_value_id]) . "',
						options_values_price = '" . $option_value_details[$option_id][$option_value_id]['options_values_price'] . "',
						price_prefix = '+'";
					tep_db_query($Query);
				}
			}
			
			// 2.2.2 Calculate Tax and Sub-Totals
			$order = new order($oID);
			$RunningSubTotal = 0;
			$RunningTax = 0;

       		//just adding in shipping tax, don't mind me
			$ot_shipping_query = tep_db_query("select value as value from " . TABLE_ORDERS_TOTAL . " where class= 'ot_shipping' and orders_id = '" . (int)$oID . "'");
			$ot_shipping_value = tep_db_fetch_array($ot_shipping_query);
			
			
			if (DISPLAY_PRICE_WITH_TAX == 'true') {
					$RunningTax += ($ot_shipping_value['value'] / (($order->info['shipping_tax'] / 100) + 1)) * ($order->info['shipping_tax'] / 100);
					} else {
					$RunningTax += (($order->info['shipping_tax'] / 100) * $ot_shipping_value['value']);
					}
			 // end shipping tax calcs
			 
		for ($i=0; $i<sizeof($order->products); $i++) {

  // This calculatiion of Subtotal and Tax is part of the 'add a product' process
		if (DISPLAY_PRICE_WITH_TAX == 'true') {
		$RunningSubTotal += (($order->products[$i]['tax'] / 100 + 1) * ($order->products[$i]['qty'] * $order->products[$i]['final_price']));
		} else {
		$RunningSubTotal += ($order->products[$i]['qty'] * $order->products[$i]['final_price']);
		}
		
		$RunningTax += (($order->products[$i]['tax'] / 100) * ($order->products[$i]['qty'] * $order->products[$i]['final_price']));			
			}// end of for ($i=0; $i<sizeof($order->products); $i++) {
			
  require(DIR_WS_INCLUDES . 'vendor_checkout_process_add.php');
  require(DIR_WS_INCLUDES . 'affiliate_checkout_process.php');			
			
		// 2.2.2.1 Tax
			$Query = 'UPDATE ' . TABLE_ORDERS_TOTAL . ' set
				text = "' . $currencies->format($RunningTax, true, $order->info['currency'], $order->info['currency_value']) . '",
				value = "' . $RunningTax . '"
				WHERE class= "ot_tax" AND orders_id= "' . $oID . '"';
			tep_db_query($Query);
			
			// 2.2.2.2 Sub-Total
			$Query = 'UPDATE ' . TABLE_ORDERS_TOTAL . ' set
				text = "' . $currencies->format($RunningSubTotal, true, $order->info['currency'], $order->info['currency_value']) . '",
				value = "' . $RunningSubTotal . '"
				WHERE class="ot_subtotal" AND orders_id= "' . $oID . '"';
			tep_db_query($Query);
			
			// 2.2.2.3 Total
			if (DISPLAY_PRICE_WITH_TAX == 'true') {
			$Query = 'SELECT sum(value) AS total_value from ' . TABLE_ORDERS_TOTAL . '
			WHERE class != "ot_total" AND class != "ot_tax" AND orders_id= "' . $oID . '"';
			$result = tep_db_query($Query);
			$row = tep_db_fetch_array($result);
			$Total = $row['total_value'];
			} else {
			$Query = 'SELECT sum(value) AS total_value from ' . TABLE_ORDERS_TOTAL . '
			WHERE class != "ot_total" AND orders_id= "' . $oID . '"';
			$result = tep_db_query($Query);
			$row = tep_db_fetch_array($result);
			$Total = $row['total_value'];
			}

			$Query = 'UPDATE ' . TABLE_ORDERS_TOTAL . ' set
				text = "' . $currencies->format($Total, true, $order->info['currency'], $order->info['currency_value']) . '",
				value = "' . $Total . '"
				WHERE class="ot_total" and orders_id= "' . $oID . '"';
			tep_db_query($Query);

			// 2.3 REDIRECTION #####
			tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit'));

		}
	
	  break;
		
  }
}

  if (($action == 'edit') && isset($_GET['oID'])) {
    $oID = tep_db_prepare_input($_GET['oID']);

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
//START by quick_fixer
//need to declare and define var DISPLAY_PAYMENT_METHOD_DROPDOWN value
//document.write('

<?php echo "DISPLAY_PAYMENT_METHOD_DROPDOWN = '".DISPLAY_PAYMENT_METHOD_DROPDOWN."';"; ?>

//');  
//END by quick_fixer
//--></script>

<script language="javascript"><!--
<? if (($_GET[action]=='') || ($_GET[action]=='edit')) {?>
addLoadListener(init);
<?}?>

function checkDigit() //ver 1.02
{
	if(event.shiftKey)
	{
	return false;
	}
	if ((event.keyCode == 8) || (event.keyCode == 9) || (event.keyCode == 13))
	{
	return true;
	}
	else
	{
// alert(event.keyCode);
	if((event.keyCode > 45 && event.keyCode < 58) || (event.keyCode > 95 && event.keyCode < 106) || (event.keyCode > 36 && event.keyCode < 41))
	{
	return true;
	}
	else
	{
	return false;
	}
	}
}	
	function openWin(){
		window.open("product_model_search.php", "SEARCHING","location=0,status=0,scrollbars=0, width=500,height=500");
}

	function checkForm(){
	err = '';
	model_number = document.getElementById('new_model_number').value;
	model_qty = document.getElementById('new_model_number_quantity').value;

	if (model_number == '')	 err = err+'Model Number Field is Empty!\n';
	if (model_qty == '')	 err = err+'Qty Field is Empty or equal to zerro!\n';

	if(err!='') {alert(err); return false;} else return true;
	}

function init()
{
  var optional = document.getElementById("optional");
  optional.className = "hidden";
  //START dropdown option for payment method by quick_fixer
  //new browsers support W3C - DOM Level 2
	if (document.getElementById) {
		//for payment dropdown menu use this
  		if (DISPLAY_PAYMENT_METHOD_DROPDOWN == 'true') { 
			var selObj = document.getElementById('update_info_payment_method');
			var selIndex = selObj.selectedIndex;
		
			//optional FOR TESTING WITH DROPDOWN input fields named txtIndex, txtValue and, txtText which outputs the index value***
			//0,1,2, based on position; the optional value (which may be different than the text value displayed); and the text value displayed in***
			//the dropdown menu
			//var txtIndexObj = document.getElementById('txtIndex');
			//var txtValueObj = document.getElementById('txtValue');
			//var txtTextObj = document.getElementById('txtText');
			//optional input fields***
			//OUTPUT optional input fields***
			//txtIndexObj.value = selIndex;
			//txtValueObj.value = selObj.options[selIndex].value;
			//txtTextObj.value = selObj.options[selIndex].text;
			//OUTPUT optional input fields***
			//text in lieu of value supported by firefox and mozilla but not others SO MAKE SURE text and optional value are the same (in the payment dropdown they are)
			if (selObj.options[selIndex].text) {
				var paymentMethod = selObj.options[selIndex].text;
			}
			else {
				var paymentMethod = selObj.options[selIndex].value;
			}
		}
		else {
			//if you only use an input field to display payment method use this
			var selObj = document.getElementById('update_info_payment_method');
			var paymentMethod = selObj.value;
		}
			              
	}
	//old browsers that don't support W3C - DOM Level 2
	else {
		//for payment dropdown menu use this
  		if (DISPLAY_PAYMENT_METHOD_DROPDOWN == 'true') { 
			var selObj = document.edit_order.update_info_payment_method;
			var selIndex = selObj.selectedIndex;
		
			//optional FOR TESTING WITH DROPDOWN input fields named txtIndex, txtValue and, txtText which outputs the index value***
			//0,1,2, based on position; the optional value (which may be different than the text value displayed); and the text value displayed in***
			//the dropdown menu
			//var txtIndexObj = document.forms.edit_order["txtIndex"].value;
			//var txtValueObj = document.forms.edit_order["txtValue"].value;
			//var txtTextObj = document.forms.edit_order["txtText"].value;
			//optional input fields***
			//OUTPUT optional input fields***
			//txtIndexObj.value = selIndex;
			//txtValueObj.value = selObj.options[selIndex].value;
			//txtTextObj.value = selObj.options[selIndex].text;
			//OUTPUT optional input fields***
			//text in lieu of value supported by firefox and mozilla but not others SO MAKE SURE text and optional value are the same (in the payment dropdown they are)
			if (selObj.options[selIndex].text) {
				var paymentMethod = selObj.options[selIndex].text;
			}
			else {
				var paymentMethod = selObj.options[selIndex].value;
			}
		}
		else {
			//if you only use an input field to display payment method use this
			var paymentMethod = document.forms.edit_order["update_info_payment_method"].value;
		}
	}
//END dropdown option for payment method by quick_fixer
  if (paymentMethod == "<?php echo ENTRY_CREDIT_CARD ?>") {
  optional.className = "";
  return true;
  } else {
  optional.className = "hidden";
  return true;
  }
  
 }

  function addLoadListener(fn)
{
  if (typeof window.addEventListener != 'undefined')
  {
    window.addEventListener('load', fn, false);
  }
  else if (typeof document.addEventListener != 'undefined')
  {
    document.addEventListener('load', fn, false);
  }
  else if (typeof window.attachEvent != 'undefined')
  {
    window.attachEvent('onload', fn);
  }
  else
  {
    var oldfn = window.onload;
    if (typeof window.onload != 'function')
    {
      window.onload = fn;
    }
    else
    {
      window.onload = function()
      {
        oldfn();
        fn();
      };
    }
  }
}
  
  function doRound(x, places) {
    return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
 }
  
  function updatePrices(action, pid) {
/*
    var qty = document.getElementById(pid + "-qty").value;
	var taxRate = document.getElementById(pid + "-tax").value;
	
	if ((action == 'qty') || (action == 'tax') || (action == 'final_price')) {
	
	var priceInclValue = document.getElementById(pid + "-final_price").value;
	var totalInclValue = document.getElementById(pid + "-final_price").value;
	var totalExclValue = document.getElementById(pid + "-final_price").value;
	
	priceInclValue = priceInclValue * ((taxRate / 100) + 1);
	totalInclValue = totalInclValue * ((taxRate / 100) + 1) * qty;
	totalExclValue = totalExclValue * qty;
		
	} //end if ((action == 'qty') || (action == 'tax') || (action == 'final_price')) 
	
	if (action == 'price_incl') {
	
	var finalPriceValue = document.getElementById(pid + "-price_incl").value;
	var totalInclValue = document.getElementById(pid + "-price_incl").value;
	var totalExclValue = document.getElementById(pid + "-price_incl").value;
	
	finalPriceValue = finalPriceValue / ((taxRate / 100) + 1);
	totalInclValue = totalInclValue * qty;
	totalExclValue = totalExclValue * qty / ((taxRate / 100) + 1);
	
	} //end of if (action == 'price_incl')
	
	if (action == 'total_excl') {
	
	var finalPriceValue = document.getElementById(pid + "-total_excl").value;
	var priceInclValue = document.getElementById(pid + "-total_excl").value;
	var totalInclValue = document.getElementById(pid + "-total_excl").value;
		
	finalPriceValue = finalPriceValue / qty;
	priceInclValue = priceInclValue * ((taxRate / 100) + 1) / qty;
	totalInclValue = totalInclValue * ((taxRate / 100) + 1);
	
	} //end of if (action == 'total_excl')
	
	if (action == 'total_incl') {
	
	var finalPriceValue = document.getElementById(pid + "-total_incl").value;
	var priceInclValue = document.getElementById(pid + "-total_incl").value;
	var totalExclValue = document.getElementById(pid + "-total_incl").value;
	
	finalPriceValue = finalPriceValue / ((taxRate / 100) + 1) / qty;
	priceInclValue = priceInclValue / qty;
	totalExclValue = totalExclValue / ((taxRate / 100) + 1);
	
	} //end of if (action == 'total_incl')
	
	if ((action != 'qty') && (action != 'tax') && (action != 'final_price')) {
	document.getElementById(pid + "-final_price").value = doRound(finalPriceValue, 4);
	}
	
	if ((action != 'qty') && (action != 'price_incl')) {
	document.getElementById(pid + "-price_incl").value = doRound(priceInclValue, 4);
	}
	
	if ((action != 'tax') && (action != 'total_excl')) {
	document.getElementById(pid + "-total_excl").value = doRound(totalExclValue, 4);
	}
	
	if (action != 'total_incl') {
	document.getElementById(pid + "-total_incl").value = doRound(totalInclValue, 4);
	}
*/	
	} //end function updatePrices(action, pid)

 //--></script>
</head>
<body>
<style type="text/css">
  
  .Subtitle {
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 11px;
  font-weight: bold;
  color: #FF6600;
  }
  
  .hidden
  {
  position: absolute;
  left: -1500em;
  }
  
</style>
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
  ?>
<!-- header_eof //-->

<!-- body //-->

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
		
<?php
if (($action == 'edit') && ($order_exists == true)) {
  $order = new order($oID);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE . '&nbsp;(' . HEADING_TITLE_NUMBER . '&nbsp;' . $oID . '&nbsp;' . HEADING_TITLE_DATE  . '&nbsp;' . tep_datetime_short($order->info['date_purchased']) . ')'; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
             <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $_GET['oID'] . '&action=edit') . '">' . tep_image_button('button_details.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <input type="button" value="PDF invoice" onclick="javascript:generatePDF('.$HTTP_GET_VARS['oID'].')" /> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> '; ?></td>
          </tr>
		<tr>
	          <td class="main" colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
          </tr>
          <tr>
            <td class="main" colspan="3"><?php echo HEADING_SUBTITLE; ?></td>
          </tr>
          <tr>
	          <td class="main" colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>

<!-- Begin Addresses Block -->

      <tr><?php echo tep_draw_form('edit_order', FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=update_order'); ?>
	  </tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>   

	<!-- Begin Update Block -->
<!-- Improvement: more "Update" buttons (Michel Haase, 2005-02-18) - here after FORM und before MENUE_TITLE_CUSTOMER -->
	
      <tr>
	      <td>
          <table width="100%" border="0" cellpadding="2" cellspacing="1">
            <tr>
              <td class="main" bgcolor="#FAEDDE"><?php echo HINT_PRESS_UPDATE; ?></td>
              <td class="main" bgcolor="#FBE2C8" width="10">&nbsp;</td>
              <td class="main" bgcolor="#FFCC99" width="10">&nbsp;</td>
              <td class="main" bgcolor="#F8B061" width="10">&nbsp;</td>
              <td class="main" bgcolor="#FF9933" width="120" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
	          </tr>
          </table>
				</td>
      </tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>   
	<!-- End of Update Block -->
      <tr>
	    <td class="SubTitle">Order Number: </td>
      </tr>
<script>
function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}

function assignOrderNumber(){
	newId = document.getElementById('newId').value;
	if (newId != '<?=$oID?>'){
	if (trim(newId) != '')
		location.href='edit_orders.php?oID=<?=$oID?>&newId='+document.getElementById('newId').value+'&action=assignNew';
	else {
		alert('Assign correct order number!');
	}
	}
	else {
		alert('Please, change order number first!');
	}
}
</script>
      <tr>
	    <td class="dataTableHeadingContent"><input type="text" id="newId" name="orderNumber" value="<?=$oID?>" />&nbsp;<input type="button" value="Replace" onclick="assignOrderNumber()" /></td>
      </tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>   
      <tr>
	    <td class="SubTitle"><?php echo MENUE_TITLE_CUSTOMER; ?></td>
	  </tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>   

			<tr>
			  <td>
      
<table border="0" class="dataTableRow" cellpadding="2" cellspacing="0">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" width="80"></td>
    <td class="dataTableHeadingContent" width="150"><?php echo ENTRY_CUSTOMER_ADDRESS; ?></td>
    <td class="dataTableHeadingContent" width="6">&nbsp;</td>
    <td class="dataTableHeadingContent" width="150"><?php echo ENTRY_SHIPPING_ADDRESS; ?></td>
	 <td class="dataTableHeadingContent" width="6">&nbsp;</td>
    <td class="dataTableHeadingContent" width="150"><?php echo ENTRY_BILLING_ADDRESS; ?></td>
  </tr>
 <?php
  if (ACCOUNT_COMPANY == 'true') {
?>
 <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_COMPANY; ?>: </b></td>
    <td><span class="main"><input name="update_customer_company" size="30" value="<?php echo tep_html_quotes($order->customer['company']); ?>" /></span></td>
		<td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_company" size="30" value="<?php echo tep_html_quotes($order->delivery['company']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input name="update_billing_company" size="30" value="<?php echo tep_html_quotes($order->billing['company']); ?>" /></span></td>
  </tr>
  <?php
  }
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_NAME; ?>: </b></td>
    <td><span class="main"><input name="update_customer_name" size="30" value="<?php echo tep_html_quotes($order->customer['name']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_name" size="30" value="<?php echo tep_html_quotes($order->delivery['name']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input name="update_billing_name" size="30" value="<?php echo tep_html_quotes($order->billing['name']); ?>" /></span></td>
  </tr>
  <tr>
    <td class="main"><b><?php echo ENTRY_ADDRESS; ?>: </b></td>
    <td><span class="main"><input name="update_customer_street_address" size="30" value="<?php echo tep_html_quotes($order->customer['street_address']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_street_address" size="30" value="<?php echo tep_html_quotes($order->delivery['street_address']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input name="update_billing_street_address" size="30" value="<?php echo tep_html_quotes($order->billing['street_address']); ?>" /></span></td>
  </tr>
  <?php
  if (ACCOUNT_SUBURB == 'true') {
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_SUBURB; ?>: </b></td>
    <td><span class="main"><input name="update_customer_suburb" size="30" value="<?php echo tep_html_quotes($order->customer['suburb']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_suburb" size="30" value="<?php echo tep_html_quotes($order->delivery['suburb']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input name="update_billing_suburb" size="30" value="<?php echo tep_html_quotes($order->billing['suburb']); ?>" /></span></td>
  </tr>
  <?php
  }
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_CITY; ?>: </b></td>
    <td><span class="main"><input name="update_customer_city" size="30" value="<?php echo tep_html_quotes($order->customer['city']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_city" size="30" value="<?php echo tep_html_quotes($order->delivery['city']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input name="update_billing_city" size="30" value="<?php echo tep_html_quotes($order->billing['city']); ?>" /></span></td>
  </tr>
  <?php
  if (ACCOUNT_STATE == 'true') {
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_STATE; ?>: </b></td>
    <td><span class="main"><input name="update_customer_state" size="30" value="<?php echo tep_html_quotes($order->customer['state']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_state" size="30" value="<?php echo tep_html_quotes($order->delivery['state']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input name="update_billing_state" size="30" value="<?php echo tep_html_quotes($order->billing['state']); ?>" /></span></td>
  </tr>
  <?php
  }
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_POSTCODE; ?>: </b></td>
    <td><span class="main"><input name="update_customer_postcode" size="30" value="<?php echo $order->customer['postcode']; ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_postcode" size="30" value="<?php echo $order->delivery['postcode']; ?>" /></span></td>
	 <td>&nbsp;</td>
    <td><span class="main"><input name="update_billing_postcode" size="30" value="<?php echo $order->billing['postcode']; ?>" /></span></td>
  </tr>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_COUNTRY; ?>: </b></td>
    <td><span class="main"><input name="update_customer_country" size="30" value="<?php echo tep_html_quotes($order->customer['country']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_country" size="30" value="<?php echo tep_html_quotes($order->delivery['country']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input name="update_billing_country" size="30" value="<?php echo tep_html_quotes($order->billing['country']); ?>" /></span></td>
  </tr>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_PHONE; ?>: </b></td>
    <td><span class="main"><input name="update_customer_telephone" size="30" value="<?php echo $order->customer['telephone']; ?>" /></span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	 <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_EMAIL; ?>: </b></td>
    <td><span class="main"><input name="update_customer_email_address" size="30" value="<?php echo $order->customer['email_address']; ?>" /></span></td>
  <td>&nbsp;</td>
    <td>&nbsp;</td>
	 <td>&nbsp;</td>
    <td>&nbsp;</td>
	</tr>
</table>

				</td>
			</tr>
<tr><td class="main"><b>Order Date:</b> <input type="text" name="update_date_purchased" value="<?=$order->info['date_purchased']?>" /></td></tr>
<tr><td class="main"><b>Order Type:</b> <?=($order->info['iswholesale'])?'WHOLESALE':'REGULAR'?></td>
<input name="flagged" type="hidden" value="<?php echo $order->info['iswholesale']; ?>" />
</tr>
<!-- End Addresses Block -->

      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>      

<!-- Begin Payment Block -->
      <tr>
	      <td class="SubTitle"><?php echo MENUE_TITLE_PAYMENT; ?></td>
			</tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>   
      <tr>
	      <td>
				
<table border="0" cellspacing="0" cellpadding="2" class="dataTableRow">
  <tr class="dataTableHeadingRow">
    <td colspan="2" class="dataTableHeadingContent"><?php echo ENTRY_PAYMENT_METHOD; ?></td>
	</tr>
  <tr>
	  <td colspan="2" class="main">
	  <?php 
	  //START for payment dropdown menu use this by quick_fixer
  		if (DISPLAY_PAYMENT_METHOD_DROPDOWN == 'true') { 
		
		
		  // Get list of all payment modules available
  $enabled_payment = array();
  $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));

  if ($dir = @dir($module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir( $module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file;
        }
      }
    }
    sort($directory_array);
    $dir->close();
  }


  // For each available payment module, check if enabled
  for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
    $file = $directory_array[$i];

    include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/payment/' . $file);
    include($module_directory . $file);

    $class = substr($file, 0, strrpos($file, '.'));
    if (tep_class_exists($class)) {
      $module = new $class;
      if ($module->check() > 0) {
        // If module enabled create array of titles
      	$enabled_payment[] = array('id' => $module->title, 'text' => $module->title);
		
		//if the payment method is the same as the payment module title then don't add it to dropdown menu
		if ($module->title == $order->info['payment_method']) {
			$paymentMatchExists='true';	
		}
      }
   }
 }
 		//just in case the payment method found in db is not the same as the payment module title then make it part of the dropdown array or else it cannot be the selected default value
		if ($paymentMatchExists !='true') {
			$enabled_payment[] = array('id' => $order->info['payment_method'], 'text' => $order->info['payment_method']);	
 }
 $enabled_payment[] = array('id' => 'Other', 'text' => 'Other');	
		//draw the dropdown menu for payment methods and default to the order value
	  		echo tep_draw_pull_down_menu('update_info_payment_method', $enabled_payment, $order->info['payment_method'], 'id="update_info_payment_method" onKeyUp="init()"'); 
		}
	  	else {
		//draw the input field for payment methods and default to the order value
	  ?><input name="update_info_payment_method" size="35" value="<?php echo $order->info['payment_method']; ?>" id="update_info_payment_method" onKeyUp="init()"/><?php
	  }
	  //END for payment dropdown menu use this by quick_fixer
	?></td>
	</tr>
	<!-- Begin Credit Card Info Block -->
	  <tr><td>
	  
	  <table id="optional">
	 <tr>
	    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
	    <td class="main"><input name="update_info_cc_type" size="10" value="<?php echo $order->info['cc_type']; ?>" /></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
	    <td class="main"><input name="update_info_cc_owner" size="20" value="<?php echo $order->info['cc_owner']; ?>" /></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
	    <td class="main"><input name="update_info_cc_number" size="20" value="<?php echo $order->info['cc_number']; ?>" /></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
	    <td class="main"><input name="update_info_cc_expires" size="4" value="<?php echo $order->info['cc_expires']; ?>" maxlength="4" /></td>
	  </tr>
	  </table>
	  
	  </td></tr>
	
  <!-- End Credit Card Info Block -->
	<tr><td class="main">Purchase Order Number: <input type="text" name="purchase_order_number" value="<?=$order->info['purchase_order_number']?>" /></td>	
</table>
 
   </td>
      </tr>
	   <tr><td>
	 <div class="smallText"><?php echo HINT_UPDATE_TO_CC ?></div>
	 </tr><td>
<!-- End Payment Block -->
	
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Products Listing Block -->
      <tr>
	      <td class="SubTitle"><?php echo MENUE_TITLE_ORDER; ?></td>
			</tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>   
      <tr>
	      <td>
				  
	<?php
    // Override order.php Class's Field Limitations
		$index = 0;
		$order->products = array();
		$orders_products_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "' order by products_model asc");
		while ($orders_products = tep_db_fetch_array($orders_products_query)) {
		$order->products[$index] = array(
		                           'qty' => $orders_products['products_quantity'],
		                   'shipped' => $orders_products['date_shipped'],
		                   'returned' => $orders_products['returned_reason'],
		                   'date_shipped_checkbox' => $orders_products['date_shipped_checkbox'],
		                   'returned_reason_checkbox' => $orders_products['returned_reason_checkbox'],
                                   'name' => tep_html_quotes($orders_products['products_name']),
                                   'model' => $orders_products['products_model'],
                                   'tax' => $orders_products['products_tax'],
                                   'price' => $orders_products['products_price'],
                                   'final_price' => $orders_products['final_price'],
                                   'back' => $orders_products['back'],
				   'is_allied' => $orders_products['is_allied'],
                                   'orders_products_id' => $orders_products['orders_products_id']);

		$subindex = 0;
		$attributes_query_string = "select * from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'";
		$attributes_query = tep_db_query($attributes_query_string);

		if (tep_db_num_rows($attributes_query)) {
		while ($attributes = tep_db_fetch_array($attributes_query)) {
		  $order->products[$index]['attributes'][$subindex] = array(
		  'option' => $attributes['products_options'],
		  'value' => $attributes['products_options_values'],
		  'prefix' => $attributes['price_prefix'],
		  'price' => $attributes['options_values_price'],
		  'orders_products_attributes_id' => $attributes['orders_products_attributes_id']);
		  
		  $subindex++;
		  }
		}
		
		$index++;
	}
	
?>


<script>
	function getDate(){
		var currentTime = new Date()
		var month = currentTime.getMonth() + 1
		var day = currentTime.getDate()
		var year = currentTime.getFullYear()
		return year + "-" + month + "-" + day;
}

	function changeDate(id){
	obj = document.getElementById('shipped_'+id);
	if (obj.checked == true){
		document.getElementById('shipped_date_'+id).disabled = false;
		document.getElementById('shipped_date_'+id).value = getDate();
	} else {
		document.getElementById('shipped_date_'+id).value = "0000-00-00";	
		document.getElementById('shipped_date_'+id).disabled = true;	
		}
	}

	function changeComments(id){
	obj = document.getElementById('returned_'+id);
	if (obj.checked == true)
		document.getElementById('returned_reason_'+id).disabled = false;
	else {
		document.getElementById('returned_reason_'+id).value = "";	
		document.getElementById('returned_reason_'+id).disabled = true;	
		}
	}

	function t_set(obj,index){
		for(i=0;i<obj.length;i++){
			if (obj[i].value==index){
				obj.selectedIndex=i;
				break;
			}
		}				
	}	


	function checkSelect(){
	err = '';
<?for ($i=0; $i<sizeof($order->products); $i++) {?>
	if (document.getElementById('shipped_<?=$order->products[$i]['orders_products_id']?>').checked == false) err = err+'error';
<?}?>
	return err;	
	}


	function selectAlls(obj){
	if (obj.checked == true) flag = true; else flag = false;
<?for ($i=0; $i<sizeof($order->products); $i++) {?>
	document.getElementById('shipped_<?=$order->products[$i]['orders_products_id']?>').checked = flag;   	
	changeDate('<?=$order->products[$i]['orders_products_id']?>');
<?}?>
	}

	function selectAllc(obj){
	counter = document.getElementById('gen_returned_reason').selectedIndex;
	word = document.getElementById('gen_returned_reason').options[counter].value;
	if (obj.checked == true) {
		flag = true; 
	} else {
		document.getElementById('gen_returned_reason').selectedIndex = 0;
		word = 'none';
		flag = false;
	}
<?for ($i=0; $i<sizeof($order->products); $i++) {?>
	document.getElementById('returned_<?=$order->products[$i]['orders_products_id']?>').checked = flag;   	
	changeComments('<?=$order->products[$i]['orders_products_id']?>');
	 t_set(document.getElementById('returned_reason_<?=$order->products[$i]['orders_products_id']?>'),word);
<?}?>
	}

	function backAll(obj){
	if (obj.checked == true) flag = true; else flag = false;
<?for ($i=0; $i<sizeof($order->products); $i++) {
	if ($order->products[$i]['back']!=1){
	?>
	document.getElementById('back_<?=$order->products[$i]['orders_products_id']?>').checked = flag;   	
	document.getElementById('final_price_<?=$order->products[$i]['orders_products_id']?>').value = '0.00';   	
	document.getElementById('tax_<?=$order->products[$i]['orders_products_id']?>').value = '0.00';   	
	document.getElementById('total_incl_<?=$order->products[$i]['orders_products_id']?>').value = '0.00';   	
	document.getElementById('returned_<?=$order->products[$i]['orders_products_id']?>').checked = flag;   	
	changeComments('<?=$order->products[$i]['orders_products_id']?>');
<?
		}
	}
?>
}

	function backSel(obj, val){
	if (obj.checked == true) flag = true; else flag = false;
	document.getElementById('back_'+val).checked = flag;   	
	document.getElementById('final_price_'+val).value = '0.00';   	
	document.getElementById('tax_'+val).value = '0.00';   	
	document.getElementById('total_incl_'+val).value = '0.00';   	
	document.getElementById('returned_'+val).checked = flag;   	
	changeComments(val);

}

</script>


<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr class="dataTableHeadingRow">
	  <td colspan="6"></td>
	  <td class="dataTableHeadingContent" align="left" style="padding-left:10px;">Shipped</td>
	  <td class="dataTableHeadingContent" align="center">Returned&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Returned Reason&nbsp;&nbsp;&nbsp;Back?</td>
	  <td class="dataTableHeadingContent" align="right"></td>
	</tr>
	<tr class="dataTableHeadingRow">
	  <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_DELETE; ?></td>
	  <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_QUANTITY; ?></td>
	  <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_PRODUCTS; ?></td>
	  <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
	  <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_TAX; ?></td>
	  <td class="dataTableHeadingContent" align="right"><?php  echo TABLE_HEADING_UNIT_PRICE; ?></td>
	  <td class="dataTableHeadingContent" style="padding-left:29px;"><input type="checkbox" name="getAlls" onclick="selectAlls(this)" value="1"/>&nbsp;Date Shipped&nbsp;&nbsp;&nbsp;</td>
	  <td align="center"><input type="checkbox" name="getAllc" onclick="selectAllc(this)" value="1"/>&nbsp;<select id='gen_returned_reason' name='gen_selection' style='width:150px;'><option value='none'></option><option value='Defective'>Defective</option><option value='Ordered Incorrectly'>Ordered Incorrectly</option><option value='Does not meet needs'>Does not meet needs</option><option value='Did not like'>Did not like</option><option value='Wrong Region Code'>Wrong Region Code</option><option value='DVD-R not compatible'>DVD-R not compatible</option><option value='Duplicate Order'>Duplicate Order</option><option value='Did not arrive in time'>Did not arrive in time</option></select><input type="checkbox" onclick="backAll(this);" /></td>
	  <td class="dataTableHeadingContent" align="right"><?php  echo TABLE_HEADING_TOTAL_PRICE_TAXED; ?></td>
</tr>

	<?php

	for ($i=0; $i<sizeof($order->products); $i++) {
	$orders_products_id = $order->products[$i]['orders_products_id'];
        echo '<input type="hidden" name="update_products['.$orders_products_id.'][qty]" value="'.$order->products[$i]['qty'].'"/>';
		$RowStyle = "dataTableContent";
		echo '	  <tr class="dataTableRow">' . "\n" .
		     '	    <td class="' . $RowStyle . '" valign="top"><div align="center">' . "<input name='update_products[$orders_products_id][delete]' type='checkbox' /></div></td>\n" . 
			 '	    <td class="' . $RowStyle . '" align="right" valign="top"><div align="center">' . "<input name='update_products[$orders_products_id][qty]' size='2' value='" . $order->products[$i]['qty'] . "' onKeyUp=\"updatePrices('qty', 'a" . $orders_products_id . "')\" id='a" . $orders_products_id . "-qty' /></div></td>\n" . 
 		     '	    <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][name]' size='35' value='".trim(strip_tags($order->products[$i]['name']))."'>";
		
		// Has Attributes? 
		if (sizeof($order->products[$i]['attributes']) > 0) {
			for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
				$orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
				echo '<br /><nobr><small>&nbsp;<i> - ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][option]' size='6' value='" . $order->products[$i]['attributes'][$j]['option'] . "'>" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][value]' size='10' value='" . $order->products[$i]['attributes'][$j]['value'] . "'>" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][prefix]' size='1' value='" . $order->products[$i]['attributes'][$j]['prefix'] . "'>" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][price]' size='6' value='" . $order->products[$i]['attributes'][$j]['price'] . "'>";
				echo '</i></small></nobr>';
			}
		}

$str = '';
if ($order->products[$i]['back']==1) $str = 'checked readonly';

		echo '	    </td>' . "\n" .
		     '	    <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][model]' size='12' value='" . $order->products[$i]['model'] . "'>" . '</td>' . "\n" .
		     '	    <td class="' . $RowStyle . '" valign="top">' . "<input id='tax_".$orders_products_id."' name='update_products[$orders_products_id][tax]' size='6' value='" . tep_display_tax_value($order->products[$i]['tax']) . "' onKeyUp=\"updatePrices('tax', 'a" . $orders_products_id . "')\" id='a" . $orders_products_id . "-tax' />" . '</td>' . "\n" .
		     '	    <td class="' . $RowStyle . '" align="right" valign="top">' . "<input id='final_price_".$orders_products_id."' name='update_products[$orders_products_id][final_price]' size='7' value='" . number_format($order->products[$i]['final_price'], 4, '.', '') . "' onKeyUp=\"updatePrices('final_price', 'a" . $orders_products_id . "')\" id='a" . $orders_products_id . "-final_price' />" . '</td>' . "\n" . 
		     '	    <td class="' . $RowStyle . '" align="right" valign="top">' . "<input onclick='changeDate(\"$orders_products_id\");' id='shipped_$orders_products_id' type='checkbox' name='update_products[$orders_products_id][date_shipped_checkbox]' value='1' />&nbsp;<input disabled type='text' id='shipped_date_$orders_products_id' name='update_products[$orders_products_id][shipped]' style='width:130px;' value='".$order->products[$i][shipped]."' /><input name='update_products[$orders_products_id][price_incl]' size='7' value='" . number_format(($order->products[$i]['final_price'] * (($order->products[$i]['tax']/100) + 1)), 4, '.', '') . "' onKeyUp=\"updatePrices('price_incl', 'a" . $orders_products_id . "')\" id='a" . $orders_products_id . "-price_incl' type='hidden' />" . '</td>' . "\n" . 
		     '	    <td class="' . $RowStyle . '" align="center" valign="top">' . "<input onclick='changeComments(\"$orders_products_id\");' id='returned_$orders_products_id' type='checkbox' name='update_products[$orders_products_id][returned_reason_checkbox]' value='1' />&nbsp;<select disabled id='returned_reason_$orders_products_id' name='update_products[$orders_products_id][reason]' style='width:150px;'><option value='none'></option><option value='Defective'>Defective</option><option value='Ordered Incorrectly'>Ordered Incorrectly</option><option value='Does not meet needs'>Does not meet needs</option><option value='Did not like'>Did not like</option><option value='Wrong Region Code'>Wrong Region Code</option><option value='DVD-R not compatible'>DVD-R not compatible</option><option value='Duplicate Order'>Duplicate Order</option><option value='Did not arrive in time'>Did not arrive in time</option></select><input id='back_".$orders_products_id."' name='update_products[$orders_products_id][back]' type='checkbox' value='".$orders_products_id."' ".$str." onclick='backSel(this, \"".$orders_products_id."\")' /><input name='update_products[$orders_products_id][total_excl]' size='7' value='" . number_format($order->products[$i]['final_price'] * $order->products[$i]['qty'], 4, '.', '') . "' onKeyUp=\"updatePrices('total_excl', 'a" . $orders_products_id . "')\" id='a" . $orders_products_id . "-total_excl' type='hidden' />" . '</td>' . "\n" .
		     '	    <td class="' . $RowStyle . '" align="right" valign="top">' . "<input id='total_incl_".$orders_products_id."' name='update_products[$orders_products_id][total_incl]' size='7' value='" . number_format((($order->products[$i]['final_price'] * (($order->products[$i]['tax']/100) + 1))) * $order->products[$i]['qty'], 4, '.', '') . "' onKeyUp=\"updatePrices('total_incl', 'a" . $orders_products_id . "')\" id='a" . $orders_products_id . "-total_incl' />" . '</td>' . "\n" .
			 '	  </tr>' . "\n" .
			 '     <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>' . "\n";

if ($order->products[$i][returned_reason_checkbox]=='1') {
?>
<script>
	 document.getElementById('returned_<?=$orders_products_id?>').checked = true;
	 document.getElementById('returned_reason_<?=$orders_products_id?>').disabled = false;	 
	 t_set(document.getElementById('returned_reason_<?=$orders_products_id?>'),'<?=$order->products[$i][returned]?>');
</script>
<?	
		}

if ($order->products[$i][date_shipped_checkbox]=='1') {?>
<script>
	 document.getElementById('shipped_<?=$orders_products_id?>').checked = true;
	 document.getElementById('shipped_date_<?=$orders_products_id?>').disabled = false;	 
</script>
<?	
		}
	}

?>
 </table> 
        </td>
      <tr>
<script>
	function showInterface(){
		location.href="<?=$_SERVER['PHP_SELF']?>?oID=<?=$oID?>&action=add_product_model";
	}
</script>
	      <td>
				  <table width="100%" cellpadding="0" cellspacing="0">
					  <tr>
						  <td valign="top"><?php echo "<span class='smalltext'>" . HINT_DELETE_POSITION . "</span>"; ?></td>
			        <td align="right"><?php echo '<a href="' . $_SERVER['PHP_SELF'] . '?oID=' . $oID . '&action=add_product&step=1">' . tep_image_button('button_add_article.gif', ADDING_TITLE) . '</a>'; ?>&nbsp;&nbsp;<?php echo '<input type="button" value="Add product by model number" title="Add product by model number" onclick="showInterface();" />'; ?></td>
						</tr>
					</table>
			  </td>
      </tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
			
	<!-- End Products Listings Block -->

	<!-- Begin Update Block -->
<!-- Improvement: more "Update" buttons (Michel Haase, 2005-02-18) -->
	
      <tr>
	      <td>
          <table width="100%" border="0" cellpadding="2" cellspacing="1">
            <tr>
              <td class="main" bgcolor="#FAEDDE"><?php echo HINT_PRESS_UPDATE; ?></td>
              <td class="main" bgcolor="#FBE2C8" width="10">&nbsp;</td>
              <td class="main" bgcolor="#FFCC99" width="10">&nbsp;</td>
              <td class="main" bgcolor="#F8B061" width="10">&nbsp;</td>
              <td class="main" bgcolor="#FF9933" width="120" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
	          </tr>
          </table>
				</td>
      </tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>   
	<!-- End of Update Block -->

	<!-- Begin Order Total Block -->
      <tr>
	      <td class="SubTitle"><?php echo MENUE_TITLE_TOTAL; ?></td>
			</tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>   
      <tr>
	      <td>

<table border="0" cellspacing="0" cellpadding="2" class="dataTableRow">
	<tr class="dataTableHeadingRow">
	  <td class="dataTableHeadingContent"></td>
	  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX; ?></td>
	  <td class="dataTableHeadingContent"width="1"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
	  <tr>
	  <td class="smallText" align="right"><b><?php echo TABLE_HEADING_SHIPPING_TAX; ?></b></td>
	  <td class="smallText" align="right"><input name="update_shipping_tax" size="6" value="<?php echo tep_display_tax_value($order->info['shipping_tax']); ?>" /></td>
	  <td></td>
	  </tr>
	</tr>
	<tr class="dataTableHeadingRow">
	  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TOTAL_MODULE; ?></td>
	  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TOTAL_AMOUNT; ?></td>
	  <td class="dataTableHeadingContent"width="1"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
	</tr>
<?php
  // Override order.php Class's Field Limitations
  $totals_query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' order by sort_order");
  $order->totals = array();
  while ($totals = tep_db_fetch_array($totals_query)) { 
	  $order->totals[] = array(
	  'title' => $totals['title'], 
	  'text' => $totals['text'], 
	  'class' => $totals['class'], 
	  'value' => $totals['value'], 
	  'orders_total_id' => $totals['orders_total_id']); 
	}

// START OF MAKING ALL INPUT FIELDS THE SAME LENGTH 
	$max_length = 0;
	$TotalsLengthArray = array();
	for ($i=0; $i<sizeof($order->totals); $i++) {
		$TotalsLengthArray[] = array("Name" => $order->totals[$i]['title']);
	}
	reset($TotalsLengthArray);
	foreach($TotalsLengthArray as $TotalIndex => $TotalDetails) {
		if (strlen($TotalDetails["Name"]) > $max_length) {
			$max_length = strlen($TotalDetails["Name"]);
		}
	}
// END OF MAKING ALL INPUT FIELDS THE SAME LENGTH

	$TotalsArray = array();
		for ($i=0; $i<sizeof($order->totals); $i++) {
		$TotalsArray[] = array(
		"Name" => $order->totals[$i]['title'], 
		"Price" => number_format($order->totals[$i]['value'], 2, '.', ''), 
		"Class" => $order->totals[$i]['class'], 
		"TotalID" => $order->totals[$i]['orders_total_id']);
		
		$TotalsArray[] = array(
		"Name" => "", 
		"Price" => "", 
		"Class" => "ot_custom", 
		"TotalID" => "0");
	}
	
	array_pop($TotalsArray);
	foreach($TotalsArray as $TotalIndex => $TotalDetails)
	{
		$TotalStyle = "smallText";
		if($TotalDetails["Class"] == "ot_total")
		{
			echo '	<tr>' . "\n" .
				   '		<td align="right" class="' . $TotalStyle . '"><b>' . $TotalDetails["Name"] . '</b></td>' .
				   '		<td align="right" class="' . $TotalStyle . '"><b>' . $currencies->format($TotalDetails["Price"], true, $order->info['currency'], $order->info['currency_value']) . '</b>' . 
						    "<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' size='" . strlen($TotalDetails["Name"]) . "' >" . 
						    "<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" . 
						    "<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" . 
						    "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' . 
				   '		<td align="right" class="' . $TotalStyle . '"><b>' . tep_draw_separator('pixel_trans.gif', '1', '17') . '</b>' . 
				   '	</tr>' . "\n";
		}
		elseif($TotalDetails["Class"] == "ot_subtotal") 
		{
			echo '	<tr>' . "\n" .
				   '		<td align="right" class="' . $TotalStyle . '"><b>' . $TotalDetails["Name"] . '</b></td>' .
				   '		<td align="right" class="' . $TotalStyle . '"><b>' . $currencies->format($TotalDetails["Price"], true, $order->info['currency'], $order->info['currency_value']) . '</b>' . 
						    "<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' size='" . strlen($TotalDetails["Name"]) . "' >" . 
						    "<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" . 
						    "<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" . 
						    "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' . 
				   '		<td align="right" class="' . $TotalStyle . '"><b>' . tep_draw_separator('pixel_trans.gif', '1', '17') . '</b>' . 
				   '	</tr>' . "\n";
		}
		elseif($TotalDetails["Class"] == "ot_tax")
		{
			echo '	<tr>' . "\n" .
				   '		<td align="right" class="' . $TotalStyle . '"><b>' . trim($TotalDetails["Name"]) . "</b><input name='update_totals[$TotalIndex][title]' type='hidden' size='" . $max_length . "' value='" . trim($TotalDetails["Name"]) . "'>" . '</td>' . "\n" .
				   '		<td align="right" class="' . $TotalStyle . '"><b>' . $currencies->format($TotalDetails["Price"], true, $order->info['currency'], $order->info['currency_value']) . '</b>' . 
						    "<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" . 
						    "<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" . 
						    "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' . 
				   '		<td align="right" class="' . $TotalStyle . '"><b>' . tep_draw_separator('pixel_trans.gif', '1', '17') . '</b>' . 
				   '	</tr>' . "\n";
		}
			else
		{
			echo '	<tr>' . "\n" .
				   '		<td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][title]' size='" . $max_length . "' value='" . strip_tags($TotalDetails["Name"]) . "'>" . '</td>' . "\n" .
				   '		<td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][value]' size='6' value='" . $TotalDetails["Price"] . "'>" . 
						    "<input type='hidden' name='update_totals[$TotalIndex][class]' value='" . $TotalDetails["Class"] . "'>" . 
						    "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . 
				   '		<td align="right" class="' . $TotalStyle . '"><b>' . tep_draw_separator('pixel_trans.gif', '1', '17') . '</b>' . 
					 '   </td>' . "\n" .
				   '	</tr>' . "\n";
		}
	}
	
		?>
</table>

	      </td>
      <tr>
			  <td class="smalltext"><?php echo HINT_TOTALS; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
	<!-- End Order Total Block -->
	
	<!-- Begin Status Block -->
      <tr>
	      <td class="SubTitle"><?php echo MENUE_TITLE_STATUS; ?></td>
			</tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr> 
      <tr>
        <td class="main">
				  
<table border="0" cellspacing="0" cellpadding="2" class="dataTableRow">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></td>
    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
    <td class="dataTableHeadingContent" align="left"><?php echo HEADING_TITLE_STATUS; ?></td>
   <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_COMMENTS; ?></td>
   </tr>
<?php
$orders_history_query = tep_db_query("select * from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added");
if (tep_db_num_rows($orders_history_query)) {
  while ($orders_history = tep_db_fetch_array($orders_history_query)) {
    echo '  <tr>' . "\n" .
         '    <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
         '    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>' . "\n" .
         '    <td class="smallText" align="center">';
    if ($orders_history['customer_notified'] == '1') {
      echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
    } else {
      echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
    }
    echo '    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>' . "\n" .
         '    <td class="smallText" align="left">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n";
   echo '    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>' . "\n" .
           '    <td class="smallText" align="left">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n";
  echo '  </tr>' . "\n";
  }
} else {
  echo '  <tr>' . "\n" .
       '    <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
       '  </tr>' . "\n";
}
?>
</table>

			  </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>
      <tr>
			  <td>	
						
<table border="0" cellspacing="0" cellpadding="2" class="dataTableRow">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_STATUS; ?></td>
    <td class="main" width="10">&nbsp;</td>
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_COMMENTS; ?></td>
  </tr>
	<tr>
	  <td>
		  <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main"><b><?php echo ENTRY_STATUS; ?></b></td>
          <td class="main" align="right"><?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status'], 'onChange="showConfirm(this)"'); ?></td>
        </tr>
<script>
	type = '<?=$order->info[payment_method]?>';
	function showConfirm(val){
		if (type == 'Purchase Order'){
			if (val.value == '20'){
				if (confirm("Press cancel to change status to Waiting for PO Payment")) t_set(val, '20'); else t_set(val, '7');
			}
		}
		else {
			if (val.value == '20'){
				notAll = checkSelect();
				if (notAll.indexOf('error') == -1) t_set(val, '20');  else {alert("Not all products checked as shipped!"); t_set(val, '5'); }
			}			
		}		
	}

	function t_set(obj,index){
		for(i=0;i<obj.length;i++){
			if (obj[i].value==index){
				obj.selectedIndex=i;
			}
		}
	}

</script>

        <tr>
          <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b></td>
          <td class="main" align="right"><?php echo tep_draw_checkbox_field('notify', '', false); ?></td>
        </tr>
        <tr>
          <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b></td>
          <td class="main" align="right"><?php echo tep_draw_checkbox_field('notify_comments', '', false); ?></td>
        </tr>
     </table>
	  </td>
    <td class="main" width="10">&nbsp;</td>
    <td class="main">
    <?php
   	  echo tep_draw_textarea_field('comments', 'soft', '40', '5', '');
		  ?>
    </td>
  </tr>
</table>

			  </td>
			</tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
	<!-- End of Status Block -->
	
	<!-- Begin Update Block -->
      <tr>
	      <td class="SubTitle"><?php echo '6. Packing Slip Comments'; ?></td>
			</tr>
      <tr>
	      <td class="SubTitle">
    <?php
   	  echo tep_draw_textarea_field('comments_slip', 'soft', '40', '5', $order->info['comments_slip']);
    ?>

	      </td>
			</tr>

	
      <tr>
	      <td class="SubTitle"><?php echo MENUE_TITLE_UPDATE; ?></td>
			</tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>   
      <tr>
	      <td>
          <table width="100%" border="0" cellpadding="2" cellspacing="1">
            <tr>
              <td class="main" bgcolor="#FAEDDE"><?php echo HINT_PRESS_UPDATE; ?></td>
              <td class="main" bgcolor="#FBE2C8" width="10">&nbsp;</td>
              <td class="main" bgcolor="#FFCC99" width="10">&nbsp;</td>
              <td class="main" bgcolor="#F8B061" width="10">&nbsp;</td>
              <td class="main" bgcolor="#FF9933" width="120" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
	          </tr>
          </table>
				</td>
      </tr>
	<!-- End of Update Block -->
	
      </form>
			
<?php
}

if($action == "add_product_model")
{
?>    
      <tr>
		<form action="<?=$_SERVER['PHP_SELF']?>?oID=<?=$oID?>&action=add_product_model&submit=1" method="post" onsubmit="return checkForm();">
        <td width="100%">
		<center><? if ($_GET[error]==1) echo "<b>Product Not found in database!</b>";?></center>
		<table>
			<tr><Td class="main"><b>Model Number: </b></td><td><input type="text" id="new_model_number" name="new_model_number" value="<?=$_GET[num]?>" /></td><td><input type='button' onClick='openWin()' value='Find Product'/></td></tr>
			<tr><Td class="main"><b>Qty: </b></td><td><input type="text" id="new_model_number_quantity" name="new_model_number_quantity" value="<?=$_GET[cnt]?>"  onKeyDown="return checkDigit();" onpaste="return false" /></td></tr>			
			<tr><Td class="main" colspan="3"><input type="submit" value="Add product" />&nbsp;&nbsp;&nbsp;<input type="button" value="Back" onclick="location.href='<?=$_SERVER['PHP_SELF']?>?oID=<?=$oID?>'" /></td></tr>			
		</table>
	</td>
		</form>
      </tr>
<?
}

if($action == "add_product")
{
?>
      <tr>
        <td width="100%">
				  <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
		      <td class="pageHeading"><?php echo ADDING_TITLE; ?> (No. <?php echo $oID; ?>)</td>
              <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
              <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
            </tr>
          </table>
				</td>
      </tr>

<?php
	// ############################################################################
	//   Get List of All Products
	// ############################################################################

		$result = tep_db_query("SELECT products_name, p.products_id, categories_name, ptc.categories_id FROM " . TABLE_PRODUCTS . " p LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON pd.products_id=p.products_id LEFT JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc ON ptc.products_id=p.products_id LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON cd.categories_id=ptc.categories_id where pd.language_id = '" . (int)$languages_id . "' ORDER BY categories_name");
		while($row = tep_db_fetch_array($result))
		{
			extract($row,EXTR_PREFIX_ALL,"db");
			$ProductList[$db_categories_id][$db_products_id] = $db_products_name;
			$CategoryList[$db_categories_id] = $db_categories_name;
			$LastCategory = $db_categories_name;
		}
		
	// ############################################################################
	//   Add Products Steps
	// ############################################################################
	echo '<tr><td><table border="0">' . "\n";
		
		// Set Defaults
			if(!isset($_POST['add_product_categories_id']))
			$add_product_categories_id = 0;

			if(!isset($_POST['add_product_products_id']))
			$add_product_products_id = 0;
			
			// Step 1: Choose Category
			echo '<tr class="dataTableRow"><form action=' . $_SERVER['PHP_SELF'] .'?oID=' . $_GET['oID'] . '&action=' . $_GET['action'] . ' method="POST">' . "\n";
			echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 1:</b></td>' .  "\n";
			echo '<td class="dataTableContent" valign="top">';
			if (isset($_POST['add_product_categories_id'])) {
			$current_category_id = $_POST['add_product_categories_id'];
			}
			echo ' ' . tep_draw_pull_down_menu('add_product_categories_id', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
			echo '<input type="hidden" name="step" value="2">' . "\n";
			echo '</td>' . "\n";
			echo '<td class="dataTableContent">' . ADDPRODUCT_TEXT_STEP1 . '</td>' . "\n";
			echo '</form></tr>' . "\n";
			echo '<tr><td colspan="3">&nbsp;</td></tr>' . "\n";
		   
		// Step 2: Choose Product
           if(($_POST['step'] > 1) && ($_POST['add_product_categories_id'] > 0))
		   {
           echo '<tr class="dataTableRow"><form action=' . $_SERVER['PHP_SELF'] .'?oID=' . $_GET['oID'] . '&action=' . $_GET['action'] . ' method="POST">' . "\n";
           echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 2: </b></td>' . "\n";
           echo '<td class="dataTableContent" valign="top"><select name="add_product_products_id" onChange="this.form.submit();">';
           $ProductOptions = "<option value='0'>" . ADDPRODUCT_TEXT_SELECT_PRODUCT . "\n";
           asort($ProductList[$_POST['add_product_categories_id']]);
           foreach($ProductList[$_POST['add_product_categories_id']] as $ProductID => $ProductName)
           {
              $ProductOptions .= "<option value='$ProductID'> $ProductName\n";
           }
		   if(isset($_POST['add_product_products_id'])){
         $ProductOptions = str_replace("value='" . $_POST['add_product_products_id'] . "'", "value='" . $_POST['add_product_products_id'] . "' selected=\"selected\"", $ProductOptions);
           }
		   echo ' ' . $ProductOptions .  ' ';
           echo '</select></td>' . "\n";
           echo '<input type="hidden" name="add_product_categories_id" value=' . $_POST['add_product_categories_id'] . '>';
           echo '<input type="hidden" name="step" value="3">' . "\n";
           echo '<td class="dataTableContent">' . ADDPRODUCT_TEXT_STEP2 . '</td>' . "\n";
           echo '</form></tr>' . "\n";
           echo '<tr><td colspan="3">&nbsp;</td></tr>' . "\n";
           }

		// Step 3: Choose Options
		if(($_POST['step'] > 2) && ($_POST['add_product_products_id'] > 0))
		
		{
			// Get Options for Products	
            //START by quick_fixer
			$result = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON po.products_options_id=pa.options_id LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON pov.products_options_values_id=pa.options_values_id WHERE products_id=" . $_POST['add_product_products_id'] . " and po.language_id = '" . (int)$languages_id . "' and pov.language_id = '" . (int)$languages_id . "'");
			//END by quick_fixer
			// Skip to Step 4 if no Options
			if(tep_db_num_rows($result) == 0)
			{
				echo '<tr class="dataTableRow">' . "\n";
				echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 3: </b></td>' . "\n";
				echo '<td class="dataTableContent" valign="top" colspan="2"><i>' . ADDPRODUCT_TEXT_OPTIONS_NOTEXIST . '</i></td>' . "\n";
				echo '</tr>' . "\n";
				$_POST['step'] = 4;
			}
			else
			{
				while($row = tep_db_fetch_array($result))
				{
					extract($row,EXTR_PREFIX_ALL,"db");
					$Options[$db_products_options_id] = $db_products_options_name;
					$ProductOptionValues[$db_products_options_id][$db_products_options_values_id] = $db_products_options_values_name;
				}
			
				echo '<tr class="dataTableRow"><form action=' . $_SERVER['PHP_SELF'] .'?oID=' . $_GET['oID'] . '&action=' . $_GET['action'] . ' method="POST">' . "\n";
				echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 3: </b></td><td class="dataTableContent" valign="top">';
				foreach($ProductOptionValues as $OptionID => $OptionValues)
				{
					$OptionOption = "<b>" . $Options[$OptionID] . "</b> - <select name='add_product_options[$OptionID]'>";
					foreach($OptionValues as $OptionValueID => $OptionValueName)
					{
					$OptionOption .= "<option value='$OptionValueID'> $OptionValueName\n";
					}
					$OptionOption .= "</select><br />\n";
					
					if(isset($_POST['add_product_options'])){
					 $OptionOption = str_replace("value='" . $_POST['add_product_options'][$OptionID] . "'", "value='" . $_POST['add_product_options'][$OptionID] . "' selected=\"selected\"", $OptionOption);
					}
					echo '' .  $OptionOption . '';
				}		
				echo '</td>';
				echo '<td class="dataTableContent" align="center"><input type="submit" value="' . ADDPRODUCT_TEXT_OPTIONS_CONFIRM . '">';
				echo '<input type="hidden" name="add_product_categories_id" value=' . $_POST['add_product_categories_id']. '>';
				echo '<input type="hidden" name="add_product_products_id" value=' . $_POST['add_product_products_id'] . '>';
				echo '<input type="hidden" name="step" value="4">';
				echo '</td>' . "\n";
				echo '</form></tr>' . "\n";
			}

			echo '<tr><td colspan="3">&nbsp;</td></tr>' . "\n";
		}

		// Step 4: Confirm
		if($_POST['step'] > 3)
		
		{
		   	echo '<tr class="dataTableRow"><form action=' . $_SERVER['PHP_SELF'] .'?oID=' . $_GET['oID'] . '&action=' . $_GET['action'] . ' method="POST">' . "\n";
			echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 4: </b></td>';
			echo '<td class="dataTableContent" valign="top"><input name="add_product_quantity" size="2" value="1"> ' . ADDPRODUCT_TEXT_CONFIRM_QUANTITY . '</td>';
			echo '<td class="dataTableContent" align="center"><input type="submit" value="' . ADDPRODUCT_TEXT_CONFIRM_ADDNOW . '">';

			if(is_array ($_POST['add_product_options']))
			{
				foreach($_POST['add_product_options'] as $option_id => $option_value_id)
				{
					echo '<input type="hidden" name="add_product_options[' . $option_id . ']" value="' . $option_value_id . '">';
				}
			}
			echo '<input type="hidden" name="add_product_categories_id" value=' . $_POST['add_product_categories_id'] . '>';
			echo '<input type="hidden" name="add_product_products_id" value=' . $_POST['add_product_products_id'] . '>';
			echo '<input type="hidden" name="step" value="5">';
			echo '</td>' . "\n";
			echo '</form></tr>' . "\n";
		}
		
		echo '</table></td></tr>' . "\n";
}  
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
<script>
	function generatePDF(id){
		window.open('generate_invoice.php?oID='+id);
	}
</script>
