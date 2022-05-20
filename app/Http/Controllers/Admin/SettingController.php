<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public $title = 'Settings';

    public function index()
    {
        $settings = Setting::orderBy('id','asc')->paginate(15);
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
            $setting->pass_tooltip =$data['pass_tooltip'];
            $setting->save();
        }    
        return redirect()->route('settings.index')->with('success', 'Setting updated successfully.');
    }
}
