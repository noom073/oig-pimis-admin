<?php
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RTARF');
$pdf->SetTitle('Report');
$pdf->SetSubject('OIG_PIMIS');
$pdf->SetKeywords('RTARF, RTARF OIG, OIG PIMIS');

// set default font subsetting mode
$fontname = TCPDF_FONTS::addTTFfont(FCPATH . 'assets/fonts/THSarabun.ttf', 'TrueTypeUnicode', '', 96);
$pdf->SetFont($fontname, '', 16);

// set default header data
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set margins
$pdf->SetMargins(15, 25, 15);
$pdf->SetHeaderMargin(0);
// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ---------------------------------------------------------

// Add a page
$pdf->AddPage();

$inspectionName = 'การตรวจสายงานฯ';
$textEx = 'Lorem ipsum, dolor sit amet consectetur adipisicing elit.' .
    'Ea veritatis mollitia impedit ipsam, delectus consectetur animi architecto eaque sunt non aut,' .
    'ab ad consequuntur quibusdam hic vel libero, sit aspernatur.';
$border = false;
$pdf->writeHTMLCell('', '', '', '', '<span style="font-size:20px">บันทึกการตรวจราชการ</span>', 0, 1, 0, 1, 'C');
$pdf->ln(10);
$pdf->writeHTMLCell('', '', '', '', '<span style="font-size:16px">เรื่อง ' . $note['INSPE_NAME'] . '</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', 15, '', '<span style="font-size:16px">หน่วยรับตรวจ ' . $note['NPRT_NAME'] . '</span>', $border, 0, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', '', '', '<span style="font-size:16px">ผบ.หน่วย ' . $note['UNIT_COMMANDER'] . '</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', 15, '', '<span style="font-size:16px">ตรวจเมื่อวันที่ ' . $note['DATE_INSPECT'] . '</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', 15, '', '<span style="font-size:16px">ผู้รับตรวจ ' . $note['AUDITEE_NAME'] . '</span>', $border, 0, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', '', '', '<span style="font-size:16px">ตำแหน่ง ' . $note['AUDITEE_POS'] . '</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', 15, '', '<span style="font-size:16px">ผู้ตรวจ ' . $note['INSPECTOR'] . '</span>', $border, 1, 0, 1, 'L');
$pdf->ln(5);
$pdf->writeHTMLCell(90, '', 15, '', '<span style="font-size:16px">ข้อควรแก้ไขและข้อบกพร่องที่ตรวจพบ</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', 20, '', '<span style="font-size:16px">1. ข้อควรแก้ไข</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell('', '', 20, '', '<span style="font-size:16px">' . $note['CAN_IMPROVE']->load() . '</span>', $border, 1, 0, 1, 'L');
$pdf->ln(5);
$pdf->writeHTMLCell(90, '', 20, '', '<span style="font-size:16px">2. ข้อบกพร่อง</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell('', '', 20, '', '<span style="font-size:16px">' . $note['FAILING']->load() . '</span>', $border, 1, 0, 1, 'L');
$pdf->ln(5);
$pdf->writeHTMLCell(90, '', 20, '', '<span style="font-size:16px">3. ข้อบกพร่องสำคัญมาก</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell('', '', 20, '', '<span style="font-size:16px">' . $note['IMPORTANT_FAILING']->load() . '</span>', $border, 1, 0, 1, 'L');
$pdf->ln(5);
$pdf->writeHTMLCell(90, '', 20, '', '<span style="font-size:16px">4. ข้อสังเกตจากการตรวจและข้อแนะนำ</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell('', '', 20, '', '<span style="font-size:16px">' . $note['COMMENTIONS']->load() . '</span>', $border, 1, 0, 1, 'L');
$pdf->ln(5);
$pdf->writeHTMLCell(60, '', 15, '', '<span style="font-size:16px">ผลคะแนนตามสายงาน</span>', $border, 0, 0, 1, 'L');
$pdf->writeHTMLCell('', '', '', '', '<span style="font-size:16px">' . +$note['INSPECTION_SCORE'] . '</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell(60, '', 15, '', '<span style="font-size:16px">คะแนนประสิทธิภาพในการปฏิบัติงาน</span>', $border, 0, 0, 1, 'L');
$pdf->writeHTMLCell('', '', '', '', '<span style="font-size:16px">'  . +$note['WORKING_SCORE'] . '</span>', $border, 1, 0, 1, 'L');

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Report.pdf', 'I');
