<?php
ini_set("memory_limit", "128M");

$vendors_id=$_GET[vendor_id];
$year      =$_GET[year];
$type      =$_GET[type];
$range     =$_GET[range];

define("TABLE_CURRENCIES", 'currencies');
require('fpdf.php');
require('bookmark.php');

require('includes/configure.php');
require('includes/functions/database.php');

require('includes/classes/currencies.php');
tep_db_connect() or die('Unable to connect to database server!');
$currencies       =new currencies();
$col2lpos        =130; // im mshm, move page 16 totals.

$vend            =tep_db_fetch_array(tep_db_query(
                                         "select vendors_name, vendors_bill_addr1, vendors_bill_addr2, vendors_bill_city, vendors_bill_state,vendors_bill_country,vendors_bill_zip  from vendors where vendors_id="
                                             . $vendors_id));
$vendors_name     =$vend['vendors_name'];
$vendors_address1 =$vend['vendors_bill_addr1'];
$vendors_address2 =$vend['vendors_bill_addr2'];
$vendors_state    =$vend['vendors_bill_state'];
$vendors_zip      =$vend['vendors_bill_zip'];
$vendors_city     =$vend['vendors_bill_city'];
$vendors_country  =$vend['vendors_bill_country'];

$config           =tep_db_fetch_array(tep_db_query(
                                          "select configuration_value as store_name from configuration where configuration_key='STORE_NAME_ADDRESS' limit 1"));
$top              =explode("\n", $config[store_name]);

$consignment_qty_added=0;
$consignment_total=0;

if ($vendors_id != 'All')
    {
    $vendors_condition_1=' and  pv.vendors_id = ' . $vendors_id;
    $vendors_condition_2=' and vendors_id = ' . $vendors_id;
    $vendors_condition_3=' vendor_id = ' . $vendors_id;
    }
else
    {
    $vendors_condition_1='';
    $vendors_condition_2='';
    $vendors_condition_3='';
    }

if ($year != 'All')
    {
    if ($range == 2)
        {
        $start     =date("Y-m-d", mktime(0, 0, 1, 12, 31, $year - 1));
        $end       =date("Y-m-d", mktime(23, 59, 59, 7, 1, $year));

        $start_show=str_replace("-", "/", date("m-d-Y", mktime(0, 0, 1, 12, 31, $year - 1)));
        $end_show  =str_replace("-", "/", date("m-d-Y", mktime(23, 59, 59, 7, 1, $year)));

        $prev_start=date("Y-m-d", mktime(0, 0, 1, 6, 30, $year - 1));
        $prev_end  =date("Y-m-d", mktime(23, 59, 59, 1, 1, $year));
        $date_range="1/1/" . $year . " - 6/30/" . $year;
        }
    elseif ($range == 3)
        {
        $start     =date("Y-m-d", mktime(0, 0, 1, 6, 30, $year));
        $end       =date("Y-m-d", mktime(23, 59, 59, 1, 1, $year + 1));

        $start_show=str_replace("-", "/", date("m-d-Y", mktime(0, 0, 1, 6, 30, $year)));
        $end_show  =str_replace("-", "/", date("m-d-Y", mktime(23, 59, 59, 1, 1, $year + 1)));

        $prev_start=date("Y-m-d", mktime(0, 0, 1, 12, 31, $year - 1));
        $prev_end  =date("Y-m-d", mktime(23, 59, 59, 7, 1, $year));
        $date_range="7/1/" . $year . " - 12/31/" . $year;
        }
    else
        {
        $date_range="All";
        $start     =date("Y-m-d", mktime(0, 0, 1, 12, 31, $year - 1));
        $end       =date("Y-m-d", mktime(23, 59, 59, 1, 1, $year + 1));

        $start_show=str_replace("-", "/", date("m-d-Y", mktime(0, 0, 1, 12, 31, $year - 1)));
        $end_show  =str_replace("-", "/", date("m-d-Y", mktime(23, 59, 59, 1, 1, $year + 1)));

        $prev_start=date("Y-m-d", mktime(0, 0, 1, 12, 31, $year - 2));
        $prev_end  =date("Y-m-d", mktime(23, 59, 59, 1, 1, $year));
        }

    $startat
                         ="and TO_DAYS(o.date_purchased)>TO_DAYS('" . $start
        . "') and TO_DAYS(o.date_purchased)<TO_DAYS('" . $end . "')";
    $startat_prev        ="and TO_DAYS(o.date_purchased)<TO_DAYS('" . $prev_end . "')";
    $payment_startat     ="and TO_DAYS(date)>TO_DAYS('" . $start . "') and TO_DAYS(date)<TO_DAYS('" . $end . "')";
    $consignment_startat ="and TO_DAYS(receive_date)>TO_DAYS('" . $start . "') and TO_DAYS(receive_date)<TO_DAYS('"
        . $end . "')";
    $payment_startat_prev="and TO_DAYS(date)<TO_DAYS('" . $prev_end . "')";
    }

if ($type == "2")
    {
    $show_type            ="Consignment";
    $consignment          =tep_db_query(
                               "SELECT b. * , c.products_model, d.products_name FROM  `products_to_vendors` a JOIN vendor_purchase_order_details b ON ( a.products_id = b.product_id ) LEFT  JOIN products c ON ( b.product_id = c.products_id ) LEFT  JOIN products_description d ON ( c.products_id = d.products_id ) WHERE a.vendors_id ="
                                   . $vendors_id . " AND  b.item_status =  'received'  order by c.products_model");
    $consignment_total    =tep_db_num_rows($consignment);
    $consignment_qty_added=ceil($consignment_total / 23);
    $consignment          =tep_db_query(
                               "SELECT b. * , c.products_model, d.products_name FROM  `products_to_vendors` a JOIN vendor_purchase_order_details b ON ( a.products_id = b.product_id ) LEFT  JOIN products c ON ( b.product_id = c.products_id ) LEFT  JOIN products_description d ON ( c.products_id = d.products_id ) WHERE a.vendors_id ="
                                   . $vendors_id . " AND  b.item_status =  'received'  group by product_id");
    }

if ($type == "3")
    {
    $show_type="Royalty";
    }

$sql_query=
    "select pv.products_id, op.orders_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_item_cost, op.products_sale_type, o.date_purchased, pd.products_name, pd.products_name_prefix, pd.products_name_suffix from orders_products op, products_to_vendors pv,  orders o, products_description pd where o.orders_id = op.orders_id and pd.products_id = pv.products_id and op.products_id = pv.products_id and op.products_sale_type="
    . $type . $vendors_condition_1 . " " . $startat . " order by o.date_purchased desc";
$products_query=tep_db_query($sql_query);

if ($vendors_condition_3 != '' and $payment_startat != '')
    $where=" where " . $vendors_condition_3 . " " . $payment_startat;

if ($vendors_condition_3 != '' and $payment_startat == '')
    $where="where " . $vendors_condition_3;

if ($vendors_condition_3 == '' and $payment_startat != '')
    $where="where " . $payment_startat;

if ($type == 2)
    $typ="Consignment";

if ($type == 3)
    $typ="Royalty";

$where.=" and type='" . $typ . "'";

$payment_rows=tep_db_num_rows(tep_db_query("select * from vendor_payments " . $where));

$total       =ceil((tep_db_num_rows($products_query)) / 23) + ceil($payment_rows / 23) + $consignment_qty_added;

$pdf         =new PDF_Bookmark('P', 'mm', 'A4');
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(201, 201, 201);
$pdf->SetDisplayMode('real');
$pdf->SetAuthor('www.travelvideostore.com');
$pdf->SetCreator('www.travelvideostore.com');
$pdf->SetFont('Arial', '', 9);

$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->text(5, 33 + $i * 10, 'SALES');
$pdf->SetFont('Arial', '', 9);
$pdf->Text(185, 295, 'Page ' . $pdf->PageNo());
$pdf->SetFont('Arial', 'I', 9);
$pdf->Text(5, 290, '*  items have a discount given on the bottom of the invoice not per line');
$pdf->Text(5, 295, 'TravelVideoStore.com ' . $show_type . ' Report ' . $date_range . " " . $vendors_name);
$pdf->SetFont('Arial', '', 9);
$pdf->Rect('1', '1', '208.2', '295.2');
$pdf->setXY(3, 3);
$pdf->Image('../images/thermal_image.jpg', 2, 2, 38, 21);
$pdf->SetFont('Arial', 'B', 15);
$pdf->Text(85, 10, 'STATEMENT');
$pdf->SetFont('Arial', '', 9);
$pdf->Text(85, 15, $vendors_name);
$pdf->Text(85, 20, $vendors_address1);
$pdf->Text(85, 25, $vendors_address2);
$pdf->Text(85, 30, $vendors_city . " " . $vendors_state . " " . $vendors_zip . " " . $vendors_country);
$pdf->setXY(45, 3);
$pdf->MultiCell(155, 6, $top[0], 0, 'R');
$pdf->setXY(45, 8);
$pdf->MultiCell(155, 5, $top[1], 0, 'R');
$pdf->setXY(45, 13);
$pdf->MultiCell(155, 5, $top[2] . " " . $top[3], 0, 'R');
$pdf->setXY(45, 18);
$pdf->MultiCell(155, 5, str_replace("Worldwide", "", $top[4]), 0, 'R');
$pdf->setXY(45, 23);
$pdf->MultiCell(155, 5, $top[5], 0, 'R');
$pdf->setXY(45, 28);
$pdf->MultiCell(155, 5, $top[6], 0, 'R');

$i=0;
$counter=0;
$cnt=0;
$iterator=0;

$pdf->setXY(5, 35 + $i * 10);
$pdf->MultiCell(20, 10, 'Orders Id', 1, 'C', 1);
$pdf->setXY(25, 35 + $i * 10);
$pdf->MultiCell(55, 10, 'Products Name', 1, 'L', 1);
$pdf->setXY(80, 35 + $i * 10);
$pdf->MultiCell(10, 10, 'Qty', 1, 'C', 1);
$pdf->setXY(90, 35 + $i * 10);
$pdf->MultiCell(15, 5, 'Vendor Accrued', 1, 'C', 1);
$pdf->setXY(105, 35 + $i * 10);
$pdf->MultiCell(15, 5, 'Total Accrued', 1, 'C', 1);
$pdf->setXY(120, 35 + $i * 10);
$pdf->MultiCell(15, 5, 'Selling Price', 1, 'C', 1);
$pdf->setXY(135, 35 + $i * 10);
$pdf->MultiCell(15, 5, 'Total Price', 1, 'C', 1);
$pdf->setXY(150, 35 + $i * 10);
$pdf->MultiCell(20, 10, 'Sale Date', 1, 'C', 1);
$pdf->setXY(170, 35 + $i * 10);
$pdf->MultiCell(30, 10, 'Type', 1, 'C', 1);

$total_sale=0;
$total_sold=0;
$total_qty_sold=0;
$next_order=0;

while ($products=tep_db_fetch_array($products_query))
    {
    $total_qty_sold+=$products['products_quantity'];

    if ($products[orders_id] != $prev)
        $next_order=$next_order + 1;

    $prev=$products[orders_id];

    if ($pdf->PageNo() == 1)
        $top_table=45;
    else
        $top_table=45;

    $pdf->setXY(5, $top_table + $i * 10);
    $pdf->Cell(20, 10, $products['orders_id'], 1, 1, 'C');
    $pdf->setXY(25, $top_table + $i * 10);

    if (strlen(trim($products['products_name_prefix'] . " " . $products['products_name'] . " "
                        . $products['products_name_suffix'])) > 27)
        $value=5;
    else
        $value=10;

    if (strlen(trim($products['products_name_prefix'] . " " . $products['products_name'] . " "
                        . $products['products_name_suffix'])) > 55)
        {
        $value=3.34;
        $flag =1;
        $pdf->SetFont('Arial', '', 8);
        }

    $pdf->MultiCell(55,
                    $value,
                    trim($products['products_name_prefix'] . " " . $products['products_name'] . " "
                             . $products['products_name_suffix']),
                    1,
                    'L');

    if ($flag == 1)
        $pdf->SetFont('Arial', '', 9);

    $pdf->setXY(80, $top_table + $i * 10);
    $pdf->Cell(10, 10, $products['products_quantity'], 1, 1, 'C');
    $pdf->setXY(90, $top_table + $i * 10);

    $ppCnt=tep_db_num_rows(tep_db_query("select * from orders_total where orders_id=" . $products[orders_id]
                                            . " and (class='ot_coupon' or class='ot_qty_discount')"));

    if ($ppCnt > 0)
        $asteriks='*';
    else
        $asteriks='';

    $pdf->Cell(15, 10, $currencies->format($products[products_item_cost], true, 'USD', '1.000000') . $asteriks, 1, 1,
               'C');
    $pdf->setXY(105, $top_table + $i * 10);
    $tot_vc    =$products['products_item_cost'] * $products['products_quantity'];
    $total_sale=$total_sale + $tot_vc;
    $final_cost=$currencies->format($tot_vc, true, 'USD', '1.000000');
    $pdf->Cell(15, 10, $final_cost, 1, 1, 'C');
    $pdf->setXY(120, $top_table + $i * 10);
    $total_sold=$total_sold + ($products[final_price] * $products[products_quantity]);
    $pdf->Cell(15, 10, $currencies->format($products[final_price], true, 'USD', '1.000000'), 1, 1, 'C');
    $pdf->setXY(135, $top_table + $i * 10);
    $final=$products[final_price] * $products[products_quantity];
    $pdf->Cell(15, 10, $currencies->format($final, true, 'USD', '1.000000'), 1, 1, 'C');
    $pdf->setXY(150, $top_table + $i * 10);
    $part=explode(" ", $products[date_purchased]);
    $pdf->MultiCell(20, 5, $products[date_purchased], 1, 'C');
    $pdf->setXY(170, $top_table + $i * 10);

    if ($products['products_sale_type'] == 1)
        $typ="Direct Purchase";

    elseif ($products['products_sale_type'] == 2)
        $typ="Consignment";

    else
        $typ="Royalty";

    $pdf->Cell(30, 10, $typ, 1, 1, 'C');
    $i++;

    if ($i == 23)
        {
        $i=0;
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->text(5, 33 + $i * 10, 'SALES');
        $pdf->SetFont('Arial', '', 9);
        $pdf->setXY(3, 3);
        $pdf->Image('../images/thermal_image.jpg', 2, 2, 38, 21);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Text(85, 10, 'STATEMENT');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(85, 15, $vendors_name);
        $pdf->Text(85, 20, $vendors_address1);
        $pdf->Text(85, 25, $vendors_address2);
        $pdf->Text(85, 30, $vendors_city . " " . $vendors_state . " " . $vendors_zip . " " . $vendors_country);
        $pdf->setXY(45, 3);
        $pdf->MultiCell(155, 6, $top[0], 0, 'R');
        $pdf->setXY(45, 8);
        $pdf->MultiCell(155, 5, $top[1], 0, 'R');
        $pdf->setXY(45, 13);
        $pdf->MultiCell(155, 5, $top[2] . " " . $top[3], 0, 'R');
        $pdf->setXY(45, 18);
        $pdf->MultiCell(155, 5, str_replace("Worldwide", "", $top[4]), 0, 'R');
        $pdf->setXY(45, 23);
        $pdf->MultiCell(155, 5, $top[5], 0, 'R');
        $pdf->setXY(45, 28);
        $pdf->MultiCell(155, 5, $top[6], 0, 'R');
        $pdf->Text(185, 295, 'Page ' . $pdf->PageNo());
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->Text(5, 290, '*  items have a discount given on the bottom of the invoice not per line');
        $pdf->Text(5, 295, 'TravelVideoStore.com ' . $show_type . ' Report ' . $date_range . " " . $vendors_name);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Rect('1', '1', '208.2', '295.2');

        $pdf->setXY(5, 35 + $i * 10);
        $pdf->MultiCell(20, 10, 'Orders Id', 1, 'C', 1);
        $pdf->setXY(25, 35 + $i * 10);
        $pdf->MultiCell(55, 10, 'Products Name', 1, 'L', 1);
        $pdf->setXY(80, 35 + $i * 10);
        $pdf->MultiCell(10, 10, 'Qty', 1, 'C', 1);
        $pdf->setXY(90, 35 + $i * 10);
        $pdf->MultiCell(15, 5, 'Vendor Accrued', 1, 'C', 1);
        $pdf->setXY(105, 35 + $i * 10);
        $pdf->MultiCell(15, 5, 'Total Accrued', 1, 'C', 1);
        $pdf->setXY(120, 35 + $i * 10);
        $pdf->MultiCell(15, 5, 'Selling Price', 1, 'C', 1);
        $pdf->setXY(135, 35 + $i * 10);
        $pdf->MultiCell(15, 5, 'Total Price', 1, 'C', 1);
        $pdf->setXY(150, 35 + $i * 10);
        $pdf->MultiCell(20, 10, 'Sale Date', 1, 'C', 1);
        $pdf->setXY(170, 35 + $i * 10);
        $pdf->MultiCell(30, 10, 'Type', 1, 'C', 1);
        }
    }

$total_prev_sale=0;
//echo "select pv.products_id, op.orders_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_item_cost, op.products_sale_type, o.date_purchased, pd.products_name, pd.products_name_prefix, pd.products_name_suffix from orders_products op, products_to_vendors pv,  orders o, products_description pd where o.orders_id = op.orders_id and pd.products_id = pv.products_id and op.products_id = pv.products_id and op.products_sale_type=".$type.$vendors_condition_1." ".$startat_prev." order by o.date_purchased desc";
$product_prev   =tep_db_query(
                     "select pv.products_id, op.orders_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_item_cost, op.products_sale_type, o.date_purchased, pd.products_name, pd.products_name_prefix, pd.products_name_suffix from orders_products op, products_to_vendors pv,  orders o, products_description pd where o.orders_id = op.orders_id and pd.products_id = pv.products_id and op.products_id = pv.products_id and op.products_sale_type="
                         . $type . $vendors_condition_1 . " " . $startat_prev . " order by o.date_purchased desc");

while ($prev=tep_db_fetch_array($product_prev))
    {
    $tot_prev       = $prev['products_item_cost'] * $prev['products_quantity'];
    $total_prev_sale=$total_prev_sale + $tot_prev;
    }

if ($vendors_condition_3 != '' and $payment_startat != '')
    $where=" where " . $vendors_condition_3 . " " . $payment_startat;

if ($vendors_condition_3 != '' and $payment_startat == '')
    $where="where " . $vendors_condition_3;

if ($vendors_condition_3 == '' and $payment_startat != '')
    $where="where " . $payment_startat;

if ($type == 2)
    $typ="Consignment";

if ($type == 3)
    $typ="Royalty";

$where.=" and type='" . $typ . "'";

$total_payment_today=tep_db_fetch_array(tep_db_query("select sum(payment) as total from vendor_payments " . $where));

$total_prev_payment =0;

if ($vendors_condition_3 != '' and $payment_startat_prev != '')
    $where=" where " . $vendors_condition_3 . " " . $payment_startat_prev;

if ($vendors_condition_3 != '' and $payment_startat_prev == '')
    $where="where " . $vendors_condition_3;

if ($vendors_condition_3 == '' and $payment_startat_prev != '')
    $where="where " . $payment_startat_prev;

if ($type == 2)
    $typ="Consignment";

if ($type == 3)
    $typ="Royalty";

$where.=" and type='" . $typ . "'";

$total_prev_payment=tep_db_fetch_array(tep_db_query("select sum(payment) as  total from vendor_payments " . $where));

if ($year != 'All')
    $total_payment=$total_payment_today[total] + $total_prev_payment[total];
else
    $total_payment=$total_payment_today[total];

if ($i < 20)
    {
    $pdf->setXY(5, 45 + $i * 10);
    $pdf->Cell(30, 10, "Total orders: " . $next_order, 0, 0, 'L');
    $pdf->setXY(5, 50 + $i * 10);
    $pdf->Cell(30, 10, "Total quantity: " . $total_qty_sold, 0, 0, 'L');
    $pdf->setXY(5, 55 + ($i * 10));
    $pdf->Cell(30, 10, "Total Sale: " . $currencies->format($total_sold, true, 'USD', '1.000000'), 0, 0, 'L');
    $col2lpos=125;
    $pdf->setXY($col2lpos, 45 + ($i * 10));

    if ($year != 'All')
        $pdf->Cell(30,
                   10,
                   "Previous Periods Total Accrued: " . $currencies->format($total_prev_sale, true, 'USD', '1.000000'),
                   0,
                   0,
                   'L');

    $pdf->setXY($col2lpos, 50 + ($i * 10));

    if ($year != 'All')
        $pdf->Cell(30, 10, "Total Previous Payments: " . $currencies->format($total_payment, true, 'USD', '1.000000'),
                   0,  0,  'L');

    ;
    $pdf->setXY($col2lpos, 55 + ($i * 10));
    $pdf->Cell(30, 10, "Current Period Total Accrued: " . $currencies->format($total_sale, true, 'USD', '1.000000'), 0,
               0,  'L');
    $pdf->setXY($col2lpos, 60 + ($i * 10));
    $pdf->SetFont('Arial', 'B', 10);

    if ($year != 'All')
        $pending_due=($total_sale + $total_prev_sale) - $total_payment;
    else
        $pending_due=($total_sale)-$total_payment;

    $pdf->Cell(30, 10, "Total Payment Due Pending: " . $currencies->format($pending_due, true, 'USD', '1.000000'), 0, 0,
               'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->setXY($col2lpos, 85 + ($i * 10));
    $royalty_due=($total_sale + $total_prev_sale);
    if ($total_sale == $total_prev_sale) {
    $royalty_due=($royalty_due * .5); }

    $pdf->Cell(30, 10, "Total Lifetime Accrued: " . $currencies->format($royalty_due, true, 'USD', '1.000000'), 0, 0,
               'L');
    }
else
    {
    $i=0;
    $pdf->AddPage();
    $pdf->setXY(3, 3);
    $pdf->Image('../images/thermal_image.jpg', 2, 2, 38, 21);
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Text(85, 10, 'STATEMENT');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Text(85, 15, $vendors_name);
    $pdf->Text(85, 20, $vendors_address1);
    $pdf->Text(85, 25, $vendors_address2);
    $pdf->Text(85, 30, $vendors_city . " " . $vendors_state . " " . $vendors_zip . " " . $vendors_country);
    $pdf->setXY(45, 3);
    $pdf->MultiCell(155, 6, $top[0], 0, 'R');
    $pdf->setXY(45, 8);
    $pdf->MultiCell(155, 5, $top[1], 0, 'R');
    $pdf->setXY(45, 13);
    $pdf->MultiCell(155, 5, $top[2] . " " . $top[3], 0, 'R');
    $pdf->setXY(45, 18);
    $pdf->MultiCell(155, 5, str_replace("Worldwide", "", $top[4]), 0, 'R');
    $pdf->setXY(45, 23);
    $pdf->MultiCell(155, 5, $top[5], 0, 'R');
    $pdf->setXY(45, 28);
    $pdf->MultiCell(155, 5, $top[6], 0, 'R');

    $pdf->Text(185, 295, 'Page ' . $pdf->PageNo());
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Text(5, 295, 'TravelVideoStore.com ' . $show_type . ' Report ' . $date_range . " " . $vendors_name);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Rect('1', '1', '208.2', '295.2');

    $pdf->setXY(5, 35 + $i * 10);
    $pdf->Cell(30, 10, "Total orders: " . $next_order, 0, 0, 'L');
    $pdf->setXY(5, 45 + $i * 10);
    $pdf->Cell(30, 10, "Total quantity: " . $total_qty_sold, 0, 0, 'L');
    $pdf->setXY(5, 50 + ($i * 10));
    $pdf->Cell(30, 10, "Total Sale: " . $currencies->format($total_sold, true, 'USD', '1.000000'), 0, 0, 'L');

    $pdf->setXY($col2lpos, 35 + ($i * 10));

    if ($year != 'All')
        $pdf->Cell(30,
                   10,
                   "Previous Periods Total Accrued: " . $currencies->format($total_prev_sale, true, 'USD', '1.000000'),
                   0,
                   0,
                   'L');

    $pdf->setXY($col2lpos, 40 + ($i * 10));

    if ($year != 'All')
        $pdf->Cell(30, 10, "Total Previous Payments: " . $currencies->format($total_payment, true, 'USD', '1.000000'),
                   0,  0,  'L');

    $pdf->setXY($col2lpos, 45 + ($i * 10));
    $pdf->Cell(30, 10, "Current Period Total Accrued: " . $currencies->format($total_sale, true, 'USD', '1.000000'), 0,
               0,  'L');
    $pdf->setXY($col2lpos, 50 + ($i * 10));
    $pdf->SetFont('Arial', 'B', 10);

    if ($year != 'All')
        $pending_due=($total_sale + $total_prev_sale) - $total_payment;
    else
        $pending_due=($total_sale)-$total_payment;

    $pdf->Cell(30, 10, "Total Payment Due Pending: " . $currencies->format($pending_due, true, 'USD', '1.000000'), 0, 0,
               'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->setXY(145, 65 + ($i * 10));
    $royalty_due=($total_sale + $total_prev_sale);
    if ($total_sale == $total_prev_sale) {
    $royalty_due=($royalty_due * .5); } 
    $pdf->Cell(30, 10, "Total Lifetime Accrued: " . $currencies->format($royalty_due, true, 'USD', '1.000000'), 0, 0,
               'L');
    }

include("gen_vendor_prod.php");

if ($vendors_condition_3 != '' and $payment_startat != '')
    $where=" where " . $vendors_condition_3 . " " . $payment_startat;

if ($vendors_condition_3 != '' and $payment_startat == '')
    $where="where " . $vendors_condition_3;

if ($vendors_condition_3 == '' and $payment_startat != '')
    $where="where " . $payment_startat;

if ($type == 2)
    $typ="Consignment";

if ($type == 3)
    $typ="Royalty";

$where.=" and type='" . $typ . "'";

$sql_query="select * from vendor_payments " . $where;
$payment  =tep_db_query($sql_query);
$found    =tep_db_num_rows($payment);

if ($found > 0)
    {
    $i=0;
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->text(5, 33 + $i * 10, 'PAYMENTS');
    $pdf->SetFont('Arial', '', 9);

    $pdf->setXY(3, 3);
    $pdf->Image('../images/thermal_image.jpg', 2, 2, 38, 21);
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Text(85, 10, 'STATEMENT');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Text(85, 15, $vendors_name);
    $pdf->Text(85, 20, $vendors_address1);
    $pdf->Text(85, 25, $vendors_address2);
    $pdf->Text(85, 30, $vendors_city . " " . $vendors_state . " " . $vendors_zip . " " . $vendors_country);
    $pdf->setXY(45, 3);
    $pdf->MultiCell(155, 6, $top[0], 0, 'R');
    $pdf->setXY(45, 8);
    $pdf->MultiCell(155, 5, $top[1], 0, 'R');
    $pdf->setXY(45, 13);
    $pdf->MultiCell(155, 5, $top[2] . " " . $top[3], 0, 'R');
    $pdf->setXY(45, 18);
    $pdf->MultiCell(155, 5, str_replace("Worldwide", "", $top[4]), 0, 'R');
    $pdf->setXY(45, 23);
    $pdf->MultiCell(155, 5, $top[5], 0, 'R');
    $pdf->setXY(45, 28);
    $pdf->MultiCell(155, 5, $top[6], 0, 'R');

    $pdf->Text(185, 295, 'Page ' . $pdf->PageNo());
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Text(5, 295, 'TravelVideoStore.com ' . $show_type . ' Report ' . $date_range . " " . $vendors_name);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Rect('1', '1', '208.2', '295.2');

    $pdf->setXY(5, 35 + $i * 10);
    $pdf->MultiCell(40, 10, 'ID', 1, 'C', 1);
    $pdf->setXY(45, 35 + $i * 10);
    $pdf->MultiCell(40, 10, 'Payment Date', 1, 'L', 1);
    $pdf->setXY(85, 35 + $i * 10);
    $pdf->MultiCell(20, 10, 'Method', 1, 'C', 1);
    $pdf->setXY(105, 35 + $i * 10);
    $pdf->MultiCell(25, 10, 'Type', 1, 'C', 1);
    $pdf->setXY(130, 35 + $i * 10);
    $pdf->MultiCell(20, 10, 'Payment', 1, 'C', 1);
    $pdf->setXY(150, 35 + $i * 10);
    $pdf->MultiCell(15, 10, 'Status', 1, 'C', 1);

    $total_payment=0;
    $first        =true;

    while ($row=tep_db_fetch_array($payment))
        {
        if ($first == true)
            $top_table=45;
        else
            $top_table=45;

        $pdf->setXY(5, $top_table + $i * 10);
        $pdf->Cell(40, 10, $row[id], 1, 1, 'C');
        $pdf->setXY(45, $top_table + $i * 10);
        $pdf->Cell(40, 10, $row[date], 1, 1, 'L');
        $pdf->setXY(85, $top_table + $i * 10);
        $pdf->Cell(20, 10, $row[method], 1, 1, 'C');
        $pdf->setXY(105, $top_table + $i * 10);
        $pdf->Cell(25, 10, $row[type], 1, 1, 'C');
        $pdf->setXY(130, $top_table + $i * 10);
        $total_payment=$total_payment + $row[payment];
        $pdf->Cell(20, 10, $currencies->format($row[payment], true, 'USD', '1.000000'), 1, 1, 'C');
        $pdf->setXY(150, $top_table + $i * 10);
        $pdf->Cell(15, 10, $row[status], 1, 1, 'C');
        $i++;

        if ($i == 23)
            {
            $i=0;
            $pdf->AddPage();
            $first=false;
            $pdf->setXY(3, 3);
            $pdf->Image('../images/thermal_image.jpg', 2, 2, 38, 21);
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->Text(85, 10, 'STATEMENT');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Text(85, 15, $vendors_name);
            $pdf->Text(85, 20, $vendors_address1);
            $pdf->Text(85, 25, $vendors_address2);
            $pdf->Text(85, 30, $vendors_city . " " . $vendors_state . " " . $vendors_zip . " " . $vendors_country);
            $pdf->setXY(45, 3);
            $pdf->MultiCell(155, 6, $top[0], 0, 'R');
            $pdf->setXY(45, 8);
            $pdf->MultiCell(155, 5, $top[1], 0, 'R');
            $pdf->setXY(45, 13);
            $pdf->MultiCell(155, 5, $top[2] . " " . $top[3], 0, 'R');
            $pdf->setXY(45, 18);
            $pdf->MultiCell(155, 5, str_replace("Worldwide", "", $top[4]), 0, 'R');
            $pdf->setXY(45, 23);
            $pdf->MultiCell(155, 5, $top[5], 0, 'R');
            $pdf->setXY(45, 28);
            $pdf->MultiCell(155, 5, $top[6], 0, 'R');

            $pdf->Text(185, 295, 'Page ' . $pdf->PageNo());
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->Text(5, 295, 'TravelVideoStore.com ' . $show_type . ' Report ' . $date_range . " " . $vendors_name);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Rect('1', '1', '208.2', '295.2');

            $pdf->setXY(5, 35 + $i * 10);
            $pdf->MultiCell(40, 10, 'ID', 1, 'C', 1);
            $pdf->setXY(45, 35 + $i * 10);
            $pdf->MultiCell(40, 10, 'Payment Date', 1, 'L', 1);
            $pdf->setXY(85, 35 + $i * 10);
            $pdf->MultiCell(20, 10, 'Method', 1, 'C', 1);
            $pdf->setXY(105, 35 + $i * 10);
            $pdf->MultiCell(25, 10, 'Type', 1, 'C', 1);
            $pdf->setXY(130, 35 + $i * 10);
            $pdf->MultiCell(20, 10, 'Payment', 1, 'C', 1);
            $pdf->setXY(150, 35 + $i * 10);
            $pdf->MultiCell(15, 10, 'Status', 1, 'C', 1);
            }
        }

    $pdf->setXY(5, 45 + $i * 10);
    $pdf->Cell(30, 10, "Total payment: " . $currencies->format($total_payment, true, 'USD', '1.000000'), 0, 0, 'L');
    $pdf->setXY(5, 55 + $i * 10);

    if ($year != 'All')
        $pdf->Cell(30,
                   10,
                   "Previous Total payment: " . $currencies->format($total_prev_payment[total], true, 'USD',
                                                                    '1.000000'),
                   0,
                   0,
                   'L');
    }

if ($consignment_total > 0)
    {
    $i=0;
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->text(5, 33 + $i * 10, 'CONSIGNMENT RECEIPTS');
    $pdf->SetFont('Arial', '', 9);

    $pdf->setXY(3, 3);
    $pdf->Image('../images/thermal_image.jpg', 2, 2, 38, 21);
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Text(85, 10, 'STATEMENT');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Text(85, 15, $vendors_name);
    $pdf->Text(85, 20, $vendors_address1);
    $pdf->Text(85, 25, $vendors_address2);
    $pdf->Text(85, 30, $vendors_city . " " . $vendors_state . " " . $vendors_zip . " " . $vendors_country);
    $pdf->setXY(45, 3);
    $pdf->MultiCell(155, 6, $top[0], 0, 'R');
    $pdf->setXY(45, 8);
    $pdf->MultiCell(155, 5, $top[1], 0, 'R');
    $pdf->setXY(45, 13);
    $pdf->MultiCell(155, 5, $top[2] . " " . $top[3], 0, 'R');
    $pdf->setXY(45, 18);
    $pdf->MultiCell(155, 5, str_replace("Worldwide", "", $top[4]), 0, 'R');
    $pdf->setXY(45, 23);
    $pdf->MultiCell(155, 5, $top[5], 0, 'R');
    $pdf->setXY(45, 28);
    $pdf->MultiCell(155, 5, $top[6], 0, 'R');

    $pdf->Text(185, 295, 'Page ' . $pdf->PageNo());
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Text(5, 295, 'TravelVideoStore.com ' . $show_type . ' Report ' . $date_range . " " . $vendors_name);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Rect('1', '1', '208.2', '295.2');
    $pdf->setXY(5, 35 + $i * 10);
    $pdf->MultiCell(40, 10, 'Products Model', 1, 'C', 1);
    $pdf->setXY(45, 35 + $i * 10);
    $pdf->MultiCell(60, 10, 'Products Title', 1, 'C', 1);
    $pdf->setXY(105, 35 + $i * 10);
    $pdf->MultiCell(20, 5, 'Qty Received', 1, 'C', 1);
    $pdf->setXY(125, 35 + $i * 10);
    $pdf->MultiCell(20, 10, 'Cost', 1, 'C', 1);
    $pdf->setXY(145, 35 + $i * 10);
    $pdf->MultiCell(30, 10, 'Received Date', 1, 'C', 1);
    $total_products=0;
    $total_qty     =0;
    $total_cost    =0;

    while ($row=tep_db_fetch_array($consignment))
        {
        $total_products   = $total_products + 1;
        $tot_for_paid     =0;
        $tot_for_prod     =0;
        $consignment_local=tep_db_query(
                               "SELECT b. * , c.products_model, d.products_name, d.products_name_prefix, d.products_name_suffix FROM  `products_to_vendors` a JOIN vendor_purchase_order_details b ON ( a.products_id = b.product_id ) LEFT  JOIN products c ON ( b.product_id = c.products_id ) LEFT  JOIN products_description d ON ( c.products_id = d.products_id ) WHERE  b.item_status =  'received'  and b.product_id="
                                   . $row[product_id]);

        while ($rows=tep_db_fetch_array($consignment_local))
            {
            $tot_for_prod = $tot_for_prod + $rows[qty];
            $tot_for_paid =$tot_for_paid + ($rows[price] * $rows[qty]);
            $total_cost   =$total_cost + ($rows[price] * $rows[qty]);
            $total_qty    =$total_qty + $rows[qty];
            $pdf->setXY(5, 45 + $i * 10);
            $pdf->Cell(40, 10, $rows[products_model], 1, 1, 'C');

            if (strlen(trim($rows['products_name_prefix'] . " " . $rows['products_name'] . " "
                                . $rows['products_name_suffix'])) > 39)
                $value=5;
            else
                $value=10;

            if (strlen(trim($rows['products_name_prefix'] . " " . $rows['products_name'] . " "
                                . $rows['products_name_suffix'])) > 60)
                $value=3.3;

            $pdf->setXY(45, 45 + $i * 10);
            //$pdf->Cell(60,$value,$rows[products_name],1,1,'C');
            $pdf->MultiCell(60,
                            $value, trim($rows['products_name_prefix'] . " " . $rows['products_name'] . " "
                                             . $rows['products_name_suffix']),
                            1,
                            'L');
            $pdf->setXY(105, 45 + $i * 10);
            $pdf->Cell(20, 10, $rows[qty], 1, 1, 'C');
            $pdf->setXY(125, 45 + $i * 10);
            $pdf->Cell(20, 10, $currencies->format($rows[price], true, 'USD', '1.000000'), 1, 1, 'C');
            $pdf->setXY(145, 45 + $i * 10);
            $pdf->Cell(30, 10, $rows[receive_date], 1, 1, 'C');
            $i++;

            if ($i == 20)
                {
                $i=0;
                $pdf->AddPage();
                $pdf->setXY(3, 3);
                $pdf->Image('../images/thermal_image.jpg', 2, 2, 38, 21);
                $pdf->SetFont('Arial', 'B', 15);
                $pdf->Text(85, 10, 'STATEMENT');
                $pdf->SetFont('Arial', '', 9);
                $pdf->Text(85, 15, $vendors_name);
                $pdf->Text(85, 20, $vendors_address1);
                $pdf->Text(85, 25, $vendors_address2);
                $pdf->Text(85, 30, $vendors_city . " " . $vendors_state . " " . $vendors_zip . " " . $vendors_country);
                $pdf->setXY(45, 3);
                $pdf->MultiCell(155, 6, $top[0], 0, 'R');
                $pdf->setXY(45, 8);
                $pdf->MultiCell(155, 5, $top[1], 0, 'R');
                $pdf->setXY(45, 13);
                $pdf->MultiCell(155, 5, $top[2] . " " . $top[3], 0, 'R');
                $pdf->setXY(45, 18);
                $pdf->MultiCell(155, 5, str_replace("Worldwide", "", $top[4]), 0, 'R');
                $pdf->setXY(45, 23);
                $pdf->MultiCell(155, 5, $top[5], 0, 'R');
                $pdf->setXY(45, 28);
                $pdf->MultiCell(155, 5, $top[6], 0, 'R');

                $pdf->Text(185, 295, 'Page ' . $pdf->PageNo());
                $pdf->SetFont('Arial', 'I', 9);
                $pdf->Text(5, 295,
                           'TravelVideoStore.com ' . $show_type . ' Report ' . $date_range . " " . $vendors_name);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Rect('1', '1', '208.2', '295.2');
                $pdf->setXY(5, 35 + $i * 10);
                $pdf->MultiCell(40, 10, 'Products Model', 1, 'C', 1);
                $pdf->setXY(45, 35 + $i * 10);
                $pdf->MultiCell(60, 10, 'Products Title', 1, 'C', 1);
                $pdf->setXY(105, 35 + $i * 10);
                $pdf->MultiCell(20, 5, 'Qty Received', 1, 'C', 1);
                $pdf->setXY(125, 35 + $i * 10);
                $pdf->MultiCell(20, 10, 'Cost', 1, 'C', 1);
                $pdf->setXY(145, 35 + $i * 10);
                $pdf->MultiCell(30, 10, 'Received Date', 1, 'C', 1);
                }
            }

        $pdf->setXY(5, 45 + $i * 10);
        $pdf->Cell(100, 10, 'Total: ', 0, 0, 'R');
        $pdf->setXY(85, 45 + $i * 10);
        $pdf->Cell(60, 10, $tot_for_prod, 0, 0, 'C');
        $pdf->setXY(105, 45 + $i * 10);
        $pdf->Cell(60, 10, $currencies->format($tot_for_paid, true, 'USD', '1.000000'), 0, 0, 'C');
        $i++;
        }

    $pdf->setXY(5, 45 + $i * 10);
    $pdf->Cell(30, 10, "Total products: " . $total_products, 0, 0, 'L');
    $pdf->setXY(5, 50 + $i * 10);
    $pdf->Cell(30, 10, "Total quantity: " . $total_qty, 0, 0, 'L');
    $pdf->setXY(5, 55 + $i * 10);
    $pdf->Cell(30, 10, "Total cost: " . $currencies->format($total_cost, true, 'USD', '1.000000'), 0, 0, 'L');
    }

$pdf->Output();
?>