<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
            </a>
        </x-slot>

        <h2 class="font-semibold text-xl text-center text-gray-800 leading-tight">
            {{ __('Edit Client') }}
        </h2>

        <form method="POST" action="{{ route('clients.update', $id) }}">

            @csrf
            @method('PUT')

            <div>
                <x-input-label for="last_name" :value="__('last_name')"/>

                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name"
                              :value="old('last_name')" required autofocus/>

                <x-input-error :messages="$errors->get('last_name')" class="mt-2"/>
            </div>

            <div class="mt-4">
                <x-input-label for="email" :value="__('email')"/>

                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                              required/>

                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-4">
                    {{ __('Submit') }}
                </x-primary-button>
            </div>

        </form>
    </x-auth-card>
</x-guest-layout>
