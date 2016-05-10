<?php
	header("Content-Type: Application/text");
	header("Content-Disposition: attachment;filename=amazon.txt");

	require('includes/application_top.php');

	$query = "SELECT  p.products_distribution, p.products_always_on_hand, p.products_isbn , p.products_upc , p.products_asin ,p.products_model , p.products_price ,"
	              ."  p.products_id , p.products_byline ,pd.products_name_prefix ,p.products_image_med , "
	              ." pd.products_name , pd.products_description ,pd.products_name_suffix , pd.products_clip_url, p.products_quantity "
	        ."FROM products p , products_description pd  "
	        ."WHERE p.products_id = pd.products_id AND ( ( p.products_quantity >0 ) OR ( p.products_always_on_hand ='1' ) OR ( p.products_distribution ='1' ) ) AND ( ( p.products_upc >'0' ) OR ( p.products_asin >'0') OR ( p.products_isbn >'0' ) ) ";
	        //."AND p.products_date_added >= DATE_SUB(CURDATE() ,INTERVAL 90 DAY)";
    $result = tep_db_query($query);

	$header = "item-is-marketplace\t";
	$header.= "product-id\t";
	$header.= "product-id-type\t";
	$header.= "item-condition\t";
	$header.= "price\t";
	$header.= "sku\t";
	$header.= "item-name\t";
	$header.= "item-description\t";
	$header.= "category1\t";
	$header.= "image-url\t";
	$header.= "quantity\t";
	$header.= "item-note\t";
	$header.= "will-ship-internationally\t";
	$header.= "expedited-shipping\n";

	echo $header;



	while($result_row = mysql_fetch_array($result))
	{
       //special price
        $query = "select specials_new_products_price from specials where products_id =".$result_row['products_id'];
        $sp_result = tep_db_query($query);
    	$sp_result_row = tep_db_fetch_array($sp_result);

		//category
		$categoryname =  "AMAZON-".$result_row['products_model'];
		if (!(isset($categoryname)))
		{
			$categoryname = "";
		}
		else  //Check whether the product is DVD/VHS
		{
			//$category_type = explode("-",$categoryname);
			//$type = $category_type[1];
			//if ($type == 'VHS')
			//	$categoryname = "296670";
			//elseif ($type == 'DVD')
			//	$categoryname = "296568";
			//else
			//	$categoryname = "\t";

			if (strstr($categoryname,"VHS"))
				$categoryname = "296670";
			elseif (strstr($categoryname,"DVD"))
				$categoryname = "296568";
			else
				$categoryname = "";

		}

		##########################
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
		##########################

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

		echo "y"."\t";
		if($result_row['products_asin']!="")
		{
			echo $result_row['products_asin']."\t";
			echo "1"."\t";
		}
			else if($result_row['products_upc']!="")
		{
			echo $result_row['products_upc']."\t";
			if(strlen($result_row['products_upc'])>=13)
				echo "4\t";
			else
				echo "3\t";
		}
                                                else if($result_row['products_isbn']!="")
		{
			echo $result_row['products_isbn']."\t";
			echo "2"."\t";
		}

		else
		{
			echo ""."\t";
			echo ""."\t";
		}
		echo "11"."\t";

		if($sp_result_row['specials_new_products_price'])
		{
			echo $sp_result_row['specials_new_products_price']."\t";
		}
		else
		{
			echo  $result_row['products_price']."\t";
		}
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
		echo $categoryname."\t";
		if($result_row['products_image_med'] !="")
		echo "http://www.travelvideostore.com/images/".$result_row['products_image_med']."\t";
		else
		echo "\t";

if ($result_row['products_distribution']==1)
{
$total_qua = $result_row['products_quantity']+500;
		echo $total_qua."\t";		
}
		else
if ($result_row['products_always_on_hand']==1)
{
$total_qua = $result_row['products_quantity']+0;
		echo $total_qua."\t";		
}
		else
		echo $result_row['products_quantity']."\t";

if ($result_row['products_distribution']==1)
{
echo "Distributor Direct - More Travel Videos to More Places!  Quick Shipping - Sample Clips - Travel Videos is all we do!\t";

}
else
		echo "More Travel Videos to More Places!  Quick Shipping - Sample Clips - Travel Videos is all we do!\t";
		echo "2\t";
		echo "y\n";
	}
?>