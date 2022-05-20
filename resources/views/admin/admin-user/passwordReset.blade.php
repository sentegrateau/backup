@extends('adminlte::page')

@section('title', 'Password Reset')

@section('content')

    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">

                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST" action="{{ route('customers.resetPost',$id) }}">	
					@csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" class="form-control" name="password" placeholder="Password"
                                   value="{{ old('password') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="text" class="form-control" name="password_confirmation" placeholder="Confirm Password"
                                   value="{{ old('password_confirmation') }}" required>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary coupon-form-submit">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
@stop