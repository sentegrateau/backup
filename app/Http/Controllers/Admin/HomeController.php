<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Models\NewsLetter;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public $title = 'Settings';

    public function index()
    {
        $settings = Setting::paginate(15);
        return view('admin.settings.add')->with(['settings' => $settings, 'title' => $this->title]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        foreach ($data['setting'] as $value) {
            $setting = Setting::find($value['id']);
            $setting->content = $value['value'];
            $setting->save();
        }

        return redirect()->route('settings.index')->with('success', 'Setting updated successfully.');
    }

    public function newsLetters()
    {
        $newsLetters = NewsLetter::orderBy('created_at','desc')->paginate(15);
        return view('admin.newsletters.index')->with(['newsLetters' => $newsLetters, 'title' => $this->title]);
    }

    public function contactList()
    {
        $contacts = Contact::orderBy('created_at','desc')->paginate(15);
        return view('admin.contact.index')->with(['contacts' => $contacts, 'title' => $this->title]);
    }
}
