<?php

ini_set("memory_limit","6400M");

//require('includes/application_top.php');
require('includes/configure.php');
require('includes/functions/database.php');
require('includes/database_tables.php');

tep_db_connect() or die('Unable to connect to database server!');

require('includes/functions/general.php');

define(DISPLAY_PRICE_WITH_TAX, 'false');
define(DEFAULT_CURRENCY, 'USD');

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

require('fpdf.php');
require('bookmark.php');


  $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);
  $date = date('d-M-Y');


$config = tep_db_fetch_array(tep_db_query("select configuration_value as store_name from configuration where configuration_key='STORE_NAME_ADDRESS' limit 1"));
$top = explode("\n",$config[store_name]);

$num = sizeof($order->products);
$total = ceil(($num-17)/22+1);
$pdf = new PDF_Bookmark('P','mm','A4');

$pdf->SetDisplayMode('real');
$pdf->SetAuthor('www.travelvideostore.com');
$pdf->SetCreator('www.travelvideostore.com');
$pdf->SetCreator('Distribution Products');
$pdf->SetTitle('Invoice Products');
$pdf->SetFont('Arial','',9);

$pdf->AddPage();
$pdf->Text(185,295,'Page '.$pdf->PageNo().' of '.ceil($total));
$pdf->SetFont('Arial','I',9);
$pdf->Text(5,295,'TravelVideoStore All invoices are due upon receipt F.E.I.D. Number 20-0132336');
$pdf->SetFont('Arial','',9);
$pdf->Rect('1','1','208.2','295.2');

$pdf->setXY(3,3);
$pdf->Image('../images/thermal_image.jpg', 2, 2, 38,21);
$pdf->SetFont('Arial','B',15);
$pdf->Text(85,10, 'INVOICE');
$pdf->SetFont('Arial','',9);
$pdf->setXY(45,3);
$pdf->MultiCell(155,6,$top[0],0,'R');
$pdf->setXY(45,8);
$pdf->MultiCell(155,5,$top[1], 0,'R');
$pdf->setXY(45,13);
$pdf->MultiCell(155,5,$top[2]." ".$top[3], 0,'R');
$pdf->setXY(45,18);
$pdf->MultiCell(155,5,str_replace("Worldwide","",$top[4]),0,'R');
$pdf->setXY(45,23);
$pdf->MultiCell(155,5,$top[5],0,'R');
$pdf->setXY(45,28);
$pdf->MultiCell(155,5,$top[6],0,'R');

$pdf->setXY(3,3);
$pdf->SetFont('Arial','B',12);
$pdf->Text(3,40, 'INVOICE #: '.$oID);
$pdf->SetFont('Arial','',9);
$pdf->SetFont('Arial','B',12);
$pdf->Text(3,45, 'Date: '.$order->info['date_purchased']);
$pdf->Line(3,47, 207, 47);
$pdf->SetFont('Arial','B',9);


$pdf->Text(3,52, 'BILL TO: ');
$pdf->Text(120,52, 'SHIP TO: ');
$pdf->SetFont('Arial','',9);
$pdf->setXY(3,54);
$pdf->MultiCell(76,4, str_replace("<BR>", "\n", strtoupper(tep_address_format($order->billing[format_id], $order->billing, 1, "", "<br>"))), 0, 'L', 0);
$pdf->setXY(120,54);
$pdf->MultiCell(76,4, str_replace("<BR>", "\n", strtoupper(tep_address_format($order->delivery[format_id], $order->delivery, 1, "", "<br>"))), 0, 'L', 0);
$pdf->setXY(3,76);
$pdf->Text(3,83, $order->customer['telephone']);
$pdf->Text(3,86, $order->customer['email_address']);
$pdf->setXY(120,66);
$pdf->Text(120,83, 'Payment Method: '.$order->info['payment_method']);
if (tep_not_null($order->info['cc_type']) || tep_not_null($order->info['cc_owner']) || tep_not_null($order->info['cc_number'])) {
$pdf->Text(120,86, 'Credit Card Type: '.$order->info['cc_type']);
$pdf->Text(120,89, 'Name on Credit Card: '.$order->info['cc_owner']);


// credit_card_show_last_4 start								 
								// Only want to show the last 4 digits of the credit card number.
								// Other digits will be represented with an 'X'. 
								$len = (strlen($order->info['cc_number']) - 3);
                $masked_cc_number = '';
                for($i = 0 ; $i < $len ; $i++)
								{
                  $masked_cc_number .= 'X';
								} // for
								$masked_cc_number .= substr($order->info['cc_number'], $len - 1, 4);
// credit_card_show_last_4 end								 

$pdf->Text(120,92, 'Credit Card Number: '.$masked_cc_number);


$pdf->Text(120,95, 'Credit Card Expiration: '.$order->info['cc_expires']);
}
		else if($order->info['purchase_order_number'])
		{
$pdf->Text(120,86, 'Purchase Order Number: '.$order->info['purchase_order_number']);
//$pdf->Text(120,89, 'Name on Credit Card: '.$order->info['cc_owner']);
}
$increment = 52;
$pdf->setXY(3, 48+$increment);
$pdf->MultiCell(56,5,'Products',1,'C',0);
$pdf->setXY(59, 48+$increment);
$pdf->MultiCell(30,5,'Part Number',1,'C', 0);	
$pdf->setXY(89, 48+$increment);
$pdf->MultiCell(18,5,'Tax',1,'C', 0);	
$pdf->setXY(107, 48+$increment);
$pdf->MultiCell(25,5,'Price(ex)',1,'C', 0);	
$pdf->setXY(132, 48+$increment);
$pdf->MultiCell(25,5,'Price(inc)',1,'C', 0);		
$pdf->setXY(157, 48+$increment);
$pdf->MultiCell(25,5,'Total(ex)',1,'C', 0);		
$pdf->setXY(182, 48+$increment);
$pdf->MultiCell(25,5,'Total(inc)',1,'C', 0);		
$pdf->SetFont('Arial','',9);

$k = 0;
    for ($i = 0, $n = $num; $i < $n; $i++) {
$name_title = $order->products[$i]['name'];
/*$comm = ("\r", "", $name_title); 
$comm = ("\n", "", $comm);
$comm = ("&nbsp;", " ", $comm);
$comm = ("&amp;", "&", $comm);
$comm = ("&#39", "'", $comm);
$comm = ("&#34", "\"", $comm);*/

	$pdf->setXY(3, 48+$k*10+5+$increment);
	$pdf->SetFont('Arial','',8);
	if (strlen(trim(strip_tags($comm)))>36) $value = 5; else $value=10;
	if (strlen(trim(strip_tags($comm)))>60) $value = 3.3;
	$pdf->MultiCell(56,$value,$order->products[$i]['qty'].'x'.strip_tags($comm),1,'L',0);
	$pdf->setXY(59, 48+$k*10+5+$increment);	
	if (strlen(trim(strip_tags($order->products[$i]['model'])))>18) $value = 5; else $value=10;
	$pdf->MultiCell(30,$value,$order->products[$i]['model'],1,'C', 0);	
	$pdf->SetFont('Arial','',9);
	$pdf->setXY(89, 48+$k*10+5+$increment);
	$pdf->MultiCell(18,$value,tep_display_tax_value($order->products[$i]['tax']) . '%',1,'C', 0);	
	$pdf->setXY(107, 48+$k*10+5+$increment);
	$pdf->MultiCell(25,$value,$currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']),1,'C', 0);	
	$pdf->setXY(132, 48+$k*10+5+$increment);
	$pdf->MultiCell(25,$value, $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']),1,'C', 0);		
	$pdf->setXY(157, 48+$k*10+5+$increment);
	$pdf->MultiCell(25,$value,$currencies->format(round($order->products[$i]['final_price'], 2) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']),1,'C', 0);		
	$pdf->setXY(182, 48+$k*10+5+$increment);
	$pdf->MultiCell(25,$value,$currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']),1,'C', 0);		
$k++;

if ($pdf->PageNo() == 1) $limit = 17; else $limit = 22;

if ($k == $limit){
$k = 0;
$increment = 0;
$pdf->AddPage();
$pdf->Text(185,295,'Page '.$pdf->PageNo().' of '.ceil($total));
$pdf->SetFont('Arial','I',9);
$pdf->Text(5,295,'TravelVideoStore All invoices are due upon receipt F.E.I.D. Number 20-0132336');
$pdf->SetFont('Arial','',9);
$pdf->Rect('1','1','208.2','295.2');

$pdf->setXY(3,3);
$pdf->Image('../images/thermal_image.jpg', 2, 2, 38,21);
$pdf->SetFont('Arial','B',15);
$pdf->Text(85,10, 'INVOICE');
$pdf->SetFont('Arial','',9);
$pdf->setXY(45,3);
$pdf->MultiCell(155,6,$top[0],0,'R');
$pdf->setXY(45,8);
$pdf->MultiCell(155,5,$top[1], 0,'R');
$pdf->setXY(45,13);
$pdf->MultiCell(155,5,$top[2]." ".$top[3], 0,'R');
$pdf->setXY(45,18);
$pdf->MultiCell(155,5,str_replace("Worldwide","",$top[4]),0,'R');
$pdf->setXY(45,23);
$pdf->MultiCell(155,5,$top[5],0,'R');
$pdf->setXY(45,28);
$pdf->MultiCell(155,5,$top[6],0,'R');

$pdf->setXY(3,3);
$pdf->SetFont('Arial','B',12);
$pdf->Text(3,40, 'INVOICE #: '.$oID);
$pdf->SetFont('Arial','',9);
$pdf->SetFont('Arial','B',12);
$pdf->Text(3,45, 'Date: '.$order->info['date_purchased']);
$pdf->Line(3,47, 207, 47);
$pdf->SetFont('Arial','B',9);

$pdf->setXY(3, 48+$increment);
$pdf->MultiCell(56,5,'Products',1,'C',0);
$pdf->setXY(59, 48+$increment);
$pdf->MultiCell(30,5,'Part Number',1,'C', 0);	
$pdf->setXY(89, 48+$increment);
$pdf->MultiCell(18,5,'Tax',1,'C', 0);	
$pdf->setXY(107, 48+$increment);
$pdf->MultiCell(25,5,'Price(ex)',1,'C', 0);	
$pdf->setXY(132, 48+$increment);
$pdf->MultiCell(25,5,'Price(inc)',1,'C', 0);		
$pdf->setXY(157, 48+$increment);
$pdf->MultiCell(25,5,'Total(ex)',1,'C', 0);		
$pdf->setXY(182, 48+$increment);
$pdf->MultiCell(25,5,'Total(inc)',1,'C', 0);		
$pdf->SetFont('Arial','',9);
}

}


  for ($p = 0, $n = sizeof($order->totals); $p < $n; $p++) {
	$pdf->setXY(22, 53+$k*10+5*$p+$increment);
	$pdf->MultiCell(185,5, strip_tags($order->totals[$p]['title']).' '.strip_tags($order->totals[$p]['text']),0,'R', 0);		
  }

$pdf->Output();

?>
