@extends('adminlte::page')

@section('title', $title.' Add')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add {{$title}}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" enctype="multipart/form-data" method="POST" action="{{ route($route.'.store') }}"
                      autocomplete="off">
                    @csrf
                    <div class="box-body">

                        <div class="form-group">
                            <label>Device</label>

                            <select name="device_id" class="form-control" required>
                                <option value="">Select Device</option>
                                @foreach($device_names  as $d_name)
                                    <option value="{{$d_name->id}}">{{$d_name->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Parameter Id</label>
                            <input type="text" class="form-control" name="param_id" placeholder="Parameter Id"
                                   value="{{ old('param_id') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Parameter Name</label>
                            <input type="text" class="form-control" name="param_name" placeholder="Parameter Name"
                                   value="{{ old('param_name') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Parameter Byte</label>
                            <input type="text" class="form-control" name="param_byte" placeholder="Parameter Byte"
                                   value="{{ old('param_byte') }}">
                        </div>
                        @for($i=0;$i<10;$i++)
                            <div class="form-group after-add-more">
                                <label>Parameter Value {{$i+1}}</label>
                                <input type="text" class="form-control" name="value[{{$i}}]"
                                       placeholder="Parameter Value {{$i+1}}"
                                       value="{{ old('value[0]') }}">
                            </div>
                        @endfor



                        {{--<div class="form-group">
                            <label>Type</label>

                            <select name="type" class="form-control" required>
                                <option value="">Select Type</option>
                                <option value="device_type">Device Type</option>
                                <option value="param">Parameter</option>
                            </select>
                        </div>--}}


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
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $("body").on("click", ".add-more", function () {
                var html = $(".after-add-more").first().clone();

                //  $(html).find(".change").prepend("<label for=''>&nbsp;</label><br/><a class='btn btn-danger remove'>- Remove</a>");

                $(html).find(".change").html("<label for=''>&nbsp;</label><br/><a class='btn btn-danger remove'>- Remove</a>");


                $(".after-add-more").last().after(html);


            });

            $("body").on("click", ".remove", function () {
                $(this).parents(".after-add-more").remove();
            });
        });
    </script>
@endsection


