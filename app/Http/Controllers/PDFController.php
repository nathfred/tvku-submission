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

    public function Header($month)
    {
        $today = Carbon::today('GMT+7');

        // AMBIL BULAN DALAM TEXT
        $month_text = $this->monthToText($month);

        // HEADER
        // Select Arial bold 16
        $this->fpdf->SetFont('Arial', 'B', 16);
        // Move to the right
        // $this->fpdf->Cell(80);
        // Framed title
        if ($month_text == 'All-month') {
            $this->fpdf->Cell(100, 10, 'Pengajuan Cuti : Tahun ' . $today->year, 1, 0, 'C');
        } elseif ($month_text == 'Non-month') {
            $this->fpdf->Cell(100, 10, 'Pengajuan Cuti : Akan Datang ', 1, 0, 'C');
        } else {
            $this->fpdf->Cell(100, 10, 'Pengajuan Cuti : Bulan ' . $month_text, 1, 0, 'C');
        }

        // Line break
        $this->fpdf->Ln(20);
    }

    public function createPDF($month)
    {
        $this->fpdf = new Fpdf;
        $this->fpdf->AddPage("L", ['500', '500']);
        $this->Header($month);

        $type = ['float', 'float', 'string', 'mixed', 'int', 'string', 'bool', 'mixed'];
        $cell = ['w', 'h', 'txt', 'border', 'ln', 'align', 'fill', 'link'];

        // COLUMN
        $this->fpdf->SetFont('Arial', 'B', 12);
        $this->fpdf->SetFillColor(193, 229, 252);
        $this->fpdf->Cell(10, 10, 'No', 1, 0, 'C', false);
        $this->fpdf->Cell(60, 10, 'Nama', 1, 0, 'C', false);
        $this->fpdf->Cell(30, 10, 'NPP', 1, 0, 'C', false);
        $this->fpdf->Cell(30, 10, 'Jabatan', 1, 0, 'C', false);
        $this->fpdf->Cell(30, 10, 'Divisi', 1, 0, 'C', false);
        $this->fpdf->Cell(20, 10, 'Jenis', 1, 0, 'C', false);
        $this->fpdf->Cell(50, 10, 'Keterangan', 1, 0, 'C', false);
        $this->fpdf->Cell(40, 10, 'Tanggal Ijin', 1, 0, 'C', false);
        $this->fpdf->Cell(40, 10, 'Tanggal Kembali', 1, 0, 'C', false);
        $this->fpdf->Cell(20, 10, 'Lama', 1, 0, 'C', false);
        $this->fpdf->Cell(40, 10, 'Acc Divisi', 1, 0, 'C', false);
        $this->fpdf->Cell(40, 10, 'Acc HRD', 1, 0, 'C', false);
        $this->fpdf->Cell(30, 10, 'Status', 1, 0, 'C', false);
        $this->fpdf->Cell(40, 10, 'Lampiran', 1, 1, 'C', false);

        // BODY (LOOP)
        $submissions = $this->getAdminTableData($month);
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
            $this->fpdf->Cell(50, $cell_height, $sub->description, 1, 0, 'L', false);
            $this->fpdf->Cell(40, $cell_height, $sub->start_date, 1, 0, 'C', false);
            $this->fpdf->Cell(40, $cell_height, $sub->end_date, 1, 0, 'C', false);
            $this->fpdf->Cell(20, $cell_height, $sub->duration . ' hari', 1, 0, 'C', false);

            // ACC DIVISI
            if ($sub->division_approval == '1') {
                $this->fpdf->Cell(40, $cell_height, 'Diterima (' . $sub->division_signed_date . ')', 1, 0, 'C', false);
            } elseif ($sub->division_approval == '0') {
                $this->fpdf->Cell(40, $cell_height, 'Ditolak (' . $sub->division_signed_date . ')', 1, 0, 'C', false);
            } else {
                $this->fpdf->Cell(40, $cell_height, 'Menunggu', 1, 0, 'C', false);
            }

            // ACC HRD
            if ($sub->hrd_approval == '1') {
                $this->fpdf->Cell(40, $cell_height, 'Diterima (' . $sub->hrd_signed_date . ')', 1, 0, 'C', false);
            } elseif ($sub->hrd == '0') {
                $this->fpdf->Cell(40, $cell_height, 'Ditolak (' . $sub->hrd_signed_date . ')', 1, 0, 'C', false);
            } else {
                $this->fpdf->Cell(40, $cell_height, 'Menunggu', 1, 0, 'C', false);
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

    public function getAdminTableData($month)
    {
        $today_carbon = Carbon::today();
        $today = $today_carbon->format('Y-m-d');

        switch ($month) {
            case 0: { // DATA SUBMISSION DI TABEL ADMIN
                    $total_submissions = Submission::where('end_date', '>', $today)->get();
                    break;
                }

            case 99: { // SEMUA DATA SUBMISSION
                    $total_submissions = Submission::orderBy('start_date', 'asc')->get();
                    break;
                }

            default: { // DATA SUBMISSION BULAN 1 - 12 TAHUN INI
                    if ($month < 1 || $month > 12) { // JIKA PARAM MONTH DILUAR NALAR
                        $total_submissions = Submission::where('end_date', '>', $today)->get();
                    } else {
                        $total_submissions = Submission::whereYear('start_date', $today_carbon->year)->whereMonth('start_date', $month)->orderBy('start_date', 'asc')->get();
                    }
                    break;
                }
        }


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

            if (!($sub->division_signed_date === NULL)) {
                $sub->division_signed_date = Carbon::createFromFormat('Y-m-d', $sub->division_signed_date);
                $sub->division_signed_date = $sub->division_signed_date->format('d-m-Y');
            }
            if (!($sub->hrd_signed_date === NULL)) {
                $sub->hrd_signed_date = Carbon::createFromFormat('Y-m-d', $sub->hrd_signed_date);
                $sub->hrd_signed_date = $sub->hrd_signed_date->format('d-m-Y');
            }

            // UBAH FORMAT KE d-m-Y
            $sub->start_date = $sub->start_date->format('d-m-Y');
            $sub->end_date = $sub->end_date->format('d-m-Y');
        }

        return $total_submissions;
    }

    public function monthToText($month)
    {
        $month_text = '';
        switch ($month) {
            case 1:
                $month_text = 'Januari';
                break;
            case 2:
                $month_text = 'Februari';
                break;
            case 3:
                $month_text = 'Maret';
                break;
            case 4:
                $month_text = 'April';
                break;
            case 5:
                $month_text = 'Mei';
                break;
            case 6:
                $month_text = 'Juni';
                break;
            case 7:
                $month_text = 'July';
                break;
            case 8:
                $month_text = 'Agustus';
                break;
            case 9:
                $month_text = 'September';
                break;
            case 10:
                $month_text = 'Oktober';
                break;
            case 11:
                $month_text = 'November';
                break;
            case 12:
                $month_text = 'Desember';
                break;
            case 99:
                $month_text = 'All-month';
                break;
            default:
                $month_text = 'Non-month';
                break;
        }
        return $month_text;
    }
}
