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
        DB::beginTransaction();
        try {
            $education = Education::create($request->validated());

            DB::commit();
            return Helpers::apiResponse(true, 'Education Created', $education);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(EducationRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $education = Education::find($id);
            if (!$education) {
                return Helpers::apiResponse(false, 'Education Not Found', [], 404);
            }
            $education->update($request->validated());

            DB::commit();
            return Helpers::apiResponse(true, 'Education Updated', $education);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy(int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $education = Education::find($id);
            if (!$education) {
                return Helpers::apiResponse(false, 'Education Not Found', [], 404);
            }
            $education->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'Education Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
