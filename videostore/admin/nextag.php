<?php
	require('includes/application_top.php');
?>
	<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html <?php echo HTML_PARAMS; ?>>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
	<title><?php echo TITLE; ?></title>
	<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
	<script language="javascript" src="includes/general.js"></script>
	`
	<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
	<!-- header //-->
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
	<!-- header_eof //-->

	<!-- body //-->
	<table border="0" width="100%" cellspacing="2" cellpadding="2">
	  <tr>
	    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
	<!-- left_navigation //-->
	<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
	<!-- left_navigation_eof //-->
	    </table></td>
<!-- body_text //-->
<?

	$query = "SELECT  concat( '" . $productURL . "' ,p.products_id) AS product_url, pd.products_name , pd.products_description , "
		   ." p.products_price , cONCAT( '" . $imageURL . "' ,p.products_image_lrg) AS image_url, pd.products_name_prefix ,"
		   ." p.products_id ,  p.products_image_med, p.producers_id, p.products_weight, p.products_isbn , p.products_upc ,products_name_suffix ,"
		   ." p.products_quantity , p.products_model ,p.products_release_date ,p.series_id,"
		   ." p.manufacturers_id "
		."FROM products p , products_description pd  "
		."WHERE p.products_quantity and p.products_upc >0 and p.products_id = pd.products_id";

	$result = tep_db_query($query);

	$OutFile = "../feeds/nextag.csv";

	//OPEN A FILE IN RE-WRITE MODE
	$fp = fopen($OutFile,"w+");


	//WRITE THE HEADER IN NEXTAG.TXT
	fwrite($fp,$header);

	$record = '';

	$record = "Product Name,Manufacturer,Manufacturer Part#,Description,Price,Click-out URL,Image URL,Category,Weight,Product Condition\n";

	fwrite($fp,$record);

	while($result_row = mysql_fetch_array($result))
	{

		//Changing Special characters in Product Name
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


		$str = "&";
		$result_row['products_name_prefix'] = str_replace($str, "and", $result_row['products_name_prefix']);
		$result_row['products_name'] = str_replace($str, "and", $result_row['products_name']);
		$result_row['products_name_suffix'] = str_replace($str, "and", $result_row['products_name_suffix']);


		//manufacturer
		$manu_query = "select producers_name from producers where producers_id =".$result_row['producers_id'];
		$manu_result = tep_db_query($manu_query);
		$manu_result_row = tep_db_fetch_array($manu_result);


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
   		$str = "\"";
   		$product_description = str_replace($str, "", $product_description);

		//Replacing the special characters with spaces in manufacturer name
		$str = "&#39";
		$manu_result_row['producers_name'] = str_replace($str, "", $manu_result_row['producers_name']);
		$str = "&#34";
		$manu_result_row['producers_name'] = str_replace($str, "", $manu_result_row['producers_name']);
		$str = "&#183";
		$manu_result_row['producers_name'] = str_replace($str, "", $manu_result_row['producers_name']);
		$str = "\"";
		$manu_result_row['producers_name'] = str_replace($str, "", $manu_result_row['producers_name']);


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

		if(($result_row['products_upc']!='' || $result_row['products_isbn']!='') && $manu_result_row['producers_name']!='')
		{
			$record =  "\"".$result_row['products_name_prefix']." ".$result_row['products_name']." ".$result_row['products_name_suffix']."\",";

			$record .=  "\"".$manu_result_row['producers_name']."\",";

			if($result_row['products_upc']!="")
			{
				$record .= $result_row['products_upc'].",";
			}
			else if($result_row['products_isbn']!="")
			{
				$record .= $result_row['products_isbn'].",";
			}


			$record .= "\"".$product_description."\",";

			if($sp_result_row['specials_new_products_price'])
				$record .=  $sp_result_row['specials_new_products_price'].",";
			else
				$record .=  $result_row['products_price'].",";

			$record .=  "http://www.travelvideostore.com/product_info.php?products_id=".$result_row['products_id']."&ref=115121,";

			if($result_row['products_image_med'] !="")
				$record .=  "http://www.travelvideostore.com/images/".$result_row['products_image_med'].",";
			else
				$record .=  ",";

			//category
			if (!(isset($medium)))
			{
				$record .= ",";
			}
			else  //Check whether the product is DVD/VHS
			{
				if ($medium == 'VHS')
					$record .= "\"VHS\",";
				elseif ($medium == 'DVD')
					$record .= "\"DVD\",";
				elseif ($medium == 'CD')
					$record .= "\"CD\",";
				else
					$record .= ",";
			}

			$record .= $result_row['products_weight'].",";

			$record .= "\"New\"\n";

			//WRITING THE RECORDS IN NEXTTAG.TXT
			fwrite($fp,$record);
		}
	}
	fclose($fp);
?>
<td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><td class="pageHeading">

		</tr>
		<tr>
            <td class="pageHeading" align=center><br><br>The nextag form feed was successfully generated!!!</td>
          </tr>
         </table>
         </td>
        </tr>
       </td>
      </table>
