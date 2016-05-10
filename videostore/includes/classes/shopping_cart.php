<?php
/*
  $Id: shopping_cart.php,v 1.35 2003/06/25 21:14:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class shoppingCart {
    var $contents, $total, $weight, $cartID, $content_type;
 

   function shoppingCart() {
      $this->reset();

    }

    function restore_contents() {
//ICW replace line
      global $customer_id, $gv_id, $REMOTE_ADDR;
//    global $customer_id;


      if (!tep_session_is_registered('customer_id')) return false;

// insert current cart contents in database
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $qty = $this->contents[$products_id]['qty'];
          $product_query = tep_db_query("select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
          if (!tep_db_num_rows($product_query)) {
            tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added, item_date_added) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . $qty . "', '" . date('Ymd') . "', '".time()."')");
            if (isset($this->contents[$products_id]['attributes'])) {
              reset($this->contents[$products_id]['attributes']);
              while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
                tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . (int)$option . "', '" . (int)$value . "')");
              }
            }
          } else {
            tep_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $qty . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
          }
        }
//ICW ADDDED FOR CREDIT CLASS GV - START
        if (tep_session_is_registered('gv_id')) {
          $gv_query = tep_db_query("insert into  " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, customer_id, redeem_date, redeem_ip) values ('" . $gv_id . "', '" . (int)$customer_id . "', now(),'" . $REMOTE_ADDR . "')");
          $gv_update = tep_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id = '" . $gv_id . "'");
          tep_gv_account_update($customer_id, $gv_id);
          tep_session_unregister('gv_id');
        }
//ICW ADDDED FOR CREDIT CLASS GV - END

      }

// reset per-session cart contents, but not the database contents
      $this->reset(false);

      $products_query = tep_db_query("select products_id, customers_basket_quantity from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "'");
      while ($products = tep_db_fetch_array($products_query)) {
        $this->contents[$products['products_id']] = array('qty' => $products['customers_basket_quantity']);
// attributes
        $attributes_query = tep_db_query("select products_options_id, products_options_value_id from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products['products_id']) . "'");
        while ($attributes = tep_db_fetch_array($attributes_query)) {
          $this->contents[$products['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
        }
      }

      $this->cleanup();
    }

    function reset($reset_database = false) {
      global $customer_id;

      $this->contents = array();
      $this->total = 0;
      $this->weight = 0;
      $this->content_type = false;

      if (tep_session_is_registered('customer_id') && ($reset_database == true)) {
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "'");
      }

      unset($this->cartID);
      if (tep_session_is_registered('cartID')) tep_session_unregister('cartID');
    }

    function add_cart($products_id, $qty = '1', $attributes = '', $notify = true) {
      global $new_products_id_in_cart, $customer_id;

      $products_id = tep_get_uprid($products_id, $attributes);
      if ($notify == true) {
        $new_products_id_in_cart = $products_id;
        tep_session_register('new_products_id_in_cart');
      }

      if ($this->in_cart($products_id)) {
        $this->update_quantity($products_id, $qty, $attributes);
      } else {
        $this->contents[] = array($products_id);
        $this->contents[$products_id] = array('qty' => $qty, 'date_added'=>time());
// insert into database
        if (tep_session_is_registered('customer_id')) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added, item_date_added) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . $qty . "', '" . date('Ymd') . "', '".time()."')");

        if (is_array($attributes)) {
          reset($attributes);
          while (list($option, $value) = each($attributes)) {
            $this->contents[$products_id]['attributes'][$option] = $value;
// insert into database
            if (tep_session_is_registered('customer_id')) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . (int)$option . "', '" . (int)$value . "')");
          }
        }
      }

/*----------- Newely added -----------------*/
if (tep_session_is_registered('customer_id')){
      $this->contents = array();

//ob_start();
      $products_query = tep_db_query("select products_id, customers_basket_quantity, item_date_added from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' order by item_date_added desc");
      while ($products = tep_db_fetch_array($products_query)) {
//echo $products['products_id']."---".$products['customers_basket_quantity']."<br>";
        $this->contents[$products['products_id']] = array('qty' => $products['customers_basket_quantity'], 'date_added'=>$products['item_date_added']);
// attributes
        $attributes_query = tep_db_query("select products_options_id, products_options_value_id from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products['products_id']) . "'");
        while ($attributes = tep_db_fetch_array($attributes_query)) {
          $this->contents[$products['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
        }
      }
	//$len = ob_get_contents();
//ob_end_clean();
//mail('x0661t@d-net.kiev.ua','test', $len);

/*----------- Newely added -----------------*/
}

      $this->cleanup();

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
      $this->cartID = $this->generate_cart_id();
    }

    function update_quantity($products_id, $quantity = '', $attributes = '') {
      global $customer_id;

      if (empty($quantity)) return true; // nothing needs to be updated if theres no quantity, so we return true..

      $this->contents[$products_id] = array('qty' => $quantity, 'date_added'=>time());
// update database
      if (tep_session_is_registered('customer_id')) tep_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $quantity . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");

      if (is_array($attributes)) {
        reset($attributes);
        while (list($option, $value) = each($attributes)) {
          $this->contents[$products_id]['attributes'][$option] = $value;
// update database
          if (tep_session_is_registered('customer_id')) tep_db_query("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = '" . (int)$value . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "' and products_options_id = '" . (int)$option . "'");
        }
      }
    }

    function cleanup() {
      global $customer_id;

      reset($this->contents);
      while (list($key,) = each($this->contents)) {
        if ($this->contents[$key]['qty'] < 1) {
          unset($this->contents[$key]);
// remove from database
          if (tep_session_is_registered('customer_id')) {
            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($key) . "'");
            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($key) . "'");
          }
        }
      }
    }

    function count_contents() {  // get total number of items in cart
      $total_items = 0;
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $total_items += $this->get_quantity($products_id);
        }
      }

      return $total_items;
    }

    function get_quantity($products_id) {
      if (isset($this->contents[$products_id])) {
        return $this->contents[$products_id]['qty'];
      } else {
        return 0;
      }
    }

    function in_cart($products_id) {
      if (isset($this->contents[$products_id])) {
        return true;
      } else {
        return false;
      }
    }

    function remove($products_id) {
      global $customer_id;

      unset($this->contents[$products_id]);
// remove from database
      if (tep_session_is_registered('customer_id')) {
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
      }

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
      $this->cartID = $this->generate_cart_id();
    }

    function remove_all() {
      $this->reset();
    }

    function get_product_id_list() {
      $product_id_list = '';
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $product_id_list .= ', ' . $products_id;
        }
      }

      return substr($product_id_list, 2);
    }

    function calculate() {
	
	
      global $customer_id, $whole;
	  
	 
	  
      $add_weight = true;

      $this->total_virtual = 0; // ICW Gift Voucher System
      $this->total = 0;
      $this->weight = 0;
      $_SESSION['dist_weight'] = 0.00;
      $_SESSION['nondist_weight'] = 0.00;

      if (!is_array($this->contents)) return 0;

      reset($this->contents);
      while (list($products_id, ) = each($this->contents)) {
        $qty = $this->contents[$products_id]['qty'];
         $new_price = 0;
// products price
        $product_query = tep_db_query("select products_distribution, products_id, products_model, products_price, products_tax_class_id, products_weight from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");

        if ($product = tep_db_fetch_array($product_query)) {
// ICW ORDER TOTAL CREDIT CLASS Start Amendment
          $no_count = 1;
          //$gv_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
          //$gv_result = tep_db_fetch_array($gv_query);

          if (preg_match('/^GIFT/', $$product['products_model'])) {
            $no_count = 0;
          }
// ICW ORDER TOTAL  CREDIT CLASS End Amendment

          $prid = $product['products_id'];
          $products_tax = tep_get_tax_rate($product['products_tax_class_id']);
          $products_price = $product['products_price'];
          $products_weight = $product['products_weight'];

          $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . (int)$prid . "' and status = '1'");
          if (tep_db_num_rows ($specials_query)) {
            $specials = tep_db_fetch_array($specials_query);
            //$products_price = $specials['specials_new_products_price'];
	    $new_price = $specials['specials_new_products_price'];
          }


	if ( ($whole['shipping']==1) && ($product['products_distribution'] == 1) && ($whole['iswholesale']==1) ) {
                               $add_weight = true;
}
	
	
	if ($add_weight){
	
	
          $this->weight_virtual += ($qty * $products_weight) * $no_count;// ICW CREDIT CLASS;
          $this->weight += ($qty * $products_weight);

	if (($product['products_distribution'] == 1) && ($whole['iswholesale']==1) ) {
                          $_SESSION['dist_weight'] += ($qty * $products_weight);
}


	if (($product['products_distribution'] == 0) && ($whole['iswholesale']==1) ) {
                         $_SESSION['nondist_weight'] += ($qty * $products_weight);
 }


		}
	$add_weight = true;
//var_dump($_SESSION);
//var_dump($_SESSION['nondist_weight']);
if ($whole['iswholesale'] == 1){

	if ($product['products_distribution'] == 1){

	if (($whole['disc1'] == 1) && (intval($whole[distribution_percentage])>0)){
          	$this->total_virtual += tep_add_tax(tep_customer_price($products_price, $product[products_id], 1), $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
         	$this->total += tep_add_tax(tep_customer_price($products_price, $product[products_id], 1), $products_tax) * $qty;
		}	
	elseif (($whole['disc1'] == 2) && (intval($whole[distribution_percentage])>0)){
		if (intval($new_price)>0) {
          	$this->total_virtual += tep_add_tax(tep_customer_price($new_price, $product[products_id], 1), $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
          	$this->total += tep_add_tax(tep_customer_price($new_price, $product[products_id], 1), $products_tax) * $qty;
			}
			else{
          	$this->total_virtual += tep_add_tax(tep_customer_price($products_price, $product[products_id], 1), $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
          	$this->total += tep_add_tax(tep_customer_price($products_price, $product[products_id], 1), $products_tax) * $qty;
			}
		}	
	else{

 	   if ($new_price) {
          	$this->total_virtual += tep_add_tax($new_price, $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
          	$this->total += tep_add_tax($new_price, $products_tax) * $qty;
    		} else {
          	$this->total_virtual += tep_add_tax($products_price, $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
          	$this->total += tep_add_tax($products_price, $products_tax) * $qty;
    		}
			
	   }

	}

	else{

	if (($whole['disc2'] == 1) && (intval($whole[nondistribution_percentage])>0)){
          	$this->total_virtual += tep_add_tax(tep_customer_price($products_price, $product[products_id], 2), $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
          	$this->total += tep_add_tax(tep_customer_price($products_price, $product[products_id], 2), $products_tax) * $qty;		
		}	
	
	elseif (($whole['disc2'] == 2) && (intval($whole[nondistribution_percentage])>0)){
	 	if (intval($new_price)>0) {
          	$this->total_virtual += tep_add_tax(tep_customer_price($new_price, $product[products_id], 2), $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
          	$this->total += tep_add_tax(tep_customer_price($new_price, $product[products_id], 2), $products_tax) * $qty;		
			}
			else{
          	$this->total_virtual += tep_add_tax(tep_customer_price($products_price, $product[products_id], 2), $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
          	$this->total += tep_add_tax(tep_customer_price($products_price, $product[products_id], 2), $products_tax) * $qty;		
			}
		}
		
	else{
 	   if ($new_price) {
          	$this->total_virtual += tep_add_tax($new_price, $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
          	$this->total += tep_add_tax($new_price, $products_tax) * $qty;
    		} else {
          	$this->total_virtual += tep_add_tax($products_price, $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
          	$this->total += tep_add_tax($products_price, $products_tax) * $qty;
    		}
	   }
	}

}

	else{
 	   if ($new_price) {
          	$this->total_virtual += tep_add_tax($new_price, $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
          	$this->total += tep_add_tax($new_price, $products_tax) * $qty;	   
    		} else {
          	$this->total_virtual += tep_add_tax($products_price, $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
          	$this->total += tep_add_tax($products_price, $products_tax) * $qty;
    		}
    }
}

//var_dump($this->total);
//var_dump($this->weight);
// attributes price
        if (isset($this->contents[$products_id]['attributes'])) {
          reset($this->contents[$products_id]['attributes']);
          while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
            $attribute_price_query = tep_db_query("select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$prid . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
            $attribute_price = tep_db_fetch_array($attribute_price_query);
            if ($attribute_price['price_prefix'] == '+') {
              $this->total += $qty * tep_add_tax($attribute_price['options_values_price'], $products_tax);
            } else {
              $this->total -= $qty * tep_add_tax($attribute_price['options_values_price'], $products_tax);
            }
          }
        }
      }
    }



    function calculate_vendor() {
      $this->total_virtual = 0; // ICW Gift Voucher System
      $this->total = 0;
      $this->weight = 0;
      $this->dist_weight = 0;
      $this->nondist_weight = 0;
      if (!is_array($this->contents)) return 0;

      reset($this->contents);
      while (list($products_id, ) = each($this->contents)) {
        $qty = $this->contents[$products_id]['qty'];

// products price
        $product_query = tep_db_query("select products_id, products_model, products_tax_class_id, products_weight from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
        if ($product = tep_db_fetch_array($product_query)) {
// ICW ORDER TOTAL CREDIT CLASS Start Amendment
          $no_count = 1;

	if (preg_match('/^GIFT/', $gv_result['products_model']))  {
            $no_count = 0;
          }
// ICW ORDER TOTAL  CREDIT CLASS End Amendment

          $prid = $product['products_id'];
          $products_price = '2.00';
          $products_weight = $product['products_weight'];

          $this->total_virtual += $products_price * $qty * $no_count;// ICW CREDIT CLASS;
          $this->weight_virtual += ($qty * $products_weight) * $no_count;// ICW CREDIT CLASS;
          $this->total += $products_price * $qty;
          $this->weight += ($qty * $products_weight);
        }
      }
    }

    function attributes_price($products_id) {
      $attributes_price = 0;

      if (isset($this->contents[$products_id]['attributes'])) {
        reset($this->contents[$products_id]['attributes']);
        while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
          $attribute_price_query = tep_db_query("select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
          $attribute_price = tep_db_fetch_array($attribute_price_query);
          if ($attribute_price['price_prefix'] == '+') {
            $attributes_price += $attribute_price['options_values_price'];
          } else {
            $attributes_price -= $attribute_price['options_values_price'];
          }
        }
      }

      return $attributes_price;
    }


    function cmp($a, $b){
	if ($a['date_added']>$b['date_added']) return 0;
	if ($a['date_added']<$b['date_added']) return 1;
	if ($a['date_added']==$b['date_added']) return 1;
    }

    function get_products() {
      global $languages_id, $whole;

      if (!is_array($this->contents)) return false;

      $products_array = array();
      reset($this->contents);

      uasort($this->contents, array("shoppingCart", "cmp"));

      while (list($products_id, ) = each($this->contents)) {
	$new_price = 0;
        $products_query = tep_db_query("select p.products_distribution, p.has_rights, p.products_out_of_print, p.products_date_available, p.products_always_on_hand, p.products_id, pd.products_name, pd.products_name_prefix, pd.products_name_suffix, p.products_model, p.products_image, p.products_price, p.products_warehouse_location, p.products_weight, p.products_tax_class_id, p.products_media_type_id, se.series_name, st.products_set_type_name, mt.products_media_type_name from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id) left join " . TABLE_MEDIA_TYPE . " mt on (p.products_media_type_id = mt.products_media_type_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$products_id . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
        if ($products = tep_db_fetch_array($products_query)) {
          $prid = $products['products_id'];
          $products_price = $products['products_price'];

          $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . (int)$prid . "' and status = '1'");
          if (tep_db_num_rows($specials_query)) {
            $specials = tep_db_fetch_array($specials_query);
            //$products_price = $specials['specials_new_products_price'];
	    $new_price = $specials['specials_new_products_price'];
          }




if ($whole['iswholesale'] == 1){
	if ($products['products_distribution'] == 1){
	if (($whole['disc1'] == 1) && (intval($whole[distribution_percentage])>0)){
		$wprice = tep_customer_price($products_price, $products_id, 1);
		$rprice = tep_customer_price($products_price, $products_id, 1) + $this->attributes_price($products_id);
		}	
	elseif (($whole['disc1'] == 2) && (intval($whole[distribution_percentage])>0)){
		if (intval($new_price)>0) {
		$wprice = tep_customer_price($new_price, $products_id, 1);
		$rprice = tep_customer_price($new_price, $products_id, 1) + $this->attributes_price($products_id);
		}
		else{
		$wprice = tep_customer_price($products_price, $products_id, 1);
		$rprice = tep_customer_price($products_price, $products_id, 1) + $this->attributes_price($products_id);
		}
		}	
	else{
	
 	   if ($new_price) {
		$wprice = $new_price;
		$rprice = $new_price + $this->attributes_price($products_id);
    		} else {
		$wprice = $products_price;
		$rprice = $products_price + $this->attributes_price($products_id);
    		}
			
	   }

	}

	else{
	if (($whole['disc2'] == 1) && (intval($whole[nondistribution_percentage])>0)){
		$wprice = tep_customer_price($products_price, $products_id, 2);
		$rprice = tep_customer_price($products_price, $products_id, 2) + $this->attributes_price($products_id);
		}	
	
	elseif (($whole['disc2'] == 2) && (intval($whole[nondistribution_percentage])>0)){
		if (intval($new_price)>0) {
		$wprice = tep_customer_price($new_price, $products_id, 2);
		$rprice = tep_customer_price($new_price, $products_id, 2) + $this->attributes_price($products_id);
			}
		else{
		$wprice = tep_customer_price($products_price, $products_id, 2);
		$rprice = tep_customer_price($products_price, $products_id, 2) + $this->attributes_price($products_id);
		}
		}
		
	else{
 	   if ($new_price) {
		$wprice = $new_price;
		$rprice = $new_price + $this->attributes_price($products_id);
    		} else {
		$wprice = $products_price;
		$rprice = $products_price + $this->attributes_price($products_id);
    		}
	   }

    }
}

	else{
 	   if ($new_price) {
		$wprice = $new_price;
		$rprice = $new_price + $this->attributes_price($products_id);
    		} else {
		$wprice = $products_price;
		$rprice = $products_price + $this->attributes_price($products_id);
    		}
}



          $products_array[] = array('id' => $products_id,
                                    'name' => '<b>' . $products['series_name'] . '</b>&nbsp;' . $products['products_name_prefix'] . '&nbsp;<b>' . $products['products_name'] . '</b>&nbsp;' . $products['products_name_suffix'] . '-' .$products['products_media_type_name'],
									'name_' => $products['products_name'],
									'name_prefix' => $products['products_name_prefix'],
									'name_suffix' => $products['products_name_suffix'],
									'distribution' => $products['products_distribution'],
									'series' => $products['series_name'],
									'set_type' => $products['products_set_type_name'],
									'media_id' => $products['products_media_type_id'],
									'loc' => $products['products_warehouse_location'],
                                    'model' => $products['products_model'],
                                    'image' => $products['products_image'],
                                    'price' => $wprice,
				    				'products_out_of_print' => $products['products_out_of_print'],
                                    'quantity' => $this->contents[$products_id]['qty'],
                                    'products_always_on_hand' => $products['products_always_on_hand'],
                                    'products_date_available' => $products['products_date_available'],
                                    'has_rights' => $products['has_rights'],
                                    'weight' => $products['products_weight'],
                                    'final_price' => $rprice,
                                    'tax_class_id' => $products['products_tax_class_id'],
                                    'attributes' => (isset($this->contents[$products_id]['attributes']) ? $this->contents[$products_id]['attributes'] : ''));
        }
      }

      return $products_array;
    }

    function show_total() {
      $this->calculate();

      return $this->total;
    }

    function show_total_vendor() {
      $this->calculate_vendor();

      return $this->total;
    }

    function show_weight() {
      $this->calculate();

      return $this->weight;
    }

    function show_weight_vendor() {
      $this->calculate_vendor();

      return $this->weight;
    }

    function generate_cart_id($length = 5) {
      return tep_create_random_value($length, 'digits');
    }

    function get_content_type() {
      $this->content_type = false;

      if ( (DOWNLOAD_ENABLED == 'true') && ($this->count_contents() > 0) ) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          if (isset($this->contents[$products_id]['attributes'])) {
            reset($this->contents[$products_id]['attributes']);
            while (list(, $value) = each($this->contents[$products_id]['attributes'])) {
              $virtual_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad where pa.products_id = '" . (int)$products_id . "' and pa.options_values_id = '" . (int)$value . "' and pa.products_attributes_id = pad.products_attributes_id");
              $virtual_check = tep_db_fetch_array($virtual_check_query);

              if ($virtual_check['total'] > 0) {
                switch ($this->content_type) {
                  case 'physical':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'virtual';
                    break;
                }
              } else {
                switch ($this->content_type) {
                  case 'virtual':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'physical';
                    break;
                }
              }
            }
// ICW ADDED CREDIT CLASS - Begin
          } elseif ($this->show_weight() == 0) {
            reset($this->contents);
            while (list($products_id, ) = each($this->contents)) {
              $virtual_check_query = tep_db_query("select products_weight from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
              $virtual_check = tep_db_fetch_array($virtual_check_query);
              if ($virtual_check['products_weight'] == 0) {
                switch ($this->content_type) {
                  case 'physical':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'virtual';
                    break;
                }
              } else {
                switch ($this->content_type) {
                  case 'virtual':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'physical';
                    break;
                }
              }
            }
// ICW ADDED CREDIT CLASS - End
          } else {
            switch ($this->content_type) {
              case 'virtual':
                $this->content_type = 'mixed';

                return $this->content_type;
                break;
              default:
                $this->content_type = 'physical';
                break;
            }
          }
        }
      } else {
        $this->content_type = 'physical';
      }

      return $this->content_type;
    }

    function unserialize($broken) {
      for(reset($broken);$kv=each($broken);) {
        $key=$kv['key'];
        if (gettype($this->$key)!="user function")
        $this->$key=$kv['value'];
      }
    }
   // ------------------------ ICW CREDIT CLASS Gift Voucher Addittion-------------------------------Start
   // amend count_contents to show nil contents for shipping
   // as we don't want to quote for 'virtual' item
   // GLOBAL CONSTANTS if NO_COUNT_ZERO_WEIGHT is true then we don't count any product with a weight
   // which is less than or equal to MINIMUM_WEIGHT
   // otherwise we just don't count gift certificates

    function count_contents_virtual() {  // get total number of items in cart disregard gift vouchers
      $total_items = 0;
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $no_count = false;
          $gv_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
          $gv_result = tep_db_fetch_array($gv_query);
 	if (preg_match('/^GIFT/', $gv_result['products_model']))  {
            $no_count=true;
          }
          if (NO_COUNT_ZERO_WEIGHT == 1) {
            $gv_query = tep_db_query("select products_weight from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($products_id) . "'");
            $gv_result=tep_db_fetch_array($gv_query);
            if ($gv_result['products_weight']<=MINIMUM_WEIGHT) {
              $no_count=true;
            }
          }
          if (!$no_count) $total_items += $this->get_quantity($products_id);
        }
      }
      return $total_items;
    }
// ------------------------ ICW CREDIT CLASS Gift Voucher Addittion-------------------------------End

  }
?>