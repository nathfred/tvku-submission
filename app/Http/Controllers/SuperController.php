<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
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

    public function edit_user($id)
    {
    }

    public function edit_employee($id)
    {
    }

    public function delete_user($id)
    {
    }
}
