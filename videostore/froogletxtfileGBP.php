<?php
header("Content-Type: Application/text");
header("Content-Disposition: attachment;filename=travelvideostoreuk.txt");

	require('includes/application_top.php');


$rt = tep_db_fetch_array(tep_db_query("select value from currencies where code='GBP' limit 1"));

	$query = "SELECT  pd.products_head_keywords_tag, pd.products_url, pd.products_name , pd.products_head_keywords_tag, pd.products_description , "
	               ." p.products_price , p.products_image_lrg , pd.products_name_prefix ,"
	               ." p.products_id ,  p.products_isbn , p.products_upc ,products_name_suffix ,"
	               ." p.products_quantity , p.products_audio_languages  , p.products_model ,p.products_release_date ,p.series_id,"
	               ." p.manufacturers_id, p.products_distribution , p.products_weight , p.products_status, p.products_media_type_id , p.products_set_type_id , p.products_video_format_id , p.products_packaging_type_id , p.products_audio_id, p.products_aspect_ratio_id, p.products_run_time, p.products_region_code_id "
	        ."FROM products p , products_description pd "
	        ."WHERE p.products_id = pd.products_id AND p.products_status=1 AND p.products_upc >0";
	        //."AND p.products_date_added >= DATE_SUB(CURDATE() ,INTERVAL 90 DAY)";

    $result = tep_db_query($query);

                $header = "link\t";
                $header.= "title\t";
	$header.= "description\t";
	$header.= "price\t";
	$header.= "image_link\t";
	$header.= "id\t";
                $header.= "feature\t";
	$header.= "availability\t";
	$header.= "upc\t";
	$header.= "isbn\t";
	$header.= "product_type\t";
	$header.= "year\t";
	$header.= "genre\t";
	$header.= "brand\t";
	$header.= "manufacturer\t";
	$header.= "currency\t";
	$header.= "mpn\t";
                $header.= "tax\t";
	$header.= "shipping_weight\t";
	$header.= "quantity\t";
                $header.= "content_language\t";
	$header.= "condition\n";
	//$header.= "expiration_date\n";
	echo $header;


	while($result_row = mysql_fetch_array($result))
	{
         $feature = " ";
         // Product Media Type
if (tep_not_null($result_row['products_media_type_id'])) { 
        $query = "select products_media_type_name from products_media_types  where products_media_type_id =".$result_row['products_media_type_id'];
        $mt = tep_db_query($query);
    	$media_type = tep_db_fetch_array($mt);
        $feature .= $media_type['products_media_type_name']. " ";
        }
         // Product Set Type
if (tep_not_null($result_row['products_set_type_id'])) {
        $feature = " "; 
        $query = "select products_set_type_name from products_set_types  where products_set_type_id =".$result_row['products_set_type_id'];
        $mt = tep_db_query($query);
    	$media_type = tep_db_fetch_array($mt);
        $feature .= $media_type['products_set_type_name']. " ";
        }
         // Product Video Format
if (tep_not_null($result_row['products_video_format_id'])) { 
        $query = "select products_video_format_name from products_video_formats where products_video_format_id =".$result_row['products_video_format_id'];
        $mt = tep_db_query($query);
    	$media_type = tep_db_fetch_array($mt);
        $feature .= $media_type['products_video_format_name']. " ";
        }
         // Product Packaging Type
if (tep_not_null($result_row['products_packaging_type_id'])) { 
        $query = "select products_packaging_type_name from products_packaging_types where products_packaging_type_id =".$result_row['products_packaging_type_id'];
        $mt = tep_db_query($query);
    	$media_type = tep_db_fetch_array($mt);
        $feature .= "Packaging Type: ". $media_type['products_packaging_type_name']. " ";
        }
         // Product Region Type
if (tep_not_null($result_row['products_region_code_id'])) { 
        $query = "select products_region_code_name , products_region_code_desc from products_region_codes where products_region_code_id =".$result_row['products_region_code_id'];
        $mt = tep_db_query($query);
    	$media_type = tep_db_fetch_array($mt);
        if ($media_type['products_region_code_name'] == "Worldwide") {
        $feature .= "Region (0) ". $media_type['products_region_code_desc']. " ";
         } else {
        $feature .= $media_type['products_region_code_name']. " ". $media_type['products_region_code_desc']. " "; }
        }
         // Product Audio Type
if (tep_not_null($result_row['products_audio_id'])) { 
        $query = "select products_audio_name from products_audios where products_audio_id =".$result_row['products_audio_id'];
        $mt = tep_db_query($query);
    	$media_type = tep_db_fetch_array($mt);
        $feature .= $media_type['products_audio_name']. " ";
        }
         // Product Aspect Ratio
if (tep_not_null($result_row['products_aspect_ratio_id'])) { 
        $query = "select products_aspect_ratio_name from products_aspect_ratios where products_aspect_ratio_id =".$result_row['products_aspect_ratio_id'];
        $mt = tep_db_query($query);
    	$media_type = tep_db_fetch_array($mt);
        $feature .= $media_type['products_aspect_ratio_name']. " ";
        }
         // Product Languages
if (tep_not_null($result_row['products_audio_languages'])) { 
        $feature .= $result_row['products_audio_languages']. " ";
        }
         // Product Run Time
if (tep_not_null($result_row['products_run_time'])) { 
        $feature .= "Run Time:".$result_row['products_run_time'];
        }
        //special price
        $query = "select specials_new_products_price from specials where products_id =".$result_row['products_id'];
        $sp_result = tep_db_query($query);
    	$sp_result_row = tep_db_fetch_array($sp_result);

        //Product type
		if(strstr($result_row['products_model'],"VHS"))
			$medium = "VHS";
		else if(strstr($result_row['products_model'],"DVD"))
			$medium = "DVD";

        //instock
					
		if($result_row['products_distribution'] >= '1')  
			$quantity = $result_row['products_quantity'] + 500;
                              else
                                              $quantity = $result_row['products_quantity'];
		
		if($quantity >= '1')
			$instock = "in stock";
		else
			$instock = "out of stock";


		//brand
		$brand_query = "select series_name from series where series_id =".$result_row['series_id'];
		$brand_result = tep_db_query($brand_query);
		$brand_result_row = tep_db_fetch_array($brand_result);

		//category
		#$categoryname = $result_row['products_model'];
		if (!(isset($medium)))
		{
			$categoryname = "\t";
		}
		else  //Check whether the product is DVD/VHS
		{
			if ($medium == 'VHS')
				$categoryname = "Media > VHS";
			elseif ($medium == 'DVD')
				$categoryname = "Media > DVDs & Video > Travel";
			else
				$categoryname = "\t";
		}

		###############################
		#$catg_query = "select categories_id from products_to_categories where products_id = ".$result_row['products_id'];
		#$catg_result = tep_db_query($catg_query);
		#$catg_id ="";
		#while($catg_result_row = mysql_fetch_array($catg_result))
		#{
		#	$catg_id.=",".$catg_result_row['categories_id'];
		#}
		#$catg_id =substr(ltrim($catg_id),1);

		#$c_query = "select categories_name from categories_description where categories_id in ($catg_id)";
		#$c_result = tep_db_query($c_query);
		#$categoryname ="";
		#while($category = mysql_fetch_array($c_result))
		#{
		#	$categoryname.=">".$category['categories_name'];
		#}
		#$categoryname =substr(ltrim($categoryname),1);
		###############################

   		//product description

		$strip_desc = $result_row['products_description'];
                              $str = "<br>";
		$strip_desc = str_replace($str, " ", $strip_desc);
                              $str = "<p>";
		$strip_desc = str_replace($str, " ", $strip_desc);

                              $str = "<P>";
		$strip_desc = str_replace($str, " ", $strip_desc);
                              $str = "<BR>";
		$strip_desc = str_replace($str, " ", $strip_desc);

		$strip_desc = preg_replace("/(\r\n|\n|\r)/", "<br>", $strip_desc);
   		$strip_desc = strip_tags($strip_desc);


   		//Replacing the Special characters with null character
                                $str = "&nbsp;";
		$strip_desc = str_replace($str, " ", $strip_desc);
		$str = "!";
		$strip_desc = str_replace($str, ". ", $strip_desc);
		$str = "&#39";
		$strip_desc = str_replace($str, "", $strip_desc);
		$str = "&#34";
		$strip_desc = str_replace($str, "", $strip_desc);
		$str = "&#183";
   		$strip_desc = str_replace($str, "*", $strip_desc);
		$str = "&#198";
   		$strip_desc = str_replace($str, "ae", $strip_desc);
                                $str = "&#150";
		$strip_desc = str_replace($str, "-", $strip_desc);
                                $str = "&#257";
		$strip_desc = str_replace($str, "a", $strip_desc);                                
                                $str = "&#268";
		$strip_desc = str_replace($str, "C", $strip_desc);
                                $str = "&#269";
		$strip_desc = str_replace($str, "C", $strip_desc);
                                $str = "&#283";
		$strip_desc = str_replace($str, "e", $strip_desc);                               
                                $str = "&#287";
		$strip_desc = str_replace($str, "g", $strip_desc);
                                $str = "&#7929";
		$strip_desc = str_replace($str, "y", $strip_desc);
                                $str = "&#259";
		$strip_desc = str_replace($str, "a", $strip_desc);
                                $str = "&#365";
		$strip_desc = str_replace($str, "C", $strip_desc);
                                $str = "&#324";
		$strip_desc = str_replace($str, "n", $strip_desc);                               
                                $str = "&#337";
		$strip_desc = str_replace($str, "o", $strip_desc);
                                $str = "&#417";
		$strip_desc = str_replace($str, "o", $strip_desc);

 if(strlen($strip_desc) > 5000) {
          $strip_desc = substr($strip_desc, 0, 5000);
          $append='...';
          $strip_desc .= $append;     }




		// echo "http://www.travelvideostore.com/product_info.php?products_id=".$result_row['products_id']."&ref=115119\t";

echo tep_href_link('product_info.php','products_id='.$result_row[products_id])."?ref=115119&currency=GBP\t";

		//Changing Special characters in item_name
		$str = "&#39";
		$result_row['products_name_prefix'] = str_replace($str, "", $result_row['products_name_prefix']);
		$result_row['products_name'] = str_replace($str, "", $result_row['products_name']);
		$result_row['products_name_suffix'] = str_replace($str, "", $result_row['products_name_suffix']);

		$str = "&#34";
		$result_row['products_name_prefix'] = str_replace($str, "", $result_row['products_name_prefix']);
		$result_row['products_name'] = str_replace($str, "", $result_row['products_name']);
		$result_row['products_name_suffix'] = str_replace($str, "", $result_row['products_name_suffix']);

		$str = "&#183";
		$result_row['products_name_prefix'] = str_replace($str, "", $result_row['products_name_prefix']);
		$result_row['products_name'] = str_replace($str, "", $result_row['products_name']);
		$result_row['products_name_suffix'] = str_replace($str, "", $result_row['products_name_suffix']);

		//Changing Special characters in Brand_name
		$str = "&#39";
		$brand_result_row['series_name'] = str_replace($str, "", $brand_result_row['series_name']);

		$str = "&#34";
		$brand_result_row['series_name'] = str_replace($str, "", $brand_result_row['series_name']);

		$str = "&#183";
		$brand_result_row['series_name'] = str_replace($str, "", $brand_result_row['series_name']);

$title = " ";
		if($brand_result_row['series_name'] !="") 
			$title .= $brand_result_row['series_name']." ";

        if($result_row['products_name_prefix'] !="") 
			$title .= $result_row['products_name_prefix']." ";
								
        if($result_row['products_name'] !="")  
			$title .= $result_row['products_name'];
		
        if($result_row['products_name_suffix'] !="")  
			$title .= " ".$result_row['products_name_suffix'];
 if(strlen($title) > 70) {
          $title = substr($title, 0, 70);
    }
 if(strlen($title) < 56) {
          $title = substr($title, 0, 5000);
          $append=' Travel Videos';
          $title.= $append;     }
                                echo $title."\t";

		echo $strip_desc."\t";

		if($sp_result_row['specials_new_products_price'])
		{
                                                $sp_result_row['specials_new_products_price'] = $sp_result_row['specials_new_products_price'] * $rt[value];
			echo round($sp_result_row['specials_new_products_price'],2)."\t";

		}
		else
		{
                                              $result_row['products_price'] = $result_row['products_price'] * $rt[value];
			echo round($result_row['products_price'], 2)."\t";

		}
		if($result_row['products_image_lrg'] !="")
			echo "http://www.travelvideostore.com/images/".$result_row['products_image_lrg']."\t";
		else
			echo "\t";
		echo $result_row['products_model']."-GBP\t";
                                echo $feature."\t";
		echo $instock."\t";
$upc =  $result_row['products_upc'];
if (tep_not_null($result_row['products_upc'])) { 
 if(strlen($upc) < 12) {
          $upc = str_pad($upc, 12, "0", STR_PAD_LEFT);
    }
}
		echo $upc."\t";
$isbn =  $result_row['products_isbn'];
if (tep_not_null($result_row['products_isbn'])) { 
 if(strlen($isbn) < 10) {
          $isbn = str_pad($upc, 10, "0", STR_PAD_LEFT);
    }
}
		echo $isbn."\t";

		echo $categoryname."\t";
		echo $result_row['products_release_date']."\t";
		echo "Travel\t";
		echo "TravelVideoStore.com\t";
		echo "TravelVideoStore.com\t";
		echo "GBP\t";
		echo $result_row['products_model']."\t";
		echo "US:FL:7.00:n\t";
		echo $result_row['products_weight']." lbs\t";
		echo $quantity."\t";
                                echo $result_row[products_audio_languages ]."\t";
		echo "NEW\n";

   }

?>