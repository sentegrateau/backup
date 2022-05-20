<div id="contact-form-errors"></div>
<form id="contact-form-submit-home">
    <div class="form-group">

        <input type="text" placeholder="Name" name="name" class="form-control" >
    </div>
    <div class="form-group">

        <input type="email" placeholder="Email" name="email" class="form-control">
    </div>

    <div class="form-group">

        <input type="text" placeholder="Phone" name="phone" class="form-control" >
    </div>
    <div class="form-group">
                                <textarea name="message" type="text" class="form-control"
                                          placeholder="Message"></textarea>
    </div>
    <div class="form-group">
        <img id="captcha-code-updated" src="{{route('home.getCaptchaCode')}}"/>
        <input autocomplete="off" name="captcha_code" type="text" class="form-control"
               placeholder="Enter Captcha Code"></input>
    </div>
    <div class=" align-center">
        <button class="button" href="#">Submit</button>
    </div>
</form>
