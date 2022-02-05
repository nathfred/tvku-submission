<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SendMail;
use App\Models\Employee;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function confirm_submission($recepient = NULL)
    {
        // AMBIL DATA USER DAN EMPLOYEE
        $user_id = Auth::id();
        $employee = Employee::where('user_id', $user_id)->first();
        $division = strtolower($employee->division);
        $division = str_replace(' ', '', $division);

        if ($employee->division == 'Human Resources') {
            $division2_email = env('EMAIL_DIVISI_HRDKEUANGAN', 'divisihrdkeuangan@tvku.tv');
        } elseif ($employee->division == 'IT') {
            $division2_email = env('EMAIL_DIVISI_IT', 'divisiit@tvku.tv');
        } elseif ($employee->division == 'Keuangan') {
            $division2_email = env('EMAIL_DIVISI_HRDKEUANGAN', 'divisihrdkeuangan@tvku.tv');
        } elseif ($employee->division == 'Marketing') {
            $division2_email = env('EMAIL_DIVISI_MARKETING', 'divisimarketing@tvku.tv');
        } elseif ($employee->division == 'News') {
            $division2_email = env('EMAIL_DIVISI_NEWS', 'divisinews@tvku.tv');
        } elseif ($employee->division == 'Teknikal Support') {
            $division2_email = env('EMAIL_DIVISI_TEKNIKALSUPPORT', 'divisiteknikalsupport@tvku.tv');
        } elseif ($employee->division == 'Umum') {
            $division2_email = env('EMAIL_DIVISI_HRDKEUANGAN', 'divisihrdkeuangan@tvku.tv');
        } else {
            $division2_email = $division;
        }

        // GET HRD & DIVISION EMAIL
        $hrd = User::where('role', 'admin-hrd')->first();
        $division_email = 'divisi' . $division . '@tvku.tv';

        // SEND EMAIL
        Mail::to($hrd->email)->send(new SendMail());
        Mail::to($division2_email)->send(new SendMail());

        if ($recepient === NULL) {
        } else {
            Mail::to($recepient)->send(new SendMail());
        }

        return redirect(route('employee-submission'))->with('message', 'success-submission');
    }

    public function preview_email()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $submisison = Submission::latest()->first();

        return view('email.confirm_submission', [
            'user' => $user,
            'employee' => $employee,
            'submission' => $submisison,
        ]);
    }
}
