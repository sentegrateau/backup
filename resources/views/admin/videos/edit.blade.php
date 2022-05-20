@extends('adminlte::page')

@section('title', 'Video Edit')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Video</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST"
                      action="{{ route('video.update',$video->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="box-body">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" value="{{$video->name}}" name="name"
                                   placeholder="Name">
                        </div>

                        <div class="form-group" >
                            <label>Type</label>
                            <select class="form-control" name="type" required>
                                <option value="">--select--</option>
                                @foreach($aType as $key => $val)
                                    <option value="{{$key}}" {{$video->type==$key?'selected':''}}>{{$val}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Link</label>
                            <input type="text" class="form-control" value="{{$video->link}}" name="link"
                                   placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <input id="switch" class="switch-chk-input" name="status"
                                   type="checkbox" {{($video->status)?'checked':''}}>
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
  <script>tinymce.init({ selector:'textarea', plugins: 'code',toolbar: 'code', });</script>
@stop
@stop
