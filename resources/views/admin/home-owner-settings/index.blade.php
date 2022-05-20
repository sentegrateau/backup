@extends('adminlte::page')

@section('title', $title.'s')

@section('content_header')
    <h1>{{$title}}s</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <span class="pull-right">
                         <a href="{{ route($routeName.'.create') }}" class="btn btn-primary">Add</a>
                    </span>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">

                    <table class="table table-bordered">
                        <tr>
                            <th>Image</th>
                            <th>Name @include('layouts.elements.sort',['routeName'=>'room','sortName'=>'name'])</th>
                            <th>Description</th>
                            <th>
                                Active @include('layouts.elements.sort',['routeName'=>'room','sortName'=>'status'])</th>
                            <th>Created
                                At @include('layouts.elements.sort',['routeName'=>'room','sortName'=>'created_at'])</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($rooms as $room)
                            <tr id="tr_{{$room->id}}">
                                <td><img src="{{$room->imageUrl}}" width="50"/></td>
                                <td>{{$room->name}}</td>
                                <td>{{$room->description}}</td>

                                <td>
                                    <a title="<?php echo (!$room->status) ? 'Activated' : 'Deactivated' ?>"
                                       href="{{route($routeName.'.activeDeactivate',['id'=>$room->id,'field_name'=>'status'])}}">
                                        <i class="fa   <?php echo ($room->status) ? 'fa-check active-color' : 'fa-times deActive-color text-red' ?>"
                                           aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>{{$room->created_at}}</td>
                                <td>
                                    <a href="{{route($routeName.'.edit',$room->id)}}">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a onclick="return (confirm('Are you sure you want to delete ?'))?true:false"
                                       href="{{route($routeName.'.delete',$room->id)}}">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer clearfix">
                    {{ $rooms->links() }}
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
@section('js')

@stop

@stop
