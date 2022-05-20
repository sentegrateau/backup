

@extends('adminlte::page')

@section('title', 'Add '.$title)

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
                      action="{{ route($routeName.'.update',$customers->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="box-body">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email Id</label>
                                <input type="text" class="form-control" name="email" placeholder="Email Id"
                                       value="{{ old('email',!empty($customers->email)?$customers->email:'') }}" required>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label title="Set/Update Password">Password</label>
                                <input type="text" class="form-control" name="password" placeholder="Change Password"
                                       value="{{ old('password') }}">
                            </div>
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

