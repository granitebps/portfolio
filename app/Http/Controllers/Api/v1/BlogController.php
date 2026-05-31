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
        $blog = DB::transaction(function () use ($request) {
            $image = $request->image;
            $nama_image = time() . '_' . md5(uniqid()) . '.jpg';

            $jpg = Helpers::compressImageIntervention($image);

            $aws_blog = 'blog/' . $nama_image;
            Storage::put($aws_blog, $jpg);

            return Blog::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'body' => $request->body,
                'image' => $aws_blog,
            ]);
        });

        return Helpers::apiResponse(true, 'Blog Created', $blog);
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
        $blog = Blog::find($id);
        if (!$blog) {
            return Helpers::apiResponse(false, 'Blog Not Found', [], 404);
        }

        DB::transaction(function () use ($request, $blog) {
            if ($request->hasFile('image')) {
                $old_foto = $blog->image;
                $nama_image = time() . '_' . md5(uniqid()) . '.jpg';

                $jpg = Helpers::compressImageIntervention($request->image);

                $aws_blog = 'blog/' . $nama_image;
                Storage::put($aws_blog, $jpg);
                Storage::delete($old_foto);

                $blog->image = $aws_blog;
            }
            $blog->title = $request->title;
            $blog->slug = Str::slug($request->title);
            $blog->body = $request->body;
            $blog->save();
        });

        return Helpers::apiResponse(true, 'Blog Updated', $blog);
    }

    public function destroy(int $id): JsonResponse
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return Helpers::apiResponse(false, 'Blog Not Found', [], 404);
        }

        DB::transaction(function () use ($blog) {
            Storage::delete($blog->image);
            $blog->delete();
        });

        return Helpers::apiResponse(true, 'Blog Deleted');
    }
}
