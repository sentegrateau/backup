@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border"></div>
                <div class="box box-primary">
                    <!-- form start -->
                    <form role="form" enctype="multipart/form-data" method="POST" action="{{route('scene.add',$type)}}">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" name="scene_name" placeholder="Scene Title"
                                       value="{{ (empty(old('scene_name')))?$scene->scene_name:old('scene_name') }}"
                                       required>
                            </div>
                            <div class="row">
                                @if(!empty($scene->scene_device_type))
                                    @foreach($scene->scene_device_type as $key=>$value)
                                        <div class="col-md-3 scene-device-type">
                                            <b>{{$value->device_name}}</b>
                                            <div class="scene-device-list">
                                                @if(!empty($devices))
                                                    <div class="form-group">
                                                        @foreach($devices as $keyD=>$valueD)
                                                            @php
                                                            $checked = '';
                                                            $selected = array_column($value->AdminDeviceTypeRelation->toArray(),'device_id');
                                                            if(in_array($valueD->id,$selected)){
                                                                $checked = 'checked';
                                                            }
                                                            @endphp
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox"
                                                                           name="devices[]"
                                                                           value="{{$value->id.'-'.$valueD->id.'-0'}}" {{$checked}}>
                                                                    {{$valueD->name}}
                                                                </label>
                                                            </div>
                                                            @if(!empty($valueD->subDevices))
                                                                @foreach($valueD->subDevices as $keyDS=>$valueDS)
                                                                    @php
                                                                        $checked = '';
                                                                        $selected = array_column($value->AdminSubDeviceTypeRelation->toArray(),'sub_device_id');
                                                                        if(in_array($valueDS->id,$selected)){
                                                                            $checked = 'checked';
                                                                        }
                                                                    @endphp
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            <input type="checkbox"
                                                                                   name="devices[]"
                                                                                   value="{{$value->id.'-'.$valueD->id.'-'.$valueDS->id}}" {{$checked}}>
                                                                            {{'--'.$valueDS->name}}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <br/>
                            @if(!empty($scene->SceneMetas))
                                @foreach($scene->SceneMetas as $key=>$value)
                                    <div class="form-group">
                                        <label>{{$value->meta_name}} (To add multiple values, press enter)</label>
                                        <input type="text" class="form-control" name="scene_metas[{{$value->id}}]"
                                               placeholder="{{$value->meta_name}}"
                                               value="{{implode(',',array_column($value->metaValue->toArray(),'meta_value'))}}"
                                               required data-role="tagsinput">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
@stop
@section('js')

@stop

@section('css')
    <style>
        .scene-device-list {
            max-height: 200px;
            overflow: auto;
            border: 1px solid #008080;
            padding-left: 20px;
        }
    </style>
@stop

