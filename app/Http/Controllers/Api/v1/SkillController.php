<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Models\Skill;
use App\Traits\Helpers;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::all();
        $skills->makeHidden(['created_at', 'updated_at']);

        return Helpers::apiResponse(true, '', $skills);
    }

    public function store(SkillRequest $request)
    {
        DB::beginTransaction();
        try {
            $skill = Skill::create($request->all());

            DB::commit();
            return Helpers::apiResponse(true, 'Skill Created', $skill);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
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

            DB::commit();
            return Helpers::apiResponse(true, 'Skill Updated', $skill);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
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

            DB::commit();
            return Helpers::apiResponse(true, 'Skill Deleted', []);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
