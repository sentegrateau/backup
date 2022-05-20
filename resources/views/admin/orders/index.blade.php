@extends('adminlte::page')

@section('title', 'Orders')

@section('content_header')
    <h1>Orders</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="row">
                     <div class="col-md-5">
                    <button style="margin-bottom: 10px" class="btn btn-primary delete_all"
                            data-url="{{route('orders.deleteAll')}}">Delete All Selected
                    </button>
                     </div>
                    <div class="col-md-7">
                    <div class="search-orders">
                        <form action="{{url('admin/orders')}}">
                            <div class="col-md-4">
                            <select name="orderstatus" class="form-control">
                                @foreach($order_status as $k => $os)
                                    <option value="{{$k}}" @if($orderstatus==$k) {{ "selected" }} @endif>{{$os}}</option>
                                @endforeach
                            </select>
                            </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="search" value="{{$search}}"
                                   placeholder="Search">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    </div>
                    </div>
                </div>
                </div>
                
                <!-- /.box-header -->
                <div class="box-body table-responsive">

                    <table class="table table-bordered">
                        <tr>
                            <th width="50px"><input type="checkbox" id="master"></th>
                            <th>Order ID
                             @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'id']) 
                            </th>
                            <th>Customer
                             @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'name']) 
                            </th>
                            <th>Order Amount
                             @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'amount']) 
                            </th>
                            <th>Ship Amount</th>
                            <th>Total Order Amount</th>
                            <th>Paid Amount
                            </th>                          
                            <th>Bank Image</th>
                            <th>Order Status
                             @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'order_status']) 
                            </th>
                             <th>Payment Type
                             @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'payment_type']) 
                            </th>
                            <th>Order Date
                             @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'created_at']) 
                            </th>
                            <th>Action</th>
                        </tr>
                        @foreach ($orders as $blg)
                            @php
                                $price=$blg->amount;
                                $gstCalc=($blg->amount*$settings['gst'])/100;
                                $gst=sprintf("%0.2f",$gstCalc);
                                $paid_amt=sprintf("%0.2f",$blg->paid_amt);

                            @endphp
                            <tr id="tr_{{$blg->id}}">
                                <td><input type="checkbox" class="sub_chk" data-id="{{$blg->id}}"></td>
                                <td>#{{$blg->id}}</td>
                                <td>{{optional($blg->user)->name}}</td>
                                <td style="text-align: right">{!! config('app.currencySymbol') !!}{{number_format(round($price,2),2)}}</td>
                                <td style="text-align: right">{!! config('app.currencySymbol') !!}{{number_format(round($blg->ship_amt,2),2)}}</td>
                                <td style="text-align: right">{!! config('app.currencySymbol') !!}{{number_format(round(($price+$blg->ship_amt),2),2)}}</td>
                                <td style="text-align: right">{!! config('app.currencySymbol') !!}{{number_format(round($paid_amt,2),2)}}</td>
                                <td>{!!  (!empty($blg->bank_img_url)?'<a download href="'.$blg->bank_img_url.'">Download</a>':'') !!}</td>
                                <td>{{$order_status[$blg->order_status]}}</td>
                                <td>{{ config('custom.paymenttype')[$blg->payment_type] }}</td>
                                <td>{{date('F d, Y h:i a',strtotime($blg->created_at))}}</td>
                                <td>
                                    <a href="{{route('orders.show',$blg->id)}}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                    <a onclick="return confirm('Are you sure?');"
                                       href="{{route('orders.delete',$blg->id)}}">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
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
