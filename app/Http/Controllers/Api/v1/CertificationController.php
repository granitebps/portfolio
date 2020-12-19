<?php

namespace App\Http\Controllers\Api\v1;

use App\Certification;
use App\Http\Controllers\Controller;
use App\Http\Requests\CertificationRequest;
use App\Traits\Helpers;
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

    public function store(CertificationRequest $request)
    {
        DB::beginTransaction();
        try {
            Certification::create($request->all());

            DB::commit();
            return Helpers::apiResponse(true, 'Certification Created');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(CertificationRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $certification = Certification::find($id);
            if (!$certification) {
                return Helpers::apiResponse(false, 'Certification Not Found', [], 404);
            }

            $certification->update($request->all());

            DB::commit();
            return Helpers::apiResponse(true, 'Certification Updated');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
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

            DB::commit();
            return Helpers::apiResponse(true, 'Certification Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
