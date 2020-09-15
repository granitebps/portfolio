<?php

namespace App\Http\Controllers\Api\v1;

use App\Experience;
use App\Http\Controllers\Controller;
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
            'company' => 'required|max:255',
            'position' => 'required|max:255',
            'desc' => 'required',
            'start_date' => 'required'
        ]);
        if (!$request->filled('current_job')) {
            $this->validate($request, [
                'end_date' => 'required'
            ]);
        }
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
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'company' => 'required|max:255',
            'position' => 'required|max:255',
            'start_date' => 'required'
        ]);
        if (!$request->has('current_job')) {
            $this->validate($request, [
                'end_date' => 'required'
            ]);
        }
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
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }
}
