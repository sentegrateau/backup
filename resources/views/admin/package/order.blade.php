@extends('adminlte::page')

@section('title', 'Package Add')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Order Package</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="post" action="{{route('package.orderSave')}}">

                    <div class="box-body">
                        @csrf
                        <ul id="imageListId" class="col-md-6 col-md-offset-3 list-group">
                            @foreach($packages as $key=>$package)
                                <li id="list-{{$key+1}}" data-id="{{$package->id}}"
                                    class="list-group-item listitemClass">{{$package->name}}</li>
                            @endforeach
                        </ul>
                        <input id="outputvalues" type="hidden" name="order" value=""/>


                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
@stop
@section('js')
    <link href=
          "//code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
          rel="stylesheet">

    <script src=
            "//code.jquery.com/ui/1.11.3/jquery-ui.js">
    </script>
    <script>
        $(function () {
            $("#imageListId").sortable({
                update: function (event, ui) {
                    getIdsOfImages();
                }//end update
            });
        });

        function getIdsOfImages() {
            var values = [];
            $('.listitemClass').each(function (index) {
                var num = $(this).attr("id").replace("list-", "");
                var val = $(this).data("id");
                values.push({id: val, order: num})
            });

            $('#outputvalues').val(JSON.stringify(values));
        }
    </script>
@stop
