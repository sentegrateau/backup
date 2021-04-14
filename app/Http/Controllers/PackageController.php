<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController as BaseController;

class PackageController extends BaseController
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $packages = Package::orderBy('order','asc')->get();
            return $this->sendResponse($packages, 'All Packages');
            } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
            }
    }
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $rules = [
                'name' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors());
            }
            DB::beginTransaction();
            $package = new Package($request->all());
            $package->save();
            DB::commit();
            return $this->sendResponse('', 'Package created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);

        }
    }
    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            $package = Package::where('id', $id)->firstOrFail();
            return $this->sendResponse($package, 'Requested Data');
            } catch (\Exception $e) {
                return $this->exceptionHandler($e->getMessage(), 500);
            }
    }
    public function update(Request $request, Package $package)
    {
        //
    }
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
             Package::where('id', $id)->delete();
            return $this->sendResponse('','Deleted Successfully');
            } catch (\Exception $e) {
                return $this->exceptionHandler($e->getMessage(), 500);
            }
    }
    /***
     *  To update the order of packages bulk
     */
    public function updateOrder(Request $request)
    {
        try{
            if ($request->has('data')){
                if( is_array($request->data) && count($request->data)){
                    foreach ($request->data as $order){
                        DB::beginTransaction();
                        Package::where('id', $order['id'])->update(['order' => $order['order']]);
                        DB::commit();
                    }
                    return $this->sendResponse('','Order Update Successfully');
                }
            }else{
               return $this->sendError('Invalid Data','',422);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }
}
