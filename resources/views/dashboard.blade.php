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
                    <a href="{{ route('clients.create') }}">Create</a>
                    <br>
                    <br>
                    <table class="table table-striped">
                        <thead>
                        <tr style="text-align: left;">
                            <th scope="col">#</th>
                            <th scope="col">last_name</th>
                            <th scope="col">email</th>
                            <th scope="col">status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <th scope="row">{{ $client['id'] }}</th>
                                <td>{{ $client['last_name'] }}</td>
                                <td>{{ $client['email'] }}</td>
                                <td>{{ $client['status'] }}</td>
                                <td><a href="{{ route('clients.show', $client['id']) }}">View</a></td>
                                <td><a href="{{ route('clients.edit', $client['id']) }}">Edit</a></td>
                                <td>
                                    <form action="{{ route('clients.destroy' , $client['id'])}}" method="POST">
                                        {{ csrf_field() }}
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button type="submit" class="btn btn-primary">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
