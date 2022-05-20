
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<div style="max-width:550px; margin:0 auto; color: rgb(0, 0, 0); padding:30px 20px;background:#fff;border:1px solid #E0E0E0;border-radius:20px;      border-top: 5px solid #f38022 ;     border-bottom: 5px solid #f38022 ;">
   <table style="margin: 15px auto;font-family: 'Roboto', sans-serif;">
      <tbody>
         <tr class=" " style="border-bottom:1px solid #dfdfdf; text-align:left;">
            <td  style="text-align:left;">
             <!-- <img width="150px" src="https://deviraksha.com/public/front/images/logo.png">  -->
			     <img width="170px" src="{{config('app.asset_url')}}/front/images/pdf-logo.jpg">

            </td>
         </tr>
         <tr >
            <td style="
               font-size:14px;
               padding: 20px 0px 10px 0px;
               color: #000;
               line-height: 30px;
               border-bottom:solid 1px #e8e7ec;font-family: 'Roboto', sans-serif; text-align:left; margin-bottom: 15px;
               display: block;">
               Dear {{$user->name}},
            </td>
         </tr>
         <tr align="">
            <td align=""  >
               {{--<p style="font-weight:bold; line-height: 22px; color:#74787e;font-size:15px; font-family: 'Roboto', sans-serif; " >  Welcome and thanks for signing up!</p>--}}
               <p style="  line-height: 25px; font-size:14px;">  Thanks for registering on the Sentegrate website. To verify your email address please use the button below.</p>
            </td>
         </tr>
         <tr align="">
            <td style="  display:block; padding:10px 0px 0px 0px;">
               <a style="background-color:#f38022 ;
                  border: none;
                  color: rgba(255, 255, 255, 1);
                  padding: 17px 0px;
                  border-radius: 3px;
                  text-decoration: none;
                  font-weight: 700;
                  cursor: pointer;
                  margin: 0 auto;
                  padding: 12px 15px;
                  display: block;
                  text-align: center;
                  font-size: 16px;font-family: 'Roboto', sans-serif;" href="{{(isset($verifyUrl)?$verifyUrl:'')}}" >Verify</a>
            </td>
         </tr>
         <tr>
            <td style="   box-sizing: border-box;
               margin-bottom: 0;
               display: block;
               margin-top: 20px;
               margin-bottom: 0px;">
               <p  style="font-size:14px; text-align: left; font-family: 'Roboto',sans-serif;">
                   If you have any questions, we’re here for you. Feel free to contact us at support@sentegrate.com.au
               </p>
            </td>
         </tr>
         <tr>
            <td style="margin-bottom: 0px;
               display: block;font-family: 'Roboto', sans-serif;">
               <p style="font-family: Avenir,Helvetica,sans-serif;
                  box-sizing: border-box;
                  line-height: 1.5em;
                  color: #7e8890;
                  margin-top: 0;
                  text-align: left;
                  font-size: 12px;">
                  If you’re having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser: {{(isset($verifyUrl)?$verifyUrl:'')}}
               </p>
            </td>
         </tr>


         <tr>
            <td style="  border-bottom: solid 1px #e8e7ec;  margin: 20px 0px; display:block;"></td>
         </tr>

         <tr>
             <td style="font-family: 'Roboto', sans-serif;color:#7d7f8b;font-size:12px;padding:2px 0px;">Sincerely</td>
         </tr>
         <tr>
             <td style="font-family: 'Roboto', sans-serif;color:#7d7f8b;font-size:14px;font-weight:400;padding:2px 0px;">Sentegrate Team</td>
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
<!--@include('emails.layout.footer')-->
