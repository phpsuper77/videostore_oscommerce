<?php
/*
  $Id: categories.php,v 1.146 2003/07/11 14:40:27 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php'); /*This Also Requires:
  includes/configure.php
  includes/filenames.php
  includes/database_tables.php
  includes/functions/database.php
  includes/functions/general.php
  includes/functions/html_output.php
  includes/classes/logger.php
  includes/classes/shopping_cart.php
  includes/functions/compatibility.php
  includes/classes/sessions.php
  includes/fuctions/sessions.php
   AND MORE...
*/

// CATEGORIES DESCRIPTION v1.5
  require('includes/functions/categories_description.php');
// END CATEGORIES DESCRIPTION v1.5
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if (isset($HTTP_GET_VARS['pID'])) {
            tep_set_product_status($HTTP_GET_VARS['pID'], $HTTP_GET_VARS['flag']);
          }

          if (USE_CACHE == 'true') {
		  
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID']));
        break;
// CATEGORIES DESCRIPTION v1.5
      case 'new_category':
      case 'edit_category':
        if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')
          $HTTP_GET_VARS['action']=$HTTP_GET_VARS['action'] . '_ACD';
        break;
// END CATEGORIES DESCRIPTION v1.5		
      case 'insert_category':
      case 'update_category':
// CATEGORIES DESCRIPTION v1.5	  
        if ( ($HTTP_POST_VARS['edit_x']) || ($HTTP_POST_VARS['edit_y']) ) {
          $HTTP_GET_VARS['action'] = 'edit_category_ACD';
        } else {	  
// END CATEGORIES DESCRIPTION v1.5	  
          if (isset($HTTP_POST_VARS['categories_id'])) $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
// CATEGORIES DESCRIPTION v1.5
          if ($categories_id == '') {
             $categories_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
           }
// END CATEGORIES DESCRIPTION v1.5
          $sort_order = tep_db_prepare_input($HTTP_POST_VARS['sort_order']);

          $sql_data_array = array('sort_order' => $sort_order);

          if ($action == 'insert_category') {
            $insert_sql_data = array('parent_id' => $current_category_id,
                                     'date_added' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_CATEGORIES, $sql_data_array);

           $categories_id = tep_db_insert_id();
          } elseif ($action == 'update_category') {
            $update_sql_data = array('last_modified' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $update_sql_data);

            tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
          }

          $languages = tep_get_languages();
          for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            $categories_name_array = $HTTP_POST_VARS['categories_name'];

            $language_id = $languages[$i]['id'];

            $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]));
// CATEGORIES DESCRIPTION v1.5
            if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
              $sql_data_array = array('categories_name' => tep_db_prepare_input($HTTP_POST_VARS['categories_name'][$language_id]),
                                      'categories_heading_title' => tep_db_prepare_input($HTTP_POST_VARS['categories_heading_title'][$language_id]),
                                      'categories_description' => tep_db_prepare_input($HTTP_POST_VARS['categories_description'][$language_id]));
            }
// END CATEGORIES DESCRIPTION v1.5
            if ($action == 'insert_category') {
              $insert_sql_data = array('categories_id' => $categories_id,
                                       'language_id' => $languages[$i]['id']);

              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

              tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
            } elseif ($action == 'update_category') {
              tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
            }
          }
// CATEGORIES DESCRIPTION v1.5
//REMOVED          if ($categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES)) {
//REMOVED            tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
//REMOVED          }
          if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
            tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . $HTTP_POST_VARS['categories_image'] . "' where categories_id = '" .  tep_db_input($categories_id) . "'");
            $categories_image = '';
      } else {
        if ($categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES)) {
          tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
        }
       }
// END CATEGORIES DESCRIPTION v1.5
          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }

          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
// CATEGORIES DESCRIPTION v1.5 
        }       
// END CATEGORIES DESCRIPTION v1.5
		break;		
      case 'delete_category_confirm':
        if (isset($HTTP_POST_VARS['categories_id'])) {
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

          $categories = tep_get_category_tree($categories_id, '', '0', '', true);
          $products = array();
          $products_delete = array();

          for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
            $product_ids_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$categories[$i]['id'] . "'");

            while ($product_ids = tep_db_fetch_array($product_ids_query)) {
              $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
            }
          }

          reset($products);
          while (list($key, $value) = each($products)) {
            $category_ids = '';

            for ($i=0, $n=sizeof($value['categories']); $i<$n; $i++) {
              $category_ids .= "'" . (int)$value['categories'][$i] . "', ";
            }
            $category_ids = substr($category_ids, 0, -2);

            $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$key . "' and categories_id not in (" . $category_ids . ")");
            $check = tep_db_fetch_array($check_query);
            if ($check['total'] < '1') {
              $products_delete[$key] = $key;
            }
          }

// removing categories can be a lengthy process
          tep_set_time_limit(0);
          for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
            tep_remove_category($categories[$i]['id']);
          }

          reset($products_delete);
          while (list($key) = each($products_delete)) {
            tep_remove_product($key);
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'delete_product_confirm':
        if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['product_categories']) && is_array($HTTP_POST_VARS['product_categories'])) {
          $product_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $product_categories = $HTTP_POST_VARS['product_categories'];

          for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
            tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
          }

          $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
          $product_categories = tep_db_fetch_array($product_categories_query);

          if ($product_categories['total'] == '0') {
            tep_remove_product($product_id);
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'move_category_confirm':
        if (isset($HTTP_POST_VARS['categories_id']) && ($HTTP_POST_VARS['categories_id'] != $HTTP_POST_VARS['move_to_category_id'])) {
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
          $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

          $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));

          if (in_array($categories_id, $path)) {
            $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
          } else {
            tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");

            if (USE_CACHE == 'true') {
              tep_reset_cache_block('categories');
              tep_reset_cache_block('also_purchased');
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
          }
        }

        break;
      case 'move_product_confirm':
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

        $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
        $duplicate_check = tep_db_fetch_array($duplicate_check_query);
        if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
        break;
      case 'insert_product':
      case 'update_product':
        if (isset($HTTP_POST_VARS['edit_x']) || isset($HTTP_POST_VARS['edit_y'])) {
          $action = 'new_product';
        } else {
        
// BOF MaxiDVD: Modified For Ultimate Images Pack!
            if ($HTTP_POST_VARS['delete_image'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image']);
            }
            if ($HTTP_POST_VARS['delete_image_med'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_med']);
            }
            if ($HTTP_POST_VARS['delete_image_lrg'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_lrg']);
            }
            if ($HTTP_POST_VARS['delete_image_sm_1'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_1']);
            }
            if ($HTTP_POST_VARS['delete_image_xl_1'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_1']);
            }
            if ($HTTP_POST_VARS['delete_image_sm_2'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_2']);
            }
            if ($HTTP_POST_VARS['delete_image_xl_2'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_2']);
            }
            if ($HTTP_POST_VARS['delete_image_sm_3'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_3']);
            }
            if ($HTTP_POST_VARS['delete_image_xl_3'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_3']);
            }
            if ($HTTP_POST_VARS['delete_image_sm_4'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_4']);
            }
            if ($HTTP_POST_VARS['delete_image_xl_4'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_4']);
            }
            if ($HTTP_POST_VARS['delete_image_sm_5'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_5']);
            }
            if ($HTTP_POST_VARS['delete_image_xl_5'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_5']);
            }
            if ($HTTP_POST_VARS['delete_image_sm_6'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_6']);
            }
            if ($HTTP_POST_VARS['delete_image_xl_6'] == 'yes') {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_6']);
            }
// EOF MaxiDVD: Modified For Ultimate Images Pack!
          if (isset($HTTP_GET_VARS['pID'])) $products_id = tep_db_prepare_input($HTTP_GET_VARS['pID']);
          $products_date_available = tep_db_prepare_input($HTTP_POST_VARS['products_date_available']);

          $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

          $sql_data_array = array('products_quantity' => tep_db_prepare_input($HTTP_POST_VARS['products_quantity']),
          'products_model' => tep_db_prepare_input($HTTP_POST_VARS['products_model']),
          'products_price' => tep_db_prepare_input($HTTP_POST_VARS['products_price']),
          'products_date_available' => $products_date_available,
          'products_weight' => tep_db_prepare_input($HTTP_POST_VARS['products_weight']),
          'products_status' => tep_db_prepare_input($HTTP_POST_VARS['products_status']),
          'products_tax_class_id' => tep_db_prepare_input($HTTP_POST_VARS['products_tax_class_id']),
          'products_media_type_id' => tep_db_prepare_input($HTTP_POST_VARS['products_media_type_id']),
		  'products_video_format_id' => tep_db_prepare_input($HTTP_POST_VARS['products_video_format_id']),
		  'products_region_code_id' => tep_db_prepare_input($HTTP_POST_VARS['products_region_code_id']),
		  'products_set_type_id' => tep_db_prepare_input($HTTP_POST_VARS['products_set_type_id']),
		  'products_packaging_type_id' => tep_db_prepare_input($HTTP_POST_VARS['products_packaging_type_id']),
		  'manufacturers_id' => tep_db_prepare_input($HTTP_POST_VARS['manufacturers_id']),
		  'distributors_id' => tep_db_prepare_input($HTTP_POST_VARS['distributors_id']),
		  'producers_id' => tep_db_prepare_input($HTTP_POST_VARS['producers_id']),
		  'series_id' => tep_db_prepare_input($HTTP_POST_VARS['series_id']),
          'products_run_time' => tep_db_prepare_input($HTTP_POST_VARS['products_run_time']),
		  'products_out_of_print' => tep_db_prepare_input($HTTP_POST_VARS['products_out_of_print']),
		  'products_audio_id' => tep_db_prepare_input($HTTP_POST_VARS['products_audio_id']),
		  'products_aspect_ratio_id' => tep_db_prepare_input($HTTP_POST_VARS['products_aspect_ratio_id']),
		  'products_warehouse_location' => tep_db_prepare_input($HTTP_POST_VARS['products_warehouse_location']),
		  'products_export_id' => tep_db_prepare_input($HTTP_POST_VARS['products_export_id']),
		  'products_audio_languages' => tep_db_prepare_input($HTTP_POST_VARS['products_audio_languages']),
		  'products_subtitle_languages' => tep_db_prepare_input($HTTP_POST_VARS['products_subtitle_languages']),
		  'products_closed_captioned' => tep_db_prepare_input($HTTP_POST_VARS['products_closed_captioned']),
		  'products_pc_bonus' => tep_db_prepare_input($HTTP_POST_VARS['products_pc_bonus']),
		  'products_release_date' => tep_db_prepare_input($HTTP_POST_VARS['products_release_date']),
		  'products_isbn' => tep_db_prepare_input($HTTP_POST_VARS['products_isbn']),
		  'products_upc' => tep_db_prepare_input($HTTP_POST_VARS['products_upc']),
		  'products_asin' => tep_db_prepare_input($HTTP_POST_VARS['products_asin']),
		  'products_color' => tep_db_prepare_input($HTTP_POST_VARS['products_color']),
		  'products_rated' => tep_db_prepare_input($HTTP_POST_VARS['products_rated']),
		  'products_byline' => tep_db_prepare_input($HTTP_POST_VARS['products_byline']),
		  'products_summary' => tep_db_prepare_input($HTTP_POST_VARS['products_summary']),
		  );

// BOF MaxiDVD: Modified For Ultimate Images Pack!
       if (($HTTP_POST_VARS['unlink_image'] == 'yes') or ($HTTP_POST_VARS['delete_image'] == 'yes')) {
            $sql_data_array['products_image'] = '';
           } else {
         if (isset($HTTP_POST_VARS['products_image']) && tep_not_null($HTTP_POST_VARS['products_image']) && ($HTTP_POST_VARS['products_image'] != 'none')) {
            $sql_data_array['products_image'] = tep_db_prepare_input($HTTP_POST_VARS['products_image']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_med'] == 'yes') or ($HTTP_POST_VARS['delete_image_med'] == 'yes')) {
            $sql_data_array['products_image_med'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_med']) && tep_not_null($HTTP_POST_VARS['products_image_med']) && ($HTTP_POST_VARS['products_image_med'] != 'none')) {
            $sql_data_array['products_image_med'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_med']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_lrg'] == 'yes') or ($HTTP_POST_VARS['delete_image_lrg'] == 'yes')) {
            $sql_data_array['products_image_lrg'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_lrg']) && tep_not_null($HTTP_POST_VARS['products_image_lrg']) && ($HTTP_POST_VARS['products_image_lrg'] != 'none')) {
            $sql_data_array['products_image_lrg'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_lrg']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_sm_1'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_1'] == 'yes')) {
            $sql_data_array['products_image_sm_1'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_sm_1']) && tep_not_null($HTTP_POST_VARS['products_image_sm_1']) && ($HTTP_POST_VARS['products_image_sm_1'] != 'none')) {
            $sql_data_array['products_image_sm_1'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_sm_1']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_xl_1'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_1'] == 'yes')) {
            $sql_data_array['products_image_xl_1'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_xl_1']) && tep_not_null($HTTP_POST_VARS['products_image_xl_1']) && ($HTTP_POST_VARS['products_image_xl_1'] != 'none')) {
            $sql_data_array['products_image_xl_1'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_xl_1']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_sm_2'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_2'] == 'yes')) {
            $sql_data_array['products_image_sm_2'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_sm_2']) && tep_not_null($HTTP_POST_VARS['products_image_sm_2']) && ($HTTP_POST_VARS['products_image_sm_2'] != 'none')) {
            $sql_data_array['products_image_sm_2'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_sm_2']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_xl_2'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_2'] == 'yes')) {
            $sql_data_array['products_image_xl_2'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_xl_2']) && tep_not_null($HTTP_POST_VARS['products_image_xl_2']) && ($HTTP_POST_VARS['products_image_xl_2'] != 'none')) {
            $sql_data_array['products_image_xl_2'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_xl_2']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_sm_3'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_3'] == 'yes')) {
            $sql_data_array['products_image_sm_3'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_sm_3']) && tep_not_null($HTTP_POST_VARS['products_image_sm_3']) && ($HTTP_POST_VARS['products_image_sm_3'] != 'none')) {
            $sql_data_array['products_image_sm_3'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_sm_3']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_xl_3'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_3'] == 'yes')) {
            $sql_data_array['products_image_xl_3'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_xl_3']) && tep_not_null($HTTP_POST_VARS['products_image_xl_3']) && ($HTTP_POST_VARS['products_image_xl_3'] != 'none')) {
            $sql_data_array['products_image_xl_3'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_xl_3']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_sm_4'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_4'] == 'yes')) {
            $sql_data_array['products_image_sm_4'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_sm_4']) && tep_not_null($HTTP_POST_VARS['products_image_sm_4']) && ($HTTP_POST_VARS['products_image_sm_4'] != 'none')) {
            $sql_data_array['products_image_sm_4'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_sm_4']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_xl_4'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_4'] == 'yes')) {
            $sql_data_array['products_image_xl_4'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_xl_4']) && tep_not_null($HTTP_POST_VARS['products_image_xl_4']) && ($HTTP_POST_VARS['products_image_xl_4'] != 'none')) {
            $sql_data_array['products_image_xl_4'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_xl_4']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_sm_5'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_5'] == 'yes')) {
            $sql_data_array['products_image_sm_5'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_sm_5']) && tep_not_null($HTTP_POST_VARS['products_image_sm_5']) && ($HTTP_POST_VARS['products_image_sm_5'] != 'none')) {
            $sql_data_array['products_image_sm_5'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_sm_5']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_xl_5'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_5'] == 'yes')) {
            $sql_data_array['products_image_xl_5'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_xl_5']) && tep_not_null($HTTP_POST_VARS['products_image_xl_5']) && ($HTTP_POST_VARS['products_image_xl_5'] != 'none')) {
            $sql_data_array['products_image_xl_5'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_xl_5']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_sm_6'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_6'] == 'yes')) {
            $sql_data_array['products_image_sm_6'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_sm_6']) && tep_not_null($HTTP_POST_VARS['products_image_sm_6']) && ($HTTP_POST_VARS['products_image_sm_6'] != 'none')) {
            $sql_data_array['products_image_sm_6'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_sm_6']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_xl_6'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_6'] == 'yes')) {
            $sql_data_array['products_image_xl_6'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_xl_6']) && tep_not_null($HTTP_POST_VARS['products_image_xl_6']) && ($HTTP_POST_VARS['products_image_xl_6'] != 'none')) {
            $sql_data_array['products_image_xl_6'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_xl_6']);
          }
          }
// EOF MaxiDVD: Modified For Ultimate Images Pack!

          if ($action == 'insert_product') {
            $insert_sql_data = array('products_date_added' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
            $products_id = tep_db_insert_id();

            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$current_category_id . "')");
          } elseif ($action == 'update_product') {
            $update_sql_data = array('products_last_modified' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $update_sql_data);

            tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
          }

          $languages = tep_get_languages();
          for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            $language_id = $languages[$i]['id'];

            $sql_data_array = array('products_name' => tep_db_prepare_input($HTTP_POST_VARS['products_name'][$language_id]),
			                        'products_name_prefix' => tep_db_prepare_input($HTTP_POST_VARS['products_name_prefix'][$language_id]),
									'products_name_suffix' => tep_db_prepare_input($HTTP_POST_VARS['products_name_suffix'][$language_id]),
                                    'products_description' => tep_db_prepare_input($HTTP_POST_VARS['products_description'][$language_id]),
									'products_head_title_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_title_tag'][$language_id]),
         							'products_head_desc_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_desc_tag'][$language_id]),
         							'products_head_keywords_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_keywords_tag'][$language_id]),
                                    'products_url' => tep_db_prepare_input($HTTP_POST_VARS['products_url'][$language_id]));

            if ($action == 'insert_product') {
              $insert_sql_data = array('products_id' => $products_id,
                                       'language_id' => $language_id);

              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

              tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
            } elseif ($action == 'update_product') {
              tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
            }
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }

          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
        }
        break;
      case 'copy_to_confirm':
        if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['categories_id'])) {
          $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

          if ($HTTP_POST_VARS['copy_as'] == 'link') {
            if ($categories_id != $current_category_id) {
              $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
              $check = tep_db_fetch_array($check_query);
              if ($check['total'] < '1') {
                tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
              }
            } else {
              $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
            }
          } elseif ($HTTP_POST_VARS['copy_as'] == 'duplicate') {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
            $product_query = tep_db_query("select products_quantity, products_model, products_image, products_image_med, products_image_lrg, products_image_sm_1, products_image_xl_1, products_image_sm_2, products_image_xl_2, products_image_sm_3, products_image_xl_3, products_image_sm_4, products_image_xl_4, products_image_sm_5, products_image_xl_5, products_image_sm_6, products_image_xl_6, products_price, products_date_available, products_weight, products_tax_class_id, manufacturers_id, distributors_id, producers_id, series_id, products_media_type_id, products_video_format_id, products_region_code_id, products_set_type_id, products_packaging_type_id, products_run_time, products_out_of_print, products_audio_id, products_aspect_ratio_id, products_warehouse_location, products_export_id, products_audio_languages, products_subtitle_languages, products_closed_captioned, products_pc_bonus, products_release_date, products_isbn, products_upc, products_asin, products_color, products_rated, products_byline, products_summary from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
            $product = tep_db_fetch_array($product_query);
            tep_db_query("insert into " . TABLE_PRODUCTS . " (products_quantity, products_model, products_image, products_image_med, products_image_lrg, products_image_sm_1, products_image_xl_1, products_image_sm_2, products_image_xl_2, products_image_sm_3, products_image_xl_3, products_image_sm_4, products_image_xl_4, products_image_sm_5, products_image_xl_5, products_image_sm_6, products_image_xl_6, products_price, products_date_added, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, distributors_id, producers_id, series_id, products_media_type_id, products_video_format_id, products_region_code_id, products_set_type_id, products_packaging_type_id, products_run_time, products_out_of_print, products_audio_id, products_aspect_ratio_id, products_warehouse_location, products_export_id, products_audio_languages, products_subtitle_languages, products_closed_captioned, products_pc_bonus, products_release_date, products_isbn, products_upc, products_asin, products_color, products_rated, products_byline, products_summary) values ('" . tep_db_input($product['products_quantity']) . "', '" . tep_db_input($product['products_model']) . "', '" . tep_db_input($product['products_image']) . "', '" . tep_db_input($product['products_image_med']) . "', '" . tep_db_input($product['products_image_lrg']) . "', '" . tep_db_input($product['products_image_sm_1']) . "', '" . tep_db_input($product['products_image_xl_1']) . "', '" . tep_db_input($product['products_image_sm_2']) . "',
             '" . tep_db_input($product['products_image_xl_2']) . "', '" . tep_db_input($product['products_image_sm_3']) . "', '" . tep_db_input($product['products_image_xl_3']) . "', '" . tep_db_input($product['products_image_sm_4']) . "', '" . tep_db_input($product['products_image_xl_4']) . "', '" . tep_db_input($product['products_image_sm_5']) . "', '" . tep_db_input($product['products_image_xl_5']) . "', '" . tep_db_input($product['products_image_sm_6']) . "', '" . tep_db_input($product['products_image_xl_6']) . "', '" . tep_db_input($product['products_price']) . "',  now(), '" . tep_db_input($product['products_date_available']) . "', '" . tep_db_input($product['products_weight']) . "', '0', '" . (int)$product['products_tax_class_id'] . "', '" . (int)$product['manufacturers_id'] . "', '" . (int)$product['distributors_id'] . "', '" . (int)$product['producers_id'] . "', '" . (int)$product['series_id'] . "', '" . (int)$product['products_media_type_id'] . "', '" . (int)$product['products_video_format_id'] . "', '" . (int)$product['products_region_code_id'] . "', '" . (int)$product['products_set_type_id'] . "', '" . (int)$product['products_packaging_type_id'] . "', '" . tep_db_input($product['products_run_time']) . "', '" . tep_db_input($product['products_out_of_print']) . "', '" . tep_db_input($product['products_audio_id']) . "', '" . tep_db_input($product['products_aspect_ratio_id']) . "', '" . tep_db_input($product['products_warehouse_location']) . "', '" . tep_db_input($product['products_export_id']) . "', '" . tep_db_input($product['products_audio_languages']) . "', '" . tep_db_input($product['products_subtitle_languages']) . "', '" . tep_db_input($product['products_closed_captioned']) . "', '" . tep_db_input($product['products_pc_bonus']) . "', '" . tep_db_input($product['products_release_date']) . "', '" . tep_db_input($product['products_isbn']) . "', '" . tep_db_input($product['products_upc']) . "', '" . tep_db_input($product['products_asin']) . "', '" . tep_db_input($product['products_color']) . "', '" . tep_db_input($product['products_rated']) . "', '" . tep_db_input($product['products_byline']) . "', '" . tep_db_input($product['products_summary']) . "')");
// BOF MaxiDVD: Modified For Ultimate Images Pack!
            $dup_products_id = tep_db_insert_id();

            $description_query = tep_db_query("select language_id, products_name, products_name_prefix, products_name_suffix, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
            while ($description = tep_db_fetch_array($description_query)) {
              tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_name_prefix, products_name_suffix,  products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url, products_viewed) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_head_title_tag']) . "', '" . tep_db_input($description['products_head_desc_tag']) . "', '" . tep_db_input($description['products_head_keywords_tag']) . "', '" . tep_db_input($description['products_url']) . "', '0')");
            }

            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");
            $products_id = $dup_products_id;
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
        break;
        
// BOF MaxiDVD: Modified For Ultimate Images Pack!
      case 'new_product_preview':
// copy image only if modified
   if (($HTTP_POST_VARS['unlink_image'] == 'yes') or ($HTTP_POST_VARS['delete_image'] == 'yes')) {
        $products_image = '';
        $products_image_name = '';
        } elseif ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')) {
        $products_image = new upload('products_image');
        $products_image->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image->parse() && $products_image->save()) {
          $products_image_name = $products_image->filename;
        } else {
          $products_image_name = (isset($HTTP_POST_VARS['products_previous_image']) ? $HTTP_POST_VARS['products_previous_image'] : '');
        }
        } else {
          if (isset($HTTP_POST_VARS['products_image']) && tep_not_null($HTTP_POST_VARS['products_image']) && ($HTTP_POST_VARS['products_image'] != 'none')) {
            $products_image_name = $HTTP_POST_VARS['products_image'];
          } else {
            $products_image_name = (isset($HTTP_POST_VARS['products_previous_image']) ? $HTTP_POST_VARS['products_previous_image'] : '');
          }
        }
   if (($HTTP_POST_VARS['unlink_image_med'] == 'yes') or ($HTTP_POST_VARS['delete_image_med'] == 'yes')) {
        $products_image_med = '';
        $products_image_med_name = '';
        } elseif ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')) {
        $products_image_med = new upload('products_image_med');
        $products_image_med->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_med->parse() && $products_image_med->save()) {
          $products_image_med_name = $products_image_med->filename;
        } else {
          $products_image_med_name = (isset($HTTP_POST_VARS['products_previous_image_med']) ? $HTTP_POST_VARS['products_previous_image_med'] : '');
        }
        } else {
          if (isset($HTTP_POST_VARS['products_image_med']) && tep_not_null($HTTP_POST_VARS['products_image_med']) && ($HTTP_POST_VARS['products_image_med'] != 'none')) {
            $products_image_med_name = $HTTP_POST_VARS['products_image_med'];
          } else {
            $products_image_med_name = (isset($HTTP_POST_VARS['products_previous_image_med']) ? $HTTP_POST_VARS['products_previous_image_med'] : '');
          }
        }
   if (($HTTP_POST_VARS['unlink_image_lrg'] == 'yes') or ($HTTP_POST_VARS['delete_image_lrg'] == 'yes')) {
        $products_image_lrg = '';
        $products_image_lrg_name = '';
        } elseif ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')) {
        $products_image_lrg = new upload('products_image_lrg');
        $products_image_lrg->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_lrg->parse() && $products_image_lrg->save()) {
          $products_image_lrg_name = $products_image_lrg->filename;
        } else {
          $products_image_lrg_name = (isset($HTTP_POST_VARS['products_previous_image_lrg']) ? $HTTP_POST_VARS['products_previous_image_lrg'] : '');
        }
        } else {
          if (isset($HTTP_POST_VARS['products_image_lrg']) && tep_not_null($HTTP_POST_VARS['products_image_lrg']) && ($HTTP_POST_VARS['products_image_lrg'] != 'none')) {
            $products_image_lrg_name = $HTTP_POST_VARS['products_image_lrg'];
          } else {
            $products_image_lrg_name = (isset($HTTP_POST_VARS['products_previous_image_lrg']) ? $HTTP_POST_VARS['products_previous_image_lrg'] : '');
          }
        }
   if (($HTTP_POST_VARS['unlink_image_sm_1'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_1'] == 'yes')) {
        $products_image_sm_1 = '';
        $products_image_sm_1_name = '';
        } else {
        $products_image_sm_1 = new upload('products_image_sm_1');
        $products_image_sm_1->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_sm_1->parse() && $products_image_sm_1->save()) {
          $products_image_sm_1_name = $products_image_sm_1->filename;
        } else {
          $products_image_sm_1_name = (isset($HTTP_POST_VARS['products_previous_image_sm_1']) ? $HTTP_POST_VARS['products_previous_image_sm_1'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_xl_1'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_1'] == 'yes')) {
        $products_image_xl_1 = '';
        $products_image_xl_1_name = '';
        } else {
        $products_image_xl_1 = new upload('products_image_xl_1');
        $products_image_xl_1->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_xl_1->parse() && $products_image_xl_1->save()) {
          $products_image_xl_1_name = $products_image_xl_1->filename;
        } else {
          $products_image_xl_1_name = (isset($HTTP_POST_VARS['products_previous_image_xl_1']) ? $HTTP_POST_VARS['products_previous_image_xl_1'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_sm_2'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_2'] == 'yes')) {
        $products_image_sm_2 = '';
        $products_image_sm_2_name = '';
        } else {
        $products_image_sm_2 = new upload('products_image_sm_2');
        $products_image_sm_2->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_sm_2->parse() && $products_image_sm_2->save()) {
          $products_image_sm_2_name = $products_image_sm_2->filename;
        } else {
          $products_image_sm_2_name = (isset($HTTP_POST_VARS['products_previous_image_sm_2']) ? $HTTP_POST_VARS['products_previous_image_sm_2'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_xl_2'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_2'] == 'yes')) {
        $products_image_xl_2 = '';
        $products_image_xl_2_name = '';
        } else {
        $products_image_xl_2 = new upload('products_image_xl_2');
        $products_image_xl_2->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_xl_2->parse() && $products_image_xl_2->save()) {
          $products_image_xl_2_name = $products_image_xl_2->filename;
        } else {
          $products_image_xl_2_name = (isset($HTTP_POST_VARS['products_previous_image_xl_2']) ? $HTTP_POST_VARS['products_previous_image_xl_2'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_sm_3'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_3'] == 'yes')) {
        $products_image_sm_3 = '';
        $products_image_sm_3_name = '';
        } else {
        $products_image_sm_3 = new upload('products_image_sm_3');
        $products_image_sm_3->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_sm_3->parse() && $products_image_sm_3->save()) {
          $products_image_sm_3_name = $products_image_sm_3->filename;
        } else {
          $products_image_sm_3_name = (isset($HTTP_POST_VARS['products_previous_image_sm_3']) ? $HTTP_POST_VARS['products_previous_image_sm_3'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_xl_3'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_3'] == 'yes')) {
        $products_image_xl_3 = '';
        $products_image_xl_3_name = '';
        } else {
        $products_image_xl_3 = new upload('products_image_xl_3');
        $products_image_xl_3->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_xl_3->parse() && $products_image_xl_3->save()) {
          $products_image_xl_3_name = $products_image_xl_3->filename;
        } else {
          $products_image_xl_3_name = (isset($HTTP_POST_VARS['products_previous_image_xl_3']) ? $HTTP_POST_VARS['products_previous_image_xl_3'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_sm_4'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_4'] == 'yes')) {
        $products_image_sm_4 = '';
        $products_image_sm_4_name = '';
        } else {
        $products_image_sm_4 = new upload('products_image_sm_4');
        $products_image_sm_4->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_sm_4->parse() && $products_image_sm_4->save()) {
          $products_image_sm_4_name = $products_image_sm_4->filename;
        } else {
          $products_image_sm_4_name = (isset($HTTP_POST_VARS['products_previous_image_sm_4']) ? $HTTP_POST_VARS['products_previous_image_sm_4'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_xl_4'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_4'] == 'yes')) {
        $products_image_xl_4 = '';
        $products_image_xl_4_name = '';
        } else {
        $products_image_xl_4 = new upload('products_image_xl_4');
        $products_image_xl_4->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_xl_4->parse() && $products_image_xl_4->save()) {
          $products_image_xl_4_name = $products_image_xl_4->filename;
        } else {
          $products_image_xl_4_name = (isset($HTTP_POST_VARS['products_previous_image_xl_4']) ? $HTTP_POST_VARS['products_previous_image_xl_4'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_sm_5'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_5'] == 'yes')) {
        $products_image_sm_5 = '';
        $products_image_sm_5_name = '';
        } else {
        $products_image_sm_5 = new upload('products_image_sm_5');
        $products_image_sm_5->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_sm_5->parse() && $products_image_sm_5->save()) {
          $products_image_sm_5_name = $products_image_sm_5->filename;
        } else {
          $products_image_sm_5_name = (isset($HTTP_POST_VARS['products_previous_image_sm_5']) ? $HTTP_POST_VARS['products_previous_image_sm_5'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_xl_5'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_5'] == 'yes')) {
        $products_image_xl_5 = '';
        $products_image_xl_5_name = '';
        } else {
        $products_image_xl_5 = new upload('products_image_xl_5');
        $products_image_xl_5->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_xl_5->parse() && $products_image_xl_5->save()) {
          $products_image_xl_5_name = $products_image_xl_5->filename;
        } else {
          $products_image_xl_5_name = (isset($HTTP_POST_VARS['products_previous_image_xl_5']) ? $HTTP_POST_VARS['products_previous_image_xl_5'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_sm_6'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_6'] == 'yes')) {
        $products_image_sm_6 = '';
        $products_image_sm_6_name = '';
        } else {
        $products_image_sm_6 = new upload('products_image_sm_6');
        $products_image_sm_6->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_sm_6->parse() && $products_image_sm_6->save()) {
          $products_image_sm_6_name = $products_image_sm_6->filename;
        } else {
          $products_image_sm_6_name = (isset($HTTP_POST_VARS['products_previous_image_sm_6']) ? $HTTP_POST_VARS['products_previous_image_sm_6'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_xl_6'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_6'] == 'yes')) {
        $products_image_xl_6 = '';
        $products_image_xl_6_name = '';
        } else {
        $products_image_xl_6 = new upload('products_image_xl_6');
        $products_image_xl_6->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_xl_6->parse() && $products_image_xl_6->save()) {
          $products_image_xl_6_name = $products_image_xl_6->filename;
        } else {
          $products_image_xl_6_name = (isset($HTTP_POST_VARS['products_previous_image_xl_6']) ? $HTTP_POST_VARS['products_previous_image_xl_6'] : '');
        }
        }
        break;
// EOF MaxiDVD: Modified For Ultimate Images Pack!
    }
  }


// check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>

<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>

<?php if ((HTML_AREA_WYSIWYG_DISABLE == 'Enable') or (HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Enable')) { ?>
<script language="Javascript1.2"><!-- // load htmlarea
//MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.8 <head>
      _editor_url = "<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>htmlarea/";  // URL to htmlarea files
        var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
         if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
          if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
           if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
       <?php if (HTML_AREA_WYSIWYG_BASIC_PD == 'Basic'){ ?>  if (win_ie_ver >= 5.5) {
       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_basic.js"');
       document.write(' language="Javascript1.2"></scr' + 'ipt>');
          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
       <?php } else{ ?> if (win_ie_ver >= 5.5) {
       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_advanced.js"');
       document.write(' language="Javascript1.2"></scr' + 'ipt>');
          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
       <?php }?>
// --></script>
<?php }?>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<div id="spiffycalendar" class="text"></div>
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
<!--CATEGORY DESRIPTION v1.5-->
<!--REMOVED     <td width="100%" valign="top">-->
	     <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!--CATEGORY DESRIPTION v1.5-->
<?php
// CATEGORIES DESCRIPTION v1.5
  //----- new_category / edit_category (when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
  if ($HTTP_GET_VARS['action'] == 'new_category_ACD' || $HTTP_GET_VARS['action'] == 'edit_category_ACD') {
    if ( ($HTTP_GET_VARS['cID']) && (!$HTTP_POST_VARS) ) {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_heading_title, 

cd.categories_description, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " 

c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $HTTP_GET_VARS['cID'] . "' and c.categories_id = 

cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
      $category = tep_db_fetch_array($categories_query);

      $cInfo = new objectInfo($category);
    } elseif ($HTTP_POST_VARS) {
      $cInfo = new objectInfo($HTTP_POST_VARS);
      $categories_name = $HTTP_POST_VARS['categories_name'];
      $categories_heading_title = $HTTP_POST_VARS['categories_heading_title'];
      $categories_description = $HTTP_POST_VARS['categories_description'];
      $categories_url = $HTTP_POST_VARS['categories_url'];
    } else {
      $cInfo = new objectInfo(array());
    }

    $languages = tep_get_languages();

    $text_new_or_edit = ($HTTP_GET_VARS['action']=='new_category_ACD') ? TEXT_INFO_HEADING_NEW_CATEGORY : 

TEXT_INFO_HEADING_EDIT_CATEGORY;
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf($text_new_or_edit, tep_output_generated_category_path($current_category_id)); 

?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, 

HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('new_category', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID'] . 

'&action=new_category_preview', 'post', 'enctype="multipart/form-data"'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_NAME; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . 

$languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', 

(($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : 

tep_get_category_name($cInfo->categories_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . 

$languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_heading_title[' . $languages[$i]['id'] 

. ']', (($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : 

tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . 

'/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', 

'70', '15', (($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : 

tep_get_category_description($cInfo->categories_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_CATEGORIES_IMAGE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . 

tep_draw_file_field('categories_image') . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . 

$cInfo->categories_image . tep_draw_hidden_field('categories_previous_image', $cInfo->categories_image); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_SORT_ORDER; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . 

tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo tep_draw_hidden_field('categories_date_added', (($cInfo->date_added) ? 

$cInfo->date_added : date('Y-m-d'))) . tep_draw_hidden_field('parent_id', $cInfo->parent_id) . tep_image_submit('button_preview.gif', 

IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID']) . 

'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </form></tr>
<?php

  //----- new_category_preview (active when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
  } elseif ($HTTP_GET_VARS['action'] == 'new_category_preview') {
    if ($HTTP_POST_VARS) {
      $cInfo = new objectInfo($HTTP_POST_VARS);
      $categories_name = $HTTP_POST_VARS['categories_name'];
      $categories_heading_title = $HTTP_POST_VARS['categories_heading_title'];
      $categories_description = $HTTP_POST_VARS['categories_description'];

// copy image only if modified
        $categories_image = new upload('categories_image');
        $categories_image->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($categories_image->parse() && $categories_image->save()) {
          $categories_image_name = $categories_image->filename;
        } else {
        $categories_image_name = $HTTP_POST_VARS['categories_previous_image'];
      }
#     if ( ($categories_image != 'none') && ($categories_image != '') ) {
#       $image_location = DIR_FS_CATALOG_IMAGES . $categories_image_name;
#       if (file_exists($image_location)) @unlink($image_location);
#       copy($categories_image, $image_location);
#     } else {
#       $categories_image_name = $HTTP_POST_VARS['categories_previous_image'];
#     }
    } else {
      $category_query = tep_db_query("select c.categories_id, cd.language_id, cd.categories_name, cd.categories_heading_title, 

cd.categories_description, c.categories_image, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . 

TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and c.categories_id = '" . $HTTP_GET_VARS['cID'] . "'");
      $category = tep_db_fetch_array($category_query);

      $cInfo = new objectInfo($category);
      $categories_image_name = $cInfo->categories_image;
    }

    $form_action = ($HTTP_GET_VARS['cID']) ? 'update_category' : 'insert_category';

    echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID'] . '&action=' . 

$form_action, 'post', 'enctype="multipart/form-data"');

    $languages = tep_get_languages();
    for ($i=0; $i<sizeof($languages); $i++) {
      if ($HTTP_GET_VARS['read'] == 'only') {
        $cInfo->categories_name = tep_get_category_name($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_heading_title = tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_description = tep_get_category_description($cInfo->categories_id, $languages[$i]['id']);
      } else {
        $cInfo->categories_name = tep_db_prepare_input($categories_name[$languages[$i]['id']]);
        $cInfo->categories_heading_title = tep_db_prepare_input($categories_heading_title[$languages[$i]['id']]);
        $cInfo->categories_description = tep_db_prepare_input($categories_description[$languages[$i]['id']]);
      }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . 

$languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $cInfo->categories_heading_title; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $categories_image_name, $cInfo->categories_name, 

SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . $cInfo->categories_description; ?></td>
      </tr>

<?php
    }
    if ($HTTP_GET_VARS['read'] == 'only') {
      if ($HTTP_GET_VARS['origin']) {
        $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
          $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
        } else {
          $back_url = $HTTP_GET_VARS['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = FILENAME_CATEGORIES;
        $back_url_params = 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . 

tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText">
<?php
/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
        if (!is_array($HTTP_POST_VARS[$key])) {
          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
        echo tep_draw_hidden_field('categories_name[' . $languages[$i]['id'] . ']', 

htmlspecialchars(stripslashes($categories_name[$languages[$i]['id']])));
        echo tep_draw_hidden_field('categories_heading_title[' . $languages[$i]['id'] . ']', 

htmlspecialchars(stripslashes($categories_heading_title[$languages[$i]['id']])));
        echo tep_draw_hidden_field('categories_description[' . $languages[$i]['id'] . ']', 

htmlspecialchars(stripslashes($categories_description[$languages[$i]['id']])));
      }
      echo tep_draw_hidden_field('X_categories_image', stripslashes($categories_image_name));
      echo tep_draw_hidden_field('categories_image', stripslashes($categories_image_name));

      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if ($HTTP_GET_VARS['cID']) {
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
      } else {
        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID']) . '">' . 

tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </form></tr>
<?php
    }

//REMOVED  if ($action == 'new_product') {
     } elseif ($action == 'new_product') {
// END CATEGORIES DESCRIPTION v1.5  
    $parameters = array('products_name' => '',
	                   'products_name_prefix' => '',
					   'products_name_suffix' => '',
                       'products_description' => '',
					   'products_head_title_tag' => '',
					   'products_head_desc_tag' => '',
					   'products_head_keywords_tag' => '',
                       'products_url' => '',
                       'products_id' => '',
                       'products_quantity' => '',
                       'products_model' => '',
                       'products_image' => '',
// BOF MaxiDVD: Modified For Ultimate Images Pack!
                       'products_image_med' => '',
                       'products_image_lrg' => '',
                       'products_image_sm_1' => '',
                       'products_image_xl_1' => '',
                       'products_image_sm_2' => '',
                       'products_image_xl_2' => '',
                       'products_image_sm_3' => '',
                       'products_image_xl_3' => '',
                       'products_image_sm_4' => '',
                       'products_image_xl_4' => '',
                       'products_image_sm_5' => '',
                       'products_image_xl_5' => '',
                       'products_image_sm_6' => '',
                       'products_image_xl_6' => '',
// EOF MaxiDVD: Modified For Ultimate Images Pack!
                       'products_price' => '',
                       'products_weight' => '',
                       'products_date_added' => '',
                       'products_last_modified' => '',
                       'products_date_available' => '',
                       'products_status' => '',
                       'products_tax_class_id' => '',
                       'products_media_type_id' => '',
					   'products_video_format_id' => '',
					   'products_region_code_id' => '',
					   'products_set_type_id' => '',
					   'products_packaging_type_id' => '',
					   'manufacturers_id' => '',
					   'distributors_id' => '',
					   'producers_id' => '',
					   'series_id' => '',
					   'products_run_time' => '',
					   'products_out_of_print' => '',
					   'products_audio_id' => '',
					   'products_aspect_ratio_id' => '',
					   'products_warehouse_location' => '',
					   'products_export_id' => '',
					   'products_audio_languages' => '',
					   'products_subtitle_languages' => '',
					   'products_closed_captioned' => '',
					   'products_pc_bonus' => '',
					   'products_release_date' => '',
					   'products_isbn' => '',
					   'products_upc' => '',
					   'products_asin' => '',
					   'products_color' => '',
					   'products_rated' => '',
					   'products_byline' => '',
					   'products_summary' => '');

    $pInfo = new objectInfo($parameters);

    if (isset($HTTP_GET_VARS['pID']) && empty($HTTP_POST_VARS)) {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
      $product_query = tep_db_query("select pd.products_name, pd.products_name_prefix, pd.products_name_suffix, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.manufacturers_id, p.distributors_id, p.producers_id, p.series_id, p.products_media_type_id, p.products_video_format_id, p.products_region_code_id, p.products_set_type_id, p.products_packaging_type_id, p.products_run_time, p.products_out_of_print, p.products_audio_id, p.products_aspect_ratio_id, p.products_warehouse_location, p.products_export_id, p.products_audio_languages, p.products_subtitle_languages, p.products_closed_captioned, p.products_pc_bonus, p.products_release_date, p.products_isbn, p.products_upc, p.products_asin, p.products_color, p.products_rated, p.products_byline, p.products_summary from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
// EOF MaxiDVD: Modified For Ultimate Images Pack!
      $product = tep_db_fetch_array($product_query);

      $pInfo->objectInfo($product);
    } elseif (tep_not_null($HTTP_POST_VARS)) {
      $pInfo->objectInfo($HTTP_POST_VARS);
      $products_name = $HTTP_POST_VARS['products_name'];
	  $products_name = $HTTP_POST_VARS['products_name_prefix'];
	  $products_name = $HTTP_POST_VARS['products_name_suffix'];
      $products_description = $HTTP_POST_VARS['products_description'];
	  $products_head_title_tag = $HTTP_POST_VARS['products_head_title_tag'];
	  $products_head_desc_tag = $HTTP_POST_VARS['products_head_desc_tag'];
	  $products_head_keywords_tag = $HTTP_POST_VARS['products_head_keywords_tag'];
      $products_url = $HTTP_POST_VARS['products_url'];
    }

    $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                     'text' => $manufacturers['manufacturers_name']);
    }
//Distributor	
	    $distributors_array = array(array('id' => '', 'text' => TEXT_NONE));
    $distributors_query = tep_db_query("select distributors_id, distributors_name from " . TABLE_DISTRIBUTORS . " order by distributors_name");
    while ($distributors = tep_db_fetch_array($distributors_query)) {
      $distributors_array[] = array('id' => $distributors['distributors_id'],
                                     'text' => $distributors['distributors_name']);
    }
//Producer	
	    $producers_array = array(array('id' => '', 'text' => TEXT_NONE));
    $producers_query = tep_db_query("select producers_id, producers_name from " . TABLE_PRODUCERS . " order by producers_name");
    while ($producers = tep_db_fetch_array($producers_query)) {
      $producers_array[] = array('id' => $producers['producers_id'],
                                     'text' => $producers['producers_name']);
    }
//Series	
	    $series_array = array(array('id' => '', 'text' => TEXT_NONE));
    $series_query = tep_db_query("select series_id, series_name from " . TABLE_SERIES . " order by series_name");
    while ($series = tep_db_fetch_array($series_query)) {
      $series_array[] = array('id' => $series['series_id'],
                                     'text' => $series['series_name']);
    }
// MEDIA_TYPE
	
	$media_type_array = array(array('id' => '', 'text' => TEXT_NONE));
    $media_type_query = tep_db_query("select products_media_type_id, products_media_type_name from " . TABLE_MEDIA_TYPE . " order by products_media_type_id");
    while ($media_type = tep_db_fetch_array($media_type_query)) {
      $media_type_array[] = array('id' => $media_type['products_media_type_id'],
                                     'text' => $media_type['products_media_type_name']);
									 
    }

// VIDEO_FORMAT
	
	$video_format_array = array(array('id' => '', 'text' => TEXT_NONE));
    $video_format_query = tep_db_query("select products_video_format_id, products_video_format_name from " . TABLE_VIDEO_FORMAT . " order by products_video_format_id");
    while ($video_format = tep_db_fetch_array($video_format_query)) {
      $video_format_array[] = array('id' => $video_format['products_video_format_id'],
                                     'text' => $video_format['products_video_format_name']);
									 
    }

// REGION_CODE
	
	$region_code_array = array(array('id' => '', 'text' => TEXT_NONE));
    $region_code_query = tep_db_query("select products_region_code_id, products_region_code_name from " . TABLE_REGION_CODE . " order by products_region_code_id");
    while ($region_code = tep_db_fetch_array($region_code_query)) {
      $region_code_array[] = array('id' => $region_code['products_region_code_id'],
                                     'text' => $region_code['products_region_code_name']);
									 
    }

// SET_TYPE
	
	$set_type_array = array(array('id' => '', 'text' => TEXT_NONE));
    $set_type_query = tep_db_query("select products_set_type_id, products_set_type_name from " . TABLE_SET_TYPE . " order by products_set_type_id");
    while ($set_type = tep_db_fetch_array($set_type_query)) {
      $set_type_array[] = array('id' => $set_type['products_set_type_id'],
                                     'text' => $set_type['products_set_type_name']);
									 
    }

// PACKAGING_TYPE
	
	$packaging_type_array = array(array('id' => '', 'text' => TEXT_NONE));
    $packaging_type_query = tep_db_query("select products_packaging_type_id, products_packaging_type_name from " . TABLE_PACKAGING_TYPE . " order by products_packaging_type_id");
    while ($packaging_type = tep_db_fetch_array($packaging_type_query)) {
      $packaging_type_array[] = array('id' => $packaging_type['products_packaging_type_id'],
                                     'text' => $packaging_type['products_packaging_type_name']);
									 
    }
	
// AUDIO
	
	$audio_array = array(array('id' => '', 'text' => TEXT_NONE));
    $audio_query = tep_db_query("select products_audio_id, products_audio_name from " . TABLE_AUDIO . " order by products_audio_id");
    while ($audio = tep_db_fetch_array($audio_query)) {
      $audio_array[] = array('id' => $audio['products_audio_id'],
                                     'text' => $audio['products_audio_name']);
									 
    }
	
// ASPECT RATIO
	
	$aspect_ratio_array = array(array('id' => '', 'text' => TEXT_NONE));
    $aspect_ratio_query = tep_db_query("select products_aspect_ratio_id, products_aspect_ratio_name from " . TABLE_ASPECT_RATIO . " order by products_aspect_ratio_id");
    while ($aspect_ratio = tep_db_fetch_array($aspect_ratio_query)) {
      $aspect_ratio_array[] = array('id' => $aspect_ratio['products_aspect_ratio_id'],
                                     'text' => $aspect_ratio['products_aspect_ratio_name']);
									 
    }

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
    }

    $languages = tep_get_languages();

    if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
    switch ($pInfo->products_status) {
      case '0': $in_status = false; $out_status = true; break;
      case '1':
      default: $in_status = true; $out_status = false;
    }
	
	if (!isset($pInfo->products_closed_captioned)) $pInfo->products_closed_captioned = '1';
    switch ($pInfo->products_closed_captioned) {
      case '0': $cc_1 = false; $cc_2 = true; break;
      case '1':
      default: $cc_1 = true; $cc_2 = false;
    }
	
	if (!isset($pInfo->products_pc_bonus)) $pInfo->products_pc_bonus = '1';
    switch ($pInfo->products_pc_bonus) {
      case '0': $pc_1 = false; $pc_2 = true; break;
      case '1':
      default: $pc_1 = true; $pc_2 = false;
    }	
	
	if (!isset($pInfo->products_out_of_print)) $pInfo->products_out_of_print = '1';
    switch ($pInfo->products_out_of_print) {
      case '0': $oop_1 = false; $oop_2 = true; break;
      case '1':
      default: $oop_1 = true; $oop_2 = false;
    }	
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript"><!--
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<script language="javascript"><!--
var tax_rates = new Array();
<?php
    for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
      if ($tax_class_array[$i]['id'] > 0) {
        echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
      }
    }
?>

function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
  var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
  var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}

function updateGross() {
  var taxRate = getTaxRate();
  var grossValue = document.forms["new_product"].products_price.value;

  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
}

function updateNet() {
  var taxRate = getTaxRate();
  var netValue = document.forms["new_product"].products_price_gross.value;

  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price.value = doRound(netValue, 4);
}
//--></script>
    <?php echo tep_draw_form('new_product', FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . '&action=new_product_preview', 'post', 'enctype="multipart/form-data"'); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br><small>(YYYY-MM-DD)</small></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?><script language="javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script></td>
          </tr>
		  <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_quantity', $pInfo->products_quantity); ?></td>
          </tr>
		  <tr>
            <td class="main">Out of Print</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_out_of_print', '1', $oop_1) . '&nbsp;' . 'In Print' . '&nbsp;' . tep_draw_radio_field('products_out_of_print', '0', $oop_2) . '&nbsp;' . 'Out of Print'; ?></td>
          </tr>
		  <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_weight', $pInfo->products_weight); ?></td>
          </tr>		  
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id); ?></td>
          </tr>
		  <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_DISTRIBUTOR; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('distributors_id', $distributors_array, $pInfo->distributors_id); ?></td>
          </tr>
		  <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_PRODUCER; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('producers_id', $producers_array, $pInfo->producers_id); ?></td>
          </tr>
		  <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_SERIES; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('series_id', $series_array, $pInfo->series_id); ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME_PREFIX; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_name_prefix[' . $languages[$i]['id'] . ']', (isset($products_name_prefix[$languages[$i]['id']]) ? $products_name_prefix[$languages[$i]['id']] : tep_get_products_name_prefix($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? $products_name[$languages[$i]['id']] : tep_get_products_name($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME_SUFFIX; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_name_suffix[' . $languages[$i]['id'] . ']', (isset($products_name_suffix[$languages[$i]['id']]) ? $products_name_suffix[$languages[$i]['id']] : tep_get_products_name_suffix($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>		  		  
<?php
    }
?>
		  <TR>
		  <TD class="main">Media Type</TD>
		  <TD>
		  <?php  echo tep_draw_separator('pixel_trans.gif', '24', '15'). '&nbsp;' . tep_draw_pull_down_menu('products_media_type_id', $media_type_array, $pInfo->products_media_type_id); ?></td>
		  </TR>
		  <TR>
		  <TD class="main">Video Format</TD>
		  <TD>
		  <?php  echo tep_draw_separator('pixel_trans.gif', '24', '15'). '&nbsp;' . tep_draw_pull_down_menu('products_video_format_id', $video_format_array, $pInfo->products_video_format_id); ?></td>
		  </TR>
		  <TR>
		  <TD class="main">Audio</TD>
		  <TD>
		  <?php  echo tep_draw_separator('pixel_trans.gif', '24', '15'). '&nbsp;' . tep_draw_pull_down_menu('products_audio_id', $audio_array, $pInfo->products_audio_id); ?></td>
		  </TR>
		  <TR>
		  <TD class="main">Aspect Ratio</TD>
		  <TD>
		  <?php  echo tep_draw_separator('pixel_trans.gif', '24', '15'). '&nbsp;' . tep_draw_pull_down_menu('products_aspect_ratio_id', $aspect_ratio_array, $pInfo->products_aspect_ratio_id); ?></td>
		  </TR>
		  <tr>
            <td class="main">Closed Captioned</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_closed_captioned', '1', $cc_1) . '&nbsp;' . 'Unavailable' . '&nbsp;' . tep_draw_radio_field('products_closed_captioned', '0', $cc_2) . '&nbsp;' . 'Available'; ?></td>
          </tr>
		  <tr>
            <td class="main">PC Bonus</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_pc_bonus', '1', $pc_1) . '&nbsp;' . 'Unavailable' . '&nbsp;' . tep_draw_radio_field('products_pc_bonus', '0', $pc_2) . '&nbsp;' . 'Available'; ?></td>
          </tr>
		  <TR>
		  <TD class="main">Region Code</TD>
		  <TD>
		  <?php  echo tep_draw_separator('pixel_trans.gif', '24', '15'). '&nbsp;' . tep_draw_pull_down_menu('products_region_code_id', $region_code_array, $pInfo->products_region_code_id); ?></td>
		  </TR>
		  <TR>
		  <TD class="main">Set Type</TD>
		  <TD>
		  <?php  echo tep_draw_separator('pixel_trans.gif', '24', '15'). '&nbsp;' . tep_draw_pull_down_menu('products_set_type_id', $set_type_array, $pInfo->products_set_type_id); ?></td>
		  </TR>
		  <TR>
		  <TD class="main">Packaging Type</TD>
		  <TD>
		  <?php  echo tep_draw_separator('pixel_trans.gif', '24', '15'). '&nbsp;' . tep_draw_pull_down_menu('products_packaging_type_id', $packaging_type_array, $pInfo->products_packaging_type_id); ?></td>
		  </TR>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_gross', $pInfo->products_price, 'OnKeyUp="updateNet()"'); ?></td>
          </tr>
<script language="javascript"><!--
updateGross();
//--></script>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
           <TR>
		   <TD class="main">Byline
		   </TD>
		   <TD class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_byline', $pInfo->products_byline); ?>
		   </TD>		
		  </TR>
		  <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>          
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_PAGE_TITLE; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('products_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_title_tag[$languages[$i]['id']]) ? $products_head_title_tag[$languages[$i]['id']] : tep_get_products_head_title_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>          
           <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_HEADER_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>          
           <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_KEYWORDS; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? $products_head_keywords_tag[$languages[$i]['id']] : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>
          <TR>
		  <TD class="main">Run Time
		  </TD>
		  <TD class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_run_time', $pInfo->products_run_time); ?>
		  </TD>		
		  </TR>
          <TR>
		  <TD class="main">Warehouse Location
		  </TD>
		  <TD class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_warehouse_location', $pInfo->products_warehouse_location); ?>
		  </TD>		
		  </TR>
		            <TR>
		  <TD class="main">Database Export ID
		  </TD>
		  <TD class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_export_id', $pInfo->products_export_id); ?>
		  </TD>		
		  </TR>
		            <TR>
		  <TD class="main">Audio Languages
		  </TD>
		  <TD class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_audio_languages', $pInfo->products_audio_languages); ?>
		  </TD>		
		  </TR>
		            <TR>
		  <TD class="main">CC / Subtitle Languages
		  </TD>
		  <TD class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_subtitle_languages', $pInfo->products_subtitle_languages); ?>
		  </TD>		
		  </TR>		  
          <TR>
		   <TD class="main">Release Date
		   </TD>
		   <TD class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_release_date', $pInfo->products_release_date); ?>
		   </TD>		
		  </TR>
          <TR>
		   <TD class="main">ISBN #
		   </TD>
		   <TD class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_isbn', $pInfo->products_isbn); ?>
		   </TD>		
		  </TR>
          <TR>
		   <TD class="main">UPC #
		   </TD>
		   <TD class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_upc', $pInfo->products_upc); ?>
		   </TD>		
		  </TR>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_model', $pInfo->products_model); ?></td>
          </tr>
<!-- // BOF: MaxiDVD Added for Ulimited Images Pack! -->

          <tr>
           <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PRODUCTS_IMAGE_NOTE; ?></span></td>
           <?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image') . '<br>'; ?>
           <?php } else { ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_textarea_field('products_image', 'soft', '70', '2', $pInfo->products_image) . tep_draw_hidden_field('products_previous_image', $pInfo->products_image) . '</td></tr></table>';
           } if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image) != '')
           echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image, $pInfo->products_image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_image', $pInfo->products_image) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span></td>
          </tr>
          <tr>
           <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PRODUCTS_IMAGE_MEDIUM; ?></span></td>
           <?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image_med') . '<br>'; ?>
           <?php } else { ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_textarea_field('products_image_med', 'soft', '70', '2', $pInfo->products_image_med) . tep_draw_hidden_field('products_previous_image_med', $pInfo->products_image_med) . '</td></tr></table>';
           } if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_med) != '')
           echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image_med . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_med, $pInfo->products_image_med, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_image_med', $pInfo->products_image_med) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span></td>
          </tr>
          <tr>
           <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PRODUCTS_IMAGE_LARGE; ?></span></td>
           <?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image_lrg') . '<br>'; ?>
           <?php } else { ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_textarea_field('products_image_lrg', 'soft', '70', '2', $pInfo->products_image_lrg) . tep_draw_hidden_field('products_previous_image_lrg', $pInfo->products_image_lrg) . '</td></tr></table>';
           } if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_lrg) != '')
           echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image_lrg . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_lrg, $pInfo->products_image_lrg, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_image_lrg', $pInfo->products_image_lrg) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_lrg" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_lrg" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span></td>
          </tr>

  <?php
      if (ULTIMATE_ADDITIONAL_IMAGES == 'enable') {
   ?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
          </tr>
         <tr>
            <td class="main" colspan="3"><?php echo TEXT_PRODUCTS_IMAGE_ADDITIONAL . '<br><hr>';?></td>
          </tr>
          <tr>
            <td class="smalltext" colspan="3"><table border="0" cellpadding="2" cellspacing="0" width="100%">
              <tr>
                <td class="smalltext" colspan="2" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_TH_NOTICE; ?></td>
                <td class="smalltext" colspan="2" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_XL_NOTICE; ?></td>
              </tr>
              
              <tr>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_SM_1; ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_sm_1') . tep_draw_hidden_field('products_previous_image_sm_1', $pInfo->products_image_sm_1); ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_XL_1; ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_xl_1') . tep_draw_hidden_field('products_previous_image_xl_1', $pInfo->products_image_xl_1); ?></span></td>
              </tr>
  <?php
      if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_sm_1) != '' or ($pInfo->products_image_xl_1) != '') {
    ?>
              <tr>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_sm_1)) { ?><span class="smallText"><?php echo $pInfo->products_image_sm_1 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_1, $pInfo->products_image_sm_1, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_1" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_1" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_xl_1)) { ?><span class="smallText"><?php echo $pInfo->products_image_xl_1 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_1, $pInfo->products_image_xl_1, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_1" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_1" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
              </tr>
  <?php
     }
   ?>
              <tr>
                <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_SM_2; ?></td>
                <td class="smallText" valign="top"><?php echo tep_draw_file_field('products_image_sm_2') . tep_draw_hidden_field('products_previous_image_sm_2', $pInfo->products_image_sm_2); ?></td>
                <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_XL_2; ?></td>
                <td class="smallText" valign="top"><?php echo tep_draw_file_field('products_image_xl_2') . tep_draw_hidden_field('products_previous_image_xl_2', $pInfo->products_image_xl_2); ?></td>
             </tr>
  <?php
      if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_sm_2) != '' or ($pInfo->products_image_xl_2) != '') {
    ?>
              <tr>
                <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_sm_2)) { ?><?php echo $pInfo->products_image_sm_2 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_2, $pInfo->products_name_sm_2, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_2" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_2" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?><?php } ?></td>
                <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_xl_2)) { ?><?php echo $pInfo->products_image_xl_2 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_2, $pInfo->products_name_xl_2, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_2" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_2" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?><?php } ?></td>
              </tr>
  <?php
     }
   ?>
              <tr>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_SM_3; ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_sm_3') . tep_draw_hidden_field('products_previous_image_sm_3', $pInfo->products_image_sm_3); ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_XL_3; ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_xl_3') . tep_draw_hidden_field('products_previous_image_xl_3', $pInfo->products_image_xl_3); ?></span></td>
              </tr>
  <?php
      if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_sm_3) != '' or ($pInfo->products_image_xl_3) != '') {
    ?>
              <tr>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_sm_3)) { ?><span class="smallText"><?php echo $pInfo->products_image_sm_3 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_3, $pInfo->products_name_sm_3, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_3" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_3" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_xl_3)) { ?><span class="smallText"><?php echo $pInfo->products_image_xl_3 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_3, $pInfo->products_name_xl_3, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_3" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_3" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
              </tr>
  <?php
     }
   ?>
              <tr>
                <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_SM_4; ?></td>
                <td class="smallText" valign="top"><?php echo tep_draw_file_field('products_image_sm_4') . tep_draw_hidden_field('products_previous_image_sm_4', $pInfo->products_image_sm_4); ?></td>
                <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_XL_4; ?></td>
                <td class="smallText" valign="top"><?php echo tep_draw_file_field('products_image_xl_4') . tep_draw_hidden_field('products_previous_image_xl_4', $pInfo->products_image_xl_4); ?></td>
             </tr>
  <?php
      if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_sm_4) != '' or ($pInfo->products_image_xl_4) != '') {
    ?>
              <tr>
                <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_sm_4)) { ?><?php echo $pInfo->products_image_sm_4 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_4, $pInfo->products_name_sm_4, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_4" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_4" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?><?php } ?></td>
                <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_xl_4)) { ?><?php echo $pInfo->products_image_xl_4 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_4, $pInfo->products_name_xl_4, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_4" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_4" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?><?php } ?></td>
              </tr>
  <?php
     }
   ?>
              <tr>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_SM_5; ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_sm_5') . tep_draw_hidden_field('products_previous_image_sm_5', $pInfo->products_image_sm_5); ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_XL_5; ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_xl_5') . tep_draw_hidden_field('products_previous_image_xl_5', $pInfo->products_image_xl_5); ?></span></td>
              </tr>
  <?php
      if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_sm_5) != '' or ($pInfo->products_image_xl_5) != '') {
    ?>
              <tr>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_sm_5)) { ?><span class="smallText"><?php echo $pInfo->products_image_sm_5 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_5, $pInfo->products_name_sm_5, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_5" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_5" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_xl_5)) { ?><span class="smallText"><?php echo $pInfo->products_image_xl_5 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_5, $pInfo->products_name_xl_5, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_5" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_5" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
              </tr>
  <?php
     }
   ?>
              <tr>
                <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_SM_6; ?></td>
                <td class="smalltext" valign="top"><?php echo tep_draw_file_field('products_image_sm_6') . tep_draw_hidden_field('products_previous_image_sm_6', $pInfo->products_image_sm_6); ?></td>
                <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_XL_6; ?></td>
                <td class="smalltext" valign="top"><?php echo tep_draw_file_field('products_image_xl_6') . tep_draw_hidden_field('products_previous_image_xl_6', $pInfo->products_image_xl_6); ?></td>
             </tr>
  <?php
      if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_sm_6) != '' or ($pInfo->products_image_xl_6) != '') {
    ?>
              <tr>
                <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_sm_6)) { ?><?php echo $pInfo->products_image_sm_6 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_6, $pInfo->products_name_sm_6, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_6" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_6" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?><?php } ?></td>
                <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_xl_6)) { ?><?php echo $pInfo->products_image_xl_6 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_6, $pInfo->products_name_xl_6, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_6" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_6" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?><?php } ?></td>
              </tr>
  <?php
     }
   ?>
            </table></td>
          </tr>
<?php
  } else {
   echo tep_draw_hidden_field('products_previous_image_sm_1', $pInfo->products_image_sm_1) .
        tep_draw_hidden_field('products_previous_image_xl_1', $pInfo->products_image_xl_1) .
        tep_draw_hidden_field('products_previous_image_sm_2', $pInfo->products_image_sm_2) .
        tep_draw_hidden_field('products_previous_image_xl_2', $pInfo->products_image_xl_2) .
        tep_draw_hidden_field('products_previous_image_sm_3', $pInfo->products_image_sm_3) .
        tep_draw_hidden_field('products_previous_image_xl_3', $pInfo->products_image_xl_3) .
        tep_draw_hidden_field('products_previous_image_sm_4', $pInfo->products_image_sm_4) .
        tep_draw_hidden_field('products_previous_image_xl_4', $pInfo->products_image_xl_4) .
        tep_draw_hidden_field('products_previous_image_sm_5', $pInfo->products_image_sm_5) .
        tep_draw_hidden_field('products_previous_image_xl_5', $pInfo->products_image_xl_5) .
        tep_draw_hidden_field('products_previous_image_sm_6', $pInfo->products_image_sm_6) .
        tep_draw_hidden_field('products_previous_image_xl_6', $pInfo->products_image_xl_6);
     };
// EOF: MaxiDVD Added for Ulimited Images Pack!
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>TEST</td>
      </tr>
    </table></form>
    
<!-- // BOF: MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 Products Description HTML -->
<?php
  if (HTML_AREA_WYSIWYG_DISABLE == 'Enable') { ?>
            <script language="JavaScript1.2" defer>
             var config = new Object();  // create new config object
             config.width = "<?php echo HTML_AREA_WYSIWYG_WIDTH; ?>px";
             config.height = "<?php echo HTML_AREA_WYSIWYG_HEIGHT; ?>px";
             config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
             config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
          <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
             editor_generate('products_description[<?php echo $languages[$i]['id']; ?>]',config);
          <?php } ?>
<?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Enable') && (HTML_AREA_WYSIWYG_DISABLE == 'Enable')){ ?>
             config.height = "25px";
             config.bodyStyle = 'background-color: white; font-family: Arial; color: black; font-size: 12px;';
             config.toolbar = [ ['InsertImageURL'] ];
             config.OscImageRoot = '<?= trim(HTTP_SERVER . DIR_WS_CATALOG_IMAGES) ?>';
             editor_generate('products_image',config);
             editor_generate('products_image_med',config);
             editor_generate('products_image_lrg',config);
            <?php } ?>
            </script>
<?php } ?>
<!-- // EOF: MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 Products Description HTML -->
    
<?php
  } elseif ($action == 'new_product_preview') {
    if (tep_not_null($HTTP_POST_VARS)) {
      $pInfo = new objectInfo($HTTP_POST_VARS);
      $products_name = $HTTP_POST_VARS['products_name'];
	  $products_name_prefix = $HTTP_POST_VARS['products_name_prefix'];
	  $products_name_suffix = $HTTP_POST_VARS['products_name_suffix'];
      $products_description = $HTTP_POST_VARS['products_description'];
	  $products_head_title_tag = $HTTP_POST_VARS['products_head_title_tag'];
	  $products_head_desc_tag = $HTTP_POST_VARS['products_head_desc_tag'];
	  $products_head_keywords_tag = $HTTP_POST_VARS['products_head_keywords_tag'];
      $products_url = $HTTP_POST_VARS['products_url'];
    } else {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
      $product_query = tep_db_query("select p.products_id, pd.language_id, pd.products_name, pd.products_name_prefix, pd.products_name_suffix, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_quantity, p.products_model, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.manufacturers_id, p.distributors_id, p.producers_id, p.series_id, p.products_media_type_id, p.products_video_format_id, p.products_region_code_id, p.products_set_type_id, p.products_packaging_type_id  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "'");
// EOF MaxiDVD: Modified For Ultimate Images Pack!
      $product = tep_db_fetch_array($product_query);

      $pInfo = new objectInfo($product);
      $products_image_name = $pInfo->products_image;
      $products_image_med_name = $pInfo->products_image_med;
      $products_image_lrg_name = $pInfo->products_image_lrg;
      $products_image_sm_1_name = $pInfo->products_image_sm_1;
      $products_image_sm_2_name = $pInfo->products_image_sm_2;
      $products_image_sm_3_name = $pInfo->products_image_sm_3;
      $products_image_sm_4_name = $pInfo->products_image_sm_4;
      $products_image_sm_5_name = $pInfo->products_image_sm_5;
      $products_image_sm_6_name = $pInfo->products_image_sm_6;
      $products_image_xl_1_name = $pInfo->products_image_xl_1;
      $products_image_xl_2_name = $pInfo->products_image_xl_2;
      $products_image_xl_3_name = $pInfo->products_image_xl_3;
      $products_image_xl_4_name = $pInfo->products_image_xl_4;
      $products_image_xl_5_name = $pInfo->products_image_xl_5;
      $products_image_xl_6_name = $pInfo->products_image_xl_6;
    }

    $form_action = (isset($HTTP_GET_VARS['pID'])) ? 'update_product' : 'insert_product';

    echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
        $pInfo->products_name = tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
		$pInfo->products_name_suffix = tep_get_products_name_suffix($pInfo->products_id, $languages[$i]['id']);
		$pInfo->products_name_prefix = tep_get_products_name_prefix($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_description = tep_get_products_description($pInfo->products_id, $languages[$i]['id']);
		$pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
        $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
        $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);

        $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);

      } else {
        $pInfo->products_name = tep_db_prepare_input($products_name[$languages[$i]['id']]);
		$pInfo->products_name_prefix = tep_db_prepare_input($products_name_prefix[$languages[$i]['id']]);
		$pInfo->products_name_suffix = tep_db_prepare_input($products_name_suffix[$languages[$i]['id']]);
        $pInfo->products_description = tep_db_prepare_input($products_description[$languages[$i]['id']]);
		$pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
        $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
        $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);

        $pInfo->products_url = tep_db_prepare_input($products_url[$languages[$i]['id']]);
      }
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . '[Series_Name_1]' .  '&nbsp;' . $pInfo->products_name_prefix . '&nbsp;' . $pInfo->products_name . '&nbsp;' . $pInfo->products_name_suffix; ?></td>
            <td class="pageHeading" align="right"><?php echo $currencies->format($pInfo->products_price); ?></td>
          </tr>
        </table></td>
      </tr>
<!-- // BOF MaxiDVD: Modified For Ultimate Images Pack! // -->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main">
<?php if ($products_image_med_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_med_name, $products_image_med_name, MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"'); } elseif ($products_image_lrg_name) { ?>
<script language="javascript"><!--
      document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_lrg_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . '</a>'; ?>');
//--></script>
<?php } elseif ($products_image_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"');}; ?>
<?php echo $pInfo->products_description . '<br><br><center>'; ?>
<?php if ($products_image_xl_1_name) { ?>
<script language="javascript"><!--
      document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_sm_1_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_1_name, $products_image_sm_1_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
//--></script>
<?php } elseif ($products_image_sm_1_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_1_name, $products_image_sm_1_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
<?php if ($products_image_xl_2_name) { ?>
<script language="javascript"><!--
      document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_sm_2_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_2_name, $products_image_sm_2_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
//--></script>
<?php } elseif ($products_image_sm_2_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_2_name, $products_image_sm_2_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
<?php if ($products_image_xl_3_name) { ?>
<script language="javascript"><!--
      document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_sm_3_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_3_name, $products_image_sm_3_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
//--></script>
<?php } elseif ($products_image_sm_3_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_3_name, $products_image_sm_3_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
<?php if ($products_image_xl_4_name) { ?>
<script language="javascript"><!--
      document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_sm_4_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_4_name, $products_image_sm_4_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
//--></script>
<?php } elseif ($products_image_sm_4_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_4_name, $products_image_sm_4_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
<?php if ($products_image_xl_5_name) { ?>
<script language="javascript"><!--
      document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_sm_5_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_5_name, $products_image_sm_5_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
//--></script>
<?php } elseif ($products_image_sm_5_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_5_name, $products_image_sm_5_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
<?php if ($products_image_xl_6_name) { ?>
<script language="javascript"><!--
      document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_sm_6_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_6_name, $products_image_sm_6_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="6" vspace="6"') . '</a>'; ?>');
//--></script>
<?php } elseif ($products_image_sm_6_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_6_name, $products_image_sm_6_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="6" vspace="6"'); }; ?>
        </td>
      </tr>
<!-- // EOF MaxiDVD: Modified For Ultimate Images Pack! // -->

<?php
      if ($pInfo->products_url) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url); ?></td>
      </tr>
<?php
      }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
      if ($pInfo->products_date_available > date('Y-m-d')) {
?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->products_date_available)); ?></td>
      </tr>
<?php
      } else {
?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->products_date_added)); ?></td>
      </tr>
<?php
      }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
    }

    if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
      if (isset($HTTP_GET_VARS['origin'])) {
        $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
          $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
        } else {
          $back_url = $HTTP_GET_VARS['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = FILENAME_CATEGORIES;
        $back_url_params = 'cPath=' . $cPath . '&pID=' . $pInfo->products_id;
      }
?>
<!-- // BOF MaxiDVD: Modified For Edit button in Preview Page // -->
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<!-- // EOF MaxiDVD: Modified For Edit button in Preview Page // -->
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText">
<?php
/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
        if (!is_array($HTTP_POST_VARS[$key])) {
          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        echo tep_draw_hidden_field('products_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_name[$languages[$i]['id']])));
		echo tep_draw_hidden_field('products_name_prefix[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_name_prefix[$languages[$i]['id']])));
		echo tep_draw_hidden_field('products_name_suffix[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_name_suffix[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_description[$languages[$i]['id']])));
		echo tep_draw_hidden_field('products_head_title_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_title_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_desc_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_keywords_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_url[$languages[$i]['id']])));
      }
      echo tep_draw_hidden_field('products_image', stripslashes($products_image_name));
// BOF MaxiDVD: Added For Ultimate Images Pack!
      echo tep_draw_hidden_field('products_image_med', stripslashes($products_image_med_name));
      echo tep_draw_hidden_field('products_image_lrg', stripslashes($products_image_lrg_name));
      echo tep_draw_hidden_field('products_image_sm_1', stripslashes($products_image_sm_1_name));
      echo tep_draw_hidden_field('products_image_xl_1', stripslashes($products_image_xl_1_name));
      echo tep_draw_hidden_field('products_image_sm_2', stripslashes($products_image_sm_2_name));
      echo tep_draw_hidden_field('products_image_xl_2', stripslashes($products_image_xl_2_name));
      echo tep_draw_hidden_field('products_image_sm_3', stripslashes($products_image_sm_3_name));
      echo tep_draw_hidden_field('products_image_xl_3', stripslashes($products_image_xl_3_name));
      echo tep_draw_hidden_field('products_image_sm_4', stripslashes($products_image_sm_4_name));
      echo tep_draw_hidden_field('products_image_xl_4', stripslashes($products_image_xl_4_name));
      echo tep_draw_hidden_field('products_image_sm_5', stripslashes($products_image_sm_5_name));
      echo tep_draw_hidden_field('products_image_xl_5', stripslashes($products_image_xl_5_name));
      echo tep_draw_hidden_field('products_image_sm_6', stripslashes($products_image_sm_6_name));
      echo tep_draw_hidden_field('products_image_xl_6', stripslashes($products_image_xl_6_name));
// EOF MaxiDVD: Added For Ultimate Images Pack!
      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if (isset($HTTP_GET_VARS['pID'])) {
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
      } else {
        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </tr>
    </table></form>
<?php
    }
  } else {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('search', FILENAME_CATEGORIES, '', 'get');
    echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');
    echo '</form>';
?>
                </td>
              </tr>
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');
    echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
    echo '</form>';
?>
                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $categories_count = 0;
    $rows = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
    } else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
    }
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

// Get parent_id for subcategories if search
      if (isset($HTTP_GET_VARS['search'])) $cPath= $categories['parent_id'];

      if ((!isset($HTTP_GET_VARS['cID']) && !isset($HTTP_GET_VARS['pID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge($categories, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $categories['categories_name'] . '</b>'; ?></td>
                <td class="dataTableContent" align="center">&nbsp;</td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $products_count = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $products_query = tep_db_query("select p.products_id, pd.products_name, pd.products_name_prefix, pd.products_name_suffix, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p2c.categories_id, mt.products_media_type_name, se.series_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_MEDIA_TYPE . " mt on (p.products_media_type_id = mt.products_media_type_id) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id), " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and pd.products_name like '%" . tep_db_input($search) . "%' order by pd.products_name");
	  
    } else {
      $products_query = tep_db_query("select p.products_id, pd.products_name, pd.products_name_prefix, pd.products_name_suffix, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, mt.products_media_type_name, se.series_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_MEDIA_TYPE . " mt on (p.products_media_type_id = mt.products_media_type_id) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id), " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by pd.products_name");
    }
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

// Get categories_id for product if search
      if (isset($HTTP_GET_VARS['search'])) $cPath = $products['categories_id'];

      if ( (!isset($HTTP_GET_VARS['pID']) && !isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['pID']) && ($HTTP_GET_VARS['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = array_merge($products, $reviews);
        $pInfo = new objectInfo($pInfo_array);
      }

      if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a><b>' . $products['series_name'] . '</b><BR>' . $products['products_name_prefix'] . '&nbsp;<b>' . $products['products_name'] . '</b>&nbsp;' . $products['products_name_suffix'] . '&nbsp;<b>-' . $products['products_media_type_name'] . '</b>' ; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($products['products_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $cPath_back = '';
    if (sizeof($cPath_array) > 0) {
      for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
        if (empty($cPath_back)) {
          $cPath_back .= $cPath_array[$i];
        } else {
          $cPath_back .= '_' . $cPath_array[$i];
        }
      }
    }

    $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                    <td align="right" class="smallText"><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($HTTP_GET_VARS['search'])) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
      case 'new_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('newcategory', FILENAME_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
        }

        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
        $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'edit_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_get_category_name($cInfo->categories_id, $languages[$i]['id']));
        }

        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->categories_image . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
        $contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
        $contents[] = array('text' => '<br><b>' . $pInfo->series_name . '</b>&nbsp;' . $pInfo->products_name_prefix . '&nbsp;<b>' . $pInfo->products_name . '</b>&nbsp;' . $pInfo->products_name_suffix . '&nbsp;<B>-' . $pInfo->products_media_type_name . '</b>');

        $product_categories_string = '';
        $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
        for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
          $category_path = '';
          for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
            $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $category_path = substr($category_path, 0, -16);
          $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
        }
        $product_categories_string = substr($product_categories_string, 0, -4);

        $contents[] = array('text' => '<br>' . $product_categories_string);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name_prefix . '&nbsp;' . $pInfo->products_name . '&nbsp;' . $pInfo->products_name_suffix  ));
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->products_name_prefix . '&nbsp;' . $pInfo->products_name . '&nbsp;' . $pInfo->products_name_suffix) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES . '<br>' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>');
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
            if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
            $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->categories_image);
            $contents[] = array('text' => '<br>' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
          } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
            $heading[] = array('text' => '<b>' . tep_get_products_name_prefix($pInfo->products_id, $languages_id) . '&nbsp;' . tep_get_products_name($pInfo->products_id, $languages_id) . '&nbsp;' . tep_get_products_name_suffix($pInfo->products_id, $languages_id) . '</b>');
// BOF MaxiDVD: Added Catalog Preview Button.
            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>  <a target="_blank" href="' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG . 'product_info.php?products_id=' . $pInfo->products_id . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>');
// EOF MaxiDVD: Added Catalog Preview Button.
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->products_date_added));
            if (tep_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->products_last_modified));
            if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->products_date_available));
            $contents[] = array('text' => '<br>' . tep_info_image($pInfo->products_image, $pInfo->products_name_prefix . '&nbsp;' . $pInfo->products_name . '&nbsp;' . $pInfo->products_name_suffix, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>' . $pInfo->products_image);
            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format($pInfo->products_price) . '<br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->products_quantity);
            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
          }
        } else { // create category/product info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS);
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
    </table>
<?php
  }
?>
    </td>
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
