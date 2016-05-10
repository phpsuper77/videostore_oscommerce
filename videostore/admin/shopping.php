<?php
/*
  $Id: shopping.php,v 1 2005/07/04 13:54:14

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
<?php
	$query = "SELECT  products_isbn , products_upc  , products_media_type_id, products_price ,producers_id ,"
	              ."  products_id  , products_weight ,"
	              ."  products_quantity "
	        ."FROM products WHERE products_upc>0 AND products_quantity >0 ";
	        //."AND p.products_date_added >= DATE_SUB(CURDATE() ,INTERVAL 90 DAY)";

    $result = tep_db_query($query);

    $header = "UPC,";
    $header.= "product url,";
    $header.= "price,";
    $header.= "condition,";
    $header.= "category,";
    $header.= "format,";
    $header.= "stock,";
    $header.= "shipping rate,";
    $header.= "shipping weight\n";

	$OutFile = "../feeds/travelvideostoreSDC.csv";
	//OPEN A FILE IN RE-WRITE MODE
	$fp = fopen($OutFile,"w+");

	//WRITE THE HEADER IN PRICEGRABBER.TXT
	fwrite($fp,$header);



	while($result_row = mysql_fetch_array($result))
	{
		$record = '';

       //special price
        $query = "select specials_new_products_price from specials where products_id =".$result_row['products_id'];
        $sp_result = tep_db_query($query);
    	$sp_result_row = tep_db_fetch_array($sp_result);

		if($result_row['products_upc']!='')
		{
			if($result_row['products_upc']!="")
			{
				$record .= $result_row['products_upc'].",";
			}
			else if($result_row['products_isbn']!="")
			{
				$record .= $result_row['products_upc'].",";
			}

			//Product URL
			$record .= "http://www.travelvideostore.com/product_info.php?products_id=".$result_row['products_id']."&ref=115123,";

			if($sp_result_row['specials_new_products_price'])
			{
				$record .=  $sp_result_row['specials_new_products_price'].",";
			}
			else
			{
				$record .=  $result_row['products_price'].",";
			}

			//Condition
			$record .=  "New,";

			//Category
			$record .=  "Movies,";

			//Product Format (DVD/VCD/CD)
			$sql_format = "select products_media_type_name from products_media_types where products_media_type_id=".$result_row['products_media_type_id'];
			$sql_result = tep_db_query($sql_format);
    		$sql_result_row = tep_db_fetch_array($sql_result);
    		$record .=  $sql_result_row['products_media_type_name'].",";

    		//Stock
			if($result_row['products_quantity'] >= '1')
				$record .= "Y,";
			else
				$record .= "N,";

			//Shipping rate
			$record .= "$0.00,";

			//Shipping weight
			if (isset($result_row['products_weight']) && $result_row['products_weight'] <> '')
				$record .= $result_row['products_weight']."\n";
			else
				$record .= "\n";

			fwrite($fp,$record);
		}
	}
fclose( $fp );
?>
<td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><td class="pageHeading">
		</tr>
		<tr>
            <td class="pageHeading" align=center><br><br>The Shopping.com form feed was created successfully !!! <br><br><span class="formAreaTitle">Please find the CSV in </b>../feeds/travelvideostoreSDC.csv<b></span></td>
          </tr>
         </table>
         </td>
        </tr>
       </td>
      </table>
