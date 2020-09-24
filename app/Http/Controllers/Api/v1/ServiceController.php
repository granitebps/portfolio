<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        if (Cache::has('services')) {
            $service = Cache::get('services');
        } else {
            $service = Services::all();
            $service->makeHidden(['created_at', 'updated_at']);
            Cache::put('services', $service, now()->addDay());
        }
        return Helpers::apiResponse(true, '', $service);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'desc' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $service = Services::create([
                'name' => $request->name,
                'icon' => $request->icon,
                'desc' => $request->desc,
            ]);

            Cache::forget('services');

            DB::commit();
            return Helpers::apiResponse(true, 'Service Created', $service);
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $service = Services::find($id);
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'desc' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            if (!$service) {
                return Helpers::apiResponse(false, 'Service Not Found', [], 404);
            }

            $service->update([
                'name' => $request->name,
                'icon' => $request->icon,
                'desc' => $request->desc,
            ]);

            Cache::forget('services');

            DB::commit();
            return Helpers::apiResponse(true, 'Service Updated', $service);
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
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

            Cache::forget('services');

            DB::commit();
            return Helpers::apiResponse(true, 'Service Deleted');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }
}
