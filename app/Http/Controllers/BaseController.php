<?php

namespace App\Http\Controllers;

use App\Traits\Helpers;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function index()
    {
        return redirect()->away('https://granitebps.com');
    }

    public function not_found()
    {
        return Helpers::apiResponse(false, 'Not Found', [], 404);
    }
}
