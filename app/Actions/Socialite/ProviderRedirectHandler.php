<?php

namespace App\Actions\Socialite;

use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProviderRedirectHandler
{
    public function execute(string $provider): RedirectResponse
    {
        if ($provider !== 'google') {
            throw new \InvalidArgumentException(__('Unsupported provider.'));
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception) {
            throw new \Exception(__('Sign in with Google failed.'));
        }
    }
}
