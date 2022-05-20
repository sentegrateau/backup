<?php

namespace App\Http\Controllers\Admin;

use App\Mail\SupportTicketCommentMail;
use App\Models\SupportTicket;
use App\Models\SupportTicketComment;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{

    public $title = 'Support Ticket';

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $req = $request->all();
        $tickets=SupportTicket::with(['category','user','lastComment']);
        if(!empty($request->input('title'))){
            $tickets->join('users', 'users.id', '=', 'support_tickets.user_id');
            $tickets->where('support_tickets.title', 'like', '%' . $request->input('title') . '%')->orWhere('users.name', 'like', '%' . $request->input('title') . '%');
        }
        if(!empty($request->input('status'))){
            $status = $request->input('status')=='open'?0:1;
            $tickets->where('support_tickets.status',$status);
        }
        $tickets = $tickets->orderBy('support_tickets.id','DESC')->paginate(15);
        //$this->pr($tickets->toArray());die;
        return view('admin.ticket.index')->with(['title' => $this->title,'tickets'=>$tickets]);
    }

    public function activeDeactivate(Request $request, $id)
    {
        $data = $request->all();
        $mt = SupportTicket::where('id', $id)->first();
        $mt->update(['status' => !($mt->status)]);
        return redirect()->route('support-ticket.index')->with('success', "Ticket " . (($mt->status) ? 'Closed' : 'Opened') . " Successfully.");
    }

    public function comments($ticketId)
    {
        $ticket=SupportTicket::where('ticket_id',$ticketId)->with(['comments'=>function($q){
            return $q->with(['sender','receiver'])->orderBy('id','asc');
        }])->first();
        return view('admin.ticket.comment')->with(['title' => $this->title,'ticket'=>$ticket]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function commentStore(Request $request,$ticketId)
    {
        $this->validate($request, array(
            'message' => 'required|max:5000',
        ));
        $ticket=SupportTicket::where('ticket_id',$ticketId)->first();
        $data = $request->all();
        $comment = new SupportTicketComment();
        $comment->support_ticket_id = $ticket->id;
        $comment->sender_id = 1;
        $comment->receiver_id = $ticket->user_id;
        $comment->message = $data['message'];
        $comment->is_hide = !empty($data['is_hide'])?$data['is_hide']:0;
        $comment->save();

        Mail::to($comment->receiver->email)->send(new SupportTicketCommentMail($comment));
        Mail::to(config('app.admin_email'))->send(new SupportTicketCommentMail($comment));

        Session::flash('success', 'Your Comment has been generated!');
        return redirect()->route('support-ticket.comments',$ticketId);
    }

    public function delete($id)
    {
        $ticket = SupportTicket::where('id', $id)->first();
        $ticket->delete();
        return back();
    }

}
