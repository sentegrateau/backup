@extends('adminlte::page')

@section('title', $title.'')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{$title}}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST"
                      action="{{ (!empty($id))?route($routeName.'.update',$id):route($routeName.'.store') }}">
                    @if(!empty($id))
                        @method('PATCH')
                    @endif
                    @csrf


                    <div class="box-body">
                        <div class="form-group">
                            <label>Kit Name</label>
                            <input type="text" class="form-control" name="kit_name" placeholder="Kit Name"
                                   value="{{ old('kit_name',$kit_name) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Kit Name2</label>
                            <input type="text" class="form-control" name="kit_name2" placeholder="Kit Name2"
                                   value="{{ old('kit_name2',$kit_name2) }}" required>
                        </div>

                    </div>
                    <!-- /.box-body -->


                    <div class="box-footer">

                        @if(!empty($id))
                            <input type="submit" name="final_submit" value="Submit" class="btn btn-primary">
                        @endif
                            <button name="next_submit" type="submit" class="btn btn-primary">Next</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
@stop
