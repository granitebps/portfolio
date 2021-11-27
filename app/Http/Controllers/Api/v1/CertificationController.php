<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificationRequest;
use App\Models\Certification;
use App\Traits\Helpers;
use Illuminate\Support\Facades\DB;

class CertificationController extends Controller
{
    public function index()
    {
        $certifications = Certification::latest('published')->get();
        return Helpers::apiResponse(true, '', $certifications);
    }

    public function store(CertificationRequest $request)
    {
        DB::beginTransaction();
        try {
            $certification = Certification::create($request->validated());

            DB::commit();
            return Helpers::apiResponse(true, 'Certification Created', $certification);
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

            $certification->update($request->validated());

            DB::commit();
            return Helpers::apiResponse(true, 'Certification Updated', $certification);
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
