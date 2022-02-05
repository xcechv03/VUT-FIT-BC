{{-- registe.blade.php --}}
{{----}}
{{-- autor: Tomáš Čechvala (xcechv03) --}}
{{----}}

<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <h2 class="font-bold text-4xl text-white">Register</h2>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')" />

                <x-input id="name" placeholder="Name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input placeholder="E-mail" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                placeholder="Password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4 pb-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                placeholder="Password again"
                                name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-between mt-4">
                <a class="underline text-sm text-gray-600 hover:text-black" href="/">
                    Go back
                </a>

                <a class="underline text-sm text-gray-600 hover:text-black" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-auth_button class="ml-4">
                    {{ __('Register') }}
                </x-auth_button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
