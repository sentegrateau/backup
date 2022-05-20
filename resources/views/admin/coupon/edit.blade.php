@extends('adminlte::page')

@section('title', 'coupon Edit')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit coupon</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST"
                      action="{{ route('coupon.update',$coupon->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="box-body">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" value="{{$coupon->name}}" name="name"
                                   placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label>Coupon Message</label>
                            <input type="text" class="form-control" value="{{$coupon->coupon_message}}" name="coupon_message"
                                   placeholder="Coupon Message">
                        </div>


                        <div class="form-group">
                            <label>coupon code</label>
                            <input type="text" class="form-control" value="{{$coupon->coupon_code}}" name="coupon_code"
                                   placeholder="coupon code">
                        </div>
                        <div class="form-group">
                            <label class="radio">Discount Type: </label>
                            <label class="radio-inline"><input type="radio" name="discount_type" value=1 {{$coupon->discount_type==1?'checked':''}}>Percentage</label>
                            <label class="radio-inline"><input type="radio" name="discount_type" value=2 {{$coupon->discount_type==1?'':'checked'}}>Amount</label>
                            <!-- <input type="text" class="form-control" value="{{$coupon->discount}}" name="discount"
                                   placeholder="discount %"> -->
                        </div>
                        <div class="form-group">
                            <label>Discount</label>
                            <input type="text" class="form-control" value="{{$coupon->discount}}" name="discount"
                                   placeholder="discount %">
                        </div>
                        <div class="form-group">
                            <label>No Expiry Date</label>

                            <input id="switch" class="switch-chk-input1" name="no_expiry"
                                   type="checkbox" {{($coupon->no_expiry)?'checked':''}}>
                            <div class="max_tickets1">
                                <div class="form-group">
                                    <label>Expiry Date</label>
                                    <input type="date" class="form-control" value="{{$coupon->expiry_date}}" name="expiry_date">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Is Unlimited</label>

                            <input id="switch" class="switch-chk-input2" name="is_unlimited"
                                   type="checkbox" {{($coupon->is_unlimited)?'checked':''}}>
                            <div class="max_tickets2">
                                <div class="form-group">
                                    <label>limit</label>
                                    <input type="text" class="form-control" value="{{$coupon->limit_users}}" name="Limit">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Send To All Users</label>

                            <input id="switch" class="switch-chk-input3" name="is_all_users"
                                   type="checkbox" {{($coupon->is_all_users)?'checked':''}}>
                            <div  class="max_tickets3">
                                    <label>Users List</label>
                                    <select class="selectUsers form-control" search="search" name="users[]" multiple="multiple" style="width:100%;height: 100px;">
                                        <?php foreach($userList as $user){ $userId = $user->id;?>
                                        <option value="{{$userId}}" {{(is_array($there_users) && in_array($user->id,$there_users))?'selected':''}} >{{$user->name}}</option>
                                        <?php }?>
                                    </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label>Disable The Coupon</label>

                            <input id="switch" class="switch-chk-input" name="disable_coupon"
                                   type="checkbox" {{($coupon->disable_coupon)?'checked':''}}>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a  href="{{env('APP_URL').'/admin/coupon'}}"><button type="button" class="btn btn-primary" style="float:right">Cancel</button></a>
                    </div>
                </form>

            </div>
            <!-- /.box -->
        </div>
    </div>
@section('js')
<!-- <script  src="{{env('APP_URL')}}/resources/views/admin/coupon/js/index.js"></script> -->
	<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
  //tinymce.init({ selector:'textarea', plugins: 'code',toolbar: 'code',relative_urls : 0,
    //      remove_script_host : 0 });
          $('div.max_tickets').hide();
          $('input.switch-chk-input1').change(function(){
            if (!$(this).is(':checked')){
                $(this).next('div.max_tickets1').show();
            }else{
                $('div.max_tickets1').hide();
            }
        }).change();
        $('input.switch-chk-input2').change(function(){
            if (!$(this).is(':checked')){
                $(this).next('div.max_tickets2').show();
            }else{
                $('div.max_tickets2').hide();
            }
        }).change();
        $('input.switch-chk-input3').change(function(){
            if (!$(this).is(':checked')){
                $(this).next('div.max_tickets3').show();
            }else{
                $('div.max_tickets3').hide();
            }
        }).change();
        $(document).ready(function() {
            $('.selectUsers').select2();
        });
          </script>
@stop
@stop
