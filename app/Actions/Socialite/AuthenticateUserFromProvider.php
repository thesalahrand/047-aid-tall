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

            if ($providerUser->getEmail() !== config('demo.admin.email')) {
                throw new \Exception(__('auth.failed'));
            }

            $user = User::updateOrCreate([
                'email' => $providerUser->getEmail(),
            ], [
                'name' => $providerUser->getName(),
                'provider_name' => $provider,
                'provider_id' => $providerUser->getId(),
                'provider_token' => $providerUser->token, // @phpstan-ignore-line
                'provider_refresh_token' => $providerUser->refreshToken, // @phpstan-ignore-line
            ]);

            if ($user->wasRecentlyCreated) {
                $user->email_verified_at = now();
                $user->save();
            }

            Auth::login($user);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage() === __('auth.failed') ? __('auth.failed') : __('Sign in with Google failed.'));
        }
    }
}
