<?php

namespace App\Mail;

use App\Models\Employee;
use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $submission = Submission::latest()->first();

        return $this->from(env('MAIL_FROM_ADDRESS', 'tvku.livereport@gmail.com'))
            ->view('email.confirm_submission', [
                'user' => $user,
                'employee' => $employee,
                'submission' => $submission,
            ]);
    }
}
