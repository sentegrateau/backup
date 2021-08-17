<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        try {
            $user = User::query();
            if ($request->has('role')) {
                $user->where('role', $request['role']);
            }

            $users = $user->get();
            return $this->sendResponse($users,'Requested Data');

        }catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    public function store(Request $request): JsonResponse {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required'
        ];
        try {
            $validation = Validator::make($request->all(), $rules);
            if ($validation->fails()) {
                return $this->sendError('Validation Errors', $validation->errors());
            }
            DB::beginTransaction();
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'role' => $request['role']
            ]);
            DB::commit();
            return $this->sendResponse($user,'User created successfully');

        }catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    public function update(Request $request, User $user): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = $user->update($request->all());
            DB::commit();
            return $this->sendResponse($user, 'User Updated Successfully');
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();
            return $this->sendResponse('', 'User deleted successfully');

        }catch (\Exception $e) {
            return $this->exceptionHandler($e->getMessage(), 500);
        }
    }
    public function findOrCreateUser(Request $request): JsonResponse
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
