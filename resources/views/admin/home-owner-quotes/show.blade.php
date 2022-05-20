@extends('adminlte::page')

@section('title', 'Quote details')

@section('content_header')

@stop
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-body">
                            <h3>Quote details</h3>
                            <div class="col-md-4">
                                <h4>Quote Place Date</h4>
                                <p>{{date('F d, Y h:i a',strtotime(optional($order[0])->created_at))}}</p>

                            </div>
                            <div class="col-md-4">
                                <h4>User:
                                    <b>{{optional($order[0]->user)->name}}</b></h4>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Items</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>Room</th>
                                        <th>HomeOwner</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $total=0;
                                    @endphp
                                    @foreach($order as $key=>$pro)
                                        <tr>
                                            <td>{{optional($pro->room)->name}}</td>
                                            <td>{{optional($pro->homeOwner)->name}}</td>
                                        </tr>

                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col -->

    </div>
@stop

