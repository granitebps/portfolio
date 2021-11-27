<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Services;
use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $service = Services::all();
        return Helpers::apiResponse(true, '', $service);
    }

    public function store(ServiceRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $service = Services::create($request->validated());

            DB::commit();
            return Helpers::apiResponse(true, 'Service Created', $service);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(ServiceRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $service = Services::find($id);
            if (!$service) {
                return Helpers::apiResponse(false, 'Service Not Found', [], 404);
            }

            $service->update($request->validated());

            DB::commit();
            return Helpers::apiResponse(true, 'Service Updated', $service);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $service = Services::find($id);
        DB::beginTransaction();
        try {
            if (!$service) {
                return Helpers::apiResponse(false, 'Service Not Found', [], 404);
            }
            $service->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'Service Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
