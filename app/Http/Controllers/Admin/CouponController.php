<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use App\Models\CouponUsedUser;
use Illuminate\Support\Facades\Session;


class CouponController extends Controller
{
    public $type = ['about-portfolio' => 'About Portfolio', 'product-&-services' => 'Product And Services', 'about-sentegrate' => 'About Sentegrate', 'why-sentegrate' => 'Why Sentegrate'];
    public function index(Request $request){
        $objCoupon = Coupon::paginate(15);
        return view('admin.coupon.index')->with(['coupon' => $objCoupon]);
    }
    public function delete($id)
    {
        Coupon::where('id',$id)->delete();
        return redirect()->route('coupon.index');
    }
    public function create()
    {
        $aType = $this->type;
        $user = User::get();
        return view('admin.coupon.add', compact('aType'))->with(['userList' => $user]);
    }
    public function store(Request $request)
    {
        $data = $request->all();
         //echo "<pre>";print_r($data);die;
        $blog =new Coupon();
        $blog->name = $data['name'];
        $blog->coupon_message = $data['coupon_message'];
        $blog->coupon_code = $data['coupon_code'];
        if(!empty($data['expiry_date'])){
            $blog->expiry_date = $data['expiry_date'];
        }
        if(!empty($data['Limit'])){
            $blog->limit_users = $data['Limit'];
        }
        if(!empty($data['discount'])){
            $blog->discount = $data['discount'];
        }
        if(!empty($data['discount_type'])){
            $blog->discount_type = $data['discount_type'];
        }else{
            $blog->discount_type = 1;
        }
        $blog->no_expiry = (!empty($data['no_expiry']) && $data['no_expiry'] == 'on') ? true : false;
        $blog->is_unlimited = (!empty($data['is_unlimited']) && $data['is_unlimited'] == 'on') ? true : false;
        $blog->is_all_users = (!empty($data['is_all_users']) && $data['is_all_users'] == 'on') ? true : false;
        $blog->disable_coupon = (!empty($data['disable_coupon']) && $data['disable_coupon'] == 'on') ? true : false;
        if(isset($data['users']) && !empty($data['users']) && is_array($data['users'])){
            $blog->users = json_encode($data['users']);
        }
        $blog->save();
        sleep(2);
        Session::flash('success', 'Coupon has been Created!');
        return redirect()->route('coupon.index');
    }
    public function edit($id)
    {
        $aType = $this->type;
        $page = Coupon::where('id', '=', $id)->first();
        $user = User::get();
        //echo "<pre>";print_R($page->users);die;
        return view('admin.coupon.edit')->with(['coupon' => $page, 'aType' => $aType,'there_users'=>json_decode($page->users),'userList' => $user]);
    }
    public function update(Request $request, $id)
    {
        $data = $request->all();
        // echo "<pre>";print_R($data);die;
        $blog = Coupon::find($id);
        //echo "<pre>";print_R($blog);die;
        $blog->name = $data['name'];
        $blog->coupon_message = $data['coupon_message'];
        $blog->coupon_code = $data['coupon_code'];
        if(!empty($data['expiry_date'])){
            $blog->expiry_date = $data['expiry_date'];
        }
        if(!empty($data['Limit'])){
            $blog->limit_users = $data['Limit'];
        }
        if(!empty($data['discount'])){
            $blog->discount = $data['discount'];
        }
        if(!empty($data['discount_type'])){
            $blog->discount_type = $data['discount_type'];
        }else{
            $blog->discount_type = 1;
        }
        $blog->no_expiry = (!empty($data['no_expiry']) && $data['no_expiry'] == 'on') ? true : false;
        $blog->is_unlimited = (!empty($data['is_unlimited']) && $data['is_unlimited'] == 'on') ? true : false;
        $blog->is_all_users = (!empty($data['is_all_users']) && $data['is_all_users'] == 'on') ? true : false;
        $blog->disable_coupon = (!empty($data['disable_coupon']) && $data['disable_coupon'] == 'on') ? true : false;
        if(isset($data['users']) && !empty($data['users']) && is_array($data['users'])){
            $blog->users = json_encode($data['users']);
        }
        $blog->save();

        Session::flash('success', 'Coupon has been updated!');

        return redirect()->route('coupon.index');
    }
    public function getCouponDetails(Request $request){
        $data = $request->all();
        $uId = $data['user_id'];
        $coupon_code = $data['coupon_code'];
        $objCoupon = new Coupon();
        $CouponUsedUser = new CouponUsedUser();
        $getCoupon = Coupon::where('coupon_code',$coupon_code)->first();
        if(!empty($getCoupon)) {
            $limit = $CouponUsedUser/*->where('user_id',$uId)*/
            ->where('coupon_id', $getCoupon->id)->count();

            // return $data;
            $dateToday = date('Y-m-d');
            $where = "
        (users like " . "'" . '%"' . $uId . '"' . "%' or is_all_users = true)
         and
        (limit_users > $limit or is_unlimited = true)
         and
        (expiry_date >= $dateToday or no_expiry = true)
         and
        disable_coupon = false
         and
        coupon_code = '$coupon_code'
        ";
            $couponData = $objCoupon->whereRaw($where)->first();
            //$this->pr($couponData);die;
            //$disc = isset($couponData['discount']) && !empty($couponData['discount'])?$couponData['discount']:0;
            if (!empty($couponData) > 0) {
                $disc = !empty($couponData->discount) ? $couponData->discount : 0;
                $return = response()->json(['data' => 'coupon applied for you', 'type' => $couponData->discount_type, 'discount' => $disc, 'code' => 200], 200);
            } else {
                $return = response()->json(['data' => 'coupon is not applicable', 'discount' => 0, 'code' => 404], 200);
            }
        }else{
            $return = response()->json(['data' => 'invalid coupon code', 'discount' => 0, 'code' => 404], 200);
        }
        return $return;
    }
}
