<?php
$colProd1pos       =5;

$colProd2pos       =30;
$colProd3pos       =85;
$colProd4pos       =100;
$colProd5pos       =118;
$colProd6pos       =136;
$colProd7pos       =161;

$sql_query_jim     =
    "select pv.products_id, op.orders_id, op.products_model, op.products_name, op.final_price, op.products_quantity, op.products_item_cost, op.products_sale_type, o.date_purchased, pd.products_name, pd.products_name_prefix, pd.products_name_suffix, op.products_model from orders_products op, products_to_vendors pv,  orders o, products_description pd where o.orders_id = op.orders_id and pd.products_id = pv.products_id and op.products_id = pv.products_id and op.products_sale_type="
    . $type . $vendors_condition_1 . " " . $startat . " order by op.products_model";

$products_query_jim=tep_db_query($sql_query_jim);

$i                 =0;
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->text(5, 33 + $i * 10, 'PRODUCT TOTALS');
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

$colProd1pos=5;
$colProd2pos=45;
$colProd3pos=100;
$colProd4pos=115;
$colProd5pos=133;
$colProd6pos=151;
$colProd7pos=176;

$pdf->setXY($colProd1pos, 35 + $i * 10);
$pdf->MultiCell(40, 10, 'Product Id', 1, 'C', 1);
$pdf->setXY($colProd2pos, 35 + $i * 10);
$pdf->MultiCell(55, 10, 'Products Name', 1, 'L', 1);
$pdf->setXY($colProd3pos, 35 + $i * 10);
$pdf->MultiCell(15, 10, 'Qty', 1, 'C', 1);
$pdf->setXY($colProd4pos, 35 + $i * 10);
$pdf->MultiCell(20, 10, 'Total Accrued', 1, 'C', 1);
$pdf->setXY($colProd5pos, 35 + $i * 10);
$pdf->MultiCell(20, 10, 'Total Sales', 1, 'C', 1);
$pdf->setXY($colProd6pos, 35 + $i * 10);
$pdf->MultiCell(25, 10, 'Sale Period', 1, 'C', 1);
$pdf->setXY($colProd7pos, 35 + $i * 10);
$pdf->MultiCell(18, 10, 'Type', 1, 'C', 1);

$total_sale         =0;
$total_sold         =0;
$total_qty_sold     =0;
$tot_vc             =0;
$totSellingProdModel=0;
$final_cost         =0;
$curProdGroup       ="";
$curProdModel       ="";
$curAccruedTot      =0;
$rowProdGroup       ="";
// reset our group totals
$curQtyPG           =0;
$curTotPG           =0;
$curSellingTotPG    =0;
$bDetailMode        =false;

while ($products=tep_db_fetch_array($products_query_jim))
    {
    if ($pdf->PageNo() == 1)
        $top_table=45;
    else
        $top_table=45;

    if ($bDetailMode)
        { // detail mode is good for troubleshooting.
        $total_qty_sold+=$products['products_quantity'];

        $pdf->setXY($colProd1pos, $top_table + $i * 10);
        $pdf->Cell(40, 10, $products['products_model'], 1, 1, 'L');
        $pdf->setXY($colProd2pos, $top_table + $i * 10);

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

        $pdf->setXY($colProd3pos, $top_table + $i * 10);
        $pdf->Cell(15, 10, $products['products_quantity'], 1, 1, 'C');
        $pdf->setXY($colProd4pos, $top_table + $i * 10);

        $ppCnt=tep_db_num_rows(tep_db_query("select * from orders_total where orders_id=" . $products[orders_id]
                                                . " and (class='ot_coupon' or class='ot_qty_discount')"));

        if ($ppCnt > 0)
            $asteriks='*';
        else
            $asteriks='';

        $pdf->Cell(18, 10, $currencies->format($products[products_item_cost], true, 'USD', '1.000000') . $asteriks, 1,
                   1,  'C');
        $pdf->setXY($colProd5pos, $top_table + $i * 10);
        $tot_vc    =$products['products_item_cost'] * $products['products_quantity'];
        $total_sale=$total_sale + $tot_vc;
        $final_cost=$currencies->format($tot_vc, true, 'USD', '1.000000');
        $pdf->Cell(18, 10, $final_cost, 1, 1, 'C');
        $pdf->setXY($colProd6pos, $top_table + $i * 10);
        $total_sold=$total_sold + ($products[final_price] * $products[products_quantity]);
        $pdf->Cell(25, 10, $currencies->format($products[final_price], true, 'USD', '1.000000'), 1, 1, 'C');
        $pdf->setXY($colProd7pos, $top_table + $i * 10);

        if ($products['products_sale_type'] == 1)
            $typ="Direct Purchase";

        elseif ($products['products_sale_type'] == 2)
            $typ="Consignment";

        else
            $typ="Royalty";

        $pdf->Cell(18, 10, $typ, 1, 1, 'C');

        $i++;
        }
    else
        {
        // summary mode, not detail mode
        $rowProdModel=$products['products_model'];

        $ipos        =stripos($rowProdModel, "-"); // first dash

        if ($ipos !== false)
            $rowProdGroup=trim(substr($rowProdModel, 0, $ipos));

        if ($curProdModel == "") //initialize
            {
            $curProdModel=$rowProdModel;
            $curProdGroup=$rowProdGroup;
            }

        if (($curProdModel != $rowProdModel) && ($curProdModel > ""))
            {
            // print our totals
            $pdf->setXY($colProd1pos, $top_table + $i * 10);
            $pdf->Cell(40, 10, $curProdModel, 1, 1, 'C');

            if (strlen($prodname) > 27)
                $value=5;
            else
                $value=10;

            if (strlen($prodname) > 55)
                {
                $value=3.34;
                $flag =1;
                $pdf->SetFont('Arial', '', 8);
                }

            $pdf->setXY($colProd2pos, $top_table + $i * 10);
            $pdf->MultiCell(55, $value, $prodname, 1, 'L');

            if ($flag == 1)
                $pdf->SetFont('Arial', '', 9);

            $pdf->setXY($colProd3pos, $top_table + $i * 10);
            $pdf->Cell(15, 10, $curQty, 1, 1, 'C');

            $ppCnt=tep_db_num_rows(tep_db_query("select * from orders_total where orders_id=" . $products[orders_id]
                                                    . " and (class='ot_coupon' or class='ot_qty_discount')"));

            if ($ppCnt > 0)
                $asteriks='*';
            else
                $asteriks='';

            $pdf->setXY($colProd4pos, $top_table + $i * 10);
            $pdf->Cell(18, 10, $currencies->format($curTot, true, 'USD', '1.000000') . $asteriks, 1, 1, 'R');

            $pdf->setXY($colProd5pos, $top_table + $i * 10);
            $final_cost=$currencies->format($totSellingProdModel, true, 'USD', '1.000000');
            $pdf->Cell(18, 10, $final_cost, 1, 1, 'R');

            $pdf->setXY($colProd6pos, $top_table + $i * 10);
            $pdf->MultiCell(25, 5, "$date_range", 1, 1, 'R');

            $pdf->setXY($colProd7pos, $top_table + $i * 10);
            $pdf->Cell(18, 10, $typ, 1, 1, 'C');

            $i++;

            // reset our product model totals
            $curProdModel       =$rowProdModel;
            $curQty             =0;
            $final_cost         =0;
            $curTot             =0;
            $curSellingTot      =0;
            $curAccruedTot      =0;
            $totSellingProdModel=0;
            }

        if (($curProdGroup != $rowProdGroup) && ($curProdGroup > ""))
            {
            // print our group totals

            $pdf->setXY($colProd1pos, $top_table + $i * 10);
            $pdf->Cell(95, 10, "Total $curProdGroup", 1, 1, 'C');

            $pdf->setXY($colProd3pos, $top_table + $i * 10);
            $pdf->Cell(15, 10, $curQtyPG, 1, 1, 'C');
            $pdf->setXY($colProd4pos, $top_table + $i * 10);
            $pdf->Cell(18, 10, $currencies->format($curTotPG, true, 'USD', '1.000000') . $asteriks, 1, 1, 'R');

            $pdf->setXY($colProd5pos, $top_table + $i * 10);
            $final_cost=$currencies->format($curSellingTotPG, true, 'USD', '1.000000');
            $pdf->Cell(18, 10, $final_cost, 1, 1, 'R');

            $i++;
            $i=0;
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->text(5, 33 + $i * 10, 'PRODUCT TOTALS');
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

            $pdf->setXY($colProd1pos, 35 + $i * 10);
            $pdf->MultiCell(40, 10, 'Product Id', 1, 'L', 1);
            $pdf->setXY($colProd2pos, 35 + $i * 10);
            $pdf->MultiCell(55, 10, 'Products Name', 1, 'L', 1);
            $pdf->setXY($colProd3pos, 35 + $i * 10);
            $pdf->MultiCell(15, 10, 'Qty', 1, 'C', 1);
            $pdf->setXY($colProd4pos, 35 + $i * 10);
            $pdf->MultiCell(20, 10, 'Total Acrrued', 1, 'C', 1);
            $pdf->setXY($colProd5pos, 35 + $i * 10);
            $pdf->MultiCell(20, 10, 'Total Sales', 1, 'C', 1);
            $pdf->setXY($colProd6pos, 35 + $i * 10);
            $pdf->MultiCell(25, 10, 'Sale Period', 1, 'C', 1);
            $pdf->setXY($colProd7pos, 35 + $i * 10);
            $pdf->MultiCell(18, 10, 'Type', 1, 'C', 1);
            // reset our group totals
            $curProdGroup   =$rowProdGroup;
            $curQtyPG       =0;
            $curTotPG       =0;
            $curSellingTotPG=0;
            }

        // tally
        $curQty+=$products['products_quantity'];
        $curTot+=$products['products_item_cost'];

        $totSellingProdModel+=($products['final_price'] * $products['products_quantity']);

        $prodname=trim($products['products_name_prefix'] . " " . $products['products_name'] . " "
                           . $products['products_name_suffix']);

        if ($products['products_sale_type'] == 1)
            $typ="Direct Purchase";

        elseif ($products['products_sale_type'] == 2)
            $typ="Consignment";

        else
            $typ="Royalty";

        $curAccruedTot+=$products['products_item_cost'] * $products['products_quantity'];
        $curAccruedF=$currencies->format($curAccruedTot, true, 'USD', '1.000000');

        $curQtyPG+=$products['products_quantity'];
        $curTotPG+=$products['products_item_cost'];
        $curSellingTotPG+=($products['final_price'] * $products['products_quantity']);
        } // endo of summary mode

    if ($i == 23)
        {
        $i=0;
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->text(5, 33 + $i * 10, 'PRODUCT TOTALS');
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

        $pdf->setXY($colProd1pos, 35 + $i * 10);
        $pdf->MultiCell(40, 10, 'Product Id', 1, 'L', 1);
        $pdf->setXY($colProd2pos, 35 + $i * 10);
        $pdf->MultiCell(55, 10, 'Products Name', 1, 'L', 1);
        $pdf->setXY($colProd3pos, 35 + $i * 10);
        $pdf->MultiCell(15, 10, 'Qty', 1, 'C', 1);
        $pdf->setXY($colProd4pos, 35 + $i * 10);
        $pdf->MultiCell(20, 10, 'Total Accrued', 1, 'C', 1);
        $pdf->setXY($colProd5pos, 35 + $i * 10);
        $pdf->MultiCell(20, 10, 'Total Sales', 1, 'C', 1);
        $pdf->setXY($colProd6pos, 35 + $i * 10);
        $pdf->MultiCell(25, 10, 'Sale Period', 1, 'C', 1);
        $pdf->setXY($colProd7pos, 35 + $i * 10);
        $pdf->MultiCell(18, 10, 'Type', 1, 'C', 1);
        }
    }

if (!$bDetailMode)
    { // residue print (Prod Model)
    if ($i > 21)
        {
        $i=0;
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->text(5, 33 + $i * 10, 'PRODUCT TOTALS');
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

        $pdf->setXY($colProd1pos, 35 + $i * 10);
        $pdf->MultiCell(40, 10, 'Product Id', 1, 'L', 1);
        $pdf->setXY($colProd2pos, 35 + $i * 10);
        $pdf->MultiCell(55, 10, 'Products Name', 1, 'L', 1);
        $pdf->setXY($colProd3pos, 35 + $i * 10);
        $pdf->MultiCell(15, 10, 'Qty', 1, 'C', 1);
        $pdf->setXY($colProd4pos, 35 + $i * 10);
        $pdf->MultiCell(20, 10, 'Total Accrued', 1, 'C', 1);
        $pdf->setXY($colProd5pos, 35 + $i * 10);
        $pdf->MultiCell(20, 10, 'Total Sales', 1, 'C', 1);
        $pdf->setXY($colProd6pos, 35 + $i * 10);
        $pdf->MultiCell(25, 10, 'Sale Period', 1, 'C', 1);
        $pdf->setXY($colProd7pos, 35 + $i * 10);
        $pdf->MultiCell(18, 10, 'Type', 1, 'C', 1);
        }

    // print our totals
    $pdf->setXY($colProd1pos, $top_table + $i * 10);
    $pdf->Cell(40, 10, $rowProdModel, 1, 1, 'C');

    if (strlen($prodname) > 27)
        $value=5;
    else
        $value=10;

    if (strlen($prodname) > 55)
        {
        $value=3.34;
        $flag =1;
        $pdf->SetFont('Arial', '', 8);
        }

    $pdf->setXY($colProd2pos, $top_table + $i * 10);
    $pdf->MultiCell(55, $value, $prodname, 1, 'L');

    if ($flag == 1)
        $pdf->SetFont('Arial', '', 9);

    $pdf->setXY($colProd3pos, $top_table + $i * 10);
    $pdf->Cell(15, 10, $curQty, 1, 1, 'C');

    $ppCnt=0;

    if ($ppCnt > 0)
        $asteriks='*';
    else
        $asteriks='';

    $pdf->setXY($colProd4pos, $top_table + $i * 10);
    $pdf->Cell(18, 10, $currencies->format($curTot, true, 'USD', '1.000000') . $asteriks, 1, 1, 'R');

    $pdf->setXY($colProd5pos, $top_table + $i * 10);
    $final_cost=$currencies->format($totSellingProdModel, true, 'USD', '1.000000');
    $pdf->Cell(18, 10, $final_cost, 1, 1, 'R');

    $pdf->setXY($colProd6pos, $top_table + $i * 10);
    $pdf->MultiCell(25, 5, "$date_range", 1, 1, 'R');

    $pdf->setXY($colProd7pos, $top_table + $i * 10);
    $pdf->Cell(18, 10, $typ, 1, 1, 'C');

    $i++;

    // print our group totals

    $pdf->setXY($colProd1pos, $top_table + $i * 10);
    $pdf->Cell(95, 10, "Total $curProdGroup", 1, 1, 'C');

    $pdf->setXY($colProd3pos, $top_table + $i * 10);
    $pdf->Cell(15, 10, $curQtyPG, 1, 1, 'C');
    $pdf->setXY($colProd4pos, $top_table + $i * 10);
    $pdf->Cell(18, 10, $currencies->format($curTotPG, true, 'USD', '1.000000') . $asteriks, 1, 1, 'R');

    $pdf->setXY($colProd5pos, $top_table + $i * 10);
    $final_cost=$currencies->format($curSellingTotPG, true, 'USD', '1.000000');
    $pdf->Cell(18, 10, $final_cost, 1, 1, 'R');

    $i++;
    }
?>