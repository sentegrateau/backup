<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function addToWishList(Request $request)
    {
        $apiCtrl = new ApiController();
        $rule = [
            'slug' => 'required',
        ];
        $data = $request->all();
        if ($apiCtrl->validateData($data, $rule)) {
            $product_id = Product::where('slug', $data['slug'])->first();
            if (!empty($product_id)) {
                $wishList = Wishlist::where(['product_id' => $product_id->id, 'user_id' => Auth::id()]);
                if (!$wishList->count()) {
                    $wishList = new Wishlist;
                    $wishList->user_id = Auth::id();
                    $wishList->product_id = $product_id->id;
                    $wishList->save();
                    $apiCtrl->message = 'Wishlist Added';
                    $apiCtrl->data = ['wishList' => true];
                } else {
                    $wishList->delete();
                    $apiCtrl->message = 'Wishlist Removed';
                    $apiCtrl->data = ['wishList' => false];
                }
            } else {
                $apiCtrl->error = true;
                $apiCtrl->message = 'Product Not Found';
            }
        }
        return $apiCtrl->jsonView();
    }
}
