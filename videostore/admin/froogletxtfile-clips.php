<?php
header("Content-Type: Application/text");
header("Content-Disposition: attachment;filename=frooglefeedfile.txt");

	require('includes/application_top.php');

	$query = "SELECT  pd.products_clip_url, pd.products_name , pd.products_description , "
	               ." p.products_price , p.products_image_med , pd.products_name_prefix ,"
	               ." p.products_id ,  p.products_isbn , p.products_upc ,products_name_suffix ,"
	               ." p.products_quantity , p.products_model ,p.products_release_date ,p.series_id,"
	               ." p.manufacturers_id "
	        ."FROM products p , products_description pd  "
	        ."WHERE p.products_id = pd.products_id  ";
	        //."AND p.products_date_added >= DATE_SUB(CURDATE() ,INTERVAL 90 DAY)";

    $result = tep_db_query($query);

    $header = "link\t";
    $header.= "name\t";
	$header.= "description\t";
	$header.= "price\t";
	$header.= "image_link\t";
	$header.= "id\t";
	$header.= "instock\t";
	$header.= "upc\t";
	$header.= "isbn\t";
	$header.= "product_type\t";
	$header.= "format\t";
	$header.= "release_date\t";
	$header.= "category\t";
	$header.= "brand\t";
	$header.= "manufacturer_id \t";
	$header.= "currency \t";
	$header.= "language \t";
	$header.= "ship_from\t";
	$header.= "label\t";
                 $header.= "clip_url\n";
	echo $header;


	while($result_row = mysql_fetch_array($result))
	{
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
		if($result_row['products_quantity'] >= '1')
			$instock = "Y";
		else
			$instock = "N";

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
				$categoryname = "296670";
			elseif ($medium == 'DVD')
				$categoryname = "296568";
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
		$strip_desc = preg_replace("/(\r\n|\n|\r)/", "<br>", $strip_desc);
   		$strip_desc = strip_tags($strip_desc);

   		//Replacing the Special characters with null character
		$str = "&#39";
		$strip_desc = str_replace($str, "", $strip_desc);
		$str = "&#34";
		$strip_desc = str_replace($str, "", $strip_desc);
		$str = "&#183";
   		$strip_desc = str_replace($str, "", $strip_desc);

		echo "http://www.travelvideostore.com/product_info.php?products_id=".$result_row['products_id']."&ref=115119\t";

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

		echo $result_row['products_name_prefix']." ".$result_row['products_name']." ".$result_row['products_name_suffix']." Travel Videos\t";
		echo $strip_desc."\t";
		if($sp_result_row['specials_new_products_price'])
		{
			echo $sp_result_row['specials_new_products_price']."\t";
		}
		else
		{
			echo $result_row['products_price']."\t";
		}
		if($result_row['products_image_med'] !="")
			echo "http://www.travelvideostore.com/images/".$result_row['products_image_med']."\t";
		else
			echo "\t";
		echo $result_row['products_model']."\t";
		echo $instock."\t";
		echo $result_row['products_upc']."\t";
		echo $result_row['products_isbn']."\t";
		echo "Video"."\t";
		echo $medium."\t";
		echo $result_row['products_release_date']."\t";
		echo $categoryname."\t";

		//Changing Special characters in Brand_name
		$str = "&#39";
		$brand_result_row['series_name'] = str_replace($str, "", $brand_result_row['series_name']);

		$str = "&#34";
		$brand_result_row['series_name'] = str_replace($str, "", $brand_result_row['series_name']);

		$str = "&#183";
		$brand_result_row['series_name'] = str_replace($str, "", $brand_result_row['series_name']);

		echo $brand_result_row['series_name']."\t";
		echo $result_row['manufacturers_id']."\t";
		echo "USD"."\t";
		echo "english"."\t";
		echo "Tampa, Florida"."\t";
		echo "NR"."\t";
		echo $result_row['products_clip_url']."\n";
   }
?>



