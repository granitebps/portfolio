<?php

namespace App\Http\Controllers\Api\v1;

use App\Blog;
use App\Http\Controllers\Controller;
use App\Traits\Helpers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blog = Blog::with('user')->latest('created_at')->get();
        $blog->makeHidden(['updated_at']);
        $blog->transform(function ($item) {
            $newFoto = asset('images/blog/' . $item->image);
            $newFoto = Storage::url($item->image);
            $item->image = $newFoto;
            $item->user->makeHidden('token');
            return $item;
        });
        return Helpers::apiResponse(true, '', $blog);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'body' => 'required',
            'image' => 'required|max:2048|image',
        ]);

        DB::beginTransaction();
        try {
            $image = $request->image;
            $image_full = $image->getClientOriginalName();
            $filename = Str::slug(pathinfo($image_full, PATHINFO_FILENAME));
            $extension = pathinfo($image_full, PATHINFO_EXTENSION);
            $nama_image = time() . '_' . $filename . '.' . $extension;

            $aws_blog = Storage::putFileAs('blog', $image, $nama_image);

            $user = User::where('email', $request->payload->sub)->first();

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
            throw $e;
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return Helpers::apiResponse(false, 'Blog Not Found', [], 404);
        }
        $newFoto = Storage::url($blog->image);
        $blog->image = $newFoto;
        $blog->user->makeHidden('token');
        return Helpers::apiResponse(true, '', $blog);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'body' => 'required|',
            'image' => 'nullable|max:2048|image',
        ]);

        DB::beginTransaction();
        try {
            $blog = Blog::find($id);
            if (!$blog) {
                return Helpers::apiResponse(false, 'Blog Not Found', [], 404);
            }
            if ($request->hasFile('image')) {
                $old_foto = $blog->image;
                $image = $request->image;
                $image_full = $image->getClientOriginalName();
                $filename = Str::slug(pathinfo($image_full, PATHINFO_FILENAME));
                $extension = pathinfo($image_full, PATHINFO_EXTENSION);
                $nama_image = time() . '_' . $filename . '.' . $extension;

                // Image upload for shared hosting
                $aws_blog = Storage::putFileAs('blog', $image, $nama_image);
                Storage::delete($old_foto);

                $blog->update([
                    'image' => $aws_blog,
                ]);
            }
            $blog->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'body' => $request->body,
            ]);

            DB::commit();
            return Helpers::apiResponse(true, 'Blog Updated', $blog);
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
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
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }
}
