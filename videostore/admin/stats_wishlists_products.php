<?php
/*
  $Id: stats_wishlists.php,v 1.00 2005/06/15
  
  Aaron Hiatt aaron@scaredrabbit.com
  http://www.scaredrabbit.com
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

if ($orderdirection=="") $orderdirection = "asc";


if ($ordersort=='wishlist'){
	$sorting = " order by pd.products_name ".$orderdirection;
}
if ($sorting=='') $sorting = "order by pd.products_name desc";

if ($orderdirection=="asc") $orderdirection = "desc"; else $orderdirection = "asc";

function getDescrWords($val){
preg_match_all("|<strong>(.*)</strong>|Ui", $val, $out, PREG_SET_ORDER);
for ($i=0;$i<count($out); $i++)
echo $out[$i][1].",";

preg_match_all("|<b>(.*)</b>|Ui", $val, $out, PREG_SET_ORDER);
for ($i=0;$i<count($out); $i++)
echo $out[$i][1].",";

preg_match_all("|<i>(.*)</i>|Ui", $val, $out, PREG_SET_ORDER);
for ($i=0;$i<count($out); $i++)
echo $out[$i][1].",";

preg_match_all("|<u>(.*)</u>|Ui", $val, $out, PREG_SET_ORDER);
for ($i=0;$i<count($out); $i++)
echo $out[$i][1].",";
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script>
	function showKeywords(val){
window.open ('info_keywords.php?products_id='+val, 'Keywords', config='height=300,width=450, toolbar=no, menubar=no, scrollbars=no, resizable=yes,location=no, directories=no, status=no');
	
}
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"  width='20' align="center">Rank</td>
                <td class="dataTableHeadingContent" width='150'><a href="stats_wishlists.php?ordersort=customer&orderdirection=<?=$orderdirection?>">Customers</a></td>
				<td class="dataTableHeadingContent" width='150'><a href="stats_wishlists.php?ordersort=company&orderdirection=<?=$orderdirection?>">Company</a></td>
				<td class="dataTableHeadingContent" width='230'><a href="stats_wishlists_products.php?ordersort=wishlist&orderdirection=<?=$orderdirection?>">Wishlist Products</a></td>
				<!--Uncomment if you use the Separate Pricing Contribution
				<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS_GROUP_NAME; ?></td>
				-->
				<td class="dataTableHeadingContent" width='80'>Products model</td>
				<td class="dataTableHeadingContent" width='80'>In stock</td>
				<td class="dataTableHeadingContent" width='50'>Quantity</td>
				<!--<td class="dataTableHeadingContent" width='110'>Keywords</td>-->
              </tr>
<?php

$s=30;
if (!isset($_GET['ctr'])) $ctr=0; else $ctr=$_GET['ctr'];
$st = $ctr*$s;
$limit="limit $st, $s";


  $products_query_raw = tep_db_query("select p.products_out_of_print, pd.products_description, p.products_model, p.products_status, p.products_quantity, w.products_id, pd.products_name_suffix, pd.products_name_prefix, pd.products_id, pd.products_name, z.categories_id, c.customers_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CUSTOMERS . " c, " . TABLE_WISHLIST . " w, " . TABLE_PRODUCTS . " p, products_to_categories z where w.products_id = z.products_id and w.products_id = pd.products_id and pd.products_id = p.products_id and c.customers_id = w.customers_id and w.products_id!='array'  group by pd.products_id ".$sorting." ".$limit);
  $general_per_page = tep_db_num_rows(tep_db_query("select p.products_out_of_print, pd.products_description, p.products_model, p.products_status, p.products_quantity, w.products_id, pd.products_name_suffix, pd.products_name_prefix, pd.products_id, pd.products_name, z.categories_id, c.customers_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CUSTOMERS . " c, " . TABLE_WISHLIST . " w, " . TABLE_PRODUCTS . " p, products_to_categories z where w.products_id = z.products_id and w.products_id = pd.products_id and pd.products_id = p.products_id and c.customers_id = w.customers_id and w.products_id!='array'  group by pd.products_id ".$sorting." ".$limit));
 
  $rows = 0;
  //$products_query = tep_db_query($products_query_raw);
  
  while ($products = tep_db_fetch_array($products_query_raw)) {  
	
    $rows++;

ob_start();
	getDescrWords($products['products_description']);
	$keywords = ob_get_contents();
ob_end_clean();

    if (strlen($rows) < 2) {
    $rows = '0' . $rows;
    }
$customers_query_raw = tep_db_query("select a.entry_company, pd.products_name, w.products_id, c.customers_id, c.customers_firstname, c.customers_lastname from " . TABLE_WISHLIST . " w, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id where c.customers_id = w.customers_id and w.products_id = pd.products_id and pd.products_id=".$products['products_id']." and w.products_id!='array' group by c.customers_firstname, c.customers_lastname");
$customers = tep_db_fetch_array($customers_query_raw);
?>
<!-- onclick="document.location.href='<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&page=1&cID=' . $customers['customers_id'], 'NONSSL'); ?>'" -->
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                <td class="dataTableContent" valign="top" align="center"><?php echo $rows; ?>.</td>
                <td class="dataTableContent" valign="top"><?php echo $customers['customers_firstname'] . ' ' . $customers['customers_lastname']; ?></td>
				<td class="dataTableContent" valign="top"><?php echo $customers['entry_company']; ?></td>
                <td valign="top" class="dataTableContent" colspan="5">
				<?php
				 
			if ($products['products_quantity'] >= '1') {
			$lc_text = '<b style="color:red;">In Stock</b>';
			} 
			elseif (($products['products_quantity'] <= '0') AND ($products['products_out_of_print'] == '0')) {
			$lc_text = '<b style="color:red;">Out of stock</b>';
			} elseif (($products['products_quantity'] <= '0') AND ($products['products_out_of_print'] == '1')){
			$lc_text = '<b style="color:red;">Out of Stock</b>';
			}

			if ($products[products_quantity]<0) $products[products_quantity]='0';

				echo "<table style='table-layout:fixed;' width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td width='230'><a href='categories.php?cPath=".$products[categories_id]."&pID=".$products[products_id]."&action=new_product'>".$products[products_name].'</a></td><td width="80" class="dataTableContent">'.$products[products_model].'</td><td width=80 class="dataTableContent">'.$lc_text.'</td><td width=50 class="dataTableContent">'.$products[products_quantity].'</td><!--<td width=110 class="dataTableContent"><a href="javascript:showKeywords(\''.$products[products_id].'\')">Keywords >></a></td>--></tr></table>';
				
				?>
				</td>
				<!--Uncomment if you use the Separate Pricing Contribution
				<td class="dataTableContent" valign="top"><?php echo $customers['customers_group_name']; ?></td>
				-->
              </tr>
	
<?php
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
<?
  $products_query_numrows = tep_db_query("select p.products_out_of_print, pd.products_description, p.products_model, p.products_status, p.products_quantity, w.products_id, pd.products_name_suffix, pd.products_name_prefix, pd.products_id, pd.products_name, z.categories_id, c.customers_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CUSTOMERS . " c, " . TABLE_WISHLIST . " w, " . TABLE_PRODUCTS . " p, products_to_categories z where w.products_id = z.products_id and w.products_id = pd.products_id and pd.products_id = p.products_id and c.customers_id = w.customers_id and w.products_id!='array'  group by pd.products_id ".$sorting);
  $total = tep_db_num_rows($products_query_numrows);
  $total_pages = ceil($total/$s);   
  $current = ceil($total/$s);  
?>			  
                <td class="smallText" valign="top">Displaying <?if ($st==0) echo '<B>1</B>'; else echo '<b>'.++$st.'</b>';?> to <b><?if ($st+30>$total) echo $total; else echo $st+30;?></b> (of <b><?=$total?></b> products)</td>
                <td class="smallText" align="right">Pages: 

<?			
if ($general_per_page<$total){
if ($ctr>0){
	$prev = $ctr-1;
	$prev_10 = $ctr-10;
	if ($prev_10<0) $prev_10=0;
	echo "<a class='pageResults' style='text-decoration:underline;color:BLUE;font-size:12px;' href='stats_wishlists_products.php?ordersort=wishlist&orderdirection=".$_GET[orderdirection]."&ctr=".$prev."'>[<< Previous]</A>";
	echo " <a class='pageResults' style='color:BLUE;font-size:12px;' href='stats_wishlists_products.php?ordersort=wishlist&orderdirection=".$_GET[orderdirection]."&ctr=".$prev_10."'>...</A> ";
}

	if ($ctr+10>$total_pages) $general = ceil($total_pages); else $general=ceil($ctr+10);
		for ($i=$ctr;$i<$general;$i++){
		$k=$i+1;
	if ($ctr!=$i)
		echo "<a class='pageResults' style='text-decoration:underline;color:BLUE;font-size:12px;' href='stats_wishlists_products.php?ordersort=wishlist&orderdirection=".$_GET[orderdirection]."&ctr=".$i."'>".$k."</A> ";
	else
		echo "<b>".$k."</b> ";
	}
if ($ctr<=$total_pages){
	$next = $ctr+1;
	$next_10 = ceil($ctr+10);
	if ($next_10<$total_pages) echo " <a class='pageResults' style='color:BLUE;font-size:12px;' href='stats_wishlists_products.php?ordersort=wishlist&orderdirection=".$_GET[orderdirection]."&ctr=".$next_10."'>...</A> ";

	echo "<a class='pageResults' style='text-decoration:underline;color:BLUE;font-size:12px;' href='stats_wishlists_products.php?ordersort=wishlist&orderdirection=".$_GET[orderdirection]."&ctr=".$next."'>[Next >>]</A>";
	}
}
	else
		echo "<b>1</b>";
	?>				
				&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
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