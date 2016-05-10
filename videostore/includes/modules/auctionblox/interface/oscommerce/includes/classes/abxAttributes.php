<?php

class abxAttributes {

	var $options_name;
	var $options_menu;
	
	// Class constructor
	function abxAttributes() {
		// Initialize just for clarity
		$this->options_name = array();
		$this->options_menu = array();
		
	}
	
	function getAttributes($products_id) {
		
        if ($total = $this->hasAttributes($products_id)) {
        	if (strpos(PROJECT_VERSION, 'CRE Loaded') !== false) {
        		$this->setupAttributes_CRE($products_id);
        	}else {
        		$this->setupAttributes($products_id);
        	}
        }
        
	}
	
	// See if product has attributes not containing values
	function hasAttributes($products_id) {
		
		$prTotalQry = $this->getHasAttributesQuery($products_id);
			
		return $prTotalQry->numRows();		
	}
	
	// This is a mess, based on Zen Cart attributes module
	function setupAttributes($products_id) {

	  global $abxDatabase;
	  global $currencies;

      if (PRODUCTS_OPTIONS_SORT_ORDER=='0') {
        $options_order_by= ' order by LPAD(popt.products_options_sort_order,11,"0")';
      } else {
        $options_order_by= ' order by popt.products_options_name';
      }
      
      if (ABX_SHOW_ATTRIBUTES_WITH_COST == '0'){
      	$cost_clause = "HAVING SUM(patrib.options_values_price) = 0 ";
      }else{
      	$cost_clause = " ";
      }

      $products_options_names_query = $abxDatabase->query("select 
      				  popt.products_options_id, 
      				  popt.products_options_name 
      				  FROM " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib
      				  WHERE patrib.products_id='" . (int)$products_id . "'
      				  AND patrib.options_id = popt.products_options_id
      				  AND popt.language_id = '" . (int)$_SESSION['languages_id'] . "' 
      				  GROUP BY popt.products_options_id, 
      				  popt.products_options_name
      				  " . $cost_clause . $options_order_by);

      while ($products_options_names = $products_options_names_query->next()) {
        $products_options_array = array();

        $sql = "select    pov.products_options_values_id,
                pov.products_options_values_name, pa.*
      			FROM   " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov
      			WHERE  pa.products_id = '" . (int)$products_id . "'
      			AND    pa.options_id = '" . (int)$products_options_names['products_options_id'] . "'
      			and    pa.options_values_id = pov.products_options_values_id
      			and    pov.language_id = '" . (int)$_SESSION['languages_id'] . "' " .$order_by;

        $products_options_qry = $abxDatabase->query($sql);
        $products_options_total = $products_options_qry->numRows();
        
        while ($products_options = $products_options_qry->next()) {
          // reset
          
		  if ($products_options['options_values_price'] == '0'){
          	$products_options_array[] = array('id' => $products_options['products_options_values_id'],
          	'text' => $products_options['products_options_values_name']);
		  }else{
		  	$products_options_array[] = array('id' => $products_options['products_options_values_id'],
          	'text' => $products_options['products_options_values_name'] . ' (' . $products_options['price_prefix'] . '$' . number_format($products_options['options_values_price'] , 2) . ')');
		  }

        }

       	// normal dropdown menu display
        	if (isset($_SESSION['cart']->contents[$prod_id]['attributes'][$products_options_names['products_options_id']])) {
            	$selected_attribute = $_SESSION['cart']->contents[$prod_id]['attributes'][$products_options_names['products_options_id']];
          	} else {
            // use customer-selected values
            	if ($_POST['id'] !='') {
              		reset($_POST['id']);
              		foreach ($_POST['id'] as $key => $value) {
                		if ($key == $products_options_names['products_options_id']) {
                  			$selected_attribute = $value;
                  			break;
                		}
              		}
            	} else {
            		$selected_attribute = '0'; //default to first attribute in the list
            	}
          	}

          	$this->options_menu[] = tep_draw_pull_down_menu('id[' . $products_options_names['products_options_id'] . ']', $products_options_array, $selected_attribute, 'id="' . 'attrib-' . $products_options_names['products_options_id'] . '"') . "\n";
          	$this->options_name[] = $products_options_names['products_options_name'];

      }
	}
	
		// This is a mess, based on Zen Cart attributes module
	function setupAttributes_CRE($products_id) {
		global $currencies;

		$products_info_query = "select p.* from products p where products_id = '".$products_id."'";
		$products_info_query_response = tep_db_query($products_info_query);
		$product_info = tep_db_fetch_array($products_info_query_response);
		
		$languages_id = abxRegistry::get("languages_id");
		
		// the tax rate will be needed, so get it once
        $tax_rate = tep_get_tax_rate($product_info['products_tax_class_id']);
        
       	if (PRODUCTS_OPTIONS_SORT_ORDER=='0') {
        	$options_order_by= ' order by LPAD(po.products_options_sort_order,11,"0")';
      	} else {
        	$options_order_by= ' order by pot.products_options_name';
      	} 
      	
      	if (ABX_SHOW_ATTRIBUTES_WITH_COST == '0'){
      		$cost_clause = "HAVING SUM(pa.options_values_price) = 0 ";
      	}else{
      		$cost_clause = " ";
      	}
        
        $products_options_query = tep_db_query("SELECT pa.options_id, pa.options_values_id, pa.options_values_price, pa.price_prefix, po.options_type, po.options_length, pot.products_options_name, pot.products_options_instruct 
                                                          from " . TABLE_PRODUCTS_ATTRIBUTES  . " AS pa,
                                                               " . TABLE_PRODUCTS_OPTIONS  . " AS po,
                                                               " . TABLE_PRODUCTS_OPTIONS_TEXT  . " AS pot
                                                        WHERE pa.products_id = '" . (int)$products_id . "'
                                                          and pa.options_id = po.products_options_id
                                                          and po.products_options_id = pot.products_options_text_id and pot.language_id = '" . (int)$languages_id . "'
                                                          GROUP BY po.products_options_id,
      				  									  pot.products_options_name
                                                        " . $cost_clause . $options_order_by);
        // Store the information from the tables in arrays for easy of processing
        $options = array();
        $options_values = array();
        while ($po = tep_db_fetch_array($products_options_query)) {
        	//  we need to find the values name
            if ( $po['options_type'] != 1  && $po['options_type'] != 4 ) {
            	$options_values_query = tep_db_query("select pov.products_options_values_name, pa.options_values_id, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES  . " pa where pov.products_options_values_id = pa.options_values_id and pa.options_id = '". $po['options_id'] ."' and pa.products_id = '". $products_id ."' and language_id = '" . (int)$languages_id . "'");
                while ($ov = tep_db_fetch_array($options_values_query)){
                	$options_values[$po['options_id']][$ov['options_values_id']] =  array('name' => stripslashes($ov['products_options_values_name']),
                                                                                        'price' => $ov['options_values_price'],
                                                                                        'prefix' => $ov['price_prefix']);
                }
            } else {
        		$options_values[$po['options_id']][$po['options_values_id']] =  array('name' => stripslashes($ov['products_options_values_name']),
                                                                                        'price' => $po['options_values_price'],
                                                                                        'prefix' => $po['price_prefix']);
            }
            $options[$po['options_id']] = array('name' => $po['products_options_name'],
                                                      'type' => $po['options_type'],
                                                      'length' => $po['options_length'],
                                                      'instructions' => $po['products_options_instruct'],
                                                      'price' => $po['options_values_price'],
                                                      'prefix' => $po['price_prefix']);

    	}
        foreach ($options as $oID => $op_data) {
        	switch ($op_data['type']) {

            	case 1:
                	$maxlength = ( $op_data['length'] > 0 ? ' maxlength="' . $op_data['length'] . '"' : '' );
                    $attribute_price = $currencies->display_price($op_data['price'], $tax_rate);
                    $tmp_html = '<input type="text" name="id['. TEXT_PREFIX . $oID . '][t]"' . $maxlength . ' />';
                    $tmp_name = $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br /><span class="smallText">' . $op_data['instructions'] . '</span>' : '');
                    $tmp_name .= ($attribute_price >= 0 ? '<br><span class="smallText">' . $op_data['prefix'] . ' ' . $attribute_price . '</span>' : '' );
                    $this->options_name[] = $tmp_name;
                    $this->options_menu[] = $tmp_html;  
                    break;

             	case 4:
                    $text_area_array = explode(';',$op_data['length']);
                    $cols = $text_area_array[0];
                    if ( $cols == '' ) $cols = '100%';
                    if (isset($text_area_array[1])) {
                        $rows = $text_area_array[1];
                    } else {
                        $rows = '';
                    }
                    $attribute_price = $currencies->display_price($op_data['price'], $tax_rate);
                    $tmp_html = '<textarea name="id['. TEXT_PREFIX  . $oID . ']" rows="'.$rows.'" cols="'.$cols.'" wrap="virtual" style="width:100%;"></textarea>';
                    $tmp_name = $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br /><span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
                    $tmp_name .= ($attribute_price >= 0 ? '<br><span class="smallText">' . $op_data['prefix'] . ' ' . $attribute_price . '</span>' : '' );
                    $this->options_name[] = $tmp_name;
                    $this->options_menu[] = $tmp_html;
                    break;

            	case 2:
                	$tmp_html = '';
                    foreach ( $options_values[$oID] as $vID => $ov_data ) {
                    	if ( (float)$ov_data['price'] == 0 ) {
                        	$price = '&nbsp;';
                        } else {
                          	$price = '(&nbsp;' . $ov_data['prefix'] . '&nbsp;' . $currencies->display_price($ov_data['price'], $tax_rate) . '&nbsp;)';
                        }
                        $tmp_html .= '<input type="radio" name="id[' . $oID . ']" value="' . $vID . '">' . $ov_data['name'] . '&nbsp;' . $price . '<br />';
                  	} // End of the for loop on the option value
                    $tmp_name = $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br /><span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
                    $this->options_name[] = $tmp_name;
                    $this->options_menu[] = $tmp_html;
                    break;

              	case 3:
                    $tmp_html = '';
                    $i = 0;
                    foreach ( $options_values[$oID] as $vID => $ov_data ) {
                    	if ( (float)$ov_data['price'] == 0 ) {
                        	$price = '&nbsp;';
                        } else {
                          	$price = '(&nbsp;'.$ov_data['prefix'] . '&nbsp;' . $currencies->display_price($ov_data['price'], $tax_rate).'&nbsp;)';
                        }
                        $tmp_html .= '<input type="checkbox" name="id[' . $oID . '][' . $i . ']" value="' . $vID . '">' . $ov_data['name'] . '&nbsp;' . $price . '<br />';
                        $i++;
                  	}
                    $tmp_name = $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br /><span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
					$this->options_name[] = $tmp_name;
					$this->options_menu[] = $tmp_html;
                    break;

             	case 0:
                  	$tmp_html = '<select name="id[' . $oID . ']">';
                  	foreach ( $options_values[$oID] as $vID => $ov_data ) {
                    	if ( (float)$ov_data['price'] == 0 ) {
                          	$price = '&nbsp;';
                        } else {
                          	$price = '(&nbsp; '.$ov_data['prefix'] . '&nbsp;' . $currencies->display_price($ov_data['price'], $tax_rate).'&nbsp;)';
                        }
                        $tmp_html .= '<option value="' . $vID . '">' . $ov_data['name'] . '&nbsp;' . $price .'</option>';
                  	} // End of the for loop on the option values
                    $tmp_html .= '</select>';
                    $tmp_name = $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br /><span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
                    $this->options_name[] = $tmp_name;
					$this->options_menu[] = $tmp_html;
                    break;
            }  //end of switch
        } //end of for
	}
	
	function getHasAttributesQuery($products_id){
		global $abxDatabase;
		
		if (ABX_SHOW_ATTRIBUTES_WITH_COST == '0'){
      	    $cost_clause = "HAVING SUM(patrib.options_values_price) = 0 ";
      	}else{
      		$cost_clause = "";
		} 
		
		if (strpos(PROJECT_VERSION, 'CRE Loaded') !== false) {
			return $abxDatabase->query("select patrib.options_id as options_id
									       from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " AS pot, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib
										   where    patrib.products_id='" . (int)$products_id . "'
									       and      patrib.options_id = popt.products_options_id
									       and      pot.products_options_text_id = popt.products_options_id 
									       GROUP BY patrib.options_id " . 
										   $cost_clause . " limit 1");
		}
		else{
			return $abxDatabase->query("select patrib.options_id as options_id
									       from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib
										   where    patrib.products_id='" . (int)$products_id . "'
									       and      patrib.options_id = popt.products_options_id
                        GROUP BY patrib.options_id " . 
										    $cost_clause . " limit 1");
		}
	}
	//NIL
    //paramenter -- $basket_id, $order_id, $product_id
    function updateOrderProducts($basket_id, $order_id, $product_id){
    	global $abxDatabase;
      
	   	//	perform inserts on the order_products table	if the order is been already imported via AuctionBlox

      $order_products_id ='';
      $prid_arr = array();
       
      if(isset($order_id) && (int)$order_id > 0) {

       	$abxSaleItem	= new abxSaleItem();
       	$saleItem		= $abxSaleItem->getSale($basket_id);
       	$product_values = $abxDatabase->fetch_row("SELECT * from " . TABLE_PRODUCTS . " WHERE products_id = " . (int)$product_id ." LIMIT 1;");
       	
     		$product_prid = $abxDatabase->fetch_row("SELECT orders_products_id from " . TABLE_ORDERS_PRODUCTS . " WHERE products_id = " . (int)$product_id. " AND orders_id = " . (int)$order_id  . " LIMIT 1;");

       	$sql_data_array = array(
            'orders_id' 			    => $order_id,
      	    'products_id' 				=> $product_id,
						'products_model'	 		=> $product_values['products_model'],
						'products_name' 			=> $saleItem['extTitle'],
						'products_price' 			=> $saleItem['priceEnd'],
						'final_price' 				=> $saleItem['priceEnd'],
						//'products_tax' 				=> '',
						//'products_quantity' 	=> $saleItem['qty'],
				);
  		  
        $abxDatabase->update(TABLE_ORDERS_PRODUCTS, $sql_data_array,"orders_products_id = ". (int)$product_prid['orders_products_id']);
  		  return $product_prid['orders_products_id'];
  	  }
	}//NIL
	
	//NIL
	function updateOrderProductsAttr(&$info, $option, $value, $attr_value = false, $order_products_id){
		global $abxDatabase;
		if (strpos(PROJECT_VERSION, 'CRE Loaded') !== false) {
			$attributes_values = $abxDatabase->fetch_row("select poptext.products_options_name, poval.products_options_values_name, pa.options_values_price, 
								pa.price_prefix 
								from 
								" . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa,
								". TABLE_PRODUCTS_OPTIONS_TEXT  . " AS poptext
								where 
								pa.products_id = '" . $info['product_id'] . "' 
								and pa.options_id = '" . (int)$option . "' 
								and pa.options_id = popt.products_options_id 
								and pa.options_values_id = '" . (int)$value . "' 
								and pa.options_values_id = poval.products_options_values_id 
								and poptext.products_options_text_id = popt.products_options_id
								and poptext.language_id = '" . (int)$_SESSION['languages_id'] . "' 
								and poval.language_id = '" . (int)$_SESSION['languages_id'] . "'");
			
			$sql_data_array = array('orders_id' => $info['order_id'],
	                              'orders_products_id' => $order_products_id,
	                              'products_options' => $attributes_values['products_options_name'],
	      						  'options_values_price' => $attributes_values['options_values_price'],
	                              'price_prefix' => $attributes_values['price_prefix'],
					              'products_options_id' => (int)$option,
				                  'products_options_values_id' => (int)$value,
	                              );
        
        	$sql_data_array['products_options_values'] = $attr_value ? $attr_value : $attributes_values['products_options_values_name'];
		
		}else{
			$attributes_values = $abxDatabase->fetch_row("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, 
								pa.price_prefix 
								from 
								" . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
								where 
								pa.products_id = '" . $info['product_id'] . "' and pa.options_id = '" . (int)$option . "' 
								and pa.options_id = popt.products_options_id and pa.options_values_id = '" . (int)$value . "' 
								and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . (int)$_SESSION['languages_id'] . "' 
								and poval.language_id = '" . (int)$_SESSION['languages_id'] . "'");
		
			$sql_data_array = array('orders_id' => $info['order_id'],
	                              'orders_products_id' => $order_products_id,
	                              'products_options' => $attributes_values['products_options_name'],
	      						  'options_values_price' => $attributes_values['options_values_price'],
	                              'price_prefix' => $attributes_values['price_prefix'],
	                              );
        
        	$sql_data_array['products_options_values'] = $attr_value ? $attr_value : $attributes_values['products_options_values_name'];
   		}
   		
	    $abxDatabase->insert(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

	}//NIL
} // end class
?>