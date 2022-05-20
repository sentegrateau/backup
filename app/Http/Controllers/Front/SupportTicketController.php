<?php
namespace App\Http\Controllers\Front;

use App\Mail\SupportTicketUser;
use App\Models\SupportTicket;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use \Swift_Mailer;
use \Swift_SmtpTransport as SmtpTransport;


class SupportTicketController extends Controller
{
    public $title = 'Support Ticket';
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $ticketCategory=TicketCategory::where('status',1)->pluck('name','id');
        return view('front.ticket.index')->with(['title' => $this->title,'categories'=>$ticketCategory]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'title' => 'required|max:255',
            'message' => 'required|max:2000',
            'category_id' => 'required',
        ));
        $getLastTicket = SupportTicket::select(['id'])->orderBy('id','desc')->first();
        $data = $request->all();
        $ticket = new SupportTicket();
        $ticket->user_id = Auth::user()->id;
        $ticket->ticket_category_id = $data['category_id'];
        $ticket->message = $data['message'];
        $ticket->title = $data['title'];
        $ticket->ticket_id = 'TICKET'.(!empty($getLastTicket)?$getLastTicket->id+1:'1');//strtoupper(str_random(10));
        $ticket->status = false;
        $ticket->priority = $data['priority'];
        if ($request->hasfile('attachment')) {
            $name = 'attachment_' . time() . $request->file('attachment')->getClientOriginalName();
            Storage::disk('ticket_attachment')->put($name, file_get_contents($request->file('attachment')->getRealPath()));
            $ticket->attachment = $name;
        }
        $ticket->save();
        $getTicket=SupportTicket::where('id',$ticket->id)->with(['category','user'])->first();
        $this->data = ['getTicket' => $getTicket];
        $transport = (new SmtpTransport(config('mail.host_other1'), config('mail.port_other1'), config('mail.encryption_other1')))->setUsername(config('mail.username_other1'))->setPassword(config('mail.password_other1'));

        $mailer = new Swift_Mailer($transport);
        Mail::setSwiftMailer($mailer);

        Mail::to(Auth::user()->email)->send(new SupportTicketUser($getTicket));
        Session::flash('success', 'Thank you for your message. Your request has been received and being reviewed by our support team.');
        return redirect()->route('support.ticket.listing');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function listing(Request $request)
    {
        $tickets=SupportTicket::where('user_id',Auth::user()->id);
        if(!empty($request->input('title'))){
            $tickets->where('title', 'like', '%' . $request->input('title') . '%');
        }
        if(!empty($request->input('status'))){
            $status = $request->input('status')=='open'?0:1;
            $tickets->where('status',$status);
        }
        $tickets = $tickets->with(['category','user','lastComment'])->orderBy('id','desc')->paginate(15);
        //$this->pr($tickets->toArray());die;
        return view('front.ticket.listing')->with(['title' => $this->title,'tickets'=>$tickets]);
    }

    public function closeTicket(Request $request, $ticketId)
    {
        $mt = SupportTicket::where('ticket_id', $ticketId)->first();
        $mt->update(['status' => 1]);
        Session::flash('success', 'Your Ticket has been closed');
        return redirect()->route('support.ticket.listing');
    }

}
