<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table table-striped">
                        <thead>
                        <tr style="text-align: left;">
                            <th scope="col">key</th>
                            <th scope="col">value</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($pet as $key => $value)
                            <tr>
                                <td>{{ $key }}</td>
                                <td>{{ json_encode($value, JSON_UNESCAPED_UNICODE) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
