<?php
ob_start();
/*
  $Id: vendor_order_products.php,v 1.80 2005/25/08 11:40:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FAQ);
  define('TXT_HOME', 'Home');
  define('TXT_PRODUCT_INFO', 'Frequently Ask Question');
  if (isset($_SESSION["vendors_id"]) && $_SESSION["vendors_id"] <> "")
  {
  }
  else
  {
	tep_redirect("../index.php");
	exit;
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="./includes/main.css">
<link rel="stylesheet" type="text/css" href="../stylesheet.css">

<script>
function rowOverEffect(object) {
  if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
}

</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<table border="0" width="100%" cellspacing="1" cellpadding="1">
<tr>
	<td width="100%" class="headerNavigation">&nbsp;<a href="../index.php" class="headerNavigation"><?php echo TXT_HOME?></span></a>&nbsp;-&nbsp;<a href="faq.php" class="headerNavigation"><?php echo TXT_PRODUCT_INFO;?></a>&nbsp;-&nbsp;<span><b>Distribution</b></span></td>
</tr>
</table>

<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php
	if (tep_session_is_registered('vendors_id'))
		require('./includes/column_left.php');
?>
<!-- left_navigation_eof //-->
    </table></td>
        <td width="100%" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
	          <tr>
	            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	            <tr>
					<td colspan=6 width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
					  <tr><td class="pageHeading" colspan=6>Frequently Ask Questions</td></tr>
					</table>
					</td>
      			</tr>
		<tr>
	<td>
<ol>
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
	$result = tep_db_fetch_array(tep_db_query("SELECT faq_id, question FROM " . TABLE_FAQ . " WHERE $query visible='1' and $query language='$language' and type=2 ORDER BY v_order"));
	if ($result['faq_id']) {
	  $old_faq_id .= $result['faq_id'] . '&';
	  $result['toc'] = '<a href="' . tep_href_link('vendors/'.FILENAME_FAQ,'#' . $result['faq_id']) . '"><b>' . $result['question'] . '</b></a>';
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
	$result = tep_db_fetch_array(tep_db_query("SELECT faq_id, question, answer FROM " . TABLE_FAQ . " WHERE $query visible='1' and type=2 and $query language='$language' ORDER BY v_order"));
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
            <?php echo $faq['answer']; ?><br><br><a href="<?php echo tep_href_link('vendors/'.FILENAME_FAQ,'#top'); ?>" target="_self"><?php echo FAQ_BACK_TO_TOP; ?></a><br><br>
<?php 
  }
?>
            </ol>
	</td>
		</tr>
	</table></td></tr>
    </table>
<br/>
</td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require('footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
