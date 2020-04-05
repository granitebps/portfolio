<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Skill;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
{
    public function index()
    {
        $data = Skill::all();
        $data->makeHidden(['created_at', 'updated_at']);

        return Helpers::apiResponse(true, '', $data);
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

            DB::commit();
            return Helpers::apiResponse(true, 'Skill Deleted', []);
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Server Error', [], 500);
        }
    }
}
