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
                      action="{{ route($routeName.'.update',$room->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="box-body">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Name"
                                   value="{{ (empty(old('name')))?$room->name:old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description"
                                      required>{{ (empty(old('description')))?$room->name:old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <img width="50" src="{{$room->imageUrl}}"/><br/>
                            <label>Image</label>
                            <input type="file" class="form-control" name="image"/>
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
