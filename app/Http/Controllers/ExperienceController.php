<?php

namespace App\Http\Controllers;

use App\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ExperienceController extends Controller
{
    public function index()
    {
        $data['title'] = 'Experience List';
        $data['experience'] = Experience::all();
        return view('admin.experience.index')->with($data);
    }

    public function create()
    {
        $data['title'] = 'Create Experience';
        return view('admin.experience.create')->with($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'company' => 'required|max:255',
            'position' => 'required|max:255',
            'start_date' => 'required'
        ]);
        if (!$request->has('current_job')) {
            $this->validate($request, [
                'end_date' => 'required'
            ]);
        }
        DB::beginTransaction();
        try {
            Experience::create([
                'company' => $request->company,
                'position' => $request->position,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'current_job' => $request->current_job
            ]);

            DB::commit();
            Session::flash('success', 'Experience Created');
            return redirect()->route('experience.index');
        } catch (\Exception $e) {
            throw $e;
            DB::rollback();
            Session::flash('error', 'Something Error');
            return redirect()->back();
        }
    }
}
