<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Admin;
use App\Models\AdminPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminUserController extends Controller
{

    public $role2 = ['1' => 'Admin', '2' => 'Sub-Admin'];

    public $permissions = ['admin/drafts' => 'Quotes', 'admin/customers' => 'Users', 'admin/package' => 'Package','admin/room'=>'Rooms','admin/device'=>'Devices','/admin/make-packages'=>'Standard Kit','admin/orders'=>'Orders','admin/home-owner-settings'=>'Home Owner Settings','home-owner-settings/relations'=>'Home Owner Relations','admin/home-owner-quotes'=>'Home Owner Quotes','admin/ticket-category'=>'Support Ticket Category','admin/support-ticket'=>'Support Ticket'];

    public $title = 'Admin User';
    public $routeName = "admin-user";

    public function __construct()
    {
        view()->composer('*', function ($view) {
            $role2 = $this->role2;
            $title = $this->title;
            $routeName = $this->routeName;
            $view->with(compact('role2', 'title', 'routeName'));
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = $this->title;
        $data = $request->all();
        $customers = Admin::orderBy('id', 'desc');

        $trash = false;
        if (!empty($data['trash'])) {
            $title = 'Trash Admin';
            $customers = $customers->onlyTrashed();
            $trash = true;
        }

        $customers = $customers->paginate(15);
        return view('admin.' . $this->routeName . '.index', compact('customers', 'title', 'trash'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.' . $this->routeName . '.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'email' => 'required|email|unique:users',
            'password' => 'required|max:15|min:8',
        ));
        $data = $request->all();
        $User = new Admin();
        $User->role = 2;
        $User->email = $data['email'];
        $User->password = Hash::make($data['password']);

        $User->save();
        Session::flash('success', $this->title . ' has been created!');
        return redirect()->route($this->routeName . '.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = $this->title;
        $customers = Admin::where('id', '=', $id)->first();
        return view('admin.' . $this->routeName . '.edit')->with(['customers' => $customers, 'title' => $title]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            //'role' => 'required',
            'email' => 'required|email|unique:admins,email,' . $id,
        ));

        $data = $request->all();


        $User = Admin::find($id);

        $User->role = $User->role;
        $User->email = $data['email'];
        if (!empty($data['password']))
            $User->password = Hash::make($data['password']);

        $User->save();
        Session::flash('success', $this->title . ' has been created!');
        return redirect()->route($this->routeName . '.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function activeDeactivate(Request $request, $id)
    {
        $mt = Admin::where('id', $id)->first();
        $mt->update(['status' => ($mt->status == '1' ? '0' : '1')]);

        // return redirect()->route('products.index')->with('success', "Product " . (($status) ? 'Activated' : 'Deactivated') . " Successfully.");
        return redirect()->back()->with('success', $this->title . " " . (($mt->status) ? 'Activated' : 'Deactivated') . " Successfully.");
    }


    public function delete($id)
    {
        $this->deleteMethod($id);
        return redirect()->back();
    }

    public function deleteMethod($id)
    {
        $device = Admin::where('id', $id)->first();
        $device->delete();
    }


    public function assignPermission($admin_id)
    {
        $title = $this->title;
        $customer = Admin::find($admin_id);
        $permissionsArr = [];
        $adminPermissions=AdminPermission::where('user_id',$admin_id)->pluck('read_permit','module_name')->toArray();
        foreach ($this->permissions as $key=>$permission) {

           // echo 'tt'.$adminPermissions[$permission];
            $permissionsArr[] = ['name' => $permission,'checked'=>(!empty($adminPermissions[$key])?true:false),'key'=>$key];
        }



        return view('admin.' . $this->routeName . '.assign-permission')->with(['id' => $admin_id, 'customer' => $customer, 'title' => $title, 'permissionsArr' => $permissionsArr]);
    }

    public function saveAssignPermission(Request $request, $id)
    {
        $data = $request->all();
        if (!empty($data['permissions'])) {
            // echo "<pre>";print_r($data['permissions']);die;
            AdminPermission::where('user_id', $id)->delete();
            foreach ($data['permissions'] as $permission => $read) {
                AdminPermission::create([
                    'module_name' => $permission,
                    'user_id' => $id,
                    'read_permit' => 1
                ]);
            }
        }

        return redirect()->back()->with('success','Permission Saved');
    }

}
