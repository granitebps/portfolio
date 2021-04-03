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
        $dates = $data->pluck('date')->unique()->sortDesc();

        $logs = [];
        foreach ($dates as $date) {
            $log = $data->where('date', $date)->sortByDesc('count');
            array_push($logs, [
                'date' => $date,
                'data' => $log->flatten(),
                'total' => $log->sum('count'),
            ]);
        }

        return Helpers::apiResponse(true, 'Logs Retrieved', $logs);
    }
}
