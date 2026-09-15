<?php

namespace App\Http\Controllers\Api\Chat;

use App\Actions\Chat\StartConversationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chat\StartConversationRequest;
use App\Http\Resources\Api\Chat\ConversationResource;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $page = (int) $request->query('page', 1);
        $perPage = 20;

        $all = Conversation::forUser($userId)
            ->with('latestMessage')
            ->withCount(['messages as unread_count' => function ($q) use ($userId) {
                $q->where('sender_id', '!=', $userId)->where('status', '!=', 'read');
            }])
            ->orderByDesc('updated_at')
            ->get()
            ->filter(function (Conversation $conversation) use ($userId) {
                $deletedAt = $conversation->deletedAtFor($userId);

                if (!$deletedAt) return true;

                return $conversation->latestMessage && $conversation->latestMessage->created_at->gt($deletedAt);
            })
            ->values();

        $total = $all->count();
        $items = $all->forPage($page, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($items, $total, $perPage, $page);

        return response()->json([
            'success' => true,
            'data'    => ConversationResource::collection($paginator),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    public function store(StartConversationRequest $request, StartConversationAction $action): JsonResponse
    {
        $conversation = $action->execute($request->user(), (int) $request->validated('recipient_id'));
        $conversation->load('latestMessage');

        return response()->json([
            'success' => true,
            'data'    => new ConversationResource($conversation),
        ]);
    }


    public function destroy(Conversation $conversation, Request $request): JsonResponse
    {
        abort_unless(
            in_array($request->user()->id, [$conversation->user_one_id, $conversation->user_two_id]),
            403,
        );

        $conversation->markDeletedFor($request->user()->id);

        return response()->json(['success' => true, 'message' => 'Chat deleted.']);
    }
}