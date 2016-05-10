<?php
/*
  $Id: reports.php,v 1.5 2003/07/09 01:18:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, 'selected_box=reports'));

  if ($selected_box == 'reports') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_PRODUCTS_VIEWED . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_PRODUCTS_PURCHASED . '</a><br>' .
'<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_BACKORDERED, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_PRODUCTS_BACKORDERED . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_STATS_REFERRAL_SOURCES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_REFERRAL_SOURCES . '</a><br>' . //rmh referrals
                                   '<a href="' . tep_href_link(FILENAME_STATS_CUSTOMERS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_ORDERS_TOTAL . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_SHIPPING_MANIFEST, '', 'NONSSL') . '" class="menuBoxContentLink" target="top">' . BOX_SHIPPING_MANIFEST . '</a><br>'.
'<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_NOTIFICATIONS, '', 'NONSSL') . '" class="menuBoxContentLink">' . 'Prod Notifications</a><br>' .
	   '<a href="' . tep_href_link(FILENAME_STATS_UNSOLD_CARTS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_UNSOLD_CARTS . '</a><br>' .
                   '<a href="' . tep_href_link(FILENAME_STATS_MONTHLY_SALES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_MONTHLY_SALES . '</a><br>' .
                   '<a href="' . tep_href_link('stat_report.php', '', 'NONSSL') . '" class="menuBoxContentLink">Day/Monthly Consignment/Royalty Report</a><br>' .
                   '<a href="' . tep_href_link('income_report.php', '', 'NONSSL') . '" class="menuBoxContentLink">Payment Method Report</a><br>' .
                   '<a href="' . tep_href_link(FILENAME_STATS_RECOVER_CART_SALES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_RECOVER_CART_SALES . '</a><br>' .                   
                   '<a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT2, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_SALES_REPORT2 . '</a><br>' .
	   '<a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_SALES_REPORT . '</a><br/>'.
	   '<a href="' . tep_href_link(FILENAME_STATS_WISHLISTS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_WISHLISTS . '</a><br>'.
	   '<a href="' . tep_href_link('stats_products_keywords.php', '', 'NONSSL') . '" class="menuBoxContentLink">Product Keywords</a><br>'.
	   '<a href="' . tep_href_link('stats_products_distribution.php', '', 'NONSSL') . '" class="menuBoxContentLink">Product Distribution</a><br>'.
	   '<a target=_new href="' . tep_href_link('generate_catalog.php', '', 'NONSSL') . '" class="menuBoxContentLink">Generate Catalog</a><br>'.
	   '<a target=_new href="' . tep_href_link('generate_type.php?type=2', '', 'NONSSL') . '" class="menuBoxContentLink">Generate DVD Catalog</a><br>'.
	   '<a target=_new href="' . tep_href_link('generate_type.php?type=1', '', 'NONSSL') . '" class="menuBoxContentLink">Generate VHS Catalog</a><br>'.
	   '<a href="' . tep_href_link('returned_products.php', '', 'NONSSL') . '" class="menuBoxContentLink">Backordered items</a><br/>'.
	   '<a href="' . tep_href_link('quality_control.php', '', 'NONSSL') . '" class="menuBoxContentLink">Quality Control</a><br/>'.
	   '<a href="' . tep_href_link('vp_duplicates.php', '', 'NONSSL') . '" class="menuBoxContentLink">Vendor Products<br/>Duplicates</a><br/>'.
	   '<a href="' . tep_href_link('outrage.php', '', 'NONSSL') . '" class="menuBoxContentLink">Outage Report</a><br/>'.
	   '<a href="' . tep_href_link('download_link_stat.php', '', 'NONSSL') . '" class="menuBoxContentLink">Download Link Report</a><br/>'.
	   '<a href="' . tep_href_link('missing_pics.php', '', 'NONSSL') . '" class="menuBoxContentLink">Missing Images Small</a><br/>'.
	   '<a href="' . tep_href_link('missing_pics_med.php', '', 'NONSSL') . '" class="menuBoxContentLink">Missing Images Medium</a><br/>'.
	   '<a href="' . tep_href_link('missing_pics_lrg.php', '', 'NONSSL') . '" class="menuBoxContentLink">Missing Images Large</a><br/>'.
	   '<a href="' . tep_href_link('missing_pics_sm_1.php', '', 'NONSSL') . '" class="menuBoxContentLink">Missing Images Wrap</a><br/>'.
	   '<a href="' . tep_href_link('missing_pics_sm_2.php', '', 'NONSSL') . '" class="menuBoxContentLink">Missing Images Disc</a><br/>'.
	   '<a href="' . tep_href_link('missing_pics_300.php', '', 'NONSSL') . '" class="menuBoxContentLink">Missing Images 300 DPI</a><br/>'.
	   '<a href="' . tep_href_link('cart_activity.php', '', 'NONSSL') . '" class="menuBoxContentLink">Cart Activity</a><br/>');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?></td>
          </tr>
<!-- reports_eof //-->



