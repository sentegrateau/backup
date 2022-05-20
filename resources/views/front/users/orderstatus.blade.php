@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="ticket-wrapper">
                    <div class="card">
                        <div class="ticket-heading">
                            <h6>Order Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="order-status-table">
                                <div class="order-head">
                                    <div class="order-item"><span class="strong">Order Number</span><span
                                                class="strong">{{$order->id}}</span></div>
                                    <div class="order-item"><span>Order Date</span><span
                                                class=""> {{date_format($order->created_at,'d/m/Y H:i:s')}} </span></div>
                                    <div class="order-item"><span>Order Status</span><span
                                                class=""> {{$order_status[$order->order_status]}} </span></div>
                                    <div class="order-item"><span>Last Update</span><span
                                                class=""> {{date_format($order->updated_at,'d/m/Y H:i:s' )}} </span></div>
                                    <div class="order-item"><span class="">Shipment</span><span
                                                class="strong"> {{$shippingText['text']}} </span></div>
                                    <div class="order-item"><span class="">Payment Method</span><span
                                                class="strong"> {{$order->payment_type==1?'Paypal':'Bank'}} </span>
                                    </div>
                                   <!--  <div class="order-item"><span>Shopper's note</span><span class="">  </span></div>
                                    <div class="order-item"><span class="strong">Total</span><span
                                                class="strong"> - </span></div> -->
                                </div>
                                <div class="order-body">
                                    @php
                                        $billing=json_decode($order->billing_info);
                                        $shipping=json_decode($order->shipping_info);
                                    @endphp
                                    <div class="ship-to-item">
                                        <h6 class="strong">Billing details:</h6>
                                        @if(!empty($billing->first_name))
                                            <div class="order-item"><span>E-Mail</span><span class="">-</span></div>
<!--                                             <div class="order-item"><span>Title</span><span class=""> - </span></div>
 -->                                            <div class="order-item"><span>First Name</span><span
                                                        class=""> {{$billing->first_name}} </span></div>
                                            <div class="order-item"><span>Last Name</span><span
                                                        class=""> {{$billing->last_name}} </span></div>
                                            <div class="order-item"><span>Company Name</span><span class=""> - </span>
                                            </div>
                                            <div class="order-item"><span>ABN/ACN</span><span
                                                        class=""> - </span></div>
                                            <div class="order-item"><span>Address 1</span><span
                                                        class="">  {{$billing->address}} </span></div>

                                            <div class="order-item"><span>City</span><span
                                                        class=""> {{!empty($billing->city)?$billing->city:''}} </span>
                                            </div>
                                            
                                            <div class="order-item"><span>State</span><span
                                                        class=""> {{$billing->state}}  </span></div>


                                            <div class="order-item"><span>Zip/Postal Code</span><span
                                                        class=""> {{$billing->zip}} </span></div>
                                           
                                            <div class="order-item"><span>Country</span><span
                                                        class=""> {{$billing->country}} </span></div>
                                            
                                            <div class="order-item"><span>Mobile Number</span><span class=""> - </span>
                                            </div>
                                            <!-- <div class="order-item"><span>Work Website</span><span class="">www.sentegrate.com.au</span>
                                            </div> -->
                                        @endif
                                    </div>
                                    <div class="ship-to-item">
                                        <h6 class="strong">Ship To:</h6>
                                        @if(!empty($shipping->first_name))
                                            <!-- <div class="order-item"><span>Address Nickname</span><span class="">-</span>
                                            </div> -->
<!--                                             <div class="order-item"><span>Title</span><span class=""> - </span></div>
 -->                                            <div class="order-item"><span>First Name</span><span
                                                        class=""> {{$shipping->first_name}} </span></div>
                                            <div class="order-item"><span>Last Name</span><span
                                                        class=""> {{$shipping->last_name}} </span></div>
                                            <div class="order-item"><span>Company Name</span><span class=""> - </span>
                                            </div>
                                            <div class="order-item"><span>Address 1</span><span
                                                        class=""> {{$shipping->address}} </span></div>
                                             <div class="order-item"><span>City</span><span
                                                        class=""> {{!empty($shipping->city)?$shipping->city:''}} </span>
                                            </div>
                                            
                                            <div class="order-item"><span>State</span><span
                                                        class=""> {{$shipping->state}}  </span></div>

                                            <div class="order-item"><span>Zip/Postal Code</span><span
                                                        class="">  {{$shipping->zip}}</span></div>
                                            
                                            <div class="order-item"><span>Country</span><span
                                                        class=""> {{$shipping->country}} </span></div>
                                            
                                            <div class="order-item"><span>Mobile Number</span><span class=""> - </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="table-order-status">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab1">Order Items</a></li>
                                    <li><a data-toggle="tab" href="#tab2">Recent Status Changes</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="tab1" class="tab-pane fade in active">
                                        <div class="table-responsive">
                                            <table cellpadding="0" cellspacing="0" class="table table-bordered">
                                                <thead class="orange-head text-center">
                                                <tr class="heading">
                                                    <th>Package</th>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Package Price</th>
                                                    <th>GST</th>
                                                    <!-- <td>Discount</td> -->
                                                    {{--
                                                    <td>Paid Amount</td>
                                                    <td>Remaining Amount</td>
                                                    --}}
                                                    <th>Total</th>
                                                </tr>
                                                </thead>
                                                @php
                                                    $orderTotalPrice=0;
                                                    $orderGstTotalPrice=0;
                                                @endphp
                                                @foreach($orders as $ord)
                                                    @php
                                                        $packageTotal=round($ord['price'],2)+round($ord['gst'],2);
                                                        $orderTotalPrice += $packageTotal;
                                                        // $orderGstTotalPrice+=round($ord['gst'],2);
                                                    @endphp
                                                    <tr class="item">
                                                        <td>{{$ord['item']}}</td>
                                                        <td>
                                                            <ul>
                                                                @foreach($items[$ord['package_id']] as $keys=>$values)
                                                                    <li>{{$values['title']}}</li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                        <td class="align-center">
                                                            <ul>
                                                                @foreach($items[$ord['package_id']] as $keys=>$values)
                                                                    <li>{{$values['qty']}}</li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                        <td class="align-right">${{number_format($ord['price'],2)}}</td>
                                                        <td class="align-right">${{number_format($ord['gst'],2)}}</td>
                                                    <!-- <td>${{round($discount/$ord['count'],2)}}</td> -->
                                                        {{--
                                                        <td>${{$ord['paid_amt']}}</td>
                                                        <td>${{($ord['productTotal'] - $ord['paid_amt'])}}</td>
                                                        --}}
                                                        <td class="align-right">${{number_format($packageTotal,2)}}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="total">
                                                    <td>Total {{--Actual: ${{round($orderTotalPrice,2)}}--}}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{--Discount:--}}</td>
                                                    <td></td>
                                                    <td class="align-right">${{number_format($orderTotalPrice,2)}}</td>
                                                </tr>
                                                <tr class="total">
                                                    <td>Discount ($)</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="align-right">
                                                        ${{!empty($discount)?number_format($discount,2):'0.00'}}</td>
                                                </tr>
                                                <tr class="total">
                                                    <td>Total After Discount</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="align-right">
                                                        ${{number_format(($orderTotalPrice)-(!empty($discount)?$discount:0),2)}}
                                                    </td>
                                                </tr>
                                                <tr class="total">
                                                    <td>Shipping Cost</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="align-right">
                                                        ${{number_format($shippingText['amt'],2)}}</td>
                                                </tr>
                                                @php
                                                    $totalLast = round($orderTotalPrice-$discount+$shippingText['amt'],2);
                                                    $totalLastSixty = ($totalLast*60)/100;
                                                @endphp
                                                <tr class="total">
                                                    <td>This Payment</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="align-right">
                                                        ${{number_format(round($totalLastSixty,2),2)}}</td>
                                                </tr>
                                                <tr class="total">
                                                    <td>GST Included</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="align-right">
                                                        ${{number_format(round(($totalLastSixty/11),2),2)}}
                                                    </td>
                                                </tr>
                                                <tr class="total">
                                                    <td>Payment due on installation</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="align-right">
                                                        ${{ number_format(round((($totalLast*40)/100),2),2) }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="tab2" class="tab-pane fade">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Sr.No.</th>
                                                <th>Status</th>
                                                <th>Remark</th>
                                                <th>Changes Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @if(!empty($recentOrderStatus))
                                                @foreach($recentOrderStatus as $key=>$val)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$order_status[$val['status']]}}</td>
                                                    <td>{{!empty($val['remarks'])?$val['remarks']:'---'}}</td>
                                                    <td>{{$val['created_at']}}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="3">No recent changes found.</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection