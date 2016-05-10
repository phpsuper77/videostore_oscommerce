<?php

require('../includes/configure.php');
require('../includes/functions/database.php');
require('../includes/functions/general.php');

ini_set("memory_limit","128M");

function generate($cPath, $filter, $sort, $field, $value, $string){

require('fpdf.php');
require('bookmark.php');


$currdate = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
$name = getCustomerName();
if ($filter=='1') $filter_type = '(VHS)';
if ($filter=='2') $filter_type = '(DVD)';
if ($filter=='') $filter_type = '(DVD/VHS)';


tep_db_connect() or die('Unable to connect to database server!');


//$sql = "update pdf set value=1 where id=1";
//tep_db_query($sql);

if ($sort!=''){
	$sort_field = substr($sort,0,1);
	$sort_order = substr($sort,1,1);
if ($sort_order=='a') $direction = "ASC"; else $direction = "DESC";
if ($sort_field=='1') $sort_string = "ORDER BY media.products_media_type_name";
if ($sort_field=='3') $sort_string = "ORDER BY pd.products_name ";
if ($sort_field=='4') $sort_string = "ORDER BY final_price";

$final_sorting = $sort_string." ".$direction;
}
	else $final_sorting = " order by p.products_always_on_hand desc, p.products_quantity desc, pd.products_name";

if ($filter!='')
	$filter_string = 'and p.products_media_type_id='.$filter." ";

if (!empty($cPath)){
	$sql_query = "SELECT p.products_always_on_hand, p.products_closed_captioned, media.products_media_type_name, p.products_date_available, p.products_release_date, p.products_price, sr.series_name, p.products_image_med, p.products_id, p.products_model, p.products_run_time, p.products_upc, p.products_release_date, f.products_video_format_name, pd.products_name_prefix, pd.products_description, pd.products_name, pd.products_name_suffix, pd.products_clip_url, c.producers_name, IF (s.status, s.specials_new_products_price, NULL ) AS specials_new_products_price, IF (s.status, s.specials_new_products_price, p.products_price) AS final_price FROM products p JOIN products_to_categories p2c ON p.products_id = p2c.products_id and p2c.categories_id = '".$_GET[cPath]."' LEFT JOIN products_video_formats f ON p.products_video_format_id = f.products_video_format_id LEFT JOIN specials s ON p.products_id = s.products_id LEFT JOIN series sr ON p.series_id = sr.series_id LEFT JOIN products_description pd ON p.products_id = pd.products_id JOIN products_media_types media ON p.products_media_type_id=media.products_media_type_id ".$filter_string." LEFT JOIN producers c ON p.producers_id = c.producers_id where (p.products_quantity>0 or (p.products_quantity=0 and p.products_always_on_hand=1) or (p.products_quantity<0 and p.products_always_on_hand=1))  GROUP BY products_id ".$final_sorting;
	$cat_query = tep_db_fetch_array(tep_db_query("SELECT categories_name from categories_description where categories_id=".$cPath));
	$category_name = $cat_query['categories_name'];
	}

if ($field=='producers_id'){
	$sql_query = "SELECT p.products_date_available, p.products_always_on_hand, p.products_closed_captioned, media.products_media_type_name, p.products_release_date, p.products_price, sr.series_name, p.products_image_med, p.products_id, p.products_model, p.products_run_time, p.products_upc, p.products_release_date, f.products_video_format_name, pd.products_name_prefix, pd.products_description, pd.products_name, pd.products_name_suffix, pd.products_clip_url, c.producers_name, IF (s.status, s.specials_new_products_price, NULL ) AS specials_new_products_price, IF (s.status, s.specials_new_products_price, p.products_price) AS final_price FROM products p LEFT JOIN products_video_formats f ON p.products_video_format_id = f.products_video_format_id LEFT JOIN specials s ON p.products_id = s.products_id LEFT JOIN series sr ON p.series_id = sr.series_id LEFT JOIN products_description pd ON p.products_id = pd.products_id JOIN products_media_types media ON p.products_media_type_id=media.products_media_type_id ".$filter_string." JOIN producers c ON p.producers_id = c.producers_id and p.producers_id='".$value."' where (p.products_quantity>0 or (p.products_quantity=0 and p.products_always_on_hand=1) or (p.products_quantity<0 and p.products_always_on_hand=1)) GROUP BY products_id ".$final_sorting;
	$category_name = "Producers Name";
	}

if ($field=='series_id'){
	$sql_query = "SELECT p.products_date_available, p.products_always_on_hand, p.products_closed_captioned, media.products_media_type_name, p.products_release_date, p.products_price, sr.series_name, p.products_image_med, p.products_id, p.products_model, p.products_run_time, p.products_upc, p.products_release_date, f.products_video_format_name, pd.products_name_prefix, pd.products_description, pd.products_name, pd.products_name_suffix, pd.products_clip_url, c.producers_name, IF (s.status, s.specials_new_products_price, NULL ) AS specials_new_products_price, IF (s.status, s.specials_new_products_price, p.products_price) AS final_price FROM products p LEFT JOIN products_video_formats f ON p.products_video_format_id = f.products_video_format_id LEFT JOIN specials s ON p.products_id = s.products_id JOIN series sr ON p.series_id = sr.series_id and p.series_id='".$value."' LEFT JOIN products_description pd ON p.products_id = pd.products_id JOIN products_media_types media ON p.products_media_type_id=media.products_media_type_id ".$filter_string." LEFT JOIN producers c ON p.producers_id = c.producers_id where (p.products_quantity>0 or (p.products_quantity=0 and p.products_always_on_hand=1) or (p.products_quantity<0 and p.products_always_on_hand=1)) GROUP BY products_id ".$final_sorting;
	$category_name = "Series Name";
	}


if (empty($cPath) and empty($field) and empty($value)){
	$category_name = $string;
$search_string = explode(" ", $string);
    $where_str_spec .= " and (";
    for ($i=0, $n=sizeof($search_string); $i<$n; $i++ ) {
      switch ($search_string[$i]) {
        case '(':
        case ')':
        case 'and':
        case 'or':
          $where_str_spec .= " " . $search_string[$i] . " ";
          break;
        default:
          $keyword = $search_string[$i];
	  if ($i!=0) $where_str_spec .=" and ";
          $where_str_spec .= "(pd.products_head_keywords_tag like '%" . $keyword . "%' or p.products_model like '%" . $keyword . "%'";
          //if (isset($HTTP_GET_VARS['search_in_description']) && ($HTTP_GET_VARS['search_in_description'] == '1'))
		  $where_str_spec .= " or pd.products_description like '%" . $keyword . "%'";
		  $where_str_spec .= " or p.products_upc like '%" . $keyword . "%'";
		  $where_str_spec .= " or p.products_isbn like '%" . $keyword . "%'";
          $where_str_spec .= ')';
          break;
      }
    }
    $where_str_spec .= " )";
$sql_query = "SELECT p.products_date_available, p.products_always_on_hand, p.products_closed_captioned, media.products_media_type_name, p.products_release_date, p.products_price, sr.series_name, p.products_image_med, p.products_id, p.products_model, p.products_run_time, p.products_upc, p.products_release_date, f.products_video_format_name, pd.products_name_prefix, pd.products_description, pd.products_name, pd.products_name_suffix, pd.products_clip_url, c.producers_name, IF ( s.status, s.specials_new_products_price,  NULL  ) AS specials_new_products_price, IF ( s.status, s.specials_new_products_price, p.products_price ) AS final_price FROM products p LEFT  JOIN products_video_formats f ON p.products_video_format_id = f.products_video_format_id LEFT  JOIN specials s ON p.products_id = s.products_id LEFT  JOIN series sr ON p.series_id = sr.series_id LEFT  JOIN products_description pd ON p.products_id = pd.products_id JOIN products_media_types media ON p.products_media_type_id = media.products_media_type_id ".$filter_string." LEFT JOIN producers c ON p.producers_id = c.producers_id WHERE p.products_status !=0 ".$where_str_spec." AND (p.products_quantity>0 or (p.products_quantity=0 and p.products_always_on_hand=1) or (p.products_quantity<0 and p.products_always_on_hand=1)) GROUP  BY products_id ".$final_sorting;
}

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
$pdf->Text(5,295,'TravelVideoStore '.$category_name.' '.$filter_type.' Catalog for '.$name.' on '.date("m/d/Y"));
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
	$pdf->SetFont('Arial','B',10);
	$pdf->Text(35+$SeriesBlock,5+$percentTop, $products[series_name]);
	$pdf->SetFont('Arial','',9);
}
if (is_file('../images/'.$products[products_image_med])){
	if (trim($products['products_media_type_name'])=="VHS") $wid = 30; else $wid = 35;
	$pdf->Image('../images/'.$products[products_image_med],5+$Imageleft,6+$percentTop, $wid, 48);
}

$pdf->SetLeftMargin($Textleft);
if ($counter==0) $fromUPC=0;
$pdf->setY(1+$fromTop+$percentTop);
$pdf->SetFont('Arial','BI',10);

if (!empty($products[products_release_date])) 
	$release = '('.$products[products_release_date].')';

//$pdf->Cell(145,5, $products[products_prefix].' '.$products[products_name].' '.$products[products_suffix].' '.$release, 0, 1,'J');
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
//$comm = eregi_replace("&#34", "'", $comm);
$comm = substr($comm, 0, 250);

$pdf->MultiCell(63,3.5, $comm."...", 0,'J');
if ($counter==0) $fromText=50; else $fromText = $fromText+74;
$pdf->setY($fromText);
$pdf->Image('../images/moreinfo.jpg',$MorefromLeft,45+$percentTop, 18, 6, $link);
$link = $pdf->Link($MorefromLeft,45+$percentTop,'18','6', 'http://www.travelvideostore.com/product_info.php?products_id='.$products[products_id]);
$pdf->SetFont('Arial','B',10);
if ($products['products_closed_captioned']==0)
	$pdf->Image('../images/cc.jpg',$MorefromLeft,37+$percentTop, 6, 4);

$part = explode("-",substr($products[products_date_available],0,10));
$date_available = mktime(0, 0, 1, $part[1], $part[2], $part[0]);

if (intval($date_available)>$currdate){
	$pdf->SetTextColor('000','00','255');
	$pdf->SetFont('Arial','B',9);
	$pdf->Text(7+$MorefromLeft,40+$percentTop,'PRE-ORDERED Ships '.substr($products['products_date_available'],0,10));
	}
	$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor('000','100','000');
$pdf->Text($MorefromLeft,44+$percentTop,'MSRP $'.round($products[products_price],2));

if (round($products[specials_new_products_price],2)!=0){
	$pdf->SetTextColor('255','00','00');
	$pdf->Text(36+$MorefromLeft,44+$percentTop,'SALE $'.round($products[specials_new_products_price],2));
}



$pdf->SetFont('Arial','',9);
$pdf->SetTextColor('0','0','0');
if (trim($products['products_clip_url'])!=''){
	$pdf->Image('../images/video_clip.jpg',$MorefromLeft+18,45+$percentTop, 18, 6);
	$link = $pdf->Link($MorefromLeft+18,45+$percentTop,'18','6', $products['products_clip_url']);
}


$prod_name = eregi_replace("&#39", "'", $products[producers_name]);
$prod_name = eregi_replace("&#34", "\"", $prod_name);

//if ($current_producer_name!=$next_producer_name) $pdf->Bookmark($prod_name,null,$percentTop);

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
		$pdf->Text(5,295,'TravelVideoStore '.$category_name.' '.$filter_type.' Catalog '.date("m/d/Y"));
		$pdf->SetFont('Arial','',9);
		$pdf->Rect('1','1','208.2','295.2');
		$i=0; $counter=0;
	    }
	}
}

$pdf->AddPage();	
$pdf->Image('../images/order_form.jpg',20,15,170);


$sql = "update pdf set value=0 where id=1";
//tep_db_query($sql);

$pdf->Output();

}

tep_db_connect() or die('Unable to connect to database server!');

//$sql = "select * from pdf where id=1";
//$status = tep_db_fetch_array(tep_db_query($sql));
//$status[value] = 0;
//if ($status[value]!=1)	

generate($_GET['cPath'], $_GET['filter'], $_GET['sort'], $_GET['field'], $_GET['value'], $_GET['search_string']);

/*
else{
for ($i=0;$i<30;$i++){
$sql = "select * from pdf";
$status = tep_db_fetch_array(tep_db_query($sql));

if ($status[value]==1) {
	sleep('1');
	}
	else{
	generate($_GET['cPath'], $_GET['filter'], $_GET['sort'], $_GET['field'], $_GET['value'], $_GET['search_string']);
	}
	}
}
*/
?>
