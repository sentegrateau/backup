<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactUsController extends Controller
{
    public function index()
    {
        $contact_us = ContactUs::orderBy('created_at','desc')->paginate(15);
        return view('admin.contactUs.index')->with(['contact_us' => $contact_us]);
    }

    public function delete($id)
    {
        $news = ContactUs::where('id', $id)->first();
        $news->delete();
        return back();
    }


    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        ContactUs::whereIn('id', explode(",", $ids))->delete();
        return response()->json(['success' => "ContactUs Deleted successfully."]);
    }
}
