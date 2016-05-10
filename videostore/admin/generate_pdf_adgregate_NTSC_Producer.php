<?php

ini_set("memory_limit","128M");

require('fpdf.php');
require('bookmark.php');

require('includes/configure.php');
require('includes/functions/database.php');
tep_db_connect() or die('Unable to connect to database server!');
$sql_query = "SELECT p.products_price, sr.series_name, p.products_image_med, p.products_audio_languages, p.products_subtitle_languages ,p.products_summary, p.products_quantity, p.products_id, p.products_model, p.products_run_time,p.products_upc, p.products_release_date, p.adgregate, p.products_asin,p.products_price,f.products_video_format_name,pd.products_name_prefix, pd.products_description, pd.products_name, pd.products_name_suffix, pd.products_clip_url, pd.products_head_desc_tag,pd.products_head_keywords_tag,pd.products_url,pd.products_clip_name,c.producers_name, pv.vendors_id, v.vendors_name,	pv.master, pv.cover, pv.label, pv.web, pv.amazon, pv.mediazone, pv.google, pv.akimbo, pv.distribution_end_date, r.products_region_code_name,r.products_region_code_desc,m.products_media_type_name, pk.products_packaging_type_name,st.products_set_type_name, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price FROM (((((((((products p, products_description pd, producers c) LEFT JOIN products_to_vendors pv on p.products_id = pv.products_id) LEFT JOIN products_video_formats f on p.products_video_format_id = f.products_video_format_id) LEFT JOIN vendors v on pv.vendors_id=v.vendors_id) LEFT JOIN products_region_codes r on p.products_region_code_id=r.products_region_code_id) LEFT JOIN products_media_types m on p.products_media_type_id=m.products_media_type_id) LEFT JOIN products_packaging_types pk on p.products_packaging_type_id=pk.products_packaging_type_id) LEFT JOIN specials s on p.products_id = s.products_id) LEFT JOIN products_set_types st on p.products_set_type_id  = st.products_set_type_id) LEFT JOIN series sr on p.series_id  = sr.series_id WHERE p.producers_id = c.producers_id AND p.products_id = pd.products_id AND p.products_distribution=1 and p.products_status=1 AND f.products_video_format_id=1 group by products_id order by c.producers_name";
$products_query = tep_db_query($sql_query);
$total = ceil((tep_db_num_rows($products_query))/10);
//$pdf = new FPDF('P','mm','A4');
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
$pdf->Text(5,295,'TravelVideoStore Distribution Catalog '.date("m/d/Y"));
$pdf->SetFont('Arial','',9);
$pdf->Rect('1','1','208.2','295.2');


$i=0;
$counter = 0;
$cnt = 0;
$iterator = 0;
while ($products = tep_db_fetch_array($products_query)) {
$iterator++;
$next_producer_name = $current_producer_name;
$current_producer_name = $products[producers_name];

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



	if (is_file('../images/'.$products[products_image_med]))
		$images = "../images/".$products[products_image_med];
		$params = @getimagesize($images);
if (!empty($products[series_name])) {
	$pdf->SetFont('Arial','B',12);
	$pdf->Text(36+$SeriesBlock,5+$percentTop, $products[series_name]);
	$pdf->SetFont('Arial','',9);
}
if (is_file('../images/'.$products[products_image_med]))
	$pdf->Image('../images/'.$products[products_image_med],5+$Imageleft,6+$percentTop, 35, 48);
$pdf->SetLeftMargin($Textleft);
if ($counter==0) $fromUPC=0;
$pdf->setY($fromTop+$percentTop);
$pdf->SetFont('Arial','B',10);

if (!empty($products[products_release_date])) 
	$release = '('.$products[products_release_date].')';

$pdf->Cell(145,5, $products[products_prefix].' '.$products[products_name].' '.$products[products_suffix].' '.$release, 0, 1,'J');

$pdf->SetFont('Arial','I',10);
$plus = 0;
if (!empty($products[products_model])) 
		$pdf->Text($UPCBlock,58+$fromUPC+$percentTop, "Model: ".$products[products_model]);

if (!empty($products[products_upc]))
		$pdf->Text($ModelBlock,58+$fromUPC+$percentTop, "UPC: ".$products[products_upc]);

$pdf->SetFont('Arial','',9);

$comm = strip_tags($products[products_description]);
$comm = preg_replace("/\r/i", "", $comm); 
$comm = preg_replace("/\n/i", "", $comm);
$comm = preg_replace("/&nbsp;/i", "", $comm);
$comm = substr($comm, 0, 250);

$pdf->MultiCell(63,4, $comm."...", 0,'J');
if ($counter==0) $fromText=50; else $fromText = $fromText+74;
$pdf->setY($fromText);
$pdf->Image('../images/moreinfo.jpg',$MorefromLeft,44+$percentTop, 18, 6, $link);
$link = $pdf->Link($MorefromLeft,44+$percentTop,'18','6', 'http://www.travelvideostore.com/product_info.php?products_id='.$products[products_id]);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor('255','0','0');
$pdf->Text($MorefromLeft,42+$percentTop,'$'.round($products[products_price],2));
$pdf->SetFont('Arial','',9);
$pdf->SetTextColor('0','0','0');
if ($products['adgregate'] == '1'){
	$pdf->Image('../images/video_clip.jpg',$MorefromLeft+18,44+$percentTop, 18, 6);
	$link = $pdf->Link($MorefromLeft+18,44+$percentTop,'18','6', 'http://www.travelvideostore.com/adgregate.php?id='.$products[products_id]);
}

if ($current_producer_name!=$next_producer_name) $pdf->Bookmark($products[producers_name],null,$percentTop);

if (!empty($products[producers_name])){
		$pdf->SetFont('Arial','B',9);
		$pdf->Text($ProducerBlock,54+$percentTop, $products[producers_name]);
		$pdf->SetFont('Arial','',9);
}

$pdf->Text($VideoBlock,47+$percentTop, $products[products_video_format_name]);
$pdf->Text($VideoBlock,50+$percentTop, $products[products_run_time]);
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
		$pdf->Text(5,295,'TravelVideoStore Distribution Catalog '.date("m/d/Y"));
		$pdf->SetFont('Arial','',9);
		$pdf->Rect('1','1','208.2','295.2');
		$i=0; $counter=0;
	    }
	}
}

$pdf->AddPage();	
$pdf->Image($abs_url.'../images/order_form.jpg',20,15,170);


//$producer_query = "SELECT c.producers_name, p.producers_id FROM products p, products_description pd, producers c LEFT JOIN products_to_vendors pv on p.products_id = pv.products_id LEFT JOIN products_video_formats f on p.products_video_format_id = f.products_video_format_id LEFT JOIN vendors v on pv.vendors_id=v.vendors_id LEFT JOIN products_region_codes r on p.products_region_code_id=r.products_region_code_id LEFT JOIN products_media_types m on p.products_media_type_id=m.products_media_type_id LEFT JOIN products_packaging_types pk on p.products_packaging_type_id=pk.products_packaging_type_id LEFT JOIN specials s on p.products_id = s.products_id LEFT JOIN products_set_types st on p.products_set_type_id  = st.products_set_type_id LEFT JOIN series sr on p.series_id  = sr.series_id WHERE p.producers_id = c.producers_id AND p.products_id = pd.products_id AND p.products_distribution=1 and p.products_image_med!='' and sr.series_name!='' group by producers_name order by c.producers_name";
//$producer_query = tep_db_query($producer_query);

$pdf->Output();
?>