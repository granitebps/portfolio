<?php

namespace App\Http\Controllers\Api\v1;

use App\Experience;
use App\Http\Controllers\Controller;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ExperienceController extends Controller
{
    public function index()
    {
        if (Cache::has('experiences')) {
            $experience = Cache::get('experiences');
        } else {
            $experience = Experience::orderBy('created_at', 'desc')->get();
            $experience->transform(function ($item) {
                $item->current_job = $item->current_job ? $item->current_job : false;
                return $item;
            });
            Cache::put('experiences', $experience, now()->addDay());
        }
        return Helpers::apiResponse(true, '', $experience);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'desc' => 'required|string',
            'current_job' => 'sometimes|boolean',
            'start_date' => 'required|string|max:255|date',
            'end_date' => 'exclude_if:current_job,1|required|string|max:255|date'
        ]);

        DB::beginTransaction();
        try {
            Experience::create([
                'company' => $request->company,
                'position' => $request->position,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'current_job' => $request->current_job,
                'desc' => $request->desc
            ]);

            Cache::forget('experiences');

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
        $this->validate($request, [
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'desc' => 'required|string',
            'current_job' => 'sometimes|boolean',
            'start_date' => 'required|string|max:255|date',
            'end_date' => 'exclude_if:current_job,1|required|string|max:255|date'
        ]);

        DB::beginTransaction();
        try {
            $experience = Experience::find($id);
            if (!$experience) {
                return Helpers::apiResponse(false, 'Experience Not Found', [], 404);
            }
            $experience->update([
                'company' => $request->company,
                'position' => $request->position,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'current_job' => $request->current_job,
                'desc' => $request->desc,
            ]);

            Cache::forget('experiences');

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

            Cache::forget('experiences');

            DB::commit();
            return Helpers::apiResponse(true, 'Experience Deleted');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }
}
