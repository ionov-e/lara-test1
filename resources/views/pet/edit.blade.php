<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
            </a>
        </x-slot>

        <h2 class="font-semibold text-xl text-center text-gray-800 leading-tight">
            {{ __('Edit Pet ') }}{{ $id }}
        </h2>

        <form method="POST" action="{{ route('pet.update', $id) }}">

            @csrf
            @method('PUT')

            <div>
                <x-input-label for="alias" :value="__('alias')" />

                <x-text-input id="alias" class="block mt-1 w-full" type="text" name="alias" :value="old('alias')" required autofocus />

                <x-input-error :messages="$errors->get('alias')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="type_id" :value="__('type_id')" />

                <x-text-input id="type_id" class="block mt-1 w-full" type="text" name="type_id" :value="old('type_id')" placeholder="6" required autofocus />

                <x-input-error :messages="$errors->get('type_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="breed_id" :value="__('breed_id')" />

                <x-text-input id="breed_id" class="block mt-1 w-full" type="text" name="breed_id" :value="old('breed_id')" placeholder="384" required autofocus />

                <x-input-error :messages="$errors->get('breed_id')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-4">
                    {{ __('Submit') }}
                </x-primary-button>
            </div>

        </form>
    </x-auth-card>
</x-guest-layout>
