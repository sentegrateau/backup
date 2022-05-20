@extends('layouts.app')
@section('content')

    <div class="container">
        <div class="row">
            <h3>
                @php
                $search = array('Signup', '2');
                $replace = array('', '');
                @endphp
                {{str_replace($search, $replace, $pageData->name, $count)}}
            </h3>
            <div class="col-md-12">
                @if(!empty($pageData))
                    {!! $pageData->description !!}
                @endif
            </div>
        </div>
    </div>
@endsection
