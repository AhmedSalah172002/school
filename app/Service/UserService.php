<?php

namespace App\Service;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService extends Controller
{
    public function detectRole($user, $role)
    {
        $roleModels = [
            'admin' => Admin::class,
            'teacher' => Teacher::class,
            'student' => Student::class,
        ];

        if (array_key_exists($role, $roleModels)) {
            $roleModels[$role]::create([
                'user_id' => $user->id,
            ]);
        }
    }

    public function loginUser($credentials)
    {
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
            $user = auth()->user();
            $token = JWTAuth::claims(['id' => $user->id])->fromUser($user);
            return $token;
        } catch (JWTException $e) {
            throw new \Exception("could not create token");
        }
    }

    public function getMe()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->errorResponse('User not found', 404);
            } else {
                return $user;
            }
        } catch (JWTException $e) {
            return $this->errorResponse('Invalid token', 400);
        }
    }
}
