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
                <form enctype="multipart/form-data" method="post" action="{{ route('settings.store') }}">
                    @csrf
                    <?php $i = 0; ?>
                    @foreach ($settings as $setting)
                        <div class="box-body">
                            <div class="form-group">
                                <label>{{$setting->name}}</label>
                                <input type="hidden" value="{{$setting->id}}" name="setting[{{$i}}][id]">
                                <input type="text" class="form-control"
                                       value="{{(empty(old('title')))?$setting->content:old($setting->module_name)}}"
                                       name="setting[{{$i}}][value]"
                                       placeholder="{{$setting->name}}">
                                <small>{{$setting->module_summery}}</small>
                            
                            </div>
                        </div>

                    <?php $i++; ?>
                @endforeach
                        <label>Tool Tip For Password Field</label>
                        <input type="text" value="{{$setting->pass_tooltip}}" name="pass_tooltip" style="width: 100%;height: 34px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;">
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