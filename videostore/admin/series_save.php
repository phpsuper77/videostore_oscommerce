<?php
/*
  $Id: series.php,v 1.55 2003/06/29 22:50:52 hpdl Exp $

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
        if (isset($HTTP_GET_VARS['mID'])) $series_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);
        $series_name = tep_db_prepare_input($HTTP_POST_VARS['series_name']);

        $sql_data_array = array('series_name' => $series_name);

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_SERIES, $sql_data_array);
          $series_id = tep_db_insert_id();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_SERIES, $sql_data_array, 'update', "series_id = '" . (int)$series_id . "'");
        }

        if ($series_image = new upload('series_image', DIR_FS_CATALOG_IMAGES)) {
          tep_db_query("update " . TABLE_SERIES . " set series_image = '" . $series_image->filename . "' where series_id = '" . (int)$series_id . "'");
        }

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          
          $language_id = $languages[$i]['id'];

          

          if ($action == 'insert') {
            $insert_sql_data = array('series_id' => $series_id,
                                     'languages_id' => $language_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            
          } elseif ($action == 'save') {
            
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('series');
        }

        tep_redirect(tep_href_link(FILENAME_SERIES, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'mID=' . $series_id));
        break;
      case 'deleteconfirm':
        $series_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);

        if (isset($HTTP_POST_VARS['delete_image']) && ($HTTP_POST_VARS['delete_image'] == 'on')) {
          $series_query = tep_db_query("select series_image from " . TABLE_SERIES . " where series_id = '" . (int)$series_id . "'");
          $series = tep_db_fetch_array($series_query);

          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $series['series_image'];

          if (file_exists($image_location)) @unlink($image_location);
        }

        tep_db_query("delete from " . TABLE_SERIES . " where series_id = '" . (int)$series_id . "'");
        

        if (isset($HTTP_POST_VARS['delete_products']) && ($HTTP_POST_VARS['delete_products'] == 'on')) {
          $products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where series_id = '" . (int)$series_id . "'");
          while ($products = tep_db_fetch_array($products_query)) {
            tep_remove_product($products['products_id']);
          }
        } else {
          tep_db_query("update " . TABLE_PRODUCTS . " set series_id = '' where series_id = '" . (int)$series_id . "'");
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('series');
        }

        tep_redirect(tep_href_link(FILENAME_SERIES, 'page=' . $HTTP_GET_VARS['page']));
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SERIES; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $series_query_raw = "select series_id, series_name, series_image, date_added, last_modified from " . TABLE_SERIES . " order by series_name";
  $series_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $series_query_raw, $series_query_numrows);
  $series_query = tep_db_query($series_query_raw);
  while ($series = tep_db_fetch_array($series_query)) {
    if ((!isset($HTTP_GET_VARS['mID']) || (isset($HTTP_GET_VARS['mID']) && ($HTTP_GET_VARS['mID'] == $series['series_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
      $series_products_query = tep_db_query("select count(*) as products_count from " . TABLE_PRODUCTS . " where series_id = '" . (int)$series['series_id'] . "'");
      $series_products = tep_db_fetch_array($series_products_query);

      $mInfo_array = array_merge($series, $series_products);
      $mInfo = new objectInfo($mInfo_array);
    }

    if (isset($mInfo) && is_object($mInfo) && ($series['series_id'] == $mInfo->series_id)) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SERIES, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $series['series_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SERIES, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $series['series_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $series['series_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($mInfo) && is_object($mInfo) && ($series['series_id'] == $mInfo->series_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_SERIES, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $series['series_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $series_split->display_count($series_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SERIES); ?></td>
                    <td class="smallText" align="right"><?php echo $series_split->display_links($series_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_SERIES, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->series_id . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
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
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_SERIES . '</b>');

      $contents = array('form' => tep_draw_form('series', FILENAME_SERIES, 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_SERIES_NAME . '<br>' . tep_draw_input_field('series_name'));
      $contents[] = array('text' => '<br>' . 'Series Description :' . '<br>' . tep_draw_textarea_field('series_description', 'soft', '70', '15'));
	  $contents[] = array('text' => '<br>' . TEXT_SERIES_IMAGE . '<br>' . tep_draw_file_field('series_image'));

      $series_inputs_string = '';
      $languages = tep_get_languages();

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_SERIES, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $HTTP_GET_VARS['mID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_SERIES . '</b>');

      $contents = array('form' => tep_draw_form('series', FILENAME_SERIES, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->series_id . '&action=save', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_SERIES_NAME . '<br>' . tep_draw_input_field('series_name', $mInfo->series_name));
      $contents[] = array('text' => '<br>' . TEXT_SERIES_IMAGE . '<br>' . tep_draw_file_field('series_image') . '<br>' . $mInfo->series_image);

      $series_inputs_string = '';
      $languages = tep_get_languages();
	  
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_SERIES, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->series_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_SERIES . '</b>');

      $contents = array('form' => tep_draw_form('series', FILENAME_SERIES, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->series_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $mInfo->series_name . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE);

      if ($mInfo->products_count > 0) {
        $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_products') . ' ' . TEXT_DELETE_PRODUCTS);
        $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $mInfo->products_count));
      }

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_SERIES, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->series_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($mInfo) && is_object($mInfo)) {
        $heading[] = array('text' => '<b>' . $mInfo->series_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SERIES, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->series_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_SERIES, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->series_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($mInfo->date_added));
        if (tep_not_null($mInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($mInfo->last_modified));
        $contents[] = array('text' => '<br>' . tep_info_image($mInfo->series_image, $mInfo->series_name));
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
