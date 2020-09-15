<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Technology;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TechnologyController extends Controller
{
    public function index()
    {
        if (Cache::has('tech')) {
            $tech = Cache::get('tech');
        } else {
            $tech = Technology::all();
            $tech->makeHidden(['created_at', 'updated_at']);
            $tech->transform(function ($item) {
                $newFoto = Storage::url($item->pic);
                $item->pic = $newFoto;
                return $item;
            });
            Cache::put('tech', $tech, now()->addDay());
        }
        return Helpers::apiResponse(true, '', $tech);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'pic' => 'required|max:2048|image',
        ]);

        DB::beginTransaction();
        try {
            $pic = $request->pic;
            $pic_full = $pic->getClientOriginalName();
            $filename = Str::slug(pathinfo($pic_full, PATHINFO_FILENAME));
            $extension = pathinfo($pic_full, PATHINFO_EXTENSION);
            $nama_pic = time() . '_' . $filename . '.' . $extension;

            $aws_tech = Storage::putFileAs('tech', $pic, $nama_pic);

            $tech = Technology::create([
                'name' => $request->name,
                'pic' => $aws_tech,
            ]);

            Cache::forget('tech');

            DB::commit();
            return Helpers::apiResponse(true, 'Technology Created', $tech);
        } catch (\Exception $e) {
            throw $e;
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'icon' => 'max:1024|image',
        ]);

        DB::beginTransaction();
        try {
            $tech = Technology::find($id);
            if (!$tech) {
                return Helpers::apiResponse(false, 'Technology Not Found', [], 404);
            }
            if ($request->hasFile('pic')) {
                $pic = $request->pic;
                $pic_full = $pic->getClientOriginalName();
                $filename = Str::slug(pathinfo($pic_full, PATHINFO_FILENAME));
                $extension = pathinfo($pic_full, PATHINFO_EXTENSION);
                $nama_pic = time() . '_' . $filename . '.' . $extension;

                $aws_tech = Storage::putFileAs('tech', $pic, $nama_pic);

                Storage::delete($tech->pic);

                $tech->update([
                    'pic' => $aws_tech,
                ]);
            }
            $tech->update([
                'name' => $request->name,
            ]);

            Cache::forget('tech');

            DB::commit();
            return Helpers::apiResponse(true, 'Technology Updated', $tech);
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
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

            Cache::forget('tech');

            DB::commit();
            return Helpers::apiResponse(true, 'Technology Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }
}
