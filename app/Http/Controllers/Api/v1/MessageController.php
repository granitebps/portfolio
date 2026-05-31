<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index(): JsonResponse
    {
        $message = Message::orderBy('created_at', 'desc')->get();
        return Helpers::apiResponse(true, '', $message);
    }

    public function store(MessageRequest $request): JsonResponse
    {
        $message = DB::transaction(fn () => Message::create($request->validated()));

        return Helpers::apiResponse(true, 'Message Created', $message);
    }

    public function destroy(int $id): JsonResponse
    {
        $message = Message::find($id);
        if (!$message) {
            return Helpers::apiResponse(false, 'Message Not Found', [], 404);
        }

        DB::transaction(fn () => $message->delete());

        return Helpers::apiResponse(true, 'Message Deleted', []);
    }

    public function markRead(Request $request, int $id): JsonResponse
    {
        $message = Message::find($id);
        if (!$message) {
            return Helpers::apiResponse(false, 'Message Not Found', [], 404);
        }

        DB::transaction(fn () => $message->update(['is_read' => true]));

        return Helpers::apiResponse(true, 'Message Mark As Read', []);
    }
}
