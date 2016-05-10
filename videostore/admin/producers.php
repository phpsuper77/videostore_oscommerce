<?php
/*
  $Id: producers.php,v 1.55 2003/06/29 22:50:52 hpdl Exp $

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
        if (isset($HTTP_GET_VARS['mID'])) $producers_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);
        $producers_name = tep_db_prepare_input($HTTP_POST_VARS['producers_name']);
		$producers_description = tep_db_prepare_input($HTTP_POST_VARS['producers_description']);

        $sql_data_array = array('producers_name' => $producers_name,'producers_description' => $producers_description);

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_PRODUCERS, $sql_data_array);
          $producers_id = tep_db_insert_id();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_PRODUCERS, $sql_data_array, 'update', "producers_id = '" . (int)$producers_id . "'");
        }

        if ($producers_image = new upload('producers_image', DIR_FS_CATALOG_IMAGES)) {
          tep_db_query("update " . TABLE_PRODUCERS . " set producers_image = '" . $producers_image->filename . "' where producers_id = '" . (int)$producers_id . "'");
        }

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {

          $language_id = $languages[$i]['id'];

          if ($action == 'insert') {
            $insert_sql_data = array('producers_id' => $producers_id,
                                     'languages_id' => $language_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);


          } elseif ($action == 'save') {

          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('producers');
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCERS, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'mID=' . $producers_id));
        break;
      case 'deleteconfirm':
        $producers_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);

        if (isset($HTTP_POST_VARS['delete_image']) && ($HTTP_POST_VARS['delete_image'] == 'on')) {
          $producer_query = tep_db_query("select producers_image from " . TABLE_PRODUCERS . " where producers_id = '" . (int)$producers_id . "'");
          $producer = tep_db_fetch_array($producer_query);

          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $producer['producers_image'];

          if (file_exists($image_location)) @unlink($image_location);
        }

        tep_db_query("delete from " . TABLE_PRODUCERS . " where producers_id = '" . (int)$producers_id . "'");

        if (isset($HTTP_POST_VARS['delete_products']) && ($HTTP_POST_VARS['delete_products'] == 'on')) {
          $products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where producers_id = '" . (int)$producers_id . "'");
          while ($products = tep_db_fetch_array($products_query)) {
            tep_remove_product($products['products_id']);
          }
        } else {
          tep_db_query("update " . TABLE_PRODUCTS . " set producers_id = '' where producers_id = '" . (int)$producers_id . "'");
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('producers');
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCERS, 'page=' . $HTTP_GET_VARS['page']));
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $producers_query_raw = "select producers_id,producers_description, producers_name, producers_image, date_added, last_modified from " . TABLE_PRODUCERS . " order by producers_name";
  $producers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $producers_query_raw, $producers_query_numrows);
  $producers_query = tep_db_query($producers_query_raw);
  while ($producers = tep_db_fetch_array($producers_query)) {
    if ((!isset($HTTP_GET_VARS['mID']) || (isset($HTTP_GET_VARS['mID']) && ($HTTP_GET_VARS['mID'] == $producers['producers_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
      $producer_products_query = tep_db_query("select count(*) as products_count from " . TABLE_PRODUCTS . " where producers_id = '" . (int)$producers['producers_id'] . "'");
      $producer_products = tep_db_fetch_array($producer_products_query);

      $mInfo_array = array_merge($producers, $producer_products);
      $mInfo = new objectInfo($mInfo_array);
    }

    if (isset($mInfo) && is_object($mInfo) && ($producers['producers_id'] == $mInfo->producers_id)) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $producers['producers_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $producers['producers_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $producers['producers_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($mInfo) && is_object($mInfo) && ($producers['producers_id'] == $mInfo->producers_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_PRODUCERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $producers['producers_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $producers_split->display_count($producers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCERS); ?></td>
                    <td class="smallText" align="right"><?php echo $producers_split->display_links($producers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->producers_id . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
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
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_PRODUCER . '</b>');

      $contents = array('form' => tep_draw_form('producers', FILENAME_PRODUCERS, 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_PRODUCERS_NAME . '<br>' . tep_draw_input_field('producers_name'));
      $contents[] = array('text' => '<br>' . 'Producers Description :' . '<br>' . tep_draw_textarea_field('producers_description', 'soft', '40', '10'));
      $contents[] = array('text' => '<br>' . TEXT_PRODUCERS_IMAGE . '<br>' . tep_draw_file_field('producers_image'));

      $producer_inputs_string = '';
      $languages = tep_get_languages();

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_PRODUCERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $HTTP_GET_VARS['mID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_PRODUCER . '</b>');

      $contents = array('form' => tep_draw_form('producers', FILENAME_PRODUCERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->producers_id . '&action=save', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_PRODUCERS_NAME . '<br>' . tep_draw_input_field('producers_name', $mInfo->producers_name));
      $contents[] = array('text' => '<br>' . 'Producers Description :' . '<br>' . tep_draw_textarea_field('producers_description', 'soft', '40', '10',$mInfo->producers_description));
      $contents[] = array('text' => '<br>' . TEXT_PRODUCERS_IMAGE . '<br>' . tep_draw_file_field('producers_image') . '<br>' . $mInfo->producers_image);

      $producer_inputs_string = '';
      $languages = tep_get_languages();



      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_PRODUCERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->producers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_PRODUCER . '</b>');

      $contents = array('form' => tep_draw_form('producers', FILENAME_PRODUCERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->producers_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $mInfo->producers_name . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE);

      if ($mInfo->products_count > 0) {
        $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_products') . ' ' . TEXT_DELETE_PRODUCTS);
        $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $mInfo->products_count));
      }

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_PRODUCERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->producers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($mInfo) && is_object($mInfo)) {
        $heading[] = array('text' => '<b>' . $mInfo->producers_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_PRODUCERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->producers_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_PRODUCERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->producers_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($mInfo->date_added));
        if (tep_not_null($mInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($mInfo->last_modified));
        $contents[] = array('text' => '<br>' . tep_info_image($mInfo->producers_image, $mInfo->producers_name));
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
