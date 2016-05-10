<?php
/*
  $Id: bizrate.php,v 1.82 2003/06/30 13:54:14 dgw_ Exp $

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

//**************
//CONFIGURATION*
//    START    *
//**************

//Create a directory within your CATALOG directory and make sure it have full read, write, and execute access.
//Update directory name below and your BizRate filename, which you chose when you "Manage Inventory"
//"CHANGEME-full-path-to-file-with-777-dir-and-file-permissions.fr-outfile.txt"
$OutFile = "../feeds/bizrate_outfile.txt";

//Update your BizRate filename, which you chose when you "Manage Inventory"
//"CHANGEME-filename-to-upload-to-bizrate.txt"
$destination_file = "bizrate_outfile.txt";

//DO NOT Chnage
$source_file = $OutFile;

//THIS IS VERY IMPORTANT
//Make sure to update the below with the URL path to your product images
//Make sure to double check this. Right click on a product image and select properties from your website to make sure this is the correct path
//When you generate the test file, copy and paste an image link into a web browser to make sure it is correct.
//Example http://www.seekshopping.com/catalog/images/
//Example http://www.storegroup.org/images/
$imageURL = 'http://www.travelvideostore.com/images/';

//When you generate the test file, copy and paste an image link into a web browser to make sure it is correct.
//Example http://www.seekshopping.com/catalog/product_info.php?products_id=
//Example http://www.storegroup.org/product_info.php?products_id=
$productURL = 'http://www.travelvideostore.com/product_info.php?products_id=';

//DO NOT Chnage
$already_sent = array();

//Input your DB information
//This information is located in your catalog/includes/configuration.php file if you don't know this info
$home = "localhost" ;
$user="terrae2_admin";
$pass="12251957";
$base="terrae2_osCommerce";

//Input BizRate FTP information
//Don't change this, just update the username and password below
$ftp_server = "ftp.bizrate.com" ;

//The username and password are created seperately from your account username and password in the "Manage Inventory" section - https://merchant.bizrate.com/pp/product_inventory/index.xpml
$ftp_user_name = "61192_njeRro";
$ftp_user_pass = "VEuepl";


//In most cases you won't need to use this, however, update the link with your domain below just in case
$convertCur = false; //default = false
$curType = "USD"; // Converts Currency to any defined currency (eg. USD, EUR, GBP)
if($convertCur)
{
//Example: http://www.seekshopping.com/catalog/product_info.php?currency=CURTYPE&products_id=
//Example: http://www.storegroup.org/product_info.php?currency=CURTYPE&products_id=
$productURL = 'http://www.travelvideostore.com/product_info.php?currency=USD&products_id=';  //where CURTYPE is your currency type (eg. USD, EUR, GBP)
}

//IMPORTANT: There is a portion of this code below that should be uncommented once you test the file creation process.
//Do the following before uncommenting the section below
//      Run this file
//      Go to the directory you created
//      View the file
//      Test the links
//
//If everything is successful, uncomment the portion of code below to actually send the file to BizRate.
//Login to BizRate to make sure the file is there.  Note: check filesize, it should be greater then 0 and you should not have gotten any errors when running this script
//
//If all is good, setup a CRON JOB so this script once a week to keep BizRate updated

//**************
//CONFIGURATION*
//     END     *
//**************


if (!($link=mysql_connect($home,$user,$pass)))
{
echo "Error when connecting itself to the data base";
exit();
}
if (!mysql_select_db( $base , $link ))
{
echo "Error the data base does not exist";
exit();
}


$query = "SELECT  p.products_always_on_hand, concat( '" . $productURL . "' ,p.products_id) AS product_url, pd.products_name , pd.products_description , "
			   ." p.products_price , cONCAT( '" . $imageURL . "' ,p.products_image_lrg) AS image_url, pd.products_name_prefix ,"
			   ." p.products_id ,  p.producers_id, p.products_weight, p.products_isbn , p.products_upc ,products_name_suffix ,"
			   ." p.products_quantity , p.products_model ,p.products_release_date ,p.series_id,"
			   ." p.manufacturers_id "
		."FROM products p , products_description pd  "
		."WHERE p.products_id = pd.products_id AND ( ( p.products_quantity >0 ) OR ( p.products_quantity =0 AND p.products_always_on_hand =1 ) OR ( p.products_quantity <0 AND p.products_always_on_hand =1 ) )";

$result = tep_db_query($query);
$list_content = '';

#$list_content = "Category \t Mfr \t Title \t Description \t Link \t Image \t SKU \t # on Hand \t Condition \t Ship. Weight \t Ship. Cost \t Bid \t Promo Value \t Other \t Price\n";

while($result_row = mysql_fetch_array($result))
{

		//Category
		$list_content .= "5114\t";

		//MFR
		$mfr_sql = "Select producers_name from producers where producers_id = $result_row[producers_id]";
		$mfr_rs_sql = tep_db_query($mfr_sql);
		$mfr_row = mysql_fetch_array($mfr_rs_sql);
		$list_content .= $mfr_row['producers_name']."\t";



		//Changing Special characters in TITLE
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

   		//Link
   		$list_content .= $result_row['product_url']."&ref=115118\t";

   		//Image URL
   		$list_content .= $result_row['image_url']."\t";

		//SKU
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

		//Quantity on hand
if ($result_row['products_always_on_hand']==1){
$total_qua = $result_row['products_quantity']+1000;
		$list_content .= $total_qua."\t";
}		
		else
		$list_content .= $result_row['products_quantity']."\t";

		//Condition
		$list_content .= "New\t";

		//Ship. Weight
		$list_content .= $result_row['products_weight']."\t";

		//Ship Cost
		$list_content .= "\t";

		//BID
		$list_content .= "\t";

		//Promo Desc
		$list_content .= "\t";

		//Other
		$list_content .= "\t";

		//Price
		$sp_query = "select specials_new_products_price from specials where products_id =".$result_row['products_id'];
		$sp_result = tep_db_query($sp_query);
		$sp_result_row = tep_db_fetch_array($sp_result);
		$spl_price = $sp_result_row['specials_new_products_price'];

		if ((isset($spl_price)) && ($spl_price <> 0))
		{
			$list_content .= $spl_price."\n";
		}
		else
		{
			$list_content .= $result_row['products_price']."\n";
		}
}


if ( file_exists( $OutFile ) )
unlink( $OutFile );

$fp = fopen( $OutFile , "w" );
$fout = fwrite( $fp , $list_content);
fclose( $fp );


//START FTP TO BIZRATE - UNCOMMENT AFTER TESTING FILE CREATION
function ftp_file( $ftpservername, $ftpusername, $ftppassword, $ftpsourcefile, $ftpdirectory, $ftpdestinationfile )
{
// set up basic connection
$conn_id = ftp_connect($ftpservername);
if ( $conn_id == false )
{
echo "FTP open connection failed to $ftpservername <BR>\n";
return false;
}

// login with username and password
$login_result = ftp_login($conn_id, $ftpusername, $ftppassword);
?>
<td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><td class="pageHeading">
<?
// check connection
if ((!$conn_id) || (!$login_result)) {
echo "FTP connection has failed!<BR>\n";
echo "Attempted to connect to " . $ftpservername . " for user " . $ftpusername . "<BR>\n";
return false;
} else {
echo "Connected to " . $ftpservername . ", for user " . $ftpusername . "<BR>\n";
}

if ( strlen( $ftpdirectory ) > 0 )
{
if (ftp_chdir($conn_id, $ftpdirectory )) {
echo "Current directory is now: " . ftp_pwd($conn_id) . "<BR>\n";
} else {
echo "Couldn't change directory on $ftpservername<BR>\n";
return false;
}
}

ftp_pasv ( $conn_id, true );
// upload the file
$upload = ftp_put( $conn_id, $ftpdestinationfile, $ftpsourcefile, FTP_ASCII );

// check upload status
if (!$upload) {
echo "$ftpservername: FTP upload has failed! Check BizRate Username & Password.<BR>\n";
return false;
} else {
echo "Uploaded " . $ftpsourcefile . " to " . $ftpservername . " as " . $ftpdestinationfile . "<BR>\n";
}

// close the FTP stream
ftp_close($conn_id);

return true;
}

ftp_file( $ftp_server, $ftp_user_name, $ftp_user_pass, $source_file, $ftp_directory, $destination_file);
//END FTP TO BIZRATE - UNCOMMENT AFTER TESTING FILE CREATION

//We hope this module works well for you and you make a lot of money!  Thanks, the StoreGroup.org
?>
		</tr>
		<tr>
            <td class="pageHeading"><br><br>The Bizrate form feed was transfered successfully !!!</td>
          </tr>
         </table>
         </td>
        </tr>
       </td>
      </table>
