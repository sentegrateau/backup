@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Coupon</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <span class="pull-right">
                            <a href="{{ route('coupon.create') }}" class="btn btn-primary">Add</a>
                    </span>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">

                    <table class="table table-bordered">
                        <tr>
                            <th width="50px"><input type="checkbox" id="master"></th>
                            <th>Name</th>
                            <th>Coupon Code</th>
                            <th>coupon Message</th>
                            <th>Discount</th>
                            <th>Discount Type</th>
                            <th>Expiry Date</th>
                            <th>No Expiry Date</th>
                            <th>Limit</th>
                            <th>No Limit</th>
                            <th>For All Users</th>
                            <th>Coupon Disabled</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($coupon as $blg)
                            <tr id="tr_{{$blg->id}}">
                                <td><input type="checkbox" class="sub_chk" data-id="{{$blg->id}}"></td>
                                <td>{{$blg->name}}</td>
                                <td>{{$blg->coupon_code}}</td>
                                <td>{{$blg->coupon_message}}</td>
                                <td>{{$blg->discount}}</td>
                                <td>{{($blg->discount_type==1)?'Percentage':'Amount'}}</td>
                                <td>{{$blg->expiry_date}}</td>
                                <td>{{$blg->no_expiry?'True':'FALSE'}}</td>
                                <td>{{$blg->limit_users}}</td>
                                <td>{{($blg->is_unlimited)?'True':'FALSE'}}</td>
                                <td>{{($blg->is_all_users)?'True':'FALSE'}}</td>
                                <td>{{($blg->disable_coupon)?'True':'FALSE'}}</td>
                                <td>
                                    <a href="{{route('coupon.edit',$blg->id)}}">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{route('coupon.delete',$blg->id)}}">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer clearfix">
                    {{ $coupon->links() }}
                </div>
            </div>
        </div>
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
