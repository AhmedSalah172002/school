<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function successResponse($data = [], $statusCode = 200)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], $statusCode);
    }

    protected function handleRequest(callable $callback)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);

        }
    }
    
    protected function errorResponse($message = "Failed", $statusCode = 500)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $statusCode);
    }
}
