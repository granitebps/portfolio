<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Helpers;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $logs = [];

        return Helpers::apiResponse(true, 'Logs Retrieved', $logs);
    }
}
