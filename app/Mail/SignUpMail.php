<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SignUpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;

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
        return $this->from(config('app.mail_from'),config('app.name'))
            //->bcc(config('app.admin_email'),config('app.name'))
			->bcc(['ned.siddiqi@sentegrate.com.au','syed.zaid@sentegrate.com.au'],config('app.name'))
            ->view('emails.sign-up', ['user' => $this->user]);
    }
}
