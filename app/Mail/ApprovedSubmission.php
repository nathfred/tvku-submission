<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Employee;
use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovedSubmission extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $acc, $approver)
    {
        $this->user_id = $id;
        $this->acc = $acc;
        $this->approver = $approver;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = User::find($this->user_id);
        $employee = Employee::where('user_id', $user->id)->first();
        $submission = Submission::where('employee_id', $employee->id)->first();

        return $this->from(env('MAIL_FROM_ADDRESS', 'tvku.livereport@gmail.com'))
            ->view('email.approved_submission', [
                'user' => $user,
                'employee' => $employee,
                'submission' => $submission,
                'acc' => $this->acc,
                'approver' => $this->approver,
            ]);
    }
}
