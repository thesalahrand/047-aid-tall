<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Provider as SocialiteProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as OAuth2User;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_admin_can_authenticate_using_the_login_screen(): void
    {
        User::factory()->create([
            'email' => config('demo.admin.email'),
            'password' => Hash::make(config('demo.admin.password')),
        ]);

        $response = $this->post('/login', [
            'email' => config('demo.admin.email'),
            'password' => config('demo.admin.password'),
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_admin_can_not_authenticate_with_invalid_email(): void
    {
        User::factory()->create([
            'email' => config('demo.admin.email'),
            'password' => Hash::make(config('demo.admin.password')),
        ]);

        $this->post('/login', [
            'email' => 'wrong.email@example.com',
            'password' => config('demo.admin.password'),
        ]);

        $this->assertGuest();
    }

    public function test_admin_can_not_authenticate_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => config('demo.admin.email'),
            'password' => Hash::make(config('demo.admin.password')),
        ]);

        $this->post('/login', [
            'email' => config('demo.admin.email'),
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_redirects_to_google_oauth_url(): void
    {
        $response = $this->get(route('auth.redirect', 'google'));

        $response->assertStatus(302);

        $redirectUrl = $response->getTargetUrl();

        $parsedQuery = [];
        parse_str(parse_url($redirectUrl)['query'] ?? '', $parsedQuery);

        $this->assertStringStartsWith(
            'https://accounts.google.com/o/oauth2/auth',
            $redirectUrl
        );

        $requiredParams = [
            'client_id',
            'redirect_uri',
            'scope',
            'response_type',
            'state',
        ];

        foreach ($requiredParams as $param) {
            $this->assertArrayHasKey(
                $param,
                $parsedQuery,
                "Missing required OAuth parameter: {$param}"
            );
        }
    }

    public function test_non_existing_admin_can_authenticate_via_google_oauth(): void
    {
        $oauth2User = new OAuth2User;
        $oauth2User->id = fake()->uuid();
        $oauth2User->name = fake()->name();
        $oauth2User->email = config('demo.admin.email');
        $oauth2User->token = Str::random(60);
        $oauth2User->refreshToken = Str::random(60);

        $mockProvider = \Mockery::mock(SocialiteProvider::class);
        $mockProvider->shouldReceive('user')->andReturn($oauth2User);

        Socialite::shouldReceive('driver')->with('google')->andReturn($mockProvider);

        $response = $this->get(route('auth.callback', 'google'));
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();

        $this->assertEquals(1, User::count());

        $this->assertDatabaseHas(User::class, [
            'name' => $oauth2User->name,
            'email' => $oauth2User->email,
            'password' => null,
            'provider_name' => 'google',
            'provider_id' => $oauth2User->id,
            'provider_token' => $oauth2User->token,
            'provider_refresh_token' => $oauth2User->refreshToken,
        ]);
    }

    public function test_existing_admin_can_authenticate_via_google_oauth(): void
    {
        $oauth2User = new OAuth2User;
        $oauth2User->id = fake()->uuid();
        $oauth2User->name = fake()->name();
        $oauth2User->email = config('demo.admin.email');
        $oauth2User->token = Str::random(60);
        $oauth2User->refreshToken = Str::random(60);

        User::factory()->providerAuthenticated()->create([
            'email' => $oauth2User->email,
        ]);

        $mockProvider = \Mockery::mock(SocialiteProvider::class);
        $mockProvider->shouldReceive('user')->andReturn($oauth2User);

        Socialite::shouldReceive('driver')->with('google')->andReturn($mockProvider);

        $response = $this->get(route('auth.callback', 'google'));
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();

        $this->assertEquals(1, User::count());

        $this->assertDatabaseHas(User::class, [
            'name' => $oauth2User->name,
            'email' => $oauth2User->email,
            'provider_name' => 'google',
            'provider_id' => $oauth2User->id,
            'provider_token' => $oauth2User->token,
            'provider_refresh_token' => $oauth2User->refreshToken,
        ]);
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
