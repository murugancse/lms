<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Setting\Model\GeneralSetting;

class SendMailableFeedback extends Mailable
{
    use Queueable, SerializesModels;
    public $sendmessage;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->sendmessage = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $generalSetting = GeneralSetting::first();

        return $this->from($generalSetting->email)
            ->view('mail.feedback');
    }
}
