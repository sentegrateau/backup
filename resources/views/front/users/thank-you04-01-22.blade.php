<?php //echo "<pre>";print_R($orders);die;?>
<div class="thanku-wrapper">
    <div class="container">
        <?php  /*<div class="brand">
            <a href="<?php echo env('APP_URL');?>">
                <img src="{{config('app.asset_url')}}/front/images/pdf-logo.jpg" alt="Sentegrate">
            </a>
        </div>*/ ?>
        <div class="row  pt-4 pb-4">
            <h3 class="thanku-title">Thank you for ordering your Smart Home Kit. If you want to download the invoice
                <a href="<?php echo URL::to('/') . '/user/invoice-download/' . $order_id ?>"> click here
                </a>
                <a onclick="location.reload()" > Exit
                </a>

            </h3>
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="title" width="20%">
                            <img src="{{config('app.asset_url')}}/front/images/pdf-logo.jpg"
                                 style="width: 100%; max-width: 300px"/>
                        </td>

                        <td width="40%">
                            Sentegrate Pty Ltd<br/>
                            Address PO Box 808, Stanhope Gardens, NSW 2768 <br/>
                            Phone: 02 86071 808<br/>

                            Email: sales@sentegrate.com.au

                        </td>

                        <td width="40%">
                            1300-zwave <br/>
                            Phone No. : +12-45678945<br/>
                            +12-45678945<br/>
                            Email : Abc@sentegrate.com.au<br/>
                            Website : www.sentegrate.com.au
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
                            <td width="30%">
                                <h2 style="font-size:14px; margin:0px; padding:0px;">Bill To,</h2>
                                {{$billing->first_name}}  {{$billing->last_name}},<br/>
                                {{$billing->address}}<br/>
                                {{!empty($billing->city)?$billing->city:''}},{{$billing->state}}, {{$billing->country}} {{$billing->zip}}
                            </td>
                        @endif

                        <td width="70%">
                            <h2 style="font-size:14px; margin:0px; padding:0px;">Ship To,</h2>
                            @if(!empty($shipping->first_name))
                                {{$shipping->first_name}}  {{$shipping->last_name}},<br/>
                                {{$shipping->address}}<br/>
                                {{!empty($shipping->city)?$shipping->city:''}}, {{$shipping->state}}, {{$shipping->country}} {{$shipping->zip}}
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
                        <td>{{$order->order_number}}</td>
                        <td>{{$order->payment_type==1?'Paypal':'Bank'}}</td>
                        <td>{{$shippingText['text']}}</td>
                        {{--  <td></td>
                          <td>${{$discount}}</td>
                          <td>Prepaid</td>--}}
                    </tr>

                </table>

                <table cellpadding="0" cellspacing="0">


                    <tr class="heading">
                        <td>Item</td>
                        <td>Price</td>
                        <td>GST</td>
                        <td>Qty</td>
                        <!-- <td>Discount</td> -->
                        {{--   <td>Paid Amount</td>
                           <td>Remaining Amount</td>--}}
                        <td>Total</td>
                    </tr>
                    @php
                        $orderTotalPrice=0;
                        $orderGstTotalPrice=0;
                    @endphp


                    @foreach($orders as $ord)
                        @php
                            $orderTotalPrice+=$ord['productTotal'];
                            $orderGstTotalPrice+=$ord['gst'];
                        @endphp
                        <tr class="item">
                            <td>{{$ord['item']}}</td>
                            <td>${{$ord['price']}}</td>
                            <td>${{$ord['gst']}}</td>
                            <td>{{$ord['qty']}}</td>
                        <!-- <td>${{round($discount/$ord['count'],2)}}</td> -->
                            {{--  <td>${{$ord['paid_amt']}}</td>
                              <td>${{($ord['productTotal'] - $ord['paid_amt'])}}</td>--}}
                            <td>${{$ord['productTotal']}}</td>
                        </tr>
                    @endforeach

                  <tr class="total">

                        <td></td>
                        <td></td>
                        <td>{{--Discount:--}}</td>
                        <td>Total: {{--Actual: ${{round($orderTotalPrice,2)}}--}}</td>
                        <td>${{round($orderTotalPrice-$discount,2)}}</td>
                    </tr>
                    <tr class="total">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Shipping:</td>
                        <td>${{$shippingText['amt']}}</td>
                    </tr>
                    <tr class="total">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Discount:</td>
                        <td>${{!empty($discount)?$discount:0}}</td>
                    </tr>
                    <tr class="total">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>You Paid:</td>
                        <td>${{!empty($paid_amt)?$paid_amt:0}}</td>
                    </tr>

                </table>
            </div>
        </div>

    </div>
</div>
<style>
    .thanku-wrapper {
        padding: 20px 0px 40px;
    }

    .thanku-title {
        text-align: center;
        color: #f38022;
        margin-bottom: 20px;
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
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }

    .Temp {
        background: #f38022;
        color: #fff;
        font-weight: 500;
        width: 18%;
    . float: right;
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
