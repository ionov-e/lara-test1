<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12 container">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="row">
                        <div class="col-md-4">
                            <a class="btn btn-info" href="{{ route('clients.create') }}">Create New Client</a>
                        </div>
                        <div class="col-md-4 offset-md-4">
                            <form class="row" action="/search" method="GET">
                                <div class="col-auto">
                                    <label class="visually-hidden" for="type_id"></label>
                                    <input class="form-control" id="type_id" type="text" name="query"
                                           placeholder="Client Search" required autofocus/>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-secondary" type="submit">Submit Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
                            @php($client['id'] = $client['id'] ?? $client['client_id'])
                            <tr>
                                <th scope="row">{{ $client['id'] }}</th>
                                <td>{{ $client['last_name'] }}</td>
                                <td>{{ $client['email'] }}</td>
                                <td>{{ $client['status'] }}</td>
                                <td><a class="btn btn-primary"
                                       href="{{ route('clients.show', $client['id']) }}">View</a></td>
                                <td><a class="btn btn-success"
                                       href="{{ route('clients.edit', $client['id']) }}">Edit</a></td>
                                <td>
                                    <form action="{{ route('clients.destroy' , $client['id'])}}" method="POST">
                                        {{ csrf_field() }}
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button type="submit" class="btn btn-danger">Delete</button>
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
