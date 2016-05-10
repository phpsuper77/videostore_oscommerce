<?php
/*
  $Id: MySimon.php,v 1.82 2003/06/30 13:54:14 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

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

$OutFile = "../feeds/mysimon_outfile.txt";

//"CHANGEME-filename-to-upload-to-MySimon.txt"
$destination_file = "mysimon_outfile.txt";

//DO NOT Chnage
$source_file = $OutFile;

$imageURL = 'http://www.travelvideostore.com/images/';

$productURL = 'http://www.travelvideostore.com/product_info.php?products_id=';

$already_sent = array();

//In most cases you won't need to use this, however, update the link with your domain below just in case
$convertCur = false; //default = false
$curType = "USD"; // Converts Currency to any defined currency (eg. USD, EUR, GBP)
if($convertCur)
{
$productURL = 'http://www.travelvideostore.com/product_info.php?currency=USD&products_id=';  //where CURTYPE is your currency type (eg. USD, EUR, GBP)
}


$query = "SELECT  p.products_always_on_hand, concat( '" . $productURL . "' ,p.products_id) AS product_url, pd.products_name , pd.products_description , "
			   ." p.products_price , cONCAT( '" . $imageURL . "' ,p.products_image) AS image_url, pd.products_name_prefix ,"
			   ." p.products_id ,  p.producers_id, p.products_weight, p.products_isbn , p.products_upc ,products_name_suffix ,"
			   ." p.products_quantity , p.products_model ,p.products_release_date ,p.series_id,"
			   ." p.manufacturers_id "
		."FROM products p , products_description pd  "
		."WHERE p.products_id = pd.products_id  AND ( ( p.products_quantity >0 ) OR ( p.products_quantity =0 AND p.products_always_on_hand =1 ) OR ( p.products_quantity <0 AND p.products_always_on_hand =1 ) )";

$result = tep_db_query($query);
$list_content = '';

$list_content = "Price\tmfr_name\tproduct\tin_stock\tshipping\tcategory\tproduct_description\tImageURL\tmfr_sku\tCondition\turl\n";

while($result_row = tep_db_fetch_array($result))
{

		//Price
		$sp_query = "select specials_new_products_price from specials where products_id =".$result_row['products_id'];
		$sp_result = tep_db_query($sp_query);
		$sp_result_row = tep_db_fetch_array($sp_result);
		$spl_price = $sp_result_row['specials_new_products_price'];

		if ((isset($spl_price)) && ($spl_price <> 0))
		{
			$list_content .= $spl_price."\t";
		}
		else
		{
			$list_content .= $result_row['products_price']."\t";
		}


		//MFR_name
		$mfr_sql = "Select producers_name from producers where producers_id = $result_row[producers_id]";
		$mfr_rs_sql = tep_db_query($mfr_sql);
		$mfr_row = tep_db_fetch_array($mfr_rs_sql);
		$list_content .= $mfr_row['producers_name']."\t";


		//Changing Special characters in TITLE - Product name
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

		$list_content .= $result_row['products_name_prefix']." ".$result_row['products_name']." ".$result_row['products_name_suffix']."\t";

		//Quantity on hand
if ($result_row['products_quantity']==1){
$total_qua = $result_row['products_quantity']+1000;
		$list_content .= $total_qua."\t";		
}
		else
		$list_content .= $result_row['products_quantity']."\t";

		//Shipping Cost
		$list_content .= "0.00\t";

		//Category
		if($result_row['products_upc']!=""){
			$category = "2461";
		}
		elseif ($result_row['products_isbn'] != ""){
			$category = "1186";
		}
		$list_content .= $category."\t";

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
   		$description = $strip_desc ;
   		$list_content .= $description."\t";

   		//Image URL
		$list_content .= $result_row['image_url']."\t";

		//mfr_sku
		if($result_row['products_upc']!=""){
			$sku = $result_row['products_upc'];
		}
		elseif ($result_row['products_isbn'] != ""){
			$sku = $result_row['products_isbn'];
		}
		elseif ($result_row['products_model'] != ""){
			$sku = $result_row['products_model'];
		}
		$list_content .= $sku."\t";

		//Condition
		$list_content .= "New\t";

   		//URL
   		$list_content .= $result_row['product_url']."&ref=115120\n";

}


if (file_exists( $OutFile ) )
unlink( $OutFile );

$fp = fopen( $OutFile , "w+" );
$fout = fwrite( $fp , $list_content);
fclose( $fp );
?>
<td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><td class="pageHeading">
		</tr>
		<tr>
            <td class="pageHeading"><br><br>The MySimon form feed was transfered successfully !!!</td>
          </tr>
         </table>
         </td>
        </tr>
       </td>
      </table>
