<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->userService->register();
            DB::commit();
            return response()->json(['message' => 'user registered'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function login(LoginUserRequest $request)
    {
        try {
            $token = $this->userService->login();
            if (!$token) {
                return response()->json(['message' => 'wrong credentials'], 401);
            }
            return response()->json(compact('token'), 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'something went wrong!'], 500);
        }
    }

    public function logout()
    {
        try {
            $this->userService->logout();
            return response()->json(['message' => 'user logged out'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'something went wrong!'], 500);
        }
    }
}
