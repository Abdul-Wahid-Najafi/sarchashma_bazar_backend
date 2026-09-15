<?php

namespace App\Http\Controllers\Api;

use App\Actions\Profile\DeleteProfileAction;
use App\Actions\Profile\GetProfileAction;
use App\Actions\Profile\UpdateProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Api\UserResource;

class ProfileController extends Controller
{
    /**
     * Get authenticated user's profile.
     */
    public function show(
        Request $request,
        GetProfileAction $action
    ): JsonResponse {
        $user = $action->execute($request->user());

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    /**
     * Update authenticated user's profile.
     */
    public function update(
        UpdateProfileRequest $request,
        UpdateProfileAction $action
    ): JsonResponse {
        // info('My Request Data:', $request->all());
        $user = $action->execute(
            $request->user(),
            $request->validated()
        );

        info('My Updated-2 User:', $user->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'پروفایل با موفقیت بروزرسانی شد.',
            'user'    => new UserResource($user)
        ]);
    }

    /**
     * Delete authenticated user's account.
     */
    public function delete(
        Request $request,
        DeleteProfileAction $action
    ): JsonResponse {
        $action->execute($request->user());

        return response()->json([
            'status' => 'success',
            'message' => 'حساب کاربری شما برای همیشه حذف شد.',
        ]);
    }
}