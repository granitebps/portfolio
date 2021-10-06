<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechnologyRequest;
use App\Models\Technology;
use App\Traits\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TechnologyController extends Controller
{
    public function index()
    {
        $tech = Technology::all();
        return Helpers::apiResponse(true, '', $tech);
    }

    public function store(TechnologyRequest $request)
    {
        DB::beginTransaction();
        try {
            $pic = $request->pic;
            $nama_pic = time() . '_' . md5(uniqid()) . '.jpg';

            $jpg = Helpers::compressImageIntervention($pic);

            $aws_tech = 'tech/' . $nama_pic;
            Storage::put($aws_tech, $jpg);

            $tech = Technology::create([
                'name' => $request->name,
                'pic' => $aws_tech,
            ]);

            DB::commit();
            return Helpers::apiResponse(true, 'Technology Created', $tech);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(TechnologyRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $tech = Technology::find($id);
            if (!$tech) {
                return Helpers::apiResponse(false, 'Technology Not Found', [], 404);
            }
            if ($request->hasFile('pic')) {
                $pic = $request->pic;
                $nama_pic = time() . '_' . md5(uniqid()) . '.jpg';

                $jpg = Helpers::compressImageIntervention($pic);

                $aws_tech = 'tech/' . $nama_pic;
                Storage::put($aws_tech, $jpg);

                Storage::delete($tech->pic);

                $tech->pic = $aws_tech;
            }
            $tech->name = $request->name;
            $tech->save();

            DB::commit();
            return Helpers::apiResponse(true, 'Technology Updated', $tech);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $tech = Technology::find($id);
            if (!$tech) {
                return Helpers::apiResponse(false, 'Technology Not Found', [], 404);
            }

            Storage::delete($tech->pic);

            $tech->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'Technology Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
