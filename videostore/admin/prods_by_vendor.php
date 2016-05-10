<?php
/*


  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $vendor_query_raw = "select vendors_id as id, vendors_name as name from " . TABLE_VENDORS . " order by name ASC";
  $vendor_query = tep_db_query($vendor_query_raw);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
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
            <td valign="top">
           <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td class="smallText" align="right"><?php echo TABLE_HEADING_VENDOR_CHOOSE; ?><?php echo tep_draw_form('vendors_report', FILENAME_PRODS_VENDORS,'','get') . tep_draw_pull_down_menu('vendors_id', tep_get_vendors(),'','onChange="this.form.submit()";');?></form></td>
              </tr>
            </table><?php
  if (isset($HTTP_POST_VARS['vendors_id']) || isset($HTTP_GET_VARS['vendors_id'])) { ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_VENDOR; ?></td>

                            
             
<?php
  if ($HTTP_GET_VARS['vendors_id']) $id = $HTTP_GET_VARS['vendors_id'];
  if ($HTTP_POST_VARS['vendors_id']) $id = $HTTP_POST_VARS['vendors_id'];
 
  $vend_query_raw = "select vendors_name as name from " . TABLE_VENDORS . " where vendors_id = '" . $id . "'";
  $vend_query = tep_db_query($vend_query_raw);
  $vendors = tep_db_fetch_array($vend_query); ?>
  
     <td class="dataTableContent"><b><?php echo $vendors['name']; ?></b></td>
		
          </tr></table>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo 'OnHand';?></td> <td class="dataTableHeadingContent"><?php echo 'Print';?></td><td class="dataTableHeadingContent"><?php echo 'LOC';?></td><td class="dataTableHeadingContent"><?php echo 'Model';?></td><td class="dataTableHeadingContent"><?php echo 'Title';?></td>                        
              </tr>

  <?php
  
  $prod_query_raw = "select pd.products_id as p_id,  p.products_quantity as p_qty, p.products_model as p_model, p.products_id as p_id, p.products_warehouse_location as p_warehouse_location,  pd.products_name_prefix as p_name_pre, pd.products_name as p_name,  p.products_out_of_print as p_print, pd.products_name_suffix as p_suffix from products p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_VENDORS . " pv where pd.products_id = p.products_id and pd.products_id = pv.products_id and pv.vendors_id = " . $id . " order by p_name ASC";
  $prod_query = tep_db_query($prod_query_raw);              
  
  while ($prods = tep_db_fetch_array($prod_query)) {
?>
		<tr class="dataTableRow">
			<td class="dataTableContent"><?php  echo $prods['p_qty'];?></td>
			<td class="dataTableContent"><?php  echo $prods['p_print'];?></td>
			<td class="dataTableContent"><?php  echo $prods['p_warehouse_location'];?></td>
			<td class="dataTableContent"><?php   echo $prods['p_model'];?></td>
			<td class="dataTableContent"><a href="<?php echo FILENAME_VENDORS_ENTRY?>?selected_box=vendors&products_id=<?php echo $prods[p_id]?>&vendors_id=<?php echo $id;?>"><?php   echo $prods['p_name_pre']; echo '&nbsp;'; echo $prods['p_name']; echo '&nbsp;'; echo $prods['p_suffix'];?></a></td>
		</tr>
<?
}
?>


            </table><?php
  }
?></td>
          </tr>
          <? /* ?><tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr> <? */ ?>
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
