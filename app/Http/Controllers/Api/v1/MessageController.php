<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Message;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $message = Message::orderBy('created_at', 'desc')->get();
        $message->makeHidden(['updated_at']);
        return Helpers::apiResponse(true, '', $message);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        DB::beginTransaction();
        try {
            $message = Message::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message
            ]);

            DB::commit();
            return Helpers::apiResponse(true, 'Message Created', $message);
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $message = Message::find($id);
            if (!$message) {
                return Helpers::apiResponse(false, 'Message Not Found', [], 404);
            }

            $message->delete();

            DB::commit();
            return Helpers::apiResponse(true, 'Message Deleted', []);
        } catch (\Exception $e) {
            \Sentry\captureException($e);
            DB::rollback();
            return Helpers::apiResponse(false, 'Server Error', [], 500);
        }
    }
}
