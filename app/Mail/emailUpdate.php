<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class emailUpdate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('app.mail_from'),config('app.name'))->subject('OTP for Email Verification')
            //->bcc(config('app.admin_email'),config('app.name'))
			->bcc([config('app.admin_email'),'syed.zaid@sentegrate.com.au'],config('app.name'))
            ->view('emails.emailUpdate', ['user' => $this->user]);
    }
}
