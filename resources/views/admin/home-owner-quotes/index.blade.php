@extends('adminlte::page')

@section('title', 'Home Owner Quotes')

@section('content_header')
    <h1>Home Owner Quotes</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <!-- /.box-header -->
                <div class="box-body table-responsive">

                    <table class="table table-bordered">
                        <tr>
                            <th>Customer</th>
                            <th>Room</th>
                            <th>Home Owner</th>
                            <th>Quote Date</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($orders as $blg)
                            <tr id="tr_{{$blg->id}}">
                                <td>{{optional($blg->user)->name}}</td>
                                <td>{{optional($blg->room)->name}}</td>
                                <td>{{optional($blg->homeOwner)->name}}</td>
                                <td>{{date('F d, Y h:i a',strtotime($blg->created_at))}}</td>
                                <td>
                                    <a href="{{route('home-owner-quotes.show',$blg->user_id)}}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer clearfix">
                    {{ $orders->links() }}
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
