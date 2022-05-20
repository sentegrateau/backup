<?php //echo "<pre>";print_R($orders);die;?>
<div class="thanku-wrapper">
    <div class="container">
        <?php  /*<div class="brand">
            <a href="<?php echo env('APP_URL');?>">
                <img src="{{config('app.asset_url')}}/front/images/pdf-logo.jpg" alt="Sentegrate">
            </a>
        </div>*/ ?>
        <div class="row  pt-4 pb-4">
            <h3 class="thanku-title">Thank you for ordering your Smart Home Kit. </h3>
            <center><h2>Please do not use the browser back button. Instead use the exit button to return to the home page</h2></center>
            @if($order->payment_type == 0)
                <center><h2>Log in to sentegrate.com.au to upload the bank receipt and view your order
                        history
                    </h2></center>
            @else
                <center>
                    <h2>
                        Log in to sentegrate.com.au to view your order history
                    </h2>
                </center>
            @endif
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="title" width="70%">
                            <img src="{{config('app.asset_url')}}/front/images/pdf-logo.jpg"
                            />
                        </td>

                        <td width="30%">
                            Sentegrate Pty Ltd <br/>
                            PO Box 808, Stanhope Gardens,<br/> NSW 2768 <br/>
                            Phone: 02 86071 808<br/>
                            Email: <a href="mailto:sales@sentegrate.com.au">sales@sentegrate.com.au</a>

                        </td>


                    </tr>
                </table>


                <table cellpadding="0" cellspacing="0">

                    @php
                        $billing=json_decode($order->billing_info);
                        $shipping=json_decode($order->shipping_info);

                    @endphp
                    <tr class="information">
                        @if(!empty($billing->first_name))
                            <td width="20%">
                                <h2 style="font-size:14px; margin:0px; padding:0px;">Bill To:</h2>
                                {{$billing->first_name}}  {{$billing->last_name}},<br/>
                                {{$billing->address}},<br/>
                                {{!empty($billing->city)?$billing->city:''}},<br>
                                {{$billing->state}} {{$billing->zip}}, {{$billing->country}}
                            </td>
                        @endif

                        <td width="70%">
                            <h2 style="font-size:14px; margin:0px; padding:0px;">Ship To:</h2>
                            @if(!empty($shipping->first_name))
                                {{$shipping->first_name}}  {{$shipping->last_name}},<br/>
                                {{$shipping->address}},<br/>
                                {{!empty($shipping->city)?$shipping->city:''}},<br>
                                {{$shipping->state}} {{$shipping->zip}}, {{$shipping->country}}
                            @endif
                        </td>
                    </tr>
                </table>


                <table cellpadding="0" cellspacing="0">

                    <tr class="heading">
                        <td>Your Order No.</td>
                        <td>Payment Method</td>
                        <td>Ship Via</td>
                        {{--  <td>Ship Date</td>
                          <td>Total Discount</td>
                          <td>Terms</td>--}}
                    </tr>


                    <tr>
                        <td>{{$order->id}}</td>
                        <td>{{$order->payment_type==1?'Paypal':'Bank'}}</td>
                        <td>{{$shippingText['text']}}</td>
                        {{--  <td></td>
                          <td>${{$discount}}</td>
                          <td>Prepaid</td>--}}
                    </tr>

                </table>
                <div style="max-height: 300px;overflow: auto;">
                    <table cellpadding="0" cellspacing="0" class="table table-bordered">

                        <thead class="orange-head text-center">
                        <tr class="heading">
                            <th>Package</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Package Price</th>
                            <th>GST</th>

                            <!-- <td>Discount</td> -->
                            {{--   <td>Paid Amount</td>
                               <td>Remaining Amount</td>--}}
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
                                <td>
                                    <ul>
                                        @foreach($items[$ord['package_id']] as $keys=>$values)
                                            <li>{{$values['qty']}}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="align-right">${{number_format($ord['price'],2)}}</td>
                                <td class="align-right">${{number_format($ord['gst'],2)}}</td>

                            <!-- <td>${{round($discount/$ord['count'],2)}}</td> -->
                                {{--  <td>${{$ord['paid_amt']}}</td>
                                  <td>${{($ord['productTotal'] - $ord['paid_amt'])}}</td>--}}
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
                            <td class="align-right">${{!empty($discount)?number_format($discount,2):'0.00'}}</td>
                        </tr>

                        <tr class="total">
                            <td>Total After Discount</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="align-right">
                                ${{number_format(($orderTotalPrice)-(!empty($discount)?$discount:0),2)}}</td>
                        </tr>
                        <tr class="total">
                            <td>Shipping Cost</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="align-right">${{number_format($shippingText['amt'],2)}}</td>
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

                            <td class="align-right">${{number_format(round($totalLastSixty,2),2)}}</td>
                        </tr>
                        <tr class="total">
                            <td>GST Included</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="align-right">
                                ${{number_format(round(($totalLastSixty/11),2),2)}}</td>
                        </tr>

                        <tr class="total">
                            <td>Payment due on installation</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="align-right">
                                ${{ number_format(round((($totalLast*40)/100),2),2) }}</td>
                        </tr>

                    </table>
                </div>

                <div class="btn-group-footer">
                    <a href="<?php echo URL::to('/') . '/user/invoice-download/' . $order_id ?>"
                       class="btn btn-warning btn-1">Download Tax Invoice
                    </a>
                    <a href="{{$url}}"  class="btn btn-warning btn-1 pull-right"> Exit
                    </a>
                </div>


            </div>
        </div>

    </div>
</div>
<style>
    .invoice-box .table-bordered {
        border: solid 1px #ddd;
        border-top: transparent;
    }

    .invoice-box .table-bordered .total td {
        border-top: solid 1px #ddd;
        border-right: solid 1px #ddd;
    }

    .btn-group-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 15px;
    }

    .btn-group-footer .btn {
        background: #f38022;
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        padding: 6px 20px;
        border-radius: 7px;
        cursor: pointer;
    }

    .orange-head th:first-child {
        border: none;
    }

    .orange-head th {
        text-align: center;
    }

    .invoice-box .table-bordered td ul {
        padding: 0px 0px 0px 10px;
    }

    .invoice-box .table-bordered td ul li {
        list-style: none;
    }

    .thanku-wrapper {
        padding: 20px 0px 40px;
    }

    .thanku-title {
        text-align: center;
        color: #f38022;
        margin-bottom: 20px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        font-size: 16px;
    }

    .mini-heading {
        text-align: center;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        font-size: 14px;
    }

    .thanku-wrapper center h2 {
        text-align: center;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        font-size: 16px;
    }

    .align-right {
        text-align: right !important;
    }

    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }

    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }

    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }

    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }

    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }

    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
        font-size: 14px;
        text-align: left;
    }

    .invoice-box table tr td {

        font-size: 14px;
        text-align: left;
    }

    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
        border-right: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(2) {

        font-weight: bold;
    }

    .orange-head {
        background: #f38022;
        color: #fff;
    }

    .orange-head th {
        padding: 10px;
        border-left: solid 1px #fff
    }

    .Temp {
        background: #f38022;
        color: #fff;
        font-weight: 500;
        width: 18%;
        float: right;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>



    /*    window.onbeforeunload = onBack;
        function onBack(evt)
       {
           if (evt == undefined)
               evt = window.event;
           if (	(evt.clientX < 0) ||
               (evt.clientY < 0) ||
               (evt.clientX > document.body.clientWidth) ||
               (evt.clientY > document.body.clientHeight)
               )
           {
               alert('Unload from browser button press');
               return "You clicked some browser button? Do you want to move away from this page?";
           }
           return undefined;
       }*/
    // window.location.replace('{{route('quote')}}');
    /*    window.onhashchange = function() {
            //blah blah blah
        alert('dddd')
        }



            if (window.history && window.history.pushState) {

                window.history.pushState('forward', null, window.location.href+'#forward');

                $(window).on('popstate', function() {
                    alert('Back button was pressed.');
                });

            }*/
    if (window.event.clientX < 40 && window.event.clientY < 0) {
        alert("Browser back button is clicked...");
    } else {
        alert("Browser refresh button is clicked...");
    }

    window.onbeforeunload = function () {
        alert();
        return "Your work will be lost.";
    };

</script>
