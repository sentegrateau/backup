jQuery(function () {

     $('#contact-number-validate').change(function () {
         var chk = telephoneCheck()
         if (!chk) {
             var html = `
                 <span class="invalid-feedback text-danger" role="alert">
                                         <strong>Please provide your landline or mobile number along with area code</strong>
                                     </span>
                                     `
             $('#show-contact-error').html(html);
         } else {
             $('#show-contact-error').html('')
             //$('#contact-form-submit').submit();
         }
     });

 });

 function telephoneCheck() {
     var str = $('#contact-number-validate').val();
     var regex = /^(0{1})?([23478]{1})(\-|\s)?((\(\d{3}\))|\d{3})(\-|\s)?(\d{3})(\-|\s)?(\d{2})/g;
     return regex.test(str);
 }
