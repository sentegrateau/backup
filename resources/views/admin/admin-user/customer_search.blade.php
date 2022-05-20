<!-- /.box-header -->
<div class="box-body table-responsive">

    <table class="table table-bordered">
        <tr>
            <th width="50px"><input type="checkbox" id="master"></th>
            <th>Role</th>
            <th>Email</th>
            <th>Status</th>
            <th>Created</th>
            <th>Action</th>

        </tr>
        @foreach ($customers as $blg)
            <tr id="tr_{{$blg->id}}">
                <td><input type="checkbox" class="sub_chk" data-id="{{$blg->id}}"></td>
                <td>{{$role2[$blg->role] }}</td>
                <td>{{$blg->email}}</td>
                <td>{{($blg->status)?'Active':'Deactive'}}</td>
                <td>{{$blg->created_at}}</td>

                <td>
                    <a href="{{route($routeName.'.edit',$blg->id)}}">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </a>
                    @if($blg->role==2)
                        <a onclick="return (confirm('Are you sure you want to delete ?'))?true:false"
                           href="{{route($routeName.'.delete',$blg->id)}}">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                        <a title="{{ (!$blg->status) ? 'Activated' : 'Deactivated' }}"
                           href="{{route($routeName.'.activeDeactivate',$blg->id)}}">
                            <i class="fa  {{($blg->status) ? 'fa-check active-color' : 'fa-times deActive-color' }}"
                               aria-hidden="true"></i>
                        </a>
                        <a href="{{route($routeName.'.assignPermission',$blg->id)}}">
                            <i class="fa fa-unlock" aria-hidden="true"></i>
                        </a>
                    @endif
                </td>

            </tr>
        @endforeach
    </table>
</div>
<!-- /.box-body -->

<div class="box-footer clearfix">
    {{ $customers->links() }}
</div>
