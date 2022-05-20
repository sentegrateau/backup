<?php

namespace App\Http\Controllers\Front;

use App\Captcha\Captcha;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactUs;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactUsmail;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page)
    {
        $pageData = collect([]);
        $headings = [];
        if (!empty($page)) {
            $pageData = Page::where('slug', $page)->first();
            if (!empty($pageData->name)) {
                $headings['title'] = $pageData->name;
            }
            if (!empty($pageData->meta_key)) {
                $headings['keywords'] = $pageData->meta_key;
            }
            if (!empty($pageData->meta_description)) {
                $headings['description'] = $pageData->meta_description;
            }
        }
        return view('front.page.index', compact('pageData', 'headings'));
    }


    public function saveContactForm(Request $request): JsonResponse
    {


        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
            'captcha_code' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            return response()->json([
                'error' => true,
                'message' => $validator->errors()->all(),
                'data' => ['captcha_img' =>route('home.getCaptchaCode')],
            ], 401);
        }

        $captcha = new Captcha();
        $userCaptcha = filter_var($request->captcha_code, FILTER_SANITIZE_STRING);
        $isValidCaptcha = $captcha->validateCaptcha($userCaptcha, 'captcha_code');
        if ($isValidCaptcha) {
            $contactUs = new ContactUs();
            $contactUs->name = $request->name;
            $contactUs->email = $request->email;
            $contactUs->phone = $request->phone;
            $contactUs->message = $request->message;
            $contactUs->save();

            Mail::to(['enquiries@sentegrate.com.au'])->send(new ContactUsmail($contactUs));



            return response()->json([
                'error' => false,
                'message' => ['Thanks for your message. We will be in touch shortly.'],
                'data' => ['captcha_img' => route('home.getCaptchaCode')],
            ], 200);
        } else {
            return response()->json([
                'error' => true,
                'message' => ['Captcha code wrong'],
                'data' => ['captcha_img' =>  route('home.getCaptchaCode')],
            ], 401);
        }

    }

}
