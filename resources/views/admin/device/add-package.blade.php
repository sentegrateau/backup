@extends('adminlte::page')

@section('title', $device->name)
@section('content_header')
    <h1>{{$device->name}}</h1>
@stop
@section('content')
    <div class="">
        <!-- left column -->

        <!-- general form elements -->
        <div class="box box-primary" id="device-packages">

        </div>
        <!-- /.box -->
    </div>

@stop
@section('js')
    <script>
        var DEVICE_ID = "{{$device_id}}";
        var previousUrl = "{{route('device.edit',$device_id)}}";

    </script>
    <script src="{{asset('js/app.js')}}"></script>

@stop
