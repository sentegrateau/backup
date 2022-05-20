@extends('adminlte::page')

@section('title', 'Device Edit')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Device</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST" action="{{ route('device.update',$device->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>Device Id</label>
                            <input type="text" class="form-control" name="device_id" placeholder="Device Id"
                                   value="{{ old('device_id',$device->device_id) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Name"
                                   value="{{ (empty(old('name')))?$device->name:old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Brand</label>
                            <input type="text" class="form-control" name="brand" placeholder="Brand"
                                   value="{{ (empty(old('brand')))?$device->brand:old('brand') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Model</label>
                            <input type="text" class="form-control" name="model" placeholder="Model"
                                   value="{{ (empty(old('model')))?$device->model:old('model') }}" />
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" class="form-control" name="price" placeholder="Price"
                                   value="{{ (empty(old('price')))?$device->price:old('price') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Discount</label>
                            <input type="text" class="form-control" name="discount" placeholder="discount"
                                   value="{{ (empty(old('discount')))?$device->discount:old('discount') }}">
                        </div>

                        <div class="form-group">
                            <label>Supplier</label>
                            <input type="text" class="form-control" name="supplier" placeholder="Supplier"
                                   value="{{ (empty(old('supplier')))?$device->supplier:old('supplier') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description" required>{{ (empty(old('description')))?$device->description:old('description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Change Image</label>
                            <input type="file" class="form-control" name="image" accept="image/png, image/gif, image/jpeg">
                        </div>
                        <img src="{{asset('images/'.$device->image)}}" width="200"/>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{route('device.addPackage', $device->id)}}" type="submit" class="btn btn-primary">Next</a>
                    </div>
                </form>

            </div>
            <!-- /.box -->
        </div>
    </div>
@stop
