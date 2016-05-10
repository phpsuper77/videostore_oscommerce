<?php
/*
  FAQ system for OSC 2.2 MS2 v2.1  22.02.2005
  Originally Created by: http://adgrafics.com admin@adgrafics.net
  Updated by: http://www.webandpepper.ch osc@webandpepper.ch v2.0 (03.03.2004)
  Last Modified: http://shopandgo.caesium55.com timmhaas@web.de v2.1 (22.02.2005)
  Released under the GNU General Public License
  osCommerce, Open Source E-Commerce Solutions
  Copyright (c) 2004 osCommerce
*/

  require('includes/application_top.php');
  
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FAQ);

  $breadcrumb->add(FAQ_NAVBAR_TITLE, tep_href_link(FILENAME_FAQ, '', 'NONSSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
				<?php
				// osCoders.biz - Analystics - start
				/*
				Conditional code added thank to  rrodkey and bfcase
				IMPORTANT -- IMPORTANT - IMPORTANT
				You'll need to update the "xxxx-x" in the samples (twice) above with your own Google Analytics account and profile number. 
				To find this number you can access your personalized tracking code in its entirety by clicking Check Status in the Analytics Settings page of your Analytics account.
				*/
				if ($request_type == 'SSL') {
				?>
				 <script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript">
				 </script>
				 <script type="text/javascript">
				   _uacct="UA-195024-1";
				   urchinTracker();
				 </script>
				<?php
				} else {
				?>
				 <script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
				 </script>
				   <script type="text/javascript">
				    _uacct="UA-195024-1";
				    urchinTracker();
				 </script>
				<?
				}
				// osCoders.biz - Analistics - end
				?>
<?php include ('includes/ssl_provider.js.php'); ?>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
    <!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" align="left"><?php echo HEADING_TITLE; ?></td>
                      </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><ol>
<?php

  function faq_toc ($language) {
	static $old_faq_id;
	
	if ($old_faq_id) {
	  $exclude = explode("&", $old_faq_id);
	  while (list($dummy,$old_id) = each($exclude)) {
		if ($old_id) {
		  $query .= 'faq_id != ' . $old_id . ' AND ';
		  unset($old_id);
		}
	  }
	}
	$result = tep_db_fetch_array(tep_db_query("SELECT faq_id, question FROM " . TABLE_FAQ . " WHERE $query visible='1' and $query language='$language' and type=1 ORDER BY v_order"));
	if ($result['faq_id']) {
	  $old_faq_id .= $result['faq_id'] . '&';
	  $result['toc'] = '<a href="' . tep_href_link(FILENAME_FAQ,'#' . $result['faq_id']) . '"><b>' . $result['question'] . '</b></a>';
	}
	return $result;
  }

  function read_faq ($language) {
	static $old_faq_id;
	
	if ($old_faq_id) {
	  $exclude = explode("&", $old_faq_id);
	  while (list($dummy,$old_id) = each($exclude)) {
		if ($old_id) {
		  $query .= 'faq_id != ' . $old_id . ' AND ';
		  unset($old_id);
		}
	  }
	}
	$result = tep_db_fetch_array(tep_db_query("SELECT faq_id, question, answer FROM " . TABLE_FAQ . " WHERE $query visible='1' and type=1 and $query language='$language' ORDER BY v_order"));
	if ($result['faq_id']) {
	  $old_faq_id .= $result['faq_id'] . '&';
	  $result['faq'] = '<b><span id="' . $result['faq_id'] . '">' . $result['question'] . '</span></b><br>' . $result['answer'];
	}
    return $result;
  }

  while ($faq = faq_toc($language)) {
?>
		    <li><?php echo $faq['toc']; ?>
<?php 
  }
?>
            </ol><hr size="1"><ol>
<?php 
  while ($faq = read_faq($language)) {
?>
            <li><b><span id="<?php echo $faq['faq_id']; ?>"><?php echo $faq['question']; ?></span></b><br>
            <?php echo $faq['answer']; ?><br><br><a href="<?php echo tep_href_link(FILENAME_FAQ,'#top'); ?>" target="_self"><?php echo FAQ_BACK_TO_TOP; ?></a><br><br>
<?php 
  }
?>
            </ol></td>
          </tr>
        </table>
        </td>
      </tr>
    </table></td>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>