@extends('adminlte::page')

@section('title', $title.'s')

@section('content_header')
    <h1>{{$title.'s'}}</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <span class="pull-right">
                         <a href="{{ route($route.'.create') }}" class="btn btn-primary">Add</a>
                    </span>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">

                    <table class="table table-bordered">
                        <tr>
                            <th>Device</th>
                            <th>Name</th>
                            <th>Label</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($blogCategory as $blg)
                            <tr id="tr_{{$blg->id}}">
                                <td>{{$blg->device->name}}</td>
                                <td>{{$blg->name}}</td>
                                <td>{{$blg->label}}</td>
                                <td>
                                    <a href="{{route($route.'.edit',$blg->id)}}">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a onclick="return (confirm('Are you sure you want to delete ?'))?true:false"
                                       href="{{route($route.'.delete',$blg->id)}}">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer clearfix">
                    {{ $blogCategory->links() }}
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.chk_featured').change(function (e) {
                var featured = 0;
                if ($(this).is(':checked')) {
                    featured = 1;
                }
                $.ajax({
                    url: $(this).attr('data-url'),
                    data: {featured: featured},
                    type: "GET",
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data['error'] == true) {
                            new PNotify({
                                title: 'Alert',
                                text: data['message'],
                                type: 'error',
                                styling: 'bootstrap3',
                                delay: 3000
                            });
                        } else {
                            new PNotify({
                                title: 'Alert',
                                text: data['message'],
                                type: 'success',
                                styling: 'bootstrap3',
                                delay: 3000
                            });
                        }
                    }
                });
            });

            $(document).on('confirm', function (e) {
                var ele = e.target;
                e.preventDefault();
                $.ajax({
                    url: ele.href,
                    type: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        if (data['success']) {
                            $("#" + data['tr']).slideUp("slow");
                            alert(data['success']);
                        } else if (data['error']) {
                            alert(data['error']);
                        } else {
                            alert('Whoops Something went wrong!!');
                        }
                    },
                    error: function (data) {
                        alert(data.responseText);
                    }
                });
                return false;
            });
        });
    </script>
@stop

@stop
