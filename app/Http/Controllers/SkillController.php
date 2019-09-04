<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Skill;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SkillController extends Controller
{
    public function index()
    {
        $data['title'] = 'Skill List';
        $data['skill'] = Skill::all();
        return view('admin.skill.index')->with($data);
    }

    public function create()
    {
        $data['title'] = 'Create Skill';
        return view('admin.skill.create')->with($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'percentage' => 'required|numeric|min:0|max:100'
        ]);

        DB::beginTransaction();
        try {
            Skill::create([
                'name' => $request->name,
                'percentage' => $request->percentage
            ]);

            DB::commit();
            Session::flash('success', 'Skill Created');
            return redirect()->route('skill.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Skill';
        $data['skill'] = Skill::findOrFail($id);
        return view('admin.skill.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|max:255',
            'percentage' => 'required|numeric|min:0|max:100'
        ]);
        DB::beginTransaction();
        try {
            $skill->update([
                'name' => $request->name,
                'percentage' => $request->percentage
            ]);

            DB::commit();
            Session::flash('success', 'Skill Updated');
            return redirect()->route('skill.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        DB::beginTransaction();
        try {
            $skill->delete();

            DB::commit();
            Session::flash('success', 'Skill Deleted');
            return redirect()->route('skill.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->back();
        }
    }
}
