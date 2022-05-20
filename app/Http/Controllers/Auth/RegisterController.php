<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminVerifyOtherUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        if (!empty($data['role2']) && $data['role2'] != 'owner') {
            $validation = Validator::make($data, [
                'role2' => ['required'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255',  'unique:users,email,NULL,id,deleted_at,NULL'],
                'contact' => ['required', 'string'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
                'company' => ['required', 'string', 'max:255'],
                'abn' => 'required|digits:11'
            ],[
                'email.unique'=>'This email address has already been registered. Please login.'
            ]);
        } else {
            $validation = Validator::make($data, [
                'role2' => ['required'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,NULL,id,deleted_at,NULL'],
                'contact' => ['required', 'string'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
                'user_referred_site' =>'sentegrate'
            ],[
                'email.unique'=>'This email address has already been registered. Please login.'
            ]);
        }
        return $validation;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'role2' => $data['role2'],
            'name' => $data['name'],
            'email' => $data['email'],
            'abn' => $data['abn'],
            'contact' => $data['contact'],
            'company' => $data['company'],
            'password' => Hash::make($data['password']),
            'user_referred_site'=>'sentegrate'

        ]);
        if (!empty($data['role2']) && $data['role2'] == 'owner')
            $user->sendEmailVerificationNotification();
        else {
            Mail::to(config('app.admin_email'))->send(new AdminVerifyOtherUser($user));
        }

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        /* Auth::logout();

         return redirect()->back()->with('success', 'Thanks for registration. We will review and approve your request. Please check your email for confirmation.');
  */
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if ($user->role2 == 'owner')
            return redirect()->back()->with('success', 'Thanks for registration.  We have sent you an email for verification. Please follow instructions in the email to create your account.');
        else
            return redirect()->back()->with('success', 'Thanks for registration. We will review and approve your request. Please check your email for confirmation.');
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
