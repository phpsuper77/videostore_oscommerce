<?php

require_once(DIR_FS_ABX5_CLASSES . 'abxFeedProductsBase.php');
require_once_cart('includes/functions/integration.php');

class abxFeedProducts extends abxFeedProductsBase {
	
	var $dbClass = null,
		$dbQuery = null,
		$sql = null,
		$timeStart = null,
		$timeEnd = null,
		$productId = null,
		$sku = null,
		$firstResult = -1,
		$maxResults = -1,
		$customFields = null;
		
	function abxFeedProducts() {
	}
	
	function _getFromClause() {
        $from = 
            'FROM ' . TABLE_PRODUCTS . ' p ' .
            ' INNER JOIN ' . TABLE_PRODUCTS_DESCRIPTION . ' pd on p.products_id=pd.products_id' .
            ' INNER JOIN ' . TABLE_LANGUAGES . ' l on pd.language_id=l.languages_id' .
	    ' LEFT JOIN series s on (p.series_id = s.series_id) ' .
            ' WHERE p.products_status = 1 ' ;
        
        if(isset($this->timeStart))
        	$from .= " AND (p.products_date_added >= '{$this->timeStart}' OR p.products_last_modified >= '{$this->timeStart}')";
        
        if(isset($this->timeEnd))
        	$from .= " AND (p.products_date_added <= '{$this->timeEnd}' OR p.products_last_modified <= '{$this->timeEnd}')";
                
        if(isset($this->productId))
        	$from .= ' AND p.products_id = ' . (int)$this->productId;
        if(isset($this->sku))
        	$from .= " AND p.products_model = '{$this->sku}'";        	
        	
        return $from;
	}
	
	function isInit()
	{
		return strlen($this->sql) == 0 ? false : true; 
	}
	
	function init() {
		global $abxDatabase;
		
		$this->dbClass = &$abxDatabase;
		
		$this->mediaTypes = $this->dbClass
		    ->fetch_results(
		      "select products_media_type_id, products_media_type_name from products_media_types", 
		      '',
		      'products_media_type_id'
		    );
		    
		$this->videoFormats = $this->dbClass
		    ->fetch_results(
		      "select products_video_format_id, products_video_format_name from products_video_formats", 
		      '',
		      'products_video_format_id'
		    );
		    
		$this->audioFormats = $this->dbClass
		    ->fetch_results(
		      "select products_audio_id, products_audio_name from products_audios", 
		      '',
		      'products_audio_id'
		    );	
		    
		$this->aspectRatios = $this->dbClass
		    ->fetch_results(
		      "select products_aspect_ratio_id, products_aspect_ratio_name from products_aspect_ratios", 
		      '',
		      'products_aspect_ratio_id'
		    );	
		    
		$this->regionCodes = $this->dbClass
		    ->fetch_results(
		      "select products_region_code_id, products_region_code_name, products_region_code_desc from products_region_codes", 
		      '',
		      'products_region_code_id'
		    );	
		    
		$this->setTypes = $this->dbClass
		    ->fetch_results(
		      "select products_set_type_id, products_set_type_name from products_set_types", 
		      '',
		      'products_set_type_id'
		    );			    
 		    			    	    
		$this->packagingTypes = $this->dbClass
		    ->fetch_results(
		      "select products_packaging_type_id, products_packaging_type_name from products_packaging_types", 
		      '',
		      'products_packaging_type_id'
		    );
		    
		//  Generate base SQL, this will be added to by the filters.
		$this->sql = 'SELECT ' .
            'p.products_id,' .
            'p.products_status,' .
            'p.products_model,' .
            'p.products_quantity,' .
            'p.products_price,' .
            'p.products_image,' .
            'p.products_weight,' .
			'p.products_date_added,' .
            'p.products_tax_class_id,' .
		    'p.products_last_modified,' .
		    'p.products_date_added,' .
		    'l.code,' .
            'pd.language_id,' .
            'p.products_distribution,' .
            'p.products_always_on_hand,' .
            'p.products_weight,' .

            'TRIM(CONCAT_WS(\' \',pd.products_name_prefix, pd.products_name, pd.products_name_suffix)) as products_name,';
		
        foreach ($this->getExtraImages() as $field)
        	$this->sql .= 'p.' . $field . ',';
        	
        foreach ($this->getCustomFields() as $field)
        	$this->sql .= $field . ',';

        $this->sql .= 
            ' pd.products_description ' .        	
            $this->_getFromClause() .	
            ' ORDER BY p.products_last_modified';
        
        if($this->firstResult >= 0 && $this->maxResults >= 0)
        	$this->sql .= " LIMIT {$this->firstResult}, {$this->maxResults}";
        else if($this->maxResults >= 0)
        	$this->sql .= " LIMIT {$this->maxResults}";
        
		$this->dbQuery = $this->dbClass->query($this->sql);
	}
	
	function getTotalResults() {
		global $abxDatabase;
		$this->dbClass = &$abxDatabase;

		$query = $this->dbClass->query('select count(*) as count ' . $this->_getFromClause());
		$result = $query->next();
		return $result['count'];
	}
	
	function addProductIdFilter($filter = null) {
		$this->productId = $filter;
		return $this;
	}
	
	function addSkuFilter($filter = null) {
		$this->sku = $filter;
		return $this;
	}	
	
    function addDateRangeFilter($timeStart, $timeEnd)
    {
		$this->timeStart = $timeStart;
		$this->timeEnd = $timeEnd;
		return $this;      
    }	
    
    function isValid()
    {
      return ($this->timeStart || $this->timeEnd || $this->productId || $this->sku) ? true : false;
    }
	
	function addUpdatedAfterFilter ($filter = null) {
		$this->updatedAfter = $filter;
		return $this;
	}
	
	function setFirstResult ($firstResult) {
		$this->firstResult = $firstResult;
		return $this;
	}	
	
	function setMaxResults ($maxResults) {
		$this->maxResults = $maxResults;
		return $this;
	}	
	
	function next() {

		if (!is_object($this->dbQuery)) {
			$this->init();
		}
		
		if ($products = $this->dbQuery->next()) {
			
			$result = array(
	        	'itemLocale'  		=> $products['code'],
	        	'itemLink' 			=> HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?products_id=' . $products['products_id'] . '&language=' . $products['code'],
	        	'itemStatus' 		=> $products['products_status'],
	        	'itemId' 			=> $products['products_id'],
	        	'itemSku' 			=> $products['products_model'],
	        	//'itemModel' 		=> $products['products_model'],
	        	'itemCurrency' 		=> DEFAULT_CURRENCY,
	        	'itemPrice' 		=> $products['products_price'],
	        	'itemImage' 		=> $this->getLargeImage($products),
	        	'itemThumbnail'		=> HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $products['products_image'],
	        	'itemQuantity' 		=> $products['products_quantity'],
	        	'itemTitle' 		=> $products['products_name'],
			    'itemWeight'		=> $products['products_weight'],
	        	'itemDescription' 	=> $products['products_description'],
	        	'itemCategoryId'    => $products['categories_id'],
	        	'itemCategoryParentId'  => $products['parent_id'],			
	        	'itemCategoryLabel' => $products['categories_name'],
			'itemCreatedOn' 	=> gmDate("Y-m-d\TH:i:s\Z", strtotime($products['products_date_added'])),
	                'itemUpdatedOn' 	=> gmDate("Y-m-d\TH:i:s\Z", strtotime($products['products_last_modified'])),
			
			'products_media_type_name' => $this->mediaTypes[$products['products_media_type_id']]['products_media_type_name'],
			'products_video_format_name' => $this->videoFormats[$products['products_video_format_id']]['products_video_format_name'],
			'products_audio_name' => $this->audioFormats[$products['products_audio_id']]['products_audio_name'],
			'products_aspect_ratio_name' => $this->aspectRatios[$products['products_aspect_ratio_id']]['products_aspect_ratio_name'],
			'products_region_code_desc' => $this->regionCodes[$products['products_region_code_id']]['products_region_code_desc'],
			'products_set_type_name' => $this->setTypes[$products['products_set_type_id']]['products_set_type_name'],
			'products_packaging_type_name' => $this->packagingTypes[$products['products_packaging_type_id']]['products_packaging_type_name'],
        	);

	    if($products['products_distribution'] == 1) {
                           $result['itemQuantity'] = 100;
                    } elseif($products['products_always_on_hand'] == 1) {
                           $result['itemQuantity'] = 50;
                    }
	      			
//if($result['products_packaging_type_name'] !== null) {var_dump($result); exit;		}
//echo $products['products_packaging_type_id']; exit;
		
        		
            if(is_array($this->getCustomFields()))
		    {
                foreach($this->getCustomFields() as $field)
				{
                  // If attributes contain '.', then strip these.  They are only used as table prefixes
                  (($pos = strpos($field, '.')) !== false) ? $field = substr($field, $pos+1) : $field;		 
                  $result[$field] = $products[$field];
                }
            }
     		if(is_array($this->getExtraImages()))
		    {
			   foreach($this->getExtraImages() as $field)
			   {
			     if(isset($products[$field]) && strlen($products[$field]) > 0)
			        $result[$field] = HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $products[$field];
			   }
			}
        	return $result;
			
		} else {
			
			return false;
		}
		
  	} // next()
  	
  	function getLargeImage($product)
  	{
  		if(strlen($product['products_image_lrg']) > 0)
  			return HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $product['products_image_lrg'];
  		if(strlen($product['products_image_med']) > 0)
  			return HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $product['products_image_med'];
  		if(strlen($product['products_image']) > 0)
  			return HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $product['products_image'];
  			
  		return null;
  	}

	/*
		Function modified to order extra fields by priority
	*/
	function getExtraImages() {
		return array(
	        'products_image_med',
	        'products_image_lrg',
	        'products_image_sm_1',
	        'products_image_xl_1',
	        'products_image_sm_2',
	        'products_image_xl_2',
	        'products_image_sm_3',
	        'products_image_xl_3',
	        'products_image_sm_4',
	        'products_image_xl_4',
	        'products_image_sm_5',
	        'products_image_xl_5',
	        'products_image_sm_6',
	        'products_image_xl_6',
		);
	}
  

	function setCustomFields($array)	{
	   $this->customFields = $array;
	}
	
	function getCustomFields() {
		return $this->customFields;
	}
	
  	function getImages(&$product) {
  		
  		$i=1;
  		$itemImages = array();
  		
  		foreach ($this->getExtraImages() as $image) {
  			if ($product[$image]) {
  				$itemImages[$i]['url'] = htmlentities(HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $product[$image]);	
  				$itemImages[$i]['priority'] = $i;
  				$i++;
  			}
  		}
  		
  		if ($i == 1) {  // no extra images found
	  		$itemImages[$i]['url'] = htmlentities(HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $product['products_image']);
	  		$itemImages[$i]['priority'] = $i;
  		}
  		
  		return $itemImages;
  	}
  	
  	function getCategories()
  	{
      $categories = array();
      $categories = abx_get_categories($categories);
      return $categories;
  	}
/*	
	function getCategories($productId, $languageId)
    {

        // This variable will persist through the session
        // so that we don't look up same category more than once...
        // as that could be expensive!
        static $categories = array();   // (category id-language id => category path)
           
        $strQuery = 'SELECT cd.categories_id, cd.language_id, cd.categories_name FROM categories_description cd, products_to_categories p2c WHERE cd.categories_id = p2c.categories_id AND p2c.products_id =' . (int)$productId . ' AND cd.language_id = ' . (int)$languageId;
        $query = $this->dbClass->query($strQuery);
        while ($categoryResult = $query->next()) {
	        
	        $categoryId = $categoryResult['categories_id'];
	        if(!isset($categories[$categoryId.'-'.$languageId]))
	        {
	            $categories[$categoryId.'-'.$languageId] = implode('/',array_reverse($this->getParentCategory(null, $categoryId, $languageId)));
	        }
	        $categoriesPath[] = htmlentities($categories[$categoryId.'-'.$languageId]);
        }
        
        return $categoriesPath;
    }

    function getParentCategory($categories_array, $categoryId, $languageId, $return_ids = false)
    {
    
        if (!is_array($categories_array)) 
        	$categories_array = array();

        $strQuery = "select c.parent_id, c.categories_id, cd.categories_name" 
                    . " from " 
                    . TABLE_CATEGORIES . " c, " 
                    . TABLE_CATEGORIES_DESCRIPTION . " cd, " 
                    . TABLE_LANGUAGES . " l " 
                    . " where c.categories_id = cd.categories_id" 
                    . " and cd.language_id = l.languages_id" 
                    . " and c.categories_id = " . (int)$categoryId  
                    . " and cd.language_id = " . (int)$languageId
                    . " order by c.sort_order, cd.categories_name LIMIT 1";
        
        $query = $this->dbClass->query($strQuery);
        while ($categories = $query->next()) {

            $categories['categories_name'] = str_replace("&", " and " , $categories['categories_name']);
            if ($return_ids == false)$categories_array[] = htmlspecialchars($categories['categories_name']);
            else $categories_array[] = htmlspecialchars($categories['categories_id']);
    
            if ($categories['parent_id'] != 0) {
                $categories_array = $this->getParentCategory($categories_array, $categories['parent_id'], $languageId, $return_ids);
            }
        }
    
        return $categories_array;
    }
*/    
} // abxFeedProducts class
?>