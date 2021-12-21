<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;
use App\Http\Resources\GalleryResource;
use App\Models\Gallery;
use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(): JsonResponse
    {
        $galeries = Gallery::latest('created_at')->get();
        return Helpers::apiResponse(true, '', GalleryResource::collection($galeries));
    }

    public function store(GalleryRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $input = $request->validated();

            $data = Gallery::create([
                'name' => $input['name'],
                'file' => $input['file'],
                'ext' => $input['ext'],
                'size' => $input['size']
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

    public function getAwsUrl(Request $request): JsonResponse
    {
        $data = $request->validate([
            'extension' => 'required|string',
        ]);

        // @phpstan-ignore-next-line
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $bucket = config('filesystems.disks.s3.bucket');

        $filename = Str::uuid() . '.' . $data['extension'];
        $path = 'galeries/' . $filename;

        $command = $client->getCommand('PutObject', [
            'Bucket' => $bucket,
            'Key' => $path, // file name in s3 bucket which you want to access
            'ACL' => 'public-read',
        ]);

        $request = $client->createPresignedRequest($command, '+60 minutes');

        $url = (string) $request->getUri();

        return Helpers::apiResponse(true, 'Generate AWS URL Successfully', [
            'url' => $url,
            'file' => $path,
            'name' => $filename,
            'content_url' => Storage::url($filename)
        ]);
    }
}
