<?php

namespace App\Jobs;

use Log;
use Illuminate\Bus\Queueable;
use App\Models\EcMeetingRequest;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendEmailToEcMeetingRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $emails;
    protected $pdf_full_path;
    protected $extension;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $emails, string $pdf_full_path, string $extension)
    {
        $this->emails = $emails;
        $this->pdf_full_path = $pdf_full_path;
        $this->extension = $extension;    
    }



    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        try {
            \Log::info('Sending emails to: ' . implode(', ', $this->emails));
            foreach ($this->emails as $email) {
                if (isset($email)) {
                    Mail::send('agenda.agenda', [], function ($message) use ($email) {
                        $message->to($email)
                            ->from(env('MAIL_USERNAME'))
                            ->subject('बैठक प्रस्ताब विवरण')
                            ->attach($this->pdf_full_path, ['as' => 'MeetingAgenda' . $this->extension, 'mime' => 'application/' . $this->extension]);
                    });
                }
            }
            
        } catch (\Exception $e) {
            // dd($e);
            \Log::error('Job failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
