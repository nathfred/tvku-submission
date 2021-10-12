<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SuperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $employees = Employee::all();

        return view('super.index', [
            'title' => 'Super Index',
            'active' => 'index',
            'users' => $users,
            'employees' => $employees
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
    public function show($id)
    {
        //
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

    public function show_user($id)
    {
        $user = User::where('id', $id)->first();
        $employee = Employee::where('user_id', $id)->first();

        // PERIKSA APAKAH USER SUDAH DAFTAR EMPLOYEE
        if ($employee === NULL) {
            $already_employee = 'FALSE';
        } else {
            $already_employee = 'TRUE';
        }

        return view('super.user_profile', [
            'title' => 'Super Index',
            'active' => 'index',
            'user' => $user,
            'employee' => $employee,
            'already_employee' => $already_employee
        ]);
    }

    public function edit_user(Request $request, $id)
    {
        $user = User::find($id);

        // CEK APAKAH ADA
        if ($user === NULL) {
            return back()->with('message', 'user-not-found');
        }

        // VALIDASI
        if ($user->email == $request->email) { // JIKA GANTI EMAIL
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:64']
            ]);
        } else { // JIKA EMAIL TETAP SAMA
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:64', 'unique:users']
            ]);
        }
        if ($user->ktp == $request->ktp) { // JIKA GANTI KTP
            $request->validate([
                'ktp' => ['required', 'string', 'min:16', 'max:16']
            ]);
        } else { // JIKA KTP TETAP SAMA
            $request->validate([
                'ktp' => ['required', 'string', 'min:16', 'max:16', 'unique:users']
            ]);
        }
        $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:255'],
            'gender' => ['required'],
            'ktp' => ['required', 'string', 'min:16', 'max:16'],
            'address' => ['required', 'string', 'min:4', 'max:128'],
            'birth' => ['required', 'date_format:Y-m-d'],
            'last_education' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:64'],
        ]);

        // UPDATE ATTRIBUTE
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->ktp = $request->ktp;
        $user->address = $request->address;
        $user->birth = $request->birth;
        $user->last_education = $request->last_education;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->save();

        return redirect(route('super-show-user', ['id' => $user->id]))->with('message', 'success-update-user');
    }

    public function edit_employee(Request $request, $id)
    {
        $employee = Employee::find($id);

        // CEK APAKAH ADA
        if ($employee === NULL) {
            return back()->with('message', 'user-not-found');
        }

        // VALIDASI
        if ($employee->npp == $request->npp) { // JIKA GANTI NPP
            $request->validate([
                'npp' => ['min:10', 'max:15', 'required'],
            ]);
        } else { // JIKA NPP TETAP SAMA
            $request->validate([
                'npp' => ['min:10', 'max:15', 'unique:employees', 'required']
            ]);
        }
        $request->validate([
            'npp' => ['min:10', 'max:15', 'required'],
            'position' => ['string', 'required'],
            'division' => ['string', 'required'],
            'joined' => ['integer', 'required'],
        ]);

        // UPDATE ATTRIBUTE
        $employee->npp = $request->npp;
        $employee->position = $request->position;
        $employee->division = $request->division;
        $employee->joined = $request->joined;
        $employee->save();

        return redirect(route('super-show-user', ['id' => $employee->user->id]))->with('message', 'success-update-employee');
    }

    public function delete_user($id)
    {
        $user = User::find($id);
        $employee_target = Employee::where('user_id', $id)->first();

        // CEK APAKAH ADA
        if ($user === NULL) {
            return back()->with('message', 'user-not-found');
        }

        if ($employee_target === NULL) {
        } else {
            // HAPUS SUBMISSION
            DB::table('submissions')->where('employee_id', $employee_target->id)->delete();
            // HAPUS EMPLOYEE
            $employee = Employee::find($employee_target->id);
            $employee->delete();
        }
        // HAPUS USER
        $user->delete();

        return redirect(route('super-index'))->with('message', 'success-delete');
    }
}
