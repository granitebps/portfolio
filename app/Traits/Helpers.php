<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Intervention\Image\Facades\Image;

trait Helpers
{
    /**
     * Return JSON Response
     *
     * @param bool $success
     * @param string $message
     * @param mixed $result
     * @param integer $code
     * @return JsonResponse
     */
    public static function apiResponse($success, $message = '', $result = [], $code = 200)
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $result
        ];
        return response()->json($response, $code);
    }

    /**
     * Compress image using Image Intervention
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @return \Intervention\Image\Image
     */
    public static function compressImageIntervention($image)
    {
        $jpg = Image::make($image)->encode('jpg', 75);
        return $jpg;
    }
}
