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
	$result = tep_db_fetch_array(tep_db_query("SELECT faq_id, question FROM " . TABLE_FAQ . " WHERE $query visible='1' AND language = '$language' ORDER BY v_order asc"));
	if ($result['faq_id']) {
	  $old_faq_id .= $result['faq_id'] . '&';
	  $result['toc'] = '<a href="' . tep_href_link(FILENAME_FAQ_VIEW_ALL,'#' . $result['faq_id']) . '"><b>' . $result['question'] . '</b></a>';
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
	$result = tep_db_fetch_array(tep_db_query("SELECT faq_id, question, answer FROM " . TABLE_FAQ . " WHERE $query visible='1' AND language = '$language' ORDER BY v_order asc"));
	if ($result['faq_id']) {
	  $old_faq_id .= $result['faq_id'] . '&';
	  $result['faq'] = '<b><span id="' . $result['faq_id'] . '">' . $result['question'] . '</span></b><br>' . $result['answer'];
	}
    return $result;
  }
  
	function browse_faq ($language,$HTTP_GET_VARS) {
	  if ($HTTP_GET_VARS['faq_lang'] != '') {
	  	$query_lang = "WHERE language = '$HTTP_GET_VARS[faq_lang]'";
	  } elseif ($HTTP_GET_VARS['faq_action'] == 'Added') {
	  	$query_lang = "WHERE language = '$language'";
	  }
	  $query = "SELECT *, DATE_FORMAT(date, '%d.%m.%y') AS d FROM " . TABLE_FAQ . " $query_lang ORDER BY v_order";
	  $daftar = tep_db_query($query);
	  $c=0;
	  while ($buffer = tep_db_fetch_array($daftar)) {
	  	$result[$c] = $buffer;
	  	$c++;
	  }
	  return $result;
	}

	function browse_faq_vendor ($language,$HTTP_GET_VARS) {
	  if ($HTTP_GET_VARS['faq_lang'] != '') {
	  	$query_lang = "WHERE language = '$HTTP_GET_VARS[faq_lang]'";
	  } elseif ($HTTP_GET_VARS['faq_action'] == 'Added') {
	  	$query_lang = "WHERE language = '$language'";
	  }
if ($query_lang!='') $query_lang .= "and type=2";
	else
		     $query_lang = "where type=2";
	  $query = "SELECT *, DATE_FORMAT(date, '%d.%m.%y') AS d FROM " . TABLE_FAQ . " $query_lang ORDER BY v_order";
	  $daftar = tep_db_query($query);
	  $c=0;
	  while ($buffer = tep_db_fetch_array($daftar)) {
	  	$result[$c] = $buffer;
	  	$c++;
	  }
	  return $result;
	}
	
	function read_data ($faq_id) {
	  $result = tep_db_fetch_array(tep_db_query("SELECT * FROM " . TABLE_FAQ . " WHERE faq_id=$faq_id"));
	  return $result;
	}
	
	function error_message($error) {
	  switch ($error) {
		case "20":
		  return '<tr class=messageStackError><td>' . tep_image(DIR_WS_IMAGES . 'icons/warning.gif', FAQ_WARNING) . ' ' . FAQ_ERROR_20 . '</td></tr>';
		  break;
		case "80":
		  return '<tr class=messageStackError><td>' . tep_image(DIR_WS_IMAGES . 'icons/warning.gif', FAQ_WARNING) . ' ' . FAQ_ERROR_80 . '</td></tr>';
		  break;
		default:
		  return $error;
	  }
	}
  
?>