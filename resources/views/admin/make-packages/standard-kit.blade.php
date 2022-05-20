@extends('adminlte::page')

@section('title', $title.' Add')
@section('content_header')
    <h1>{{$kit->title}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add {{$title}}</h3>
                </div>
                <div class="box-body">
                    <div id="make-packages"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var standardPkgUrl = "{{ url('public/api/draft/'.$kit->id) }}";
        var kitData =
            {!!  $kit !!}
        var cartValues =<?php echo json_encode($items); ?>
    </script>
    <script src="{{asset('js/app.js')}}"></script>

@stop
