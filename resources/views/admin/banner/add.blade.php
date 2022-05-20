@extends('adminlte::page')

@section('title', $title.' Add')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add {{$title}}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST" action="{{ route('banner.store') }}">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Title"
                                   value="{{ old('title') }}" required>
                        </div>
                        {{--<div class="form-group">
                            <label>Sub Title</label>
                            <input type="text" class="form-control" name="sub_title" placeholder="Sub Title"
                                   value="{{ old('sub_title') }}" required>
                        </div>--}}
                        <div class="form-group">
                            <label>Url</label>
                            <input type="text" class="form-control" name="url" placeholder="Url"
                                   value="{{ old('url') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Order</label>
                            <input type="text" class="form-control" name="ordr" placeholder="Order"
                                   value="{{ old('ordr') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Expiry</label>
                            <input type="date" class="form-control" name="expiry" placeholder="Expiry"
                                   value="{{ old('expiry') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" class="form-control" name="image" placeholder="Image"
                                   value="{{ old('name') }}" multiple>
                        </div>
                        <div class="form-group">
                            <label>{{$title}} Visibility</label>
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
@stop
