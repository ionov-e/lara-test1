<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Client') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <a href="{{ route('pet.create', $client['id']) }}">Create Pet</a>
                    <br><br>
                    <table class="table table-striped">
                        <thead>
                        <tr style="text-align: left;">
                            <th scope="col">key</th>
                            <th scope="col">value</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($client as $key => $value)
                            <tr>
                                <td>{{ $key }}</td>
                                <td>{{ json_encode($value, JSON_UNESCAPED_UNICODE) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>Number of pets</td>
                            <td>{{ count($pets) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @php($petCount = 0)
    @foreach ($pets as $pet)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2>Pet {{ ++$petCount }}</h2>
                        <br><br>
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
                            <tr>
                                <td><br><br></td>
                            </tr>
                            <tr>
                                <td><a href="{{ route('pet.show', $pet['id']) }}">View</a></td>
                            </tr>
                            <tr>
                                <td><a href="{{ route('pet.edit', $pet['id']) }}">Edit</a></td>
                            </tr>
                            <tr>
                                <td>
                                    <form action="{{ route('pet.destroy' , $pet['id'])}}" method="POST">
                                        {{ csrf_field() }}
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button type="submit" class="btn btn-primary">Delete</button>
                                    </form>
                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</x-app-layout>
