<?php

namespace App\Http\Controllers;

use App\Experience;
use App\Message;
use App\Portfolio;
use App\Profile;
use App\Services;
use App\Skill;
use App\Technology;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['title'] = 'Dashboard';
        return view('home')->with($data);
    }

    public function welcome()
    {
        $data['user'] = User::first();
        $data['profile'] = Profile::first();
        $data['skill'] = Skill::all();
        $data['tech'] = Technology::all();
        $data['service'] = Services::all();
        $data['experience'] = Experience::all();
        $data['portfolio'] = Portfolio::all();

        $data['count_personal'] = Portfolio::where('type', 1)->count();
        $data['count_client'] = Portfolio::where('type', 2)->count();
        $data['count_tech'] = Technology::count();
        $experience = Experience::all();
        $month = 0;
        foreach ($experience as $key => $value) {
            if ($value->current_job == 1) {
                $count = Carbon::parse($value->start_date)->diffInMonths(Carbon::now());
                $month += $count;
            } else {
                $count = Carbon::parse($value->start_date)->diffInMonths(Carbon::parse($value->end_date));
                $month += $count;
            }
        }
        $data['count_month'] = $month;
        return view('welcome')->with($data);
    }

    public function message(Request $request)
    {
        DB::beginTransaction();
        try {
            Message::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message
            ]);

            DB::commit();
            Session::flash('success', 'Message Send');
            return redirect()->route('welcome');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->route('welcome');
        }
    }

    public function getMessage()
    {
        $data['title'] = 'Message List';
        $data['message'] = Message::all();
        return view('admin.message.index')->with($data);
    }
}
