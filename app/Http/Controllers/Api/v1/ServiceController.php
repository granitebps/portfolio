<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $service = Service::all();
        return Helpers::apiResponse(true, '', $service);
    }

    public function store(ServiceRequest $request): JsonResponse
    {
        $service = DB::transaction(fn () => Service::create($request->validated()));

        return Helpers::apiResponse(true, 'Service Created', $service);
    }

    public function update(ServiceRequest $request, int $id): JsonResponse
    {
        $service = Service::find($id);
        if (!$service) {
            return Helpers::apiResponse(false, 'Service Not Found', [], 404);
        }

        DB::transaction(fn () => $service->update($request->validated()));

        return Helpers::apiResponse(true, 'Service Updated', $service);
    }

    public function destroy(int $id): JsonResponse
    {
        $service = Service::find($id);
        if (!$service) {
            return Helpers::apiResponse(false, 'Service Not Found', [], 404);
        }

        DB::transaction(fn () => $service->delete());

        return Helpers::apiResponse(true, 'Service Deleted');
    }
}
