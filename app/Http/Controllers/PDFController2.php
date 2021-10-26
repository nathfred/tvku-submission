<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Submission;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PDFController2 extends Controller
{
    private $fpdf;

    public function __construct()
    {
    }

    public function Header($month, $division)
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
            $this->fpdf->Cell(150, 10, 'Pengajuan Cuti : Tahun ' . $today->year . ' (' . $division . ')', 1, 0, 'C');
        } elseif ($month_text == 'Non-month') {
            $this->fpdf->Cell(150, 10, 'Pengajuan Cuti : Total ' . ' (' . $division . ')', 1, 0, 'C');
        } else {
            $this->fpdf->Cell(150, 10, 'Pengajuan Cuti : Bulan ' . $month_text . ' (' . $division . ')', 1, 0, 'C');
        }

        // Line break
        $this->fpdf->Ln(20);
    }

    public function createPDF($month, $division)
    {
        $this->fpdf = new Fpdf;
        $this->fpdf->AddPage("L", ['350', '500']);
        // $this->fpdf->AddPage("L", "A4");
        // dd($month);
        // dd($division);
        $this->Header($month, $division);

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
        $submissions = $this->getAdminTableData($month, $division);
        $current_height = 10;
        $this->fpdf->SetFont('Arial', '', 10);
        $i = 1;
        foreach ($submissions as $sub) {
            $current_height = 10;
            $next_page = FALSE;
            // PERIKSA POSISI Y
            if (!($sub->attachment === NULL)) { // JIKA ADA ATTACHMENT : PERLU SETIDAKNYA 40+10 HEIGHT
                if ($current_height >= $this->fpdf->GetPageHeight() - 50) {
                    $next_page = TRUE;
                    $this->fpdf->AddPage("L", ['500', '500']);
                }
            } else { // JIKA TIDAK, PERLU SEIDAKNYA 10+10 HEIGHT
                if ($current_height >= $this->fpdf->GetPageHeight() - 20) {
                    $next_page = TRUE;
                    $this->fpdf->AddPage("L", ['500', '500']);
                }
            }

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
            $Y = $this->fpdf->getY();
            // $this->fpdf->Cell(20, $cell_height, $Y, 1, 0, 'C', false);

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
                $current_height = $current_height + 40;
            } else {
                $this->fpdf->Cell(40, $cell_height, '-', 1, 1, 'C', false);
                $current_height = $current_height + 10;
            }

            $i++;
        }

        $today = Carbon::today('GMT+7');
        // AMBIL BULAN DALAM TEXT
        $month_text = $this->monthToText($month);
        $file_name = '';
        if ($month_text == 'All-month') {
            $file_name = 'Tahun_' . $today->year;
        } elseif ($month_text == 'Non-month') {
            $file_name = 'Total';
        } else {
            $file_name = 'Bulan_' . $month_text . '_Tahun_' . $today->year;
        }

        // PRINT
        $this->fpdf->Output('D', 'Rekap_' . $file_name . '_' . $division . '.pdf');
        exit;
    }

    public function getAdminTableData($month, $division)
    {
        $today_carbon = Carbon::today('GMT+7');
        $today = $today_carbon->format('Y-m-d');

        switch ($month) {
            case 0: { // DATA SUBMISSION DI TABEL ADMIN DIVISI
                    // $total_submissions = Submission::where('end_date', '>', $today)->orderBy('start_date', 'desc')->get();
                    $total_submissions = Submission::orderBy('created_at', 'desc')->get();
                    break;
                }

            case 99: { // SEMUA DATA SUBMISSION
                    $total_submissions = Submission::orderBy('start_date', 'asc')->get();
                    break;
                }

            case 100: { // SEMUA DI TAHUN INI
                    $total_submissions = Submission::whereYear('created_at', $today_carbon->year)->orderBy('start_date', 'asc')->get();
                    break;
                }

            default: { // DATA SUBMISSION BULAN 1 - 12 TAHUN INI
                    if ($month < 1 || $month > 12) { // JIKA PARAM MONTH DILUAR NALAR
                        $total_submissions = Submission::where('end_date', '>', $today)->get();
                    } else {
                        $total_submissions = Submission::whereYear('start_date', $today_carbon->year)->whereMonth('start_date', $month)->orWhereMonth('end_date', $month)->orderBy('start_date', 'asc')->get();
                        // END DATE TANGGAL 1 PADA BULAN DEPAN TIDAK TERMASUK (HAPUS DARI COLLECTION)
                        $total_submissions = $total_submissions->keyBy('id');
                        foreach ($total_submissions as $sub) {
                            $day_end_date = Carbon::parse($sub->end_date)->format('d');
                            $month_end_date = Carbon::parse($sub->end_date)->format('m');
                            if ($day_end_date == 1 && $month_end_date == $month) {
                                $total_submissions->forget($sub->id);
                            }
                        }
                    }
                    break;
                }
        }

        $total_submissions = $total_submissions->keyBy('id');
        // FILTER SESUAI DIVISI
        if ($division == 'HRD Keuangan') { // HRD KEUANGAN
            foreach ($total_submissions as $sub) {
                if (!($sub->employee->division == 'Human Resources' || $sub->employee->division == 'Keuangan' || $sub->employee->division == 'Umum')) {
                    $total_submissions->forget($sub->id);
                }
            }
        } else {
            foreach ($total_submissions as $sub) {
                if (!($sub->employee->division == $division)) {
                    $total_submissions->forget($sub->id);
                }
            }
        }

        // HITUNG DURASI START DATE -> END DATE (HARI)
        foreach ($total_submissions as $sub) {
            // UBAH KE FORMAT CARBON
            $start_date = Carbon::createFromFormat('Y-m-d', $sub->start_date);
            $end_date = Carbon::createFromFormat('Y-m-d', $sub->end_date);
            // HITUNG DURASI DALAM FORMAT CARBON
            $duration = $start_date->diffInDaysFiltered(function (Carbon $date) {
                return !$date->isWeekend();
            }, $end_date);
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
            case 100:
                $month_text = 'All-month';
                break;
            default:
                $month_text = 'Non-month';
                break;
        }
        return $month_text;
    }

    public function createPDFArchive($division)
    {
        $this->fpdf = new Fpdf;
        $this->fpdf->AddPage("P", "A4");

        $this->HeaderArchive($division);

        // COLUMN
        $this->fpdf->SetFont('Arial', 'B', 12);
        $this->fpdf->SetFillColor(193, 229, 252);
        $this->fpdf->Cell(10, 10, 'No', 1, 0, 'C', false);
        $this->fpdf->Cell(40, 10, 'Nama Lengkap', 1, 0, 'C', false);
        $this->fpdf->Cell(10, 10, 'Jan', 1, 0, 'C', false);
        $this->fpdf->Cell(10, 10, 'Feb', 1, 0, 'C', false);
        $this->fpdf->Cell(10, 10, 'Mar', 1, 0, 'C', false);
        $this->fpdf->Cell(10, 10, 'Apr', 1, 0, 'C', false);
        $this->fpdf->Cell(10, 10, 'Mei', 1, 0, 'C', false);
        $this->fpdf->Cell(10, 10, 'Jun', 1, 0, 'C', false);
        $this->fpdf->Cell(10, 10, 'Jul', 1, 0, 'C', false);
        $this->fpdf->Cell(10, 10, 'Ags', 1, 0, 'C', false);
        $this->fpdf->Cell(10, 10, 'Sep', 1, 0, 'C', false);
        $this->fpdf->Cell(10, 10, 'Okt', 1, 0, 'C', false);
        $this->fpdf->Cell(10, 10, 'Nov', 1, 0, 'C', false);
        $this->fpdf->Cell(10, 10, 'Des', 1, 0, 'C', false);
        $this->fpdf->Cell(20, 10, 'Total', 1, 1, 'C', false);

        // BODY (LOOP)
        $employees = $this->getArchiveData($division);
        $current_height = 60;
        $this->fpdf->SetFont('Arial', '', 12);
        $i = 1;
        foreach ($employees as $employee) {
            // CEK HALAMAN UNTUK POSISI ENTRY PALING ATAS HALAMAN TSB
            if ($i == 1) { // HALAMAN PERTAMA
                $current_height = 60;
            } else { // SELAIN HALAMAN PERTAMA
                $current_height = 30;
            }
            // PERIKSA POSISI Y (NEXT PAGE)
            if ($current_height >= $this->fpdf->GetPageHeight() - 30) {
                $this->fpdf->AddPage("P", "A4");
            }

            $cell_height = 10;
            $cell_width = 40;
            $font_size = 12;

            // NO
            $this->fpdf->Cell(10, $cell_height, $i, 1, 0, 'C', false);

            // EMPLOYEE NAME
            $temp_font_size = $font_size;
            while ($this->fpdf->GetStringWidth($employee->user->name) > $cell_width) {
                $this->fpdf->SetFontSize($temp_font_size);
                $temp_font_size = $temp_font_size - 1;
            }
            $this->fpdf->Cell(40, $cell_height, $employee->user->name, 1, 0, 'C', false);
            $this->fpdf->SetFontSize($font_size);

            // MONTH SUBS (LOOP)
            for ($j = 0; $j < 12; $j++) {
                if ($employee->month_sub[$j] == 0) {
                    $this->fpdf->Cell(10, $cell_height, '-', 1, 0, 'C', false);
                } else {
                    $this->fpdf->Cell(10, $cell_height, $employee->month_sub[$j], 1, 0, 'C', false);
                }
            }

            // TOTAL
            $this->fpdf->Cell(20, $cell_height, $employee->total . ' kali', 1, 1, 'C', false);

            $current_height = $current_height + $cell_height;
            $i++;
        }

        $today = Carbon::today('GMT+7');
        $file_name = 'Jumlah_Total_Pengajuan_Pegawai';
        // PRINT
        $this->fpdf->Output('D', 'Rekap_' . $file_name  . '_' . $division . '.pdf');
        exit;
    }

    public function HeaderArchive($division)
    {
        $year = Carbon::today('GMT+7')->year;

        $this->fpdf->Image("img/tvku_logo_ori.png", NULL, NULL, 30, 17);
        $this->fpdf->Cell(0, 0, '', 0, 1, 'C', false); // DUMMY CELL UNTUK ENTER SETELAH GAMBAR

        // Line break
        $this->fpdf->Ln(5);

        $this->fpdf->SetFont('Arial', 'B', 16);
        $this->fpdf->Cell(30, 10, '', 0, 0, 'C'); // DUMMY SUPAYA JUDUL CENTER
        $this->fpdf->Cell(140, 10, 'REKAP CUTI PEGAWAI ' . '(' . $division . ': ' . $year . ')', 1, 0, 'C');

        // Line break
        $this->fpdf->Ln(20);
    }

    public function getArchiveData($division)
    {
        if ($division == 'HRD Keuangan') {
            $employees = Employee::where('division', 'Human Resources')->orWhere('division', 'Keuangan')->orWhere('division', 'Umum')->get();
        } else {
            $employees = Employee::where('division', $division)->get();
        }
        $approved_submissions = Submission::where('division_approval', 1)->where('hrd_approval', 1)->orderBy('employee_id', 'asc')->get();

        foreach ($employees as $employee) {
            $month_sub = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
            $total = 0;
            for ($i = 0; $i < 12; $i++) {
                foreach ($approved_submissions as $sub) {
                    $sub_month = Carbon::parse($sub->start_date);
                    $sub_month = $sub_month->format('m');
                    if ($sub->employee_id == $employee->id && $sub_month == $i + 1) {
                        $month_sub[$i] = $month_sub[$i] + 1;
                        $total++;
                    }
                }
            }
            $employee->month_sub = $month_sub;
            $employee->total = $total;
        }

        return $employees;
    }
}
