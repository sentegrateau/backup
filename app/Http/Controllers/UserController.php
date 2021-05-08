<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    public function findOrCreateUser(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $find_user = User::where([
                ['name' , '=', $request['name']],
                ['email' , '=', $request['email']],
                ['role' , '=', $request['role']]
            ])->first();
            if (!$find_user) {
                DB::beginTransaction();
                $user = User::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'role' => $request['role']
                ]);
                DB::commit();
                return $this->sendResponse($user, 'Requested Data');
            } else {
                return $this->sendResponse($find_user, 'Requested Data');
            }

        }catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }
}
