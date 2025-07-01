<?php

namespace App\Http\Controllers\Socialite;

use App\Actions\Socialite\ProviderRedirectHandler;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProviderRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $provider, ProviderRedirectHandler $action): RedirectResponse
    {
        try {
            return $action->execute($provider);
        } catch (\Exception $e) {
            return back()
                ->withErrors(['email' => $e->getMessage()]);
        }
    }
}
