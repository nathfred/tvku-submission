<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminHRDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // AMBIL DATA USER (ADMIN-HRD)
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        // AMBIL 3 PENGAJUAN TERAKHIR (BERDASARKAN TANGGAL PENGAJUAN DIBUAT)
        // $recent_submissions = Submission::orderBy('created_at', 'desc')->take(3)->get();
        $recent_submissions = Submission::latest()->take(3)->get();

        // UBAH FORMAT DATE (Y-m-d menjadi d-m-Y)
        foreach ($recent_submissions as $sub) {
            // UBAH KE FORMAT CARBON
            $sub->start_date = Carbon::createFromFormat('Y-m-d', $sub->start_date);
            $sub->end_date = Carbon::createFromFormat('Y-m-d', $sub->end_date);
            // UBAH FORMAT KE d-m-Y
            $sub->start_date = $sub->start_date->format('d-m-Y');
            $sub->end_date = $sub->end_date->format('d-m-Y');
        }

        // $date = '2000-12-31';
        // $date2 = '31-12-2000';
        // $today = Carbon::today();
        // dd($today->format('d-m-Y'));
        // dd(Carbon::createFromFormat('d-m-Y', '31-12-2000')->toDateString());

        // CARI YANG HARI INI SEDANG CUTI (SUBMISSION YANG SUDAH DI ACC)
        $today = Carbon::today();
        $today = $today->format('Y-m-d');
        // dd($today);
        $current_submissions = Submission::where('start_date', '<=', $today)->where('end_date', '>', $today)->where('hrd_approval', '1')->where('division_approval', '1');
        dd($current_submissions->count());

        return view('admin.index', [
            'title' => 'Admin Index',
            'active' => 'index',
            'user' => $user,
            'recent_submissions' => $recent_submissions
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
}
