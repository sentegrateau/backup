<?php

namespace App\Http\Controllers\Api;

use App\Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PackageController extends BaseController
{
    /**
     * Add a new pet to the store
     *
     * @OA\Post(
     *     path="/pet",
     *     tags={"pet"},
     *     operationId="addPet",
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"petstore_auth": {"write:pets", "read:pets"}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Pet"}
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $package = Package::query();
            if ($request->has('ifPkgNoRoom')) {
                $package = $package->join('package__room__device', 'packages.id', '=', 'package__room__device.package_id')
                    ->select('packages.*');
            }

            if ($request->has('status')) {
                $package->where('status', $request['status']);
            }
            $packages = $package->groupBy('packages.id')->orderBy('order', 'asc')->get();
            return $this->sendResponse($packages, 'All Packages');
        } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    public function store(Request $request): JsonResponse
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

    public function show($id): JsonResponse
    {
        try {
            $package = Package::FindOrFail($id);
            return $this->sendResponse($package, 'Requested Data');
        } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    public function update(Request $request, Package $package): JsonResponse
    {
        try {
            if ($request->has('activation')) {
                DB::beginTransaction();
                $update = $package->update(['status' => $request['activation']]);
                DB::commit();
                if ($update) {
                    return $this->sendResponse('', 'Status updated successfully');
                }
            } else {
                DB::beginTransaction();
                $update = $package->update($request->all());
                DB::commit();
                if ($update) {
                    return $this->sendResponse('', 'Package updated successfully');
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Package::where('id', $id)->delete();
            return $this->sendResponse('', 'Deleted Successfully');
        } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    /***
     *  To update the order of packages bulk
     */
    public function updateOrder(Request $request): JsonResponse
    {
        try {
            if ($request->has('data')) {
                if (is_array($request->data) && count($request->data)) {
                    foreach ($request->data as $order) {
                        DB::beginTransaction();
                        Package::where('id', $order['id'])->update(['order' => $order['order']]);
                        DB::commit();
                    }
                    return $this->sendResponse('', 'Order Update Successfully');
                }
            } else {
                return $this->sendError('Invalid Data', '', 422);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }


    public function getPackageWithDevices(Request $request): JsonResponse
    {
        try {
            $package = Package::query();
            $packages = $package->with(['devices'=>function($q){
                return $q->groupBy('id');
            }])->orderBy('order', 'asc')->get();
            return $this->sendResponse($packages, 'All Packages');
        } catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }
}
