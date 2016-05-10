<?php
/*
  $Id: products_options.php, ver 2.0 05/01/2005 Exp $

  Copyright (c) 2004-2005 Daniel Bahna (daniel.bahna@gmail.com)

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $languages = tep_get_languages();

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  $products_id_view = $_REQUEST['products_id_view'];
  $products_id_master = $HTTP_GET_VARS['products_id_master'];
  if ($products_id_master) { $products_id_view = $products_id_master; }

  if (tep_not_null($action)) {
    $page_info = '';
    if (isset($HTTP_GET_VARS['attribute_page'])) $page_info .= 'attribute_page=' . $HTTP_GET_VARS['attribute_page'] . '&';
    if (tep_not_null($page_info)) {
      $page_info = substr($page_info, 0, -1);
    }

    switch ($action) {
      case 'Inherit':
        $products_id_master = tep_db_prepare_input($_REQUEST['products_id_master']);
        $products_id_slave = tep_db_prepare_input($_REQUEST['products_id_slave']);
        $pop_order_id = tep_db_prepare_input($_REQUEST['pop_order_id']);

        $products = tep_db_query("select p.pop_products_id_slave from " . TABLE_PRODUCTS_OPTIONS_PRODUCTS . " p where p.pop_products_id_master=".$products_id_slave." order by p.pop_id");
        while ($products_values = tep_db_fetch_array($products)) {
          $products_id_slave2 = $products_values['pop_products_id_slave'];

         $check = tep_db_query("select p.pop_id from " . TABLE_PRODUCTS_OPTIONS_PRODUCTS . " p where p.pop_products_id_master=".$products_id_master." and p.pop_products_id_slave=".$products_id_slave2);
         if (!tep_db_fetch_array($check)) {
           tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_PRODUCTS . " values ('', '" . (int)$products_id_master . "', '" . (int)$products_id_slave2 . "', '". (int)$pop_order_id."')");
         }
        }

         tep_redirect(tep_href_link(FILENAME_RELATED_PRODUCTS, $page_info.'&products_id_master='.$products_id_master.'&products_id_slave='.$products_id_slave.'&products_id_view='.$products_id_view));
        break;
      case 'Insert':
        $products_id_master = tep_db_prepare_input($_REQUEST['products_id_master']);
        $products_id_slave = tep_db_prepare_input($_REQUEST['products_id_slave']);
        $pop_order_id = tep_db_prepare_input($_REQUEST['pop_order_id']);

        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_PRODUCTS . " values ('', '" . (int)$products_id_master . "', '" . (int)$products_id_slave . "', '". (int)$pop_order_id."')");

         tep_redirect(tep_href_link(FILENAME_RELATED_PRODUCTS, $page_info.'&products_id_master='.$products_id_master.'&products_id_slave='.$products_id_slave.'&products_id_view='.$products_id_view));
        break;
      case 'update_product_attribute':
        $products_id_master = tep_db_prepare_input($_REQUEST['products_id_master']);
        $products_id_slave = tep_db_prepare_input($_REQUEST['products_id_slave']);
        $pop_order_id = tep_db_prepare_input($_REQUEST['pop_order_id']);
        $pop_id = tep_db_prepare_input($_REQUEST['pop_id']);

        tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_PRODUCTS . " set pop_products_id_master = '" . (int)$products_id_master . "', pop_products_id_slave = '" . (int)$products_id_slave . "', pop_order_id = '".(int)$pop_order_id."' where pop_id = '" . (int)$pop_id . "'");
        tep_redirect(tep_href_link(FILENAME_RELATED_PRODUCTS, $page_info.'&products_id_view='.$products_id_view));
        break;
       case 'delete_attribute':
        $pop_id = tep_db_prepare_input($HTTP_GET_VARS['pop_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_PRODUCTS . " where pop_id = '" . (int)$pop_id . "'");

        tep_redirect(tep_href_link(FILENAME_RELATED_PRODUCTS, $page_info.'&products_id_view='.$products_id_view));
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function go_option() {
  if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
    location = "<?php echo tep_href_link(FILENAME_RELATED_PRODUCTS, 'option_page=' . ($HTTP_GET_VARS['option_page'] ? $HTTP_GET_VARS['option_page'] : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
  }
}
//--></script>
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

      </tr>
<!-- products_attributes //-->
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE_ATRIB; ?>&nbsp;</td>
            <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?>&nbsp;</td><td><form name="formview"><select name="products_id_view" onChange="return formview.submit();">
<?php

    echo '<option name="Show All Products" value="">Show All Products</option>';
    $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
    while ($products_values = tep_db_fetch_array($products)) {
        if ($products_id_view == $products_values['products_id']) {
              echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '" SELECTED>' . $products_values['products_name'] . '</option>';
        } else {
              echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
        }
    }
?>
            </select></form>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
<?php

foreach ($HTTP_POST_VARS as $key => $value) {
   echo "Key: $key; Value: $value<br>\n";
}


  if ($action == 'update_attribute') {
    $form_action = 'update_product_attribute';
  } else {
    $form_action = 'add_product_attributes';
  }

  if (!isset($attribute_page)) {
    $attribute_page = 1;
  }
  $prev_attribute_page = $attribute_page - 1;
  $next_attribute_page = $attribute_page + 1;
?>
        <td><form name="attributes" action="<?php echo tep_href_link(FILENAME_RELATED_PRODUCTS, 'action=' . $form_action . '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page); ?>" method="get"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="7" class="smallText">
<?php
  $per_page = 20;
  $attributes = "select pa.* from " . TABLE_PRODUCTS_OPTIONS_PRODUCTS . " pa left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pa.pop_products_id_master = pd.products_id and pd.language_id = '" . (int)$languages_id . "'";
  if ($products_id_view) { $attributes .= " where pd.products_id='$products_id_view'"; }
  $attributes .= " order by pd.products_name, pa.pop_order_id, pa.pop_id";

  $attribute_query = tep_db_query($attributes);

  $attribute_page_start = ($per_page * $attribute_page) - $per_page;
  $num_rows = tep_db_num_rows($attribute_query);

  if ($num_rows <= $per_page) {
     $num_pages = 1;
  } else if (($num_rows % $per_page) == 0) {
     $num_pages = ($num_rows / $per_page);
  } else {
     $num_pages = ($num_rows / $per_page) + 1;
  }
  $num_pages = (int) $num_pages;

  $attributes = $attributes . " LIMIT $attribute_page_start, $per_page";

  // Previous
  if ($prev_attribute_page) {
    echo '<a href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, 'attribute_page=' . $prev_attribute_page) . '"> &lt;&lt; </a> | ';
  }

  for ($i = 1; $i <= $num_pages; $i++) {
    if ($i != $attribute_page) {
      echo '<a href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, 'attribute_page=' . $i) . '">' . $i . '</a> | ';
    } else {
      echo '<b><font color="red">' . $i . '</font></b> | ';
    }
  }

  // Next
  if ($attribute_page != $num_pages) {
    echo '<a href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, 'attribute_page=' . $next_attribute_page) . '"> &gt;&gt; </a>';
  }
?>
            </td>
          </tr>
          <tr>
            <td colspan="7"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>(To)&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>(From)&nbsp;</td>
            <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ORDER; ?>&nbsp;</td>
            <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="7"><?php echo tep_black_line(); ?></td>
          </tr>
<?php
  $next_id = 1;
  $attributes = tep_db_query($attributes);
  while ($attributes_values = tep_db_fetch_array($attributes)) {
    $products_name_master = tep_get_products_name($attributes_values['pop_products_id_master']);
    $products_name_slave = tep_get_products_name($attributes_values['pop_products_id_slave']);
    $pop_order_id = $attributes_values['pop_order_id'];
    $rows++;
?>
          <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
    if (($action == 'update_attribute') && ($HTTP_GET_VARS['pop_id'] == $attributes_values['pop_id'])) {
?>
            <td class="smallText">&nbsp;<?php echo $attributes_values['pop_id']; ?><input type="hidden" name="pop_id" value="<?php echo $attributes_values['pop_id']; ?>">&nbsp;</td>
            <td class="smallText">&nbsp;<select name="products_id_master">
<?php
      $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
      while($products_values = tep_db_fetch_array($products)) {
        if ($attributes_values['pop_products_id_master'] == $products_values['products_id']) {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '" SELECTED>' . $products_values['products_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
        }
      }
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="products_id_slave">
<?php
      $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
      while($products_values = tep_db_fetch_array($products)) {
        if ($attributes_values['pop_products_id_slave'] == $products_values['products_id']) {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '" SELECTED>' . $products_values['products_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
        }
      }
?>
            </select>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<input type="text" name="pop_order_id" value="<?php echo $attributes_values['pop_order_id']; ?>" size="6">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, '&attribute_page=' . $attribute_page . '&products_id_view='.$products_id_view, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>

<?php
    } elseif (($action == 'delete_product_attribute') && ($HTTP_GET_VARS['pop_id'] == $attributes_values['pop_id'])) {
?>
            <td class="smallText">&nbsp;<b><?php echo $attributes_values["pop_id"]; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $products_name_master; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $products_name_slave; ?></b>&nbsp;</td>
            <td class="smallText" align="center">&nbsp;<b><?php echo $pop_order_id; ?></b>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<b><?php echo '<a href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, 'action=delete_attribute&pop_id=' . $HTTP_GET_VARS['pop_id']). '&products_id_view='.$products_id_view. '">'; ?><?php echo tep_image_button('button_confirm.gif', IMAGE_CONFIRM); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page.'&products_id_view='.$products_id_view, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</b></td>
<?php
    } else {
// DANIEL: basic browse table list
?>
            <td class="smallText">&nbsp;<?php echo $attributes_values["pop_id"]; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $products_name_master; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $products_name_slave; ?>&nbsp;</td>
            <td class="smallText" align="center">&nbsp;<?php echo $pop_order_id; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, 'action=update_attribute&pop_id=' . $attributes_values['pop_id'] . '&attribute_page=' . $attribute_page.'&products_id_view='.$products_id_view, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_edit.gif', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, 'action=delete_product_attribute&pop_id=' . $attributes_values['pop_id'] . '&attribute_page=' . $attribute_page.'&products_id_view='.$products_id_view, 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
    }
    $max_attributes_id_query = tep_db_query("select max(pop_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_PRODUCTS);
    $max_attributes_id_values = tep_db_fetch_array($max_attributes_id_query);
    $next_id = $max_attributes_id_values['next_id'];
?>
          </tr>
<?php
  }
  if ($action != 'update_attribute') {
// DANIEL: bottom line with INSERT BUTTON
?>
          <tr>
            <td colspan="7"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
            <td class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
      	    <td class="smallText">&nbsp;<select name="products_id_master">
<?php
    $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
    $products_id_master = $HTTP_GET_VARS['products_id_master'];
    if (!$products_id_master) { $products_id_master = $products_id_view; }
    while ($products_values = tep_db_fetch_array($products)) {
        if ($products_id_master == $products_values['products_id']) {
              echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '" SELECTED>' . $products_values['products_name'] . '</option>';
        } else {
              echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
        }
    }
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="products_id_slave">
<?php
    $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
    while ($products_values = tep_db_fetch_array($products)) {
        if ($HTTP_GET_VARS['products_id_slave'] == $products_values['products_id']) {
              echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '" SELECTED>' . $products_values['products_name'] . '</option>';
        } else {
              echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
        }
    }
?>
            </select>&nbsp;</td>
            <td class="smallText" align="center">&nbsp;<input type="text" name="pop_order_id" size="3">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;&nbsp;<input type="submit" name="action" value="Insert">&nbsp;<input type="submit" name="action" value="Inherit">&nbsp;</td>
          </tr>
<?php
  }
?>
          <tr>
            <td colspan="7"><?php echo tep_black_line(); ?></td>
          </tr>
        </table>
<input type="hidden" name="products_id_view" value="<?php echo $products_id_view; ?>">
        </form></td>
      </tr>
    </table></td>
<!-- products_attributes_eof //-->
  </tr>
</table>
<!-- body_text_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
