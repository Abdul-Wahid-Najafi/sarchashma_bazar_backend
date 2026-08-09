<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\SendOtpRequest;
use App\Http\Requests\Api\Auth\VerifyOtpRequest;
use App\Actions\Auth\SendOtpAction;
use App\Actions\Auth\VerifyOtpAction;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\Auth\UpdateProfileRequest;
use App\Actions\Auth\UpdateProfileAction;
use App\Http\Requests\Api\Auth\GoogleLoginRequest;
use App\Actions\Auth\HandleGoogleLoginAction;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Actions\Auth\LoginWithEmailAction;
use App\Actions\Auth\ResetPasswordAction;
use App\Http\Requests\Api\Auth\ResetPasswrodRequest;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function sendOtp(SendOtpRequest $request, SendOtpAction $action): JsonResponse
    {
        $code = $action->execute($request->validated('identifier'));

        return response()->json([
            'message' => 'Verfication code send to your email',
            'code'    => $code 
        ]);
    }

    public function verifyOtp(VerifyOtpRequest $request, VerifyOtpAction $action): JsonResponse
    {
        $user = $action->execute(
            $request->validated('identifier'),
            $request->validated('code')
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Code verfied',
            'token'   => $token,
            'user'    => new UserResource($user)
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request, UpdateProfileAction $action): JsonResponse
    {
        info('My Request Data:', $request->all());
        $user = $request->user();

        $updatedUser = $action->execute($user, $request->validated());

        return response()->json([
            'message' => 'Your profile updated successfully',
            'user'    => new UserResource($updatedUser)
        ]);
    }


    public function googleLogin(GoogleLoginRequest $request, HandleGoogleLoginAction $action): JsonResponse
    {
        info('My Request Data_gogole:', $request->all());
        $user = $action->execute(
                $request->validated()['id_token']
            );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login with google was successfully',
            'token'   => $token,
            'user'    => new UserResource($user)
        ]);
    }


    public function loginWithEmail(LoginRequest $request, LoginWithEmailAction $action): JsonResponse
    {
        $user = $action->execute(
            $request->validated('email'),
            $request->validated('password')
        );

        $user->load('shop');
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login was succesfully',
            'token'   => $token,
            'user'    => new UserResource($user)
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'logout successfully'
        ]);
    }


    public function resetPassword(ResetPasswrodRequest $request, ResetPasswordAction $action): JsonResponse
    {

        $user = $request->user();

        $action->execute($user, $request->validated('password'));
        $user->load('shop');
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Password reset successfully.',
            'token'   => $token,
           'user'    => new UserResource($user)
        ], 200);
    }

}