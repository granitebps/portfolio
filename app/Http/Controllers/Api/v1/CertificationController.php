<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificationRequest;
use App\Models\Certification;
use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CertificationController extends Controller
{
    public function index(): JsonResponse
    {
        $certifications = Certification::latest('published')->get();
        return Helpers::apiResponse(true, '', $certifications);
    }

    public function store(CertificationRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $certification = Certification::create($request->validated());

            DB::commit();
            return Helpers::apiResponse(true, 'Certification Created', $certification);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(CertificationRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $certification = Certification::find($id);
            if (!$certification) {
                return Helpers::apiResponse(false, 'Certification Not Found', [], 404);
            }

            $certification->update($request->validated());

            DB::commit();
            return Helpers::apiResponse(true, 'Certification Updated', $certification);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy(int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $certification = Certification::find($id);
            if (!$certification) {
                return Helpers::apiResponse(false, 'Certification Not Found', [], 404);
            }

            $certification->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'Certification Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
