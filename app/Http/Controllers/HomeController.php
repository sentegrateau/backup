<?php

namespace App\Http\Controllers;

use App\Captcha\Captcha;
use App\Models\Banner;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Video;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;

;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  $jwt = JWT::encode(['test'=>'test'], 't');
        //  $decoded = JWT::decode($jwt, 't', array('HS256'));

        $banners = Banner::where('status', 1)->where('expiry', '>=', date('Y-m-d'))->orderBy('ordr', 'asc')->get();
        $aboutPortfolio = Page::where('status', 1)->where('type', 'about-portfolio')->get();
        $productServices = Page::where('status', 1)->where('type', 'product-&-services')->get();
        $videosSmartHomes = Video::where('status', 1)->where('type', 'discover-smart-homes')->get();
        $videosSentegrateHomes = Video::where('status', 1)->where('type', 'sentegrate-smart-home-products')->get();
        $aboutSentegrate = Page::where('status', 1)->where('type', 'about-sentegrate')->first();
        $whySentegrate = Page::where('status', 1)->where('type', 'why-sentegrate')->first();
        $setting = Setting::where('module_name', 'slider_time')->first();

        return view('home', compact('setting','banners', 'aboutPortfolio', 'productServices', 'videosSmartHomes', 'videosSentegrateHomes', 'aboutSentegrate', 'whySentegrate'));
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }

    public function quote()
    {
        $user = Auth::user();

        $token = JWT::encode(['partner_id' => 'sentegrate', 'user_name' => $user->name, 'user_email' => $user->email, 'role2' => $user->role2, 'role' => $user->role, 'abn' => $user->abn, 'company' => $user->company], 't');
        //  $decoded = JWT::decode($token, 't', array('HS256'));
        return view('front.quote.index', compact('token'));
    }

    public function getCaptchaCode()
    {
        $captcha = $this->getCaptchaStream();

        $img = $captcha['instance']->renderCaptchaImage($captcha['image_stream']);

        return response($img)->header('Content-type', 'image/png');
    }

    public function getCaptchaStream()
    {
        $captcha = new Captcha();
        $captcha_code = $captcha->getCaptchaCode(6);
        $captcha->setSession('captcha_code', $captcha_code);
        $imageData = $captcha->createCaptchaImage($captcha_code);
        return ['instance' => $captcha, 'image_stream' => $imageData];
    }


    public function customization($orderId)
    {

        return view('front.kit.customization', compact('orderId'));
    }

}
