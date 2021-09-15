<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('employee.index', [
            'title' => 'Index',
            'active' => 'index'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user_id = Auth::id();
        Employee::create([
            "user_id" => $user_id,
            "npp" => $request->npp,
            "position" => $request->position,
            "division" => $request->division,
            "joined" => $request->joined
        ]);

        // return view('employee.profile', [
        //     "title" => "Profile",
        //     "active" => "index",
        //     "message" => "success-register"
        // ]);
        $message = "success-register";
        return redirect(route('employee-profile'))->with('message', $message);
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
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        $employee = Employee::where('user_id', $user_id)->first();

        // $user->npp = $employee->npp;
        // $user->position = $employee->position;
        // $user->division = $employee->division;
        // $user->joined = $employee->joined;

        // PERIKSA APAKAH USER SUDAH DAFTAR EMPLOYEE
        if ($employee === NULL) {
            return view('employee.profile', [
                "title" => "Profile",
                "active" => "profile",
                "already_employee" => 'FALSE',
                "employee" => $employee,
                "user" => $user,
            ]);
        }

        return view('employee.profile', [
            "title" => "Profile",
            "active" => "profile",
            "already_employee" => 'TRUE',
            "employee" => $employee,
            "user" => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function submission()
    {
        return $this->hasMany(Submission::class);
    }
}
