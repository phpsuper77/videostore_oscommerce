<?php
	header("Content-Type: Application/text");
	header("Content-Disposition: attachment;filename=yahoofeedfile.txt");

	require('includes/application_top.php');


	$query = "SELECT  pd.products_url, pd.products_name , pd.products_description , p.series_id,"
	               ." p.products_price , p.products_image_med , pd.products_name_prefix ,"
	               ." p.products_id ,  p.products_isbn , p.products_upc ,products_name_suffix ,"
	               ." p.products_quantity , p.products_model ,p.products_release_date ,p.producers_id "
	        ."FROM products p , products_description pd  "
	        ."WHERE p.products_id = pd.products_id  ";
	        //."AND p.products_date_added >= DATE_SUB(CURDATE() ,INTERVAL 90 DAY)";

    $result = tep_db_query($query);


 	$header = "code\t";
    $header.= "name\t";
	$header.= "description\t";
	$header.= "price\t";
	$header.= "product-url\t";
	$header.= "medium\t";
	$header.= "image-url\t";
	$header.= "upc\t";
	$header.= "isbn\t";
	$header.= "in-stock\t";
	$header.= "brand\t";
	$header.= "manufacturer\t";
	$header.= "manufacturer-part-no\t";
	$header.= "model-no\t";
	$header.= "ean\t";
	$header.= "classification\t";
	$header.= "condition\t";
	$header.= "sale-price\t";
	$header.= "msrp\t";
	$header.= "availability\t";
	$header.= "promo-text\n";
	echo $header;



	while($result_row = mysql_fetch_array($result))
	{
       //special price
        $query = "select specials_new_products_price from specials where products_id =".$result_row['products_id'];
        $sp_result = tep_db_query($query);
    	$sp_result_row = tep_db_fetch_array($sp_result);

        //Product type
		if(strstr($result_row['products_model'],"-VHS-"))
			$medium = "VHS";
		else if(strstr($result_row['products_model'],"-DVD-"))
			$medium = "DVD";
		else if(strstr($result_row['products_model'],"-CD-"))
			$medium = "CD";


        //instock
		if($result_row['products_quantity'] >= '1')
			$instock = "Y";
		else
			$instock = "Y";

		//brand
		$brand_query = "select series_name from series where series_id =".$result_row['series_id'];
		$brand_result = tep_db_query($brand_query);
		$brand_result_row = tep_db_fetch_array($brand_result);

		//manufacturer
		$manu_query = "select producers_name from producers where producers_id =".$result_row['producers_id'];
		$manu_result = tep_db_query($manu_query);
		$manu_result_row = tep_db_fetch_array($manu_result);

		//category
		if (!(isset($medium)))
		{
			$categoryname = "\t";
		}
		else  //Check whether the product is DVD/VHS
		{
			if ($medium == 'VHS')
				$categoryname = "DVD and Video";
			elseif ($medium == 'DVD')
				$categoryname = "DVD and Video";
			elseif ($medium == 'CD')
				$categoryname = "Music";
			else
				$categoryname = "\t";
		}
		#########################
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
		#########################

		//ean
		if(strlen($result_row['products_upc'])>=13)
		$ean=$result_row['products_upc'];

		//product description
		$product_description = $result_row['products_description'];
		$product_description = preg_replace("/(\r\n|\n|\r)/", "<br>", $product_description);
   		$product_description = strip_tags($product_description);

		//Replacing the Special characters with null character
   		$str = "&#39";
   		$product_description = str_replace($str, "", $product_description);
   		$str = "&#34";
   		$product_description = str_replace($str, "", $product_description);
   		$str = "&#183";
   		$product_description = str_replace($str, "", $product_description);

		echo $result_row['products_model']."\t";

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

		echo $result_row['products_name_prefix']." ".$result_row['products_name']." ".$result_row['products_name_suffix']."\t";
		echo $product_description."\t";
		if($sp_result_row['specials_new_products_price'] >= '1')
		{
			echo round($sp_result_row['specials_new_products_price'],2)."\t";
		}
		else
		{
			echo round($result_row['products_price'],2)."\t";
		}
		echo "http://www.travelvideostore.com/product_info.php?products_id=".$result_row['products_id']."&ref=115124\t";
		echo $medium."\t";
		if($result_row['products_image_med'] !="")
			echo "http://www.travelvideostore.com/images/".$result_row['products_image_med']."\t";
		else
			echo "\t";
		echo $result_row['products_upc']."\t";
		echo $result_row['products_isbn']."\t";
		echo $instock."\t";


		//Replacing the special characters with spaces
		$str = "&#39";
		$brand_result_row['series_name'] = str_replace($str, "", $brand_result_row['series_name']);

		$str = "&#34";
		$brand_result_row['series_name'] = str_replace($str, "", $brand_result_row['series_name']);

		$str = "&#183";
		$brand_result_row['series_name'] = str_replace($str, "", $brand_result_row['series_name']);
		echo $brand_result_row['series_name']."\t";

		//Replacing the special characters with spaces in manufacturer name
		$str = "&#39";
		$manu_result_row['producers_name'] = str_replace($str, "", $manu_result_row['producers_name']);

		$str = "&#34";
		$manu_result_row['producers_name'] = str_replace($str, "", $manu_result_row['producers_name']);

		$str = "&#183";
		$manu_result_row['producers_name'] = str_replace($str, "", $manu_result_row['producers_name']);

		echo $manu_result_row['producers_name']."\t";
		echo $result_row['manufacturers_id']."\t";
		echo $result_row['products_model']."\t";
		echo $ean."\t";
		echo "New\t";
		echo "New\t";
		if($sp_result_row['specials_new_products_price'] >= '1')
		{
			echo round($sp_result_row['specials_new_products_price'],2)."\t";
		}
		else
		{
			echo "\t";
		}

		//  echo round($sp_result_row['specials_new_products_price'],2)."\t";
		echo round($result_row['products_price'],2)."\t";
		echo "1\t";
		echo "More Travel Videos to More Places - Free Shipping and Free Video Clip Samples\n";



  }
?>
