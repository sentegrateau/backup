<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
class ImageUploadController extends BaseController
{
    public function store(Request $request){
        try {
            $request->validate([
                "image" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            if($request->has('image')){
                $image = $request->file('image');
                $teaser_image = time().'.'.$image->getClientOriginalExtension();
                $destination_path = public_path('/images');
                $image->move($destination_path, $teaser_image);
                return $this->sendResponse(env('APP_URL').'/images/'.$teaser_image,'Uploaded Successfully');
            }else {
                return $this->sendError('No found','Image Not Found', 404);
            }


        }catch (\Exception $e){
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }
}
