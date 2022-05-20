@extends('adminlte::page')

@section('title', 'Blog Edit')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Blog</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST"
                      action="{{ route('blog.update',$blog->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" name="blog_category_id">
                                <option value="0">Select Blog Category</option>
                                @foreach($blogCategories as $category)
                                    <option value="{{$category['id']}}" {{($blog->blog_category_id==$category['id'])?'selected':''}}>{{$category['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" value="{{$blog->title}}" name="title"
                                   placeholder="Title">
                        </div>

                        <div class="form-group">

                            <div class="input_fields_wrap">
                                <button class="add_field_button">Add More Fields</button>

                                @foreach ($blog->blogImages as $indexKey =>$blogImg)
                                    <div class="col-md-12" id="blog-img-{{$blogImg->id}}">
                                        {{--<input type="file" class="col-md-3" name="blog_img[{{$i}}][img]">--}}
                                        <img width="50" src="{{asset('blog/'.$blogImg->image)}}"/>
                                        <input value="{{$blogImg->order}}" type="text" class="col-md-3" name="blog_img[{{$indexKey}}][order]" placeholder="Order">

                                        <input value="{{$blogImg->alt_text}}" type="text" class="col-md-3" name="blog_img[{{$indexKey}}][alt_text]" placeholder="Alternate Text">
                                        <a href="javascript:void(0);" class="delete-blog-img"
                                           data-id="{{$blogImg->id}}">Delete</a>
                                    </div>

                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Content</label>
                            <textarea name="content" id="content">{{$blog->content}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Tags</label>
                            <input type="text" class="form-control tags-input" name="blog_tags" placeholder="Tags" data-role="tagsinput" value="{{$blog->blog_tag}}">
                        </div>

                        <div class="form-group">
                            <label>Meta Key</label>
                            <input type="text" class="form-control" value="{{$blog->meta_key}}" name="meta_key"
                                   placeholder="Meta Key">
                        </div>

                        <div class="form-group">
                            <label>Meta Description</label>
                            <input type="text" class="form-control" value="{{$blog->meta_description}}" name="meta_description"
                                   placeholder="Meta Description">
                        </div>

                        <div class="form-group">
                            <label>Blog Visibility</label>

                            <input id="switch" class="switch-chk-input" name="status"
                                   type="checkbox" {{($blog->status)?'checked':''}}>
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
        //$('textarea').ckeditor();
        CKEDITOR.replace('content', {
            filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
        });
        $(document).ready(function () {
            var max_fields = 10; //maximum input boxes allowed
            var wrapper = $(".input_fields_wrap"); //Fields wrapper
            var add_button = $(".add_field_button"); //Add button ID

            var x = parseInt('{{count($blog->blogImages)}}'); //initlal text box count
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
            });


            $('.delete-blog-img').click(function () {
                var id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: "{{route('blog.blogImgDelete')}}",
                    data: {'_token': $('input[name=_token]').val(), id: id},
                    dataType: "json",
                    success: function (resultData) {
                        $('#blog-img-' + id).remove();
                    }
                });
            })
        });
    </script>
@stop
@stop