<?php

namespace App\Http\Controllers;

use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class BaseController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->away('https://granitebps.com');
    }

    public function not_found(): JsonResponse
    {
        return Helpers::apiResponse(false, 'Not Found', [], 404);
    }
}
