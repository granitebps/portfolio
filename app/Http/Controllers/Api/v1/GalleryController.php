<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(): JsonResponse
    {
        $galeries = Gallery::latest('created_at')->get();
        return Helpers::apiResponse(true, '', $galeries);
    }

    public function store(GalleryRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $file = $request->file;
            $fileFullname = $file->getClientOriginalName();
            $filename = Str::slug(pathinfo($fileFullname, PATHINFO_FILENAME));
            $ext = pathinfo($fileFullname, PATHINFO_EXTENSION);
            $size = $file->getSize();

            $nama_file = time() . '_' . $filename . '.' . $ext;

            // Save File
            $awsPath = 'galeries/' . $nama_file;
            Storage::putFileAs('galeries', $file, $nama_file);

            $data = Gallery::create([
                'name' => $nama_file,
                'file' => $awsPath,
                'ext' => $ext,
                'size' => $size
            ]);

            DB::commit();
            return Helpers::apiResponse(true, 'File Uploaded', $data);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy(int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $gallery = Gallery::find($id);
            if (!$gallery) {
                return Helpers::apiResponse(false, 'File Not Found', [], 404);
            }

            Storage::delete($gallery->file);

            $gallery->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'File Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
