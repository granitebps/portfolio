<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;

trait Helpers
{
    /**
     * Return JSON Response
     *
     * @param bool $success
     * @param string $message
     * @param array $result
     * @param integer $code
     * @return \Illuminate\Http\UploadedFile
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
     * Compress Image Using Cloudinary
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @return string
     */
    public static function compressImageCloudinary($image)
    {
        // Compress image using Cloudinary
        $compressedImage = cloudinary()->upload($image->getRealPath(), [
            'folder' => 'temps',
            'transformation' => [
                'quality' => 'auto',
                'fetch_format' => 'auto'
            ]
        ]);

        // Get Image URL, Image PublicID, and Image File from Cloudinary
        $url = $compressedImage->getPath();
        $publicId = $compressedImage->getPublicId();
        $jpg = file_get_contents($url);

        // Remove File from Cloudinary
        cloudinary()->destroy($publicId);

        return $jpg;
    }

    /**
     * Compress image using Cloudinary
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
