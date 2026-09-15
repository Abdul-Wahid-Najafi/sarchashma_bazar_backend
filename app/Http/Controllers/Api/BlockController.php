<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function store(int $userId, Request $request): JsonResponse
    {
        if ($userId === $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot block yourself.',
            ], 422);
        }

        $request->user()->blockedUsers()->syncWithoutDetaching([$userId]);

        return response()->json(['success' => true, 'message' => 'User blocked.']);
    }

    public function destroy(int $userId, Request $request): JsonResponse
    {
        $request->user()->blockedUsers()->detach($userId);

        return response()->json(['success' => true, 'message' => 'User unblocked.']);
    }
}