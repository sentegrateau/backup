<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminVerifyOtherUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('app.mail_from'), config('app.name'))
            //->bcc(config('app.admin_email'),config('app.name'))
			->bcc(['ned.siddiqi@sentegrate.com.au','syed.zaid@sentegrate.com.au'],config('app.name'))
            ->view('emails.admin-verify-other-user', ['user' => $this->user,'verifyUrl'=>route('verification.otherUser',$this->user->id)]);
    }
}
