<?php

namespace App\Http\Controllers\Api\v1;

use App\Certification;
use App\Http\Controllers\Controller;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CertificationController extends Controller
{
    public function index()
    {
        if (Cache::has('certifications')) {
            $certifications = Cache::get('certifications');
        } else {
            $certifications = Certification::latest('published')->get();
            Cache::put('certifications', $certifications, now()->addDay());
        }
        return Helpers::apiResponse(true, '', $certifications);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'link' => 'required|string|url|max:255',
            'published' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            Certification::create($data);

            Cache::forget('certifications');

            DB::commit();
            return Helpers::apiResponse(true, 'Certification Created');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Server Error', [], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'link' => 'required|string|url|max:255',
            'published' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $certification = Certification::find($id);
            if (!$certification) {
                return Helpers::apiResponse(false, 'Certification Not Found', [], 404);
            }

            $certification->update($data);

            Cache::forget('certifications');

            DB::commit();
            return Helpers::apiResponse(true, 'Certification Updated');
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
            $certification = Certification::find($id);
            if (!$certification) {
                return Helpers::apiResponse(false, 'Certification Not Found', [], 404);
            }

            $certification->delete();

            Cache::forget('certifications');

            DB::commit();
            return Helpers::apiResponse(true, 'Certification Deleted');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Server Error', [], 500);
        }
    }
}
