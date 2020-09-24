<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Education;
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'institute' => 'required|string|max:255',
            'start_year' => 'required|integer|min:1900|max:9999|date_format:Y',
            'end_year' => 'required|integer|min:1900|max:9999|date_format:Y'
        ]);
        DB::beginTransaction();
        try {
            Education::create([
                'name' => $request->name,
                'institute' => $request->institute,
                'start_year' => $request->start_year,
                'end_year' => $request->end_year,
            ]);

            Cache::forget('educations');

            DB::commit();
            return Helpers::apiResponse(true, 'Education Created');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'institute' => 'required|string|max:255',
            'start_year' => 'required|integer|min:1900|max:9999|date_format:Y',
            'end_year' => 'required|integer|min:1900|max:9999|date_format:Y'
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

            Cache::forget('educations');

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

            Cache::forget('educations');

            DB::commit();
            return Helpers::apiResponse(true, 'Education Deleted');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }
}
