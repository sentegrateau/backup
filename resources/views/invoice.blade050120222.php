<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Invoice</title>

    <style>
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

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }


    </style>
</head>

<body>
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
                Phone: 02 86071 808 <br/>
                Email:sales@sentegrate.com.au

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
                    {{!empty($billing->city)?$billing->city:''}},{{$billing->state}}
                    , {{$billing->country}} {{$billing->zip}}
                </td>
            @endif

            <td width="70%">
                <h2 style="font-size:14px; margin:0px; padding:0px;">Ship To,</h2>
                @if(!empty($shipping->first_name))
                    {{$shipping->first_name}}  {{$shipping->last_name}},<br/>
                    {{$shipping->address}}<br/>
                    {{!empty($shipping->city)?$shipping->city:''}}, {{$shipping->state}}
                    , {{$shipping->country}} {{$shipping->zip}}
                @endif
            </td>
        </tr>
    </table>


    <table cellpadding="0" cellspacing="0">

        <tr class="heading">
            <td>Your Order No.</td>
            <td>Payment Method</td>
            <td>Ship Via</td>
            <td>Ship Date</td>
            <td>Terms</td>
        </tr>


        <tr>
            <td>{{$order->order_number}}</td>
            <td>{{$order->payment_type==1?'Paypal':'Bank'}}</td>
            <td>{{$shippingText['text']}}</td>
            <td></td>
            <td>Prepaid</td>
        </tr>

    </table>

    <table cellpadding="0" cellspacing="0">


        <tr class="heading">
            <td>Item</td>
            <td>Price</td>
            <td>GST</td>
            <td>Qty</td>
            <td>Paid Amount</td>
            <td>Remaining Amount</td>
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
                <td>${{$ord['paid_amt']}}</td>
                <td>${{($ord['productTotal'] - $ord['paid_amt'])}}</td>
                <td>${{$ord['productTotal']}}</td>
            </tr>
        @endforeach
        <tr class="total">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total: ${{round($orderTotalPrice,2)}}</td>
        </tr>
    </table>
</div>
</body>
</html>
