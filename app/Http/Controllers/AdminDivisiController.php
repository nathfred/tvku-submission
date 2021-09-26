<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // AMBIL DATA USER (ADMIN-DIVISI)
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        // AMBIL DIVISI SESUAI ADMIN DIVISI (AMBIL DARI EMAIL)
        $division = str_replace('divisi', '', $user->email);
        $division = str_replace('@tvku.tv', '', $division);
        $division = ucfirst($division);

        $recent_submissions = Submission::latest()->get();

        // UBAH FORMAT DATE (Y-m-d menjadi d-m-Y)
        foreach ($recent_submissions as $sub) {
            // UBAH KE FORMAT CARBON
            $sub->start_date = Carbon::createFromFormat('Y-m-d', $sub->start_date);
            $sub->end_date = Carbon::createFromFormat('Y-m-d', $sub->end_date);
            // UBAH FORMAT KE d-m-Y
            $sub->start_date = $sub->start_date->format('d-m-Y');
            $sub->end_date = $sub->end_date->format('d-m-Y');
        }

        $today = Carbon::today('GMT+7');
        $today = $today->format('Y-m-d');

        // TOTAL PENGAJUAN (YANG BELUM KADALUARSA)
        $total_submissions = Submission::where('end_date', '>', $today)->orderBy('created_at', 'desc')->get();

        // TOTAL PENGAJUAN YANG SUDAH DI ACC HRD (YANG BELUM KADALUARSA)
        // $responded_submissions = Submission::where('end_date', '>', $today)->where('hrd_approval', '0')->orWhere('hrd_approval', '1')->get();
        $responded_submissions = Submission::where('end_date', '>', $today)->whereNotNull('hrd_approval')->get();

        // TOTAL PENGAJUAN YANG BELUM DI ACC HRD (YANG BELUM KADALUARSA)
        $unresponded_submissions = Submission::where('end_date', '>', $today)->whereNull('hrd_approval')->get();

        // CARI YANG HARI INI SEDANG CUTI (SUBMISSION YANG SUDAH DI ACC)
        $current_submissions = Submission::where('start_date', '<=', $today)->where('end_date', '>', $today)->where('hrd_approval', '1')->where('division_approval', '1')->orderBy('created_at', 'desc')->get();
        // dd($current_submissions->count());

        $all_submissions = Submission::all();

        // SET KEY TO COLLECTIONS (FOR REMOVAL : FORGET METHOD NEEDS KEY)
        $all_submissions = $all_submissions->keyBy('id');
        $total_submissions = $total_submissions->keyBy('id');
        $recent_submissions = $recent_submissions->keyBy('id');
        $responded_submissions = $responded_submissions->keyBy('id');
        $unresponded_submissions = $unresponded_submissions->keyBy('id');
        $current_submissions = $current_submissions->keyBy('id');

        // FILTER SESUAI DIVISI
        foreach ($all_submissions as $sub) {
            if (!($sub->employee->division == $division)) {
                // if ($total_submissions->contains('id', $sub->id)) {
                //     $total_submissions->forget($sub->id);
                // }
                // if ($recent_submissions->contains('id', $sub->id)) {
                //     $recent_submissions->forget($sub->id);
                // }
                // if ($responded_submissions->contains('id', $sub->id)) {
                //     $responded_submissions->forget($sub->id);
                // }
                // if ($unresponded_submissions->contains('id', $sub->id)) {
                //     $unresponded_submissions->forget($sub->id);
                // }
                // if ($current_submissions->contains('id', $sub->id)) {
                //     $current_submissions->forget($sub->id);
                // }
                $recent_submissions->forget($sub->id);
                $total_submissions->forget($sub->id);
                $responded_submissions->forget($sub->id);
                $unresponded_submissions->forget($sub->id);
                $current_submissions->forget($sub->id);
            }
        }

        $recent_submissions = $recent_submissions->take(3);

        return view('admin-divisi.index', [
            'title' => 'Admin Index',
            'active' => 'index',
            'user' => $user,
            'recent_submissions' => $recent_submissions,
            'total_submissions' => $total_submissions->count(),
            'responded_submissions' => $responded_submissions->count(),
            'unresponded_submissions' => $unresponded_submissions->count(),
            'current_submissions' => $current_submissions->count(),
            'all_submissions' => $total_submissions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // AMBIL DATA USER (ADMIN-DIVISI)
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        // AMBIL DIVISI SESUAI ADMIN DIVISI (AMBIL DARI EMAIL)
        $division = str_replace('divisi', '', $user->email);
        $division = str_replace('@tvku.tv', '', $division);
        $division = ucfirst($division);
        if ($division == 'It') {
            $division = 'IT';
        }
        // dd($division);

        $today = Carbon::today('GMT+7');
        $today = $today->format('Y-m-d');

        $total_submissions = Submission::where('end_date', '>', $today)->orderBy('created_at', 'desc')->get();

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

        $all_submissions = Submission::all();

        // SET KEY TO COLLECTIONS (FOR REMOVAL : FORGET METHOD NEEDS KEY)
        $all_submissions = $all_submissions->keyBy('id');
        $total_submissions = $total_submissions->keyBy('id');

        // FILTER SESUAI DIVISI
        foreach ($all_submissions as $sub) {
            if (!($sub->employee->division == $division)) {
                $total_submissions->forget($sub->id);
            }
        }

        return view('admin-divisi.submissions', [
            'title' => 'Daftar Pengajuan Cuti',
            'active' => 'cuti',
            'total_submissions' => $total_submissions,
            'division' => $division
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function acc_submission($id, $acc)
    {
        $today = Carbon::today('GMT+7');

        $submission = Submission::find($id);
        $submission->division_approval = $acc;
        $submission->division_signed_date = $today;
        $submission->save();

        if ($acc == 1) {
            return redirect()->route('admindivisi-submission')->with('message', 'success-submission-acc');
        } elseif ($acc == 0) {
            return redirect()->route('admindivisi-submission')->with('message', 'success-submission-dec');
        } else {
            return redirect()->route('admindivisi-submission')->with('message', 'success-submission-unknown');
        }
    }

    public function employees()
    {
        // AMBIL DATA USER (ADMIN-DIVISI)
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        // AMBIL DIVISI SESUAI ADMIN DIVISI (AMBIL DARI EMAIL)
        $division = str_replace('divisi', '', $user->email);
        $division = str_replace('@tvku.tv', '', $division);
        $division = ucfirst($division);
        if ($division == 'It') {
            $division = 'IT';
        }

        $employees = Employee::orderBy('division', 'asc')->get();
        // $approved_submissions = Submission::where('hrd_approval', 1)->where('division_approval', 1)->get();

        $today = Carbon::today('GMT+7');
        $month = $today->format('m');
        $approved_submissions_month = Submission::where('hrd_approval', 1)->where('division_approval', 1)->whereMonth('start_date', $month)->get();

        // SET KEY TO COLLECTIONS (FOR REMOVAL : FORGET METHOD NEEDS KEY)
        $employees = $employees->keyBy('id');
        // FILTER EMPLOYEE SESUAI DIVISI
        foreach ($employees as $employee) {
            if (!($employee->division == $division)) {
                $employees->forget($employee->id);
            }
        }

        // HITUNG BERAPA KALI EMPLOYEE SUDAH CUTI BULAN INI
        foreach ($employees as $employee) {
            // CARA 1 (WORKED BUT NOT EFFICIENT)
            // $employee_month_submissions = Submission::where('employee_id', $employee->id)->where('hrd_approval', 1)->where('division_approval', 1)->whereMonth('start_date', $month)->get();
            // $employee->total = $employee_month_submissions->count();

            // CARA 2
            $total_cuti = 0;
            foreach ($approved_submissions_month as $sub) {
                if ($sub->employee_id == $employee->id) {
                    $total_cuti++;
                }
            }
            $employee->total = $total_cuti;
        }

        return view('admin-divisi.employees', [
            'title' => 'Daftar Pegawai',
            'active' => 'employees',
            'employees' => $employees,
        ]);
    }

    public function archive()
    {
        $employees = Employee::orderBy('division', 'asc')->get();
        $approved_submissions = Submission::where('division_approval', 1)->where('hrd_approval', 1)->orderBy('employee_id', 'asc')->get();

        // AMBIL DATA USER (ADMIN-DIVISI)
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        // AMBIL DIVISI SESUAI ADMIN DIVISI (AMBIL DARI EMAIL)
        $division = str_replace('divisi', '', $user->email);
        $division = str_replace('@tvku.tv', '', $division);
        $division = ucfirst($division);
        if ($division == 'It') {
            $division = 'IT';
        }

        // SET KEY TO COLLECTIONS (FOR REMOVAL : FORGET METHOD NEEDS KEY)
        $employees = $employees->keyBy('id');
        // FILTER EMPLOYEE SESUAI DIVISI
        foreach ($employees as $employee) {
            if (!($employee->division == $division)) {
                $employees->forget($employee->id);
            }
        }

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

        return view('admin-divisi.archive', [
            'title' => 'Arsip Bulanan',
            'active' => 'archive',
            'employees' => $employees
        ]);
    }
}
