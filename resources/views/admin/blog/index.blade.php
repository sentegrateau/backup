@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Blog</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button style="margin-bottom: 10px" class="btn btn-primary delete_all"
                            data-url="{{ route('blog.deleteAll') }}">Delete All Selected
                    </button>
                    <span class="pull-right">
                         <a href="{{ route('blog.create') }}" class="btn btn-primary">Add</a>
                    </span>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">

                    <table class="table table-bordered">
                        <tr>
                            <th width="50px"><input type="checkbox" id="master"></th>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Post</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($blog as $blg)
                            <tr id="tr_{{$blg->id}}">
                                <td><input type="checkbox" class="sub_chk" data-id="{{$blg->id}}"></td>
                                <td><img width="50"
                                         onError="this.onerror=null;this.src='{{asset('img/no-image.png')}}';"
                                         src="{{(!empty($blg->blogImages[0]))?asset('blog/'.$blg->blogImages[0]->image):''}}"/>
                                </td>
                                <td>{{!empty($blg->blogcategory->name)?$blg->blogcategory->name:'--'}}</td>
                                <td>{{$blg->title}}</td>
                                <td>{{substr(strip_tags($blg->content),0,20)}}</td>
                                <td>{{($blg->status)?'Active':'Deactive'}}</td>
                                <td>
                                    <a href="{{route('blog.edit',$blg->id)}}">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{route('blog.delete',$blg->id)}}">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer clearfix">
                    {{ $blog->links() }}
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {


            $('#master').on('click', function (e) {
                if ($(this).is(':checked', true)) {
                    $(".sub_chk").prop('checked', true);
                } else {
                    $(".sub_chk").prop('checked', false);
                }
            });


            $('.delete_all').on('click', function (e) {


                var allVals = [];
                $(".sub_chk:checked").each(function () {
                    allVals.push($(this).attr('data-id'));
                });


                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {


                    var check = confirm("Are you sure you want to delete this row?");
                    if (check == true) {


                        var join_selected_values = allVals.join(",");

                        console.log("$(this).data('url')", $(this).data('url'))
                        $.ajax({
                            url: $(this).data('url'),
                            type: 'DELETE',
                            data: 'ids=' + join_selected_values + '&_token=' + $('input[name=_token]').val(),
                            success: function (data) {
                                if (data['success']) {
                                    $(".sub_chk:checked").each(function () {
                                        $(this).parents("tr").remove();
                                    });
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


                        $.each(allVals, function (index, value) {
                            $('table tr').filter("[data-row-id='" + value + "']").remove();
                        });
                    }
                }
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