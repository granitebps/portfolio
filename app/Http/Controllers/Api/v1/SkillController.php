<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100'
        ]);

        DB::beginTransaction();
        try {
            Skill::create($data);

            Cache::forget('skills');

            DB::commit();
            return Helpers::apiResponse(true, 'Skill Created', $data);
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Server Error', [], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100'
        ]);

        DB::beginTransaction();
        try {
            $skill = Skill::find($id);
            if (!$skill) {
                return Helpers::apiResponse(false, 'Skill Not Found', [], 400);
            }

            $skill->update($data);

            Cache::forget('skills');

            DB::commit();
            return Helpers::apiResponse(true, 'Skill Updated', $data);
        } catch (\Exception $e) {
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
                return Helpers::apiResponse(false, 'Skill Not Found', [], 400);
            }

            $skill->delete();

            Cache::forget('skills');

            DB::commit();
            return Helpers::apiResponse(true, 'Skill Deleted', []);
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Server Error', [], 500);
        }
    }
}
