@extends('adminlte::page')

@section('title', 'Tag Add')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Tag</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST" action="{{ route('tag.store') }}">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="tag_name" placeholder="Title"
                                   value="{{ old('tag_name') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Visibility</label>
                            <input id="switch" class="switch-chk-input" name="status" type="checkbox">
                            <label for="switch" class="switch-chk"></label>
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