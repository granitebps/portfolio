<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Portfolio;
use App\PortfolioPic;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index()
    {
        if (Cache::has('portfolio')) {
            $portfolio = Cache::get('portfolio');
        } else {
            $portfolio = Portfolio::with('pic')->orderBy('created_at', 'desc')->get();
            $portfolio->transform(function ($item) {
                $newThumb = Storage::url($item->thumbnail);
                $item->thumbnail = $newThumb;

                $item->type = (int)$item->type;

                if (is_null($item->url)) {
                    $item->url = '';
                }

                $item->pic->transform(function ($pic) {
                    $newPic = Storage::url($pic->pic);
                    $pic->pic = $newPic;
                    $pic->makeHidden(['portfolio_id', 'created_at', 'updated_at']);
                    return $pic;
                });

                return $item;
            });
            Cache::put('portfolio', $portfolio, now()->addDay());
        }
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

            $aws_thumbnail = Storage::putFileAs('portfolio/' . $folderName, $thumbnail, $nama_thumbnail);

            $portfolio = Portfolio::create([
                'name' => $request->name,
                'desc' => $request->desc,
                'type' => $request->type,
                'url' => $request->url,
                'thumbnail' => $aws_thumbnail
            ]);

            $pic = $request->pic;
            foreach ($pic as $image) {
                $image_full = $image->getClientOriginalName();
                $filename = Str::slug(pathinfo($image_full, PATHINFO_FILENAME));
                $extension = pathinfo($image_full, PATHINFO_EXTENSION);
                $nama_image = time() . '_' . $filename . '.' . $extension;

                $aws_pic = Storage::putFileAs('portfolio/' . $folderName, $image, $nama_image);

                $portfolio->pic()->create([
                    'pic' => $aws_pic
                ]);
            }

            Cache::forget('portfolio');

            DB::commit();
            return Helpers::apiResponse(true, 'Portfolio Created', $portfolio);
        } catch (\Exception $e) {
            \Sentry\captureException($e);
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
        if ($request->filled('url')) {
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
            if ($request->name !== $portfolio->name) {
                $oldImages = Storage::allFiles('portfolio/' . $oldFolderName);
                foreach ($oldImages as $oldImage) {
                    $newLoc = str_replace('portfolio/' . $oldFolderName, 'portfolio/' . $folderName, $oldImage);
                    Storage::copy($oldImage, $newLoc);
                }

                $newThumb = str_replace('portfolio/' . $oldFolderName, 'portfolio/' . $folderName, $portfolio->thumbnail);
                $portfolio->update([
                    'thumbnail' => $newThumb
                ]);

                foreach ($portfolio->pic as $value) {
                    $newPic = str_replace('portfolio/' . $oldFolderName, 'portfolio/' . $folderName, $value->pic);
                    $value->pic = $newPic;
                    $value->save();
                }

                Storage::deleteDirectory('portfolio/' . $oldFolderName);
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

                $oldThubmnail = str_replace('portfolio/' . $oldFolderName, 'portfolio/' . $folderName, $portfolio->thumbnail);
                Storage::delete($oldThubmnail);
                $aws_thumbnail = Storage::putFileAs('portfolio/' . $folderName, $thumbnail, $nama_thumbnail);

                $portfolio->update([
                    'thumbnail' => $aws_thumbnail
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
                foreach ($pic as $image) {
                    $image_full = $image->getClientOriginalName();
                    $filename = Str::slug(pathinfo($image_full, PATHINFO_FILENAME));
                    $extension = pathinfo($image_full, PATHINFO_EXTENSION);
                    $nama_image = time() . '_' . $filename . '.' . $extension;

                    $aws_pic = Storage::putFileAs('portfolio/' . $folderName, $image, $nama_image);

                    $portfolio->pic()->create([
                        'pic' => $aws_pic
                    ]);
                }
            }

            Cache::forget('portfolio');

            DB::commit();
            return Helpers::apiResponse(true, 'Portfolio Updated');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
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

            Storage::deleteDirectory('portfolio/' . $folderName);

            $portfolio->pic()->delete();
            $portfolio->delete();

            Cache::forget('portfolio');

            DB::commit();
            return Helpers::apiResponse(true, 'Portfolio Deleted');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function destroy_photo($id)
    {
        DB::beginTransaction();
        try {
            $portfolio = PortfolioPic::find($id);
            if (!$portfolio) {
                return Helpers::apiResponse(false, 'Portfolio Picture Not Found', [], 404);
            }

            Storage::delete($portfolio->pic);

            $portfolio->delete();

            Cache::forget('portfolio');

            DB::commit();
            return Helpers::apiResponse(true, 'Portfolio Picture Deleted');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }
}
