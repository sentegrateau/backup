@extends('adminlte::page')

@section('title', 'user details')

@section('content_header')

@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body">
                <table class="table">
                    <tr>
                        <th>Name</th>
                        <td>{{$user->name}}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{$user->email}}</td>
                    </tr>
                    <tr>
                        <th>Contact</th>
                        <td>{{$user->contact}}</td>
                    </tr>
                    @if($user->role2 != 'owner')
                        <tr>
                            <th>ABN</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Company</th>
                            <td></td>
                        </tr>
                    @endif
                </table>
            </div>

        </div>

    </div>
@stop

