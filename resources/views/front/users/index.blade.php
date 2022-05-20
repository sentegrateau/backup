<?php
//  if(isset($shippingAdd->shipping_country)){
//      echo $shippingAdd->shipping_country;die;
// }else{
//     echo "";die;
// }
?>
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="ticket-wrapper">
                    <div class="card">
                        <div class="ticket-heading"><h6>Your Profile</h6></div>
                        <div class="card-body">
                            <div class="">
                                <form class="" role="form" method="POST" action="{{route('user.profile.store')}}">
                                    @csrf

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" placeholder="Email"
                                                   value="{{ (empty(old('email')))?Auth::user()->email:old('email') }}"
                                                   readonly
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" placeholder="Name"
                                                   value="{{ (empty(old('name')))?Auth::user()->name:old('name') }}"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contact <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="contact"
                                                   placeholder="0XXXXXXXXX" id="contact-number-validate"
                                                   value="{{ (empty(old('contact')))?Auth::user()->contact:old('contact') }}"
                                                   autocomplete="off"
                                                   required>
                                            <span id="show-contact-error"></span>
                                        </div>
                                    </div>
                                    @if(Auth::user()->role2 != 'owner')
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Company <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="company"
                                                       placeholder="Company"
                                                       value="{{ (empty(old('company')))?Auth::user()->company:old('company') }}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>ABN <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="abn" placeholder="ABN"
                                                       pattern="[0-9]{11}"
                                                       title="ABN number should be greater than or equal to 11 digit"
                                                       value="{{ (empty(old('abn')))?Auth::user()->abn:old('abn') }}"
                                                       minlength="11" required>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Change Password</label>

                                            <div class="input-group" id="show_hide_password">
                                                <input id="password" type="password"
                                                       placeholder="Password"
                                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#\$%\^&\*]).{6,}"
                                                       title="Must contain at least one number and one uppercase and lowercase and special letter, and at least 6 or more characters"
                                                       name="password">
                                                <div class="input-group-addon">
                                                    <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                                </div>
                                            </div>


                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <div class="input-group" id="confirm_show_hide_password">
                                                <input id="password-confirm"
                                                       placeholder="Confirm Password"
                                                       type="password"
                                                       class="form-control"
                                                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#\$%\^&\*]).{6,}"
                                                       title="Must contain at least one number and one uppercase and lowercase and special letter, and at least 6 or more characters"
                                                       name="password_confirmation">
                                                <div class="input-group-addon">
                                                    <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="form-group text-center">
                                        <button type="submit" class="btn btn-ticket">
                                            <i class="fa fa-btn fa-ticket"></i> Save
                                        </button>
                                    </div>
                                </form>--}}

                                    {{--Shipping Address Start--}}
                                    {{--<form class="" role="form" method="POST"
                                          action="{{route('user.profile.address.store')}}">--}}
                                    <div class="col-md-12">
                                        <h5>Shipping Address</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   name="ship_address[shipping_first_name]"
                                                   placeholder="First Name"
                                                   value="{{ (empty(old('ship_address[shipping_first_name]')))?(!empty($shippingAdd)?$shippingAdd->shipping_first_name:''):old('ship_address[shipping_first_name]') }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   name="ship_address[shipping_last_name]"
                                                   placeholder="Last Name"
                                                   value="{{ (empty(old('ship_address[shipping_last_name]')))?(!empty($shippingAdd)?$shippingAdd->shipping_last_name:''):old('ship_address[shipping_last_name]') }}"
                                                   required>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                         <div class="form-group">
                                             <label>Email</label>
                                             <input type="email" class="form-control" name="ship_address[shipping_email]"
                                                    placeholder="you@example.com"
                                                    value="{{ (empty(old('ship_address[shipping_email]')))?(!empty($shippingAdd)?$shippingAdd->shipping_email:''):old('ship_address[shipping_email]') }}"
                                                    required>
                                         </div>
                                     </div>--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   name="ship_address[shipping_phone]"
                                                   placeholder="Phone"
                                                   autocomplete="off"
                                                   value="{{ (empty(old('ship_address[shipping_phone]')))?(!empty($shippingAdd)?$shippingAdd->shipping_phone:''):old('ship_address[shipping_phone]') }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Address <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   name="ship_address[shipping_address]"
                                                   placeholder="1234 Main St"
                                                   value="{{ (empty(old('ship_address[shipping_address]')))?(!empty($shippingAdd)?$shippingAdd->shipping_address:''):old('ship_address[shipping_address]') }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Address 2 (Optional)</label>
                                            <input type="text" class="form-control"
                                                   name="ship_address[shipping_address_2]"
                                                   placeholder="Apartment or suite"
                                                   value="{{ (empty(old('ship_address[shipping_address_2]')))?(!empty($shippingAdd)?$shippingAdd->shipping_address_2:''):old('ship_address[shipping_address_2]') }}"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Country <span class="text-danger">*</span></label>
                                            <select class="form-control cls_shipping_country"
                                                    name="ship_address[shipping_country]">
                                                <?php foreach($countries as $country){
                                                if((isset($shippingAdd->shipping_country) && $country['name'] == $shippingAdd->shipping_country) || $country['name'] == 'Australia'){?>
                                                <option value="{{$country['name']}}"
                                                        selected>{{$country['name']}}</option>
                                                <?php }else{ ?>
                                                <option value="{{$country['name']}}">{{$country['name']}}</option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        <!-- <input type="text" class="form-control" name="ship_address[shipping_country]"
                                                   placeholder="Country"
                                                   value="{{ (empty(old('ship_address[shipping_country]')))?(!empty($shippingAdd)?$shippingAdd->shipping_country:''):old('ship_address[shipping_country]') }}"
                                            > -->
                                        </div>
                                    </div>
                                    @php
                                        $selectedShipCountry = (empty(old('ship_address[shipping_country]')))?(!empty($shippingAdd)?$shippingAdd->shipping_country:''):old('ship_address[shipping_country]');
                                    @endphp
                                    <div class="div-main-ship div-main-state">
                                        <div class="col-md-6 state-input-div"
                                             style="display:{{$selectedShipCountry=='Australia'?'none':'block'}}">
                                            <div class="form-group" id="stateLabel">
                                                <label>State <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control"
                                                       name="ship_address[shipping_state]"
                                                       placeholder="State"
                                                       value="{{ (empty(old('ship_address[shipping_state]')))?(!empty($shippingAdd)?$shippingAdd->shipping_state:''):old('ship_address[shipping_state]') }}"
                                                >
                                            </div>
                                        </div>


                                        <div class="col-md-6 state-select-div"
                                             style="display:{{$selectedShipCountry=='Australia'?'block':'none'}} ;">
                                            <div class="form-group" id="stateSelectLabel">
                                                <label>State <span class="text-danger">*</span></label>
                                                <select class="state-select form-control">
                                                    <option value="">--Select State--</option>
                                                    <option value="New South Wales" {{!empty($shippingAdd) && $shippingAdd->shipping_state=='New South Wales'?'selected':''}}>New South Wales</option>
                                                    <option value="Queensland" {{!empty($shippingAdd) && $shippingAdd->billing_state=='Queensland'?'selected':''}}>Queensland</option>
                                                    <option value="South Australia" {{!empty($shippingAdd) && $shippingAdd->shipping_state=='South Australia'?'selected':''}}>South Australia</option>
                                                    <option value="Tasmania" {{!empty($shippingAdd) && $shippingAdd->shipping_state=='Tasmania'?'selected':''}}>Tasmania</option>
                                                    <option value="Victoria" {{!empty($shippingAdd) && $shippingAdd->shipping_state=='Victoria'?'selected':''}}>Victoria</option>
                                                    <option value="Western Australia" {{!empty($shippingAdd) && $shippingAdd->shipping_state=='Western Australia'?'selected':''}}>Western Australia</option>
                                                    <option value="Australian Capital Territory" {{!empty($shippingAdd) && $shippingAdd->shipping_state=='Australian Capital Territory'?'selected':''}}>Australian Capital
                                                        Territory
                                                    </option>
                                                    <option value="Northern Territory" {{!empty($shippingAdd) && $shippingAdd->shipping_state=='Northern Territory'?'selected':''}}>Northern Territory</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>City <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="ship_address[shipping_city]"
                                                   placeholder="City"
                                                   value="{{ (empty(old('ship_address[shipping_city]')))?(!empty($shippingAdd)?$shippingAdd->shipping_city:''):old('ship_address[shipping_city]') }}"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Postal Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="ship_address[shipping_zip]"
                                                   placeholder="Postal Code"
                                                   value="{{ (empty(old('ship_address[shipping_zip]')))?(!empty($shippingAdd)?$shippingAdd->shipping_zip:''):old('ship_address[shipping_zip]') }}"
                                            >
                                        </div>
                                    </div>
                                    {{--Shipping Address End--}}
                                    {{--Same Address Checkbox Start--}}
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input name="same_add" class="form-check-input cls-same-add-checkbox"
                                                   type="checkbox" value="1"
                                                   id="flexCheckDisabled" {{!empty($shippingAdd)?($shippingAdd->same_add==1?'checked':''):'checked'}}/>
                                            <label class="form-check-label" for="flexCheckDisabled"> Billing address is
                                                the same as my shipping address </label>
                                        </div>
                                    </div>
                                    {{--Same Address Checkbox End--}}
                                    {{--Billing Address Start--}}
                                    <div class="div-billing-address {{!empty($shippingAdd)?($shippingAdd->same_add==1?'hidden':''):'hidden'}}">
                                        <div class="col-md-12">
                                            <h5>Billing Address</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>First Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control cls_bill_address"
                                                       name="bill_address[billing_first_name]"
                                                       placeholder="First Name"
                                                       value="{{ (empty(old('bill_address[billing_first_name]')))?(!empty($shippingAdd)?$shippingAdd->billing_first_name:''):old('bill_address[billing_first_name]') }}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Last Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control cls_bill_address"
                                                       name="bill_address[billing_last_name]"
                                                       placeholder="Last Name"
                                                       value="{{ (empty(old('bill_address[billing_last_name]')))?(!empty($shippingAdd)?$shippingAdd->billing_last_name:''):old('bill_address[billing_last_name]') }}"
                                                       required>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-6">
                                             <div class="form-group">
                                                 <label>Email</label>
                                                 <input type="email" class="form-control cls_bill_address" name="bill_address[billing_email]"
                                                        placeholder="you@example.com"
                                                        value="{{ (empty(old('bill_address[billing_email]')))?(!empty($shippingAdd)?$shippingAdd->billing_email:''):old('bill_address[billing_email]') }}"
                                                        required>
                                             </div>
                                         </div>--}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control cls_bill_address"
                                                       name="bill_address[billing_phone]"
                                                       placeholder="Phone"
                                                       autocomplete="off"
                                                       value="{{ (empty(old('bill_address[billing_phone]')))?(!empty($shippingAdd)?$shippingAdd->billing_phone:''):old('bill_address[billing_phone]') }}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control cls_bill_address"
                                                       name="bill_address[billing_address]"
                                                       placeholder="1234 Main St"
                                                       value="{{ (empty(old('bill_address[billing_address]')))?(!empty($shippingAdd)?$shippingAdd->billing_address:''):old('bill_address[billing_address]') }}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address 2 (Optional)</label>
                                                <input type="text" class="form-control"
                                                       name="bill_address[billing_address_2]"
                                                       placeholder="Apartment or suite"
                                                       value="{{ (empty(old('bill_address[billing_address_2]')))?(!empty($shippingAdd)?$shippingAdd->billing_address_2:''):old('bill_address[billing_address_2]') }}"
                                                >
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Select Country <span class="text-danger">*</span></label>
                                                <select class="form-control cls_billing_country"
                                                        name="bill_address[billing_country]">
                                                    <?php foreach($countries as $country){
                                                    if((isset($shippingAdd->billing_country) && $country['name'] == $shippingAdd->billing_country) || $country['name'] == 'Australia'){?>
                                                    <option value="{{$country['name']}}"
                                                            selected>{{$country['name']}}</option>
                                                    <?php }else{ ?>
                                                    <option value="{{$country['name']}}">{{$country['name']}}</option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>

                                        @php
                                            $selectedBillCountry = (empty(old('bill_address[billing_country]')))?(!empty($shippingAdd)?$shippingAdd->billing_country:''):old('bill_address[billing_country]');
                                        @endphp
                                        <div class="div-main-bill div-main-state">
                                            <div class="col-md-6 state-input-div"
                                                 style="display:{{$selectedBillCountry=='Australia'?'none':'block'}} ;">
                                                <div class="form-group">
                                                    <label>State <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control cls_bill_address"
                                                           name="bill_address[billing_state]"
                                                           placeholder="State"
                                                           value="{{ (empty(old('bill_address[billing_state]')))?(!empty($shippingAdd)?$shippingAdd->billing_state:''):old('bill_address[billing_state]') }}"
                                                    >
                                                </div>
                                            </div>

                                            <div class="col-md-6 state-select-div"
                                                 style="display:{{$selectedBillCountry=='Australia'?'block':'none'}} ;">
                                                <div class="form-group" id="stateSelectLabel">
                                                    <label>State <span class="text-danger">*</span></label>
                                                    <select class="state-select form-control">
                                                        <option value="">--Select State--</option>
                                                        <option value="New South Wales" {{!empty($shippingAdd) && $shippingAdd->billing_state=='New South Wales'?'selected':''}}>New South Wales</option>
                                                        <option value="Queensland" {{!empty($shippingAdd) && $shippingAdd->billing_state=='Queensland'?'selected':''}}>Queensland</option>
                                                        <option value="South Australia" {{!empty($shippingAdd) && $shippingAdd->billing_state=='South Australia'?'selected':''}}>South Australia</option>
                                                        <option value="Tasmania" {{!empty($shippingAdd) && $shippingAdd->billing_state=='Tasmania'?'selected':''}}>Tasmania</option>
                                                        <option value="Victoria" {{!empty($shippingAdd) && $shippingAdd->billing_state=='Victoria'?'selected':''}}>Victoria</option>
                                                        <option value="Western Australia" {{!empty($shippingAdd) && $shippingAdd->billing_state=='Western Australia'?'selected':''}}>Western Australia</option>
                                                        <option value="Australian Capital Territory" {{!empty($shippingAdd) && $shippingAdd->billing_state=='Australian Capital Territory'?'selected':''}}>Australian Capital
                                                            Territory
                                                        </option>
                                                        <option value="Northern Territory" {{!empty($shippingAdd) && $shippingAdd->billing_state=='Northern Territory'?'selected':''}}>Northern Territory</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control cls_bill_address"
                                                       name="bill_address[billing_city]"
                                                       placeholder="City"
                                                       value="{{ (empty(old('bill_address[billing_city]')))?(!empty($shippingAdd)?$shippingAdd->billing_city:''):old('bill_address[billing_city]') }}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Postal Code <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control cls_bill_address"
                                                       name="bill_address[billing_zip]"
                                                       placeholder="Postal Code"
                                                       value="{{ (empty(old('bill_address[billing_zip]')))?(!empty($shippingAdd)?$shippingAdd->billing_zip:''):old('bill_address[billing_zip]') }}"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    {{--Billing Address End--}}
                                    <div class="form-group text-center col-md-12">
                                        <button type="submit" class="btn btn-ticket">
                                            <i class="fa fa-btn fa-ticket"></i> Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        var states;
        $(document).ready(function () {
            $(document).on('change', '.cls_shipping_country', function (e) {
                var countryName = $(this).val();
                if (countryName == 'Australia') {
                    $('.div-main-ship').find('.state-input-div').hide();
                    $('.div-main-ship').find('.state-select-div').show();
                    $('.div-main-ship').find('.state-input-div input').val('');
                } else {
                    $('.div-main-ship').find('.state-input-div').show();
                    $('.div-main-ship').find('.state-select-div').hide();
                }
            });

            $(document).on('change', '.cls_billing_country', function (e) {
                var countryName = $(this).val();
                if (countryName == 'Australia') {
                    $('.div-main-bill').find('.state-input-div').hide();
                    $('.div-main-bill').find('.state-select-div').show();
                    $('.div-main-bill').find('.state-input-div input').val('');
                } else {
                    $('.div-main-bill').find('.state-input-div').show();
                    $('.div-main-bill').find('.state-select-div').hide();
                }
            });

            $(document).on('change', '.state-select', function (e) {
                var nameState = $(this).val();
                $(this).parents('.div-main-state').find('.state-input-div input').val(nameState);
            });


            $("#show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("fa-eye-slash");
                    $('#show_hide_password i').removeClass("fa-eye");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("fa-eye-slash");
                    $('#show_hide_password i').addClass("fa-eye");
                }
            });

            $("#confirm_show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#confirm_show_hide_password input').attr("type") == "text") {
                    $('#confirm_show_hide_password input').attr('type', 'password');
                    $('#confirm_show_hide_password i').addClass("fa-eye-slash");
                    $('#confirm_show_hide_password i').removeClass("fa-eye");
                } else if ($('#confirm_show_hide_password input').attr("type") == "password") {
                    $('#confirm_show_hide_password input').attr('type', 'text');
                    $('#confirm_show_hide_password i').removeClass("fa-eye-slash");
                    $('#confirm_show_hide_password i').addClass("fa-eye");
                }
            });


            $('#CountryHere').select2();
            $('#stateHere').select2();
            var hide = "{{!empty($shippingAdd)?($shippingAdd->same_add==1?true:false):true}}";

            if (hide == true) {
                $('.div-billing-address').addClass('hidden');
                $('.cls_bill_address').attr('required', false);
            } else {
                $('.div-billing-address').removeClass('hidden');
                $('.cls_bill_address').attr('required', true);
            }

            $(document).on("change", ".cls-same-add-checkbox", function () {
                var c = this.checked ? $('.div-billing-address').addClass('hidden') : $('.div-billing-address').removeClass('hidden');
                var d = this.checked ? $('.cls_bill_address').attr('required', false) : $('.cls_bill_address').attr('required', true);
            });
            let CountryName = '<?php if (isset($shippingAdd->shipping_country)) {
                echo $shippingAdd->shipping_country;
            } else {
                echo "";
            }?>'
            if (CountryName != "") {
                console.log(CountryName);
                //getStatesFunction(CountryName);
            } else {
                //getStatesFunction('Australia');
            }
            /*$(document).on("change", ".cls-same-add-checkbox", function () {

            });*/
        });
        $(document).on('change', '#CountryHere', function (e) {
            var selected = $(this).find('option:selected');
            let Country = selected[0].innerHTML;

            //getStatesFunction(Country);
        });

        /*function getStatesFunction(Country) {
            let ApiUrl = '<?php echo URL::to('/');?>/api/getStatesList/' + Country;
            $.ajax({
                url: ApiUrl,
                success: function (result) {
                    states = result;
                    $('#stateHere').remove();
                    // stateLabel
                    //
                    let AppendFirst = "<label>State</label><select id='stateHere' class='form-control' name='ship_address[shipping_state]'><option value='' disabled>select State</option>";
                    $("#stateLabel").append(AppendFirst);
                    for (let i of states) {
                        // var lielem = $("#nitsmenu li").text();
                        // var element = $(lielem).text();
                        let prefillState = "{{isset($shippingAdd->shipping_state)?$shippingAdd->shipping_state:''}}";
                        let Append = '';
                        console.log(prefillState);
                        if (i.name == prefillState) {
                            console.log(12, i.name);
                            Append = "<option value='" + i.name + "' selected>" + i.name + "</option>";
                        } else {
                            console.log(34, i.name);
                            Append = "<option value='" + i.name + "'>" + i.name + "</option>";
                        }
                        $("#stateHere").append(Append);
                        //    $("#nitspopupmenu").append("<div class='form-group'><div class='pagesmenu selected'><span><i class ='fa fa-bars'></i>" + lielem + "");
                    }
                    $("#stateHere").append('</select>');
                    $("#stateHere").select2();
                }
            });
        }*/
    </script>
@stop
