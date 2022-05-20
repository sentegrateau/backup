@extends('adminlte::page')

@section('title', $title.'s')

@section('content_header')
    <h1>{{$title.'s'}}</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    {{--Filter by Priority :
                    <a href="{{url('admin/support-ticket?priority=high')}}"><small class="btn btn-xs bg-red">Urgent</small></a>
                    <a href="{{url('admin/support-ticket?priority=medium')}}"><small class="btn btn-xs bg-orange">Need to be fixed soon</small></a>
                    <a href="{{url('admin/support-ticket?priority=low')}}"><small class="btn btn-xs bg-blue">Can wait</small></a>
                    <a href="{{url('admin/support-ticket')}}"><small class="btn btn-xs bg-green">All</small></a>--}}

                    <div class="shortby pull-left">

                        <strong>Filter by: </strong>

                        <a href="{{request()->fullUrlWithQuery(['status' => 'open'])}}">
                            <small class="btn btn-default  btn-sm {{request()->input('status')=='open'?'active':''}}">
                                Open
                            </small>
                        </a>
                        <a href="{{request()->fullUrlWithQuery(['status' => 'close'])}}">
                            <small class="btn btn-default  btn-sm {{request()->input('status')=='close'?'active':''}}">
                                Closed
                            </small>
                        </a>
                        <a href="{{url('admin/support-ticket')}}">
                            <small class="btn btn-default">Clear All</small>
                        </a>
                    </div>

                    <div class="search-ticket pull-right">
                        <form action="{{url('admin/support-ticket')}}">
                            @if(!empty(request()->input('status')))
                                <input type="hidden" value="{{request()->input('status')}}"
                                       name="status">
                            @endif
                            <input type="text" value="{{request()->input('title')}}" name="title"
                                   placeholder="search by title" class="form-control col-md-3">
                        </form>

                    </div>

                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Ticket Id</th>
                            <th>User</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($tickets))
                            @php $i = ($tickets->perPage() * ($tickets->currentPage() - 1)+1);@endphp
                            @foreach($tickets as $key=>$value)
                                <tr>
                                    <td>{{$value->ticket_id}}</td>
                                    <td>
                                        <a href="{{route('customers.show',$value->user->id)}}">{{$value->user->name}}</a>
                                    </td>
                                    <td>{{$value->category->name}}</td>
                                    <td>{{$value->title}}</td>
                                    <th>
                                        @php
                                            $text = 'Need to be fixed soon';
                                            $textcolor = 'orange';
                                            if($value->priority == 'low'){
                                                $text = 'Can wait';
                                                $textcolor = 'primary';
                                            }else if($value->priority == 'high'){
                                                $text = 'Urgent';
                                                $textcolor = 'red';
                                            }
                                        @endphp
                                        <small class="label bg-{{$textcolor}}">{{$text}}</small>
                                    </th>
                                    <td>{{$value->message}}</td>
                                    <td>
                                        @if($value->status==0)
                                            @if(!empty($value->lastComment) && $value->lastComment->sender_id == Auth::user()->id)
                                                <small class="label bg-yellow">Awaiting Customes's Reply</small>
                                            @elseif(!empty($value->lastComment) && $value->lastComment->sender_id != Auth::user()->id)
                                                <small class="label bg-orange">Awaiting Agent's Reply</small>
                                            @else
                                                <small class="label bg-red">Open</small>
                                            @endif
                                        @else
                                            <small class="label bg-green">Closed</small>
                                        @endif
                                    </td>
                                    <td>{{$value->created_at}}</td>
                                    <td>

                                        <div class="btn-group" id="status" data-toggle="buttons">
                                            <label class="btn btn-default btn-on btn-xs {{(!$value->status)?'active':''}}">
                                                <input type="radio" value="1" data-url="{{route('support-ticket.activeDeactivate',$value->id)}}" {{(!$value->status)?'checked':''}} class="radio-status">Open</label>
                                            <label class="btn btn-default btn-off btn-xs {{($value->status)?'active':''}}">
                                                <input type="radio" value="0" data-url="{{route('support-ticket.activeDeactivate',$value->id)}}" {{($value->status)?'checked':''}} class="radio-status">Close</label>
                                        </div>
                                        @php /*
                                        <a title="<?php echo (!$value->status) ? 'Close' : 'Open' ?>"
                                           href="{{route('support-ticket.activeDeactivate',$value->id)}}">
                                            <i class="fa  fa-check <?php echo ($value->status) ? 'deActive-color' : 'active-color' ?>"
                                               aria-hidden="true"></i>
                                        </a>
                                        */@endphp
                                        <a title="See Comments"
                                           href="{{route('support-ticket.comments',$value->ticket_id)}}">
                                            <i class="fa fa-comment" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{route('support-ticket.delete',$value->id)}}"
                                           onclick="return confirm('are you sure?');">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </a>
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
                <!-- /.box-body -->

                <div class="box-footer clearfix">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
@section('css')
    <style>
        .btn-default.btn-on.active {
            background-color: #5BB75B;
            color: white;
        }

        .btn-default.btn-off.active {
            background-color: #DA4F49;
            color: white;
        }
    </style>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {

            $('.radio-status').on('change', function (e) {
                location.href = $(this).attr('data-url');
            });


            $('#master').on('click', function (e) {
                if ($(this).is(':checked', true)) {
                    $(".sub_chk").prop('checked', true);
                } else {
                    $(".sub_chk").prop('checked', false);
                }
            });


            $('.delete_all').on('click', function (e) {


                var allVals = [];
                $(".sub_chk:checked").each(function () {
                    allVals.push($(this).attr('data-id'));
                });


                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {


                    var check = confirm("Are you sure you want to delete this row?");
                    if (check == true) {


                        var join_selected_values = allVals.join(",");

                        console.log("$(this).data('url')", $(this).data('url'))
                        $.ajax({
                            url: $(this).data('url'),
                            type: 'DELETE',
                            data: 'ids=' + join_selected_values + '&_token=' + $('input[name=_token]').val(),
                            success: function (data) {
                                if (data['success']) {
                                    $(".sub_chk:checked").each(function () {
                                        $(this).parents("tr").remove();
                                    });
                                    alert(data['success']);
                                } else if (data['error']) {
                                    alert(data['error']);
                                } else {
                                    alert('Whoops Something went wrong!!');
                                }
                            },
                            error: function (data) {
                                alert(data.responseText);
                            }
                        });


                        $.each(allVals, function (index, value) {
                            $('table tr').filter("[data-row-id='" + value + "']").remove();
                        });
                    }
                }
            });
            $(document).on('confirm', function (e) {
                var ele = e.target;
                e.preventDefault();


                $.ajax({
                    url: ele.href,
                    type: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        if (data['success']) {
                            $("#" + data['tr']).slideUp("slow");
                            alert(data['success']);
                        } else if (data['error']) {
                            alert(data['error']);
                        } else {
                            alert('Whoops Something went wrong!!');
                        }
                    },
                    error: function (data) {
                        alert(data.responseText);
                    }
                });


                return false;
            });
        });
    </script>
@stop

@stop
