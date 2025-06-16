<?php

namespace App\Actions\Socialite;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthenticateUserFromProvider
{
    public function execute(string $provider): void
    {
        if ($provider !== 'google') {
            throw new \InvalidArgumentException(__('Unsupported provider.'));
        }

        try {
            $providerUser = Socialite::driver($provider)->user();

            $user = User::updateOrCreate([
                'provider_name' => $provider,
                'provider_id' => $providerUser->getId(),
            ], [
                'name' => $providerUser->getName(),
                'email' => $providerUser->getEmail(),
                'provider_token' => $providerUser->token, // @phpstan-ignore-line
                'provider_refresh_token' => $providerUser->refreshToken, // @phpstan-ignore-line
            ]);

            if ($user->wasRecentlyCreated) {
                $user->email_verified_at = now();
                $user->save();
            }

            Auth::login($user);
        } catch (\Exception) {
            throw new \Exception(__('Sign in with Google failed.'));
        }
    }
}
