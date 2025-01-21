<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function register(RegisterRequest $request , UserService $userService)
    {
        DB::beginTransaction();
        return $this->handleRequest(function () use ($request , $userService) {
            $validatod = $request->validated();
            $user = User::create([
                'username' => $validatod['username'],
                'email' => $validatod['email'],
                'password' => Hash::make($validatod['password']),
                'phone' => $validatod['phone'],
            ]);
            $userService->detectRole($user , $validatod['role']);
            $token = JWTAuth::fromUser($user);
            DB::commit();

            return $this->successResponse(['user'=> $user , 'token'=>$token], 201);

        });
    }

    public function login(Request $request , UserService $userService)
    {
        return $this->handleRequest(function () use ($request , $userService) {
            $credentials = $request->only('email', 'password');
            $token = $userService->loginUser($credentials);
            return $this->successResponse(['token'=>$token], 201);
        });
    }

    public function getUser(UserService $userService)
    {
        return $this->handleRequest(function () use ($userService) {
            $user = $userService->getMe();
            return $this->successResponse(['user' =>  $user ], 200);
        });
    }

    public function logout()
    {
       return $this->handleRequest(function () {
           JWTAuth::invalidate(JWTAuth::getToken());
           return $this->successResponse(['message' => 'Successfully logged out']);
       });
    }
}
