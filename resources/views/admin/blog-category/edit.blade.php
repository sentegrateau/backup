@extends('adminlte::page')

@section('title', 'Blog Category Edit')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Blog Category</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST"
                      action="{{ route('blog-category.update',$blogCategory->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Name"
                                   value="{{ (empty(old('name')))?$blogCategory->name:old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Category Visibility</label>
                            <input id="switch" class="switch-chk-input" name="status"
                                   type="checkbox" {{($blogCategory->status)?'checked':''}}>
                            <label for="switch" class="switch-chk"></label>
                        </div>
                        <div class="form-group">
                            <label>Featured</label>
                            <input id="switch1" class="switch-chk-input" name="featured"
                                   type="checkbox" {{($blogCategory->featured)?'checked':''}}>
                            <label for="switch1" class="switch-chk"></label>
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