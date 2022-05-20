@extends('adminlte::page')

@section('title', 'Approved Prescription')

@section('content_header')
    <h1>Prescriptions</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">

                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">

                    <table class="table table-bordered">
                        <tr>
                            <th>User Name</th>
                            <th>Medicine Name</th>
                            <th>Address</th>
                            <th>Post</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($prescription as $pr)
                            <tr id="tr_{{$pr->id}}">
                                <td>{{$pr->user->first_name}}</td>
                                <td>{{!empty($pr->medicine_name)?$pr->medicine_name:'----'}}</td>
                                <td>{{$pr->prescription_address}}</td>
                                <td>{{$pr->prescription_zip}}</td>
                                <td>{{$pr->created_at}}</td>
                                <td><label class="label bg-green">Arroved</label></td>
                                <td>
                                    <a href="{{route('admin.prescription.admin-approved-detail',$pr->slug)}}" data-toggle="tooltip" data-placement="top" title="Check Details">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer clearfix">
                    {{ $prescription->links() }}
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
@section('js')

    <script>
        $('[data-toggle="tooltip"]').tooltip();
        $(document).ready(function(){$('[data-toggle="popover"]').popover();});
    </script>

@stop

@stop