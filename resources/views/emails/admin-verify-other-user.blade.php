

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<div style="max-width:550px; margin:0 auto; color: rgb(0, 0, 0); padding:30px 20px;background:#fff;border:1px solid #E0E0E0;border-radius:20px;      border-top: 5px solid #f38022 ;     border-bottom: 5px solid #f38022 ;">
   <table style="margin: 15px auto;font-family: 'Roboto', sans-serif;">
      <tbody>
         <tr class=" " style="border-bottom:1px solid #dfdfdf; text-align:left;">
            <td  style="text-align:left;">
              <!--<img width="150px" src="https://deviraksha.com/public/front/images/logo.png">   -->
			     <img width="150px" src="{{url('public/front/images/logo.png')}}">

            </td>
         </tr>
         <tr>
            <td style="
               font-size:18px;
               padding: 20px 0px 10px 0px;
               color: #000;
               line-height: 30px;
               font-weight: 700;border-bottom:solid 1px #e8e7ec;font-family: 'Roboto', sans-serif; text-align:left; margin-bottom: 15px;
               display: block;">
                Hi Admin, A new user has registered on the website with the following details:
            </td>
         </tr>


		  <tr>
            <td>



                <table style="border-collapse: collapse; font-weight:300; width:100%" class="head-bg">
                    <tr  style="-webkit-print-color-adjust: exact;color: #737581;">
                        <td style="    padding: 8px 15px;     border-right: solid 1px #d2d2d2;
                        border-bottom: solid 1px #d2d2d2;
                        border-left: solid 1px #d2d2d2;
                        border-top: 1px solid #d2d2d2;">
                            <b>Name </b>
                        </td>
                        <td style="    padding: 8px 15px;     border-right: solid 1px #d2d2d2;
                        border-bottom: solid 1px #d2d2d2;
                        border-left: solid 1px #d2d2d2;
                        border-top: 1px solid #d2d2d2;">
                            <b>Email</b>
                        </td>
                        <td style="    padding: 8px 15px;     border-right: solid 1px #d2d2d2;
                        border-bottom: solid 1px #d2d2d2;
                        border-left: solid 1px #d2d2d2;
                        border-top: 1px solid #d2d2d2;">
                            <b> Company </b>
                        </td>
                        <td style="    padding: 8px 15px;text-align: right;     border-right: solid 1px #d2d2d2;
                        border-bottom: solid 1px #d2d2d2;
                        border-left: solid 1px #d2d2d2;
                        border-top: 1px solid #d2d2d2;">
                            <b> ABN</b>
                        </td>
                    </tr>



                        <tr style=" -webkit-print-color-adjust: exact;border-bottom:solid 1px #d2d2d2;font-size:14px;color: #737581;">
                            <td style="padding:14px; border-right:solid 1px #d2d2d2;border-bottom:solid 1px #d2d2d2;  border-left:solid 1px #d2d2d2;">{{$user->name}}</td>
                            <td  style="padding:14px;border-right:solid 1px #d2d2d2; border-bottom:solid 1px #d2d2d2;">{{$user->email}}</td>
                            <td  style="padding:14px; border-right:solid 1px #d2d2d2; border-bottom:solid 1px #d2d2d2;">{{$user->company}}</td>
                            <td  style="padding:14px 20px;text-align: right;border-right:solid 1px #d2d2d2; border-bottom:solid 1px #d2d2d2;">{{$user->abn}}</td>
                        </tr>


                </table>
            </td>
        </tr>

		         <tr align="">
            <td align=""  >

               <p style="  line-height: 25px; font-size:14px;">  Click here to verify user</p>
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
                  font-size: 16px;font-family: 'Roboto', sans-serif;     margin-bottom: 20px;" href="{{(isset($verifyUrl)?$verifyUrl:'')}}" >Verify</a>
            </td>
         </tr>


		  <tr>
            <td style="    margin-bottom: 0px;
               display: block;font-family: 'Roboto', sans-serif;">
               <p style="     font-family: Avenir,Helvetica,sans-serif;
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
            <td align="center"
               style="font-family:'Open Sans',sans-serif!important;font-weight:400!important;padding-top: 12px!important;color:#7e8890!important;font-size:11px!important;letter-spacing:.05em!important"
               valign="top"><em>If you need any further help then please contact our "Help Center"</em>
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
