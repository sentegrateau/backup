@extends('adminlte::page')

@section('title', 'Edit '.$title)

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit {{$title}}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST"
                      action="{{ route('customers.update',$customers->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group radio">
                                Role:
                                @foreach($role2 as $key=>$role)
                                    <label>
                                        <input type="radio" name="role2" placeholder="Role"
                                               value="{{ $key }}"
                                            {{($key==old('role2',$customers->role2))?'checked':''}}>{{$role}}
                                    </label>
                                @endforeach
                            </div>
                            <input type="checkbox" name="customer"
                                   value="true" {{($customers->customer)?'checked':''}}>
                            Customer {{$customers->cutomer}}
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Name"
                                       value="{{ (empty(old('name')))?$customers->name:old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email ID</label>
                                <input type="text" class="form-control" name="email" placeholder="Email ID"
                                       value="{{ (empty(old('email')))?$customers->email:old('email') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact</label>
                                <input type="text" class="form-control" name="contact" placeholder="Contact"
                                       value="{{ (empty(old('contact')))?$customers->contact:old('contact') }}"
                                       >
                            </div>
                        </div>

                        <div class="col-md-6 show-hide">
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" class="form-control" name="company" placeholder="Company"
                                       value="{{ (empty(old('company')))?$customers->company:old('company') }}"
                                >
                            </div>
                        </div>
                        <div class="col-md-6 show-hide">
                            <div class="form-group">
                                <label>ABN</label>
                                <input type="text" class="form-control" name="abn" placeholder="ABN"
                                       value="{{ (empty(old('abn')))?$customers->abn:old('abn') }}">
                            </div>
                        </div>
                        <br/>
                        <h3>Shipping Details</h3>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="ship_address[shipping_first_name]"
                                       placeholder="First Name"
                                       value="{{ (empty(old('ship_address[shipping_first_name]')))?(!empty($shippingAdd)?$shippingAdd->shipping_first_name:''):old('ship_address[shipping_first_name]') }}"
                                       >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="ship_address[shipping_last_name]"
                                       placeholder="Last Name"
                                       value="{{ (empty(old('ship_address[shipping_last_name]')))?(!empty($shippingAdd)?$shippingAdd->shipping_last_name:''):old('ship_address[shipping_last_name]') }}"
                                       >
                            </div>
                        </div>
                        {{--<div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="ship_address[shipping_email]"
                                       placeholder="you@example.com"
                                       value="{{ (empty(old('ship_address[shipping_email]')))?(!empty($shippingAdd)?$shippingAdd->shipping_email:''):old('ship_address[shipping_email]') }}"
                                       >
                            </div>
                        </div>--}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="number" class="form-control" name="ship_address[shipping_phone]"
                                       placeholder="Phone"
                                       value="{{ (empty(old('ship_address[shipping_phone]')))?(!empty($shippingAdd)?$shippingAdd->shipping_phone:''):old('ship_address[shipping_phone]') }}"
                                       >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control" name="ship_address[shipping_address]"
                                       placeholder="1234 Main St"
                                       value="{{ (empty(old('ship_address[shipping_address]')))?(!empty($shippingAdd)?$shippingAdd->shipping_address:''):old('ship_address[shipping_address]') }}"
                                       >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address 2 (Optional)</label>
                                <input type="text" class="form-control" name="ship_address[shipping_address_2]"
                                       placeholder="Apartment or suite"
                                       value="{{ (empty(old('ship_address[shipping_address_2]')))?(!empty($shippingAdd)?$shippingAdd->shipping_address_2:''):old('ship_address[shipping_address_2]') }}"
                                >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Country</label>
                                <input type="text" class="form-control" name="ship_address[shipping_country]"
                                       placeholder="Country"
                                       value="{{ (empty(old('ship_address[shipping_country]')))?(!empty($shippingAdd)?$shippingAdd->shipping_country:''):old('ship_address[shipping_country]') }}"
                                >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>State</label>
                                <input type="text" class="form-control" name="ship_address[shipping_state]"
                                       placeholder="State"
                                       value="{{ (empty(old('ship_address[shipping_state]')))?(!empty($shippingAdd)?$shippingAdd->shipping_state:''):old('ship_address[shipping_state]') }}"
                                >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" class="form-control" name="ship_address[shipping_city]"
                                       placeholder="City"
                                       value="{{ (empty(old('ship_address[shipping_city]')))?(!empty($shippingAdd)?$shippingAdd->shipping_city:''):old('ship_address[shipping_city]') }}"
                                >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Postal Code</label>
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
                                <input name="same_add" class="form-check-input cls-same-add-checkbox" type="checkbox" value="1"
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
                                    <label>First Name</label>
                                    <input type="text" class="form-control cls_bill_address" name="bill_address[billing_first_name]"
                                           placeholder="First Name"
                                           value="{{ (empty(old('bill_address[billing_first_name]')))?(!empty($shippingAdd)?$shippingAdd->billing_first_name:''):old('bill_address[billing_first_name]') }}"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control cls_bill_address" name="bill_address[billing_last_name]"
                                           placeholder="Last Name"
                                           value="{{ (empty(old('bill_address[billing_last_name]')))?(!empty($shippingAdd)?$shippingAdd->billing_last_name:''):old('bill_address[billing_last_name]') }}"
                                           required>
                                </div>
                            </div>
                            {{--<div class="col-md-6">
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
                                    <label>Phone</label>
                                    <input type="number" class="form-control cls_bill_address" name="bill_address[billing_phone]"
                                           placeholder="Phone"
                                           value="{{ (empty(old('bill_address[billing_phone]')))?(!empty($shippingAdd)?$shippingAdd->billing_phone:''):old('bill_address[billing_phone]') }}"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control cls_bill_address" name="bill_address[billing_address]"
                                           placeholder="1234 Main St"
                                           value="{{ (empty(old('bill_address[billing_address]')))?(!empty($shippingAdd)?$shippingAdd->billing_address:''):old('bill_address[billing_address]') }}"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address 2 (Optional)</label>
                                    <input type="text" class="form-control" name="bill_address[billing_address_2]"
                                           placeholder="Apartment or suite"
                                           value="{{ (empty(old('bill_address[billing_address_2]')))?(!empty($shippingAdd)?$shippingAdd->billing_address_2:''):old('bill_address[billing_address_2]') }}"
                                    >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Country</label>
                                    <input type="text" class="form-control cls_bill_address" name="bill_address[billing_country]"
                                           placeholder="Country"
                                           value="{{ (empty(old('bill_address[billing_country]')))?(!empty($shippingAdd)?$shippingAdd->billing_country:''):old('bill_address[billing_country]') }}"
                                    >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" class="form-control cls_bill_address" name="bill_address[billing_state]"
                                           placeholder="State"
                                           value="{{ (empty(old('bill_address[billing_state]')))?(!empty($shippingAdd)?$shippingAdd->billing_state:''):old('bill_address[billing_state]') }}"
                                    >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control cls_bill_address" name="bill_address[billing_city]"
                                           placeholder="City"
                                           value="{{ (empty(old('bill_address[billing_city]')))?(!empty($shippingAdd)?$shippingAdd->billing_city:''):old('bill_address[billing_city]') }}"
                                    >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" class="form-control cls_bill_address" name="bill_address[billing_zip]"
                                           placeholder="Postal Code"
                                           value="{{ (empty(old('bill_address[billing_zip]')))?(!empty($shippingAdd)?$shippingAdd->billing_zip:''):old('bill_address[billing_zip]') }}"
                                    >
                                </div>
                            </div>
                        </div>
                        {{--Billing Address End--}}


                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
@stop
@section('js')
    <script>
        $(function () {
            var role2 = "{{old('role2',$customers->role2)}}"
            $('.show-hide').hide()
            @if(!empty(old('role2',$customers->role2)) && old('role2',$customers->role2)!='owner')
            $('.show-hide').show()
            @endif
            $('input[type=radio]').change(function () {
                if ($(this).val() != 'owner') {
                    $('.show-hide').show()
                } else {
                    $('.show-hide').hide()
                }
            })
        })
    </script>
    <script>
        $(document).ready(function () {
            var hide = "{{!empty($shippingAdd)?($shippingAdd->same_add==1?true:false):true}}";
            if(hide == true){
                $('.div-billing-address').addClass('hidden');
                $('.cls_bill_address').attr('required',false);
            }else{
                $('.div-billing-address').removeClass('hidden');
                $('.cls_bill_address').attr('required',true);
            }

            $(document).on("change", ".cls-same-add-checkbox", function () {
                var c = this.checked ? $('.div-billing-address').addClass('hidden') : $('.div-billing-address').removeClass('hidden');
                var d = this.checked ? $('.cls_bill_address').attr('required',false) : $('.cls_bill_address').attr('required',true);
            });

            /*$(document).on("change", ".cls-same-add-checkbox", function () {

            });*/
        });
    </script>
@stop
