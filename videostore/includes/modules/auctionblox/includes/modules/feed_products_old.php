<?php
/*
  $Id: feed_products.php,v 1.7 2005/04/12 14:08:11 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004-2008 AuctionBlox
*/
  require_once(DIR_FS_ABX5_FUNCTIONS . 'formatter.php');  
  require_once_cart('includes/classes/abxFeedProducts.php');

  class abxModule_Feed_Products extends abxModule {

    function abxModule_Feed_Products()
    {
        $this->process();
    }

    function process()
    {
//    	if (isset($_GET['sellerID']) && isset($_GET['timestamp']) ) {
//
//          $signature = abxToken::sign(
//            array(
//              $_GET['sellerID'],
//              $_GET['timestamp']
//            ),
//            AUCTIONBLOX_PASSCODE
//          );
//          if (strcasecmp($signature, $_GET['signature']) === 0) {
          	$this->processProducts();
//          } else {
//            echo 'error.password.incorrect';
//          }
//    	} 
//    	else { 
//    	  echo 'You have found the AuctionBlox.com Product Feed URL.';
//          exit;
//          break;
//
//      	}// end if    	
    }
    
    function processProducts()
    {
      $attributes = array(
      	//	'products_ebay_category', 
	//	'products_store_category1_ebay', 
	//	'products_ebay_desc', 
	  'ebay_category_1',
	  'ebay_category_2',
	  'ebay_store_category_1',
	  'ebay_store_category_2',
	  'products_isbn',	
	  'products_upc',	
	  'products_audio_languages',
	  'products_subtitle_languages',
	  'products_closed_captioned',
	  'products_pc_bonus',
	  'products_run_time',
	  'products_head_keywords_tag',
	  'products_head_title_tag',
	  'products_media_type_id',
	  'products_video_format_id',
	  'products_audio_id',
	  'products_aspect_ratio_id',
	  'products_region_code_id',
	  'products_set_type_id',
	  'products_packaging_type_id',
	  'products_youtube_id',
	  'products_release_date',
	  's.series_id',
	  's.series_name',
	  's.series_description',
	  's.series_image',	  
      	);
    
    	$feedData = new abxFeedProducts();
    	$feedData->setCustomFields($attributes);

		$perPage = ABX_FEED_PRODUCTS_MAX_PER_PAGE;
		
    	$timeStart = addslashes($_GET['timeStart']);
	    $timeEnd = addslashes($_GET['timeEnd']);
	    
	    if ($timeStart != '' && $timeEnd != '') {
	      $timeStart = date('Y-m-d H:i:s', parse_iso8601_datetime($timeStart));
	      $timeEnd = date('Y-m-d H:i:s', parse_iso8601_datetime($timeEnd));
	      $feedData->addDateRangeFilter($timeStart, $timeEnd);
	    }

		if($_GET['testTimestamps'])
		{
		  echo 'timeStart in local time = ' . $timeStart . '<br/>';
		  echo 'timeEnd in local time = ' . $timeEnd . '<br/>';
		  exit;
		}
	    
	    
	    $pid = addslashes($_GET['productId']);
	    if ($pid != '') {
	      $feedData->addProductIdFilter((int)$pid);
	    }
	       
	    $param = addslashes($_GET['sku']);
	    if ($param != '') {
	      $feedData->addSkuFilter($param);
	    }
	    
	    $param = addslashes($_GET['perPage']);
	    if ($param != '') {
	      $perPage = $param;
	    }
	    
	    if(!$feedData->isValid())
	    {
	      echo 'Missing necessary parameters';
	      exit;
	    }
	      
	    
	    $tokens = array(
	        '{CHANNEL_TITLE}' 			=> 'AuctionBlox Catalog Datafeed',
	        '{CHANNEL_DESCRIPTION}' 	=> 'AuctionBlox Catalog Datafeed',
	        '{CHANNEL_LINK}' 			=> htmlentities($this->getCurrentURL()),
	        '{CHANNEL_VERSION}' 		=> '1.0',
	        '{PAGE_NUMBER}' 			=> ($start/$perPage) + 1,
	        '{PAGE_SIZE}' 				=> $perPage,
	        '{TOTAL_RESULTS}' 			=> $feedData->getTotalResults(),
	    );

	    $keys = array_keys($tokens);
	    $values = array_values($tokens);
	    
	    ob_start();        
  
	    header("Content-type: application/xml; charset=utf-8;");
	    header("Cache-Control: no-store, no-cache");

	    $start = 0;
        $end = $perPage;
        	    
	    if(isset($_GET['page']))
	    {
	      $start = ((int)($_GET['page']) - 1) * $perPage;
	      $end = $start + $perPage;
	    }
	    
	    $feedData->setFirstResult($start);
	    $feedData->setMaxResults($end - $start);
	    	
	    echo str_replace($keys, $values, $this->getRssStart());
	    	  
	    // Multiple rows for locale
	    $lastProduct = -1;
	    while($row = $feedData->next()) 
	    {
//var_dump($row); exit;		
	    
	        $str = '';
	        

		$title = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row['itemTitle']);		
		$desc = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row['itemDescription']);		
		
			    
			    // Uncomment if necessary
			    //$desc = strip_selected_tags($desc, '<scr'.'ipt>', true);
						        
	        $tokens = array(
	            '{ITEM_LOCALE}' 		=> $row['itemLocale'],
	            '{ITEM_LINK}' 			=> htmlentities($row['itemLink']),
	            '{ITEM_STATUS}' 		=> $row['itemStatus'],
	            '{ITEM_ID}' 			=> $row['itemId'],
	            '{ITEM_SKU}' 			=> $row['itemSku'],
	            '{ITEM_CREATED_ON}' 	=> $row['itemCreatedOn'],
	            '{ITEM_UPDATED_ON}' 	=> $row['itemUpdatedOn'],
	            '{ITEM_CURRENCY}' 		=> $row['itemCurrency'],
	            '{ITEM_PRICE}' 			=> $row['itemPrice'],
	            '{ITEM_WEIGHT}' 		=> $row['itemWeight'],
	            '{ITEM_QUANTITY}' 		=> $row['itemQuantity'],
	            '{ITEM_TITLE}' 			=> htmlentities($title),
	            '{ITEM_DESCRIPTION}' 	=> $desc,
	        );
	
	        $keys = array_keys($tokens);
	        $values = array_values($tokens);
	
//	        if($lastProduct !== (int)$row['itemId'])
	        {
	            if($lastProduct != -1)  // not first time
	              $str .= str_replace($keys, $values, $this->getItemElementEnd());
	            
	            $str .= str_replace($keys, $values, $this->getItemElementStart());
	            
	            if($_GET['detailLevel'] !== 'basic') {
	              $str .= str_replace($keys, $values, $this->getItemExtraElement());
	              $str .= str_replace('{ITEM_THUMBNAIL}', htmlentities($row['itemThumbnail']), $this->getItemThumbnailElement());
	              $str .= str_replace('{ITEM_IMAGE}', htmlentities($row['itemImage']), $this->getItemImageElement());
	              
  	         	  if(is_array($feedData->getExtraImages()))
        		  {
        			foreach($feedData->getExtraImages() as $image)
        			{
        			  $str .= "<abx:$image>" . htmlentities($row[$image]) . "</abx:$image>";
        			}
                  }	              
	            }
	            
/*	            
	            if($lastProduct == -1)
	            {
	              $str .= '<abx:marketplace type="EBAY">
	                         <abx:action>DELIST</abx:action>
	                         <abx:template>eBay US Fixed Price</abx:template>
	                       </abx:marketplace>
	                       <abx:marketplace type="AMAZON">
	                         <abx:action>LIST</abx:action>
	                         <abx:template>Amazon US Book Template</abx:template>
	                       </abx:marketplace>';
	            }
*/
	        }
	        
	        if($_GET['detailLevel'] !== 'basic')
	        {
	          //locale-specific
	          $str .= str_replace($keys, $values, $this->getItemTitleElement());
	          $str .= str_replace($keys, $values, $this->getItemDescriptionElement());
	        } 
	        
	        $lastProduct = (int)$row['itemId'];
	        
		
		if(is_array($attributes))
    { 
		  foreach($attributes as $attribute)
		  {
		    // If attributes contain '.', then strip these.  They are only used as table prefixes
				(($pos = strpos($attribute, '.')) !== false) ? $attribute = substr($attribute, $pos+1) : $attribute;		 
		  
		    $str .= "<abx:$attribute>" . htmlentities($row[$attribute]) . "</abx:$attribute>";
	    }
		    if(isset($row['products_media_type_name'])) $str .= '<abx:products_media_type_name>' .  htmlentities($row['products_media_type_name']) . '</abx:products_media_type_name>';
		    if(isset($row['products_video_format_name'])) $str .= '<abx:products_video_format_name>' .  htmlentities($row['products_video_format_name']) . '</abx:products_video_format_name>';
		    if(isset($row['products_audio_name'])) $str .= '<abx:products_audio_name>' .  htmlentities($row['products_audio_name']) . '</abx:products_audio_name>';
		    if(isset($row['products_aspect_ratio_name'])) $str .= '<abx:products_aspect_ratio_name>' .  htmlentities($row['products_aspect_ratio_name']) . '</abx:products_aspect_ratio_name>';
		    if(isset($row['products_region_code_desc'])) $str .= '<abx:products_region_code_desc>' .  htmlentities($row['products_region_code_desc']) . '</abx:products_region_code_desc>';
		    if(isset($row['products_set_type_name'])) $str .= '<abx:products_set_type_name>' .  htmlentities($row['products_set_type_name']) . '</abx:products_set_type_name>';
		    if(isset($row['products_packaging_type_name'])) $str .= '<abx:products_packaging_type_name>' .  htmlentities($row['products_packaging_type_name']) . '</abx:products_packaging_type_name>';
		    
		}
		
		
		
	        
		echo $str;
	    }
	
	    if($lastProduct !== -1)
	        echo str_replace($keys, $values, $this->getItemElementEnd());
	    
	    echo $this->getRssEnd();
	    
	    ob_end_flush();  
	    exit;          	

    }
    
    function getProductTemplate()
    {
        
    }

    function getRssStart()
    {
       return '<?xml version="1.0" encoding="UTF-8"?>
              <feed xmlns="http://www.w3.org/2005/Atom"
              	  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              	  xmlns:abx="http://www.auctionblox.com/services/feeds/catalog"
              	  xsi:schemaLocation=
              	    "http://www.auctionblox.com/services/feeds/catalog http://www.auctionblox.com/services/feeds/catalog/1.0/product_1_0.xsd">
              	<id>{CHANNEL_LINK}</id>
              	<title>{CHANNEL_TITLE}</title>
              	<author><name>AuctionBlox</name></author>
              	<link href="{CHANNEL_LINK}" />
              	<abx:version>2.0</abx:version>
              	<abx:pageNumber>{PAGE_NUMBER}</abx:pageNumber>
              	<abx:pageSize>{PAGE_SIZE}</abx:pageSize>
              	<abx:totalResults>{TOTAL_RESULTS}</abx:totalResults>';
    }
    
    function getItemElementStart()
    {
        return '<entry>' .
               '  <abx:id>{ITEM_ID}</abx:id>' .
               '  <abx:status>{ITEM_STATUS}</abx:status>' .
               '  <abx:sku>{ITEM_SKU}</abx:sku>' .
               '  <abx:updatedOn>{ITEM_UPDATED_ON}</abx:updatedOn>' .
               '  <abx:createdOn>{ITEM_CREATED_ON}</abx:createdOn>' .
               '  <abx:quantity>{ITEM_QUANTITY}</abx:quantity>';
    }
    
    function getItemExtraElement()
    {
        return '  <title>{ITEM_TITLE}</title>' .
               '  <link>{ITEM_LINK}</link>' .
               '  <description><![CDATA[{ITEM_DESCRIPTION}]]></description>' .
               '  <abx:locale>{ITEM_LOCALE}</abx:locale>' .
               '  <abx:weight>{ITEM_WEIGHT}</abx:weight>' .
               '  <abx:price currency="{ITEM_CURRENCY}">{ITEM_PRICE}</abx:price>';
    	
    }
    
    function getItemImageElement()
    {
    	return '  <abx:image>{ITEM_IMAGE}</abx:image>';
    }
    
    function getItemThumbnailElement()
    {
    	return '  <abx:thumbnail>{ITEM_THUMBNAIL}</abx:thumbnail>';
    }
    
    function getItemTitleElement()
    {
        return '  <abx:title locale="{ITEM_LOCALE}" format="text/html">{ITEM_TITLE}</abx:title>';
    }

    function getItemDescriptionElement()
    {
				return '  <abx:description locale="{ITEM_LOCALE}" format="text/html"><![CDATA[{ITEM_DESCRIPTION}]]></abx:description>';
    }
    
    function getItemCategoryElement()
    {
        return '  <abx:category id="{ITEM_CATEGORY_ID}" locale="{ITEM_LOCALE}" parent_id="{ITEM_CATEGORY_PARENT_ID}">{ITEM_CATEGORY_LABEL}</abx:category>';
    }

    function getItemElementEnd()
    {
        return '</entry>';
    }
    
    function getRssEnd()
    {
        return '  </feed>';    
    }
    
	function getCurrentURL() {
		$s = empty($_SERVER["HTTPS"]) ? ''
			: ($_SERVER["HTTPS"] == "on") ? "s"
			: "";
			
		$severProtocol = strtolower($_SERVER["SERVER_PROTOCOL"]);
		
		$protocol = substr($severProtocol, 0, strpos($severProtocol, "/"));
		$port = ($_SERVER["SERVER_PORT"] == "80") ? ""
			: (":".$_SERVER["SERVER_PORT"]);
		return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
	}   
  }//end class
function strip_selected_tags($str, $tags = "", $stripContent = false) 
{ 
    preg_match_all("/<([^>]+)>/i", $tags, $allTags, PREG_PATTERN_ORDER); 
    foreach ($allTags[1] as $tag) { 
        $replace = "%(<$tag.*?>)(.*?)(<\/$tag.*?>)%is"; 
        $replace2 = "%(<$tag.*?>)%is"; 
        //echo $replace; 
        if ($stripContent) { 
            $str = preg_replace($replace,'',$str); 
            $str = preg_replace($replace2,'',$str); 
        } 
        $str = preg_replace($replace,'${2}',$str); 
        $str = preg_replace($replace2,'${2}',$str); 
    } 
    return $str; 
} 
?>