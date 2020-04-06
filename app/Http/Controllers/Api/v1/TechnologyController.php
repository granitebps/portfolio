<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Technology;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TechnologyController extends Controller
{
    public function index()
    {
        $tech = Technology::all();
        $tech->makeHidden(['created_at', 'updated_at']);
        $tech->transform(function ($item) {
            $newFoto = asset('images/tech/' . $item->pic);
            $item->pic = $newFoto;
            return $item;
        });
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

            // Image upload for shared hosting
            $pic->storeAs('tech', $nama_pic, 'hosting');

            // Storage::putFileAs('public/images/tech', $pic, $picName);
            $tech = Technology::create([
                'name' => $request->name,
                'pic' => $nama_pic,
            ]);

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
                $old_foto = $tech->pic;
                $pic = $request->pic;
                $pic_full = $pic->getClientOriginalName();
                $filename = Str::slug(pathinfo($pic_full, PATHINFO_FILENAME));
                $extension = pathinfo($pic_full, PATHINFO_EXTENSION);
                $nama_pic = time() . '_' . $filename . '.' . $extension;

                // Image upload for shared hosting
                $pic->storeAs('tech', $nama_pic, 'hosting');
                File::delete(public_path() . '/images/tech/' . $old_foto);

                // Storage::delete('public/images/tech/' . $tech->pic);
                // Storage::putFileAs('public/images/tech', $pic, $picName);
                $tech->update([
                    'pic' => $nama_pic,
                ]);
            }
            $tech->update([
                'name' => $request->name,
            ]);

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
            // Hosting
            File::delete(public_path() . '/images/tech/' . $tech->pic);

            // Storage::delete('public/images/tech/' . $tech->pic);
            $tech->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'Technology Deleted');
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }
}