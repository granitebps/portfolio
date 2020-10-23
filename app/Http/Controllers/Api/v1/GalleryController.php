<?php

namespace App\Http\Controllers\Api\v1;

use App\Gallery;
use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;
use App\Traits\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $tech = Gallery::latest('created_at')->get();
        $tech->makeHidden(['updated_at']);
        $tech->transform(function ($item) {
            $item->name = $item->image;
            $newFoto = Storage::url($item->image);
            $item->image = $newFoto;
            return $item;
        });
        return Helpers::apiResponse(true, '', $tech);
    }

    public function store(GalleryRequest $request)
    {
        DB::beginTransaction();
        try {
            $images = $request->image;
            foreach ($images as $image) {
                $nama_pic = time() . '_' . md5(uniqid()) . '.jpg';

                $jpg = Helpers::compressImageCloudinary($image);

                // Save File
                $awsPath = 'galeries/' . $nama_pic;
                Storage::put($awsPath, $jpg);

                Gallery::create([
                    'image' => $awsPath,
                ]);
            }

            DB::commit();
            return Helpers::apiResponse(true, 'Images Uploaded');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $gallery = Gallery::find($id);
            if (!$gallery) {
                return Helpers::apiResponse(false, 'Image Not Found', [], 404);
            }

            Storage::delete($gallery->image);

            $gallery->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'Image Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
