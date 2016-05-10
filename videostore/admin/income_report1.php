<?php
/*
  $Id: currencies.php,v 1.49 2003/06/29 22:50:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $year = $_GET['year']?$_GET['year']:date("Y");
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
            <td class="pageHeading">PAYMENT METHOD REPORT</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
	<form action="income_report.php" method="get" name="f1">
        <td align="right" class="smalltext">
		Select Year: 
		<select name="year" onChange='document.f1.submit();'>
		<?
			for($i=2003;$i<date("Y")+1; $i++){
			if ($year==$i) $sel = 'selected'; else $sel = '';
			echo "<option value='".$i."' ".$sel.">".$i."</option>";
			}
		?>
		</select>
	</td>
	</form>
      </tr>

      <tr>
        <td class="smalltext">
<? if ($_GET[action]==''){ ?>
		<table cellspacing="0" cellpadding="5" border="0" width="100%">
		<tr class="dataTableHeadingRow">
			<td class="dataTableHeadingContent">Month</td>
			<td class="dataTableHeadingContent">Gross Sales</td>
			<td class="dataTableHeadingContent">Visa Sales</td>
			<td class="dataTableHeadingContent">MasterCard Sales</td>
			<td class="dataTableHeadingContent">Visa/Mastercard Sales</td>
			<td class="dataTableHeadingContent">Discover</td>
			<td class="dataTableHeadingContent">AMEX</td>
			<td class="dataTableHeadingContent">Check Sales</td>
			<td class="dataTableHeadingContent">Purchase Order Sales</td>
			<td class="dataTableHeadingContent">PayPal Sales</td>
                        <td class="dataTableHeadingContent">Amazon Sales</td>
                        <td class="dataTableHeadingContent">CreateSpace Sales</td>
                        <td class="dataTableHeadingContent">Unidentified CC Sales</td>
			<td class="dataTableHeadingContent">Details</td>
		</tr>
		<?
			if ($year==date("Y")) $last_month = date("n")+1; else $last_month=13;
			for ($i=1;$i<$last_month;$i++){
			if (strlen($i)==1) $index = "0".$i; else $index = $i;
		?>
		<tr class="dataTableRow">
			<td class="dataTableContent"><u><b><? echo date("F", mktime(0, 0, 1, $index, 1, $year)); ?></b></u></td>
<?
		$g_total = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));
		$v_total = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.cc_type like '%visa%' and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));
		$m_total = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and (o.cc_type like '%master%card%' or o.cc_type like '%mc%') and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));
		$d_total = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.cc_type like '%discover%' and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));
		$a_total = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and (o.cc_type like '%amex%' or o.cc_type like '%american%express%') and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));
		$c_total = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%check%' and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));
		$p_total = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and (o.payment_method like 'PO%' or o.payment_method like '%Purchase Order%') and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));
		$pp_total = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%pay%pal%' and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));
		$am_total = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%amazon%' and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));
		$cs_total = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%customflix%' and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));
		$ui_total = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%credit%card%'and o.cc_type not like '%master%card%' and o.cc_type not like '%mc%' and o.cc_type not like '%visa%' and o.cc_type not like '%amex%' and o.cc_type not like '%american%express%' and o.cc_type not like '%discover%'  and DATE_FORMAT(o.date_purchased,'%Y-%m')='".$year."-".$index."'"));
?>
		<td class="dataTableContent"><?=$currencies->format($g_total[total],true,'USD','1.000000')?></td>
		<td class="dataTableContent"><?=$currencies->format($v_total[total],true,'USD','1.000000')?></td>
		<td class="dataTableContent"><?=$currencies->format($m_total[total],true,'USD','1.000000')?></td>
		<td class="dataTableContent"><?=$currencies->format(($v_total[total]+$m_total[total]),true,'USD','1.000000')?></td>
		<td class="dataTableContent"><?=$currencies->format($d_total[total],true,'USD','1.000000')?></td>
		<td class="dataTableContent"><?=$currencies->format($a_total[total],true,'USD','1.000000')?></td>
		<td class="dataTableContent"><?=$currencies->format($c_total[total],true,'USD','1.000000')?></td>
		<td class="dataTableContent"><?=$currencies->format($p_total[total],true,'USD','1.000000')?></td>
		<td class="dataTableContent"><?=$currencies->format($pp_total[total],true,'USD','1.000000')?></td>
		<td class="dataTableContent"><?=$currencies->format($am_total[total],true,'USD','1.000000')?></td>
		<td class="dataTableContent"><?=$currencies->format($cs_total[total],true,'USD','1.000000')?></td>
		<td class="dataTableContent"><?=$currencies->format($ui_total[total],true,'USD','1.000000')?></td>
		<td class="dataTableContent"><a style='color:red;' href='income_report.php?action=details&year=<?=$year?>&month=<?=$index?>'>See Details</a></td>
		</tr>
<?                      $gt = $gt + $g_total[total];
                        $vt = $vt + $v_total[total];
			$mt = $mt + $m_total[total];
			$vm = $vt + $mt;
			$dt = $dt + $d_total[total];
			$at = $at + $a_total[total];
			$ct = $ct + $c_total[total];
			$pt = $pt + $p_total[total];
			$ppt = $ppt + $pp_total[total];
			$amt = $amt + $am_total[total];
			$cst = $cst + $cs_total[total];
			$uit = $uit + $ui_total[total];
			
		} 
?>
		<tr class="dataTableRow">
			<td class="dataTableContent"><b>Total:</b></td>
			<td class="dataTableContent"><b><?=$currencies->format($gt,true,'USD','1.000000')?></b></td>
			<td class="dataTableContent"><b><?=$currencies->format($vt,true,'USD','1.000000')?></b></td>
			<td class="dataTableContent"><b><?=$currencies->format($mt,true,'USD','1.000000')?></b></td>
			<td class="dataTableContent"><b><?=$currencies->format($vm,true,'USD','1.000000')?></b></td>
			<td class="dataTableContent"><b><?=$currencies->format($dt,true,'USD','1.000000')?></b></td>
			<td class="dataTableContent"><b><?=$currencies->format($at,true,'USD','1.000000')?></b></td>
			<td class="dataTableContent"><b><?=$currencies->format($ct,true,'USD','1.000000')?></b></td>
			<td class="dataTableContent"><b><?=$currencies->format($pt,true,'USD','1.000000')?></b></td>
			<td class="dataTableContent"><b><?=$currencies->format($ppt,true,'USD','1.000000')?></b></td>
			<td class="dataTableContent"><b><?=$currencies->format($amt,true,'USD','1.000000')?></b></td>
			<td class="dataTableContent"><b><?=$currencies->format($cst,true,'USD','1.000000')?></b></td>
			<td class="dataTableContent"><b><?=$currencies->format($uit,true,'USD','1.000000')?></b></td>
			<td class="dataTableContent">&nbsp;</td>
		</tr>
		</table>
	</td>
      </tr>
    </table>
<? }

if ($_GET[action]=='details'){
?>

<table cellpadding="5" cellspacing="0" border="1" width="100%">
	<tr class="dataTableHeadingRow">
		<td width='5%' ><u><b><? echo date("F", mktime(0, 0, 1, $month, 1, $year)); ?></b></u></td>
		<td class="dataTableHeadingContent">Gross Sale</td>
		<td class="dataTableHeadingContent">Visa Sales</td>
		<td class="dataTableHeadingContent">Mastercard Sales</td>
		<td class="dataTableHeadingContent">Visa/Mastercard Sales</td>
		<td class="dataTableHeadingContent">Discover Sales</td>
		<td class="dataTableHeadingContent">AMEX Sales</td>
		<td class="dataTableHeadingContent">Check Sales</td>
		<td class="dataTableHeadingContent">Purchase Order Sales</td>
		<td class="dataTableHeadingContent">Paypal Sales</td>
		<td class="dataTableHeadingContent">Amazon Sales</td>
		<td class="dataTableHeadingContent">CreateSpace Sales</td>
		<td class="dataTableHeadingContent">Unidentified CC Sales</td>
	</tr>
						<?
						for($p=1;$p<date("t", mktime(0, 0, 1, $month, 1, $year))+1;$p++){
						$r = 0;
						?>
						<tr class="dataTableRow">
							<td class='dataTableContent' width="5%"><?=$p?></td>
							<td class='dataTableContent' width="10%" align="center">
							<?
							if (strlen($p)==1) $d_index = "0".$p; else $d_index=$p;
							$result = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true', 'USD', '1.000000')."&nbsp;<a href='income_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=1'>Details</a>"; else echo "&nbsp;";
							$g_total = $g_total+$result[total];
							?>
							</td>
							<td class='dataTableContent' width="10%" align="center">
							<?
							$result = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.cc_type like '%visa%' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true', 'USD', '1.000000')."&nbsp;<a href='income_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=2'>Details</a>"; else echo "&nbsp;";
							$vis = $result[total];
							$v_total = $v_total+$result[total];
							?>
							</td>
							<td class='dataTableContent' width="10%" align="center">
							<?
							$result = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and (o.cc_type like '%master%card%' or o.cc_type like '%mc%') and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true', 'USD', '1.000000')."&nbsp;<a href='income_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=3'>Details</a>"; else echo "&nbsp;";
							$mas = $result[total];
							$m_total = $m_total+$result[total];
							?>
							</td>
							<td class='dataTableContent' width="10%" align="center">
							<?
							$r = $vis+$mas;
							if(intval($r)!=0)
							echo $currencies->format($r, 'true', 'USD', '1.000000')."&nbsp;<a href='income_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=4'>Details</a>"; else echo "&nbsp;";
							?>
							</td>
							<td class='dataTableContent' width="10%" align="center">
							<?
							$result = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.cc_type like '%discover%' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true', 'USD', '1.000000')."&nbsp;<a href='income_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=5'>Details</a>"; else echo "&nbsp;";
							$d_total = $d_total+$result[total];
							?>
							</td>

							<td class='dataTableContent' width="10%" align="center">
							<?
							$result = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and (o.cc_type like '%amex%' or o.cc_type like '%american%express%') and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true','USD','1.000000')."&nbsp;<a href='income_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=6'>Details</a>"; else echo "&nbsp;";
							$a_total = $a_total+$result[total];
							?>
							</td>
							<td class='dataTableContent' width="10%" align="center">
							<?
							$result = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%check%' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true', 'USD','1.000000')."&nbsp;<a href='income_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=7'>Details</a>"; else echo "&nbsp;";
							$c_total = $c_total+$result[total];
							?>
							</td>
							<td class='dataTableContent' width="10%" align="center">
							<?
							$result = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and (o.payment_method like 'PO%' or o.payment_method like '%Purchase Order%') and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true', 'USD', '1.000000')."&nbsp;<a href='income_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=8'>Details</a>"; else echo "&nbsp;";
							$p_total = $p_total+$result[total];
							?>
							</td>
							<td class='dataTableContent' width="10%" align="center">
							<?
							$result = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%pay%pal%' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true', 'USD', '1.000000')."&nbsp;<a href='income_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=9'>Details</a>"; else echo "&nbsp;";
							$pp_total = $pp_total+$result[total];
							?>

							</td>
							<td class='dataTableContent' width="10%" align="center">
							<?
							$result = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%amazon%' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true', 'USD', '1.000000')."&nbsp;<a href='income_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=10'>Details</a>"; else echo "&nbsp;";
							$am_total = $am_total+$result[total];
							?>

                                                        </td>
							<td class='dataTableContent' width="10%" align="center">
							<?
							$result = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%customflix%' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true', 'USD', '1.000000')."&nbsp;<a href='income_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=11'>Details</a>"; else echo "&nbsp;";
							$cs_total = $cs_total+$result[total];
							?>

                                                        </td>
							<td class='dataTableContent' width="10%" align="center">
							<?
							$result = tep_db_fetch_array(tep_db_query("select sum(round(ot.value,2)) as total from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%credit%card%' and o.cc_type not like '%master%card%' and o.cc_type not like '%mc%' and o.cc_type not like '%visa%' and o.cc_type not like '%amex%' and o.cc_type not like '%american%express%' and o.cc_type not like '%discover%'  and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$d_index."'"));
							if(intval($result[total])!=0)
							echo $currencies->format($result[total], 'true', 'USD', '1.000000')."&nbsp;<a href='income_report.php?action=day_detail&year=".$year."&month=".$month."&day=".$d_index."&type=11'>Details</a>"; else echo "&nbsp;";
							$ui_total = $ui_total+$result[total];
							?>

							</td>
						</tr>
						<?
							}
						?>
      <tr class='dataTableRow'>
		<td width="5%" class='dataTableContent'><b>Total:</b></td>
		<td width="10%" class="dataTableContent" align="center"><?=$currencies->format($g_total, 'true', 'USD', '1.000000')?></td>
		<td width="10%" class="dataTableContent" align="center"><?=$currencies->format($v_total, 'true', 'USD', '1.000000')?></td>
		<td width="10%" class="dataTableContent" align="center"><?=$currencies->format($m_total, 'true', 'USD', '1.000000')?></td>
		<td width="10%" class="dataTableContent" align="center"><?=$currencies->format(($m_total+$v_total), 'true', 'USD', '1.000000')?></td>
		<td width="10%" class="dataTableContent" align="center"><?=$currencies->format($d_total, 'true', 'USD', '1.000000')?></td>
		<td width="10%" class="dataTableContent" align="center"><?=$currencies->format($a_total, 'true', 'USD', '1.000000')?></td>
		<td width="10%" class="dataTableContent" align="center"><?=$currencies->format($c_total, 'true', 'USD', '1.000000')?></td>
		<td width="10%" class="dataTableContent" align="center"><?=$currencies->format($p_total, 'true', 'USD', '1.000000')?></td>
		<td width="10%" class="dataTableContent" align="center"><?=$currencies->format($pp_total, 'true', 'USD', '1.000000')?></td>
		<td width="10%" class="dataTableContent" align="center"><?=$currencies->format($am_total, 'true', 'USD', '1.000000')?></td>
		<td width="10%" class="dataTableContent" align="center"><?=$currencies->format($cs_total, 'true', 'USD', '1.000000')?></td>
		<td width="10%" class="dataTableContent" align="center"><?=$currencies->format($ui_total, 'true', 'USD', '1.000000')?></td>
      </tr>
      <tr><td align="center" colspan="10"><a href='income_report.php?year=<?=$year?>'>&laquo;&nbsp;Back</a></td></tr>
</table>

<? }

if ($_GET[action]=="day_detail"){
?>
<table cellspacing="0" cellspadding="5" width="100%" border="0">
	<tr>
		<td colspan="4">

<?
if ($type == 1) {
	echo "Gross Sale";
	$prod = tep_db_query("select o.orders_id, o.date_purchased, ot.value, o.payment_method, o.cc_type from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."'");
	}
if ($type == 2) {
	echo "Visa Sale";
	$prod = tep_db_query("select o.orders_id, o.date_purchased, ot.value, o.payment_method, o.cc_type from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.cc_type like '%visa%' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."'");
}
if ($type == 3) {
	echo "Mastercard Sale";
	$prod = tep_db_query("select o.orders_id, o.date_purchased, ot.value, o.payment_method, o.cc_type  from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and (o.cc_type like '%master%card%' or o.cc_type like '%mc%') and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."'");
}
if ($type == 4) {
		echo "Visa/Mastercard Sale";
	$prod = tep_db_query("select o.orders_id, o.date_purchased, ot.value, o.payment_method, o.cc_type  from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and (o.cc_type like '%master%card%' or o.cc_type like '%mc%' or o.cc_type like '%visa%') and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."'");
}
if ($type == 5) {
		echo "Discover Sale";
	$prod = tep_db_query("select o.orders_id, o.date_purchased, ot.value, o.payment_method, o.cc_type  from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.cc_type like '%discover%' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."'");
}
if ($type == 6) {
		echo "AMEX Sale";
	$prod = tep_db_query("select o.orders_id, o.date_purchased, ot.value, o.payment_method, o.cc_type  from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and (o.cc_type like '%amex%' or o.cc_type like '%american%express%') and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."'");
}
if ($type == 7) {
		echo "Check Sale";
	$prod = tep_db_query("select o.orders_id, o.date_purchased, ot.value, o.payment_method, o.cc_type  from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%check%' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."'"); 
}
if ($type == 8) {
		echo "Purchase Order Sale";
	$prod = tep_db_query("select o.orders_id, o.date_purchased, ot.value, o.payment_method, o.cc_type  from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and (o.payment_method like 'PO%' or o.payment_method like '%Purchase Order%') and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."'"); 
}
if ($type == 9) {
		echo "PayPal Sale";
	$prod = tep_db_query("select o.orders_id, o.date_purchased, ot.value, o.payment_method, o.cc_type  from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%pay%pal%' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."'");
}
if ($type == 10) {
		echo "Amazon Sale";
	$prod = tep_db_query("select o.orders_id, o.date_purchased, ot.value, o.payment_method, o.cc_type  from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%amazon%' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."'");
}
if ($type == 11) {
		echo "CreateSpace Sale";
	$prod = tep_db_query("select o.orders_id, o.date_purchased, ot.value, o.payment_method, o.cc_type  from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%customflix%' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."'");
}
if ($type == 12) {
		echo "Unidentified CC Sale";
	$prod = tep_db_query("select o.orders_id, o.date_purchased, ot.value, o.payment_method, o.cc_type  from orders o left join orders_total ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.payment_method like '%credit%card%' and o.cc_type not like '%master%card%' and o.cc_type not like '%mc%' and o.cc_type not like '%visa%' and o.cc_type not like '%amex%' and o.cc_type not like '%american%express%' and o.cc_type not like '%discover%' and DATE_FORMAT(o.date_purchased,'%Y-%m-%d')='".$year."-".$month."-".$day."'");
}
echo " on <b>".date("F", mktime(0, 0, 1, $month, $day,  $year)).", ".$day."</b>"
?>
		</td>
	</tr>
	<tr class="dataTableHeadingRow">
		<td class="dataTableHeadingContent" width="25%">Order Number</td>
		<td class="dataTableHeadingContent" width="25%">Date Purchased</td>
		<td class="dataTableHeadingContent" width="25%">Gross Sale</td>
		<td class="dataTableHeadingContent" width="25%">Payment Method</td>
	</tr>
<?
	while ($row = tep_db_fetch_array($prod)){
$total = $total + $row[value];
	$method = $row[payment_method];
	if (strpos($method, 'Credit Card') !== false) $method = $method." (".$row[cc_type].")";
		echo "<tr class='dataTableContentRow'><td class='dataTableContent'><a target='new' href='orders.php?oID=".$row[orders_id]."&action=edit'>".$row[orders_id]."</a></td><td class='dataTableContent'>".$row[date_purchased]."</td><td class='dataTableContent'>".$currencies->format($row[value])."</td><td class='dataTableContent'>".$method."</td></tr>";
}
?>
<tr><td colspan="4" class='dataTableContentRow'><b>Total: <?=$currencies->format($total)?></b></td></tr>
<tr><td align="center" colspan="3"><a href='income_report.php?action=details&year=<?=$year?>&month=<?=$month?>'>&laquo;&nbsp;Back</a></td></tr>
</table>
<?
}

?>
	</td>
      </tr>
	</table>
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