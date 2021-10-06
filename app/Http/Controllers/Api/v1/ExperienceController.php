<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExperienceRequest;
use App\Models\Experience;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExperienceController extends Controller
{
    public function index()
    {
        $experience = Experience::orderBy('start_date', 'desc')->get();
        return Helpers::apiResponse(true, '', $experience);
    }

    public function store(ExperienceRequest $request)
    {
        DB::beginTransaction();
        try {
            Experience::create($request->validated());

            DB::commit();
            return Helpers::apiResponse(true, 'Experience Created');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(ExperienceRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $experience = Experience::find($id);
            if (!$experience) {
                return Helpers::apiResponse(false, 'Experience Not Found', [], 404);
            }
            $experience->update($request->validated());

            DB::commit();
            return Helpers::apiResponse(true, 'Experience Updated');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
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
            DB::rollback();
            throw $e;
        }
    }
}
