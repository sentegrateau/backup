<?php
namespace App\Http\Controllers\Front;

use App\Mail\SupportTicketCommentMail;
use App\Models\SupportTicketComment;
use App\Models\SupportTicket;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class SupportTicketCommentController extends Controller
{
    public $title = 'Support Ticket';
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index($ticketId)
    {
        $ticket=SupportTicket::where('ticket_id',$ticketId)->where('user_id',Auth::user()->id)->with(['comments'=>function($q){
            return $q->with(['sender','receiver'])->where('is_hide',0)->orderBy('id','asc');
        }])->first();
        //$this->pr($ticket->toArray(),1);
        return view('front.ticket-comment.index')->with(['title' => $this->title,'ticket'=>$ticket]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$ticketId)
    {
        $data = $request->all();
        if ($request->hasfile('image')) {
            $this->validate($request, array(
                'image' => 'required',
            ));
        }else{
            $this->validate($request, array(
                'message' => 'required|max:5000',
            ));
        }
        $ticket=SupportTicket::where('ticket_id',$ticketId)->first();
        $comment = new SupportTicketComment();
        $comment->support_ticket_id = $ticket->id;
        $comment->sender_id = Auth::user()->id;
        $comment->receiver_id = 1;
        $comment->message = $data['message'];
        if ($request->hasfile('image')) {
            $name = 'support_' . time() . $request->file('image')->getClientOriginalName();
            Storage::disk('support_attachment')->put($name, file_get_contents($request->file('image')->getRealPath()));
            $comment->image = $name;
        }
        $comment->save();

        Mail::to(Auth::user()->email)->send(new SupportTicketCommentMail($comment));
        Mail::to(config('app.admin_email'))->send(new SupportTicketCommentMail($comment));
        Session::flash('success', 'Your Comment has been generated, we will contact you soon!');
        return redirect()->route('support.ticket.comment',$ticketId);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function listing()
    {
        $tickets=SupportTicket::where('user_id',Auth::user()->id)->with(['category','user'])->paginate(15);
        return view('front.ticket.listing')->with(['title' => $this->title,'tickets'=>$tickets]);
    }

}
