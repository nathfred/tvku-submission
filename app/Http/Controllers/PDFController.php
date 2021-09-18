<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Codedge\Fpdf\Fpdf\Fpdf;

class PDFController extends Controller
{
    private $fpdf;

    public function __construct()
    {
    }

    public function createPDF()
    {
        $this->fpdf = new Fpdf;
        $this->fpdf->AddPage("L", ['100', '100']);
        $this->fpdf->SetFont('Arial', 'B', 12);

        $type = ['float', 'float', 'string', 'mixed', 'int', 'string', 'bool', 'mixed'];
        $cell = ['w', 'h', 'txt', 'border', 'ln', 'align', 'fill', 'link'];

        $this->fpdf->SetFillColor(193, 229, 252);
        $this->pdf->Cell(10, 10, 'No', 1, 0, 'C', false);
        $this->pdf->Cell(40, 10, 'Nama', 1, 0, 'C', false);
        $this->pdf->Cell(30, 10, 'NPP', 1, 0, 'C', false);
        $this->pdf->Cell(30, 10, 'Jabatan', 1, 0, 'C', false);
        $this->pdf->Cell(30, 10, 'Divisi', 1, 0, 'C', false);
        $this->pdf->Cell(20, 10, 'Jenis', 1, 0, 'C', false);
        $this->pdf->Cell(40, 10, 'Keterangan', 1, 0, 'C', false);
        $this->pdf->Cell(20, 10, 'Tanggal Ijin', 1, 0, 'C', false);
        $this->pdf->Cell(20, 10, 'Tanggal Kembali', 1, 0, 'C', false);
        $this->pdf->Cell(10, 10, 'Lama', 1, 0, 'C', false);
        $this->pdf->Cell(10, 10, 'Acc Divisi', 1, 0, 'C', false);
        $this->pdf->Cell(10, 10, 'Acc HRD', 1, 0, 'C', false);
        $this->pdf->Cell(20, 10, 'Lampiran', 1, 0, 'C', false);
        $this->pdf->Cell(10, 10, 'Status', 1, 0, 'C', false);

        $this->fpdf->SetFont('Arial', '', 10);
        // LOOP

        $this->fpdf->Output();
        exit;
    }
}
