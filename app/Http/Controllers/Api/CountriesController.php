<?php

namespace App\Http\Controllers\Api;

use App\Models\countries;
use App\Models\States;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountriesController extends Controller
{
    public function getStatesList($id){
        $return = States::join('countries as ct','ct.id','states.country_id')->Where('ct.name',$id)->select('states.*','ct.name as country_name')->get();
        return response()->json($return);
    }
}
