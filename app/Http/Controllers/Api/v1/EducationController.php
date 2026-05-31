<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationRequest;
use App\Models\Education;
use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class EducationController extends Controller
{
    public function index(): JsonResponse
    {
        $education = Education::orderBy('start_year', 'desc')->get();
        return Helpers::apiResponse(true, '', $education);
    }

    public function store(EducationRequest $request): JsonResponse
    {
        $education = DB::transaction(fn () => Education::create($request->validated()));

        return Helpers::apiResponse(true, 'Education Created', $education);
    }

    public function update(EducationRequest $request, int $id): JsonResponse
    {
        $education = Education::find($id);
        if (!$education) {
            return Helpers::apiResponse(false, 'Education Not Found', [], 404);
        }

        DB::transaction(fn () => $education->update($request->validated()));

        return Helpers::apiResponse(true, 'Education Updated', $education);
    }

    public function destroy(int $id): JsonResponse
    {
        $education = Education::find($id);
        if (!$education) {
            return Helpers::apiResponse(false, 'Education Not Found', [], 404);
        }

        DB::transaction(fn () => $education->delete());

        return Helpers::apiResponse(true, 'Education Deleted');
    }
}
