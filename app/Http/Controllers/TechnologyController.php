<?php

namespace App\Http\Controllers;

use App\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class TechnologyController extends Controller
{
    public function index()
    {
        $data['title'] = 'Technology List';
        $data['tech'] = Technology::all();
        return view('admin.tech.index')->with($data);
    }

    public function create()
    {
        $data['title'] = 'Create Technology';
        return view('admin.tech.create')->with($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'pic' => 'required|max:1024|image',
        ]);

        DB::beginTransaction();
        try {
            $pic = $request->pic;
            $picName = $pic->getClientOriginalName();

            // Image upload for shared hosting
            $pic->storeAs('tech', $picName, 'hosting');

            // Storage::putFileAs('public/images/tech', $pic, $picName);
            Technology::create([
                'name' => $request->name,
                'pic' => $picName,
            ]);

            DB::commit();
            Session::flash('success', 'Technology Created');
            return redirect()->route('tech.index');
        } catch (\Exception $e) {
            throw $e;
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Technology';
        $data['tech'] = Technology::findOrFail($id);
        return view('admin.tech.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $tech = Technology::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|max:255',
            'icon' => 'max:1024|image',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('pic')) {
                $pic = $request->pic;
                $picName = $pic->getClientOriginalName();

                // Image upload for shared hosting
                $pic->storeAs('tech', $picName, 'hosting');
                File::delete(public_path() . '/images/tech/' . $tech->pic);

                // Storage::delete('public/images/tech/' . $tech->pic);
                // Storage::putFileAs('public/images/tech', $pic, $picName);
                $tech->update([
                    'pic' => $picName,
                ]);
            }
            $tech->update([
                'name' => $request->name,
            ]);

            DB::commit();
            Session::flash('success', 'Technology Updated');
            return redirect()->route('tech.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $tech = Technology::findOrFail($id);
        DB::beginTransaction();
        try {
            // Hosting
            File::delete(public_path() . '/images/tech/' . $tech->pic);

            // Storage::delete('public/images/tech/' . $tech->pic);
            $tech->delete();

            DB::commit();
            Session::flash('success', 'Technology Deleted');
            return redirect()->route('tech.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->back();
        }
    }
}
