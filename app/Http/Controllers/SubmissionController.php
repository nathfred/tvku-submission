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

        $user_submissions = Submission::where('employee_id', $employee->id)->get();
        // dd($user_submissions);

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

        return view('employee.submission-create', [
            'title' => 'Buat Pengajuan',
            'active' => 'submisison',
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
        // PERIKSA REQUEST START DATE & END DATE
        if ($request->start_date >= $request->end_date) {
            return view('employee.submission-create')->with('message', 'incorrect-date');
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

        return redirect(route('employee-submission'));
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
}
