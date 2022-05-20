<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
      <title>Quotation</title>
      {{--    
      <style>
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
         .price-table{text-align: right;}
      </style>
      --}}
   </head>
   <body>
      <div style="border: 1px solid #000; padding: 25px;font-family: sans-serif;" >
      <table  cellpadding="0" cellspacing="0" style="width:100%;font-family: sans-serif; font-size:13px;">
         <tbody>
            <tr>
                <td style="width:50%;">
                     
                <img width="215px"  src="{{config('app.asset_url')}}/front/images/pdf-logo.jpg">
                </td>
                <td >
                <span>Suite 207A, 30 Campbell Street, Blacktown, NSW 2148</span><br>
                Phone:02 86 071 808<br>
                Email: sales@sentegrate.com.au<br>
                </td>
            </tr>
            <tr>
                <td height="30"></td>
            </tr>
            <tr>
                <td style="font-size: 22px;
                color: #5d5d5d;"><b>Quotation</b> </td>
            </tr>
            <tr>
                <td height="10"></td>
            </tr>
            <tr style="vertical-align: initial;">
                <td   style=" padding:20px 0px;">
                <p style=" margin: 0;margin-bottom: 10px;">Quotation for: <!-- {{$user->name}} --></p>
                <p style=" margin: 0;margin-bottom: 10px;">{{$user->company}} <br>{{$user->email}} </br></p>
                </td>
                <td   style=" padding:20px 0px;">
                <p style=" margin: 0;margin-bottom: 10px;">Date: {{$quotation->created_at->format('d/m/Y') }}</p>
                <p style=" margin: 0;margin-bottom: 10px;">Quotation Ref: {{explode("-",$quotation->quot_name,2)[1]}}</p>
                </td>
            </tr>
            <tr>
                <td  style="width:100%; " colspan="4">Thanks for requesting a Sentegrate quote. The quoted price include Smart Home Kit, configuration and one year support.</td>
            </tr>
            <tr>
                <td height="20"></td>
            </tr>
            <tr style="border: 1px solid #000;">
                <td style="width:100%;" colspan="2"  style="border-bottom:solid 1px #000">
         
                    <tr class="tabletitle" >
                        <td class="item" style=" padding:8px; border: 1px solid #000; ">
                            <b>Package Name and Description </b>
                        </td>
                        <td class="Hours"style="padding:8px;border: 1px solid #000;width: 250px;">
                            <b>Product Name</b>
                        </td>
                        <td class="Rate" style="padding:8px;border: 1px solid #000;  ">
                            <b> Product Quantity </b>
                        </td>
                        <td class="subtotal" style="padding:8px; border: 1px solid #000; ">
                            <b> Total Price</b>
                        </td>
                    </tr>
                    {{--@foreach($draft_items as $item)                    
					<tr class="service">
                        <td style=" padding:8px;border: 1px solid #000; ">{{$packageName}}</td>
                        <td style=" padding:8px; border: 1px solid #000; text-align: left; float: none;">{{$item['device']['name']}}</td>
                        <td style=" padding:8px;border: 1px solid #000; text-align:center;">{{$item['quantity']}}</td>
                        <td style="padding:8px; border: 1px solid #000;text-align: right;">{{($price>0?'$'.number_format($price,2):'')}}</td>
                    </tr>
                    @endforeach--}}
					
					@foreach($new_item as $item)
					<tr class="service" style="vertical-align:middle;">
						<td style=" padding:8px;border: 1px solid #000; ">{{$item['package_name']}}</td>
						<td style=" padding:8px;border: 1px solid #000; vertical-align:top;">
							<table style="width:100%;">
							@foreach($item['items'] as $itemData)
							<tr style="text-align:left;"><td><span style="display:block; text-align:left;">{{$itemData['item_name']}}</span></td></tr>
							@endforeach
							</table>
						</td>
						<td style=" padding:8px;border: 1px solid #000; vertical-align:top; ">
							<table style="width:100%;">
							@foreach($item['items'] as $itemData)
							<tr style="text-align:center;"><td><span style="display:block; text-align:center;">{{$itemData['quantity']}}</span></td></tr>
							@endforeach
							</table>
						</td>
						<td style="padding:8px; border: 1px solid #000;text-align: right;">${{number_format($item['price'],2)}}</td>
						
					</tr>
					@endforeach
					
					
                    <tr class="service">
                        <td class="item" style=" padding:8px; border: 1px solid #000; text-align:right">
                            TOTAL PRICE
                        </td>
                        <td class="Hours" style="padding:8px; border: 1px solid #000;">
                        </td>
                        <td class="Rate" style="padding:8px; border: 1px solid #000;">
                        </td>
                        <td class="subtotal" style="padding:8px; border: 1px solid #000; text-align: right;">
                            ${{number_format($quotation->total_amount,2)}}
                        </td>
                    </tr>
                    <tr class="service">
                        <td class="item" style=" padding:8px; border: 1px solid #000;  text-align:right">
                            GST
                        </td>
                        <td class="Hours" style="padding:8px; border: 1px solid #000;">
                        </td>
                        <td class="Rate" style="padding:8px; border: 1px solid #000; ">
                        </td>
                        <td class="subtotal" style="padding:8px; border: 1px solid #000; text-align: right;">
                            ${{number_format((($quotation->total_amount*10)/100),2) }}
                        </td>
                    </tr>
                    <tr class="service">
                        <td class="item" style="padding:8px; border: 1px solid #000;text-align:right">
                            TOTAL INCLUDING GST
                        </td>
                        <td class="Hours" style="padding:8px; border: 1px solid #000;">
                        
                        </td>
                        <td class="Rate" style="padding:8px; border: 1px solid #000;">
                       
                        </td>
                        <td class="subtotal" style="padding:8px; border: 1px solid #000; text-align: right;">
                            ${{number_format($quotation->total_amount+(($quotation->total_amount*10)/100),2)}}
                        </td>
                    </tr>

               
                </td>
            </tr>
         </tbody>
      </table>
   
        
                <p style="margin-top:10px;font-size:13px;"> Quote Validity: Price valid for 15 days from quotation date</p>
                <p style="margin-top:10px;font-size:13px;">To accept this quote and place  your order  please <a href="https://sentegrate.com.au/quote">here</a> to open this quote and order</p>
           
    </div>
   </body>
</html>