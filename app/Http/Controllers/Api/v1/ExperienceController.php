<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExperienceRequest;
use App\Models\Experience;
use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ExperienceController extends Controller
{
    public function index(): JsonResponse
    {
        $experience = Experience::orderBy('start_date', 'desc')->get();
        return Helpers::apiResponse(true, '', $experience);
    }

    public function store(ExperienceRequest $request): JsonResponse
    {
        $experience = DB::transaction(fn () => Experience::create($request->validated()));

        return Helpers::apiResponse(true, 'Experience Created', $experience);
    }

    public function update(ExperienceRequest $request, int $id): JsonResponse
    {
        $experience = Experience::find($id);
        if (!$experience) {
            return Helpers::apiResponse(false, 'Experience Not Found', [], 404);
        }

        DB::transaction(fn () => $experience->update($request->validated()));

        return Helpers::apiResponse(true, 'Experience Updated', $experience);
    }

    public function destroy(int $id): JsonResponse
    {
        $experience = Experience::find($id);
        if (!$experience) {
            return Helpers::apiResponse(false, 'Experience Not Found', [], 404);
        }

        DB::transaction(fn () => $experience->delete());

        return Helpers::apiResponse(true, 'Experience Deleted');
    }
}
