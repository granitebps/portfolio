<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Education;
use App\Traits\Helpers;
use Illuminate\Support\Facades\DB;

class EducationController extends Controller
{
    public function index()
    {
        $education = Education::orderBy('start_year', 'asc')->get();
        return Helpers::apiResponse(true, '', $education);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'institute' => 'required|max:255',
            'start_year' => 'required',
            'end_year' => 'required'
        ]);
        DB::beginTransaction();
        try {
            Education::create([
                'name' => $request->name,
                'institute' => $request->institute,
                'start_year' => $request->start_year,
                'end_year' => $request->end_year,
            ]);

            DB::commit();
            return Helpers::apiResponse(true, 'Education Created');
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'institute' => 'required|max:255',
            'start_year' => 'required',
            'end_year' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $education = Education::find($id);
            if (!$education) {
                return Helpers::apiResponse(false, 'Education Not Found', [], 404);
            }
            $education->update([
                'name' => $request->name,
                'institute' => $request->institute,
                'start_year' => $request->start_year,
                'end_year' => $request->end_year,
            ]);

            DB::commit();
            return Helpers::apiResponse(true, 'Education Updated');
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $education = Education::find($id);
            if (!$education) {
                return Helpers::apiResponse(false, 'Education Not Found', [], 404);
            }
            $education->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'Education Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }
}
