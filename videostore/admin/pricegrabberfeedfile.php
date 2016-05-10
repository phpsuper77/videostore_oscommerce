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
	//Input pricegrabber FTP information
	//Don't change this, just update the username and password below
	$ftp_server = "ftp.pricegrabber.com" ;
	$ftp_user_name = "travelvideostore";
	$ftp_user_pass = "GufFer";
	$OutFile = "../pricegrabber/pricegrabber.txt";
	$destination_file = "pricegrabber.txt";
	$source_file = $OutFile;

	$query = "SELECT  p.products_always_on_hand, p.products_isbn , p.products_upc  , p.products_price ,p.producers_id ,"
	              ."  p.products_id  ,pd.products_name_prefix ,p.products_image_med , p.products_weight ,"
	              ." pd.products_name , pd.products_description ,pd.products_name_suffix , p.products_quantity "
	        ."FROM products p , products_description pd  "
	        ."WHERE p.products_id = pd.products_id AND ( ( p.products_quantity >0 ) OR ( p.products_quantity =0 AND p.products_always_on_hand =1 ) OR ( p.products_quantity <0 AND p.products_always_on_hand =1 ) )";
	        //."AND p.products_date_added >= DATE_SUB(CURDATE() ,INTERVAL 90 DAY)";
    $result = tep_db_query($query);

    $header = "ISBN/UPC\t";
    $header.= "Manufacturer\t";
    $header.= "Product Name\t";
    $header.= "Referring Product URL\t";
    $header.= "Condition\t";
    $header.= "Quantity\t";
    $header.= "Price\t";
    $header.= "Shipping costs\n";

	//OPEN A FILE IN RE-WRITE MODE
	$fp = fopen($OutFile,"w+");

	//WRITE THE HEADER IN PRICEGRABBER.TXT
	fwrite($fp,$header);

	$record = '';

	while($result_row = mysql_fetch_array($result))
	{
       //special price
        $query = "select specials_new_products_price from specials where products_id =".$result_row['products_id'];
        $sp_result = tep_db_query($query);
    	$sp_result_row = tep_db_fetch_array($sp_result);



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

		//Replacing the special characters with spaces in manufacturer name
		$str = "&#39";
		$manu_result_row['producers_name'] = str_replace($str, "", $manu_result_row['producers_name']);

		$str = "&#34";
		$manu_result_row['producers_name'] = str_replace($str, "", $manu_result_row['producers_name']);

		$str = "&#183";
		$manu_result_row['producers_name'] = str_replace($str, "", $manu_result_row['producers_name']);

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


		if($result_row['products_upc']!='' || $result_row['products_isbn']!='')
		{
			if($result_row['products_upc']!="")
			{
				$record = $result_row['products_upc']."\t";
			}
			else if($result_row['products_isbn']!="")
			{
				$record = $result_row['products_isbn']."\t";
			}


			$record .=  $manu_result_row['producers_name']."\t";

			$record .=  $result_row['products_name_prefix']." ".$result_row['products_name']." ".$result_row['products_name_suffix']."\t";
			$record .=  "http://www.travelvideostore.com/product_info.php?products_id=".$result_row['products_id']."&ref=115122\t";
			$record .=  "New\t";

if ($result_row['products_always_on_hand']==1){
$total_qua = $result_row['products_quantity']+1000;
			$record .=  $total_qua."\t";

}			else
			$record .=  $result_row['products_quantity']."\t";
			if($sp_result_row['specials_new_products_price'])
			{
				$record .=  $sp_result_row['specials_new_products_price']."\t";
			}
			else
			{
				$record .=  $result_row['products_price']."\t";
			}

			if (isset($result_row['products_weight']) && $result_row['products_weight'] <> '')
				$record .= $result_row['products_weight']."\n";
			else
				$record .= "\t\n";


			//WRITING THE RECORDS IN PRICEGRABBER.TXT
			fwrite($fp,$record);
		}
	}

	fclose($fp);

	//START FTP TO PRICEGRABBER
	function ftp_file( $ftpservername, $ftpusername, $ftppassword, $ftpsourcefile, $ftpdirectory, $ftpdestinationfile )
	{
		//set up basic connection
		$conn_id = ftp_connect($ftpservername);
		if ( $conn_id == false )
		{
			echo "<br><span class=main>FTP open connection failed to $ftpservername <BR></span>\n";
			return false;
		}

		// login with username and password
		$login_result = ftp_login($conn_id, $ftpusername, $ftppassword);
?>

<td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><td class="pageHeading">

		</tr>
		<tr>
            <td class="pageHeading"><br><br>The Pricegrabber form feed was transfered successfully !!!</td>
          </tr>
         </table>
         </td>
        </tr>
       </td>
      </table>


<?
	// check connection
	if ((!$conn_id) || (!$login_result))
	{
		echo "<br><span class=main>FTP connection has failed!<BR>\n";
		echo "Attempted to connect to " . $ftpservername . " for user " . $ftpusername . "</span><BR>\n";
		return false;
	} else
	{
		echo "<br><span class=main>Connected to " . $ftpservername . ", for user " . $ftpusername . "</span><BR>\n";
	}

	if ( strlen( $ftpdirectory ) > 0 )
	{
		if (ftp_chdir($conn_id, $ftpdirectory ))
		{
			echo "Current directory is now: " . ftp_pwd($conn_id) . "<BR>\n";
		}
		else
		{
			echo "Couldn't change directory on $ftpservername<BR>\n";
			return false;
		}
	}

	ftp_pasv ( $conn_id, true );

	// upload the file
	$upload = ftp_put($conn_id, $ftpdestinationfile, $ftpsourcefile, FTP_ASCII );

	// check upload status
	if (!$upload)
	{
		echo "<span class=main>$ftpservername: FTP upload has failed! Check Pricegrabber Username & Password.</span><BR>\n";
		return false;
	}
	else
	{
		echo "<br><span class=main>Uploaded " . $ftpsourcefile . " to " . $ftpservername . " as " . $ftpdestinationfile . "</span><BR>\n";
	}

	// close the FTP stream
	ftp_close($conn_id);
	return true;
}

	ftp_file( $ftp_server, $ftp_user_name, $ftp_user_pass, $source_file, $ftp_directory, $destination_file);
	//END FTP TO PRICEGRABBER
	exit;
?>