<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ServiceController extends Controller
{
    public function index()
    {
        $data['service'] = Services::all();
        $data['title'] = 'Service List';
        return view('admin.service.index')->with($data);
    }

    public function create()
    {
        $data['title'] = 'Create Service';
        return view('admin.service.create')->with($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'icon' => 'required|max:255',
            'desc' => 'required',
        ]);

        DB::beginTransaction();
        try {
            Services::create([
                'name' => $request->name,
                'icon' => $request->icon,
                'desc' => $request->desc,
            ]);

            DB::commit();
            Session::flash('success', 'Service Created');
            return redirect()->route('service.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Service';
        $data['service'] = Services::findOrFail($id);
        return view('admin.service.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $service = Services::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|max:255',
            'icon' => 'required|max:255',
            'desc' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $service->update([
                'name' => $request->name,
                'icon' => $request->icon,
                'desc' => $request->desc,
            ]);

            DB::commit();
            Session::flash('success', 'Service Updated');
            return redirect()->route('service.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $service = Services::findOrFail($id);
        DB::beginTransaction();
        try {
            $service->delete();

            DB::commit();
            Session::flash('success', 'Service Deleted');
            return redirect()->route('service.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->back();
        }
    }
}
