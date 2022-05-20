<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
        $this->subject( 'Sentegrate support ' .$this->ticket->ticket_id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@sentegrate.com.au',config('app.name'))
            //->bcc(config('app.admin_email'),config('app.name'))
			->bcc([config('app.admin_email'),'syed.zaid@sentegrate.com.au'],config('app.name'))
            ->subject('Sentegrate support ' .$this->ticket->ticket_id)
            ->view('emails.support-ticket-user', ['ticket' => $this->ticket]);
    }
}
