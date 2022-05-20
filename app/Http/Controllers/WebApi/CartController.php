<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use View;


class CartController extends Controller
{
    /*public function addCart(Request $request)
    {
        $apiCtrl = new ApiController();
        $rule = [
            'product_id' => 'required',
            'qty' => 'required',
        ];
        $data = $request->all();
        if ($apiCtrl->validateData($data, $rule)) {
            $remainStock = Stock::where('product_id', $data['product_id']);
            $remainStock = $remainStock->sum(DB::raw('stock - sold_stock'));
            $getCartStock = Cart::where('product_id', $data['product_id'])->sum(DB::raw('qty'));

            if ($data['qty'] <= ($remainStock-$getCartStock)) {
                $cartData = [
                    'product_id' => $data['product_id'],
                ];
                if (!empty(Auth::user()->id)) {
                    $cartData['user_id'] = Auth::user()->id;
                } else {
                    $cartData['guest_id'] = $data['guest_id'];
                }
                $cartValue = $cartData;
                $cartValue['qty'] = $data['qty'];

                $getCart = Cart::where($cartData)->first();
                if(!empty($getCart)){
                    $cartValue['qty'] = ($data['qty']+$getCart->qty);
                }
                $cart = Cart::updateOrCreate(
                    $cartData,
                    $cartValue
                );
                unset($cart->user_id);
                $cart->html = view('layouts.elements.header-cart', ['cart' => Cart::cart()])->render();
                $apiCtrl->data = $cart;
            } else {
                $apiCtrl->message = 'Only ' . $remainStock . ' items are available';
                $apiCtrl->code = 401;
                $apiCtrl->error = true;
            }
        }
        return $apiCtrl->jsonView();
    }*/

    public function addCart(Request $request)
    {
        $apiCtrl = new ApiController();
        $rule = [
            'product_id' => 'required',
            'qty' => 'required',
        ];
        $data = $request->all();
        if ($apiCtrl->validateData($data, $rule)) {
            $remainStock = Stock::where('product_id', $data['product_id']);
            $remainStock = $remainStock->sum(DB::raw('stock - sold_stock'));

            if ($data['qty'] <= $remainStock) {
                $cartData = [
                    'product_id' => $data['product_id'],
                ];
                if (!empty(Auth::user()->id)) {
                    $cartData['user_id'] = Auth::user()->id;
                } else {
                    $cartData['guest_id'] = $data['guest_id'];
                }
                $cartValue = $cartData;
                $cartValue['qty'] = $data['qty'];
                $cart = Cart::updateOrCreate(
                    $cartData,
                    $cartValue
                );
                unset($cart->user_id);
                $cart->html = view('layouts.elements.header-cart', ['cart' => Cart::cart()])->render();
                $apiCtrl->data = $cart;
            } else {
                $apiCtrl->message = 'Only ' . $remainStock . ' items are available';
                $apiCtrl->code = 401;
                $apiCtrl->error = true;
            }
        }
        return $apiCtrl->jsonView();
    }

    public function getQuantity(Request $request){
        $apiCtrl = new ApiController();
        $rule = [
            'product_id' => 'required',
            'qty' => 'required',
        ];
        $data = $request->all();
        if ($apiCtrl->validateData($data, $rule)) {
            $remainStock = Stock::where('product_id', $data['product_id']);
            $remainStock = $remainStock->sum(DB::raw('stock - sold_stock'));
            /*if (!empty(Auth::user()->id)) {
                $cartStock = Cart::where('user_id', Auth::user()->id)->sum('qty');
            } else {
                $cartStock = Cart::where('guest_id', $data['guest_id'])->sum('qty');
            }*/
            //$cartStock = $data['is_cart']=="true"?0:$cartStock;

            $cartStock = 0;
            if ($data['qty'] <= ($remainStock-$cartStock)) {
                if( $data['is_cart'] == "true"){
                    //update cart for particular product
                    $cartData = ['product_id' => $data['product_id']];
                    if (!empty(Auth::user()->id)) {
                        $cartData['user_id'] = Auth::user()->id;
                    } else {
                        $cartData['guest_id'] = $data['guest_id'];
                    }
                    $cartValue = $cartData;
                    $cartValue['qty'] = $data['qty'];
                    Cart::updateOrCreate($cartData,$cartValue);

                    //get cart sum to update the cart amounts
                    if (!empty(Auth::user()->id)) {
                        $cart = Cart::where('user_id', Auth::id())->where('product_id', $data['product_id'])->select('carts.*', DB::raw('(SELECT IFNULL(SUM(products.special_price*carts.qty), 0) from carts join products on products.id = carts.product_id  where user_id="' . Auth::id() . '") AS cart_price'))->first();
                    } else {
                        $cart = Cart::where('guest_id', $data['guest_id'])->where('product_id', $data['product_id'])->select('carts.*', DB::raw('(SELECT IFNULL(SUM(products.special_price*carts.qty), 0) from carts join products on products.id = carts.product_id  where guest_id="' . $data['guest_id'] . '") AS cart_price'))->first();
                    }
                    $apiCtrl->data = ['cart_price'=>$cart->cart_price];
                }
                $apiCtrl->message = 'items are available';
                $apiCtrl->code = 200;
                $apiCtrl->error = false;
            }else{
                $apiCtrl->message = 'Not in stock';
                $apiCtrl->code = 401;
                $apiCtrl->error = true;
            }
        }
        return $apiCtrl->jsonView();
    }

    public function getCart(Request $request)
    {
        if (!empty(Auth::user()->id)) {
            $this->data = Cart::where('user_id', Auth::user()->id)->with('variantOption')->exclude(['user_id'])->get();
        } else {
            $this->data = Cart::where('guest_id', $request->guest_id)->with('variantOption')->exclude(['user_id'])->get();
        }

        return $this->jsonView();
    }

    public function removeCart(Request $request)
    {
        $rule = [
            'cart_id' => 'required',
        ];
        $data = $request->all();
        if ($this->validateData($data, $rule)) {

            $cart = Cart::where('id', $data['cart_id']);
            if (!empty(Auth::user()->id)) {
                $cart->where('user_id', Auth::user()->id);
            } else {
                $cart->where('guest_id', $data['guest_id']);
            }
            if ($cart->count()) {
                $cart->delete();
                $this->message = 'Cart item removed';
            } else {
                $this->message = 'Cart item not exits.';
                $this->code = 401;
            }
        }
        return $this->jsonView();
    }

    public function updateCart(Request $request)
    {
        $rule = [
            'products' => 'required',
        ];
        $data = $request->all();
        if ($this->validateData($data, $rule)) {
            $error = false;
            foreach ($data['products'] as $product) {
                $remainStock = Stock::where('product_id', $product['product_id']);
                if ($product['variations'] == 1) {
                    $remainStock->where('variant_option_id', $product['variant_option_id']);
                }
                $remainStock = $remainStock->sum(DB::raw('stock - sold_stock'));

                if ($product['qty'] <= $remainStock) {
                    $cart = Cart::where('id', $product['id']);
                    if (!empty(Auth::user()->id)) {
                        $cart->where('user_id', Auth::user()->id);
                    } else {
                        $cart->where('guest_id', $data['guest_id']);
                    }
                    $cart->update(['qty' => $product['qty']]);
                } else {
                    $error = true;
                    $this->message = '"' . $product['product_value']['name'] . '" Only ' . $remainStock . ' items are available';
                    $this->code = 401;
                }
            }
            if (!$error)
                $this->message = 'Cart Updated';

        }
        return $this->jsonView();
    }

    public function removeAllCart(Request $request)
    {
        Cart::where('user_id', Auth::user()->id)->delete();
        return $this->jsonView();
    }
}
