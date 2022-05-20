@extends('adminlte::page')

@section('title', 'Packages')

@section('content_header')
    <h1>Packages</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <span class="pull-right">
                         <a href="{{ route('package.order') }}" class="btn btn-primary">Order</a>
                         <a href="{{ route('package.create') }}" class="btn btn-primary">Add</a>
                    </span>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">

                    <table class="table table-bordered">
                        <tr>
                            <th>Name
                                @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'name'])
                            </th>
                            <th>Description</th>
                            <th>Active
                                @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'status'])
                            </th>
                            <th>Created At
                                @include('layouts.elements.sort',['routeName'=>$routeName,'sortName'=>'created_at'])
                            </th>
                            <th>Action</th>
                        </tr>
                        @foreach ($packages as $pkg)
                            <tr id="tr_{{$pkg->id}}">
                                <td>{{$pkg->name}}</td>
                                <td>{{$pkg->description}}</td>
                                <td>
                                    <a title="<?php echo (!$pkg->status) ? 'Activated' : 'Deactivated' ?>"
                                       href="{{route('package.activeDeactivate',$pkg->id)}}">
                                        <i class="fa   <?php echo ($pkg->status) ? 'fa-check active-color' : 'fa-times deActive-color text-red' ?>"
                                           aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>{{$pkg->created_at}}</td>
                                <td>
                                    <a href="{{route('package.edit',$pkg->id)}}">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a onclick="return (confirm('Are you sure you want to delete ?'))?true:false"
                                       href="{{route('package.delete',$pkg->id)}}">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer clearfix">
                    {{ $packages->links() }}
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
@section('js')

@stop

@stop
