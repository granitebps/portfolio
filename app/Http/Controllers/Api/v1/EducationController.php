<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationRequest;
use App\Models\Education;
use App\Traits\Helpers;
use Illuminate\Support\Facades\DB;

class EducationController extends Controller
{
    public function index()
    {
        $education = Education::orderBy('start_year', 'desc')->get();
        return Helpers::apiResponse(true, '', $education);
    }

    public function store(EducationRequest $request)
    {
        DB::beginTransaction();
        try {
            Education::create($request->all());

            DB::commit();
            return Helpers::apiResponse(true, 'Education Created');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(EducationRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $education = Education::find($id);
            if (!$education) {
                return Helpers::apiResponse(false, 'Education Not Found', [], 404);
            }
            $education->update($request->all());

            DB::commit();
            return Helpers::apiResponse(true, 'Education Updated');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
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
            throw $e;
        }
    }
}
