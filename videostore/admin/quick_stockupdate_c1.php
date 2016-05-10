<?php
/*
   quick_stockupdate.php v1.1 by Tomorn Kaewtong / http://www.phpthailand.com
   MODIFIED quick_stockupdate.php v2.1 by Dominic Stein

   Stand-alone Admin tool for osCommerce v2.2-CVS

   A spin-off of my Quick DeActivate script so you can set a lot of quantities
   in a single process. Also allows you to change the STATUS of the products
   based upon quantities provided.

   Released under the GPL licence.
*/

include('includes/application_top.php');


/// optional parameter to set max products per row:
$max_cols = 4;

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<SCRIPT LANGUAGE="JavaScript1.2" SRC="jsgraph/graph.js"></SCRIPT>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
    	<td width="<?php echo BOX_WIDTH; ?>" valign="top">
			<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
			<!-- left_navigation //-->
			<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
			<!-- left_navigation_eof //-->
	        </table>
		</td>
<!-- body_text //-->
		<td width="100%" valign="top">

<table style="border:none" border="0" width="90%" align="center" class="none"><tr><td>
<?php
// we've done nothing cool yet...
$msg_stack = 'Fetched products from category';

 if ($HTTP_POST_VARS['stock_update']) {

   //set counter
     $stock = 0;
     $status_a = 0;
     $status_d = 0;


  while (list($key, $items) = each($stock_update)) {
   // updated for image change as well as stock thowden 0/2004
   // update the quantity in stock and image
   //$sql = "UPDATE products SET products_quantity = ".$items['stock'].", products_warehouse_location = '".$items['location']."', products_model = '".$items['model']."', products_upc = '".$items['upc']."', products_isbn = '".$items['isbn']."', products_asin = '".$items['asin']."', products_status = '".$items['status']."', products_image = '".$items['image']."', products_image_med = '".$items['imagemed']."', products_image_lrg = '".$items['imagelrg']."' WHERE products_id = $key";

   $sql = "UPDATE products SET products_quantity = ".$items['stock'].", products_warehouse_location = '".$items['location']."', products_model = '".$items['model']."', products_upc = '".$items['upc']."', products_isbn = '".$items['isbn']."', products_asin = '".$items['asin']."', products_status = '".$items['status']."', products_image = '".$items['image']."', products_image_med = '".$items['imagemed']."', products_image_lrg = '".$items['imagelrg']."', products_price = ". $items['products_price'] .", products_out_of_print = ". $items['products_out_of_print'] ." WHERE products_id = $key";//NEWLY INSERTED
   $update = tep_db_query($sql);

	######NEWLY INSERTED######

	if (isset($items['specials_new_products_price']) && $items['specials_new_products_price'] <> '')
	{
		$sql_select = tep_db_query("SELECT count(*) AS cnt FROM specials WHERE products_id = $key");
		$rs_select = tep_db_fetch_array($sql_select);
		if ($rs_select['cnt'] > 0)
			$spl_sql = "UPDATE specials SET specials_new_products_price = ". $items['specials_new_products_price'] . ", specials_last_modified = now() WHERE products_id = $key";//NEWLY INSERTED
		else
			$spl_sql = "INSERT INTO specials (products_id,specials_new_products_price,specials_date_added) values ($key,$items[specials_new_products_price],now())";//NEWLY INSERTED
	}
	else
		$spl_sql = "DELETE FROM specials WHERE products_id = $key";//NEWLY INSERTED

	$spl_update = tep_db_query($spl_sql);
	###########################

   $stock_i++;


   // we're de-re-activating the selected products
   if ($HTTP_POST_VARS['update_status']) {
     if ($value >= 1 ) {
                       $dereac = tep_db_query("UPDATE products SET products_status = 1 WHERE products_id = $key");
     $status_a++;
     }else{
                       $dereac = tep_db_query("UPDATE products SET products_status = 0 WHERE products_id = $key");
     $status_d++;
    }
   }
  }
 $msg_stack = '<br>Updated quantity: ' . $stock_i . ' products<br>Activated status: ' . $status_a . ' products<br>De-activated status: ' .  $status_d . ' products';
 }
?>
<br><form method="post" action="quick_stockupdate_c1.php">
<?php

   // first select all categories that have 0 as parent:
      $sql = tep_db_query("SELECT c.categories_id, cd.categories_name from categories c, categories_description cd WHERE c.parent_id = 0 AND c.categories_id = cd.categories_id AND cd.language_id = 1");
       echo '<center><font face="Verdana"><b>Quick Stock Update v1.1</b></font>'. tep_draw_separator('pixel_trans.gif', '100%', '3') . '</center><table border="0" align="center"><tr>';
        while ($parents = tep_db_fetch_array($sql)) {
           // check if the parent has products
           $check = tep_db_query("SELECT products_id FROM products_to_categories WHERE categories_id = '" . $parents['categories_id'] . "'");
	   if (tep_db_num_rows($check) > 0) {

              $tree = tep_get_category_tree();
              $dropdown= tep_draw_pull_down_menu('cat_id', $tree, '', 'onChange="this.form.submit();"'); //single
              $all_list = '<form method="post" action="quick_stockupdate_c1.php"><th class="smallText" align="left" valign="top">All categories:<br>' . $dropdown . '</form></th>';

           } else {

           // get the tree for that parent
              $tree = tep_get_category_tree($parents['categories_id']);
             // draw a dropdown with it:
                $dropdown = tep_draw_pull_down_menu('cat_id', $tree, '', 'onChange="this.form.submit();"');
                $list .= '<form method="post" action="quick_stockupdate_c1.php"><th class="smallText" align="left" valign="top">' . $parents['categories_name'] . '<br>' . $dropdown . '</form></th>';
        }
       }
       echo $list . $all_list . '</form></tr></table><p>';



   // see if there is a category ID:

  if ($HTTP_POST_VARS['cat_id']) {

      // start the table
      echo '<form method="post" action="quick_stockupdate_c1.php"><table border="0" width="100%"><tr>';
       $i = 0;

      // get all active prods in that specific category

       $sql2 = tep_db_query("SELECT p.products_id, p. products_quantity, p. products_model, p.products_price, p.products_out_of_print, p.products_status, p.products_image, p.products_image_med, p.products_image_lrg, p.products_warehouse_location, p.products_upc, p.products_isbn, p.products_asin,  pd.products_head_title_tag, pd.products_name from products p,  products_description pd where p.products_id=pd.products_id and p.products_warehouse_location < 'D1' and p.products_warehouse_location > 'B8'  order by p.products_warehouse_location");
// added changes for image update thowden 10/2004 stock_update becomes a multi-dim array
     while ($results = tep_db_fetch_array($sql2)) {

	  ###NEWLY ADDED#################
	  $sql_spl = tep_db_query("SELECT specials_new_products_price,products_id from specials where products_id =". $results['products_id']);
	  $result_spl = tep_db_fetch_array($sql_spl);
	  ###############################

           $i++;
             echo '<td align="center" valign="top" class="smallText">' . tep_image(DIR_WS_CATALOG . DIR_WS_IMAGES . $results['products_image'], 'ID  ' . $results['products_id'] . ': ' . $results['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br><br>';
             echo '<input type="text" size="30" name="stock_update[' . $results['products_id'] . '][image]" value="' . $results['products_image'] . '"><br>';
 echo '<input type="text" size="30" name="stock_update[' . $results['products_id'] . '][imagemed]" value="' . $results['products_image_med'] . '"><br>';
 echo '<input type="text" size="30" name="stock_update[' . $results['products_id'] . '][imagelrg]" value="' . $results['products_image_lrg'] . '"><br>';
			 echo $results['products_id'] . ': ' . $results['products_head_title_tag'] . '<br>';
 echo '<input type="text" size="20" name="stock_update[' . $results['products_id'] . '][model]" value="' . $results['products_model'] . '">model<br><i>';

			 echo '<input type="text" size="3" name="stock_update[' . $results['products_id'] . '][stock]" value="' . $results['products_quantity'] . '">stock<br><i>';
                         echo '<input type="text" size="3" name="stock_update[' . $results['products_id'] . '][location]" value="' . $results['products_warehouse_location'] . '">location<br><i>';
                         echo '<input type="text" size="12" name="stock_update[' . $results['products_id'] . '][upc]" value="' . $results['products_upc'] . '">upc<br><i>';
                         echo '<input type="text" size="10" name="stock_update[' . $results['products_id'] . '][isbn]" value="' . $results['products_isbn'] . '">isbn<br><i>';
                         echo '<input type="text" size="10" name="stock_update[' . $results['products_id'] . '][asin]" value="' . $results['products_asin'] . '">asin<br><i>';
                         echo '<input type="text" size="1" name="stock_update[' . $results['products_id'] . '][status]" value="' . $results['products_status'] . '">status<br><i>';

                         ######Newly Inserted####
                         echo '<input type="text" size="8" name="stock_update[' . $results['products_id'] . '][products_price]" value="' . $results['products_price'] . '">Products Price<br><i>'; //NEWLY ADDED
                         echo '<input type="text" size="1" name="stock_update[' . $results['products_id'] . '][products_out_of_print]" value="' . $results['products_out_of_print'] . '">Products out of print<br><i>';//NEWLY ADDED

                         if (isset($result_spl['specials_new_products_price']))
                         	echo '<input type="text" size="8" name="stock_update[' . $results['products_id'] . '][specials_new_products_price]" value="' . $result_spl['specials_new_products_price'] . '">Special Price<br><i>';//NEWLY ADDED
                         else
                         	echo '<input type="text" size="8" name="stock_update[' . $results['products_id'] . '][specials_new_products_price]">Special Price<br><i>';//NEWLY ADDED
                         #########################

             echo (($results['products_status'] == 0) ? '<font color="ff0000"><b>not active</b></font>' : '<font color="009933"><b>active</b></font>');
             echo '</i></td>';
          if ($i == $max_cols) {
               echo '</tr><tr><td><hr></td><td><hr></td><td><hr></td><td><hr></td><td><hr></td><td><hr></td></tr><tr>';
               $i =0;
         }
    }
  echo '<input type="hidden" name="cat_id" value="' . $HTTP_POST_VARS['cat_id'] . '">';
  echo '</tr><br><td align="center" colspan="10" class="smallText">';
  echo '<input type="checkbox" name="update_status">Check to set status on each individual product based on items in stock<br><i>( one or more in stock will become <font color="009933"><b>active</b></font> / b1 in stock will become <font color="ff0000"><b>not active</b></font> )</i><p>';
  echo '<input type="submit" value="Update"></td></tr><td colspan="30" align="left" class="smallText"><font color="6666cc"><br><b>Last performed action:</b><br>' . $msg_stack . '</b></font></td></tr></form>';
  } //if
?>
    </tr></table>
  </td>
</tr></table><center>
 <a style="font-family:Verdana,Arial,Helvetica,sans-serif;font-size:xx-small;text-decoration:none;text-decoration:none;color=ccbbcc;" href="mailto:info@phpthailand.com?subject=QuickStockUpdate"><b>&copy; 2002 Tomorn K. -</b></a>
 <a style="font-family:Verdana,Arial,Helvetica,sans-serif;font-size:xx-small;text-decoration:none;color=#ccbbcc;" href="http://www.phpthailand.com"><b>http://www.phpthailand.com</b></a></center>
		</td>
<!-- body_text_eof //-->
	</tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>