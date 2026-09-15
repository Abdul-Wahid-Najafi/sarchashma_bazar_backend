<?php

namespace App\Http\Controllers\Api\Chat;

use App\Actions\Chat\MarkMessagesDeliveredAction;
use App\Actions\Chat\MarkMessagesReadAction;
use App\Actions\Chat\SendMessageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chat\SendMessageRequest;
use App\Http\Resources\Api\Chat\MessageResource;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Conversation $conversation, Request $request, MarkMessagesDeliveredAction $deliverAction): JsonResponse
    {
        $this->authorizeAccess($conversation, $request->user());

        $deliverAction->execute($conversation, $request->user());

        $deletedAt = $conversation->deletedAtFor($request->user()->id); 

        $messages = $conversation->messages()
        ->when($deletedAt, fn ($q) => $q->where('created_at', '>', $deletedAt))
        ->with(['sender', 'product.images'])
        ->latest()
        ->paginate(30);

        return response()->json([
            'success' => true,
            'data'    => MessageResource::collection($messages),
            'meta'    => [
                'current_page' => $messages->currentPage(),
                'last_page'    => $messages->lastPage(),
            ],
        ]);
    }

    public function store(SendMessageRequest $request, Conversation $conversation, SendMessageAction $action): JsonResponse
    {
        $this->authorizeAccess($conversation, $request->user());

        $otherUser = $conversation->otherUser($request->user()->id);

        if ($request->user()->hasBlocked($otherUser->id) || $request->user()->isBlockedBy($otherUser->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot send messages in this conversation.',
            ], 403);
        }

        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment');
        }

        $message = $action->execute($conversation, $request->user(), $data);

        return response()->json([
            'success' => true,
            'data'    => new MessageResource($message),
        ], 201);
    }

    public function markRead(
        Conversation $conversation,
        Request $request,
        MarkMessagesReadAction $action,
    ): JsonResponse {
        $this->authorizeAccess($conversation, $request->user());

        $ids = $action->execute($conversation, $request->user());

        return response()->json(['success' => true, 'data' => ['updated_ids' => $ids]]);
    }

    public function markDelivered(
        Conversation $conversation,
        Request $request,
        MarkMessagesDeliveredAction $action,
    ): JsonResponse {
        $this->authorizeAccess($conversation, $request->user());

        $ids = $action->execute($conversation, $request->user());

        return response()->json(['success' => true, 'data' => ['updated_ids' => $ids]]);
    }

    protected function authorizeAccess(Conversation $conversation, User $user): void
    {
        abort_unless(
            in_array($user->id, [$conversation->user_one_id, $conversation->user_two_id]),
            403,
            'You do not have access to this conversation.',
        );
    }
}