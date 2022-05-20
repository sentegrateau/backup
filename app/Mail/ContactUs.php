<?php

namespace App\Mail;

use App\Http\Controllers\Front\UserController;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $contactform;


    public function __construct($contactform)
    {
        $this->contactdata = $contactform;

        $this->subject(config('APP_NAME') . ' Order Confirmation #' . $this->contactdata->order_number);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('app.mail_from'), config('app.name'))
            //->bcc(config('app.admin_email'),config('app.name'))
            ->bcc([config('app.admin_email'),'syed.zaid@sentegrate.com.au'],config('app.name'))
			->view('emails.contact-us');
    }
}
