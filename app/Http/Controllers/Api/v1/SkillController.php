<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Skill;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
{
    public function index()
    {
        if (Cache::has('skills')) {
            $skills = Cache::get('skills');
        } else {
            $skills = Skill::all();
            $skills->makeHidden(['created_at', 'updated_at']);
            Cache::put('skills', $skills, now()->addDay());
        }

        return Helpers::apiResponse(true, '', $skills);
    }

    public function store(SkillRequest $request)
    {
        DB::beginTransaction();
        try {
            $skill = Skill::create($request->all());

            Cache::forget('skills');

            DB::commit();
            return Helpers::apiResponse(true, 'Skill Created', $skill);
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Server Error', [], 500);
        }
    }

    public function update(SkillRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $skill = Skill::find($id);
            if (!$skill) {
                return Helpers::apiResponse(false, 'Skill Not Found', [], 404);
            }

            $skill->update($request->all());

            Cache::forget('skills');

            DB::commit();
            return Helpers::apiResponse(true, 'Skill Updated', $skill);
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Server Error', [], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $skill = Skill::find($id);
            if (!$skill) {
                return Helpers::apiResponse(false, 'Skill Not Found', [], 404);
            }

            $skill->delete();

            Cache::forget('skills');

            DB::commit();
            return Helpers::apiResponse(true, 'Skill Deleted', []);
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Server Error', [], 500);
        }
    }
}
