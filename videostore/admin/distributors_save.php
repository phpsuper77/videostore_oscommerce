<?php
/*
  $Id: distributors.php,v 1.55 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['mID'])) $distributors_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);
        $distributors_name = tep_db_prepare_input($HTTP_POST_VARS['distributors_name']);

        $sql_data_array = array('distributors_name' => $distributors_name);

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_DISTRIBUTORS, $sql_data_array);
          $distributors_id = tep_db_insert_id();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_DISTRIBUTORS, $sql_data_array, 'update', "distributors_id = '" . (int)$distributors_id . "'");
        }

        if ($distributors_image = new upload('distributors_image', DIR_FS_CATALOG_IMAGES)) {
          tep_db_query("update " . TABLE_DISTRIBUTORS . " set distributors_image = '" . $distributors_image->filename . "' where distributors_id = '" . (int)$distributors_id . "'");
        }

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          
          $language_id = $languages[$i]['id'];

          
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('distributors');
        }

        tep_redirect(tep_href_link(FILENAME_DISTRIBUTORS, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'mID=' . $distributors_id));
        break;
      case 'deleteconfirm':
        $distributors_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);

        if (isset($HTTP_POST_VARS['delete_image']) && ($HTTP_POST_VARS['delete_image'] == 'on')) {
          $distributor_query = tep_db_query("select distributors_image from " . TABLE_DISTRIBUTORS . " where distributors_id = '" . (int)$distributors_id . "'");
          $distributor = tep_db_fetch_array($distributor_query);

          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $distributor['distributors_image'];

          if (file_exists($image_location)) @unlink($image_location);
        }

        tep_db_query("delete from " . TABLE_DISTRIBUTORS . " where distributors_id = '" . (int)$distributors_id . "'");


        if (isset($HTTP_POST_VARS['delete_products']) && ($HTTP_POST_VARS['delete_products'] == 'on')) {
          $products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where distributors_id = '" . (int)$distributors_id . "'");
          while ($products = tep_db_fetch_array($products_query)) {
            tep_remove_product($products['products_id']);
          }
        } else {
          tep_db_query("update " . TABLE_PRODUCTS . " set distributors_id = '' where distributors_id = '" . (int)$distributors_id . "'");
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('distributors');
        }

        tep_redirect(tep_href_link(FILENAME_DISTRIBUTORS, 'page=' . $HTTP_GET_VARS['page']));
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
<script language="javascript" src="includes/general.js"></script>
</head>
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DISTRIBUTORS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $distributors_query_raw = "select distributors_id, distributors_name, distributors_image, date_added, last_modified from " . TABLE_DISTRIBUTORS . " order by distributors_name";
  $distributors_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $distributors_query_raw, $distributors_query_numrows);
  $distributors_query = tep_db_query($distributors_query_raw);
  while ($distributors = tep_db_fetch_array($distributors_query)) {
    if ((!isset($HTTP_GET_VARS['mID']) || (isset($HTTP_GET_VARS['mID']) && ($HTTP_GET_VARS['mID'] == $distributors['distributors_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
      $distributor_products_query = tep_db_query("select count(*) as products_count from " . TABLE_PRODUCTS . " where distributors_id = '" . (int)$distributors['distributors_id'] . "'");
      $distributor_products = tep_db_fetch_array($distributor_products_query);

      $mInfo_array = array_merge($distributors, $distributor_products);
      $mInfo = new objectInfo($mInfo_array);
    }

    if (isset($mInfo) && is_object($mInfo) && ($distributors['distributors_id'] == $mInfo->distributors_id)) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_DISTRIBUTORS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $distributors['distributors_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_DISTRIBUTORS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $distributors['distributors_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $distributors['distributors_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($mInfo) && is_object($mInfo) && ($distributors['distributors_id'] == $mInfo->distributors_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_DISTRIBUTORS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $distributors['distributors_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $distributors_split->display_count($distributors_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_DISTRIBUTORS); ?></td>
                    <td class="smallText" align="right"><?php echo $distributors_split->display_links($distributors_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_DISTRIBUTORS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->distributors_id . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . 'New Distributor' . '</b>');

      $contents = array('form' => tep_draw_form('distributors', FILENAME_DISTRIBUTORS, 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br>' . 'Distributors Name' . '<br>' . tep_draw_input_field('distributors_name'));
      $contents[] = array('text' => '<br>' . 'Distributors Image' . '<br>' . tep_draw_file_field('distributors_image'));

      $distributor_inputs_string = '';
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $distributor_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']);
      }

      
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_DISTRIBUTORS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $HTTP_GET_VARS['mID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_DISTRIBUTOR . '</b>');

      $contents = array('form' => tep_draw_form('distributors', FILENAME_DISTRIBUTORS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->distributors_id . '&action=save', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . 'Distributor\'s Name' . '<br>' . tep_draw_input_field('distributors_name', $mInfo->distributors_name));
      $contents[] = array('text' => '<br>' . 'Distributor\'s Image' . '<br>' . tep_draw_file_field('distributors_image') . '<br>' . $mInfo->distributors_image);

      $distributor_inputs_string = '';
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $distributor_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;';
      }

    
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_DISTRIBUTORS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->distributors_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_DISTRIBUTOR . '</b>');

      $contents = array('form' => tep_draw_form('distributors', FILENAME_DISTRIBUTORS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->distributors_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $mInfo->distributors_name . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE);

      if ($mInfo->products_count > 0) {
        $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_products') . ' ' . TEXT_DELETE_PRODUCTS);
        $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $mInfo->products_count));
      }

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_DISTRIBUTORS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->distributors_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($mInfo) && is_object($mInfo)) {
        $heading[] = array('text' => '<b>' . $mInfo->distributors_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_DISTRIBUTORS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->distributors_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_DISTRIBUTORS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->distributors_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($mInfo->date_added));
        if (tep_not_null($mInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($mInfo->last_modified));
        $contents[] = array('text' => '<br>' . tep_info_image($mInfo->distributors_image, $mInfo->distributors_name));
        $contents[] = array('text' => '<br>' . TEXT_PRODUCTS . ' ' . $mInfo->products_count);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
