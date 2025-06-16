<?php

namespace App\Http\Controllers\Socialite;

use App\Actions\Socialite\AuthenticateUserFromProvider;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ProviderCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $provider, AuthenticateUserFromProvider $action): RedirectResponse
    {
        try {
            $action->execute($provider);

            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\InvalidArgumentException|\Exception $e) {
            return to_route('login')
                ->withErrors(['email' => $e->getMessage()]);
        }
    }
}
