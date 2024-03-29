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
        DB::beginTransaction();
        try {
            $folderName = Str::slug($request->name, '-');

            $thumbnail = $request->thumbnail;
            $nama_thumbnail = time() . '_thumbnail-' . md5(uniqid()) . '.jpg';

            $jpg = Helpers::compressImageIntervention($thumbnail);

            $aws_thumbnail = 'portfolio/' . $folderName . '/' . $nama_thumbnail;
            Storage::put($aws_thumbnail, $jpg);

            $portfolio = Portfolio::create([
                'name' => $request->name,
                'desc' => $request->desc,
                'type' => $request->type,
                'url' => $request->url,
                'thumbnail' => $aws_thumbnail
            ]);

            $pic = $request->pic;
            foreach ($pic as $image) {
                $nama_image = time() . '_' . md5(uniqid()) . '.jpg';

                $jpg = Helpers::compressImageIntervention($image);

                $aws_pic = 'portfolio/' . $folderName . '/' . $nama_image;
                Storage::put($aws_pic, $jpg);

                $portfolio->pic()->create([
                    'pic' => $aws_pic
                ]);
            }

            DB::commit();
            return Helpers::apiResponse(true, 'Portfolio Created', $portfolio);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(PortfolioRequest $request, int $id): JsonResponse
    {
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
                $portfolio->thumbnail = $newThumb;

                foreach ($portfolio->pic as $value) {
                    $newPic = str_replace('portfolio/' . $oldFolderName, 'portfolio/' . $folderName, $value->pic);
                    $value->pic = $newPic;
                    $value->save();
                }

                Storage::deleteDirectory('portfolio/' . $oldFolderName);
            }

            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->thumbnail;
                $nama_thumbnail = time() . '_thumbnail-' . md5(uniqid()) . '.jpg';

                $jpg = Helpers::compressImageIntervention($thumbnail);

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
                $pic = $request->pic;
                foreach ($pic as $image) {
                    $nama_image = time() . '_' . md5(uniqid()) . '.jpg';

                    $jpg = Helpers::compressImageIntervention($image);

                    $aws_pic = 'portfolio/' . $folderName . '/' . $nama_image;
                    Storage::put($aws_pic, $jpg);

                    $portfolio->pic()->create([
                        'pic' => $aws_pic
                    ]);
                }
            }

            DB::commit();
            return Helpers::apiResponse(true, 'Portfolio Updated', $portfolio);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy(int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $portfolio = Portfolio::find($id);
            if (!$portfolio) {
                return Helpers::apiResponse(false, 'Portfolio Not Found', [], 404);
            }

            // $folderName = Str::slug($portfolio->name, '-');

            // Storage::deleteDirectory('portfolio/' . $folderName);

            $portfolio->pic()->delete();
            $portfolio->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'Portfolio Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy_photo(int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $portfolio = PortfolioPic::find($id);
            if (!$portfolio) {
                return Helpers::apiResponse(false, 'Portfolio Picture Not Found', [], 404);
            }

            Storage::delete($portfolio->pic);

            $portfolio->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'Portfolio Picture Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
