<?php
/*
  $Id: manufacturer_info.php,v 1.11 2003/06/09 22:12:05 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

 /*	if (isset($HTTP_GET_VARS['products_id'])) {
    $distributor_query = tep_db_query("select d.distributors_id, d.distributors_name, d.distributors_image from " . TABLE_DISTRIBUTORS . " d , " . TABLE_PRODUCTS . " p  where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.distributors_id = d.distributors_id");
    if (tep_db_num_rows($distributor_query)) {
      $distributor = tep_db_fetch_array($distributor_query);
?>
	  <!-- distributor_info //-->
          <tr>
            <td>
<img src="images/bar-clap.gif">
<?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => 'Distributor');

      new infoBoxHeading($info_box_contents, false, false);

      $distributor_info_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
	  if (tep_not_null($distributor['distributors_name'])) $distributor_info_string .= '<tr><td align="center" class="infoBoxContents" colspan="2"><b>' . $distributor['distributors_name'] . '</b></td></tr>';

      if (tep_not_null($distributor['distributors_image'])) $distributor_info_string .= '<tr><td align="center" class="infoBoxContents" colspan="2">' . tep_image(DIR_WS_IMAGES . $distributor['distributors_image'], $distributor['distributors_name']) . '</td></tr>';
      $distributor_info_string .= '<tr><td valign="top" class="infoBoxContents">-&nbsp;</td><td valign="top" class="infoBoxContents"><a href="' . tep_href_link(FILENAME_DEFAULT, 'distributors_id=' . $distributor['distributors_id']) . '">' . 'See more products from&nbsp;' . $distributor['distributors_name'] . '</a></td></tr>' .
                                   '</table>';

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $distributor_info_string);

      new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- distributor_info_eof //-->
<?php
    }
  }

*/?>













<?php	  

	if (isset($HTTP_GET_VARS['products_id'])) {
    $producer_query = tep_db_query("select pr.producers_id, pr.producers_name, pr.producers_image from " . TABLE_PRODUCERS . " pr , " . TABLE_PRODUCTS . " p  where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.producers_id = pr.producers_id");
    if (tep_db_num_rows($producer_query)) {
      $producer = tep_db_fetch_array($producer_query);
	  ?>
	  <!-- producer_info //-->
          <tr>
            <td>
<img alt="this is image" src="images/bar-clap.gif">
<?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => 'Producer');

      new infoBoxHeading($info_box_contents, false, false);

      $producer_info_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
	  if (tep_not_null($producer['producers_name'])) $producer_info_string .= '<tr><td align="center" class="infoBoxContents" colspan="2"><b>' . $producer['producers_name'] . '</b></td></tr>';
      if (tep_not_null($producer['producers_image'])) $producer_info_string .= '<tr><td align="center" class="infoBoxContents" colspan="2">' . tep_image(DIR_WS_IMAGES . $producer['producers_image'], $producer['producers_name']) . '</td></tr>';
      $producer_info_string .= '<tr><td valign="top" class="infoBoxContents">-&nbsp;</td><td valign="top" class="infoBoxContents"><a href="' . tep_href_link(FILENAME_DEFAULT, 'producers_id=' . $producer['producers_id']) . '">' . 'See more products from&nbsp;' . $producer['producers_name'] . '</a></td></tr>' .
                                   '</table>';

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $producer_info_string);

      new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- producer_info_eof //-->
	<?php
    }
  }
?>  










<?php
	if (isset($HTTP_GET_VARS['products_id'])) {
    $serie_query = tep_db_query("select se.series_id, se.series_name, se.series_image from " . TABLE_SERIES . " se , " . TABLE_PRODUCTS . " p  where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.series_id = se.series_id");
    if (tep_db_num_rows($serie_query)) {
      $serie = tep_db_fetch_array($serie_query);
	  ?>
<!-- serie_info //-->
          <tr>
            <td>
<img alt="this is image" src="images/bar-clap.gif">
<?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => 'Series');

      new infoBoxHeading($info_box_contents, false, false);

      $serie_info_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
	  if (tep_not_null($serie['series_name'])) $serie_info_string .= '<tr><td align="center" class="infoBoxContents" colspan="2"><b>' . $serie['series_name'] . '</b></td></tr>';
      if (tep_not_null($serie['series_image'])) $serie_info_string .= '<tr><td align="center" class="infoBoxContents" colspan="2">' . tep_image(DIR_WS_IMAGES . $serie['series_image'], $serie['series_name']) . '</td></tr>';
      $serie_info_string .= '<tr><td valign="top" class="infoBoxContents">-&nbsp;</td><td valign="top" class="infoBoxContents"><a href="' . tep_href_link(FILENAME_DEFAULT, 'series_id=' . $serie['series_id']) . '">' . 'See more products from the&nbsp;' . $serie['series_name'] . '&nbsp;Series' . '</a></td></tr>' .
                                   '</table>';

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $serie_info_string);

      new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- serie_info_eof //-->
<?php
    }
  }
?>
