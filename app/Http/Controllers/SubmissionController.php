<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        $employee = Employee::where('user_id', $user_id)->first();

        // CEK APAKAH USER SUDAH DAFTAR EMPLOYEE
        if ($employee === NULL) {
            return redirect()->route('employee-profile')->with('message', 'register-employee-first');
        }

        $user_submissions = Submission::where('employee_id', $employee->id)->orderBy('created_at', 'desc')->get();
        // dd($user_submissions);

        // UBAH FORMAT DATE (Y-m-d menjadi d-m-Y)
        foreach ($user_submissions as $sub) {
            // UBAH KE FORMAT CARBON
            $sub->start_date = Carbon::createFromFormat('Y-m-d', $sub->start_date);
            $sub->end_date = Carbon::createFromFormat('Y-m-d', $sub->end_date);
            // HITUNG DURASI
            $duration = $sub->start_date->diffInDaysFiltered(function (Carbon $date) {
                return !$date->isWeekend();
            }, $sub->end_date);
            $sub->duration = $duration;
            // UBAH FORMAT KE d-m-Y
            $sub->start_date = $sub->start_date->format('d-m-Y');
            $sub->end_date = $sub->end_date->format('d-m-Y');
        }

        return view('employee.submissions', [
            "title" => "Daftar Pengajuan Cuti",
            "active" => "submission",
            "user_submissions" => $user_submissions,
            "employee" => $employee,
            "user" => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        $employee = Employee::where('user_id', $user_id)->first();

        // CEK APAKAH USER SUDAH DAFTAR EMPLOYEE
        if ($employee === NULL) {
            return redirect()->route('employee-profile')->with('message', 'register-employee-first');
        }

        return view('employee.submission-create', [
            'title' => 'Buat Pengajuan',
            'active' => 'submission',
            'user' => $user,
            'employee' => $employee,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        $employee = Employee::where('user_id', $user_id)->first();

        // dd($request);

        $request->validate([
            // 'name' => 'required|max:64',
            // 'npp' => 'required|max:10',
            // 'division' => 'required|max:64',
            // 'posiiton' => 'required|max:64',
            'type' => 'required|max:32',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'description' => 'required|max:255',
            'attachment' => 'file|mimes:png,jpg,jpeg|max:2048'
        ]);

        $now = Carbon::now('GMT+7');
        $today = Carbon::today('GMT+7');

        // UBAH REQUEST DATE KE FORMAT CARBON
        $start_date_carbon = Carbon::createFromFormat('Y-m-d', $request->start_date);
        $end_date_carbon = Carbon::createFromFormat('Y-m-d', $request->end_date);

        // PERIKSA TANGGAL IJIN DAN TANGGAL KEMBALI APAKAH VALID DENGAN TANGGAL SUBMISSION DIBUAT
        if ($today->greaterThan($start_date_carbon) || $today->greaterThan($end_date_carbon)) {
            return redirect()->route('employee-submission-create')->with('message', 'incorrect-date');
        }

        // PERIKSA REQUEST START DATE & END DATE
        if ($start_date_carbon >= $request->end_date) {
            return redirect()->route('employee-submission-create')->with('message', 'incorrect-date');
        }

        // PERIKSA APAKAH EMPLOYEE SUDAH ABSEN 2X? MAX 2X SEBULAN KECUALI HAMIL
        $today = Carbon::today('GMT+7');
        $month = $today->format('m');
        $user_month_submissions = Submission::where('employee_id', $employee->id)->whereMonth('start_date', $month)->get();
        if ($user_month_submissions->count() >= 2 && $request->description == "Hamil") {
            // AMAN, BOLEH IJIN
        } elseif ($user_month_submissions->count() >= 2) {
            return redirect()->route('employee-submission-create')->with('message', 'max-submission-per-month');
        }

        // JIKA SUBMISSION TANPA LAMPIRAN (ATTACHMENT)
        if ($request->attachment === NULL) {
            Submission::create([
                'employee_id' => $employee->id,
                'type' => $request->type,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 'Unresponded',
            ]);
            return redirect(route('employee-submission'))->with('message', 'success-submission');
        }
        $file = $request->attachment;

        $file_name = 'Cuti_' . $employee->npp . '_' . $request->start_date . '_' . $request->end_date . '.' . $file->getClientOriginalExtension();
        $directory = 'data_file/cuti/';

        $file->move($directory, $file_name);

        Submission::create([
            'employee_id' => $employee->id,
            'type' => $request->type,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'Unresponded',
            'attachment' => $file_name
        ]);

        return redirect(route('employee-submission'))->with('message', 'success-submission');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function show(Submission $submission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function edit(Submission $submission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Submission $submission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Submission $submission)
    {
        //
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function delete_submission($id)
    {
        // dd($id);
        $submission = Submission::find($id);
        // dd($submission);

        // VALIDASI APAKAH SUBMISSION ADA
        if ($submission === NULL || is_null($submission)) {
            return back()->with('message', 'submission-not-found');
        }

        $submission->delete();
        return back()->with('message', 'success-delete-submission');
    }
}
