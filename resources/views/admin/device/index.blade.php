@extends('adminlte::page')

@section('title', 'Devices')

@section('content_header')
    <h1>Devices</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <form action="{{route('device.index')}}">
                        <div class="pull-left col-md-3">
                            <input type="text" class="form-control" name="search" value="{{$search}}"
                                   placeholder="Search">
                        </div>
                        <div class="pull-left col-md-3">
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </form>
                    <span class="pull-right">
                         <a href="{{ route('device.create') }}" class="btn btn-primary">Add</a>
                    </span>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">

                    <table class="table table-bordered">
                        <tr>
                            <th>Image</th>
                             <th>Name
                            @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'name'])
                            </th>
                            <th>Description</th>
                            <th>Brand
                            @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'brand'])
                            </th>
                            <th>Model
                            @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'model'])
                            </th>
                            <th>Price
                            @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'price'])
                            </th>
                            <th>Active
                            @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'status'])
                            </th>
                            <th>Action</th>
                        </tr>
                        @foreach ($devices as $device)
                            <tr id="tr_{{$device->id}}">
                                <td><img src="{{$device->imageUrl}}" width="40"/></td>
                                <td>{{$device->name}}</td>
                                <td>{{$device->description}}</td>
                                <td>{{$device->brand}}</td>
                                <td>{{$device->model}}</td>
                                <td>{{number_format($device->price, 2)}}</td>
                                <td>
                                    <a title="<?php echo (!$device->status) ? 'Activated' : 'Deactivated' ?>"
                                       href="{{route('device.activeDeactivate',$device->id)}}">
                                        <i class="fa   <?php echo ($device->status) ? 'fa-check active-color' : 'fa-times deActive-color' ?>"
                                           aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('device.edit',$device->id)}}">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a onclick="return (confirm('Are you sure you want to delete ?'))?true:false"
                                       href="{{route('device.delete',$device->id)}}">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer clearfix">
                    {{ $devices->links() }}
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
@section('js')

@stop

@stop
