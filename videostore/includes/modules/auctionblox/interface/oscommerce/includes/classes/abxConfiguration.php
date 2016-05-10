<?php
/*
  $Id: abxConfiguration.php,v 1.7 2008/04/02 18:46:24 devosc Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/

  class abxConfiguration {

    function update($id,$value)
    {
      global $abxDatabase;

      $array = array(
        'configuration_value' => $value,
        'last_modified'       => 'now()'
      );

      return $abxDatabase->update(TABLE_ABX_CONFIGURATION,$array,'configuration_id = ' .(int)$id);
    }

    function updateByKey($key,$value)
    {
      global $abxDatabase;

      $array = array(
        'configuration_value' => $value,
        'last_modified'       => 'now()'
      );

      return $abxDatabase->update(TABLE_ABX_CONFIGURATION,$array,"configuration_key = '$key'");
    }

    function queryById($id)
    {
      global $abxDatabase;

      return $abxDatabase->fetch_row("select configuration_id, configuration_title, configuration_value, configuration_key, configuration_description, date_added, last_modified, use_function, set_function from " . TABLE_ABX_CONFIGURATION . " where configuration_id = " . (int)$id);
    }

    function queryByKey($key)
    {
      global $abxDatabase;
      return $abxDatabase->fetch_row("select configuration_id, configuration_title, configuration_value, configuration_key, configuration_description, date_added, last_modified, use_function, set_function from " . TABLE_ABX_CONFIGURATION . " where configuration_key = '$key'");
    }
    
    /**
	* convert unix timestamp to ISO 8601 compliant date string
	*
	* 
	*/
	function getIso8601TimeStamp(){

		$datestr = date('Y-m-d\TH:i:sO',time());

		$eregStr =
		'([0-9]{4})-'.	// centuries & years CCYY-
		'([0-9]{2})-'.	// months MM-
		'([0-9]{2})'.	// days DD
		'T'.			// separator T
		'([0-9]{2}):'.	// hours hh:
		'([0-9]{2}):'.	// minutes mm:
		'([0-9]{2})(\.[0-9]*)?'. // seconds ss.ss...
		'(Z|[+\-][0-9]{2}:?[0-9]{2})?'; // Z to indicate UTC, -/+HH:MM:SS.SS... for local tz's

		if(preg_match('/'.$eregStr,$datestr.'/',$regs)){
			return sprintf('%04d-%02d-%02dT%02d:%02d:%02dZ',$regs[1],$regs[2],$regs[3],$regs[4],$regs[5],$regs[6]);
		}
		return false;
	}
	
  	function getOrderStatuses()
	{
      global $abxDatabase, $languages_id;
      
	  $config = array();
	  
	  $orders_status_query = $abxDatabase->query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
	  while ($orders_status = $orders_status_query->next()) {
	    $config['status'][]= 
	      array('id' => $orders_status['orders_status_id'],
	            'label' => $orders_status['orders_status_name']);
       }
	  
	  return $config;
	}

	function getCategories(&$categories_array, $parent_id = '0')
    {
      global $abxDatabase, $languages_id;
  
      //NOTE: Since we are doing a recursive search for subcategories under a single category,
      // we can implement our Nested Set model algorithm outright               
      $query = "select l.code as locale, c.categories_id, c.parent_id, c.sort_order, cd.categories_name from " .
                TABLE_CATEGORIES . " c, " . 
                TABLE_CATEGORIES_DESCRIPTION . " cd, " . 
                TABLE_LANGUAGES . " l " .
                " WHERE parent_id = " . (int)$parent_id . 
                " AND c.categories_id = cd.categories_id" .
                ' AND cd.language_id = l.languages_id' .
                " AND cd.language_id = " . (int)$languages_id . 
                " ORDER BY c.sort_order, cd.categories_name";
      
      $categories_query = $abxDatabase->query($query);
      while ($categories = $categories_query->next()) {
        if(isset($categories['parent_id']) && $categories['parent_id'] > 0)
        {
           $categories_array['category'][] = array(
                      'id' => $categories['categories_id'],
                      'locale' => $categories['locale'],           
                      'parentId' => $categories['parent_id'],
                      'name' => $indent . $categories['categories_name']
           );
        }
        else
        {
           $categories_array['category'][] = array(
                      'id' => $categories['categories_id'],
                      'locale' => $categories['locale'],              
                      'name' => $indent . $categories['categories_name']
           );
        }
  
        if ($categories['categories_id'] != $parent_id) {
          $categories_array = $this->getCategories($categories_array, $categories['categories_id']);
        }
      }
  
      return $categories_array;
    }
  }//end class
?>