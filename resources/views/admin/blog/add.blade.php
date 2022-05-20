@extends('adminlte::page')

@section('title', 'Blog Add')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Blog</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST" action="{{ route('blog.store') }}">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>Blog Category</label>
                            <select class="form-control" name="blog_category_id">
                                <option value="0">Select Blog Category</option>
                                @foreach($blogCategories as $category)
                                    <option value="{{$category['id']}}">{{$category['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Title"
                                   value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group">

                            <div class="input_fields_wrap">
                                <button class="add_field_button">Add More Fields</button>
                                <div class="col-md-12">
                                    <input type="file" class="col-md-3" name="blog_img[0][img]">
                                    <input type="text" class="col-md-3" name="blog_img[0][order]" placeholder="Order">
                                    <input type="text" class="col-md-3" name="blog_img[0][alt_text]" placeholder="Alternate Text">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Content</label>
                            <textarea name="content" id="content" required>{{ old('content') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Tags</label>
                            <input type="text" class="form-control tags-input" name="blog_tags" placeholder="Tags" data-role="tagsinput">
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
                            <label>Blog Visibility</label>

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
    <script src="{{URL::to('/')}}/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="{{URL::to('/')}}/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
    <script>
        //$('textarea').ckeditor({extraPlugins: 'uploadimage'});
        CKEDITOR.replace('content', {
            filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
        });
        $(document).ready(function () {
            var max_fields = 10; //maximum input boxes allowed
            var wrapper = $(".input_fields_wrap"); //Fields wrapper
            var add_button = $(".add_field_button"); //Add button ID

            var x = 1; //initlal text box count
            $(add_button).click(function (e) { //on add input button click
                e.preventDefault();
                if (x < max_fields) { //max input box allowed
                    //text box increment
                    $(wrapper).append('<div class="col-md-12"><input type="file" class="col-md-3" name="blog_img[' + x + '][img]"><input type="text" class="col-md-3" name="blog_img[' + x + '][order]" placeholder="Order"><input type="text" class="col-md-3" name="blog_img[' + x + '][alt_text]" placeholder="Alternate Text"><a href="javascript:void(0);" class="remove_field">Remove</a></div>'); //add input box
                    x++;
                }
            });

            $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            })
        });
    </script>
@stop
@stop