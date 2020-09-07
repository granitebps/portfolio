<?php

namespace App\Http\Controllers\Api\v1;

use App\Gallery;
use App\Http\Controllers\Controller;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
            'image.*' => 'max:2048|image'
        ]);

        DB::beginTransaction();
        try {
            $images = $request->image;
            foreach ($images as $key => $image) {
                $pic_full = $image->getClientOriginalName();
                $filename = Str::slug(pathinfo($pic_full, PATHINFO_FILENAME));
                $extension = pathinfo($pic_full, PATHINFO_EXTENSION);
                $nama_pic = time() . '_' . $filename . '.' . $extension;

                $aws_galery = Storage::putFileAs('galeries', $image, $nama_pic);

                Gallery::create([
                    'image' => $aws_galery,
                ]);
            }

            DB::commit();
            return Helpers::apiResponse(true, 'Images Uploaded');
        } catch (\Exception $e) {
            throw $e;
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
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
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }
}