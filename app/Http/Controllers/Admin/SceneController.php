<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use App\Models\Device;
use App\Models\Scene;
use App\Models\SceneDeviceRelation;
use App\Models\SceneMetaValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Psr\Log\NullLogger;

class SceneController extends Controller
{

    public $title = 'Scene';


    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request, $type)
    {
        $scene = Scene::where('type',$type)->with(['scene_device_type'=>function ($q){
            return $q->with(['AdminDeviceTypeRelation','AdminSubDeviceTypeRelation']);
        },'sceneMetas'=>function ($query){
            return $query->with(['metaValue']);
        }])->first();
        //$this->pr($scene->toArray());die;
        if(!empty($scene)){
            $devices = Device::with('subDevices')->get();
            //$this->pr($devices->toArray());die;
            return view('admin.scene.index')->with(['scene' => $scene,'devices'=>$devices,'type'=>$type, 'title' => $scene->scene_name]);
        }else{
            Session::flash('error', $this->title . ' not found');
            return redirect()->route('scene.index',$type);
        }
    }

    public function store(Request $request, $type)
    {
        $data = $request->all();
        //$this->pr($data);die;
        $scene = Scene::where('type',$type)->first();
        if(!empty($data['devices'])){
            $dataDeviceRelation = [];
            /*Delete before insert again*/
            SceneDeviceRelation::where('scene_id',$scene->id)->where('user_id',0)->delete();
            foreach ($data['devices'] as $key=>$value){
                $dataVal = explode('-',$value);
                $dataDeviceRelation[$key]['scene_id'] = $scene->id;
                $dataDeviceRelation[$key]['scene_device_type_id'] = $dataVal[0];
                $dataDeviceRelation[$key]['device_id'] = $dataVal[1];
                $dataDeviceRelation[$key]['sub_device_id'] = $dataVal[2];
                $dataDeviceRelation[$key]['user_id'] = 0;
                $dataDeviceRelation[$key]['order_id'] = null;
                $dataDeviceRelation[$key]['created_at'] = date('Y-m-d h:i:s');
                $dataDeviceRelation[$key]['updated_at'] = date('Y-m-d h:i:s');
            }
            /*Insert*/
            SceneDeviceRelation::insert($dataDeviceRelation);
        }
        if(!empty($data['scene_metas'])){
            $dataSceneMeta = [];
            foreach ($data['scene_metas'] as $key=>$value){
                /*Delete according to meta id*/
                SceneMetaValue::where('scene_meta_id',$value)->where('user_id',0)->delete();
                $dataSceneMeta[$key]['scene_meta_id'] = $key;
                $dataSceneMeta[$key]['meta_value'] = $value;
                $dataSceneMeta[$key]['user_id'] = 0;
                $dataSceneMeta[$key]['order_id'] = null;
                $dataSceneMeta[$key]['created_at'] = date('Y-m-d h:i:s');
                $dataSceneMeta[$key]['updated_at'] = date('Y-m-d h:i:s');
            }
            /*Insert*/
            SceneMetaValue::insert($dataSceneMeta);
        }
        Session::flash('success', $this->title . ' has been updated!');
        return redirect()->route('scene.index',$type);
    }
}
