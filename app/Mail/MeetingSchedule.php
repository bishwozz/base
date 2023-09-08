<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MeetingSchedule extends Mailable
{
    use Queueable, SerializesModels;

    public $meeting_date_ad, $meeting_date_bs, $meeting_start_time,$meeting_agendas;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($meeting_date)
    {
        $this->meeting_date_ad = $meeting_date->start_date_ad;
        $this->meeting_date_bs = $meeting_date->start_date_bs;
        $this->meeting_start_time = $meeting_date->start_time;
        $this->meeting_agendas = $meeting_date->agendas;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('sendEmail.meetingSchedule');
    }
}
