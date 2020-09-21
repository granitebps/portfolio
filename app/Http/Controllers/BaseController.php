<?php

namespace App\Http\Controllers;

use App\Traits\Helpers;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function index()
    {
        return Helpers::apiResponse(true, 'API GBPS Website', [
            'landing_page' => 'https://granitebps.com',
            'api' => 'https://api.granitebps.com'
        ], 200);
    }

    public function not_found()
    {
        return Helpers::apiResponse(false, 'Not Found', [], 404);
    }
}
