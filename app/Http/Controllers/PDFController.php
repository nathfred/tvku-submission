<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Submission;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PDFController extends Controller
{
    private $fpdf;

    public function __construct()
    {
    }

    public function createPDF()
    {
        $this->fpdf = new Fpdf;
        $this->fpdf->AddPage("L", ['5000', '500']);

        $type = ['float', 'float', 'string', 'mixed', 'int', 'string', 'bool', 'mixed'];
        $cell = ['w', 'h', 'txt', 'border', 'ln', 'align', 'fill', 'link'];

        // HEADER
        $this->fpdf->SetFont('Arial', 'B', 12);
        $this->fpdf->SetFillColor(193, 229, 252);
        $this->fpdf->Cell(10, 10, 'No', 1, 0, 'C', false);
        $this->fpdf->Cell(60, 10, 'Nama', 1, 0, 'C', false);
        $this->fpdf->Cell(30, 10, 'NPP', 1, 0, 'C', false);
        $this->fpdf->Cell(30, 10, 'Jabatan', 1, 0, 'C', false);
        $this->fpdf->Cell(30, 10, 'Divisi', 1, 0, 'C', false);
        $this->fpdf->Cell(20, 10, 'Jenis', 1, 0, 'C', false);
        $this->fpdf->Cell(40, 10, 'Keterangan', 1, 0, 'C', false);
        $this->fpdf->Cell(40, 10, 'Tanggal Ijin', 1, 0, 'C', false);
        $this->fpdf->Cell(40, 10, 'Tanggal Kembali', 1, 0, 'C', false);
        $this->fpdf->Cell(20, 10, 'Lama', 1, 0, 'C', false);
        $this->fpdf->Cell(30, 10, 'Acc Divisi', 1, 0, 'C', false);
        $this->fpdf->Cell(30, 10, 'Acc HRD', 1, 0, 'C', false);
        $this->fpdf->Cell(30, 10, 'Status', 1, 0, 'C', false);
        $this->fpdf->Cell(40, 10, 'Lampiran', 1, 1, 'C', false);


        // BODY (LOOP)
        $submissions = $this->getData();
        $this->fpdf->SetFont('Arial', '', 10);
        $i = 1;
        foreach ($submissions as $sub) {
            $this->fpdf->setTextColor(0, 0, 0); // BLACK

            // FILTER APAKAH SUBMISSION MEMLIKI GAMBAR
            // JIKA YA, CELL HEIGHT SUBMISSION TSB AKAN LEBIH
            if (!($sub->attachment === NULL)) {
                $cell_height = 40; // MENYESUALIKAN HEIGHT CELL GAMBAR
            } else {
                $cell_height = 10;
            }

            $this->fpdf->Cell(10, $cell_height, $i, 1, 0, 'C', false);
            $this->fpdf->Cell(60, $cell_height, $sub->employee->user->name, 1, 0, 'L', false);
            $this->fpdf->Cell(30, $cell_height, $sub->employee->npp, 1, 0, 'L', false);
            $this->fpdf->Cell(30, $cell_height, $sub->employee->position, 1, 0, 'L', false);
            $this->fpdf->Cell(30, $cell_height, $sub->employee->division, 1, 0, 'L', false);
            $this->fpdf->Cell(20, $cell_height, $sub->type, 1, 0, 'L', false);
            $this->fpdf->Cell(40, $cell_height, $sub->description, 1, 0, 'L', false);
            $this->fpdf->Cell(40, $cell_height, $sub->start_date, 1, 0, 'C', false);
            $this->fpdf->Cell(40, $cell_height, $sub->end_date, 1, 0, 'C', false);
            $this->fpdf->Cell(20, $cell_height, $sub->duration . ' hari', 1, 0, 'C', false);

            // ACC DIVISI
            if ($sub->division_approval == '1') {
                $this->fpdf->Cell(30, $cell_height, 'Diterima', 1, 0, 'C', false);
            } elseif ($sub->division_approval == '0') {
                $this->fpdf->Cell(30, $cell_height, 'Ditolak', 1, 0, 'C', false);
            } else {
                $this->fpdf->Cell(30, $cell_height, 'Menunggu', 1, 0, 'C', false);
            }

            // ACC HRD
            if ($sub->hrd_approval == '1') {
                $this->fpdf->Cell(30, $cell_height, 'Diterima', 1, 0, 'C', false);
            } elseif ($sub->hrd == '0') {
                $this->fpdf->Cell(30, $cell_height, 'Ditolak', 1, 0, 'C', false);
            } else {
                $this->fpdf->Cell(30, $cell_height, 'Menunggu', 1, 0, 'C', false);
            }

            // STATUS
            if ($sub->division_approval == 1 && $sub->hrd_approval == 1) {
                $this->fpdf->setTextColor(0, 200, 0); // GREEN
                $this->fpdf->Cell(30, $cell_height, 'Diterima', 1, 0, 'C', false);
            } elseif ($sub->division_approval == '0' && $sub->hrd_approval == '0') {
                $this->fpdf->setTextColor(200, 0, 0); // RED
                $this->fpdf->Cell(30, $cell_height, 'Ditolak', 1, 0, 'C', false);
            } else {
                $this->fpdf->setTextColor(200, 200, 0); // YELLOW
                $this->fpdf->Cell(30, $cell_height, 'Menunggu', 1, 0, 'C', false);
            }

            // GAMBAR
            if (!($sub->attachment === NULL)) {
                // $this->fpdf->Cell(40, 40, '', 1, 1, 'C', false);
                $this->fpdf->Image("data_file/cuti/$sub->attachment", NULL, NULL, 40, 40);
                // $this->fpdf->Cell(40, 40, '', 1, 1, 'C', false);
                $this->fpdf->Cell(0, 0, '', 0, 1, 'C', false); // DUMMY CELL UNTUK ENTER SETELAH GAMBAR
            } else {
                $this->fpdf->Cell(40, $cell_height, '', 1, 1, 'C', false);
            }

            $i++;
        }

        // PRINT
        $this->fpdf->Output();
        exit;
    }

    public function getData()
    {
        // DUPLIKASI DARI AdminHRDController->show()
        $today = Carbon::today();
        $today = $today->format('Y-m-d');

        $total_submissions = Submission::where('end_date', '>', $today)->get();

        // HITUNG DURASI START DATE -> END DATE (HARI)
        foreach ($total_submissions as $sub) {
            // UBAH KE FORMAT CARBON
            $start_date = Carbon::createFromFormat('Y-m-d', $sub->start_date);
            $end_date = Carbon::createFromFormat('Y-m-d', $sub->end_date);
            // HITUNG DURASI DALAM FORMAT CARBON
            $duration = $start_date->diffInDays($end_date);
            // TAMBAHKAN ATTRIBUT BARU (DURASI)
            $sub->duration = $duration;
        }

        // UBAH FORMAT DATE (Y-m-d menjadi d-m-Y)
        foreach ($total_submissions as $sub) {
            // UBAH KE FORMAT CARBON
            $sub->start_date = Carbon::createFromFormat('Y-m-d', $sub->start_date);
            $sub->end_date = Carbon::createFromFormat('Y-m-d', $sub->end_date);
            // UBAH FORMAT KE d-m-Y
            $sub->start_date = $sub->start_date->format('d-m-Y');
            $sub->end_date = $sub->end_date->format('d-m-Y');
        }

        return $total_submissions;
    }
}
