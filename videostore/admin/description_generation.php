<?php
/*
  $Id: stats_products_viewed.php,v 1.29 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

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
<script>
	function checkit(){
	if (document.getElementById('number').value!='') document.f1.submit();
	else{
		alert("Enter product ID first, plz!");
		return false;
		}
	}

	function wholedb(){
		document.getElementById('action').value='wholedb';
		document.f1.submit();
	}

</script>
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
    <td width="100%" valign="top">
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Description Scrubing</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
	  <tr>
<td><br/>
<?
function decraper($html, $delstyles=false) {
$whitespace = array("\t","\n","\r");
$spaces = array(' ',' ',' ',' ');
$html = str_replace($whitespace, '', $html);
for ($t = 1; $t <= 5; $t++) {
$html = (str_replace($spaces, ' ', $html));
}
$commoncrap = array('&quot;'
,'font-weight: normal;'
,'font-style: normal;'
,'line-height: normal;'
,'font-size-adjust: none;'
,'font-stretch: normal;'); //If it is so normal, why they bother?
$replace = array("'");
$html = str_replace($commoncrap, $replace, $html);
$patterns = array();
$replacements = array();
$patterns[0] = '/(<table\s.*)(width=)(\d+%)(\D)/i'; # Fix unquoted non-alphanumeric characters in table tags
$patterns[1] = '/(<td\s.*)(width=)(\d+%)(\D)/i';
$patterns[2] = '/(<th\s.*)(width=)(\d+%)(\D)/i';
$patterns[3] = '/<td( colspan="[0-9]+")?( rowspan="[0-9]+")?( width="[0-9]+")?( height="[0-9]+")?.*?>/i';
$patterns[4] = '/<tr.*?>/i';
$patterns[5] = '/<\/st1:address>(<\/st1:\w*>)?<\/p>[\n\r\s]*<p[\s\w="\']*>/i';
$patterns[6] = '/<o:p.*?>/i';
$patterns[7] = '/<\/o:p>/i';
$patterns[8] = '/<o:SmartTagType[^>]*>/i';
$patterns[9] = '/<st1:[\w\s"=]*>/i';
$patterns[10] = '/<\/st1:\w*>/i';
$patterns[11] = '/<p class="MsoNormal"[^>]*>(.*?)<\/p>/i';
$patterns[12] = '/ style="margin-top: 0cm;"/i';
$patterns[13] = '/<(\w[^>]*) class=([^ |>]*)([^>]*)/i';
$patterns[14] = '/<ul(.*?)>/i';
$patterns[15] = '/<ol(.*?)>/i';
$patterns[17] = '/<br \/>&nbsp;<br \/>/i';
$patterns[18] = '/&nbsp;<br \/>/i';
$patterns[19] = '/<!-.*?>/';
$patterns[20] = '/\s*style=(""|\'\')/';
$patterns[21] = '/ style=[\'"]tab-interval:[^\'"]*[\'"]/i';
$patterns[22] = '/behavior:[^;\'"]*;*(\n|\r)*/i';
$patterns[23] = '/mso-[^:]*:"[^"]*";/i';
$patterns[24] = '/mso-[^;\'"]*;*(\n|\r)*/i';
$patterns[25] = '/\s*font-family:[^;"]*;?/i';
$patterns[26] = '/margin[^"\';]*;?/i';
$patterns[27] = '/text-indent[^"\';]*;?/i';
$patterns[28] = '/tab-stops:[^\'";]*;?/i';
$patterns[29] = '/border-color: *([^;\'"]*)/i';
$patterns[30] = '/border-collapse: *([^;\'"]*)/i';
$patterns[31] = '/page-break-before: *([^;\'"]*)/i';
$patterns[32] = '/font-variant: *([^;\'"]*)/i';
$patterns[33] = '/<span [^>]*><br \/><\/span><br \/>/i';
$patterns[34] = '/" "/';
$patterns[35] = '/[\t\r\n]/';
$patterns[36] = '/\s\s/s';
$patterns[37] = '/ style=""/';
$patterns[38] = '/<span>(.*?)<\/span>/i';
$patterns[39] = '/<span>(.*?)<\/span>/i';//twice, nested spans
$patterns[40] = '/(;\s|\s;)/';
$patterns[41] = '/;;/';
$patterns[42] = '/";/';
$patterns[43] = '/<li(.*?)>/i';
$patterns[45] = '/<\?xml:namespace(.*?)\/>/i';
$patterns[46] = '/<span>/i';
$patterns[47] = '/<\/span>/i';
$patterns[48] = '/<p><\/p>/i';
$patterns[48] = '/<STRONG>(.[ ]*)<\/STRONG>/i';
$patterns[49] = '/<STRONG>/i';
$patterns[50] = '/<p><\/p>/i';
$patterns[51] = '/<st1:country-region>/i';
$patterns[52] = '/<\/st1:country-region>/i';
$patterns[53] = '/<P> <\/P>/i';
$patterns[54] = '/<font(.*?)>/i';
$patterns[55] = '/<\/font>/i';
$patterns[56] = '/<span lang=es>/i';

$patterns[57] = '/<H[1-6]>/i';
$patterns[58] = '/<\/H[1-6]>/i';

$replacements[0] = '$1$2"$3"$4';
$replacements[1] = '$1$2"$3"$4';
$replacements[2] = '$1$2"$3"$4';
$replacements[3] = '<td$1$2$3$4>';
$replacements[4] = '<tr>';
$replacements[5] = '<br />';
$replacements[6] = '';
$replacements[7] = '';
$replacements[8] = '';
$replacements[9] = '';
$replacements[10] = '';
$replacements[11] = '$1<br />';
$replacements[12] = '';
$replacements[13] = '<$1$3';
$replacements[14] = '<ul>';
$replacements[15] = '<ol>';
$replacements[17] = '<br />';
$replacements[18] = '<br />';
$replacements[19] = '';
$replacements[20] = '';
$replacements[21] = '';
$replacements[22] = '';
$replacements[23] = '';
$replacements[24] = '';
$replacements[25] = '';
$replacements[26] = '';
$replacements[27] = '';
$replacements[28] = '';
$replacements[29] = '';
$replacements[30] = '';
$replacements[31] = '';
$replacements[32] = '';
$replacements[33] = '<br />';
$replacements[34] = '""';
$replacements[35] = '';
$replacements[36] = '';
$replacements[37] = '';
$replacements[38] = '$1';
$replacements[39] = '$1';
$replacements[40] = ';';
$replacements[41] = ';';
$replacements[42] = '"';
$replacements[43] = '<li>';
$replacements[45] = '';
$replacements[46] = '';
$replacements[47] = '';
$replacements[48] = '';
$replacements[49] = ' <STRONG>';
$replacements[50] = '';
$replacements[51] = '';
$replacements[52] = '';
$replacements[53] = '';
$replacements[54] = '';
$replacements[55] = '';
$replacements[56] = '';

$replacements[57] = '<B>';
$replacements[58] = '</B><BR />';

if($delstyles===true){
$patterns[44] = '/ style=".*?"/';
$replacements[44] = '';
}
ksort($patterns);
ksort($replacements);
$html = preg_replace($patterns, $replacements, $html);
for ($t=1;$t<=3;$t++) {
$html = (str_replace($spaces, ' ', $html));
}
return $html;
}

?>
	<form action="description_generation.php" method="post" name="f1">
		<input id="action" type="hidden" name="action" value="generate"/>
	Product Id: <input type="text" name="product_id" value="<?=$product_id?>" id="number" /><br/>
	<input onclick="return checkit();" type="submit" value="Generate and Update" />&nbsp;<input onclick="return wholedb();" type="submit" value="Generate and Update for Whole DB" />&nbsp;<input type="button" value="Cancel" onclick="location.href='description_generation.php'" />
	</form><br/><br/><br/>
<?
if ($_GET['flag']=="true"){
echo "<br>".$i." products successfully updated!<br/><br/><br/>";
}

if ($_POST['action']=="generate"){
echo "<br>Successfully updated!<br/><br/><br/>";
	$descr = tep_db_fetch_array(tep_db_query("SELECT products_id, products_description FROM products_description where products_id=".$_POST[product_id]." limit 0,1"));
	tep_db_query("update products_description set products_description='".addslashes(decraper(stripslashes($descr[products_description]), true))."' where products_id=".$descr[products_id]);
?>
<b>Was:</b> <textarea style="width:800px; height:300px;"><?=$descr[products_description]?></textarea><br/><br/>
<b>Is:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <textarea style="width:800px; height:300px;"><?=decraper(stripslashes($descr[products_description]), true)?></textarea><br/><br/>
<?
echo "<br>Successfully updated!";
}

if ($_POST['action']=="wholedb"){

ini_set("memory_limit","128M");

$fp = fopen("../db_backup/products_description.sql", "w");

$sql = "SELECT * from products_description";
$export = tep_db_query($sql);
$fields = mysql_num_fields($export);
  while ($row = tep_db_fetch_array($export)) {
	$k = 0;
	$line = '';
	$line = "insert into products_description values(";
		foreach($row as $value){
			if (mysql_field_name($export, $k)!='products_id')
				$line .=",'".addslashes($value)."'";
			else
				$line .=addslashes($value);
			$k++;
			}
	$line .=");";
	$data .=trim($line)."\n";
	
}
	$data = str_replace("\r","",$data);

$fp = fopen("../db_backup/products_description.sql", "w+");
fputs($fp, $data);
fclose($fp);

	$i=0;
	$descr = tep_db_query("SELECT products_id, products_description FROM products_description order by products_id");
	while ($row = tep_db_fetch_array($descr)){
	if ($row[products_id]!=''){
		tep_db_query("update products_description set products_description='".addslashes(decraper(stripslashes($row[products_description]), true))."' where products_id='".$row[products_id]."'");
		//echo "update products_description set products_description='".addslashes(decraper(stripslashes($row[products_description]), true))."' where products_id='".$row[products_id]."'<br/>";
		$i++;
		}
	}
	echo "<script>window.location.href='description_generation.php?flag=true&i=".$i."';</script>";
}
?>

</td></tr>
        </table></td>
      </tr>
            </table>
		</td>
          </tr>
	<tr><td></td></tr>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>