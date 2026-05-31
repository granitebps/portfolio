<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PortfolioRequest;
use App\Models\Portfolio;
use App\Models\PortfolioPic;
use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index(): JsonResponse
    {
        $portfolio = Portfolio::with('pic')
            ->orderBy('created_at', 'desc')
            ->get();
        return Helpers::apiResponse(true, '', $portfolio);
    }

    public function store(PortfolioRequest $request): JsonResponse
    {
        $portfolio = DB::transaction(function () use ($request) {
            $folderName = Str::slug($request->name, '-');

            $nama_thumbnail = time() . '_thumbnail-' . md5(uniqid()) . '.jpg';
            $jpg = Helpers::compressImageIntervention($request->thumbnail);
            $aws_thumbnail = 'portfolio/' . $folderName . '/' . $nama_thumbnail;
            Storage::put($aws_thumbnail, $jpg);

            $portfolio = Portfolio::create([
                'name' => $request->name,
                'desc' => $request->desc,
                'type' => $request->type,
                'url' => $request->url,
                'thumbnail' => $aws_thumbnail
            ]);

            foreach ($request->pic as $image) {
                $nama_image = time() . '_' . md5(uniqid()) . '.jpg';
                $jpg = Helpers::compressImageIntervention($image);
                $aws_pic = 'portfolio/' . $folderName . '/' . $nama_image;
                Storage::put($aws_pic, $jpg);
                $portfolio->pic()->create(['pic' => $aws_pic]);
            }

            return $portfolio;
        });

        return Helpers::apiResponse(true, 'Portfolio Created', $portfolio);
    }

    public function update(PortfolioRequest $request, int $id): JsonResponse
    {
        $portfolio = Portfolio::find($id);
        if (!$portfolio) {
            return Helpers::apiResponse(false, 'Portfolio Not Found', [], 404);
        }

        DB::transaction(function () use ($request, $portfolio) {
            $oldFolderName = Str::slug($portfolio->name, '-');
            $folderName = Str::slug($request->name, '-');

            if ($request->name !== $portfolio->name) {
                foreach (Storage::allFiles('portfolio/' . $oldFolderName) as $oldImage) {
                    $newLoc = str_replace('portfolio/' . $oldFolderName, 'portfolio/' . $folderName, $oldImage);
                    Storage::copy($oldImage, $newLoc);
                }

                $portfolio->thumbnail = str_replace('portfolio/' . $oldFolderName, 'portfolio/' . $folderName, $portfolio->thumbnail);

                foreach ($portfolio->pic as $value) {
                    $value->pic = str_replace('portfolio/' . $oldFolderName, 'portfolio/' . $folderName, $value->pic);
                    $value->save();
                }

                Storage::deleteDirectory('portfolio/' . $oldFolderName);
            }

            if ($request->hasFile('thumbnail')) {
                $nama_thumbnail = time() . '_thumbnail-' . md5(uniqid()) . '.jpg';
                $jpg = Helpers::compressImageIntervention($request->thumbnail);
                $oldThubmnail = str_replace('portfolio/' . $oldFolderName, 'portfolio/' . $folderName, $portfolio->thumbnail);
                Storage::delete($oldThubmnail);
                $aws_thumbnail = 'portfolio/' . $folderName . '/' . $nama_thumbnail;
                Storage::put($aws_thumbnail, $jpg);
                $portfolio->thumbnail = $aws_thumbnail;
            }

            $portfolio->name = $request->name;
            $portfolio->desc = $request->desc;
            $portfolio->type = $request->type;
            $portfolio->url = $request->url;
            $portfolio->save();

            if ($request->hasFile('pic')) {
                foreach ($request->pic as $image) {
                    $nama_image = time() . '_' . md5(uniqid()) . '.jpg';
                    $jpg = Helpers::compressImageIntervention($image);
                    $aws_pic = 'portfolio/' . $folderName . '/' . $nama_image;
                    Storage::put($aws_pic, $jpg);
                    $portfolio->pic()->create(['pic' => $aws_pic]);
                }
            }
        });

        return Helpers::apiResponse(true, 'Portfolio Updated', $portfolio);
    }

    public function destroy(int $id): JsonResponse
    {
        $portfolio = Portfolio::find($id);
        if (!$portfolio) {
            return Helpers::apiResponse(false, 'Portfolio Not Found', [], 404);
        }

        DB::transaction(function () use ($portfolio) {
            $portfolio->pic()->delete();
            $portfolio->delete();
        });

        return Helpers::apiResponse(true, 'Portfolio Deleted');
    }

    public function destroy_photo(int $id): JsonResponse
    {
        $portfolio = PortfolioPic::find($id);
        if (!$portfolio) {
            return Helpers::apiResponse(false, 'Portfolio Picture Not Found', [], 404);
        }

        DB::transaction(function () use ($portfolio) {
            Storage::delete($portfolio->pic);
            $portfolio->delete();
        });

        return Helpers::apiResponse(true, 'Portfolio Picture Deleted');
    }
}
