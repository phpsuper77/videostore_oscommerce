<?php
/*
  $Id: banner.php,v 1.9 2001/12/14 12:55:49 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/

////
// Sets the status of a banner
  function tep_set_banner_status($banners_id, $status) {
    if ($status == '1') {
      return tep_db_query("update " . TABLE_BANNERS . " set status = '1', date_status_change = now(), date_scheduled = NULL where banners_id = '" . $banners_id . "'");
    } elseif ($status == '0') {
      return tep_db_query("update " . TABLE_BANNERS . " set status = '0', date_status_change = now() where banners_id = '" . $banners_id . "'");
    } else {
      return -1;
    }
  }

////
// Auto activate banners
  function tep_activate_banners() {
    $banners_query = tep_db_query("select banners_id, date_scheduled from " . TABLE_BANNERS . " where date_scheduled != ''");
    if (tep_db_num_rows($banners_query)) {
      while ($banners = tep_db_fetch_array($banners_query)) {
        if (date('Y-m-d H:i:s') >= $banners['date_scheduled']) {
          tep_set_banner_status($banners['banners_id'], '1');
        }
      }
    }
  }

////
// Auto expire banners
  function tep_expire_banners() {
    $banners_query = tep_db_query("select b.banners_id, b.expires_date, b.expires_impressions, sum(bh.banners_shown) as banners_shown from " . TABLE_BANNERS . " b, " . TABLE_BANNERS_HISTORY . " bh where b.status = '1' and b.banners_id = bh.banners_id group by b.banners_id");
    if (tep_db_num_rows($banners_query)) {
      while ($banners = tep_db_fetch_array($banners_query)) {
        if ($banners['expires_date']) {
          if (date('Y-m-d H:i:s') >= $banners['expires_date']) {
            tep_set_banner_status($banners['banners_id'], '0');
          }
        } elseif ($banners['expires_impressions']) {
          if ($banners['banners_shown'] >= $banners['expires_impressions']) {
            tep_set_banner_status($banners['banners_id'], '0');
          }
        }
      }
    }
  }

////
// Display a banner from the specified group or banner id ($identifier)
  function tep_display_banner($action, $identifier) {
// WebMakers.com Added: Banner Manager
    global $my_page_ssl;

    switch (true) {
    case ( BANNERS_SHOW_ON_SSL=='1' and $my_page_ssl=='on' ):
      $my_banner_filter=" and banners_on_ssl= " . "'1' ";
      break;
    case ( BANNERS_SHOW_ON_SSL=='1' and $my_page_ssl=='off' ):
      $my_banner_filter='';
      break;
    case ( BANNERS_SHOW_ON_SSL=='0' and $my_page_ssl=='on' ):
      $my_banner_filter=" and banners_on_ssl= " . "'2' ";
      break;
    case ( BANNERS_SHOW_ON_SSL=='0' and $my_page_ssl=='off' ):
      $my_banner_filter='';
      break;
    }

    if ($action == 'dynamic') {
      $banners_query = tep_db_query("select count(*) as count from " . TABLE_BANNERS . " where status = '1' " . $my_banner_filter . " and banners_group = '" . $identifier . "'");
      $banners = tep_db_fetch_array($banners_query);
      if ($banners['count'] > 0) {
// WebMakers.com Added: Banner Manager
        $banner = tep_random_select("select banners_id, banners_title, banners_image, banners_html_text, banners_open_new_windows, banners_on_ssl from " . TABLE_BANNERS . " where status = '1' " . $my_banner_filter . "  and banners_group = '" . $identifier . "'");
      } else {
        return '<b>TEP ERROR! (tep_display_banner(' . $action . ', ' . $identifier . ') -> No banners with group \'' . $identifier . '\' found!</b>';
      }
    } elseif ($action == 'static') {
      if (is_array($identifier)) {
        $banner = $identifier;
      } else {
// WebMakers.com Added: Banner Manager
        $banner_query = tep_db_query("select banners_id, banners_title, banners_image, banners_html_text,  banners_open_new_windows, banners_on_ssl from " . TABLE_BANNERS . " where status = '1'  " . $my_banner_filter . " and banners_id = '" . $identifier . "'");
        if (tep_db_num_rows($banner_query)) {
          $banner = tep_db_fetch_array($banner_query);
        } else {
          return '<b>TEP ERROR! (tep_display_banner(' . $action . ', ' . $identifier . ') -> Banner with ID \'' . $identifier . '\' not found, or status inactive</b>';
        }
      }
    } else {
      return '<b>TEP ERROR! (tep_display_banner(' . $action . ', ' . $identifier . ') -> Unknown $action parameter value - it must be either \'dynamic\' or \'static\'</b>';
    }

    if ($banner['banners_html_text']) {
      $banner_string = $banner['banners_html_text'];
    } else {
// WebMakers.com Added: Banner Manager
      if ( $banner['banners_open_new_windows'] =='1' ) {
        $banner_string = '<a href="' . tep_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $banner['banners_id']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $banner['banners_image'], $banner['banners_title']) . '</a>';
      } else {
        $banner_string = '<a href="' . tep_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $banner['banners_id']) . '">' . tep_image(DIR_WS_IMAGES . $banner['banners_image'], $banner['banners_title']) . '</a>';
      }
    }

    tep_update_banner_display_count($banner['banners_id']);

    return $banner_string;
  }

////
// Check to see if a banner exists
  function tep_banner_exists($action, $identifier) {
// WebMakers.com Added: Banner Manager
    global $my_page_ssl;

    switch (true) {
    case ( BANNERS_SHOW_ON_SSL=='1' and $my_page_ssl=='on' ):
      $my_banner_filter=" and banners_on_ssl= " . "'1' ";
      break;
    case ( BANNERS_SHOW_ON_SSL=='1' and $my_page_ssl=='off' ):
      $my_banner_filter='';
      break;
    case ( BANNERS_SHOW_ON_SSL=='0' and $my_page_ssl=='on' ):
      $my_banner_filter=" and banners_on_ssl= " . "'2' ";
      break;
    case ( BANNERS_SHOW_ON_SSL=='0' and $my_page_ssl=='off' ):
      $my_banner_filter='';
      break;
    }

    if ($action == 'dynamic') {
      return tep_random_select("select banners_id, banners_title, banners_image, banners_html_text, banners_open_new_windows from " . TABLE_BANNERS . " where status = '1'  " . $my_banner_filter . " and banners_group = '" . $identifier . "'");
    } elseif ($action == 'static') {
      $banner_query = tep_db_query("select banners_id, banners_title, banners_image, banners_html_text, banners_open_new_windows from " . TABLE_BANNERS . " where status = '1'  " . $my_banner_filter . " and banners_id = '" . $identifier . "'");
      return tep_db_fetch_array($banner_query);
    } else {
      return false;
    }
  }

////
// Update the banner display statistics
  function tep_update_banner_display_count($banner_id) {
    $banner_check_query = tep_db_query("select count(*) as count from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . $banner_id . "' and date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
    $banner_check = tep_db_fetch_array($banner_check_query);

    if ($banner_check['count'] > 0) {
      tep_db_query("update " . TABLE_BANNERS_HISTORY . " set banners_shown = banners_shown + 1 where banners_id = '" . $banner_id . "' and date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
    } else {
      tep_db_query("insert into " . TABLE_BANNERS_HISTORY . " (banners_id, banners_shown, banners_history_date) values ('" . $banner_id . "', 1, now())");
    }
  }

////
// Update the banner click statistics
  function tep_update_banner_click_count($banner_id) {
    tep_db_query("update " . TABLE_BANNERS_HISTORY . " set banners_clicked = banners_clicked + 1 where banners_id = '" . $banner_id . "' and date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
  }
?>