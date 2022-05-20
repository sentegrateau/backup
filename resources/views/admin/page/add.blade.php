@extends('adminlte::page')

@section('title', 'Page Add')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Page</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST" action="{{ route('page.store') }}">
                    @csrf
                    <div class="box-body">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Name"
                                   value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group" >
                            <label>Type</label>
                            <select class="form-control" name="type" >
                                <option value="">--select--</option>
                                @foreach($aType as $key => $val)
                                    <option value="{{$key}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Content</label>
                            <textarea name="description" id="content">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Meta Key</label>
                            <input type="text" class="form-control" name="meta_key" placeholder="Meta Key"
                                   value="{{ old('meta_key') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Meta Description</label>
                            <input type="text" class="form-control" name="meta_description" placeholder="Meta Description"
                                   value="{{ old('meta_description') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Page Visibility</label>

                            <input id="switch" class="switch-chk-input" name="status" type="checkbox" required>
                            <label for="switch" class="switch-chk"></label>
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
            remove_script_host : 0});</script>
@stop
@stop
