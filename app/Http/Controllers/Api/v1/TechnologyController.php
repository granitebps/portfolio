<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechnologyRequest;
use App\Models\Technology;
use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TechnologyController extends Controller
{
    public function index(): JsonResponse
    {
        $tech = Technology::all();
        return Helpers::apiResponse(true, '', $tech);
    }

    public function store(TechnologyRequest $request): JsonResponse
    {
        $tech = DB::transaction(function () use ($request) {
            $nama_pic = time() . '_' . md5(uniqid()) . '.jpg';
            $jpg = Helpers::compressImageIntervention($request->pic);
            $aws_tech = 'tech/' . $nama_pic;
            Storage::put($aws_tech, $jpg);

            return Technology::create([
                'name' => $request->name,
                'pic' => $aws_tech,
            ]);
        });

        return Helpers::apiResponse(true, 'Technology Created', $tech);
    }

    public function update(TechnologyRequest $request, int $id): JsonResponse
    {
        $tech = Technology::find($id);
        if (!$tech) {
            return Helpers::apiResponse(false, 'Technology Not Found', [], 404);
        }

        DB::transaction(function () use ($request, $tech) {
            if ($request->hasFile('pic')) {
                $nama_pic = time() . '_' . md5(uniqid()) . '.jpg';
                $jpg = Helpers::compressImageIntervention($request->pic);
                $aws_tech = 'tech/' . $nama_pic;
                Storage::put($aws_tech, $jpg);
                Storage::delete($tech->pic);
                $tech->pic = $aws_tech;
            }
            $tech->name = $request->name;
            $tech->save();
        });

        return Helpers::apiResponse(true, 'Technology Updated', $tech);
    }

    public function destroy(int $id): JsonResponse
    {
        $tech = Technology::find($id);
        if (!$tech) {
            return Helpers::apiResponse(false, 'Technology Not Found', [], 404);
        }

        DB::transaction(function () use ($tech) {
            Storage::delete($tech->pic);
            $tech->delete();
        });

        return Helpers::apiResponse(true, 'Technology Deleted');
    }
}
