@extends('adminlte::page')

@section('title', ' Edit')

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
                      action="{{ route($route.'.update',$blogCategory->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>Device</label>

                            <select name="device_id" class="form-control" required>
                                <option value="">Select Device</option>

                                @foreach($device_names  as $d_name)
                                    <option value="{{$d_name->id}}" {{(old('device_id',$blogCategory->device_id)==$d_name->id)?'selected':''}}>{{$d_name->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Name"
                                   value="{{old('name',$blogCategory->name)}}" required>
                        </div>

                        <div class="form-group">
                            <label>Label</label>
                            <input type="text" class="form-control" name="label" placeholder="Label"
                                   value="{{old('label',$blogCategory->label)}}" >
                        </div>

                       {{-- <div class="form-group">
                            <label>Type</label>

                            <select name="type" class="form-control" required>
                                <option value="">Select Type</option>
                                @foreach($fType as $key=>$val)
                                    <option value="{{$key}}" {{(old('type',$blogCategory->type)==$key)?'selected':''}}>{{$val}}</option>
                                @endforeach
                            </select>
                        </div>--}}

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
