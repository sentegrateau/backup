<table class="emailer" style="width: 600px; margin: 15px auto; color: rgb(0, 0, 0); border: 1px solid rgb(230, 230, 230);">
   <tbody>
      <tr class="text-center " style=" border-bottom:1px solid #dfdfdf; background: #f3f3f3;">
         <td align="center">
            <img src="{{url('public/front/images/logo/emailer.png')}}"
               style="width: 170px; margin: 0px auto; padding: 20px 0px;">
         </td>
      </tr>

      <tr style=" text-align:center;">
         <td style="padding:25px 0px 0px 0px; font-size: 14px;">Hey {{$user->first_name}},</td>
      </tr>
      <tr align="center">
         <td style="font-family: inherit;
            font-size: 23px;
			padding:10px 0px;
            color: #212127;
            line-height: 30px;
            font-weight: 500;">You are <span style="    color: #46c8f5;
    font-weight: 500;
    font-size: 28px;">welcome</span>
			</td>
      </tr>
      <tr style=" text-align:center;  display: block;   margin-bottom:30px;">
         <td style="padding:10px 0px 0px 0px;     display: block; width:100%;    font-weight: 500;font-size: 16px;">Your one time password for email verification is : <strong style=" color: #46c8f5;">{{$user->otp}}</strong></td>
      </tr>

   </tbody>
</table>
