<?php

require('includes/application_top.php');

ini_set("memory_limit","256M");

function generate(){
global $cart, $currencies, $whole, $customer_id;

require('pdf/fpdf.php');
require('pdf/bookmark.php');

$currdate = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));

$name = getCustomerName();

tep_db_connect() or die('Unable to connect to database server!');

$products = $cart->get_products();

for ($i=0, $n=sizeof($products); $i<$n; $i++) {
	if ($i!=sizeof($products)-1) $sep = ','; else $sep = '';
	$search_string .= $products[$i][id].$sep;
}

$num = $cart->count_contents();

$sql_query = "SELECT p.products_always_on_hand, products_tax_class_id,products_distribution, p.products_closed_captioned, media.products_media_type_name, p.products_date_available, p.products_release_date, p.products_price, sr.series_name, p.products_image_med, p.products_id, p.products_model, p.products_run_time, p.products_upc, p.products_release_date, f.products_video_format_name, pd.products_name_prefix, pd.products_description, pd.products_name, pd.products_name_suffix, pd.products_clip_url, c.producers_name, IF (s.status, s.specials_new_products_price, NULL ) AS specials_new_products_price, IF (s.status, s.specials_new_products_price, p.products_price) AS final_price FROM products p JOIN products_to_categories p2c ON p.products_id = p2c.products_id LEFT JOIN products_video_formats f ON p.products_video_format_id = f.products_video_format_id LEFT JOIN specials s ON p.products_id = s.products_id LEFT JOIN series sr ON p.series_id = sr.series_id LEFT JOIN products_description pd ON p.products_id = pd.products_id JOIN products_media_types media ON p.products_media_type_id=media.products_media_type_id LEFT JOIN producers c ON p.producers_id = c.producers_id where p.products_id IN (".$search_string.")  GROUP BY products_id";

$products_query = tep_db_query($sql_query);
$total = ceil((tep_db_num_rows($products_query))/10);
$pdf = new PDF_Bookmark('P','mm','A4');

$pdf->SetDisplayMode('real');
$pdf->SetAuthor('www.travelvideostore.com');
$pdf->SetCreator('www.travelvideostore.com');
$pdf->SetCreator('Distribution Products');
$pdf->SetTitle('Distribution Products');
$pdf->SetFont('Arial','',9);

$pdf->AddPage();
$pdf->Text(185,295,'Page '.$pdf->PageNo().' of '.ceil($total));
$pdf->SetFont('Arial','I',9);
$pdf->Text(5,295,'TravelVideoStore Shopping Cart for '.$name.' on '.date("m/d/Y"));
$pdf->SetFont('Arial','',9);
$pdf->Rect('1','1','208.2','295.2');

$i=0;
$counter = 0;
$cnt = 0;
$iterator = 0;
while ($products = tep_db_fetch_array($products_query)) {
$MSRP = 0; $SALE= 0;
$iterator++;
$next_producer_name = $current_producer_name;
$current_producer_name = $products[producers_name];




/************* PRICE CALCULATION **********************/
if ($whole['iswholesale'] == 1){
	if ($products['products_distribution'] == 1){

	if (($whole['disc1'] == 1) && (intval($whole[distribution_percentage])>0)){
		$MSRP =  $currencies->format($products['products_price'], true, 'USD', '1.000000');
		$SALE =  $currencies->format(tep_customer_price($products['products_price'], $products['products_id'], 1), true, 'USD', '1.000000');	
		}	
	elseif (($whole['disc1'] == 2) && (intval($whole[distribution_percentage])>0)){
		if (intval($products['specials_new_products_price'])>0){
		$MSRP = $currencies->format($products['specials_new_products_price'], true, 'USD', '1.000000');
		$SALE = $currencies->format(tep_customer_price($products['specials_new_products_price'], $products['products_id'], 1), true, 'USD', '1.000000');	
		}
		else{
		$MSRP = $currencies->format($products['products_price'], true, 'USD', '1.000000');
		$SALE = $currencies->format(tep_customer_price($products['products_price'], $products['products_id'], 1), true, 'USD', '1.000000');	
			}
		}	
	else{
 	   if ($products['specials_new_products_price']) {
      		$MSRP = $currencies->format($products['products_price'], true, 'USD', '1.000000');
		$SALE = $currencies->format($products['specials_new_products_price'], true, 'USD', '1.000000');
    		} else {
      	    	$MSRP = $currencies->format($products['products_price'], true, 'USD', '1.000000');
    		}
	   }

	}
	else{
	if (($whole['disc2'] == 1) && (intval($whole[nondistribution_percentage])>0)){
		$MSRP = $currencies->format($products['products_price'], true, 'USD', '1.000000');
		$SALE = $currencies->format(tep_customer_price($products['products_price'], $products['products_id'], 2), true, 'USD', '1.000000');	
		}	
	
	elseif (($whole['disc2'] == 2) && (intval($whole[nondistribution_percentage])>0)){
	 	if (intval($products['specials_new_products_price'])>0) {
			$MSRP = $currencies->format($products['specials_new_products_price'], true, 'USD', '1.000000');
			$SALE = $currencies->format(tep_customer_price($products['specials_new_products_price'], $products['products_id'], 2), true, 'USD', '1.000000');	
			}
			else{
			$MSRP = $currencies->format($products['products_price'], true, 'USD', '1.000000'); 
			$SALE = $currencies->format(tep_customer_price($products['products_price'], $products['products_id'], 2), true, 'USD', '1.000000');	
				}
		}
	else{
 	   if ($products['specials_new_products_price']) {
      		$MSRP = $currencies->format($products['products_price'], true, 'USD', '1.000000');
		$SALE = $currencies->format($products['specials_new_products_price'], true, 'USD', '1.000000');
    		} else {
      	    	$MSRP = $currencies->format($products['products_price'], true, 'USD', '1.000000');
    		}
	   }
	}
}
	else{
 	   if ($products['specials_new_products_price']) {
      		$MSRP = $currencies->format($products['products_price'], true, 'USD', '1.000000');
		$SALE = $currencies->format($products['specials_new_products_price'], true, 'USD', '1.000000');
    		} else {
      	    	$MSRP = $currencies->format($products['products_price'], true, 'USD', '1.000000');
    		}
}

/**************** END OF PRICE CALCULATION **************************/




if ($counter%2==0) $Imageleft = 0; else $Imageleft = 105;
if ($counter%2==0) $Textleft = 40; else $Textleft = 145;
if ($counter%2==0) $fromTop = 5; else $Textleft = 145;
if ($counter%2==0) $MorefromLeft = 42; else $MorefromLeft = 147;
if ($counter%2==0) $VideofromLeft = 10; else $Videofromleft = 145;
if ($counter%2==0) $VideoBlock = 81; else $VideoBlock = 186;
if ($counter%2==0) $ModelBlock = 56; else $ModelBlock = 161;
if ($counter%2==0) $UPCBlock = 6; else $UPCBlock = 111;
if ($counter%2==0) $ProducerBlock = 42; else $ProducerBlock = 147;
if ($counter%2==0) $SeriesBlock = 6; else $SeriesBlock = 111;



	if (is_file('images/'.$products[products_image_med]))
		$images = "images/".$products[products_image_med];
		$params = @getimagesize($images);
if (!empty($products[series_name])) {
	$pdf->SetFont('Arial','B',10);
	$pdf->Text(35+$SeriesBlock,5+$percentTop, $products[series_name]);
	$pdf->SetFont('Arial','',9);
}
if (is_file('images/'.$products[products_image_med])){
	if (trim($products['products_media_type_name'])=="VHS") $wid = 30; else $wid = 35;
	$pdf->Image('images/'.$products[products_image_med],5+$Imageleft,6+$percentTop, $wid, 48);
}

$pdf->SetLeftMargin($Textleft);
if ($counter==0) $fromUPC=0;
$pdf->setY(1+$fromTop+$percentTop);
$pdf->SetFont('Arial','BI',10);

if (!empty($products[products_release_date])) 
	$release = '('.$products[products_release_date].')';

$pdf->MultiCell(63,3.5, $products[products_prefix].' '.$products[products_name].' '.$products[products_suffix].' '.$release, 0, 1,'J');
$pdf->SetFont('Arial','I',10);
$plus = 0;
if (!empty($products[products_model])) 
		$pdf->Text($UPCBlock,58+$fromUPC+$percentTop, "Model: ".$products[products_model]);

if (!empty($products[products_upc]))
		$pdf->Text($ModelBlock,58+$fromUPC+$percentTop, "UPC: ".$products[products_upc]);

$pdf->SetFont('Arial','',9);

$comm = trim(strip_tags($products[products_description]));
$comm = eregi_replace("\r", "", $comm); 
$comm = eregi_replace("\n", "", $comm);
$comm = eregi_replace("&nbsp;", "", $comm);
$comm = eregi_replace("&amp;", "&", $comm);
$comm = eregi_replace("&#39", "'", $comm);
$comm = eregi_replace("&#34", "\"", $comm);

$comm = substr($comm, 0, 250);

$pdf->MultiCell(63,3.5, $comm."...", 0,'J');
if ($counter==0) $fromText=50; else $fromText = $fromText+74;
$pdf->setY($fromText);
$pdf->Image('images/moreinfo.jpg',$MorefromLeft,45+$percentTop, 18, 6, $link);
$link = $pdf->Link($MorefromLeft,45+$percentTop,'18','6', 'http://www.travelvideostore.com/product_info.php?products_id='.$products[products_id]);
$pdf->SetFont('Arial','B',10);
if ($products['products_closed_captioned']==0)
	$pdf->Image('images/cc.jpg',$MorefromLeft,37+$percentTop, 6, 4);

$part = explode("-",substr($products[products_date_available],0,10));
$date_available = mktime(0, 0, 1, $part[1], $part[2], $part[0]);

if (intval($date_available)>$currdate){
	$pdf->SetTextColor('000','00','255');
	$pdf->SetFont('Arial','B',9);
	$pdf->Text(7+$MorefromLeft,40+$percentTop,'PRE-ORDERED Ships '.substr($products['products_date_available'],0,10));
	}
	$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor('000','100','000');
//$pdf->Text($MorefromLeft,44+$percentTop,'MSRP $'.round($products[products_price],2));
$pdf->Text($MorefromLeft,44+$percentTop,'MSRP '.$MSRP);

if ($SALE!=''){
	$pdf->SetTextColor('255','00','00');
	$pdf->Text(36+$MorefromLeft,44+$percentTop,'SALE '.$SALE);
}



$pdf->SetFont('Arial','',9);
$pdf->SetTextColor('0','0','0');
if (trim($products['products_clip_url'])!=''){
	$pdf->Image('images/video_clip.jpg',$MorefromLeft+18,45+$percentTop, 18, 6);
	$link = $pdf->Link($MorefromLeft+18,45+$percentTop,'18','6', $products['products_clip_url']);
}


$prod_name = eregi_replace("&#39", "'", $products[producers_name]);
$prod_name = eregi_replace("&#34", "\"", $prod_name);

if (!empty($prod_name)){
		$pdf->SetFont('Arial','B',9);
		$pdf->Text($ProducerBlock,54+$percentTop, $prod_name);
		$pdf->SetFont('Arial','',9);
}

$pdf->Text($VideoBlock,48+$percentTop, $products[products_video_format_name]);
$pdf->Text($VideoBlock,51+$percentTop, $products[products_run_time]);
$i=$i+74;
$counter++;
if ($counter==2) {
	$counter = 0;
	$percentTop= $percentTop+58;
}
$cnt++;
	if ($cnt==10){
		if ($iterator/10 != $total){
		$percentTop = 0; $cnt = 0;
		$pdf->ln(65);
		$pdf->AddPage();	
		$pdf->Text(185,295,'Page '.$pdf->PageNo().' of '.ceil($total));
		$pdf->SetFont('Arial','I',9);
		$pdf->Text(5,295,'TravelVideoStore  Shopping Cart for '.$name.' on '.date("m/d/Y"));
		$pdf->SetFont('Arial','',9);
		$pdf->Rect('1','1','208.2','295.2');
		$i=0; $counter=0;
	    }
	}
}

$pdf->AddPage();	
$pdf->Image('images/order_form.jpg',20,15,170);


$sql = "update pdf set value=0 where id=1";

$pdf->Output();

}

tep_db_connect() or die('Unable to connect to database server!');

generate();

?>