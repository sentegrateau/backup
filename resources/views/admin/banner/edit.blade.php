@extends('adminlte::page')

@section('title', $title.' Edit')

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
                      action="{{ route('banner.update',$banner->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" value="{{$banner->title}}" name="title"
                                   placeholder="Title">
                        </div>
                        {{--<div class="form-group">
                            <label>Sub Title</label>
                            <input type="text" class="form-control" value="{{$banner->sub_title}}" name="sub_title"
                                   placeholder="Sub Title">
                        </div>--}}
                        <div class="form-group">
                            <label>Url</label>
                            <input type="text" class="form-control" name="url" placeholder="Url"
                                   value="{{ old('url',$banner->url) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Order</label>
                            <input type="text" class="form-control" name="ordr" placeholder="Order"
                                   value="{{ old('ordr',$banner->ordr) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Expiry</label>
                            <input type="date" class="form-control" name="expiry" placeholder="Expiry"
                                   value="{{ old('expiry',$banner->expiry) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <img src="{{$banner->full}}" width="50">
                            <input type="file" class="form-control" name="image" placeholder="Image"
                                   value="{{ old('image') }}" multiple>
                        </div>
                        <div class="form-group">
                            <label>{{$title}} Visibility</label>
                            <input id="switch" class="switch-chk-input" name="status"
                                   type="checkbox" {{($banner->status)?'checked':''}}>
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
@stop
