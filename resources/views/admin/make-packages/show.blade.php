@extends('adminlte::page')

@section('title', $title.'s')

@section('content_header')
    <h1>{{$title.'s'}}</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-body">
                            <h3>{{$draft->quot_name}} details</h3>
                            <div class="col-md-4">
                                <h4>Quotation Place Date</h4>
                                <p>{{date('F d, Y h:i a',strtotime($draft->created_at))}}</p>
                            </div>
                            {{-- <div class="col-md-4">
                                 <h4>Billing Address</h4>
                                 <p>
                                     {{$shipping->shipping_first_name.' '.$shipping->shipping_last_name}}<br/>
                                     {{$shipping->shipping_address}}<br/>
                                     {{$shipping->shipping_state}}, {{$shipping->shipping_zip}}<br/>
                                     {{$shipping->shipping_country}}<br/>
                                 </p>
                                 <p><b>Email Address :</b>
                                     <a href="mailto:{{$shipping->shipping_email}}">{{$shipping->shipping_email}}</a><br/>
                                 </p>
                                 <p><b>Phone :</b>
                                     <a href="tel:{{$shipping->shipping_phone}}">{{$shipping->shipping_phone}}</a></p>
                             </div>
                             <div class="col-md-4">
                                 <h4>Shipping Address</h4>
                                 <p>
                                     {{$shipping->shipping_first_name.' '.$shipping->shipping_last_name}}<br/>
                                     {{$shipping->shipping_address}}<br/>
                                     {{$shipping->shipping_state}}, {{$shipping->shipping_zip}}<br/>
                                     {{$shipping->shipping_country}}<br/>
                                 </p>
                                 <p><b>Email Address :</b>
                                     <a href="mailto:{{$shipping->shipping_email}}">{{$shipping->shipping_email}}</a><br/>
                                 </p>
                                 <p><b>Phone :</b>
                                     <a href="tel:{{$shipping->shipping_phone}}">{{$shipping->shipping_phone}}</a></p>
                             </div>--}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>Package</th>
                                        <th>room</th>
                                        <th>device</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $total=0;
                                    @endphp
                                    @foreach($draft->items as $key=>$pro)
                                        <tr>
                                            <td>{{$pro->package->name}}</td>
                                            <td>{{$pro->room->name}}</td>
                                            <td>{{$pro->device->name}}</td>
                                            <td>{{$pro->quantity}}</td>
                                            <td>{!! config('app.currencySymbol') !!}{{$pro->price}}</td>
                                            </td>
                                        </tr>
                                        @php
                                            $total+=($pro->quantity*$pro->price)
                                            @endphp
                                    @endforeach
                                    {{--

                                                                        <tr>
                                                                            <td colspan="3"></td>
                                                                            <td><b>Coupon:</b></td>
                                                                            <td>{!! config('app.currencySymbol') !!}{{$order->coupon_amt}}</td>
                                                                        </tr>--}}
                                    <tr>
                                        <td colspan="3"></td>
                                        <td><b>Total:</b></td>
                                        <td>{!! config('app.currencySymbol') !!}{{$total}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
