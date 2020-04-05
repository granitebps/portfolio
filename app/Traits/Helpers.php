<?php

namespace App\Traits;

trait Helpers
{
    public static function apiResponse($success, $message = '', $result = [], $code = 200)
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $result
        ];
        return response()->json($response, $code);
    }
}
