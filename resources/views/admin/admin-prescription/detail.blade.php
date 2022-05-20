@extends('adminlte::page')

@section('title', 'Prescription')

@section('content_header')
    <h1>Prescription</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="">({{$prescription->user->first_name}}) Prescription</h3>
                    <span class="time"><i class="fa fa-clock-o"></i> {{$prescription->created_at}}</span>
                </div>
                <!-- /.box-header -->
                <div class="box-body row">
                    <div class="timeline-item col-md-4">
                        @if(!empty($prescription->prescriptionImages))
                            <div class="">
                            @foreach($prescription->prescriptionImages as $key=>$value)
                                <div class="timeline-body">
                                    <div class="embed-responsive embed-responsive-16by9 img-thumbnail">
                                        <a href="{{$value}}" data-toggle="lightbox" data-title="Prescription" data-gallery="gallery">
                                        <img src="{{$value}}" class="embed-responsive-item"/>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="timeline-item col-md-8">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            @if(!empty($prescription->prescriptionRelation))
                                @foreach($prescription->prescriptionRelation as $key=>$value)
                                    @php
                                        $totalAmount = array_sum(array_column($value->medicine->toArray(),'price'));
                                    @endphp
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="heading{{$key+1}}">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key+1}}" aria-expanded="false" aria-controls="collapse{{$key+1}}">
                                                    {{ $value->store->name }}:
                                                    <i class="fa fa-inr" aria-hidden="true"></i>
                                                    <strong>{{$totalAmount}}</strong>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse{{$key+1}}" class="panel-collapse collapse" role="tabpane{{$key+1}}" aria-labelledby="heading{{$key+1}}">
                                            <div class="panel-body">
                                                @if(!empty($value->medicine))
                                                    <table class="table table-striped">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Medicine Name</th>
                                                                <th>Quantity</th>
                                                                <th>Price</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($value->medicine as $keyM=>$valueM)
                                                                <tr>
                                                                    <td>{{$valueM->medicine_name}}</td>
                                                                    <td>{{$valueM->qty}}</td>
                                                                    <td>{{$valueM->price}}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <h3>No medicine added by this store....</h3>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h3>No Store added medicine yet...</h3>
                            @endif
                        </div>

                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.col -->
    </div>
    <link href="{{ asset('vendor/adminlte/dist/css/ekko-lightbox.css')}}" rel="stylesheet">
@section('js')
    <script src="{{ asset('vendor/adminlte/dist/js/ekko-lightbox.min.js') }}"></script>
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });
    </script>
@stop

<style>
    .panel-group .panel-heading a {padding:10px 5px;display: block;text-decoration: none;position: relative;}
    .panel-group .panel-heading a[aria-expanded="true"]:after {content: '-';float: right;}
    .panel-group .panel-heading a[aria-expanded="false"]:after {content: '+';float: right;}
</style>

@stop