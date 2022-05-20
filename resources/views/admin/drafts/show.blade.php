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
                            <div class="col-md-4">
                                <h4>Kit Name</h4>
                                <p>{{$draft->title}}</p>
                            </div>

                            <div class="col-md-4">
                                <h4>Package Name</h4>
                                <p>
                                    @foreach($packages as $packge)
                                        {{$packge}}<br/>
                                    @endforeach


                                </p>
                            </div>
                            <div class="col-md-4">
                                {{--   <h4>Billing Address</h4>
                                   <p>
                                       {{$shipping->shipping_first_name.' '.$shipping->shipping_last_name}}<br/>
                                       {{$shipping->shipping_address}}<br/>
                                       {{$shipping->shipping_state}}, {{$shipping->shipping_zip}}<br/>
                                       {{$shipping->shipping_country}}<br/>
                                   </p>--}}
                                <p><b>User :</b>
                                    {{$draft->user->name}}</p>
                                <p><b>Email Address :</b>
                                    <a href="mailto:{{$draft->user->email}}">{{$draft->user->email}}</a><br/>
                                </p>

                            </div>
                            {{--  <div class="col-md-4">
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
                        <form action="{{route('drafts.saveItemQty')}}" method="post">
                            @csrf
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
                                            <th>Action</th>
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
                                                <td><input type="text" value="{{$pro->quantity}}"
                                                           name="qty[{{$pro->id}}]"/></td>
                                                <td>{!! config('app.currencySymbol') !!}{{round($pro->price*$pro->quantity,2)}}</td>
                                                <td>
                                                    <a onclick="return (confirm('Are you sure you want to delete ?'))?true:false"
                                                       href="{{route($routeName.'.deleteItem',$pro->id)}}">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </a></td>
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
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <form role="form" enctype="multipart/form-data" method="POST"
                      action="{{ route($routeName.'.update',$draft->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Expiry</label>
                                        <input type="date" class="form-control" id="datepicker" name="validity"
                                               value="{{$draft->validity}}"/>
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-right">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('js')

    <script>
        $('#datepicker').daterangepicker()
    </script>
@stop
