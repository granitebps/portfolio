<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Services;
use App\Traits\Helpers;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        $service = Services::all();
        $service->makeHidden(['created_at', 'updated_at']);
        return Helpers::apiResponse(true, '', $service);
    }

    public function store(ServiceRequest $request)
    {
        DB::beginTransaction();
        try {
            $service = Services::create($request->all());

            DB::commit();
            return Helpers::apiResponse(true, 'Service Created', $service);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(ServiceRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $service = Services::find($id);
            if (!$service) {
                return Helpers::apiResponse(false, 'Service Not Found', [], 404);
            }

            $service->update($request->all());

            DB::commit();
            return Helpers::apiResponse(true, 'Service Updated', $service);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $service = Services::findOrFail($id);
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
