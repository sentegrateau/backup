<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{

    public $role2 = ['owner' => 'Home Owner', 'installer' => 'Installer / Electrician', 'developer' => 'Developer',];

    public $title = 'User';

    public function __construct()
    {
        view()->composer('*', function ($view) {
            $role2 = $this->role2;
            $title = $this->title;
            $view->with(compact('role2', 'title'));
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
        $customers = User::orderBy('id', 'desc');

        $trash = false;
        if (!empty($data['trash'])) {
            $title = 'Trash Customer';
            $customers = $customers->onlyTrashed();
            $trash = true;
        }

        $customers = $customers->paginate(15);
        return view('admin.customers.index', compact('customers', 'title', 'trash'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customers.add');
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
            'name' => 'required|max:150|min:8',
            'phone' => 'required|max:15|min:8',
            'email' => 'required|email|unique:users',
            'password' => 'required|max:15|min:8',
        ));
        $data = $request->all();
        $User = new User();
        $User->role2 = $data['role2'];
        $User->name = $data['name'];
        $User->phone = $data['phone'];
        $User->email = $data['email'];
        $User->password = Hash::make($data['password']);

        $User->save();
        Session::flash('success', 'User has been created!');
        return redirect()->route('customers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $User = User::where('id', '=', $id)->first();
        return view('admin.customers.show')->with(['user' => $User]);
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
        $customers = User::where('id', '=', $id)->first();

        $shippingAdd = $customers->shippingDetails;


        return view('admin.customers.edit')->with(['customers' => $customers, 'title' => $title, 'shippingAdd' => $shippingAdd]);
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
            'name' => 'required|max:150|min:2',
           /* 'contact' => 'required',*/
            'email' => 'required|email|unique:users,email,' . $id,
        ));
        if (!empty($data['role2']) && $data['role2'] != 'owner') {
            $this->validate($request, array(
                'company' => 'required',
                'abn' => 'required|numeric|max:11|min:11',
            ));
        }
        $data = $request->all();


        $User = User::find($id);

        $User->role2 = $data['role2'];
        $User->name = $data['name'];
        $User->contact = $data['contact'];
        $User->customer = (!empty($data['customer'])) ? true : false;
        $User->email = $data['email'];
        if (!empty($data['role2']) && $data['role2'] != 'owner') {
            $User->abn = $data['abn'];
            $User->company = $data['company'];
        } else {
            $User->abn = null;
            $User->company = null;
        }

        $User->save();
        if (!empty($data['same_add']) && $data['same_add'] == 1) {
            $data['bill_address']['billing_first_name'] = $data['ship_address']['shipping_first_name'];
            $data['bill_address']['billing_last_name'] = $data['ship_address']['shipping_last_name'];
            if (!empty($data['ship_address']['shipping_email']))
                $data['bill_address']['billing_email'] = $data['ship_address']['shipping_email'];
            $data['bill_address']['billing_phone'] = $data['ship_address']['shipping_phone'];
            $data['bill_address']['billing_address'] = $data['ship_address']['shipping_address'];
            $data['bill_address']['billing_address_2'] = $data['ship_address']['shipping_address_2'];
            $data['bill_address']['billing_country'] = $data['ship_address']['shipping_country'];
            $data['bill_address']['billing_state'] = $data['ship_address']['shipping_state'];
            $data['bill_address']['billing_city'] = $data['ship_address']['shipping_city'];
            $data['bill_address']['billing_zip'] = $data['ship_address']['shipping_zip'];
        }
        $delivery_details = array_merge($data['ship_address'], $data['bill_address']);
        $delivery_details['user_id'] = $User->id;
        $delivery_details['same_add'] = (!empty($data['same_add']) && $data['same_add'] == 1) ? 1 : null;
        $shippingAdd = ShippingDetail::where('user_id', $User->id)->first();
        if (empty($shippingAdd)) {
            $ship = ShippingDetail::create($delivery_details);
        } else {
            $ship = ShippingDetail::find($shippingAdd->id);
            $ship->update($delivery_details);
        }
        Session::flash('success', 'User has been created!');
        return redirect()->back();
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
        $mt = User::where('id', $id)->first();
        $mt->update(['status' => ($mt->status == '1' ? '0' : '1')]);

        // return redirect()->route('products.index')->with('success', "Product " . (($status) ? 'Activated' : 'Deactivated') . " Successfully.");
        return redirect()->back()->with('success', "Customers " . (($mt->status) ? 'Activated' : 'Deactivated') . " Successfully.");
    }


    public function passwordReset($id)
    {
        return view('admin.customers.passwordReset')->with(['id' => $id]);
    }

    public function passwordResetPost(Request $request, $id)
    {

        $this->validate($request, [
            'password' => 'required|max:15|min:8',
            'password_confirmation' => 'required',
        ]);


        $password = $request->password;
        $user = User::where('id', $id)->first();
        $user->password = Hash::make($password);
        $user->update(); //or $user->save();
        Session::flash('success', 'Password changed');
        return back();
    }

    public function delete($id)
    {
        $this->deleteMethod($id);
        return redirect()->back();
    }

    public function deleteMethod($id)
    {
        $device = User::where('id', $id)->first();
        $device->delete();
    }

}
