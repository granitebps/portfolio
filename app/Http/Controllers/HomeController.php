<?php

namespace App\Http\Controllers;

use App\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function welcome()
    {
        $skill_request = Http::get('https://api.granitebps.com/api/v1/skill');
        $skill_response = $skill_request->json();
        $technology_request = Http::get('https://api.granitebps.com/api/v1/technology');
        $technology_response = $technology_request->json();
        $service_request = Http::get('https://api.granitebps.com/api/v1/service');
        $service_response = $service_request->json();
        $user_request = Http::get('https://api.granitebps.com/api/v1/profile');
        $user_response = $user_request->json();
        $portfolio_request = Http::get('https://api.granitebps.com/api/v1/portfolio');
        $portfolio_response = $portfolio_request->json();
        $experience_request = Http::get('https://api.granitebps.com/api/v1/experience');
        $experience_response = $experience_request->json();

        $data['skill'] = $skill_response['data'];
        $data['tech'] = $technology_response['data'];
        $data['service'] = $service_response['data'];
        $data['user'] = $user_response['data'];
        $data['profile'] = $user_response['data']['profile'];
        $data['portfolio'] = $portfolio_response['data'];
        $data['experience'] = $experience_response['data'];

        $portfolio = collect($portfolio_response['data']);
        $portfolio_personal = $portfolio->filter(function ($value) {
            return $value['type'] == 1;
        });
        $portfolio_client = $portfolio->filter(function ($value) {
            return $value['type'] == 2;
        });

        $data['count_personal'] = count($portfolio_personal);
        $data['count_client'] = count($portfolio_client);
        $data['count_tech'] = count($technology_response['data']);
        $experience = $experience_response['data'];
        $month = 0;
        foreach ($experience as $key => $value) {
            if ($value['current_job'] == 1) {
                $count = Carbon::parse($value['start_date'])->diffInMonths(Carbon::now());
                $month += $count;
            } else {
                $count = Carbon::parse($value['start_date'])->diffInMonths(Carbon::parse($value['end_date']));
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
}
