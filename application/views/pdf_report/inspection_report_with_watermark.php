<?php
class MYPDF extends TCPDF
{
    //Page header
    public function Header()
    {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = "assets/images/logo_opa.png";
        $y = 0;
        $size = 30;
        for ($j = 0; $j < 10; $j++) {
            $x = 0;
            for ($i = 0; $i < 7; $i++) {
                $this->Image($img_file, $x, $y, $size, $size, '', '', '', false, 300,'', false, false, 0, 'L', false, false);
                $x += $size;
            }
            $y += $size;
        }
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
// $pdf->setPrintHeader(false);
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
$pdf->writeHTMLCell('', '', '', '', '<span style="font-size:16px">เรื่อง ' . $inspectionName . '</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', 15, '', '<span style="font-size:16px">หน่วยรับตรวจ ' . 'xxx' . '</span>', $border, 0, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', '', '', '<span style="font-size:16px">ผบ.หน่วย ' . 'xxx' . '</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', 15, '', '<span style="font-size:16px">ตรวจเมื่อวันที่ ' . 'xxx' . '</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', 15, '', '<span style="font-size:16px">ผู้รับตรวจ ' . 'xxx' . '</span>', $border, 0, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', '', '', '<span style="font-size:16px">ตำแหน่ง ' . 'xxx' . '</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', 15, '', '<span style="font-size:16px">ผู้ตรวจ ' . 'xxx' . '</span>', $border, 1, 0, 1, 'L');
$pdf->ln(10);
$pdf->writeHTMLCell(90, '', 15, '', '<span style="font-size:16px">ข้อควรแก้ไขและข้อบกพร่องที่ตรวจพบ</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell(90, '', 20, '', '<span style="font-size:16px">1. ข้อควรแก้ไข</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell('', '', 20, '', '<span style="font-size:16px">' . $textEx . '</span>', $border, 1, 0, 1, 'L');
$pdf->ln(5);
$pdf->writeHTMLCell(90, '', 20, '', '<span style="font-size:16px">2. ข้อบกพร่อง</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell('', '', 20, '', '<span style="font-size:16px">' . $textEx . '</span>', $border, 1, 0, 1, 'L');
$pdf->ln(5);
$pdf->writeHTMLCell(90, '', 20, '', '<span style="font-size:16px">3. ข้อบกพร่องสำคัญมาก</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell('', '', 20, '', '<span style="font-size:16px">' . $textEx . '</span>', $border, 1, 0, 1, 'L');
$pdf->ln(5);
$pdf->writeHTMLCell(90, '', 20, '', '<span style="font-size:16px">4. ข้อสังเกตจากการตรวจและข้อแนะนำ</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell('', '', 20, '', '<span style="font-size:16px">' . $textEx . '</span>', $border, 1, 0, 1, 'L');
$pdf->ln(10);
$pdf->writeHTMLCell(60, '', 15, '', '<span style="font-size:16px">ผลคะแนนตามสายงาน</span>', $border, 0, 0, 1, 'L');
$pdf->writeHTMLCell('', '', '', '', '<span style="font-size:16px">' . 'xxx.xx' . '</span>', $border, 1, 0, 1, 'L');
$pdf->writeHTMLCell(60, '', 15, '', '<span style="font-size:16px">คะแนนประสิทธิภาพในการปฏิบัติงาน</span>', $border, 0, 0, 1, 'L');
$pdf->writeHTMLCell('', '', '', '', '<span style="font-size:16px">'  . 'xxx.xx' . '</span>', $border, 1, 0, 1, 'L');

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Report.pdf', 'I');
