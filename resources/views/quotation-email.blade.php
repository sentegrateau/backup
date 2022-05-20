<!--<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quotation</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<div id="inventory-invoice">
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col">
                        <a target="_blank" href="#">
                            <img width="250px"  src="{{config('app.asset_url')}}/front/images/logo.png">
                        </a>
                    </div>
                   {{-- <div class="col company-details">
                        <h2 class="name">
                            <a target="_blank" href="#">
                                Sentegrate
                            </a>
                        </h2>
                        <div>Loreum Ipsum</div>
                        <div>(123) 456-789</div>
                        <div>info@company.com</div>
                    </div>--}}
                </div>
            </header>
            <main>
                <div>
                    Thanx for requesting a sentegrate quote. the quoted price include smart home kit,
                    configuration and one year support.
                </div>
                {{--<div class="row contacts">
                    <div class="col invoice-to">
                        <div class="text-gray-light">INVOICE TO:</div>
                        <h2 class="to">Lorim Ipsu,</h2>
                        <div class="address">Address</div>
                        <div class="email"><a href="mailto:test@example.com">test@example.com</a></div>
                    </div>
                    <div class="col invoice-details">
                        <h1 class="invoice-id">INVOICE {{$quotation->quotation_no}}</h1>
                        <div class="date">Date of Invoice: {{ $quotation->created_at->format('m/d/y') }}</div>
                        <div class="date">Due Date: {{$quotation->validity->format('m/d/y')}}</div>
                    </div>
                </div>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th>SR NO.</th>
                        <th class="text-left">DESCRIPTION</th>
                        <th class="text-right">PRICE</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($draft_items as $item)
                        <tr>
                            <td class="no">{{ $loop->iteration }}</td>
                            <td class="text-left"><h3>{{$item['device']['title']}}</h3></td>
                            <td class="unit">${{$item->price}}</td>
                            <td class="tax">{{$item->quantity}}</td>
                            <td class="total">${{($item->price) * $item->quantity}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2">SUBTOTAL</td>
                        <td>${{$quotation->total_amount}}</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2">TAX 10%</td>
                        <td>{{$quotation->total_amount * 0.1}}</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2">GRAND TOTAL</td>
                        <td>{{$quotation->total_amount + ($quotation->total_amount * 0.1)}}</td>
                    </tr>
                    </tfoot>
                </table>
                <div class="thanks">Thank you!</div>--}}
                <br/>
                <div class="notices">
                    <div>NOTICE:</div>
                    <div class="notice">System Generated Invoice.</div>
                </div>
            </main>
            <footer>
                Invoice was generated on a computer and is valid without the signature and seal.
            </footer>
        </div>
        <div></div>
    </div>
</div>
</body>
</html>-->


<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<div style="max-width:550px; margin:0 auto; color: rgb(0, 0, 0); padding:30px 20px;background:#fff;border:1px solid #E0E0E0;border-radius:20px;      border-top: 5px solid #f38022 ;     border-bottom: 5px solid #f38022 ;">
   <table style="margin: 15px auto;font-family: 'Roboto', sans-serif;">
      <tbody>
         <tr class=" " style="border-bottom:1px solid #dfdfdf; text-align:left;">
            <td  style="text-align:left;">
           
			        <img width="150px"  src="{{config('app.asset_url')}}/front/images/pdf-logo.jpg">


            </td>
         </tr>
         <tr>
            <td style="
               font-size:18px;
               padding: 20px 0px 10px 0px;
               color: #000;
               line-height: 30px;
               border-bottom:solid 1px #e8e7ec;font-family: 'Roboto', sans-serif; text-align:left; margin-bottom: 15px;
               display: block;">
               Dear {{$user->name}}
            </td>
         </tr>
		  <tr align="">
            <td align=""  >


				 <p style="  line-height: 25px; font-size:16px;">Thank you for requesting Sentegrate pre-configured home automation kit quotation. Your quotation no {{$quotationNo}} is attached.</p>
				 <p style="  line-height: 25px; font-size:16px;">Should you have any questions, please do not hesitate to contact us. </p>

            </td>
         </tr>






		  <tr>
            <td style="  border-bottom: solid 1px #e8e7ec;  margin: 20px 0px; display:block;"></td>
         </tr>
		  <tr>
            <td style="font-family: 'Roboto', sans-serif;color:#7d7f8b;font-size:15px;padding:2px 0px;">Sincerely</td>
         </tr>
         <tr>
            <td style="font-family: 'Roboto', sans-serif;color:#7d7f8b;font-size:12px;font-weight:400;padding:2px 0px;">Sentegrate Team</td>
         </tr>
		  <tr>
        <td>

            <p style="margin-top:0px; margin-bottom: 5px;font-size:12px;color:#7d7f8b;">Phone:  <a href="tel:02-86071808">02-86071808 	</a></p>
            <p style="margin-top:5px;font-size:12px;color:#7d7f8b; margin-bottom:5px;">Online Customer Support:  <a href="#">sentegrate.com.au/contact</a></p>
        </td>
         </tr>
         <tr>
            <td style="font-family: 'Roboto', sans-serif;color:#f38022;font-size:12px;font-weight:400;padding:2px 0px;">Website: Sentegrate.com.au</td>
         </tr>

		   <tr>
            <td class="m_-7384016164109556678mcnTextContent" style="padding-top:0;padding-right:18px;padding-bottom:9px;padding-left:18px; color:#7d7f8b; margin-top: 22px;
               display: block;" valign="top">
               <div style="text-align:center"><em>Copyright © 2022 Sentegrate, All rights reserved.</em><br>
               </div>
            </td>
         </tr>

		 </tbody>
		 </table>
		 </div>




