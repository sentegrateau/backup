@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="ticket-wrapper">
                    <div class="card">
                        <div class="ticket-heading"><h6>Request a problem resolution or change</h6>
                            <a href="{{route('support.ticket')}}" class="btn btn-outline-primary pull-right">Add
                                Ticket</a>
                        </div>
                        <div class="">
                            <div class="ticket-filters">
                                <div class="filtersticket">
                                    <div class="shortby">

                                        <strong>Filter by: </strong>

                                        <a href="{{request()->fullUrlWithQuery(['status' => 'open'])}}">
                                            <small
                                                class="btn btn-default btn-sm {{request()->input('status')=='open'?'active':''}}">
                                                Open
                                            </small>
                                        </a>
                                        <a href="{{request()->fullUrlWithQuery(['status' => 'close'])}}">
                                            <small
                                                class="btn btn-default btn-sm {{request()->input('status')=='close'?'active':''}}">
                                                Closed
                                            </small>
                                        </a>
                                        <a href="{{url('support-ticket/listing')}}">
                                            <small class="btn btn-default">Clear All</small>
                                        </a>
                                    </div>

                                    <div class="search-ticket">
                                        <form action="{{route('support.ticket.listing')}}">
                                            @if(!empty(request()->input('status')))
                                                <input type="hidden" value="{{request()->input('status')}}"
                                                       name="status">
                                            @endif
                                            <input type="text" value="{{request()->input('title')}}" name="title"
                                                   placeholder="search by title">
                                            <button  type="submit" class="search_icon"><i class="fas fa-search"></i></button>
                                        </form>

                                    </div>


                                </div>

                            </div>
                            <div class="chat-lists">
                                <div class="ticket-create-item">


                                    <table class="table">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th>Ticket No</th>
                                            <th>Category</th>
                                            <th>Title</th>
                                            <th>priority</th>

                                            <th>Time</th>
                                            <th>Status</th>

                                            <th>Action</th>

                                        </tr>
                                        </thead>

                                        <tbody>
                                        @if(!empty($tickets))
                                            @php $i = ($tickets->perPage() * ($tickets->currentPage() - 1)+1);@endphp
                                            @foreach($tickets as $key=>$value)
                                                <tr class="cls-tr-ticket-detail">
                                                    <td onclick="ticketDetail('{{route("support.ticket.comment",$value->ticket_id)}}');">{{$value->ticket_id}}</td>
                                                    <td onclick="ticketDetail('{{route("support.ticket.comment",$value->ticket_id)}}');">{{$value->category->name}}</td>

                                                    <td onclick="ticketDetail('{{route("support.ticket.comment",$value->ticket_id)}}');">{{$value->title}}</td>
                                                    <td onclick="ticketDetail('{{route("support.ticket.comment",$value->ticket_id)}}');"> @php
                                                            $text = 'Need to be fixed soon';
                                                            $textcolor = 'warning';
                                                            if($value->priority == 'low'){
                                                                $text = 'Can wait';
                                                                $textcolor = 'info';
                                                            }else if($value->priority == 'high'){
                                                                $text = 'Urgent';
                                                                $textcolor = 'danger';
                                                            }
                                                        @endphp
                                                        <small class="btn btn-{{$textcolor}} btn-xs">{{$text}}</small>
                                                    </td>

                                                    <td onclick="ticketDetail('{{route("support.ticket.comment",$value->ticket_id)}}');">{{ \Carbon\Carbon::parse($value->created_at)->diffForHumans() }}</td>
                                                    <td onclick="ticketDetail('{{route("support.ticket.comment",$value->ticket_id)}}');">
											         <span class="ticket-statusbtn open">
                                @if($value->status==0)
                                                             @if(!empty($value->lastComment) && $value->lastComment->sender_id != Auth::user()->id)
                                                                 <span class="btn btn-warning btn-xs">   Awaiting Customer's Reply
								  </span>
                                                             @elseif(!empty($value->lastComment) && $value->lastComment->sender_id == Auth::user()->id)
                                                                 <span class="btn   btn-sm orange-col btn-xs ">       Awaiting Agent's Reply </span>
                                                             @else
                                                                 <span class="btn btn-danger btn-xs ">Open</span>
                                                             @endif
                                                         @else
                                                             <span class="btn btn-success btn-xs">  Closed </span>
                                                         @endif
                            </span>


                                                    </td>


                                                    <td>
                                                        @if($value->status == 0)
                                                            {{--<a href="{{route('support-ticket.close',$value->ticket_id)}}"
                                                               class="btn btn-danger btn-xs"
                                                               onclick="return confirm('Are you sure you want to Close this Ticket')">Close
                                                                Ticket</a>--}}

                                                            <a data-route="{{route('support-ticket.close',$value->ticket_id)}}"
                                                               href="javascript:void(0);"
                                                               class="btn btn-danger btn-xs btn-close-ticket">Close
                                                                Ticket</a>

                                                            <a href="{{route('support.ticket.comment',$value->ticket_id)}}"
                                                               class="btn btn-success btn-xs">Reply</a>

                                                        @else
                                                            <a href="{{route('support.ticket.comment',$value->ticket_id)}}"
                                                               class="btn btn-success btn-xs">Details</a>
                                                        @endif
                                                    </td>
                                                </tr>


                                            @endforeach
                                        @else
                                            <tr>
                                                <td>You have not any tickets yet...</td>
                                            </tr>
                                        @endif
                                        </tbody>


                                    </table>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="pannel-footer clearfix">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('front.ticket.modal-ticket-close')
@endsection
@section('css')
    <style>
        .cls-tr-ticket-detail {
            cursor: pointer;
        }

        .cancel-btn-width {
            width: 140px !important;
        }

        .text_siz {
            font-size: 16px !important;
            font-weight: bold !important;
            margin-top: 20px !important;
        }


    </style>
@endsection
@section('js')
    <script>
        function ticketDetail(url) {
            window.location.href = url;
        }

        $(document).on('click', '.btn-close-ticket', function () {
            var route = $(this).attr('data-route');
            // $('.btn-modal-close-ticket').attr('href', route);
            // $('#close-ticket-modal').modal({'backdrop': 'static'});
            Swal.fire({
                title: '',
                text: 'Are you sure, you want to close this ticket?',
                icon: '',
                showCancelButton: true,
                confirmButtonColor: '#f38022',
                cancelButtonColor: '#f38022',
                confirmButtonText: 'Close',
                customClass: {
                    cancelButton: 'cancel-btn-width',
                    confirmButton: 'cancel-btn-width',
                    content: 'text_siz',

                    htmlContainer: 'text_siz'
                }
            }).then((result) => {
                if (result.value) {
                    location.href = route;
                } else {

                }
            });
        });
    </script>


    <script>

    </script>
@endsection
