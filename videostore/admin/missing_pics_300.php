<?php
/*
  missing_pics.php,v 3.5

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Idea by FaustinoInc.com

  Rewritten by Brian Burton (dynamoeffects)

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>"/> <meta name="robots" content="noindex, nofollow" /> <meta name="googlebot" content="noarchive" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script type="text/javascript" src="includes/general.js"></script>
</head>
<body onload="SetFocus();">
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
    <td width="100%" valign="top">
        <h1 class="pageHeading"><?php echo HEADING_TITLE . ' - Ver. 3.5 (2011-03-03) '; ?>
        <a href="http://addons.oscommerce.com/info/4503"> link to contrib for update </a></h1>
<?php
  //Change this line if you keep your images stored in a strange directory.
  //Don't touch it unless you're having problems.
  $image_directory = (DIR_FS_CATALOG . DIR_WS_IMAGES);

  $image_columns = array('cover_image');

  $column_query_string = '';
  $image_count = 0;

  foreach ($image_columns as $column) {
    if ($column_query_string != '') $column_query_string .= ', ';
    $column_query_string .= $column;
  }

  $image_array = array();
  $images_query = tep_db_query("SELECT products_id, " . $column_query_string . " FROM " . TABLE_PRODUCTS);

  while ($row = tep_db_fetch_array($images_query)) {
    $image_array[$row['products_id']] = array();

    foreach ($image_columns as $column) {
      if ($row[$column] != '') {
        $image_array[$row['products_id']][] = $row[$column];
        $image_count++;
      }
    }
  }

  /* Our image array is now built, start checking files. */
  $missing_images = array();

  foreach ($image_array as $id => $product) {
    foreach ($product as $image) {
      if (! is_file($image_directory . $image)){
        if (!is_array($missing_images[$id])) $missing_images[$id] = array();

        $missing_images[$id][] = $image;
      }
    }
  }
?>
<?php  if (count($missing_images) > 0) { ?>
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="dataTableHeadingRow">
                  <td class="dataTableHeadingContent" align="center" width="30"><?php echo TABLE_HEADING_PRODUCT_ID; ?></td>
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_NAME; ?></td>
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_IMAGES; ?></td>
                </tr>
<?php
      foreach ($missing_images as $id => $files) {
        $product_query = tep_db_query("SELECT products_name FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE products_id = '" . (int)$id . "'");
        $product = tep_db_fetch_array($product_query);
?>
                <tr>
                  <td class="dataTableContent" align="center" width="30"><?php echo $id; ?></td>
                  <td class="dataTableContent"><a href="<?php echo tep_href_link(FILENAME_CATEGORIES, 'pID=' . $id . '&amp;action=new_product') . '">' . $product['products_name']; ?></a></td>
                  <td class="dataTableContent"><?php foreach ($files as $f) {
                                                       echo $f . '<br />';
                                                     }?> </td>
                </tr>
                <tr>
                  <td colspan="3" style="padding: 0px; height: 1px; font-size: 1px; background-color: #EFEFEF"></td>
                </tr>
<?php
      }
?>
              </table>
<?php
    } else { ?>
      <div> <?php echo CONGRATULATION_1 . $image_count . CONGRATULATION_2 ; ?> </div><br />
<?php  } ?>
<hr />
<?php // inizio per articoli senza fotografia
  $images_query_temp = tep_db_query("SELECT products_id, products_model FROM " . TABLE_PRODUCTS . " WHERE products_image IS NULL");
  $product_temp = tep_db_fetch_array($images_query_temp);
    if (count($product_temp) > 1) {
?>
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="dataTableHeadingRow">
                  <td class="dataTableHeadingContent" align="center" width="30"><?php echo TABLE_HEADING_PRODUCT_ID; ?></td>
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_MODEL; ?></td>
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_IMAGES_2; ?></td>
                </tr>
                   <?php
                   $image_missed_array = array();
                   $image_missed_columns = array('products_image');
                   $images_missed_query = tep_db_query("SELECT products_id, products_model FROM " . TABLE_PRODUCTS . " WHERE products_image IS NULL");
                   while ($row_missed = tep_db_fetch_array($images_missed_query)) {
                     $product_id_temp = $row_missed['products_id'];
                     $products_name_temp = $row_missed['products_model'];
                     if ( $products_name_temp == '' ) $products_name_temp .= TABLE_HEADING_PRODUCT_MODEL . ': -???-';
                     ?>
                <tr>
                  <td class="dataTableContent" align="center" width="30"><?php echo $product_id_temp; ?></td>
                  <td class="dataTableContent"><a href="<?php echo tep_href_link(FILENAME_CATEGORIES, 'pID=' . $product_id_temp . '&amp;action=new_product') . '">' . $products_name_temp; ?></a></td>
                </tr>
                     <?php
                     $image_missed_array[$row_missed['products_id']] = array();
                     foreach ($image_missed_columns as $column_missed) {
                       $image_missed_array[$row_missed['products_id']][] = $row_missed[$column_missed];
                       $image_missed_count++;
                     }
                   }
                   ?>
                <tr>
                  <td colspan="3" style="padding: 0px; height: 1px; font-size: 1px; background-color: #EFEFEF"></td>
                </tr>
              </table>

<?php } else { ?>
        <div> <?php echo CONGRATULATION_3 . $image_count . CONGRATULATION_4 ; ?> </div><br />
<?php } ?>
<hr />
<?php
// fine controllo articoli senza immagine
?>
<!-- body_text_eof //-->
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>