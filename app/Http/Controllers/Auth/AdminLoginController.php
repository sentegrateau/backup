<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class AdminLoginController extends Controller
{
    /**
     * Show the application’s login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {

        return view('auth.admin.login');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->role==2 && $user->status != 1) {
            //Auth::logout();
            Auth::guard('admin')->logout();
            $messageBag = new MessageBag;
            $messageBag->add('email', 'The email address is inactive');
            return redirect()->back()->withErrors($messageBag)->withInput();
        }
    }


}
