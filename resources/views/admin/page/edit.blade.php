@extends('adminlte::page')

@section('title', 'Page Edit')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Page</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST"
                      action="{{ route('page.update',$page->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="box-body">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" value="{{$page->name}}" name="name"
                                   placeholder="Name">
                        </div>

                        <div class="form-group" >
                            <label>Type</label>
                            <select class="form-control" name="type" >
                                <option value="">--select--</option>
                                @foreach($aType as $key => $val)
                                    <option value="{{$key}}" {{$page->type==$key?'selected':''}}>{{$val}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Content</label>
                            <textarea name="description" id="content">{{$page->description}}</textarea>
                        </div>


                        <div class="form-group">
                            <label>Meta Key</label>
                            <input type="text" class="form-control" value="{{$page->meta_key}}" name="meta_key"
                                   placeholder="Meta Key">
                        </div>

                        <div class="form-group">
                            <label>Meta Description</label>
                            <input type="text" class="form-control" value="{{$page->meta_description}}" name="meta_description"
                                   placeholder="Meta Description">
                        </div>

                        <div class="form-group">
                            <label>Page Visibility</label>

                            <input id="switch" class="switch-chk-input" name="status"
                                   type="checkbox" {{($page->status)?'checked':''}}>
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
	<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script>tinymce.init({ selector:'textarea', plugins: 'code',toolbar: 'code',relative_urls : 0,
          remove_script_host : 0 });</script>
@stop
@stop
