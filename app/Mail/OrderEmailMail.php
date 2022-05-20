<?php

namespace App\Mail;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class OrderEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $order;

    public function __construct($order,$emailOrder)
    {
        $this->order = $order;

        $this->emailOrder = $emailOrder;

        $this->subject($this->emailOrder->subject);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sales@sentegrate.com.au', config('app.name'))
            ->view('emails.order-email')->with(['content'=>$this->emailOrder->content ,'order'=>$this->order]);
    }
}
