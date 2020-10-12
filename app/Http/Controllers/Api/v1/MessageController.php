<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Message;
use App\Traits\Helpers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $message = Message::orderBy('created_at', 'desc')->get();
        $message->makeHidden(['updated_at']);
        return Helpers::apiResponse(true, '', $message);
    }

    public function store(MessageRequest $request)
    {
        DB::beginTransaction();
        try {
            $message = Message::create($request->all());

            DB::commit();
            return Helpers::apiResponse(true, 'Message Created', $message);
        } catch (\Exception $e) {
            if (App::environment('production')) {
                \Sentry\captureException($e);
            }
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
            if (App::environment('production')) {
                \Sentry\captureException($e);
            }
            DB::rollback();
            return Helpers::apiResponse(false, 'Server Error', [], 500);
        }
    }
}
