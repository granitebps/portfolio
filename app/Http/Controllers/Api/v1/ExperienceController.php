<?php

namespace App\Http\Controllers\Api\v1;

use App\Experience;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExperienceRequest;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ExperienceController extends Controller
{
    public function index()
    {
        if (Cache::has('experiences')) {
            $experience = Cache::get('experiences');
        } else {
            $experience = Experience::orderBy('start_date', 'desc')->get();
            $experience->transform(function ($item) {
                $item->current_job = $item->current_job ? $item->current_job : false;
                return $item;
            });
            Cache::put('experiences', $experience, now()->addDay());
        }
        return Helpers::apiResponse(true, '', $experience);
    }

    public function store(ExperienceRequest $request)
    {
        DB::beginTransaction();
        try {
            Experience::create($request->all());

            DB::commit();
            return Helpers::apiResponse(true, 'Experience Created');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $experience = Experience::find($id);
            if (!$experience) {
                return Helpers::apiResponse(false, 'Experience Not Found', [], 404);
            }
            $experience->update($request->all());

            DB::commit();
            return Helpers::apiResponse(true, 'Experience Updated');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $experience = Experience::find($id);
            if (!$experience) {
                return Helpers::apiResponse(false, 'Experience Not Found', [], 404);
            }
            $experience->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'Experience Deleted');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }
}
