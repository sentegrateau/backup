<!-- /.box-header -->
<div class="box-body table-responsive">

    <table class="table table-bordered">
        <tr>
            <th width="50px"><input type="checkbox" id="master"></th>
            <th>Role</th>
            <th>Customer</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Company</th>
            <th>ABN</th>
            <th>Status</th>
            <th>Created</th>
            <th>Last Login</th>
            @if(!$trash)
                <th>Action</th>
            @endif
        </tr>
        @foreach ($customers as $blg)
            <tr id="tr_{{$blg->id}}">
                <td><input type="checkbox" class="sub_chk" data-id="{{$blg->id}}"></td>
                <td>{{$role2[$blg->role2] }}</td>
                <td>{{($blg->customer)?'Customer':'' }}</td>
                <td>{{$blg->name }}</td>
                <td>{{$blg->email}}</td>
                <td>{{$blg->contact}}</td>
                <td>{{$blg->company}}</td>
                <td>{{$blg->abn}}</td>
                <td>{{($blg->status)?'Active':'Inactive'}}</td>
                <td>{{$blg->created_at}}</td>
                <td>{{$blg->last_login}}</td>
                @if(!$trash)
                    <td>
                        <a href="{{route('customers.edit',$blg->id)}}">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a>
                        <a href="{{route('customers.reset',$blg->id)}}">
                            <i class="fa fa-fw fa-cog" aria-hidden="true"></i>
                        </a>
                        <a onclick="return (confirm('Are you sure you want to delete ?'))?true:false"
                           href="{{route('customers.delete',$blg->id)}}">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                        <a title="{{ (!$blg->status) ? 'Activated' : 'Deactivated' }}"
                           href="{{route('customers.activeDeactivate',$blg->id)}}">
                            <i class="fa  {{($blg->status) ? 'fa-check active-color' : 'fa-times deActive-color' }}"
                               aria-hidden="true"></i>
                        </a>
                    </td>
                @endif
            </tr>
        @endforeach
    </table>
</div>
<!-- /.box-body -->

<div class="box-footer clearfix">
    {{ $customers->links() }}
</div>
