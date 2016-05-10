<?php
header("Content-Type: Application/msexcel");
header("Content-Disposition: attachment;filename=frooglefeedfile.xls");

	require('includes/application_top.php');

	$query = "SELECT  pd.products_url, pd.products_name , pd.products_description , "
	               ." p.products_price , p.products_image_med , pd.products_name_prefix ,"
	               ." p.products_id ,  p.products_isbn , p.products_upc ,products_name_suffix ,"
	               ." p.products_quantity , p.products_model ,p.products_release_date "
	        ."FROM products p , products_description pd  "
	        ."WHERE p.products_id = pd.products_id  "
	        ."AND p.products_date_added >= DATE_SUB(CURDATE() ,INTERVAL 90 DAY)";

    $result = tep_db_query($query);
    $result_row = tep_db_fetch_array($result);

?>

<table width="100%" cellSpacing=1 cellPadding=1 border=1>
<tr>

<td align="right"><b>product_url</td>
<td align="right"><b>name</td>
<td align="right"><b>description</td>
<td align="right"><b>price</td>
<td align="right"><b>image_url</td>
<td align="right"><b>offer_id</td>
<td align="right"><b>instock</td>
<td align="right"><b>upc</td>
<td align="right"><b>isbn</td>
<td align="right"><b>product_type</td>
<td align="right"><b>format</td>
<td align="right"><b>release_date</td>

</tr>

<?php

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

		?>
		<tr>

		<td align="right"><? echo "http://www.travelvideostore.com/product_info.php/products_id/".$result_row['products_id'];?></td>
		<td align="right"><? echo $result_row['products_name_prefix']." ".$result_row['products_name']." ".$result_row['products_name_suffix'];?></td>
		<td align="right"><? echo htmlspecialchars($result_row['products_description']);?></td>
	<?	if($sp_result_row['specials_new_products_price'])
		{ ?>
			<td align="right"><? echo $sp_result_row['specials_new_products_price'];?></td>
	<?	}
		else
		{ ?>
			<td align="right"><? echo $result_row['products_price'];?></td>
	<?	} ?>
		<td align="right"><? echo "http://www.travelvideostore.com/images/".$result_row['products_image_med'];?></td>
		<td align="right"><? echo $result_row['products_id'];?></td>
		<td align="right"><? echo $instock;?></td>
		<td align="right"><? echo $result_row['products_upc'];?></td>
		<td align="right"><? echo $result_row['products_isbn'];?></td>
		<td align="right"><? echo "Video";?></td>
		<td align="right"><? echo $medium;?></td>
		<td align="right"><? echo $result_row['products_release_date'];?></td>
		</tr>

<?  } ?>
</table>


