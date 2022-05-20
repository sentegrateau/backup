@extends('adminlte::page')

@section('title', $title.'s')

@section('content_header')
    <h1>Ticket #{{$ticket->ticket_id}}</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary direct-chat direct-chat-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><p>{{$ticket->title}}</p></h3>
                    @php
                        $text = 'Need to be fixed soon';
                        $textcolor = 'orange';
                        if($ticket->priority == 'low'){
                            $text = 'Can wait';
                            $textcolor = 'primary';
                        }else if($ticket->priority == 'high'){
                            $text = 'Urgent';
                            $textcolor = 'red';
                        }
                    @endphp
                    <b>Priority: </b><label class="label bg-{{$textcolor}}">{{$text}}</label>
                    <b>Category</b>: {{optional($ticket->category)->name}}
                    <a href="{{url('admin/support-ticket')}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                    <br>
                    @if(!empty($ticket->attachment))
                        <a href="{{$ticket->full}}" download><img src="{{$ticket->full}}" width="200"/></a>
                    @endif
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="direct-chat-messages" id="chat-list">
                        @if($ticket->comments->count() > 0)
                            @foreach($ticket->comments as $key=>$value)
                                @if(Auth::user()->id != $value->sender_id)
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-name pull-left">{{optional($value->sender)->name}}</span>
                                            <span class="direct-chat-timestamp pull-right">{{ \Carbon\Carbon::parse($value->created_at)->diffForHumans() }}</span>
                                        </div>
                                        <div class="direct-chat-text">
                                            @if(!empty($value->image))
                                                <a href="{{$value->full}}" target="_blank"><img src="{{$value->full}}" width="150"/></a><br/>
                                            @endif
                                            {{$value->message}}
                                        </div>
                                    </div>

                                @else

                                    <div class="direct-chat-msg right">
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-name pull-right">You</span>
                                            <span class="direct-chat-timestamp pull-left">{{ \Carbon\Carbon::parse($value->created_at)->diffForHumans() }}</span>
                                        </div>
                                        <div class="direct-chat-text">
                                            @if(!empty($value->image))
                                                <a href="{{$value->full}}" target="_blank"><img src="{{$value->full}}" width="150"/></a><br/>
                                            @endif
                                            {{$value->message}}
                                            {{$value->is_hide == 1?'(hidden)':''}}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <p>Add your Comments below....</p>
                        @endif
                    </div>
                </div>
                <div class="box-footer">
                    <form class="" role="form" method="POST"
                          action="{{route('support-ticket.comments.store',$ticket->ticket_id)}}">
                        @csrf

                        <div class="input-group">
                        <span class="input-group-addon">
                            <label>
                                <input type="checkbox" name="is_hide" value="1"> Hide Comment
                            </label>
                        </span>
                            <input type="text" name="message" placeholder="Type Message ..." class="form-control"
                                   required>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary btn-flat">Send</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        .direct-chat-messages {
            max-height: 550px;
            height: auto;
        }
    </style>
@section('js')
    <script>
        $(document).ready(function () {
            $("#chat-list").scrollTop($("#chat-list")[0].scrollHeight);
        });
    </script>
@stop

@stop