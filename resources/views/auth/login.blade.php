<x-guest-layout :$pageTitle>
    <div
        class="mt-6 bg-white border border-gray-200 rounded-xl shadow-2xs dark:bg-neutral-800 dark:border-neutral-700 mx-2 sm:mx-0 sm:w-md">
        <div class="p-4 sm:p-6">
            <div class="text-center">
                <h1 class="block text-2xl font-semibold text-gray-800 dark:text-white">{{ __('Sign in') }}</h1>
                {{-- <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
          {{ __('Don\'t have an account yet?') }}
          <a class="text-blue-600 decoration-2 hover:underline focus:outline-hidden focus:underline font-medium dark:text-blue-500"
            href="../examples/html/register.html">
            {{ __('Sign up here') }}
          </a>
        </p> --}}
            </div>

            <div class="mt-6">
                <a href="{{ route('auth.redirect', ['provider' => 'google']) }}">
                    <x-white-button type="button" class="w-full">
                        <x-icons.google class="w-4" />
                        {{ __('Sign in with Google') }}
                    </x-white-button>
                </a>

                <x-center-aligned-divider :value="__('Or')" />

                <x-alert variant="info"
                    class="mb-4">{{ __('We\'ve prefilled the demo credentials for you. Just hit "Sign in" and explore!') }}</x-alert>

                <form action="{{ route('login') }}" method="POST" class="grid gap-y-4">
                    @csrf

                    <div>
                        <x-input-label for="email" class="mb-2" :value="__('Email')" />
                        <x-text-input id="email" type="text" name="email" :value="old('email', config('demo.admin.email'))" required autofocus
                            autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        {{-- <div class="flex flex-wrap justify-between items-center gap-2"> --}}
                        <x-input-label for="password" class="mb-2" :value="__('Password')" />
                        {{-- <a class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-hidden focus:underline font-medium dark:text-blue-500"
                  href="../examples/html/recover-account.html">Forgot password?</a> --}}
                        {{-- </div> --}}
                        <x-text-input id="password" type="password" name="password" :value="old('password', config('demo.admin.password'))" required
                            autocomplete="password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center space-x-2">
                        <div class="flex">
                            <x-checkbox-input id="remember" name="remember" />
                        </div>
                        <x-input-label for="remember" :value="__('Remember me')" />
                    </div>

                    <x-solid-button>{{ __('Sign in') }}</x-solid-button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
