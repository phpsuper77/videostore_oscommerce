<?php
/*
  $Id: mysqlperformance.php,v 1.0 2007/02/04 22:50:51 hpdl Exp $

  Contribution made by Biznetstar.com 
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');


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
	  <td>
	  <?php echo TEXT_NOTE_MYSQL_PERFORMANCE; ?>
	  </td>
	  </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="3" cellpadding="3">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"> <?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_QUERY; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_QLOCATION; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_QUERY_TIME; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE_CREATED; ?>&nbsp;</td>             
			 </tr>
<?php
 if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] > 1)) $rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;

 $performance_query_raw = "select qid, query, qlocation, time, qtime from ".TABLE_MYSQL_PERFORMANCE." order by qid desc";
 $performance_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $performance_query_raw, $performance_query_numrows);

  $performance_query_numrows = tep_db_query("SELECT * FROM ".TABLE_MYSQL_PERFORMANCE."");
  $performance_query_numrows = tep_db_num_rows($performance_query_numrows);

  $rows = 0;
  
  $performance_query = tep_db_query($performance_query_raw);

   while ($performance = tep_db_fetch_array($performance_query))  {
   $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                <td class="dataTableContent"><?php echo $rows; ?></td>
                <td class="dataTableContent" align="left"><?php echo $performance['query']; ?></td>
                <td class="dataTableContent" align="right"><?php echo $performance['qlocation'];?></td>
				<td class="dataTableContent" align="right"><?php echo $performance['time'];?></td>
				<td class="dataTableContent" align="right"><?php echo $performance['qtime'];?></td>
              </tr>
<?
  }
?>
         <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $performance_split->display_count($performance_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_QUERIES); ?></td>
                <td class="smallText" align="right"><?php echo $performance_split->display_links($performance_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>

              	  <tr>
	  <td colspan="5"><br/>
<?php echo TEXT_NOTE_2_MYSQL_PERFORMANCE; ?>
	  </td>
	  </tr>
            </table></td>
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
