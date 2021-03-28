<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\ViewModels\Log;
use Illuminate\Http\Request;
use App\Traits\Helpers;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $data = Log::all();
        $dates = $data->pluck('date')->unique();

        $logs = [];
        foreach ($dates as $date) {
            $log = $data->where('date', $date);
            array_push($logs, [
                'date' => $date,
                'total' => $log->sum('count'),
                'data' => $log,
            ]);
        }

        return Helpers::apiResponse(true, 'Logs Retrieved', $logs);
    }
}
