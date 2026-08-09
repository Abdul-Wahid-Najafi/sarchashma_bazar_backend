<?php

namespace App\Actions\Auth;

use App\Enums\UserStatus;
use App\Models\SocialAccount;
use App\Models\User;
use Google\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HandleGoogleLoginAction
{
    public function execute(string $idToken): User
    {
        $client = new  Client([
            'client_id' => config('services.google.client_id'),
        ]);

        $payload = $client->verifyIdToken($idToken);

        if (!$payload) {
            throw ValidationException::withMessages([
                'google' => [
                    'invalid google token',
                ],
            ]);
        }

        return DB::transaction(function () use ($payload) {

            $providerId = $payload['sub'];

            $email = $payload['email'] ?? null;

            $firstName = $payload['given_name'] ?? null;

            $lastName = $payload['family_name'] ?? "User";

            $picture = $payload['picture'] ?? null;
            $passwrod=$payload['given_name'].'12399324923';


            $socialAccount = SocialAccount::where(
                'provider',
                'google',
            )->where(
                'provider_id',
                $providerId,
            )->first();

            if ($socialAccount) {

                $user = $socialAccount->user;

                if ($user->status == UserStatus::BANNED->value) {

                    throw ValidationException::withMessages([
                        'google' => [
                            'your account banned',
                        ],
                    ]);
                }

                return $user;
            }

            $user = User::where(
                'email',
                $email,
            )->first();

            if (!$user) {

                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'profile_picture' => $picture,
                    'status' => UserStatus::ACTIVE->value,
                    'password'=>$passwrod
                ]);
            }

            SocialAccount::create([
                'user_id' => $user->id,
                'provider' => 'google',
                'provider_id' => $providerId,
            ]);

            return $user;
        });
    }
}