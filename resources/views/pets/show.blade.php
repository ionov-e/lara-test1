<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pet ') }}{{ $pet['id'] }}
        </h2>
    </x-slot>

    @isset($notification)
        @include('components.alert-notification')
    @endisset

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @include('pets.table')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
