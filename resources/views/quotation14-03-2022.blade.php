<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Quotation</title>

{{--    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 11pt;
            line-height: 24px;
			font-family: Arial;
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

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
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

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>--}}
</head>

<body>
    {{--<table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <img src="{{asset('/logo/logo.png')}}" style="width: 100%; max-width: 300px" />
                        </td>

                        <td>
                            Invoice #: {{$quotation->quotation_no}}<br />
                            Created: {{ $quotation->created_at->format('m/d/y') }}<br />
                            Due: {{$quotation->validity->format('d/m/y')}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            Sparksuite, Inc.<br />
                            12345 Sunny Road<br />
                            Sunnyville, CA 12345
                        </td>

                        <td>
                            Acme Corp.<br />
                            John Doe<br />
                            john@example.com
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td>Payment Method</td>

            <td>Check #</td>
        </tr>

        <tr class="details">
            <td></td>

            <td></td>
        </tr>

        <tr class="heading">
            <td>Item</td>
            <td>Quantity</td>
            <td>Price</td>
        </tr>
        @foreach($draft_items as $item)
        <tr class="item">
            <td>{{$item['device']['title']}}</td>
            <td>{{$item['quantity']}}</td>
            <td>${{$item['quantity'] * $item['price']}}</td>
        </tr>
            @if($loop->last)
                <tr class="last-item">
                    <td>{{$item['device']['title']}}</td>
                    <td>{{$item['quantity']}}</td>
                    <td>${{$item['quantity'] * $item['price']}}</td>
                </tr>
            @endif
        @endforeach
        <tr class="total">
            <td></td>

            <td>Total: ${{$quotation->total_amount}}</td>
        </tr>
    </table>--}}

<div style="border: 1px solid #000; padding: 25px;" >
    <table  cellpadding="0" cellspacing="0" style="width:100%;             font-family: sans-serif;" >
        <tr>
            <td style="width:50%;">
                <img width="250px"  src="{{config('app.asset_url')}}/front/images/pdf-logo.jpg">
            </td>
            <td >
                <span>PO Box 808, Stanhope Gardens, NSW 2768</span><br>
                Phone:02 86 071 808<br>
                Email: enquiries@sentegrate.com.au<br>
            </td>
        </tr>
        <tr>
            <td height="30"></td>
        </tr>
        <tr>
            <td style="    font-size: 22px;
         color: #5d5d5d;"><b>Quotation</b> </td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
        <tr style="    vertical-align: initial;">
            <td   style=" padding:20px 0px;">
                <p style=" margin: 0;    margin-bottom: 10px;">Quotation for:</p>
                <p style=" margin: 0;    margin-bottom: 10px;">{{$setting['name']}}</p>
                <p style=" margin: 0;    margin-bottom: 10px;">{{$setting['company']}}</p>
                <p style=" margin: 0;    margin-bottom: 10px;">{{$setting['email']}}</p>
            </td>
            <td   style=" padding:20px 0px;">
                <p style=" margin: 0;    margin-bottom: 10px;">Date:{{$quotation->created_at->format('m/d/y') }}</p>
                <p style=" margin: 0;    margin-bottom: 10px;">Quotation Ref:{{$quotation->quotation_no}}</p>
            </td>
        </tr>

        <tr>
            <td  style="width:100%; " colspan="2">Thanks for requesting a Sentegrate quote. The quoted price include Smart Home Kit, configuration and one year support.</td>

        </tr>
        <tr>
            <td height="20"></td>
        </tr>
        <tr>
            <td style="width:100%; " colspan="2">
                <table style="   border-collapse: collapse; width: 100%; border: 1px solid #000;">
                    <tr class="tabletitle" >
                        <td class="item" style=" padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">
                            <b>package Name and Description </b>
                        </td>
                        <td class="Hours" style="padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">
                            <b>Product Name</b>
                        </td>
                        <td class="Rate" style="padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">
                            <b> Product Quantity </b>
                        </td>
                        <td class="subtotal" style="padding:8px; border-bottom: 1px solid #000;">
                            <b> Total Price</b>
                        </td>
                    </tr>
                    @foreach($draft_items as $item)
                        <tr class="service">
                            <td style=" padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">{{$item['package']['name']}}</td>
                            <td style=" padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">{{$item['device']['name']}}</td>
                            <td style=" padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">{{$item['quantity']}}</td>
                            <td style="padding:8px; border-bottom: 1px solid #000;">${{$item['quantity'] * $item['price']}}</td>
                        </tr>

                    @endforeach



                    <tr class="service">
                        <td class="item" style=" padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000; text-align:right">
                            TOTAL PRICE
                        </td>
                        <td class="Hours" style="padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">

                        </td>
                        <td class="Rate" style="padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">

                        </td>
                        <td class="subtotal" style="padding:8px; border-bottom: 1px solid #000;">
                            ${{$quotation->total_amount}}
                        </td>
                    </tr>
                    <tr class="service">
                        <td class="item" style=" padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000; text-align:right">
                            GST
                        </td>
                        <td class="Hours" style="padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">

                        </td>
                        <td class="Rate" style="padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">

                        </td>
                        <td class="subtotal" style="padding:8px; border-bottom: 1px solid #000;">
                            ${{(($quotation->total_amount*10)/100) }}
                        </td>
                    </tr>
                    <tr class="service">
                        <td class="item" style=" padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000; text-align:right">
                            TOTAL INCLUDING GST
                        </td>
                        <td class="Hours" style="padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">

                        </td>
                        <td class="Rate" style="padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">

                        </td>
                        <td class="subtotal" style="padding:8px; border-bottom: 1px solid #000;">
                            ${{$quotation->total_amount+(($quotation->total_amount*10)/100)}}
                        </td>
                    </tr>


                    {{--<tr class="service">
                        <td class="item" style=" padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000; text-align:right">
                            GST
                        </td>
                        <td class="Hours" style="padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">

                        </td>
                        <td class="Rate" style="padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">

                        </td>
                        <td class="subtotal" style="padding:8px; border-bottom: 1px solid #000;">
                            $8,610.00
                        </td>
                    </tr>

                    <tr class="service">
                        <td class="item" style=" padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000; text-align:right">
                            TOTAL INCLUDING GST
                        </td>
                        <td class="Hours" style="padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">

                        </td>
                        <td class="Rate" style="padding:8px; border-bottom: 1px solid #000;  border-right: 1px solid #000;">

                        </td>
                        <td class="subtotal" style="padding:8px; border-bottom: 1px solid #000;">
                            $8,610.00
                        </td>
                    </tr>--}}





                </table>
            </td>
        </tr>
        <tr>
            <td height="20"></td>
        </tr>
        <tr>
            <td  style="width:100%; " colspan="2"> Quote Validity: Price valid for 15 days from quotation date</td>

        </tr>

        <tr>
            <td height="20"></td>
        </tr>
        <tr>
            <td  style="width:100%; " colspan="2">To accept this quote and place  your order  please <a href="https://sentegrate.com.au/quote">here</a> to open this quote and order</td>

        </tr>
    </table>
</body>
</html>
