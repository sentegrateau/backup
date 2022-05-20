@extends('adminlte::page')

@section('title', 'Page Add')

@section('content')


    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Coupon</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST" action="{{ route('coupon.store') }}">
                    @csrf
                    <div class="box-body">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Name" required>
                        </div>
                        <div class="form-group">
                            <label>Coupon Message</label>
                            <input type="text" class="form-control" name="coupon_message" placeholder="Coupon Message" required>
                        </div>
                        <div class="form-group">
                            <label>Coupon Code</label>
                            <input type="text" class="form-control" name="coupon_code" placeholder="Coupon Code" required>
                        </div>
                        <div class="form-group">
                            <label class="radio">Discount Type: </label>
                            <label class="radio-inline"><input type="radio" name="discount_type" value=1 checked>Percentage</label>
                            <label class="radio-inline"><input type="radio" name="discount_type" value=2 >Amount</label>
                        </div>
                        <div class="form-group">
                            <label>Discount</label>
                            <input type="text" class="form-control" name="discount" placeholder="Discount" required>
                        </div>
                        <div class="form-group">
                            <label>No Expiry Date</label>

                            <input id="switch" class="switch-chk-input1" name="no_expiry"
                                   type="checkbox" checked>
                            <div class="max_tickets1">
                                <div class="form-group">
                                    <label>Expiry Date</label>
                                    <input type="date" class="form-control" name="expiry_date">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Is Unlimited</label>

                            <input id="switch" class="switch-chk-input2" name="is_unlimited"
                                   type="checkbox" checked>
                            <div class="max_tickets2">
                                <div class="form-group">
                                    <label>limit</label>
                                    <input type="text" class="form-control" name="Limit">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Send To All Users</label>

                            <input id="switch" class="switch-chk-input3" name="is_all_users"
                                   type="checkbox" checked>
                            <div  class="max_tickets3">
                                    <label>Users List</label>
                                    <select class="selectUsers form-control" search="search" name="users[]" multiple="multiple" style="width:100%;height: 100px;">
                                        <?php foreach($userList as $user){ $userId = $user->id;?>
                                        <option value="{{$userId}}" >{{$user->name}}</option>
                                        <?php }?>
                                    </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Disable The Coupon</label>
                            <input id="switch" class="switch-chk-input" name="disable_coupon" type="checkbox" checked>
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
    {{--<script src="{{URL::to('/')}}/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="{{URL::to('/')}}/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
    <script>
        //$('textarea').ckeditor({extraPlugins: 'uploadimage'});
        CKEDITOR.replace('content', {
            filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
        });

        $('textarea').ckeditor({
            toolbar: 'Full',
            enterMode : CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            toolbar: 'Full',
            enterMode : CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P
        });
    </script>--}}

    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({ selector:'textarea', plugins: 'code',toolbar: 'code',relative_urls : 0,
            remove_script_host : 0});
            $('div.max_tickets1').hide();
            $('div.max_tickets2').hide();
            $('div.max_tickets3').hide();
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
