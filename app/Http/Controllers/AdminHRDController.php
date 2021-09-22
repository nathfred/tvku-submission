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

        $today = Carbon::today('GMT+7');
        $today = $today->format('Y-m-d');
        // dd($today);

        // TOTAL PENGAJUAN (YANG BELUM KADALUARSA)
        $total_submissions = Submission::where('end_date', '>', $today)->orderBy('created_at', 'asc')->get();

        // TOTAL PENGAJUAN YANG SUDAH DI ACC HRD (YANG BELUM KADALUARSA)
        $responded_submissions = Submission::where('end_date', '>', $today)->where('hrd_approval', '0')->orWhere('hrd_approval', '1')->get();

        // TOTAL PENGAJUAN YANG BELUM DI ACC HRD (YANG BELUM KADALUARSA)
        $unresponded_submissions = Submission::where('end_date', '>', $today)->whereNull('hrd_approval')->get();

        // CARI YANG HARI INI SEDANG CUTI (SUBMISSION YANG SUDAH DI ACC)
        $current_submissions = Submission::where('start_date', '<=', $today)->where('end_date', '>', $today)->where('hrd_approval', '1')->where('division_approval', '1')->orderBy('created_at', 'asc')->get();
        // dd($current_submissions->count());

        return view('admin-hrd.index', [
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
        $today = Carbon::today('GMT+7');
        $today = $today->format('Y-m-d');

        $total_submissions = Submission::where('end_date', '>', $today)->orderBy('created_at', 'asc')->get();

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

        return view('admin-hrd.submissions', [
            'title' => 'Daftar Pengajuan Cuti',
            'active' => 'Cuti',
            'total_submissions' => $total_submissions
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
        $submission->hrd_approval = $acc;
        $submission->hrd_signed_date = $today;
        $submission->save();

        if ($acc == 1) {
            return redirect()->route('adminhrd-submission')->with('message', 'success-submission-acc');
        } elseif ($acc == 0) {
            return redirect()->route('adminhrd-submission')->with('message', 'success-submission-dec');
        } else {
            return redirect()->route('adminhrd-submission')->with('message', 'success-submission-unknown');
        }
    }
}
