<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
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
        $this->fpdf->AddPage("L", ['350', '500']);
        // $this->fpdf->AddPage("L", "A4");
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

        // PRINT
        $this->fpdf->Output();
        exit;
    }

    public function getAdminTableData($month)
    {
        $today_carbon = Carbon::today('GMT+7');
        $today = $today_carbon->format('Y-m-d');

        switch ($month) {
            case 0: { // DATA SUBMISSION DI TABEL ADMIN
                    $total_submissions = Submission::where('end_date', '>', $today)->orderBy('start_date', 'desc')->get();
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
        $today_carbon = Carbon::today('GMT+7');
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
                $month_text = 'Juli';
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

    public function createPDFEmployee(Request $request)
    {
        $employee = Employee::where('npp', $request->npp)->first();
        if ($employee === NULL || (!$employee)) {
            return redirect()->route('adminhrd-submission')->with('message', 'npp-not-found');
        }
        // $user = User::where('id', $employee->user_id)->first();

        $this->fpdf = new Fpdf;
        $this->fpdf->AddPage("L", ['350', '500']);

        $this->HeaderEmployee($employee);

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
        $submissions = $this->getEmployeeData($employee);
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

        // PRINT
        $this->fpdf->Output();
        exit;
    }

    public function HeaderEmployee($employee)
    {
        $today = Carbon::today('GMT+7');

        $this->fpdf->SetFont('Arial', 'B', 16);
        $this->fpdf->Cell(150, 10, 'Pengajuan Cuti : ' . $employee->user->name . ' (' . $today->year . ')', 1, 0, 'C');

        // Line break
        $this->fpdf->Ln(20);
    }

    public function getEmployeeData($employee)
    {
        $submissions = Submission::where('employee_id', $employee->id)->orderBy('created_at', 'asc')->get();

        // HITUNG DURASI START DATE -> END DATE (HARI)
        foreach ($submissions as $sub) {
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
        foreach ($submissions as $sub) {
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
        return $submissions;
    }

    public function createPDFSubmission($id)
    {
        $submission = Submission::where('id', $id)->first();
        if ($submission === NULL) {
            return redirect()->route('employee-submission')->with('message', 'incorrect-sub-id');
        }

        // HITUNG DURASI START DATE -> END DATE (HARI)
        // UBAH KE FORMAT CARBON
        $start_date = Carbon::createFromFormat('Y-m-d', $submission->start_date);
        $end_date = Carbon::createFromFormat('Y-m-d', $submission->end_date);
        // HITUNG DURASI DALAM FORMAT CARBON
        $duration = $start_date->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekend();
        }, $end_date);
        // TAMBAHKAN ATTRIBUT BARU (DURASI)
        $submission->duration = $duration;

        // UBAH FORMAT DATE (Y-m-d menjadi d-m-Y)
        // UBAH KE FORMAT CARBON
        $submission->start_date = Carbon::createFromFormat('Y-m-d', $submission->start_date);
        $submission->end_date = Carbon::createFromFormat('Y-m-d', $submission->end_date);
        // UBAH FORMAT KE d-m-Y
        $submission->start_date = $submission->start_date->format('d-m-Y');
        $submission->end_date = $submission->end_date->format('d-m-Y');
        if (!($submission->division_signed_date === NULL)) {
            $submission->division_signed_date = Carbon::createFromFormat('Y-m-d', $submission->division_signed_date);
            $submission->division_signed_date = $submission->division_signed_date->format('d-m-Y');
        }
        if (!($submission->hrd_signed_date === NULL)) {
            $submission->hrd_signed_date = Carbon::createFromFormat('Y-m-d', $submission->hrd_signed_date);
            $submission->hrd_signed_date = $submission->hrd_signed_date->format('d-m-Y');
        }
        // CONVERT TIMESTAMP TO STRING FORMAT (created_at ALWAYS IN TIMESTAMP FORMAT, USE OTHER VARIABLE -> created_date)
        $created_at = Carbon::parse($submission->created_at);
        $submission->created_date = $created_at->format('d-m-Y');

        // UBAH APPROVAL HRD & DIVISI (BOOLEAN -> STRING)
        if ($submission->division_approval === NULL) {
            $submission->division_approval = 'Belum Direspon';
        } elseif ($submission->division_approval == '0') {
            $submission->division_approval = 'Ditolak';
        } elseif ($submission->division_approval == '1') {
            $submission->division_approval = 'Diterima';
        }
        if ($submission->hrd_approval === NULL) {
            $submission->hrd_approval = 'Belum Direspon';
        } elseif ($submission->hrd_approval == '0') {
            $submission->hrd_approval = 'Ditolak';
        } elseif ($submission->hrd_approval == '1') {
            $submission->hrd_approval = 'Diterima';
        }

        $this->fpdf = new Fpdf;
        $this->fpdf->AddPage("P", "A4");

        $this->HeaderSubmission();

        // COLUMN
        $this->fpdf->SetFont('Arial', 'B', 14);
        $this->fpdf->SetFillColor(193, 229, 252);
        $this->fpdf->Cell(50, 10, 'Nama', 0, 0, 'L', false);
        $this->fpdf->Cell(80, 10, ': ' . $submission->employee->user->name, 0, 1, 'L', false);
        $this->fpdf->Cell(50, 10, 'NPP', 0, 0, 'L', false);
        $this->fpdf->Cell(80, 10, ': ' . $submission->employee->npp, 0, 1, 'L', false);
        $this->fpdf->Cell(50, 10, 'Jabatan', 0, 0, 'L', false);
        $this->fpdf->Cell(80, 10, ': ' . $submission->employee->position, 0, 1, 'L', false);
        $this->fpdf->Cell(50, 10, 'Divisi', 0, 0, 'L', false);
        $this->fpdf->Cell(80, 10, ': ' . $submission->employee->division, 0, 1, 'L', false);
        $this->fpdf->Cell(50, 10, 'Jenis Ijin', 0, 0, 'L', false);
        $this->fpdf->Cell(80, 10, ': ' . $submission->type, 0, 1, 'L', false);
        $this->fpdf->Cell(50, 10, 'Tanggal Ijin', 0, 0, 'L', false);
        $this->fpdf->Cell(80, 10, ': ' . $submission->start_date, 0, 1, 'L', false);
        $this->fpdf->Cell(50, 10, 'Tanggal Kembali', 0, 0, 'L', false);
        $this->fpdf->Cell(80, 10, ': ' . $submission->end_date, 0, 1, 'L', false);
        $this->fpdf->Cell(50, 10, 'Lama Ijin', 0, 0, 'L', false);
        $this->fpdf->Cell(80, 10, ': ' . $submission->duration . ' Hari', 0, 1, 'L', false);
        $this->fpdf->Cell(50, 10, 'Lampiran', 0, 0, 'L', false);
        if ($submission->attachment === NULL) {
            $this->fpdf->Cell(80, 10, ': Tanpa Lampiran', 0, 1, 'L', false);
        } else {
            $this->fpdf->Cell(80, 10, ': Ada Lampiran', 0, 1, 'L', false);
        }
        $this->fpdf->Cell(50, 10, 'Keterangan', 0, 0, 'L', false);
        $this->fpdf->Cell(80, 10, ': ' . $submission->description, 0, 1, 'L', false);

        // Line break
        $this->fpdf->Ln(30);
        $this->fpdf->Cell(20, 10, '', 0, 0, 'C'); // DUMMY SUPAYA KE KANAN
        $this->fpdf->Cell(30, 10, 'Diajukan Oleh', 0, 0, 'L', false);
        $this->fpdf->Cell(40, 10, '', 0, 0, 'C'); // DUMMY SUPAYA KE KANAN
        $this->fpdf->Cell(40, 10, 'Disetujui Oleh', 0, 1, 'L', false);

        // Line break
        $this->fpdf->Ln(5);
        $this->fpdf->Cell(20, 10, '', 0, 0, 'C'); // DUMMY SUPAYA KE KANAN
        $this->fpdf->Cell(30, 10, 'Pegawai', 0, 0, 'L', false);
        $this->fpdf->Cell(40, 10, '', 0, 0, 'C'); // DUMMY SUPAYA KE KANAN
        $this->fpdf->Cell(40, 10, 'Manager Divisi', 0, 0, 'L', false);
        $this->fpdf->Cell(40, 10, ' Manager HRD', 0, 1, 'L', false);

        // Line break
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20, 10, '', 0, 0, 'C'); // DUMMY SUPAYA KE KANAN
        // $this->fpdf->Cell(30, 10, $submission->employee->user->name, 0, 0, 'L', false);
        $this->fpdf->Cell(30, 10, 'SETUJU', 1, 0, 'C', false);
        $this->fpdf->Cell(40, 10, '', 0, 0, 'C'); // DUMMY SUPAYA KE KANAN
        $this->fpdf->Cell(40, 10, $submission->division_approval, 1, 0, 'C', false);
        $this->fpdf->Cell(40, 10, $submission->hrd_approval, 1, 1, 'C', false);

        // Line break
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20, 10, '', 0, 0, 'C'); // DUMMY SUPAYA KE KANAN
        $this->fpdf->Cell(30, 10, '(' . $submission->created_date . ')', 0, 0, 'C', false);
        $this->fpdf->Cell(40, 10, '', 0, 0, 'C'); // DUMMY SUPAYA KE KANAN
        if ($submission->division_approval == 'Belum Direspon') {
            $this->fpdf->Cell(40, 10, '', 0, 0, 'C', false);
        } else {
            $this->fpdf->Cell(40, 10, '(' . $submission->division_signed_date . ')', 0, 0, 'C', false);
        }
        if ($submission->hrd_approval == 'Belum Direspon') {
            $this->fpdf->Cell(40, 10, '', 0, 1, 'C', false);
        } else {
            $this->fpdf->Cell(40, 10, '(' . $submission->hrd_signed_date . ')', 0, 1, 'C', false);
        }

        // APAKAH ADA LAMPIRAN?
        if (!($submission->attachment === NULL)) {
            $this->fpdf->AddPage("P", "A4");

            $this->fpdf->SetFont('Arial', 'B', 16);
            $this->fpdf->Cell(50, 10, '', 0, 0, 'C'); // DUMMY SUPAYA JUDUL CENTER
            $this->fpdf->Cell(80, 10, 'LAMPIRAN CUTI', 1, 1, 'C');
            // Line break
            $this->fpdf->Ln(10);

            $this->fpdf->Image("data_file/cuti/$submission->attachment", NULL, NULL, 190, 190);
        }

        // PRINT
        $this->fpdf->Output();
        exit;
    }

    public function HeaderSubmission()
    {
        $this->fpdf->Image("img/tvku_logo_ori.png", NULL, NULL, 30, 17);
        $this->fpdf->Cell(0, 0, '', 0, 1, 'C', false); // DUMMY CELL UNTUK ENTER SETELAH GAMBAR

        // Line break
        $this->fpdf->Ln(5);

        $this->fpdf->SetFont('Arial', 'B', 16);
        $this->fpdf->Cell(50, 10, '', 0, 0, 'C'); // DUMMY SUPAYA JUDUL CENTER
        $this->fpdf->Cell(100, 10, 'IJIN TIDAK MASUK BEKERJA', 1, 0, 'C');

        // Line break
        $this->fpdf->Ln(20);
    }

    public function createPDFArchive()
    {
        $this->fpdf = new Fpdf;
        $this->fpdf->AddPage("P", "A4");

        $this->HeaderArchive();

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
        $employees = $this->getArchiveData();
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

        // PRINT
        $this->fpdf->Output();
        exit;
    }

    public function HeaderArchive()
    {
        $year = Carbon::today('GMT+7')->year;

        $this->fpdf->Image("img/tvku_logo_ori.png", NULL, NULL, 30, 17);
        $this->fpdf->Cell(0, 0, '', 0, 1, 'C', false); // DUMMY CELL UNTUK ENTER SETELAH GAMBAR

        // Line break
        $this->fpdf->Ln(5);

        $this->fpdf->SetFont('Arial', 'B', 16);
        $this->fpdf->Cell(50, 10, '', 0, 0, 'C'); // DUMMY SUPAYA JUDUL CENTER
        $this->fpdf->Cell(100, 10, 'REKAP CUTI PEGAWAI ' . '(' . $year . ')', 1, 0, 'C');

        // Line break
        $this->fpdf->Ln(20);
    }

    public function getArchiveData()
    {
        $employees = Employee::orderBy('division', 'asc')->get();
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
