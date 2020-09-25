<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Education;
use App\Http\Requests\EducationRequest;
use App\Traits\Helpers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EducationController extends Controller
{
    public function index()
    {
        if (Cache::has('educations')) {
            $education = Cache::get('educations');
        } else {
            $education = Education::orderBy('start_year', 'desc')->get();
            Cache::put('educations', $education, now()->addDay());
        }
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
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
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
            \Sentry\captureException($e);
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
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }
}
