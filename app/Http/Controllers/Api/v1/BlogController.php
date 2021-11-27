<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(): JsonResponse
    {
        $blog = Blog::with('user')
            ->latest('created_at')
            ->get();
        return Helpers::apiResponse(true, '', $blog);
    }

    public function store(BlogRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $image = $request->image;
            $nama_image = time() . '_' . md5(uniqid()) . '.jpg';

            $jpg = Helpers::compressImageIntervention($image);

            $aws_blog = 'blog/' . $nama_image;
            Storage::put($aws_blog, $jpg);

            $user = auth()->user();

            $blog = Blog::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'body' => $request->body,
                'image' => $aws_blog,
            ]);

            DB::commit();
            return Helpers::apiResponse(true, 'Blog Created', $blog);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show(int $id, string $slug): JsonResponse
    {
        $blog = Blog::where('id', $id)->where('slug', $slug)->first();
        if (!$blog) {
            return Helpers::apiResponse(false, 'Blog Not Found', [], 404);
        }
        return Helpers::apiResponse(true, '', $blog);
    }

    public function update(BlogRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $blog = Blog::find($id);
            if (!$blog) {
                return Helpers::apiResponse(false, 'Blog Not Found', [], 404);
            }
            if ($request->hasFile('image')) {
                $old_foto = $blog->image;
                $image = $request->image;
                $nama_image = time() . '_' . md5(uniqid()) . '.jpg';

                $jpg = Helpers::compressImageIntervention($image);

                $aws_blog = 'blog/' . $nama_image;
                Storage::put($aws_blog, $jpg);

                Storage::delete($old_foto);

                $blog->image = $aws_blog;
            }
            $blog->title = $request->title;
            $blog->slug = Str::slug($request->title);
            $blog->body = $request->body;
            $blog->save();

            DB::commit();
            return Helpers::apiResponse(true, 'Blog Updated', $blog);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy(int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $blog = Blog::find($id);
            if (!$blog) {
                return Helpers::apiResponse(false, 'Blog Not Found', [], 404);
            }

            Storage::delete($blog->image);

            $blog->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'Blog Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
