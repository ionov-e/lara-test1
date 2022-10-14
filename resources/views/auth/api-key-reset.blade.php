<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('api-key-reset') }}">
            @csrf

            <!-- Url -->
            <div class="mt-4">
                <x-input-label for="url" :value="__('Your URL')" />

                <x-text-input id="url" class="block mt-1 w-full" type="url" name="url" :value="old('url')" required  />

                <x-input-error :messages="$errors->get('url')" class="mt-2" />
                <x-input-error :messages="$errors->get('key')" class="mt-2" />
            </div>

            <!-- API Key -->
            <div class="mt-4">
                <x-input-label for="key" :value="__('API Key')" />

                <x-text-input id="key" class="block mt-1 w-full" type="text" name="key" minlength="4" :value="old('key')" required />

                <x-input-error :messages="$errors->get('key')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-3">
                    {{ __('Submit New Settings') }}
                </x-primary-button>
            </div>

        </form>
    </x-auth-card>
</x-guest-layout>
