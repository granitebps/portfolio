<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Models\Skill;
use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
{
    public function index(): JsonResponse
    {
        $skills = Skill::all();

        return Helpers::apiResponse(true, '', $skills);
    }

    public function store(SkillRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $skill = Skill::create($request->validated());

            DB::commit();
            return Helpers::apiResponse(true, 'Skill Created', $skill);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(SkillRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $skill = Skill::find($id);
            if (!$skill) {
                return Helpers::apiResponse(false, 'Skill Not Found', [], 404);
            }

            $skill->update($request->validated());

            DB::commit();
            return Helpers::apiResponse(true, 'Skill Updated', $skill);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy(int $id): JsonResponse
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
