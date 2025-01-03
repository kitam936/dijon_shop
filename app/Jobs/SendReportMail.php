<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportMail;
use App\Mail\TestMail;

class SendReportMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $report_info;
    public $user;
    public function __construct($report_info,$user)
    {
        $this->report_info = $report_info;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        Mail::to($this->user['email'])->send(new ReportMail($this->report_info,$this->user));
        // Mail::to('test@example.com')->send(new TestMail());
    }
}
