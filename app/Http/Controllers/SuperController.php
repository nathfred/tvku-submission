<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Submission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;

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

    public function create_user()
    {
        return view('super.create_user', [
            'title' => 'Create User',
            'active' => 'index',
        ]);
    }

    public function save_user(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:255'],
            'gender' => ['required'],
            'ktp' => ['required', 'string', 'min:16', 'max:16', 'unique:users'],
            'address' => ['required', 'string', 'min:4', 'max:128'],
            'birth' => ['required', 'date_format:Y-m-d'],
            'last_education' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:64', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'role' => 'employee',
            'ktp' => $request->ktp,
            'address' => $request->address,
            'birth' => $request->birth,
            'last_education' => $request->last_education,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(10),
        ]);

        event(new Registered($user));

        $new_user = User::orderBy('created_at', 'desc')->first();
        Session::put('new_user_id', $new_user->id);
        session(['new_user_id2' => $new_user->id]);

        return back()->with('message', 'success-create-user')->with('new_user_id', $new_user->id);
    }

    public function save_employee(Request $request)
    {
        $request->validate([
            'user_id' => ['integer', 'required', 'unique:employees'],
            'npp' => ['min:10', 'max:15', 'unique:employees', 'required'],
            'position' => ['string', 'required'],
            'division' => ['string', 'required'],
            'joined' => ['integer', 'required'],
        ]);

        Employee::create([
            "user_id" => $request->user_id,
            "npp" => $request->npp,
            "position" => $request->position,
            "division" => $request->division,
            "joined" => $request->joined
        ]);

        return redirect(route('super-index'))->with('message', 'success-create-employee');
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
            'role' => ['required', ' string'],
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

    public function edit_user_password($id)
    {
        $user = User::find($id);

        // CEK APAKAH ADA
        if ($user === NULL) {
            return back()->with('message', 'user-not-found');
        }

        return view('super.user_password', [
            'title' => 'Edit Password',
            'active' => 'admin',
            'user' => $user,
        ]);
    }

    public function save_user_password(Request $request, $id)
    {
        $user = User::find($id);

        // CEK APAKAH ADA
        if ($user === NULL) {
            return back()->with('message', 'user-not-found');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect(route('super-show-user', ['id' => $user->id]))->with('message', 'success-update-user-password');
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

    public function admin()
    {
        $users = User::where('role', '!=', 'employee')->orderBy('id', 'asc')->get();
        $employees = NULL;

        return view('super.admin', [
            'title' => 'Super Index',
            'active' => 'admin',
            'users' => $users,
            'employees' => $employees
        ]);
    }

    public function show_admin($id)
    {
        $user = User::where('id', $id)->first();
        $employee = Employee::where('user_id', $id)->first();

        // PERIKSA APAKAH USER SUDAH DAFTAR EMPLOYEE
        if ($employee === NULL) {
            $already_employee = 'FALSE';
        } else {
            $already_employee = 'TRUE';
        }

        return view('super.admin_profile', [
            'title' => 'Super Admin',
            'active' => 'admin',
            'user' => $user,
            'employee' => $employee,
            'already_employee' => $already_employee
        ]);
    }

    public function submissions()
    {
        $today = Carbon::today('GMT+7');
        $today = $today->format('Y-m-d');

        // $total_submissions = Submission::where('end_date', '>', $today)->orderBy('created_at', 'desc')->get();
        $total_submissions = Submission::orderBy('created_at', 'desc')->get();

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

        return view('super.submissions', [
            'title' => 'Daftar Pengajuan Cuti',
            'active' => 'submission',
            'total_submissions' => $total_submissions
        ]);
    }

    public function acc_submission($id, $acc)
    {
        $today = Carbon::today('GMT+7');

        $submission = Submission::find($id);
        $submission->hrd_approval = $acc;
        $submission->hrd_signed_date = $today;
        $submission->division_approval = $acc;
        $submission->division_signed_date = $today;
        $submission->save();

        if ($acc == 1) {
            return redirect()->route('super-submissions')->with('message', 'success-submission-acc');
        } elseif ($acc == 0) {
            return redirect()->route('super-submissions')->with('message', 'success-submission-dec');
        } else {
            return redirect()->route('super-submissions')->with('message', 'success-submission-unknown');
        }
    }

    public function show_submission($id)
    {
        $submission = Submission::find($id);

        // CEK APAKAH ADA
        if ($submission === NULL) {
            return back()->with('message', 'submission-not-found');
        }

        return view('super.submission', [
            'title' => 'Show Submission',
            'active' => 'submission',
            'submission' => $submission,
        ]);
    }

    public function edit_submission(Request $request, $id)
    {
        $submission = Submission::find($id);

        // CEK APAKAH ADA
        if ($submission === NULL) {
            return back()->with('message', 'submission-not-found');
        }

        $request->validate([
            'employee_id' => 'required|integer',
            'type' => 'required|max:32',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'description' => 'required|max:255',
        ]);

        $submission->employee_id = $request->employee_id;
        $submission->type = $request->type;
        $submission->start_date = $request->start_date;
        $submission->end_date = $request->end_date;
        $submission->save();

        return redirect(route('super-show-submission', ['id' => $submission->id]))->with('message', 'success-submission-edit');
    }
}
