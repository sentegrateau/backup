<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Cartalyst\Stripe\Stripe;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return Redirect::route('admin.login');
    }

    public function stripe()
    {
        $stripe = new Stripe('sk_test_Vg63xRV0Jwg1FmsprpQbTywK', '2019-05-16');
        try {
            $token = $stripe->tokens()->create([
                'card' => [
                    'number' => '42424242424242425',
                    'exp_month' => 11,
                    'exp_year' => 2020,
                    'cvc' => 111,
                ],
            ]);

            $charge = $stripe->charges()->create([
                'card' => $token['id'],
                'currency' => 'USD',
                'amount' => 20.49,
                'description' => 'wallet',
            ]);

            $this->pr($charge);
            die;
        } catch (Exception $e) {
            $error2 = $e->getMessage();
            $this->pr($error2);
            die;
        }
    }
}
