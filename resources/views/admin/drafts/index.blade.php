@extends('adminlte::page')

@section('title', $title.'s')

@section('content_header')
    <h1>{{$title.'s'}}</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body table-responsive">

                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Total Amount</th>
                            <th>Expiry</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($drafts as $mt)
                            <tr id="tr_{{$mt->id}}">
                                <td>{{$mt->quot_name}}</td>
                                <td>{{optional($mt->user)->name}}</td>
                                <td>{{optional($mt->user)->email}}</td>
                                <td>{{$mt->total_amount}}</td>
                                <td>{{$mt->validity}}</td>
                                <td>
                                    <a href="{{route($routeName.'.show',$mt->id)}}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                 {{--   <a href="{{route($routeName.'.edit',$mt->id)}}">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>--}}
                                    <a onclick="return (confirm('Are you sure you want to delete ?'))?true:false"
                                       href="{{route($routeName.'.delete',$mt->id)}}">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer clearfix">
                    {{ $drafts->links() }}
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {


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
