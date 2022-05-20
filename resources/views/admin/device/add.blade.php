@extends('adminlte::page')

@section('title', 'Devices Add')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Device</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST" action="{{ route('device.store') }}" autocomplete="off">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>Device Id</label>
                            <input type="text" class="form-control" name="device_id" placeholder="Device Id"
                                   value="{{ old('device_id') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Name"
                                   value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Brand</label>
                            <input type="text" class="form-control" name="brand" placeholder="Brand"
                                   value="{{ old('brand') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Model</label>
                            <input type="text" class="form-control" name="model" placeholder="Model"
                                   value="{{ old('model') }}" />
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" class="form-control" name="price" placeholder="Price"
                                   value="{{ old('price') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Discount</label>
                            <input type="text" class="form-control" name="discount" placeholder="discount"
                                   value="{{ old('discount') }}">
                        </div>

                        <div class="form-group">
                            <label>Supplier</label>
                            <input type="text" class="form-control" name="supplier" placeholder="Supplier"
                                   value="{{ old('supplier') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description" required>{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" class="form-control" name="image" accept="image/png, image/gif, image/jpeg" required>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
@stop
