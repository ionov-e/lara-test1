<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table table-striped">
                        <thead>
                        <tr style="text-align: left;">
                            <th scope="col">#</th>
                            <th scope="col">last_name</th>
                            <th scope="col">Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <th scope="row">{{ $client['id'] }}</th>
                                <td>{{ $client['last_name'] }}</td>
                                <td>{{ $client['email'] }}</td>
                                <td><a href="">Просмотреть</a></td>
                                <td><a href="">Редактировать</a></td>
                                <td><a href="">Удалить</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
