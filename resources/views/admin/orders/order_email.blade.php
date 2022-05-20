@extends('adminlte::page')

@section('title', 'Order Email Settings')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Order Email Settings</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST"
                      action="{{ route('orders.email.submit', $id) }}">
                    @csrf
                    <div class="box-body">

                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" class="form-control" value="{{$email->subject}}" name="subject"
                                   placeholder="Subject">
                        </div>

                        <div class="form-group">
                            <label>Content</label>
                            <textarea name="content" class="form-control" placeholder="Content">{{$email->content}}</textarea>
                        </div>

                        
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
@section('js')
<!-- <script  src="{{env('APP_URL')}}/resources/views/admin/coupon/js/index.js"></script> -->
	<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
      
  </script>
  <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({ selector:'textarea', plugins: 'code',toolbar: 'code',relative_urls : 0,
            remove_script_host : 0});</script>
@stop
@stop
