@extends('layouts.app')
@section('content')
    <div id="customization-root"></div>
@endsection
@section('js')
    <script>
        var orderId = '{{$orderId}}';
    </script>
    <script src="{{asset('js/app.js')}}"></script>
@endsection
