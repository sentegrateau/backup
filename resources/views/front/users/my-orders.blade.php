@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="ticket-wrapper">
                    <div class="card">
                        <div class="ticket-heading"><h6>My Orders</h6></div>
                        <div class="card-body">
                            <div class="">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Order Number</th>
                                        <th scope="col">Order Date</th>
                                        <th scope="col">Order Status</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Upload Files</th>
                                        <th scope="col">Download Tax Invoice</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        @php

                                            $gst=round((($order->amount)*$settings['gst'])/100,2);
                                            $shipping_amt=$order->ship_amt;
                                            $orderAmt=sprintf("%0.2f",($order->amount+$gst+$shipping_amt));
                                        @endphp
                                        <tr>
                                            <td><a href="{{route('front.orderstatus',$order->id)}}">{{$order->id}}</a></td>
                                            <td>{{$order->created_at}}</td>
                                            <td>{{$order_status[$order->order_status]}}</td>
                                            <td>{!! config('app.currencySymbol') !!}{{$orderAmt}}</td>
                                            <td>
                                                @if( $order->payment_type==0)
                                                    @if(!$order->bank_img)
                                                        <input type="file" data-orderid="{{$order->id}}"
                                                               name="upload_file"
                                                               onChange="uploadFile(this)"/>
                                                    @else
                                                        {{$order->bank_img}}<a
                                                           href="{{route('user.bankImgRemove',$order->id)}}" class="btn btn-warning" style="padding: 5px;
    margin-left: 10px;
    line-height: 13px;
    font-size: 14px;">X</a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td><a href="{{route('user.invoiceDownload',$order->id)}}">Invoice
                                                    Download</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')



    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function uploadFile(ref) {
            var order_id = $(ref).data('orderid');
            var file_data = $(ref).prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('order_id', order_id);

            $.ajax({
                url: "{{route('user.uploadBankTransferFile')}}",
                type: "post",
                data: form_data,
                contentType: false,
                processData: false,
                success: function (data) {
                    location.reload();
                }
            });
        }

    </script>
@endsection

