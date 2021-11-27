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
        DB::beginTransaction();
        try {
            $message = Message::create($request->validated());

            DB::commit();
            return Helpers::apiResponse(true, 'Message Created', $message);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy(int $id): JsonResponse
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
            DB::rollback();
            throw $e;
        }
    }

    public function markRead(Request $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $message = Message::find($id);
            if (!$message) {
                return Helpers::apiResponse(false, 'Message Not Found', [], 404);
            }

            $message->update(['is_read' => true]);

            DB::commit();
            return Helpers::apiResponse(true, 'Message Mark As Read', []);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
