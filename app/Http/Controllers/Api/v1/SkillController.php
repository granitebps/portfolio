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
        $skill = DB::transaction(fn () => Skill::create($request->validated()));

        return Helpers::apiResponse(true, 'Skill Created', $skill);
    }

    public function update(SkillRequest $request, int $id): JsonResponse
    {
        $skill = Skill::find($id);
        if (!$skill) {
            return Helpers::apiResponse(false, 'Skill Not Found', [], 404);
        }

        DB::transaction(fn () => $skill->update($request->validated()));

        return Helpers::apiResponse(true, 'Skill Updated', $skill);
    }

    public function destroy(int $id): JsonResponse
    {
        $skill = Skill::find($id);
        if (!$skill) {
            return Helpers::apiResponse(false, 'Skill Not Found', [], 404);
        }

        DB::transaction(fn () => $skill->delete());

        return Helpers::apiResponse(true, 'Skill Deleted', []);
    }
}
