@extends('adminlte::page')

@section('title', 'Permission '.$title)

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Permission {{$title}}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST"
                      action="{{ route($routeName.'.saveAssignPermission',$id) }}">
                    @csrf
                    <div class="box-body">

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Module</th>
                                <th scope="col">Permission</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($permissionsArr as $key=>$permission)
                                <tr>
                                    <th scope="row">#</th>
                                    <td>{{$permission['name']}}</td>
                                    <td>
                                        <input type="checkbox" name="permissions[{{$permission['key']}}]" {{(!empty($permission['checked'])?'checked':'')}} value="1"/>
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
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
