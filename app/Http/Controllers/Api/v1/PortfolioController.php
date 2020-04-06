<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Portfolio;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolio = Portfolio::with('pic')->orderBy('created_at', 'desc')->get();
        $portfolio->transform(function ($item) {
            $folderName = Str::slug($item->name, '-');
            $newThumb = asset('images/portfolio/' . $folderName . '/' . $item->thumbnail);
            $item->thumbnail = $newThumb;
            $newType = $item->type === 1 ? 'Personal Project' : 'Client Project';
            $item->type = $newType;

            $item->pic->transform(function ($pic) use ($folderName) {
                $newPic = asset('images/portfolio/' . $folderName . '/' . $pic->pic);
                $pic->pic = $newPic;
                $pic->makeHidden(['created_at', 'updated_at', 'id', 'portfolio_id']);
                return $pic;
            });

            return $item;
        });
        return Helpers::apiResponse(true, '', $portfolio);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'type' => 'required',
            'thumbnail' => 'required|image|max:2048',
            'pic' => 'required',
            'pic.*' => 'image|max:2048',
        ]);
        if ($request->filled('url')) {
            $this->validate($request, [
                'url' => 'url',
            ]);
        }

        DB::beginTransaction();
        try {
            $folderName = Str::slug($request->name, '-');

            $thumbnail = $request->thumbnail;
            $thumbnail_full = $thumbnail->getClientOriginalName();
            $filename = Str::slug(pathinfo($thumbnail_full, PATHINFO_FILENAME));
            $extension = pathinfo($thumbnail_full, PATHINFO_EXTENSION);
            $nama_thumbnail = time() . '_thumbnail-' . $filename . '.' . $extension;

            // Storage::putFileAs('public/images/portfolio/' . $folderName, $thumbnail, $thumbnailName);

            // Hosting
            $thumbnail->storeAs('portfolio/' . $folderName, $nama_thumbnail, 'hosting');

            $portfolio = Portfolio::create([
                'name' => $request->name,
                'desc' => $request->desc,
                'type' => $request->type,
                'url' => $request->url,
                'thumbnail' => $nama_thumbnail
            ]);

            $pic = $request->pic;
            foreach ($pic as $image) {
                $image_full = $image->getClientOriginalName();
                $filename = Str::slug(pathinfo($image_full, PATHINFO_FILENAME));
                $extension = pathinfo($image_full, PATHINFO_EXTENSION);
                $nama_image = time() . '_' . $filename . '.' . $extension;

                // Hosting
                $image->storeAs('portfolio/' . $folderName, $nama_image, 'hosting');

                // Storage::putFileAs('public/images/portfolio/' . $folderName, $image, $imageName);
                $portfolio->pic()->create([
                    'pic' => $nama_image
                ]);
            }

            DB::commit();
            return Helpers::apiResponse(true, 'Portfolio Created', $portfolio);
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'type' => 'required',
        ]);
        if (!empty($request->url)) {
            $this->validate($request, [
                'url' => 'url',
            ]);
        }

        DB::beginTransaction();
        try {
            $portfolio = Portfolio::find($id);
            if (!$portfolio) {
                return Helpers::apiResponse(false, 'Portfolio Not Found', [], 404);
            }

            $oldFolderName = Str::slug($portfolio->name, '-');
            $folderName = Str::slug($request->name, '-');
            if ($request->name != $portfolio->name) {
                // Storage::move('public/images/portfolio/' . $oldFolderName, 'public/images/portfolio/' . $folderName);
                // Storage::deleteDirectory('public/images/portfolio/' . $oldFolderName);

                // Hosting
                Storage::disk('hosting')->move('portfolio/' . $oldFolderName, 'portfolio/' . $folderName);
                Storage::disk('hosting')->deleteDirectory('portfolio/' . $oldFolderName);
            }

            if ($request->hasFile('thumbnail')) {
                $this->validate($request, [
                    'thumbnail' => 'image|max:2048',
                ]);
                $thumbnail = $request->thumbnail;
                $thumbnail_full = $thumbnail->getClientOriginalName();
                $filename = Str::slug(pathinfo($thumbnail_full, PATHINFO_FILENAME));
                $extension = pathinfo($thumbnail_full, PATHINFO_EXTENSION);
                $nama_thumbnail = time() . '_thumbnail-' . $filename . '.' . $extension;
                // if (File::exists(public_path() . '/storage/images/portfolio/' . $oldFolderName)) {

                // Hosting
                if (File::exists(public_path() . '/images/portfolio/' . $oldFolderName)) {
                    Storage::disk('hosting')->delete('portfolio/' . $oldFolderName . '/' . $portfolio->thumbnail);

                    // Storage::delete('public/images/portfolio/' . $oldFolderName . '/' . $portfolio->thumbnail);
                } else {

                    // Hosting
                    Storage::disk('hosting')->delete('portfolio/' . $folderName . '/' . $portfolio->thumbnail);

                    // Storage::delete('public/images/portfolio/' . $folderName . '/' . $portfolio->thumbnail);
                }

                // Hosting
                $thumbnail->storeAs('portfolio/' . $folderName, $nama_thumbnail, 'hosting');

                // Storage::putFileAs('public/images/portfolio/' . $folderName, $thumbnail, $nama_thumbnail);
                $portfolio->update([
                    'thumbnail' => $nama_thumbnail
                ]);
            }

            $portfolio->update([
                'name' => $request->name,
                'desc' => $request->desc,
                'type' => $request->type,
                'url' => $request->url,
            ]);

            if ($request->hasFile('pic')) {
                $this->validate($request, [
                    'pic' => 'required',
                    'pic.*' => 'image|max:2048',
                ]);
                $pic = $request->pic;
                foreach ($portfolio->pic as $value) {
                    // if (File::exists(public_path() . '/storage/images/portfolio/' . $oldFolderName)) {

                    // // Hosting
                    if (File::exists(public_path() . '/images/portfolio/' . $oldFolderName)) {
                        Storage::disk('hosting')->delete('portfolio/' . $oldFolderName . '/' . $value->pic);

                        // Storage::delete('public/images/portfolio/' . $oldFolderName . '/' . $value->pic);
                    } else {
                        // Storage::delete('public/images/portfolio/' . $folderName . '/' . $value->pic);

                        // Hosting
                        Storage::disk('hosting')->delete('portfolio/' . $folderName . '/' . $value->pic);
                    }
                    $value->delete();
                }
                foreach ($pic as $image) {
                    $image_full = $image->getClientOriginalName();
                    $filename = Str::slug(pathinfo($image_full, PATHINFO_FILENAME));
                    $extension = pathinfo($image_full, PATHINFO_EXTENSION);
                    $nama_image = time() . '_' . $filename . '.' . $extension;
                    // Storage::putFileAs('public/images/portfolio/' . $folderName, $image, $imageName);

                    // Hosting
                    $image->storeAs('portfolio/' . $folderName, $nama_image, 'hosting');

                    $portfolio->pic()->create([
                        'pic' => $nama_image
                    ]);
                }
            }

            DB::commit();
            return Helpers::apiResponse(true, 'Portfolio Updated');
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $portfolio = Portfolio::find($id);
            if (!$portfolio) {
                return Helpers::apiResponse(false, 'Portfolio Not Found', [], 404);
            }

            $folderName = Str::slug($portfolio->name, '-');
            // Storage::deleteDirectory('public/images/portfolio/' . $folderName);

            // Hosting
            Storage::disk('hosting')->deleteDirectory('portfolio/' . $folderName);

            $portfolio->pic()->delete();
            $portfolio->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'Portfolio Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }
}
