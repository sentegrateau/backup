@extends('adminlte::page')

@section('title', 'Order #'.$order->order_id.' details')

@section('content_header')

@stop
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-body">
                            <h3>Order #{{$order->id}} details</h3>
                            <div class="col-md-4">
                                <h4>Order Place Date</h4>
                                <p>{{date('F d, Y h:i a',strtotime($order->created_at))}}</p>
                                <strong>Status : </strong>
                                <p>{{$order_status[$order->order_status]}}</p>

                                <strong>Customer : </strong>
                                <p><a href="{{route('customers.edit',$order->user->id)}}">{{$order->user->name}}</a></p>
								
								@if($quotation['category']!='6')
									<strong>{{$quotation['category']}}  </strong>
									<p>
										<a href="{{$title!='--'?env('APP_URL').'/admin/drafts/'.$order->draft_id:''}}">{{$quotation['title']}}</a>
									</p>							
									
								@endif
                                
                                <strong>Coupon Applied : </strong>
                                <p>{{!empty($order->coupon_code)?$order->coupon_code:''}}</p>
                            </div>

                            <div class="col-md-4">
                                <h4>Shipping Address</h4>
                                <p>
                                    {{$shipping->first_name.' '.$shipping->last_name}}<br/>
                                    {{$shipping->address}}<br/>
                                    {{$shipping->state}}, {{$shipping->zip}}<br/>
                                    {{$shipping->country}}<br/>
                                </p>
                                @if(!empty($shipping->email))
                                    <p><b>Email Address :</b>
                                        <a href="#">{{$shipping->email}}</a><br/>
                                    </p>
                                @endif
                                <strong>Payment Type : </strong>
                                <p> {{ config('custom.paymenttype')[$ordertype] }}</p>

                            </div>
                            <div class="col-md-4">
                                <h4>Billing Address</h4>
                                <p>
                                    {{$billing->first_name.' '.$billing->last_name}}<br/>
                                    {{$billing->address}}<br/>
                                    {{$billing->state}}, {{$billing->zip}}<br/>
                                    {{$billing->country}}<br/>
                                </p>
                                @if(!empty($billing->email))
                                    <p><b>Email Address :</b>
                                        <a href="mailto:{{$billing->email}}">{{$billing->email}}</a><br/>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.col -->
        <div class="col-md-4">
            @if($order->order_status!=6)
                <form role="form" enctype="multipart/form-data" method="POST"
                      action="{{ route('orders.update',$order->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Order Action</label>
                                        <select id="order-status-sel" class="form-control" name="order_status">
                                            @foreach($order_status as $key=>$status)
                                                @if($key != 6)
                                                    <option value="{{$key}}" {{($key==$order->order_status)?'selected':''}}>
                                                        {{$status}}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Remark</label>
                                        <input class="form-control" name="remark" />
                                    </div>

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-right">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Delivery Agent code place Here-->
                </form>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Items</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <form action="{{route('orders.saveOtherDetails',$order->id)}}" method="post">
                            @csrf
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th>Package</th>
                                    <th>room</th>
                                    <th>device</th>
                                    <th>Qty</th>
                                    <th  style="text-align: right">Price</th>
                                    <th style="text-align: right">Gst</th>
                                    <th  style="text-align: right">Discount</th>
                                    <th  style="text-align: right">Total</th>

                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total=0;
                                    $gstTotal = 0 ;
                                @endphp
                                @foreach($order->items as $key=>$pro)
                                    @php
                                        $price=optional($pro->device)->price *$pro->quantity;
                                        $gst=round((($price*$pro->quantity)*$settings['gst'])/100,2);
                                        $gstTotal +=$gst;

                                    @endphp
                                    <tr>
                                        <td>{{optional($pro->package)->name}}</td>
                                        <td>{{optional($pro->room)->name}}</td>
                                        <td>{{optional($pro->device)->name}}</td>
                                        <td>{{$pro->quantity}}</td>
                                        <td style="text-align: right">{!! config('app.currencySymbol') !!}{{number_format(round($price,2),2)}}</td>
                                        <td style="text-align: right">{!! config('app.currencySymbol') !!}{{number_format(round($gst,2),2)}}</td>
                                        <td style="text-align: right">{!! config('app.currencySymbol') !!}{{number_format(round($gst,2),2)}}</td>
                                        <td style="text-align: right">{!! config('app.currencySymbol') !!}{{(number_format(round($price+$gst,2),2)) }}</td>

                                    </tr>
                                    @php
                                        $total+=($pro->quantity*optional($pro->device)->price)+$gst
                                    @endphp
                                @endforeach
                                <tr>
                                    <td colspan="6"></td>

                                    <td><b>Coupon Discount:</b></td>

                                    <td style="text-align: right">${{number_format(round($order->discount,2),2)}}</td>


                                </tr>
                                <tr>
                                    <td colspan="6"></td>

                                    <td><b>Total GST:</b></td>

                                    <td style="text-align: right">${{number_format(round($gstTotal,2),2)}}</td>


                                </tr>
                                <tr>
                                    <td colspan="6"></td>

                                    <td><b>Shipping:</b></td>

                                    <td style="text-align: right">${{number_format(round($order->ship_amt,2),2)}}</td>

                                </tr>

                                <tr>
                                    <td colspan="6"></td>
                                    <td><b>Total:</b></td>
                                    <td style="text-align: right">
                                        ${{number_format(round(($total-$order->discount+$settings[$order->ship_type . '_ship_amt']),2),2)}}</td>

                                </tr>
                                </tbody>
                            </table>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('js')
    <script>
        $(function () {
            $('#order-status-sel').change(function () {
                var val = $(this).val();
                showTrack(val);
            });
        });
        showTrack({{$order->status}});

        function showTrack(val) {
            if (val == 2) {
                $('#track-input').show()
            } else {
                $('#track-input').hide()
            }
        }
    </script>
@stop
