@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="ticket-wrapper">
                    <div class="card activated">


                        <div class="ticket-heading">


                            <div class="sectionone">
                                <div class="title-ticket">
                                    <h3>{{$ticket->title}}</h3>
                                    <span>Ticket ID #{{$ticket->ticket_id}}</span><br>
                                    <span>Category #{{$ticket->category->name}}</span>
                                </div>
                                <div class="priority">

                                    <span>priority</span>
                                    @php
                                        $text = 'Need to be fixed soon';
                                        $textcolor = 'warning';
                                        if($ticket->priority == 'low'){
                                            $text = 'Can wait';
                                            $textcolor = 'info';
                                        }else if($ticket->priority == 'high'){
                                            $text = 'Urgent';
                                            $textcolor = 'danger';
                                        }
                                    @endphp
                                    <small class="  btn-{{$textcolor}} btn-xs">{{$text}}</small>
                                </div>

                            </div>

                            <div class="sectiontwo">
                                <a href="{{route('support.ticket.listing')}}" class="back-button">
                                    <i class=" "> <img src="{{asset('front/images/left-arrow.png')}}"
                                                       alt="{{config('app.name')}}"> </i></a>

                                @if($ticket->status == 0)
                                    {{--<a href="{{route('support-ticket.close',$ticket->ticket_id)}}"
                                       class="btn-danger btn-xs" onclick="return confirm('Are you sure you want to Close this Ticket')">Close Ticket</a>--}}
                                    <a data-route="{{route('support-ticket.close',$ticket->ticket_id)}}"
                                       href="javascript:void(0);"
                                       class="btn-danger btn-xs btn-close-ticket">Close Ticket</a>
                                @endif
                            </div>

                        </div>


                        <div class="card-body">

                            <div class="ticketmassage">
                                <div class="massage-content">
                                    <div class="massage-image">
                                        @if(!empty($ticket->attachment))
                                            <img src="{{$ticket->full}}" width="100">
                                        @endif
                                    </div>
                                    <p>{{$ticket->message}}</p>


                                </div>

                                <div class="massage-status">
                                    <span class="ticket-status">Status</span>
                                    <span class="ticket-statusbtn open">
                                @if($ticket->status==0)
                                            @if(!empty($ticket->lastComment) && $ticket->lastComment->sender_id != Auth::user()->id)
                                                <span class="btn btn-warning btn-xs ">   Awaiting Customer's Reply
								  </span>
                                            @elseif(!empty($ticket->lastComment) && $ticket->lastComment->sender_id == Auth::user()->id)
                                                <span class="btn   btn-sm orange-col btn-xs">       Awaiting Agent's Reply </span>
                                            @else
                                                <span class="btn btn-danger btn-xs ">Open</span>
                                            @endif
                                        @else
                                            <span class="btn btn-success btn-xs">  Closed </span>
                                        @endif
                            </span>
                                    <span class="ticket-time">{{ \Carbon\Carbon::parse($ticket->created_at)->diffForHumans() }}</span>
                                </div>

                            </div>


                        </div>


                        <div class="chat-wrapper">
                            <div class=" ">
                                <div class="card-body">
                                    <div class="chat-list" id="chat-list">
                                        @if($ticket->comments->count() > 0)
                                            @foreach($ticket->comments as $key=>$value)
                                                @if($value->sender_id != Auth::user()->id)
                                                    <div class="chat-list-item chat-left">
                                                        <div class="chat-detials">
                                                            <h3>Support</h3>
                                                            <span class="chat-date-time"><i
                                                                        class="fas fa-calendar-alt"></i>{{ \Carbon\Carbon::parse($value->created_at)->diffForHumans() }}</span>
                                                            <div class="chat-convertation">
                                                                @if(!empty($value->image))
                                                                    <a href="{{$value->full}}" target="_blank"><img src="{{$value->full}}" width="150"/></a>
                                                                @endif
                                                                <p>
                                                                    {{$value->message}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="chat-list-item chat-right">
                                                        <div class="chat-detials">
                                                            <h3>{{$value->sender->name}}</h3>
                                                            <span class="chat-date-time"><i
                                                                        class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($value->created_at)->diffForHumans() }}</span>
                                                            <div class="chat-convertation">
                                                                @if(!empty($value->image))
                                                                    <a href="{{$value->full}}" target="_blank"><img src="{{$value->full}}" width="150"/></a>
                                                                @endif
                                                                <p>
                                                                    {{$value->message}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            @if($ticket->status == 0)
                                                <p>Add your Comments here....</p>
                                            @endif
                                        @endif
                                    </div>
                                    @if($ticket->status == 0)
                                        <form class="form-horizontal" role="form" method="POST"
                                              action="{{route('support.ticket.comment.store',$ticket->ticket_id)}}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="chat-ftr">
                                                <div class="upload-btn-wrapper">
                                                    <span class="upload-file-name"></span>
                                                    <button class="btn"><i class="fas fa-paperclip"></i></button>
                                                    <input type="file" name="image"
                                                           accept="image/png, image/gif, image/jpeg"/>
                                                </div>
                                                <input type="text" name="message" placeholder="Enter Comment"
                                                       class="form-control">
                                                <button type="submit" class="btn btn-send-msg"><i
                                                            class="fas fa-paper-plane"></i></button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('front.ticket.modal-ticket-close')
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $("#chat-list").scrollTop($("#chat-list")[0].scrollHeight);

            $(document).on('click', '.btn-close-ticket', function () {
                var route = $(this).attr('data-route');
                $('.btn-modal-close-ticket').attr('href', route);
                $('#close-ticket-modal').modal({'backdrop': 'static'});
            });

            $('input[type="file"]').change(function (e) {
                var file = e.target.files[0].name;
                $(".upload-file-name").text(file);

            });
        });
    </script>
@stop

