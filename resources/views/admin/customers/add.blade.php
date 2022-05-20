@extends('adminlte::page')

@section('title', 'Add '.$title)

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Customers</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST" action="{{ route('customers.store') }}">
                    @csrf
                    <div class="box-body">
						<div class="col-md-6">
							<div class="form-group">
								<label>Name</label>
								<input type="text" class="form-control" name="first_name" placeholder="Name"
									   value="{{ old('first_name') }}" required>
							</div>
                        </div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Email Id</label>
								<input type="text" class="form-control" name="email" placeholder="Email Id"
									   value="{{ old('email') }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Phone</label>
								<input type="text" class="form-control" name="phone" placeholder="phone"
									   value="{{ old('phone') }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Password</label>
								<input type="text" class="form-control" name="password" placeholder="password"
									   value="{{ old('password') }}" required>
							</div>
						</div>


                        {{-- <div class="form-group">
                           <label>Job Category Visibility</label>
                           <input id="switch" class="switch-chk-input" name="status" type="checkbox">
                           <label for="switch" class="switch-chk"></label>
                       </div> !--}}
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
